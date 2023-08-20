# Set of utils what not added to main laravel cashier stripe package

![Packagist License](https://img.shields.io/packagist/l/think.studio/laravel-cashier-tools?color=%234dc71f)
[![Packagist Version](https://img.shields.io/packagist/v/think.studio/laravel-cashier-tools)](https://packagist.org/packages/think.studio/laravel-cashier-tools)
[![Total Downloads](https://img.shields.io/packagist/dt/think.studio/laravel-cashier-tools)](https://packagist.org/packages/think.studio/laravel-cashier-tools)
[![Build Status](https://scrutinizer-ci.com/g/dev-think-one/laravel-cashier-tools/badges/build.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/laravel-cashier-tools/build-status/main)
[![Code Coverage](https://scrutinizer-ci.com/g/dev-think-one/laravel-cashier-tools/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/laravel-cashier-tools/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dev-think-one/laravel-cashier-tools/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/laravel-cashier-tools/?branch=main)

## Installation

Install the package via composer:

```bash
composer require think.studio/laravel-cashier-tools
```

## Usage

### Create promotion codes command

```shell
php artisan cashier-tools:api:create:promotion-codes COUPON_ID_2021 -P "2021$" >> codes.txt
php artisan cashier-tools:api:create:promotion-codes COUPON_ID_2021 -S 20 -C 100 --p_max_redemptions=10 --stripe-secret=sk_live_4FL... >> codes.txt
```

### Use helper for checkout redirect url

Builder to support redirect url params: (Like '{CHECKOUT_SESSION_ID}')

```php
$url = route('cpd.account.index');

$subscription->allowPromotionCodes()
             ->checkout([
                 'success_url' => StripeCheckoutUrlBuilder::prepareSuccessUrl($url),
                 'cancel_url'  => StripeCheckoutUrlBuilder::prepareCancelUrl($url),
             ]);

$subscription->checkout([
    'success_url' => StripeCheckoutUrlBuilder::make($url)
                                             ->withSessionId()
                                             ->useResultStatus('foo')
                                             ->url(),
    'cancel_url'  => StripeCheckoutUrlBuilder::make($url)
                                             ->useResultStatus('bar')
                                             ->url(),
]);
```

### Use dashboard router

```php
DashboardRouter::fromConfig()->productsUrl()
DashboardRouter::fromConfig()->productsUrl($stripeProduct->id)
(new DashboardRouter(true))->promotionCodesUrl($promotionCode->id)
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/)
