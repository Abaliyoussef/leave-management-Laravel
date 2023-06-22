@extends('home')
@section('content')
<div class="flex items-center justify-center h-screen">

<form class="w-full max-w-md mx-auto mt-10" method="post" action="{{route('password.update')}}">
		
@csrf
<h2 class="text-xl font-bold mb-6">réinitialisation de mot de passe</h2>
  <div class="mb-6">
    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
    <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="name@exemple.com">
    @if($errors->has('email'))
							<span class="text-red-600">{{ $errors->first('email') }}</span>
		@endif
  </div>
  <div class="mb-6">
    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
    <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="nouveau mot de passe">
    @if($errors->has('password'))
							<span class="text-red-600">{{ $errors->first('password') }}</span>
		@endif
  </div>
  <div class="mb-6">
    <label for="password_confirmation " class="block mb-2 text-sm font-medium text-gray-900">Email</label>
    <input type="password" id="password_confirmation " name="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="nouveau mot de passe">
    @if($errors->has('password_confirmation'))
							<span class="text-red-600">{{ $errors->first('password_confirmation') }}</span>
		@endif
  </div>
  <input type="hidden" id="" name="token" value="{{$token}}">


  <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-base px-5 py-3 w-full sm:w-auto">Submit</button>
</form>
</div>



@endsection
@section('title','reset password')