@extends('layouts.app')

@section('content')
<div class="newtopbar">
    <div class="wrapper">
        <div class="newtopbar__mob">
            <span class="newtopbar__mobtitle"></span>
            <span class="newtopbar__mobarrow"></span>
        </div>
        <nav class="newtopbar__nav">
            <ul class="newtopbar__list s-between">
                <li class="newtopbar__item active">
                    <a href="#" class="newtopbar__link">Все</a>
                </li>
                <li class="newtopbar__item">
                    <a href="#" class="newtopbar__link">Хранение</a>
                </li>
                <li class="newtopbar__item">
                    <a href="#" class="newtopbar__link">Черновик</a>
                </li>
                <li class="newtopbar__item">
                    <a href="#" class="newtopbar__link">Постановка</a>
                </li>
                <li class="newtopbar__item">
                    <a href="#" class="newtopbar__link">Осмотр</a>
                </li>
                <li class="newtopbar__item">
                    <a href="#" class="newtopbar__link">Выдача</a>
                </li>
                <li class="newtopbar__item">
                    <a href="#" class="newtopbar__link">Выдано</a>
                </li>
                <li class="newtopbar__item">
                    <a href="#" class="newtopbar__link">Отклонено</a>
                </li>
                <li class="newtopbar__item">
                    <a href="#" class="newtopbar__link">Дубли</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
<div class="newfilter">
    <div class="wrapper s-between">
        <div class="newfilter__search d-flex">
            <select name="" id="" class="newfilter__select">
                <option value="Партнер">Партнер</option>
                <option value="Партнер">Партнер</option>
                <option value="Партнер">Партнер</option>
                <option value="Партнер">Партнер</option>
            </select>
            <select name="" id="" class="newfilter__select">
                <option value="Стоянка">Стоянка</option>
                <option value="Стоянка">Стоянка</option>
                <option value="Стоянка">Стоянка</option>
            </select>
            <select name="" id="" class="newfilter__select">
                <option value="Исполнитель">Исполнитель</option>
                <option value="Исполнитель">Исполнитель</option>
                <option value="Исполнитель">Исполнитель</option>
            </select>
            <input type="text" class="newfilter__input" placeholder="Поиск по тексту">
        </div>
        <div class="newfilter__sort">
            <button class="newfilter__save newbtn">Сохраненые</button>
            <button class="newfilter__sortcol newbtn">Колонки</button>
            <button class="newfilter__sortrow newbtn active">Строки</button>
        </div>
    </div>
