@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('view_requests.store', ['application' => $application->id]) }}" enctype="multipart/form-data">
        @csrf

        <div class="container page-head-wrap">
            <div class="page-head">
                <div class="page-head__top d-flex align-items-center">
                    <a href="#" class="page-head__cancel">Отменить</a>
                    <h1>{{ $title }}</h1>
                    <div class="ml-auto d-flex">
                        <label class="field-style">
                            <span class="field-style-title">Статус</span>
                            <select name="view_request[status_id]">
                                @foreach($status as $k=>$v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </label>
                        <button class="btn btn-white">Создать заявку</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="inner-page">
                <div class="row no-gutters">
                    <div class="col-md-12">
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Информация об авто
                            </div>
                            <div class="row no-gutters">
                                <div class="col-6">
                                    <div class="info-item">
                                        <span>VIN</span>
                                        {{ $application->vin }}
                                    </div>
                                    <div class="info-item">
                                        <span>Гос. номер</span>
                                        {{ $application->license_plate }}
                                    </div>
                                    <div class="info-item">
                                        <span>Партнёр</span>
                                        {{ $application->partner->name }}
                                    </div>
                                    <div class="info-item">
                                        <span>Стоянка</span>
                                        {{ $application->parking->title }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="info-item">
                                        <span>Дата постановки</span>
                                        {{ $application->formated_arrived_at }}
                                    </div>
                                    <div class="info-item">
                                        <span>Принял</span>
                                        @if($application->acceptedBy)
                                            {{ $application->acceptedBy->name }}
                                        @else
                                            Не указан
                                        @endif
                                    </div>
                                    <div class="info-item">
                                        <span>Дата выдачи</span>
                                        {{ $application->formated_issued_at }}
                                    </div>
                                    <div class="info-item">
                                        <span>Выдал</span>
                                        @if($application->issuedBy)
                                            {{ $application->issuedBy->name }}
                                        @else
                                            Не указан
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Административная информация
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="field-style">
                                        <span> ФИО собственника</span>
                                        <input type="text" name="view_request[client_name]"
                                               value="{{ old('view_request.client_name') }}" placeholder="Не указан">
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>Название организации</span>
                                        <input type="text" name="view_request[organization_name]"
                                               value="{{ old('view_request.organization_name') }}" placeholder="Не указан">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Дата
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>Дата осмотра</span>
                                        <input type="text" name="view_request[arriving_at]" class="date @error('arriving_at') invalid @enderror">
                                        @error('arriving_at')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="field-style @error('arriving_interval') invalid @enderror">
                                        <span>Промежуток времени</span>
                                        <select name="view_request[arriving_interval]">
                                            <option selected hidden value="">{{ __('Select a time interval..') }}</option>
                                            <option value="10:00 - 14:00">10:00 - 14:00</option>
                                            <option value="14:00 - 18:00">14:00 - 18:00</option>
                                        </select>
                                    </label>
                                    @error('arriving_interval')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Дополнительно
                            </div>
                            <div class="field-style">
                                <span>Комментарий</span>
                                <textarea class="mw-100" name="view_request[comment]" id="" placeholder="Комментарий">{{ old('view_request.comment') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection
