<?php

namespace App\Admin\Controllers;

use App\Admin\Conf\Products;
use App\Admin\Extensions\ProductExporter;
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
use App\Admin\Models\ProductVial;
use Illuminate\Support\Facades\DB;
use App\Admin\Widgets\Action\DeleteRowAction;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Facades\Admin;

class ProductVialController extends Controller
{
    use HasResourceActions;

    const TAG = Products::PRODUCT_VIAL;

    public static $productClassName = ProductVial::class;

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
    public static $capMaterialMap = [
        'AS' => 'AS',
        'ABS' => 'ABS',
        'PP' => 'PP',
        'PETG' => 'PETG',
        'PET' => 'PET',
        'POM' => 'POM',
        'SAN' => 'SAN',
        'PMMA' => 'PMMA',
        'Glass' => 'Glass',
        'Aluminum' => 'Aluminum',
        'Not sure' => 'Not sure',
    ];
    public static $shapeMap = [
        'Round/Cylindical' => 'Round/Cylindical',
        'Square' => 'Square',
        'Other' => 'Other',
        'Not sure' => 'Not sure',
    ];
    public static $vialMap = [
        '1' => '1',
        '2+' => '2+',
        'Not sure' => 'Not sure',
    ];
    public static $applicatorMap = [
        'Brush' => 'Brush',
        'FLocking Plastic Applicator' => 'FLocking Plastic Applicator',
        'Non FLocking Plastic Applicator' => 'Non FLocking Plastic Applicator',
        'Other Applicator' => 'Other Applicator',
        'Not sure' => 'Not sure',
    ];
    public static $edgesStyleMap = [
        'Sharp' => 'Sharp',
        'Soft' => 'Soft',
        'Round' => 'Round',
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
        'Screw' => 'Screw',
        'Snap' => 'Snap',
        'Magnetic' => 'Magnetic',
        'Button' => 'Button',
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
        $grid->cap_material('Cap Material')->width('120');
        $grid->inner_cap_material('Inner Cap Material')->width('120');
        $grid->collar_material('Collar Material')->width('120');
        $grid->rod_material('Rod Material')->width('120');
        $grid->available_applicator_options('Available Applicator Options')->width('180');
        $grid->base_material('Base Material')->width('120');
        $grid->shape('Shape')->display(function ($value) {
            if (array_key_exists($value, self::$shapeMap)) {
                return self::$shapeMap[$value];
            }
            return null;
        })->width('50');
        $grid->edges_style('Edges Style')->display(function ($value) {
            if (array_key_exists($value, self::$edgesStyleMap)) {
                return self::$edgesStyleMap[$value];
            }
            return null;
        })->width('80');
        $grid->vial('Vial#')->width('50');
        $grid->ofc('OFC (mL)')->width('50');
        $grid->estimate_capacity('Estimate Capacity (mL)')->width('150');
        $grid->color('Color')->display(function ($value) {
            if (array_key_exists($value, self::$colorMap)) {
                return self::$colorMap[$value];
            }
            return null;
        })->width('80');
        $grid->applicator('Applicator')->width('80');
//        $grid->thick_wall('Thick Wall')->display(function ($value) {
//            if (array_key_exists($value, Products::$switchMap)) {
//                return Products::$switchMap[$value];
//            }
//            return null;
//        })->width('80');
        $grid->wall_style('Wall Style')->display(function ($value) {
            if (array_key_exists($value, self::$wallStyleMap)) {
                return self::$wallStyleMap[$value];
            }
            return null;
        })->width('120');
        $grid->closure_mechanism('Closure Mechanism')->display(function ($value) {
            if (array_key_exists($value, self::$closureMechanismMap)) {
                return self::$closureMechanismMap[$value];
            }
            return null;
        })->width('120');
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
//        $grid->updated_at('Updated');
        $grid->disableActions();
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
            $filter->equal('vial', 'Vial#')->select(self::$vialMap);
            $filter->equal('base_material', 'Base Material')->select(self::$materialMap);
            $filter->equal('applicator', 'Applicator')->select(self::$applicatorMap);
//            $filter->equal('thick_wall', 'Thick Wall')->select(Products::$switchMap);
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
            $filter->between('overall_height', 'Overall Height (mm)');
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
        $form->select('cap_material', 'Cap Material')->options(self::$capMaterialMap)->rules('required')->setWidth(4);
        $form->select('inner_cap_material', 'Inner Cap Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('collar_material', 'Collar Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('rod_material', 'Rod Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('base_material', 'Base Material')->options(self::$materialMap)->rules('required')->setWidth(4);
        $form->select('shape', 'Shape')->options(self::$shapeMap)->rules('required')->setWidth(4);
        $form->select('edges_style', 'Edges Style')->options(self::$edgesStyleMap)->rules('required')->setWidth(4);
        $form->select('color', 'Color')->options(self::$colorMap)->rules('required')->setWidth(4);
        $form->select('vial', 'Vial#')->options(self::$vialMap)->rules('required')->setWidth(4);
        $form->select('applicator', 'Applicator')->options(self::$applicatorMap)->rules('required')->setWidth(4);
//        $form->select('thick_wall', 'Thick Wall')->options(Products::$switchMap)->rules('required')->setWidth(4);
        $form->select('wall_style', 'Wall Style')->options(self::$wallStyleMap)->rules('required')->setWidth(4);
        $form->select('closure_mechanism', 'Closure Mechanism')->options(self::$closureMechanismMap)->rules('required')->setWidth(4);
        $form->divider();
        $form->text('ofc', 'OFC (mL)')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The OFC must be a number'])->setWidth(4);
        $form->text('estimate_capacity', 'Estimate Capacity (mL)')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Estimate Capacity (mL) must be a number'])->setWidth(4);
        $form->text('overall_width', 'Overall Width (mm)')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Overall Width (mm) must be a number'])->setWidth(4);
        $form->text('overall_height', 'Overall Height (mm)')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Overall Height (mm) must be a number'])->setWidth(4);
        $form->text('available_applicator_options', 'Available Applicator Options')->rules('required');
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
        $show->cap_material('Cap Material');
        $show->inner_cap_material('Inner Cap Material');
        $show->collar_material('Collar Material');
        $show->rod_material('Rod Material');
        $show->base_material('Base Material');
        $show->shape('Shape');
        $edgesStyle = self::$edgesStyleMap;
        $show->edges_style('Edges Style')->as(function ($value) use ($edgesStyle) {
            if (array_key_exists($value, $edgesStyle)) {
                return $edgesStyle[$value];
            }
            return null;
        });
        $color = self::$colorMap;
        $show->color('Color')->as(function ($value) use ($color) {
            if (array_key_exists($value, $color)) {
                return $color[$value];
            }
            return null;
        });
        $show->vial('Vial#');
        $show->ofc('OFC (mL)');
        $show->estimate_capacity('Estimate Capacity (mL)');
        $show->available_applicator_options('Available Applicator Options');
        $show->applicator('Applicator');
        $switchMap = Products::$switchMap;
//        $show->thick_wall('Thick Wall')->as(function ($value) use ($switchMap) {
//            if (array_key_exists($value, $switchMap)) {
//                return $switchMap[$value];
//            }
//            return null;
//        });
        $wallStyle = self::$wallStyleMap;
        $show->wall_style('Wall Style')->as(function ($value) use ($wallStyle) {
            if (array_key_exists($value, $wallStyle)) {
                return $wallStyle[$value];
            }
            return null;
        });
        $closureMechanism = self::$closureMechanismMap;
        $show->closure_mechanism('Closure Mechanism')->as(function ($value) use ($closureMechanism) {
            if (array_key_exists($value, $closureMechanism)) {
                return $closureMechanism[$value];
            }
            return null;
        });
        $show->divider();
        $show->overall_width('Overall Width (mm)');
        $show->overall_height('Overall Height (mm)');
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