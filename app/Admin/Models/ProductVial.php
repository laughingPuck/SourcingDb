<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVial extends Model
{
    use SoftDeletes;

    protected $table = 'product_vial';

    public function images()
    {
        return $this->hasMany(ImageVial::class, 'product_id', 'id');
    }
}
