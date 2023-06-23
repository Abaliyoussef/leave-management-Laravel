<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class registerController extends Controller
{
    public function register(){
        $departs=DB::table('departements')->get();
        return view('auth.register',['departements'=>$departs]);
    }
  
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'cin' => ['required' ],
            'genre' => ['required'],
            'departement' => ['required'],
            'poste' => ['required'],
            'datenaissance' => ['required', 'date'],
            'phonenumber' => ['required', 'numeric','digits:10'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required','confirmed','min:8'],
        ]);
        $filename='no-image.png';
        $file= $request->file('photo');
        if($file){
            $filename= date('YmdHis').'_'.$file->getClientOriginalName();
            $file-> move(public_path('Image'), $filename);
        }
        
        $user = User::create([
            'last_name' => $request->nom,
            'first_name' => $request->prenom,
            'cin' => $request->cin,
            'email' => $request->email,
            'genre' => $request->genre,
            'role' => "user",
            'poste' => $request->poste,
            'date_naissance' => $request->datenaissance,
            'phone' => $request->phonenumber,
            'image' => $filename,
            'score' => 22,
            'verifie' => false,
            'user_active' => true,
            'departement_id' => $request->departement,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/');
    }
}
