<?php
namespace PropertyStream\Facades;

use Illuminate\Support\Facades\Facade;

class TW extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'teamwork'; // Keep this in mind
    }
}