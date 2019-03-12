<?php

namespace App\Admin\Controllers;

use App\Admin\Conf\Products;
use App\Admin\Extensions\ProductExporter;
use App\Admin\Models\ProductPencil;
use App\Admin\Models\ProductStick;
use App\Admin\Models\ProductTube;
use App\Admin\Widgets\Action\EditProductBtn;
use App\Admin\Widgets\Action\GalleryBtn;
use App\Admin\Widgets\Action\MailProductBtn;
use App\Admin\Widgets\Action\ViewRowAction;
use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Show;
use App\Admin\Widgets\AdminContent;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\HasResourceActions;
use Illuminate\Support\Facades\DB;
use App\Admin\Widgets\Action\DeleteRowAction;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Facades\Admin;

class ProductTubeController extends Controller
{
    use HasResourceActions;

    const TAG = Products::PRODUCT_TUBE;

    public static $productClassName = ProductTube::class;

    public static $tubeShapeMap = [
        'Round' => 'Round',
        'Square' => 'Square',
        'Oval/Fat' => 'Oval/Fat',
        'Other' => 'Other',
        'Not sure' => 'Not sure',
    ];
    public static $layerMap = [
        '1' => '1',
        '5' => '5',
        'Other' => 'Other',
        'Not sure' => 'Not sure',
    ];
    public static $chamberMap = [
        '1' => '1',
        '2+' => '2+',
        'Not sure' => 'Not sure',
    ];
    public static $applicatorMaterialMap = [
        'Needle Tip' => 'Needle Tip',
        'Dropper' => 'Dropper',
        'Flip Cap' => 'Flip Cap',
        'Roller' => 'Roller',
        'Brush' => 'Brush',
        'Pump' => 'Pump',
        'Other' => 'Other',
        'Not sure' => 'Not sure'
    ];

    public function index(AdminContent $content)
    {
        return $content
            ->header('Products > '.Products::$productCateMap[self::TAG]['display'])
            ->description(' ')
            ->row(view('admin.tool.product_mail', ['tag' => self::TAG]))
            ->body($this->grid()->render());
    }

    public function edit($id, AdminContent $content)
    {
        Permission::check('page-products-write');
        return $content
            ->header('Products > '.Products::$productCateMap[self::TAG]['display'].' > Edit')
            ->description(' ')
            ->body($this->form()->edit($id));
    }

    public function create(AdminContent $content)
    {
        Permission::check('page-products-write');
        return $content
            ->header('Products > '.Products::$productCateMap[self::TAG]['display'].' > Create')
            ->description(' ')
            ->body($this->form());
    }

    public function show($id, AdminContent $content)
    {
        return $content
            ->header('Products > '.Products::$productCateMap[self::TAG]['display'].' > Detail')
            ->description(' ')
            ->row(view('admin.tool.product_mail', ['tag' => self::TAG]))
            ->row($this->detail($id))
            ->row($this->showImages($id));
    }

