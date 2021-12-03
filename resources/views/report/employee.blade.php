@extends('layouts.app')

@section('content')
    <section class="setting">
        <div class="wrapper">
            <h2 class="setting__title">{{ $title }}</h2>
            <div class="setting__bar d-flex">
                <form action="{{ route('report.report-by-employee') }}" method="GET">
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
                        <a href="{{ route('report.csv-by-employee', request()->query()) }}" class="btn blue-btn w-auto-btn">CSV</a>
                    </div>
                </form>
            </div>
            <div class="table-response">
                <table class="setting__table low">
                    <thead>
                    <tr>
                        <th>Сотрудник</th>
                        <th>Принял</th>
                        <th>Показал</th>
                        <th>Выдал</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data['data'] as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->accepted_number }}</td>
                        <td>{{ $user->reviewed_number }}</td>
                        <td>{{ $user->issued_number }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
