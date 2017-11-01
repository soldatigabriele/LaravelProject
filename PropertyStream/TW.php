<?php

namespace PropertyStream;

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

class TW
{
    public function test()
    {
        dd('ok TW');
    }

    public function updateClientTask($user_id, $task_id, $active)
    {
        $clientTask = ClientTask::where('fk_user', $user_id)->where('fk_task', $task_id)->first();
        $clientTask->active = $active;
        $clientTask->save();
    }

    public function updateVisibleTasks($user_id, $task_id, $option)
    {
        $tasklist = Task::where('fk_user', $user_id)->where('task_id', $task_id)->first();
        $tasklist->visible = $option;
        $tasklist->save();
    }

    public function updateVisibleTasklists($user_id, $tasklists_id, $option)
    {
        $tasklist = Tasklist::where('fk_user', $user_id)->where('tasklist_id', $tasklists_id)->first();
        $tasklist->visible = $option;
        $tasklist->save();
    }

    public function getProjectName($project_id)
    {
	$id = 1269929;
        $client = new Client();
        $res = $client->request('GET', 'https://gabrielesoldati.eu.teamwork.com/projects/' . $project_id . '/tasks.json?includeCompletedTasks=true&getSubTasks=false', [
            'auth' => [env('TEAMWORK_PASSWORD'), 'xxx']]);
        $content = $res->getBody()->getContents();
        $json = json_decode($content, true);
//        echo(count($json['todo-items']));

        if(count($json['todo-items'])<=1){
//            no project found
            return ['error'=>'project not found'];
        }
        $project['name'] = $json['todo-items'][0]['project-name'];
        $project['id'] = $project_id;
        return $project;
    }


    public static function updateProjectNames()
    {
        $client = new Client();
        $res = $client->request('GET', 'https://gabrielesoldati.eu.teamwork.com/projects.json', [
            'auth' => [env('TEAMWORK_PASSWORD'), 'xxx']]);

        $content = $res->getBody()->getContents();
        $json = json_decode($content, true);
        $i = 0;
        foreach ($json['projects'] as $a) {
            $project_name = $json['projects'][$i]['name'];
            $project_id = $json['projects'][$i]['id'];
            $projects = Project::where('project_id', $project_id)->where('project_name', '=', null)->get();
            if ($projects) {
                foreach ($projects as $project) {
                    $project->project_name = $project_name;
                    $project->save();
                }
            }
            $i++;
        }
    }

    public function getTasklists($project_id, $user_id)
    {
        $client = new Client();
        $res = $client->request('GET', 'https://gabrielesoldati.eu.teamwork.com/projects/' . $project_id . '/tasklists.json', [
            'auth' => [env('TEAMWORK_PASSWORD'), 'xxx']]);
        $content = $res->getBody()->getContents();
        $json = json_decode($content, true);
        $countTasklist = Tasklist::where('fk_project', $project_id)->where('fk_user', $user_id)->get();

        if (count($countTasklist)) {
            foreach ($json['tasklists'] as $list) {
//                    if there is a tasklist with the same id, just updates the fields
                $tl = Tasklist::where('fk_project', $project_id)->where('tasklist_id', $list['id'])->where('fk_user', $user_id)->first();
                if ($tl) {
                    $tl->tasklist_name = $list['name'];
                    $tl->uncompleted = $list['uncompleted-count'];
                    $tl->save();
                } else {
//                        else if there is no tasklist with that id, creates it
                    Tasklist::create([
                        'tasklist_id' => $list['id'],
                        'tasklist_name' => $list['name'],
                        'uncompleted' => $list['uncompleted-count'],
                        'fk_user' => $user_id,
                        'fk_project' => $project_id
                    ]);
                }
            }
        } else {
            foreach ($json['tasklists'] as $list) {
                Tasklist::create([
                    'tasklist_id' => $list['id'],
                    'tasklist_name' => $list['name'],
                    'uncompleted' => $list['uncompleted-count'],
                    'fk_user' => $user_id,
                    'fk_project' => $project_id
                ]);
            }
        }


    }

