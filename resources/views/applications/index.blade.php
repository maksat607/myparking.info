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
                    @can('application_update')
                    <a class="newcart__edit" href="{{ route('applications.edit', ['application' => $application->id]) }}">
                        редактировать
                    </a>
                    @endcan
                    @can('application_delete')
                    <a class="newcart__delete" href="#"
                       onclick="if( confirm('Delete it?') ) { event.preventDefault();
                           document.getElementById('deleteApp{{ $application->id }}').submit(); return true }">
                        удалить
                    </a>
                    <form id="deleteApp{{ $application->id }}" method="POST"
                          action="{{ route('applications.destroy', ['application' => $application->id]) }}">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endcan
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
                {{--@elseif($application->issuance) --}}{{-- Исправить заявку --}}{{--
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
                    </div>--}}
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
                <a href="#" class="newcart__moreinfo have-comments" data-app-id="{{ $application->id }}">
                    Подробное описание
                </a>

                @if($application->acceptions)
                    @can('application_to_accepted')
                    <div class="newcart__confirmbtn">
                        <button class="newcart__accept issue" data-app-id="{{ $application->id }}">Принять</button>
                        <button class="newcart__deny deny" data-app-id="{{ $application->id }}">Отказать</button>
                    </div>
                    @endcan
                @elseif($application->status->code == 'storage')
                    <div class="newcart__confirmbtn">
                        @can('application_to_issue')
                        <button class="newcart__bluebtn">Выдача</button>
                        @endcan
                        @can('application_to_inspection')
                        <a href="{{ route('view_requests.create', ['application' => $application->id]) }}" class="newcart__bluebtn">Осмотр</a>
                        @endcan
                        <button class="newcart__bluebtn">Скачать акт</button>
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

        </div>
    </div>
</div>
@endsection
