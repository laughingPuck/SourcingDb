<?php
/**
 * Created by PhpStorm.
 * User: taylorfeng
 * Date: 2019/1/31
 * Time: 18:11
 */

namespace App\Admin\Controllers;

use App\Admin\Models\ProductEmailLog;
use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Show;
use App\Admin\Widgets\AdminContent;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\HasResourceActions;

class ProductEmailLogController extends Controller
{
    use HasResourceActions;

    public function index(AdminContent $content)
    {
        return $content
            ->header('Admin > Product Email Log')
            ->description(' ')
            ->body($this->grid()->render());
    }

    public function grid()
    {
        $grid = new Grid(new ProductEmailLog());

        $grid->username('Username');
        $grid->product_cate('Product Category');
        $grid->product_id('Product Id');
        $grid->to_email('Email Address');
        $grid->created_at('Created At');

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->between('created_at', 'Created At')->datetime();
            $filter->like('username', 'Username');
        });

        $grid->expandFilter();
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableActions();

        return $grid;
    }
}