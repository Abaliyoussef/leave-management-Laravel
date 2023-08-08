@extends('auth.admin.layout')
@section('content')


<link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet"/>
<div class="w-full max-w-2xl mx-auto mt-10 mb-6">
  <div class="flex justify-center">
  <h2 class="text-xl font-bold mb-6">création d'un utilisateur</h2>
</div>
  <form method="post" action="{{route('users.store')}}" enctype="multipart/form-data" >
  @csrf
    <div class="grid grid-cols-2 gap-6">
      <div class="mb-6">
        <label for="nom" class="block mb-2 text-sm font-medium text-gray-900">Nom</label>
        <input type="text" id="nom" name="nom" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="nom de l'utilisateur">
        @if($errors->has('nom'))
			<span class="text-red-600">{{ $errors->first('nom') }}</span>
		@endif
      </div>
      <div class="mb-6">
        <label for="prenom" class="block mb-2 text-sm font-medium text-gray-900">Prenom</label>
        <input type="text" id="prenom" name="prenom" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="prenom de l'utilisateur">
        @if($errors->has('prenom'))
							<span class="text-red-600">{{ $errors->first('prenom') }}</span>
		@endif
      </div>
    </div>
    <div class="grid grid-cols-2 gap-6">
      <div class="mb-6">
        <label for="nom_ar" class="block mb-2 text-sm font-medium text-gray-900">Nom en Arabe</label>
        <input type="text" id="nom_ar" name="nom_ar" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="votre nom en arabe">
        @if($errors->has('nom_ar'))
							<span class="text-red-600">{{ $errors->first('nom_ar') }}</span>
		@endif
      </div>
      <div class="mb-6">
        <label for="prenom" class="block mb-2 text-sm font-medium text-gray-900">Prénom an arabe</label>
        <input type="text" id="prenom_ar" name="prenom_ar" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="votre prenom_ar">
        @if($errors->has('prenom_ar'))
							<span class="text-red-600">{{ $errors->first('prenom_ar') }}</span>
		@endif
      </div>
    </div>
    <div class="grid grid-cols-2 gap-6">
      <div class="mb-6">
        <label for="cin" class="block mb-2 text-sm font-medium text-gray-900">CIN</label>
        <input type="text" id="cin" name="cin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Ex.:AB34123">
        @if($errors->has('cin'))
							<span class="text-red-600">{{ $errors->first('cin') }}</span>
		@endif
      </div>
    <div class="mb-6">
        <label for="datenaissance" class="block mb-2 text-sm font-medium text-gray-900">Date de naissance</label>
        <input type="date" id="datenaissance" name="datenaissance"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="date de naissance">
        @if($errors->has('datenaissance'))
							<span class="text-red-600">{{ $errors->first('datenaissance') }}</span>
		@endif
      </div>
    </div>
    
    <div class="grid grid-cols-2 gap-6">
    <div class="mb-6">
        <label for="numdesom" class="block mb-2 text-sm font-medium text-gray-900">numéro de som</label>
        <input type="text" id="numdesom" name="numdesom"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="numéro de som">
        @if($errors->has('numdesom'))
							<span class="text-red-600">{{ $errors->first('numdesom') }}</span>
		@endif
      </div>
      <div class="mb-6">
        <label for="role" class="block mb-2 text-sm font-medium text-gray-900">role</label>
        <select name="role" id="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Role">
            <option value="admin">admin</option>
            <option value="manager">manager</option>
            <option value="user">user</option>
        </select>
        @if($errors->has('role'))
							<span class="text-red-600">{{ $errors->first('role') }}</span>
		@endif
    </div>
    </div>
    
    <div class="grid grid-cols-3 gap-6">
    <div class="mb-6">
        <label for="nationalite" class="block mb-2 text-sm font-medium text-gray-900">Nationalité</label>
        <input type="text" id="nationatlite" name="nationalite" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="votre nationalité">
        @if($errors->has('nationalite'))
							<span class="text-red-600">{{ $errors->first('nationalite') }}</span>
		@endif
      </div>
      <div class="mb-6">
        <label for="nationalite_ar" class="block mb-2 text-sm font-medium text-gray-900">Nationalité an arabe</label>
        <input type="text" id="nationalite_ar" name="nationalite_ar" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="votre nationalité an arabe">
        @if($errors->has('nationalite_ar'))
							<span class="text-red-600">{{ $errors->first('nationalite_ar') }}</span>
		@endif
      </div>
      <div class="mb-6">
        <label for="situation" class="block mb-2 text-sm font-medium text-gray-900">Situation familiale</label>
        <select id="situation" name="situation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" >
        @foreach($situationsFamiliales as $situationFamiliale)
        <option value="{{$situationFamiliale}}" {{ auth()->user()->situation == $situationFamiliale ? 'selected' : '' }}>{{$situationFamiliale}}</option>
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
        <select name="genre" id="genre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"placeholder="Genre">>
            <option value="homme">homme</option>
            <option value="femme">femme</option>
        </select>
        @if($errors->has('genre'))
							<span class="text-red-600">{{ $errors->first('genre') }}</span>
		@endif
    </div>
      <div class="mb-6">
        <label for="photo" class="block mb-2 text-sm font-medium text-gray-900">Photo</label>
        <input type="file" id="photo" name="photo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="photo">
        @if($errors->has('photo'))
							<span class="text-red-600">{{ $errors->first('photo') }}</span>
		@endif
      </div>
    </div>
    <div class="grid grid-cols-3 gap-6">
    <div class="mb-6">
        <label for="poste" class="block mb-2 text-sm font-medium text-gray-900">poste </label>
        <input type="text" id="poste" name="poste" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="le poste de l'utilisateur">
        @if($errors->has('poste'))
							<span class="text-red-600">{{ $errors->first('poste') }}</span>
		@endif
      </div>
      <div class="mb-6">
        <label for="poste" class="block mb-2 text-sm font-medium text-gray-900">poste en arabe</label>
        <input type="text" id="poste_ar" name="poste_ar" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="votre poste en arabe">
        @if($errors->has('poste_ar'))
							<span class="text-red-600">{{ $errors->first('poste_ar') }}</span>
		@endif
      </div>
    <div class="mb-6">
        <label for="departement" class="block mb-2 text-sm font-medium text-gray-900">département</label>
        <select name="departement" id="departement" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" >
        @foreach($departements as $departement)
        <option value="{{$departement->id}}">{{$departement->depart_name}}</option>
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
        <input type="email" id="Email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="saisir l'mail d'utilisateur">
        @if($errors->has('email'))
							<span class="text-red-600">{{ $errors->first('email') }}</span>
		@endif
      </div>
    <div class="mb-6">
        <label for="number" class="block mb-2 text-sm font-medium text-gray-900">Numéro de téléphone</label>
        <input type="text" id="number" name="phonenumber"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="numéro de téléphone">
        @if($errors->has('phonenumber'))
							<span class="text-red-600">{{ $errors->first('phonenumber') }}</span>
		@endif
      </div>
    </div>
    <div class="grid grid-cols-2 gap-6">
      <div class="mb-6">
        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Mot de passe</label>
        <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="saisir le mot de passe">
        @if($errors->has('password'))
							<span class="text-red-600">{{ $errors->first('password') }}</span>
		@endif
      </div>
    <div class="mb-6">
        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirmer le mot de passe</label>
        <input type="password" id="password_confirmation" name="password_confirmation"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="confirmer le mot de passe">
        @if($errors->has('password_confirmation'))
							<span class="text-red-600">{{ $errors->first('password_confirmation') }}</span>
		@endif
      </div>
    </div>
    <div class="flex justify-center">
      <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2.5 px-5 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" onclick="submitForm(event,'voulez-vous Ajouter cet utilisateur ?','Ajouter')">Créer</button>
    </div>
    <!-- Rest of the form fields -->
  </form>
</div>
@endsection
@section('title','création des utilisateurs')