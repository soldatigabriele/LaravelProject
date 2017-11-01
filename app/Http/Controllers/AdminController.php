<?php

namespace App\Http\Controllers;

use App\ClientTaskDescription;
use App\Payment;
use Illuminate\Http\Request;
use App\User;
use App\Folder;
use App\Tasklist;
use App\Task;
use App\Tag;
use App\ClientTask;
use App\Project;
use Debugbar;
use GuzzleHttp\Client as Client;
use Auth;
use PropertyStream\Facades\TW;
use Session;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function cpanel()
    {
        $users = User::where('id', '>', 0)->orderBy('id', 'desc')->get();
//        TW::updateAll();
        return view('admin.controlPanel', compact('users'));

    }

    public function deleteUser($id)
    {
        $usermail = User::where('id', $id)->first()->email;
        User::where('id', $id)->delete();
        return redirect('admin/cpanel')->with('deleted', $usermail);
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|max:255',
        ]);

        $user = new User();
        $user->email = $request->email;
        if($request->other_email!==''){
            $user->other_email = $request->other_email;
        }else{
            $user->other_email = NULL;
        }
        if (strpos($request->name, ' ') !== false) {
            $name = explode(' ', $request->name);
            $user->name = $name[0];
            $user->surname = $name[1];
        } else {
            $user->surname = $request->name;
        }
        if ($request->admin == true) {
            $user->admin = true;
        }
        $user->save();
        return redirect('/admin/cpanel');

    }

    public function show($id)
    {
//        show user details

        $user = User::where('id', $id)->first();
        $projects = Project::where('fk_user', $id)->get();
        $folders = Folder::where('fk_user', $id)->get();
//        update projects, tasklists and tasks
        TW::updateAll();
        return view('admin.user', compact('user', 'projects', 'folders'));

    }

    public function projectStore(Request $request)
    {
        $this->validate($request, [
            'project_id' => 'required|min:5',
        ]);
        $userid = $request->user_id;
        $project_id = $request->project_id;
        if (Project::where('project_id', $project_id)->count()) {
            return redirect('/admin/user/' . $userid)->with('error', 'project already existing');
        } else {
//            check if project exists
            $msg = TW::getProjectName($project_id);
            if (isset($msg['error'])) {
                return redirect('/admin/user/' . $userid)->with('error', 'project does not exist');
            }
            $project = new Project;
            $project->fk_user = $userid;
            $project->project_id = $project_id;
            $project->save();
//        retrieve the tasks and store it into the DB
            TW::getTasks($request->project_id, $userid);
//        retrieve the tasklists and store it into the DB
            TW::getTasklists($request->project_id, $userid);
//        update the name of the projects
            TW::updateProjectNames();

            return redirect('/admin/user/' . $userid);
        }
//        return redirect('/admin/user/' . $userid);

    }

    public static function updateAll()
    {
        $projects = Project::all();
        foreach ($projects as $project) {
            TW::updateProjectNames();
            TW::getTasklists($project->project_id, $project->fk_user);
            TW::getTasks($project->project_id, $project->fk_user);
        }
        return json_encode(['msg'=>'projects, tasklists and tasks updated']);
    }

    public function projectDestroy(Request $request)
    {
        $userid = $request->user_id;
        $project_id = $request->project_id;
        $url = '/admin/user/' . $userid;
        Project::where('id', $project_id)->delete();
        Folder::where('fk_project', $project_id)->where('fk_user', $userid)->delete();
        Tasklist::where('fk_project', $project_id)->where('fk_user', $userid)->delete();
        Task::where('fk_project', $project_id)->where('fk_user', $userid)->delete();
        return redirect($url);
    }

    public function folderStore(Request $request)
    {
        $this->validate($request, [
            'folder_id' => 'required|min:20',
        ]);
        $folder = new Folder;
        $user_id = $request->user_id;
        $project_id = $request->project_id;
        $folder->fk_user = $user_id;
        $folder->fk_project = $project_id;
        $folder->folder_id = $request->folder_id;
        $folder->save();

        $url = '/admin/user/' . $user_id . '/project/' . $project_id;
        return redirect($url);
    }

    public function folderDestroy(Request $request)
    {
        $userid = $request->user_id;
        $project_id = $request->project_id;
        $url = '/admin/user/' . $userid . '/project/' . $project_id;
        Folder::where('fk_project', $request->project_id)->delete();
        return redirect($url);

    }

