<?php

namespace App\Http\Controllers\departement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Departement;
use Illuminate\Support\Facades\DB; 
class departementManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departs=DB::table('departements')
        ->paginate(7);
        return view('auth.admin.departs.index-departs',['departements'=>$departs]);    }

  
    public function searchDeparts(Request $request)
{
$departs=DB::table('departements')
    ->where('depart_name', 'LIKE', '%' .  $request->input('search') . '%')
    ->paginate(7);
    $departs->appends($request->all());
    return view('auth.admin.departs.index-departs',['departements'=>$departs]);
}


    public function create()
    {
        return view('auth.admin.departs.create-depart');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
           
        ]);
        Departement::create([
            'depart_name' => $request->nom,
        ]);

        return redirect()->route('departements.index')->with('success', 'Enregistré avec succès');
    }

    

    public function edit($id)
    {
        $departement=Departement::find($id);
        if($departement){
            return view('auth.admin.departs.edit-depart',['departement'=> $departement]);
        }
        return redirect()->route('departements.index');
    }

   

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
        ]);
        $departement = Departement::find($id);
        if($departement){
            $departement->depart_name=$request->nom;
            $departement->save();
        }
        return redirect()->route('departements.index')->with('success', 'Modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $departement = Departement::find($id);
        if($departement){
            $departement->delete();
        }
        return redirect()->route('departements.index')->with('success', 'Supprimé avec succès');
    }
}
