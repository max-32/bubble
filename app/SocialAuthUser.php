<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SocialAuthUser extends Authenticatable
{
    use Notifiable;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'social_auth_users_registered';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
