<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name','surname', 'email','other_email', 'password', 'profile_pic', 'google_id','teamwork_id','confirmation_code','confirmed'
    ];

    protected $hidden = [
        'password', 'remember_token','admin',
    ];

    public function isAdmin()
    {
// checks if user has an admin column and returns it
        return $this->admin;
    }

}
