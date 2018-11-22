<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes;

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'cate_id', 'id');
    }
}
