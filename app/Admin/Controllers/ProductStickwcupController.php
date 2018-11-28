<?php

namespace App\Admin\Controllers;

use App\Admin\Conf\Products;
use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Show;
use App\Admin\Widgets\AdminContent;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\HasResourceActions;
use App\Admin\Models\ProductStickwcup;
use Illuminate\Support\Facades\DB;
use App\Admin\Widgets\Action\DeleteRow;

class ProductStickwcupController extends Controller
{
    use HasResourceActions;

    const NAME = 'Stick With Cup';
    const TAG = Products::PRODUCT_STICK_WITH_CUP;
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
            ->header('Products > '.self::NAME)
            ->description(' ')
            ->row(view('admin.tool.product_mail', ['tag' => self::TAG]))
            ->body($this->grid()->render());
    }

    public function edit($id, AdminContent $content)
    {
        return $content
            ->header('Products > '.self::NAME.' > Edit')
            ->description(' ')
            ->body($this->form()->edit($id));
    }

    public function create(AdminContent $content)
    {
        return $content
            ->header('Products > '.self::NAME.' > Create')
            ->description(' ')
            ->body($this->form());
    }

    public function show($id, AdminContent $content)
    {
        return $content
            ->header('Products > '.self::NAME.' > Detail')
            ->description(' ')
            ->row(view('admin.tool.product_mail', ['tag' => self::TAG]))
            ->row($this->detail($id))
            ->row($this->showImages($id));
    }

    public function grid()
    {
        $grid = new Grid(new self::$productClassName());

        $grid->cosmopak_item('Cosmopak Item#');
        $grid->vendor_item('Vendor Item#');
        $grid->manufactory_name('Manufactory Name');
        $grid->item_description('Item Description');
        $grid->material('Material');
        $grid->shape('Shape');
        $grid->style('Style');
        $grid->cup('Cup#');
        $grid->cup_size('Cup Size');
        $grid->cover_material('Cover Material');
        $grid->overall_length('Overall Length');
        $grid->overall_height('Overall Height');
        $grid->overall_width('Overall Width');
        $grid->mechanism('Mechanism');
        $grid->storage_location('Storage Location');
        $grid->sample_available('Sample Available');
        $grid->related_projects('Related Projects');
        $grid->moq('Moq');
        $grid->price('Price');
        $grid->mold_status('Mold Status');
        $grid->images('Images')->display(function ($images) {
            $count = count($images);
            if ($count) {
                return "<a href='gallery/".self::TAG."/{$this->id}' class='btn btn-xs btn-success'><i class='fa fa-image'></i>&nbsp;&nbsp;{$count}</a>";
            } else {
                return "<button type='button' disabled='disabled' class='btn btn-xs btn-default'><i class='fa fa-image'></i>&nbsp;&nbsp;{$count}</button>";
            }
        });

//        $grid->state('Display')->display(function ($type) {
//            return $type ? 'on' : 'off';
//        });
        $grid->created_at('Created At');

        $tag = self::TAG;
        $grid->actions(function (Grid\Displayers\Actions $actions) use ($tag) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
            // append一个操作
            $id = $actions->getKey();
            $script = "javascript:productGridMailBox('{$id}');";

            $actions->append('<a href="'.$tag.'/'.$id.'/edit" class="btn btn-xs btn-primary" style="margin: 5px 5px;"><i class="fa fa-edit"></i>&nbsp;&nbsp;Edit</a>');
            $actions->append('<a href="'.$tag.'/'.$id.'" class="btn btn-xs btn-info" style="margin: 5px 5px;"><i class="fa fa-eye"></i>&nbsp;&nbsp;View</a>');
            $actions->append('<a href="'.$script.'" class="btn btn-xs btn-success" style="margin: 5px 5px;"><i class="fa fa-envelope"></i>&nbsp;&nbsp;Mail</a>');
            $actions->append(new DeleteRow($actions->getKey(), $tag));
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->between('created_at', 'Created At')->datetime();
            $filter->like('cosmopak_item', 'Cosmopak#');
            $filter->like('vendor_item', 'Vendor#');
            $filter->equal('material', 'Material')->select(self::$materialMap);
            $filter->equal('shape', 'Shape')->select(self::$shapeMap);
            $filter->equal('style', 'Style')->select(self::$styleMap);
            $filter->equal('cup', 'Cup#')->select(self::$cupMap);
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
            }, 'Images')->select([
                '1' => 'Only with images',
                '0' => 'Only without images',
            ]);
            $filter->between('overall_height', 'Overall Height');
            $filter->between('overall_width', 'Overall Width');
        });

        $grid->expandFilter();
        $grid->disableExport();

        return $grid;
    }

    public function form()
    {
        $form = new Form(new self::$productClassName());

        $form->display('id', 'ID');

        $form->text('cosmopak_item', 'Cosmopak Item#')->rules('required');
        $form->text('vendor_item', 'Vendor Item#')->rules('required');
        $form->text('manufactory_name', 'Manufactory Name')->rules('required');
        $form->text('item_description', 'Item Description')->rules('required');
        $form->divider();
        $form->select('material', 'Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('shape', 'Shape')->options(self::$shapeMap)->rules('required')->setWidth(4);
        $form->select('style', 'Style')->options(self::$styleMap)->rules('required')->setWidth(4);
        $form->select('cup', 'Cup#')->options(self::$cupMap)->rules('required')->setWidth(4);
        $form->text('cup_size', 'Cup Size')->rules('required|regex:/^\d+$/|max:1', ['regex' => 'The Price must be a number'])->setWidth(4);
        $form->text('cover_material', 'Cover Material')->rules('required');
        $form->divider();
        $form->text('overall_length', 'Overall Length')->rules('required')->setWidth(4);
        $form->text('overall_width', 'Overall Width')->rules('required')->setWidth(4);
        $form->text('overall_height', 'Overall Height')->rules('required')->setWidth(4);
        $form->select('mechanism', 'Mechanism')->options(self::$mechanismMap)->rules('required')->setWidth(4);
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
                $tools->append('<a href="/'.config('admin.route.prefix').'/gallery/'.self::TAG.'/'.$id.'" class="btn btn-sm btn-success" style="margin-right: 5px;"><i class="fa fa-image"></i>&emsp;'.$imagesNum.'&nbsp;images</a>');
            } else {
                $tools->append('<button type="button" class="btn btn-sm btn-default" disabled="disabled" style="width: 100px;margin-right: 5px;"><i class="fa fa-image"></i>&emsp;No&nbsp;&nbsp;image</button>');
            }

            $script = "javascript:productGridMailBox('{$id}');";
            $tools->append('<a href="'.$script.'" class="btn btn-sm btn-info" style="margin-right: 5px;"><i class="fa fa-envelope"></i>&nbsp;&nbsp;Email to</a>');
        });

        $show->id('ID');
        $show->cosmopak_item('Cosmopak Item#');
        $show->vendor_item('Vendor Item#');
        $show->manufactory_name('Manufactory Name');
        $show->item_description('Item Description');
        $show->divider();
        $show->material('Material');
        $show->shape('Shape');
        $show->style('Style');
        $show->cup('Cup#');
        $show->cup_size('Cup Size');
        $show->cover_material('Cover Material');
        $show->divider();
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
        $box = new Box('Images', view('admin.product_images', ['imageList' => $images]));
        $box->style('default');

        return $box;
    }
}