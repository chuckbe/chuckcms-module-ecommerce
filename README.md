# ChuckCMS Module: Ecommerce

[![Latest Stable Version](https://poser.pugx.org/chuckbe/chuckcms-module-ecommerce/version.png)](https://packagist.org/packages/chuckbe/chuckcms-module-ecommerce) [![Total Downloads](https://poser.pugx.org/chuckbe/chuckcms-module-ecommerce/d/total.png)](https://packagist.org/packages/chuckbe/chuckcms-module-ecommerce)

### Extend ChuckCMS with ecommerce

Add this package as a module to your ChuckCMS installation to add ecommerce functionality. The package requires ChuckCMS and a functioning ecommerce template (look into chuckbe/chuckcms-template-london).

## How to install?

### Use Composer to install

```
composer require chuckbe/chuckcms-module-ecommerce
```

### Publish assets

```
php artisan vendor:publish --tag=chuckcms-module-ecommerce-public --force
```

### Publish config files

```
php artisan vendor:publish --tag=chuckcms-module-ecommerce-config --force
```

In the published file `config/chuckcms-module-ecommerce.php` you can change business details, language and currency settings among other general configuration.

### Add the module to ChuckCMS

Use the following command to publish the module to ChuckCMS. You can use the same command to update an existing installation but be aware that all your settings will be overriden.

```
php artisan chuckcms-module-ecommerce:install
```

> Installing the ecommerce module will conflict with your current users' role.
> Go to the DB and navigate to the 'model_has_roles' table.
> Change all the 'model_type' values into `Chuckbe\ChuckcmsModuleEcommerce\Models\User`

> For the authentication to be working you need to update the `config/chuckcms.php`.
> Change all the controllers' namespace from `Chuckbe\Chuckcms\...`
> into `Chuckbe\ChuckcmsModuleEcommerce\...`.

### Migrate tables

```
php artisan migrate
```

#### List

1. Add Mollie webhook URL to excepted on VerifyCsrfToken
2.






#### Interfaces

##### ChuckCart

This facade controls everything related to the cart functionality:
- Cart
- CartItems
- CartItemDiscount
- CartItemExtras
- CartItemOptions


###### Cart

A cart can contain items and discounts, these are stored in the session and can be stored in the database using the appropriate methods. 

*Methods*



###### CartItem

A cart item can have options, extras, and discounts besides the given attributes. 

*Attributes*
- ``` $cartItem->rowId ```
> Returns the unique identifier.

- ``` $cartItem->id ```
> Returns the given id (non-unique).

- ``` $cartItem->name ```
> Returns the given name.

- ``` $cartItem->qty ```
> Returns the quantity.

- ``` $cartItem->price ```
> Returns the price including or excluding tax (settings dependent) without discounts.

- ``` $cartItem->tax ```
> Returns the final tax for the whole item.

- ``` $cartItem->_discount ```
> Returns the discount value for the total price.

- ``` $cartItem->total ```
> Returns the subtotal * unit price.

- ``` $cartItem->options ```
> Returns the given options (selected attributes) of the item.

- ``` $cartItem->extras ```
> Returns the given extras of the item.

- ``` $cartItem->discounts ```
> Returns a collection of CartItemDiscount linked to the item.

- ``` $cartItem->model ```

- ``` $cartItem->taxRate ```
> Returns the given model of the item or null.

- ``` $cartItem->isSaved ```

Example of calculation:
```
        // NEW CART ITEM
        // TAXED            = TRUE
        // UNIT BASE        = 4.00          =           $ITEM->PRICE
        // TAXRATE          = 21            =           $ITEM->TAXRATE
        // UNIT EXTRAS      = 2.12          =           $ITEM->EXTRAS->TOTAL
        // UNIT RAW         = 6.120000      =           UNIT BASE + UNIT EXTRAS
        // UNIT             = 6.12          =           round(UNIT RAW)
        // QTY              = 6             =           $ITEM->QTY
        
        // TOTAL BASE       = 24.00         =           QTY * UNIT BASE
        // TOTAL EXTRAS     = 12.72         =           QTY * UNIT EXTRAS
        // TOTAL RAW        = 36.720000     =           QTY * UNIT RAW
        // TOTAL            = 36.72         =           QTY * UNIT
        
        // DISCOUNT BASE    = 2.400         =           calculateDiscount(TOTAL BASE)
        // DISCOUNT EXTRAS  = 1.272         =           calculateDiscount(TOTAL EXTRAS)
        // DISCOUNT RAW     = 3.672         =           calculateDiscount(TOTAL RAW)
        // DISCOUNT         = 3.67          =           round(calculateDiscount(TOTAL))
        
        // FINAL BASE       = 21.60         =           round(TOTAL BASE - DISCOUNT BASE)
        // FINAL EXTRAS     = 11.45         =           round(TOTAL EXTRAS - DISCOUNT EXTRAS)
        // FINAL RAW        = 33.048        =           TOTAL RAW - DISCOUNT RAW
        // FINAL            = 33.05         =           round(TOTAL RAW - DISCOUNT RAW)
        
        // TAX BASE         = 3.75          =           round( TAX BASE RAW )
        // TAX BASE RAW     = 3.74876033    =           ((TOTAL BASE - DISCOUNT BASE) / (100 + TAXRATE)) * TAXRATE
        // TAX EXTRAS       = 0.65          =           round( TAX EXTRAS RAW )
        // TAX EXTRAS RAW   = 0.65026415    =           ((TOTAL EXTRAS - DISCOUNT EXTRAS) / (100 + TAXRATE)) * TAXRATE
        // TAX RAW          = 4.40          =           TAX BASE RAW + TAX EXTRAS RAW
        // TAX              = 4.40          =           round(TAX RAW)
        
        // TAX RATES        = [21, 6]       =           taxRates()
        // TAX FOR RATE[21] = 3.75          =           taxForRate(21)
        // TAX FOR RATE[6]  = 0.65          =           taxForRate(6)
```