    public function grid()
    {
        $grid = new Grid(new self::$productClassName());

        // actions
        $grid->column('View', ' ')->display(function () {
            $btn = new ViewRowAction($this->id, self::TAG);
            return $btn->render();
        });
        $grid->column('Mail', ' ')->display(function () {
            $btn = new MailProductBtn($this->id);
            return $btn->render();
        });
        if (Admin::user()->can('page-products-write')) {
            $grid->column('Edit', ' ')->display(function () {
                $btn = new EditProductBtn($this->id, self::TAG);
                return $btn->render();
            });
            $grid->column('Delete', ' ')->display(function () {
                $btn = new DeleteRowAction($this->id, self::TAG);
                return $btn->render();
            });
        }
        $grid->column('', ' ')->display(function () {
            return '<p style="width: 20px;"></p>';
        });

        $grid->cosmopak_item('Cosmopak Item#')->width('120');
        if (Admin::user()->can('page-sensitive-column')) {
            $grid->vendor_item('Vendor Item#')->width('120');
            $grid->manufactory_name('Manufactory Name')->width('120');
        }
        $grid->item_description('Item Description')->width('120');
        $grid->tube_shape('Tube Shape')->width('120');
        $grid->chamber('Chamber')->width('120');
        $grid->layer('Layer')->width('120');
        $grid->tube_material('Tube Material')->width('120');
        $grid->cap_material('Cap Material')->width('120');
        $grid->closure_mechanism('Closure Mechanism')->width('120');
        $grid->applicator('Applicator')->width('120');
        $grid->applicator_material('Applicator Material')->width('120');
        $grid->estimate_capacity('Estimate Capacity')->width('120');
        $grid->tube_diameter('Tube Diameter')->width('120');
        $grid->overall_length('Overall Length')->width('120');
        $grid->moq('Moq')->width('50');
        $grid->price('Price')->width('50');
        $grid->mold_status('Mold Status')->width('80');
        $grid->cover_image('Image')->display(function () {
            $image = DB::table(Products::$productCateMap[self::TAG]['img_table'])->where('product_id', $this->id)->whereNull('deleted_at')->first();
            if ($image) {
                return "<img src='/{$image->url}' alt='{$image->title}' style='height: 80px;' />";
            } else {
                return '';
            }
        });
        $grid->images('Images')->display(function ($images) {
            $btn = new GalleryBtn(count($images), $this->id, self::TAG);
            return $btn->render();
        })->width('80');
        $grid->created_at('Created At')->width('120');
        $grid->disableActions();
        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->between('created_at', 'Created At')->datetime();
            $filter->like('cosmopak_item', 'Cosmopak#');
            if (Admin::user()->can('page-sensitive-column')) {
                $filter->like('vendor_item', 'Vendor#');
            }
            $filter->equal('tube_shape', 'Tube Shape')->select(self::$tubeShapeMap);
            $filter->equal('layer', 'Layer')->select(self::$layerMap);
            $filter->equal('chamber', 'Chamber')->select(self::$chamberMap);
            $filter->equal('applicator_material', 'Applicator Material')->select(self::$applicatorMaterialMap);
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
            $filter->between('estimate_capacity', 'Estimate Capacity');
            $filter->between('tube_diameter', 'Tube Diameter');
        });

        $grid->expandFilter();
        $exceptField = [];
        if (!Admin::user()->can('page-products-write')) {
            $exceptField = ['vendor_item', 'manufactory_name'];
            $grid->disableCreateButton();
        }
        if (Admin::user()->username != 'admin') {
            $grid->disableExport();
        }
        $grid->exporter(new ProductExporter($grid, self::TAG, $exceptField));

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

        $form->select('tube_shape', 'Tube Shape')->options(self::$tubeShapeMap)->rules('required')->setWidth(4);
        $form->select('chamber', 'Chamber')->options(self::$chamberMap)->rules('required')->setWidth(4);
        $form->select('layer', 'Layer')->options(self::$layerMap)->rules('required')->setWidth(4);
        $form->select('tube_material', 'Tube Material')->options(self::$applicatorMaterialMap)->rules('required')->setWidth(4);
        $form->select('cap_material', 'Cap Material')->options(self::$applicatorMaterialMap)->rules('required')->setWidth(4);
        $form->select('applicator_material', 'Applicator Material')->options(self::$applicatorMaterialMap)->rules('required')->setWidth(4);
        $form->divider();
        $form->text('closure_mechanism', 'Closure Mechanism')->rules('required');
        $form->text('applicator', 'Applicator')->rules('required');
        $form->text('estimate_capacity', 'Estimate Capacity')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Estimate Capacity must be a number'])->setWidth(4);
        $form->text('tube_diameter', 'Tube Diameter')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Tube Diameter must be a number'])->setWidth(4);
        $form->text('overall_length', 'Overall Length')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Overall Length must be a number'])->setWidth(4);
        $form->divider();
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

        $imagesNum = DB::table(Products::$productCateMap[self::TAG]['img_table'])->where('product_id', $id)->whereNull('deleted_at')->count();

        $show->panel()->tools(function (\Encore\Admin\Show\Tools $tools) use ($imagesNum, $id) {
            if (!Admin::user()->can('page-products-write')) {
                $tools->disableEdit();
                $tools->disableDelete();
            }
            $tools->append(new GalleryBtn($imagesNum, $id, self::TAG, GalleryBtn::STYLE_DETAIL_TOOL));
            $tools->append(new MailProductBtn($id, MailProductBtn::STYLE_DETAIL_TOOL));
        });

        $show->id('ID');
        $show->cosmopak_item('Cosmopak Item#');
        if (Admin::user()->can('page-sensitive-column')) {
            $show->vendor_item('Vendor Item#');
            $show->manufactory_name('Manufactory Name');
        }
        $show->item_description('Item Description');
        $show->divider();

        $show->tube_shape('Tube Shape');
        $show->chamber('Chamber');
        $show->layer('Layer');
        $show->tube_material('Tube Material');
        $show->cap_material('Cap Material');
        $show->closure_mechanism('Closure Mechanism');
        $show->applicator('Applicator');
        $show->applicator_material('Applicator Material');
        $show->estimate_capacity('Estimate Capacity');
        $show->tube_diameter('Tube Diameter');
        $show->overall_length('Overall Length');
        
        $show->divider();
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
        $images = DB::table(Products::$productCateMap[self::TAG]['img_table'])->where('product_id', $id)->whereNull('deleted_at')->get();
        $box = new Box('Images', view('admin.product_images', ['imageList' => $images]));
        $box->style('default');

        return $box;
    }
}