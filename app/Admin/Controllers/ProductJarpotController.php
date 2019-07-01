<?php

namespace App\Admin\Controllers;

use App\Admin\Conf\Products;
use App\Admin\Extensions\ProductExporter;
use App\Admin\Models\ProductJarpot;
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

class ProductJarpotController extends Controller
{
    use HasResourceActions;

    const TAG = Products::PRODUCT_JAR_POT;

    public static $productClassName = ProductJarpot::class;

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
    public static $shifterMap = [
        'Yes' => 'Yes',
        'No' => 'No',
        'Not Sure' => 'Not Sure'
    ];
    public static $shapeMap = [
        'Round' => 'Round',
        'Square' => 'Square',
        'Other' => 'Other',
        'Not sure' => 'Not sure',
    ];
    public static $chamberMap = [
        '1' => '1',
        '2+' => '2+',
        'Not sure' => 'Not sure',
    ];
    public static $colorMap = [
        'Clear' => 'Clear',
        'Opaque' => 'Opaque',
        'Translucent' => 'Translucent',
    ];
    public static $wallStyleMap = [
        'Thin' => 'Thin',
        'Thick' => 'Thick',
        'Double Wall' => 'Double Wall',
        'Overmold' => 'Overmold',
    ];
    public static $closureMechanismMap = [
        'Snap' => 'Snap',
        'Magnetic' => 'Magnetic',
        'Button' => 'Button',
        'Screw' => 'Screw',
        'Others' => 'Others',
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

        $grid->shape('Shape')->width('50');
        $grid->chamber('Chamber')->width('50');
        $grid->ofc('OFC(ml)')->width('50');
        $grid->estimate_capacity('Estimate Capacity (mL)')->width('150');
        $grid->color('Color')->width('80');
        $grid->cap_material('Cap Material')->width('120');
        $grid->liner_material('Liner Material')->width('120');
        $grid->base_material('Base Material')->width('120');
        $grid->cover_disc('Cover Disc')->display(function ($value) {
            if (array_key_exists($value, Products::$switchMap)) {
                return Products::$switchMap[$value];
            }
            return null;
        })->width('80');
        $grid->stifter_material('Shifter')->width('120');
        $grid->wall_style('Wall Style')->width('120');
        $grid->closure_mechanism('Closure Mechanism')->width('120');
        $grid->overall_length('Overall Length (mm)')->width('120');
        $grid->overall_width('Overall Width (mm)')->width('120');
        $grid->overall_height('Overall Height (mm)')->width('120');
        $grid->moq('MOQ')->width('50');
        $grid->price('Price (USD)')->width('50');
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
            $filter->equal('shape', 'Shape')->select(self::$shapeMap);
            $filter->equal('base_material', 'Base Material')->select(self::$materialMap);
            $filter->equal('cover_disc', 'Cover Disc')->select(Products::$switchMap);
            $filter->equal('chamber', 'Chamber#')->select(self::$chamberMap);
            $filter->equal('wall_style', 'Wall Style')->select(self::$wallStyleMap);
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
            $filter->between('estimate_capacity', 'Estimate Capacity (mL)');
            $filter->between('overall_width', 'Overall Width (mm)');
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
        $form->select('shape', 'Shape')->options(self::$shapeMap)->rules('required')->setWidth(4);
        $form->select('chamber', 'Chamber')->options(self::$chamberMap)->rules('required')->setWidth(4);
        $form->select('color', 'Color')->options(self::$colorMap)->rules('required')->setWidth(4);
        $form->select('cap_material', 'Cap Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('liner_material', 'Liner Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('base_material', 'Base Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('cover_disc', 'Cover Disc')->options(Products::$switchMap)->rules('required')->setWidth(4);
        $form->select('stifter_material', 'Shifter')->options(self::$shifterMap)->rules('required')->setWidth(4);
        $form->select('wall_style', 'Wall Style')->options(self::$wallStyleMap)->rules('required')->setWidth(4);
        $form->select('closure_mechanism', 'Closure Mechanism')->options(self::$closureMechanismMap)->rules('required')->setWidth(4);
        $form->divider();
        $form->text('ofc', 'OFC(ml)')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Ofc Width must be a number'])->setWidth(4);
        $form->text('estimate_capacity', 'Estimate Capacity (mL)')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Estimate Capacity (mL) Width must be a number'])->setWidth(4);
        $form->text('overall_length', 'Overall Length (mm)')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Overall Length (mm) must be a number'])->setWidth(4);
        $form->text('overall_width', 'Overall Width (mm)')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Overall Width (mm) must be a number'])->setWidth(4);
        $form->text('overall_height', 'Overall Height (mm)')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Overall Height (mm) must be a number'])->setWidth(4);
        $form->divider();
        $form->text('moq', 'MOQ')->rules('required');
        $form->text('price', 'Price (USD)')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Price (USD) must be a number'])->setWidth(4);
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
        $show->shape('Shape');
        $show->chamber('Chamber');
        $show->ofc('OFC(ml)');
        $show->estimate_capacity('Estimate Capacity (mL)');
        $show->color('Color');
        $show->cap_material('Cap Material');
        $show->liner_material('Liner Material');
        $show->base_material('Base Material');
        $switchMap = Products::$switchMap;
        $show->cover_disc('Cover Disc')->as(function ($value) use ($switchMap) {
            if (array_key_exists($value, $switchMap)) {
                return $switchMap[$value];
            }
            return null;
        });
        $show->stifter_material('Shifter');
        $show->wall_style('Wall Style');
        $show->closure_mechanism('Closure Mechanism');
        $show->overall_length('Overall Length (mm)');
        $show->overall_width('Overall Width (mm)');
        $show->overall_height('Overall Height (mm)');
        $show->divider();
        $show->moq('MOQ');
        $show->price('Price (USD)');
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