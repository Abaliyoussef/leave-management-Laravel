@extends('auth.manager.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet"/>

<div class="w-full max-w-md mx-auto mt-10 mb-6">

<form class="w-full max-w-md mx-auto mt-10" method="post" action="{{route('conge.store')}}">
@if($user->score==0)

<script>
  Swal.fire({
  icon: 'warning',
  title: 'Attention!',
  text: 'Cet employé a épuisé son solde de jours de congés.',
  footer: '<a href="{{route('users.index')}}"><Bold>Liste des utilisateur</Bold>s</a>'
})
</script>
@endif
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

@csrf
<h2 class="text-xl font-bold mb-6">Création du congé</h2>
<input type="hidden"  name="user_id" value="{{$user->id}}" >
<input type="hidden"  name="status" value="Accordé" >


  <div class="mb-6">
    <label for="nom" class="block mb-2 text-sm font-medium text-gray-900">L'employé</label>
    <input type="text" id="nom" name="nom" value="{{$user->last_name.' '.$user->first_name}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"  disabled>
  </div>
  <div class="mb-6">
    <label for="" class="block mb-2 text-sm font-medium text-gray-900">Date de début</label>
    <input type="date"  name="date_debut"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"  >
    @if($errors->has('date_debut'))
							<span class="text-red-600">{{ $errors->first('date_debut') }}</span>
		@endif
  </div>  <div class="mb-6">
    <label for="" class="block mb-2 text-sm font-medium text-gray-900">Date de fin</label>
    <input type="date"  name="date_fin"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"  min="" placeholder="Select a date" >
    @if($errors->has('date_fin'))
							<span class="text-red-600">{{ $errors->first('date_fin') }}</span>
		@endif
  </div>
    
  <div class="">
    <label for="" class="block mb-2 text-sm font-medium text-gray-900">Description (facultatif)</label>
    <textArea   name="description"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"  ></textArea>
  </div>
  <button type="submit" class="mt-6 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-base px-5 py-3 w-full sm:w-auto">Créer</button>
</form>
</div>
<div style="height: 92vh"></div>


@endsection
@section('title','création')