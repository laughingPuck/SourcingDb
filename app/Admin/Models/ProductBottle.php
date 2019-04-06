<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBottle extends Model
{
    use SoftDeletes;

    protected $table = 'product_bottle';

    public function images()
    {
        return $this->hasMany(ImageBottle::class, 'product_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(FileBottle::class, 'product_id', 'id');
    }
}