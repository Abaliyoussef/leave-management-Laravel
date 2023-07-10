@extends('auth.manager.layout')
@section('content')
<div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Demandes d'annulation</h1>
                        <ol class="breadcrumb mb-4">
                            
                        </ol>
                        <div class="d-flex justify-content-between mb-3">

                        <a href="{{route('manager.conge.demandes-annulation')}}" class="btn btn-primary"><i class="fa fa-refresh" aria-hidden="true"></i> </a>
  <form action="{{route('manager.conge.demandes-annulation.search')}}" method="get"> <div class="form-group">
    @csrf
    <input type="text" name="search" value="{{old('search')}}" class="form-control" placeholder="Search..."  >
  </div></form>
 
</div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Liste des demandes d'annulation
                            </div>
                            <div class="card-body">
                            <div class="table-responsive">
                            <table class="table">
  <thead>
  <tr>
                                            <th>#</th>
                                            <th>Demandeur</th>
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
                                          <td>{{$conge->date_debut}}</td>
                                          <td>{{$conge->date_fin}}</td>
                                          <td>{{$conge->duree}}</td>
                                          <td>{{$conge->description}}</td>
                                          <td><div class="btn btn-sm btn-warning" >{{$conge->status}}</div></td>                                                                                       
                                            <td>  <div class="btn-group" role="group">
    
    <form action="{{route('conge.update',['id'=>$conge->id])}}"  method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" value="Accordé" name="status"/>
      <input type="hidden" value="1" name="demande_annulation"/>
      <button type="submit" class="btn btn-sm btn-danger mx-2" onclick="submitForm(event,'voulez-vous refuser cette demande d\'annulation ?','Refuser')" ><i class="fa fa-times" aria-hidden="true"></i> Refuser</button>
    </form>
    <form action="{{route('conge.update',['id'=>$conge->id])}}"  method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" value="Annulé" name="status"/>
      <button type="submit" onclick="submitForm(event,'voulez-vous accepter cette demande d\'annulation ?','Accepter')" class="btn btn-sm btn-success" ><i class="fa fa-check" aria-hidden="true"></i> Accepter</button>
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
@section('title','demandes annulation')