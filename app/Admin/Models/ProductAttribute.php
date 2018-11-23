<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use SoftDeletes;

    const FORM_TYPE_TXT = 1;
    const FORM_TYPE_NUM = 2;
    const FORM_TYPE_AREA = 3;
    const FORM_TYPE_IMG = 4;
    const FORM_TYPE_SELECT = 5;
    const FORM_TYPE_MULTI_SELECT = 6;
    const FORM_TYPE_SWITCH = 7;
    const FORM_TYPE_COLOR = 8;

    public static $formTypeMap = [
        self::FORM_TYPE_TXT => 'Text',
        self::FORM_TYPE_NUM => 'Number',
        self::FORM_TYPE_AREA => 'Text area',
        self::FORM_TYPE_IMG => 'Image',
        self::FORM_TYPE_SELECT => 'Select',
        self::FORM_TYPE_MULTI_SELECT => 'Multi select',
        self::FORM_TYPE_SWITCH => 'Switch',
        self::FORM_TYPE_COLOR => 'Color'
    ];

    const DATA_TYPE_TXT = 1;
    const DATA_TYPE_NUM = 2;
    const DATA_TYPE_AREA = 3;

    public static $dataTypeMap = [
        self::DATA_TYPE_TXT => 'String',
        self::DATA_TYPE_NUM => 'Decimal',
        self::DATA_TYPE_AREA => 'Int',
    ];

    protected $fillable = ['attribute_name', 'attribute_desc', 'form_type', 'data_type', 'options', 'default'];
}
