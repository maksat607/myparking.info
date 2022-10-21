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
                    <select name="partner_id[]" class="select-multiple" multiple="multiple">
                        <option hidden value="" disabled>Выберите партнера</option>
                        @foreach($partners as $partner)
                            <option @if(in_array($partner->id,request()->query('partner_id')??[]   )) selected
                                    @endif value="{{ $partner->id }}">{{ $partner->shortname }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="field-style">
                    <span>Стоянка</span>
                    <select name="parking_id[]" class="select-multiple" multiple="multiple">
                        <option hidden value="" disabled>Выберите стоянку</option>
                        @foreach($parking as $p)
                            <option @if(in_array($p->id,request()->query('parking_id')??[])) selected
                                    @endif value="{{ $p->id }}">{{ $p->title }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="field-style">
                    <span>Статус</span>
                    <select name="status_id" class="page-select">
                        <option value="instorage">Хранение</option>
                        <option value="arrived">Были на Хранение</option>
                        <option @if(request()->query('status_id') == 'issued') selected @endif value="issued">Выданные
                        </option>
                    </select>
                </label>
                <label class="field-style">
                    <span>Даты отчёта</span>
                    <input type="text" class="date-range input" name="dates" value="">
                    @push('scripts')
                        const dateReportRange = '{{ request()->query('dates', '') }}';
                    @endpush
                </label>
                <label class="chosen-post">
                    <input type="checkbox" @if(request()->get('favorite')) checked @endif
                    name="favorite" class="checkbox-chosen">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2.5C12.3788 2.5 12.7251 2.714 12.8945 3.05279L15.4734 8.2106L21.144 9.03541C21.5206 9.0902 21.8335 9.35402 21.9511 9.71599C22.0687 10.078 21.9706 10.4753 21.6981 10.741L17.571 14.7649L18.4994 20.4385C18.5608 20.8135 18.4043 21.1908 18.0957 21.4124C17.787 21.6339 17.3794 21.6614 17.0438 21.4834L12 18.8071L6.95624 21.4834C6.62062 21.6614 6.21306 21.6339 5.9044 21.4124C5.59573 21.1908 5.4393 20.8135 5.50065 20.4385L6.42906 14.7649L2.30193 10.741C2.02942 10.4753 1.93136 10.078 2.04897 9.71599C2.16658 9.35402 2.47946 9.0902 2.85609 9.03541L8.5267 8.2106L11.1056 3.05279C11.275 2.714 11.6213 2.5 12 2.5ZM12 5.73607L10.082 9.57221C9.93561 9.86491 9.65531 10.0675 9.33147 10.1146L5.14842 10.723L8.19813 13.6965C8.43182 13.9243 8.53961 14.2519 8.4869 14.574L7.80004 18.7715L11.5313 16.7917C11.8244 16.6361 12.1756 16.6361 12.4687 16.7917L16.2 18.7715L15.5132 14.574C15.4604 14.2519 15.5682 13.9243 15.8019 13.6965L18.8516 10.723L14.6686 10.1146C14.3448 10.0675 14.0645 9.86491 13.9181 9.57221L12 5.73607Z"
                            fill="#536E9B"/>
                        <path d="M9 9L3.5 10L7.5 14.5L7 20.5L12 18L17.5 20.5L16.5 14L21 10L15 9L12 4L9 9Z" fill="transparent"/>
                    </svg>
                </label>
            </div>
            <button type="submit" class="btn btn-primary ml-auto">Показать</button>
            <a href="{{ route('report.csv-by-partner', request()->query()) }}" class="btn btn-dowenload"></a>
            <label class="field-style pl-5">Всего: {{ count($data['data']) -1 }}</label>
        </form>

    </div>

    <div class="container">
        <div class="inner-page">

            <table class="table fs-13 sortable horizontal-scrollable vertical-scrollable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Партнер</th>
                    <th>Город</th>
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
                <?php $r = 0; ?>
                @foreach($data['data'] as $application)
                    <tr class="car-show-modal" data-app-id="{{ $application['id'] }}">
                        <input type="hidden" id="appId" value="{{ $application['id'] }}">
                        <td class="tr-id">@if($application['id']) {{ $loop->iteration }} @endif</td>
                        <td style="word-break: keep-all">{{ $application['id'] }}</td>
                        <td>{{ $application['partner'] }}</td>
                        <td>{{ $application['parking'] }}</td>
                        <td>{{ $application['external_id'] }}</td>
                        <td>{{ $application['car_mark_name'] }}</td>
                        <td>{{ $application['car_model_name'] }}</td>
                        <td>{{ $application['year'] }}</td>
                        <td>{{ $application['car_type_name'] }}</td>
                        <td>{{ $application['vin'] }}</td>
                        <td>{{ $application['license_plate'] }}</td>
                        <td>{{ $application['status_name'] }}</td>
                        <td>{{ optional($application)['formated_arrived_at'] }}</td>
                        <td>{{ optional($application)['formated_issued_at'] }}</td>
                        <td>{{ $application['parked_days'] }}</td>
                        <td style="word-break: keep-all">{{ $application['parked_price'] }}</td>
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
