<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductJarpot extends Model
{
    use SoftDeletes;

    protected $table = 'product_jarpot';

    public function images()
    {
        return $this->hasMany(ImageJarpot::class, 'product_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(FileJarpot::class, 'product_id', 'id');
    }
}