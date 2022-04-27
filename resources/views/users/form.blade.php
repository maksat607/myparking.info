<form method="POST" action="@if(isset($user)) {{ route('users.update', ['user'=>$user->id]) }} @else {{ route('users.store') }} @endif">
    @csrf
    @if(isset($user)) @method('PUT') @endif

    <div class="container page-head-wrap">
        <div class="page-head">
            <div class="page-head__top d-flex align-items-center">
                <h1>{{ $title }}</h1>
                <div class="ml-auto d-flex">
                    <button type="submit" class="btn btn-white">
                        @if(isset($user)) {{ __('Update') }} @else {{ __('Create') }} @endif
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
                            О пользователе
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="field-style">
                                    <span>Имя</span>
                                    <input id="name" type="text" class="@error('name') is-invalid @enderror" name="name"
                                           value="@if(isset($user)){{ $user->name }}@else{{ old('name') }}@endif"
                                           required placeholder="Не указан">

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="switch-radio-wrap mt-11px">
                                    <input type="checkbox" name="status" value="1"
                                    @if(isset($user) && !$user->status){{ '' }}@else {{ 'checked' }}@endif>
                                    <span class="switcher-radio"></span>
                                    <span>Активен</span>
                                </label>
                            </div>
                            <div class="col-6 mt-3">
                                <label class="field-style">
                                    <span>E-Mail</span>
                                    <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email"
                                           value="@if(isset($user)){{ $user->email }}@else{{ old('email') }}@endif"
                                           required placeholder="Не указан">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </label>
                            </div>
                            <div class="col-6 mt-3">
                                <label class="field-style">
                                    <span>Тел</span>
                                    <input id="phone" type="text" class="@error('phone') is-invalid @enderror" name="phone"
                                           value="@if(isset($user)){{ $user->phone }}@else{{ old('phone') }}@endif"
                                           required placeholder="Не указан">

                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </label>
                            </div>
                            <div class="col-6 mt-3">
                                @hasanyrole('SuperAdmin|Admin|Manager')
                                <label class="field-style">
                                    <span>Роль</span>
                                    <select class="page-select @error('role') is-invalid @enderror" name="role" id="role" required>
                                        <option selected hidden value="">{{ __('Select a role.') }}</option>
                                        @foreach($roles as $role)
                                            @if(isset($user) && $user->hasRole($role))
                                                <option selected value="{{ $role }}">{{ $role }}</option>
                                            @else
                                                <option value="{{ $role }}">{{ $role }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </label>
                                @endhasanyrole
                            </div>
                        </div>
                    </div>

                    <div class="inner-page__item">
                        <div class="inner-item-title">
                            Доступы
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="field-style">
                                    <span>Пароль*</span>
                                    <sapn class="password">
                                        <input id="password" type="password" class="@error('password') is-invalid @enderror"
                                               name="password" @if(!isset($user)) {{ 'required' }} @endif placeholder="Не указан" autocomplete="off">
                                        <div class="password-control"></div>

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </sapn>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="field-style">
                                    <span>Подтверждение пароля*</span>
                                    <sapn class="password">
                                        <input id="password-confirm" type="password"
                                               name="password_confirmation" @if(!isset($user)) {{ 'required' }} @endif
                                               autocomplete="off" placeholder="Не указан">
                                        <div class="password-control"></div>
                                    </sapn>
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

{{--    <div class="form-group row">
        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

        <div class="col-md-6">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                   value="@if(isset($user)){{ $user->name }}@else{{ old('name') }}@endif"
                   required autofocus>

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

        <div class="col-md-6">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                   value="@if(isset($user)){{ $user->email }}@else{{ old('email') }}@endif"
                   required >

            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

        <div class="col-md-6">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" @if(!isset($user)) {{ 'required' }} @endif autocomplete="off">

            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

        <div class="col-md-6">
            <input id="password-confirm" type="password" class="form-control"
                   name="password_confirmation" @if(!isset($user)) {{ 'required' }} @endif autocomplete="off">
        </div>
    </div>

    @hasanyrole('SuperAdmin|Admin|Manager')
    <div class="form-group row">
        <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Role') }}</label>

        <div class="col-md-6">
            <select class="custom-select @error('role') is-invalid @enderror" name="role" id="role" required>
                <option selected hidden value="">{{ __('Select a role.') }}</option>
                @foreach($roles as $role)
                    @if(isset($user) && $user->hasRole($role))
                        <option selected value="{{ $role }}">{{ $role }}</option>
                    @else
                        <option value="{{ $role }}">{{ $role }}</option>
                    @endif
                @endforeach
            </select>
            @error('role')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    @endhasanyrole

    <div class="form-group row">
        <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>

        <div class="col-md-6">
            <select class="custom-select @error('status') is-invalid @enderror" name="status" id="status" required>
                <option @if(isset($user) && $user->status == 1) selected @endif  value="1">{{ __('Active') }}</option>
                <option @if(isset($user) && $user->status == 0) selected @endif value="0">{{ __('Not active') }}</option>
            </select>
            @error('status')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                @if(isset($user)) {{ __('Update') }} @else {{ __('Create') }} @endif
            </button>
        </div>
    </div>--}}
</form>
