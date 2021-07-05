<form method="POST"
      action="@if(isset($partner_user)){{ route('partner-users.update',
                        ['partner_user'=>$partner_user->id, 'partner'=>$partner->id]) }}@else{{
                            route('partner-users.store', ['partner'=>$partner->id]) }} @endif">
    @csrf
    @if(isset($partner_user)) @method('PUT') @endif
    <div class="form-group row">
        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

        <div class="col-md-6">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                   value="@if(isset($partner_user)){{ $partner_user->name }}@elseif($partner){{ $partner->name }}@else{{ old('name') }}@endif"
                   required autofocus>

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

        <div class="col-md-6">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                   value="@if(isset($partner_user)){{ $partner_user->email }}@elseif($partner){{ $partner->email }}@else{{ old('email') }}@endif">

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
                   name="password" @if(!isset($partner_user)) {{ 'required' }} @endif autocomplete="off">

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
                   name="password_confirmation" @if(!isset($partner_user)) {{ 'required' }} @endif autocomplete="off">
        </div>
    </div>


    <div class="form-group row">
        <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>

        <div class="col-md-6">
            <select class="custom-select @error('status') is-invalid @enderror" name="status" id="status" required>
                <option @if(isset($partner_user) && $partner_user->status == 1) selected @endif  value="1">{{ __('Active') }}</option>
                <option @if(isset($partner_user) && $partner_user->status == 0) selected @endif value="0">{{ __('Not active') }}</option>
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
                @if(isset($partner_user)) {{ __('Update') }} @else {{ __('Create') }} @endif
            </button>
            @if(isset($partner_user))
            <a class="btn btn-danger" onclick="if( confirm('Delete it?') ) { event.preventDefault();
                document.getElementById('deletePartnerUser').submit(); return true }">
                {{ __('Delete') }}
            </a>
            @endif
        </div>
    </div>
</form>

@if(isset($partner_user))
<form id="deletePartnerUser"
      method="POST"
      action="{{ route('partner-users.destroy', ['partner_user'=>$partner_user->id, 'partner'=>$partner->id]) }}">
    @csrf
    @method('DELETE')
</form>
@endif
