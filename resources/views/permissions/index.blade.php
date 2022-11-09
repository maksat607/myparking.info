@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-end">

        @if(isset($type))
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#permissionModal"
                    data-whatever="@mdo">Add
            </button>
        @endif
    </div>
    <form action="{{ route('permissions.sync') }}" method="POST" id="savePermissions">
        @csrf
        @if(isset($type))
            <input type="hidden" name="{{ $type }}">
        @endif
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
                    <thead class="permission-head">
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
                    @if(!empty($permissions))
                        @foreach($permissions as $permission)
                            <tr>
                                <td class="text-left d-block">
                                    @if(Lang::hasForLocale($permission->name, 'ru'))
                                        <span>{{ __($permission->name) }}</span>
                                    @else
                                        <span>{{ $permission->ru }}</span>
                                    @endif
                                    <small><small>{{ $permission->name }}</small></small>
                                </td>
                                @if(!empty($roles))
                                    @foreach($roles as $role)
                                        <td>
                                            @if($role->hasPermissionTo($permission->name))
                                                <label class="checkbox-custom">
                                                    <input name="{{ $role->name }}[]"
                                                           checked
                                                           type="checkbox" value="{{ $permission->name }}">
                                                    <span></span>
                                                </label>
                                            @else
                                                <label class="checkbox-custom">
                                                    <input name="{{ $role->name }}[]"
                                                           type="checkbox" value="{{ $permission->name }}">
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

<div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-labelledby="premissionModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form type="Post" action="{{ route('permissions.store') }}">
                @csrf
                <div class="modal-body">

                    <div class="form-group d-flex align-items-center flex-column w-75">
                        <label for="recipient-name" class="col-form-label">Type:</label>
                        <select name="element" class="form-select" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="button">Button</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="radio">Radio</option>
                            <option value="status">DropdownStatus</option>
                            <option value="dropdown">Dropdown</option>
                            <option value="menu">Menus</option>
                            <option value="icons">Icons</option>

                        </select>
                    </div>
                    <div class="form-group d-flex align-items-center flex-column w-75">
                        <label for="message-text" class="col-form-label">code:</label>
                        <input type="text" name="code" class="w-100">
                        <label for="message-text" class="col-form-label">text:</label>
                        <input type="text" name="text" class="w-100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
