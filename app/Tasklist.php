<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tasklist extends Model
{
    //
    protected $fillable = [
        'tasklist_id','tasklist_name','fk_user','fk_project','uncompleted'
    ];
}
