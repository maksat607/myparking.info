@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $title }}</div>

                    <div class="card-body">

                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th scope="row">@lang('Title')</th>
                                <td>{{ $parking->title }}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Region')</th>
                                <td>{{ $parking->code }}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Address')</th>
                                <td>{{ $parking->address }}</td>
                            </tr>
                            <tr>
                                <th scope="row">@lang('Timezone')</th>
                                <td>{{ $parking->timezone }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
