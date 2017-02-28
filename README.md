# Omnipay: GoCardlessV2

**Go Cardless driver for the Omnipay PHP payment processing library using the GoCardless v2 API**

[![Build Status](https://travis-ci.org/digitickets/omnipay-gocardlessv2.png?branch=master)](https://travis-ci.org/digitickets/omnipay-gocardlessv2)
[![Latest Stable Version](https://poser.pugx.org/digitickets/omnipay-gocardlessv2/version.png)](https://packagist.org/packages/digitickets/omnipay-gocardlessv2)
[![Total Downloads](https://poser.pugx.org/digitickets/omnipay-gocardlessv2/d/total.png)](https://packagist.org/packages/digitickets/omnipay-gocardlessv2)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.5+. This package implements GoCardless support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```
composer require digitickets/gocardlessv2:"^0"
```

## Basic Usage

The following gateways are provided by this package:

* GoCardless Pro
* GoCardless Redirect Flow


This is still in Development - DO NOT USE IN LIVE APPLICATIONS 

You need to set your `access_token`. Setting `testMode` to true will use the `sandbox` environment.

This gateway supports single payments or scheduled subscriptions via bank mandate only. For more details about what this gateway supports please consult [the documentation](https://developer.gocardless.com/api-reference/)

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Driver specific usage

This driver supports multiple methods of implementation available via GoCardless. Please consult their documentation to confirm which methods are correct for your situation. Not all methods are applicable to every route.
This driver does not provide access to the list methods - data may only be retrieved by primary key.

### Create customer

```php
$customer = $gateway->createCustomer([
    'customerData' => array(
                'given_name' => 'Mike',
                'family_name' => 'Jones',
                'email' => 'mike.jones@example.com',
                'country_code' => 'GB'
            ),
])->send();
```
You can find full list of options [here](https://developer.gocardless.com/api-reference/#customers-create-a-customer).

###Find customer (By id)

```php
$customer = $gateway->findCustomer(1)->send();
```
You can find full list of options [here](https://developers.braintreepayments.com/reference/request/customer/find/php)


###Parse webhook notification

```php
$notification = $gateway->parseNotification(
                                getallheaders(),
                                file_get_contents('php://input'), 
                                'MySecurityToken'
                            )
                        ->send();
```
This will fetch the event associated with the web hook.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/digitickets/omnipay-gocardlessv2e/issues),
or better yet, fork the library and submit a pull request.
