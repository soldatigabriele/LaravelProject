<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PropertyStream\Facades\TW;
use PropertyStream\Facades\GC;
use Foobar\Facades\Foo;
use App\GoCardlessPro;
use App\User;
use App\Project;
use App\Payment;
use App\ClientTaskDescription;
class TestController extends Controller
{


    public function test1(GoCardlessPro $goCardlessPro)
    {
        $client = $goCardlessPro->client();

        $customers = $client->customers()->list()->records;
        echo '<pre>';
        print_r($customers);
        echo '</pre>';
//        echo'<pre>';
//       print_r($users);die();
    }

    public function test2(GoCardlessPro $goCardlessPro)
    {
//        Register a new client
        $client = $goCardlessPro->client();

        $redirectFlow = $client->redirectFlows()->create([
            "params" => [
                // This will be shown on the payment pages
                "description" => "Wine boxes",
                // Not the access token
                "session_token" => "dummy_session_token",
//                redirect URI
                "success_redirect_url" => "http://propstream.link/test3"]
        ]);
        print('URL: <a href="' . $redirectFlow->redirect_url . '">' . $redirectFlow->redirect_url . '</a>');
    }

    public function test3(GoCardlessPro $goCardlessPro, Request $request)
    {
//        store the user details into the DB
        $flowID = $request->redirect_flow_id;
        $client = $goCardlessPro->client();
        $redirectFlow = $client->redirectFlows()->complete(
            $flowID, //The redirect flow ID from above.
            ["params" => ["session_token" => "dummy_session_token"]]
        );
        print("Mandate: " . $redirectFlow->links->mandate . "<br />");
// Save this mandate ID for the next section.
        print("Customer: " . $redirectFlow->links->customer . "<br />");
//        store the mandate and customer into the database
    }

    public function test4(GoCardlessPro $goCardlessPro)
    {
        $ik=strtoupper(hash('md5',uniqid()));

        $client = $goCardlessPro->client();
        $payment = $client->payments()->create([
            "params" => [
                "amount" => 1000, // 10 GBP in pence
                "currency" => "GBP",
                "links" => [
//                    user's mandate id
                    "mandate" => "MD0001379J9ME0"
                ],
                // Almost all resources in the API let you store custom metadata,
                // which you can retrieve later
                "metadata" => [
                    "invoice_number" => "001",
                    "test" => "test12"
                ]
            ],
            "headers" => [
                "Idempotency-Key" => $ik
            ]
        ]);

// Keep hold of this payment ID - we'll use it in a minute
// It should look like "PM000260X9VKF4"
        print("ID: " . $payment->id);
    }


    public function test5(GoCardlessPro $goCardlessPro)
    {
        $client = $goCardlessPro->client();
        $payment = $client->payments()->get("PM000260X9VKF4");
        // Payment ID from above

        print("Status: " . $payment->status . "<br />");
        print("Cancelling...<br />");

        $payment = $client->payments()->cancel("PM000260X9VKF4");
        print("Status: " . $payment->status);

    }
    public function test7(){
        return json_encode('ok');
    }

    public function test8(){
        $user = User::where('id', 2)->first();
        $mandate = $user->mandate;
        $customer = $user->customer;
//        dd($mandate);
        $amount = '100';
        $description = 'test description';

        $goCardlessPro = new GoCardlessPro;
        $client = $goCardlessPro->client();
        $payment = GC::setupPayment($client,$mandate,$amount,$description);
        echo $payment.'<br>';
//        $payment = Payment::where('fk_client_task','>',43)->first();
//        $payment_id = $payment->payment_id;
//        $payment = GC::getPaymentDetails($client,$payment_id);
        echo '<pre>';
//        print_r($payment);
        echo '</pre>';
        die();
    }
    public function test9()
    {
        $projects = Project::all();
        foreach ($projects as $project) {
            TW::updateProjectNames();
            TW::getTasklists($project->project_id, $project->fk_user);
            TW::getTasks($project->project_id, $project->fk_user);
        }
        return json_encode(['msg'=>'projects, tasklists and tasks updated']);
    }
//        TW::createClientTask(2, 228701, 7224869, 'set');
//        $users = TW::getUserId();
//        foreach($users as $user){
//            $user['email'];
//            $user['id'];
//        }
//$email = 'gabriele@22group.co.uk';
//        foreach($users as $us){
//            if($us['email']===$email) {
//                $tw_id = $us['id'];
//            }
//        }
//        echo $tw_id;
//        echo Foo::Bar();
//        $num = '228701';
//        $arr = TW::GetProjects($num);
//        dd($arr);

}
