<form method="POST" action="@if(isset($parking)) {{ route('parkings.update', ['parking'=>$parking->id]) }} @else {{ route('parkings.store') }} @endif">
    @csrf
    @if(isset($parking)) @method('PUT') @endif

    <div class="container page-head-wrap">
        <div class="page-head">
            <div class="page-head__top d-flex align-items-center">
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
                <div class="col-md-12">
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



                </div>

            </div>
        </div>
    </div>

</form>
<div class="container">
    <form id="appFilter" action="{{ url()->current() }}" method="GET" class="filter d-flex align-items-center">
    <label class="field-style {{ session('PartnerHide') }}">
        <span>Партнёр</span>
        <select name="partner_id" class="page-select">
            <option selected value="0">Базовые цены</option>
            @foreach(auth()->user()->adminPartners as $partner)
                @if(request()->get('partner_id') == $partner->id)
                    <option selected value="{{ $partner->id }}">{{ $partner->shortname }}</option>
                @else
                    <option value="{{ $partner->id }}">{{ $partner->shortname }}</option>
                @endif
            @endforeach
        </select>
    </label>
    </form>
</div>


        @include('pricings.pricing')


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

