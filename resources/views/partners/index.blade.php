@extends('layouts.app')

@section('content')
    <section class="setting">
        <div class="wrapper">
            <h2 class="setting__title">{{ $title }}</h2>
            <div class="setting__bar s-between">
                <input type="text" class="input setting__search" placeholder="Поиск по столбцам">
                <a class="btn blue-btn" href="{{ route('partners.create') }}">{{ __('Create') }}</a>
            </div>
            <div class="table-response">
                <table class="setting__table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">@lang('Name')</th>
                            <th scope="col">@lang('Address')</th>
                            <th scope="col">@lang('Phone')</th>
                            <th scope="col">@lang('Email')</th>
                            <th scope="col">@lang('Partner types')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partners as $partner)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $partner->name }}</td>
                                <td>{{ $partner->address }}</td>
                                <td>{{ $partner->phone }}</td>
                                <td>{{ $partner->email }}</td>
                                <td>{{ $partner->partnerType->name }}</td>
                                <td>
                                    @if ($partner->status)
                                        <span class="status-td statusgreen">{{ __('Active') }}</span>
                                    @else
                                        <span class="status-td statuspink">{{ __('Not active') }}</span>
                                    @endif
                                </td>

                                <td class="text-right">
                                    <div class="btn-group" role="group">
                                        {{-- @can('user_update') --}}
                                        <a class="tbale-btn"
                                            href="{{ route('partners.edit', ['partner' => $partner->id]) }}">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                        {{-- @endcan --}}
                                        {{-- <a class="btn btn-warning" href="{{ route('partner-users.create', ['partner'=>$partner->id]) }}">
                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                    </a> --}}
                                        @can('issetPartnerUser', $partner)
                                            <a class="tbale-btn"
                                                href="{{ route('partner-users.create', ['partner' => $partner->id]) }}">
                                                <i class="fa fa-user-plus" aria-hidden="true"></i>
                                            </a>


                                        @else
                                            <a class="tbale-btn"
                                               href="{{ route('partner-users.edit',
                                                                    ['partner_user'=>$partner->user->id, 'partner'=>$partner->id]) }}">
                                                <i class="fa fa-user-times" aria-hidden="true"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
