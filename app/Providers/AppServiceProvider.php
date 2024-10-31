<?php

namespace App\Providers;

use App\Exceptions\Handler;
use App\Services\Factory\ServiceFactory;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ExceptionHandler::class, Handler::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env("NGROK") === true) {
            URL::forceScheme('https');
        }
    }
}
