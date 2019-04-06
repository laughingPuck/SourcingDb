<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBrush extends Model
{
    use SoftDeletes;

    protected $table = 'product_brush';

    public function images()
    {
        return $this->hasMany(ImageBrush::class, 'product_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(FileBrush::class, 'product_id', 'id');
    }
}