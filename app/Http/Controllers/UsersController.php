<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;

class UsersController extends Controller
{
    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        return view('users.show' , compact('user'));
    }

    public function edit($id)
    {
        return view('users.edit', compact('user', 'id'));
        //return redirect()->route('users.show', [$user]);
    }
}
