<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

class Role
{
    use Notifiable;

     /*Table name*/
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'role_name', 'role_access', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
