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

class DocumentsController extends Controller
{
    use HasResourceActions;

    public function index($cate, $id, AdminContent $content)
    {
        if (!array_key_exists($cate, Products::$productCateMap)) {
            $body = 'no such product';
            $title = ' ';
        } else {
            $title = "Files ".Products::$productCateMap[$cate]['display'];
            $body = $this->showFiles($cate, $id);
        }

        return $content
            ->header($title)
            ->description(' ')
            ->body($body);
    }

    protected function showFiles($cate, $id)
    {
        $table = Products::$productCateMap[$cate]['file_table'];
        $files = DB::table($table)->where('product_id', $id)->whereNull('deleted_at')->get();
        $box = new ToolBox('ID:'.$id, view('admin.product_document', ['fileList' => $files, 'cate' => $cate, 'adminPrefix' => config('admin.route.prefix')]));
        $box->style('default');

        $box->addTool("<a href='/".config('admin.route.prefix')."/{$cate}/{$id}' style='margin-right: 10px;' class='btn btn-sm btn-primary'><i class='fa fa-eye'></i>&nbsp;&nbsp;Return to detail</a>");
        $box->addTool("<a href='/".config('admin.route.prefix')."/{$cate}' class='btn btn-sm btn-default'><i class='fa fa-list'></i>&nbsp;&nbsp;Return to list</a>");

        return $box;
    }
}