@extends('layouts.app')

@section('content')
    <section class="setting">
        <div class="wrapper">
            <h2 class="setting__title">{{ $title }}</h2>
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
                        @includeWhen(request()->routeIs('legals.all'), 'legals.admin.all_for_user')
                        @includeWhen(request()->routeIs('legals.parkings.all'), 'legals.admin.all_for_parking')
                    </table>
                @else
                    <p>{{ __('Legal entities have not yet been created. Create at least one record.') }}</p>
                @endif
            </div>
        </div>
    </section>
@endsection
