<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class userManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userEmail = Auth::user()->email;
        $users =  DB::table('users')
            ->join('departements', 'users.departement_id', '=', 'departements.id')
            ->select('users.*', 'departements.depart_name as departement_name')
            ->where('email', '!=', $userEmail)
            ->where('user_active', '=', true)
            ->where('verifie', '=', true)
            ->paginate(4);
        return view('auth.admin.index-users',['users'=>$users]);
    }
    //search method
public function searchUsers(Request $request)
{
    $userEmail = Auth::user()->email;
$users=DB::table('users')
            ->join('departements', 'users.departement_id', '=', 'departements.id')
            ->select('users.*', 'departements.depart_name as departement_name')
            ->where('users.email', '!=', $userEmail)
            ->where('users.user_active', '=', true)
            ->where('users.verifie', '=', true)
            ->where('users.last_name', 'LIKE', '%' .  $request->input('search') . '%')
            ->paginate(4);
    $users->appends($request->all());
    return view('auth.admin.index-users',['users'=>$users]);
}


    public function getAllNotVerifiedUsers()
    {
        $userEmail = Auth::user()->email;

        $users =  DB::table('users')
            ->join('departements', 'users.departement_id', '=', 'departements.id')
            ->select('users.*', 'departements.depart_name as departement_name')
            ->where('email', '!=', $userEmail)
            ->where('user_active', '=', true)
            ->where('verifie', '=', false)
            ->paginate(4);
        return view('auth.admin.new-registred-users',['users'=>$users]);
    }

    public function searchNotVerifiedUsers(Request $request)
    {
        $userEmail = Auth::user()->email;
    $users=DB::table('users')
                ->join('departements', 'users.departement_id', '=', 'departements.id')
                ->select('users.*', 'departements.depart_name as departement_name')
                ->where('users.email', '!=', $userEmail)
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
        $userEmail = Auth::user()->email;

        $users =  DB::table('users')
            ->join('departements', 'users.departement_id', '=', 'departements.id')
            ->select('users.*', 'departements.depart_name as departement_name')
            ->where('email', '!=', $userEmail)
            ->where('user_active', '=', false)
            ->where('verifie', '=', true)
            ->paginate(4);
        return view('auth.admin.desactivated-users',['users'=>$users]);
    }

    public function searchDesactivatedUsers(Request $request)
    {
        $userEmail = Auth::user()->email;
    $users=DB::table('users')
                ->join('departements', 'users.departement_id', '=', 'departements.id')
                ->select('users.*', 'departements.depart_name as departement_name')
                ->where('users.email', '!=', $userEmail)
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

        return redirect()->route('users.index');
        

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
        }
        
        
            $user->last_name = $request->nom;
            $user->first_name = $request->prenom;
            $user->cin = $request->cin;
            $user->email = $request->email;
            $user->genre = $request->genre;
            $user->role = $request->role;
            $user->poste = $request->poste;
            $user->date_naissance = $request->datenaissance;
            $user->phone = $request->phonenumber;
            $user->image = $filename;
            $user->score = 22;
            $user->verifie = true;
            $user->user_active = true;
            $user->departement_id = $request->departement;
            $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('users.index');
        
    }
    public function verify($id)
    {
        $user =User::find($id);
        $user->verifie = true;
        $user->save();
        return redirect()->route('admin.users.new-registred');
    }
    public function activate($id)
    {
        $user =User::find($id);
        $user->user_active=true;
        $user->save();
        return redirect()->route('admin.users.desactives');
    }

    public function destroy($id)
    {
        $user =User::find($id);
        $user->user_active=false;
        $user->save();
        return redirect()->route('users.index');
    }
}
