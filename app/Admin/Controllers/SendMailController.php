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
use Encore\Admin\Facades\Admin;
use App\Admin\Models\ProductEmailLog;

class SendMailController extends Controller
{
    public function productGridMail(Request $request)
    {
        $cate = $request->cate;
        $id = $request->id;
        $address = $request->address;

        if (!preg_match('/^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/', $address)) {
            $this->ajaxRes(-1, 'Wrong email address');
        }
//        if ('747591224@qq.com' != $address) {
//            $this->ajaxRes(-1, 'Wrong Email Address');
//        }
//        print_r(Admin::user()->username);exit;
        if (!array_key_exists($cate, Products::$productCateMap)) {
            $this->ajaxRes(-1, 'No such product');
        } else {
            $content = 'Test email.';
            $toMail = $address;

            // log
            $log = new ProductEmailLog();
            $log->user_id = Admin::user()->id;
            $log->username = Admin::user()->username;
            $log->product_id = $id;
            $log->product_cate = $cate;
            $log->to_email = $address;
            $log->save();

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