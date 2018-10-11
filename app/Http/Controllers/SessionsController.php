<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Auth;

class SessionsController extends Controller
{
    public function create() {
        return view('sessions.create');
    }

    public function store(Request $request) {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            //landing page
            session()->flash('success', 'Welcome!');
            return redirect()->route('users.show', [Auth::user()]);
        } else {
            //fail alert
            session()->flash('danger', 'Sorry that your mailbox and password do not match!');
            return redirect()->back();
        }
        return;
    }
}
