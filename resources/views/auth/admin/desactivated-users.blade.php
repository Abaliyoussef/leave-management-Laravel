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
                        <h1 class="mt-4">Les utilisateurs désactivés</h1>
                        <ol class="breadcrumb mb-4">
                            
                        </ol>
                        <div class="d-flex justify-content-between mb-3">

  <form action="{{route('admin.users.desactives.search')}}" method="get"> <div class="form-group">
    @csrf
    <input type="text" name="search" value="{{old('search')}}" class="form-control" placeholder="Search..."  >
  </div></form>
 
</div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                liste des utilisateurs désactivés
                            </div>
                            <div class="card-body">
                            <div class="table-responsive">
                            <table class="table">
  <thead>
  <tr>
                                            <th>#</th>
                                            <th>Image</th>
                                            <th>nom</th>
                                            <th>CIN</th>
                                            <th>N. SOM</th>
                                            <th>Nationalité</th>
                                            <th>Genre</th>
                                            <th>Email</th>
                                            <th>Département</th>
                                            <th>Poste</th>
                                            <th>Tel</th>
                                            <th>Actions</th>
                                        </tr>
  </thead>
  <tbody>
  @foreach($users as $user)
                                        <tr>
                                        
                                        <td>{{$user->id}}</td>
                                        <td><div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden;">
                                            <img src="{{url('Image/'.$user->image)}}"  alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                                            </div></td>
                                            <td>{{$user->last_name.' ' .$user->first_name}}</td>
                                            <td>{{$user->cin}}</td>
                                            <td>{{$user->num_de_som}}</td>
                                            <td>{{$user->nationalite}}</td>
                                            <td>{{$user->genre}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->departement_name}}</td>
                                            <td>{{$user->poste}}</td>
                                            <td>{{$user->phone}}</td>
                                            
                                            <td>  <div class="btn-group" role="group">
    <form action="{{route('admin.users.activate',['user'=>$user->id])}}" id="activer-form" method="POST">
      @csrf
      <button type="submit" id="activer-button" class="btn btn-sm btn-info mx-2" onclick="submitForm(event,'voulez-vous activer cet utilisateur ?','Activer')"><i class="fa fa-check-square" aria-hidden="true"></i></button>
    </form>
    <form action="{{route('users.destroy',['id'=>$user->id])}}" id="delete-form" method="POST">
      @csrf
      @method('DELETE')
      <button type="submit" id="delete-button" class="btn btn-sm btn-danger" onclick="submitForm(event,'voulez-vous supprimer cet utilisateur ?','Supprimer')"><i class="fa fa-trash" aria-hidden="true"></i>
</button>
    </form>
  </div></td>
                                        </tr>
                                        @endforeach
                                        
  </tbody>
  
</table>
{{ $users->links() }}

</div>
                            </div>
                        </div>
                    </div>
                </main>
                
            </div>
            <script>
const deleteButton = document.getElementById('delete-button');
deleteButton.addEventListener('click', function(event) {
    event.preventDefault();
    Swal.fire({
  title: 'voulez-vous que cet utilisateur sera supprimé définitivement ?',
  
  showCancelButton: true,
  confirmButtonText: 'Supprimer',
  cancelButtonText: 'Annuler',
  
}).then((result) => {
  if (result.isConfirmed) {
    document.getElementById('delete-form').submit();
  } 
})
});

const activerButton = document.getElementById('activer-button');
activerButton.addEventListener('click', function(event) {
    event.preventDefault();
    Swal.fire({
  title: 'Cet utilisateur va être réactivé',
  
  showCancelButton: true,
  confirmButtonText: 'Activer',
  cancelButtonText: 'Annuler',
  
}).then((result) => {
  if (result.isConfirmed) {
    document.getElementById('activer-form').submit();
  } 
})
});
            </script>
@endsection
@section('title','utilisateurs désactivés')