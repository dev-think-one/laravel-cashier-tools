<?php

namespace CashierUtils;

use CashierUtils\Console\Commands\CreatePromotionCodesCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreatePromotionCodesCommand::class,
            ]);
        }
    }

    public function register()
    {
        //
    }
}
