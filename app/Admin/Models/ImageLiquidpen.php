<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageLiquidpen extends Model
{
    use SoftDeletes;

    protected $table = 'image_liquidpen';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}