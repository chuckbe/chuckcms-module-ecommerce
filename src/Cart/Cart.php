<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Cart;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Events\Dispatcher;
use Chuckbe\ChuckcmsModuleEcommerce\Cart\Contracts\Buyable;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\DiscountRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Cart\Exceptions\UnknownModelException;
use Chuckbe\ChuckcmsModuleEcommerce\Cart\Exceptions\InvalidRowIDException;
use Chuckbe\ChuckcmsModuleEcommerce\Cart\Exceptions\CartAlreadyStoredException;

class Cart
{
    const DEFAULT_INSTANCE = 'default';

    /**
     * Instance of the session manager.
     *
     * @var \Illuminate\Session\SessionManager
     */
    protected $session;

    /**
     * Instance of the discount repository.
     *
     * @var \Chuckbe\ChuckcmsModuleEcommerce\Chuck\DiscountRepository
     */
    protected $discountRepository;

    /**
     * Instance of the event dispatcher.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    private $events;

    /**
     * Holds the current cart instance.
     *
     * @var string
     */
    private $instance;

    /**
     * Cart constructor.
     *
     * @param \Illuminate\Session\SessionManager                        $session
     * @param \Illuminate\Contracts\Events\Dispatcher                   $events
     * @param \Chuckbe\ChuckcmsModuleEcommerce\Chuck\DiscountRepository $discountRepository
     */
    public function __construct(SessionManager $session, Dispatcher $events, DiscountRepository $discountRepository)
    {
        $this->session = $session;
        $this->discountRepository = $discountRepository;
        $this->events = $events;

        $this->instance(self::DEFAULT_INSTANCE);
    }

    /**
     * Set the current cart instance.
     *
     * @param string|null $instance
     * @return \Gloudemans\Shoppingcart\Cart
     */
    public function instance($instance = null)
    {
        $instance = $instance ?: self::DEFAULT_INSTANCE;

        $this->instance = sprintf('%s.%s', 'cart', $instance);

        return $this;
    }

    /**
     * Get the current cart instance.
     *
     * @return string
     */
    public function currentInstance()
    {
        return str_replace('cart.', '', $this->instance);
    }

    /**
     * Add an item to the cart.
     *
     * @param mixed     $id
     * @param mixed     $name
     * @param int|float $qty
     * @param float     $price
     * @param array     $options
     * @param array     $extras
     * @param float     $taxrate
     * @param bool      $taxed
     * @return \Gloudemans\Shoppingcart\CartItem
     */
    public function add($id, $name = null, $qty = null, $price = null, array $options = [], array $extras = [], $taxrate = null, $taxed = false)
    {
        if ($this->isMulti($id)) {
            return array_map(function ($item) {
                return $this->add($item);
            }, $id);
        }

        if ($id instanceof CartItem) {
            $cartItem = $id;
        } else {
            $cartItem = $this->createCartItem($id, $name, $qty, $price, $options, $extras, $taxrate, $taxed);
        }

        $content = $this->getContent();

        if ($content->has($cartItem->rowId)) {
            $cartItem->qty += $content->get($cartItem->rowId)->qty;
        }

        $content->put($cartItem->rowId, $cartItem);

        $this->session->put($this->instance, $content);

        $this->resetDiscounts();

        $cartItem = $this->get($cartItem->rowId);

        $this->events->dispatch('cart.added', $cartItem);

        return $cartItem;
    }

    /**
     * Update the cart item with the given rowId.
     *
     * @param string $rowId
     * @param mixed  $value
     * @return \Gloudemans\Shoppingcart\CartItem
     */
    public function update($rowId, $value)
    {
        $cartItem = $this->get($rowId);

        if ($value instanceof Buyable) {
            $cartItem->updateFromBuyable($value);
        } elseif (is_array($value)) {
            $cartItem->updateFromArray($value);
        } else {
            $cartItem->qty = $value;
        }

        $content = $this->getContent();

        if ($rowId !== $cartItem->rowId) {
            $content->pull($rowId);

            if ($content->has($cartItem->rowId)) {
                $existingCartItem = $this->get($cartItem->rowId);
                $cartItem->setQuantity($existingCartItem->qty + $cartItem->qty);
            }
        }

        if ($cartItem->qty <= 0) {
            $this->remove($cartItem->rowId);
            return;
        } else {
            $content->put($cartItem->rowId, $cartItem);
        }

        $this->session->put($this->instance, $content);

        $this->resetDiscounts();

        $cartItem = $this->get($cartItem->rowId);

        $this->events->dispatch('cart.updated', $cartItem);

        return $cartItem;
    }

