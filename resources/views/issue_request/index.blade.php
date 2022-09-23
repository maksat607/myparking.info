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
                    @foreach($issueRequests as $issueRequest)
                        <tr>
                            <td>{{ $issueRequest->application->car_title }}</td>
                            <td>{{ $issueRequest->application->vin }}</td>
                            <td>{{ $issueRequest->application->license_plate }}</td>
                            <td>{{ $issueRequest->formated_arriving_at }}</td>
                            <td>{{ $issueRequest->formated_arriving_interval }}</td>
                            <td class="status{{ $issueRequest->application->status->getColorClass() }}">{{$issueRequest->application->status->name}}</td>
                            <td>
                                <button class="newcart__btnpop"></button>
                                <div class="newcart__setting">
                                    @if($issueRequest->application->status->code == 'storage')
                                        @can('application_issue')
                                            <a href="{{ route('application.issuance.create', ['application' => $issueRequest->application->id]) }}">Выдать</a>
                                        @endcan
                                        @can('application_to_inspection')
                                            <a href="{{ route('view_requests.create', ['application' => $issueRequest->application->id]) }}">Осмотр</a>
                                        @endcan
                                    @endif
                                    <a href="">Скачать акт</a>
                                    <a href="{{ route('issue_requests.edit', [
                                                    'issue_request' => $issueRequest->id,
                                                    ]) }}">Редактировать</a>
                                    <a href="#" class="newcart__del"
                                       onclick="if( confirm('Delete it?') ) {
                                           event.preventDefault();
                                           document.getElementById('deleteIssueRequests{{ $issueRequest->id }}').submit(); return true }">Удалить</a>

                                    <form id="deleteIssueRequests{{ $issueRequest->id }}"
                                          method="POST"
                                          action="{{ route('issue_requests.destroy', ['issue_request' => $issueRequest->id]) }}">
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
