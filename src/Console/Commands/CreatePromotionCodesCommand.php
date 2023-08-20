<?php

namespace CashierUtils\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Laravel\Cashier\Cashier;
use Stripe\StripeClient;

class CreatePromotionCodesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashier-tools:api:create:promotion-codes
    {couponId : Coupon ID}
    {--P|pattern=$ : Pattern for creation}
    {--S|chars-count=8 : Random strings count}
    {--C|count=1 : Promo codes count}
    {--p_active= : Parameter active}
    {--p_customer= : Parameter customer}
    {--p_expires_at= : Parameter expires_at}
    {--p_max_redemptions= : Parameter max_redemptions}
    {--stripe-secret= : Stripe secret key}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "
    Create new promotion codes \n
    Example: \n
    php artisan cashier-tools:api:create:promotion-codes 4o73oLhX -P \"FREE12$\" -S 8 -C 2 --p_max_redemptions=1 >> codes.txt
    ";

    protected ?StripeClient $cachedStripeClient = null;

    public function handle()
    {
        $createdCount = 0;
        for ($i = 1; $i <= $this->option('count'); $i++) {
            $code = $this->getCode();
            if (empty($code)) {
                continue;
            }

            $data = [
                'coupon' => $this->argument('couponId'),
                'code'   => $code,
            ];
            if ($this->option('p_active')) {
                $data['active'] = !($this->option('p_active') === 'false');
            }
            if ($this->option('p_customer')) {
                $data['customer'] = $this->option('p_customer');
            }
            if ($this->option('p_expires_at')) {
                $data['expires_at'] = $this->option('p_expires_at');
            }
            if ($this->option('p_max_redemptions')) {
                $data['max_redemptions'] = $this->option('p_max_redemptions');
            }
            $promotionCode = $this->stripeClient()->promotionCodes->create($data);
            $this->info("Promotion code [{$promotionCode->code}]({$promotionCode->id}]) created.");
            $createdCount++;
        }

        $this->info("Totally created: {$createdCount} codes.");

        return 0;
    }

    public function getCode(): string
    {
        $charsCount = 0;
        $option     = $this->option('chars-count');
        if ($option && is_numeric($option)) {
            $charsCount = (int)$option;
        }
        $pattern = '$';
        $option  = $this->option('pattern');
        if (!empty($option) && is_string($option)) {
            $pattern = $option;
        }

        $code = Str::replace('$', Str::upper(Str::random($charsCount)), $pattern);

        if ($code) {
            $codes = $this->stripeClient()->promotionCodes->all([
                'code' => $code,
            ]);
            if (!$codes->isEmpty()) {
                $this->error("Code {$code} already exists.");

                return '';
            }
        }

        return $code;
    }

    public function stripeClient(): StripeClient
    {
        if ($this->cachedStripeClient) {
            return $this->cachedStripeClient;
        }

        $params = [];
        if ($key = $this->option('stripe-secret')) {
            $params['api_key'] = $key;
        }

        return $this->cachedStripeClient = app(Cashier::class)::stripe($params);
    }
}
