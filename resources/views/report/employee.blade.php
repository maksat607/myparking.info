@extends('layouts.app')

@section('content')


    <div class="container page-head-wrap">
        <div class="page-head">
            <div class="page-head__top d-flex align-items-center">
                <h1>{{ $title }}</h1>
            </div>
        </div>
        <form action="{{ route('report.report-by-employee') }}" method="GET" class="filter d-flex align-items-center">
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
            <a href="{{ route('report.csv-by-employee', request()->query()) }}" class="btn-dowenload"></a>
        </form>
    </div>

    <div class="container">
        <div class="inner-page">
            <table class="table fs-13">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Сотрудник</th>
                    <th>Принял</th>
                    <th>Показал</th>
                    <th>Выдал</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data['data'] as $user)
                    <tr>
                        <td class="tr-id">{{ $loop->iteration }}</td>
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
@endsection
