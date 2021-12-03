@extends('layouts.app')

@section('content')
    <section class="profile">
        <div class="profile__wrap">
            <h3>{{ $title }}</h3>
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="d-flex">
                    <div class="item50">
                        <span class="input-title">{{ __('Name') }}</span>
                        <input id="name" type="text" class="input profile__input @error('name') is-invalid @enderror" name="name"
                            value="@if (isset($user)) {{ $user->name }}@else{{ old('name') }} @endif" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="item50">
                        <span class="input-title">{{ __('E-Mail Address') }}</span>
                        <input id="email" type="email" class="input profile__input @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required disabled>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="item50">
                        <span class="input-title">{{ __('Password') }}</span>
                        <input id="password" type="password" class="input profile__input @error('password') is-invalid @enderror" name="password" @if (!isset($user)) {{ 'required' }} @endif autocomplete="off">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                    </div>

                    <div class="item50">
                        <span class="input-title">{{ __('Confirm Password') }}</span>
                        <input id="password-confirm" type="password" class="input profile__input"
                        name="password_confirmation" @if (!isset($user)) {{ 'required' }} @endif autocomplete="off">
                    </div>
                </div>
                <button  type="submit" class="btn blue-btn w-auto-btn profile-btn">{{ __('Update') }}</button>
            </form>
        </div>
    </section>
    {{-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $title }}</div>

                    <div class="card-body">

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="@if (isset($user)) {{ $user->name }}@else{{ old('name') }} @endif" required>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ $user->email }}" required disabled>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password" @if (!isset($user)) {{ 'required' }} @endif autocomplete="off">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" @if (!isset($user)) {{ 'required' }} @endif autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Update') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
