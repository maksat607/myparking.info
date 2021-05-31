<form method="POST" action="@if(isset($user)) {{ route('users.update', ['user'=>$user->id]) }} @else {{ route('users.store') }} @endif">
    @csrf
    @if(isset($user)) @method('PUT') @endif
    <div class="form-group row">
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
    </div>
</form>
