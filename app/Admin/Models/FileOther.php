<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileOther extends Model
{
    use SoftDeletes;

    protected $table = 'file_other';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}
