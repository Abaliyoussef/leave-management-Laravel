<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function login(){
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $user =Auth::user();
            $request->session()->regenerate();
            $user->user_active=true;
            $user->save();
            return redirect()->intended('/');
        }
 
        return back()->withErrors([
            'email' => 'Les informations d\'identification sont invalides. Veuillez réessayer.',
        ]);
    }
    public function logout(Request $request)
    {
        $user =Auth::user();
        $user->user_active=false;
        $user->save();
        Auth::logout();
     
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/');
    }
}
