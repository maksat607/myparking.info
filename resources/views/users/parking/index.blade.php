@extends('layouts.app')

@section('content')
    <section class="setting">
        <div class="wrapper">
            <h2 class="setting__title">{{ $title }}</h2>
            <div class="table-response">
                <table class="setting__table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('Title')</th>
                        <th scope="col">@lang('Region')</th>
                        <th scope="col">@lang('Address')</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($managerParkings as $parking)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>
                                <a
                                    href="{{ route('parkings.edit', ['parking' => $parking->id]) }}">{{ $parking->title }}</a>
                            </td>
                            <td>{{ $parking->code }}</td>
                            <td>{{ $parking->address }}</td>
                            <td>
                                <a class="tbale-btn"
                                   href="{{ route('parkings.edit', ['parking' => $parking->id]) }}">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
