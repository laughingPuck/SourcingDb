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
            'name' => 'required|max:50',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            //landing page
            session()->flash('success', 'Hi '. (Auth::user()->name) .',Welcome to Sourcing DB System.' );
            return redirect()->route('show', [Auth::user()]);
        } else {
            //fail alert
            session()->flash('danger', 'Sorry that your user name and password do not match!');
            return redirect()->back();
        }
        return;
    }

    public function show(User $user)
    {
        return view('sessions.show' , compact('user'));
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', 'Successfully Log Out！');
        return redirect('/');
    }
}
