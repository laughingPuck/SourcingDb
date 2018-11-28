<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $images = DB::table('product_categories')->whereNull('deleted_at')->get();
        return $content
            ->header('Home')
            ->description('Products Menu')
            ->row(view('admin.index', ['imageList' => $images, 'urlPrefix' => env('APP_URL').'/'.config('admin.route.prefix').'/']));
    }
}
