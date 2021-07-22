<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} | {{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script type="text/javascript">
        const APP_URL = {!! json_encode(url('/')) !!};
        </script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            @canany(['user_view', 'user_create', 'user_update', 'user_delete'])
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}">{{ __('Users') }}</a>
                            </li>
                            @endcanany
                            @can('permission_update')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('permissions.index') }}">{{ __('Permissions') }}</a>
                                </li>
                            @endcan
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}" >
                                        {{ __('Profile') }}
                                    </a>
                                    @hasanyrole('Admin')
                                        @canany(['legal_view', 'legal_create', 'legal_update', 'legal_delete'])
                                            <a class="dropdown-item" href="{{ route('legals.index') }}" >
                                                {{ __('Legal entities') }}
                                            </a>
                                        @endcanany
                                    @endhasanyrole

                                    @hasanyrole('SuperAdmin|Admin')
                                    <a class="dropdown-item" href="{{ route('parkings.index') }}" >
                                        {{ __('Parking lots') }}
                                    </a>
                                        @canany(['partner_view', 'partner_create', 'partner_update'])
                                            <a class="dropdown-item" href="{{ route('partners.index') }}" >
                                                {{ __('Partners') }}
                                            </a>
                                        @endcanany
                                        @canany(['partner_type_view', 'partner_type_create', 'partner_type_update'])
                                            <a class="dropdown-item" href="{{ route('partner-types.index') }}" >
                                                {{ __('Partner types') }}
                                            </a>
                                        @endcanany
                                    @endhasanyrole

                                    @hasanyrole('Partner')
                                    <a class="dropdown-item" href="{{ route('partner.parkings') }}" >
                                        {{ __('Parking lots') }}
                                    </a>
                                    @endhasanyrole
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
            @if (session('success'))
                <x-alert type="success" :message="session('success')"/>
            @elseif(session('error'))
                <x-alert type="danger" :message="session('error')"/>
            @elseif(session('warning'))
                <x-alert type="warning" :message="session('warning')"/>
            @elseif (session('resent'))
                <x-alert type="success" :message="__('A fresh verification link has been sent to your email address.')"/>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
