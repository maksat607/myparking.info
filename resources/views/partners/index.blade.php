@extends('layouts.app')

@section('content')

    <div class="container page-head-wrap">
        <div class="page-head">
            <div class="page-head__top d-flex align-items-center">
                <h1>{{ $title }}</h1>
                <label class="field-style blue">
                    <span>Поиск</span>
                    <input type="text" placeholder="Поиск по столбцам">
                </label>
                <div class="ml-auto d-flex">
                    <button class="btn btn-white">Добавить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="inner-page">
            <table class="table">
                <thead>
                <tr>
                    <th></th>
                    <th scope="col">@lang('Name')</th>
                    <th scope="col">@lang('Address')</th>
                    <th scope="col">@lang('Phone')</th>
                    <th scope="col">@lang('Email')</th>
                    <th scope="col">@lang('Status')</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($partners as $partner)
                    <tr class="@if(!$partner->status){{ 'disabled-tr' }}@endif">
                        <td class="tr-id">{{ $loop->iteration }}</th>
                        <td>
                            <div class="first-info d-flex align-items-center">
                                <span class="status-dot status-success">&bull;</span>
                                <span>{{ $partner->name }}</span>
                            </div>
                            <div class="company-type">{{ $partner->partnerType->name }}</div>
                        </td>
                        <td style="width: 200px;">{{ $partner->address }}</td>
                        <td>{{ $partner->phone }}</td>
                        <td>{{ $partner->email }}</td>
                        <td>
                            @if ($partner->status)
                                <span class="status-td statusgreen">{{ __('Active') }}</span>
                            @else
                                <span class="status-td statuspink">{{ __('Not active') }}</span>
                            @endif
                        </td>

                        <td>
                            <div class="car-dd">
                                <div class="car-close-dd"></div>
                                <div class="car-dd-body">
                                    @can('partner_update')
                                        <a class="tbale-btn"
                                           href="{{ route('partners.edit', ['partner' => $partner->id]) }}">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                    @endcan
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
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $partners->links() }}
    </div>

@endsection
