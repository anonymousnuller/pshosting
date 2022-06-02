Reseller-Services PHP API Client
=======================
This **PHP 7.2+** library allows you to communicate with the Reseller-Services API.

[![Latest Stable Version](http://poser.pugx.org/bschleyer/reseller-services-phpclient/v)](https://packagist.org/packages/bschleyer/reseller-services-phpclient)
[![Total Downloads](http://poser.pugx.org/bschleyer/reseller-services-phpclient/downloads)](https://packagist.org/packages/bschleyer/reseller-services-phpclient)
[![Latest Unstable Version](http://poser.pugx.org/bschleyer/reseller-services-phpclient/v/unstable)](https://packagist.org/packages/bschleyer/reseller-services-phpclient)
[![License](http://poser.pugx.org/bschleyer/reseller-services-phpclient/license)](https://packagist.org/packages/bastianleicht/reseller-services-php)

> You can find the full API documentation [here](https://docs.reseller-services.de)!

## Getting Started

Recommended installation is using **Composer**!

In the root of your project execute the following:
```sh
$ composer require bschleyer/reseller-services-phpclient
```

Or add this to your `composer.json` file:
```json
{
    "require": {
        "bschleyer/reseller-services-phpclient": "^1.0"
    }
}
```

Then perform the installation:
```sh
$ composer install --no-dev
```

### Examples

Creating the ResellerServices main object:
```php
<?php
// Require the autoloader
require_once 'vendor/autoload.php';

// Use the library namespace
use ResellerServices\ResellerServices;

// Then simply pass your API-Token when creating the API client object.
$client = new ResellerServices('API-Token');

// Then you are able to perform a request
var_dump($client->domain()->getPrice('de'));
?>
```

If you want more info on how to use this PHP-API you should check out the [Wiki](https://github.com/bschleyer/reseller-services-phpclient/wiki).
