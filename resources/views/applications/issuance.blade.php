@extends('layouts.app')

@section('content')
    <div class="wrapper">
        <h1>{{ $title }}</h1>
    </div>
    <form method="POST" action="{{ route('application.issuance', ['application' => $application->id]) }}">
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
                                        @if($client && !empty($client->issuance_document) && $client->issuance_document != $document)
                                            <option selected value="{{ $client->issuance_document }}">{{ $client->issuance_document }}</option>
                                        @endif
                                        <option @if($client && $client->issuance_document == $document) selected @endif value="{{ $document }}">{{ $document }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="tabform__inputwrap">
                                <input type="text" id="issuanceDocumentInput" name="client[issuance_document]" placeholder="Иной документ" value="@if($client){{ $client->issuance_document }}@endif">
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
                <button class="tabform__footerbtn bgpink">Выдать</button>
                <button class="tabform__footerbtn bggreen">Отменить</button>
            </div>
        </div>
    </form>
@endsection
