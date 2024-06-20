<?php

namespace App\Providers;

use App\WebSocketClient;
use Illuminate\Support\ServiceProvider;

class WebSocketClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(WebSocketClient::class, function () {
            return new WebSocketClient(config('websocket.host'), config('websocket.port'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        
    }
}
