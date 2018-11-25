<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Form;
use Encore\Admin\Show;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SendMailController extends Controller
{
    public function productGridMail(Request $request)
    {
        $cate = $request->cate;
        $id = $request->id;
        $address = $request->address;

        if ('747591224@qq.com' != $address) {
            $this->ajaxRes(-1, 'Wrong Mail Address');
        }

        if (!array_key_exists($cate, ImageGalleryController::$productCateMap)) {
            $this->ajaxRes(-1, 'No such product');
        } else {
            $content = '这是一封来自Laravel的测试邮件.';
            $toMail = $address;

            Mail::raw($content, function ($message) use ($toMail) {
                $message->subject('[ 测试 ] 测试邮件SendMail - ' .date('Y-m-d H:i:s'));
                $message->to($toMail);
            });
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