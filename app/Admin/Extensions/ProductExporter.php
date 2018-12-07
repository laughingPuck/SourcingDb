<?php
/**
 * Created by PhpStorm.
 * User: taylorfeng
 * Date: 2018/12/7
 * Time: 17:05
 */

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;
use Encore\Admin\Grid;

class ProductExporter extends AbstractExporter
{
    public $cate;
    public $exceptFields;

    public function __construct(Grid $grid, $cate, $exceptFields = [])
    {
        $this->cate = $cate;
        $this->exceptFields = $exceptFields;
        parent::__construct($grid);
    }

    public function export()
    {
        $cate = $this->cate;
        $exceptFields = $this->exceptFields;
        Excel::create($cate, function($excel) use ($exceptFields) {

            $excel->sheet('export data', function($sheet) use ($exceptFields) {

                // 这段逻辑是从表格数据中取出需要导出的字段
                $rows = collect($this->getData())->map(function ($item) use ($exceptFields) {
                    $exceptFields = array_merge($exceptFields, ['id', 'images', 'state']);
                    return array_except($item, $exceptFields);
                });

                $sheet->rows($rows);

            });

        })->export('xls');
    }
}