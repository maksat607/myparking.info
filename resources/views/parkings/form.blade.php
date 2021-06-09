<form method="POST" action="@if(isset($parking)) {{ route('parkings.update', ['parking'=>$parking->id]) }} @else {{ route('parkings.store') }} @endif">
    @csrf
    @if(isset($parking)) @method('PUT') @endif
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

    <div class="form-group row">
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
    </div>

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
        <label for="user" class="col-md-4 col-form-label text-md-right">{{ __('User') }}</label>

        <div class="col-md-6">
            <select class="custom-select @error('user') is-invalid @enderror" name="user" id="user" required>
                <option value="{{ $user->id }}">{{ $user->name }}</option>
                @foreach($children as $child)
                    <option
                        value="{{ $child->id }}"
                        @if(isset($parking) && $child->id === $parking->user_id) selected @endif
                    >{{ $child->name }}</option>
                @endforeach
            </select>
            @error('user')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    @endhasanyrole

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
    </div>--}}

    <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                @if(isset($parking)) {{ __('Update') }} @else {{ __('Create') }} @endif
            </button>
        </div>
    </div>
</form>
