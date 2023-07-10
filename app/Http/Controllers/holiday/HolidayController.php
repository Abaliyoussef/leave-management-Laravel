<?php

namespace App\Http\Controllers\holiday;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use App\Models\Conge;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;
use App\Models\User;

class HolidayController extends Controller
{
    
    public function index(){
        $holidays=DB::table('holidays')->orderBy('holiday_date')->paginate(5);
        return view('auth.manager.holiday.index-holidays',['holidays'=>$holidays]);
    }

    //this method for showing holidays in the employee dashboard
    public function holidays(){
        $holidays=DB::table('holidays')->orderBy('holiday_date')->paginate(5);
        return view('auth.employe.index-holidays',['holidays'=>$holidays]);
    }
   
    public function create()
    {
        return view('auth.manager.holiday.create-holiday');
    }

    
    public function store(Request $request)
    {
        
        $request->validate([
            "date"=>['required','unique:App\Models\Holiday,holiday_date'],
            "description"=>"required"
        ]);

        $holiday = new Holiday();

        $holiday->holiday_date = $request->date;
        $holiday->description = $request->description;

        $conges = Conge::with('user')->where([['status','!=','Annulé'],['date_fin','>',Carbon::today()]])->get();
        foreach($conges as $conge){
            $startingDate = Carbon::parse($conge->date_debut);
            $end_date = Carbon::parse($conge->date_fin);
            $dateToCheck=Carbon::parse($request->date);
            if ($dateToCheck->between($startingDate, $end_date)&& !$dateToCheck->isWeekend()) {
               
                $user=User::find($conge->user_id);
                $user->score++;
                $user->save();
                $conge->duree--;
                $conge->save();
            } 
           }

        $holiday->save();
        return redirect()->route('holidays.index')->with('success', 'Enregistré avec succès');
    }

   
    public function show($id)
    {
        return view('auth.manager.holiday.edit-holiday',['holiday'=>Holiday::find($id)]);
    }

   
    public function refrechCongeDuration()
    {
        $conges=Conge::find();
        
    }

    
    public function update(Request $request, $id)
    {
        
        $request->validate([
            "date"=>['required','unique:App\Models\Holiday,holiday_date'],
            "description"=>"required"
        ]);

        $holiday =Holiday::find($id);


        
        $conges = Conge::with('user')->where([['status','!=','Annulé'],['date_fin','>',Carbon::today()]])->get();
        foreach($conges as $conge){
            $startingDate = Carbon::parse($conge->date_debut);
            $end_date = Carbon::parse($conge->date_fin);
            $newDate=Carbon::parse($request->date);
            $oldDate=Carbon::parse($holiday->holiday_date);
            if ($newDate->between($startingDate, $end_date)&& !$newDate->isWeekend()) {
               
                $user=User::find($conge->user_id);
                $user->score++;
                $user->save();
                $conge->duree--;
                $conge->save();
            }
            if ($oldDate->between($startingDate, $end_date )&& !$oldDate->isWeekend()) {
               
                $user=User::find($conge->user_id);
                $user->score--;
                $user->save();
                $conge->duree++;
                $conge->save();
            }  
           }
           
        $holiday->holiday_date = $request->date?$request->date:$holiday->holiday_date;
        $holiday->description = $request->description?$request->description:$holiday->description;
        $holiday->save();
        return redirect()->route('holidays.index')->with('success', 'Modifié avec succès');
    }

   
    public function destroy($id)
    {
        $holiday=Holiday::find($id);
        $conges = Conge::with('user')->where([['status','!=','Annulé'],['date_fin','>',Carbon::today()]])->get();
        foreach($conges as $conge){
            $startingDate = Carbon::parse($conge->date_debut);
            $end_date = Carbon::parse($conge->date_fin);
            $dateToCheck=Carbon::parse($holiday->holiday_date);
            if ($dateToCheck->between($startingDate, $end_date)&& !$dateToCheck->isWeekend()) {
               
                $user=User::find($conge->user_id);
                $user->score--;
                $user->save();
                $conge->duree++;
                $conge->save();
            } 
           }
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Supprimé avec succès');
    }
}
