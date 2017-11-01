<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Folder;
use App\Tag;
use App\Tasklist;
use App\Task;
use App\ClientTask;
use App\Project;
use GuzzleHttp\Client as Client;
use Auth;
use PropertyStream\Facades\TW;
use App\Googl;
use Session;
use App\Mail\Contact;
use App\Mail\Confirm;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function userTaskList($project_id, Request $request)
    {

        $user_id = Auth::user()->id;
//        $project_id = $request->project_id;
        $hasAcess = Project::where('project_id', $project_id)->where('fk_user', $user_id)->first();
        if ($hasAcess) {
            $clientTasks = [];
            $studioTasks = [];
            $lists = [];
            $otherList = [];
            $otherTasks = [];
            $folder = '';
            $tags = Tag::all();


//            update tasks
//            TW::getTasks($project_id,$user_id);
//            retrieve tasks
            $folder = Folder::where('fk_user', $user_id)->where('fk_project', $project_id)->first();
            if ($folder) {
                $folder = $folder->folder_id;
            }
            $tasks = Task::where('fk_user', $user_id)->where('visible', 1)->get();
            $project = TW::getProjectName($project_id);
//            dd($project);
            Session::set('project_id', $project['id']);
            Session::set('project_name', $project['name']);
            $user_tw_id = User::find($user_id)->teamwork_id;
            $tasklists = Tasklist::where('fk_project', $project_id)->where('fk_user', $user_id)->where('visible', 1)->get();
            foreach ($tasklists as $tasklist) {

                $StudioTasksTot = Task::where('fk_user', $user_id)->where('visible', 1)->where('fk_tasklist', $tasklist->tasklist_id)->get()->count();
                $clientTasksTot = ClientTask::where('fk_user', $user_id)->where('visible', 1)->where('fk_tasklist', $tasklist->tasklist_id)->get()->count();
                $tot = $StudioTasksTot + $clientTasksTot;

                $studioCompleted = Task::where('fk_user', $user_id)->where('visible', 1)->where('fk_tasklist', $tasklist->tasklist_id)->where('completed', 1)->get()->count();
                $completedClientTasks = ClientTask::where('fk_user', $user_id)->where('visible', 1)->where('fk_tasklist', $tasklist->tasklist_id)->where('completed', 1)->get()->count();
                $completed = $completedClientTasks + $studioCompleted;
                $percent = (int)($completed * 100 / $tot);

//                $uncompleted_client_tasks = Task::where('', 0);

                $lists[$tasklist->tasklist_id]['name'] = $tasklist->tasklist_name;
                $lists[$tasklist->tasklist_id]['tasklist_id'] = $tasklist->tasklist_id;
                $lists[$tasklist->tasklist_id]['percent'] = $percent;
                $lists[$tasklist->tasklist_id]['completed'] = $completed;
                $lists[$tasklist->tasklist_id]['uncompleted'] = $tot - $completed;
                $lists[$tasklist->tasklist_id]['tot'] = $tot;
                $lists[$tasklist->tasklist_id]['uncompletedClientTasks'] = false;

            }

            $other_tasks = ClientTask::where('fk_project', $project_id)->where('visible', 1)->where('fk_user', $user_id)->where('other_task', 1)->get();
            $tot = count($other_tasks);
            $completed = 0;
            foreach ($other_tasks as $other_task) {
                if ((int)$other_task->completed === 1) {
                    $completed += 1;
                }
                $otherTasks[$other_task->id]['ot_id'] = $other_task->id;
                $otherTasks[$other_task->id]['content'] = $other_task->content;
                $otherTasks[$other_task->id]['active'] = $other_task->active;
                $otherTasks[$other_task->id]['completed'] = $other_task->completed;
                $otherTasks[$other_task->id]['fk_tag'] = $other_task->fk_tag;
                $otherTasks[$other_task->id]['description'] = $other_task->description;
                $otherTasks[$other_task->id]['short_description'] = substr($other_task->description, 0, 20) . '...';
                $otherTasks[$other_task->id]['visible'] = $other_task->visible;
                $otherTasks[$other_task->id]['url'] = $other_task->url;
                $otherTasks[$other_task->id]['amount'] = $other_task->amount;
            }
            $otherList['percent'] = 0;
            if ($tot) {
                $otherList['percent'] = (int)($completed * 100 / $tot);
            }
            $otherList['completed'] = $completed;
            $otherList['uncompleted'] = $tot - $completed;
            $otherList['tot'] = $tot;
            $uncomp_ot = ClientTask::where('fk_project', $project_id)->where('fk_user', $user_id)->where('other_task', 1)->where('completed', 0)->where('active', 1)->get();
            if (count($uncomp_ot)) {
                $uncomp = true;
            } else {
                $uncomp = false;
            }
            $otherList['uncompletedClientTasks'] = $uncomp;

            foreach ($tasks as $item) {

                $resp = explode(',', $item->responsible_party_ids);
                if (!in_array($user_tw_id, $resp)) {
//                    checks if the client has uncompleted tasks and if the task is visible
                    $studioTasks[$item->fk_tasklist][$item->task_id]['task_id'] = $item->task_id;
                    $studioTasks[$item->fk_tasklist][$item->task_id]['fk_tasklist'] = $item->fk_tasklist;
                    $studioTasks[$item->fk_tasklist][$item->task_id]['tags'] = $item->tags;
                    $studioTasks[$item->fk_tasklist][$item->task_id]['content'] = $item->content;
                    $studioTasks[$item->fk_tasklist][$item->task_id]['description'] = $item->description;
                    $studioTasks[$item->fk_tasklist][$item->task_id]['completed'] = $item->completed;

                }
            }
//                    $clientTasks[$item->fk_tasklist][$item->task_id]['parentTaskId'] = $item->parentTaskId;
            $tasks = ClientTask::where('fk_user', $user_id)->where('visible', 1)->where('fk_project', $project_id)->where('other_task', 0)->get();
            foreach ($tasks as $item) {
                if (!$item->completed && $item->active && isset($lists[$item->fk_tasklist]['name'])) {
                    $lists[$item->fk_tasklist]['uncompletedClientTasks'] = true;
                }
                $clientTasks[$item->fk_tasklist][$item->fk_task]['client_task_id'] = $item->id;
                $clientTasks[$item->fk_tasklist][$item->fk_task]['fk_tasklist'] = $item->fk_tasklist;
                $clientTasks[$item->fk_tasklist][$item->fk_task]['content'] = $item->content;
                $clientTasks[$item->fk_tasklist][$item->fk_task]['amount'] = $item->amount;
                $clientTasks[$item->fk_tasklist][$item->fk_task]['url'] = $item->url;
                $clientTasks[$item->fk_tasklist][$item->fk_task]['fk_tag'] = $item->fk_tag;
                $clientTasks[$item->fk_tasklist][$item->fk_task]['parentTaskId'] = $item->fk_task;
                $clientTasks[$item->fk_tasklist][$item->fk_task]['tags'] = ' ';
                $clientTasks[$item->fk_tasklist][$item->fk_task]['description'] = $item->description;
                $clientTasks[$item->fk_tasklist][$item->fk_task]['short-description'] = $item->short_description;
                $clientTasks[$item->fk_tasklist][$item->fk_task]['active'] = (int)$item->active;
                $clientTasks[$item->fk_tasklist][$item->fk_task]['completed'] = (int)$item->completed;
            }
//                if (null === $item->parentTaskId) {
//                    $clientTasks[$item->fk_tasklist][$item->parentTaskId]['subtask'] = 'null';
//                } else {
//                    $clientTasks[$item->fk_tasklist][$item->parentTaskId]['subtask'] = $item->task_id;
//                }

//            var_dump($lists);
//        var_dump($studioTasks);
//        var_dump($clientTasks);
            return view('user.project', compact('lists', 'project', 'clientTasks', 'studioTasks', 'otherTasks', 'otherList', 'folder', 'tags'));
        }
    }

    public function completeClientTask(Request $request)
    {
        if (Session::token() !== $request->_token) {
            $response =
                ['msg' => 'Unauthorized attempt to create setting'];
            return json_encode($response);
        }

        $taskName = ClientTask::where('id', $request->id)->where('fk_user', Auth::user()->id)->first();
        if (count($taskName)) {
            $taskName->completed = 1;
            $response = array(
                'status' => 'success',
                'msg' => 'Task Completed',
            );
            $taskName->save();
        }

//returning the progress bar status
        $user_id = Auth::user()->id;
        $tasklist_id = ClientTask::where('id', $request->id)->where('visible', 1)->first()->fk_tasklist;
        if ((int)$tasklist_id !== 0) {
            $tasklist = Tasklist::where('tasklist_id', $tasklist_id)->where('fk_user', $user_id)->where('visible', 1)->first();
//calculate the number of completed and uncompleted tasks
            $studioCompleted = Task::where('fk_user', $user_id)->where('visible', 1)->where('fk_tasklist', $tasklist_id)->where('completed', 1)->get()->count();
            $completedClientTasks = ClientTask::where('fk_user', $user_id)->where('visible', 1)->where('fk_tasklist', $tasklist_id)->where('completed', 1)->get()->count();
            $completed = $completedClientTasks + $studioCompleted;
            $StudioTasksTot = Task::where('fk_user', $user_id)->where('visible', 1)->where('fk_tasklist', $tasklist->tasklist_id)->get()->count();
            $clientTasksTot = ClientTask::where('fk_user', $user_id)->where('visible', 1)->where('fk_tasklist', $tasklist->tasklist_id)->get()->count();
            $tot = $StudioTasksTot + $clientTasksTot;
            $percent = (int)($completed * 100 / $tot);
            $uncompleted = false;
            $uncompletedClientTasks = ClientTask::where('fk_user', $user_id)->where('fk_tasklist', $tasklist_id)->where('active', 1)->where('completed', 0)->where('visible', 1)->count();
            if ($uncompletedClientTasks > 0) {
                $uncompleted = true;
            }
            $response['studioCompleted'] = $studioCompleted;
            $response['completedClientTasks'] = $completedClientTasks;
        } else {
//            other client task
//calculate the number of completed and uncompleted tasks
            $completed = ClientTask::where('fk_user', $user_id)->where('visible', 1)->where('other_task', 1)->where('completed', 1)->get()->count();
            $tot = ClientTask::where('fk_user', $user_id)->where('visible', 1)->where('other_task', 1)->get()->count();
            $percent = (int)($completed * 100 / $tot);
            $uncompleted = false;
            $uncompletedClientTasks = ClientTask::where('fk_user', $user_id)->where('other_task', 1)->where('active', 1)->where('completed', 0)->where('visible', 1)->count();
            if ($uncompletedClientTasks > 0) {
                $uncompleted = true;
            }

        }
        $response['$tot'] = $tot;
        $response['tasklist_id'] = $tasklist_id;
        $response['somma dei due'] = $completed;
        $response['tot'] = $tot;
        $response['percent'] = $percent;
        $response['uncompleted'] = $uncompleted;

// sends the email
        $project_id = $taskName->fk_project;
        Mail::to(User::find(1)->other_email)->send(new Contact($taskName->content, $user_id, $project_id));

        return json_encode($response);

    }

    public function registerOtherEmail(Request $request)
    {
        $user = Auth::user();
        if ($request->use_gmail === "true") {
            $user->confirmation_code = uniqid();
            $user->other_email = $user->email;
        } else {
            $this->validate($request, ['email' => 'required|max:50|email|confirmed']);
            $user->confirmation_code = uniqid();
            $user->other_email = $request->email;
        }
        $user->save();

        Mail::to($user->other_email)->send(new Confirm($user));

        return redirect('/home')->with('success', 'Email registered: check your inbox email and follow the instructions');
    }

    public function confirmOtherEmail(Request $request)
    {

        $code = $request->code;
        $user = User::where('confirmation_code', $code)->first();
        $user->confirmed = 1;
        $user->save();
        auth()->login($user);
        return redirect('/home')->with('success', 'Account verified');
    }
}
