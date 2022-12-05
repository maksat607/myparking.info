@if(isset($parking))
<div class="container">
    <div class="inner-page">
        <form method="POST" action="{{route('parking.prices.store',[$parking->id,'partner_id'=>request()->get('partner_id')])}}">
            @csrf

            <div class="container page-head-wrap">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="page-head">

                    <div class="page-head__top ">
                        <div class="d-flex align-items-center justify-content-between">
                            <h1 class="text-nowrap">Редактировать цены для стоянки: {{ $parking->title }}</h1>

                            <div class="ml-auto d-flex ">
                                <button class="btn btn-white"
                                        type="submit">Сохранить</button>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-group row p-5">
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

                    @foreach($prices as $price)
                        <tr>
                            <input type="hidden" name="pricings[{{$price->car_type_id}}][parking_id]"
                                   value="{{ $parking->id }}">
                            <input type="hidden" name="pricings[{{$price->car_type_id}}][partner_id]"
                                   value="@if(request()->has('partner_id')) {{ request()->partner_id }} @else 0 @endif">
                            <td>
                                {{ $price->carType->name }}
                                <input type="hidden" name="pricings[{{$price->car_type_id}}][car_type_id]"
                                       value="@if(!empty($price->car_type_id)){{ $price->car_type_id }}@endif">
                            </td>
                            <td>
                                <input type="number" min="0"
                                       class="form-control @if($errors->has($price->car_type_id.'.regular_price')) is-invalid @endif"
                                       name="pricings[{{$price->car_type_id}}][regular_price]"
                                       value="@if(isset($price->regular_price) ){{ $price->regular_price }}@elseif(!is_null(old('pricings.'.$price->car_type_id.'.regular_price'))){{ old('pricings.'.$price->car_type_id.'.regular_price') }}@else{{ 500 }}@endif">
                            </td>
                            <td>
                                <input type="number" min="0"
                                       class="form-control @if($errors->has($price->car_type_id.'.discount_price')) is-invalid @endif"
                                       name="pricings[{{$price->car_type_id}}][discount_price]"
                                       value="@if(isset($price->discount_price) ){{ $price->discount_price }}@elseif(!is_null(old('pricings.'.$price->car_type_id.'.discount_price'))){{ old('pricings.'.$price->car_type_id.'.discount_price') }}@else{{ 500 }}@endif">
                            </td>
                            <td>
                                <input type="number" min="0"
                                       class="form-control @if($errors->has($price->car_type_id.'.free_days')) is-invalid @endif"
                                       name="pricings[{{$price->car_type_id}}][free_days]"
                                       value="@if(isset($price->free_days) ){{ $price->free_days }}@elseif(!is_null(old('pricings.'.$price->car_type_id.'.free_days'))){{ old('pricings.'.$price->car_type_id.'.free_days') }}@else{{ 0 }}@endif">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

        </form>
    </div>
</div>
@endif
