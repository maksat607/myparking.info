@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ $title }}
                        <div class="text-right my-2">
                            <a class="btn btn-primary" href="{{ route('partners.create') }}" >{{ __('Create') }}</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <table class="table">
                            <thead class="thead-dark">
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
                            @foreach($partners as $partner)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $partner->name }}</td>
                                    <td>{{ $partner->address }}</td>
                                    <td>{{ $partner->phone }}</td>
                                    <td>{{ $partner->email }}</td>
                                    <td>{{ $partner->partnerType->name }}</td>
                                    <td>
                                        @if($partner->status)
                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('Not active') }}</span>
                                        @endif
                                    </td>

                                    <td class="text-right">
                                        <div class="btn-group" role="group">
                                            {{--                                    @can('user_update')--}}
                                            <a class="btn btn-primary" href="{{ route('partners.edit', ['partner'=>$partner->id]) }}">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a>
                                            {{--                                    @endcan--}}
                                           {{-- <a class="btn btn-warning" href="{{ route('partner-users.create', ['partner'=>$partner->id]) }}">
                                                <i class="fa fa-user-plus" aria-hidden="true"></i>
                                            </a>--}}
                                            @can('issetPartnerUser', $partner)
                                            <a class="btn btn-warning" href="{{ route('partner-users.create', ['partner'=>$partner->id]) }}">
                                                <i class="fa fa-user-plus" aria-hidden="true"></i>
                                            </a>
                                            @else
                                                <a class="btn btn-danger"
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
            </div>
        </div>
    </div>
@endsection
