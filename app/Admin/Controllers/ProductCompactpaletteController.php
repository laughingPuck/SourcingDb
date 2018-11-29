<?php

namespace App\Admin\Controllers;

use App\Admin\Conf\Products;
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
use App\Admin\Models\ProductCompactpalette;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Facades\Admin;
use App\Admin\Widgets\Action\DeleteRowAction;

class ProductCompactpaletteController extends Controller
{
    use HasResourceActions;

    const NAME = 'Compact & Palette';
    const TAG = Products::PRODUCT_COMPACT_PALETTE;
    const IMAGE_TABLE = 'image_compactpalette';

    public static $productClassName = ProductCompactpalette::class;

    public static $shapeMap = [
        'Rentangle' => 'Rentangle',
        'Round' => 'Round',
        'Square' => 'Square',
        'Oval' => 'Oval',
        'Other' => 'Other',
        'Not sure' => 'Not sure',
    ];
    public static $panWellMap = [
        '1' => '1',
        '2+' => '2+',
        'Not sure' => 'Not sure',
    ];
    public static $switchMap = [
        1 => 'Yes',
        2 => 'No',
        3 => 'Not sure',
    ];
    public static $latchSystemMap = [
        'Rasied Bump Fit' => 'Rasied Bump Fit',
        'Latch Button' => 'Latch Button',
        'Magnetic' => 'Magnetic',
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
        Permission::check('page-products-write');
        return $content
            ->header('Products > '.self::NAME.' > Edit')
            ->description(' ')
            ->body($this->form()->edit($id));
    }

    public function create(AdminContent $content)
    {
        Permission::check('page-products-write');
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
        $grid->material('Material')->width('80');
        $grid->shape('Shape')->width('50');
        $grid->pan_well('Pan Well#')->width('80');
        $grid->overall_length('Overall Height')->width('120');
        $grid->overall_width('Overall Width')->width('120');
        $grid->overall_height('Overall Height')->width('120');
        $grid->mirror('Mirror')->width('50');
        $grid->window('Window')->width('50');
        $grid->pan_well_shape('Pan Well Shape')->width('120');
        $grid->pan_well_width('Pan Well Width/radius')->width('140');
        $grid->pan_well_height('Pan Well Height')->width('120');
        $grid->applicator_well('Applicator Well')->width('120');
        $grid->latch_system('Latch System')->width('120');
        $grid->injector_pin('Injector Pin')->width('120');
        $grid->storage_location('Storage Location')->width('120');
        $grid->sample_available('Sample Available')->width('120');
        $grid->related_projects('Related Projects')->width('120');
        $grid->moq('Moq')->width('50');
        $grid->price('Price')->width('50');
        $grid->images('Images')->display(function ($images) {
            $btn = new GalleryBtn(count($images), $this->id, self::TAG);
            return $btn->render();
        });

//        $grid->state('Display')->display(function ($type) {
//            return $type ? 'on' : 'off';
//        });
        $grid->created_at('Created At')->width('120');
//        $grid->updated_at('Updated');

        $grid->disableActions();
//        $productTag = self::TAG;
//        $grid->actions(function (Grid\Displayers\Actions $actions) use ($productTag) {
//            $actions->disableDelete();
//            $actions->disableEdit();
//            $actions->disableView();
//            // append一个操作
//            $id = $actions->getKey();
//            $actions->append(new ViewRowAction($id, $productTag));
//            $actions->append(new MailProductBtn($id));
//            if (Admin::user()->can('page-products-write')) {
//                $actions->append(new EditProductBtn($id, $productTag));
//                $actions->append(new DeleteRowAction($id, $productTag));
//            }
//        });

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
            $filter->equal('shape', 'Shape')->select(self::$shapeMap);
            $filter->equal('pan_well', 'Pan Well#')->select(self::$panWellMap);
            $filter->between('pan_well_width', 'Pan Well Width');
            $filter->equal('applicator_well', 'Applicator Well')->select(self::$switchMap);
            $filter->equal('latch_system', 'Latch System')->select(self::$latchSystemMap);
            $filter->equal('window', 'Window')->select(self::$switchMap);
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
            $filter->between('overall_length', 'Overall Length');
            $filter->between('overall_width', 'Overall Width');
        });

        $grid->expandFilter();
        $grid->disableExport();
        $grid->disableRowSelector();
        if (!Admin::user()->can('page-products-write')) {
            $grid->disableCreateButton();
        }

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
        $form->text('material', 'Material')->rules('required');
        $form->select('shape', 'Shape')->options(self::$shapeMap)->rules('required')->setWidth(4);
        $form->select('pan_well', 'Pan Well#')->options(self::$panWellMap)->rules('required')->setWidth(4);
        $form->text('overall_length', 'Overall Length')->rules('required')->setWidth(4);
        $form->text('overall_width', 'Overall Width')->rules('required')->setWidth(4);
        $form->text('overall_height', 'Overall Height')->rules('required')->setWidth(4);
        $form->divider();
        $form->select('mirror', 'Mirror')->options(self::$switchMap)->rules('required')->setWidth(4);
        $form->select('window', 'Window')->options(self::$switchMap)->rules('required')->setWidth(4);
        $form->text('pan_well_shape', 'Pan Well Shape')->rules('required');
        $form->text('pan_well_width', 'Pan Well Width')->rules('required')->setWidth(4);
        $form->text('pan_well_height', 'Pan Well Height')->rules('required')->setWidth(4);
        $form->select('applicator_well', 'Applicator Well')->options(self::$switchMap)->rules('required')->setWidth(4);
        $form->text('latch_system', 'Latch System')->rules('required')->setWidth(4);
        $form->select('injector_pin', 'Injector Pin')->options(self::$switchMap)->rules('required')->setWidth(4);
        $form->divider();
        $form->text('storage_location', 'Storage Location')->rules('required');
        $form->text('sample_available', 'Sample Available')->rules('required');
        $form->text('related_projects', 'Related Projects')->rules('required');
        $form->text('moq', 'Moq')->rules('required');
        $form->text('price', 'Price')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Price must be a number'])->setWidth(4);
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
        $show->material('Material');
        $show->shape('Shape');
        $show->pan_well('Pan Well#');
        $show->divider();
        $show->overall_length('Overall Height');
        $show->overall_width('Overall Width');
        $show->overall_height('Overall Height');
        $show->mirror('Mirror');
        $show->window('Window');
        $show->pan_well_shape('Pan Well Shape');
        $show->pan_well_width('Pan Well Width');
        $show->pan_well_height('Pan Well Height');
        $show->applicator_well('Applicator Well');
        $show->latch_system('Latch System');
        $show->injector_pin('Injector Pin');
        $show->storage_location('Storage Location');
        $show->sample_available('Sample Available');
        $show->related_projects('Related Projects');
        $show->moq('Moq');
        $show->price('Price');
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