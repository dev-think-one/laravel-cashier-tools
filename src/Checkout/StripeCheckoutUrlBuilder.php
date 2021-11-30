<?php

namespace CashierUtils\Checkout;

class StripeCheckoutUrlBuilder
{
    public static string $sessionKey      = 'checkout_session';
    public static string $resultStatusKey = 'payment_status';

    public static string $statusSuccess  = 'success';
    public static string $statusCanceled = 'canceled';

    protected string $url;

    protected bool $withSessionId   = false;
    protected ?string $resultStatus = null;

    protected function __construct(string $url)
    {
        $this->url = $url;
    }

    public static function make(string $url)
    {
        return new static($url);
    }

    public function withSessionId(bool $withSessionId = true): static
    {
        $this->withSessionId = $withSessionId;

        return $this;
    }

    public function useResultStatus(?string $resultStatus = null): static
    {
        $this->resultStatus = $resultStatus;

        return $this;
    }

    public function url(): string
    {
        $query = parse_url($this->url, PHP_URL_QUERY);
        parse_str($query, $query);
        if ($this->withSessionId) {
            $query[static::$sessionKey] = 'CHECKOUT_SESSION_ID_TO_REPLACE';
        }
        if ($this->resultStatus) {
            $query[static::$resultStatusKey] = $this->resultStatus;
        }
        $newUrl = strtok($this->url, '?') . '?' . http_build_query($query);

        return str_replace('CHECKOUT_SESSION_ID_TO_REPLACE', '{CHECKOUT_SESSION_ID}', $newUrl);
    }

    public static function prepareSuccessUrl(string $url, ?string $resultStatus = null): string
    {
        return static::make($url)->withSessionId()
                     ->useResultStatus($resultStatus ?? static::$statusSuccess)
                     ->url();
    }

    public static function prepareCancelUrl(string $url, ?string $resultStatus = null): string
    {
        return static::make($url)
                     ->useResultStatus($resultStatus ?? static::$statusCanceled)
                     ->url();
    }
}
