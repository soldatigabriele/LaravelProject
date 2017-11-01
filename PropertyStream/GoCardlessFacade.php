<?php
namespace PropertyStream\Facades;

use Illuminate\Support\Facades\Facade;

class GC extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'gocardless'; // Keep this in mind
    }
}