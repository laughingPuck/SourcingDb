<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileJarpot extends Model
{
    use SoftDeletes;

    protected $table = 'file_jarpot';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}
