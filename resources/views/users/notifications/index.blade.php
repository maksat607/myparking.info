@extends('layouts.app')

@section('content')

    <div class="container page-head-wrap">
        <div class="page-head">
            <div class="page-head__top d-flex align-items-center">
                <h1>Уведомления</h1>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="inner-page">
            <ul class="notification__list">

                @foreach(auth()->user()->notifications as $notification)
                    @if(isset($notification->data['short']))
                        <li>
                            <span>{{ json_decode($notification)->data->short }}</span>
                            <div>{{ $notification->created_at->diffForHumans() }}</div>
                            <p>{{ json_decode($notification)->data->long }}</p>
                        </li>
                    @endif
                    @if(isset($notification->data['message']))
                        <li>
                            <span>{{ json_decode($notification)->data->title }}</span>
                            <div>{{ $notification->created_at->diffForHumans() }}</div>
                            <p>{{ json_decode($notification)->data->message }}</p>
                        </li>
                    @endif
                @endforeach

            </ul>
        </div>
    </div>
@endsection
