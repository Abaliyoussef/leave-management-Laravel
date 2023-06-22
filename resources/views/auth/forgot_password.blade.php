@extends('home')
@section('content')
<div class="flex items-center justify-center h-screen">
    
<form class="w-full max-w-md mx-auto mt-10"method="post" action="{{route('password.request')}}">
@csrf
  <div class="mb-6">
    <label for="email" class=" mb-2 text-sm font-medium text-gray-900">Mot de passe oublié? Aucun problème. Indiquez-nous simplement votre adresse e-mail et nous vous enverrons par e-mail un lien de réinitialisation de mot de passe qui vous permettra d'en choisir un nouveau.</label>
    <input type="email" id="email" name="email" class=" mt-5 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="name@example.com">
  </div>


  <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-base px-5 py-3 w-full sm:w-auto">Envoyer</button>
</form>
</div>



@endsection
@section('title','forgot password')