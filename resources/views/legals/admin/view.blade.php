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
                                    <th scope="row">@lang('Name')</th>
                                    <td>{{ $legal->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('Reg. Number')</th>
                                    <td>{{ $legal->reg_number }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('INN')</th>
                                    <td>{{ $legal->inn }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
