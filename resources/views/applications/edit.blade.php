@extends('layouts.app')

@section('content')
    <header>
        <div class="wrapper">
            <h1>{{ $title }}</h1>
        </div>
    </header>

    @include('applications.form.update')

@endsection
