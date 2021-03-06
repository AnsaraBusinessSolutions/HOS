<?php

namespace App;

    use Illuminate\Notifications\Notifiable;
    use Illuminate\Foundation\Auth\User as Authenticatable;

    class Custodian extends Authenticatable
    {
        use Notifiable;

        protected $guard = 'custodian';
        protected $table = 'users';

        protected $fillable = [
            'name', 'email', 'password','last_login_at',
        ];

        protected $hidden = [
            'password', 'remember_token',
        ];
    }
