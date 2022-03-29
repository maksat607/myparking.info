<form method="POST" action="@if(isset($legal)) {{ route('legals.update', ['legal'=>$legal->id]) }} @else {{ route('legals.store') }} @endif">
    @csrf
    @if(isset($legal)) @method('PUT') @endif

    <div class="container page-head-wrap">
        <div class="page-head">
            <div class="page-head__top d-flex align-items-center">
                <h1>{{ $title }}</h1>
                <div class="ml-auto d-flex">
                    <button type="submit" class="btn btn-white">@if(isset($legal)) {{ __('Update') }} @else {{ __('Create') }} @endif</button>
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
                            О юр. лице
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="field-style">
                                    <span>Название</span>
                                    <input type="text"
                                        name="name"
                                        class="@error('name'){{ 'is-invalid' }}@enderror"
                                        value="@if(isset($legal)){{ $legal->name }}@else{{ old('name') }}@endif"
                                        placeholder="Не указано"
                                        required>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="switch-radio-wrap mt-11px">
                                    <input type="checkbox" name="status" value="1"
                                           @if(isset($legal) && !$legal->status){{ '' }}@else {{ 'checked' }}@endif>
                                    <span class="switcher-radio"></span>
                                    <span>Активен</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="inner-page__item">
                        <div class="inner-item-title">
                            Юр. данные
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="field-style">
                                    <span>ИНН</span>
                                    <input type="text" class="@error('inn'){{ 'is-invalid' }}@enderror" name="inn"
                                    value="@if(isset($legal)){{ $legal->inn }}@else{{ old('inn') }}@endif"
                                    required placeholder="Не указан">

                                    @error('inn')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </label>
                                <label class="field-style mt-4">
                                    <span>КПП</span>
                                    <input type="text" class="@error('kpp'){{ 'is-invalid' }}@enderror" name="kpp"
                                           value="@if(isset($legal)){{ $legal->kpp }}@else{{ old('kpp') }}@endif"
                                           required placeholder="Не указан">

                                    @error('kpp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="field-style">
                                    <span>ОГРН</span>
                                    <input type="text" class="form-control @error('reg_number') is-invalid @enderror"
                                        name="reg_number"
                                        value="@if(isset($legal)){{ $legal->reg_number }}@else{{ old('reg_number') }}@endif"
                                        required placeholder="Не указан">

                                    @error('reg_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="inner-page__item">
                        <div class="inner-item-title">
                            Связи <span style="color: darkred">новый блок</span>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <fieldset class="fieldset">
                                    <legend class="legend">Связи</legend>
                                    <div class="d-flex">
                                        <div class="type-card parts-list">
                                            <div class="nav condition-nav" id="condition" role="tablist" aria-orientation="vertical">
                                                <a class="block-nav__item active" href="#condition-1" data-toggle="tab">Стоянки</a>
                                            </div>
                                        </div>
                                        <div class="type-card-info tab-content">
                                            <div class="row no-gutters tab-pane fade show active" id="condition-1">
                                                <label class="field-style">
                                                    <span>Поиск</span>
                                                    <input type="text" placeholder="По названию">
                                                </label>
                                            </div>

                                            <ul class="list">
                                                <li class="list__item">
                                                    <label>
                                                        <input type="checkbox" class="list-checkbox">
                                                        <span class="list-checked"></span>
                                                        ПАО СберБанкСтрах
                                                    </label>
                                                </li>
                                                <li class="list__item">
                                                    <label>
                                                        <input type="checkbox" class="list-checkbox">
                                                        <span class="list-checked"></span>
                                                        А-Приори
                                                    </label>
                                                </li>
                                                <li class="list__item">
                                                    <label>
                                                        <input type="checkbox" class="list-checkbox" checked>
                                                        <span class="list-checked"></span>
                                                        РосСтрах Групп
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </fieldset>
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
                                О юр.лице
                            </div>
                            <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                <span class="sidebar__check-name">Название</span>
                                <span class="sidebar__icon"></span>
                            </div>
                        </div>
                        <div class="sidebar__item">
                            <div class="sidebar-item-title">
                                Юр. данные
                            </div>
                            <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                <span class="sidebar__check-name">ОГРН</span>
                                <span class="sidebar__icon"></span>
                            </div>
                            <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                <span class="sidebar__check-name">ИНН</span>
                                <span class="sidebar__icon"></span>
                            </div>
                        </div>
                        <div class="sidebar__item">
                            <div class="sidebar-item-title">
                                Связи
                            </div>
                            <div class="sidebar__check-item d-flex align-items-center justify-content-between">
                                <span class="sidebar__check-name">Стоянки</span>
                                <span class="sidebar__icon"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>
