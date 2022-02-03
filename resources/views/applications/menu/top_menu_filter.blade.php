<div class="container page-head-wrap">
    <div class="page-head">
        <div class="page-head__top d-flex align-items-center">
            <h1>Автомобили</h1>
            <a href="{{ route('applications.create') }}" class="btn ml-auto btn-white">Добавить авто</a>
        </div>
    </div>
</div>
<div class="container">
    <ul class="page-nav">
        <li class="page-nav__item{{ (request()->route('status_id') == 2) ? ' active' : '' }}">
            <a href="{{ route('applications.index', ['status_id' => 2]) }}" class="page-nav__link">Хранение</a>
        </li>
        <li class="page-nav__item{{ (request()->route('status_id') == 1) ? ' active' : '' }}">
            <a href="{{ route('applications.index', ['status_id' => 1]) }}" class="page-nav__link">Черновики</a>
        </li>
        <li class="page-nav__item{{ (request()->route('status_id') == 7) ? ' active' : '' }}">
            <a href="{{ route('applications.index', ['status_id' => 7]) }}" class="page-nav__link">Постановка</a>
        </li>
        <li class="page-nav__item{{ (request()->routeIs('issue_requests.index')) ? ' active' : '' }}">
            <a href="{{ route('issue_requests.index') }}" class="page-nav__link">Выдача</a>
        </li>
        <li class="page-nav__item{{ (request()->routeIs('view_requests.index')) ? ' active' : '' }}">
            <a href="{{ route('view_requests.index') }}" class="page-nav__link">Осмотр</a>
        </li>
        @unlessrole('Operator')
        <li class="page-nav__item{{ (request()->route('status_id') == 3) ? ' active' : '' }}">
            <a href="{{ route('applications.index', ['status_id' => 3]) }}" class="page-nav__link">Выдано</a>
        </li>
        @endunlessrole
        <li class="page-nav__item{{ (request()->route('status_id') == 6) ? ' active' : '' }}">
            <a href="{{ route('applications.index', ['status_id' => 6]) }}" class="page-nav__link">Отклонено</a>
        </li>
        @unlessrole('Operator')
        <li class="page-nav__item{{ request()->routeIs('applications.duplicate') ? ' active' : '' }}">
            <a href="{{ route('applications.duplicate') }}" class="page-nav__link">Дубли</a>
        </li>
        @endunlessrole
        <li class="page-nav__item{{
                    (is_null(request()->route('status_id')) && request()->routeIs('applications.index')) ? ' active' : ''
                }}">
            <a href="{{ route('applications.index') }}" class="page-nav__link">Все</a>
        </li>
    </ul>
    <form id="appFilter" action="{{ url()->current() }}" method="GET" class="filter d-flex align-items-center">
        <label class="field-style">
            <span> Поиск по тексту</span>
            <input type="text" name="search" value="@if(request()->get('search')){{ request()->get('search') }}@endif">
        </label>

        <label class="field-style">
            <span>Партнёр</span>
            <select name="partner" class="page-select">
                <option selected value="">Выберите партнера</option>
                @foreach($partners as $partner)
                    @if(request()->get('partner') == $partner->id)
                        <option selected value="{{ $partner->id }}">{{ $partner->name }}</option>
                    @else
                        <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                    @endif
                @endforeach
            </select>
        </label>
        <label class="field-style mr-0">
            <span>Стоянка</span>
            <select name="parking" class="page-select">
                <option selected value="">Выберите стоянку</option>
                @foreach($parkings as $parking)
                    @if(request()->get('parking') == $parking->id)
                        <option selected value="{{ $parking->id }}">{{ $parking->title }}</option>
                    @else
                        <option value="{{ $parking->id }}">{{ $parking->title }}</option>
                    @endif
                @endforeach
            </select>
        </label>

        {{--<label class="field-style mr-0">
            <span>Исполнитель</span>
            <select name="parking" class="page-select">
                <option selected value="">Выберите исполнителя</option>
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
        </label>--}}

        <label class="chosen-post">
            <input type="checkbox" @if(request()->get('favorite')) checked @endif
            name="favorite" class="checkbox-chosen">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2.5C12.3788 2.5 12.7251 2.714 12.8945 3.05279L15.4734 8.2106L21.144 9.03541C21.5206 9.0902 21.8335 9.35402 21.9511 9.71599C22.0687 10.078 21.9706 10.4753 21.6981 10.741L17.571 14.7649L18.4994 20.4385C18.5608 20.8135 18.4043 21.1908 18.0957 21.4124C17.787 21.6339 17.3794 21.6614 17.0438 21.4834L12 18.8071L6.95624 21.4834C6.62062 21.6614 6.21306 21.6339 5.9044 21.4124C5.59573 21.1908 5.4393 20.8135 5.50065 20.4385L6.42906 14.7649L2.30193 10.741C2.02942 10.4753 1.93136 10.078 2.04897 9.71599C2.16658 9.35402 2.47946 9.0902 2.85609 9.03541L8.5267 8.2106L11.1056 3.05279C11.275 2.714 11.6213 2.5 12 2.5ZM12 5.73607L10.082 9.57221C9.93561 9.86491 9.65531 10.0675 9.33147 10.1146L5.14842 10.723L8.19813 13.6965C8.43182 13.9243 8.53961 14.2519 8.4869 14.574L7.80004 18.7715L11.5313 16.7917C11.8244 16.6361 12.1756 16.6361 12.4687 16.7917L16.2 18.7715L15.5132 14.574C15.4604 14.2519 15.5682 13.9243 15.8019 13.6965L18.8516 10.723L14.6686 10.1146C14.3448 10.0675 14.0645 9.86491 13.9181 9.57221L12 5.73607Z"
                      fill="#536E9B" />
                <path d="M9 9L3.5 10L7.5 14.5L7 20.5L12 18L17.5 20.5L16.5 14L21 10L15 9L12 4L9 9Z" fill="transparent"/>
            </svg>
        </label>
        <div class="ml-auto">
            <label class="custom-input-wrap">
                <input type="radio" value="column" @if(request()->get('direction', 'column') == 'column') checked @endif class="checkbox-chosen" name="direction">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 5C3 3.89543 3.89543 3 5 3H9C10.1046 3 11 3.89543 11 5V9C11 10.1046 10.1046 11 9 11H5C3.89543 11 3 10.1046 3 9V5ZM9 5H5V9H9V5ZM13 5C13 3.89543 13.8954 3 15 3H19C20.1046 3 21 3.89543 21 5V9C21 10.1046 20.1046 11 19 11H15C13.8954 11 13 10.1046 13 9V5ZM19 5H15V9H19V5ZM3 15C3 13.8954 3.89543 13 5 13H9C10.1046 13 11 13.8954 11 15V19C11 20.1046 10.1046 21 9 21H5C3.89543 21 3 20.1046 3 19V15ZM9 15H5V19H9V15ZM13 15C13 13.8954 13.8954 13 15 13H19C20.1046 13 21 13.8954 21 15V19C21 20.1046 20.1046 21 19 21H15C13.8954 21 13 20.1046 13 19V15ZM19 15H15V19H19V15Z"
                          fill="#536E9B" />
                </svg>
            </label>
            <label class="custom-input-wrap">
                <input type="radio" value="table" @if(request()->get('direction', 'column') == 'table') checked @endif class="checkbox-chosen" name="direction">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 2C20.1046 2 21 2.89543 21 4V6C21 7.10457 20.1046 8 19 8L5 8C3.89543 8 3 7.10457 3 6L3 4C3 2.89543 3.89543 2 5 2L19 2ZM19 6V4L5 4V6L19 6Z"
                          fill="#536E9B" />
                    <path d="M19 16C20.1046 16 21 16.8954 21 18V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20L3 18C3 16.8954 3.89543 16 5 16L19 16ZM19 20V18H5V20H19Z"
                          fill="#536E9B" />
                    <path d="M19 9C20.1046 9 21 9.89543 21 11V13C21 14.1046 20.1046 15 19 15L5 15C3.89543 15 3 14.1046 3 13L3 11C3 9.89543 3.89543 9 5 9L19 9ZM19 13V11L5 11V13L19 13Z"
                          fill="#536E9B" />
                </svg>
            </label>
            <label class="custom-input-wrap">
                <input type="radio" value="row" @if(request()->get('direction', 'column') == 'row') checked @endif class="checkbox-chosen" name="direction">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 3C20.1046 3 21 3.89543 21 5V9C21 10.1046 20.1046 11 19 11L5 11C3.89543 11 3 10.1046 3 9L3 5C3 3.89543 3.89543 3 5 3L19 3ZM19 9V5L5 5L5 9L19 9Z"
                          fill="#536E9B" />
                    <path d="M19 13C20.1046 13 21 13.8954 21 15V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19L3 15C3 13.8954 3.89543 13 5 13L19 13ZM19 19V15L5 15L5 19H19Z"
                          fill="#536E9B" />
                </svg>
            </label>
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

{{--<div class="newtopbar">
    <div class="wrapper">
        <div class="newtopbar__mob">
            <span class="newtopbar__mobtitle"></span>
            <span class="newtopbar__mobarrow"></span>
        </div>
        <nav class="newtopbar__nav">
            <ul class="newtopbar__list s-between">
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
                @unlessrole('Operator')
                <li class="newtopbar__item{{ (request()->route('status_id') == 3) ? ' active' : '' }}">
                    <a href="{{ route('applications.index', ['status_id' => 3]) }}" class="newtopbar__link">Выдано</a>
                </li>
                @endunlessrole
                <li class="newtopbar__item{{ (request()->route('status_id') == 6) ? ' active' : '' }}">
                    <a href="{{ route('applications.index', ['status_id' => 6]) }}" class="newtopbar__link">Отклонено</a>
                </li>
                <li class="newtopbar__item{{
                    (is_null(request()->route('status_id')) && request()->routeIs('applications.index')) ? ' active' : ''
                }}">
                    <a href="{{ route('applications.index') }}" class="newtopbar__link">Все</a>
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
</div>--}}

