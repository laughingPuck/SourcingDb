<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageStickwcup extends Model
{
    use SoftDeletes;

    protected $table = 'image_stickwcup';

    protected $fillable = ['title', 'desc', 'url'];
}
