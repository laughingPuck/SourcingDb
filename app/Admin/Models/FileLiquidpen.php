<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileLiquidpen extends Model
{
    use SoftDeletes;

    protected $table = 'file_liquidpen';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}
