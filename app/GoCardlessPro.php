<?php


namespace App;


class GoCardlessPro
{
    public function client()
    {
        $client = new \GoCardlessPro\Client([
            'access_token' => env('GC_ACCESS_TOKEN'),
            // Change me to LIVE when you're ready to go live
            'environment' => \GoCardlessPro\Environment::SANDBOX
        ]);
        return $client;
    }

}