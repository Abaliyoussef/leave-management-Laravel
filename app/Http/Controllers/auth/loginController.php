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
       
        if (Auth::attempt($credentials,$request->remember)) {
            $request->session()->regenerate();
            $user=Auth::user();
            if($user->role=='admin'){
                return redirect()->route('users.index');
            }else if($user->role=='manager'){
                return redirect()->route('manager.conge.new-demands');
            }
            return redirect()->route('employe.Allconges',['id'=>$user->id]);
        }
 
        return back()->withErrors([
            'email' => 'Les informations d\'identification sont invalides. Veuillez réessayer.',
        ]);
    }
    public function logout(Request $request)
    {

        Auth::logout();
     
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect('/');
    }
}
