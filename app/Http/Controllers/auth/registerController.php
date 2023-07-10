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
            'numdesom' => ['required' ],
            'nationalite' => ['required' ],
            'situation' => ['required' ],
            'genre' => ['required'],
            //'departement' => ['required'],
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
            'num_de_som' => $request->numdesom,
            'nationalite' => $request->nationalite,
            'situation' => $request->situation,
            'role' => "user",
            'poste' => $request->poste,
            'date_naissance' => $request->datenaissance,
            'phone' => $request->phonenumber,
            'image' => $filename,
            'score' => 22,
            'verifie' => false,
            'user_active' => true,
            'departement_id' => 1,//$request->departement,
            'password' => Hash::make($request->password),
        ]);
        Auth::login($user);
        return redirect('/');
    }
    public function profile()
    {
        $user=Auth::user();
        $departs=DB::table('departements')->get();
        if($user->role=='admin'){
            return view('auth.admin.profile',['departements'=>$departs]);
        }else if($user->role=='manager'){
            return view('auth.manager.profile',['departements'=>$departs]);
        }
        return view('auth.employe.profile',['departements'=>$departs]);
}

    //update user profile infos
    public function updateProfil(Request $request, $id)
    {
        
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'cin' => ['required' ],
            'genre' => ['required'],
            'password' => ['confirmed'],
            'departement' => ['required'],
            'poste' => ['required'],
            'datenaissance' => ['required', 'date'],
            'phonenumber' => ['required', 'numeric','digits:10'],
            'email' => ['required', 'string', 'email'],
        ]);
        $user = User::find($id);
        $filename=$user->image;
        $file= $request->file('photo');
        if($file){
            $filename= date('YmdHis').'_'.$file->getClientOriginalName();
            $file-> move(public_path('Image'), $filename);
            if($user->image != 'no-image.png'){
                $imagePath = public_path('Image\\'.$user->image);
                unlink($imagePath);
                }
        }
        
        
        $user->last_name = $request->nom;
        $user->first_name = $request->prenom;
        $user->cin = $request->cin;
        $user->email = $request->email;
        $user->genre = $request->genre;
        $user->poste = $request->poste;
        $user->date_naissance = $request->datenaissance;
        $user->phone = $request->phonenumber;
        $user->image = $filename;
        $user->verifie = true;
        $user->user_active = true;
        if($request->password){
            $user->password=Hash::make($request->password);
        }
        $user->departement_id = $request->departement;
        $user->save();

        return redirect()->back()->with('success', 'Modifié avec succès!');
        
    }
}
