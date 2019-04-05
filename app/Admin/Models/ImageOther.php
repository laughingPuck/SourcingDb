<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageOther extends Model
{
    use SoftDeletes;

    protected $table = 'image_other';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}