<div class="newtopbar">
    <div class="wrapper">
        <div class="newtopbar__mob">
            <span class="newtopbar__mobtitle"></span>
            <span class="newtopbar__mobarrow"></span>
        </div>
        <nav class="newtopbar__nav">
            <ul class="newtopbar__list s-between">
                <li class="newtopbar__item{{
                    (is_null(request()->route('status_id')) && request()->routeIs('applications.index')) ? ' active' : ''
                }}">
                    <a href="{{ route('applications.index') }}" class="newtopbar__link">Все</a>
                </li>
                <li class="newtopbar__item{{ (request()->route('status_id') == 2) ? ' active' : '' }}">
                    <a href="{{ route('applications.index', ['status_id' => 2]) }}" class="newtopbar__link">Хранение</a>
                </li>
                <li class="newtopbar__item{{ (request()->route('status_id') == 1) ? ' active' : '' }}">
                    <a href="{{ route('applications.index', ['status_id' => 1]) }}" class="newtopbar__link">Черновик</a>
                </li>
                <li class="newtopbar__item{{ (request()->routeIs('application.accepting.request')) ? ' active' : '' }}">
                    <a href="{{ route('application.accepting.request') }}" class="newtopbar__link">Постановка</a>
                </li>
                <li class="newtopbar__item{{ (request()->routeIs('view_requests.index')) ? ' active' : '' }}">
                    <a href="{{ route('view_requests.index') }}" class="newtopbar__link">Осмотр</a>
                </li>
                <li class="newtopbar__item{{ (request()->routeIs('issue_requests.index')) ? ' active' : '' }}">
                    <a href="{{ route('issue_requests.index') }}" class="newtopbar__link">Выдача</a>
                </li>
                <li class="newtopbar__item{{ (request()->route('status_id') == 3) ? ' active' : '' }}">
                    <a href="{{ route('applications.index', ['status_id' => 3]) }}" class="newtopbar__link">Выдано</a>
                </li>
                <li class="newtopbar__item{{ (request()->route('status_id') == 6) ? ' active' : '' }}">
                    <a href="{{ route('applications.index', ['status_id' => 6]) }}" class="newtopbar__link">Отклонено</a>
                </li>
                <li class="newtopbar__item{{ request()->routeIs('applications.duplicate') ? ' active' : '' }}">
                    <a href="{{ route('applications.duplicate') }}" class="newtopbar__link">Дубли</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
<div class="newfilter">
    <form id="appFilter" action="{{ url()->current() }}">
        <div class="wrapper s-between">
            <div class="newfilter__search d-flex">
                <select name="partner" class="newfilter__select">
                    <option selected hidden value="">Выберите партнера</option>
                    @foreach($partners as $partner)
                        @if(request()->get('partner') == $partner->id)
                            <option selected value="{{ $partner->id }}">{{ $partner->name }}</option>
                        @else
                            <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                        @endif
                    @endforeach
                </select>
                <select name="parking" class="newfilter__select">
                    <option selected hidden value="">Выберите стоянку</option>
                    @foreach($parkings as $parking)
                        @if(request()->get('parking') == $parking->id)
                            <option selected value="{{ $parking->id }}">{{ $parking->title }}</option>
                        @else
                            <option value="{{ $parking->id }}">{{ $parking->title }}</option>
                        @endif
                    @endforeach
                </select>

                <select name="user" class="newfilter__select">
                    <option selected hidden value="">Выберите исполнителя</option>
                    <option @if(request()->get('user') == $user->id) selected @endif
                            value="{{ $user->id }}">{{ $user->name }}</option>
                    @foreach($user->kids as $kid)
                        @if(request()->get('user') == $kid->id)
                            <option selected value="{{ $kid->id }}">{{ $kid->name }}</option>
                        @else
                            <<option value="{{ $kid->id }}">{{ $kid->name }}</option>
                        @endif
                    @endforeach
                </select>
                <input type="text" class="newfilter__input" name="search"
                       value="@if(request()->get('search')){{ request()->get('search') }}@endif" placeholder="Поиск по тексту">
            </div>
            <div class="newfilter__sort">
                <input type="checkbox" @if(request()->get('favorite')) checked @endif
                name="favorite" class="checkbox-chosen newfilter__save">
                <input type="radio" @if(request()->get('direction') == 'row') checked @endif
                name="direction" value="row" class="btn-radio btn-radio--row">
                <input type="radio" @if(request()->get('direction') != 'row') checked @endif
                name="direction" value="column" class="btn-radio btn-radio--col">
            </div>
        </div>
        @if(request()->routeIs('applications.duplicate'))
            <div id="groupByDuplicate">
                <div class="wrapper">
                    <div class="group_radio">
                        <label for="vin">
                            <input type="radio" name="group-by" value="vin" id="vin"
                                @if(request()->get('group-by') == 'vin' || request()->get('group-by') == '')
                                    checked
                                @endif
                            >
                            <span>{{ __('By vin') }}</span>
                        </label>
                        <label for="licensePlate">
                            <input type="radio" name="group-by" value="license_plate" id="licensePlate"
                                @if(request()->get('group-by') == 'license_plate')
                                    checked
                                @endif
                            >
                            <span>{{ __('By state number') }}</span>
                        </label>
                    </div>
                </div>
            </div>
        @endif
    </form>
</div>

