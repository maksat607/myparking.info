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
                    @foreach($acceptingRequests as $acceptingRequest)
                        <tr>
                            <td>{{ $acceptingRequest->application->car_title }}</td>
                            <td>{{ $acceptingRequest->application->vin }}</td>
                            <td>{{ $acceptingRequest->application->license_plate }}</td>
                            @if($acceptingRequest->application->acceptions)
                                <td class="statusblue">Постановка</td>
                            @endif
                            <td class="status{{ $acceptingRequest->application->status->getColorClass() }}">{{$acceptingRequest->application->status->name}}</td>
                            <td>
                                <button class="newcart__btnpop"></button>
                                <div class="newcart__setting">
                                    @if($acceptingRequest->application->acceptions)
                                        @can('application_to_accepted')
                                            <a href="{{ route('applications.edit', ['application' => $acceptingRequest->application->id]) }}">
                                                Принять
                                            </a>
                                            <a href="" data-app-id="{{ $acceptingRequest->application->id }}">Отказать</a>
                                        @endcan
                                    @endif
                                    <a href="{{ route('applications.edit', [
                                                    'application' => $acceptingRequest->application->id,
                                                    ]) }}">Редактировать</a>
                                    <a href="#" class="newcart__del"
                                       onclick="if( confirm('Delete it?') ) {
                                           event.preventDefault();
                                           document.getElementById('deleteApp{{ $acceptingRequest->application->id }}').submit(); return true }">Удалить</a>

                                    <form id="deleteApp{{ $acceptingRequest->application->id }}"
                                          method="POST"
                                          action="{{ route('applications.delete', ['application' => $acceptingRequest->application->id]) }}">
                                        @csrf
                                        @method('POST')
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
