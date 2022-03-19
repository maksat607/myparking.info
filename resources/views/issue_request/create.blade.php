@extends('layouts.app')

@section('content')

    <form method="POST" action="{{ route('issue_requests.store', ['application' => $application->id]) }}">
        @csrf
        <div class="container page-head-wrap">
            <div class="page-head">
                <div class="page-head__top d-flex align-items-center">
                    <h1>{{ $title }}</h1>
                    <div class="ml-auto d-flex">
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
                                        <input type="text" name="issue_request[arriving_at]" class="date-manager">
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="field-style @error('arriving_interval') invalid @enderror">
                                        <span>Промежуток времени</span>
                                        <select name="issue_request[arriving_interval]" class="page-select">
                                            <option selected hidden value="">{{ __('Select a time interval..') }}</option>
                                            <option @if(old('app_data.arriving_interval') == '10:00 - 14:00'){{ 'selected' }}@endif value="10:00 - 14:00">10:00 - 14:00</option>
                                            <option @if(old('app_data.arriving_interval') == '14:00 - 18:00'){{ 'selected' }}@endif value="14:00 - 18:00">14:00 - 18:00</option>
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
                                            @if(
                                                old('client.legal_type_id') == array_keys($individualLegalOptions)[0] ||
                                                is_null(old('client.legal_type_id'))
                                                )
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
                                           @if(old('client.legal_type_id') == array_keys($individualLegalOptions)[1])
                                               checked
                                           @endif
                                        >
                                        <span class="switcher-radio"></span>
                                        <span>{{ $individualLegalOptions[2] }}</span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>Название организации</span>
                                        <input type="text"
                                               value="{{ old('client.organization_name') }}"
                                               name="client[organization_name]" placeholder="Не указан">
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="field-style">
                                        <span>ИНН</span>
                                        <input type="text"
                                               value="{{ old('client.inn') }}"
                                               name="client[inn]" placeholder="Не указан">
                                    </label>
                                </div>
                                <div class="col-6 mt-3">
                                    <label class="field-style">
                                        <span>ФИО доверенного лица</span>
                                        <input type="text"
                                               value="{{ old('client.fio') }}"
                                               name="client[fio]" placeholder="Не указан">
                                    </label>
                                </div>
                                <div class="col-6 mt-3">
                                    <label class="field-style">
                                        <span>Телефон доверенного лица</span>
                                        <input type="text"
                                               value="{{ old('client.phone') }}"
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
                </div>
            </div>
        </div>

    </form>
@endsection
