@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ $title }}</div>

                    <div class="card-body">

                        @include('applications.form')

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
