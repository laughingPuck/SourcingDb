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
            ->header('Product Stick With Cup')
            ->description(' ')
            ->body($this->grid()->render());
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('Product Stick With Cup Edit')
            ->description(' ')
            ->body($this->form()->edit($id));
    }

    public function create(Content $content)
    {
        return $content
            ->header('Product Stick With Cup Create')
            ->description('')
            ->body($this->form());
    }

    public function show($id, Content $content)
    {
        return $content
            ->header('Product Stick With Cup Detail')
            ->description(' ')
            ->row($this->detail($id))
            ->row($this->showImages($id));
    }

    public function grid()
    {
        $grid = new Grid(new ProductStickwcup());

//        $grid->id('ID')->sortable();
        $grid->manufactory_name('Manufactory Name');
        $grid->material('Material');
        $grid->shape('Shape');
        $grid->style('Style');
        $grid->mechanism('Mechanism');
        $grid->overall_height('Overall Height');
        $grid->overall_width('Overall Width');
        $grid->images('Images')->display(function ($images) {
            $count = count($images);
            return "<span class='label label-primary'>{$count}</span>";
        });

//        $grid->state('Display')->display(function ($type) {
//            return $type ? 'on' : 'off';
//        });
        $grid->created_at('Created At');
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
            $filter->between('created_at', 'Created At')->datetime();
            $filter->between('overall_height', 'Overall Height');
            $filter->between('overall_width', 'Overall Width');
            $filter->equal('material', 'Material')->select(self::$materialMap);
            $filter->equal('shape', 'Shape')->select(self::$shapeMap);
            $filter->equal('style', 'Style')->select(self::$styleMap);
            $filter->equal('cup', 'Cup')->select(self::$cupMap);
            $filter->equal('mechanism', 'Mechanism')->select(self::$mechanismMap);
            $filter->where(function ($query) {
                switch ($this->input) {
                    case '1':
                        $query->has('images');
                        break;
                    case '0':
                        $query->doesntHave('images');
                        break;
                }
            }, 'Has Images')->select([
                '1' => 'Only with images',
                '0' => 'Only without images',
            ]);
        });

        $grid->expandFilter();

        return $grid;
    }

    public function form()
    {
        $form = new Form(new ProductStickwcup());

        $form->display('id', 'ID');

        $form->text('cosmopak_item', 'Cosmopak Item')->rules('required')->required();
        $form->text('vendor_item', 'Vendor Item')->rules('required')->required();
        $form->text('manufactory_name', 'Manufactory Name')->rules('required')->required();
        $form->text('item_description', 'Item Description')->rules('required')->required();
        $form->select('material', 'Material')->options(self::$materialMap)->rules('required')->required()->setWidth(3);
        $form->select('shape', 'Shape')->options(self::$shapeMap)->rules('required')->required()->setWidth(3);
        $form->select('style', 'Style')->options(self::$styleMap)->rules('required')->required()->setWidth(3);
        $form->select('cup', 'Cup')->options(self::$cupMap)->rules('required')->required()->setWidth(3);
        $form->text('cup_size', 'Cup Size')->rules('required')->required()->setWidth(2);
        $form->text('cover_material', 'Cover Material')->rules('required')->required();
        $form->text('overall_length', 'Overall Length')->rules('required')->required()->setWidth(2);
        $form->text('overall_width', 'Overall Width')->rules('required')->required()->setWidth(2);
        $form->text('overall_height', 'Overall Height')->rules('required')->required()->setWidth(2);
        $form->select('mechanism', 'Mechanism')->options(self::$mechanismMap)->rules('required')->required()->setWidth(3);
        $form->text('storage_location', 'Storage Location')->rules('required')->required();
        $form->text('sample_available', 'Sample Available')->rules('required')->required();
        $form->text('related_projects', 'Related Projects')->rules('required')->required();
        $form->text('moq', 'Moq')->rules('required')->required();
        $form->text('price', 'Price')->rules('required')->required()->setWidth(2);
        $form->text('mold_status', 'Mold Status')->rules('required')->required();
        $form->switch('state', 'Display')->value(1)->required();

        $form->hasMany('images', function (Form\NestedForm $form) {
            $form->image('url', 'Image');
            $form->text('Title');
            $form->text('Desc');
        });

        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');

        return $form;
    }

    protected function detail($id)
    {
        $show = new Show(ProductStickwcup::findOrFail($id));

        $imagesNum = DB::table('image_stickwcup')->where('product_id', $id)->whereNull('deleted_at')->count();

        $show->panel()->tools(function (\Encore\Admin\Show\Tools $tools) use ($imagesNum, $id) {
            if ($imagesNum) {
                $tools->append('<a href="/stick_w_cup/image/'.$id.'" class="btn btn-sm btn-success" style="width: 150px;margin-right: 5px;"><i class="fa fa-image"></i>&emsp;Check&nbsp;'.$imagesNum.'&nbsp;images</a>');
            } else {
                $tools->append('<button type="button" class="btn btn-sm btn-default" disabled="disabled" style="width: 100px;margin-right: 5px;"><i class="fa fa-image"></i>&emsp;No&nbsp;&nbsp;image</button>');
            }
        });

        $show->id('ID');
        $show->cosmopak_item('Cosmopak Item');
        $show->vendor_item('Vendor Item');
        $show->manufactory_name('Manufactory Name');
        $show->item_description('Item Description');
        $show->material('Material');
        $show->shape('Shape');
        $show->style('Style');
        $show->cup('Cup');
        $show->cup_size('Cup Size');
        $show->cover_material('Cover Material');
        $show->overall_length('Overall Height');
        $show->overall_width('Overall Width');
        $show->overall_height('Overall Height');
        $show->mechanism('Mechanism');
        $show->storage_location('Storage Location');
        $show->sample_available('Sample Available');
        $show->related_projects('Related Projects');
        $show->moq('Moq');
        $show->price('Price');
        $show->mold_status('Mold Status');
        $show->state('State');

        $show->created_at('Created At');
        $show->updated_at('Updated At');

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