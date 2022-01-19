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
    @livewireStyles

</head>

<body>
    <header class="header" id="app">
        <div class="container header__wrap">
            <a href="/">
                <img src="{{ asset('img/logo.svg') }}" alt="" class="logo">
            </a>
            <nav class="nav">
                <ul class="nav__list">
                    @auth
                    <li class="nav__item{{ (request()->routeIs('applications.create')) ? ' active' : '' }}">
                        @can('application_create')
                            <a href="{{ route('applications.create') }}" class="nav__link">{{ __('Add a car') }}</a>
                        @endcan
                    </li>
                    <li class="nav__item{{ (request()->routeIs('applications.index')) ? ' active' : '' }}">
                        <a class="nav__link" href="{{ route('applications.index') }}">
                            {{ __('Applications') }}
                        </a>
                    </li>
                    <li class="nav__item nav__item-dd">
                        <a href="" class="nav__link">Таблицы</a>
                        <ul class="nav__item-dd-list">
                            @hasanyrole('Admin')
                            @canany(['legal_view', 'legal_create', 'legal_update', 'legal_delete'])
                                <li class="{{ (request()->routeIs('legals.index')) ? 'active' : '' }}">
                                    <a href="{{ route('legals.index') }}">
                                        {{ __('Legal entities') }}
                                    </a>
                                </li>
                            @endcanany
                            @endhasanyrole
                            @hasanyrole('SuperAdmin|Admin')
                            <li class="{{ (request()->routeIs('parkings.index')) ? 'active' : '' }}">
                                <a href="{{ route('parkings.index') }}" >
                                    {{ __('Parking lots') }}
                                </a>
                            </li>
                            @canany(['partner_view', 'partner_create', 'partner_update'])
                                <li class="{{ (request()->routeIs('partners.index')) ? 'active' : '' }}">
                                    <a href="{{ route('partners.index') }}" >
                                        {{ __('Partners') }}
                                    </a>
                                </li>
                            @endcanany
                            @endhasanyrole
                            @hasanyrole('Partner')
                            <li class="{{ (request()->routeIs('partner.parkings')) ? 'active' : '' }}">
                                <a href="{{ route('partner.parkings') }}" >
                                    {{ __('Parking lots') }}
                                </a>
                            </li>
                            @endhasanyrole
                            @canany(['user_view', 'user_create', 'user_update', 'user_delete'])

                                <li class="{{ (request()->routeIs('users.index')) ? 'active' : '' }}">
                                    <a href="{{ route('users.index') }}">{{ __('Users') }}</a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                    <li class="nav__item nav__item-dd">
                        <a href="" class="nav__link">{{ __('Report') }}</a>
                        <ul class="nav__item-dd-list">
                            @unlessrole('Partner|PartnerOperator')
                            <li>
                                <a href="{{ route('report.report-by-partner') }}">
                                    {{ __('Partner Report') }}
                                </a>
                            </li>
                            @endunlessrole
                            @hasanyrole('SuperAdmin|Admin|Manager')
                            <li>
                                <a href="{{ route('report.report-by-employee') }}">
                                    {{ __('Employee Report') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('report.report-all-partner') }}">
                                    {{ __('All Partners Report') }}
                                </a>
                            </li>
                            @endhasanyrole

                        </ul>
                    </li>
                    @hasanyrole('SuperAdmin')
                    <li class="nav__item nav__item-dd">
                        <a href="" class="nav__link">Настройки</a>
                        <ul class="nav__item-dd-list">

                            @canany(['partner_type_view', 'partner_type_create', 'partner_type_update'])
                                <li>
                                    <a href="{{ route('partner-types.index') }}" >
                                        {{ __('Partner types') }}
                                    </a>
                                </li>
                            @endcanany
                            @can('permission_update')
                                <li>
                                    <a href="{{ route('permissions.index') }}">{{ __('Permissions') }}</a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                    @endhasanyrole
                    @endauth
                </ul>
            </nav>
            @auth
            <div class="header__user ml-auto d-flex align-items-center">
                <div class="header__user-icon">
                    <svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 5.83366C13.0545 5.83366 10.6667 8.22147 10.6667 11.167C10.6667 14.1125 13.0545 16.5003 16 16.5003C18.9455 16.5003 21.3333 14.1125 21.3333 11.167C21.3333 8.22147 18.9455 5.83366 16 5.83366ZM8 11.167C8 6.74871 11.5817 3.16699 16 3.16699C20.4183 3.16699 24 6.74871 24 11.167C24 15.5853 20.4183 19.167 16 19.167C11.5817 19.167 8 15.5853 8 11.167ZM10.6667 24.5003C8.45753 24.5003 6.66667 26.2912 6.66667 28.5003C6.66667 29.2367 6.06971 29.8337 5.33333 29.8337C4.59695 29.8337 4 29.2367 4 28.5003C4 24.8184 6.98477 21.8337 10.6667 21.8337H21.3333C25.0152 21.8337 28 24.8184 28 28.5003C28 29.2367 27.403 29.8337 26.6667 29.8337C25.9303 29.8337 25.3333 29.2367 25.3333 28.5003C25.3333 26.2912 23.5425 24.5003 21.3333 24.5003H10.6667Z"
                              fill="#011A3F" />
                    </svg>
                </div>
                <div class="header__user-info">
                    <div class="header__user-pos">{{ Auth::user()->getRole() }}</div>
                    <div class="header__user-name">
                        {{ Auth::user()->name }} <span class="icon icon-arrow-d"></span>
                        <div class="header__user-dd">
                            <div>
                                <a href="{{ route('profile.edit') }}">
                                    {{ __('Profile') }}
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </header>
    <div id="app">

        {{--<header class="header">
            <div class="wrapper d-flex">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ url('/') }}/img/logo-ap.png" alt="{{ config('app.name', 'Laravel') }}" class="header__logo">
                </a>
                <div class="mob-menu-btn"><span></span></div>
                <div class="header__nav s-between">
                    @can('application_create')
                    <a href="{{ route('applications.create') }}" class="btn blue-btn">{{ __('Add application') }}</a>
                    @endcan
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
                        @endguest
                    </ul>
                    @auth
                    <div class="header__user-info nav__dd">
                                <i class="fa fa-file-text" aria-hidden="true"></i>
                                <span>{{ __('Report') }}</span> <span class="arrow-d"></span>
                                <ul class="nav__dd-list">
                                    @unlessrole('Partner|PartnerOperator')
                                    <li class="nav__dd-item">
                                        <a class="nav__dd-link" href="{{ route('report.report-by-partner') }}">
                                            {{ __('Partner Report') }}
                                        </a>
                                    </li>
                                    @endunlessrole
                                    @hasanyrole('SuperAdmin|Admin|Manager')
                                    <li class="nav__dd-item">
                                        <a class="nav__dd-link" href="{{ route('report.report-by-employee') }}">
                                            {{ __('Employee Report') }}
                                        </a>
                                    </li>
                                    <li class="nav__dd-item">
                                        <a class="nav__dd-link" href="{{ route('report.report-all-partner') }}">
                                            {{ __('All Partners Report') }}
                                        </a>
                                    </li>
                                    @endhasanyrole
                                </ul>
                            </div>

                    <div class="header__user-info nav__dd">
                        <i class="fa fa-cog" aria-hidden="true"></i>
                        <span>{{ __('Settings') }}</span> <span class="arrow-d"></span>
                        <ul class="nav__dd-list">
                            <li class="nav__dd-item">
                                <a class="nav__dd-link" href="{{ route('applications.index') }}">
                                    {{ __('Applications') }}
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

                            @canany(['user_view', 'user_create', 'user_update', 'user_delete'])

                                <li class="nav__dd-item">
                                    <a class="nav__dd-link" href="{{ route('users.index') }}">{{ __('Users') }}</a>
                                </li>
                            @endcanany
                            @can('permission_update')
                                <li class="nav__dd-item">
                                    <a class="nav__dd-link"
                                       href="{{ route('permissions.index') }}">{{ __('Permissions') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </div>

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
        </header>--}}
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

    <script type="text/javascript">
        @stack('scripts')
    </script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    {!! Toastr::message() !!}
    @livewireScripts
</body>

</html>
