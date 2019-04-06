<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTube extends Model
{
    use SoftDeletes;

    protected $table = 'product_tube';

    public function images()
    {
        return $this->hasMany(ImageTube::class, 'product_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(FileTube::class, 'product_id', 'id');
    }
}