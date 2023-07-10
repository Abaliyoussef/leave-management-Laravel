@extends('auth.employe.layout')
@section('content')
<div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Jours fériés</h1>
                        <ol class="breadcrumb mb-4">
                            
                        </ol>
                        <div class="d-flex justify-content-between mb-3">

</form>
 
</div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Liste des jours fériés
                            </div>
                            <div class="card-body">
                            <div class="table-responsive">
                            <table class="table">
  <thead>
  <tr>
                                            
                                            <th>intitulé</th>
                                            <th>date</th>
                                            
                                        </tr>
  </thead>
  <tbody>
  @foreach($holidays as $holiday)
                                        <tr>
                                        
                                            
                                            <td>{{$holiday->description}}</td>
                                            <td>{{$holiday->holiday_date}}</td>
                                          
                                            
                    
                                        </tr>
                                        @endforeach
                                        
  </tbody>
  
</table>
{{ $holidays->links() }}

</div>
                            </div>
                        </div>
                    </div>
                </main>
                
            </div>
@endsection
@section('title','jours fériés')