<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageBottle extends Model
{
    use SoftDeletes;

    protected $table = 'image_bottle';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}