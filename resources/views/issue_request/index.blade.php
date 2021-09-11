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
                    @foreach($issueRequests as $issueRequest)
                        <tr>
                            <td>{{ $issueRequest->application->car_title }}</td>
                            <td>{{ $issueRequest->application->vin }}</td>
                            <td>{{ $issueRequest->application->license_plate }}</td>
                            @if($issueRequest->application->acceptions)
                                <td class="statuspink">Постановка</td>
                                {{--@elseif($issueRequest->application->issuance)
                                <td class="statuspink">Выдача</td>--}}
                            @endif
                            <td class="statuspink">Выдача</td>
                            <td class="status{{ $issueRequest->application->status->getColorClass() }}">{{$issueRequest->application->status->name}}</td>
                            <td>
                                <button class="newcart__btnpop"></button>
                                <div class="newcart__setting">
                                    <a href="">Выдача</a>
                                    <a href="">Скачать акт</a>
                                    <a href="">Подробное описание</a>
                                    <a href="{{ route('view_requests.edit', [
                                                    'view_request' => $issueRequest->id,
                                                    ]) }}">Редактировать</a>
                                    <a href="#" class="newcart__del"
                                       onclick="if( confirm('Delete it?') ) {
                                           event.preventDefault();
                                           document.getElementById('deleteissueRequests{{ $issueRequest->id }}').submit(); return true }">Удалить</a>

                                    <form id="deleteissueRequests{{ $issueRequest->id }}"
                                          method="POST"
                                          action="{{ route('view_requests.destroy', ['view_request' => $issueRequest->id]) }}">
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
