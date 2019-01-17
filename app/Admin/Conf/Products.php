<?php
namespace App\Admin\Conf;

use App\Admin\Models\ProductCompactpalette;
use App\Admin\Models\ProductStickwcup;
use App\Admin\Models\ProductVial;

class Products
{
    const PRODUCT_STICK_WITH_CUP = 'stick_with_cup';
    const PRODUCT_VIAL = 'vial';
    const PRODUCT_COMPACT_PALETTE = 'compact_palette';

    public static $productCateMap = [
        self::PRODUCT_STICK_WITH_CUP => [
            'display' => 'Stick With Cup',
            'img_table' => 'image_stickwcup',
            'file_table' => 'file_stickwcup',
            'product_table' => 'product_stickwcup',
            'uri' => 'stick_with_cup',
            'model' => ProductStickwcup::class,
            'id' => 1
        ],
        self::PRODUCT_VIAL => [
            'display' => 'Vial',
            'img_table' => 'image_vial',
            'file_table' => 'file_vial',
            'product_table' => 'product_vial',
            'uri' => 'vial',
            'model' => ProductVial::class,
            'id' => 2
        ],
        self::PRODUCT_COMPACT_PALETTE => [
            'display' => 'Compact & Palette',
            'img_table' => 'image_compactpalette',
            'file_table' => 'file_compactpalette',
            'product_table' => 'product_compactpalette',
            'uri' => 'compact_palette',
            'model' => ProductCompactpalette::class,
            'id' => 3
        ],
    ];

    public static $switchMap = [
        'Yes' => 'Yes',
        'No' => 'No',
        'Not sure' => 'Not sure',
    ];
}