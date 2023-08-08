<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Providers\FamilialeState;

class registerController extends Controller
{
    public function register(){
        $departements=DB::table('departements')->get();
        $situationsFamiliales=FamilialeState::getFamilialeStates();
        return view('auth.register', compact('departements', 'situationsFamiliales'));
    }
  
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'nom_ar' => ['required'],
            'prenom_ar' => ['required'],
            'cin' => ['required' ],
            'numdesom' => ['required' ],
            'nationalite' => ['required' ],
            'nationalite_ar' => ['required' ],
            'situation' => ['required' ],
            'genre' => ['required'],
            'departement' => ['required'],
            'poste' => ['required'],
            'poste_ar' => ['required'],
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
            'last_name_ar' => $request->nom_ar,
            'first_name_ar' => $request->prenom_ar,
            'cin' => $request->cin,
            'email' => $request->email,
            'genre' => $request->genre,
            'num_de_som' => $request->numdesom,
            'nationalite' => $request->nationalite,
            'nationalite_ar' => $request->nationalite_ar,
            'situation' => $request->situation,
            'role' => "user",
            'poste' => $request->poste,
            'poste_ar' => $request->poste_ar,
            'date_naissance' => $request->datenaissance,
            'phone' => $request->phonenumber,
            'image' => $filename,
            'score' => 22,
            'verifie' => false,
            'user_active' => true,
            'departement_id' =>$request->departement,
            'password' => Hash::make($request->password),
        ]);
        Auth::login($user);
        return redirect()->route('login');
    }
    public function profile()
    {
        $departements=DB::table('departements')->get();
        $situationsFamiliales=FamilialeState::getFamilialeStates();
        
        $user=Auth::user();
        if($user->role=='admin'){
            return view('auth.admin.profile', compact('departements', 'situationsFamiliales'));
        }else if($user->role=='manager'){
            return view('auth.manager.profile', compact('departements', 'situationsFamiliales'));
        }
        return view('auth.employe.profile', compact('departements', 'situationsFamiliales'));
    }

    //update user profile infos
    public function updateProfil(Request $request, $id)
    {
        
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'nom_ar' => ['required'],
            'prenom_ar' => ['required'],
            'cin' => ['required' ],
            'genre' => ['required'],
            'nationalite' => ['required'],
            'nationalite_ar' => ['required'],
            'password' => ['confirmed'],
            'departement' => ['required'],
            'poste' => ['required'],
            'poste_ar' => ['required'],
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
        $user->last_name_ar = $request->nom_ar;
        $user->first_name_ar = $request->prenom_ar;
        $user->cin = $request->cin;
        $user->email = $request->email;
        $user->genre = $request->genre;
        $user->nationalite = $request->nationalite;
        $user->nationalite_ar = $request->nationalite_ar;
        $user->poste = $request->poste;
        $user->poste_ar =$request->poste_ar;
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
