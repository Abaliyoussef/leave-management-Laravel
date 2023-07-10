@extends('auth.employe.layout')
@section('content')
<div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Suggestion de congés</h1>
                        <ol class="breadcrumb mb-4">
                           
                        </ol>
                        <div class="d-flex justify-content-between mb-3">

                    
  
 
</div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Liste des congés suggérés
                            </div>
                            <div class="card-body">
                            <div class="table-responsive">
                            <table class="table">
  <thead>
  <tr>
                                            
                                            <th>Date de debut</th>
                                            <th>Date de fin</th>
                                            <th>durée</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Actions</th>

                                        </tr>
  </thead>
  <tbody>
  @foreach($conges as $conge)
                                        <tr>
                                        
                                            
                                            <td>{{$conge->date_debut}}</td>
                                            <td>{{$conge->date_fin}}</td>
                                            <td>{{$conge->duree}}</td>
                                            <td>{{$conge->description}}</td>
                                            <td><div class="btn btn-sm btn-warning" >{{$conge->status}}</div></td>                                            
                                            <td>  <div class="btn-group" role="group">
   <form action="{{route('manager.conge.delete',['id'=>$conge->id])}}" method="POST">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-sm btn-secondary mx-2" onclick="submitForm(event,'cette proposition de congé va être supprimée ?','Supprimer')"><i class="fa fa-trash"></i></button>
    </form>

    <form action="{{route('conge.update',['id'=>$conge->id])}}" method="POST">
      @csrf
      @method('PUT')
      <input type="hidden" value="Accordé" name="status"/>
      <button type="submit" class="btn btn-sm btn-success" onclick="submitForm(event,'cette proposition de congé va être acceptée ?','Accepter')"><i class="fa fa-check-circle" aria-hidden="true"> </i>Accepter</button>
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
@section('title','suggestions de congés')