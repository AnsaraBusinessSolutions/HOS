<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Hos3pl extends Authenticatable
{
    use Notifiable;

        protected $guard = 'hos3pl';
        protected $table = 'users';

        protected $fillable = [
            'name', 'email', 'password','last_login_at',
        ];

        protected $hidden = [
            'password', 'remember_token',
        ];
}
