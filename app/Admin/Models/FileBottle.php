<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileBottle extends Model
{
    use SoftDeletes;

    protected $table = 'file_bottle';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}
