<?php

namespace App\Admin\Controllers;

use App\Admin\Conf\Products;
use App\Admin\Extensions\ProductExporter;
use App\Admin\Models\ProductBrush;
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

class ProductBrushController extends Controller
{
    use HasResourceActions;

    const TAG = Products::PRODUCT_BRUSH;

    public static $productClassName = ProductBrush::class;

    public static $handleMaterialMap = [
        'Plastic' => 'Plastic',
        'Metal' => 'Metal',
        'Wood' => 'Wood',
        'Other' => 'Other',
        'Not sure' => 'Not sure',
    ];
    public static $handleShapeMap = [
        'Round' => 'Round',
        'Square' => 'Square',
        'Oval' => 'Oval',
        'Other' => 'Other',
        'Not sure' => 'Not sure',
    ];
    public static $brushMaterialMap = [
        'Animal Hair' => 'Animal Hair',
        'Synthetic Hair' => 'Synthetic Hair',
        'Silicon' => 'Silicon',
        'Sponge' => 'Sponge',
        'Other' => 'Other',
        'Not sure' => 'Not sure',
    ];
    public static $brushMap = [
        '1 End' => '1 End',
        '2 End' => '2 End',
        'Not sure' => 'Not sure',
    ];
    public static $setIndividualMap = [
        'Individual' => 'Individual',
        'Come As Set' => 'Come As Set',
        'Not sure' => 'Not sure',
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

//        $grid->column('PDF', ' ')->display(function () {
//            $btn = new PDFProductBtn($this->id, self::TAG);
//            return $btn->render();
//        });

        $grid->column('', ' ')->display(function () {
            return '<p style="width: 20px;"></p>';
        });

        $grid->cosmopak_item('Cosmopak Item#')->width('120');
        if (Admin::user()->can('page-sensitive-column')) {
            $grid->vendor_item('Vendor Item#')->width('120');
            $grid->manufactory_name('Manufactory Name')->width('120');
        }
        $grid->item_description('Item Description')->width('120');

        $grid->cap_material('Cap Material')->width('120');
        $grid->handle_material('Handle Material')->width('120');
        $grid->handle_shape('Handle Shape')->width('120');
        $grid->handle_length('Handle Length')->width('120');
        $grid->brush_material('Brush Material')->width('120');
        $grid->brush_shape('Brush Shape')->width('120');
        $grid->brush('Brush')->width('120');
        $grid->ferrual_material('Ferrual Material')->width('120');
        $grid->ferrual_length('Ferrual Length')->width('120');
        $grid->overall_length('Overall Length')->width('120');
        $grid->overall_width('Overall Width')->width('120');
        $grid->set_individual('Set Individual')->width('120');

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

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->between('created_at', 'Created At')->datetime();
            if (Admin::user()->can('page-sensitive-column')) {
                $filter->like('vendor_item', 'Vendor#');
                $filter->where(function ($query) {
                    $query->where('cosmopak_item', 'like', "%{$this->input}%")
                        ->orWhere('item_description', 'like', "%{$this->input}%")
                        ->orWhere('manufactory_name', 'like', "%{$this->input}%");
                }, 'Name,Description or Model Number');
            } else {
                $filter->where(function ($query) {
                    $query->where('cosmopak_item', 'like', "%{$this->input}%")
                        ->orWhere('item_description', 'like', "%{$this->input}%");
                }, 'Description or Model Number');
            }
            $filter->equal('handle_material', 'Handle Material')->select(self::$handleMaterialMap);
            $filter->equal('handle_shape', 'Handle Shape')->select(self::$handleShapeMap);
            $filter->equal('brush_material', 'Brush Material')->select(self::$brushMaterialMap);
            $filter->equal('brush', 'Brush')->select(self::$brushMap);
            $filter->equal('set_individual', 'Set Individual')->select(self::$setIndividualMap);
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
        $form->select('cap_material', 'Cap Material')->options(self::$handleMaterialMap)->rules('required')->setWidth(4);
        $form->select('handle_material', 'Handle Material')->options(self::$handleMaterialMap)->rules('required')->setWidth(4);
        $form->select('handle_shape', 'Handle Shape')->options(self::$handleShapeMap)->rules('required')->setWidth(4);
        $form->select('brush_material', 'Brush Material')->options(self::$brushMaterialMap)->rules('required')->setWidth(4);
        $form->select('brush_shape', 'Brush Shape')->options(self::$handleShapeMap)->rules('required')->setWidth(4);
        $form->select('brush', 'Brush')->options(self::$brushMap)->rules('required')->setWidth(4);
        $form->select('ferrual_material', 'Ferrual Material')->options(self::$handleMaterialMap)->rules('required')->setWidth(4);
        $form->select('set_individual', 'Set Individual')->options(self::$setIndividualMap)->rules('required')->setWidth(4);
        $form->divider();
        $form->text('ferrual_length', 'Ferrual Length')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Ferrual Length must be a number'])->setWidth(4);
        $form->text('handle_length', 'Handle Length')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Handle Length must be a number'])->setWidth(4);
        $form->text('overall_length', 'Overall Length')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Overall Length must be a number'])->setWidth(4);
        $form->text('overall_width', 'Overall Width')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Overall Width must be a number'])->setWidth(4);
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
        $show->divider();
        $show->cap_material('Cap Material');
        $show->handle_material('Handle Material');
        $show->handle_shape('Handle Shape');
        $show->handle_length('Handle Length');
        $show->brush_material('Brush Material');
        $show->brush_shape('Brush Shape');
        $show->brush('Brush');
        $show->ferrual_material('Ferrual Material');
        $show->ferrual_length('Ferrual Length');
        $show->overall_length('Overall Length');
        $show->overall_width('Overall Width');
        $show->set_individual('Set Individual');
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