    /**
     * Remove the cart item with the given rowId from the cart.
     *
     * @param string $rowId
     * @return void
     */
    public function remove($rowId)
    {
        $cartItem = $this->get($rowId);

        $content = $this->getContent();

        $content->pull($cartItem->rowId);

        $this->events->dispatch('cart.removed', $cartItem);

        $this->session->put($this->instance, $content);

        $this->resetDiscounts();
    }

    /**
     * Get a cart item from the cart by its rowId.
     *
     * @param string $rowId
     * @return \Gloudemans\Shoppingcart\CartItem
     */
    public function get($rowId)
    {
        $content = $this->getContent();

        if ( ! $content->has($rowId))
            throw new InvalidRowIDException("The cart does not contain rowId {$rowId}.");

        return $content->get($rowId);
    }

    /**
     * Destroy the current cart instance.
     *
     * @return void
     */
    public function destroy()
    {
        $this->session->remove($this->instance);
        $this->session->remove($this->instance.'_discounts');
    }

    /**
     * Get the content of the cart.
     *
     * @return \Illuminate\Support\Collection
     */
    public function content()
    {
        //dd($this->session->get($this->instance));
        if (is_null($this->session->get($this->instance))) {
            return new Collection;
        }

        return $this->session->get($this->instance);
    }

    /**
     * Get the discounts of the cart.
     *
     * @return \Illuminate\Support\Collection
     */
    public function discounts()
    {
        if (is_null($this->session->get($this->instance.'_discounts'))) {
            return new Collection;
        }

        return $this->session->get($this->instance.'_discounts');
    }

    /**
     * Get the number of items in the cart.
     *
     * @return int|float
     */
    public function count()
    {
        $content = $this->getContent();

        return $content->sum('qty');
    }

