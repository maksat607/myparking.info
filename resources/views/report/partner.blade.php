@extends('layouts.app')

@section('content')

    <div class="container page-head-wrap">
        <div class="page-head">
            <div class="page-head__top d-flex align-items-center">
                <h1>{{ $title }}</h1>
            </div>
        </div>
        <form action="{{ route('report.report-by-partner') }}" method="GET" class="filter">
            <div class="d-flex align-items-center mb-3">
                <label class="field-style">
                    <span>Партнер</span>
                    <select name="partner_id" class="page-select">
                        <option hidden value="">Выберите партнера</option>
                        @foreach($partners as $partner)
                            <option @if(request()->query('partner_id') == $partner->id) selected @endif value="{{ $partner->id }}">{{ $partner->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="field-style">
                    <span>Стоянка</span>
                    <select name="parking_id" class="page-select">
                        <option hidden value="">Выберите стоянку</option>
                        @foreach($parking as $p)
                            <option @if(request()->query('parking_id') == $p->id) selected @endif value="{{ $p->id }}">{{ $p->title }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="field-style">
                    <span>Статус</span>
                    <select name="status_id" class="page-select">
                        <option value="arrived">Были на Хранение</option>
                        <option @if(request()->query('status_id') == 'issued') selected @endif value="issued">Выданные</option>
                    </select>
                </label>
                <label class="field-style">
                    <span>Даты отчёта</span>
                    <input type="text" class="date-range input" name="dates" value="">
                    @push('scripts')
                        const dateReportRange = '{{ request()->query('dates', '') }}';
                    @endpush
                </label>
            </div>
            <button type="submit" class="btn btn-primary ml-auto">Показать</button>
            <a href="{{ route('report.csv-by-partner', request()->query()) }}" class="btn btn-dowenload"></a>
        </form>
    </div>

    <div class="container">
        <div class="inner-page">
            <table class="table fs-13 sortable">
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
                    <tr class="car-show-modal" data-app-id="{{ $application->id }}">
                        <td class="tr-id">{{ $loop->iteration }}</td>
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

@endsection
@push('style')
    <style>
        /* Sortable tables */
        table.sortable thead {
            cursor: pointer;
        }
    </style>
@endpush

@push('script-includes')
    <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
@endpush
