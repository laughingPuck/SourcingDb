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

class ProductExporter extends AbstractExporter
{
    public function export()
    {
        Excel::create('Product Data', function($excel) {

            $excel->sheet('export data', function($sheet) {

                // 这段逻辑是从表格数据中取出需要导出的字段
                $rows = collect($this->getData())->map(function ($item) {
                    return array_except($item, ['id', 'images', 'state']);
                });

                $sheet->rows($rows);

            });

        })->export('xls');
    }
}