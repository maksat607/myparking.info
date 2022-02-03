@extends('layouts.app')

@section('content')
    @include('applications.menu.top_menu_filter')

    <section class="car-table">
        <div class="container">
            <table class="table-wrap">
                @foreach($viewRequests as $viewRequest)
                    <tr class="@if($viewRequest->application->favorite){{ 'select-favorite' }}@endif" id="application_{{ $viewRequest->application->id }}">
                        <td>
                            <div class="favorite" data-app-id="{{ $viewRequest->application->id }}">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2.5C12.3788 2.5 12.7251 2.714 12.8945 3.05279L15.4734 8.2106L21.144 9.03541C21.5206 9.0902 21.8335 9.35402 21.9511 9.71599C22.0687 10.078 21.9706 10.4753 21.6981 10.741L17.571 14.7649L18.4994 20.4385C18.5608 20.8135 18.4043 21.1908 18.0957 21.4124C17.787 21.6339 17.3794 21.6614 17.0438 21.4834L12 18.8071L6.95624 21.4834C6.62062 21.6614 6.21306 21.6339 5.9044 21.4124C5.59573 21.1908 5.4393 20.8135 5.50065 20.4385L6.42906 14.7649L2.30193 10.741C2.02942 10.4753 1.93136 10.078 2.04897 9.71599C2.16658 9.35402 2.47946 9.0902 2.85609 9.03541L8.5267 8.2106L11.1056 3.05279C11.275 2.714 11.6213 2.5 12 2.5ZM12 5.73607L10.082 9.57221C9.93561 9.86491 9.65531 10.0675 9.33147 10.1146L5.14842 10.723L8.19813 13.6965C8.43182 13.9243 8.53961 14.2519 8.4869 14.574L7.80004 18.7715L11.5313 16.7917C11.8244 16.6361 12.1756 16.6361 12.4687 16.7917L16.2 18.7715L15.5132 14.574C15.4604 14.2519 15.5682 13.9243 15.8019 13.6965L18.8516 10.723L14.6686 10.1146C14.3448 10.0675 14.0645 9.86491 13.9181 9.57221L12 5.73607Z"
                                          fill="white"></path>
                                </svg>
                            </div>
                        </td>
                        <td>
                            <h3 class="car-title">
                                <span class="car-show-info" data-app-id="{{ $viewRequest->application ->id }}">
                                    {{ $viewRequest->application->car_title }}
                                </span>
                                {{--<span class="car-show-modal">
                                    <svg width="16" height="14" viewBox="0 0 16 14" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.5 1.5C0.5 0.671573 1.17157 0 2 0H14C14.8284 0 15.5 0.671572 15.5 1.5V9.75C15.5 10.5784 14.8284 11.25 14 11.25H10.5607L8.53033 13.2803C8.23744 13.5732 7.76256 13.5732 7.46967 13.2803L5.43934 11.25H2C1.17157 11.25 0.5 10.5784 0.5 9.75V1.5ZM14 1.5H2V9.75H5.75C5.94891 9.75 6.13968 9.82902 6.28033 9.96967L8 11.6893L9.71967 9.96967C9.86032 9.82902 10.0511 9.75 10.25 9.75H14V1.5Z"
                                              fill="#536E9B"></path>
                                        <path d="M9.125 5.625C9.125 6.24632 8.62132 6.75 8 6.75C7.37868 6.75 6.875 6.24632 6.875 5.625C6.875 5.00368 7.37868 4.5 8 4.5C8.62132 4.5 9.125 5.00368 9.125 5.625Z"
                                              fill="#536E9B"></path>
                                        <path d="M12.125 5.625C12.125 6.24632 11.6213 6.75 11 6.75C10.3787 6.75 9.875 6.24632 9.875 5.625C9.875 5.00368 10.3787 4.5 11 4.5C11.6213 4.5 12.125 5.00368 12.125 5.625Z"
                                              fill="#536E9B"></path>
                                        <path d="M6.125 5.625C6.125 6.24632 5.62132 6.75 5 6.75C4.37868 6.75 3.875 6.24632 3.875 5.625C3.875 5.00368 4.37868 4.5 5 4.5C5.62132 4.5 6.125 5.00368 6.125 5.625Z"
                                              fill="#536E9B"></path>
                                    </svg>
                                    <span class="car__cunt">12</span>
                                </span>--}}
                            </h3>
                            <span class="car__subtitle">{{ $viewRequest->application->parking->title }}</span>
                        </td>
                        <td>
                            <span>VIN</span>
                            {{ $viewRequest->application->vin }}
                        </td>
                        <td>
                            <span>Гос. номер</span>
                            {{ $viewRequest->application->license_plate }}
                        </td>
                        {{--<td class="text-right">
                            <span>{{ $viewRequest->application->partner->name }}</span>
                            <span>{{ $viewRequest->application->external_id }}</span>
                        </td>--}}
                        <td class="text-right">
                            <div class="nowrap-text date-delivery">
                                <span>Дата осмотра</span>
                                {{ $viewRequest->formated_arriving_at }}
                            </div>
                            <div class="nowrap-text">
                                <span>Промежуток времени</span>
                                {{ $viewRequest->formated_arriving_interval }}
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="status-table {{ $viewRequest->application->status->getColorClass() }}">{{$viewRequest->application->status->name}}</div>
                            @if($viewRequest->application->returned)
                                <div class="status-table status-danger">Повтор</div>
                            @endif
                        </td>
                        <td>
                            <div class="car-dd">
                                <div class="car-close-dd"></div>
                                <div class="car-dd-body">
                                    @if($viewRequest->application->status->code == 'storage')
                                        @can('application_issue')
                                            <a href="@if(auth()->user()->hasRole(['Admin', 'Manager']))
                                            {{ route('application.issuance.create', ['application' => $viewRequest->application->id]) }}
                                            @else
                                            {{ route('issue_requests.edit', ['issue_request' => $viewRequest->application->issuance->id]) }}
                                            @endif
                                                " class="link text-success">
                                                <svg class="car-dd-icon" width="21" height="20" viewBox="0 0 21 20"
                                                     fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M18 11.6665C18 11.2063 17.6269 10.8332 17.1667 10.8332L7.16667 10.8332C5.78595 10.8332 4.66667 9.71388 4.66667 8.33317L4.66666 4.99984C4.66666 4.5396 4.29357 4.1665 3.83333 4.1665C3.37309 4.1665 3 4.5396 3 4.99984L3 8.33317C3 10.6344 4.86548 12.4998 7.16667 12.4998L17.1667 12.4998C17.6269 12.4998 18 12.1267 18 11.6665Z"
                                                          fill="#27AE60" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M17.7559 12.2558C18.0814 11.9303 18.0814 11.4027 17.7559 11.0772L14.4226 7.74391C14.0972 7.41848 13.5695 7.41848 13.2441 7.74391C12.9186 8.06935 12.9186 8.59699 13.2441 8.92242L15.9882 11.6665L13.2441 14.4106C12.9186 14.736 12.9186 15.2637 13.2441 15.5891C13.5695 15.9145 14.0972 15.9145 14.4226 15.5891L17.7559 12.2558Z"
                                                          fill="#27AE60" />
                                                </svg>

                                                Выдача
                                            </a>
                                        @endcan
                                    @endif
                                    <a href="{{ route('view_requests.edit', [
                                                    'view_request' => $viewRequest->id,
                                                    ]) }}" class="link">
                                        <svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13.2929 0.292893C13.6834 -0.0976311 14.3166 -0.0976311 14.7071 0.292893L18.7071 4.29289C19.0976 4.68342 19.0976 5.31658 18.7071 5.70711L5.70711 18.7071C5.51957 18.8946 5.26522 19 5 19H1C0.447715 19 0 18.5523 0 18V14C0 13.7348 0.105357 13.4804 0.292893 13.2929L10.2927 3.2931L13.2929 0.292893ZM11 5.41421L2 14.4142V17H4.58579L13.5858 8L11 5.41421ZM15 6.58579L16.5858 5L14 2.41421L12.4142 4L15 6.58579Z"
                                                  fill="#536E9B"></path>
                                        </svg>
                                    </a>

                                    <a href="#" class="link basket delete"
                                       data-deletion-id="deleteViewRequest{{ $viewRequest->id }}"
                                       data-message="Уверены что хотите удалить выбранный элемент?"
                                    >

                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <g opacity="0.6">
                                                <path d="M7 4C7 2.89543 7.89543 2 9 2H15C16.1046 2 17 2.89543 17 4V6H18.9897C18.9959 5.99994 19.0021 5.99994 19.0083 6H21C21.5523 6 22 6.44772 22 7C22 7.55228 21.5523 8 21 8H19.9311L19.0638 20.1425C18.989 21.1891 18.1182 22 17.0689 22H6.93112C5.88184 22 5.01096 21.1891 4.9362 20.1425L4.06888 8H3C2.44772 8 2 7.55228 2 7C2 6.44772 2.44772 6 3 6H4.99174C4.99795 5.99994 5.00414 5.99994 5.01032 6H7V4ZM9 6H15V4H9V6ZM6.07398 8L6.93112 20H17.0689L17.926 8H6.07398ZM10 10C10.5523 10 11 10.4477 11 11V17C11 17.5523 10.5523 18 10 18C9.44772 18 9 17.5523 9 17V11C9 10.4477 9.44772 10 10 10ZM14 10C14.5523 10 15 10.4477 15 11V17C15 17.5523 14.5523 18 14 18C13.4477 18 13 17.5523 13 17V11C13 10.4477 13.4477 10 14 10Z"
                                                      fill="#EB5757"></path>
                                            </g>
                                        </svg>
                                    </a>

                                    <form id="deleteViewRequest{{ $viewRequest->id }}"
                                          method="POST"
                                          action="{{ route('view_requests.destroy', ['view_request' => $viewRequest->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $viewRequests->links() }}
        </div>
    </section>
{{--    <section class="newcart">
        <div class="wrapper">
            <div class="newcart__tablewrap">
                <table class="newcart__col">
                    <thead class="newcart__header">
                    <tr>
                        <th class="active">Называние <i class="fa fa-filter" aria-hidden="true"></i>
                            <div class="newcart__tablefilter">
                                <input type="text" placeholder="Название">
                            </div>
                        </th>
                        <th>VIN <i class="fa fa-filter" aria-hidden="true"></i></th>
                        <th>Гос. номер <i class="fa fa-filter" aria-hidden="true"></i></th>
                        <th>Дата приезда <i class="fa fa-filter" aria-hidden="true"></i></th>
                        <th>Промежуток времени <i class="fa fa-filter" aria-hidden="true"></i></th>
                            <div class="newcart__tablefilter">
                                <select name="" id="">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                        </th>
                        <th>Статус <i class="fa fa-filter" aria-hidden="true"></i></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="newcart__body">
                    @foreach($viewRequests as $viewRequest)
                    <tr>
                        <td>{{ $viewRequest->application->car_title }}</td>
                        <td>{{ $viewRequest->application->vin }}</td>
                        <td>{{ $viewRequest->application->license_plate }}</td>
                        <td>{{ $viewRequest->formated_arriving_at }}</td>
                        <td>{{ $viewRequest->formated_arriving_interval }}</td>
                        <td class="status{{ $viewRequest->application->status->getColorClass() }}">{{$viewRequest->application->status->name}}</td>
                        <td>
                            <button class="newcart__btnpop"></button>
                            <div class="newcart__setting">
                                @if($viewRequest->application->status->code == 'storage')
                                    @can('application_issue')
                                        <a href="{{ route('application.issuance.create', ['application' => $viewRequest->application->id]) }}" >Выдать</a>
                                    @endcan
                                @endif
                                <a href="">Скачать акт</a>
                                <a href="{{ route('view_requests.edit', [
                                                    'view_request' => $viewRequest->id,
                                                    ]) }}">Редактировать</a>
                                <a href="#" class="newcart__del"
                                    onclick="if( confirm('Delete it?') ) {
                                        event.preventDefault();
                                        document.getElementById('deleteViewRequest{{ $viewRequest->id }}').submit(); return true }">Удалить</a>

                                <form id="deleteViewRequest{{ $viewRequest->id }}"
                                      method="POST"
                                      action="{{ route('view_requests.destroy', ['view_request' => $viewRequest->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>--}}
@endsection
