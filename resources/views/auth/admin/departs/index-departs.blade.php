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

  <a href="{{route('departements.create')}}" class="btn btn-primary">ajouter </a>
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
                                DataTable Example
                            </div>
                            <div class="card-body">
                            <div class="table-responsive">
                            <table class="table">
  <thead>
  <tr>
                                            <th>#</th>
                                            <th>nom</th>
                                            <th>action</th>
                                            
                                        </tr>
  </thead>
  <tbody>
  @foreach($departements as $departement)
                                        <tr>
                                        
                                            <td>{{$departement->id}}</td>
                                            <td>{{$departement->depart_name}}</td>
                                          
                                            
                                            <td>  <div class="btn-group" role="group">
    <a href="{{route('departements.edit',['departement'=>$departement->id])}}" class="btn btn-sm btn-info">modifier</a>
    <form action="{{route('departements.destroy',['departement'=>$departement->id])}}" method="POST">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('ce département va être supprimé')">supprimer</button>
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