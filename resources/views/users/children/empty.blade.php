@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ $title }}
                        <div class="text-right my-2">
                            <a class="btn btn-primary" href="{{ route('users.children.create', ['user'=>$user->id]) }}" >{{ __('Add') }}</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <p>{{ __('There are no users yet. Create a new user') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
