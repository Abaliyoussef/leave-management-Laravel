@extends('auth.manager.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet"/>

<div class="w-full max-w-md mx-auto mt-10 mb-6">

<form class="w-full max-w-md mx-auto mt-10" method="post" action="{{route('conge.store')}}">
		
@csrf

@if($errors->has('erreur'))
<script>
  Swal.fire({
  icon: 'error',
  title: 'Erreur',
  text: '{{ $errors->first('erreur') }}',
  footer: '<a href="{{route('users.index')}}"><Bold>Liste des utilisateur</Bold>s</a>'
})
</script>
@endif
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
<h2 class="text-xl font-bold mb-6">création du suggestion</h2>
<input type="hidden"  name="user_id" value="{{$conge->user->id}}" >
<input type="hidden"  name="status" value="Proposé" >


  <div class="mb-6">
    <label for="nom" class="block mb-2 text-sm font-medium text-gray-900">L'employé</label>
    <input type="text" id="nom" name="nom" value="{{$conge->user->last_name.' '.$conge->user->first_name}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"  disabled>
  </div>
  <div class="mb-6">
    <label for="" class="block mb-2 text-sm font-medium text-gray-900">Date de début</label>
    <input type="date"  name="date_debut"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"  >
    @if($errors->has('date_debut'))
							<span class="text-red-600">{{ $errors->first('date_debut') }}</span>
		@endif
  </div>  <div class="mb-6">
    <label for="" class="block mb-2 text-sm font-medium text-gray-900">Durée</label>
    <input type="date"  name="duree"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"   min="1">
    @if($errors->has('duree'))
							<span class="text-red-600">{{ $errors->first('duree') }}</span>
		@endif
  </div>
    
  <div class="">
    <label for="" class="block mb-2 text-sm font-medium text-gray-900">Description(facultatif)</label>
    <textArea   name="description"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"  ></textArea>
  </div>
  <button type="submit" class="mt-6 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-base px-5 py-3 w-full sm:w-auto">Envoyer</button>
</form>
</div>
<div style="height: 92vh"></div>


@endsection
@section('title','création')