//    updates the checkboxes to show and hide tasklists to a specific user
    public function updateVisibleTasklists(Request $request)
    {

        if (Session::token() !== $request->_token) {
            $response =
                ['msg' => 'Unauthorized attempt to create setting'];
            return json_encode($response);
        }

        $task_id = $request->id;
        $value = $request->value;
        $option = $request->option;
        $user_id = $request->user_id;
        if ($value === 'tasklist') {
            TW::updateVisibleTasklists($user_id, $task_id, $option);
        } else if ($value === 'task') {
            TW::updateVisibleTasks($user_id, $task_id, $option);
        }

        $response = array(
            'name' => $task_id,
            'option' => $option,
            'value' => $value,
            'user_id' => $user_id,
            'status' => 'success',
            'msg' => 'Setting created successfully',
        );
        return json_encode($response);
    }


    public function taskList($user_id, $project_id)
    {
        $user_id = $user_id;
        $hasAcess = Project::where('project_id', $project_id)->where('fk_user', $user_id)->first();
        if ($hasAcess) {
            $clientTasks = [];
            $studioTasks = [];
            $otherTasks = [];
            $lists = [];

            $folder = Folder::where('fk_project', $project_id)->first();

//            update tasks
//            TW::getTasks($project_id,$user_id);
//            retrieve tasks
            $tasks = Task::where('fk_user', $user_id)->get();
            $project = TW::getProjectName($project_id);
            $user_tw_id = User::find($user_id)->teamwork_id;
            $tasklists = Tasklist::where('fk_project', $project_id)->where('fk_user', $user_id)->get();
            $client_tasks = ClientTask::where('fk_project', $project_id)->where('fk_user', $user_id)->where('other_task', 0)->get();
            foreach ($client_tasks as $client_task) {
                $clientTasks[$client_task->fk_task]['content'] = $client_task->content;
                $clientTasks[$client_task->fk_task]['active'] = $client_task->active;
                $clientTasks[$client_task->fk_task]['completed'] = $client_task->completed;
                $clientTasks[$client_task->fk_task]['fk_tag'] = $client_task->fk_tag;
                $clientTasks[$client_task->fk_task]['description'] = $client_task->description;
                $clientTasks[$client_task->fk_task]['short_description'] = substr($client_task->description, 0, 20) . '...';
                $clientTasks[$client_task->fk_task]['url'] = $client_task->url;
                $clientTasks[$client_task->fk_task]['amount'] = $client_task->amount;
            }
            $other_tasks = ClientTask::where('fk_project', $project_id)->where('fk_user', $user_id)->where('other_task', 1)->get();
            foreach ($other_tasks as $other_task) {
                $otherTasks[$other_task->id]['ot_id'] = $other_task->id;
                $otherTasks[$other_task->id]['content'] = $other_task->content;
                $otherTasks[$other_task->id]['active'] = $other_task->active;
                $otherTasks[$other_task->id]['completed'] = $other_task->completed;
                $otherTasks[$other_task->id]['fk_tag'] = $other_task->fk_tag;
                $otherTasks[$other_task->id]['description'] = $other_task->description;
                $otherTasks[$other_task->id]['visible'] = $other_task->visible;
                $otherTasks[$other_task->id]['short_description'] = substr($other_task->description, 0, 20) . '...';
                $otherTasks[$other_task->id]['url'] = $other_task->url;
                $otherTasks[$other_task->id]['amount'] = $other_task->amount;
            }

            foreach ($tasklists as $tasklist) {
                $tot = 0;
                $completed = 0;
                $tasksTot = Task::where('fk_user', $user_id)->where('fk_tasklist', $tasklist->tasklist_id)->get();
                $tot = count($tasksTot);
                $tasksCompleted = Task::where('fk_user', $user_id)->where('visible', 1)->where('fk_tasklist', $tasklist->tasklist_id)->where('completed', 1)->get();
                $completed = count($tasksCompleted);
                $percent = (int)($completed * 100 / $tot);

                $lists[$tasklist->tasklist_id]['name'] = $tasklist->tasklist_name;
                $lists[$tasklist->tasklist_id]['tasklist_id'] = $tasklist->tasklist_id;
                $lists[$tasklist->tasklist_id]['percent'] = $percent;
                $lists[$tasklist->tasklist_id]['visible'] = $tasklist->visible;
                $lists[$tasklist->tasklist_id]['completed'] = $completed;
                $lists[$tasklist->tasklist_id]['uncompleted'] = $tot - $completed;
                $lists[$tasklist->tasklist_id]['tot'] = $tot;
                $lists[$tasklist->tasklist_id]['uncompletedClientTasks'] = false;
            }

            $tags = Tag::all();

            foreach ($tasks as $item) {
                if (count(ClientTask::where('fk_task', $item->task_id)->where('fk_user', $user_id)->first())) {
                    $has_client_task = 1;
                } else {
                    $has_client_task = 0;
                }
                $studioTasks[$item->fk_tasklist][$item->task_id]['task_id'] = $item->task_id;
                $studioTasks[$item->fk_tasklist][$item->task_id]['fk_tasklist'] = $item->fk_tasklist;
                $studioTasks[$item->fk_tasklist][$item->task_id]['content'] = $item->content;
                $studioTasks[$item->fk_tasklist][$item->task_id]['visible'] = $item->visible;
                $studioTasks[$item->fk_tasklist][$item->task_id]['tags'] = $item->tags;
                $studioTasks[$item->fk_tasklist][$item->task_id]['has_client_task'] = $has_client_task;
                $studioTasks[$item->fk_tasklist][$item->task_id]['description'] = $item->description;
                $studioTasks[$item->fk_tasklist][$item->task_id]['completed'] = $item->completed;
            }
            return view('admin.project', compact('lists', 'project', 'clientTasks', 'studioTasks', 'otherTasks', 'folder', 'tags'))->with('user_id', $user_id)->with('project_id', $project_id);
        }
    }

}
