@extends('auth.employe.layout')
@section('content')
@if(auth()->user()->verifie==0)
<div class="modal" id="myModal" tabindex="-1" role="dialog"data-bs-backdrop="static" 
            data-bs-keyboard="false" 
            tabindex="-1"
            aria-labelledby="aboutUsLabel" 
            aria-hidden="true" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bonjour @if(auth()->check())
                    {{ auth()->user()->first_name.' '.auth()->user()->last_name }}
                        @endif</h5>
 
      </div>
      <div class="modal-body">
        <p>Vous êtes un nouveau utilisateur et votre compte sera activé une fois que l'administrateur aura vérifié vos informations.</p>
      </div>
      <div class="modal-footer">
        <a href="{{route('logout')}}" class="btn btn-primary">Se déconnecter</a>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
   $('#myModal').modal('show');
   //$('#myModal').modal({backdrop: 'static', keyboard: false}, 'show');
   });
  // Prevent modal from being hidden when clicking outside of it
 

</script>
@endif
@if(Session::has('success'))
<script>
  Swal.fire({
  position: 'top',
  icon: 'success',
  title: '{{ Session::get('success')}}',
  showConfirmButton: false,
  timer: 2000,
})

</script>
@endif
<div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Mes congés</h1>
                        <ol class="breadcrumb mb-4">
                            
                        </ol>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Liste des congés
                            </div>
                            <div class="card-body">
                            <div class="table-responsive">
                            <table class="table">
  <thead>
  <tr>
                                            
                                            <th>Date de début</th>
                                            <th>Date de fin</th>
                                            <th>Durée</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                            <th>Demande</th>
                                            <th>Décision congé</th>
                                            <th>Procès verbal</th>

                                        </tr>
  </thead>
  <tbody>
  @foreach($conges as $conge)
                                        <tr>
                                            

                                            <td>{{$conge->date_debut}}</td>
                                            <td>{{$conge->date_fin}}</td>
                                            <td>{{$conge->duree}}</td>
                                            <td>{{$conge->description}}</td>
                                            <td><div class="btn btn-sm btn-warning" >{{ $conge->status}}</div></td>                                            
                                            <td>  <div class="btn-group" role="group">
    <form action="{{route('conge.update',['id'=>$conge->id])}}" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" value="annulation..." name="status"/>
      <button type="submit" class="btn btn-sm btn-danger mx-2" onclick="submitForm(event,'voulez-vous annulez ce congé ?','Oui')" {{$conge->status=='annulation...' ||$conge->status=='Annulé' ||$conge->status=='Pending'  ? 'disabled' : ""}} {{$conge->demande_annulation == 1 ? 'disabled' : ''}}>{{$conge->demande_annulation==1 ? 'Annulation refusé' : 'Annuler'}}</button>
    </form>
    <form action="{{route('conge.delete',['id'=>$conge->id])}}" method="POST">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-sm btn-secondary" onclick="submitForm(event,'voulez-vous supprimer ce congé ?','Supprimer')" {{$conge->status == 'Accordé' ? 'disabled' : ''}}><i class="fa fa-trash"></i></button>
    </form>
    <td>  <div class="btn-group" role="group">
                                            @if($conge->status=="Pending")
                                            <a href="{{route('conge.demande',['id'=>$conge->id])}}" class="btn btn-sm btn-primary mx-2"><i class="fa fa-download" aria-hidden="true"></i> Télécharger</a>
                                            @endif
                                            </div></td>
  </div></td>
  @if($conge->status=='Accordé')

  <td>  <div class="btn-group" role="group">
                                            <a href="{{route('conge.decision',['id'=>$conge->id])}}" class="btn btn-sm btn-primary mx-2"><i class="fa fa-download" aria-hidden="true"></i> FR</a>
                                            <a href="{{route('conge.decision.ar',['id'=>$conge->id])}}" class="btn btn-sm btn-primary mx-2"><i class="fa fa-download" aria-hidden="true"></i> AR</a>

                                            </div></td>
                                            <td>  <div class="btn-group" role="group">
                                            <a href="{{route('conge.procesVerbal',['id'=>$conge->id])}}" class="btn btn-sm btn-primary mx-2"><i class="fa fa-download" aria-hidden="true"></i> FR</a>
                                            <a href="{{route('conge.procesVerbal.ar',['id'=>$conge->id])}}" class="btn btn-sm btn-primary mx-2"><i class="fa fa-download" aria-hidden="true"></i> AR</a>

                                            </div></td>
  @endif
                                        </tr>
                                        @endforeach
                                        
  </tbody>
  
</table>
{{ $conges->links() }}

</div>
                            </div>
                        </div>
                    </div>
                </main>
                
            </div>
@endsection
@section('title','mes congés')