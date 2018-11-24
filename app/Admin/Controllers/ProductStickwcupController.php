<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Show;
use App\Admin\Widgets\AdminContent;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\HasResourceActions;
use App\Admin\Models\ProductStickwcup;
use Illuminate\Support\Facades\DB;

class ProductStickwcupController extends Controller
{
    use HasResourceActions;

    const NAME = 'Stick With Cup';
    const URI = 'stick_with_cup';
    const IMAGE_TABLE = 'image_stickwcup';

    public static $productClassName = ProductStickwcup::class;

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

    public function index(AdminContent $content)
    {
        return $content
            ->header('Product '.self::NAME)
            ->description(' ')
            ->body($this->grid()->render());
    }

    public function edit($id, AdminContent $content)
    {
        return $content
            ->header('Product '.self::NAME.' Edit')
            ->description(' ')
            ->body($this->form()->edit($id));
    }

    public function create(AdminContent $content)
    {
        return $content
            ->header('Product '.self::NAME.' Create')
            ->description('')
            ->body($this->form());
    }

    public function show($id, AdminContent $content)
    {
        return $content
            ->header('Product '.self::NAME.' Detail')
            ->description(' ')
            ->row($this->detail($id))
            ->row($this->showImages($id));
    }

    public function grid()
    {
        $grid = new Grid(new self::$productClassName());

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
            if ($count) {
                return "<a href='/gallery/".self::URI."/{$this->id}' class='btn btn-xs btn-success'><i class='fa fa-image'></i>&nbsp;&nbsp;{$count}</a>";
            } else {
                return "<button type='button' disabled='disabled' class='btn btn-xs btn-default'><i class='fa fa-image'></i>&nbsp;&nbsp;{$count}</button>";
            }
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
        $form = new Form(new self::$productClassName());

        $form->display('id', 'ID');

        $form->text('cosmopak_item', 'Cosmopak Item')->rules('required');
        $form->text('vendor_item', 'Vendor Item')->rules('required');
        $form->text('manufactory_name', 'Manufactory Name')->rules('required');
        $form->text('item_description', 'Item Description')->rules('required');
        $form->divider();
        $form->select('material', 'Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('shape', 'Shape')->options(self::$shapeMap)->rules('required')->setWidth(4);
        $form->select('style', 'Style')->options(self::$styleMap)->rules('required')->setWidth(4);
        $form->select('cup', 'Cup')->options(self::$cupMap)->rules('required')->setWidth(4);
        $form->select('mechanism', 'Mechanism')->options(self::$mechanismMap)->rules('required')->setWidth(4);
        $form->divider();
        $form->text('cup_size', 'Cup Size')->rules('required|regex:/^\d+$/|max:1', ['regex' => 'The Price must be a number'])->setWidth(4);
        $form->text('cover_material', 'Cover Material')->rules('required');
        $form->text('overall_length', 'Overall Length')->rules('required')->setWidth(4);
        $form->text('overall_width', 'Overall Width')->rules('required')->setWidth(4);
        $form->text('overall_height', 'Overall Height')->rules('required')->setWidth(4);
        $form->text('storage_location', 'Storage Location')->rules('required');
        $form->text('sample_available', 'Sample Available')->rules('required');
        $form->text('related_projects', 'Related Projects')->rules('required');
        $form->text('moq', 'Moq')->rules('required');
        $form->text('price', 'Price')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Price must be a number'])->setWidth(4);
        $form->text('mold_status', 'Mold Status')->rules('required');
        $form->switch('state', 'Display')->value(1);

        $form->hasMany('images', function (Form\NestedForm $form) {
            $form->image('url', 'Image');
            $form->text('title', 'Title');
            $form->text('desc', 'Desc');
            $form->switch('state', 'Display')->value(1);
        });

        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');

        return $form;
    }

    protected function detail($id)
    {
        $productClass = self::$productClassName;
        $show = new Show($productClass::findOrFail($id));

        $imagesNum = DB::table(self::IMAGE_TABLE)->where('product_id', $id)->whereNull('deleted_at')->count();

        $show->panel()->tools(function (\Encore\Admin\Show\Tools $tools) use ($imagesNum, $id) {
            if ($imagesNum) {
                $tools->append('<a href="/gallery/'.self::URI.'/'.$id.'" class="btn btn-sm btn-success" style="width: 150px;margin-right: 5px;"><i class="fa fa-image"></i>&emsp;Check&nbsp;'.$imagesNum.'&nbsp;images</a>');
            } else {
                $tools->append('<button type="button" class="btn btn-sm btn-default" disabled="disabled" style="width: 100px;margin-right: 5px;"><i class="fa fa-image"></i>&emsp;No&nbsp;&nbsp;image</button>');
            }
        });

        $show->id('ID');
        $show->cosmopak_item('Cosmopak Item');
        $show->vendor_item('Vendor Item');
        $show->manufactory_name('Manufactory Name');
        $show->item_description('Item Description');
        $show->divider();
        $show->material('Material');
        $show->shape('Shape');
        $show->style('Style');
        $show->cup('Cup');
        $show->divider();
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
        $images = DB::table(self::IMAGE_TABLE)->where('product_id', $id)->whereNull('deleted_at')->get();
        $box = new Box('Images', view('admin.productimages', ['imageList' => $images]));
        $box->style('default');

        return $box;
    }
}