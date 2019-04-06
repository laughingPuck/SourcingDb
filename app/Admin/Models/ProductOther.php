<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOther extends Model
{
    use SoftDeletes;

    protected $table = 'product_other';

    public function images()
    {
        return $this->hasMany(ImageOther::class, 'product_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(FileOther::class, 'product_id', 'id');
    }
}