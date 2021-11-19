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
                        <th>Дата приезда <i class="fa fa-filter" aria-hidden="true"></i></th>
                        <th>Промежуток времени <i class="fa fa-filter" aria-hidden="true"></i></th>
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
                        <td>{{ $viewRequest->formated_arriving_at }}</td>
                        <td>{{ $viewRequest->formated_arriving_interval }}</td>
                        <td class="status{{ $viewRequest->application->status->getColorClass() }}">{{$viewRequest->application->status->name}}</td>
                        <td>
                            <button class="newcart__btnpop"></button>
                            <div class="newcart__setting">
                                @if($viewRequest->application->status->code == 'storage')
                                    @can('application_issue')
                                        <a href="{{ route('application.issuance.create', ['application' => $viewRequest->application->id]) }}" >Выдать</a>
                                    @endcan
                                @endif
                                <a href="">Скачать акт</a>
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
