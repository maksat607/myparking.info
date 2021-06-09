@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ $title }}
                    </div>

                    <div class="card-body">

                            @if($legals->isNotEmpty())
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
                                @includeWhen(request()->routeIs('legals.all'), 'legals.admin.all_for_user')
                                @includeWhen(request()->routeIs('legals.parkings.all'), 'legals.admin.all_for_parking')
                            </table>
                            @else
                                <p>{{ __('Legal entities have not yet been created. Create at least one record.') }}</p>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
