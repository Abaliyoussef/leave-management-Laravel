@extends('auth.admin.layout')
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
                        <h1 class="mt-4">Les départements</h1>
                        <ol class="breadcrumb mb-4">
                           
                        </ol>
                        <div class="d-flex justify-content-between mb-3">

  <a href="{{route('departements.create')}}" class="btn btn-primary"><i class="fa fa-add"></i></a>
  <form action="{{route('admin.departements.search')}}" method="get">
     <div class="form-group">
    @csrf
    <input type="text" name="search" value="{{old('search')}}" class="form-control" placeholder="Search..."  >
  </div>
</form>
 
</div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Liste des départements
                            </div>
                            <div class="card-body">
                            <div class="table-responsive">
                            <table class="table">
  <thead>
  <tr>
                                            <th>#</th>
                                            <th>Nom</th>
                                            <th>Actions</th>
                                            
                                        </tr>
  </thead>
  <tbody>
  @foreach($departements as $departement)
                                        <tr>
                                        
                                            <td>{{$departement->id}}</td>
                                            <td>{{$departement->depart_name}}</td>
                                          
                                            
                                            <td>  <div class="btn-group" role="group">
    <a href="{{route('departements.edit',['departement'=>$departement->id])}}" class="btn btn-sm btn-info mx-2"><i class="fa fa-edit"></i></a>
    <form action="{{route('departements.destroy',['departement'=>$departement->id])}}" method="POST">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-sm btn-danger" onclick="submitForm(event,'vous voulez supprimer cet élément ?','Supprimer')"><i class="fa fa-trash"></i></button>
    </form>
  </div></td>
                                        </tr>
                                        @endforeach
                                        
  </tbody>
  
</table>
{{ $departements->links() }}

</div>
                            </div>
                        </div>
                    </div>
                </main>
                
            </div>
@endsection
@section('title','gestion des departement')