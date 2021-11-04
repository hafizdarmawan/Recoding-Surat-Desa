<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'photo', 'sin', 'name', 'password', 'birth_place', 'birth_date', 'gender', 'address', 'religion', 'marital_status', 'profession'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'birth_date' => 'date',
    ];

    public function getShortGender()
    {
        return substr($this->gender, 0, 1);
    }

    public function getPsb()
    {
        return $this->birth_place . ', ' . $this->birth_date->formatLocalized('%d %B %Y');
    }
}
