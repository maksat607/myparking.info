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
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($legals as $legal)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        <a href="{{ route('legals.view', ['user'=>$legal->owner->id, 'legal'=>$legal->id]) }}">{{ $legal->name }}</a>
                                    </td>
                                    <td>{{ $legal->reg_number }}</td>
                                    <td>{{ $legal->inn }}</td>
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
    </div>
@endsection
