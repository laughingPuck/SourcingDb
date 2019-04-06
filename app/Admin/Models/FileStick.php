<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileStick extends Model
{
    use SoftDeletes;

    protected $table = 'file_stick';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}
