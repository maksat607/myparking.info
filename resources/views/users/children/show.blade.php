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
                                    <td>{{ $child->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('E-mail')</th>
                                    <td>{{ $child->email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('Status')</th>
                                    <td>
                                        @if($child->status)
                                            <span class="badge badge-success">{{ 'Включен' }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ 'Отключен' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
