<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileTube extends Model
{
    use SoftDeletes;

    protected $table = 'file_tube';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}
