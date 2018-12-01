<?php

namespace App\Admin\Controllers;

use App\Admin\Conf\Products;
use App\Admin\Widgets\AdminContent;
use App\Http\Controllers\Controller;
use Encore\Admin\Show;
use Encore\Admin\Grid;
use Illuminate\Http\Request;
use Encore\Admin\Widgets\Form;
use App\Admin\Models\ProductCategory;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;

class ImportDataController extends Controller
{
    public function index(AdminContent $content, $msg = [])
    {
//        $className = Products::$productCateMap[$request->category]['product_table'];

        if ($msg) {
            if ($msg['type'] == 'error') {
                $content = $content->withError('Import error', $msg['content']);
            } elseif ($msg['type'] == 'success') {
                $content = $content->withSuccess('Import success', $msg['content']);
            }
        }
        return $content
            ->header('Admin > Import')
            ->description(' ')
            ->row($this->importBox())
            ->row($this->tabBox());
    }

    public function import(Request $request)
    {
        if (!$request->file('file')) {
            $this->index(new AdminContent(), ['type' => 'error', 'content' => 'The File is required!']);
        } elseif (!$request->category) {
            $this->index(new AdminContent(), ['type' => 'error', 'content' => 'The Category is required!']);
        } elseif (!array_key_exists($request->category, Products::$productCateMap)) {
            $this->index(new AdminContent(), ['type' => 'error', 'content' => 'Category not fund!']);
        } else {
            $className = Products::$productCateMap[$request->category]['model'];

            $fileBasePath = 'storage/app/';
            $filePath = $request->file('file')->store('admin/temp');

//        var_dump($file->getPath().$file->getFilename(), $file->getClientOriginalExtension());exit;
            $unlinkPath = storage_path('app').'/'.$filePath;
            $controller = $this;
            Excel::load($fileBasePath.$filePath, function($reader) use ($className, $controller, $unlinkPath) {
                $data = $reader->all();
                $i = 0;
                if ($data) {
                    foreach ($data as $row) {
                        $model = new $className();
                        foreach ($row as $k => $v) {
                            $model->$k = $v;
                        }
                        $model->save();
                        $i++;
                    }
                }
                $controller->index(new AdminContent(), ['type' => 'success', 'content' => "Import {$i} rows successful!"]);
                @unlink($unlinkPath);
            });
        }
    }

    protected function importForm()
    {
        $form = new Form();
        $form->select('category', 'Category')->rules('required')->options(ProductCategory::all()->pluck('cate_name', 'link'))->setWidth(3);
        $form->file('file')->setWidth(5);
        $form->disableReset();
        return $form;
    }

    protected function importBox()
    {
        $box = new Box('Import', $this->importForm());
        $box->style('default');

        return $box;
    }

    protected function tabBox()
    {
        $tab = new Tab();
        $tab->title('Required Fields');

        $list = ProductCategory::all();
        foreach ($list as $cate) {
            if (!array_key_exists($cate->link, Products::$productCateMap)) {
                continue;
            }
            $columns = Schema::getColumnListing(Products::$productCateMap[$cate->link]['product_table']);
            unset($columns[array_search('id', $columns)]);
            unset($columns[array_search('created_at', $columns)]);
            unset($columns[array_search('updated_at', $columns)]);
            unset($columns[array_search('deleted_at', $columns)]);
            unset($columns[array_search('state', $columns)]);
            $rows = [];

            foreach ($columns as $v) {
                $rows[] = [$v];
            }
            $table = new Table([], $rows);
            $tab->add($cate->cate_name, $table->render());
        }

        return $tab;
    }
}