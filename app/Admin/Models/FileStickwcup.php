<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileStickwcup extends Model
{
    use SoftDeletes;

    protected $table = 'file_stickwcup';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}
