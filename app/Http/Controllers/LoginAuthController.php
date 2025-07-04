<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;


class LoginAuthController extends Controller

{
    public function getLogin()
    {
        return view('login.login');
    }
    
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

    // Attempt login for both 'web' and 'faculty' guards with role validation
    $validatedAdmin = auth()->guard('web')->attempt([
        'email' => $request->email,
        'password' => $request->password,
        'role' => 'Administrator',
    ]);

    $validatedStaff = auth()->guard('web')->attempt([
        'email' => $request->email,
        'password' => $request->password,
        'role' => 'staff',
    ]);

        if ($validatedAdmin) {
            return redirect()->route('dashboard')->with('success', 'You have successfully logged in.');
        }
        elseif($validatedStaff) {
            return redirect()->route('home')->with('success', 'You have successfully logged in.');
        }
        else {
            return redirect()->back()->with('error', 'Invalid Credentials');
        }
    }
    
}


