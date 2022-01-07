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
            <a href="#" class="page-head__cancel">Отменить</a>
            <h1>{{ $title }}</h1>
            <div class="ml-auto d-flex">
                <label class="field-style">
                    <span class="field-style-title">Статус</span>
                    <select class="custom-select" name="state">
                        <option value="1">Хранение</option>
                        <option value="2">Осмотр</option>
                        <option value="3">Выдача</option>
                        <option value="4">Черновик</option>
                    </select>
                </label>
                <button class="btn btn-white">Создать заявку</button>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="inner-page">
        <div class="row no-gutters position-relative">
            <div class="col-md-8 block-nav">
                <div class="nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="block-nav__item" id="v-pills-settings-tab" data-toggle="pill"
                       href="#v-pills-1"
                       role="tab"
                       aria-controls="v-pills-settings" aria-selected="false">Заявка</a>
                    <a class="block-nav__item active" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-2"
                       role="tab"
                       aria-controls="v-pills-settings" aria-selected="false">Авто</a>
                </div>
            </div>
            <div class="tab-content tab-content-main col-md-12">
                <div class="row no-gutters tab-pane fade" id="v-pills-1">
                    <div class="col-md-8 main-col">
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
                                </div>
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
                                        <input type="text" name="app_data[courier_phone]"
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
                                <div class="col-6">
                                    <label class="field-style @error('partner_id') invalid @enderror">
                                        <span>Партнёр*</span>
                                        <select name="app_data[partner_id]" id="partner_id" class="partner_id page-select">
                                            <option selected hidden disabled value="">{{ __('Select a partner..') }}</option>
                                            @foreach($partners as $partner)
                                                @if($loop->count == 1)
                                                    <option selected value="{{ $partner->id }}">{{ $partner->name }}</option>
                                                @else
                                                    <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>Стоянка*</span>
                                        <select name="app_data[parking_id]" id="parking_id">
                                            <option selected hidden disabled value="">{{ __('Select a parking..') }}</option>
                                            @foreach($parkings as $parking)
                                                <option value="{{ $parking->id }}">{{ $parking->title }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                                <div class="col-12 mt-3">
                                    <label class="field-style w-100">
                                        <span>Партнёр*</span>
                                        <input type="text" id="external_id" name="app_data[external_id]"
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
                                        <span>Дата осмотра</span>
                                        <input type="text" id="arriving_at" class="date" name="app_data[arriving_at]" placeholder="Не указан">
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>Промежуток времени</span>
                                        <select id="arriving_interval" name="app_data[arriving_interval]">
                                            <option selected hidden value="">{{ __('Select a time interval..') }}</option>
                                            <option value="10:00 - 14:00">10:00 - 14:00</option>
                                            <option value="14:00 - 18:00">14:00 - 18:00</option>
                                        </select>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sidebar">
                            <div class="sidebar__title">
                                Чек-лист оформления
                            </div>
                            <div class="sidebar__item">
                                <div class="sidebar-item-title">
                                    Административные данные
                                </div>
                                <div
                                    class="check-valid sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">VIN / Номер кузова</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div
                                    class="check-invalid sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Государственный номер</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                            </div>

                            <div class="sidebar__item">
                                <div class="sidebar-item-title">
                                    О собственнике
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">ФИО собственника</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Телефон собственника</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                            </div>
                            <div class="sidebar__item">
                                <div class="sidebar-item-title">
                                    Системная информация
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Партнёр*</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Стоянка*</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Номер убытка / договора*</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                            </div>
                            <div class="sidebar__item">
                                <div class="sidebar-item-title">
                                    Дата
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Дата постановки</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Промежуток времени</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row no-gutters tab-pane fade show active" id="v-pills-2">
                    <div class="col-md-8 main-col">
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Марка и модель
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <fieldset class="fieldset">
                                        <legend class="legend">Тип автомобиля</legend>
                                        <div class="d-flex">
                                            <div class="type-card">
                                                <input type="text" placeholder="Поиск">
                                                <ul class="type-list">
                                                    <li class="type-item active">Легковой автомобиль</li>
                                                    <li class="type-item">Автобус</li>
                                                    <li class="type-item">Автокраны</li>
                                                    <li class="type-item">Автопогрузчики</li>
                                                    <li class="type-item">Автопоезд/ТС на сцепке</li>
                                                    <li class="type-item">Бульдозеры</li>
                                                    <li class="type-item">Грузовик</li>
                                                </ul>
                                            </div>
                                            <div class="type-card-info">
                                                <ul class="type-info">
                                                    <li class="type-info-item">
                                                        <span class="type-info-title">Тип автомобиля</span>
                                                        <div>Легковой автомобиль</div>
                                                    </li>
                                                    <li class="type-info-item">
                                                        <span class="type-info-title">Марка</span>
                                                        <div>BMW</div>
                                                    </li>
                                                    <li class="type-info-item">
                                                        <span class="type-info-title">Модель</span>
                                                        <div>1 серия</div>
                                                    </li>
                                                    <li class="type-info-item">
                                                        <span class="type-info-title">Год выпуска</span>
                                                        <span>Не указан</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Поколение и модификация
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <fieldset class="fieldset">
                                        <legend class="legend">Поколение</legend>
                                        <div class="d-flex">
                                            <div class="type-card">
                                                <input type="text" placeholder="Поиск">
                                                <ul class="type-list">
                                                    <li class="type-item active">F20/F21 [рестайлинг]</li>
                                                    <li class="type-item">F52</li>
                                                    <li class="type-item">F40</li>
                                                </ul>
                                            </div>
                                            <div class="type-card-info">
                                                <ul class="type-info">
                                                    <li class="type-info-item">
                                                        <span class="type-info-title">Поколение</span>
                                                        <div>F20/F21 [рестайлинг]</div>
                                                    </li>
                                                    <li class="type-info-item">
                                                        <span class="type-info-title">Кузов</span>
                                                        <div>Седан</div>
                                                    </li>
                                                    <li class="type-info-item">
                                                        <span class="type-info-title">Модификация</span>
                                                        <div>118i Steptronic (136 л.с.)</div>
                                                    </li>
                                                    <li class="type-info-item">
                                                        <span class="type-info-title">Двигатель</span>
                                                        <div>Бензиновый</div>
                                                    </li>
                                                    <li class="type-info-item">
                                                        <span class="type-info-title">КПП</span>
                                                        <div>Автомат</div>
                                                    </li>
                                                    <li class="type-info-item">
                                                        <span class="type-info-title">Привод</span>
                                                        <div>Передний</div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </fieldset>
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
                                        <input type="text" placeholder="Не указан" value="XTA210600C0000001">
                                    </label>
                                    <label class="field-style mt-3">
                                        <span>VIN</span>
                                        <input type="text" placeholder="Не указан" value="XTA210600C0000001">
                                        <button type="button" class="add"></button>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>Гос. номер</span>
                                        <input type="text" placeholder="Не указан" value="А001АА177">
                                    </label>
                                    <div class="mt-2">
                                        <label class="switch-radio-wrap">
                                            <input type="checkbox">
                                            <span class="switcher-radio"></span>
                                            <span>Нет учёта</span>
                                        </label>
                                    </div>
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
                                        <input type="text" placeholder="Не указан">
                                    </label>
                                    <div class="mt-2">
                                        <label class="switch-radio-wrap">
                                            <input type="checkbox">
                                            <span class="switcher-radio"></span>
                                            <span>Принят на хранение</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>ПТС</span>
                                        <div class="d-flex two-field">
                                            <input type="text" placeholder="Не указан">
                                            <select name="" id="" class="page-select">
                                                <option></option>
                                                <option value="1">Электронный</option>
                                                <option value="2">Оригинал</option>
                                                <option value="3">Дубликать</option>
                                                <option value="4">Электронный</option>
                                            </select>
                                        </div>
                                    </label>
                                    <div class="mt-2">
                                        <label class="switch-radio-wrap">
                                            <input type="checkbox">
                                            <span class="switcher-radio"></span>
                                            <span>Принят на хранение</span>
                                        </label>
                                    </div>
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
                                    <div class="mt-2 mb-3">
                                        <label class="switch-radio-wrap">
                                            <input type="radio" name="owners">
                                            <span class="switcher-radio"></span>
                                            <span>Первый</span>
                                        </label>
                                    </div>
                                    <div class="mt-2 mb-3">
                                        <label class="switch-radio-wrap">
                                            <input type="radio" name="owners">
                                            <span class="switcher-radio"></span>
                                            <span>Второй</span>
                                        </label>
                                    </div>
                                    <div class="mt-2 mb-3">
                                        <label class="switch-radio-wrap">
                                            <input type="radio" name="owners">
                                            <span class="switcher-radio"></span>
                                            <span>Третий и более</span>
                                        </label>
                                    </div>

                                </div>
                                <div class="col-6">
                                    <div class="inner-page__item-title">Кол-во ключей</div>
                                    <div class="row">
                                        <div class="col-5 mt-2 mb-3">
                                            <label class="switch-radio-wrap">
                                                <input type="radio" name="owners">
                                                <span class="switcher-radio"></span>
                                                <span>0</span>
                                            </label>
                                        </div>
                                        <div class="col-5 mt-2 mb-3">
                                            <label class="switch-radio-wrap">
                                                <input type="radio" name="owners">
                                                <span class="switcher-radio"></span>
                                                <span>1</span>
                                            </label>
                                        </div>
                                        <div class="col-5 mt-2 mb-3">
                                            <label class="switch-radio-wrap">
                                                <input type="radio" name="owners">
                                                <span class="switcher-radio"></span>
                                                <span>2</span>
                                            </label>
                                        </div>
                                        <div class="col-5 mt-2 mb-3">
                                            <label class="switch-radio-wrap">
                                                <input type="radio" name="owners">
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
                                        <select name="" id="" class="page-select">
                                            <option></option>
                                            <option value="1">Красный</option>
                                            <option value="2">Синий</option>
                                            <option value="3">Желтый</option>
                                            <option value="4">Черный</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="field-style mileage">
                                        <span>Пробег</span>
                                        <input type="number" placeholder="Не указан">
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
                                    <fieldset class="fieldset">
                                        <legend class="legend">Неисправности</legend>
                                        <div class="d-flex">
                                            <div class="type-card parts-list tab-checkbox" id="tab-checkbox" role="tablist">
                                                <label class="switch-radio-wrap d-flex">
                                                    <input type="checkbox" checked>
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="part-title"><a data-toggle="tab" href="#tab-tex1">Двигатель</a></span>
                                                    <span class="condition">Исправен</span>
                                                </label>
                                                <label class="switch-radio-wrap d-flex">
                                                    <input type="checkbox" checked>
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="part-title"><a data-toggle="tab" href="#tab-tex2">КПП</a></span>
                                                    <span class="condition">Исправен</span>
                                                </label>
                                                <label class="switch-radio-wrap d-flex">
                                                    <input type="checkbox" checked>
                                                    <span class="switcher-radio ml-auto"></span>
                                                    <span class="part-title"><a data-toggle="tab" href="#tab-tex3">Электрика</a></span>
                                                    <span class="condition">Исправен</span>
                                                </label>
                                                <label class="switch-radio-wrap d-flex">
                                                    <input type="checkbox" checked>
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
                                                        <input type="checkbox" checked>
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Рывки и толчки авто при
                                                                переключении</span>
                                                    </label>
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox">
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Повышенный шум при переключении
                                                            </span>
                                                    </label>
                                                </div>
                                                <div class="tab-pane fade" id="tab-tex2">
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox" checked>
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Рывки и толчки авто при
                                                                переключении</span>
                                                    </label>
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox">
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Повышенный шум при переключении
                                                            </span>
                                                    </label>
                                                </div>
                                                <div class="tab-pane fade" id="tab-tex3">
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox" checked>
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Рывки и толчки авто при
                                                                переключении</span>
                                                    </label>
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox">
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Повышенный шум при переключении
                                                            </span>
                                                    </label>
                                                </div>
                                                <div class="tab-pane fade" id="tab-tex4">
                                                    <label class="switch-radio-wrap d-flex mb-3">
                                                        <input type="checkbox">
                                                        <span class="switcher-radio ml-auto"></span>
                                                        <span class="check-box-text">Повышенный шум при переключении
                                                            </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row mt-5">
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
                            </div>
                        </div>
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Фотографии
                            </div>
                            <div class="page-file-list">
                                <div class="page-add-file">
                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.6"
                                              d="M20.0013 6.6665C20.9218 6.6665 21.668 7.4127 21.668 8.33317V18.3332H31.668C32.5884 18.3332 33.3346 19.0794 33.3346 19.9998C33.3346 20.9203 32.5884 21.6665 31.668 21.6665H21.668V31.6665C21.668 32.587 20.9218 33.3332 20.0013 33.3332C19.0808 33.3332 18.3346 32.587 18.3346 31.6665V21.6665H8.33464C7.41416 21.6665 6.66797 20.9203 6.66797 19.9998C6.66797 19.0794 7.41416 18.3332 8.33464 18.3332H18.3346V8.33317C18.3346 7.4127 19.0808 6.6665 20.0013 6.6665Z"
                                              fill="#536E9B" />
                                    </svg>
                                </div>
                                <div class="page-file-item">
                                    <img src="./assets/image/car.jpg" alt="">
                                    <div class="page-file__option">
                                        <button type="button" class="page-file__zoom"></button>
                                        <button type="button" class="page-file__delete"></button>
                                    </div>
                                </div>
                                <div class="page-file-item">
                                    <img src="./assets/image/car.jpg" alt="">
                                    <div class="page-file__option">
                                        <button type="button" class="page-file__zoom"></button>
                                        <button type="button" class="page-file__delete"></button>
                                    </div>
                                </div>
                                <div class="page-file-item">
                                    <img src="./assets/image/car.jpg" alt="">
                                    <div class="page-file__option">
                                        <button type="button" class="page-file__zoom"></button>
                                        <button type="button" class="page-file__delete"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Дополнительно
                            </div>
                            <div class="field-style">
                                <span>Описание</span>
                                <textarea name="" id="" placeholder="Не указан"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sidebar">
                            <div class="sidebar__title">
                                Чек-лист оформления
                            </div>
                            <div class="sidebar__item">
                                <div class="sidebar-item-title">
                                    Марка и модель
                                </div>
                                <div
                                    class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Тип автомобиля</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div
                                    class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Марка</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div
                                    class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Модель</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div
                                    class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Год выпуска</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                            </div>

                            <div class="sidebar__item">
                                <div class="sidebar-item-title">
                                    Поколение и модификация
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Поколение</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Кузов</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Модификация</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Двигатель</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">КПП</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Привод</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                            </div>

                            <div class="sidebar__item">
                                <div class="sidebar-item-title">
                                    Административная информация
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">VIN</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Гос. номер</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                            </div>

                            <div class="sidebar__item">
                                <div class="sidebar-item-title">
                                    Документы
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">СТС</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">ПТС</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                            </div>

                            <div class="sidebar__item">
                                <div class="sidebar-item-title">
                                    Информация об автомобиле
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Кол-во владельцев</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Кол-во ключей</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Пробег</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Цвет</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                            </div>

                            <div class="sidebar__item">
                                <div class="sidebar-item-title">
                                    Тех. состояние
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Двигатель</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">КПП</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Электрика</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Ходовая</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Кузов</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Салон</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                            </div>
                            <div class="sidebar__item">
                                <div class="sidebar-item-title">
                                    Фотографии
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Фотографии</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                            </div>
                            <div class="sidebar__item">
                                <div class="sidebar-item-title">
                                    Дополнительно
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Описание</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>






<section class="tabform">
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
                                                    {{-- <li class="tabform__li"><img src="img/bmw-icon.png"> bmw</li> --}}
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
</section>
