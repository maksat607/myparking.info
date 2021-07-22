@extends('layouts.app')

@section('content')
    <section class="setting">
        <div class="wrapper">
            <h2 class="setting__title">{{ $title }}</h2>
            <div class="setting__bar s-between">
                <input type="text" class="input setting__search" placeholder="Поиск по столбцам">
                <a class="btn blue-btn" href="{{ route('partner-types.create') }}">{{ __('Create') }}</a>
            </div>
            <div class="table-response">
                <table class="setting__table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">@lang('Name')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partner_types as $type)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ $type->name }}</td>
                                        <td>
                                            @if ($type->status)
                                                <span class="status-td statusgreen">{{ __('Active') }}</span>
                                            @else
                                                <span class="status-td statuspink">{{ __('Not active') }}</span>
                                            @endif
                                        </td>

                                        <td class="text-right">
                                            {{-- @can('user_update') --}}
                                            <a class="tbale-btn"
                                                href="{{ route('partner-types.edit', ['partner_type' => $type->id]) }}">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a>
                                            {{-- @endcan --}}
                                        </td>
                                    </tr>
                                @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
