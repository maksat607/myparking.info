@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ $title }}
                        <div class="text-right my-2">
                            <a class="btn btn-primary" href="{{ route('users.children.create', ['user'=>$user->id]) }}" >{{ __('Add') }}</a>
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
                                <th scope="col">@lang('Status')</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($children as $child)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        <a href="{{ route('users.children.show', ['user'=>$user->id, 'child'=>$child->id]) }}">{{ $child->name }}</a>
                                    </td>
                                    <td>{{ $child->email }}</td>
                                    <td>{{ $child->getRole() }}</td>
                                    <td>@if($child->status)
                                            <span class="badge badge-success">{{ 'Включен' }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ 'Отключен' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            @can('user_update')
                                                <a class="btn btn-primary" href="{{ route('users.children.edit', ['user'=>$user->id, 'child'=>$child->id]) }}">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a>
                                            @endcan

                                            @can(['delete_self', 'user_delete'], $child)
                                                <a class="btn btn-danger" onclick="if( confirm('Delete it?') ) { event.preventDefault();
                                                     document.getElementById('deleteChild{{ $child->id }}').submit(); return true }">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </a>
                                            @endcan

                                        </div>

                                        @can(['delete_self', 'user_delete'], $child)
                                            <form id="deleteChild{{ $child->id }}"
                                                  method="POST"
                                                  action="{{ route('users.children.destroy', ['user'=>$user->id, 'child'=>$child->id]) }}">
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
