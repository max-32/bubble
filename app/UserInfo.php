<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserInfo extends Authenticatable
{
    use Notifiable;

    /**
     * Table primary key
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'users_info';

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
