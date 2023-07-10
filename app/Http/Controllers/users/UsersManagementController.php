<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UsersManagementController extends Controller
{
  
    public function index()
    {

        $users =  DB::table('users')
            ->join('departements', 'users.departement_id', '=', 'departements.id')
            ->select('users.*', 'departements.depart_name as departement_name')
            ->where('user_active', '=', true)
            ->where('verifie', '=', true)
            ->paginate(4);
            if (Auth::user()->role=='admin'){
            return view('auth.admin.index-users',['users'=>$users]);
            }
            if( Auth::user()->role=='manager'){
            return view('auth.manager.conges.create-conge',['users'=>$users]);
            }
        abort(404);
        
    }
    //search method
public function searchUsers(Request $request)
{
$users=DB::table('users')
            ->join('departements', 'users.departement_id', '=', 'departements.id')
            ->select('users.*', 'departements.depart_name as departement_name')
            ->where('users.user_active', '=', true)
            ->where('users.verifie', '=', true)
            ->where('users.last_name', 'LIKE', '%' .  $request->input('search') . '%')
            ->paginate(4);
    $users->appends($request->all());
    if ( Auth::user()->role=='admin'){
        return view('auth.admin.index-users',['users'=>$users]);
        }
        if( Auth::user()->role=='manager'){
        return view('auth.manager.conges.create-conge',['users'=>$users]);
        }
    abort(404);
}


    public function getAllNotVerifiedUsers()
    {

        $users =  DB::table('users')
            ->join('departements', 'users.departement_id', '=', 'departements.id')
            ->select('users.*', 'departements.depart_name as departement_name')
            ->where('user_active', '=', true)
            ->where('verifie', '=', false)
            ->paginate(4);
        return view('auth.admin.new-registred-users',['users'=>$users]);
    }

    public function searchNotVerifiedUsers(Request $request)
    {
    $users=DB::table('users')
                ->join('departements', 'users.departement_id', '=', 'departements.id')
                ->select('users.*', 'departements.depart_name as departement_name')
                ->where('users.user_active', '=', true)
                ->where('users.verifie', '=', false)
                ->where('users.last_name', 'LIKE', '%' .  $request->input('search') . '%')
                ->paginate(4);
        
        $users->appends($request->all());
        return view('auth.admin.new-registred-users',['users'=>$users]);
    }
    // }

    public function getAllDesactivatedUsers()
    {

        $users =  DB::table('users')
            ->join('departements', 'users.departement_id', '=', 'departements.id')
            ->select('users.*', 'departements.depart_name as departement_name')
            ->where('user_active', '=', false)
            ->where('verifie', '=', true)
            ->paginate(4);
        return view('auth.admin.desactivated-users',['users'=>$users]);
    }

    public function searchDesactivatedUsers(Request $request)
    {
    $users=DB::table('users')
                ->join('departements', 'users.departement_id', '=', 'departements.id')
                ->select('users.*', 'departements.depart_name as departement_name')
                ->where('users.user_active', '=', false)
                ->where('users.verifie', '=', true)
                ->where('users.last_name', 'LIKE', '%' .  $request->input('search') . '%')
                ->paginate(4);
        
        $users->appends($request->all());
        return view('auth.admin.desactivated-users',['users'=>$users]);
    }

    public function create()
    {
        $departs=DB::table('departements')->get();
        return view('auth.admin.create-user',['departements'=>$departs]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'cin' => ['required' ],
            'genre' => ['required'],
            'numdesom' => ['required' ],
            'nationalite' => ['required' ],
            'situation' => ['required' ],
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
            'num_de_som' => $request->numdesom,
            'nationalite' => $request->nationalite,
            'situation' => $request->situation,
            'role' => $request->role,
            'poste' => $request->poste,
            'date_naissance' => $request->datenaissance,
            'phone' => $request->phonenumber,
            'image' => $filename,
            'score' => 22,
            'verifie' => true,
            'user_active' => true,
            'departement_id' => $request->departement,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Enregistré avec succès');
    }


    public function edit($id)
    {
        $departements=DB::table('departements')->get();
        $user =User::find($id);
      return  view('auth.admin.edit-user', compact('departements', 'user'));
    }

 
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'cin' => ['required' ],
            'genre' => ['required'],
            'numdesom' => ['required' ],
            'nationalite' => ['required' ],
            'situation' => ['required' ],
            'role' => ['required'],
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
        $user->num_de_som = $request->numdesom;
        $user->nationalite = $request->nationalite;
        $user->situation = $request->situation;
        $user->genre = $request->genre;
        $user->role = $request->role;
        $user->poste = $request->poste;
        $user->date_naissance = $request->datenaissance;
        $user->phone = $request->phonenumber;
        $user->image = $filename;
        
        $user->verifie = true;
        $user->user_active = true;
        $user->departement_id = $request->departement;
        $user->save();

        return redirect()->route('users.index')->with('success', 'Modifié avec succès');
        
    }

    
    public function verify($id)
    {
        $user =User::find($id);
        $user->verifie = true;
        $user->save();
        return redirect()->route('users.index')->with('success', 'Enregistré avec succès');
    }
    public function activate($id)
    {
        $user =User::find($id);
        $user->user_active=true;
        $user->save();
        return redirect()->route('users.index')->with('success', 'Activé avec succès');
    }
    public function desactivate($id)
    {
        $user =User::find($id);
        $user->user_active=false;
        $user->save();
        return redirect()->route('admin.users.desactives')->with('success', 'Désavtivé avec succès');
        

    }

    public function destroy($id)
    {
        $user =User::find($id);
        $user->delete();
        return redirect()->route('admin.users.desactives')->with('success', 'Suppriméé avec succès');
    }
}
