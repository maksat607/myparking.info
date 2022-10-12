<section class="tabform">
    <div class="wrapper">
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
                                    <input type="text" name="car_data[vin_array][]" placeholder="XTA210600C000001">
                                </div>
                                <div class="tabform__inputwrap">
                                    <label>Гос. номер</label>
                                    <input type="text" name="car_data[license_plate]" placeholder="A001AA177">
                                </div>
                                <div class="tabform__inputwrap w-100">
                                    <label>Номер убытка или лизингового договора</label>
                                    <input type="text" name="app_data[external_id]" placeholder="002AT-20/0200285, 002AS21-004489">
                                </div>
                                <div class="tabform__inputwrap">
                                    <label>ФИО собственника</label>
                                    <input type="text" name="app_data[courier_fullname]" placeholder="Иванов Иван Иванович">
                                </div>
                                <div class="tabform__inputwrap">
                                    <label>Телефон собственника</label>
                                    <input type="tel" name="app_data[courier_phone]" placeholder="+7 (___) ___-__-__">
                                </div>
                            </div>
                        </div>
                        <div class="tabform__item">
                            <h2>Системные данные</h2>
                            <div class="tabform__group d-flex">
                                <div class="tabform__inputwrap">
                                    <label>Партнёр</label>
                                    <select name="app_data[partner_id]" id="partner_id">
                                        @foreach($partners as $partner)
                                        <option value="{{ $partner->id }}">{{ $partner->shortname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="tabform__inputwrap">
                                    <label>Стоянка</label>
                                    <select name="app_data[parking_id]" id="parking_id">
                                        @foreach($parkings as $parking)
                                            <option value="{{ $parking->id }}">{{ $parking->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="tabform__inputwrap">
                                    <label>Дата постановки</label>
                                    <input type="text" id="arriving_at" class="date" name="app_data[arriving_at]"
                                           placeholder="Выберите дату..">
                                </div>
                                <div class="tabform__inputwrap">
                                    <label>Промежуток</label>
                                    <select id="arriving_interval" name="app_data[arriving_interval]">
                                        <option value="10:00 - 14:00">10:00 - 14:00</option>
                                        <option value="14:00 - 18:00">14:00 - 18:00</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tabform__content" id="tabform__auto">
                    <div class="tabform-flex">
                        <div class="tabform__item w-100">
                            <h2>Марка и модель авто</h2>
                            <div class="tabform__cartlist d-flex">
                                <div class="tabform__cart select first-cart" id="types">
                                    <h3>{{ __('The type of car...') }} <span class="mob-arrow"></span></h3>
                                    <div class="tabform__mob-dd">
                                        <input type="text" placeholder="Поиск" class="select-search">
                                        <ul class="select-list tabform__ul">
                                            @foreach($carList as $car)
                                                    @if ($loop->first)
                                                        <li class="select-item tabform__li active"><a href="" data-name-id="car_type_id" data-id="{{ $car->id }}">{{ $car->name }}</a></li>
                                                    @else
                                                        <li class="select-item tabform__li"><a href="" data-name-id="car_type_id" data-id="{{ $car->id }}">{{ $car->name }}</a></li>
                                                    @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div id="selectGroup">
                                    <div class="tabform__cart select" id="marks">
                                        <h3>{{ __('The brand of the car...') }} <span class="mob-arrow"></span></h3>
                                        <div class="tabform__mob-dd">
                                            <input type="text" placeholder="Поиск" class="select-search">
                                            <ul class="tabform__ul select-list" data-placeholder="Выберите тип авто">
                                                {{-- <li class="tabform__li"><img src="img/bmw-icon.png"> bmw</li> --}}
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tabform__cart select" id="models">
                                        <h3>{{ __('The car model...') }} <span class="mob-arrow"></span></h3>
                                        <div class="tabform__mob-dd">
                                            <input type="text" placeholder="Поиск" class="select-search">
                                            <ul class="select-list tabform__ul" data-placeholder="Выберите марку авто">
                                                <li class="placeholder statuspink">Выберите марку авто</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="tabform__cart select" id="years">
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
                                    <textarea class="form-control" id="autoDesc" rows="4" name="car_data[car_title]"></textarea>
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
                                        <input type="text" placeholder="XTA210600C000001">
                                    </div>
                                    <div class="tabform__inputwrap">
                                        <label>Гос. номер</label>
                                        <input type="text" placeholder="A001AA177">
                                    </div>
                                </div>
                            </div>
                            <div class="tabform__item">
                                <h2>Комментарий</h2>
                                <textarea name="car_data[car_additional]" id="car_additional" placeholder="Комментарий"></textarea>
                            </div>
                        </div>
                        <div class="tabform__item">
                            <h2>Об авто</h2>
                            <div class="tabform__inputwrap w-100">
                                <label>Количество ключей</label>
                                <label class="tabform__radio">
                                    <input type="radio" name="car_data[car_key_quantity]" value="0">
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
                                <input type="text" name="car_data[sts]" id="sts" placeholder="XTA210600C000001">
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
                                <input type="text" name="car_data[pts]" placeholder="XTA210600C000001">
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
                                    <input type="text" name="car_data[milage]" placeholder="50000">
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
                                        <input type="checkbox" name="car_data[condition_engine]" value="0">
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
                                        <input type="checkbox" name="car_data[condition_transmission]" value="0">
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
                                        <input type="checkbox" name="car_data[condition_electric]" value="0">
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
                                        <input type="checkbox" name="car_data[condition_gear]" value="0">
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
                <label class="tabform__checkbox">
                    <input type="checkbox" name="app_data[status]">
                    <span class="tabform__checkboxnew"></span> Черновик
                </label>
                <button class="tabform__footerbtn bgpink">Создать</button>
                <button class="tabform__footerbtn bggreen">Отменить</button>
            </div>
            </form>
        </div>

    </div>
</section>

