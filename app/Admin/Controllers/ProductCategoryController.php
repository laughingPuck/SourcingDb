<?php

namespace App\Admin\Controllers;

use App\Admin\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Show;
use App\Admin\Widgets\AdminContent;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\HasResourceActions;

class ProductCategoryController extends Controller
{
    use HasResourceActions;

    public function index(AdminContent $content)
    {
        return $content
            ->header('Product Category List')
            ->body($this->grid()->render());
    }

    public function edit($id, AdminContent $content)
    {
        return $content
            ->header('Product Category Edit')
            ->body($this->form()->edit($id));
    }

    public function create(AdminContent $content)
    {
        return $content
            ->header('Product Category Create')
            ->body($this->form());
    }

    public function show($id, AdminContent $content)
    {
        return $content
            ->header('Product Category Detail')
            ->description(' ')
            ->row($this->detail($id));
    }

    public function attributeEdit($id, AdminContent $content)
    {
        return $content
            ->header('Product Category Attribute Edit')
            ->body($this->form()->edit($id));
    }

    public function grid()
    {
        $grid = new Grid(new ProductCategory());

        $grid->cover_image('Cover Image')->display(function ($coverImage) {
            if ($coverImage ) {
                return "<img src='/{$coverImage}' alt='{$this->cate_name}' style='height: 50px;' />";
            } else {
                return null;
            }
        });
        $grid->cate_name('Category Name');
        $grid->link('Link');
        $grid->created_at('Created At');

        $grid->actions(function (Grid\Displayers\Actions $actions) {
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('cate_name', 'Category Name');
        });

        $grid->expandFilter();

        return $grid;
    }

    public function form()
    {
        $form = new Form(new ProductCategory());

        $form->display('id', 'ID');

        $form->text('cate_name', 'Category Name')->rules('required')->required();
        $form->textarea('cate_desc', 'Category Desc');
        $form->image('cover_image', 'Cover Image');
//        $form->cropper('cover_image','Cover Image')->cRatio(300, 200);
        $form->text('link', 'Link');

        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');

        return $form;
    }

    public function attributesForm()
    {
        $form = new Form(new ProductCategory());

        $form->hasMany('attributes', function (Form\NestedForm $form) {
            $form->image('url', 'Image');
            $form->text('Title');
            $form->text('Desc');
        });

        return $form;
    }

    protected function detail($id)
    {
        $show = new Show(ProductCategory::findOrFail($id));

        $show->id('ID');
        $show->cate_name('Category Name');
        $show->cate_desc('Category Desc');
        $show->cover_image()->image();
        $show->link('Link');

        $show->created_at('Created At');
        $show->updated_at('Updated At');

        return $show;
    }
}