    public function getTasks($project_id, $user_id)
    {

        $client = new Client();
        $res = $client->request('GET', 'https://gabrielesoldati.eu.teamwork.com/projects/' . $project_id . '/tasks.json?includeCompletedTasks=true&getSubTasks=false', [
            'auth' => [env('TEAMWORK_PASSWORD'), 'xxx']]);
        $content = $res->getBody()->getContents();
        $json = json_decode($content, true);
        $proj_id = Project::where('project_id', $project_id)->where('fk_user', $user_id)->first()->id;
        $tasks = Task::where('fk_project', $proj_id)->where('fk_user', $user_id)->get();
        if (count($tasks)) {
            foreach ($json['todo-items'] as $item) {
//                if the single task is already stored into the DB, update it, and add the new tasks
                $task = Task::where('fk_project', $proj_id)->where('task_id', $item['id'])->where('fk_user', $user_id)->first();
                $tags = '';
                if ($task) {
                    if (isset($item['tags'])) {
                        foreach ($item['tags'] as $tag) {
                            $tags .= $tag['name'];
                        }
                        $task->tags = $tags;
                    }
                    $task->fk_tasklist = $item['todo-list-id'];
                    $task->description = $item['description'];
                    $task->content = $item['content'];
                    $task->parentTaskId = $item['parentTaskId'];
                    $task->creatorFirstName = $item['creator-firstname'];
                    $task->creatorLastName = $item['creator-lastname'];
                    $task->fk_project = $proj_id;
                    $task->fk_user = $user_id;
                    $task->visible = true;

                    if ($item['completed'] == 1) {
                        $task->completed = true;
                    } else {
                        $task->completed = false;
                    };
//                    if (isset($item['responsible-party-ids'])) {
//                        $task->responsible_party_ids = $item['responsible-party-ids'];
//                    } else {
                        $task->responsible_party_ids = '';
//                    }

                    $task->save();
                } else {
//      otherwise create the new single task and save it
                    $tags = '';
                    if (isset($item['tags'])) {
                        foreach ($item['tags'] as $tag) {
                            $tags .= $tag['name'];
                        }
                    }
                    if (isset($item['responsible-party-ids'])) {
                        $responsible_party_ids = $item['responsible-party-ids'];
                    } else {
                        $responsible_party_ids = ' ';
                    }
                    Task::create([
                        'task_id' => $item['id'],
                        'fk_tasklist' => $item['todo-list-id'],
                        'description' => $item['description'],
                        'content' => $item['content'],
                        'parentTaskId' => $item['parentTaskId'],
                        'tags' => $tags,
                        'creatorFirstName' => $item['creator-firstname'],
                        'creatorLastName' => $item['creator-lastname'],
                        'responsible_party_ids' => '',
                        'fk_project' => $proj_id,
                        'fk_user' => $user_id,
                        'visible' => true,
                    ]);
                }
            }
        } else {
//            otherwise retrieve all the tasks for this project
            foreach ($json['todo-items'] as $task) {
                $DBtasks = new Task;
                $tags = '';
                if (isset($task['tags'])) {
                    foreach ($task['tags'] as $tag) {
                        $tags .= $tag['name'];
                    }
                    $DBtasks->tags = $tags;
                }
                $DBtasks->task_id = $task['id'];
                $DBtasks->fk_tasklist = $task['todo-list-id'];
                $DBtasks->description = $task['description'];
                $DBtasks->content = $task['content'];
                $DBtasks->parentTaskId = $task['parentTaskId'];
                $DBtasks->creatorFirstName = $task['creator-firstname'];
                $DBtasks->creatorLastName = $task['creator-lastname'];
                $DBtasks->fk_project = $proj_id;
                $DBtasks->fk_user = $user_id;

                if ($task['completed'] == 1) {
                    $DBtasks->completed = true;
                } else {
                    $DBtasks->completed = false;
                };

//                if (isset($task['responsible-party-ids'])) {
//                    $DBtasks->responsible_party_ids = $task['responsible-party-ids'];
//                } else {
                    $DBtasks->responsible_party_ids = '';
//                }
                $DBtasks->save();
            }
        }
    }

    public
    function getUserId()
    {
        $client = new Client();
        $res = $client->request('GET', 'https://gabrielesoldati.eu.teamwork.com/companies/26078/people.json',
            ['auth' => [env('TEAMWORK_PASSWORD'), 'xxx']]
        );
        $content = $res->getBody()->getContents();
        $json = json_decode($content, true);
        $people = 0;
        foreach ($json['people'] as $x) {
            $people++;
        }
        $users = [];
        for ($i = 0; $i < $people; $i++) {
            $users[$i]['id'] = $json['people'][$i]['id'];
            $users[$i]['email'] = $json['people'][$i]['user-name'];
        }
        return $users;
    }

    public function MarkTaskUncompleted($task_id)
    {
        $client = new Client();
        $res = $client->request('PUT', 'https://gabrielesoldati.eu.teamwork.com/tasks/' . $task_id . '/uncomplete.json', ['auth' => [env('TEAMWORK_PASSWORD'), 'xxx']]);
        $content = $res->getBody()->getContents();
        $json = json_decode($content, true);
        var_dump($json);
    }

    public function updateAll(){
        $projects = Project::all();
        foreach ($projects as $project) {
            Self::updateProjectNames();
            Self::getTasklists($project->project_id, $project->fk_user);
            Self::getTasks($project->project_id, $project->fk_user);
        }
//        return json_encode(['msg'=>'projects, tasklists and tasks updated']);
    }

    public function MarkTaskCompleted($task_id)
    {
        $client = new Client();
        $res = $client->request('PUT', 'https://gabrielesoldati.eu.teamwork.com/tasks/' . $task_id . '/complete.json', ['auth' => [env('TEAMWORK_PASSWORD'), 'xxx']]);
        $content = $res->getBody()->getContents();
        $json = json_decode($content, true);
        var_dump($json);
    }
}
