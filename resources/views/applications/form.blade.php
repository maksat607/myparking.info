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
            <div class="contentWrapper">
                <div class="tabform__content active" id="tabform__request">
                    <div class="tabform-flex">
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
                                <div class="tabform__inputwrap w-100">
                                    <label>Номер убытка или лизингового договора</label>
                                    <input type="text" placeholder="002AT-20/0200285, 002AS21-004489">
                                </div>
                                <div class="tabform__inputwrap">
                                    <label>ФИО собственника</label>
                                    <input type="text" placeholder="Иванов Иван Иванович">
                                </div>
                                <div class="tabform__inputwrap">
                                    <label>Телефон собственника</label>
                                    <input type="text" placeholder="+7 (___) ___-__-__">
                                </div>
                            </div>
                        </div>
                        <div class="tabform__item">
                            <h2>Системные данные</h2>
                            <div class="tabform__group d-flex">
                                <div class="tabform__inputwrap">
                                    <label>Партнёр</label>
                                    <select name="" id="">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>
                                <div class="tabform__inputwrap">
                                    <label>Стоянка</label>
                                    <select name="" id="">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>
                                <div class="tabform__inputwrap">
                                    <label>Дата постановки</label>
                                    <input type="date">
                                </div>
                                <div class="tabform__inputwrap">
                                    <label>Промежуток</label>
                                    <select name="" id="">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
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
                                                        <li class="select-item tabform__li active"><a href="" class="active" data-name-id="type_id" data-id="{{ $car->id }}">{{ $car->name }}</a></li>
                                                    @else
                                                        <li class="select-item tabform__li"><a href="" data-name-id="type_id" data-id="{{ $car->id }}">{{ $car->name }}</a></li>
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
                                    <textarea class="form-control" id="autoDesc" rows="4" name="autoDesc"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tabform__item w-100">
                            <h2>Фотографии авто</h2>
                            <div class="tabform__gallery">

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
                                <textarea name="" id="" placeholder="Комментарий"></textarea>
                            </div>
                        </div>
                        <div class="tabform__item">
                            <h2>Об авто</h2>
                            <div class="tabform__inputwrap w-100">
                                <label>Количество ключей</label>
                                <label class="tabform__radio">
                                    <input type="radio" name="key">
                                    <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="tabform__radionum">0</span>
                                    </span>
                                </label>
                                <label class="tabform__radio">
                                    <input type="radio" name="key">
                                    <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="tabform__radionum">1</span>
                                    </span>
                                </label>
                                <label class="tabform__radio">
                                    <input type="radio" name="key">
                                    <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="tabform__radionum">2</span>
                                    </span>
                                </label>
                                <label class="tabform__radio">
                                    <input type="radio" name="key">
                                    <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="">4</span>
                                    </span>
                                </label>
                            </div>
                            <div class="tabform__inputwrap w-100">
                                <label>Свидетельство о регистрации (СТС)</label>
                                <input type="text" placeholder="XTA210600C000001">
                                <label class="tabform__checkbox">
                                    <input type="checkbox" name="switch">
                                    <span class="tabform__checkboxnew"></span> Принят на хранение
                                </label>
                            </div>
                            <div class="tabform__inputwrap w-100">
                                <label>Паспорт транспортного средства (ПТС)</label>
                                <label class="tabform__radio">
                                    <input type="radio" name="pts">
                                    <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="tabform__radionum">Оригинал</span>
                                    </span>
                                </label>
                                <label class="tabform__radio">
                                    <input type="radio" name="pts">
                                    <span class="d-flex">
                                        <span class="tabform__radionew"></span>
                                        <span class="tabform__radionum">Электронный</span>
                                    </span>
                                </label>
                                <input type="text" placeholder="XTA210600C000001">
                                <label class="tabform__checkbox">
                                    <input type="checkbox" name="switch">
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
                                <div class="tabform__cart cart-3">
                                    <h3>Поколение <span class="mob-arrow"></span></h3>
                                    <div class="tabform__mob-dd">
                                        <input type="text" placeholder="Поиск">
                                        <ul class="tabform__ul">
                                            <li class="tabform__li active">F20/F21 (Рестайлинг)</li>
                                            <li class="tabform__li">F52</li>
                                            <li class="tabform__li">F 40</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tabform__cart cart-3">
                                    <h3>Кузов <span class="mob-arrow"></span></h3>
                                    <div class="tabform__mob-dd">
                                        <input type="text" placeholder="Поиск">
                                        <ul class="tabform__ul">
                                            <li class="tabform__li active">F20/F21 (Рестайлинг)</li>
                                            <li class="tabform__li">F52</li>
                                            <li class="tabform__li">F 40</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tabform__cart cart-3">
                                    <h3>Модификация <span class="mob-arrow"></span></h3>
                                    <div class="tabform__mob-dd">
                                        <input type="text" placeholder="Поиск">
                                        <ul class="tabform__ul">
                                            <li class="tabform__li active">F20/F21 (Рестайлинг)</li>
                                            <li class="tabform__li">F52</li>
                                            <li class="tabform__li">F 40</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tabform__cart cart-3">
                                    <h3>Двигатель <span class="mob-arrow"></span></h3>
                                    <div class="tabform__mob-dd">
                                        <input type="text" placeholder="Поиск">
                                        <ul class="tabform__ul">
                                            <li class="tabform__li active">F20/F21 (Рестайлинг)</li>
                                            <li class="tabform__li">F52</li>
                                            <li class="tabform__li">F 40</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tabform__cart cart-3">
                                    <h3>КПП <span class="mob-arrow"></span></h3>
                                    <div class="tabform__mob-dd">
                                        <input type="text" placeholder="Поиск">
                                        <ul class="tabform__ul">
                                            <li class="tabform__li active">F20/F21 (Рестайлинг)</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tabform__cart cart-3">
                                    <h3>Привод <span class="mob-arrow"></span></h3>
                                    <div class="tabform__mob-dd">
                                        <input type="text" placeholder="Поиск">
                                        <ul class="tabform__ul">
                                            <li class="tabform__li active">F20/F21 (Рестайлинг)</li>
                                            <li class="tabform__li">F52</li>
                                            <li class="tabform__li">F 40</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tabform__inputwrap cart-3">
                                    <label>Цвет</label>
                                    <select name="" id="">
                                        <option value="0">Цвет</option>
                                        <option value="2">Белый</option>
                                    </select>
                                </div>
                                <div class="tabform__inputwrap cart-3">
                                    <label>Пробег</label>
                                    <input type="text" placeholder="50000">
                                </div>
                                <div class="tabform__inputwrap cart-3">
                                    <label>Количество владельцев</label>
                                    <label class="tabform__radio">
                                        <input type="radio" name="owner">
                                        <span class="d-flex">
                                            <span class="tabform__radionew"></span>
                                            <span class="tabform__radionum">1</span>
                                        </span>
                                    </label>
                                    <label class="tabform__radio">
                                        <input type="radio" name="owner">
                                        <span class="d-flex">
                                            <span class="tabform__radionew"></span>
                                            <span class="tabform__radionum">2</span>
                                        </span>
                                    </label>
                                    <label class="tabform__radio">
                                        <input type="radio" name="owner">
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
                                        <input type="checkbox" name="condition">
                                        <span class="d-flex">
                                            <span class="tabform__checkboxnew"></span> <span>Двигатель в
                                                порядке</span>
                                        </span>
                                    </label>
                                    <div class="tabform__checkboxgroup">
                                        <label class="tabform__checkbox">
                                            <input type="checkbox" name="switch">
                                            <span class="tabform__checkboxnew"></span> Дымность двигателя
                                            (густой, белый, сизый,
                                            черный)
                                        </label>
                                        <label class="tabform__checkbox">
                                            <input type="checkbox" name="switch">
                                            <span class="tabform__checkboxnew"></span> Повышенный стук и шум
                                            при работе двигателя
                                        </label>
                                    </div>
                                </div>
                                <div class="cart-4">
                                    <label class="tabform__checkbox medium-radio">
                                        <input type="checkbox" name="condition">
                                        <span class="d-flex">
                                            <span class="tabform__checkboxnew"></span> <span>КПП в порядке</span>
                                        </span>
                                    </label>
                                </div>
                                <div class="cart-4">
                                    <label class="tabform__checkbox medium-radio">
                                        <input type="checkbox" name="condition">
                                        <span class="d-flex">
                                            <span class="tabform__checkboxnew"></span> <span>Электрика в
                                                порядке</span>
                                        </span>
                                    </label>
                                </div>
                                <div class="cart-4">
                                    <label class="tabform__checkbox medium-radio">
                                        <input type="checkbox" name="condition">
                                        <span class="d-flex">
                                            <span class="tabform__checkboxnew"></span> <span>Ходовая в
                                                порядке</span>
                                        </span>
                                    </label>
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
                    <input type="checkbox" name="switch">
                    <span class="tabform__checkboxnew"></span> Черновик
                </label>
                <button class="tabform__footerbtn bgpink">Создать</button>
                <button class="tabform__footerbtn bggreen">Отменить</button>
            </div>
        </div>
    </div>
</section>
