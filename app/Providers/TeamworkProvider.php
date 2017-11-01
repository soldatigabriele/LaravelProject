<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \PropertyStream\TW;

class TeamworkProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->bind('teamwork', function () {
            return new TW;
        });
    }
}
