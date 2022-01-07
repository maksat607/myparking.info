@extends('layouts.app')

@section('content')

    <div class="container page-head-wrap">
        <div class="page-head">
            <div class="page-head__top d-flex align-items-center">
                <h1>{{ $title }}</h1>
                <label class="field-style blue">
                    <span>Поиск</span>
                    <input type="text" placeholder="Поиск по столбцам">
                </label>
                <div class="ml-auto d-flex">
                    @can('issetPartnerOperator', Auth::user())
                        <a class="btn btn-white" href="{{ route('users.create') }}" >Добавить</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="inner-page">
            <table class="table">
                <thead>
                <tr>
                    <th></th>
                    <th>Имя</th>
                    @hasanyrole('SuperAdmin|Admin|Manager')
                    <th>Роль</th>
                    @endhasanyrole
                    <th>E-Mail</th>
                    @hasanyrole('SuperAdmin')
                    <th scope="col">@lang('Legal entities')</th>
                    @endhasanyrole
                    <th>Стоянки</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                <tr @if(!$user->status)class="disabled-tr" @endif>
                    <td class="tr-id">{{ $loop->iteration }}</td>
                    <td>
                        <div class="first-info d-flex align-items-center
                                @if($user->children_count) table-dd-show @endif"
                                @if($user->children_count) id="table-dd-{{ Str::slug($user->name, '_') }}" @endif>
                            <span class="status-dot status-success">&bull;</span>
                            <span>{{ $user->name }}</span>
                            @if ($user->children_count)
                                <span class="count-user">&nbsp;({{ $user->children_count }})</span>
                                <span class="dd-table-arrow"></span>
                            @endisset
                        </div>
                    </td>
                    @hasanyrole('SuperAdmin|Admin|Manager')
                    <td>{{ $user->getRole() }}</td>
                    @endhasanyrole
                    <td>{{ $user->email }}</td>
                    @hasanyrole('SuperAdmin')
                    <td>
                        @if ($user->legals->isNotEmpty())
                            <a href="{{ route('legals.view', ['user'=>$user->id, 'legal'=>$user->legals->pluck('id')->first()]) }}">
                                {{ $user->legals->pluck('name')->first() }}
                            </a>
                            @if($user->legals->count() > 1)
                                <br>
                                <a href="{{ route('legals.all', ['user'=>$user->id]) }}">{{ __('show all') }}</a>
                            @endif
                        @else
                            <p>{{ __('No Legal entity') }}</p>
                        @endif
                    </td>
                    @endhasanyrole
                    <td>
                        @if($user->managerParkings->isNotEmpty())
                            <a href="{{ route('parkings.edit', ['parking' => $user->managerParkings->pluck('id')->first()]) }}">
                                {{ $user->managerParkings->pluck('title')->first() }}
                            </a>
                            @if($user->managerParkings->count() > 1)
                                <br>
                                <a href="{{ route('user.parking.all', ['user'=>$user->id]) }}">{{ __('show all') }}</a>
                            @endif
                        @else
                            <p>{{ __('No parking lots') }}</p>
                        @endif
                    </td>
                    <td>
                        <div class="car-dd">
                            <div class="car-close-dd"></div>
                            <div class="car-dd-body">
                                @can('user_update')
                                    <a href="{{ route('users.edit', ['user'=>$user->id]) }}" class="link">
                                        <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13.2929 0.292893C13.6834 -0.0976311 14.3166 -0.0976311 14.7071 0.292893L18.7071 4.29289C19.0976 4.68342 19.0976 5.31658 18.7071 5.70711L5.70711 18.7071C5.51957 18.8946 5.26522 19 5 19H1C0.447715 19 0 18.5523 0 18V14C0 13.7348 0.105357 13.4804 0.292893 13.2929L10.2927 3.2931L13.2929 0.292893ZM11 5.41421L2 14.4142V17H4.58579L13.5858 8L11 5.41421ZM15 6.58579L16.5858 5L14 2.41421L12.4142 4L15 6.58579Z" fill="#536E9B"></path>
                                        </svg>
                                    </a>
                                @endcan
                                @can(['delete_self', 'user_delete'], $user)
                                    <a href="#" onclick="if( confirm('Delete it?') ) {
                                        event.preventDefault();
                                        document.getElementById('deleteUser{{ $user->id }}').submit(); return true }" class="link">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g opacity="0.6">
                                                <path d="M7 4C7 2.89543 7.89543 2 9 2H15C16.1046 2 17 2.89543 17 4V6H18.9897C18.9959 5.99994 19.0021 5.99994 19.0083 6H21C21.5523 6 22 6.44772 22 7C22 7.55228 21.5523 8 21 8H19.9311L19.0638 20.1425C18.989 21.1891 18.1182 22 17.0689 22H6.93112C5.88184 22 5.01096 21.1891 4.9362 20.1425L4.06888 8H3C2.44772 8 2 7.55228 2 7C2 6.44772 2.44772 6 3 6H4.99174C4.99795 5.99994 5.00414 5.99994 5.01032 6H7V4ZM9 6H15V4H9V6ZM6.07398 8L6.93112 20H17.0689L17.926 8H6.07398ZM10 10C10.5523 10 11 10.4477 11 11V17C11 17.5523 10.5523 18 10 18C9.44772 18 9 17.5523 9 17V11C9 10.4477 9.44772 10 10 10ZM14 10C14.5523 10 15 10.4477 15 11V17C15 17.5523 14.5523 18 14 18C13.4477 18 13 17.5523 13 17V11C13 10.4477 13.4477 10 14 10Z" fill="#EB5757"></path>
                                            </g>
                                        </svg>
                                    </a>
                                    <form id="deleteUser{{ $user->id }}"
                                          method="POST"
                                          action="{{ route('users.destroy', ['user'=>$user->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>
                    @foreach($user->children as $child)
                        <tr class="table-dd @if(!$child->status)disabled-tr @endif" data-id="table-dd-{{ Str::slug($user->name, '_') }}">
                        <td></td>
                        <td>
                            <div class="d-flex">
                                <span class="child-id">{{ $loop->parent->iteration }}.{{ $loop->iteration }}</span>
                                <div class="first-info d-flex align-items-center">
                                    <span class="status-dot status-success">&bull;</span>
                                    <span>{{ $child->name }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $child->getRole() }}</td>
                        <td>{{ $child->email }}</td>
                        <td></td>
                        <td></td>
                        <td>
                            <div class="car-dd">
                                <div class="car-close-dd"></div>
                                <div class="car-dd-body">
                                    @can('user_update')
                                        <a href="{{ route('users.children.edit', ['user'=>$user->id, 'child'=>$child->id]) }}" class="link">
                                            <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M13.2929 0.292893C13.6834 -0.0976311 14.3166 -0.0976311 14.7071 0.292893L18.7071 4.29289C19.0976 4.68342 19.0976 5.31658 18.7071 5.70711L5.70711 18.7071C5.51957 18.8946 5.26522 19 5 19H1C0.447715 19 0 18.5523 0 18V14C0 13.7348 0.105357 13.4804 0.292893 13.2929L10.2927 3.2931L13.2929 0.292893ZM11 5.41421L2 14.4142V17H4.58579L13.5858 8L11 5.41421ZM15 6.58579L16.5858 5L14 2.41421L12.4142 4L15 6.58579Z" fill="#536E9B"></path>
                                            </svg>
                                        </a>
                                    @endcan
                                    @can(['delete_self', 'user_delete'], $child)
                                        <a href="#" onclick="if( confirm('Delete it?') ) { event.preventDefault();
                                            document.getElementById('deleteChild{{ $child->id }}').submit(); return true }" class="link">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g opacity="0.6">
                                                    <path d="M7 4C7 2.89543 7.89543 2 9 2H15C16.1046 2 17 2.89543 17 4V6H18.9897C18.9959 5.99994 19.0021 5.99994 19.0083 6H21C21.5523 6 22 6.44772 22 7C22 7.55228 21.5523 8 21 8H19.9311L19.0638 20.1425C18.989 21.1891 18.1182 22 17.0689 22H6.93112C5.88184 22 5.01096 21.1891 4.9362 20.1425L4.06888 8H3C2.44772 8 2 7.55228 2 7C2 6.44772 2.44772 6 3 6H4.99174C4.99795 5.99994 5.00414 5.99994 5.01032 6H7V4ZM9 6H15V4H9V6ZM6.07398 8L6.93112 20H17.0689L17.926 8H6.07398ZM10 10C10.5523 10 11 10.4477 11 11V17C11 17.5523 10.5523 18 10 18C9.44772 18 9 17.5523 9 17V11C9 10.4477 9.44772 10 10 10ZM14 10C14.5523 10 15 10.4477 15 11V17C15 17.5523 14.5523 18 14 18C13.4477 18 13 17.5523 13 17V11C13 10.4477 13.4477 10 14 10Z" fill="#EB5757"></path>
                                                </g>
                                            </svg>
                                        </a>
                                        <form id="deleteChild{{ $child->id }}"
                                              method="POST"
                                              action="{{ route('users.children.destroy', ['user'=>$user->id, 'child'=>$child->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
    </div>





