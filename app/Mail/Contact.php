<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Project;
use App\User;

class Contact extends Mailable
{
    use Queueable, SerializesModels;
    public $task,$user,$project;

//   TODO DISABLE LESS SECURE APPS from Gmail account
    public function __construct($task_content,$user_id,$project_id)
    {
        $this->project['name'] = Project::where('project_id',$project_id)->first()->project_name;
        $this->task = $task_content;
        $user = User::find($user_id);
        $this->user['name'] = $user->name;
        $this->user['id'] = $user->id;
        $this->user['email']= $user->email;
        $this->project['id'] = $project_id;
    }

    public function build()
    {
        return $this
            ->subject($this->user['name'].' has completed a task')
            ->view('emails.contact');
    }

}
