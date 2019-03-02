<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageBrush extends Model
{
    use SoftDeletes;

    protected $table = 'image_brush';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}