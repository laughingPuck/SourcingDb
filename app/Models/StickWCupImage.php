<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StickWCupImage extends Model
{
    use Notifiable;

     /*Table name*/
    protected $table = 'stickwcup_image';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image_id', 'description', 'id',
        'created_at', 'updated_at',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
