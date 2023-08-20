<?php

namespace CashierUtils\Tests\Console;

use Carbon\Carbon;
use CashierUtils\Tests\TestCase;
use Laravel\Cashier\Cashier;

class CreatePromotionCodesCommandTest extends TestCase
{
    /** @test */
    public function create_coupons()
    {
        if (!config('cashier.secret')) {
            $this->markTestSkipped('env "STRIPE_SECRET" not provided');
        }

        $coupon = Cashier::stripe()->coupons->create([
            'percent_off'        => 20,
            'duration'           => 'repeating',
            'duration_in_months' => 3,
        ]);

        $customer = Cashier::stripe()->customers->create([
            'description' => 'Test Customer',
        ]);

        $result = Cashier::stripe()->promotionCodes->all([
            'coupon' => $coupon->id,
        ]);
        $this->assertEquals(0, $result->count());

        $this->artisan('cashier-tools:api:create:promotion-codes', [
            'couponId'            => $coupon->id,
            '--pattern'           => 'FREE12$',
            '--chars-count'       => 4,
            '--count'             => 2,
            '--p_active'          => true,
            '--p_customer'        => $customer->id,
            '--p_expires_at'      => Carbon::now()->addDay()->timestamp,
            '--p_max_redemptions' => 3,
        ])->assertExitCode(0);

        $result = Cashier::stripe()->promotionCodes->all([
            'coupon' => $coupon->id,
        ]);
        $this->assertEquals(2, $result->count());

        Cashier::stripe()->coupons->delete($coupon->id);
        Cashier::stripe()->customers->delete($customer->id);
    }
}
