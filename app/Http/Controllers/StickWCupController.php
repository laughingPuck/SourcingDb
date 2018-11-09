<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\StickWCup;
use Auth;

class StickWCupController extends Controller
{
    public function show()
    {
        return view('stickwcup.show');
    }

    public function create()
    {
        return view('stickwcup.create');
    }
}
