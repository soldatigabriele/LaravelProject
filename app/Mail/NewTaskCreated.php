<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Project;
use App\User;

class NewTaskCreated extends Mailable
{
    use Queueable, SerializesModels;
    public $task,$user,$project;

    public function __construct($task_description,$task_content,$user_id,$project_id)
    {
        $this->project['name'] = Project::where('project_id',$project_id)->first()->project_name;
        $this->task['name'] = $task_content;
        $this->task['description'] = $task_description;
        $user = User::find($user_id);
        $this->user['name'] = $user->name;
        $this->user['id'] = $user->id;
        $this->user['email']= $user->email;
        $this->project['id'] = $project_id;
    }

    public function build()
    {
        return $this
            ->subject('PropertyStreamLink - new task added')
            ->view('emails.newtaskadded');
    }

}
