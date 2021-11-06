<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Admin extends Model
{
    use Notifiable;

    protected $fillable = ['photo', 'name', 'username', 'password'];

    protected $hidden = ['password', 'remember_token'];
}