{{--    <section class="setting">
        <div class="wrapper">
            <h2 class="setting__title">{{ $title }}</h2>
            <div class="setting__bar s-between">
                <input type="text" class="input setting__search" placeholder="Поиск по столбцам">

                @can('issetPartnerOperator', Auth::user())
                    <a class="btn blue-btn" href="{{ route('users.create') }}" >{{ __('Create') }}</a>
                @endcan

            </div>
            <div class="table-response">
                <table class="setting__table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">@lang('Name')</th>
                            <th scope="col">@lang('E-mail')</th>
                            <th scope="col">@lang('Role')</th>
                            @hasanyrole('SuperAdmin')
                            <th scope="col">@lang('Legal entities')</th>
                            @endhasanyrole
                            <th scope="col">@lang('Parking lots')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>
                                <a href="{{ route('users.show', ['user'=>$user->id]) }}">{{ $user->name }}</a>
                                @isset($user->children_count)

                                    @if ($user->children_count)
                                        <a href="{{ route('users.children.index', ['user'=>$user->id]) }}" class="badge badge-primary">{{ $user->children_count }}</a>
                                    @else
                                        <a href="{{ route('users.children.index', ['user'=>$user->id]) }}" class="badge badge-secondary">{{ $user->children_count }}</a>
                                    @endif
                                @endisset
                            </td>
                            <td>{{ $user->email }}</td>
                            @hasanyrole('SuperAdmin|Admin|Manager')
                            <td>{{ $user->getRole() }}</td>
                            @endhasanyrole
                            <td>
                                @if($user->managerParkings->isNotEmpty())
                                    <a href="{{ route('parkings.edit', ['parking' => $user->managerParkings->pluck('id')->first()]) }}">
                                        {{ $user->managerParkings->pluck('title')->first() }}
                                    </a>
                                    @if($user->managerParkings->count() > 1)
                                        <br>
                                        <a href="{{ route('user.parking.all', ['user'=>$user->id]) }}">{{ __('show all') }}</a>
                                    @endif
                                @else
                                    <p>{{ __('No parking lots') }}</p>
                                @endif
                            </td>
                            @hasanyrole('SuperAdmin')
                            <td>
                                @if ($user->legals->isNotEmpty())
                                    <a href="{{ route('legals.view', ['user'=>$user->id, 'legal'=>$user->legals->pluck('id')->first()]) }}">
                                        {{ $user->legals->pluck('name')->first() }}
                                    </a>
                                    @if($user->legals->count() > 1)
                                        <br>
                                        <a href="{{ route('legals.all', ['user'=>$user->id]) }}">{{ __('show all') }}</a>
                                    @endif
                                @else
                                    <p>{{ __('No Legal entity') }}</p>
                                @endif
                            </td>
                            @endhasanyrole
                            <td>@if($user->status)
                                    <span class="status-td statusgreen">{{ __('Active') }}</span>
                                @else
                                    <span class="status-td statuspink">{{ __('Not active') }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="btn-group">
                                    @can('user_update')
                                    <a class="tbale-btn" href="{{ route('users.edit', ['user'=>$user->id]) }}">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </a>
                                    @endcan

                                    @can(['delete_self', 'user_delete'], $user)
                                    <a class="tbale-btn" onclick="if( confirm('Delete it?') ) { event.preventDefault(); document.getElementById('deleteUser{{ $user->id }}').submit(); return true }">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </a>
                                    @endcan

                                </div>

                                @can(['delete_self', 'user_delete'], $user)
                                <form id="deleteUser{{ $user->id }}"
                                      method="POST"
                                      action="{{ route('users.destroy', ['user'=>$user->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endcan

                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                </table>
            </div>
        </div>
    </section>--}}
@endsection
