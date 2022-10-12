@extends('layouts.app')

@section('content')

    <form action="{{ route('permissions.sync') }}" method="POST" id="savePermissions">
        @csrf
        <div class="container page-head-wrap">
            <div class="page-head">
                <div class="page-head__top d-flex align-items-center">
                    <h1>{{ $title }}</h1>
                    <label class="field-style blue">
                        <span>Поиск</span>
                        <input type="text" placeholder="Поиск по столбцам">
                    </label>
                    <div class="ml-auto d-flex">
                        <button class="btn btn-white">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="inner-page">
                <table class="table text-center">
                    <thead>
                    <tr>
                        <th class="text-left">Действие</th>
                        @if (!empty($roles))
                            @foreach ($roles as $role)
                                <th><strong>{{ $role->name }}</strong></th>
                            @endforeach
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @dump($permissions)
                    @dump($roles)
                    @if(!empty($permissions))
                        @foreach($permissions as $permission)
                            <tr>
                                <td class="text-left d-block">
                                    <span>{{ __($permission) }}</span>
                                    <small><small>{{ $permission }}</small></small>
                                </td>
                                @if(!empty($roles))
                                    @foreach($roles as $role)
                                        <td>
                                            @if($role->hasPermissionTo($permission))
                                                <label class="checkbox-custom">
                                                    <input name="{{ $role->name }}[]"
                                                           checked
                                                           type="checkbox" value="{{ $permission }}">
                                                    <span></span>
                                                </label>
                                            @else
                                                <label class="checkbox-custom">
                                                    <input name="{{ $role->name }}[]"
                                                           type="checkbox" value="{{ $permission }}">
                                                    <span></span>
                                                </label>
                                            @endif
                                        </td>
                                    @endforeach
                                @endif
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </form>

@endsection
