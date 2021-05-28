@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ $title }}
                        <div class="text-right my-2">
                            <a class="btn btn-primary" href="{{ route('users.create') }}" >{{ __('Create') }}</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('E-mail')</th>
                                <th scope="col">@lang('Role')</th>
                                <th scope="col">@lang('Legal entities')</th>
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
                                        @if($user->children_count)
                                            <a href="{{ route('users.children.index', ['user'=>$user->id]) }}" class="badge badge-primary">{{ $user->children_count }}</a>
                                        @else
                                            <a href="{{ route('users.children.index', ['user'=>$user->id]) }}" class="badge badge-secondary">{{ $user->children_count }}</a>
                                        @endif
                                    @endisset
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->getRole() }}</td>
                                <td>
                                    @if($user->legals->isNotEmpty())
                                    <a href="{{ route('legals.view', ['user'=>$user->id, 'legal'=>$user->legals->pluck('id')->first()]) }}">
                                        {{ $user->legals->pluck('name')->first() }}
                                    </a>
                                    <br>
                                    <a href="{{ route('legals.all', ['user'=>$user->id]) }}">{{ __('show all') }}</a>
                                    @else
                                        <p>{{ __('No Legal entity') }}</p>
                                    @endif
                                </td>
                                <td>@if($user->status)
                                        <span class="badge badge-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('Not active') }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="btn-group">
                                        @can('user_update')
                                        <a class="btn btn-primary" href="{{ route('users.edit', ['user'=>$user->id]) }}">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                        @endcan

                                        @can(['delete_self', 'user_delete'], $user)
                                        <a class="btn btn-danger" onclick="if( confirm('Delete it?') ) { event.preventDefault();
                                                     document.getElementById('deleteUser{{ $user->id }}').submit(); return true }">
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
            </div>
        </div>
    </div>
@endsection
