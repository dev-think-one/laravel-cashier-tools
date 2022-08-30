<?php

namespace CashierUtils\Tests;

use CashierUtils\Checkout\StripeCheckoutUrlBuilder;

class StripeCheckoutUrlBuilderTest extends TestCase
{
    /** @test */
    public function success_url()
    {
        $url = 'https://test.co.uk/example/hook?foo=bar&baz=';

        $newUrl = StripeCheckoutUrlBuilder::make($url)->url();
        $this->assertEquals($url, $newUrl);

        $newUrl = StripeCheckoutUrlBuilder::make($url)->useResultStatus('foo')->url();
        $this->assertEquals($url . '&payment_status=foo', $newUrl);

        $newUrl = StripeCheckoutUrlBuilder::make($url)->withSessionId()->useResultStatus('foo')->url();
        $this->assertEquals($url . '&checkout_session={CHECKOUT_SESSION_ID}&payment_status=foo', $newUrl);
    }

    /** @test */
    public function prepare_success_url()
    {
        $url = 'https://test.co.uk/example/hook?foo=bar&baz=';

        $newUrl = StripeCheckoutUrlBuilder::prepareSuccessUrl($url);
        $this->assertEquals($url . '&checkout_session={CHECKOUT_SESSION_ID}&payment_status=success', $newUrl);

        $newUrl = StripeCheckoutUrlBuilder::prepareSuccessUrl($url, 'foo');
        $this->assertEquals($url . '&checkout_session={CHECKOUT_SESSION_ID}&payment_status=foo', $newUrl);
    }

    /** @test */
    public function cancel_url()
    {
        $url = 'https://test.co.uk/example/hook?foo=bar&baz=';

        $newUrl = StripeCheckoutUrlBuilder::make($url)->url();
        $this->assertEquals($url, $newUrl);

        $newUrl = StripeCheckoutUrlBuilder::make($url)->useResultStatus('bar')->url();
        $this->assertEquals($url . '&payment_status=bar', $newUrl);
    }

    /** @test */
    public function prepare_cancel_url()
    {
        $url = 'https://test.co.uk/example/hook?foo=bar&baz=';

        $newUrl = StripeCheckoutUrlBuilder::prepareCancelUrl($url);
        $this->assertEquals($url . '&payment_status=canceled', $newUrl);

        $newUrl = StripeCheckoutUrlBuilder::prepareCancelUrl($url, 'foo');
        $this->assertEquals($url . '&payment_status=foo', $newUrl);
    }
}
