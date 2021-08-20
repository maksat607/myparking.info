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
                <option selected hidden value="">{{ __('Select a Partner type') }}</option>
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

    <div class="form-group row">
        <h4>{{ __('Price') }}</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>{{ 'Car Type' }}</th>
                    <th>{{ 'Regular Price'}}</th>
                    <th>{{ 'Discount Price' }}</th>
                    <th>{{ 'Free Days' }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($pricings as $price)
                <tr>
                    <td>
                        {{ $price->car_type_name }}
                        <input type="hidden" name="pricings[{{$price->car_type_id}}][car_type_id]"
                               value="@if(!empty($price->car_type_id)){{ $price->car_type_id }}@endif" >
                    </td>
                    <td>
                        <input type="number" min="0" class="form-control @error($errors->has($price->car_type_id.'.regular_price')) is-invalid @enderror" name="pricings[{{$price->car_type_id}}][regular_price]"
                               value="@if(isset($price->regular_price) && isset($partner)){{ $price->regular_price }}@elseif(!is_null(old('pricings.'.$price->car_type_id.'.regular_price'))){{ old('pricings.'.$price->car_type_id.'.regular_price') }}@else{{ 500 }}@endif" >
                    </td>
                    <td>
                        <input type="number" min="0" class="form-control @error($errors->has($price->car_type_id.'.discount_price')) is-invalid @enderror" name="pricings[{{$price->car_type_id}}][discount_price]"
                               value="@if(isset($price->discount_price) && isset($partner)){{ $price->discount_price }}@elseif(!is_null(old('pricings.'.$price->car_type_id.'.discount_price'))){{ old('pricings.'.$price->car_type_id.'.discount_price') }}@else{{ 500 }}@endif" >
                    </td>
                    <td>
                        <input type="number" min="0" class="form-control @error($errors->has($price->car_type_id.'.free_days')) is-invalid @enderror" name="pricings[{{$price->car_type_id}}][free_days]"
                               value="@if(isset($price->free_days) && isset($partner)){{ $price->free_days }}@elseif(!is_null(old('pricings.'.$price->car_type_id.'.free_days'))){{ old('pricings.'.$price->car_type_id.'.free_days') }}@else{{ 0 }}@endif" >
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

    <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                @if(isset($partner)) {{ __('Update') }} @else {{ __('Create') }} @endif
            </button>
        </div>
    </div>
</form>
