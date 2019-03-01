<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageStick extends Model
{
    use SoftDeletes;

    protected $table = 'image_stick';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}