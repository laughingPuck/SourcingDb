<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileBrush extends Model
{
    use SoftDeletes;

    protected $table = 'file_brush';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}
