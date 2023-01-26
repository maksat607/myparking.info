@foreach($storageNotifications as $notification)

    @if(json_decode($notification)->data->user_id==auth()->id())
        <div class="chat__item user-mess">
            <div class="d-flex">
                <div class="chat__user-img">
                    <img src="{{ asset('img/avatar.png') }}" alt="">
                </div>
                <div class="chat__user-info">
                    <div
                        class="chat__user-name">{{ auth()->user()->getRole() }}
                        (Вы)
                    </div>
                    <div class="chat__date">{{ ($notification->created_at->format('d.m.Y H:i')) }}</div>
                </div>
            </div>
            <div class="chat__mess">
                {{ json_decode($notification)->data->message }}
            </div>
            <div>
            </div>
        </div>
    @else

        <div class="chat__item">

            <div class="d-flex">
                <div class="chat__user-img">
                    <img src="{{ asset('img/avatar.png') }}" alt="">
                </div>
                <div class="chat__user-info">
                    <div
                        class="chat__user-name">{{ optional(json_decode($notification)->data)->role }}</div>
                    <div class="chat__date">{{ ($notification->created_at->format('d.m.Y H:i')) }}</div>
                </div>
            </div>
            <div class="chat__mess">
                {{ json_decode($notification)->data->message }}
            </div>
            <div>
            </div>
        </div>
    @endif
@endforeach
<div>

</div>

