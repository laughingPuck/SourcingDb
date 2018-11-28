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

class ImageStickwcupController extends Controller
{
    use HasResourceActions;

    public function index($id, AdminContent $content)
    {
        return $content
            ->header('Images Stick With Cup')
            ->description(' ')
            ->body($this->showImages($id));
    }

    protected function showImages($id)
    {
        $images = DB::table('image_stickwcup')->where('product_id', $id)->whereNull('deleted_at')->get();
        $box = new ToolBox('ID:'.$id, view('admin.product_image_gallery', ['imageList' => $images]));
        $box->style('default');

        $box->addTool('<a href="/stick_w_cup/'.$id.'" style="margin-right: 10px;" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i>&nbsp;&nbsp;Return to detail</a>');
        $box->addTool('<a href="/stick_w_cup" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;&nbsp;Return to list</a>');

        return $box;
    }
}