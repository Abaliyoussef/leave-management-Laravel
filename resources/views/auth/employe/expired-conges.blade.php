@extends('auth.employe.layout')
@section('content')
<div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Mes congés expirés</h1>
                        <ol class="breadcrumb mb-4">
                            
                        </ol>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Liste des congés expirés
                            </div>
                            <div class="card-body">
                            <div class="table-responsive">
                            <table class="table">
  <thead>
  <tr>
                                            
                                            <th>Date de debut</th>
                                            <th>Date de fin</th>
                                            <th>Durée</th>
                                            <th>Description</th>
                                            <th>status</th>

                                        </tr>
  </thead>
  <tbody>
  @foreach($conges as $conge)
                                        <tr>
                                            <td>{{$conge->date_debut}}</td>
                                            <td>{{$conge->date_fin}}</td>
                                            <td>{{$conge->duree}}</td>
                                            <td>{{$conge->description}}</td>
                                            <td><div class="btn btn-sm btn-warning" >Expiré</div></td>                                            
                                            
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
@section('title','mes congés expirés')