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
