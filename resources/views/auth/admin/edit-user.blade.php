@extends('auth.admin.layout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet"/>

<div class="w-full max-w-2xl mx-auto mt-10 mb-6">
  <div class="flex justify-center">
  <h2 class="text-xl font-bold mb-6">Modification des informations</h2>
</div>
  <form method="post" action="{{route('users.update',['id'=>$user->id])}}" enctype="multipart/form-data" >
  @csrf
  @method('PUT')
    <div class="grid grid-cols-2 gap-6">
      <div class="mb-6">
        <label for="nom" class="block mb-2 text-sm font-medium text-gray-900">Nom</label>
        <input type="text" id="nom" name="nom" value="{{$user->last_name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="nom de l'utilisateur">
        @if($errors->has('nom'))
							<span class="text-red-600">{{ $errors->first('nom') }}</span>
		@endif
      </div>
      <div class="mb-6">
        <label for="prenom" class="block mb-2 text-sm font-medium text-gray-900">Prénom</label>
        <input type="text" id="prenom" name="prenom" value="{{$user->first_name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="prenom de l'utilisateur">
        @if($errors->has('prenom'))
							<span class="text-red-600">{{ $errors->first('prenom') }}</span>
		@endif
      </div>
    </div>
    <div class="grid grid-cols-2 gap-6">
      <div class="mb-6">
        <label for="nom_ar" class="block mb-2 text-sm font-medium text-gray-900">Nom en Arabe</label>
        <input type="text" id="nom_ar" name="nom_ar" value="{{$user->last_name_ar }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="votre nom en arabe">
        @if($errors->has('nom_ar'))
							<span class="text-red-600">{{ $errors->first('nom_ar') }}</span>
		@endif
      </div>
      <div class="mb-6">
        <label for="prenom" class="block mb-2 text-sm font-medium text-gray-900">Prénom an arabe</label>
        <input type="text" id="prenom_ar" name="prenom_ar" value="{{$user->first_name_ar }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="votre prenom_ar">
        @if($errors->has('prenom_ar'))
							<span class="text-red-600">{{ $errors->first('prenom_ar') }}</span>
		@endif
      </div>
    </div>
    <div class="grid grid-cols-2 gap-6">
      <div class="mb-6">
        <label for="cin" class="block mb-2 text-sm font-medium text-gray-900">CIN</label>
        <input type="text" id="cin" name="cin" value="{{$user->cin }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Ex.:AB34123">
        @if($errors->has('cin'))
							<span class="text-red-600">{{ $errors->first('cin') }}</span>
		@endif
      </div>
    <div class="mb-6">
        <label for="datenaissance" class="block mb-2 text-sm font-medium text-gray-900">Date de naissance</label>
        <input type="date" id="datenaissance" value="{{ $user->date_naissance  }}"  name="datenaissance"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="date de naissance">
        @if($errors->has('datenaissance'))
							<span class="text-red-600">{{ $errors->first('datenaissance') }}</span>
		@endif
      </div>
    </div>
    <div class="grid grid-cols-2 gap-6">
    <div class="mb-6">
        <label for="numdesom" class="block mb-2 text-sm font-medium text-gray-900">Numéro de som</label>
        <input type="text" id="numdesom" name="numdesom" value="{{$user->num_de_som }}"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="numéro de som">
        @if($errors->has('numdesom'))
							<span class="text-red-600">{{ $errors->first('numdesom') }}</span>
		@endif
      </div>
      <div class="mb-6">
        <label for="role" class="block mb-2 text-sm font-medium text-gray-900">rôle</label>
        <select name="role" id="role" value="{{$user->role }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }} >admin</option>
            <option value="manager"  {{ $user->role == 'manager' ? 'selected' : '' }}>manager</option>
            <option value="user"  {{ $user->role == 'user' ? 'selected' : '' }}>user</option>
        </select>
        @if($errors->has('role'))
							<span class="text-red-600">{{ $errors->first('role') }}</span>
		@endif
    </div>
    </div>
    
    <div class="grid grid-cols-3 gap-6">
    <div class="mb-6">
        <label for="nationalite" class="block mb-2 text-sm font-medium text-gray-900">Nationalité</label>
        <input type="text" id="nationatlite" name="nationalite" value={{$user->nationalite}} class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="votre nationalité">
        @if($errors->has('nationalite'))
							<span class="text-red-600">{{ $errors->first('nationalite') }}</span>
		@endif
      </div>
      <div class="mb-6">
        <label for="nationalite_ar" class="block mb-2 text-sm font-medium text-gray-900">Nationalité an arabe</label>
        <input type="text" id="nationalite_ar" name="nationalite_ar" value={{$user->nationalite_ar}} class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="votre nationalité an arabe">
        @if($errors->has('nationalite_ar'))
							<span class="text-red-600">{{ $errors->first('nationalite_ar') }}</span>
		@endif
      </div>
      <div class="mb-6">
        <label for="situation" class="block mb-2 text-sm font-medium text-gray-900">Situation familiale</label>
        <select id="situation" name="situation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" >
        @foreach($situationsFamiliales as $situationFamiliale)
        <option value="{{$situationFamiliale}}" {{ $user->situation == $situationFamiliale ? 'selected' : '' }}>{{$situationFamiliale}}</option>
        @endforeach
        </select>
        @if($errors->has('situation'))
							<span class="text-red-600">{{ $errors->first('situation') }}</span>
		@endif
      </div>
    </div>
    <div class="grid grid-cols-2 gap-6">
    <div class="mb-6">
        <label for="genre" class="block mb-2 text-sm font-medium text-gray-900">genre</label>
        <select name="genre" id="genre" value="{{$user->genre }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <option value="homme" {{ $user->genre == 'homme' ? 'selected' : '' }}>homme</option>
            <option value="femme" {{ $user->genre == 'femme' ? 'selected' : '' }}>femme</option>
        </select>
        @if($errors->has('genre'))
							<span class="text-red-600">{{ $errors->first('genre') }}</span>
		@endif
    </div>
      <div class="mb-6">
        <label for="photo" class="block mb-2 text-sm font-medium text-gray-900">Photo</label>
        <input type="file" id="photo" name="photo" value="{{$user->image }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="photo">
        @if($errors->has('photo'))
							<span class="text-red-600">{{ $errors->first('photo') }}</span>
		@endif
      </div>
    </div>
    <div class="grid grid-cols-3 gap-6">
    <div class="mb-6">
        <label for="poste" class="block mb-2 text-sm font-medium text-gray-900">poste </label>
        <input type="text" id="poste" name="poste" value="{{$user->poste }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="votre poste">
        @if($errors->has('poste'))
							<span class="text-red-600">{{ $errors->first('poste') }}</span>
		@endif
      </div>
    <div class="mb-6">
        <label for="poste" class="block mb-2 text-sm font-medium text-gray-900">poste en arabe </label>
        <input type="text" id="poste" name="poste_ar" value="{{$user->poste_ar }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="votre poste">
        @if($errors->has('poste_ar'))
							<span class="text-red-600">{{ $errors->first('poste_ar') }}</span>
		@endif
      </div>
    <div class="mb-6">
        <label for="departement" class="block mb-2 text-sm font-medium text-gray-900">département</label>
        <select name="departement" id="departement" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" >
        @foreach($departements as $departement)
        <option value="{{$departement->id}}" {{ $user->departement_id == $departement->id ? 'selected' : '' }}>{{$departement->depart_name}}</option>
        @endforeach
        </select>
        @if($errors->has('departement'))
							<span class="text-red-600">{{ $errors->first('departement') }}</span>
		@endif
    </div>
    </div>
    
    
    <div class="grid grid-cols-2 gap-6">
      <div class="mb-6">
        <label for="Email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
        <input type="email" id="Email" value="{{$user->email }}" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="saisir l'Email">
        @if($errors->has('email'))
							<span class="text-red-600">{{ $errors->first('email') }}</span>
		@endif
      </div>
    <div class="mb-6">
        <label for="number" class="block mb-2 text-sm font-medium text-gray-900">numéro de téléphone</label>
        <input type="text" id="number" name="phonenumber" value="{{$user->phone }}"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="numéro de téléphone">
        @if($errors->has('phonenumber'))
							<span class="text-red-600">{{ $errors->first('phonenumber') }}</span>
		@endif
      </div>
    </div>
    <div class="flex justify-center">
      <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2.5 px-5 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" onclick="submitForm(event,'voulez-vous modifier les informations de cet utilisateur ?','Modifier')">Modifier</button>
    </div>
    <!-- Rest of the form fields -->
  </form>
</div>
@endsection
@section('title','modification des informations')