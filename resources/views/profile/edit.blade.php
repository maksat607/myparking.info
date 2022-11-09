@extends('layouts.app')

@section('content')
    <section class="profile">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-6">
                    <h3>{{ $title }}</h3>
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <span class="input-title">{{ __('Name') }}</span>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                   name="name"
                                   value="@if (isset($user)) {{ $user->name }}@else{{ old('name') }} @endif" required>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <span class="input-title">{{ __('E-Mail Address') }}</span>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ $user->email }}" required disabled>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="field-style">Тел</label>

                            <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror"
                                   name="phone"
                                   value="@if(isset($user)){{ $user->phone }}@else{{ old('phone') }}@endif"
                                   required placeholder="Не указан">

                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>
                        <div class="form-group">
                            <span class="input-title">{{ __('Password') }}</span>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password"
                                   @if (!isset($user)) {{ 'required' }} @endif autocomplete="off">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <span class="input-title">{{ __('Confirm Password') }}</span>
                            <input id="password-confirm" type="password" class="form-control"
                                   name="password_confirmation"
                                   @if (!isset($user)) {{ 'required' }} @endif autocomplete="off">
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                            @hasanyrole('Partner')
                            <label class="switch-radio-wrap mt-11px">
                                <input type="checkbox" name="moderation"
                                       @if($user->partner->moderation==0) value="0" @else value="1" checked
                                       @endif @if(isset($partner)&&$disabled) onclick="return false;" @endif>
                                <span class="switcher-radio"></span>
                                <span>{{__('Moderation')}}</span>
                            </label>
                            @endrole
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>

@endsection
