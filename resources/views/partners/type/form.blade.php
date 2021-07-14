<form method="POST" action="@if(isset($partner_type)) {{ route('partner-types.update', ['partner_type'=>$partner_type->id]) }} @else {{ route('partner-types.store') }} @endif">
    @csrf
    @if(isset($partner_type)) @method('PUT') @endif
    <div class="form-group row">
        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

        <div class="col-md-6">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                   value="@if(isset($partner_type)){{ $partner_type->name }}@else{{ old('name') }}@endif"
                   required autofocus>

            @error('name')
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
                   value="@if(isset($partner_type)){{ $partner_type->rank }}@elseif(!is_null(old('rank'))){{ old('rank') }}@else{{ 0 }}@endif" >

            @error('rank')
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
                <option @if(isset($partner_type) && $partner_type->status == 1) selected @endif  value="1">{{ __('Active') }}</option>
                <option @if(isset($partner_type) && $partner_type->status == 0) selected @endif value="0">{{ __('Not active') }}</option>
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
                @if(isset($partner_type)) {{ __('Update') }} @else {{ __('Create') }} @endif
            </button>
        </div>
    </div>
</form>
