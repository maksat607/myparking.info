@if($errors->any())

    {{ implode('', $errors->all(':message')) }}
@endif
<form id="appStore" method="POST" action="{{ route('applications.store') }}" enctype="multipart/form-data">
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
                <label class="field-style">
                    <span class="field-style-title">Статус</span>
                    <select class="custom-select" name="app_data[status_id] @error('status_id') invalid @enderror" id="status-selection">
                        @foreach($statuses as $status)
                            @if(old('app_data.status_id') == $status->id)
                                <option value="{{ $status->id }}" selected >{{ $status->name }}</option>
                                @continue
                            @elseif($status->code == 'storage')
                                <option value="{{ $status->id }}" selected >{{ $status->name }}</option>
                                @continue
                            @endif
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </label>
                <button class="btn btn-white mr-2" type="button" id="tabPrev">Назад</button>
                <button class="btn btn-white" type="button" id="tabNext">Далее</button>
                <button class="btn btn-white" id="save">Создать заявку</button>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="inner-page">
        <div class="row no-gutters position-relative">
            <div class="col-md-8 block-nav">
                <div class="nav tabs" id="v-pills-tab" role="tablist" aria-orientation="vertical">
{{--                    <span class="block-nav__item active" id="v-pills-settings-tab" data-id="v-pills-1"--}}

