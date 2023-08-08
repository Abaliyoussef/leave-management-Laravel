@extends('auth.manager.layout')
@section('content')
<div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Congés archivés</h1>
                        <ol class="breadcrumb mb-4">
                           
                        </ol>
                        <div class="d-flex justify-content-between mb-3">

                        <a href="{{route('manager.conge.archived')}}" class="btn btn-primary"><i class="fa fa-refresh" aria-hidden="true"></i> </a>
  <form action="{{route('manager.conge.archived.search')}}" method="get"> <div class="form-group">
    @csrf
    <input type="text" name="search" value="{{old('search')}}" class="form-control" placeholder="Search..."  >
  </div></form>
  
 
</div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Liste des congés archivés
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
                                            <th>Décision congé</th>
                                            <th>Procès verbal</th>

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
                                          <td><div class="btn btn-sm btn-warning" >expiré</div></td> 
                                          <td>  <div class="btn-group" role="group">

    <form action="{{route('conge.delete',['id'=>$conge->id])}}"  method="POST">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-sm btn-danger" onclick="submitForm(event,'vous voulez supprimer cet élément ?','Supprimer')" ><i class="fa fa-trash"></i></button>
    </form>
  </div></td>                                           
                                          <td>  <div class="btn-group" role="group">
                                            <a href="{{route('conge.decision',['id'=>$conge->id])}}" class="btn btn-sm btn-primary mx-2"><i class="fa fa-download" aria-hidden="true"></i> FR</a>
                                            <a href="{{route('conge.decision.ar',['id'=>$conge->id])}}" class="btn btn-sm btn-primary mx-2"><i class="fa fa-download" aria-hidden="true"></i> AR</a>

                                            </div></td>
                                            <td>  <div class="btn-group" role="group">
                                            <a href="{{route('conge.procesVerbal',['id'=>$conge->id])}}" class="btn btn-sm btn-primary mx-2"><i class="fa fa-download" aria-hidden="true"></i> FR</a>
                                            <a href="{{route('conge.procesVerbal.ar',['id'=>$conge->id])}}" class="btn btn-sm btn-primary mx-2"><i class="fa fa-download" aria-hidden="true"></i> AR</a>

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
@section('title','congés archivés')