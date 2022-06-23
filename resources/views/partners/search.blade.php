
@extends('layouts.app')

@section('content')

@dump($partner)
    <form method="POST" action="{{ route('partners.store') }}" class="mkmk">
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

                <div class="page-head__top d-flex align-items-center">
                    <h1>{{ $title }}</h1>
                    <div class="ml-auto d-flex">
                        <button class="btn btn-white" type="submit">Добавить</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="inner-page add-partners">

                <div class="inner-page__item">
                    <div class="inner-item-title">
                        Поиск по базе
                    </div>
                    <div class="d-down-field">
                        <span>Поиск</span>
                        @if(auth()->user()->hasRole(['SuperAdmin']))<input type="hidden" id="superadminid" name="superadmin">@endif
                        <label class="field-style">
                            <input type="text" class="inner-page-search" placeholder="Название или юр. данные">
                        </label>
                        <div class="d-dowen-body">
                            <ul class="d-dowen-select" data-url="{{ route('home') }}">
                            </ul>
                            <button   type="button" onclick="location.href='{{ route('addNewPartner') }}'">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.6" d="M8 0C8.55228 0 9 0.447715 9 1V7H15C15.5523 7 16 7.44772 16 8C16 8.55229 15.5523 9 15 9H9V15C9 15.5523 8.55228 16 8 16C7.44772 16 7 15.5523 7 15V9H1C0.447715 9 0 8.55229 0 8C0 7.44772 0.447715 7 1 7H7V1C7 0.447715 7.44772 0 8 0Z" fill="#536E9B"></path>
                                </svg>
                                Добавить своего
                            </button>
                        </div>
                    </div>
                </div>
                @if(isset($personal))

                    <input type="hidden" name="beingAdded" value="frompublic">
                @if(isset($partner))<input type="hidden" name="partner" value="{{$partner}}">@endif
                    <div class="inner-page__item">
                        <div class="inner-item-title">
                            О партнёре
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="field-style @if(isset($disabled)&&$disabled) disabled @endif">
                                    <span>Полное название</span>

                                    <input id="name" type="text" class="form-control" name="name"
                                           value="@if(isset($partner)){{ $partner->name }}@else{{ old('name') }}@endif"
                                           required autofocus placeholder="Не указан"  @if(isset($disabled)&&$disabled) readonly @endif >

                                </label>
                            </div>

                            <div class="col-6">
                                <label class="switch-radio-wrap mt-11px  @if(isset($disabled)&&$disabled) disabled @endif" >
                                    <input type="checkbox" name="status" @if(isset($partner)&&($partner->status==0)) value="0"  @else value="1"  checked @endif @if(isset($partner)) onclick="return false;" @endif>
                                    <span class="switcher-radio"></span>
                                    <span>Активен</span>
                                </label>
                            </div>
                            <div class="col-6 mt-3">
                                <label class="field-style  @if(isset($disabled)&&$disabled) disabled @endif">
                                    <span>Короткое название</span>

                                    <input id="shortname" type="text" class="form-control " name="shortname"
                                           value="@if(isset($partner)){{ $partner->shortname }}@else{{ old('shortname') }}@endif"
                                           required autofocus placeholder="Не указан" @if(isset($disabled)&&$disabled) readonly @endif >


                                </label>
                            </div>

                            <div class="col-6 mt-3">
                                <label class="field-style @if($errors->has('inn')) invalid @endif  @if(isset($disabled)&&$disabled) disabled @endif">
                                    <span>ИНН</span>
                                    <input type="text" name="inn"
                                           value="@if(isset($partner)){{ $partner->inn }}@else{{ old('inn') }}@endif"
                                           autofocus placeholder="Не указан" @if(isset($disabled)&&$disabled) readonly @endif >

                                </label>
                            </div>


                            <div class="col-6 mt-3">
                                <label class="field-style span  @if(isset($disabled)&&$disabled) disabled @endif">
                                    <span>Тип партнёра</span>
                                    @if(!$personal)
                                        <input type="text"
                                               value="@if(isset($partner)){{ $partner->partnerType->name }}@else{{ old('partner_type') }}@endif"
                                               autofocus placeholder="Не указан"  @if(isset($disabled)&&$disabled) readonly @endif>
                                        <input type="hidden" name="partner_type"
                                               value="@if(isset($partner)){{ $partner->partnerType->id }}@else{{ old('partner_type') }}@endif"
                                               autofocus placeholder="Не указан"  @if(isset($disabled)&&$disabled) readonly @endif>
                                    @else
                                    <select name="partner_type" id="partner_id" class="partner_id page-select custom-select @if($errors->has('partner_type')) is-invalid @endif" required  >

                                        <option selected hidden  value="">Выберите тип партнера</option>
                                        @foreach($partner_types as $partner_type)
                                            @if((isset($partner) && ($partner->partnerType->id === $partner_type->id))||(old('partner_type') == $partner_type->id))
                                                <option selected value="{{ $partner_type->id }}">{{ $partner_type->name }}</option>
                                            @else
                                                <option value="{{ $partner_type->id }}">{{ $partner_type->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @endif
                                </label>
                            </div>
                            <div class="col-6 mt-3">
                                <label class="field-style  @if(isset($disabled)&&$disabled) disabled @endif">
                                    <span>КПП</span>
                                    <input id="kpp" type="text" name="kpp"
                                           value="@if(isset($partner)){{ $partner->kpp }}@else{{ old('kpp') }}@endif"
                                           required autofocus placeholder="Не указан" @if(isset($disabled)&&$disabled) readonly @endif >
                                </label>
                            </div>
                            <div class="col-6 mt-3">
                                <label class="field-style span  @if(isset($disabled)&&$disabled) disabled @endif">
                                    <span>База</span>

                                    @if(!$personal)
                                        <input  type="hidden" name="base" value="public" readonly="readonly" >
                                        <input  type="text" value="Общая" readonly="readonly" >
                                    @else
                                        @if(auth()->user()->hasRole(['Admin']))
                                            <input  type="hidden" name="base" value="user" readonly="readonly" >
                                            <input  type="text" value="Пользовательская" readonly="readonly" >
                                        @else
                                            <select name="base"      >
                                                <option value="public" @if(auth()->user()->hasRole(['Admin']))  @endif >Общая</option>
                                                <option value="user">Пользовательская</option>
                                            </select>
                                        @endif
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>



                @if(auth()->user()->hasRole(['Admin'])&& isset($pricings))
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

                            @foreach($pricings as $price)
                                <tr>
                                    <td>
                                        {{ $price->car_type_name }}
                                        <input type="hidden" name="pricings[{{$price->car_type_id}}][car_type_id]"
                                               value="@if(!empty($price->car_type_id)){{ $price->car_type_id }}@endif" >
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control @if($errors->has($price->car_type_id.'.regular_price')) is-invalid @endif" name="pricings[{{$price->car_type_id}}][regular_price]"
                                               value="@if(isset($price->regular_price) && ($personal)){{ $price->regular_price }}@elseif(!is_null(old('pricings.'.$price->car_type_id.'.regular_price'))){{ old('pricings.'.$price->car_type_id.'.regular_price') }}@else{{ 500 }}@endif" >
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control @if($errors->has($price->car_type_id.'.discount_price')) is-invalid @endif" name="pricings[{{$price->car_type_id}}][discount_price]"
                                               value="@if(isset($price->discount_price) && ($personal)){{ $price->discount_price }}@elseif(!is_null(old('pricings.'.$price->car_type_id.'.discount_price'))){{ old('pricings.'.$price->car_type_id.'.discount_price') }}@else{{ 500 }}@endif" >
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control @if($errors->has($price->car_type_id.'.free_days')) is-invalid @endif" name="pricings[{{$price->car_type_id}}][free_days]"
                                               value="@if(isset($price->free_days) && ($personal)){{ $price->free_days }}@elseif(!is_null(old('pricings.'.$price->car_type_id.'.free_days'))){{ old('pricings.'.$price->car_type_id.'.free_days') }}@else{{ 0 }}@endif" >
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                @endif
                @endif

            </div>
        </div>
    </form>
@endsection
