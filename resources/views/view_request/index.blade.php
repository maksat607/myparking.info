@extends('layouts.app')

@section('content')
    @include('applications.menu.top_menu_filter')
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
