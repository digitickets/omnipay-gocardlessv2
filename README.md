# Omnipay: GoCardlessV2

**Go Cardless driver for the Omnipay PHP payment processing library using the GoCardless v2 API**

[![Build Status](https://travis-ci.org/digitickets/omnipay-gocardlessv2.png?branch=master)](https://travis-ci.org/digitickets/omnipay-gocardlessv2)
[![Coverage Status](https://coveralls.io/repos/github/digitickets/omnipay-gocardlessv2/badge.svg?branch=master)](https://coveralls.io/github/digitickets/omnipay-gocardlessv2?branch=master)
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

* GoCardless Pro - Release Candidate
* GoCardless Pro (JS Flow) - Release Candidate
* GoCardless Redirect Flow - Alpha Release

Please let us know if you are using these release candidates in a production envrionment and we will make a formal release - until that time we may push breaking changes!

Don't know which gateway to use?
* If you are using GoCardless' own payment screens on their site you want the RedirectGateway.
* If you are using the GoCardless Javascript to process the card details and return a token then the JSFlowGateway is for you.
* If you are handling the bank account details on your own server (accepting the highest level of PCI responsibility) then the Pro Gateway is for you.

All the gateways wrap a common core with a lot of shared methods but they differ in mechanism for creating customers, bank accounts and mandates. 
Redirect returns you a mandate with customer / bank account created behind the scenes. 
JSFlow returns you a bank account token so you can create a customer, bank account and mandate yourself without handling the bank account details. 
Pro requires you to submit all the data yourself in individual steps.
Creating a subscription or taking a single payment is common to all.

This is still in Development - Only the JS Flow gateway is currently stable. 

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
                            'card' => new \Omnipay\Common\CreditCard( // use the standard omnipay card to hold the customer data
                                [
                                    'firstName' => 'Mike',
                                    'lastName' => 'Jones',
                                    'email' => 'mike.jones@example.com',
                                    'address1' => 'Iconic Song House, 47 Penny Lane',
                                    'address2' => 'Wavertree',
                                    'city' => 'Liverpool',
                                    'company' => 'Mike Jones Enterprises',
                                    'country' => 'GB',
                                    'postal_code' => 'L18 1DE',
                                    'state' => 'Merseyside',
                                ]
                            ),
                            'customerMetaData' => [
                                'meta1' => 'Lorem Ipsom Dolor Est',
                                'meta2' => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.',
                                'meta567890123456789012345678901234567890123456789' => 'Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia.',
                            ],
                            'swedishIdentityNumber' => '123',
            ),
])->send();
```
You can find full list of options [here](https://developer.gocardless.com/api-reference/#customers-create-a-customer).

### Find customer (By id)

```php
$customer = $gateway->findCustomer(1)->send();
```


### Parse webhook notification

```php
$notification = $gateway->parseNotification(
                                getallheaders(),
                                file_get_contents('php://input'), 
                                'MySecurityToken'
                            )
                        ->send();
```
This will fetch the event associated with the web hook.

### Process repeat billing

TODO - this. Use the standard repeatePurchase() format (see sagepay for example structure)

### Suggested generic omnipay driver flow

We are exploring using a simplified set of functions to allow agnostic processing. Each step checks if the method exists on the driver 
and if it does call it accordingly, before calling getXyzRefernce and adding it to the data passed around. 
It is hoped that this structure should work with several major gateways - we have considered Stripe, Paypal and the various GoCardless options.
1.	CreateCustomer
2.	completeCustomer
3.	createPaymentMethod (either create card or create bank account)
4.	completePaymentMethod (either complete card or complete bank account)
5.	createMandate
6.	completeMandate

---- above this point is setting up the customer data (effectively taking it to the point of having an authorisation token), below is creating the transaction data

7.	createPlan
8.	completePlan
9.	createSubscription
10.	completeSubscription


## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/digitickets/omnipay-gocardlessv2e/issues),
or better yet, fork the library and submit a pull request.
