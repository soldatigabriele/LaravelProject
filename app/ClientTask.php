<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientTask extends Model
{
    protected $fillable = [
        'content',
        'description',
        'instructions',
        'amount',
        'active',
        'completed',
        'other_task',
        'visible',
        'fk_task',
        'fk_tasklist',
        'fk_user',
        'fk_project',
        'fk_tag',
        'url'
    ];
}