<?php

namespace CashierUtils\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Cashier\CashierServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use DatabaseMigrations;

    protected function getPackageProviders($app)
    {
        return [
            CashierServiceProvider::class,
            \CashierUtils\ServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ]);
    }
}
