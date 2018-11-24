<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Show;
use App\Admin\Widgets\AdminContent;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\HasResourceActions;
use App\Admin\Models\ProductCompactpalette;
use Illuminate\Support\Facades\DB;

class ProductCompactpaletteController extends Controller
{
    use HasResourceActions;

    const NAME = 'Compact & Palette';
    const URI = 'compact_palette';
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
            ->header('Product '.self::NAME)
            ->description(' ')
            ->body($this->grid()->render());
    }

    public function edit($id, AdminContent $content)
    {
        return $content
            ->header('Product '.self::NAME.' Edit')
            ->description(' ')
            ->body($this->form()->edit($id));
    }

    public function create(AdminContent $content)
    {
        return $content
            ->header('Product '.self::NAME.' Create')
            ->description('')
            ->body($this->form());
    }

    public function show($id, AdminContent $content)
    {
        return $content
            ->header('Product '.self::NAME.' Detail')
            ->description(' ')
            ->row($this->detail($id))
            ->row($this->showImages($id));
    }

    public function grid()
    {
        $grid = new Grid(new self::$productClassName());

//        $grid->id('ID')->sortable();
        $grid->manufactory_name('Manufactory Name');
        $grid->shape('Shape');
        $grid->pan_well('Pan Well');
        $grid->pan_well_width('Pan Well With');
        $grid->applicator_well('Applicator Well');
        $grid->latch_system('Latch System');
        $grid->window('Window');
        $grid->overall_length('Overall Length');
        $grid->overall_width('Overall Width');
        $grid->images('Images')->display(function ($images) {
            $count = count($images);
            if ($count) {
                return "<a href='/gallery/".self::URI."/{$this->id}' class='btn btn-xs btn-success'><i class='fa fa-image'></i>&nbsp;&nbsp;{$count}</a>";
            } else {
                return "<button type='button' disabled='disabled' class='btn btn-xs btn-default'><i class='fa fa-image'></i>&nbsp;&nbsp;{$count}</button>";
            }
        });

//        $grid->state('Display')->display(function ($type) {
//            return $type ? 'on' : 'off';
//        });
        $grid->created_at('Created At');
//        $grid->updated_at('Updated');

        $grid->actions(function (Grid\Displayers\Actions $actions) {
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->between('created_at', 'Created At')->datetime();
            $filter->between('overall_length', 'Overall Length');
            $filter->between('overall_width', 'Overall Width');
            $filter->between('pan_well_width', 'Pan Well Width');
            $filter->equal('shape', 'Shape')->select(self::$shapeMap);
            $filter->equal('pan_well', 'Pan Well')->select(self::$panWellMap);
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
            }, 'Has Images')->select([
                '1' => 'Only with images',
                '0' => 'Only without images',
            ]);
        });

        $grid->expandFilter();

        return $grid;
    }

    public function form()
    {
        $form = new Form(new self::$productClassName());

        $form->display('id', 'ID');

        $form->text('cosmopak_item', 'Cosmopak Item')->rules('required');
        $form->text('vendor_item', 'Vendor Item')->rules('required');
        $form->text('manufactory_name', 'Manufactory Name')->rules('required');
        $form->text('item_description', 'Item Description')->rules('required');
        $form->text('material', 'Material')->rules('required');
        $form->divider();
        $form->select('shape', 'Shape')->options(self::$shapeMap)->rules('required')->setWidth(4);
        $form->select('pan_well', 'Pan Well')->options(self::$panWellMap)->rules('required')->setWidth(4);
        $form->select('mirror', 'Mirror')->options(self::$switchMap)->rules('required')->setWidth(4);
        $form->select('window', 'Window')->options(self::$switchMap)->rules('required')->setWidth(4);
        $form->select('applicator_well', 'Applicator Well')->options(self::$switchMap)->rules('required')->setWidth(4);
        $form->select('injector_pin', 'Injector Pin')->options(self::$switchMap)->rules('required')->setWidth(4);
        $form->divider();
        $form->text('overall_length', 'Overall Length')->rules('required')->setWidth(4);
        $form->text('overall_width', 'Overall Width')->rules('required')->setWidth(4);
        $form->text('overall_height', 'Overall Height')->rules('required')->setWidth(4);
        $form->text('pan_well_shape', 'Pan Well Shape')->rules('required');
        $form->text('pan_well_width', 'Pan Well Width')->rules('required')->setWidth(4);
        $form->text('pan_well_height', 'Pan Well Height')->rules('required')->setWidth(4);
        $form->text('latch_system', 'Latch System')->rules('required')->setWidth(4);
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
            if ($imagesNum) {
                $tools->append('<a href="/gallery/'.self::URI.'/'.$id.'" class="btn btn-sm btn-success" style="width: 150px;margin-right: 5px;"><i class="fa fa-image"></i>&emsp;Check&nbsp;'.$imagesNum.'&nbsp;images</a>');
            } else {
                $tools->append('<button type="button" class="btn btn-sm btn-default" disabled="disabled" style="width: 100px;margin-right: 5px;"><i class="fa fa-image"></i>&emsp;No&nbsp;&nbsp;image</button>');
            }
        });

        $table->increments('id')->unsigned();
        $table->string('cosmopak_item');
        $table->string('vendor_item');
        $table->string('manufactory_name');
        $table->string('item_description');
        $table->string('material');
        $table->string('shape');
        $table->string('pan_well');
        $table->decimal('overall_length');
        $table->decimal('overall_width');
        $table->decimal('overall_height');
        $table->tinyInteger('mirror')->unsigned();
        $table->tinyInteger('window')->unsigned();
        $table->string('pan_well_shape');
        $table->decimal('pan_well_width');
        $table->decimal('pan_well_height');
        $table->tinyInteger('applicator_well')->unsigned();
        $table->tinyInteger('injector_pin')->unsigned();
        $table->string('latch_system');
        $table->string('storage_location');
        $table->string('sample_available');
        $table->string('related_projects');
        $table->string('moq');
        $table->decimal('price');
        $table->tinyInteger('state')->default(1)->unsigned();
        $table->timestamps();
        $table->softDeletes();

        $show->id('ID');
        $show->cosmopak_item('Cosmopak Item');
        $show->vendor_item('Vendor Item');
        $show->manufactory_name('Manufactory Name');
        $show->item_description('Item Description');
        $show->divider();
        $show->material('Material');
        $show->shape('Shape');
        $show->pan_well('Pan Well');
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
        $show->injector_pin('Injector Pin');
        $show->latch_system('Latch System');
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
        $box = new Box('Images', view('admin.productimages', ['imageList' => $images]));
        $box->style('default');

        return $box;
    }
}