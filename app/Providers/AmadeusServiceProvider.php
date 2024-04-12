<?php

namespace App\Providers;

use Amadeus\Amadeus;
use Illuminate\Support\ServiceProvider;

class AmadeusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Amadeus::class, function ($app) {
            $config = $app->make('config')->get('amadeus');

            return Amadeus::builder($config['api_key'], $config['api_secret'])->build();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
