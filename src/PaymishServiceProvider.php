<?php

namespace Bhekor\Paymish;

use Illuminate\Support\ServiceProvider;

class PaymishServiceProvider extends ServiceProvider
{
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
                (int) config('paymish.timeout', 30),
                (bool) config('paymish.is_live', false),
                config('paymish.endpoints'),
                config('paymish.public_key'),
                config('paymish.secret_key'),
                config('paymish.email'),
                config('paymish.password'),
                (int) config('paymish.token_cache_seconds', 3500),
            );
        });

        $this->app->alias(PaymishClient::class, 'paymish');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/config/paymish.php' => config_path('paymish.php'),
            ], 'paymish-config');
        }
    }
}
