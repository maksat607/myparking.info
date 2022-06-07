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
                    <a href="{{ route('partners.create') }}" class="btn btn-white">Добавить</a>
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
                    <th scope="col">Название</th>
                    <th scope="col">Тип</th>
                    <th scope="col">ИНН</th>
                    <th scope="col">КПП</th>
                    <th scope="col">База</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($partners as $partner)
                    <tr class="@if(!$partner->status){{ 'disabled-tr' }}@endif">
                        <td class="tr-id">{{ $loop->iteration }}</th>
                        <td>
                            @if($partner->status)
                            <div class="first-info d-flex align-items-center">
                                <span class="status-dot status-success">&bull;</span>
                                <span>{{ $partner->shortname }}</span>
                            </div>
                            @else
                                <div class="first-info d-flex align-items-center status-danger">
                                    <span class="status-dot">&bull;</span>
                                    <span>{{ $partner->shortname }}</span>
                                </div>
                            @endif
                            <div class="company-type">{{ $partner->name }}</div>
                        </td>
                        <td style="width: 200px;">{{ $partner->partnerType->name }}</td>
                        <td>{{ $partner->inn }}</td>
                        <td>{{ $partner->kpp }}</td>
                        <td>{{ $partner->base_type }}</td>
{{--                        <td>--}}
    {{--                            @if ($partner->status)--}}
{{--                                <span class="status-td statusgreen">{{ __('Active') }}</span>--}}
{{--                            @else--}}
{{--                                <span class="status-td statuspink">{{ __('Not active') }}</span>--}}
{{--                            @endif--}}
{{--                        </td>--}}

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
