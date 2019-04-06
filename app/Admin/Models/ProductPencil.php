<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPencil extends Model
{
    use SoftDeletes;

    protected $table = 'product_pencil';

    public function images()
    {
        return $this->hasMany(ImagePencil::class, 'product_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(FilePencil::class, 'product_id', 'id');
    }
}