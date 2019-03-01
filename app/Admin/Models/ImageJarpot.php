<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageJarpot extends Model
{
    use SoftDeletes;

    protected $table = 'image_jarpot';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}