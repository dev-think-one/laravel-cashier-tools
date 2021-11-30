<?php

namespace CashierUtils;

use Illuminate\Support\Str;

/**
 * @method string subscriptionsUrl(string|null $id = null)
 * @method string invoicesUrl(string|null $id = null)
 * @method string productsUrl(string|null $id = null)
 * @method string pricesUrl(string|null $id = null)
 * @method string couponsUrl(string|null $id = null)
 * @method string promotionCodesUrl(string|null $id = null)
 */
class DashboardRouter
{
    public static string $baseUrlLive = 'https://dashboard.stripe.com';

    public static string $baseUrlTest = 'https://dashboard.stripe.com/test';

    protected bool $isTestMode = false;

    public static function fromConfig(): static
    {
        return new static(!str_starts_with(config('cashier.key'), 'pk_live'));
    }

    public function __construct(bool $isTestMode = false)
    {
        $this->isTestMode = $isTestMode;
    }

    public function setMode(bool $testMode = true): static
    {
        $this->isTestMode = $testMode;

        return $this;
    }

    public function isTestMode(): bool
    {
        return $this->isTestMode;
    }

    public function isLiveMode(): bool
    {
        return !$this->isTestMode();
    }

    public function baseUrl(): string
    {
        return $this->isTestMode ? static::$baseUrlTest : static::$baseUrlLive;
    }

    public function fullUrl(string $path = ''): string
    {
        return rtrim($this->baseUrl(), '/') . '/' . ltrim($path, '/');
    }

    public function __call(string $name, array $arguments)
    {
        if (Str::endsWith($name, 'Url')
            && ($entity = Str::beforeLast($name, 'Url'))) {
            $entity = Str::snake($entity);
            $id     = $arguments[0] ?? null;

            return $this->fullUrl($entity . ($id ? "/{$id}" : ''));
        }

        throw new \BadMethodCallException("Method {$name} not exists.");
    }
}
