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
                    @foreach($applications as $application)
                        <tr>
                            <td>{{ $application->car_title }}</td>
                            <td>{{ $application->vin }}</td>
                            <td>{{ $application->license_plate }}</td>
                            @if($application->acceptions)
                            <td class="statuspink">Постановка</td>
                            @elseif($application->issuance)
                                <td class="statuspink">Выдача</td>
                            @elseif($application->viewRequests->last())
                                <td class="statuspink">Осмотр</td>
                            @else
                                <td class="statuspink">Нет заявок</td>
                            @endif
                            <td class="status{{ $application->status->getColorClass() }}">{{$application->status->name}}</td>
                            <td>
                                <button class="newcart__btnpop"></button>
                                <div class="newcart__setting">
                                    @if($application->status->code == 'storage')
                                        @can('application_issue')
                                            <a href="{{ route('application.issuance.create', ['application' => $application->id]) }}" >Выдать</a>
                                        @endcan

                                        @can('application_to_issue')
                                            @if($application->issuance)
                                                <a href="{{ route('issue_requests.edit', ['issue_request' => $application->issuance->id]) }}" >Выдача</a>
                                            @else
                                                <a href="{{ route('issue_requests.create', ['application' => $application->id]) }}" >Выдача</a>
                                            @endif
                                        @endcan

                                        @can('application_to_inspection')
                                            <a href="{{ route('view_requests.create', ['application' => $application->id]) }}" >Осмотр</a>
                                        @endcan
                                    @endif

                                    @hasanyrole('Admin|Manager')
                                    <a href="">Скачать акт</a>
                                    @endhasanyrole

                                    <a href="{{ route('applications.edit', [
                                                    'application' => $application->id,
                                                    ]) }}">Редактировать</a>

                                    <a href="#" class="newcart__del"
                                       onclick="if( confirm('Delete it?') ) {
                                           event.preventDefault();
                                           document.getElementById('deleteApp{{ $application->id }}').submit(); return true }">Удалить</a>

                                    <form id="deleteApp{{ $application->id }}"
                                          method="POST"
                                          action="{{ route('applications.destroy', ['application' => $application->id]) }}">
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
            {{ $applications->links() }}
        </div>
    </section>
@endsection
