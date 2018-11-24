<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCompactpalette extends Model
{
    use SoftDeletes;

    protected $table = 'product_compactpalette';

    public function images()
    {
        return $this->hasMany(ImageCompactpalette::class, 'product_id', 'id');
    }
}
