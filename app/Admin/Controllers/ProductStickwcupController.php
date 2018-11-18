<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\HasResourceActions;
use App\ProductStickwcup;
use Illuminate\Support\Facades\DB;

class ProductStickwcupController extends Controller
{
    use HasResourceActions;

    const MATERIAL_TYPE_PLASTIC = 1;

    public static $materialMap = [
        'Plastic' => 'Plastic',
        'Alumium' => 'Alumium',
    ];
    public static $shapeMap = [
        'Round' => 'Round',
        'Square' => 'Square',
        'Oval' => 'Oval',
        'Other' => 'Other',
    ];
    public static $styleMap = [
        'Twist' => 'Twist',
        'Push up' => 'Push up',
        'Deodorant' => 'Deodorant',
    ];
    public static $cupMap = [
        'Cup#' => 'Cup#',
        '1' => '1',
        '2+' => '2+',
        'Not sure' => 'Not sure',
    ];
    public static $mechanismMap = [
        'Repel' => 'Repel',
        'Push up' => 'Push up',
        'Repel/Propel' => 'Repel/Propel',
        'Other' => 'Other',
        'Not sure' => 'Not sure',
    ];

    public function index(Content $content)
    {
        return $content
            ->header('Product Stick w cup')
            ->description(' ')
            ->body($this->grid()->render());
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('Product Stick w cup Edit')
            ->description(' ')
            ->body($this->form()->edit($id));
    }

    public function create(Content $content)
    {
        return $content
            ->header('Product Stick w cup Create')
            ->description('')
            ->body($this->form());
    }

    public function show($id, Content $content)
    {
        return $content
            ->header('Product Stick w cup Detail')
            ->description(' ')
            ->row($this->detail($id))
            ->row($this->showImages($id));
    }

    public function grid()
    {
        $grid = new Grid(new ProductStickwcup());

//        $grid->id('ID')->sortable();
        $grid->manufactory_name('ManufactoryName');
        $grid->material('Material');
        $grid->shape('Shape');
        $grid->style('Style');
        $grid->mechanism('Mechanism');
        $grid->overall_height('Overall height');
        $grid->overall_width('Overall width');
//        $grid->state('Display')->display(function ($type) {
//            return $type ? 'on' : 'off';
//        });
        $grid->created_at('Created');
//        $grid->updated_at('Updated');

        $grid->actions(function (Grid\Displayers\Actions $actions) {
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->between('created_at', 'Created Time')->datetime();
            $filter->between('overall_height', 'Overall height');
            $filter->between('overall_width', 'Overall width');
            $filter->equal('material')->select(self::$materialMap);
            $filter->equal('shape')->select(self::$shapeMap);
            $filter->equal('style')->select(self::$styleMap);
            $filter->equal('cup')->select(self::$cupMap);
            $filter->equal('mechanism')->select(self::$mechanismMap);
        });

        $grid->expandFilter();

        return $grid;
    }

    public function form()
    {
        $form = new Form(new ProductStickwcup());

        $form->display('id', 'ID');

        $form->text('cosmopak_item')->rules('required')->required();
        $form->text('vendor_item')->rules('required')->required();
        $form->text('manufactory_name')->rules('required')->required();
        $form->text('item_description')->rules('required')->required();
        $form->select('material')->options(self::$materialMap)->rules('required')->required()->setWidth(3);
        $form->select('shape')->options(self::$shapeMap)->rules('required')->required()->setWidth(3);
        $form->select('style')->options(self::$styleMap)->rules('required')->required()->setWidth(3);
        $form->select('cup')->options(self::$cupMap)->rules('required')->required()->setWidth(3);
        $form->text('cup_size')->rules('required')->required()->setWidth(2);
        $form->text('cover_material')->rules('required')->required();
        $form->text('overall_length')->rules('required')->required()->setWidth(2);
        $form->text('overall_width')->rules('required')->required()->setWidth(2);
        $form->text('overall_height')->rules('required')->required()->setWidth(2);
        $form->select('mechanism')->options(self::$mechanismMap)->rules('required')->required()->setWidth(3);
        $form->text('storage_location')->rules('required')->required();
        $form->text('sample_available')->rules('required')->required();
        $form->text('related_projects')->rules('required')->required();
        $form->text('moq')->rules('required')->required();
        $form->text('price')->rules('required')->required()->setWidth(2);
        $form->text('mold_status')->rules('required')->required();
        $form->switch('state', 'display')->value(1)->required();

        $form->hasMany('images', function (Form\NestedForm $form) {
            $form->image('url', 'image');
            $form->text('title');
            $form->text('desc');
        });

        $form->display('created_at', 'Created');
        $form->display('updated_at', 'Updated');

        return $form;
    }

    protected function detail($id)
    {
        $show = new Show(ProductStickwcup::findOrFail($id));

        $show->id('id');
        $show->cosmopak_item('cosmopak_item');
        $show->vendor_item('vendor_item');
        $show->manufactory_name('manufactory_name');
        $show->item_description('item_description');
        $show->material('material');
        $show->shape('shape');
        $show->style('style');
        $show->cup('cup');
        $show->cup_size('cup_size');
        $show->cover_material('cover_material');
        $show->overall_length('overall_length');
        $show->overall_width('overall_width');
        $show->overall_height('overall_height');
        $show->mechanism('mechanism');
        $show->storage_location('storage_location');
        $show->sample_available('sample_available');
        $show->related_projects('related_projects');
        $show->moq('moq');
        $show->price('price');
        $show->mold_status('mold_status');
        $show->state();

        $show->created_at('Created');
        $show->updated_at('Updated');

        return $show;
    }

    protected function showImages($id)
    {
        $images = DB::table('image_stickwcup')->where('product_id', $id)->whereNull('deleted_at')->get();
        $box = new Box('Images', view('admin.productimages', ['imageList' => $images]));
        $box->style('default');

        return $box;
    }
}