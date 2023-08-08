@extends('auth.manager.layout')
@section('content')
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
                        <h1 class="mt-4">Nouvelles demandes</h1>
                        <ol class="breadcrumb mb-4">
                          
                        </ol>
                        <div class="d-flex justify-content-between mb-3">

                        <a href="{{route('manager.conge.new-demands')}}" class="btn btn-primary"><i class="fa fa-refresh" aria-hidden="true"></i> </a>
  <form action="{{route('manager.conge.new-demands.search')}}" method="get"> <div class="form-group">
    @csrf
    <input type="text" name="search" value="{{old('search')}}" class="form-control" placeholder="Search..."  >
  </div></form>
  
 
</div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Liste des nouveaux congés
                            </div>
                            <div class="card-body">
                            <div class="table-responsive">
                            <table class="table">
  <thead>
  <tr>
                                            <th>#</th>
                                            <th>Demandeur</th>
                                            <th>Score</th>
                                            <th>Date de début</th>
                                            <th>Date de fin</th>
                                            <th>Durée</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Actions</th>

                                        </tr>
  </thead>
  <tbody>
  @foreach($conges as $conge)
                                        <tr>
                                        
                                            <td>{{$conge->id}}</td>
                                          
                                            <td>{{$conge->user->last_name.' '.$conge->user->first_name}}</td>
                                            <td>{{$conge->user->score}}</td>
                                            <td>{{$conge->date_debut}}</td>
                                            <td>{{$conge->date_fin}}</td>
                                            <td>{{$conge->duree}}</td>
                                            <td>{{$conge->description}}</td>
                                            <td><div class="btn btn-sm btn-warning" >{{$conge->status}}</div></td>                                            
                                            <td>  <div class="btn-group" role="group">
    @if($conge->status=="Pending")
    <form  action="{{route('conge.update',['id'=>$conge->id])}}" id="annuler-form" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" value="Annulé" name="status"/>
      <button type="submit" class="btn btn-sm btn-danger mx-2" onclick="submitForm(event,'voulez-vous annuler ce congé ?','Oui')" ><i class="fa fa-times" aria-hidden="true"> </i> Annuler
</button>
    </form>

    <form action="{{route('conge.update',['id'=>$conge->id])}}"  method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" value="Accordé" name="status"/>
      <button type="submit" id="accorder-button" class="btn btn-sm btn-success" onclick="submitForm(event,'voulez-vous accorder ce congé ?','Accorder')" ><i class="fa fa-check-circle" aria-hidden="true"> </i> Accorder
</button>
    </form>
    <a href="{{route('suggestion.create',['id'=>$conge->id])}}"  class="btn btn-sm btn-info mx-2"><i class="fa fa-reply" aria-hidden="true"></i> Suggérer</a>
    @endif
    <form action="{{route('conge.delete',['id'=>$conge->id])}}"  method="POST">
      @csrf
      @method('DELETE')
      <button type="submit" id="delete-button" class="btn btn-sm btn-danger" onclick="submitForm(event,'voulez-vous supprimer ce congé ?','Supprimer')" ><i class="fa fa-trash"></i></button>
    </form>
  </div></td>
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
@section('title','nouvelles demandes')