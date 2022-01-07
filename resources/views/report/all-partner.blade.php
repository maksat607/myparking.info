@extends('layouts.app')

@section('content')


    <div class="container page-head-wrap">
        <div class="page-head">
            <div class="page-head__top d-flex align-items-center">
                <h1>{{ $title }}</h1>
            </div>
        </div>
        <form action="{{ route('report.report-all-partner') }}" method="GET" class="filter d-flex align-items-center">
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
                <span>Даты отчёта</span>
                <input type="text" class="date-range input" name="dates" value="">
                @push('scripts')
                    const dateReportRange = '{{ request()->query('dates', '') }}';
                @endpush
            </label>
            <button type="submit" class="btn btn-primary ml-auto">Показать</button>
            <a href="{{ route('report.csv-all-partner', request()->query()) }}" class="btn-dowenload"></a>
        </form>
    </div>

    <div class="container">
        <div class="inner-page">
            <table class="table fs-13">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Партнёр</th>
                    <th>Стоянка</th>
                    <th>Хранится</th>
                    <th>Выдано</th>
                    <th>Всего дней</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data['data'] as $application)

                    <tr>
                        <td class="tr-id">{{ $loop->iteration }}</td>
                        <td>{{ $application->partner_name }}</td>
                        <td>{{ $application->parking_name }}</td>
                        <td>{{ $application->arrived }}</td>
                        <td>{{ $application->issued }}</td>
                        <td>{{ $application->total_days }}</td>
                    </tr>

                @endforeach
                <tr>
                    <td class="tr-id"></td>
                    <td>{{ $total->partner_name }}</td>
                    <td>{{ $total->parking_name }}</td>
                    <td>{{ $total->arrived }}</td>
                    <td>{{ $total->issued }}</td>
                    <td>{{ $total->total_days }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
