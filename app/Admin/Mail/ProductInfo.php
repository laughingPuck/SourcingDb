<?php

namespace App\Admin\Mail;

use App\Admin\Conf\Products;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProductInfo extends Mailable
{
    use Queueable, SerializesModels;

    public $cate;
    public $id;

    public function __construct($cate, $id)
    {
        $this->cate = $cate;
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $info = [];
        $images = [];
        if (array_key_exists($this->cate, Products::$productCateMap)) {
            $table = Products::$productCateMap[$this->cate]['product_table'];
            $imgTable = Products::$productCateMap[$this->cate]['img_table'];
            $info = DB::table($table)->where('id', $this->id)->get();
            $images = DB::table($imgTable)->where('product_id', $this->id)->whereNull('deleted_at')->get();
        }
        $content = $this->view('admin.mail.product_info', ['info' => $info]);
        foreach ($images as $image) {
            $content = $content->attach(storage_path('app/admin').'/'.$image->url);
        }
        return $content;
    }
}
