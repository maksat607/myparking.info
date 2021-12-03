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
            <form method="POST" action="{{ route('applications.update', ['application' => $application->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
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
                                               value="{{ $application->vin }}"
                                               placeholder="XTA210600C000001">
                                        <div id="vinDuplicates"></div>
                                    </div>
                                    <div class="tabform__inputwrap">
                                        <label>Гос. номер</label>
                                        <input type="text" id="license_plate"
                                               class="license_plate @error('license_plate') is-invalid @enderror"
                                               name="car_data[license_plate]"
                                               value="{{ $application->license_plate }}"
                                               placeholder="A001AA177">
                                        <div id="licensePlateDuplicates"></div>
                                    </div>
                                    <div class="tabform__inputwrap w-100">
                                        <label>Номер убытка или лизингового договора</label>
                                        <input type="text" id="external_id" name="app_data[external_id]"
                                               value="{{ $application->external_id }}"
                                               placeholder="002AT-20/0200285, 002AS21-004489">
                                    </div>
                                    <div class="tabform__inputwrap">
                                        <label>ФИО собственника</label>
                                        <input type="text" name="app_data[courier_fullname]"
                                               value="{{ $application->courier_fullname }}"
                                               placeholder="Иванов Иван Иванович">
                                    </div>
                                    <div class="tabform__inputwrap">
                                        <label>Телефон собственника</label>
                                        <input type="tel" name="app_data[courier_phone]"
                                               value="{{ $application->courier_phone }}"
                                               placeholder="+7 (___) ___-__-__">
                                    </div>
                                </div>
                            </div>
                            <div class="tabform__item">
                                <h2>Системные данные</h2>
                                <div class="tabform__group d-flex">
                                    <div class="tabform__inputwrap">
                                        <label>Партнёр</label>
                                        <select name="app_data[partner_id]" class="partner_id @error('license_plate') is-invalid @enderror">
                                            <option selected hidden value="">{{ __('Select a partner..') }}</option>
                                            @foreach($partners as $partner)
                                                <option @if($application->partner_id == $partner->id) selected @endif value="{{ $partner->id }}">{{ $partner->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="tabform__inputwrap">
                                        <label>Стоянка</label>
                                        <select name="app_data[parking_id]" id="parking_id">
                                            <option selected hidden value="">{{ __('Select a parking..') }}</option>
                                            @foreach($parkings as $parking)
                                                <option @if($application->parking_id == $parking->id) selected @endif value="{{ $parking->id }}">{{ $parking->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="tabform__inputwrap">
                                        <label>Дата приезда</label>
                                        <input type="text" id="arriving_at" class="date" name="app_data[arriving_at]"
                                               placeholder="Выберите дату..">
                                    </div>
                                    @push('scripts')
                                        const dateDataApplication = '{{ ($application->arriving_at) ? $application->arriving_at->format('d-m-Y') : now()->format('d-m-Y') }}';
                                    @endpush
                                    <div class="tabform__inputwrap">
                                        <label>Промежуток</label>
                                        <select id="arriving_interval" name="app_data[arriving_interval]">
                                            <option selected hidden value="">{{ __('Select a time interval..') }}</option>
                                            <option @if( $application->arriving_interval == "10:00 - 14:00" ) selected @endif value="10:00 - 14:00">10:00 - 14:00</option>
                                            <option @if( $application->arriving_interval == "14:00 - 18:00" ) selected @endif value="14:00 - 18:00">14:00 - 18:00</option>
                                        </select>
                                    </div>
                                    @hasanyrole('Admin|Manager')
                                    @if(auth()->user()->hasRole('Admin') || (auth()->user()->hasRole('Manager') && $application->status_id == 3))
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

                                        @push('scripts')
                                            const dateDataIssuedApplication = '{{ ($application->issued_at) ? $application->issued_at->format('d-m-Y') : null }}';
                                        @endpush
                                    </div>
                                    <div class="tabform__inputwrap">
                                        <label>Кто выдал</label>
                                        <select name="app_data[issued_by]" id="issued_by" class="issued_by @error('issued_by') is-invalid @enderror">
                                            <option selected hidden value="">{{ __('Select a manager..') }}</option>
                                            @foreach($managers as $manager)
                                                <option @if($application->issued_by == $manager->id) selected @endif value="{{ $manager->id }}">{{ $manager->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    @hasrole('Admin')
                                    <div class="tabform__inputwrap">
                                        <label>Статус</label>
                                        <select name="app_data[status_admin]" id="status_admin" class="status_admin @error('status_admin') is-invalid @enderror">
                                            <option selected hidden value="">{{ __('Select a status..') }}</option>
                                            @foreach($statuses as $status)
                                                <option @if($application->status_id == $status->id) selected @endif value="{{ $status->id }}">{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endhasrole
                                    @endhasanyrole
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
                                                @foreach($carTypes as $carType)
                                                    @if ($loop->first && empty($application->car_type_id))
                                                        <li class="select-item tabform__li active">
                                                            <a href="" data-name-id="car_type_id" data-id="{{ $carType->id }}">{{ $carType->name }}</a>
                                                        </li>
                                                    @elseif($application->car_type_id === $carType->id)
                                                        <li class="select-item tabform__li active">
                                                            <a href="" data-name-id="car_type_id" data-id="{{ $carType->id }}">{{ $carType->name }}</a>
                                                        </li>
                                                    @else
                                                        <li class="select-item tabform__li">
                                                            <a href="" data-name-id="car_type_id" data-id="{{ $carType->id }}">{{ $carType->name }}</a>
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
                                                    @if(!$carMarks)
                                                        <li class="placeholder statuspink">Выберите тип авто</li>
                                                    @else
                                                        @foreach($carMarks as $carMark)
                                                            @if($application->car_mark_id === $carMark->id)
                                                                <li class="select-item tabform__li active">
                                                                    <a href="" data-name-id="car_mark_id" data-id="{{ $carMark->id }}">{{ $carMark->name }}</a>
                                                                </li>
                                                            @else
                                                                <li class="select-item tabform__li">
                                                                    <a href="" data-name-id="car_mark_id" data-id="{{ $carMark->id }}">{{ $carMark->name }}</a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tabform__cart select car_model_id" id="models">
                                            <h3>{{ __('The car model...') }} <span class="mob-arrow"></span></h3>
                                            <div class="tabform__mob-dd">
                                                <input type="text" placeholder="Поиск" class="select-search">
                                                <ul class="select-list tabform__ul" data-placeholder="Выберите марку авто">
                                                    @if(!$carModels)
                                                        <li class="placeholder statuspink">Выберите марку авто</li>
                                                    @else
                                                        @foreach($carModels as $carModel)
                                                            @if($application->car_model_id === $carModel->id)
                                                                <li class="select-item tabform__li active">
                                                                    <a href="" data-name-id="car_model_id" data-id="{{ $carModel->id }}">{{ $carModel->name }}</a>
                                                                </li>
                                                            @else
                                                                <li class="select-item tabform__li">
                                                                    <a href="" data-name-id="car_model_id" data-id="{{ $carModel->id }}">{{ $carModel->name }}</a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tabform__cart select year" id="years">
                                            <h3>{{ __('The year of the car...') }} <span class="mob-arrow"></span></h3>
                                            <div class="tabform__mob-dd">
                                                <input type="text" placeholder="Поиск" class="select-search">
                                                <ul class="select-list tabform__ul" data-placeholder="Выберите модель авто">
                                                    @if(!$carYears)
                                                        <li class="placeholder statuspink">Выберите модель авто</li>
                                                    @else
                                                        @foreach($carYears as $carYear)
                                                            @if($application->year === $carYear->id)
                                                                <li class="select-item tabform__li active">
                                                                    <a href="" data-name-id="year" data-id="{{ $carYear->id }}">{{ $carYear->name }}</a>
                                                                </li>
                                                            @else
                                                                <li class="select-item tabform__li">
                                                                    <a href="" data-name-id="year" data-id="{{ $carYear->id }}">{{ $carYear->name }}</a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="textArea" class="d-none">
                                        <label for="reg_number" style="padding: 0 15px;">{{ __('Description of auto') }}</label>
                                        <textarea class="form-control" id="autoDesc"
                                                  rows="4"
                                                  name="car_data[car_title]"
                                                  value="{{ $application->car_title }}"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="tabform__item w-100">
                                <h2>Фотографии авто</h2>
                                <div class="tabform__gallery">
                                    <div class="input-images"></div>
                                </div>
                                @push('scripts')
                                    const carAttachmentDataApplication = @json($attachments);
                                @endpush
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
                                              id="car_additional" placeholder="Комментарий">{{ $application->car_additional }}</textarea>
                                </div>
                            </div>
                            <div class="tabform__item">
                                <h2>Об авто</h2>
                                <div class="tabform__inputwrap w-100">
                                    <label>Количество ключей</label>
                                    <label class="tabform__radio">
                                        <input type="radio" name="car_data[car_key_quantity]" @if(!$application->car_key_quantity) checked @endif value="0">
                                        <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="tabform__radionum">0</span>
                                    </span>
                                    </label>
                                    <label class="tabform__radio">
                                        <input type="radio" name="car_data[car_key_quantity]" @if($application->car_key_quantity == 1) checked @endif value="1">
                                        <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="tabform__radionum">1</span>
                                    </span>
                                    </label>
                                    <label class="tabform__radio">
                                        <input type="radio" name="car_data[car_key_quantity]" @if($application->car_key_quantity == 2) checked @endif value="2">
                                        <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="tabform__radionum">2</span>
                                    </span>
                                    </label>
                                    <label class="tabform__radio">
                                        <input type="radio" name="car_data[car_key_quantity]" @if($application->car_key_quantity == 4) checked @endif value="4">
                                        <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="">4</span>
                                    </span>
                                    </label>
                                </div>
                                <div class="tabform__inputwrap w-100">
                                    <label>Свидетельство о регистрации (СТС)</label>
                                    <input type="text" name="car_data[sts]" value="{{ $application->sts }}" id="sts" placeholder="XTA210600C000001">
                                    <label class="tabform__checkbox">
                                        <input type="checkbox" name="car_data[sts_provided]" @if($application->sts_provided) checked @endif value="1">
                                        <span class="tabform__checkboxnew"></span> Принят на хранение
                                    </label>
                                </div>
                                <div class="tabform__inputwrap w-100">
                                    <label>Паспорт транспортного средства (ПТС)</label>
                                    <label class="tabform__radio">
                                        <input type="radio" name="car_data[pts_type]" @if($application->pts_type == 'Оригинал') checked @endif value="Оригинал">
                                        <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="tabform__radionum">Оригинал</span>
                                    </span>
                                    </label>
                                    <label class="tabform__radio">
                                        <input type="radio" name="car_data[pts_type]" @if($application->pts_type == 'Электронный') checked @endif value="Электронный">
                                        <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="tabform__radionum">Электронный</span>
                                    </span>
                                    </label>
                                    <input type="text" name="car_data[pts]" value="{{ $application->pts }}" placeholder="XTA210600C000001">
                                    <label class="tabform__checkbox">
                                        <input type="checkbox" name="car_data[pts_provided]" @if($application->pts_provided) checked @endif value="1">
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
                                                @if(!$carGenerations)
                                                    <li class="placeholder statuspink">Выберите поколение авто</li>
                                                @else
                                                    @foreach($carGenerations as $carGeneration)
                                                        @if($application->car_generation_id === $carGeneration->id)
                                                            <li class="select-item tabform__li active">
                                                                <a href="" data-name-id="car_generation_id" data-id="{{ $carGeneration->id }}">{{ $carGeneration->name }}</a>
                                                            </li>
                                                        @else
                                                            <li class="select-item tabform__li">
                                                                <a href="" data-name-id="car_generation_id" data-id="{{ $carGeneration->id }}">{{ $carGeneration->name }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tabform__cart select cart-3" id="series">
                                        <h3>{{ __('Series...') }} <span class="mob-arrow"></span></h3>
                                        <div class="tabform__mob-dd">
                                            <input type="text" placeholder="Поиск" class="select-search">
                                            <ul class="select-list tabform__ul" data-placeholder="Выберите кузов авто">
                                                @if(!$carSeriess)
                                                    <li class="placeholder statuspink">Выберите кузов авто</li>
                                                @else
                                                    @foreach($carSeriess as $carSeries)
                                                        @if($application->car_series_id === $carSeries->id)
                                                            <li class="select-item tabform__li active">
                                                                <a href="" data-name-id="car_series_id" data-id="{{ $carSeries->id }}" data-body="{{ $carSeries->body }}">{{ $carSeries->name }}</a>
                                                            </li>
                                                        @else
                                                            <li class="select-item tabform__li">
                                                                <a href="" data-name-id="car_series_id" data-id="{{ $carSeries->id }}" data-body="{{ $carSeries->body }}">{{ $carSeries->name }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tabform__cart select cart-3" id="modifications">
                                        <h3>{{ __('Modifications...') }} <span class="mob-arrow"></span></h3>
                                        <div class="tabform__mob-dd">
                                            <input type="text" placeholder="Поиск" class="select-search">
                                            <ul class="select-list tabform__ul" data-placeholder="Выберите модификацию авто">
                                                @if(!$carModifications)
                                                    <li class="placeholder statuspink">Выберите модификацию авто</li>
                                                @else
                                                    @foreach($carModifications as $carModification)
                                                        @if($application->car_modification_id === $carModification->id)
                                                            <li class="select-item tabform__li active">
                                                                <a href="" data-name-id="car_modification_id" data-id="{{ $carModification->id }}">{{ $carModification->name }}</a>
                                                            </li>
                                                        @else
                                                            <li class="select-item tabform__li">
                                                                <a href="" data-name-id="car_modification_id" data-id="{{ $carModification->id }}">{{ $carModification->name }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tabform__cart select cart-3" id="engines">
                                        <h3>{{ __('Engines...') }} <span class="mob-arrow"></span></h3>
                                        <div class="tabform__mob-dd">
                                            <input type="text" placeholder="Поиск" class="select-search">
                                            <ul class="select-list tabform__ul" data-placeholder="Выберите двигатель авто">
                                                @if(!$carEngines)
                                                    <li class="placeholder statuspink">Выберите двигатель авто</li>
                                                @else
                                                    @foreach($carEngines as $carEngine)
                                                        @if($application->car_engine_id === $carEngine->id)
                                                            <li class="select-item tabform__li active">
                                                                <a href="" data-name-id="car_engine_id" data-id="{{ $carEngine->id }}">{{ $carEngine->name }}</a>
                                                            </li>
                                                        @else
                                                            <li class="select-item tabform__li">
                                                                <a href="" data-name-id="car_engine_id" data-id="{{ $carEngine->id }}">{{ $carEngine->name }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tabform__cart select cart-3" id="transmissions">
                                        <h3>{{ __('Transmission...') }} <span class="mob-arrow"></span></h3>
                                        <div class="tabform__mob-dd">
                                            <input type="text" placeholder="Поиск" class="select-search">
                                            <ul class="select-list tabform__ul" data-placeholder="Выберите КПП авто">
                                                @if(!$carTransmissions)
                                                    <li class="placeholder statuspink">Выберите КПП авто</li>
                                                @else
                                                    @foreach($carTransmissions as $carTransmission)
                                                        @if($application->car_transmission_id === $carTransmission->id)
                                                            <li class="select-item tabform__li active">
                                                                <a href="" data-name-id="car_transmission_id" data-id="{{ $carTransmission->id }}">{{ $carTransmission->name }}</a>
                                                            </li>
                                                        @else
                                                            <li class="select-item tabform__li">
                                                                <a href="" data-name-id="car_transmission_id" data-id="{{ $carTransmission->id }}">{{ $carTransmission->name }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tabform__cart select cart-3" id="gears">
                                        <h3>{{ __('Gear...') }} <span class="mob-arrow"></span></h3>
                                        <div class="tabform__mob-dd">
                                            <input type="text" placeholder="Поиск" class="select-search">
                                            <ul class="select-list tabform__ul" data-placeholder="Выберите привод авто">
                                                @if(!$carGears)
                                                    <li class="placeholder statuspink">Выберите привод авто</li>
                                                @else
                                                    @foreach($carGears as $carGear)
                                                        @if($application->car_gear_id === $carGear->id)
                                                            <li class="select-item tabform__li active">
                                                                <a href="" data-name-id="car_gear_id" data-id="{{ $carGear->id }}">{{ $carGear->name }}</a>
                                                            </li>
                                                        @else
                                                            <li class="select-item tabform__li">
                                                                <a href="" data-name-id="car_gear_id" data-id="{{ $carGear->id }}">{{ $carGear->name }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tabform__inputwrap cart-3">
                                        <label>Цвет</label>
                                        <select name="car_data[color]" id="color">
                                            @foreach($colors as $color)
                                                @if($application->color == $color['value'])
                                                    <option selected value="{{ $color['value'] }}">{{ $color['label'] }}</option>
                                                @endif
                                                <option value="{{ $color['value'] }}">{{ $color['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="tabform__inputwrap cart-3">
                                        <label>Пробег</label>
                                        <input type="text" name="car_data[milage]" value="{{ $application->milage }}" placeholder="50000">
                                    </div>
                                    <div class="tabform__inputwrap cart-3">
                                        <label>Количество владельцев</label>
                                        <label class="tabform__radio">
                                            <input type="radio" name="car_data[owner_number]" @if($application->owner_number == 1) checked @endif  value="1">
                                            <span class="d-flex">
                                            <span class="tabform__radionew"></span>
                                            <span class="tabform__radionum">1</span>
                                        </span>
                                        </label>
                                        <label class="tabform__radio">
                                            <input type="radio" name="car_data[owner_number]" @if($application->owner_number == 2) checked @endif value="2">
                                            <span class="d-flex">
                                            <span class="tabform__radionew"></span>
                                            <span class="tabform__radionum">2</span>
                                        </span>
                                        </label>
                                        <label class="tabform__radio">
                                            <input type="radio" name="car_data[owner_number]" @if($application->owner_number == 3) checked @endif value="3">
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
                                            <input type="checkbox" name="car_data[condition_engine]" @if(is_null($application->condition_engine)) checked @endif value="">
                                            <span class="d-flex">
                                            <span class="tabform__checkboxnew"></span> <span>Двигатель в
                                                порядке</span>
                                        </span>
                                        </label>
                                        <div class="tabform__checkboxgroup">
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_engine][]"
                                                       @if($application->condition_engine && in_array('Дымность двигателя (густой, белый, сизый, черный)', $application->condition_engine)) checked @endif
                                                       value="Дымность двигателя (густой, белый, сизый, черный)">
                                                <span class="tabform__checkboxnew"></span> Дымность двигателя (густой, белый, сизый, черный)
                                            </label>
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_engine][]"
                                                       @if($application->condition_engine && in_array('Повышенный стук и шум при работе двигателя', $application->condition_engine)) checked @endif
                                                       value="Повышенный стук и шум при работе двигателя">
                                                <span class="tabform__checkboxnew"></span> Повышенный стук и шум при работе двигателя
                                            </label>
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_engine][]"
                                                       @if($application->condition_engine && in_array('Повышенный шум при работе выхлопной системы', $application->condition_engine)) checked @endif
                                                       value="Повышенный шум при работе выхлопной системы">
                                                <span class="tabform__checkboxnew"></span> Повышенный шум при работе выхлопной системы
                                            </label>
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_engine][]"
                                                       @if($application->condition_engine && in_array('Подтекание при осмотре подкапотного пространства', $application->condition_engine)) checked @endif
                                                       value="Подтекание при осмотре подкапотного пространства">
                                                <span class="tabform__checkboxnew"></span> Подтекание при осмотре подкапотного пространства
                                            </label>
                                        </div>
                                    </div>
                                    <div class="cart-4">
                                        <label class="tabform__checkbox medium-radio">
                                            <input type="checkbox" name="car_data[condition_transmission]" @if(is_null($application->condition_transmission)) checked @endif value="">
                                            <span class="d-flex">
                                            <span class="tabform__checkboxnew"></span> <span>КПП в порядке</span>
                                        </span>
                                        </label>
                                        <div class="tabform__checkboxgroup">
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_transmission][]"
                                                       @if($application->condition_transmission && in_array('Рывки и толчки авто при переключении', $application->condition_transmission)) checked @endif
                                                       value="Рывки и толчки авто при переключении">
                                                <span class="tabform__checkboxnew"></span> Рывки и толчки авто при переключении
                                            </label>
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_transmission][]"
                                                       @if($application->condition_transmission && in_array('Повышенный шум при переключении', $application->condition_transmission)) checked @endif
                                                       value="Повышенный шум при переключении">
                                                <span class="tabform__checkboxnew"></span> Повышенный шум при переключении
                                            </label>
                                        </div>
                                    </div>
                                    <div class="cart-4">
                                        <label class="tabform__checkbox medium-radio">
                                            <input type="checkbox" name="car_data[condition_electric]"
                                                   @if(is_null($application->condition_electric)) checked @endif
                                                   value="">
                                            <span class="d-flex">
                                            <span class="tabform__checkboxnew"></span> <span>Электрика в порядке</span>
                                        </span>
                                        </label>
                                        <div class="tabform__checkboxgroup">
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_electric][]"
                                                       @if($application->condition_electric && in_array('Ошибки на панели приборов при заведенном ДВС', $application->condition_electric)) checked @endif
                                                       value="Ошибки на панели приборов при заведенном ДВС">
                                                <span class="tabform__checkboxnew"></span> Ошибки на панели приборов при заведенном ДВС
                                            </label>
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_electric][]"
                                                       @if($application->condition_electric && in_array('Неправильные команды электроники', $application->condition_electric)) checked @endif
                                                       value="Неправильные команды электроники">
                                                <span class="tabform__checkboxnew"></span> Неправильные команды электроники
                                            </label>
                                        </div>
                                    </div>
                                    <div class="cart-4">
                                        <label class="tabform__checkbox medium-radio">
                                            <input type="checkbox" name="car_data[condition_gear]"
                                                   @if(is_null($application->condition_gear)) checked @endif
                                                   value="">
                                            <span class="d-flex">
                                            <span class="tabform__checkboxnew"></span> <span>Ходовая в
                                                порядке</span>
                                        </span>
                                        </label>
                                        <div class="tabform__checkboxgroup">
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_gear][]"
                                                       @if($application->condition_gear && in_array('Посторонний звук со стороны ходовой', $application->condition_gear)) checked @endif
                                                       value="Посторонний звук со стороны ходовой">
                                                <span class="tabform__checkboxnew"></span> Посторонний звук со стороны ходовой
                                            </label>
                                            <label class="tabform__checkbox">
                                                <input type="checkbox" name="car_data[condition_gear][]"
                                                       @if($application->condition_gear && in_array('Посторонние звуки при вращении рулевого колеса', $application->condition_gear)) checked @endif
                                                       value="Посторонние звуки при вращении рулевого колеса">
                                                <span class="tabform__checkboxnew"></span> Посторонние звуки при вращении рулевого колеса
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
                    @if($application->acceptions)
                        @can('application_to_accepted')
                            <label class="tabform__checkbox" id="statusId">
                                <input type="checkbox" name="app_data[accept]">
                                <span class="tabform__checkboxnew"></span> Принять
                            </label>
                        @endcan
                    @endif
                        <button class="tabform__footerbtn bgpink" type="button" id="tabPrev">Назад</button>
                        <button class="tabform__footerbtn bggreen" type="button" id="tabNext">Далее</button>
                    <button class="tabform__footerbtn bgpink" id="save">Обновить</button>
                    <button class="tabform__footerbtn bggreen" type="button" >Отменить</button>
                </div>
            </form>
        </div>

    </div>
    @push('scripts')
    const carDataApplication = @json($dataApplication);
    @endpush
</section>
