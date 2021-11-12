<form method="POST" action="@if(isset($legal)) {{ route('legals.update', ['legal'=>$legal->id]) }} @else {{ route('legals.store') }} @endif">
    @csrf
    @if(isset($legal)) @method('PUT') @endif
    <div class="form-group row">
        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

        <div class="col-md-6">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                   value="@if(isset($legal)){{ $legal->name }}@else{{ old('name') }}@endif" autofocus required >

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="inn" class="col-md-4 col-form-label text-md-right">{{ __('INN') }}</label>

        <div class="col-md-6">
            <input id="inn" type="text" class="form-control @error('inn') is-invalid @enderror" name="inn"
                   value="@if(isset($legal)){{ $legal->inn }}@else{{ old('inn') }}@endif"
                   required >

            @error('inn')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="reg_number" class="col-md-4 col-form-label text-md-right">{{ __('Reg. Number') }}</label>

        <div class="col-md-6">
            <input id="reg_number" type="text" class="form-control @error('reg_number') is-invalid @enderror" name="reg_number"
                   value="@if(isset($legal)){{ $legal->reg_number }}@else{{ old('reg_number') }}@endif"
                   required >

            @error('reg_number')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>



    <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                @if(isset($legal)) {{ __('Update') }} @else {{ __('Create') }} @endif
            </button>
        </div>
    </div>
</form>