{{--                       role="tab"--}}
{{--                       aria-controls="v-pills-settings" aria-selected="false">Заявка</span>--}}
{{--                    <span class="block-nav__item" id="v-pills-settings-tab" data-id="v-pills-2"--}}
{{--                       role="tab"--}}
{{--                       aria-controls="v-pills-settings" aria-selected="false">Авто</span>--}}
                    <a class="block-nav__item active d-none" id="v-pills-settings-tab" data-id="v-pills-1"
                       href="#"
                       role="tab"
                       aria-controls="v-pills-settings" aria-selected="false">Заявка</a>
                    <a class="block-nav__item d-none" id="v-pills-settings-tab" data-id="v-pills-2" href="#"
                       role="tab"
                       aria-controls="v-pills-settings" aria-selected="false">Авто</a>
                </div>
            </div>
            <div class="tab-content tab-content-main col-md-12">
                <div class="row no-gutters tab-pane fade show active" id="v-pills-1">
                    <div class="col-md-12 main-col">
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Административная информация
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="field-style @error('vin_array') invalid @enderror">

                                        <span>VIN</span>
                                        <input type="text" id="vin"
                                               class="vin"
                                               name="car_data[vin_array]"
                                               value="{{ old('car_data.vin_array') }}"
                                               placeholder="Не указан">

                                    </label>
                                    <label class="switch-radio-wrap mt-2">
                                        <input class="checkbox-unknown cvin" data-for="vin" type="checkbox"  name="car_data[vin_status]" value="1">
                                        <span class="switcher-radio"></span>
                                        <span>неизвестно</span>
                                    </label>
                                </div>

                                <div class="col-6">
                                    <label class="field-style @error('license_plate') invalid @enderror">
                                        <span>Гос. номер</span>
                                        <input type="text" id="license_plate"
                                               class="license_plate"
                                               name="car_data[license_plate]"
                                               value="{{ old('car_data.license_plate') }}"
                                               placeholder="Не указан">
{{--                                        <span class="invalid__item">Неверный формат</span>--}}
                                    </label>
                                    <label class="switch-radio-wrap mt-2">
                                        <input class="checkbox-unknown clicense" type="checkbox" data-for="license_plate" name="car_data[license_plate_status]" value="1">
                                        <span class="switcher-radio"></span>
                                        <span>неизвестно</span>
                                    </label>
                                </div>
                                <div class="col-6 mt-3">
                                    <div id="vinDuplicates" class="conformity">
                                    </div>
                                </div>
                                <div class="col-6 mt-3">
                                    <div id="licensePlateDuplicates" class="conformity">
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <div id="allDuplicates" class="conformity">

                                    </div>
                                </div>
                                <label class="switch-radio-wrap ml-3 repeat-checkbox d-none">
                                    <input class="" type="checkbox" id="repeat-checkbox" data-for="license_plate" name="car_data[returned]" value="1">
                                    <span class="switcher-radio"></span>
                                    <span>повторное размещение</span>
                                </label>
                            </div>
                        </div>
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                О собственнике
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>ФИО собственника</span>
                                        <input type="text" name="app_data[courier_fullname]"
                                               value="{{ old('app_data.courier_fullname') }}"
                                               placeholder="Не указан">
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>Телефон собствениика</span>
                                        <input type="tel" name="app_data[courier_phone]"
                                                    id="tel"
                                               value="{{ old('app_data.courier_phone') }}"
                                               placeholder="+7 (___) ___-__-__">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Системная информация
                            </div>
                            <div class="row">
                                @if(in_array(auth()->user()->getRole(),['Partner','PartnerOperator']))
                                    <input type="hidden" name="app_data[partner_id]" value="{{ auth()->user()->partner->id }}">
                                @endif
                                @if(!in_array(auth()->user()->getRole(),['Partner','PartnerOperator']))
                                <div class="col-6 ">
                                    <label class="field-style @error('partner_id') invalid @enderror">
                                        <span>Партнёр*</span>
                                        <select name="app_data[partner_id]" id="partner_id" class="partner_id page-select">
                                            <option selected hidden disabled value="">{{ __('Select a partner..') }}</option>
                                            @foreach($partners as $partner)

                                                @if($loop->count == 1||$partners->count()==1)
                                                    <option selected value="{{ $partner->id }}">{{ $partner->shortname }}</option>
                                                    @continue
                                                @elseif(old('app_data.partner_id') == $partner->id)
                                                    <option selected value="{{ $partner->id }}">{{ $partner->shortname }}</option>
                                                    @continue
                                                @else
                                                    <option value="{{ $partner->id }}">{{ $partner->shortname }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                                @endif
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>Стоянка*</span>
                                        <select name="app_data[parking_id]" id="parking_id" class="page-select">
                                            <option selected hidden disabled value="">{{ __('Select a parking..') }}</option>
                                            @foreach($parkings as $parking)
                                                @if(old('app_data.parking_id') == $parking->id)
                                                    <option selected value="{{ $parking->id }}">{{ $parking->title }}</option>
                                                    @continue
                                                @else
                                                    <option value="{{ $parking->id }}">{{ $parking->title }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                                <div class="col-12 mt-3">
                                    <label class="field-style w-100">
                                        <span>Номер убытка или лизингового договора*</span>
                                        <input type="text" id="external_id" name="app_data[external_id]" id="external"
                                               value="{{ old('app_data.external_id') }}" placeholder="Не указан">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Дата
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>Дата поставки</span>
                                        <input type="text" id="arriving_at" class="@if(auth()->user()->hasRole(['SuperAdmin','Admin','Manager'])) date-manager @else date @endif" name="app_data[arriving_at]" placeholder="Не указан">
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>Промежуток времени</span>
                                        <select id="arriving_interval" name="app_data[arriving_interval]">
                                            <option selected hidden disabled value="">{{ __('Select a time interval..') }}</option>
                                            <option @if(old('app_data.arriving_interval') == '10:00 - 14:00'){{ 'selected' }}@endif value="10:00 - 14:00">10:00 - 14:00</option>
                                            <option @if(old('app_data.arriving_interval') == '14:00 - 18:00'){{ 'selected' }}@endif value="14:00 - 18:00">14:00 - 18:00</option>
                                        </select>
                                    </label>
                                </div>
{{--                                @hasrole('Admin')--}}
{{--                                <div class="col-6 mt-3">--}}
{{--                                    <label class="field-style">--}}
{{--                                        <span>Дата выдачи</span>--}}
{{--                                        <div class="input-group flatpickr">--}}
{{--                                            <input type="text" id="issued_at" class="date-admin" name="app_data[issued_at]"--}}
{{--                                                   placeholder="Выберите дату.." data-input>--}}
{{--                                            <div class="input-group-append">--}}
{{--                                                <button id="dataClear" class="btn btn-danger" type="button" data-clear>--}}
{{--                                                    <i class="fa fa-times-circle" aria-hidden="true"></i>--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                                <div class="col-6 mt-3">--}}
{{--                                    <label class="field-style">--}}
{{--                                        <span>==Кто выдал</span>--}}
{{--                                        <select name="app_data[issued_by]" id="issued_by" class="issued_by @error('issued_by') is-invalid @enderror">--}}
{{--                                            <option selected hidden value="">{{ __('Select a manager..') }}</option>--}}
{{--                                            @foreach($managers as $manager)--}}
{{--                                                @if(old('app_data.issued_by') == $manager->id)--}}
{{--                                                    <option selected value="{{ $manager->id }}">{{ $manager->name }}</option>--}}
{{--                                                    @continue--}}
{{--                                                @else--}}
{{--                                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>--}}
{{--                                                @endif--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                                --}}{{--<div class="col-12 mt-3">--}}
{{--                                    <label class="field-style">--}}
{{--                                        <span>Статус</span>--}}
{{--                                        <select name="app_data[status_admin]" id="status_admin" class="status_admin @error('status_admin') is-invalid @enderror">--}}
{{--                                            <option selected hidden value="">{{ __('Select a status..') }}</option>--}}
{{--                                            @foreach($statuses as $statuse)--}}
{{--                                                <option value="{{ $statuse->id }}">{{ $statuse->name }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                                @endhasrole--}}
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row no-gutters tab-pane fade" id="v-pills-2">
                    <div class="col-md-12 main-col">
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Марка и модель
                            </div>
                            <div class="row mr-offset-20">
                                <div class="col-12">
                                    <div class="tabform__cartlist w-100 d-flex">
                                        <fieldset class="tabform__cart select first-cart car_type_id fieldset new-style-model" id="types">
                                            <legend class="legend">{{ __('The type of car...') }} <span class="mob-arrow"></span></legend>
                                            <div class="tabform__mob-dd type-card">
                                                <input type="text" placeholder="Поиск" class="select-search">
                                                <ul class="select-list tabform__ul type-list">
                                                    @foreach($carTypes as $car)
                                                        @if ($loop->first)
                                                            <li class="select-item tabform__li active">
                                                                <a href="" data-name-id="car_type_id" data-id="{{ $car->id }}">{{ $car->name }}</a>
                                                            </li>
                                                        @else
                                                            <li class="select-item tabform__li">
                                                                <a href="" data-name-id="car_type_id" data-id="{{ $car->id }}">{{ $car->name }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </fieldset>
                                        <fieldset class="tabform__cart select car_mark_id fieldset new-style-model" id="marks" data-id="selectGroup">
                                            <legend class="legend">{{ __('The brand of the car...') }} <span class="mob-arrow"></span></legend>
                                            <div class="tabform__mob-dd type-card">
                                                <input type="text" placeholder="Поиск" class="select-search">
                                                <ul class="tabform__ul select-list type-list" data-placeholder="Выберите тип авто">
                                                    {{-- <li class="tabform__li"><img src="img/bmw-icon.png"> bmw</li> --}}
                                                </ul>
                                            </div>
                                        </fieldset>
                                        <fieldset class="tabform__cart select car_model_id fieldset new-style-model" id="models" data-id="selectGroup">
                                            <legend class="legend">{{ __('The car model...') }} <span class="mob-arrow"></span></legend>
                                            <div class="tabform__mob-dd type-card">
                                                <input type="text" placeholder="Поиск" class="select-search">
                                                <ul class="select-list tabform__ul type-list" data-placeholder="Выберите марку авто">
                                                    <li class="placeholder statuspink">Выберите марку авто</li>
                                                </ul>
                                            </div>
                                        </fieldset>
                                        <fieldset class="tabform__cart select year fieldset new-style-model" id="years" data-id="selectGroup">
                                            <legend class="legend">{{ __('The year of the car...') }} <span class="mob-arrow"></span></legend>
                                            <div class="tabform__mob-dd type-card">
                                                <input type="text" placeholder="Поиск" class="select-search">
                                                <ul class="select-list tabform__ul type-list" data-placeholder="Выберите модель авто">
                                                    <li class="placeholder statuspink">Выберите модель авто</li>
                                                </ul>
                                            </div>
                                        </fieldset>
                                        <div id="textArea" class="d-none col">
                                            <label for="reg_number" style="padding: 0 15px;">{{ __('Description of auto') }}</label>
                                            <textarea class="form-control mw-100" id="autoDesc"
                                                      rows="4"
                                                      name="car_data[car_title]"
                                                      value="{{ old('car_data.car_title') }}"
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Поколение и модификация
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="tabform__cartlist tabform__cartlist-col-3 d-flex justify-content-between">
                                        <fieldset class="tabform__cart select cart-3 fieldset" id="generations">
                                            <legend class="legend">{{ __('Generation...') }} <span class="mob-arrow"></span></legend>
                                            <div class="tabform__mob-dd">
                                                <input type="text" placeholder="Поиск" class="select-search">
                                                <ul class="select-list tabform__ul type-list" data-placeholder="Выберите поколение авто">
                                                    <li class="placeholder statuspink">Выберите поколение авто</li>
                                                </ul>
                                            </div>
                                        </fieldset>
                                        <fieldset class="tabform__cart select cart-3 fieldset" id="series">
                                            <legend class="legend">{{ __('Series...') }} <span class="mob-arrow"></span></legend>
                                            <div class="tabform__mob-dd">
                                                <input type="text" placeholder="Поиск" class="select-search">
                                                <ul class="select-list tabform__ul type-list" data-placeholder="Выберите кузов авто">
                                                    <li class="placeholder statuspink">Выберите кузов авто</li>
                                                </ul>
                                            </div>
                                        </fieldset>
                                        <fieldset class="tabform__cart select cart-3 fieldset" id="modifications">
                                            <legend class="legend">{{ __('Modifications...') }} <span class="mob-arrow"></span></legend>
                                            <div class="tabform__mob-dd">
                                                <input type="text" placeholder="Поиск" class="select-search">
                                                <ul class="select-list tabform__ul type-list" data-placeholder="Выберите модификацию авто">
                                                    <li class="placeholder statuspink">Выберите модификацию авто</li>
                                                </ul>
                                            </div>
                                        </fieldset>
                                        <fieldset class="tabform__cart select cart-3 fieldset" id="engines">
                                            <legend class="legend">{{ __('Engines...') }} <span class="mob-arrow"></span></legend>
                                            <div class="tabform__mob-dd">
                                                <input type="text" placeholder="Поиск" class="select-search">
                                                <ul class="select-list tabform__ul type-list" data-placeholder="Выберите двигатель авто">
                                                    <li class="placeholder statuspink">Выберите двигатель авто</li>
                                                </ul>
                                            </div>
                                        </fieldset>
                                        <fieldset class="tabform__cart select cart-3 fieldset" id="transmissions">
                                            <legend class="legend">{{ __('Transmission...') }} <span class="mob-arrow"></span></legend>
                                            <div class="tabform__mob-dd">
                                                <input type="text" placeholder="Поиск" class="select-search">
                                                <ul class="select-list tabform__ul type-list" data-placeholder="Выберите КПП авто">
                                                    <li class="placeholder statuspink">Выберите КПП авто</li>
                                                </ul>
                                            </div>
                                        </fieldset>
                                        <fieldset class="tabform__cart select cart-3 fieldset" id="gears">
                                            <legend class="legend">{{ __('Gear...') }} <span class="mob-arrow"></span></legend>
                                            <div class="tabform__mob-dd">
                                                <input type="text" placeholder="Поиск" class="select-search">
                                                <ul class="select-list tabform__ul type-list" data-placeholder="Выберите привод авто">
                                                    <li class="placeholder statuspink">Выберите привод авто</li>
                                                </ul>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Административная информация
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>VIN</span>
                                        <input class="vin" type="text" placeholder="Не указан">
{{--                                        <input class="vin" type="text" placeholder="Не указан" value="XTA210600C0000001">--}}
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>Гос. номер</span>
{{--                                        <input class="license_plate" type="text" placeholder="Не указан" value="А001АА177">--}}
                                        <input class="license_plate" id="plate" type="text" placeholder="Не указан">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Документы
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>СТС</span>
                                        <input type="text" name="car_data[sts]"
                                               value="{{ old('car_data.sts') }}"
                                               id="sts" placeholder="Не указан">
                                    </label>
                                    @if(!auth()->user()->hasRole(['Partner','PartnerOperator','Operator']))
                                    <div class="mt-2">
                                        <label class="switch-radio-wrap">
                                            <input @if(old('car_data.sts_provided') == 1){{ 'checked' }}@endif type="checkbox" name="car_data[sts_provided]" value="1">
                                            <span class="switcher-radio"></span>
                                            <span>Принят на хранение</span>
                                        </label>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>ПТС</span>
                                        <div class="d-flex two-field">
                                            <input id="pts_type_input" name="car_data[pts_]" value="{{ old('car_data.pts') }}" type="text" placeholder="Не указан">
                                            <select id="pts_type" name="car_data[pts_type]" class="page-select">
                                                <option selected hidden disabled value="">{{ __('Select a pts type..') }}</option>
                                                <option @if(old('car_data.pts_type') == 'Электронный') selected @endif value="Электронный">Электронный</option>
                                                <option @if(old('car_data.pts_type') == 'Оригинал') selected @endif value="Оригинал">Оригинал</option>
                                                <option @if(old('car_data.pts_type') == 'Дубликат') selected @endif value="Дубликат">Дубликат</option>
                                            </select>
                                        </div>
                                    </label>
                                    @if(!auth()->user()->hasRole(['Partner','PartnerOperator','Operator']))
                                    <div class="mt-2">
                                        <label class="switch-radio-wrap">
                                            <input @if(old('car_data.pts_provided') == 1){{ 'checked' }}@endif type="checkbox" name="car_data[pts_provided]" value="1">
                                            <span class="switcher-radio"></span>
                                            <span>Принят на хранение</span>
                                        </label>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Информация об автомобиле
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="inner-page__item-title">Кол-во владельцев</div>
                                    <div class="d-flex radio-check-list">
                                        <div class="mt-2 mb-3">
                                            <label class="switch-radio-wrap">
                                                <input @if(old('car_data.owner_number') == 1){{ 'checked' }}@endif type="radio" name="car_data[owner_number]" value="1">
                                                <span class="switcher-radio"></span>
                                                <span>Первый</span>
                                            </label>
                                        </div>
                                        <div class="mt-2 mb-3">
                                            <label class="switch-radio-wrap">
                                                <input @if(old('car_data.owner_number') == 2){{ 'checked' }}@endif type="radio" name="car_data[owner_number]" value="2">
                                                <span class="switcher-radio"></span>
                                                <span>Второй</span>
                                            </label>
                                        </div>
                                        <div class="mt-2 mb-3">
                                            <label class="switch-radio-wrap">
                                                <input @if(old('car_data.owner_number') == 3){{ 'checked' }}@endif type="radio" name="car_data[owner_number]" value="3">
                                                <span class="switcher-radio"></span>
                                                <span>Третий и более</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="inner-page__item-title">Кол-во ключей</div>
                                    <div class="d-flex radio-check-list">
                                        <div class="mt-2 mb-3">
                                            <label class="switch-radio-wrap">
                                                <input @if(is_null(old('car_data.car_key_quantity')) || old('car_data.car_key_quantity') == '0'){{ 'checked' }}@endif type="radio" name="car_data[car_key_quantity]" value="0">
                                                <span class="switcher-radio"></span>
                                                <span>0</span>
                                            </label>
                                        </div>
                                        <div class="mt-2 mb-3">
                                            <label class="switch-radio-wrap">
                                                <input @if(old('car_data.car_key_quantity') == 1){{ 'checked' }}@endif type="radio" name="car_data[car_key_quantity]" value="1">
                                                <span class="switcher-radio"></span>
                                                <span>1</span>
                                            </label>
                                        </div>
                                        <div class="mt-2 mb-3">
                                            <label class="switch-radio-wrap">
                                                <input @if(old('car_data.car_key_quantity') == 2){{ 'checked' }}@endif type="radio" name="car_data[car_key_quantity]" value="2">
                                                <span class="switcher-radio"></span>
                                                <span>2</span>
                                            </label>
                                        </div>
                                        <div class="mt-2 mb-3">
                                            <label class="switch-radio-wrap">
                                                <input @if(old('car_data.car_key_quantity') == 3){{ 'checked' }}@endif type="radio" name="car_data[car_key_quantity]" value="3">
                                                <span class="switcher-radio"></span>
                                                <span>3</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>Цвет</span>
                                        <select name="car_data[color]" id="color" class="page-select" style="width: 255px">
                                            <option selected hidden disabled value="">{{ __('Select a color..') }}</option>
                                            @foreach($colors as $color)
                                                @if(old('car_data.color') == $color['value'])
                                                    <option selected value="{{ $color['value'] }}">{{ $color['label'] }}</option>
                                                    @continue
                                                @else
                                                    <option value="{{ $color['value'] }}">{{ $color['label'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="field-style mileage">
                                        <span>Пробег</span>
                                        <input type="number" name="car_data[milage]" value="{{ old('car_data.milage') }}" placeholder="Не указан">
                                        <span class="mileage-type">км</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Тех. состояние
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row radio-check-list">
                                        <div class="col-3 mt-2 mb-3">
                                            <label class="switch-radio-wrap bold">
                                                <input type="checkbox" name="car_data[condition_engine][]" value="" class="chech-dd">
                                                <span class="switcher-radio"></span>
                                                <span>Неисправности
                                                        двигателя</span>
                                            </label>
                                            <div class="chech-dd-list">
                                                <label class="switch-radio-wrap d-flex mb-3">
                                                    <input type="checkbox" name="car_data[condition_engine][]" value="Дымность двигателя (густой, белый, сизый, черный)">
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="check-box-text">Дымность двигателя (густой,
                                                            белый, сизый, черный)</span>
                                                </label>
                                                <label class="switch-radio-wrap d-flex mb-3">
                                                    <input type="checkbox" name="car_data[condition_engine][]" value="Повышенный стук и шум при работе двигателя">
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="check-box-text">Повышенный стук и шум при
                                                            работе двигателя</span>
                                                </label>
                                                <label class="switch-radio-wrap d-flex mb-3">
                                                    <input type="checkbox" name="car_data[condition_engine][]" value="Повышенный шум при работе выхлопной системы">
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="check-box-text">Повышенный шум при работе
                                                            выхлопной системы</span>
                                                </label>
                                                <label class="switch-radio-wrap d-flex mb-3">
                                                    <input type="checkbox" name="car_data[condition_engine][]" value="Подтекание при осмотре подкапотного пространства">
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="check-box-text">Подтекание при осмотре
                                                            подкапотного пространства</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-3 mt-2 mb-3">
                                            <label class="switch-radio-wrap bold">
                                                <input type="checkbox" name="car_data[condition_transmission][]" value="" class="chech-dd">
                                                <span class="switcher-radio"></span>
                                                <span>Неисправности КПП</span>
                                            </label>
                                            <div class="chech-dd-list" id="">
                                                <label class="switch-radio-wrap d-flex mb-3">
                                                    <input type="checkbox" name="car_data[condition_transmission][]" value="Рывки и толчки авто при переключении">
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="check-box-text">Рывки и толчки авто при
                                                            переключении</span>
                                                </label>
                                                <label class="switch-radio-wrap d-flex mb-3">
                                                    <input type="checkbox" name="car_data[condition_transmission][]" value="Повышенный шум при переключении">
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="check-box-text">Повышенный шум при
                                                            переключении</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-3 mt-2 mb-3">
                                            <label class="switch-radio-wrap bold">
                                                <input type="checkbox" name="car_data[condition_electric][]" value="" class="chech-dd">
                                                <span class="switcher-radio"></span>
                                                <span>Неисправности
                                                        электрики</span>
                                            </label>
                                            <div class="chech-dd-list" id="">
                                                <label class="switch-radio-wrap d-flex mb-3">
                                                    <input type="checkbox" name="car_data[condition_electric][]" value="Ошибки на панели приборов при заведенном ДВС">
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="check-box-text">Ошибки на панели приборов при
                                                            заведенном ДВС</span>
                                                </label>
                                                <label class="switch-radio-wrap d-flex mb-3">
                                                    <input type="checkbox" name="car_data[condition_electric][]" value="Неправильные команды электроники">
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="check-box-text">Неправильные команды
                                                            электроники</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-3 mt-2 mb-3">
                                            <label class="switch-radio-wrap bold">
                                                <input type="checkbox" name="car_data[condition_gear][]" value="" class="chech-dd">
                                                <span class="switcher-radio"></span>
                                                <span>Неисправности
                                                        ходовой</span>
                                            </label>
                                            <div class="chech-dd-list" id="">
                                                <label class="switch-radio-wrap d-flex mb-3">
                                                    <input type="checkbox" name="car_data[condition_gear][]" value="Посторонний звук со стороны ходовой">
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="check-box-text">Посторонний звук со стороны
                                                            ходовой</span>
                                                </label>
                                                <label class="switch-radio-wrap d-flex mb-3">
                                                    <input type="checkbox" name="car_data[condition_gear][]" value="Посторонние звуки при вращении рулевого колеса">
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="check-box-text">Посторонние звуки при вращении
                                                            рулевого колеса</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    {{--<fieldset class="fieldset">
                                        <legend class="legend">Неисправности</legend>
                                        <div class="d-flex">
                                            <div class="type-card parts-list tab-checkbox" id="tab-checkbox" role="tablist">
                                                <label class="switch-radio-wrap d-flex">
                                                    <input type="checkbox" name="car_data[condition_engine]" value="" checked>
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="part-title"><a data-toggle="tab" href="#tab-tex1">Двигатель</a></span>
                                                    <span class="condition">Исправен</span>
                                                </label>
                                                <label class="switch-radio-wrap d-flex">
                                                    <input type="checkbox" name="car_data[condition_transmission]" value="" checked>
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="part-title"><a data-toggle="tab" href="#tab-tex2">КПП</a></span>
                                                    <span class="condition">Исправен</span>
                                                </label>
                                                <label class="switch-radio-wrap d-flex">
                                                    <input type="checkbox" name="car_data[condition_electric]" value="" checked>
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="part-title"><a data-toggle="tab" href="#tab-tex3">Электрика</a></span>
                                                    <span class="condition">Исправен</span>
                                                </label>
                                                <label class="switch-radio-wrap d-flex">
                                                    <input type="checkbox" name="car_data[condition_gear]" checked value="">
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="part-title"><a data-toggle="tab" href="#tab-tex4">Ходовая</a></span>
                                                    <span class="condition">Исправен</span>
                                                </label>
                                            </div>
                                            <div class="type-card-info tab-content">
                                                <div class="tab-pane fade show active" id="tab-info">
                                                    Неисправностей не обнаружено
                                                </div>
                                                <div class="tab-pane fade" id="tab-tex1">
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox" name="car_data[condition_engine][]" value="Дымность двигателя (густой, белый, сизый, черный)">
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Дымность двигателя (густой, белый, сизый, черный)</span>
                                                    </label>
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox" name="car_data[condition_engine][]" value="Повышенный стук и шум при работе двигателя">
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Повышенный стук и шум при работе двигателя</span>
                                                    </label>
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox" name="car_data[condition_engine][]" value="Повышенный шум при работе выхлопной системы">
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Повышенный шум при работе выхлопной системы</span>
                                                    </label>
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox" name="car_data[condition_engine][]" value="Подтекание при осмотре подкапотного пространства">
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Подтекание при осмотре подкапотного пространства</span>
                                                    </label>
                                                </div>
                                                <div class="tab-pane fade" id="tab-tex2">
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox" name="car_data[condition_transmission][]" value="Рывки и толчки авто при переключении">
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Рывки и толчки авто при переключении</span>
                                                    </label>
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox" name="car_data[condition_transmission][]" value="Повышенный шум при переключении">
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Повышенный шум при переключении</span>
                                                    </label>
                                                </div>
                                                <div class="tab-pane fade" id="tab-tex3">
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox" name="car_data[condition_electric][]" value="Ошибки на панели приборов при заведенном ДВС">
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Ошибки на панели приборов при заведенном ДВС</span>
                                                    </label>
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox" name="car_data[condition_electric][]" value="Неправильные команды электроники">
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Неправильные команды электроники</span>
                                                    </label>
                                                </div>
                                                <div class="tab-pane fade" id="tab-tex4">
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox" name="car_data[condition_gear][]" value="Посторонний звук со стороны ходовой">
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Посторонний звук со стороны ходовой</span>
                                                    </label>
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox" name="car_data[condition_gear][]" value="Посторонние звуки при вращении рулевого колеса">
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Посторонние звуки при вращении рулевого колеса</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>--}}
                                </div>
                            </div>
                            {{--<div class="row mt-5">
                                <div class="col-12">
                                    <fieldset class="fieldset">
                                        <legend class="legend">Повреждения</legend>
                                        <div class="d-flex">
                                            <div class="type-card parts-list">
                                                <div class="nav condition-nav" id="condition" role="tablist"
                                                     aria-orientation="vertical">
                                                    <a class="block-nav__item active" href="#condition-1"
                                                       data-toggle="tab">Кузов</a>
                                                    <a class="block-nav__item" href="#condition-2"
                                                       data-toggle="tab">Салон</a>
                                                </div>
                                            </div>
                                            <div class="type-card-info tab-content">
                                                <div class="row no-gutters tab-pane fade show active"
                                                     id="condition-1">Кузов</div>
                                                <div class="row no-gutters tab-pane fade" id="condition-2">Салон
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>--}}
                        </div>
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Фотографии
                            </div>
                            <div class="page-file-list" id="images">
                                <div class="page-add-file no-ajax upload-file">
                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.6"
                                              d="M20.0013 6.6665C20.9218 6.6665 21.668 7.4127 21.668 8.33317V18.3332H31.668C32.5884 18.3332 33.3346 19.0794 33.3346 19.9998C33.3346 20.9203 32.5884 21.6665 31.668 21.6665H21.668V31.6665C21.668 32.587 20.9218 33.3332 20.0013 33.3332C19.0808 33.3332 18.3346 32.587 18.3346 31.6665V21.6665H8.33464C7.41416 21.6665 6.66797 20.9203 6.66797 19.9998C6.66797 19.0794 7.41416 18.3332 8.33464 18.3332H18.3346V8.33317C18.3346 7.4127 19.0808 6.6665 20.0013 6.6665Z"
                                              fill="#536E9B" />
                                    </svg>
                                </div>

                            </div>
                            <input type="file" id="noAjaxFileUploader" name="images[]" class="d-none" multiple>
                        </div>


                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Документы
                            </div>
                            Паспорт, доверенность и прочее
                            <div class="page-file-list" id='images'>
                                <div class="page-add-file no-ajax upload-file doc">
                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.6"
                                              d="M20.0013 6.6665C20.9218 6.6665 21.668 7.4127 21.668 8.33317V18.3332H31.668C32.5884 18.3332 33.3346 19.0794 33.3346 19.9998C33.3346 20.9203 32.5884 21.6665 31.668 21.6665H21.668V31.6665C21.668 32.587 20.9218 33.3332 20.0013 33.3332C19.0808 33.3332 18.3346 32.587 18.3346 31.6665V21.6665H8.33464C7.41416 21.6665 6.66797 20.9203 6.66797 19.9998C6.66797 19.0794 7.41416 18.3332 8.33464 18.3332H18.3346V8.33317C18.3346 7.4127 19.0808 6.6665 20.0013 6.6665Z"
                                              fill="#536E9B" />
                                    </svg>
                                </div>
                            </div>
                            <input type="file" id="noAjaxFileUploaderDoc" name="docs[]" class="d-none" multiple>
                        </div>



                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Дополнительно
                            </div>
                            <div class="field-style">
                                <span>Описание</span>
                                <textarea name="car_data[car_additional]"
                                          id="car_additional" placeholder="Не указан" class="mw-100">{{ old('car_data.car_additional') }}</textarea>
                            </div>
                        </div>
                        <div id="hiddenInputs"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</form>





{{--<section class="tabform">
    <div class="wrapper">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="tabform__wrap">
            <div class="newtopbar">
                <div class="newtopbar__mobtab">
                    <span class="newtopbar__mobtitletab"></span>
                    <span class="newtopbar__mobarrow"></span>
                </div>
                <div class="buttonWrapper">
                    <button class="tabform__btn active" data-id="tabform__request">Заявка</button>
                    <button class="tabform__btn" data-id="tabform__auto">Авто</button>
                    <button class="tabform__btn" data-id="tabform__more">Подробно</button>
                </div>
            </div>
            <form method="POST" action="{{ route('applications.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="contentWrapper">
                    <div class="tabform__content active" id="tabform__request">
                        <div class="tabform-flex">
                            <div class="tabform__item">
                                <h2>Административные данные</h2>
                                <div class="tabform__group d-flex">
                                    <div class="tabform__inputwrap">
                                        <label>VIN / Номер кузова</label>
                                        <input type="text" id="vin"
                                               class="vin @error('vin_array') is-invalid @enderror"
                                               name="car_data[vin_array]"
                                               value="{{ old('car_data.vin_array') }}"
                                               placeholder="XTA210600C000001">
                                        <div id="vinDuplicates"></div>
                                    </div>
                                    <div class="tabform__inputwrap">
                                        <label>Гос. номер</label>
                                        <input type="text" id="license_plate"
                                               class="license_plate @error('license_plate') is-invalid @enderror"
                                               name="car_data[license_plate]"
                                               value="{{ old('car_data.license_plate') }}"
                                               placeholder="A001AA177">
                                        <div id="licensePlateDuplicates"></div>
                                    </div>
                                    <div class="tabform__inputwrap w-100">
                                        <label>Номер убытка или лизингового договора</label>
                                        <input type="text" id="external_id" name="app_data[external_id]"
                                               value="{{ old('app_data.external_id') }}"
                                               placeholder="002AT-20/0200285, 002AS21-004489">
                                    </div>
                                    <div class="tabform__inputwrap">
                                        <label>ФИО собственника</label>
                                        <input type="text" name="app_data[courier_fullname]"
                                               value="{{ old('app_data.courier_fullname') }}"
                                               placeholder="Иванов Иван Иванович">
                                    </div>
                                    <div class="tabform__inputwrap">
                                        <label>Телефон собственника</label>
                                        <input type="tel" name="app_data[courier_phone]"
                                               value="{{ old('app_data.courier_phone') }}"
                                               placeholder="+7 (___) ___-__-__">
                                    </div>
                                </div>
                            </div>
                            <div class="tabform__item">
                                <h2>Системные данные</h2>
                                <div class="tabform__group d-flex">
                                    <div class="tabform__inputwrap">
                                        <label>Партнёр</label>
                                        <select name="app_data[partner_id]" id="partner_id" class="partner_id @error('partner_id') is-invalid @enderror">
                                            <option selected hidden value="">{{ __('Select a partner..') }}</option>
                                            @foreach($partners as $partner)
                                                @if($loop->count == 1)
                                                    <option selected value="{{ $partner->id }}">{{ $partner->name }}</option>
                                                @else
                                                    <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="tabform__inputwrap">
                                        <label>Стоянка</label>
                                        <select name="app_data[parking_id]" id="parking_id">
                                            <option selected hidden value="">{{ __('Select a parking..') }}</option>
                                            @foreach($parkings as $parking)
                                                <option value="{{ $parking->id }}">{{ $parking->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="tabform__inputwrap">
                                        <label>Дата приезда</label>
                                        <input type="text" id="arriving_at" class="date" name="app_data[arriving_at]"
                                               placeholder="Выберите дату..">
                                    </div>
                                    <div class="tabform__inputwrap">
                                        <label>Промежуток</label>
                                        <select id="arriving_interval" name="app_data[arriving_interval]">
                                            <option selected hidden value="">{{ __('Select a time interval..') }}</option>
                                            <option value="10:00 - 14:00">10:00 - 14:00</option>
                                            <option value="14:00 - 18:00">14:00 - 18:00</option>
                                        </select>
                                    </div>
                                    @hasrole('Admin')
                                    <div class="tabform__inputwrap">
                                        <label>Дата выдачи</label>
                                        <div class="input-group flatpickr">
                                            <input type="text" id="issued_at" class="date-admin" name="app_data[issued_at]"
                                                   placeholder="Выберите дату.." data-input>
                                            <div class="input-group-append">
                                                <button id="dataClear" class="btn btn-danger" type="button" data-clear>
                                                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tabform__inputwrap">
                                        <label>Кто выдал</label>
                                        <select name="app_data[issued_by]" id="issued_by" class="issued_by @error('issued_by') is-invalid @enderror">
                                            <option selected hidden value="">{{ __('Select a manager..') }}</option>
                                            @foreach($managers as $manager)
                                                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="tabform__inputwrap">
                                        <label>Статус</label>
                                        <select name="app_data[status_admin]" id="status_admin" class="status_admin @error('status_admin') is-invalid @enderror">
                                            <option selected hidden value="">{{ __('Select a status..') }}</option>
                                            @foreach($statuses as $statuse)
                                                <option value="{{ $statuse->id }}">{{ $statuse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endhasrole
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tabform__content" id="tabform__auto">
                        <div class="tabform-flex">
                            <div class="tabform__item w-100">
                                <h2>Марка и модель авто</h2>
                                <div class="tabform__cartlist d-flex">
                                    <div class="tabform__cart select first-cart car_type_id" id="types">
                                        <h3>{{ __('The type of car...') }} <span class="mob-arrow"></span></h3>
                                        <div class="tabform__mob-dd">
                                            <input type="text" placeholder="Поиск" class="select-search">
                                            <ul class="select-list tabform__ul">
                                                @foreach($carTypes as $car)
                                                    @if ($loop->first)
                                                        <li class="select-item tabform__li active">
                                                            <a href="" data-name-id="car_type_id" data-id="{{ $car->id }}">{{ $car->name }}</a>
                                                        </li>
                                                    @else
                                                        <li class="select-item tabform__li">
                                                            <a href="" data-name-id="car_type_id" data-id="{{ $car->id }}">{{ $car->name }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="selectGroup">
                                        <div class="tabform__cart select car_mark_id" id="marks">
                                            <h3>{{ __('The brand of the car...') }} <span class="mob-arrow"></span></h3>
                                            <div class="tabform__mob-dd">
                                                <input type="text" placeholder="Поиск" class="select-search">
                                                <ul class="tabform__ul select-list" data-placeholder="Выберите тип авто">
                                                    --}}{{-- <li class="tabform__li"><img src="img/bmw-icon.png"> bmw</li> --}}{{--
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tabform__cart select car_model_id" id="models">
                                            <h3>{{ __('The car model...') }} <span class="mob-arrow"></span></h3>
                                            <div class="tabform__mob-dd">
                                                <input type="text" placeholder="Поиск" class="select-search">
                                                <ul class="select-list tabform__ul" data-placeholder="Выберите марку авто">
                                                    <li class="placeholder statuspink">Выберите марку авто</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tabform__cart select year" id="years">
                                            <h3>{{ __('The year of the car...') }} <span class="mob-arrow"></span></h3>
                                            <div class="tabform__mob-dd">
                                                <input type="text" placeholder="Поиск" class="select-search">
                                                <ul class="select-list tabform__ul" data-placeholder="Выберите модель авто">
                                                    <li class="placeholder statuspink">Выберите модель авто</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="textArea" class="d-none">
                                        <label for="reg_number" style="padding: 0 15px;">{{ __('Description of auto') }}</label>
                                        <textarea class="form-control" id="autoDesc"
                                                  rows="4"
                                                  name="car_data[car_title]"
                                                  value="{{ old('car_data.car_title') }}"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="tabform__item w-100">
                                <h2>Фотографии авто</h2>
                                <div class="tabform__gallery">
                                    <div class="input-images"></div>
                                </div>
                            </div>
                            <div class="tabform__itemwrap">
                                <div class="tabform__item">
                                    <h2>Административные данные</h2>
                                    <div class="tabform__group d-flex">
                                        <div class="tabform__inputwrap">
                                            <label>VIN / Номер кузова</label>
                                            <input class="vin" type="text" placeholder="XTA210600C000001">
                                        </div>
                                        <div class="tabform__inputwrap">
                                            <label>Гос. номер</label>
                                            <input class="license_plate" type="text" placeholder="A001AA177">
                                        </div>
                                    </div>
                                </div>
                                <div class="tabform__item">
                                    <h2>Комментарий</h2>
                                    <textarea name="car_data[car_additional]"
                                              value="{{ old('car_data.car_additional') }}"
                                              id="car_additional" placeholder="Комментарий"></textarea>
                                </div>
                            </div>
                            <div class="tabform__item">
                                <h2>Об авто</h2>
                                <div class="tabform__inputwrap w-100">
                                    <label>Количество ключей</label>
                                    <label class="tabform__radio">
                                        <input type="radio" name="car_data[car_key_quantity]" checked value="0">
                                        <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="tabform__radionum">0</span>
                                    </span>
                                    </label>
                                    <label class="tabform__radio">
                                        <input type="radio" name="car_data[car_key_quantity]" value="1">
                                        <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="tabform__radionum">1</span>
                                    </span>
                                    </label>
                                    <label class="tabform__radio">
                                        <input type="radio" name="car_data[car_key_quantity]" value="2">
                                        <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="tabform__radionum">2</span>
                                    </span>
                                    </label>
                                    <label class="tabform__radio">
                                        <input type="radio" name="car_data[car_key_quantity]" value="4">
                                        <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="">4</span>
                                    </span>
                                    </label>
                                </div>
                                <div class="tabform__inputwrap w-100">
                                    <label>Свидетельство о регистрации (СТС)</label>
                                    <input type="text" name="car_data[sts]" value="{{ old('car_data.sts') }}" id="sts" placeholder="XTA210600C000001">
                                    <label class="tabform__checkbox">
                                        <input type="checkbox" name="car_data[sts_provided]" value="1">
                                        <span class="tabform__checkboxnew"></span> Принят на хранение
                                    </label>
                                </div>
                                <div class="tabform__inputwrap w-100">
                                    <label>Паспорт транспортного средства (ПТС)</label>
                                    <label class="tabform__radio">
                                        <input type="radio" name="car_data[pts_type]" value="Оригинал">
                                        <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="tabform__radionum">Оригинал</span>
                                    </span>
                                    </label>
                                    <label class="tabform__radio">
                                        <input type="radio" name="car_data[pts_type]" value="Электронный">
                                        <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="tabform__radionum">Электронный</span>
                                    </span>
                                    </label>
                                    <input type="text" name="car_data[pts]" value="{{ old('car_data.pts') }}" placeholder="XTA210600C000001">
                                    <label class="tabform__checkbox">
                                        <input type="checkbox" name="car_data[pts_provided]" value="1">
                                        <span class="tabform__checkboxnew"></span> Принят на хранение
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tabform__content" id="tabform__more">
                        <div class="tabform-flex">
                            <div class="tabform__item w-100">
                                <h2>Дополнительная информация</h2>
                                <div class="tabform__cartlist d-flex">
                                    <div class="tabform__cart select cart-3" id="generations">
                                        <h3>{{ __('Generation...') }} <span class="mob-arrow"></span></h3>
                                        <div class="tabform__mob-dd">
                                            <input type="text" placeholder="Поиск" class="select-search">
                                            <ul class="select-list tabform__ul" data-placeholder="Выберите поколение авто">
                                                <li class="placeholder statuspink">Выберите поколение авто</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tabform__cart select cart-3" id="series">
                                        <h3>{{ __('Series...') }} <span class="mob-arrow"></span></h3>
                                        <div class="tabform__mob-dd">
                                            <input type="text" placeholder="Поиск" class="select-search">
                                            <ul class="select-list tabform__ul" data-placeholder="Выберите кузов авто">
                                                <li class="placeholder statuspink">Выберите кузов авто</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tabform__cart select cart-3" id="modifications">
                                        <h3>{{ __('Modifications...') }} <span class="mob-arrow"></span></h3>
                                        <div class="tabform__mob-dd">
                                            <input type="text" placeholder="Поиск" class="select-search">
                                            <ul class="select-list tabform__ul" data-placeholder="Выберите модификацию авто">
                                                <li class="placeholder statuspink">Выберите модификацию авто</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tabform__cart select cart-3" id="engines">
                                        <h3>{{ __('Engines...') }} <span class="mob-arrow"></span></h3>
                                        <div class="tabform__mob-dd">
                                            <input type="text" placeholder="Поиск" class="select-search">
                                            <ul class="select-list tabform__ul" data-placeholder="Выберите двигатель авто">
                                                <li class="placeholder statuspink">Выберите двигатель авто</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tabform__cart select cart-3" id="transmissions">
                                        <h3>{{ __('Transmission...') }} <span class="mob-arrow"></span></h3>
                                        <div class="tabform__mob-dd">
                                            <input type="text" placeholder="Поиск" class="select-search">
                                            <ul class="select-list tabform__ul" data-placeholder="Выберите КПП авто">
                                                <li class="placeholder statuspink">Выберите КПП авто</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tabform__cart select cart-3" id="gears">
                                        <h3>{{ __('Gear...') }} <span class="mob-arrow"></span></h3>
                                        <div class="tabform__mob-dd">
                                            <input type="text" placeholder="Поиск" class="select-search">
                                            <ul class="select-list tabform__ul" data-placeholder="Выберите привод авто">
                                                <li class="placeholder statuspink">Выберите привод авто</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tabform__inputwrap cart-3">
                                        <label>Цвет</label>
                                        <select name="car_data[color]" id="color">
                                            @foreach($colors as $color)
                                                <option value="{{ $color['value'] }}">{{ $color['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="tabform__inputwrap cart-3">
                                        <label>Пробег</label>
                                        <input type="text" name="car_data[milage]" value="{{ old('car_data.milage') }}" placeholder="50000">
                                    </div>
                                    <div class="tabform__inputwrap cart-3">
                                        <label>Количество владельцев</label>
                                        <label class="tabform__radio">
                                            <input type="radio" name="car_data[owner_number]" value="1">
                                            <span class="d-flex">
                                            <span class="tabform__radionew"></span>
                                            <span class="tabform__radionum">1</span>
                                        </span>
                                        </label>
                                        <label class="tabform__radio">
                                            <input type="radio" name="car_data[owner_number]" value="2">
                                            <span class="d-flex">
                                            <span class="tabform__radionew"></span>
                                            <span class="tabform__radionum">2</span>
                                        </span>
                                        </label>
                                        <label class="tabform__radio">
                                            <input type="radio" name="car_data[owner_number]" value="3">
                                            <span class="d-flex">
                                            <span class="tabform__radionew"></span>
                                            <span class="tabform__radionum">3 и более</span>
                                        </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="tabform__item w-100">
                                <h2>Состояние авто</h2>

                                <div class="d-flex">
                                    <div class="cart-4">
                                        <label class="tabform__checkbox medium-radio">
                                            <input type="checkbox" name="car_data[condition_engine]" checked value="">
                                            <span class="d-flex">
                                            <span class="tabform__checkboxnew"></span> <span>Двигатель в
                                                порядке</span>
                                        </span>
                                        </label>
                                        <div class="tabform__checkboxgroup">
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_engine][]" value="Дымность двигателя
                                            (густой, белый, сизый,
                                            черный)">
                                                <span class="tabform__checkboxnew"></span> Дымность двигателя
                                                (густой, белый, сизый,
                                                черный)
                                            </label>
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_engine][]" value="Повышенный стук и шум
                                            при работе двигателя">
                                                <span class="tabform__checkboxnew"></span> Повышенный стук и шум
                                                при работе двигателя
                                            </label>
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_engine][]" value="Повышенный шум при работе выхлопной системы">
                                                <span class="tabform__checkboxnew"></span> Повышенный шум при работе выхлопной системы
                                            </label>
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_engine][]" value="Подтекание при осмотре подкапотного пространства">
                                                <span class="tabform__checkboxnew"></span> Подтекание при осмотре подкапотного пространства
                                            </label>
                                        </div>
                                    </div>
                                    <div class="cart-4">
                                        <label class="tabform__checkbox medium-radio">
                                            <input type="checkbox" name="car_data[condition_transmission]" checked value="">
                                            <span class="d-flex">
                                            <span class="tabform__checkboxnew"></span> <span>КПП в порядке</span>
                                        </span>
                                        </label>
                                        <div class="tabform__checkboxgroup">
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_transmission][]" value="Рывки и толчки авто при переключении">
                                                <span class="tabform__checkboxnew"></span> Рывки и толчки авто при переключении
                                            </label>
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_transmission][]" value="Повышенный шум при переключении">
                                                <span class="tabform__checkboxnew"></span> Повышенный шум при переключении
                                            </label>
                                        </div>
                                    </div>
                                    <div class="cart-4">
                                        <label class="tabform__checkbox medium-radio">
                                            <input type="checkbox" name="car_data[condition_electric]" checked value="">
                                            <span class="d-flex">
                                            <span class="tabform__checkboxnew"></span> <span>Электрика в
                                                порядке</span>
                                        </span>
                                        </label>
                                        <div class="tabform__checkboxgroup">
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_electric][]" value="Ошибки на панели приборов при заведенном ДВС">
                                                <span class="tabform__checkboxnew"></span> Ошибки на панели приборов при заведенном ДВС
                                            </label>
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_electric][]" value="Неправильные команды электроники">
                                                <span class="tabform__checkboxnew"></span> Неправильные команды электроники
                                            </label>
                                        </div>
                                    </div>
                                    <div class="cart-4">
                                        <label class="tabform__checkbox medium-radio">
                                            <input type="checkbox" name="car_data[condition_gear]" checked value="">
                                            <span class="d-flex">
                                            <span class="tabform__checkboxnew"></span> <span>Ходовая в
                                                порядке</span>
                                        </span>
                                        </label>
                                        <div class="tabform__checkboxgroup">
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_gear][]" value="Посторонний звук со стороны ходовой">
                                                <span class="tabform__checkboxnew"></span> Ошибки на панели приборов при заведенном ДВС
                                            </label>
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_gear][]" value="Посторонние звуки при вращении рулевого колеса">
                                                <span class="tabform__checkboxnew"></span> Неправильные команды электроники
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tabform__item">
                                <h2>Состояние кузова</h2>
                            </div>
                            <div class="tabform__item">
                                <h2>Состояние салона</h2>
                            </div>
                        </div>
                    </div>
                    <div id="hiddenInputs"></div>
                </div>
                <div class="tabform__footer">
                    <label class="tabform__checkbox" id="statusId">
                        <input type="checkbox" name="app_data[status_id]">
                        <span class="tabform__checkboxnew"></span> Черновик
                    </label>

                    <button class="tabform__footerbtn bgpink" type="button" id="tabPrev">Назад</button>
                    <button class="tabform__footerbtn bggreen" type="button" id="tabNext">Далее</button>
                    <button class="tabform__footerbtn bgpink" id="save">Создать</button>
                    <a href="{{ route('applications.index') }}" class="tabform__footerbtn bggreen" >Отменить</a>
                </div>
            </form>
        </div>

    </div>
</section>--}}
