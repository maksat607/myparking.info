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
        <header class="header">
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
    <script src="{{ asset('js/app.js') }}"></script>
    {!! Toastr::message() !!}
</body>

</html>
