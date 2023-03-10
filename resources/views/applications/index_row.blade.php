@extends('layouts.app')

@section('content')
    @include('applications.menu.top_menu_filter')
    <section class="car-row">
        <div class="container">
            @foreach($applications as $application)
            <div class="car-row__item d-flex @if($application->favorite){{ 'select-favorite' }}@endif" id="application_{{ $application->id }}">
                <div class="car-slide-wrap">
                    <span class="pagingInfo"></span>
                    <div class="favorite">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2.5C12.3788 2.5 12.7251 2.714 12.8945 3.05279L15.4734 8.2106L21.144 9.03541C21.5206 9.0902 21.8335 9.35402 21.9511 9.71599C22.0687 10.078 21.9706 10.4753 21.6981 10.741L17.571 14.7649L18.4994 20.4385C18.5608 20.8135 18.4043 21.1908 18.0957 21.4124C17.787 21.6339 17.3794 21.6614 17.0438 21.4834L12 18.8071L6.95624 21.4834C6.62062 21.6614 6.21306 21.6339 5.9044 21.4124C5.59573 21.1908 5.4393 20.8135 5.50065 20.4385L6.42906 14.7649L2.30193 10.741C2.02942 10.4753 1.93136 10.078 2.04897 9.71599C2.16658 9.35402 2.47946 9.0902 2.85609 9.03541L8.5267 8.2106L11.1056 3.05279C11.275 2.714 11.6213 2.5 12 2.5ZM12 5.73607L10.082 9.57221C9.93561 9.86491 9.65531 10.0675 9.33147 10.1146L5.14842 10.723L8.19813 13.6965C8.43182 13.9243 8.53961 14.2519 8.4869 14.574L7.80004 18.7715L11.5313 16.7917C11.8244 16.6361 12.1756 16.6361 12.4687 16.7917L16.2 18.7715L15.5132 14.574C15.4604 14.2519 15.5682 13.9243 15.8019 13.6965L18.8516 10.723L14.6686 10.1146C14.3448 10.0675 14.0645 9.86491 13.9181 9.57221L12 5.73607Z"
                                  fill="#fff" />
                            <path d="M9 9L3.5 10L7.5 14.5L7 20.5L12 18L17.5 20.5L16.5 14L21 10L15 9L12 4L9 9Z" fill="transparent"/>
                        </svg>
                    </div>
                    <div class="car-slide">
                        @if($application->attachments->isNotEmpty())
                            @foreach($application->attachments as $attachment)
                                <div>
                                    <a href="{{ $attachment->url }}">
                                        <img src="{{ $attachment->thumbnail_url }}" class="car-row__image">
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div>
                                <img src="{{ $application->default_attachment->thumbnail_url }}" class="car-row__image">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="car-row__info d-flex">
                    <div class="car-row__col-6">
                        <div>
                            <h3 class="car-title">
                                <span class="car-show-info" data-app-id="{{ $application ->id }}">{{ $application->car_title }}</span>
{{--                                <span class="car-show-modal">
                                    <svg width="16" height="14" viewBox="0 0 16 14" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0.5 1.5C0.5 0.671573 1.17157 0 2 0H14C14.8284 0 15.5 0.671572 15.5 1.5V9.75C15.5 10.5784 14.8284 11.25 14 11.25H10.5607L8.53033 13.2803C8.23744 13.5732 7.76256 13.5732 7.46967 13.2803L5.43934 11.25H2C1.17157 11.25 0.5 10.5784 0.5 9.75V1.5ZM14 1.5H2V9.75H5.75C5.94891 9.75 6.13968 9.82902 6.28033 9.96967L8 11.6893L9.71967 9.96967C9.86032 9.82902 10.0511 9.75 10.25 9.75H14V1.5Z"
                                              fill="#536E9B" />
                                        <path d="M9.125 5.625C9.125 6.24632 8.62132 6.75 8 6.75C7.37868 6.75 6.875 6.24632 6.875 5.625C6.875 5.00368 7.37868 4.5 8 4.5C8.62132 4.5 9.125 5.00368 9.125 5.625Z"
                                              fill="#536E9B" />
                                        <path d="M12.125 5.625C12.125 6.24632 11.6213 6.75 11 6.75C10.3787 6.75 9.875 6.24632 9.875 5.625C9.875 5.00368 10.3787 4.5 11 4.5C11.6213 4.5 12.125 5.00368 12.125 5.625Z"
                                              fill="#536E9B" />
                                        <path d="M6.125 5.625C6.125 6.24632 5.62132 6.75 5 6.75C4.37868 6.75 3.875 6.24632 3.875 5.625C3.875 5.00368 4.37868 4.5 5 4.5C5.62132 4.5 6.125 5.00368 6.125 5.625Z"
                                              fill="#536E9B" />
                                    </svg>
                                    <span class="car__cunt">12</span>
                                </span>--}}
                            </h3>
                            <span class="car__subtitle">ID {{ @$application->id }}</span>
                            <span class="car__subtitle">{{ @$application->parking->title }}</span>
                        </div>
                        <div class="d-flex">
                            <div class="car-row__vin">
                                <span>VIN</span>
                                {{ $application->vin }}
                            </div>
                            <div>
                                <span>??????. ??????????</span>
                                {{ $application->license_plate }}
                            </div>
                        </div>
                    </div>
                    <div class="car-row__col-6 text-right">
                        <div class="fs-0">
                            <span class="car-row__status {{ $application->status->getColorClass() }}">{{$application->status->name}}</span>
                            @if($application->returned)
                                <span class="car-row__status status-danger">????????????</span>
                            @endif
                            <span>{{ $application->partner->name }}</span>
                            <span>{{ $application->external_id }}</span>
                        </div>
                        <div class="d-flex">
                            <div class="date-delivery ml-auto">
                                <span>???????? ????????????????????</span>
                                {{ $application->formated_arrived_at }}
                            </div>
                            <div>
                                <span>???????? ????????????</span>
                                {{ $application->formated_issued_at }}
                            </div>
                        </div>
                        <div class="car-dd">
                            <div class="car-close-dd"></div>
                            {{--<div class="car-dd-body">
                                @if($application->acceptions)
                                    @can('application_to_accepted')
                                        <a href="{{ route('applications.edit', ['application' => $application->id]) }}" class="btn btn-success">??????????????</a>
                                        <a href="{{ route('application.deny', ['application_id' => $application->id]) }}" class="btn btn-danger">??????????????????</a>
                                    @endcan
                                    @elseif($application->status->code == 'storage')
                                    @hasanyrole('Admin|Manager')
                                        <a href="{{ route('application.generate-act', ['application' => $application->id]) }}" class="link">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 2C12.5523 2 13 2.44772 13 3V13.5858L15.2929 11.2929C15.6834 10.9024 16.3166 10.9024 16.7071 11.2929C17.0976 11.6834 17.0976 12.3166 16.7071 12.7071L12.7071 16.7071C12.3166 17.0976 11.6834 17.0976 11.2929 16.7071L7.29289 12.7071C6.90237 12.3166 6.90237 11.6834 7.29289 11.2929C7.68342 10.9024 8.31658 10.9024 8.70711 11.2929L11 13.5858V3C11 2.44772 11.4477 2 12 2ZM5 17C5.55228 17 6 17.4477 6 18V20H18V18C18 17.4477 18.4477 17 19 17C19.5523 17 20 17.4477 20 18V20C20 21.1046 19.1046 22 18 22H6C4.89543 22 4 21.1046 4 20V18C4 17.4477 4.44772 17 5 17Z"
                                                      fill="#536E9B" />
                                            </svg>
                                            ?????????????? ??????
                                        </a>
                                    @endhasanyrole
                                    @can('application_update')
                                        <a href="{{ route('applications.edit', ['application' => $application->id]) }}" class="link">
                                            <svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M13.2929 0.292893C13.6834 -0.0976311 14.3166 -0.0976311 14.7071 0.292893L18.7071 4.29289C19.0976 4.68342 19.0976 5.31658 18.7071 5.70711L5.70711 18.7071C5.51957 18.8946 5.26522 19 5 19H1C0.447715 19 0 18.5523 0 18V14C0 13.7348 0.105357 13.4804 0.292893 13.2929L10.2927 3.2931L13.2929 0.292893ZM11 5.41421L2 14.4142V17H4.58579L13.5858 8L11 5.41421ZM15 6.58579L16.5858 5L14 2.41421L12.4142 4L15 6.58579Z"
                                                      fill="#536E9B" />
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('application_delete')
                                        <a href="#" class="link basket delete"
                                           data-deletion-id="deleteApp{{ $application->id }}"
                                           data-message="?????????????? ?????? ???????????? ?????????????? ?????????????????? ???????????????"
                                        >
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <g opacity="0.6">
                                                    <path d="M7 4C7 2.89543 7.89543 2 9 2H15C16.1046 2 17 2.89543 17 4V6H18.9897C18.9959 5.99994 19.0021 5.99994 19.0083 6H21C21.5523 6 22 6.44772 22 7C22 7.55228 21.5523 8 21 8H19.9311L19.0638 20.1425C18.989 21.1891 18.1182 22 17.0689 22H6.93112C5.88184 22 5.01096 21.1891 4.9362 20.1425L4.06888 8H3C2.44772 8 2 7.55228 2 7C2 6.44772 2.44772 6 3 6H4.99174C4.99795 5.99994 5.00414 5.99994 5.01032 6H7V4ZM9 6H15V4H9V6ZM6.07398 8L6.93112 20H17.0689L17.926 8H6.07398ZM10 10C10.5523 10 11 10.4477 11 11V17C11 17.5523 10.5523 18 10 18C9.44772 18 9 17.5523 9 17V11C9 10.4477 9.44772 10 10 10ZM14 10C14.5523 10 15 10.4477 15 11V17C15 17.5523 14.5523 18 14 18C13.4477 18 13 17.5523 13 17V11C13 10.4477 13.4477 10 14 10Z"
                                                          fill="#EB5757" />
                                                </g>
                                            </svg>
                                        </a>
                                        <form id="deleteApp{{ $application->id }}" method="POST"
                                              action="{{ route('applications.destroy', ['application' => $application->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endcan
                                    @can('application_to_inspection')
                                        <a href="{{ route('view_requests.create', ['application' => $application->id]) }}" class="btn btn-warning">???????????? ???? ????????????</a>
                                    @endcan
                                    @can('application_to_issue')
                                        @if($application->issuance)
                                            <a href="@if(auth()->user()->hasRole(['Admin', 'Manager']))
                                                    {{ route('application.issuance.create', ['application' => $application->id]) }}
                                                @else
                                                    {{ route('issue_requests.edit', ['issue_request' => $application->issuance->id]) }}
                                                @endif" class="btn btn-success">???????????? ???? ????????????</a>
                                        @else
                                            <a href="@if(auth()->user()->hasRole(['Admin', 'Manager']))
                                                    {{ route('application.issuance.create', ['application' => $application->id]) }}
                                                @else
                                                    {{ route('issue_requests.create', ['application' => $application->id]) }}
                                                @endif" class="btn btn-success">???????????? ???? ????????????</a>
                                        @endif
                                    @endcan
                                @endif
                            </div>--}}
                            <div class="car-dd-body">
                                @if($application->acceptions && $user->hasRole(['SuperAdmin', 'Admin', 'Manager']))
                                    @can('application_to_accepted')
                                        <a href="{{ route('applications.edit', ['application' => $application->id]) }}" class="btn btn-success">??????????????</a>
                                        <a href="{{ route('application.deny', ['application_id' => $application->id]) }}"
                                           data-confirm-id="{{ $application->id }}"
                                           data-confirm-url="{{ route('application.deny', ['application_id' => $application->id]) }}"
                                           data-confirm-type="deny"
                                           data-confirm-message="?????????????? ?????? ???????????? ???????????????????"
                                           class="btn btn-danger deny">??????????????????</a>
                                    @endcan
                                @elseif(
                                        ($application->acceptions ||
                                            $application->status->code == 'draft' ||
                                            $application->status->code == 'cancelled-by-us'
                                        ))
                                    @can('update', $application)
                                        <a href="{{ route('applications.edit', ['application' => $application->id]) }}" class="link">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M13.5774 1.91058C13.9028 1.58514 14.4305 1.58514 14.7559 1.91058L18.0893 5.24391C18.4147 5.56935 18.4147 6.09699 18.0893 6.42243L7.25592 17.2558C7.09964 17.412 6.88768 17.4998 6.66667 17.4998H3.33333C2.8731 17.4998 2.5 17.1267 2.5 16.6665V13.3332C2.5 13.1122 2.5878 12.9002 2.74408 12.7439L11.0772 4.41075L13.5774 1.91058ZM11.6667 6.17835L4.16667 13.6783V15.8332H6.32149L13.8215 8.33317L11.6667 6.17835ZM15 7.15466L16.3215 5.83317L14.1667 3.67835L12.8452 4.99984L15 7.15466Z"
                                                      fill="#536E9B" />
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('delete', $application)
                                        <a href="#" class="link basket delete"
                                           data-confirm-id="deleteApp{{ $application->id }}"
                                           data-confirm-type="delete"
                                           data-confirm-message="?????????????? ?????? ???????????? ?????????????? ?????????????????? ???????????????"
                                        >
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <g opacity="0.6">
                                                    <path d="M5.83366 3.33317C5.83366 2.4127 6.57985 1.6665 7.50033 1.6665H12.5003C13.4208 1.6665 14.167 2.4127 14.167 3.33317V4.99984H15.8251C15.8302 4.99979 15.8354 4.99979 15.8405 4.99984H17.5003C17.9606 4.99984 18.3337 5.37293 18.3337 5.83317C18.3337 6.29341 17.9606 6.6665 17.5003 6.6665H16.6096L15.8868 16.7852C15.8245 17.6574 15.0988 18.3332 14.2244 18.3332H5.77626C4.90186 18.3332 4.17613 17.6574 4.11383 16.7852L3.39106 6.6665H2.50033C2.04009 6.6665 1.66699 6.29341 1.66699 5.83317C1.66699 5.37293 2.04009 4.99984 2.50033 4.99984H4.16011C4.16528 4.99979 4.17044 4.99979 4.17559 4.99984H5.83366V3.33317ZM7.50033 4.99984H12.5003V3.33317H7.50033V4.99984ZM5.06197 6.6665L5.77626 16.6665H14.2244L14.9387 6.6665H5.06197ZM8.33366 8.33317C8.7939 8.33317 9.16699 8.70627 9.16699 9.1665V14.1665C9.16699 14.6267 8.7939 14.9998 8.33366 14.9998C7.87342 14.9998 7.50033 14.6267 7.50033 14.1665V9.1665C7.50033 8.70627 7.87342 8.33317 8.33366 8.33317ZM11.667 8.33317C12.1272 8.33317 12.5003 8.70627 12.5003 9.1665V14.1665C12.5003 14.6267 12.1272 14.9998 11.667 14.9998C11.2068 14.9998 10.8337 14.6267 10.8337 14.1665V9.1665C10.8337 8.70627 11.2068 8.33317 11.667 8.33317Z"
                                                          fill="#EB5757" />
                                                </g>
                                            </svg>
                                        </a>
                                        <form id="deleteApp{{ $application->id }}" method="POST"
                                              action="{{ route('applications.delete', ['application' => $application->id]) }}">
                                            @csrf
                                            @method('POST')
                                        </form>
                                    @endcan
                                @elseif($application->status->code == 'storage')
                                    @hasanyrole('Admin|Manager')
                                    <a href="{{ route('application.generate-act', ['application' => $application->id]) }}" class="link">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 2C12.5523 2 13 2.44772 13 3V13.5858L15.2929 11.2929C15.6834 10.9024 16.3166 10.9024 16.7071 11.2929C17.0976 11.6834 17.0976 12.3166 16.7071 12.7071L12.7071 16.7071C12.3166 17.0976 11.6834 17.0976 11.2929 16.7071L7.29289 12.7071C6.90237 12.3166 6.90237 11.6834 7.29289 11.2929C7.68342 10.9024 8.31658 10.9024 8.70711 11.2929L11 13.5858V3C11 2.44772 11.4477 2 12 2ZM5 17C5.55228 17 6 17.4477 6 18V20H18V18C18 17.4477 18.4477 17 19 17C19.5523 17 20 17.4477 20 18V20C20 21.1046 19.1046 22 18 22H6C4.89543 22 4 21.1046 4 20V18C4 17.4477 4.44772 17 5 17Z"
                                                  fill="#536E9B" />
                                        </svg>
                                        ?????????????? ??????
                                    </a>
                                    @endhasanyrole
                                    @can('update', $application)
                                        <a href="{{ route('applications.edit', ['application' => $application->id]) }}" class="link">
                                            <svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M13.2929 0.292893C13.6834 -0.0976311 14.3166 -0.0976311 14.7071 0.292893L18.7071 4.29289C19.0976 4.68342 19.0976 5.31658 18.7071 5.70711L5.70711 18.7071C5.51957 18.8946 5.26522 19 5 19H1C0.447715 19 0 18.5523 0 18V14C0 13.7348 0.105357 13.4804 0.292893 13.2929L10.2927 3.2931L13.2929 0.292893ZM11 5.41421L2 14.4142V17H4.58579L13.5858 8L11 5.41421ZM15 6.58579L16.5858 5L14 2.41421L12.4142 4L15 6.58579Z"
                                                      fill="#536E9B" />
                                            </svg>
                                        </a>
                                    @endcan
                                    @can('delete', $application)
                                        <a href="#" class="link basket delete"
                                           data-confirm-id="deleteApp{{ $application->id }}"
                                           data-confirm-type="delete"
                                           data-confirm-message="?????????????? ?????? ???????????? ?????????????? ?????????????????? ???????????????"
                                        >
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <g opacity="0.6">
                                                    <path d="M7 4C7 2.89543 7.89543 2 9 2H15C16.1046 2 17 2.89543 17 4V6H18.9897C18.9959 5.99994 19.0021 5.99994 19.0083 6H21C21.5523 6 22 6.44772 22 7C22 7.55228 21.5523 8 21 8H19.9311L19.0638 20.1425C18.989 21.1891 18.1182 22 17.0689 22H6.93112C5.88184 22 5.01096 21.1891 4.9362 20.1425L4.06888 8H3C2.44772 8 2 7.55228 2 7C2 6.44772 2.44772 6 3 6H4.99174C4.99795 5.99994 5.00414 5.99994 5.01032 6H7V4ZM9 6H15V4H9V6ZM6.07398 8L6.93112 20H17.0689L17.926 8H6.07398ZM10 10C10.5523 10 11 10.4477 11 11V17C11 17.5523 10.5523 18 10 18C9.44772 18 9 17.5523 9 17V11C9 10.4477 9.44772 10 10 10ZM14 10C14.5523 10 15 10.4477 15 11V17C15 17.5523 14.5523 18 14 18C13.4477 18 13 17.5523 13 17V11C13 10.4477 13.4477 10 14 10Z"
                                                          fill="#EB5757" />
                                                </g>
                                            </svg>
                                        </a>
                                        <form id="deleteApp{{ $application->id }}" method="POST"
                                              action="{{ route('applications.delete', ['application' => $application->id]) }}">
                                            @csrf
                                            @method('POST')
                                        </form>
                                    @endcan

                                    @can('application_to_inspection')
                                        <a href="{{ route('view_requests.create', ['application' => $application->id]) }}" class="btn btn-warning">???????????? ???? ????????????</a>
                                    @endcan
                                    @can('application_to_issue')
                                        @if($application->issuance)
                                            <a href="@if(auth()->user()->hasRole(['Admin', 'Manager']))
                                            {{ route('application.issuance.create', ['application' => $application->id]) }}
                                            @else
                                            {{ route('issue_requests.edit', ['issue_request' => $application->issuance->id]) }}
                                            @endif" class="btn btn-success">???????????? ???? ????????????</a>
                                        @else
                                            <a href="@if(auth()->user()->hasRole(['Admin', 'Manager']))
                                            {{ route('application.issuance.create', ['application' => $application->id]) }}
                                            @else
                                            {{ route('issue_requests.create', ['application' => $application->id]) }}
                                            @endif" class="btn btn-success">???????????? ???? ????????????</a>
                                        @endif
                                    @endcan

                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            {{ $applications->links() }}
        </div>
    </section>
@endsection
