<?php
/**
 * Created by PhpStorm.
 * User: taylorfeng
 * Date: 2018/12/7
 * Time: 17:05
 */

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use App\Admin\Widgets\Excel;
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
                $exceptFields = array_merge($exceptFields, ['id', 'image', 'files', 'images', 'state', 'deleted_at']);
                // 这段逻辑是从表格数据中取出需要导出的字段
                $keys = [];
                if (isset($this->getData(false)[0])) {
                    $data = json_decode(json_encode($this->getData(false)[0]), true);
                    $keys = array_keys($data);
                    $keys = array_diff($keys, $exceptFields);
                }
                $rows = collect($this->getData())->map(function ($item) use ($exceptFields) {
                    return array_except($item, $exceptFields);
                });

                $rows = $rows->toArray();
                array_unshift($rows, $keys);
                $sheet->rows($rows);
            });

        })->export('xls');
    }
}