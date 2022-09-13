@extends('layouts.app')
@section('content')
    <div class="container page-head-wrap">
        <div class="page-head partner">
            <div class="page-head__top d-flex align-items-center">
                <h1>{{ $title }}</h1>
                    <form id="appPartnerFilter" action="{{ url()->current() }}" method="GET"
                      class="d-flex align-items-center">
                    <label class="field-style blue">
                        <span>Поиск</span>
                        <input type="text" name="search" placeholder="Поиск по столбцам" value="@if(request()->get('search')){{ request()->get('search') }}@endif">
                    </label>
                    <label class="switch-radio-wrap " style="margin-right: 35px;">
                        <input type="checkbox" name="public" @if(request()->get('public')) checked @endif>
                        <span class="switcher-radio onfilter"></span>
                        <div>Общие</div>
                    </label>
                    <label class="switch-radio-wrap ">
                        <input type="checkbox" name="user" @if(request()->get('user')) checked @endif>
                        <span class="switcher-radio onfilter"></span>
                        <div>Пользовательские</div>
                    </label>
                </form>
                <div class="ml-auto d-flex">
                    {{--                    <a href="{{ route('partners.create') }}" class="btn btn-white">Добавить</a>--}}
                    <a href="{{ route('partners.search') }}" class="btn btn-white">Добавить</a>
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
                    <th class="sortable" scope="col">@sortablelink('name','Название')</th>
                    <th class="sortable" scope="col">@sortablelink('partnerType.name','Тип')</th>
                    <th class="sortable" scope="col">@sortablelink('inn','ИНН')</th>
                    <th class="sortable" scope="col">@sortablelink('kpp','КПП')</th>
                    <th scope="col">База</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($partners->sortBy('name') as  $partner)
                    <tr class="@if(!$partner->status){{ 'disabled-tr' }}@endif">
                        <td class="tr-id">
                        {{ $partner->number }}</th>
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
                                    company-type
                                </div>
                            @endif
                            <div class="company-type">{{ $partner->name }}</div>
                        </td>
                        <td style="width: 200px;">@if($partner->partnerType!==null){{ $partner->partnerType->name }} @else
                                null @endif</td>
                        <td>{{ $partner->inn }}</td>
                        <td>{{ $partner->kpp }}</td>
                        <td>
                            @if($partner->base_type=='public')
                                <div>общая</div>
                                <div>
                                        <button type="button" class="text-grey text-btn @if(auth()->user()->hasRole('SuperAdmin')) partner-users-show-modal" @endif
                                                data-partner-id="{{ $partner->id }}">
                                            Организация: {{ $partner->users->count() }}</button>
                                    </div>
                                    @else
                                        <div>Пользовательская</div>
                                        <div>
                                            <button type="button"
                                                    class="text-grey text-btn"> {{ @$partner->created_user->email }}</button>
                                        </div>

                            @endif

                        </td>
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

        <div class="partner-users-modal-block">

        </div>

        <div class="partner-overlay"></div>
    </div>

@endsection
