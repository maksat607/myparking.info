@extends('layouts.app')

@section('content')
    <section class="setting">
        <div class="wrapper">
            <h2 class="setting__title">{{ $title }}</h2>
            <div class="setting__bar d-flex">
                <form action="{{ route('report.report-by-partner') }}" method="GET">
                    <div class="newfilter__search d-flex">
                        <select name="partner_id" class="newfilter__select">
                            <option hidden value="">Выберите партнера</option>
                            @foreach($partners as $partner)
                            <option @if(request()->query('partner_id') == $partner->id) selected @endif value="{{ $partner->id }}">{{ $partner->name }}</option>
                            @endforeach
                        </select>
                        <select name="parking_id" class="newfilter__select">
                            <option hidden value="">Выберите стоянку</option>
                            @foreach($parking as $p)
                                <option @if(request()->query('parking_id') == $p->id) selected @endif value="{{ $p->id }}">{{ $p->title }}</option>
                            @endforeach
                        </select>
                        <select name="status_id" class="newfilter__select">
                            <option value="arrived">Были на Хранение</option>
                            <option @if(request()->query('status_id') == 'issued') selected @endif value="issued">Выданные</option>
                        </select>

                        <input type="text" class="date-range input" name="dates" value="">
                        @push('scripts')
                            const dateReportRange = '{{ request()->query('dates', '') }}';
                        @endpush
                    </div>
                    <div class="setting__wrap-btn">
                        <button class="btn blue-btn w-auto-btn" type="submit">Показать</button>
                        <a href="{{ route('report.csv-by-partner', request()->query()) }}" class="btn blue-btn w-auto-btn">CSV</a>
                    </div>
                </form>
            </div>
            <div class="table-response">
                <table class="setting__table low">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Ном. убытка</th>
                        <th>Марка</th>
                        <th>Модель</th>
                        <th>Год</th>
                        <th>Тип авто</th>
                        <th>VIN</th>
                        <th>Гос. номер</th>
                        <th>Статус</th>
                        <th>Постановка</th>
                        <th>Выдано</th>
                        <th>Кол-во дней</th>
                        <th>Сумма</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data['data'] as $application)
                    <tr>
                        <td>1</td>
                        <td>{{ $application->external_id }}</td>
                        <td>{{ $application->car_mark_name }}</td>
                        <td>{{ $application->car_model_name }}</td>
                        <td>{{ $application->year }}</td>
                        <td>{{ $application->car_type_name }}</td>
                        <td>{{ $application->vin }}</td>
                        <td>{{ $application->license_plate }}</td>
                        <td>{{ $application->status_name }}</td>
                        <td>{{ $application->formated_arrived_at }}</td>
                        <td>{{ $application->formated_issued_at }}</td>
                        <td>{{ $application->parked_days }}</td>
                        <td>{{ $application->parked_price }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
