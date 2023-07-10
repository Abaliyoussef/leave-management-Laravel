<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>@yield('title')</title>
        <link href="{{ url('css/styles.css') }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </head>
    <body>
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.html">Tasmim Web</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{route('profile')}}">Paramètres</a></li>
                        <li><a class="dropdown-item" href="{{route('logout')}}">Se déconnecter</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading text-white">Jours restants : {{auth()->user()->score}}</div>

                            <div class="sb-sidenav-menu-heading">Administration</div>
                            <a class="nav-link collapsed" href="{{route('employe.Allconges',['id'=>auth()->user()->id])}}" >
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Mes conges
                            </a>
                            <a class="nav-link collapsed" href="{{route('employe.conge.proposition',['id'=>auth()->user()->id])}}" >
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Propositions de conges
                            </a>
                            <a class="nav-link collapsed" href="{{route('employe.conge.expires',['id'=>auth()->user()->id])}}" >
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Mes conges expiré
                            </a>
                            <a class="nav-link collapsed" href="{{route('conge.create',['id'=>auth()->user()->id])}}" >
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                créer un conge
                            </a>
                            <a class="nav-link collapsed" href="{{route('employe.holidays')}}" >
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                calendrier
                            </a>

                          
                            
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                    <i class="fa fa-user" aria-hidden="true"></i> 
                        @if(auth()->check())
                    {{ auth()->user()->first_name.' '.auth()->user()->last_name }}
                        @endif
                    </div>
                </nav>
            </div>
                        
                        @yield('content')
                  
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{url('js/scripts.js')}}"></script>
        
    </body>
</html>
