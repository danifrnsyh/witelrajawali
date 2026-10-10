<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $httpHost = $_SERVER['HTTP_X_FORWARDED_HOST']
            ?? $_SERVER['HTTP_X_ORIGINAL_HOST']
            ?? $_SERVER['HTTP_HOST']
            ?? null;

        $isHttps = (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || str_starts_with((string) config('app.url'), 'https://');

        if ($isHttps) {
            URL::forceScheme('https');
        }

        if ($httpHost && str_contains($httpHost, 'ngrok')) {
            URL::forceScheme('https');
            URL::forceRootUrl('https://'.$httpHost);
        } elseif (str_contains((string) config('app.url'), 'ngrok')) {
            URL::forceScheme('https');
            URL::forceRootUrl(config('app.url'));
        }
    }
}
