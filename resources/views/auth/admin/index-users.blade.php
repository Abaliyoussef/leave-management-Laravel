@extends('auth.admin.layout')
@section('content')
<div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Tables</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Tables</li>
                        </ol>
                        <div class="d-flex justify-content-between mb-3">

  <a href="{{route('users.create')}}" class="btn btn-primary">ajouter </a>
  <form action="{{route('admin.users.search')}}" method="get"> <div class="form-group">
    @csrf
    <input type="text" name="search" value="{{old('search')}}" class="form-control" placeholder="Search..."  >
  </div></form>
 
</div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                DataTable Example
                            </div>
                            <div class="card-body">
                            <div class="table-responsive">
                            <table class="table">
  <thead>
  <tr>
                                            <th>#</th>
                                            <th>image</th>
                                            <th>nom</th>
                                            <th>prenom</th>
                                            <th>cin</th>
                                            <th>email</th>
                                            <th>departement</th>
                                            <th>poste</th>
                                            <th>téléphone</th>
                                            <th>action</th>
                                        </tr>
  </thead>
  <tbody>
  @foreach($users as $user)
                                        <tr>
                                        
                                            <td>{{$user->id}}</td>
                                            <td><img src="{{url('Image/'.$user->image)}}" 
                                            style="height: 100px; width: 150px;"/></td>
                                            <td>{{$user->last_name}}</td>
                                            <td>{{$user->first_name}}</td>
                                            <td>{{$user->cin}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->departement_name}}</td>
                                            <td>{{$user->poste}}</td>
                                            <td>{{$user->phone}}</td>
                                            
                                            <td>  <div class="btn-group" role="group">
    <a href="{{route('users.edit',['user'=>$user->id])}}" class="btn btn-sm btn-info">modifier</a>
    <form action="{{route('users.destroy',['user'=>$user->id])}}" method="POST">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('cet utilisateur va etre désactivé')">désactiver</button>
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
@endsection
@section('title','gestion des utilisateurs')