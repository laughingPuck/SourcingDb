<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageCompactpalette extends Model
{
    use SoftDeletes;

    protected $table = 'image_compactpalette';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}
