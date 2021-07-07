@extends('layouts.app')

@section('content')
    <section class="setting">
        <div class="wrapper">
            <h2 class="setting__title">{{ $title }}</h2>
            <div class="setting__bar s-between">
                <input type="text" class="input setting__search" placeholder="Поиск по столбцам">
                <a class="btn blue-btn" href="{{ route('legals.create') }}">{{ __('Create') }}</a>
            </div>
            <div class="table-response">
                @if ($legals->isNotEmpty())
                    <table class="setting__table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Reg. Number')</th>
                                <th scope="col">@lang('INN')</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($legals as $legal)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        <a
                                            href="{{ route('legals.show', ['legal' => $legal->id]) }}">{{ $legal->name }}</a>
                                    </td>
                                    <td>{{ $legal->reg_number }}</td>
                                    <td>{{ $legal->inn }}</td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            @can('legal_update')
                                                <a class="tbale-btn"
                                                    href="{{ route('legals.edit', ['legal' => $legal->id]) }}">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a>
                                            @endcan

                                            @can('legal_delete')
                                                <a class="tbale-btn"
                                                    onclick="if( confirm('Delete it?') ) { event.preventDefault();
                                                                document.getElementById('deleteLegal{{ $legal->id }}').submit(); return true }">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </a>
                                            @endcan

                                        </div>

                                        @can('legal_delete')
                                            <form id="deleteLegal{{ $legal->id }}" method="POST"
                                                action="{{ route('legals.destroy', ['legal' => $legal->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endcan

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>{{ __('Legal entities have not yet been created. Create at least one record.') }}</p>
                @endif
            </div>
        </div>
    </section>
    {{-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ $title }}
                        <div class="text-right my-2">
                            <a class="btn btn-primary" href="{{ route('legals.create') }}">{{ __('Create') }}</a>
                        </div>
                    </div>

                    <div class="card-body">

                        @if ($legals->isNotEmpty())
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">@lang('Name')</th>
                                        <th scope="col">@lang('Reg. Number')</th>
                                        <th scope="col">@lang('INN')</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($legals as $legal)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>
                                                <a
                                                    href="{{ route('legals.show', ['legal' => $legal->id]) }}">{{ $legal->name }}</a>
                                            </td>
                                            <td>{{ $legal->reg_number }}</td>
                                            <td>{{ $legal->inn }}</td>
                                            <td class="text-right">
                                                <div class="btn-group">
                                                    @can('legal_update')
                                                        <a class="btn btn-primary"
                                                            href="{{ route('legals.edit', ['legal' => $legal->id]) }}">
                                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                        </a>
                                                    @endcan

                                                    @can('legal_delete')
                                                        <a class="btn btn-danger"
                                                            onclick="if( confirm('Delete it?') ) { event.preventDefault();
                                                                    document.getElementById('deleteLegal{{ $legal->id }}').submit(); return true }">
                                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                        </a>
                                                    @endcan

                                                </div>

                                                @can('legal_delete')
                                                    <form id="deleteLegal{{ $legal->id }}" method="POST"
                                                        action="{{ route('legals.destroy', ['legal' => $legal->id]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endcan

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>{{ __('Legal entities have not yet been created. Create at least one record.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
