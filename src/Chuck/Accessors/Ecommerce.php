<?php

namespace Chuckbe\ChuckcmsModuleEcommerce\Chuck\Accessors;

use Chuckbe\Chuckcms\Chuck\ModuleRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\OrderRepository;
use Chuckbe\ChuckcmsModuleEcommerce\Chuck\CustomerRepository;
use Chuckbe\Chuckcms\Models\Module;
use Chuckbe\Chuckcms\Models\Template;
use ChuckProduct;
use Exception;
use ChuckCart;
use Illuminate\Support\Facades\Schema;

use App\Http\Requests;

class Ecommerce
{
    private $module;
    private $moduleRepository;
    private $orderRepository;
    private $customerRepository;
    private $moduleSettings;

    public function __construct(Module $module, ModuleRepository $moduleRepository, OrderRepository $orderRepository, CustomerRepository $customerRepository) 
    {
        $this->module = $module;
        $this->moduleRepository = $moduleRepository;
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->moduleSettings = $this->getModuleSettings($module);
    }

    /**
     * Return the module object
     *
     **/
    public function get()
    {
        return $this->module;
    }

    /**
     * Return the settings array
     *
     * @var Module $module
     **/
    private function getModuleSettings(Module $module)
    {
        return $module->settings ?? [];
    }

    public function getSetting($var)
    {
        $setting = $this->resolveSetting($var, $this->moduleSettings);
        return $setting !== 'undefined' ? $setting : null;
    }

    public function setSetting($var, $value)
    {
        $this->updateSetting($var, $this->moduleSettings, $value);    
    }

    private function resolveSetting($var, $settings)
    {
        $split = explode('.', $var);
        foreach ($split as $value) {
            if (array_key_exists($value, $settings)) {
                $settings = $settings[$value];
            } else {
                return 'undefined';
            }
        }

        return $settings;
    }

    private function updateSetting($var, $settings_object, $input)
    {
        $split = explode('.', $var);
        $settings = $settings_object;
        $level = count($split);

        if($level == 1) {
            $settings[$split[0]] = $input;
        } elseif($level == 2) {
            $settings[$split[0]][$split[1]] = $input;
        } elseif($level == 3) {
            $settings[$split[0]][$split[1]][$split[2]] = $input;
        } elseif($level == 4) {
            $settings[$split[0]][$split[1]][$split[2]][$split[3]] = $input;
        } elseif($level == 5) {
            $settings[$split[0]][$split[1]][$split[2]][$split[3]][$split[4]] = $input;
        }

        $json = $this->module->json;
        $json['settings'] = $settings;
        $this->module->json = $json;
        $this->module->update();
    }

    public function getDefaultShippingPrice($price = null)
    {
        foreach ($this->moduleSettings['shipping']['carriers'] as $carrier) {
            if($carrier['default']) {
                $price = (float)$carrier['cost']; 
                break;
            }
        }
        return $price;
    }

    public function getDefaultShippingPriceForCart($instance)
    {
        foreach($this->getCarriersForCart($instance) as $carrierKey => $carrier) {
            if($carrier['default']) {
                $price = (float)$this->getCarrierTotalForCart($carrierKey, $instance); 
                break;
            } else {
                $price = (float)$this->getCarrierTotalForCart($carrierKey, $instance); 
                break;
            }
        }
        return $price;
    }

    public function getCartTotalWeight(string $identifier)
    {
        $totalWeight = 0;
        foreach(ChuckCart::instance($identifier)->content() as $rowKey => $item) {
            $totalWeight += ($item->qty * ChuckProduct::weightBySKU($item->id));
        }

        return $totalWeight;
    }

    public function getCartFinal(string $identifier)
    {
        return ChuckCart::instance($identifier)->final();
    }

    public function getCarrier($key)
    {
        return array_key_exists($key, $this->moduleSettings['shipping']['carriers']) ? $this->moduleSettings['shipping']['carriers'][$key] : [];
    }

    public function getCarrierSubtotal($key)
    {
        return array_key_exists($key, $this->moduleSettings['shipping']['carriers']) ? $this->priceWithoutTax((float)$this->moduleSettings['shipping']['carriers'][$key]['cost'], 21) : 0.00;
    }

    public function getCarrierSubtotalForCart($key, $instance, $vat = null)
    {
        return array_key_exists($key, $this->moduleSettings['shipping']['carriers']) ? $this->priceWithoutTax((float)$this->getCarrierTotalForCart($key, $instance), 21) : 0.00;
    }

    public function getCarrierTax($key)
    {
        return array_key_exists($key, $this->moduleSettings['shipping']['carriers']) ? $this->taxFromPrice((float)$this->moduleSettings['shipping']['carriers'][$key]['cost'], 21) : 0.00;
    }

    public function getCarrierTaxRate($key)
    {
        return array_key_exists($key, $this->moduleSettings['shipping']['carriers']) ? 
                    (array_key_exists('tax_rate', $this->moduleSettings['shipping']['carriers']) ? (int)$this->moduleSettings['shipping']['carriers'][$key]['tax_rate'] : 21) : 21;
    }

