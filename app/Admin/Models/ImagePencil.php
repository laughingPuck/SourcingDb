<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImagePencil extends Model
{
    use SoftDeletes;

    protected $table = 'image_pencil';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}