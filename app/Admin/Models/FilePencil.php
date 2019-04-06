<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FilePencil extends Model
{
    use SoftDeletes;

    protected $table = 'file_pencil';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}
