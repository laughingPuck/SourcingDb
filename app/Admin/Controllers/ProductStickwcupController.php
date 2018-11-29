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
use App\Admin\Models\ProductStickwcup;
use Illuminate\Support\Facades\DB;
use App\Admin\Widgets\Action\DeleteRowAction;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Facades\Admin;

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
        $grid->shape('Shape')->width('80');
        $grid->style('Style')->width('80');
        $grid->cup('Cup#')->width('80');
        $grid->cup_size('Cup Size')->width('100');
        $grid->cover_material('Cover Material')->width('120');
        $grid->overall_length('Overall Length')->width('120');
        $grid->overall_height('Overall Height')->width('120');
        $grid->overall_width('Overall Width')->width('120');
        $grid->mechanism('Mechanism')->width('80');
        $grid->storage_location('Storage Location')->width('120');
        $grid->sample_available('Sample Available')->width('120');
        $grid->related_projects('Related Projects')->width('120');
        $grid->moq('Moq')->width('50');
        $grid->price('Price')->width('50');
        $grid->mold_status('Mold Status')->width('80');
        $grid->images('Images')->display(function ($images) {
            return new GalleryBtn(count($images), $this->id, self::TAG);
        })->width('80');
        $grid->created_at('Created At')->width('120');

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
            $filter->like('cosmopak_item', 'Cosmopak#');
            if (Admin::user()->can('page-sensitive-column')) {
                $filter->like('vendor_item', 'Vendor#');
            }
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