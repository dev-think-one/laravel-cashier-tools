# Set of utils what not added to main laravel cashier stripe package

[![Packagist License](https://img.shields.io/packagist/l/yaroslawww/laravel-cashier-tools?color=%234dc71f)](https://github.com/yaroslawww/laravel-cashier-tools/blob/master/LICENSE.md)
[![Packagist Version](https://img.shields.io/packagist/v/yaroslawww/laravel-cashier-tools)](https://packagist.org/packages/yaroslawww/laravel-cashier-tools)
[![Build Status](https://scrutinizer-ci.com/g/yaroslawww/laravel-cashier-tools/badges/build.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-cashier-tools/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/yaroslawww/laravel-cashier-tools/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-cashier-tools/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yaroslawww/laravel-cashier-tools/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-cashier-tools/?branch=master)

## Installation

Install the package via composer:

```bash
composer require yaroslawww/laravel-cashier-tools
```

## Usage

### Create promotion codes command

```shell
php artisan cashier-tools:api:create:promotion-codes COUPON_ID_2021 -P "2021$" >> codes.txt
php artisan cashier-tools:api:create:promotion-codes COUPON_ID_2021 -S 20 -C 100 --p_max_redemptions=10 --stripe-secret=sk_live_4FL... >> codes.txt
```

### Use helper for checkout redirect url

Builder to support redirect url params: (Like '{CHECKOUT_SESSION_ID}')

```injectablephp
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

```injectablephp
DashboardRouter::fromConfig()->productsUrl()
DashboardRouter::fromConfig()->productsUrl($stripeProduct->id)
(new DashboardRouter(true))->promotionCodesUrl($promotionCode->id)
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/)