    public function getCarrierTaxRateForCart($key, $instance)
    {
        return array_key_exists($key, $this->moduleSettings['shipping']['carriers']) ? 
                    (array_key_exists('tax_rate', $this->moduleSettings['shipping']['carriers']) ? (int)$this->moduleSettings['shipping']['carriers'][$key]['tax_rate'] : 21) : 21;
    }

    public function getCarrierTaxForCart($key, $instance, $vat = null)
    {
        return array_key_exists($key, $this->moduleSettings['shipping']['carriers']) ? $this->taxFromPrice((float)$this->getCarrierTotalForCart($key, $instance), 21) : 0.00;
    }

    public function getCarrierTotal($key)
    {
        return array_key_exists($key, $this->moduleSettings['shipping']['carriers']) ? (float)$this->moduleSettings['shipping']['carriers'][$key]['cost'] : 0.00;
    }

    public function getCarrierTotalForCart($key, $instance)
    {
        if( array_key_exists($key, $this->moduleSettings['shipping']['carriers']) ) {
            $carrier = $this->moduleSettings['shipping']['carriers'][$key];

            if( !array_key_exists('free_from', $carrier) ) {
                return $carrier['cost'];
            }

            if( is_null($carrier['free_from']) ) {
                return $carrier['cost'];
            }

            if( (float)ChuckCart::instance($instance)->total() <= (float)$carrier['free_from'] ) {
                return $carrier['cost'];
            }
        } 

        return 0.00;              
    }

    public function getCarriers()
    {
        return $this->moduleSettings['shipping']['carriers'];
    }

    public function getCarriersForCart($instance)
    {
        $carriers = $this->moduleSettings['shipping']['carriers'];
        $object = [];

        foreach($carriers as $key => $carrier) {
            if($this->getCartFinal($instance) <= (!array_key_exists('min_cart', $carrier) ? 0.000 : (float)$carrier['min_cart'])) {
                continue;
            }
            
            if(array_key_exists('max_cart', $carrier) && (float)$carrier['max_cart'] > 0) {
                if($this->getCartFinal($instance) > (float)$carrier['max_cart']) {
                    continue;
                }
            }
            
            if(array_key_exists('min_weight', $carrier) && (float)$carrier['min_weight'] > 0) {
                if($this->getCartTotalWeight($instance) < (!array_key_exists('min_weight', $carrier) ? 0.000 : (float)$carrier['min_weight'])) {
                    continue;
                }
            }
            
            if(array_key_exists('max_weight', $carrier) && (float)$carrier['max_weight'] > 0) {
                if($this->getCartTotalWeight($instance) > (!array_key_exists('max_weight', $carrier) ? 0.000 : (float)$carrier['max_weight'])) {
                    continue;
                }
            }            

            $object[$key] = $carrier;
        }
        
        return $object;
    }

    public function isDefaultCarrier($key) :bool
    {
        if (!array_key_exists($key, $this->moduleSettings['shipping']['carriers'])) {
            return false;
        }

        return $this->moduleSettings['shipping']['carriers'][$key]['default'];
    }

    public function getSupportedCountries()
    {
        return $this->moduleSettings['order']['countries'];
    }

    public function getSupportedCurrencies()
    {
        return $this->moduleSettings['general']['supported_currencies'];
    }

    public function getFeaturedCurrency()
    {
        return $this->moduleSettings['general']['featured_currency'];
    }

    public function getTemplate()
    {
        return Template::where('slug', $this->moduleSettings['layout']['template'])->first();
    }

    public function formatPrice($var)
    {
        return '€ ' . number_format((float)$var, (int)$this->getSetting('general.decimals'), $this->getSetting('general.decimals_separator'), $this->getSetting('general.thousands_separator'));
    }

    public function priceWithoutTax($price, $tax)
    {
        $newprice = $price / ((100 + $tax) / 100);
        return round($newprice, 2);
    }

    public function taxFromPrice($price, $tax)
    {
        $newprice = ($price / (100 + $tax)) * $tax;
        return round($newprice, 2);
    }

    public function followupContent(string $order_number)
    {
        return $this->orderRepository->followup($order_number);
    }

    public function followupScripts(string $order_number)
    {
        return $this->orderRepository->followupScripts($order_number);
    }

    public function defaultVatRate()
    {
        foreach(config('chuckcms-module-ecommerce.vat') as $key => $vat) {
            if($vat['default']) {
                return $vat['amount'];
            }
        }

        return 0;
    }

    public function getDefaultGroup()
    {
        return $this->customerRepository->defaultGroup();
    }

    public function allGroups()
    {
        return $this->customerRepository->allGroups();
    }

    public function totalSales()
    {
        return $this->orderRepository->totalSales();
    }

    public function totalSalesLast7Days()
    {
        return $this->orderRepository->totalSalesLast7Days();
    }

    public function totalSalesLast7DaysQty()
    {
        return $this->orderRepository->totalSalesLast7DaysQty();
    }

}