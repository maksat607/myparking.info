@extends('layouts.app')

@section('content')
<div class="wrapper">
    <h1>{{ $title }}</h1>
</div>
<form method="POST" action="{{ route('issue_requests.store', ['application' => $application->id]) }}">
    @csrf
<section>
    <div class="wrapper">
        <div class="contentWrapper d-flex flex-start">
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
                <h2>Данные для выдачи</h2>
                <div class="tabform__group d-flex flex-end">
                    <div class="tabform__inputwrap">
                        <label>Основание выдачи</label>
                        <select id="issuanceDocument">
                            <option selected hidden value="">{{ __('Select a document type..') }}</option>
                            @foreach($documentOptions as $document)
                            <option value="{{ $document }}">{{ $document }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="tabform__inputwrap">
                        <input type="text" id="issuanceDocumentInput" name="client[issuance_document]" placeholder="Иной документ" value="{{ old('client.issuance_document_other') }}">
                    </div>
                    <div class="tabform__inputwrap">
                        <label>Дата выдачи</label>
                        <input type="text" name="issue_request[arriving_at]" class="date @error('arriving_at') is-invalid @enderror">
                        @error('arriving_at')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="tabform__inputwrap">
                        <label>Промежуток</label>
                        <select name="issue_request[arriving_interval]" class="@error('arriving_interval') is-invalid @enderror">
                            <option selected hidden value="">{{ __('Select a time interval..') }}</option>
                            <option value="10:00 - 14:00">10:00 - 14:00</option>
                            <option value="14:00 - 18:00">14:00 - 18:00</option>
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
                            <input type="radio" name="client[legal_type_id]" value="1" checked>
                            <span class="d-flex">
                                <span class="tabform__radionew"></span>
                                <span class="tabform__radionum">{{ $individualLegalOptions[1] }}</span>
                            </span>
                        </label>
                        <label class="tabform__radio">
                            <input type="radio" name="client[legal_type_id]" value="2">
                            <span class="d-flex">
                                    <span class="tabform__radionew"></span>
                                    <span class="tabform__radionum">{{ $individualLegalOptions[2] }}</span>
                                </span>
                        </label>
                        <div class="tabform__inputgroup">
                            <input type="text" name="client[lastname]" value="{{ old('client.lastname') }}"  placeholder="Фамилия">
                            <input type="text" name="client[firstname]" value="{{ old('client.firstname') }}" placeholder="Имя">
                            <input type="text" name="client[middlename]" value="{{ old('client.middlename') }}" placeholder="Отчество">
                            <input type="text" name="client[passport]" value="{{ old('client.passport') }}" placeholder="Паспорт">
                        </div>
                    </div>
                    <div class="tabform__inputwrap w-100">
                        <label>Персональные данные</label>
                        <label class="tabform__radio">
                            <input type="radio" name="client[preferred_contact_method]" value="{{ $preferredContactMethodOptions[0] }}" checked>
                            <span class="d-flex">
                                    <span class="tabform__radionew"></span>
                                    <span class="tabform__radionum">Телефон</span>
                                </span>
                        </label>
                        <label class="tabform__radio">
                            <input type="radio" name="client[preferred_contact_method]" value="{{ $preferredContactMethodOptions[1] }}">
                            <span class="d-flex">
                                    <span class="tabform__radionew"></span>
                                    <span class="tabform__radionum">Электронная почта</span>
                                </span>
                        </label>
                        <div class="tabform__inputgroup">
                            <input type="text" name="client[phone]" value="{{ old('client.phone') }}" placeholder="Телефон">
                            <input type="text" name="client[address]" value="{{ old('client.address') }}" placeholder="Адрес">
                            <input type="text" name="client[email]" value="{{ old('client.email') }}" placeholder="Электронная почта">
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
</form>
@endsection
