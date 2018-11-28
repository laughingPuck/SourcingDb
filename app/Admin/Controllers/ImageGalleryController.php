<?php

namespace App\Admin\Controllers;

use App\Admin\Conf\Products;
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

    public function index($cate, $id, AdminContent $content)
    {
        if (!array_key_exists($cate, Products::$productCateMap)) {
            $body = 'no such product';
            $title = ' ';
        } else {
            $title = "Images ".Products::$productCateMap[$cate]['display'];
            $body = $this->showImages($cate, $id);
        }

        return $content
            ->header($title)
            ->description(' ')
            ->body($body);
    }

    protected function showImages($cate, $id)
    {
        $table = Products::$productCateMap[$cate]['img_table'];
        $images = DB::table($table)->where('product_id', $id)->whereNull('deleted_at')->get();
        $box = new ToolBox('ID:'.$id, view('admin.product_image_gallery', ['imageList' => $images]));
        $box->style('default');

        $box->addTool("<a href='/".config('admin.route.prefix')."/{$cate}/{$id}' style='margin-right: 10px;' class='btn btn-sm btn-primary'><i class='fa fa-eye'></i>&nbsp;&nbsp;Return to detail</a>");
        $box->addTool("<a href='/".config('admin.route.prefix')."/{$cate}' class='btn btn-sm btn-default'><i class='fa fa-list'></i>&nbsp;&nbsp;Return to list</a>");

        return $box;
    }
}