</div>
<section class="newcart">
    <div class="wrapper">
        <div class="newcart__list d-flex">
            @foreach($applications as $application)
            <article class="newcart__item" id="application_{{ $application->id }}">
                <div class="newcart__img newcart__save lazy">
                    @foreach($application->attachments as $attachment)
                    <div class="newcart__imgwrap">
                        <img src="{{ $attachment->thumbnail_url }}" alt="">
                    </div>
                    @endforeach

                </div>
                <div class="newcart__topbtn">
                    <a class="newcart__edit" href="{{ route('applications.edit', ['application' => $application->id]) }}">
                        редактировать
                    </a>
                    <button class="newcart__delete">
                        удалить
                    </button>
                </div>
                <h3 class="newcart__title">{{ $application->car_title }}</h3>
                <div class="newcart__type s-between">
                    {{ $application->carType->name }} <span class="newcart__repeat">Повтор</span>
                </div>
                <div class="newcart__vin s-between">
                    Vin: <span class="newcart__vinnum">{{ $application->vin }}</span>
                </div>
                <div class="newcart__numberwrap s-between">
                    Гос. номер: <span class="newcart__number">{{ $application->license_plate }}</span>
                </div>
                @if($application->acceptions)
                <div class="newcart__dd">
                    <div class="newcart__btn"><span class="newcart__btntitle">Заявка:</span>
                        <span class="newcart__status blue">Постановка</span></div>
                    <div class="newcart__des">
                        <ul class="newcart__deslist">
                            <li class="newcart__desitem">
                                <span>Дата прибытия:</span>
                                <strong>{{ $application->formated_arriving_at }}</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Промежуток:</span>
                                <strong>{{ $application->arriving_interval }}</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата создания:</span>
                                <strong>{{ $application->formated_created_at }}</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата завершения:</span>
                                <strong>
                                    {{ $application->formated_issued_at }}
                                </strong>
                            </li>
                        </ul>
                    </div>
                </div>
                @elseif($application->issue)
                    <div class="newcart__dd">
                        <div class="newcart__btn"><span class="newcart__btntitle">Заявка:</span>
                            <span class="newcart__status pink">Выдача</span></div>
                        <div class="newcart__des">
                            <ul class="newcart__deslist">
                                <li class="newcart__desitem">
                                    <span>Дата прибытия:</span>
                                    <strong>{{ $application->formated_arriving_at }}</strong>
                                </li>
                                <li class="newcart__desitem">
                                    <span>Промежуток:</span>
                                    <strong>{{ $application->arriving_interval }}</strong>
                                </li>
                                <li class="newcart__desitem">
                                    <span>Дата создания:</span>
                                    <strong>{{ $application->formated_created_at }}</strong>
                                </li>
                                <li class="newcart__desitem">
                                    <span>Дата завершения:</span>
                                    <strong>
                                        {{ $application->formated_issued_at }}
                                    </strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                    {{-- TODO: заявка на осмотр --}}
                @endif
                @if(!$application->acceptions && $application->status->code != 'pending')
                <div class="newcart__dd">
                    <div class="newcart__btn"><span class="newcart__btntitle">Статус:</span> <span
                            class="newcart__status {{ $application->status->getColorClass() }}">{{$application->status->name}}</span></div>
                    <div class="newcart__des">
                        <ul class="newcart__deslist">
                            <li class="newcart__desitem">
                                <span>Дата принятия:</span>
                                <strong>{{ $application->formated_arrived_at }}</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата выдачи:</span>
                                <strong>{{ $application->formated_issued_at }}</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Сумма простоя:</span>
                                <strong>{{ $application->parked_price_regular }} ({{ $application->parked_days_regular }} дн.)</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                @endif
                <a href="#" class="newcart__moreinfo have-comments">
                    Подробное описание
                </a>
                @if($application->acceptions)
                <div class="newcart__confirmbtn">
                    <button class="newcart__accept issue" data-app-id="{{ $application->id }}">Принять</button>
                    <button class="newcart__deny deny" data-app-id="{{ $application->id }}">Отказать</button>
                </div>
                @endif
            </article>
            @endforeach
            {{--<article class="newcart__item">
                <div class="newcart__img newcart__nosave lazy">
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                </div>
                <div class="newcart__topbtn">
                    <button class="newcart__edit">
                        редактировать
                    </button>
                    <button class="newcart__delete">
                        удалить
                    </button>
                </div>
                <h3 class="newcart__title">Mercedes-Benz E-klasse AMG 2019 </h3>
                <div class="newcart__type s-between">
                    Легковой автомобиль <span class="newcart__repeat">Повтор</span>
                </div>
                <div class="newcart__vin s-between">
                    Vin: <span class="newcart__vinnum">Z94G2811BJR094175</span>
                </div>
                <div class="newcart__numberwrap s-between">
                    Гос. номер: <span class="newcart__number">а211во198</span>
                </div>
                <div class="newcart__dd">
                    <div class="newcart__btn"><span class="newcart__btntitle">Заявка:</span> <span
                            class="newcart__status pink">Осмотр</span></div>
                    <div class="newcart__des">
                        <ul class="newcart__deslist">
                            <li class="newcart__desitem">
                                <span>Дата прибытия:</span>
                                <strong>25.02.2021</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Промежуток:</span>
                                <strong>10:00 - 14:00</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата создания:</span>
                                <strong>25.02.2021</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата завершения:</span>
                                <strong>Не указана</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="newcart__dd">
                    <div class="newcart__btn"><span class="newcart__btntitle">Статус:</span> <span
                            class="newcart__status green">Хранение</span></div>
                    <div class="newcart__des">
                        <ul class="newcart__deslist">
                            <li class="newcart__desitem">
                                <span>Дата принятия:</span>
                                <strong>25.02.2021</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата выдачи:</span>
                                <strong>Не указана</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Сумма простоя:</span>
                                <strong>1,000.00 (2 дн.)</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                <a href="#" class="newcart__moreinfo">
                    Подробное описание
                </a>
            </article>
            <article class="newcart__item">
                <div class="newcart__img newcart__save lazy">
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                </div>
                <div class="newcart__topbtn">
                    <button class="newcart__edit">
                        редактировать
                    </button>
                    <button class="newcart__delete">
                        удалить
                    </button>
                </div>
                <h3 class="newcart__title">Mercedes-Benz E-klasse AMG 2019 </h3>
                <div class="newcart__type s-between">
                    Легковой автомобиль <span class="newcart__repeat">Повтор</span>
                </div>
                <div class="newcart__vin s-between">
                    Vin: <span class="newcart__vinnum">Z94G2811BJR094175</span>
                </div>
                <div class="newcart__numberwrap s-between">
                    Гос. номер: <span class="newcart__number">а211во198</span>
                </div>
                <div class="newcart__dd">
                    <div class="newcart__btn"><span class="newcart__btntitle">Заявка:</span> <span
                            class="newcart__status blue">Поставка</span></div>
                    <div class="newcart__des">
                        <ul class="newcart__deslist">
                            <li class="newcart__desitem">
                                <span>Дата прибытия:</span>
                                <strong>25.02.2021</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Промежуток:</span>
                                <strong>10:00 - 14:00</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата создания:</span>
                                <strong>25.02.2021</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата завершения:</span>
                                <strong>Не указана</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                <a href="#" class="newcart__moreinfo">
                    Подробное описание
                </a>
                <div class="newcart__confirmbtn">
                    <button class="newcart__accept">Принять</button>
                    <button class="newcart__deny">Отказать</button>
                </div>
            </article>
            <article class="newcart__item">
                <div class="newcart__img newcart__save lazy">
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                </div>
                <div class="newcart__topbtn">
                    <button class="newcart__edit">
                        редактировать
                    </button>
                    <button class="newcart__delete">
                        удалить
                    </button>
                </div>
                <h3 class="newcart__title">Mercedes-Benz E-klasse AMG 2019 </h3>
                <div class="newcart__type s-between">
                    Легковой автомобиль <span class="newcart__repeat">Повтор</span>
                </div>
                <div class="newcart__vin s-between">
                    Vin: <span class="newcart__vinnum">Z94G2811BJR094175</span>
                </div>
                <div class="newcart__numberwrap s-between">
                    Гос. номер: <span class="newcart__number">а211во198</span>
                </div>
                <div class="newcart__dd">
                    <div class="newcart__btn"><span class="newcart__btntitle">Статус:</span> <span
                            class="newcart__status green">Хранение</span></div>
                    <div class="newcart__des">
                        <ul class="newcart__deslist">
                            <li class="newcart__desitem">
                                <span>Дата принятия:</span>
                                <strong>25.02.2021</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата выдачи:</span>
                                <strong>Не указана</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Сумма простоя:</span>
                                <strong>1,000.00 (2 дн.)</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                <a href="#" class="newcart__moreinfo">
                    Подробное описание
                </a>
                <div class="newcart__confirmbtn">
                    <button class="newcart__bluebtn">Выдача</button>
                    <button class="newcart__bluebtn">Осмотр</button>
                    <button class="newcart__bluebtn">Скачать акт</button>
                </div>
            </article>
            <article class="newcart__item">
                <div class="newcart__img newcart__save lazy">
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                </div>
                <div class="newcart__topbtn">
                    <button class="newcart__edit">
                        редактировать
                    </button>
                    <button class="newcart__delete">
                        удалить
                    </button>
                </div>
                <h3 class="newcart__title">Mercedes-Benz E-klasse AMG 2019 </h3>
                <div class="newcart__type s-between">
                    Легковой автомобиль <span class="newcart__repeat">Повтор</span>
                </div>
                <div class="newcart__vin s-between">
                    Vin: <span class="newcart__vinnum">Z94G2811BJR094175</span>
                </div>
                <div class="newcart__numberwrap s-between">
                    Гос. номер: <span class="newcart__number">а211во198</span>
                </div>
                <div class="newcart__dd">
                    <div class="newcart__btn"><span class="newcart__btntitle">Заявка:</span> <span
                            class="newcart__status pink">Выдача</span></div>
                    <div class="newcart__des">
                        <ul class="newcart__deslist">
                            <li class="newcart__desitem">
                                <span>Дата прибытия:</span>
                                <strong>25.02.2021</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Промежуток:</span>
                                <strong>10:00 - 14:00</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата создания:</span>
                                <strong>25.02.2021</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата завершения:</span>
                                <strong>Не указана</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="newcart__dd">
                    <div class="newcart__btn"><span class="newcart__btntitle">Статус:</span> <span
                            class="newcart__status green">Хранение</span></div>
                    <div class="newcart__des">
                        <ul class="newcart__deslist">
                            <li class="newcart__desitem">
                                <span>Дата принятия:</span>
                                <strong>25.02.2021</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата выдачи:</span>
                                <strong>Не указана</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Сумма простоя:</span>
                                <strong>1,000.00 (2 дн.)</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                <a href="#" class="newcart__moreinfo">
                    Подробное описание
                </a>
            </article>
            <article class="newcart__item">
                <div class="newcart__img newcart__nosave lazy">
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                </div>
                <div class="newcart__topbtn">
                    <button class="newcart__edit">
                        редактировать
                    </button>
                    <button class="newcart__delete">
                        удалить
                    </button>
                </div>
                <h3 class="newcart__title">Mercedes-Benz E-klasse AMG 2019 </h3>
                <div class="newcart__type s-between">
                    Легковой автомобиль <span class="newcart__repeat">Повтор</span>
                </div>
                <div class="newcart__vin s-between">
                    Vin: <span class="newcart__vinnum">Z94G2811BJR094175</span>
                </div>
                <div class="newcart__numberwrap s-between">
                    Гос. номер: <span class="newcart__number">а211во198</span>
                </div>
                <div class="newcart__dd">
                    <div class="newcart__btn"><span class="newcart__btntitle">Статус:</span> <span
                            class="newcart__status blue">Осмотр</span></div>
                    <div class="newcart__des">
                        <ul class="newcart__deslist">
                            <li class="newcart__desitem">
                                <span>Дата принятия:</span>
                                <strong>25.02.2021</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата выдачи:</span>
                                <strong>Не указана</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Сумма простоя:</span>
                                <strong>1,000.00 (2 дн.)</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                <a href="#" class="newcart__moreinfo">
                    Подробное описание
                </a>
            </article>
            <article class="newcart__item">
                <div class="newcart__img newcart__nosave lazy">
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                </div>
                <div class="newcart__topbtn">
                    <button class="newcart__edit">
                        редактировать
                    </button>
                    <button class="newcart__delete">
                        удалить
                    </button>
                </div>
                <h3 class="newcart__title">Mercedes-Benz E-klasse AMG 2019 </h3>
                <div class="newcart__type s-between">
                    Легковой автомобиль <span class="newcart__repeat">Повтор</span>
                </div>
                <div class="newcart__vin s-between">
                    Vin: <span class="newcart__vinnum">Z94G2811BJR094175</span>
                </div>
                <div class="newcart__numberwrap s-between">
                    Гос. номер: <span class="newcart__number">а211во198</span>
                </div>
                <div class="newcart__dd">
                    <div class="newcart__btn"><span class="newcart__btntitle">Статус:</span> <span
                            class="newcart__status pink">Отклонено</span></div>
                    <div class="newcart__des">
                        <ul class="newcart__deslist">
                            <li class="newcart__desitem">
                                <span>Дата принятия:</span>
                                <strong>25.02.2021</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата выдачи:</span>
                                <strong>Не указана</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Сумма простоя:</span>
                                <strong>1,000.00 (2 дн.)</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                <a href="#" class="newcart__moreinfo">
                    Подробное описание
                </a>
            </article>
            <article class="newcart__item">
                <div class="newcart__img newcart__nosave lazy">
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                    <div class="newcart__imgwrap">
                        <img src="./image/amg.jpg" alt="">
                    </div>
                </div>
                <div class="newcart__topbtn">
                    <button class="newcart__edit">
                        редактировать
                    </button>
                    <button class="newcart__delete">
                        удалить
                    </button>
                </div>
                <h3 class="newcart__title">Mercedes-Benz E-klasse AMG 2019 </h3>
                <div class="newcart__type s-between">
                    Легковой автомобиль <span class="newcart__repeat">Повтор</span>
                </div>
                <div class="newcart__vin s-between">
                    Vin: <span class="newcart__vinnum">Z94G2811BJR094175</span>
                </div>
                <div class="newcart__numberwrap s-between">
                    Гос. номер: <span class="newcart__number">а211во198</span>
                </div>
                <div class="newcart__dd">
                    <div class="newcart__btn"><span class="newcart__btntitle">Заявка:</span> <span
                            class="newcart__status blue">Постановка</span></div>
                    <div class="newcart__des">
                        <ul class="newcart__deslist">
                            <li class="newcart__desitem">
                                <span>Дата прибытия:</span>
                                <strong>25.02.2021</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Промежуток:</span>
                                <strong>10:00 - 14:00</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата создания:</span>
                                <strong>25.02.2021</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата завершения:</span>
                                <strong>Не указана</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                <a href="#" class="newcart__moreinfo">
                    Подробное описание
                </a>
                <div class="newcart__confirmbtn">
                    <button class="newcart__accept">Принять</button>
                    <button class="newcart__deny">Отказать</button>
                </div>
            </article>--}}
        </div>
    </div>
