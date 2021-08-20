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


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">

        {{-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ url('/') }}/img/logo-ap.png" alt="{{ config('app.name', 'Laravel') }}" class="header__logo">
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
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
                                <li class="nav__item">
                                    <a class="nav__link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav__item">
                                    <a class="nav__link" href="{{ route('register') }}">{{ __('Register') }}</a>
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
                                    <a class="nav-link"
                                        href="{{ route('permissions.index') }}">{{ __('Permissions') }}</a>
                                </li>
                            @endcan
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        {{ __('Profile') }}
                                    </a>
                                    @hasanyrole('Admin')
                                    @canany(['legal_view', 'legal_create', 'legal_update', 'legal_delete'])
                                        <a class="dropdown-item" href="{{ route('legals.index') }}">
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
                                </li>
                            @endcanany
                            @endhasanyrole
                            <li class="nav__dd-item">
                                <a class="nav__dd-link" href="{{ route('parkings.index') }}">
                                    {{ __('Parking lots') }}
                                </a>
                            </li>
                            <li class="nav__dd-item">
                                <a class="nav__dd-link" href="{{ route('partners.index') }}">
                                    {{ __('Partners') }}
                                </a>
                            </li>
                            <li class="nav__dd-item">
                                <a class="nav__dd-link" href="{{ route('partner-types.index') }}">
                                    {{ __('Partner types') }}
                                </a>
                            </li>
                            <li class="nav__dd-item">
                                <a class="nav__dd-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                            </li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </ul>
                    </div>
                    @endauth
                </div>
            </div>
        </nav> --}}
        <header class="header">
            <div class="wrapper d-flex">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ url('/') }}/img/logo-ap.png" alt="{{ config('app.name', 'Laravel') }}" class="header__logo">
                </a>
                <div class="mob-menu-btn"><span></span></div>
                <div class="header__nav s-between">
                    <a href="{{ route('applications.create') }}" class="btn blue-btn">Добавить заявку</a>
                    <ul class="nav s-between">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav__item">
                                    <a class="nav__link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav__item">
                                    <a class="nav__link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            @canany(['user_view', 'user_create', 'user_update', 'user_delete'])
                                <li class="nav__item">
                                    <a class="nav__link" href="{{ route('users.index') }}">{{ __('Users') }}</a>
                                </li>
                            @endcanany
                            @can('permission_update')
                                <li class="nav__item">
                                    <a class="nav__link"
                                        href="{{ route('permissions.index') }}">{{ __('Permissions') }}</a>
                                </li>
                            @endcan
                        @endguest
                    </ul>
                    @auth
                    <div class="header__user-info nav__dd">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <span>{{ Auth::user()->name }}</span> <span class="arrow-d"></span>
                        <ul class="nav__dd-list">
                            <li class="nav__dd-item">
                                <a class="nav__dd-link" href="{{ route('profile.edit') }}">
                                    {{ __('Profile') }}
                                </a>
                            </li>
                            <li class="nav__dd-item">
                                <a class="nav__dd-link" href="{{ route('applications.index') }}">
                                    {{ __('Application') }}
                                </a>
                            </li>
                            @hasanyrole('Admin')
                                @canany(['legal_view', 'legal_create', 'legal_update', 'legal_delete'])
                                    <li class="nav__dd-item">
                                        <a class="nav__dd-link" href="{{ route('legals.index') }}">
                                            {{ __('Legal entities') }}
                                        </a>
                                    </li>
                                @endcanany
                            @endhasanyrole

                            @hasanyrole('SuperAdmin|Admin')
                            <li class="nav__dd-item">
                                <a class="nav__dd-link" href="{{ route('parkings.index') }}" >
                                    {{ __('Parking lots') }}
                                </a>
                            </li>
                            @canany(['partner_view', 'partner_create', 'partner_update'])
                                <li class="nav__dd-item">
                                    <a class="nav__dd-link" href="{{ route('partners.index') }}" >
                                        {{ __('Partners') }}
                                    </a>
                                </li>
                            @endcanany
                            @canany(['partner_type_view', 'partner_type_create', 'partner_type_update'])
                                <li class="nav__dd-item">
                                    <a class="nav__dd-link" href="{{ route('partner-types.index') }}" >
                                        {{ __('Partner types') }}
                                    </a>
                                </li>
                            @endcanany
                            @endhasanyrole

                            @hasanyrole('Partner')
                            <li class="nav__dd-item">
                                <a class="nav__dd-link" href="{{ route('partner.parkings') }}" >
                                    {{ __('Parking lots') }}
                                </a>
                            </li>
                            @endhasanyrole
                            <li class="nav__dd-item">
                                <a class="nav__dd-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                            </li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </ul>
                    </div>
                    @endauth
                </div>
            </div>
        </header>
        <main class="py-4">
            @if (session('success'))
                <x-alert type="success" :message="session('success')" />
            @elseif(session('error'))
                <x-alert type="danger" :message="session('error')" />
            @elseif(session('warning'))
                <x-alert type="warning" :message="session('warning')" />
            @elseif (session('resent'))
                <x-alert type="success"
                    :message="__('A fresh verification link has been sent to your email address.')" />
            @endif
            @yield('content')
        </main>
    </div>

    <footer class="footer">
        <div class="wrapper s-between">
            <img src="./img/logo-ap-footer.png" alt="" class="footer__logo">
            <span>© 2021 parkingscars.ru</span>
        </div>
    </footer>

    <script type="text/javascript">
    @stack('scripts')
    </script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

</body>

</html>
