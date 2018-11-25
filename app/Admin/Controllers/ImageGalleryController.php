<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Show;
use App\Admin\Widgets\AdminContent;
use App\Admin\Widgets\ToolBox;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\HasResourceActions;
use Illuminate\Support\Facades\DB;

class ImageGalleryController extends Controller
{
    use HasResourceActions;

    const PRODUCT_STICK_WITH_CUP = 'stick_with_cup';
    const PRODUCT_VIAL = 'vial';
    const PRODUCT_COMPACT_PALETTE = 'compact_palette';

    public static $productCateMap = [
        self::PRODUCT_STICK_WITH_CUP => ['display' => 'Stick With Cup', 'table' => 'image_stickwcup', 'uri' => 'stick_with_cup'],
        self::PRODUCT_VIAL => ['display' => 'Vial', 'table' => 'image_vial', 'uri' => 'vial'],
        self::PRODUCT_COMPACT_PALETTE => ['display' => 'Compact & Palette', 'table' => 'image_compactpalette', 'uri' => 'compact_palette'],
    ];

    public function index($cate, $id, AdminContent $content)
    {
        if (!array_key_exists($cate, self::$productCateMap)) {
            $body = 'no such product category';
            $title = ' ';
        } else {
            $title = "Images ".self::$productCateMap[$cate]['display'];
            $body = $this->showImages($cate, $id);
        }

        return $content
            ->header($title)
            ->description(' ')
            ->body($body);
    }

    protected function showImages($cate, $id)
    {
        $table = self::$productCateMap[$cate]['table'];
        $images = DB::table($table)->where('product_id', $id)->whereNull('deleted_at')->get();
        $box = new ToolBox('ID:'.$id, view('admin.productImageGallery', ['imageList' => $images]));
        $box->style('default');

        $box->addTool("<a href='/{$cate}/{$id}' style='margin-right: 10px;' class='btn btn-sm btn-primary'><i class='fa fa-eye'></i>&nbsp;&nbsp;Return to detail</a>");
        $box->addTool("<a href='/{$cate}' class='btn btn-sm btn-default'><i class='fa fa-list'></i>&nbsp;&nbsp;Return to list</a>");

        return $box;
    }
}