@extends('layouts.app')

@section('content')
@include('applications.menu.top_menu_filter')
<section class="car-col">
    <div class="container">
        <div class="row">
            @foreach($applications as $application)
                <form id="denyApp{{ $application->id }}" method="POST"
                      action="{{ route('application.deny', ['application_id' => $application->id]) }}">
                    @csrf
                    @method('POST')
                </form>
            <div class="col-md-3 @if($application->favorite){{ 'select-favorite' }}@endif" id="application_{{ $application->id }}">
                <div class="car-col__item">
                    <div class="car-slide-wrap">
                        <span class="pagingInfo"></span>
                        <div class="favorite" data-app-id="{{ $application->id }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2.5C12.3788 2.5 12.7251 2.714 12.8945 3.05279L15.4734 8.2106L21.144 9.03541C21.5206 9.0902 21.8335 9.35402 21.9511 9.71599C22.0687 10.078 21.9706 10.4753 21.6981 10.741L17.571 14.7649L18.4994 20.4385C18.5608 20.8135 18.4043 21.1908 18.0957 21.4124C17.787 21.6339 17.3794 21.6614 17.0438 21.4834L12 18.8071L6.95624 21.4834C6.62062 21.6614 6.21306 21.6339 5.9044 21.4124C5.59573 21.1908 5.4393 20.8135 5.50065 20.4385L6.42906 14.7649L2.30193 10.741C2.02942 10.4753 1.93136 10.078 2.04897 9.71599C2.16658 9.35402 2.47946 9.0902 2.85609 9.03541L8.5267 8.2106L11.1056 3.05279C11.275 2.714 11.6213 2.5 12 2.5ZM12 5.73607L10.082 9.57221C9.93561 9.86491 9.65531 10.0675 9.33147 10.1146L5.14842 10.723L8.19813 13.6965C8.43182 13.9243 8.53961 14.2519 8.4869 14.574L7.80004 18.7715L11.5313 16.7917C11.8244 16.6361 12.1756 16.6361 12.4687 16.7917L16.2 18.7715L15.5132 14.574C15.4604 14.2519 15.5682 13.9243 15.8019 13.6965L18.8516 10.723L14.6686 10.1146C14.3448 10.0675 14.0645 9.86491 13.9181 9.57221L12 5.73607Z"
                                      fill="white" />
                            </svg>
                        </div>
                        <div class="car-slide">
                            @if($application->attachments->where('file_type','image')->count()>0)
                                @foreach($application->attachments->where('file_type','image')->all() as $attachment)
                                    <div class="newcart__imgwrap">
                                        <a href="{{ $attachment->url }}">
                                            <img src="{{ $attachment->thumbnail_url }}" class="car-row__image">
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="newcart__imgwrap">
                                    <img src="{{ $application->default_attachment->thumbnail_url }}" class="car-row__image">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="car-col__info">
                        <div class="car-show-modal" data-app-id="{{ $application->id }}">
                            <h3 class="car-title">
                                <span class="car-show-info">{{ $application->car_title }}</span>
                                <span class="d-none">
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
                                </span>
                            </h3>

                            <span class="car__subtitle">ID {{ @$application->id }}</span>
                            <span class="car__subtitle">{{ @$application->parking->title }}</span>

                        </div>
                        <div class="car-col__info-item">
                            <div>
                                <span>VIN</span>
                                {{ $application->vin }}
                            </div>
                            <div>
                                <span>Гос. номер</span>
                                {{ $application->license_plate }}
                            </div>
                        </div>

                        <div class="car-col__info-item">
                            <div @if($application->status->id==7) class="arrived-date-class" @endif>
                                <span>Дата постановки</span>
                                @if($application->status->id==7)
                                    @php
                                        $dateDataApplication = ($application->arriving_at) ? $application->arriving_at->format('d.m.Y') : now()->format('d.m.Y');
                                        $dateTime =$dateDataApplication.' '.str_replace(' ', '', $application->arriving_interval);
                                    @endphp
                                    {{ $dateTime }}
                                @else
                                {{ $application->formated_arrived_at }}
                                @endif

                            </div>
                            <div  @if($application->issuance) class="issue-date-class" @endif>
                                <span>Дата выдачи</span>
                                @if($application->issuance)
                                    @php
                                            $dateDataApplication = ($application->issuance->arriving_at) ? $application->issuance->arriving_at->format('d.m.Y') : now()->format('d.m.Y');

                                            $interval = ($application->issuance->arriving_interval) ? $application->issuance->arriving_interval : '';
                                            $dateTime =$dateDataApplication.' '.str_replace(' ', '', $interval);
                                    @endphp
                                    {{ $dateTime }}

                                @else
                                    {{ $application->formated_issued_at }}
                                @endif
                            </div>
                        </div>
                        <div class="car-col__info-item">
                            <div class="car-status-wrap">
                                @if($application->viewRequests->last())
                                    <span class="car-status status-new">Есть осмотр</span>
                                @endif
                                @if($application->issuance)
                                   <span class="car-status status-new">На выдачу</span>
                                @endif
                                <span class="car-status {{ $application->status->getColorClass() }}">{{$application->status->name}}</span>
                                @if($application->returned)
                                    <span class="car-status status-danger">Повтор</span>
                                @endif

                            </div>
                            @if($application->partner)
                                <span>{{ $application->partner->name }}</span>
                            @else
                                <span>{{ $application->partner_id }}</span>
                            @endif
{{--                            <span>{{ $application->partner->name }}</span>--}}
                            <span>{{ $application->external_id }}</span>

                            <div class="car-dd">
                                <div class="car-close-dd"></div>
                                <div class="car-dd-body">
                                    @if($application->acceptions && $user->hasRole(['SuperAdmin', 'Admin', 'Manager']))
                                        @can('application_to_accepted')
                                            <a href="{{ route('applications.edit', ['application' => $application->id]) }}" class="text-success btn">Принять</a>
                                            <a href="{{ route('application.deny', ['application_id' => $application->id]) }}"
                                               data-confirm-id="{{ $application->id }}"
                                               data-confirm-url="{{ route('application.deny', ['application_id' => $application->id]) }}"
                                               data-confirm-type="deny"
                                               data-confirm-message="Уверены что хотите отклонить?"
                                               class="text-danger deny btn">Отклонить</a>
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
                                               data-confirm-message="Уверены что хотите удалить выбранный элемент?"
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
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.99967 1.6665C10.4599 1.6665 10.833 2.0396 10.833 2.49984V11.3213L12.7438 9.41058C13.0692 9.08515 13.5968 9.08515 13.9223 9.41058C14.2477 9.73602 14.2477 10.2637 13.9223 10.5891L10.5889 13.9224C10.2635 14.2479 9.73585 14.2479 9.41042 13.9224L6.07709 10.5891C5.75165 10.2637 5.75165 9.73602 6.07709 9.41058C6.40252 9.08515 6.93016 9.08515 7.2556 9.41058L9.16634 11.3213V2.49984C9.16634 2.0396 9.53944 1.6665 9.99967 1.6665ZM4.16634 14.1665C4.62658 14.1665 4.99967 14.5396 4.99967 14.9998V16.6665H14.9997V14.9998C14.9997 14.5396 15.3728 14.1665 15.833 14.1665C16.2932 14.1665 16.6663 14.5396 16.6663 14.9998V16.6665C16.6663 17.587 15.9201 18.3332 14.9997 18.3332H4.99967C4.0792 18.3332 3.33301 17.587 3.33301 16.6665V14.9998C3.33301 14.5396 3.7061 14.1665 4.16634 14.1665Z"
                                                      fill="#536E9B" />
                                            </svg>
                                        </a>
                                    @endhasanyrole
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
                                           data-confirm-message="Уверены что хотите удалить выбранный элемент?"
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

                                    <span class="right-btn-group d-flex">
                                        @can('application_to_inspection')
                                        <a href="{{ route('view_requests.create', ['application' => $application->id]) }}" class="link text-warning">
                                            <svg class="car-dd-icon" width="20" height="20" viewBox="0 0 20 20"
                                                 fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12.5003 9.99984C12.5003 11.3806 11.381 12.4998 10.0003 12.4998C8.61961 12.4998 7.50033 11.3806 7.50033 9.99984C7.50033 8.61913 8.61961 7.49984 10.0003 7.49984C11.381 7.49984 12.5003 8.61913 12.5003 9.99984Z"
                                                      fill="#F2994A" />
                                                <path d="M18.2457 9.62716C16.4472 6.03013 13.2529 4.1665 10.0003 4.1665C6.74772 4.1665 3.55349 6.03013 1.75497 9.62716C1.63767 9.86177 1.63767 10.1379 1.75497 10.3725C3.55349 13.9695 6.74772 15.8332 10.0003 15.8332C13.2529 15.8332 16.4472 13.9695 18.2457 10.3725C18.363 10.1379 18.363 9.86177 18.2457 9.62716ZM10.0003 14.1665C7.52634 14.1665 4.99871 12.8159 3.44171 9.99984C4.99871 7.18382 7.52634 5.83317 10.0003 5.83317C12.4743 5.83317 15.0019 7.18382 16.5589 9.99984C15.0019 12.8159 12.4743 14.1665 10.0003 14.1665Z"
                                                      fill="#F2994A" />
                                            </svg>
                                            Осмотр
                                        </a>
                                        @endcan
                                        @can('application_to_issue')
                                            @if($application->issuance)
                                                <a href="@if(auth()->user()->hasRole(['Admin', 'Manager']))
                                                                {{ route('application.issuance.create', ['application' => $application->id]) }}
                                                            @else
                                                                {{ route('issue_requests.edit', ['issue_request' => $application->issuance->id]) }}
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

                                                    Выдать
                                                </a>
                                            @else
                                                <a href="@if(auth()->user()->hasRole(['Admin', 'Manager']))
                                                        {{ route('application.issuance.create', ['application' => $application->id]) }}
                                                    @else
                                                        {{ route('issue_requests.create', ['application' => $application->id]) }}
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

                                                    Выдать
                                                </a>
                                            @endif
                                        @endcan
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        {{ $applications->links() }}
    </div>
