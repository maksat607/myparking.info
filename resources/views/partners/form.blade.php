<form method="POST" action="@if(isset($partner)) {{ route('partners.update', ['partner'=>$partner->id]) }} @else {{ route('partners.store') }} @endif">
    @csrf
    @if(isset($partner)) @method('PUT') @endif
    <div class="form-group row">
        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

        <div class="col-md-6">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                   value="@if(isset($partner)){{ $partner->name }}@else{{ old('name') }}@endif"
                   required autofocus>

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

        <div class="col-md-6">
            <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address"
                   value="@if(isset($partner)){{ $partner->address }}@else{{ old('address') }}@endif">

            @error('address')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Phone') }}</label>

        <div class="col-md-6">
            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"
                   value="@if(isset($partner)){{ $partner->phone }}@else{{ old('phone') }}@endif">

            @error('phone')
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
                   value="@if(isset($partner)){{ $partner->email }}@else{{ old('email') }}@endif">

            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="rank" class="col-md-4 col-form-label text-md-right">{{ __('Sorting') }}</label>

        <div class="col-md-6">
            <input id="rank" type="number" min="0" class="form-control @error('rank') is-invalid @enderror" name="rank"
                   value="@if(isset($partner)){{ $partner->rank }}@elseif(!is_null(old('rank'))){{ old('rank') }}@else{{ 0 }}@endif" >

            @error('rank')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="partner_type" class="col-md-4 col-form-label text-md-right">{{ __('Partner types') }}</label>

        <div class="col-md-6">
            <select class="custom-select @error('partner_type') is-invalid @enderror" name="partner_type" id="partner_type" required>
                <option selected hidden value="">{{ __('Select a Partner type.') }}</option>
                @foreach($partner_types as $partner_type)
                    @if(isset($partner) && ($partner->partnerType->id === $partner_type->id))
                        <option selected value="{{ $partner_type->id }}">{{ $partner_type->name }}</option>
                    @else
                        <option value="{{ $partner_type->id }}">{{ $partner_type->name }}</option>
                    @endif
                @endforeach
            </select>
            @error('partner_type')
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
                <option @if(isset($partner) && $partner->status == 1) selected @endif  value="1">{{ __('Active') }}</option>
                <option @if(isset($partner) && $partner->status == 0) selected @endif value="0">{{ __('Not active') }}</option>
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
                @if(isset($partner)) {{ __('Update') }} @else {{ __('Create') }} @endif
            </button>
        </div>
    </div>
</form>
