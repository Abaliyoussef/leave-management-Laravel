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
use Illuminate\Support\Facades\Lang;
use App\Mail\CongeCreated;
use App\Mail\CongeRejected;
use Illuminate\Support\Facades\Mail;

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
            "date_debut"=>['before_or_equal:date_fin','after:today'],
            "user_id"=>['required' ]
        ]);
        
        $finishingDate = Carbon::parse($request->input('date_fin'));
        $startingDate = Carbon::parse($request->input('date_debut'));

        $holidays = Holiday::where([['holiday_date', '>=', $startingDate],['holiday_date', '<=', $finishingDate]])->pluck('holiday_date')->toArray();
        $user_conges=Conge::where([["user_id","=",$request->user_id]])->get();
        
        foreach($user_conges as $conge){
            if($startingDate->between($conge->date_debut, $conge->date_fin) || $finishingDate->between($conge->date_debut, $conge->date_fin)){
                return back()->withErrors([
                    'erreur' => 'Congé non enregistré.date déja choisi comme congé',
                ]);
            }
            
        }
        if ($startingDate->isWeekend() || in_array($startingDate->toDateString(), $holidays) || $finishingDate->isWeekend() || in_array($finishingDate->toDateString(), $holidays)) {
            return back()->withErrors([
                'erreur' => 'Congé non enregistré.un des dates choisi est un weekend ou un jour férié',
            ]);
        }

        $weekendDays = $startingDate->diffInDaysFiltered(function ($date) {
            return $date->isWeekend();
        }, $finishingDate);
        $finishingDate->isWeekend()?$weekendDays++:0;
        $holidayDays = count($holidays);
        $totalDuration = $startingDate->diffInDays($finishingDate)+1;
        //check if a holiday date falls on a weekend
        foreach ($holidays as $holiday) {
            $date=Carbon::parse($holiday);
            if ($date->isWeekend()) {
                $holidayDays--;
            } 
          }

        $reelduration=$totalDuration-$holidayDays-$weekendDays;


        $conge = new Conge();
        $conge->date_debut = $request->date_debut;
        $conge->date_fin =  $request->date_fin;
        $conge->description = $request->description;
        $conge->duree=$reelduration;
        $conge->status = $request->status ? $request->status : 'Pending';
        $conge->user_id = $request->user_id;
        
        //updating user's score
        $user=User::find($request->user_id);
          if($user->score-$reelduration<0){
            return back()->withErrors([
                'erreur' => 'Congé non enregistré.veuillez consulter le solde des jours restant',
            ]);
          }
        $user->score-=$reelduration;
        $conge->save();
        $user->save();
        Mail::to($user->email)->send(new CongeCreated($user,$conge));
        // 
        if ( Auth::user()->role=='user'){
            return redirect()->route('employe.Allconges',['id'=>Auth::user()->id])->with('success', 'Entregistré avec succès');
            }
        if( Auth::user()->role=='manager'){
            if($conge->status=='Proposé'){
               return redirect()->route('manager.conge.new-demands')->with('success', 'Entregistré avec succès'); 
            }
            return redirect()->route('manager.conge.all-demands')->with('success', 'Entregistré avec succès');         
         }
}
public function updateConge(Request $request, $id){
    
    $request->validate([
        "date_debut"=>['before_or_equal:date_fin','after:today'],

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
            Mail::to($user->email)->send(new CongeRejected($user,$conge));
        }
        $conge->save();
        if($request->status=='Accordé'){
            $user=User::find($conge->user_id);
            Mail::to($user->email)->send(new CongeCreated($user,$conge));
        }

        return redirect()->back()->with('success', 'Entregistré avec succès');

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
        
        if($conge->status != 'Annulé' && Carbon::parse($conge->date_fin) > Carbon::today() ){
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
    $conges = Conge::where("user_id",$id)->where([["status","=","proposé"],['date_debut','>',Carbon::today()]])->orderBy('date_debut','desc')->paginate(6);
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
   
    
    <div class="date">Fait à Casablanca le <span style="font-weight: bold;" class="decision-date">'.$conge->created_at->toDateString().'</span></div>


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
    <p>Rédigé le : <span class="label">'.$conge->created_at->toDateString().'</span></p>
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
//arabic docs
public function generateDecisionArabic($id){  
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
            text-align: left;
            margin-top: 30px;
        }
        p{
            font-weight: bold;
        }
    </style>
    <br><br><br><br><br><br><br><br>
    <div class="titre">قـــــرا ر</div>
    
    <div >
        <div class="titre">رخصة إداريــــة</div>

        <div >
            <p>رخصة إدارية مدتها &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;: <span >'.$conge->duree.' (ي)</span></p>
            <p>رقم التأجـير &nbsp;&nbsp;&emsp; &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;: <span >'.$conge->user->num_de_som.'</span></p>
            <p><span style="text-align: center;"> ابتداء من &nbsp;'.$conge->date_debut.'&nbsp;   إلى&nbsp;'.$conge->date_fin.'</span></p>
        </div>
    </div>
    
  
   
    
    <div class="date">الدار البيضاء في  : <span style="font-weight: bold;" class="decision-date">'.$conge->created_at->toDateString().'</span></div>


';

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // ---------------------------------------------------------    
 // set font
$pdf->SetFont('dejavusans', '', 16);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// add a page
$pdf->AddPage();
$pdf->setRTL(true);

// Persian and English content
$pdf->WriteHTML($html, true, 0, true, 0);



//Close and output PDF document
$pdf->Output($conge->user->first_name.'_'.$conge->user->last_name.'_DECISION.pdf', 'I');

}
public function generatePVArabic($id){  
    //
    $conge = Conge::with('user.departement')->find($id);

    Lang::setLocale('fr');
    //this suffix is user to add (ة) to familiale situation if the user is a women
    $genreSuffix = $conge->user->genre == "homme" ? "" : "ة";

    $situationFamiliale=Lang::get('FamilialeState.'.$conge->user->situation);
    $situationFamiliale=$situationFamiliale.$genreSuffix;
    $end_date = Carbon::parse($conge->date_fin);
    $startingDate = Carbon::parse($conge->date_debut);
    $holidays = Holiday::where([['holiday_date', '>=', $startingDate]])->pluck('holiday_date')->toArray();

    $duration=1;
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
    
    <h2 class="title">	محضر استئناف العمل	</h2>
    <div style="height: 200vh">  </div>
    <p>أنا الممضي أسفله ,<p/>
 
    
      <p class="label">الصفة&nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;:<span class="value">&emsp;مدير الشركة</span></p> 
     
      <p class="label">الإسم الشخصي&emsp;&nbsp;&nbsp;&emsp;&emsp;&emsp;&emsp;:<span class="value">&emsp;'.$conge->user->first_name_ar.'</span></p> 
      <p class="label">الإسم العائلي&nbsp;&nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;:<span class="value">&emsp;'.$conge->user->last_name_ar.'</span></p> 
      <p class="label">الجنسية&emsp;&emsp;&nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;:<span class="value">&emsp;'.$conge->user->nationalite_ar.'</span></p> 
      <p class="label">الحالة العائلية &emsp;&nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;:<span class="value">&emsp;'. $situationFamiliale.'</span></p> 
      <p class="label">الإطـــار&emsp;&nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;:<span class="value">&emsp;'.$conge->user->poste_ar.'</span></p> 
      <p class="label" >تاريخ الغياب &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;:<span class="value">&emsp;'.$conge->date_debut.'</span></p> 
      <p class="label" >تاريخ الالتحاق&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;:<span class="value">&emsp;'.$end_date->toDateString().'</span></p> 
      <p class="label">أسبـــابه &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&nbsp;&nbsp;:<span class="value">&emsp;رخصة إدارية</span></p> 
      <p></p>
    <p>حــــرر في : <span class="label">'.$conge->created_at->toDateString().'</span></p>
    <div></div>
    <div></div>
    <p>طابع وإمضاء مدير الشركة&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;إمضاء الاجير</p>
    
    ';
      // // Set PDF properties and content
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            // ---------------------------------------------------------    
     // set font
    $pdf->SetFont('dejavusans', '', 14);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->setRTL(true);

    // add a page
    $pdf->AddPage();
    // Persian and English content
    $pdf->WriteHTML($html, true, 0, true, 0);
    
    //Close and output PDF document
    $pdf->Output($conge->user->first_name.'_'.$conge->user->last_name.'_PV.pdf', 'I');
}
public function generateDemande($id){  
    //
    $conge = Conge::with('user')->find($id);

    $html='
    <style>
    table {
        width: 100%;
        
        margin-top: 20px;
        
    }

     td {
        border: 1px solid #000000;
        padding: 15px;
        
        text-align: left;
    }

   
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
    <h2 class="title">DEMANDE DE CONGE</h2>
    
    <h3 class="sub-title">Année '.Carbon::now()->subYear()->year.'-'.Carbon::now()->year.'</h3>
    <div style="height: 200vh">  </div>
    <p></p>
    
    <table>
    <tr>
        <td><b>Nom</b></td>
        <td>'.$conge->user->last_name.'</td>
    </tr>
    <tr>
        <td><b>Prénom</b></td>
        <td>'.$conge->user->first_name.'</td>
    </tr>
    <tr>
        <td><b>D.R.P.P</b></td>
        <td>'.$conge->user->num_de_som.'</td>
    </tr>
    <tr>
        <td><b>Pôle</b></td>
        <td>DSI</td>
    </tr>
    <tr>
        <td><b>Cadre</b></td>
        <td>'.$conge->user->poste.'</td>
    </tr>
    <tr>
        <td><b>Durée</b></td>
        <td>'.$conge->duree.' jour(s)</td>
    </tr>
    <tr>
        <td><b>Période de congé</b></td>
        <td>'.$conge->date_debut.' à '.$conge->date_fin.'</td>
    </tr>
   
</table>


    <div></div>
    <p>signature du salarié(e) :</p>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <p>Avis du responsable du pôle :</p>
    ';
    // // Set PDF properties and content
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            // ---------------------------------------------------------    
     // set font
    $pdf->SetFont('dejavusans', '', 14);
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
