<?php

namespace PropertyStream;

use App\GoCardlessPro;

use Illuminate\Http\Request;
use App\User;
use App\Task;
use App\Tasklist;
use App\Folder;
use App\ClientTask;
use App\Project;
use Debugbar;
use GuzzleHttp\Client as Client;
use Auth;
use App\Exceptions\TeamworkException;


class GC
{
    public function test()
    {
        dd('ok TW');
    }

    public function setupPayment($client, $mandate, $amount, $description)
    {
        $ik = strtoupper(hash('md5', uniqid()));
        $amount = $amount * 100;
//        $client = $goCardlessPro->client();
        $payment = $client->payments()->create([
            "params" => [
                "amount" => $amount, // 10 GBP in pence
                "currency" => "GBP",
                "links" => [
//                    user's mandate id
                    "mandate" => $mandate
                ],
                // Almost all resources in the API let you store custom metadata,
                // which you can retrieve later
                "metadata" => [
                    "description" => $description,
                ]
            ],
            "headers" => [
                "Idempotency-Key" => $ik
            ]
        ]);
        return $payment->id;
    }

    public function getPaymentDetails($client,$payment_id)
    {
        $payment = $client->payments()->get($payment_id);
        return $payment->api_response->body->payments;
    }

}