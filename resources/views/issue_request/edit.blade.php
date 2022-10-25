@extends('layouts.app')

@section('content')

    <form method="POST" action="{{ route('issue_requests.update', ['issue_request' => $issueRequest->id]) }}">
        @csrf
        @method('PUT')
        <div class="container page-head-wrap">
            <div class="page-head">
                <div class="page-head__top d-flex align-items-center">
                    <h1>{{ $title }}</h1>
                    <div class="ml-auto d-flex">
                        <button class="btn btn-white">Обновить</button>
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
                                        <span>ФИО собственника</span>
                                        {{ $application->courier_fullname }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="info-item">
                                        <span>Партнёр</span>
                                        {{ $application->partner->name }}
                                    </div>
                                    <div class="info-item">
                                        <span>Стоянка</span>
                                        {{ $application->parking->title }}
                                    </div>
                                    <div class="info-item">
                                        <span>Номер убытка / договора</span>
                                        {{ $application->external_id }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Дата
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="field-style @error('arriving_at') invalid @enderror">
                                        <span>Дата выдачи</span>
                                        <input type="text" name="issue_request[arriving_at]" class="date">
                                    </label>
                                    @push('scripts')
                                        const dateDataIssue = '{{ ($issueRequest->arriving_at) ? $issueRequest->arriving_at->format('d-m-Y') : now()->format('d-m-Y') }}';
                                    @endpush
                                </div>
                                <div class="col-6">
                                    <label class="field-style @error('arriving_interval') invalid @enderror">
                                        <span>Промежуток времени</span>
                                        <select name="issue_request[arriving_interval]" class="page-select">
                                            <option selected hidden value="">{{ __('Select a time interval..') }}</option>
                                            <option @if( $issueRequest->arriving_interval == "10:00 - 14:00" ) selected @endif value="10:00 - 14:00">10:00 - 14:00</option>
                                            <option @if( $issueRequest->arriving_interval == "14:00 - 18:00" ) selected @endif value="14:00 - 18:00">14:00 - 18:00</option>
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
                                Информация о выдаче
                            </div>
                            <div class="row">
                                <div class="col-6 mt-2 mb-5">
                                    <label class="switch-radio-wrap">
                                        <input type="radio" name="client[legal_type_id]"
                                            value="1"
                                            @if($client && $client->legal_type_id == array_keys($individualLegalOptions)[0])
                                                checked
                                            @elseif(is_null($client))
                                                checked
                                            @endif
                                        >
                                        <span class="switcher-radio"></span>
                                        <span>{{ $individualLegalOptions[1] }}</span>
                                    </label>


                                </div>
                                <div class="col-6 mt-2 mb-5">
                                    <label class="switch-radio-wrap">
                                        <input type="radio" name="client[legal_type_id]"
                                            value="2"
                                            @if($client && $client->legal_type_id == array_keys($individualLegalOptions)[1])
                                                checked
                                            @endif
                                        >
                                        <span class="switcher-radio"></span>
                                        <span>{{ $individualLegalOptions[2] }}</span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="field-style @error('organization_name') invalid @enderror">
                                        <span>Название организации</span>
                                        <input type="text"
                                               value="@if($client){{ $client->organization_name }}@endif"
                                               name="client[organization_name]" placeholder="Не указан">
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="field-style @error('inn') invalid @enderror">
                                        <span>ИНН</span>
                                        <input type="text"
                                               value="@if($client){{ $client->inn }}@endif"
                                               name="client[inn]" placeholder="Не указан">
                                    </label>
                                </div>
                                <div class="col-6 mt-3">
                                    <label class="field-style @error('fio') invalid @enderror">
                                        <span>ФИО доверенного лица</span>
                                        <input type="text"
                                               value="@if($client){{ $client->fio }}@endif"
                                               name="client[fio]" placeholder="Не указан">
                                    </label>
                                </div>
                                <div class="col-6 mt-3">
                                    <label class="field-style @error('phone') invalid @enderror">
                                        <span>Телефон доверенного лица</span>
                                        <input type="text"
                                               value="@if($client){{ $client->phone }}@endif"
                                               name="client[phone]" placeholder="Не указан">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="inner-page__item">
                            <div class="inner-item-title">
                                Документы
                            </div>
                            Паспорт, доверенность и прочее
                            <div class="page-file-list">
                                <div class="page-add-file no-ajax upload-file">
                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.6"
                                              d="M20.0013 6.6665C20.9218 6.6665 21.668 7.4127 21.668 8.33317V18.3332H31.668C32.5884 18.3332 33.3346 19.0794 33.3346 19.9998C33.3346 20.9203 32.5884 21.6665 31.668 21.6665H21.668V31.6665C21.668 32.587 20.9218 33.3332 20.0013 33.3332C19.0808 33.3332 18.3346 32.587 18.3346 31.6665V21.6665H8.33464C7.41416 21.6665 6.66797 20.9203 6.66797 19.9998C6.66797 19.0794 7.41416 18.3332 8.33464 18.3332H18.3346V8.33317C18.3346 7.4127 19.0808 6.6665 20.0013 6.6665Z"
                                              fill="#536E9B" />
                                    </svg>
                                </div>
                                @php
                                    $type = ['pdf'=>'pdf-icon','doc'=>'doc-icon','docx'=>'doc-icon','xls'=>'xls-icon','xlsx'=>'xls-icon','csv'=>'xls-icon'];
                                @endphp
                                @foreach($application->attachments->where('file_type','docs')->all() as $attachment)
                                    @if(in_array(strtolower(explode('.',$attachment->name)[array_key_last(explode('.',$attachment->name))]),['jpg','jpeg','png','bmp']))
                                        <div class="page-file-item" data-src="{{ $attachment->url }}">
                                            <img src="{{ $attachment->thumbnail_url }}" alt="">
                                            <div class="page-file__option">
                                                <button type="button" class="page-file__zoom" ></button>
                                                <button type="button" class="page-file__delete" data-img-id="{{ $attachment->id }}"></button>
                                            </div>
                                        </div>
                                    @else
                                        {{-- @dump(array_key_exists(explode('.',$attachment->name)[array_key_last(explode('.',$attachment->name))],$type))   --}}
                                        @if(array_key_exists(explode('.',$attachment->name)[array_key_last(explode('.',$attachment->name))],$type))
                                            <div class="page-file-item doc">
                                                <div class="file-icon {{ $type[explode('.',$attachment->name)[array_key_last(explode('.',$attachment->name))]] }}"></div>
                                                <span>{{$attachment->name}}</span>
                                                <div class="page-file__option">
                                                    <a href="{{ $attachment->url }}" type="button" class="page-file__download" data-img-id="{{ $attachment->id }}"></a>
                                                    <button type="button" class="page-file__delete" data-img-id="{{ $attachment->id }}"></button>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                            <input type="file" id="noAjaxFileUploader" name="docs[]" class="d-none" multiple>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection
