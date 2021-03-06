<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStickwcup extends Model
{
    use SoftDeletes;

    protected $table = 'product_stickwcup';

    public function images()
    {
        return $this->hasMany(ImageStickwcup::class, 'product_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(FileStickwcup::class, 'product_id', 'id');
    }
}
