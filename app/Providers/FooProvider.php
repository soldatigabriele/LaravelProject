<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \Foobar\Foo;

class FooProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->bind('foo', function () {
            return new Foo; //Add the proper namespace at the top
        });
    }
}
