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
                <div class="col-md-12">
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
                </div>
            </div>
        </div>
    </div>

</form>
