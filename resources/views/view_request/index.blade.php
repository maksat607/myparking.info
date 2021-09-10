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
                    <li class="newtopbar__item">
                        <a href="#" class="newtopbar__link">Все</a>
                    </li>
                    <li class="newtopbar__item active">
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
                <button class="newfilter__sortcol newbtn active">Колонки</button>
                <button class="newfilter__sortrow newbtn">Строки</button>
            </div>
        </div>
    </div>
    <section class="newcart">
        <div class="wrapper">
            <div class="newcart__tablewrap">
                <table class="newcart__col">
                    <thead class="newcart__header">
                    <tr>
                        <th class="active">Называние <i class="fa fa-filter" aria-hidden="true"></i>
                            <div class="newcart__tablefilter">
                                <input type="text" placeholder="Название">
                            </div>
                        </th>
                        <th>VIN <i class="fa fa-filter" aria-hidden="true"></i></th>
                        <th>Гос. номер <i class="fa fa-filter" aria-hidden="true"></i></th>
                        <th class="active">Заявка <i class="fa fa-filter" aria-hidden="true"></i>
                            <div class="newcart__tablefilter">
                                <select name="" id="">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                        </th>
                        <th>Статус <i class="fa fa-filter" aria-hidden="true"></i></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="newcart__body">
                    @foreach($viewRequests as $viewRequest)
                    <tr>
                        <td>{{ $viewRequest->application->car_title }}</td>
                        <td>{{ $viewRequest->application->vin }}</td>
                        <td>{{ $viewRequest->application->license_plate }}</td>
                        @if($viewRequest->application->acceptions)
                        <td class="statuspink">Постановка</td>
                        {{--@elseif($viewRequest->application->issuance)
                        <td class="statuspink">Выдача</td>--}}
                        @endif
                        <td class="statuspink">Осмотр</td>
                        <td class="status{{ $viewRequest->application->status->getColorClass() }}">{{$viewRequest->application->status->name}}</td>
                        <td>
                            <button class="newcart__btnpop"></button>
                            <div class="newcart__setting">
                                <a href="">Выдача</a>
                                <a href="">Скачать акт</a>
                                <a href="">Подробное описание</a>
                                <a href="{{ route('view_requests.edit', [
                                                    'view_request' => $viewRequest->id,
                                                    ]) }}">Редактировать</a>
                                <a href="#" class="newcart__del"
                                    onclick="if( confirm('Delete it?') ) {
                                        event.preventDefault();
                                        document.getElementById('deleteViewRequest{{ $viewRequest->id }}').submit(); return true }">Удалить</a>

                                <form id="deleteViewRequest{{ $viewRequest->id }}"
                                      method="POST"
                                      action="{{ route('view_requests.destroy', ['view_request' => $viewRequest->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
