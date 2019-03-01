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
use App\Admin\Models\ProductCompactpalette;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Facades\Admin;
use App\Admin\Widgets\Action\DeleteRowAction;
use Illuminate\Support\MessageBag;

class ProductCompactpaletteController extends Controller
{
    use HasResourceActions;

    const TAG = Products::PRODUCT_COMPACT_PALETTE;

    public static $productClassName = ProductCompactpalette::class;

    public static $shapeMap = [
        'Rentangle' => 'Rentangle',
        'Round' => 'Round',
        'Square' => 'Square',
        'Oval' => 'Oval',
        'Other' => 'Other',
        'Not sure' => 'Not sure',
    ];
    public static $edgesStyleMap = [
        'Soft Edges' => 'Soft Edges',
        'Sharp Edges' => 'Sharp Edges',
        'Not sure' => 'Not sure',
    ];
    public static $panWellMap = [
        '1' => '1',
        '2+' => '2+',
        'Not sure' => 'Not sure',
    ];
    public static $materialMap = [
        'AS' => 'AS',
        'ABS' => 'ABS',
        'PP' => 'PP',
        'PETG' => 'PETG',
        'PET' => 'PET',
        'POM' => 'POM',
        'SAN' => 'SAN',
        'PMMA' => 'PMMA',
        'Not sure' => 'Not sure',
    ];
    public static $closureMechanismMap = [
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

        $grid->column('PDF', ' ')->display(function () {
            $btn = new PDFProductBtn($this->id, self::TAG);
            return $btn->render();
        });

        $grid->column('', ' ')->display(function () {
            return '<p style="width: 20px;"></p>';
        });

        $grid->cosmopak_item('Cosmopak Item#')->width('120');
        if (Admin::user()->can('page-sensitive-column')) {
            $grid->vendor_item('Vendor Item#')->width('120');
            $grid->manufactory_name('Manufactory Name')->width('120');
        }
        $grid->item_description('Item Description')->width('120');
        $grid->material('Material')->display(function ($value) {
            if (array_key_exists($value, self::$materialMap)) {
                return self::$materialMap[$value];
            }
            return null;
        })->width('80');
        $grid->shape('Shape')->width('50');
        $grid->edges_style('Edges Style')->display(function ($value) {
            if (array_key_exists($value, self::$edgesStyleMap)) {
                return self::$edgesStyleMap[$value];
            }
            return null;
        })->width('80');
        $grid->closure_mechanism('Closure Mechanism')->display(function ($value) {
            if (array_key_exists($value, self::$closureMechanismMap)) {
                return self::$closureMechanismMap[$value];
            }
            return null;
        })->width('120');
        $grid->pan_well('Pan Well#')->width('80');
        $grid->overall_length('Overall Height')->width('120');
        $grid->overall_width('Overall Width')->width('120');
        $grid->overall_height('Overall Height')->width('120');
        $grid->mirror('Mirror')->display(function ($value) {
            if (array_key_exists($value, Products::$switchMap)) {
                return Products::$switchMap[$value];
            }
            return null;
        })->width('50');
        $grid->window('Window')->display(function ($value) {
            if (array_key_exists($value, Products::$switchMap)) {
                return Products::$switchMap[$value];
            }
            return null;
        })->width('50');
        $grid->pan_well_shape('Pan Well Shape')->width('120');
        $grid->pan_well_width('Pan Well Width/radius')->width('140');
        $grid->pan_well_height('Pan Well Height')->width('120');
        $grid->applicator_well('Applicator Well')->display(function ($value) {
            if (array_key_exists($value, Products::$switchMap)) {
                return Products::$switchMap[$value];
            }
            return null;
        })->width('100');
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
        });
        $grid->files('Files')->display(function ($files) {
            $btn = new DocumentBtn(count($files), $this->id, self::TAG);
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
            $filter->equal('edges_style', 'Edges Style')->select(self::$edgesStyleMap);
            $filter->equal('pan_well', 'Pan Well#')->select(self::$panWellMap);
            $filter->equal('material', 'Material')->select(self::$materialMap);
            $filter->between('pan_well_width', 'Pan Well Width');
            $filter->equal('applicator_well', 'Applicator Well')->select(Products::$switchMap);
            $filter->equal('closure_mechanism', 'Closure Mechanism')->select(self::$closureMechanismMap);
            $filter->equal('window', 'Window')->select(Products::$switchMap);
            $filter->equal('mirror', 'Mirror')->select(Products::$switchMap);
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
        $exceptField = [];
        if (!Admin::user()->can('page-products-write')) {
            $exceptField = ['vendor_item', 'manufactory_name'];
            $grid->disableCreateButton();
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
        $form->select('pan_well', 'Pan Well#')->options(self::$panWellMap)->rules('required')->setWidth(4);
        $form->text('overall_length', 'Overall Length')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Overall Length must be a number'])->setWidth(4);
        $form->text('overall_width', 'Overall Width')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Overall Width must be a number'])->setWidth(4);
        $form->text('overall_height', 'Overall Height')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Overall Height must be a number'])->setWidth(4);
        $form->divider();
        $form->select('mirror', 'Mirror')->options(Products::$switchMap)->rules('required')->setWidth(4);
        $form->select('window', 'Window')->options(Products::$switchMap)->rules('required')->setWidth(4);
        $form->select('closure_mechanism', 'Closure Mechanism')->options(self::$closureMechanismMap)->rules('required')->setWidth(4);
        $form->text('pan_well_shape', 'Pan Well Shape')->rules('required');
        $form->text('pan_well_width', 'Pan Well Width')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Pan Well Width must be a number'])->setWidth(4);
        $form->text('pan_well_height', 'Pan Well Height')->rules('required|regex:/^\d+(\.\d{0,2})?$/', ['regex' => 'The Pan Well Height must be a number'])->setWidth(4);
        $form->select('applicator_well', 'Applicator Well')->options(Products::$switchMap)->rules('required')->setWidth(4);
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

        $form->saving(function ($form){
            if ($form->files) {
                foreach ($form->files as $file) {
                    if (isset($file['url']) && $file['url']->getClientMimeType() != 'application/pdf') {
                        $error = new MessageBag([
                            'title'   => 'save error',
                            'message' => 'Only support PDF files.',
                        ]);
                        return back()->with(compact('error'));
                    }
                }
            }
        });

//        $form->submitted(function ($form){
//            var_dump($form->files);exit;
//            foreach ($form->files as $file) {
//                if (isset($file['url']->mimeType) && $file['url']->mimeType != 'application/pdf') {
//                    $error = new MessageBag([
//                        'title'   => 'save error',
//                        'message' => 'Only support PDF files.',
//                    ]);
//                    return back()->with(compact('error'));
//                }
//            }
//        });

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

        $switch = Products::$switchMap;
        $edgesStyle = self::$edgesStyleMap;
        $closureMechanism = self::$closureMechanismMap;

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
        $show->edges_style('Edges Style')->as(function ($value) use ($edgesStyle) {
            if (array_key_exists($value, $edgesStyle)) {
                return $edgesStyle[$value];
            }
            return null;
        });
        $show->pan_well('Pan Well#');
        $show->divider();
        $show->overall_length('Overall Height');
        $show->overall_width('Overall Width');
        $show->overall_height('Overall Height');
        $show->mirror('Mirror')->as(function ($value) use ($switch) {
            if (array_key_exists($value, $switch)) {
                return $switch[$value];
            }
            return null;
        });
        $show->window('Window')->as(function ($value) use ($switch) {
            if (array_key_exists($value, $switch)) {
                return $switch[$value];
            }
            return null;
        });
        $show->closure_mechanism('Closure Mechanism')->as(function ($value) use ($closureMechanism) {
            if (array_key_exists($value, $closureMechanism)) {
                return $closureMechanism[$value];
            }
            return null;
        });
        $show->pan_well_shape('Pan Well Shape');
        $show->pan_well_width('Pan Well Width');
        $show->pan_well_height('Pan Well Height');
        $show->applicator_well('Applicator Well')->as(function ($value) use ($switch) {
            if (array_key_exists($value, $switch)) {
                return $switch[$value];
            }
            return null;
        });
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
