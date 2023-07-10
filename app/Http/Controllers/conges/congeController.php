<?php

namespace App\Http\Controllers\conges;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Models\Conge;
use App\Models\Holiday;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use TCPDF;

class congeController extends Controller
{

    public function createConge($id){
        $user=User::find($id);
        if(Auth::user()->role=='manager'){
          return view('auth.manager.conges.addingCongeForm',['user'=>$user]);  
        }
        return view('auth.employe.addingCongeForm',['user'=>$user]);  

    }
    public function createSuggestion($id){
        $conge=Conge::find($id);
          return view('auth.manager.conges.suggestionForm',['conge'=>$conge]);   

    }


    public function storeConge(Request $request){

        $request->validate([
            "date_debut"=>['after:today'],
            "duree"=>['required','gt:0'],
            "user_id"=>['required' ]
        ]);
        
 
        $startingDate = Carbon::parse($request->input('date_debut'));
        $duration = $request->duree-1;
        $end_date = $startingDate;
        $holidays = Holiday::whereDate('holiday_date', '>=', $startingDate)->pluck('holiday_date')->toArray();
        
        if ($startingDate->isWeekend() || in_array($startingDate->toDateString(), $holidays)) {
            return back()->withErrors([
                'erreur' => 'Congé non enregistré.le jour choisi est un weekend ou un jour férié',
            ]);
        }
        while ($duration > 0) {
            $end_date->addDay();
        
            // Skip weekends (Saturday and Sunday)
            if ($end_date->isWeekend()) {
                continue;
            }
            // Skip non-working days (holidays)
            
            if (in_array($end_date->toDateString(), $holidays)) {
                continue;
            }
        
            $duration--;
        }
       


        $conge = new Conge();
        $conge->date_debut = $request->date_debut;
        $conge->date_fin =  $end_date->toDateString();
        $conge->description = $request->description;
        $conge->duree=$request->duree;
        $conge->status = $request->status ? $request->status : 'Pending';
        $conge->user_id = $request->user_id;
        
        //updating user's score
        $user=User::find($request->user_id);
          if($user->score-$request->duree<0){
            return back()->withErrors([
                'erreur' => 'Congé non enregistré.veuillez consulter le solde des jours restant',
            ]);
          }
        $user->score-=$request->duree;
        $conge->save();
        $user->save();
        return redirect()->back()->with('success', 'Entregistré avec succès');
}
public function updateConge(Request $request, $id){
    
    $request->validate([
        "date_debut"=>['before_or_equal:date_fin' ,'after:today'],
    ]);
    
        $conge = Conge::find($id);
        $conge->date_debut = !empty($request->date_debut) ? $request->date_debut : $conge->date_debut;
        $conge->date_fin = !empty($request->date_fin) ? $request->date_fin : $conge->date_fin;
        $conge->description = !empty($request->description) ? $request->description : $conge->description;
        $conge->demande_annulation =  $request->demande_annulation=='1' ? true : false;
        $conge->status = !empty($request->status) ? $request->status : $conge->status;
        $conge->user_id = !empty($request->user_id) ? $request->user_id : $conge->user_id;
        if($request->status=='Annulé'){
            $user=User::find($conge->user_id);
            $user->score+=$conge->duree;
            $user->save();
        }
        $conge->save();


        return redirect()->back();
}

public function allPendingConges(){  
    $conges = Conge::with('user.departement')
    ->where([["status","Pending"],['date_debut','>=',Carbon::today()]])
    ->orwhere([["status","proposé"],['date_debut','>=',Carbon::today()]])
    ->orderBy('id','desc')
    ->paginate(3);
    return view('auth.manager.conges.new-demands',['conges'=>$conges]);
}

public function searchAllPendingConges(Request $request){ 
    $data=$request->search; 
    $conges = Conge::with('user.departement')
    ->where([["status","Pending"],['date_debut','>=',Carbon::today()]])
    ->orwhere([["status","proposé"],['date_debut','>=',Carbon::today()]])
    ->whereIn('user_id', static function($query) use($data){
        $query->select(['id'])
            ->from('users')
            ->where('last_name' ,'like',"%{$data}%")
            ->orWhere('first_name' ,'like',"%{$data}%");})
    ->orderBy('id','desc')
    ->paginate(3);
    $conges->appends($request->all());
    return view('auth.manager.conges.new-demands',['conges'=>$conges]);
    
}

public function allNotPendingConges(){ 
    $conges = Conge::with('user.departement')->where([["status","=","Accordé"],['date_debut','>=',Carbon::today()]])->orderBy('id','desc')->paginate(3);
    return view('auth.manager.conges.all-demands',['conges'=>$conges]);
}

public function searchAllNotPendingConges(Request $request){ 
    $data=$request->search; 
    $conges = Conge::with('user.departement')->where([["status","=","Accordé"],['date_debut','>=',Carbon::today()]])
    ->whereIn('user_id', static function($query) use($data){
        $query->select(['id'])
            ->from('users')
            ->where('last_name' ,'like',"%{$data}%")
            ->orWhere('first_name' ,'like',"%{$data}%");})
    ->orderBy('id','desc')
    ->paginate(3);
    $conges->appends($request->all());
    return view('auth.manager.conges.all-demands',['conges'=>$conges]);
}


public function inProgressConges(){ 
    $conges = Conge::with('user.departement')->where([['status','=','Accordé'],['date_debut','<=',Carbon::today()],['date_fin','>=',Carbon::today()],['status','=','Accordé']])->orderBy('id','desc')->paginate(3);

    return view('auth.manager.conges.inProgress-conge',['conges'=>$conges]);
}

public function archivedConges(){ 
    $conges = Conge::with('user.departement')->where([['status','=','Accordé'],['date_fin','<',Carbon::today()]])->orderBy('id','desc')->paginate(3);

    return view('auth.manager.conges.archived-conge',['conges'=>$conges]);
}
public function searchArchivedConges(Request $request){ 
    $data = $request->search;
    $conges = Conge::with('user.departement')
    ->where([['status','=','Accordé'],['date_fin','<=',Carbon::today()]])
    ->whereIn('user_id', static function($query) use($data){
      $query->select(['id'])
          ->from('users')
          ->where('last_name' ,'like',"%{$data}%")
          ->orWhere('first_name' ,'like',"%{$data}%");
    })
    ->orderBy('id','desc')
    ->paginate(3);
    $conges->appends($request->all());
    return view('auth.manager.conges.archived-conge',['conges'=>$conges]);
}


public function demandeAnnulationConges(){ 
    $conges = Conge::with('user.departement')->where([['status','=','annulation...'],['date_debut','>=',Carbon::today()]])->orderBy('id','desc')->paginate(3);
    return view('auth.manager.conges.annulation-demands',['conges'=>$conges]);
}

public function searchDemandeAnnulationConges(){ 
    $data = $request->search;
    $conges = Conge::with('user.departement')->where([['status','=','annulation...'],['date_debut','>=',Carbon::today()]])
    ->whereIn('user_id', static function($query) use($data){
        $query->select(['id'])
            ->from('users')
            ->where('last_name' ,'like',"%{$data}%")
            ->orWhere('first_name' ,'like',"%{$data}%");
      })
      ->orderBy('id','desc')
      ->paginate(3);
    $conges->appends($request->all());
    return view('auth.manager.conges.annulation-demands',['conges'=>$conges]);

}
public function delete($id){
    
        $conge = Conge::find($id);
        
        if($conge->status != 'Annulé'){
        $user=User::find($conge->user_id);
        $user->score+=$conge->duree;
        $user->save();
        }

        $conge->delete();
        return redirect()->back();

    
}
public function userNotPendingConges($id){  
    $conges = Conge::where("user_id",$id)->where([["status","!=","proposé"],['date_fin','>=',Carbon::today()]])->orderBy('date_debut','desc')->paginate(6);
    return view('auth.employe.my-demands',['conges'=>$conges]);

}
public function userSuggestedConges($id){  
    $conges = Conge::where("user_id",$id)->where([["status","=","proposé"],['date_debut','>=',Carbon::today()]])->orderBy('date_debut','desc')->paginate(6);
    return view('auth.employe.suggested-conges',['conges'=>$conges]);
}
public function userExpiredConges($id){  
    $conges = Conge::where("user_id",$id)->where([["status","=","Accordé"],['date_fin','<',Carbon::today()]])->orderBy('date_debut','desc')->paginate(6);
    return view('auth.employe.expired-conges',['conges'=>$conges]);
}
public function generateDecision($id){  
    $conge = Conge::with('user.departement')->find($id);
    $html='

    <style>

        .titre {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .sec-titre {
            font-size: 14px;
            font-weight: bold;
            
        }
        span{
              font-weight: normal;
          }
        .date {
            text-align: right;
            margin-top: 30px;
        }
        p{
            font-weight: bold;
        }
    </style>

    <div class="titre">DECISION DE CONGE</div>
    
    <div >
        <div class="titre">DECIDE</div>
        <div >
            <p><span >Congé administratif d’une durée de <span style="font-weight: bold;" >'.$conge->duree.'</span> jours est accordé à:</span ></p>
            <p>Salarié(e)&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;: <span >'.$conge->user->first_name.' '.$conge->user->last_name.'</span></p>
            <p>Grade&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;: <span >'.$conge->user->poste.'</span></p>
            <p>D.R.P.P&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;: <span >'.$conge->user->num_de_som.'</span></p>
            <p>Affectation&emsp;&emsp;&emsp;&emsp;&emsp;: <span >DSI</span></p>
            <p>Pour la période du&emsp;: <span >'.$conge->date_debut.' à '.$conge->date_fin.'</span></p>
        </div>
    </div>
    
    
        <p class="sec-titre">ARTICLE I</p>
        <p><span >L’intéressé est autorisé éventuellement à quitter le Territoire national pour la même période.</span ></p>
        <div>  </div>
        <p class="sec-titre">ARTICLE II</p>
        <p><span >L’intéressé est tenue d’aviser le service compétent dès sa reprise de travail après expiration du congé susvisé.</span ></p>
        <div>  </div>
   
    
    <div class="date">Fait à Casablanca le <span style="font-weight: bold;" class="decision-date">'.Carbon::today()->toDateString().'</span></div>


';

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // ---------------------------------------------------------    
 // set font
$pdf->SetFont('dejavusans', '', 12);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// add a page
$pdf->AddPage();
// Persian and English content
$pdf->WriteHTML($html, true, 0, true, 0);



//Close and output PDF document
$pdf->Output($conge->user->first_name.'_'.$conge->user->last_name.'_DECISION.pdf', 'I');

}

public function generatePV($id){  
    $conge = Conge::with('user.departement')->find($id);
    $html='
    <style>
    .title{
        text-align:center;
        
    }
    .sub-title{
        text-align:center;
    }
      .label {
        font-weight: bold;
        margin-bottom:35px;
      }
      .value{
        font-weight: normal;
      }
    </style>
    <br><br><br>
    <h2 class="title">DECISION</h2>
    
    <h3 class="sub-title">Procès-verbal de reprise de travail</h3>
    <div style="height: 200vh">  </div>
    <p>Je suis le soussigné</p>
    <p></p>
    
      <p class="label">Fonction&nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;:<span class="value">&nbsp;Directeur</span></p> 
     
      <p class="label">Prénom&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;:<span class="value">&nbsp;'.$conge->user->first_name.'</span></p> 
      <p class="label">Nom&nbsp;&nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;:<span class="value">&nbsp;'.$conge->user->last_name.'</span></p> 
      <p class="label">Nationalité&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;:<span class="value">&nbsp;'.$conge->user->nationalite.'</span></p> 
      <p class="label">Situation familiale&emsp;&emsp;:<span class="value">&nbsp;'. $conge->user->situation.'</span></p> 
      <p class="label">Cadre&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;:<span class="value">&nbsp;'.$conge->user->poste.'</span></p> 
      <p class="label" >Période d\'absence&emsp;&emsp;:<span class="value">&nbsp;'.$conge->date_debut.' à '.$conge->date_fin.'</span></p> 
      <p class="label">Raison&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;:<span class="value">&nbsp;Congé administratif</span></p> 
      <p></p>
    <p>Rédigé le : <span class="label">'.Carbon::today()->toDateString().'</span></p>
    <div></div>
    <div></div>
    <p>Tampon et signature du Directeur de l\'entreprise&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Signature de l\'employé(e)</p>
    
    ';
      // // Set PDF properties and content
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            // ---------------------------------------------------------    
     // set font
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    // add a page
    $pdf->AddPage();
    // Persian and English content
    $pdf->WriteHTML($html, true, 0, true, 0);
    
    //Close and output PDF document
    $pdf->Output($conge->user->first_name.'_'.$conge->user->last_name.'_PV.pdf', 'I');
}

}
