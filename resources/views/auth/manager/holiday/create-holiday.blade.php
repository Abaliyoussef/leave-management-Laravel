@extends('auth.manager.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet"/>

<div class="w-full max-w-md mx-auto mt-10 mb-6">

<form class="w-full max-w-md mx-auto mt-10" method="post" action="{{route('holidays.store')}}">
		
@csrf
<div class="flex justify-center">
<h2 class="text-xl font-bold mb-6">Création</h2>
</div>

  <div class="mb-6">
    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Intitulé</label>
    <input type="text" id="description" name="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Ex.: la marche verte">
    @if($errors->has('description'))
							<span class="text-red-600">{{ $errors->first('description') }}</span>
		@endif
  </div>
  <div class="mb-6">
    <label for="date" class="block mb-2 text-sm font-medium text-gray-900">Date</label>
    <input type="date" id="date" name="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" >
    @if($errors->has('date'))
							<span class="text-red-600">{{ $errors->first('date') }}</span>
		@endif
  </div>
  <div class="flex justify-center">
  <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-base px-5 py-3 w-full sm:w-auto">Créer</button>
  </div>
</form>
</div>
<div style="height: 92vh"></div>


@endsection
@section('title','création')