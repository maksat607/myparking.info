@extends('layouts.app')

@section('content')

    <form method="POST" action="{{ route('issue_requests.update', ['issue_request' => $issueRequest->id]) }}">
        @csrf
        @method('PUT')
        <div class="container page-head-wrap">
            <div class="page-head">
                <div class="page-head__top d-flex align-items-center">
                    <a href="#" class="page-head__cancel">Отменить</a>
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
                    <div class="col-md-8">
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
                                <div class="page-add-file">
                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.6"
                                              d="M20.0013 6.6665C20.9218 6.6665 21.668 7.4127 21.668 8.33317V18.3332H31.668C32.5884 18.3332 33.3346 19.0794 33.3346 19.9998C33.3346 20.9203 32.5884 21.6665 31.668 21.6665H21.668V31.6665C21.668 32.587 20.9218 33.3332 20.0013 33.3332C19.0808 33.3332 18.3346 32.587 18.3346 31.6665V21.6665H8.33464C7.41416 21.6665 6.66797 20.9203 6.66797 19.9998C6.66797 19.0794 7.41416 18.3332 8.33464 18.3332H18.3346V8.33317C18.3346 7.4127 19.0808 6.6665 20.0013 6.6665Z"
                                              fill="#536E9B" />
                                    </svg>
                                </div>
                                <div class="page-file-item">
                                    <div class="file-icon doc-icon"></div>
                                    <span>dogovor-na-otvetstv...</span>
                                    <div class="page-file__option">
                                        <button type="button" class="page-file__download"></button>
                                        <button type="button" class="page-file__delete"></button>
                                    </div>
                                </div>
                                <div class="page-file-item">
                                    <div class="file-icon pdf-icon"></div>
                                    <span>dogovor-na-otvetstv...</span>
                                    <div class="page-file__option">
                                        <button type="button" class="page-file__download"></button>
                                        <button type="button" class="page-file__delete"></button>
                                    </div>
                                </div>
                                <div class="page-file-item">
                                    <div class="file-icon xls-icon"></div>
                                    <span>dogovor-na-otvetstv...</span>
                                    <div class="page-file__option">
                                        <button type="button" class="page-file__download"></button>
                                        <button type="button" class="page-file__delete"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sidebar">
                            <div class="sidebar__title">
                                Чек-лист оформления
                            </div>
                            <div class="sidebar__item">
                                <div class="sidebar-item-title">
                                    Дата
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Дата выдачи</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Промежуток времени</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                            </div>
                            <div class="sidebar__item">
                                <div class="sidebar-item-title">
                                    Ифнормация о выдаче
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Название организации</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">ИНН</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">ФИО доверенного лица</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Телефон доверенного лица</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                            </div>
                            <div class="sidebar__item">
                                <div class="sidebar-item-title">
                                    Документы
                                </div>
                                <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                    <span class="sidebar__check-name">Документы</span>
                                    <span class="sidebar__icon"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>


