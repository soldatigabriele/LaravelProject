<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GoCardlessPro;
use App\User;
use App\Payment;
use App\ClientTask;
use Auth;
use Redirect;
use Session;
use PropertyStream\Facades\GC;
use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;

class GoCardlessController extends Controller
{
    //

    public function showCustomers(GoCardlessPro $goCardlessPro)
    {
        $client = $goCardlessPro->client();

        $customers = $client->customers()->list()->records;
        echo '<pre>';
        print_r($customers);
        echo '</pre>';
    }

    public function setupNewCustomer(GoCardlessPro $goCardlessPro, Request $request)
    {
//        Register a new client
        $client = $goCardlessPro->client();

        $redirectFlow = $client->redirectFlows()->create([
            "params" => [
                // This will be shown on the payment pages
                "description" => Session::get('project_name'),
                // Not the access token
                "session_token" => "dummy_session_token",
//                redirect URI
                "success_redirect_url" => "http://propstream.link/gocardless/store-customer"]
        ]);

        Session::set('ot_id', $request->ot_id);
        return Redirect::to($redirectFlow->redirect_url);
    }

    public function storeCustomer(GoCardlessPro $goCardlessPro, Request $request)
    {
//        store the user details into the DB
        $flowID = $request->redirect_flow_id;
        $client = $goCardlessPro->client();
        $redirectFlow = $client->redirectFlows()->complete(
            $flowID, //The redirect flow ID from above.
            ["params" => ["session_token" => "dummy_session_token"]]
        );
        $id = Auth::user()->id;
        $user = User::find($id);
        $user->mandate = $redirectFlow->links->mandate;
        $user->customer = $redirectFlow->links->customer;
        $user->save();
        $clientTask = ClientTask::where('id', Session::get('ot_id'))->first();
        $clientTask->completed = 1;
        $clientTask->save();

        Mail::to(User::find(1)->other_email)->send(new Contact('GoCardless Account Setup', Auth::user()->id, Session::get('project_id')));
        return Redirect('/project/' . Session::get('project_id'))->with('gocardless', 'Registration completed');
    }

//    public function test5(GoCardlessPro $goCardlessPro)
//    {
//        $client = $goCardlessPro->client();
//        $payment = $client->payments()->get("PM000260X9VKF4");
//        // Payment ID from above
//
//        print("Status: " . $payment->status . "<br />");
//        print("Cancelling...<br />");
//
//        $payment = $client->payments()->cancel("PM000260X9VKF4");
//        print("Status: " . $payment->status);
//
//    }
//    public function makePayment(Request $request)
//    {
//        Session::set('ot_id', $request->id);
//        $payment_id = ClientTask::where('fk_client_task', $request->ot_id)->first()->payment_id;
//    }

    public function confirmPayment(Request $request)
    {
        $user = Auth::user();

        $goCardlessPro = new GoCardlessPro;
        $client = $goCardlessPro->client();

        $task_id = $request->id;
        $amount = $request->amount;
        $payment = Payment::where('fk_client_task', $task_id)->where('fk_user', $user->id)->first();
        $description = $payment->description;

        $mandate = $user->mandate;
//        generate the payment id and store it into the database
        $payment_id = GC::setupPayment($client, $mandate, $amount, $description);
        $payment->payment_id = $payment_id;
        $payment->save();

        return json_encode([
            'msg' => 'payment completed',
            '$task_id' => $task_id,
            'amount' => $amount,
            '$mandate' => $mandate,
            '$payment_id' => $payment_id,
        ]);

//        return Redirect('/project/' . Session::get('project_id'))->with('payment', 'Payment Task Completed');
    }
}
