<?php
namespace App\Admin\Conf;

use App\Admin\Models\ProductBottle;
use App\Admin\Models\ProductBrush;
use App\Admin\Models\ProductCompactpalette;
use App\Admin\Models\ProductJarpot;
use App\Admin\Models\ProductStick;
use App\Admin\Models\ProductStickwcup;
use App\Admin\Models\ProductVial;

class Products
{
    const PRODUCT_STICK_WITH_CUP = 'stick_with_cup';
    const PRODUCT_VIAL = 'vial';
    const PRODUCT_COMPACT_PALETTE = 'compact_palette';
    const PRODUCT_BOTTLE = 'bottle';
    const PRODUCT_JAR_POT = 'jar_pot';
    const PRODUCT_STICK = 'stick';
    const PRODUCT_BRUSH = 'brush';

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
        self::PRODUCT_BOTTLE => [
            'display' => 'Bottle',
            'img_table' => 'image_bottle',
            'product_table' => 'product_bottle',
            'uri' => 'bottle',
            'model' => ProductBottle::class,
            'id' => 4
        ],
        self::PRODUCT_JAR_POT => [
            'display' => 'Jar & Pot',
            'img_table' => 'image_jarpot',
            'product_table' => 'product_jarpot',
            'uri' => 'jar_pot',
            'model' => ProductJarpot::class,
            'id' => 5
        ],
        self::PRODUCT_STICK => [
            'display' => 'Stick',
            'img_table' => 'image_stick',
            'product_table' => 'product_stick',
            'uri' => 'stick',
            'model' => ProductStick::class,
            'id' => 6
        ],
        self::PRODUCT_BRUSH => [
            'display' => 'Brush',
            'img_table' => 'image_brush',
            'product_table' => 'product_brush',
            'uri' => 'brush',
            'model' => ProductBrush::class,
            'id' => 7
        ],
    ];

    public static $switchMap = [
        '1' => 'Yes',
        '0' => 'No',
        '-1' => 'Not sure',
    ];
}