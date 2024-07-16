<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    public function boot(UrlGenerator $url): void
    {
        if (env('REDIRECT_HTTPS')) {
            $url->forceScheme('https');
            URL::forceScheme('https');
        }
    }
}
