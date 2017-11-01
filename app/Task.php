<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'task_id', 'description', 'content', 'creatorFirstName',
        'creatorLastName','responsible_party_ids',
        'is_client_responsible', 'fk_user', 'fk_project','tags','fk_project','visible','completed','parentTaskId'
    ];

}
