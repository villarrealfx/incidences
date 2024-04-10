<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @livewireStyles()
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                @php
                    $period_exists = DB::table('periods')->get();
                @endphp
                    @if ($period_exists->count())
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Reportes</button>
                                <ul class="dropdown-menu">
                                    <li class="nav-item"><a class="dropdown-item @if (Route::currentRouteName() == 'ndi') active @endif" href="{{ route('ndi') }}">NDI</a></li>
                                    <li class="nav-item"><a class="dropdown-item @if (Route::currentRouteName() == 'ndi.year') active @endif" href="{{ route('ndi.year') }}">NDI Anual</a></li>
                                    {{--<li class="nav-item"><a class="dropdown-item @if (Route::currentRouteName() == 'charts') active @endif" href="{{ route('charts') }}">Gráficas</a></li>--}}
                                    <li class="nav-item"><a class="dropdown-item @if (Route::currentRouteName() == 'manual-operations') active @endif" href="{{ route('manual-operations') }}">Operaciones Manuales</a></li>
                                    {{--<li class="nav-item"><a class="dropdown-item @if (Route::currentRouteName() == 'pac-list') active @endif" href="{{ route('pac-list') }}">Listado PAC</a></li>--}}
                                    <li class="nav-item"><a class="dropdown-item @if (Route::currentRouteName() == 'pac-blocks') active @endif" href="{{ route('pac-blocks') }}">PAC</a></li>
                                </ul>
                            </div>
                            {{--<li class="nav-item">
                                <a class="nav-link @if (Route::currentRouteName() == 'charts') active @endif" href="{{ route('charts') }}">Gráficas</a>
                            </li>--}}
                        </ul>
                    @endif

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->

                        @guest
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Data Maestra</button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('service.centers') }}" class="dropdown-item @if (Route::currentRouteName() == 'service.centers') active @endif">Centros de Servicio</a></li>
                                    <li><a href="{{ route('substations') }}" class="dropdown-item @if (Route::currentRouteName() == 'substations') active @endif">Subestaciones</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                        <p class="text-muted text-center">Circuitos</p>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a href="{{ route('circuits') }}" class="dropdown-item @if (Route::currentRouteName() == 'circuits') active @endif">Circuitos</a></li>
                                    <li><a href="{{ route('circuit-loads') }}" class="dropdown-item @if (Route::currentRouteName() == 'circuit-loads') active @endif">Cargas</a></li>
                                    <li><a href="{{ route('disconnectors') }}" class="dropdown-item @if (Route::currentRouteName() == 'disconnectors') active @endif">Seccionadores</a></li>
                                    <li><a href="{{ route('fuse-cutouts') }}" class="dropdown-item @if (Route::currentRouteName() == 'fuse-cutouts') active @endif">Cortacorrientes</a></li>
                                    <li><a href="{{ route('transformer-banks') }}" class="dropdown-item @if (Route::currentRouteName() == 'transformer-banks') active @endif">Bancos</a></li>
                                    <li><a href="{{ route('distribution-transformers') }}" class="dropdown-item @if (Route::currentRouteName() == 'distribution-transformers') active @endif">Transformadores</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                        <p class="text-muted text-center">Incidencias</p>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a href="{{ route('periods') }}" class="dropdown-item @if (Route::currentRouteName() == 'periods') active @endif">Períodos</a></li>
                                    <li><a href="{{ route('systems') }}" class="dropdown-item @if (Route::currentRouteName() == 'systems') active @endif">Sistemas</a></li>
                                    <li><a href="{{ route('causes') }}" class="dropdown-item @if (Route::currentRouteName() == 'causes') active @endif">Causas</a></li>
                                </ul>
                            </div>
                            <li class="nav-item">
                                <a class="nav-link @if (Route::currentRouteName() == 'incidences') active @endif" href="{{ route('incidences') }}">Incidencias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if (Route::currentRouteName() == 'upload.file') active @endif" href="{{ route('upload.file') }}">Importar</a>
                            </li>

                            {{--@if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif--}}
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @livewireScripts()
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</body>
</html>
