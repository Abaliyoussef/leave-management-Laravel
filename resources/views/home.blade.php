<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"> 
<link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet"/>
<title>@yield('title')</title>
</head>
<body>
<nav class="bg-gray-900 text-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <!-- Brand Link -->
      <div class="flex-shrink-0">
        <a href="#" class="text-xl font-bold">Brand</a>
      </div>
      
      <!-- Navigation Links -->
      <div class="flex space-x-4">
        <a href="#" class="hover:text-gray-300">Home</a>
        @guest
        <a href="{{route('login')}}" class="hover:text-gray-300">Login</a>
        <a href="{{route('register')}}" class="hover:text-gray-300">Register</a>
        @else
        <a href="{{route('dashboard')}}" class="hover:text-gray-300">Dashboard</a>
        @endguest
      </div>
    </div>
  </div>
</nav>
@section('title','Accueil')
@yield('content')
</body>
</html>