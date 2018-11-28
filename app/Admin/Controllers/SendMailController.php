<?php

namespace App\Admin\Controllers;

use App\Admin\Mail\ProductInfo;
use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Show;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\Mail;
use App\Admin\Conf\Products;
use Illuminate\Http\Request;

class SendMailController extends Controller
{
    public function productGridMail(Request $request)
    {
        $cate = $request->cate;
        $id = $request->id;
        $address = $request->address;

//        if ('747591224@qq.com' != $address) {
//            $this->ajaxRes(-1, 'Wrong Email Address');
//        }

        if (!array_key_exists($cate, Products::$productCateMap)) {
            $this->ajaxRes(-1, 'No such product');
        } else {
            $content = 'Test email.';
            $toMail = $address;

            Mail::to($address)->send(new ProductInfo($cate, $id));

//            Mail::raw($content, function ($message) use ($toMail) {
//                $message->subject('[ Test ] testing SendMail - ' .date('Y-m-d H:i:s'));
//                $message->to($toMail);
//            });
            $this->ajaxRes(0, 'Success');
        }
    }

    private function ajaxRes($code, $msg, $data = [])
    {
        $res = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        echo json_encode($res);
        exit;
    }

}