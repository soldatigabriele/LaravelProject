<?php

namespace App\Http\Controllers;

use GoCardlessPro\Client;
use Illuminate\Http\Request;
use App\Payment;
use App\ClientTask;
use App\Tag;
use App\GoCardlessPro;
use PropertyStream\Facades\TW;
use PropertyStream\Facades\GC;
use App\User;
use App\Mail\NewTaskCreated;
use Illuminate\Support\Facades\Mail;

class ClientTaskController extends Controller
{


    public function show($task_id, Request $request)
    {
//        $user_id = $request->user_id;
//        $clientTask = ClientTask::where('fk_user', $user_id)->where('fk_task', $task_id)->first();
//        $tag_id = $clientTask->fk_tag;
//        $tag_name = Tag::where('id', $tag_id)->first();
//        switch ($tag_id) {
//            case 1:
//                break;
//            case 2:
//                break;
//            case 3:
//                break;
//            case 4:
//                $payment = Payment::where('fk_client_task', $clientTask->id)->first();
//                break;
//            case 5:
//                break;
//            case 6:
//                break;
//        }
//        return view('admin.clientTask', compact('payment'))
//            ->with('user_id', $request->user_id);
    }

    public function setTagDetails(Request $request)
    {
        $task_id = (int)$request->task_id;
        $tasklist_id = (int)$request->taskltruoist_id;
        $option = (int)$request->option;
        $user_id = (int)$request->user_id;
        $project_id = (int)$request->project_id;
        $description = $request->description;
        $instructions = $request->instructions;
        $content = $request->content;
        $amount = $request->amount;
        $other_task = (int)$request->other_task;
        $url = $request->url;
        $active = (int)$request->active;
        $visible = (int)$request->visible;

// creates a new client task
        $client = ClientTask::create([
            'content' => $content,
            'description' => $description,
            'instructions' => $instructions,
            'fk_task' => $task_id,
            'fk_tasklist' => $tasklist_id,
            'fk_user' => $user_id,
            'fk_project' => $project_id,
            'other_task' => $other_task,
            'fk_tag' => $option,
            'url' => $url,
            'amount' => $amount,
            'active' => $active,
            'other_task' => $other_task,
            'visible' => $visible
        ]);
        $task_fk = $client->id;
//        if it is a payment

        $user = User::find($user_id);
        if ($option === 3) {
            $mandate = $user->mandate;
            $customer = $user->customer;
            Payment::create([
                'description' => $description,
                'amount' => $amount,
                'user_mandate' => $mandate,
                'user_customer' => $customer,
                'fk_user' => $user_id,
                'payment_id' => null,
                'fk_client_task' => $task_fk,
            ]);
        }

// and returns the data
        $response = array(
            'tasklist_id' => $tasklist_id,
            'task_id' => $task_id,
            'task_fk' => $task_fk,
            'option' => $option,
            'amount' => $amount,
            'url' => $url,
            'active' => $active,
            'user_id' => $user_id,
            'user' => $user,
            'content' => $content,
            'other_task' => $other_task,
            'project_id' => $project_id,
            'short_description' => substr($description, 0, 20),
            'description' => $description,
            'instructions' => $instructions,
            'user_mandate' => $user->mandate,
            'user_customer' => $user->customer,
            'msg' => 'message',
        );

        if ($active === 1) {
            Mail::to($user->other_email)->send(new NewTaskCreated($description,$content, $user_id, $project_id));
        }
        return json_encode($response);

    }

    public function updateVisibleTask(Request $request)
    {
        $task_id = (int)$request->task_id;
        $user_id = (int)$request->user_id;
        $active = (int)$request->active;
        TW::updateClientTask($user_id, $task_id, $active);
        $clientTask = ClientTask::where('fk_user', $user_id)->where('fk_task', $task_id)->first();

        $response = array(
            'task_id' => $task_id,
            'active' => $active,
            'user_id' => $user_id,
            'msg' => 'task updated',
        );

        if ($active === 1) {
            $user = User::find($user_id);
            Mail::to($user->other_email)->send(new NewTaskCreated($clientTask->description,$clientTask->content, $user_id, $clientTask->fk_project));
        }

        return json_encode($response);
    }

    public function updateActiveOtherTask(Request $request)
    {
        $ot_id = (int)$request->ot_id;
        $active = (int)$request->active;
        $visible = (int)$request->visible;
        $user_id = (int)$request->user_id;
        $task = ClientTask::where('id', $ot_id)->first();
        $task->active = $active;
        $task->visible = $visible;
        $task->save();
        $response = array(
            'ot_id' => $ot_id,
            'active' => $active,
            'msg' => 'task updated',
        );
        if ($active === 1) {
            $user = User::find($user_id);
            Mail::to($user->email)->send(new NewTaskCreated($task->description,$task->content, $task->fk_user, $task->fk_project));
        }
        return json_encode($response);
    }

    public function updateTagDetails(Request $request)
    {
        $task_id = (int)$request->task_id;
        $tasklist_id = (int)$request->tasklist_id;
        $option = (int)$request->option;
        $user_id = (int)$request->user_id;
        $project_id = (int)$request->project_id;
        $description = $request->description;
        $instructions = $request->instructions;
        $content = $request->content;
        $amount = $request->amount;
        $url = $request->url;
        $active = (int)$request->active;

        $task = ClientTask::where('fk_task', $task_id)->where('fk_user', $user_id)->first();
        $task->content = $content;
        $task->description = $description;
        $task->instructions = $instructions;
        $task->amount = $amount;
        $task->active = $active;
        $task->fk_tag = $option;
        $task->url = $url;
        $task->save();

        $response = array(
            'tasklist_id' => $tasklist_id,
            'task_id' => $task_id,
            'option' => $option,
            'amount' => $amount,
            'url' => $url,
            'active' => $active,
            'user_id' => $user_id,
            'content' => $content,
            'project_id' => $project_id,
            'short_description' => substr($description, 0, 20),
            'description' => $description,
            'instructions' => $instructions,
            'msg' => 'message',
        );
        if ($active === 1) {
            $user = User::find($user_id);
            Mail::to($user->email)->send(new NewTaskCreated($task->description,$task->content, $task->fk_user, $task->fk_project));
        }
        return json_encode($response);

    }

    public function completeTask(Request $request)
    {
        $task_id = $request->task_id;
        $project_id = $request->project_id;

        $task = ClientTask::find($task_id);
        $task->completed = 1;
        $task->save();

        return json_encode(['msg' => 'ok', 'id' => $task->id]);
//        return redirect('/project/'.$project_id);
    }

    public function deleteTag(Request $request)
    {
        $task_id = (int)$request->task_id;
        $user_id = (int)$request->user_id;
        ClientTask::where('fk_task', $task_id)->where('fk_user', $user_id)->first()->delete();
        $response = array(
            'task_id' => $task_id,
            'user_id' => $user_id,
            'msg' => 'tag deleted',
        );
        return json_encode($response);
    }

}
