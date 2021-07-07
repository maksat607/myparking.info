@extends('layouts.app')

@section('content')
    <section class="setting">
        <div class="wrapper">
            <h2 class="setting__title">{{ $title }}</h2>
            <div class="setting__bar s-between">
                <input type="text" class="input setting__search" placeholder="Поиск по столбцам">
                <a class="btn blue-btn"
                    onclick="event.preventDefault(); document.getElementById('savePermissions').submit(); return true">
                    {{ __('Save') }}
                </a>
            </div>
            <div class="table-response">
                <form action="{{ route('permissions.sync') }}" method="POST" id="savePermissions">
                    @csrf
                    <table class="setting__table">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Permissions') }}</th>
                                @if (!empty($roles))
                                    @foreach ($roles as $role)
                                        <th scope="col">{{ $role->name }}</th>
                                    @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($permissions))
                                @foreach($permissions as $permission)
                                    <tr>
                                        <th scope="col">{{ $permission }}</th>
                                        @if(!empty($roles))
                                            @foreach($roles as $role)
                                                <td scope="col">
                                                    @if($role->hasPermissionTo($permission))
                                                        <input name="{{ $role->name }}[]"
                                                               checked
                                                               type="checkbox" value="{{ $permission }}">
                                                    @else
                                                        <input name="{{ $role->name }}[]"
                                                               type="checkbox" value="{{ $permission }}">
                                                    @endif
                                                </td>
                                            @endforeach
                                        @endif
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </section>
    {{-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ $title }}
                        <div class="text-right my-2">
                            <a class="btn btn-primary" onclick="event.preventDefault();
                                                     document.getElementById('savePermissions').submit(); return true">
                                {{ __('Save') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                            <form action="{{ route('permissions.sync') }}" method="POST" id="savePermissions">
                                @csrf

                                <table class="table">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">{{ __('Permissions') }}</th>
                                        @if (!empty($roles))
                                            @foreach ($roles as $role)
                                                <th scope="col">{{ $role->name }}</th>
                                            @endforeach
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($permissions))
                                            @foreach ($permissions as $permission)
                                                <tr>
                                                    <th scope="col">{{ $permission }}</th>
                                                    @if (!empty($roles))
                                                        @foreach ($roles as $role)
                                                            <td scope="col">
                                                                @if ($role->hasPermissionTo($permission))
                                                                    <input name="{{ $role->name }}[]"
                                                                           checked
                                                                           type="checkbox" value="{{ $permission }}">
                                                                @else
                                                                    <input name="{{ $role->name }}[]"
                                                                           type="checkbox" value="{{ $permission }}">
                                                                @endif
                                                            </td>
                                                        @endforeach
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>

                            </form>

                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
