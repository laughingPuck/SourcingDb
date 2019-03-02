<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageTube extends Model
{
    use SoftDeletes;

    protected $table = 'image_tube';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}