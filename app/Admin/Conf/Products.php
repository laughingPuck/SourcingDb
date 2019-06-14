<?php
namespace App\Admin\Conf;

use App\Admin\Models\ProductBottle;
use App\Admin\Models\ProductBrush;
use App\Admin\Models\ProductCompactpalette;
use App\Admin\Models\ProductJarpot;
use App\Admin\Models\ProductLiquidpen;
use App\Admin\Models\ProductPencil;
use App\Admin\Models\ProductStick;
use App\Admin\Models\ProductStickwcup;
use App\Admin\Models\ProductTube;
use App\Admin\Models\ProductVial;

class Products
{
    const PRODUCT_STICK_WITH_CUP = 'stick_with_cup';
    const PRODUCT_VIAL = 'vials';
    const PRODUCT_COMPACT_PALETTE = 'compacts_palettes';
    const PRODUCT_BOTTLE = 'bottles';
    const PRODUCT_JAR_POT = 'jars_pots';
    const PRODUCT_STICK = 'solid_formula_sticks';
    const PRODUCT_BRUSH = 'brushes';
    const PRODUCT_LIQUID_PEN = 'liquid_formula_pens';
    const PRODUCT_PENCIL = 'pencils';
    const PRODUCT_TUBE = 'tubes';
    const PRODUCT_OTHER = 'others';

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
            'display' => 'Vials',
            'img_table' => 'image_vial',
            'file_table' => 'file_vial',
            'product_table' => 'product_vial',
            'uri' => 'vials',
            'model' => ProductVial::class,
            'id' => 2
        ],
        self::PRODUCT_COMPACT_PALETTE => [
            'display' => 'Compacts & Palettes',
            'img_table' => 'image_compactpalette',
            'file_table' => 'file_compactpalette',
            'product_table' => 'product_compactpalette',
            'uri' => 'compacts_palettes',
            'model' => ProductCompactpalette::class,
            'id' => 3
        ],
        self::PRODUCT_BOTTLE => [
            'display' => 'Bottles',
            'img_table' => 'image_bottle',
            'file_table' => 'file_bottle',
            'product_table' => 'product_bottle',
            'uri' => 'bottles',
            'model' => ProductBottle::class,
            'id' => 4
        ],
        self::PRODUCT_JAR_POT => [
            'display' => 'Jars & Pots',
            'img_table' => 'image_jarpot',
            'file_table' => 'file_jarpot',
            'product_table' => 'product_jarpot',
            'uri' => 'jars_pots',
            'model' => ProductJarpot::class,
            'id' => 5
        ],
        self::PRODUCT_STICK => [
            'display' => 'Solid Formula Sticks',
            'img_table' => 'image_stick',
            'file_table' => 'file_stick',
            'product_table' => 'product_stick',
            'uri' => 'solid_formula_sticks',
            'model' => ProductStick::class,
            'id' => 6
        ],
        self::PRODUCT_BRUSH => [
            'display' => 'Brushes',
            'img_table' => 'image_brush',
            'file_table' => 'file_brush',
            'product_table' => 'product_brush',
            'uri' => 'brushes',
            'model' => ProductBrush::class,
            'id' => 7
        ],
        self::PRODUCT_LIQUID_PEN => [
            'display' => 'Liquid Formula Pens',
            'img_table' => 'image_liquidpen',
            'file_table' => 'file_liquidpen',
            'product_table' => 'product_liquidpen',
            'uri' => 'liquid_formula_pens',
            'model' => ProductLiquidpen::class,
            'id' => 8
        ],
        self::PRODUCT_PENCIL => [
            'display' => 'Pencils',
            'img_table' => 'image_pencil',
            'file_table' => 'file_pencil',
            'product_table' => 'product_pencil',
            'uri' => 'pencils',
            'model' => ProductPencil::class,
            'id' => 9
        ],
        self::PRODUCT_TUBE => [
            'display' => 'Tubes',
            'img_table' => 'image_tube',
            'file_table' => 'file_tube',
            'product_table' => 'product_tube',
            'uri' => 'tubes',
            'model' => ProductTube::class,
            'id' => 10
        ],
        self::PRODUCT_OTHER => [
            'display' => 'Others',
            'img_table' => 'image_other',
            'file_table' => 'file_other',
            'product_table' => 'product_other',
            'uri' => 'others',
            'model' => ProductTube::class,
            'id' => 11
        ]
    ];

    public static $switchMap = [
        '1' => 'Yes',
        '0' => 'No',
        '-1' => 'Not sure',
    ];
}