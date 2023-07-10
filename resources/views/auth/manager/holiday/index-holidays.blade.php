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
                    <h1 class="mt-4">Jours fériés</h1>
                        <ol class="breadcrumb mb-4">
                           
                        </ol>
                        <div class="d-flex justify-content-between mb-3">

  <a href="{{route('holidays.create')}}" class="btn btn-primary"><i class="fa fa-add"></i></a>

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
                                            <th>#</th>
                                            <th>Intitulé</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                            
                                        </tr>
  </thead>
  <tbody>
  @foreach($holidays as $holiday)
                                        <tr>
                                            <td>{{$holiday->id}}</td>
                                            <td>{{$holiday->description}}</td>
                                            <td>{{$holiday->holiday_date}}</td>
                                            <td>  <div class="btn-group" role="group">
    <a href="{{route('holidays.show',['holiday'=>$holiday->id])}}" class="btn btn-sm btn-info mx-2"><i class="fa fa-edit"></i></a>
    <form action="{{route('holidays.destroy',['holiday'=>$holiday->id])}}"  method="POST">
      @csrf
      @method('DELETE')
      <button type="submit"  class="btn btn-sm btn-danger " onclick="submitForm(event,'vous voulez supprimer cet élément ?','Supprimer')"><i class="fa fa-trash"></i></button>
    </form>
  </div></td>
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
@section('title','Gestion des jours fériés')