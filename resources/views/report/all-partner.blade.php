@extends('layouts.app')

@section('content')
    <section class="setting">
    <div class="wrapper">
        <h2 class="setting__title">{{ $title }}</h2>
        <div class="setting__bar d-flex">
            <form action="{{ route('report.report-all-partner') }}" method="GET">
            <div class="newfilter__search d-flex">
                <select name="parking_id" class="newfilter__select">
                    <option hidden value="">Выберите стоянку</option>
                    @foreach($parking as $p)
                        <option @if(request()->query('parking_id') == $p->id) selected @endif value="{{ $p->id }}">{{ $p->title }}</option>
                    @endforeach
                </select>
                <input type="text" class="date-range input" name="dates" value="">
                @push('scripts')
                    const dateReportRange = '{{ request()->query('dates', '') }}';
                @endpush
            </div>
            <div class="setting__wrap-btn">
                <button class="btn blue-btn w-auto-btn" type="submit">Показать</button>
                <a href="{{ route('report.csv-all-partner', request()->query()) }}" class="btn blue-btn w-auto-btn">CSV</a>
            </div>
            </form>
        </div>
        <div class="table-response">
            <table class="setting__table low">
                <thead>
                <tr>
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
                    <td>{{ $application->partner_name }}</td>
                    <td>{{ $application->parking_name }}</td>
                    <td>{{ $application->arrived }}</td>
                    <td>{{ $application->issued }}</td>
                    <td>{{ $application->total_days }}</td>
                </tr>

                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>{{ $total->partner_name }}</td>
                        <td>{{ $total->parking_name }}</td>
                        <td>{{ $total->arrived }}</td>
                        <td>{{ $total->issued }}</td>
                        <td>{{ $total->total_days }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</section>
@endsection
