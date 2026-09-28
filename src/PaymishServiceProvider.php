<?php

namespace Bhekor\Paymish;

use Illuminate\Support\ServiceProvider;
use Bhekor\Paymish\PaymishClient;

class PaymishServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/paymish.php',
            'paymish'
        );

        $this->mergeConfigFrom(
            __DIR__.'/config/endpoints.php',
            'paymish.endpoints'
        );

        $this->app->singleton(PaymishClient::class, function ($app) {
            return new PaymishClient(
                config('paymish.api_key'),
                config('paymish.base_url', 'https://api.paymish.com'),
                config('paymish.timeout', 30),
                config('paymish.is_live', false),
                config('paymish.endpoints')
            );
        });

        $this->app->alias(PaymishClient::class, 'paymish');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/config/paymish.php' => config_path('paymish.php'),
            __DIR__.'/config/endpoints.php' => config_path('paymish-endpoints.php'),
        ], 'paymish-config');
    }
}
