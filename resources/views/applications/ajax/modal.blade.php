<img src="{{ $application->attachments->first()->thumbnail_url }}" alt="" class="newpopup__img">
<div class="newpopup__left">
    <h3 class="newcart__title">{{ $application->car_title }} <span class="newcart__repeat">Повтор</span></h3>
    <ul class="newpopup__ul">
        <li>
            <span>
                <span>Партнёр:</span>
            </span>
            <span>
                <span>{{ $application->partner->name }}</span>
            </span>
        </li>
        <li>
            <span>
                <span>VIN:</span>
            </span>
            <span>
                <span>{{ $application->vin }}</span>
            </span>
        </li>
        <li>
            <span>
                <span>Гос. номер:</span>
            </span>
            <span>
                <span>{{ $application->license_plate }}</span>
            </span>
        </li>
        <li>
            <span>
                <span> Номер Убытка/Договора:</span>
            </span>
            <span>
                <span>{{ $application->external_id }}</span>
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
                <span class="status{{ $application->status->getColorClass() }}">{{$application->status->name}}</span>
            </span>
        </li>
        <li>
            <span>
                <span>Хранение</span>
            </span>
            <span>
                <span>{{ $application->formated_arrived_at }}</span>
            </span>
        </li>
        <li>
            <span>
                <span>Дата выдачи:</span>
            </span>
            <span>
                <span>{{ $application->formated_issued_at }}</span>
            </span>
        </li>
        <li>
            <span>
                <span>Сумма перестоя:</span>
            </span>
            <span>
                <span>{{ $application->parked_price_regular }} ({{ $application->parked_days_regular }} дн.)</span>
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
                        <span>{{ $application->parking->title }}</span>
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
                        <span>{{ $application->courier_fullname }}</span>
                    </span>
                </li>
                <li>
                    <span><span>Телефон доставщика:</span></span>
                    <span>
                        <span>{{ $application->courier_phone }}</span>
                    </span>
                </li>
            </ul>
        </div>
        <div class="newpopup__data dsactive">
            <h3>Комментарий</h3>
            <div class="newpopup__ul newpopup__coments">
                {{ $application->car_additional }}
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
                        <span>{{ $application->pts }}</span>
                    </span>
                </li>
                <li>
                    <span>
                        <span>Тип ПТС:</span>
                    </span>
                    <span>
                        <span>{{ $application->pts_type }}</span>
                    </span>
                </li>
                <li>
                    <span>
                        <span>СТС:</span>
                    </span>
                    <span>
                        <span>{{ $application->sts }}</span>
                    </span>
                </li>
                <li>
                    <span>
                        <span>Пробег:</span>
                    </span>
                    <span>
                        <span>{{ $application->milage }}</span>
                    </span>
                </li>
                <li>
                    <span>
                        <span>Кол-во владельцев:</span>
                    </span>
                    <span>
                        <span>@if($application->owner_number < 3) {{ $application->owner_number }} @else {{ $application->owner_number }} и более @endif</span>
                    </span>
                </li>
                <li>
                    <span>
                        <span>Кол-во ключей:</span>
                    </span>
                    <span>
                        <span>{{ $application->car_key_quantity }}</span>
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <div class="newpopup__item">
        <div class="newpopup__data dsactive">
            <h3>Техническое состояние</h3>
            <ul class="newpopup__ul">
                <li>
                    <span>
                        <span>Электроника:</span>
                    </span>
                    <span>
                        <span>@if(!is_null($application->condition_electric)) {{ implode(', ', $application->condition_electric) }} @endif</span>
                    </span>
                </li>
                <li>
                    <span>
                        <span>Трансмиссия:</span>
                    </span>
                    <span>
                        <span>@if(!is_null($application->condition_transmission)) {{ implode(', ', $application->condition_transmission) }} @endif</span>
                    </span>
                </li>
                <li>
                    <span>
                        <span>Двигатель:</span>
                    </span>
                    <span>
                        <span>
                            @if(!is_null($application->condition_engine)) {{ implode(', ', $application->condition_engine) }} @endif
                        </span>
                    </span>
                </li>
            </ul>
        </div>
        {{--<div class="newpopup__data dsactive">
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
        </div>--}}
    </div>
</div>
