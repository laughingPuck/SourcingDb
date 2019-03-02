<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductLiquidpen extends Model
{
    use SoftDeletes;

    protected $table = 'product_liquidpen';

    public function images()
    {
        return $this->hasMany(ImageLiquidpen::class, 'product_id', 'id');
    }
}