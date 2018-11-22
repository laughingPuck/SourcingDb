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
            ->header('Index')
            ->description('Products...')
            ->row(view('admin.index', ['imageList' => $images]));
    }
}