{{--    <div class="wrapper">
        <h1>{{ $title }}</h1>
    </div>
    <form method="POST" action="{{ route('issue_requests.update', ['issue_request' => $issueRequest->id]) }}">
        @csrf
        @method('PUT')
        <section>
            <div class="wrapper">
                <div class="contentWrapper d-flex flex-start">
                    <div class="tabform__item tabform__item--carinfo d-flex">
                        @if($application->attachments->isNotEmpty())
                            <img src="{{ $application->attachments->first()->thumbnail_url }}" alt="" class="newpopup__img">
                        @else
                            <img src="{{ $application->default_attachment->thumbnail_url }}" alt="" class="newpopup__img">
                        @endif
                        <div class="newpopup__left">
                            <h3 class="newcart__title">{{ $application->car_title }}</h3>
                            @if($application->returned)
                                <span class="newcart__repeat">Повтор</span>
                            @endif
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
                        <h2>Данные для выдачи</h2>
                        <div class="tabform__group d-flex flex-end">
                            <div class="tabform__inputwrap">
                                <label>Основание выдачи</label>
                                <select id="issuanceDocument">
                                    <option selected hidden value="">{{ __('Select a document type..') }}</option>
                                    @if($client && !empty($client->issuance_document) && !in_array($client->issuance_document, $documentOptions))
                                        <option selected value="{{ $client->issuance_document }}">{{ $client->issuance_document }}</option>
                                    @endif
                                    @foreach($documentOptions as $document)
                                        <option @if($client && $client->issuance_document == $document) selected @endif value="{{ $document }}">{{ $document }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="tabform__inputwrap">
                                <input type="text" id="issuanceDocumentInput" name="client[issuance_document]" placeholder="Иной документ" value="@if($client){{ $client->issuance_document }}@endif">
                            </div>
                            <div class="tabform__inputwrap">
                                <label>Дата выдачи</label>
                                <input type="text" name="issue_request[arriving_at]" class="date @error('arriving_at') is-invalid @enderror">
                                @error('arriving_at')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                @push('scripts')
                                    const dateDataIssue = '{{ ($issueRequest->arriving_at) ? $issueRequest->arriving_at->format('d-m-Y') : now()->format('d-m-Y') }}';
                                @endpush
                            </div>
                            <div class="tabform__inputwrap">
                                <label>Промежуток</label>
                                <select name="issue_request[arriving_interval]" class="@error('arriving_interval') is-invalid @enderror">
                                    <option selected hidden value="">{{ __('Select a time interval..') }}</option>
                                    <option @if( $issueRequest->arriving_interval == "10:00 - 14:00" ) selected @endif value="10:00 - 14:00">10:00 - 14:00</option>
                                    <option @if( $issueRequest->arriving_interval == "14:00 - 18:00" ) selected @endif value="14:00 - 18:00">14:00 - 18:00</option>
                                </select>
                                @error('arriving_interval')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="tabform__inputwrap w-100">
                                <label>Персональные данные</label>
                                <label class="tabform__radio">
                                    <input type="radio" name="client[legal_type_id]" value="1"
                                        @if($client && $client->legal_type_id == array_keys($individualLegalOptions)[0])
                                            checked
                                        @elseif(is_null($client))
                                            checked
                                        @endif>
                                    <span class="d-flex">
                                <span class="tabform__radionew"></span>
                                <span class="tabform__radionum">{{ $individualLegalOptions[1] }}</span>
                            </span>
                                </label>
                                <label class="tabform__radio">
                                    <input type="radio" name="client[legal_type_id]" value="2"
                                           @if($client && $client->legal_type_id == array_keys($individualLegalOptions)[1]) checked @endif>
                                    <span class="d-flex">
                                    <span class="tabform__radionew"></span>
                                    <span class="tabform__radionum">{{ $individualLegalOptions[2] }}</span>
                                </span>
                                </label>
                                <div class="tabform__inputgroup">
                                    <input type="text" name="client[lastname]"
                                           value="@if($client){{ $client->lastname }}@else{{ old('client.lastname') }}@endif"  placeholder="Фамилия">
                                    <input type="text" name="client[firstname]"
                                           value="@if($client){{ $client->firstname }}@else{{ old('client.firstname') }}@endif" placeholder="Имя">
                                    <input type="text" name="client[middlename]"
                                           value="@if($client){{ $client->middlename }}@else{{ old('client.middlename') }}@endif" placeholder="Отчество">
                                    <input type="text" name="client[passport]"
                                           value="@if($client){{ $client->passport }}@endif" placeholder="Паспорт">
                                </div>
                            </div>
                            <div class="tabform__inputwrap w-100">
                                <label>Персональные данные</label>
                                <label class="tabform__radio">
                                    <input type="radio" name="client[preferred_contact_method]"
                                        value="{{ $preferredContactMethodOptions[0] }}"
                                        @if($client && $client->preferred_contact_method == $preferredContactMethodOptions[0])
                                            checked
                                        @elseif(is_null($client))
                                            checked
                                        @endif>
                                    <span class="d-flex">
                                    <span class="tabform__radionew"></span>
                                    <span class="tabform__radionum">Телефон</span>
                                </span>
                                </label>
                                <label class="tabform__radio">
                                    <input type="radio" name="client[preferred_contact_method]"
                                        value="{{ $preferredContactMethodOptions[1] }}"
                                        @if($client && $client->preferred_contact_method == $preferredContactMethodOptions[1]) checked @endif>
                                    <span class="d-flex">
                                    <span class="tabform__radionew"></span>
                                    <span class="tabform__radionum">Электронная почта</span>
                                </span>
                                </label>
                                <div class="tabform__inputgroup">
                                    <input type="text" name="client[phone]"
                                        value="@if($client){{ $client->phone }}@else{{ old('client.phone') }}@endif" placeholder="Телефон">
                                    <input type="text" name="client[address]"
                                        value="@if($client){{ $client->address }}@else{{ old('client.address') }}@endif" placeholder="Адрес">
                                    <input type="text" name="client[email]"
                                        value="@if($client){{ $client->email  }}@else{{ old('client.email') }}@endif" placeholder="Электронная почта">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="wrapper">
            <div class="tabform__footer">
                <button class="tabform__footerbtn bgpink">Создать</button>
                <button class="tabform__footerbtn bggreen">Отменить</button>
            </div>
        </div>
    </form>--}}
@endsection
