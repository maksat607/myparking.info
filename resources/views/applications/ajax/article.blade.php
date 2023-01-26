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
        <button class="newcart__accept" id="issue" data-app-id="{{ $application->id }}">Принять</button>
        <button class="newcart__deny" id="deny" data-app-id="{{ $application->id }}">Отказать</button>
    </div>
@endif
