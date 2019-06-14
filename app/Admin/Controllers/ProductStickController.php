<?php

namespace App\Admin\Controllers;

use App\Admin\Conf\Products;
use App\Admin\Extensions\ProductExporter;
use App\Admin\Models\ProductStick;
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

class ProductStickController extends Controller
{
    use HasResourceActions;

    const TAG = Products::PRODUCT_STICK;

    public static $productClassName = ProductStick::class;

    public static $materialMap = [
        'AS' => 'AS',
        'ABS' => 'ABS',
        'PP' => 'PP',
        'PETG' => 'PETG',
        'PET' => 'PET',
        'POM' => 'POM',
        'SAN' => 'SAN',
        'PMMA' => 'PMMA',
        'Glass' => 'Glass',
        'Not sure' => 'Not sure',
    ];
    public static $shapeMap = [
        'Round' => 'Round',
        'Square' => 'Square',
        'Oval' => 'Oval',
        'Other' => 'Other',
    ];
    public static $closureMechanismMap = [
        'Twist' => 'Twist',
        'Sanp' => 'Sanp',
        'Magnetic' => 'Magnetic',
        'Others' => 'Others',
        'Not sure' => 'Not sure',
    ];
    public static $mechanismMap = [
        'Snap' => 'Snap',
        'Magnetic' => 'Magnetic',
        'Button' => 'Button',
        'Others' => 'Others',
        'Not sure' => 'Not sure',
    ];
    public static $cupMap = [
        'Cup#' => 'Cup#',
        '1' => '1',
        '2+' => '2+',
        'Not sure' => 'Not sure',
    ];
    public static $fillingMethod = [
        'Topp Filling' => 'Topp Filling',
        'Bottom Filling' => 'Bottom Filling',
        'Blank' => 'Blank',
    ];
    public static $edgesStyleMap = [
        'Soft' => 'Soft',
        'Sharp' => 'Sharp',
        'Round' => 'Round',
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

        $grid->shape('Shape')->width('120');
        $grid->edges_style('Edges Style')->width('120');
        $grid->cup('Cup')->width('120');
        $grid->cup_size('Cup Size')->width('120');
        $grid->cup_material('Cup Material')->width('120');
        $grid->recommend_for_soft('Recommend For Soft')->width('150');
        $grid->estimate_capacity('Estimate Capacity')->width('120');
        $grid->cup_cover_material('Cup Cover Material')->width('120');
        $grid->cap_material('Cap Material')->width('120');
        $grid->ashell_material('A-Shell Material')->width('120');
        $grid->body_material('Body Material')->width('120');
        $grid->outer_base_material('Outer Base Material')->width('150');
        $grid->overall_length('Overall Length')->width('120');
        $grid->overall_width('Overall Width')->width('120');
        $grid->overall_height('Overall Height')->width('120');
        $grid->filling_method('Filling Method')->width('120');
        $grid->closure_mechanism('Closure Mechanism')->width('120');
        $grid->mechanism('Mechanism')->width('120');
        $grid->moq('Moq')->width('50');
        $grid->price('Price')->width('50');
        $grid->mold_status('Mold Status')->width('80');
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
            $filter->equal('material', 'Material')->select(self::$materialMap);
            $filter->equal('shape', 'Shape')->select(self::$shapeMap);
            $filter->equal('mechanism', 'Mechanism')->select(self::$mechanismMap);
            $filter->equal('closure_mechanism', 'Closure Mechanism')->select(self::$closureMechanismMap);
            $filter->equal('cup', 'Cup')->select(self::$cupMap);
            $filter->equal('filling_method', 'Filling Method')->select(self::$fillingMethod);
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
            $filter->between('overall_height', 'Overall Height');
            $filter->between('overall_width', 'Overall Width');
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
        $form->select('material', 'Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('shape', 'Shape')->options(self::$shapeMap)->rules('required')->setWidth(4);
        $form->select('edges_style', 'Edges Style')->options(self::$edgesStyleMap)->rules('required')->setWidth(4);
        $form->select('cup', 'Cup')->options(self::$cupMap)->rules('required')->setWidth(4);
        $form->select('cup_material', 'Cap Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('cap_material', 'Cap Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('cup_cover_material', 'Cup Cover Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('ashell_material', 'A-Shell Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('body_material', 'Body Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('outer_base_material', 'Outer Base Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('mechanism', 'Mechanism')->options(self::$mechanismMap)->rules('required')->setWidth(4);
        $form->select('filling_method', 'Filling Method')->options(self::$fillingMethod)->rules('required')->setWidth(4);
        $form->select('closure_mechanism', 'Closure Mechanism')->options(self::$closureMechanismMap)->rules('required')->setWidth(4);
        $form->divider();
        $form->text('recommend_for_soft', 'Recommend For Soft')->rules('required');
        $form->text('cup_size', 'Cup Size')->rules('required|regex:/^\d+$/|max:1', ['regex' => 'The Cup Size must be a number'])->setWidth(4);
        $form->text('estimate_capacity', 'Estimate Capacity')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Estimate Capacity Width must be a number'])->setWidth(4);
        $form->text('overall_length', 'Overall Length')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Overall Length must be a number'])->setWidth(4);
        $form->text('overall_width', 'Overall Width')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Overall Width must be a number'])->setWidth(4);
        $form->text('overall_height', 'Overall Height')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Overall Height must be a number'])->setWidth(4);
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
        $show->material('Material');
        $show->shape('Shape');
        $show->edges_style('Edges Style');
        $show->cup('Cup');
        $show->cup_size('Cup Size');
        $show->cup_material('Cup Material');
        $show->recommend_for_soft('Recommend For Soft');
        $show->estimate_capacity('Estimate Capacity');
        $show->cup_cover_material('Cup Cover Material');
        $show->cap_material('Cap Material');
        $show->ashell_material('A-Shell Material');
        $show->body_material('Body Material');
        $show->outer_base_material('Outer Base Material');
        $show->overall_length('Overall Length');
        $show->overall_width('Overall Width');
        $show->overall_height('Overall Height');
        $show->filling_method('Filling Method');
        $show->closure_mechanism('Closure Mechanism');
        $show->mechanism('Mechanism');
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