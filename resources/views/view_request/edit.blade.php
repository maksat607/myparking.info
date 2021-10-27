@extends('layouts.app')

@section('content')
    <div class="wrapper">
        <h1>{{ $title }}</h1>
    </div>
    <form method="POST" action="{{ route('view_requests.update', ['view_request' => $viewRequest->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <section>
            <div class="wrapper">
                <div class="contentWrapper d-flex">
                    <div class="tabform__item tabform__item--carinfo d-flex">
                        <img src="{{ $application->attachments->first()->thumbnail_url }}" alt="" class="newpopup__img">
                        <div class="newpopup__left">
                            <h3 class="newcart__title">{{ $application->car_title }}</h3>
                            <span class="newcart__repeat">Повтор</span>
                        </div>
                        <ul class="newpopup__ul">
                            <li>
                        <span>
                            <span>Партнёр:</span>
                        </span>
                                <span>
                            <span>{{ $application->partner->name }}</span>
                        </span>
                            </li>
                            <li>
                        <span>
                            <span>VIN:</span>
                        </span>
                                <span>
                            <span>{{ $application->vin }}</span>
                        </span>
                            </li>
                            <li>
                        <span>
                            <span>Гос. номер:</span>
                        </span>
                                <span>
                            <span>{{ $application->license_plate }}</span>
                        </span>
                            </li>
                            <li>
                        <span>
                            <span> Номер Убытка/Договора:</span>
                        </span>
                                <span>
                            <span>{{ $application->external_id }}</span>
                        </span>
                            </li>
                        </ul>
                    </div>
                    <div class="tabform__item">
                        <h2>Данные для осмотра</h2>
                        <div class="tabform__group d-flex">
                            <div class="tabform__inputwrap">
                                <label>ФИО</label>
                                <input type="text" name="view_request[client_name]"
                                       value="{{ $viewRequest->client_name }}"
                                       placeholder="Иванов Иван Иванович">
                            </div>
                            <div class="tabform__inputwrap">
                                <label>Название организации</label>
                                <input type="text" name="view_request[organization_name]"
                                       value="{{ $viewRequest->organization_name }}"
                                       placeholder="А-Приори">
                            </div>
                            <div class="tabform__inputwrap">
                                <label>Дата постановки</label>
                                <input type="text" name="view_request[arriving_at]" class="date @error('arriving_at') is-invalid @enderror">
                                @error('arriving_at')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                @push('scripts')
                                    const dateDataViewRequest = '{{ $viewRequest->arriving_at->format('d-m-Y') }}';
                                @endpush
                            </div>
                            <div class="tabform__inputwrap">
                                <label>Промежуток</label>
                                <select name="view_request[arriving_interval]" class="@error('arriving_interval') is-invalid @enderror">
                                    <option selected hidden value="">{{ __('Select a time interval..') }}</option>
                                    <option @if( $viewRequest->arriving_interval == "10:00 - 14:00" ) selected @endif value="10:00 - 14:00">10:00 - 14:00</option>
                                    <option @if( $viewRequest->arriving_interval == "14:00 - 18:00" ) selected @endif value="14:00 - 18:00">14:00 - 18:00</option>
                                </select>
                                @error('arriving_interval')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="wrapper">
            <h1>Результаты осмотра</h1>
        </div>
        <div class="wrapper">
            <div class="contentWrapper d-flex">
                <div class="tabform__item">
                    <h2>Фотографии авто</h2>
                    <div class="tabform__gallery">
                        <div class="input-images"></div>
                    </div>
                    @push('scripts')
                        const carAttachmentDataViewRequest = @json($attachments);
                    @endpush
                </div>
                <div class="tabform__item">
                    <h2>Комментарий</h2>
                    <textarea name="view_request[comment]" placeholder="Комментарий">{{ $viewRequest->comment }}</textarea>
                </div>
            </div>
            <div class="tabform__footer">
                <select name="view_request[status_id]">
                    @foreach($status as $k=>$v)
                        <option @if($viewRequest->status_id == $k) selected @endif value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>
                <button class="tabform__footerbtn bgpink">Создать</button>
                <button class="tabform__footerbtn bggreen">Отменить</button>
            </div>
        </div>
    </form>
@endsection
