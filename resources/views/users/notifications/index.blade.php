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
                        <li class="@if(!$notification->read_at) new-notif @endif ">

                            <span>{{ json_decode($notification)->data->short }}</span>
                            <div>{{ $notification->created_at->diffForHumans() }}</div>

                            <p>{!!   makeClickableApplicationNotification(json_decode($notification)->data->long,json_decode($notification)->data->id,json_decode($notification)->data->user_id) !!}</p>
                        </li>

                    @endif
                    @if(isset($notification->data['message']))
                        <li class="@if(!$notification->read_at) new-notif @endif ">
                            <span>{!! makeClickableUserNotification(json_decode($notification)->data->title,json_decode($notification)->data->id) !!}</span>
                            <div>{{ $notification->created_at->diffForHumans() }}</div>
                            <p>{{ json_decode($notification)->data->message }}</p>
                        </li>
                    @endif
                    @if($notification->markAsRead())@endif
                @endforeach

            </ul>
        </div>
    </div>
    <div class="message-to-users-modal-block">

    </div>

    <div class="message-to-users-overlay"></div>
@endsection
