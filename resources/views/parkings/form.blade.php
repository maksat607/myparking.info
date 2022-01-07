<form method="POST" action="@if(isset($parking)) {{ route('parkings.update', ['parking'=>$parking->id]) }} @else {{ route('parkings.store') }} @endif">
    @csrf
    @if(isset($parking)) @method('PUT') @endif

    <div class="container page-head-wrap">
        <div class="page-head">
            <div class="page-head__top d-flex align-items-center">
                <a href="#" class="page-head__cancel">Отменить</a>
                <h1>{{ $title }}</h1>
                <div class="ml-auto d-flex">
                    <button class="btn btn-white">
                        @if(isset($parking)) {{ __('Update') }} @else {{ __('Create') }} @endif
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="inner-page">
            <div class="row no-gutters">
                <div class="col-md-8">
                    <div class="inner-page__item">
                        <div class="inner-item-title">
                            О стоянке
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="field-style">
                                    <span>Название</span>
                                    <input type="text"
                                       class="@error('title') is-invalid @enderror" name="title"
                                       value="@if(isset($parking)){{ $parking->title }}@else{{ old('title') }}@endif"
                                       required placeholder="Не указан">

                                    @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="switch-radio-wrap mt-11px">
                                    <input type="checkbox" name="status" value="1"
                                           @if(isset($parking) && !$parking->status){{ '' }}@else {{ 'checked' }}@endif>
                                    <span class="switcher-radio"></span>
                                    <span>Активен</span>
                                </label>
                            </div>
                            <div class="col-6 mt-3">
                                <label class="field-style">
                                    <span>Регион</span>
                                    <input type="text"
                                       class="form-control @error('code') is-invalid @enderror" name="code"
                                       value="@if(isset($parking)){{ $parking->code }}@else{{ old('code') }}@endif"
                                       placeholder="Не указан">

                                    @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </label>
                            </div>
                            <div class="col-6 mt-3">
                                <label class="field-style">
                                    <span>Адрес</span>
                                    <input type="text"
                                       class="form-control @error('address') is-invalid @enderror" name="address"
                                       value="@if(isset($parking)){{ $parking->address }}@else{{ old('address') }}@endif"
                                       placeholder="Не указан">

                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mt-3">
                                <label for="legals" class="field-style">
                                    <span>{{ __('Legal entities') }}</span>
                                    <select class="page-select multiple @error('legals') is-invalid @enderror"
                                            name="legals[]" id="legals" multiple required>
                                        @foreach($legals as $legal)
                                            <option
                                                value="{{ $legal->id }}"
                                                @if(isset($parking) && $parking->legals->contains('id', $legal->id)) selected @endif
                                            >{{ $legal->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('legals')
                                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                    @enderror
                                </label>

                            </div>
                            @hasanyrole('SuperAdmin|Admin')
                            <div class="col-6 mt-3">
                                <label for="user" class="field-style">
                                    <span>{{ __('Users') }}</span>
                                    <select class="page-select multiple @error('users') is-invalid @enderror"
                                            name="users[]" id="users" multiple required>
                                        @foreach($users as $user)
                                            <option
                                                value="{{ $user->id }}"
                                                @if(isset($parking) && $parking->managers->contains('id', $user->id)) selected @endif
                                            >{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('users')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </label>
                            </div>
                            @endhasanyrole
                        </div>
                    </div>



                    <div class="inner-page__item">
                        <div class="inner-item-title">
                            Связи <span style="color: darkred">новый блок</span>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <fieldset class="fieldset">
                                    <legend class="legend">Связи</legend>
                                    <div class="d-flex">
                                        <div class="type-card parts-list">
                                            <div class="nav condition-nav" id="condition" role="tablist" >
                                                <a class="block-nav__item active" href="#tab-1" data-toggle="tab">Юр. лица</a>
                                                <a class="block-nav__item " href="#tab-2" data-toggle="tab">Пользователи</a>
                                            </div>
                                        </div>
                                        <div class="type-card-info tab-content">
                                            <div class="row no-gutters tab-pane fade show active" id="tab-1">
                                                <label class="field-style">
                                                    <span>Поиск</span>
                                                    <input type="text" placeholder="По названию">
                                                </label>
                                                <ul class="list">
                                                    <li class="list__item">
                                                        <label>
                                                            <input type="checkbox" class="list-checkbox">
                                                            <span class="list-checked"></span>
                                                            ПАО СберБанкСтрах
                                                        </label>
                                                    </li>
                                                    <li class="list__item">
                                                        <label>
                                                            <input type="checkbox" class="list-checkbox">
                                                            <span class="list-checked"></span>
                                                            А-Приори
                                                        </label>
                                                    </li>
                                                    <li class="list__item">
                                                        <label>
                                                            <input type="checkbox" class="list-checkbox" checked>
                                                            <span class="list-checked"></span>
                                                            РосСтрах Групп
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="row no-gutters tab-pane fade" id="tab-2">
                                                <label class="field-style">
                                                    <span>Поиск</span>
                                                    <input type="text" placeholder="По названию">
                                                </label>
                                                <ul class="list">
                                                    <li class="list__item">
                                                        <label>
                                                            <input type="checkbox" class="list-checkbox">
                                                            <span class="list-checked"></span>
                                                            ПАО СберБанкСтрах
                                                        </label>
                                                    </li>
                                                    <li class="list__item">
                                                        <label>
                                                            <input type="checkbox" class="list-checkbox">
                                                            <span class="list-checked"></span>
                                                            А-Приори
                                                        </label>
                                                    </li>
                                                    <li class="list__item">
                                                        <label>
                                                            <input type="checkbox" class="list-checkbox" checked>
                                                            <span class="list-checked"></span>
                                                            РосСтрах Групп
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="sidebar">
                        <div class="sidebar__title">
                            Чек-лист оформления
                        </div>
                        <div class="sidebar__item">
                            <div class="sidebar-item-title">
                                О стоянке
                            </div>
                            <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                <span class="sidebar__check-name">Название</span>
                                <span class="sidebar__icon"></span>
                            </div>
                            <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                <span class="sidebar__check-name">Регион</span>
                                <span class="sidebar__icon"></span>
                            </div>
                            <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                <span class="sidebar__check-name">Адрес</span>
                                <span class="sidebar__icon"></span>
                            </div>
                        </div>
                        <div class="sidebar__item">
                            <div class="sidebar-item-title">
                                Связи
                            </div>
                            <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                <span class="sidebar__check-name">Юр. лица</span>
                                <span class="sidebar__icon"></span>
                            </div>
                            <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                <span class="sidebar__check-name">Пользователи</span>
                                <span class="sidebar__icon"></span>
                            </div>
                            <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                <span class="sidebar__check-name">Партнёры</span>
                                <span class="sidebar__icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>


{{--
    <div class="form-group row">
        <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>

        <div class="col-md-6">
            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                   value="@if(isset($parking)){{ $parking->title }}@else{{ old('title') }}@endif"
                   required autofocus>

            @error('title')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('Region') }}</label>

        <div class="col-md-6">
            <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code"
                   value="@if(isset($parking)){{ $parking->code }}@else{{ old('code') }}@endif" >

            @error('code')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

        <div class="col-md-6">
            <input id="code" type="text" class="form-control @error('address') is-invalid @enderror" name="address"
                   value="@if(isset($parking)){{ $parking->address }}@else{{ old('address') }}@endif" >

            @error('address')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

--}}
{{--    <div class="form-group row">
        <label for="timezone" class="col-md-4 col-form-label text-md-right">{{ __('TimeZone') }}</label>

        <div class="col-md-6">
            <input id="timezone" type="text" class="form-control @error('timezone') is-invalid @enderror" name="timezone"
                   value="@if(isset($parking)){{ $parking->timezone }}@else{{ old('timezone') }}@endif"
                   placeholder="{{ config('app.timezone') }}">

            @error('timezone')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>--}}{{--


    <div class="form-group row">
        <label for="legals" class="col-md-4 col-form-label text-md-right">{{ __('Legal entities') }}</label>

        <div class="col-md-6">
            <select class="custom-select multiple @error('legals') is-invalid @enderror" name="legals[]" id="legals" multiple required>
                @foreach($legals as $legal)
                    <option
                        value="{{ $legal->id }}"
                        @if(isset($parking) && $parking->legals->contains('id', $legal->id)) selected @endif
                    >{{ $legal->name }}</option>
                @endforeach
            </select>

            @error('legals')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    @hasanyrole('SuperAdmin|Admin')
    <div class="form-group row">
        <label for="user" class="col-md-4 col-form-label text-md-right">{{ __('Users') }}</label>

        <div class="col-md-6">
            <select class="custom-select multiple @error('users') is-invalid @enderror" name="users[]" id="users" multiple required>
                @foreach($users as $user)
                    <option
                        value="{{ $user->id }}"
                        @if(isset($parking) && $parking->managers->contains('id', $user->id)) selected @endif
                    >{{ $user->name }}</option>
                @endforeach
            </select>
            @error('users')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    @endhasanyrole

--}}
{{--    <div class="form-group row">
        <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>

        <div class="col-md-6">
            <select class="custom-select @error('status') is-invalid @enderror" name="status" id="status" required>
                <option @if(isset($parking) && $parking->status == 1) selected @endif  value="1">{{ __('Active') }}</option>
                <option @if(isset($parking) && $parking->status == 0) selected @endif value="0">{{ __('Not active') }}</option>
            </select>
            @error('status')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>--}}{{--


    <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                @if(isset($parking)) {{ __('Update') }} @else {{ __('Create') }} @endif
            </button>
        </div>
    </div>
--}}

