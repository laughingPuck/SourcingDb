<?php

namespace App\Admin\Controllers;

use App\Admin\Conf\Products;
use App\Admin\Extensions\ProductExporter;
use App\Admin\Models\ProductOther;
use App\Admin\Widgets\Action\DocumentBtn;
use App\Admin\Widgets\Action\EditProductBtn;
use App\Admin\Widgets\Action\GalleryBtn;
use App\Admin\Widgets\Action\MailProductBtn;
use App\Admin\Widgets\Action\PDFProductBtn;
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

class ProductOtherController extends Controller
{
    use HasResourceActions;

    const TAG = Products::PRODUCT_OTHER;

    public static $productClassName = ProductOther::class;

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

//        $grid->column('PDF', ' ')->display(function () {
//            $btn = new PDFProductBtn($this->id, self::TAG);
//            return $btn->render();
//        });

        $grid->column('', ' ')->display(function () {
            return '<p style="width: 20px;"></p>';
        });

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
        $grid->cosmopak_item('Cosmopak Item#')->width('120');
        if (Admin::user()->can('page-sensitive-column')) {
            $grid->vendor_item('Vendor Item#')->width('120');
            $grid->manufactory_name('Manufactory Name')->width('120');
        }
        $grid->item_description('Item Description')->width('120');
        $grid->material('Material')->width('120');

        $grid->moq('Moq')->width('50');
        $grid->price('Price')->width('50');
        $grid->files('Files')->display(function ($files) {
            $btn = new DocumentBtn(count($files), $this->id, self::TAG);
            return $btn->render();
        });
        $grid->created_at('Created At')->width('120');
        $grid->disableActions();
        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        $grid->disableFilter();
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
        $form->text('material', 'Material')->rules('required');
        $form->divider();
        $form->text('moq', 'Moq')->rules('required');
        $form->text('price', 'Price')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Price must be a number'])->setWidth(4);
        $form->switch('state', 'Display')->value(1);

        $form->hasMany('images', function (Form\NestedForm $form) {
            $form->image('url', 'Image');
            $form->text('title', 'Title');
            $form->text('desc', 'Desc');
            $form->switch('state', 'Display')->value(1);
        });

        $form->hasMany('files', function (Form\NestedForm $form) {
            $form->file('url', 'File');
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
        $filesNum = DB::table(Products::$productCateMap[self::TAG]['file_table'])->where('product_id', $id)->whereNull('deleted_at')->count();

        $show->panel()->tools(function (\Encore\Admin\Show\Tools $tools) use ($imagesNum, $filesNum, $id) {
            if (!Admin::user()->can('page-products-write')) {
                $tools->disableEdit();
                $tools->disableDelete();
            }
            $tools->append(new GalleryBtn($imagesNum, $id, self::TAG, GalleryBtn::STYLE_DETAIL_TOOL));
            $tools->append(new DocumentBtn($filesNum, $id, self::TAG, GalleryBtn::STYLE_DETAIL_TOOL));
            $tools->append(new MailProductBtn($id, MailProductBtn::STYLE_DETAIL_TOOL));
        });

        $show->id('ID');
        $show->cosmopak_item('Cosmopak Item#');
        if (Admin::user()->can('page-sensitive-column')) {
            $show->vendor_item('Vendor Item#');
            $show->manufactory_name('Manufactory Name');
        }
        $show->item_description('Item Description');
        $show->material('Material');
        $show->divider();
        $show->moq('Moq');
        $show->price('Price');
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