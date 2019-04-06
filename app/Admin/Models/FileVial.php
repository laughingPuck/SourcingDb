<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileVial extends Model
{
    use SoftDeletes;

    protected $table = 'file_vial';

    protected $fillable = ['title', 'desc', 'url', 'state'];
}
