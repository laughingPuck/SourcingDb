<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStick extends Model
{
    use SoftDeletes;

    protected $table = 'product_stick';

    public function images()
    {
        return $this->hasMany(ImageStick::class, 'product_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(FileStick::class, 'product_id', 'id');
    }
}