</section>
<div class="newpopup">
    <div class="newpopup__main">
        <div class="newpopup__close"></div>
        <div class="newpopup__top d-flex">
            <img src="./image/amg.jpg" alt="" class="newpopup__img">
            <div class="newpopup__left">
                <h3 class="newcart__title">Mercedes-Benz E-klasse MG 2019 <span class="newcart__repeat">Повтор</span></h3>
                <ul class="newpopup__ul">
                    <li>
                            <span>
                                <span>Партнёр:</span>
                            </span>
                        <span>
                                <span>Росгосстрах СПб ПАО СК</span>
                            </span>
                    </li>
                    <li>
                            <span>
                                <span>VIN:</span>
                            </span>
                        <span>
                                <span>Z94G2811BJR094175</span>
                            </span>
                    </li>
                    <li>
                            <span>
                                <span>Гос. номер:</span>
                            </span>
                        <span>
                                <span>а211во198</span>
                            </span>
                    </li>
                    <li>
                            <span>
                                <span> Номер Убытка/Договора:</span>
                            </span>
                        <span>
                                <span>002AT-20/0200285, 002AS21-004489</span>
                            </span>
                    </li>
                </ul>
            </div>
            <div class="newpopup__right">
                <ul class="newpopup__statusinfo">
                    <li>
                            <span>
                                <span>Статус:</span>
                            </span>
                        <span>
                                <span class="statusgreen">Хранение</span>
                            </span>
                    </li>
                    <li>
                            <span>
                                <span>Хранение</span>
                            </span>
                        <span>
                                <span>25.02.2021</span>
                            </span>
                    </li>
                    <li>
                            <span>
                                <span>Дата выдачи:</span>
                            </span>
                        <span>
                                <span>Не указана</span>
                            </span>
                    </li>
                    <li>
                            <span>
                                <span>Сумма перестоя:</span>
                            </span>
                        <span>
                                <span>1,000.00 (2 дн.)</span>
                            </span>
                    </li>
                </ul>
            </div>
            <div class="newpopup__list d-flex">
                <div class="newpopup__item">
                    <div class="newpopup__data dsactive">
                        <h3>Системные данные</h3>
                        <ul class="newpopup__ul">
                            <li>
                                    <span>
                                        <span>Стоянка:</span>
                                    </span>
                                <span>
                                        <span>Лужнецкая ТТК</span>
                                    </span>
                            </li>
                            <li>
                                    <span>
                                        <span>Принял:</span>
                                    </span>
                                <span>
                                        <span>storage.spb</span>
                                    </span>
                            </li>
                            <li>
                                    <span>
                                        <span>Выдал:</span>
                                    </span>
                                <span>
                                        <span>storage.spb</span>
                                    </span>
                            </li>
                        </ul>
                    </div>
                    <div class="newpopup__data dsactive">
                        <h3>Административные данные</h3>
                        <ul class="newpopup__ul">
                            <li>
                                    <span>
                                        <span>ФИО доставщика:</span>
                                    </span>
                                <span>
                                        <span>Иванов Иван Иванович</span>
                                    </span>
                            </li>
                            <li>
                                <span><span>Телефон доставщика:</span></span>
                                <span>
                                        <span>+7 (999) 999-99-99</span>
                                    </span>
                            </li>
                        </ul>
                    </div>
                    <div class="newpopup__data dsactive">
                        <h3>Комментарий</h3>
                        <div class="newpopup__ul newpopup__coments">
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ad, placeat? Blanditiis
                            laudantium, amet, sunt rerum consequatur impedit doloremque, officiis hic est quod autem
                            neque omnis officia earum reprehenderit facere ab.
                        </div>
                    </div>
                </div>
                <div class="newpopup__item">
                    <div class="newpopup__data dsactive">
                        <h3>Об автомобиле</h3>
                        <ul class="newpopup__ul">
                            <li>
                                    <span>
                                        <span>ПТС:</span>
                                    </span>
                                <span>
                                        <span>78 УТ 611693</span>
                                    </span>
                            </li>
                            <li>
                                    <span>
                                        <span>Тип ПТС:</span>
                                    </span>
                                <span>
                                        <span>Оригинал</span>
                                    </span>
                            </li>
                            <li>
                                    <span>
                                        <span>СТС:</span>
                                    </span>
                                <span>
                                        <span>78 29 975903</span>
                                    </span>
                            </li>
                            <li>
                                    <span>
                                        <span>Пробег:</span>
                                    </span>
                                <span>
                                        <span>25 000</span>
                                    </span>
                            </li>
                            <li>
                                    <span>
                                        <span>Кол-во владельцев:</span>
                                    </span>
                                <span>
                                        <span>3 и более</span>
                                    </span>
                            </li>
                            <li>
                                    <span>
                                        <span>Кол-во ключей:</span>
                                    </span>
                                <span>
                                        <span>2</span>
                                    </span>
                            </li>
                        </ul>
                    </div>
                    <div class="newpopup__data dsactive">
                        <h3>Техническое состояние</h3>
                        <ul class="newpopup__ul">
                            <li>
                                    <span>
                                        <span>Электроника:</span>
                                    </span>
                                <span>
                                        <span>Неправильные команды электроники</span>
                                    </span>
                            </li>
                            <li>
                                    <span>
                                        <span>Трансмиссия:</span>
                                    </span>
                                <span>
                                        <span>Повышенный шум при переключении</span>
                                    </span>
                            </li>
                            <li>
                                    <span>
                                        <span>Двигатель:</span>
                                    </span>
                                <span>
                                        <span>
                                            Дымность двигателя (густой белый, сизый, черный), Повышенный стук и шум
                                            при работе двигателя
                                        </span>
                                    </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="newpopup__item">
                    <div class="newpopup__data dsactive">
                        <h3>Повреждения кузова</h3>
                        <ul class="newpopup__ul">
                            <li>
                                    <span>
                                        <span>Переднее левое крыло:</span>
                                    </span>
                                <span>
                                        <span>На замену, Скол/царапина</span>
                                    </span>
                            </li>
                            <li>
                                    <span>
                                        <span>Переднее правое крыло:</span>
                                    </span>
                                <span>
                                        <span>Вмятина, на замену</span>
                                    </span>
                            </li>
                            <li>
                                    <span>
                                        <span>Дверь багажника:</span>
                                    </span>
                                <span>
                                        <span>
                                            Следы ремонта, Вмятина
                                        </span>
                                    </span>
                            </li>
                        </ul>
                    </div>
                    <div class="newpopup__data dsactive">
                        <h3>Повреждения салона</h3>
                        <ul class="newpopup__ul">
                            <li>
                                    <span>
                                        <span>Торпедо:</span>
                                    </span>
                                <span>
                                        <span>Потёртость</span>
                                    </span>
                            </li>
                            <li>
                                    <span>
                                        <span>Пол:</span>
                                    </span>
                                <span>
                                        <span>Порез, Прожог, Грязь</span>
                                    </span>
                            </li>
                            <li>
                                    <span>
                                        <span>Переднее правое
                                            сидение:</span>
                                    </span>
                                <span>
                                        <span>
                                            Грязь, Потёртость
                                        </span>
                                    </span>
                            </li>
                            <li>
                                    <span>
                                        <span>Заднее сидение:</span>
                                    </span>
                                <span>
                                        <span>
                                            Порез
                                        </span>
                                    </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection