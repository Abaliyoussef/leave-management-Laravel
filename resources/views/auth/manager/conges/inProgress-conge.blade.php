@extends('auth.manager.layout')
@section('content')
<div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Congés en cours</h1>
                        <ol class="breadcrumb mb-4">
                           
                        </ol>
                        <div class="d-flex justify-content-between mb-3">


 
</div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Liste congés en cours
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
                                            <th>Documents</th>
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
                                        <td><div class="btn btn-sm " style="background-color: #f1c40f; color: #ffffff; padding: 10px;" >En cours...</div></td>                                            
                                        <td>  <div class="btn-group" role="group">

<a href="{{route('conge.decision',['id'=>$conge->id])}}" class="btn btn-sm btn-primary mx-2"><i class="fa fa-download" aria-hidden="true"></i> Décision</a>
<a href="{{route('conge.procesVerbal',['id'=>$conge->id])}}" class="btn btn-sm btn-primary mx-2"><i class="fa fa-download" aria-hidden="true"></i> Procès verbal</a>

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
@section('title','Congés en cours')