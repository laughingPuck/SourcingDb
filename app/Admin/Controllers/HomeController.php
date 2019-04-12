<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Facades\Admin;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $images = DB::table('product_categories')->whereNull('deleted_at')->get();

        foreach ($images as $k => $image) {
            if (!Admin::user()->can('page-index-all-products') && $image->id >= 4) {
                unset($images[$k]);
            }
        }

        return $content
            ->header('Home')
            ->description('Products Menu')
            ->row(view('admin.index', ['imageList' => $images, 'urlPrefix' => env('APP_URL').'/'.config('admin.route.prefix').'/']));
    }
}
