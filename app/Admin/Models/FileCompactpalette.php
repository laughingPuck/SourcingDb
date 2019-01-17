<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileCompactpalette extends Model
{
    use SoftDeletes;

    protected $table = 'file_compactpalette';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}
