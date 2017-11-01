<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'description','payment_id','status','amount','user_mandate','user_customer', 'fk_client_task', 'fk_user'
    ];
}
