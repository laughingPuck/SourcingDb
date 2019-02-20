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

    public $infoExceptions = ['vendor_item', 'manufactory_name', 'price', 'created_at', 'updated_at', 'deleted_at', 'state'];

    public function __construct($cate, $id, $infoExceptions = [])
    {
        $this->cate = $cate;
        $this->id = $id;
        $this->infoExceptions = array_merge($this->infoExceptions, $infoExceptions);
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

        $info = isset($info[0]) ? $info[0] : [];
        foreach ($this->infoExceptions as $item) {
            if (isset($info->$item)) unset($info->$item);
        }

        $content = $this->view('admin.mail.product_info', ['info' => $info]);
        if (isset($images[0])) {
            $content = $content->attach(storage_path('app/admin').'/'.$images[0]->url);
        }
        return $content;
    }
}
