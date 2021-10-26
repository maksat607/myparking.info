@extends('layouts.app')

@section('content')
@include('applications.menu.top_menu_filter')
<section class="newcart">
    <div class="wrapper">
        <div class="newcart__list d-flex">
            @foreach($applications as $application)
            <article class="newcart__item" id="application_{{ $application->id }}">
                <div class="newcart__wrapp @if($application->favorite) newcart__save @else newcart__nosave @endif">
                    <div class="newcart__img lazy">
                        @foreach($application->attachments as $attachment)
                        <div class="newcart__imgwrap">
                            <img src="{{ $attachment->thumbnail_url }}" alt="">
                        </div>
                        @endforeach
                    </div>
                    <div class="favorite" data-app-id="{{ $application->id }}"></div>
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
                    {{ $application->carType->name }}
                    @if($application->returned)
                    <span class="newcart__repeat">Повтор</span>
                    @endif
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
                @elseif($application->issuance)
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
                @if($application->viewRequests->last())
                    <div class="newcart__dd">
                        <div class="newcart__btn"><span class="newcart__btntitle">Заявка:</span>
                            <span class="newcart__status pink">Осмотр</span></div>
                        <div class="newcart__des">
                            <ul class="newcart__deslist">
                                <li class="newcart__desitem">
                                    <span>Дата осмотра:</span>
                                    <strong>{{ $application->viewRequests->last()->formated_arriving_at }}</strong>
                                </li>
                                <li class="newcart__desitem">
                                    <span>Промежуток:</span>
                                    <strong>{{ $application->viewRequests->last()->arriving_interval }}</strong>
                                </li>
                                <li class="newcart__desitem">
                                    <span>Дата создания:</span>
                                    <strong>{{ $application->viewRequests->last()->formated_created_at }}</strong>
                                </li>
                                <li class="newcart__desitem">
                                    <span>Дата завершения:</span>
                                    <strong>
                                        {{ $application->viewRequests->last()->formated_finished_at }}
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
                <a href="#" class="newcart__moreinfo have-comments" data-app-id="{{ $application->id }}">
                    Подробное описание
                </a>

                @if($application->acceptions)
                    @can('application_to_accepted')
                    <div class="newcart__confirmbtn">
                        <a href="{{ route('applications.edit', ['application' => $application->id]) }}"
                           class="newcart__accept issue">Принять</a>
                        <a href="{{ route('application.deny', ['application_id' => $application->id]) }}"
                           class="newcart__deny">Отказать</a>
                    </div>
                    @endcan
                @elseif($application->status->code == 'storage')
                    <div class="newcart__confirmbtn">
                        @can('application_issue')
                            <a href="{{ route('application.issuance.create', ['application' => $application->id]) }}" class="newcart__bluebtn">Выдать</a>
                        @endcan
                        @can('application_to_issue')
                            @if($application->issuance)
                                <a href="{{ route('issue_requests.edit', ['issue_request' => $application->issuance->id]) }}" class="newcart__bluebtn">Выдача</a>
                            @else
                                <a href="{{ route('issue_requests.create', ['application' => $application->id]) }}" class="newcart__bluebtn">Выдача</a>
                            @endif
                        @endcan
                        @can('application_to_inspection')
                        <a href="{{ route('view_requests.create', ['application' => $application->id]) }}" class="newcart__bluebtn">Осмотр</a>
                        @endcan
                        <button class="newcart__bluebtn">Скачать акт</button>
                    </div>
                @endif

            </article>
            @endforeach
        </div>
        {{ $applications->links() }}
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
