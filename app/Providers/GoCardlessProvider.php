<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \PropertyStream\GC;

class GoCardlessProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->bind('gocardless', function () {
            return new GC;
        });
    }
}
