@extends('auth.admin.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet"/>

<div class="w-full max-w-md mx-auto mt-10 mb-6">

<form class="w-full max-w-md mx-auto mt-10" method="post" action="{{route('departements.update',['departement'=>$departement->id])}}">
@method('PUT')
@csrf
<h2 class="text-xl font-bold mb-6">Modification</h2>
  <div class="mb-6">
    <label for="nom" class="block mb-2 text-sm font-medium text-gray-900">nom</label>
    <input type="text" id="nom" name="nom" value="{{$departement->depart_name}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="nom du département">

  </div>
  
  <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-base px-5 py-3 w-full sm:w-auto">Modifier</button>
</form>
</div>

<div style="height: 92vh"></div>


@endsection
@section('title','modification')