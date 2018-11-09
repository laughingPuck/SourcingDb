<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StickWCup extends Model
{
    use Notifiable;

     /*Table name*/
    protected $table = 'stickwcup';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', '', 'cosmopak_item', 'vendor_item', 'manufactory_name', 'item_description', 'material', 'shape',
        'style', 'cup', 'cup_size', 'cover_material', 'overall_length', 'overall_width', 'overall_height',
        'mechanism', 'storage_location', 'sample_available', 'related_projects', 'moq', 'price', 'mold_status',
        'created_at', 'updated_at',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