</section>


{{--<section class="newcart">
    <div class="wrapper">
        <div class="newcart__list d-flex">
            @foreach($applications as $application)
            <article class="newcart__item" id="application_{{ $application->id }}">
                <div class="newcart__wrapp @if($application->favorite) newcart__save @else newcart__nosave @endif">
                    <div class="newcart__img lazy">
                        @if($application->attachments->isNotEmpty())
                            @foreach($application->attachments as $attachment)
                            <div class="newcart__imgwrap">
                                <a href="{{ $attachment->url }}">
                                    <img src="{{ $attachment->thumbnail_url }}" alt="">
                                </a>
                            </div>
                            @endforeach
                        @else
                            <div class="newcart__imgwrap">
                                <img src="{{ $application->default_attachment->thumbnail_url }}" alt="">
                            </div>
                        @endif

                    </div>
                    <div class="favorite" data-app-id="{{ $application->id }}"></div>
                </div>
                <div class="newcart__topbtn">
                    @can('application_update')
                    <a class="newcart__edit" href="{{ route('applications.edit', ['application' => $application->id]) }}">
                        редактировать
                    </a>
                    @endcan
                    @can('application_delete')
                    <a class="newcart__delete delete" href="#"
                       data-deletion-id="deleteApp{{ $application->id }}"
                       data-message="Уверены что хотите удалить выбранный элемент?">
                        удалить
                    </a>
                    <form id="deleteApp{{ $application->id }}" method="POST"
                          action="{{ route('applications.destroy', ['application' => $application->id]) }}">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endcan
                </div>
                <h3 class="newcart__title">{{ $application->car_title }}</h3>
                <div class="newcart__type s-between">
                    {{ $application->carType->name }}
                    @if($application->returned)
                    <span class="newcart__repeat">Повтор</span>
                    @endif
                </div>
                <div class="newcart__vin s-between">
                    Vin: <span class="newcart__vinnum">{{ $application->vin }}</span>
                </div>
                <div class="newcart__numberwrap s-between">
                    Гос. номер: <span class="newcart__number">{{ $application->license_plate }}</span>
                </div>
                @if($application->acceptions)
                <div class="newcart__dd">
                    <div class="newcart__btn"><span class="newcart__btntitle">Заявка:</span>
                        <span class="newcart__status blue">Постановка</span></div>
                    <div class="newcart__des">
                        <ul class="newcart__deslist">
                            <li class="newcart__desitem">
                                <span>Дата прибытия:</span>
                                <strong>{{ $application->formated_arriving_at }}</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Промежуток:</span>
                                <strong>{{ $application->arriving_interval }}</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата создания:</span>
                                <strong>{{ $application->formated_created_at }}</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата завершения:</span>
                                <strong>
                                    {{ $application->formated_issued_at }}
                                </strong>
                            </li>
                        </ul>
                    </div>
                </div>
                @elseif($application->issuance)
                    <div class="newcart__dd">
                        <div class="newcart__btn"><span class="newcart__btntitle">Заявка:</span>
                            <span class="newcart__status pink">Выдача</span></div>
                        <div class="newcart__des">
                            <ul class="newcart__deslist">
                                <li class="newcart__desitem">
                                    <span>Дата прибытия:</span>
                                    <strong>{{ $application->formated_arriving_at }}</strong>
                                </li>
                                <li class="newcart__desitem">
                                    <span>Промежуток:</span>
                                    <strong>{{ $application->arriving_interval }}</strong>
                                </li>
                                <li class="newcart__desitem">
                                    <span>Дата создания:</span>
                                    <strong>{{ $application->formated_created_at }}</strong>
                                </li>
                                <li class="newcart__desitem">
                                    <span>Дата завершения:</span>
                                    <strong>
                                        {{ $application->formated_issued_at }}
                                    </strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
                @if($application->viewRequests->last())
                    <div class="newcart__dd">
                        <div class="newcart__btn"><span class="newcart__btntitle">Заявка:</span>
                            <span class="newcart__status pink">Осмотр</span></div>
                        <div class="newcart__des">
                            <ul class="newcart__deslist">
                                <li class="newcart__desitem">
                                    <span>Дата осмотра:</span>
                                    <strong>{{ $application->viewRequests->last()->formated_arriving_at }}</strong>
                                </li>
                                <li class="newcart__desitem">
                                    <span>Промежуток:</span>
                                    <strong>{{ $application->viewRequests->last()->arriving_interval }}</strong>
                                </li>
                                <li class="newcart__desitem">
                                    <span>Дата создания:</span>
                                    <strong>{{ $application->viewRequests->last()->formated_created_at }}</strong>
                                </li>
                                <li class="newcart__desitem">
                                    <span>Дата завершения:</span>
                                    <strong>
                                        {{ $application->viewRequests->last()->formated_finished_at }}
                                    </strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
                @if(!$application->acceptions && $application->status->code != 'pending')
                <div class="newcart__dd">
                    <div class="newcart__btn"><span class="newcart__btntitle">Статус:</span> <span
                            class="newcart__status {{ $application->status->getColorClass() }}">{{$application->status->name}}</span></div>
                    <div class="newcart__des">
                        <ul class="newcart__deslist">
                            <li class="newcart__desitem">
                                <span>Дата принятия:</span>
                                <strong>{{ $application->formated_arrived_at }}</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Дата выдачи:</span>
                                <strong>{{ $application->formated_issued_at }}</strong>
                            </li>
                            <li class="newcart__desitem">
                                <span>Сумма простоя:</span>
                                <strong>{{ $application->parked_price_regular }} ({{ $application->parked_days_regular }} дн.)</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                @endif
                <a href="#" class="newcart__moreinfo @if($application->car_additional) have-comments @endif"
                   data-app-id="{{ $application->id }}"
                   title="{{ Str::words($application->car_additional, 10, '...') }}"
                >
                    Подробное описание
                </a>

                @if($application->acceptions)
                    @can('application_to_accepted')
                    <div class="newcart__confirmbtn">
                        <a href="{{ route('applications.edit', ['application' => $application->id]) }}"
                           class="newcart__accept issue">Принять</a>
                        <a href="{{ route('application.deny', ['application_id' => $application->id]) }}"
                           class="newcart__deny">Отказать</a>
                    </div>
                    @endcan
                @elseif($application->status->code == 'storage')
                    <div class="newcart__confirmbtn">
                        @can('application_issue')
                            <a href="{{ route('application.issuance.create', ['application' => $application->id]) }}" class="newcart__bluebtn">Выдать</a>
                        @endcan
                        @can('application_to_issue')
                            @if($application->issuance)
                                <a href="{{ route('issue_requests.edit', ['issue_request' => $application->issuance->id]) }}" class="newcart__bluebtn">Выдача</a>
                            @else
                                <a href="{{ route('issue_requests.create', ['application' => $application->id]) }}" class="newcart__bluebtn">Выдача</a>
                            @endif
                        @endcan
                        @can('application_to_inspection')
                        <a href="{{ route('view_requests.create', ['application' => $application->id]) }}" class="newcart__bluebtn">Осмотр</a>
                        @endcan
                        @hasanyrole('Admin|Manager')
                        <a href="{{ route('application.generate-act', ['application' => $application->id]) }}" class="newcart__bluebtn">Скачать акт</a>
                        @endhasanyrole
                    </div>
                @endif

            </article>
            @endforeach
        </div>
        {{ $applications->links() }}
    </div>
</section>--}}
@endsection
