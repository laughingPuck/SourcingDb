<?php

namespace App\Admin\Controllers;

use App\Admin\Mail\ProductInfo;
use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Show;
use Encore\Admin\Grid;
use App\Admin\Conf\Products;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Support\Facades\DB;

class ProductPDFController extends Controller
{
    public function download($cate, $id)
    {
        $productInfo = DB::table(Products::$productCateMap[$cate]['product_table'])->where('id', $id)->whereNull('deleted_at')->first();
        $images = DB::table(Products::$productCateMap[$cate]['img_table'])->where('product_id', $id)->whereNull('deleted_at')->get();

        $html = '';
        $html .= "<h1>Product Info:</h1><br/>";
        $html .= "<table>";
        foreach ($productInfo as $k => $v) {
            $html .= "<tr><td>{$k}</td><td>{$v}</td></tr>";
        }
        $html .= "</table>";

        $html .= "<br/><h1>Images:</h1><br/>";
        if ($images) {
            foreach ($images as $v) {
                $html .= "<img src=\"http://sdb.networkexpert.net/{$v->url}\" width='200' style='max-height: 200px;' alt=\"{$v->title}\">";
            }
        } else {
            $html .= "no image";
        }

        $html2pdf = new Html2Pdf();
        $html2pdf->writeHTML($html);
        $html2pdf->output('1.pdf', 'D');
    }
}