    /**
     * Get the subtotal (total - tax) of the items in the cart.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     * @return float
     */
    public function subtotal($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        $content = $this->getContent();

        $subTotal = $content->reduce(function ($subTotal, CartItem $cartItem) {
            return $subTotal + $cartItem->_total;
        }, 0);

        return $this->numberFormat($subTotal, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Get the total price of the items in the cart.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     * @return string
     */
    public function total($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        $content = $this->getContent();

        $total = $content->reduce(function ($total, CartItem $cartItem) {
            return $total + $cartItem->_total;
        }, 0);

        return $this->numberFormat($total, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Get the final price of the items in the cart.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     * @return string
     */
    public function final($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        $content = $this->getContent();

        $final = $content->reduce(function ($final, CartItem $cartItem) {
            return $final + $cartItem->_final;
        }, 0);

        return $this->numberFormat($final, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Get the total tax of the items in the cart.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     * @return float
     */
    public function tax($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        $content = $this->getContent();

        $tax = $content->reduce(function ($tax, CartItem $cartItem) {
            return $tax + ($cartItem->_tax);
        }, 0);

        return $this->numberFormat($tax, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Get the total discount of the items in the cart.
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     * @return float
     */
    public function discount($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        $content = $this->getContent();

        $discount = $content->reduce(function ($discount, CartItem $cartItem) {
            return $discount + $cartItem->_discount;
        }, 0);

        return $this->numberFormat($discount, $decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Get the total discount of the items in the cart. > PHASE THIS OUT
     *
     * @param int    $decimals
     * @param string $decimalPoint
     * @param string $thousandSeperator
     * @return float
     */
    public function globalDiscount($decimals = null, $decimalPoint = null, $thousandSeperator = null)
    {
        return $this->discount($decimals, $decimalPoint, $thousandSeperator);
    }

    /**
     * Search the cart content for a cart item matching the given search closure.
     *
     * @param \Closure $search
     * @return \Illuminate\Support\Collection
     */
    public function search(Closure $search)
    {
        $content = $this->getContent();

        return $content->filter($search);
    }

    /**
     * Associate the cart item with the given rowId with the given model.
     *
     * @param string $rowId
     * @param mixed  $model
     * @return void
     */
    public function associate($rowId, $model)
    {
        if(is_string($model) && ! class_exists($model)) {
            throw new UnknownModelException("The supplied model {$model} does not exist.");
        }

        $cartItem = $this->get($rowId);

        $cartItem->associate($model);

        $content = $this->getContent();

        $content->put($cartItem->rowId, $cartItem);

        $this->session->put($this->instance, $content);
    }

    /**
     * Set the tax rate for the cart item with the given rowId.
     *
     * @param string    $rowId
     * @param int|float $taxRate
     * @return void
     */
    public function setTax($rowId, $taxRate)
    {
        $cartItem = $this->get($rowId);

        $cartItem->setTaxRate($taxRate);

        $content = $this->getContent();

        $content->put($cartItem->rowId, $cartItem);

        $this->session->put($this->instance, $content);
    }

    /**
     * Set a global discount for the cart.
     *
     * @param string $discountDescription
     * @param string $discountType
     * @param int|float $discountValue
     * @param int $priority
     * @return void
     */
    public function addDiscount(string $discountCode, $discountType = 'percentage', $discountValue, $priority)
    {
        $discounts = $this->getDiscounts();

        $discount = $this->discountRepository->code($discountCode);

        $discounts->put($discountCode, $discount->json);

        $this->session->put($this->instance.'_discounts', $discounts);

        $this->resetDiscounts();
    }

    /**
     * Remove a discount for the cart.
     *
     * @param string $discountCode
     * @return void
     */
    public function removeDiscount(string $discountCode)
    {
        $discounts = $this->getDiscounts();

        $discounts->pull($discountCode);

        $this->session->put($this->instance.'_discounts', $discounts);

        $this->resetDiscounts();
    }

    /**
     * Validate a discount for the cart and user.
     *
     * @param string $discountCode
     * @param string|null $user
     * @return bool
     */
    public function validateDiscount(string $discountCode, $user)
    {
        $discount = $this->discountRepository->code($discountCode);

        if(is_null($discount)) {
            return false;
        }

        if(! $this->discountRepository->checkValidity($discount)) {
            return false;
        }

        if(! $this->discountRepository->checkMinima($discount, $this)) {
            return false;
        }

        if(! $this->discountRepository->checkAvailability($discount)) {
            return false;
        }

        if($user) {
            if(! $this->discountRepository->checkAvailabilityForCustomer($discount, $user->id)) {
                return false;
            }
        }

        if(! $this->discountRepository->checkAvailabilityForCustomerGroup($discount, $user)) {
            return false;
        }

        if(! $this->discountRepository->checkConditions($discount, $this)) {
            return false;
        }

        return true;
    }

    /**
     * Validate a discount for an item in the cart.
     *
     * @param string $rowId
     * @param string $itemId
     * @param string $discountCode
     * @return bool
     */
    protected function validateItemDiscount(
        string $rowId, 
        string $itemId, 
        string $discountCode
    )
    {
        $discount = $this->discountRepository->code($discountCode);

        if(is_null($discount)) {
            return false;
        }

        if(! $this->discountRepository->checkConditions($discount, $this)) {
            return false;
        }

        if (! $this->discountRepository->isApplicableForCartItem($discount, $this, $itemId)) {
            return false;
        }

        return true;
    }

    /**
     * Reset all discounts on all items.
     *
     * @return void
     */
    protected function resetDiscounts()
    {
        $discounts = $this->getDiscounts()->toArray();

        $content = $this->getContent();

        //remove gifts from content
        //re-add all gifts from discounts to the content

        foreach ($content as $cartItem) {
            
            $this->removeItemDiscount($cartItem->rowId);
            
            foreach($discounts as $discountKey => $discount) { //sort by priority 
                $remainder = 0;
                $discountValue = $discount['value'] + 0;

                if(!$this->validateItemDiscount($cartItem->rowId, $cartItem->id, $discount['code'])) {
                    continue;
                }

                if ($discount['type'] == 'currency' && $discountValue > $cartItem->final) {
                    $remainder = $discountValue - $cartItem->final;
                    $discountValue = $cartItem->final;

                    $this->setItemDiscount(
                        $cartItem->rowId, 
                        $discount['code'], 
                        $discount['type'], 
                        $discountValue
                    );

                    $discounts[$discountKey]['value'] = $remainder;

                    continue;
                }

                if ($discount['type'] == 'currency' && $discountValue <= $cartItem->final) {

                    $this->setItemDiscount(
                        $cartItem->rowId, 
                        $discount['code'], 
                        $discount['type'], 
                        $discountValue
                    );

                    unset($discounts[$discountKey]);

                    continue;
                }

                $this->setItemDiscount(
                    $cartItem->rowId, 
                    $discount['code'], 
                    $discount['type'], 
                    $discountValue);
            }

            $discounts = $discounts;
        }
    }

    /**
     * Set the discount for the cart item with the given rowId.
     *
     * @param string    $rowId
     * @param int|float $discountValue
     * @param string $discountType
     * @param string $discountDescription
     * @return void
     */
    protected function setItemDiscount($rowId, $discountCode, $discountType = 'percentage', $discountValue, $discountDescription = '')
    {
        $cartItem = $this->get($rowId);

        $cartItem->setDiscount([$discountCode, $discountType, $discountValue, $discountDescription]);

        $content = $this->getContent();

        $content->put($cartItem->rowId, $cartItem);

        $this->session->put($this->instance, $content);
    }

    /**
     * Remove the discount for the cart item with the given rowId.
     *
     * @param string    $rowId
     * @param int|float $discountValue
     * @param string $discountType
     * @param string $discountDescription
     * @return void
     */
    protected function removeItemDiscount($rowId)
    {
        $cartItem = $this->get($rowId);

        $cartItem->removeDiscount();

        $content = $this->getContent();

        $content->put($cartItem->rowId, $cartItem);

        $this->session->put($this->instance, $content);
    }

    /**
     * Store an the current instance of the cart.
     *
     * @param mixed $identifier
     * @return void
     */
    public function store($identifier)
    {
        $content = $this->getContent();
        $discounts = $this->getDiscounts();


        $this->getConnection()
             ->table($this->getTableName())
             ->where('identifier', $identifier)
             ->where('instance', $this->currentInstance())
             ->delete();


        $this->getConnection()->table($this->getTableName())->insert([
            'identifier' => $identifier,
            'instance' => $this->currentInstance(),
            'content' => serialize($content),
            'discounts' => serialize($discounts),
            'created_at'=> new \DateTime()
        ]);

        $this->events->dispatch('cart.stored');
    }

    /**
     * Restore the cart with the given identifier.
     *
     * @param mixed $identifier
     * @return void
     */
    public function restore($identifier)
    {
        if( ! $this->storedCartWithIdentifierExists($identifier)) {
            return;
        }

        $stored = $this->getConnection()->table($this->getTableName())
            ->where('instance', $this->currentInstance())
            ->where('identifier', $identifier)->first();

        $storedContent = unserialize($stored->content);
        $storedDiscounts = unserialize($stored->discounts);

        $currentInstance = $this->currentInstance();

        $this->instance($stored->instance);

        $content = $this->getContent();

        foreach ($storedContent as $cartItem) {
            $content->put($cartItem->rowId, $cartItem);
        }

        $discounts = $this->getDiscounts();

        foreach ($storedDiscounts as $discount) {
            $discounts->put($discount['code'], $discount);
        }

        $this->events->dispatch('cart.restored');

        $this->session->put($this->instance, $content);
        $this->session->put($this->instance.'_discounts', $discounts);

        return $this->instance($currentInstance);
    }



    /**
     * Deletes the stored cart with given identifier
     *
     * @param mixed $identifier
     */
    protected function deleteStoredCart($identifier) {
        $this->getConnection()
             ->table($this->getTableName())
             ->where('identifier', $identifier)
             ->delete();
    }



    /**
     * Magic method to make accessing the total, tax and subtotal properties possible.
     *
     * @param string $attribute
     * @return float|null
     */
    public function __get($attribute)
    {
        if($attribute === 'subtotal') { //@todo: retire this
            return $this->subtotal();
        }
        
        if($attribute === 'total') {
            return $this->total();
        }

        if($attribute === 'final') {
            return $this->final();
        }

        if($attribute === 'tax') {
            return $this->tax();
        }

        if($attribute === 'discount') {
            return $this->discount();
        }

        if($attribute === 'isTaxed') { 
            return $this->isTaxed();
        }

        return null;
    }

    /**
     * Get the carts content, if there is no cart content set yet, return a new empty Collection
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getContent()
    {
        $content = $this->session->has($this->instance)
            ? $this->session->get($this->instance)
            : new Collection;

        return $content;
    }

    /**
     * Get the carts discounts, if there are no cart discounts set yet, return a new empty Collection
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getDiscounts()
    {
        $discounts = $this->session->has($this->instance.'_discounts')
            ? $this->session->get($this->instance.'_discounts')
            : new Collection;

        return $discounts;
    }

    /**
     * Create a new CartItem from the supplied attributes.
     *
     * @param mixed     $id
     * @param mixed     $name
     * @param int|float $qty
     * @param float     $price
     * @param array     $options
     * @param array     $extras
     * @param float     $taxrate
     * @param bool      $taxed
     * @return \Gloudemans\Shoppingcart\CartItem
     */
    private function createCartItem($id, $name, $qty, $price, array $options, array $extras, $taxrate, bool $taxed)
    {
        if ($id instanceof Buyable) {
            $cartItem = CartItem::fromBuyable($id, $qty ?: []);
            $cartItem->setQuantity($name ?: 1);
            $cartItem->associate($id);
        } elseif (is_array($id)) {
            $cartItem = CartItem::fromArray($id);
            $cartItem->setQuantity($id['qty']);
        } else {
            $cartItem = CartItem::fromAttributes($id, $name, $price, $options, $extras, $taxed);
            $cartItem->setQuantity($qty);
        }

        if(isset($taxrate) && is_numeric($taxrate)) {
            $cartItem->setTaxRate($taxrate);
        } else {
            $cartItem->setTaxRate(config('cart.tax'));
        }

        return $cartItem;
    }

    /**
     * Check if the item is a multidimensional array or an array of Buyables.
     *
     * @param mixed $item
     * @return bool
     */
    private function isMulti($item)
    {
        if ( ! is_array($item)) return false;

        return is_array(head($item)) || head($item) instanceof Buyable;
    }

    /**
     * See if the cart is doing price calculation using tax included or excluded.
     *
     * @return bool
     */
    public function isTaxed()
    {
        $content = $this->getContent();

        foreach($content as $cartItem) {
            if($cartItem->taxed) {
                return true;
            }
        }

        return false;
    }

     

    /**
     * Get the total discount of the items in the cart.
     *
     * @return bool
     */
    public function hasDiscount()
    {
        $discounts = $this->getDiscounts();

        if(count($discounts) > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param $identifier
     * @return bool
     */
    protected function storedCartWithIdentifierExists($identifier)
    {
        return $this->getConnection()->table($this->getTableName())->where('identifier', $identifier)->where('instance', $this->currentInstance())->exists();
    }

    /**
     * Get the database connection.
     *
     * @return \Illuminate\Database\Connection
     */
    protected function getConnection()
    {
        $connectionName = $this->getConnectionName();

        return app(DatabaseManager::class)->connection($connectionName);
    }

    /**
     * Get the database table name.
     *
     * @return string
     */
    protected function getTableName()
    {
        return config('cart.database.table', 'shoppingcart');
    }

    /**
     * Get the database connection name.
     *
     * @return string
     */
    private function getConnectionName()
    {
        $connection = config('cart.database.connection');

        return is_null($connection) ? config('database.default') : $connection;
    }

    public function calculateDiscount($price, $type, $value)
    {
        switch ($type) {
            case 'currency':
                return ($value > $price) ? 0 : $value;
                break;
            case 'percentage':
                return ($price * ($value / 100));
                break;
        }
    }

    /**
     * Get the Formated number
     *
     * @param $value
     * @param $decimals
     * @param $decimalPoint
     * @param $thousandSeperator
     * @return string
     */
    private function numberFormat($value, $decimals, $decimalPoint, $thousandSeperator)
    {
        if(is_null($decimals)){
            $decimals = is_null(config('cart.format.decimals')) ? 2 : config('cart.format.decimals');
        }
        if(is_null($decimalPoint)){
            $decimalPoint = is_null(config('cart.format.decimal_point')) ? '.' : config('cart.format.decimal_point');
        }
        if(is_null($thousandSeperator)){
            $thousandSeperator = is_null(config('cart.format.thousand_seperator')) ? ',' : config('cart.format.thousand_seperator');
        }

        return number_format($value, $decimals, $decimalPoint, $thousandSeperator);
    }
}
