<div class="modal-block active">
    <div class="ov-test">
        <div class="car-row__item d-flex">
            <div class="car-slide-wrap">
                <span class="pagingInfo"></span>
                <div class="favorite">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2.5C12.3788 2.5 12.7251 2.714 12.8945 3.05279L15.4734 8.2106L21.144 9.03541C21.5206 9.0902 21.8335 9.35402 21.9511 9.71599C22.0687 10.078 21.9706 10.4753 21.6981 10.741L17.571 14.7649L18.4994 20.4385C18.5608 20.8135 18.4043 21.1908 18.0957 21.4124C17.787 21.6339 17.3794 21.6614 17.0438 21.4834L12 18.8071L6.95624 21.4834C6.62062 21.6614 6.21306 21.6339 5.9044 21.4124C5.59573 21.1908 5.4393 20.8135 5.50065 20.4385L6.42906 14.7649L2.30193 10.741C2.02942 10.4753 1.93136 10.078 2.04897 9.71599C2.16658 9.35402 2.47946 9.0902 2.85609 9.03541L8.5267 8.2106L11.1056 3.05279C11.275 2.714 11.6213 2.5 12 2.5ZM12 5.73607L10.082 9.57221C9.93561 9.86491 9.65531 10.0675 9.33147 10.1146L5.14842 10.723L8.19813 13.6965C8.43182 13.9243 8.53961 14.2519 8.4869 14.574L7.80004 18.7715L11.5313 16.7917C11.8244 16.6361 12.1756 16.6361 12.4687 16.7917L16.2 18.7715L15.5132 14.574C15.4604 14.2519 15.5682 13.9243 15.8019 13.6965L18.8516 10.723L14.6686 10.1146C14.3448 10.0675 14.0645 9.86491 13.9181 9.57221L12 5.73607Z"
                            fill="white"/>
                    </svg>
                </div>
                <div class="car-slide">
                    @if($application->attachments->isNotEmpty())
                        @foreach($application->attachments->where('file_type','image')->all() as $attachment)
                            <div class="newcart__imgwrap">
                                <a href="{{ $attachment->url }}">
                                    <img src="{{ $attachment->thumbnail_url }}" class="car-row__image">
                                </a>

                            </div>
                        @endforeach
                    @else
                        <div>
                            <img src="{{ $application->default_attachment->thumbnail_url }}" alt=""
                                 class="car-row__image lk">
                        </div>
                    @endif
                </div>
            </div>
            <div class="car-row__info d-flex">
                <div class="car-row__col-6">
                    <div>
                        <h3 class="car-title">
                            {{ $application->car_title }}
                            <span class="car-show-modal">
                                    <svg width="16" height="14" viewBox="0 0 16 14" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M0.5 1.5C0.5 0.671573 1.17157 0 2 0H14C14.8284 0 15.5 0.671572 15.5 1.5V9.75C15.5 10.5784 14.8284 11.25 14 11.25H10.5607L8.53033 13.2803C8.23744 13.5732 7.76256 13.5732 7.46967 13.2803L5.43934 11.25H2C1.17157 11.25 0.5 10.5784 0.5 9.75V1.5ZM14 1.5H2V9.75H5.75C5.94891 9.75 6.13968 9.82902 6.28033 9.96967L8 11.6893L9.71967 9.96967C9.86032 9.82902 10.0511 9.75 10.25 9.75H14V1.5Z"
                                            fill="#536E9B"></path>
                                        <path
                                            d="M9.125 5.625C9.125 6.24632 8.62132 6.75 8 6.75C7.37868 6.75 6.875 6.24632 6.875 5.625C6.875 5.00368 7.37868 4.5 8 4.5C8.62132 4.5 9.125 5.00368 9.125 5.625Z"
                                            fill="#536E9B"></path>
                                        <path
                                            d="M12.125 5.625C12.125 6.24632 11.6213 6.75 11 6.75C10.3787 6.75 9.875 6.24632 9.875 5.625C9.875 5.00368 10.3787 4.5 11 4.5C11.6213 4.5 12.125 5.00368 12.125 5.625Z"
                                            fill="#536E9B"></path>
                                        <path
                                            d="M6.125 5.625C6.125 6.24632 5.62132 6.75 5 6.75C4.37868 6.75 3.875 6.24632 3.875 5.625C3.875 5.00368 4.37868 4.5 5 4.5C5.62132 4.5 6.125 5.00368 6.125 5.625Z"
                                            fill="#536E9B"></path>
                                    </svg>
                                </span>
                        </h3>
                        <span class="car__subtitle">ID {{ $application->id }}</span>
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
                        {{--                    new change--}}
                        @if(auth()->user()->hasRole(['SuperAdmin', 'Admin','Moderator']))

                            <label class="mr-0 mb-0 border-0">
                                <select class="status-select theme-back"
                                        name="app_data[status_id] @error('status_id') invalid @enderror">
                                    @foreach(\App\Models\Status::all() as $status)

                                        @if($application->status->id == $status->id)
                                            <option value="{{ $status->id }}" selected>{{ $status->name }}</option>
                                            @continue
                                        @endif
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </label>


                            {{--                    end new change--}}
                        @else
                            <span class="car-row__status">{{$application->status->name}}</span>
                        @endif
                        @if($application->returned)
                            <span class="car-row__status">????????????</span>
                        @endif


                        @if($application->partner)
                            <span>{{ $application->partner->name }}</span>
                        @else
                            <span></span>
                        @endif
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
                </div>

            </div>
        </div>
        <div class="modal-block__body">
            <div class="modal-block__sidebar">
                <h3 class="modal-sidebar-title">?????? ???? ????????????????????</h3>
                <div class="nav flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="active" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-1" role="tab"
                       aria-controls="v-pills-settings" aria-selected="true">??????????????</a>
                    <a class="" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-3" role="tab"
                       aria-controls="v-pills-settings" aria-selected="false">??????????????<span class="cunter-info">1</span></a>
                </div>
            </div>
            <div class="modal-block__main">
                <div class="tab-content chat-block" id="v-pills-tabContent">
                    <div class="tab-pane fade active show" id="v-pills-1">
                        <div class="chat">
                            <div class="chat__list">
                                @include('components.messages')
                            </div>
                            <div name="message" class="message-form">
                                <div class="d-flex">
                                    <input type="file" id="uploader" name="images[]" class="d-none" multiple="">
                                    <input type="hidden" id="appId" value="{{$application->id}}">
                                    <button name="" type="button" class="upload-file chat-file chose-file">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M18.4243 19.4626C19.174 18.7753 19.2202 17.5342 18.3929 16.7068L10.2929 8.60681C10.0834 8.39734 9.81657 8.39734 9.6071 8.60681C9.39762 8.81629 9.39762 9.08312 9.6071 9.2926L16.3071 15.9926C16.6976 16.3831 16.6976 17.0163 16.3071 17.4068C15.9166 17.7973 15.2834 17.7973 14.8929 17.4068L8.19289 10.7068C7.20236 9.71629 7.20236 8.18312 8.19289 7.1926C9.18341 6.20208 10.7166 6.20208 11.7071 7.1926L19.8071 15.2926C21.3746 16.8601 21.4256 19.4075 19.7918 20.922C18.2234 22.4743 15.6884 22.52 14.1787 20.8926L4.69289 11.4068C2.50236 9.21629 2.50236 5.78312 4.69289 3.5926C6.88341 1.40208 10.3166 1.40208 12.5071 3.5926L20.6071 11.6926C20.9976 12.0831 20.9976 12.7163 20.6071 13.1068C20.2166 13.4973 19.5834 13.4973 19.1929 13.1068L11.0929 5.00681C9.68341 3.59734 7.51658 3.59734 6.1071 5.00681C4.69762 6.41629 4.69762 8.58312 6.1071 9.9926L15.6071 19.4926C15.6173 19.5028 15.6274 19.5133 15.6371 19.524C16.3244 20.2737 17.5655 20.3199 18.3929 19.4926C18.4031 19.4824 18.4136 19.4723 18.4243 19.4626Z"
                                                fill="#536E9B"></path>
                                        </svg>
                                    </button>
                                    <input name="usermsg" id="message" type="text" placeholder="?????????? ??????????????????"
                                           class="message-input">
                                    <button class="send-mess" data-app-id="{{ $application->id }}">
                                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M33 18C33 18.5682 32.679 19.0876 32.1708 19.3417L5.17082 32.8417C4.65325 33.1004 4.03298 33.0384 3.57688 32.6823C3.12078 32.3262 2.91019 31.7395 3.03572 31.1746L5.96341 18L3.03572 4.82541C2.91019 4.26053 3.12078 3.67382 3.57688 3.3177C4.03298 2.96159 4.65325 2.89958 5.17082 3.15837L32.1708 16.6584C32.679 16.9125 33 17.4319 33 18ZM8.70326 19.5L6.64793 28.749L25.1459 19.5L8.70326 19.5ZM25.1459 16.5L6.64793 7.25103L8.70326 16.5L25.1459 16.5Z"
                                                fill="#011A3F"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-2">
                        <div class="chat">
                            <div class="chat__date">01.01.21</div>
                            <div class="chat__list mk">
                                <div class="chat__item">
                                    <div class="d-flex">
                                        <div class="chat__user-img">
                                            <img src="./assets/img/avatar.png" alt="">
                                        </div>
                                        <div class="chat__user-info">
                                            <div class="chat__user-name">??????????????</div>
                                            <div class="chat__date">01.01.2021 12:49</div>
                                        </div>
                                    </div>
                                    <div class="chat__mess">
                                        222Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat.
                                    </div>
                                    <div class="caht__imge-list d-flex">
                                            <span class="caht__img-item">
                                                <span href="#" class="caht__imge-wrap">
                                                    <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                    <div class="caht__imge-zoom"></div>
                                                </span>
                                                Nissan Juke
                                            </span>
                                        <span class="caht__img-item">
                                                <span href="#" class="caht__imge-wrap">
                                                    <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                    <div class="caht__imge-zoom"></div>
                                                </span>
                                                Nissan Juke
                                            </span>
                                        <span class="caht__img-item">
                                                <span href="#" class="caht__imge-wrap">
                                                    <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                    <div class="caht__imge-zoom"></div>
                                                </span>
                                                Nissan Juke
                                            </span>
                                        <span class="caht__img-item">
                                                <span href="#" class="caht__imge-wrap">
                                                    <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                    <div class="caht__imge-zoom"></div>
                                                </span>
                                                Nissan Juke
                                            </span>
                                        <span class="caht__img-item">
                                                <span href="#" class="caht__imge-wrap">
                                                    <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                    <div class="caht__imge-zoom"></div>
                                                </span>
                                                Nissan Juke
                                            </span>
                                        <span href="#" class="caht__imge-wrap">
                                                <a href="#" class="caht__imge-all">
                                                    <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                    <span class="caht__imge-count">?????? <span>15</span></span>
                                                </a>
                                            </span>
                                    </div>
                                </div>
                                <div class="chat__item user-mess">

                                    <div class="d-flex">
                                        <div class="chat__user-img">
                                            <img src="./assets/img/avatar.png" alt="">
                                        </div>
                                        <div class="chat__user-info">
                                            <div class="chat__user-name">???????????????? (????)</div>
                                            <div class="chat__date">01.01.2021 12:49</div>
                                        </div>
                                    </div>
                                    <div class="chat__mess">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua.
                                    </div>
                                    <div class="caht__file-list">
                                        <div class="caht__file-item d-flex">
                                            dogovor-na-otvetstv...
                                            <div class="caht__file-icon">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_155_3346)">
                                                        <path
                                                            d="M5.1213 0H11.8131L17.4777 5.90996V17.3982C17.4777 18.8353 16.313 20 14.876 20H5.1213C3.68425 20 2.51953 18.8353 2.51953 17.3982V2.60177C2.51953 1.16474 3.68425 0 5.1213 0V0Z"
                                                            fill="#0263D1"></path>
                                                        <path opacity="0.302" fill-rule="evenodd" clip-rule="evenodd"
                                                              d="M11.8047 0V5.86141H17.4774L11.8047 0Z"
                                                              fill="white"></path>
                                                        <path
                                                            d="M4.93359 14.271V10.9601H6.10642C6.34098 10.9601 6.55939 10.9952 6.7616 11.0599C6.96381 11.1273 7.14714 11.2243 7.31162 11.3538C7.47608 11.4832 7.60551 11.6557 7.69987 11.8714C7.79423 12.0871 7.84277 12.3352 7.84277 12.6156C7.84277 12.8959 7.79423 13.144 7.69987 13.3597C7.60551 13.5754 7.47608 13.7479 7.31162 13.8773C7.14717 14.0067 6.96381 14.1038 6.7616 14.1712C6.55939 14.2359 6.341 14.271 6.10642 14.271H4.93359ZM5.76131 13.5511H6.00666C6.13877 13.5511 6.26279 13.5349 6.37332 13.5053C6.48656 13.4729 6.589 13.4217 6.68606 13.3543C6.78313 13.2869 6.85861 13.1898 6.91253 13.0631C6.96916 12.9391 6.9961 12.7881 6.9961 12.6156C6.9961 12.443 6.96914 12.292 6.91253 12.1653C6.85861 12.0413 6.78313 11.9442 6.68606 11.8768C6.589 11.8067 6.48656 11.7582 6.37332 11.7258C6.26279 11.6962 6.13877 11.68 6.00666 11.68H5.76131V13.5511ZM9.85138 14.3087C9.3526 14.3087 8.94009 14.147 8.61386 13.8261C8.28762 13.5053 8.12584 13.1009 8.12584 12.6156C8.12584 12.1303 8.28762 11.7258 8.61386 11.405C8.94009 11.0842 9.3526 10.9224 9.85138 10.9224C10.3421 10.9224 10.7492 11.0842 11.0754 11.405C11.399 11.7259 11.5607 12.1303 11.5607 12.6156C11.5607 13.1009 11.399 13.5053 11.0754 13.8261C10.7492 14.147 10.3421 14.3087 9.85138 14.3087ZM9.21508 13.3139C9.37954 13.4972 9.58983 13.5889 9.84597 13.5889C10.1021 13.5889 10.3097 13.4972 10.4742 13.3139C10.6386 13.1278 10.7195 12.8959 10.7195 12.6156C10.7195 12.3352 10.6386 12.1033 10.4742 11.9172C10.3097 11.7339 10.1021 11.6422 9.84597 11.6422C9.58983 11.6422 9.37954 11.7339 9.21508 11.9172C9.05062 12.1033 8.96703 12.3352 8.96703 12.6156C8.96703 12.8959 9.05062 13.1278 9.21508 13.3139ZM13.5289 14.3087C13.0463 14.3087 12.6446 14.1577 12.3264 13.8612C12.0056 13.5619 11.8465 13.1467 11.8465 12.6156C11.8465 12.0871 12.0083 11.6719 12.3318 11.3726C12.6581 11.0734 13.0544 10.9224 13.5289 10.9224C13.9576 10.9224 14.3081 11.0275 14.5858 11.2405C14.8608 11.4508 15.0199 11.7312 15.0603 12.0817L14.2245 12.2516C14.1895 12.0682 14.1059 11.9199 13.9765 11.8094C13.8471 11.6988 13.6961 11.6422 13.5235 11.6422C13.2863 11.6422 13.0894 11.7258 12.9304 11.8957C12.7713 12.0682 12.6904 12.3055 12.6904 12.6155C12.6904 12.9256 12.7713 13.1628 12.9277 13.3327C13.0867 13.5053 13.2836 13.5888 13.5235 13.5888C13.696 13.5888 13.8444 13.5403 13.9657 13.4433C14.087 13.3462 14.1625 13.2168 14.1949 13.055L15.0495 13.2491C14.9713 13.5835 14.7988 13.8423 14.5292 14.0283C14.2623 14.2144 13.9279 14.3087 13.5289 14.3087Z"
                                                            fill="white"></path>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_155_3346">
                                                            <rect width="20" height="20" fill="white"></rect>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="caht__file-item d-flex">
                                            dogovor-na-otvetstv...
                                            <div class="caht__file-icon">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_155_3346)">
                                                        <path
                                                            d="M5.1213 0H11.8131L17.4777 5.90996V17.3982C17.4777 18.8353 16.313 20 14.876 20H5.1213C3.68425 20 2.51953 18.8353 2.51953 17.3982V2.60177C2.51953 1.16474 3.68425 0 5.1213 0V0Z"
                                                            fill="#0263D1"></path>
                                                        <path opacity="0.302" fill-rule="evenodd" clip-rule="evenodd"
                                                              d="M11.8047 0V5.86141H17.4774L11.8047 0Z"
                                                              fill="white"></path>
                                                        <path
                                                            d="M4.93359 14.271V10.9601H6.10642C6.34098 10.9601 6.55939 10.9952 6.7616 11.0599C6.96381 11.1273 7.14714 11.2243 7.31162 11.3538C7.47608 11.4832 7.60551 11.6557 7.69987 11.8714C7.79423 12.0871 7.84277 12.3352 7.84277 12.6156C7.84277 12.8959 7.79423 13.144 7.69987 13.3597C7.60551 13.5754 7.47608 13.7479 7.31162 13.8773C7.14717 14.0067 6.96381 14.1038 6.7616 14.1712C6.55939 14.2359 6.341 14.271 6.10642 14.271H4.93359ZM5.76131 13.5511H6.00666C6.13877 13.5511 6.26279 13.5349 6.37332 13.5053C6.48656 13.4729 6.589 13.4217 6.68606 13.3543C6.78313 13.2869 6.85861 13.1898 6.91253 13.0631C6.96916 12.9391 6.9961 12.7881 6.9961 12.6156C6.9961 12.443 6.96914 12.292 6.91253 12.1653C6.85861 12.0413 6.78313 11.9442 6.68606 11.8768C6.589 11.8067 6.48656 11.7582 6.37332 11.7258C6.26279 11.6962 6.13877 11.68 6.00666 11.68H5.76131V13.5511ZM9.85138 14.3087C9.3526 14.3087 8.94009 14.147 8.61386 13.8261C8.28762 13.5053 8.12584 13.1009 8.12584 12.6156C8.12584 12.1303 8.28762 11.7258 8.61386 11.405C8.94009 11.0842 9.3526 10.9224 9.85138 10.9224C10.3421 10.9224 10.7492 11.0842 11.0754 11.405C11.399 11.7259 11.5607 12.1303 11.5607 12.6156C11.5607 13.1009 11.399 13.5053 11.0754 13.8261C10.7492 14.147 10.3421 14.3087 9.85138 14.3087ZM9.21508 13.3139C9.37954 13.4972 9.58983 13.5889 9.84597 13.5889C10.1021 13.5889 10.3097 13.4972 10.4742 13.3139C10.6386 13.1278 10.7195 12.8959 10.7195 12.6156C10.7195 12.3352 10.6386 12.1033 10.4742 11.9172C10.3097 11.7339 10.1021 11.6422 9.84597 11.6422C9.58983 11.6422 9.37954 11.7339 9.21508 11.9172C9.05062 12.1033 8.96703 12.3352 8.96703 12.6156C8.96703 12.8959 9.05062 13.1278 9.21508 13.3139ZM13.5289 14.3087C13.0463 14.3087 12.6446 14.1577 12.3264 13.8612C12.0056 13.5619 11.8465 13.1467 11.8465 12.6156C11.8465 12.0871 12.0083 11.6719 12.3318 11.3726C12.6581 11.0734 13.0544 10.9224 13.5289 10.9224C13.9576 10.9224 14.3081 11.0275 14.5858 11.2405C14.8608 11.4508 15.0199 11.7312 15.0603 12.0817L14.2245 12.2516C14.1895 12.0682 14.1059 11.9199 13.9765 11.8094C13.8471 11.6988 13.6961 11.6422 13.5235 11.6422C13.2863 11.6422 13.0894 11.7258 12.9304 11.8957C12.7713 12.0682 12.6904 12.3055 12.6904 12.6155C12.6904 12.9256 12.7713 13.1628 12.9277 13.3327C13.0867 13.5053 13.2836 13.5888 13.5235 13.5888C13.696 13.5888 13.8444 13.5403 13.9657 13.4433C14.087 13.3462 14.1625 13.2168 14.1949 13.055L15.0495 13.2491C14.9713 13.5835 14.7988 13.8423 14.5292 14.0283C14.2623 14.2144 13.9279 14.3087 13.5289 14.3087Z"
                                                            fill="white"></path>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_155_3346">
                                                            <rect width="20" height="20" fill="white"></rect>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat__item">
                                    <div class="d-flex">
                                        <div class="chat__user-img">
                                            <img src="./assets/img/avatar.png" alt="">
                                        </div>
                                        <div class="chat__user-info">
                                            <div class="chat__user-name">??????????????</div>
                                            <div class="chat__date">01.01.2021 12:49</div>
                                        </div>
                                    </div>
                                    <div class="chat__mess">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat.
                                    </div>
                                </div>
                            </div>

                                <div class="d-flex">
                                    <button name="" type="button" class="chose-file">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M18.4243 19.4626C19.174 18.7753 19.2202 17.5342 18.3929 16.7068L10.2929 8.60681C10.0834 8.39734 9.81657 8.39734 9.6071 8.60681C9.39762 8.81629 9.39762 9.08312 9.6071 9.2926L16.3071 15.9926C16.6976 16.3831 16.6976 17.0163 16.3071 17.4068C15.9166 17.7973 15.2834 17.7973 14.8929 17.4068L8.19289 10.7068C7.20236 9.71629 7.20236 8.18312 8.19289 7.1926C9.18341 6.20208 10.7166 6.20208 11.7071 7.1926L19.8071 15.2926C21.3746 16.8601 21.4256 19.4075 19.7918 20.922C18.2234 22.4743 15.6884 22.52 14.1787 20.8926L4.69289 11.4068C2.50236 9.21629 2.50236 5.78312 4.69289 3.5926C6.88341 1.40208 10.3166 1.40208 12.5071 3.5926L20.6071 11.6926C20.9976 12.0831 20.9976 12.7163 20.6071 13.1068C20.2166 13.4973 19.5834 13.4973 19.1929 13.1068L11.0929 5.00681C9.68341 3.59734 7.51658 3.59734 6.1071 5.00681C4.69762 6.41629 4.69762 8.58312 6.1071 9.9926L15.6071 19.4926C15.6173 19.5028 15.6274 19.5133 15.6371 19.524C16.3244 20.2737 17.5655 20.3199 18.3929 19.4926C18.4031 19.4824 18.4136 19.4723 18.4243 19.4626Z"
                                                fill="#536E9B"></path>
                                        </svg>
                                    </button>
                                    <input name="usermsg" type="text" placeholder="?????????? ??????????????????"
                                           class="message-input">
                                    <button name="" type="submit" class="send-mess">
                                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M33 18C33 18.5682 32.679 19.0876 32.1708 19.3417L5.17082 32.8417C4.65325 33.1004 4.03298 33.0384 3.57688 32.6823C3.12078 32.3262 2.91019 31.7395 3.03572 31.1746L5.96341 18L3.03572 4.82541C2.91019 4.26053 3.12078 3.67382 3.57688 3.3177C4.03298 2.96159 4.65325 2.89958 5.17082 3.15837L32.1708 16.6584C32.679 16.9125 33 17.4319 33 18ZM8.70326 19.5L6.64793 28.749L25.1459 19.5L8.70326 19.5ZM25.1459 16.5L6.64793 7.25103L8.70326 16.5L25.1459 16.5Z"
                                                fill="#011A3F"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="caht__imge-list d-flex">
                                        <span class="caht__img-item">
                                            <span href="#" class="caht__imge-wrap">
                                                <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                <div class="caht__imge-remove"></div>
                                            </span>
                                            Nissan Juke
                                        </span>
                                    <span class="caht__img-item">
                                            <span href="#" class="caht__imge-wrap">
                                                <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                <div class="caht__imge-remove"></div>
                                            </span>
                                            Nissan Juke
                                        </span>
                                    <span class="caht__img-item">
                                            <span href="#" class="caht__imge-wrap">
                                                <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                <div class="caht__imge-remove"></div>
                                            </span>
                                            Nissan Juke
                                        </span>
                                    <span class="caht__img-item">
                                            <span href="#" class="caht__imge-wrap">
                                                <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                <div class="caht__imge-remove"></div>
                                            </span>
                                            Nissan Juke
                                        </span>
                                    <span class="caht__img-item">
                                            <span href="#" class="caht__imge-wrap">
                                                <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                <div class="caht__imge-remove"></div>
                                            </span>
                                            Nissan Juke
                                        </span>
                                </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-3">
                        <div class="chat">
                            <div class="chat__date">01.01.21</div>
                            <div class="chat__list">
                                <div class="chat__item">
                                    <div class="d-flex">
                                        <div class="chat__user-img">
                                            <img src="./assets/img/avatar.png" alt="">
                                        </div>
                                        <div class="chat__user-info">
                                            <div class="chat__user-name">??????????????</div>
                                            <div class="chat__date">01.01.2021 12:49</div>
                                        </div>
                                    </div>
                                    <div class="chat__mess">
                                        333Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat.
                                    </div>
                                    <div class="caht__imge-list d-flex">
                                            <span class="caht__img-item">
                                                <span href="#" class="caht__imge-wrap">
                                                    <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                    <div class="caht__imge-zoom"></div>
                                                </span>
                                                Nissan Juke
                                            </span>
                                        <span class="caht__img-item">
                                                <span href="#" class="caht__imge-wrap">
                                                    <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                    <div class="caht__imge-zoom"></div>
                                                </span>
                                                Nissan Juke
                                            </span>
                                        <span class="caht__img-item">
                                                <span href="#" class="caht__imge-wrap">
                                                    <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                    <div class="caht__imge-zoom"></div>
                                                </span>
                                                Nissan Juke
                                            </span>
                                        <span class="caht__img-item">
                                                <span href="#" class="caht__imge-wrap">
                                                    <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                    <div class="caht__imge-zoom"></div>
                                                </span>
                                                Nissan Juke
                                            </span>
                                        <span class="caht__img-item">
                                                <span href="#" class="caht__imge-wrap">
                                                    <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                    <div class="caht__imge-zoom"></div>
                                                </span>
                                                Nissan Juke
                                            </span>
                                        <span href="#" class="caht__imge-wrap">
                                                <a href="#" class="caht__imge-all">
                                                    <img src="./assets/image/car.jpg" alt="" class="caht__imge-item">
                                                    <span class="caht__imge-count">?????? <span>15</span></span>
                                                </a>
                                            </span>
                                    </div>
                                </div>
                                <div class="chat__item user-mess">

                                    <div class="d-flex">
                                        <div class="chat__user-img">
                                            <img src="./assets/img/avatar.png" alt="">
                                        </div>
                                        <div class="chat__user-info">
                                            <div class="chat__user-name">???????????????? (????)</div>
                                            <div class="chat__date">01.01.2021 12:49</div>
                                        </div>
                                    </div>
                                    <div class="chat__mess">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua.
                                    </div>
                                    <div class="caht__file-list">
                                        <div class="caht__file-item d-flex">
                                            dogovor-na-otvetstv...
                                            <div class="caht__file-icon">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_155_3346)">
                                                        <path
                                                            d="M5.1213 0H11.8131L17.4777 5.90996V17.3982C17.4777 18.8353 16.313 20 14.876 20H5.1213C3.68425 20 2.51953 18.8353 2.51953 17.3982V2.60177C2.51953 1.16474 3.68425 0 5.1213 0V0Z"
                                                            fill="#0263D1"></path>
                                                        <path opacity="0.302" fill-rule="evenodd" clip-rule="evenodd"
                                                              d="M11.8047 0V5.86141H17.4774L11.8047 0Z"
                                                              fill="white"></path>
                                                        <path
                                                            d="M4.93359 14.271V10.9601H6.10642C6.34098 10.9601 6.55939 10.9952 6.7616 11.0599C6.96381 11.1273 7.14714 11.2243 7.31162 11.3538C7.47608 11.4832 7.60551 11.6557 7.69987 11.8714C7.79423 12.0871 7.84277 12.3352 7.84277 12.6156C7.84277 12.8959 7.79423 13.144 7.69987 13.3597C7.60551 13.5754 7.47608 13.7479 7.31162 13.8773C7.14717 14.0067 6.96381 14.1038 6.7616 14.1712C6.55939 14.2359 6.341 14.271 6.10642 14.271H4.93359ZM5.76131 13.5511H6.00666C6.13877 13.5511 6.26279 13.5349 6.37332 13.5053C6.48656 13.4729 6.589 13.4217 6.68606 13.3543C6.78313 13.2869 6.85861 13.1898 6.91253 13.0631C6.96916 12.9391 6.9961 12.7881 6.9961 12.6156C6.9961 12.443 6.96914 12.292 6.91253 12.1653C6.85861 12.0413 6.78313 11.9442 6.68606 11.8768C6.589 11.8067 6.48656 11.7582 6.37332 11.7258C6.26279 11.6962 6.13877 11.68 6.00666 11.68H5.76131V13.5511ZM9.85138 14.3087C9.3526 14.3087 8.94009 14.147 8.61386 13.8261C8.28762 13.5053 8.12584 13.1009 8.12584 12.6156C8.12584 12.1303 8.28762 11.7258 8.61386 11.405C8.94009 11.0842 9.3526 10.9224 9.85138 10.9224C10.3421 10.9224 10.7492 11.0842 11.0754 11.405C11.399 11.7259 11.5607 12.1303 11.5607 12.6156C11.5607 13.1009 11.399 13.5053 11.0754 13.8261C10.7492 14.147 10.3421 14.3087 9.85138 14.3087ZM9.21508 13.3139C9.37954 13.4972 9.58983 13.5889 9.84597 13.5889C10.1021 13.5889 10.3097 13.4972 10.4742 13.3139C10.6386 13.1278 10.7195 12.8959 10.7195 12.6156C10.7195 12.3352 10.6386 12.1033 10.4742 11.9172C10.3097 11.7339 10.1021 11.6422 9.84597 11.6422C9.58983 11.6422 9.37954 11.7339 9.21508 11.9172C9.05062 12.1033 8.96703 12.3352 8.96703 12.6156C8.96703 12.8959 9.05062 13.1278 9.21508 13.3139ZM13.5289 14.3087C13.0463 14.3087 12.6446 14.1577 12.3264 13.8612C12.0056 13.5619 11.8465 13.1467 11.8465 12.6156C11.8465 12.0871 12.0083 11.6719 12.3318 11.3726C12.6581 11.0734 13.0544 10.9224 13.5289 10.9224C13.9576 10.9224 14.3081 11.0275 14.5858 11.2405C14.8608 11.4508 15.0199 11.7312 15.0603 12.0817L14.2245 12.2516C14.1895 12.0682 14.1059 11.9199 13.9765 11.8094C13.8471 11.6988 13.6961 11.6422 13.5235 11.6422C13.2863 11.6422 13.0894 11.7258 12.9304 11.8957C12.7713 12.0682 12.6904 12.3055 12.6904 12.6155C12.6904 12.9256 12.7713 13.1628 12.9277 13.3327C13.0867 13.5053 13.2836 13.5888 13.5235 13.5888C13.696 13.5888 13.8444 13.5403 13.9657 13.4433C14.087 13.3462 14.1625 13.2168 14.1949 13.055L15.0495 13.2491C14.9713 13.5835 14.7988 13.8423 14.5292 14.0283C14.2623 14.2144 13.9279 14.3087 13.5289 14.3087Z"
                                                            fill="white"></path>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_155_3346">
                                                            <rect width="20" height="20" fill="white"></rect>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="caht__file-item d-flex">
                                            dogovor-na-otvetstv...
                                            <div class="caht__file-icon">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_155_3346)">
                                                        <path
                                                            d="M5.1213 0H11.8131L17.4777 5.90996V17.3982C17.4777 18.8353 16.313 20 14.876 20H5.1213C3.68425 20 2.51953 18.8353 2.51953 17.3982V2.60177C2.51953 1.16474 3.68425 0 5.1213 0V0Z"
                                                            fill="#0263D1"></path>
                                                        <path opacity="0.302" fill-rule="evenodd" clip-rule="evenodd"
                                                              d="M11.8047 0V5.86141H17.4774L11.8047 0Z"
                                                              fill="white"></path>
                                                        <path
                                                            d="M4.93359 14.271V10.9601H6.10642C6.34098 10.9601 6.55939 10.9952 6.7616 11.0599C6.96381 11.1273 7.14714 11.2243 7.31162 11.3538C7.47608 11.4832 7.60551 11.6557 7.69987 11.8714C7.79423 12.0871 7.84277 12.3352 7.84277 12.6156C7.84277 12.8959 7.79423 13.144 7.69987 13.3597C7.60551 13.5754 7.47608 13.7479 7.31162 13.8773C7.14717 14.0067 6.96381 14.1038 6.7616 14.1712C6.55939 14.2359 6.341 14.271 6.10642 14.271H4.93359ZM5.76131 13.5511H6.00666C6.13877 13.5511 6.26279 13.5349 6.37332 13.5053C6.48656 13.4729 6.589 13.4217 6.68606 13.3543C6.78313 13.2869 6.85861 13.1898 6.91253 13.0631C6.96916 12.9391 6.9961 12.7881 6.9961 12.6156C6.9961 12.443 6.96914 12.292 6.91253 12.1653C6.85861 12.0413 6.78313 11.9442 6.68606 11.8768C6.589 11.8067 6.48656 11.7582 6.37332 11.7258C6.26279 11.6962 6.13877 11.68 6.00666 11.68H5.76131V13.5511ZM9.85138 14.3087C9.3526 14.3087 8.94009 14.147 8.61386 13.8261C8.28762 13.5053 8.12584 13.1009 8.12584 12.6156C8.12584 12.1303 8.28762 11.7258 8.61386 11.405C8.94009 11.0842 9.3526 10.9224 9.85138 10.9224C10.3421 10.9224 10.7492 11.0842 11.0754 11.405C11.399 11.7259 11.5607 12.1303 11.5607 12.6156C11.5607 13.1009 11.399 13.5053 11.0754 13.8261C10.7492 14.147 10.3421 14.3087 9.85138 14.3087ZM9.21508 13.3139C9.37954 13.4972 9.58983 13.5889 9.84597 13.5889C10.1021 13.5889 10.3097 13.4972 10.4742 13.3139C10.6386 13.1278 10.7195 12.8959 10.7195 12.6156C10.7195 12.3352 10.6386 12.1033 10.4742 11.9172C10.3097 11.7339 10.1021 11.6422 9.84597 11.6422C9.58983 11.6422 9.37954 11.7339 9.21508 11.9172C9.05062 12.1033 8.96703 12.3352 8.96703 12.6156C8.96703 12.8959 9.05062 13.1278 9.21508 13.3139ZM13.5289 14.3087C13.0463 14.3087 12.6446 14.1577 12.3264 13.8612C12.0056 13.5619 11.8465 13.1467 11.8465 12.6156C11.8465 12.0871 12.0083 11.6719 12.3318 11.3726C12.6581 11.0734 13.0544 10.9224 13.5289 10.9224C13.9576 10.9224 14.3081 11.0275 14.5858 11.2405C14.8608 11.4508 15.0199 11.7312 15.0603 12.0817L14.2245 12.2516C14.1895 12.0682 14.1059 11.9199 13.9765 11.8094C13.8471 11.6988 13.6961 11.6422 13.5235 11.6422C13.2863 11.6422 13.0894 11.7258 12.9304 11.8957C12.7713 12.0682 12.6904 12.3055 12.6904 12.6155C12.6904 12.9256 12.7713 13.1628 12.9277 13.3327C13.0867 13.5053 13.2836 13.5888 13.5235 13.5888C13.696 13.5888 13.8444 13.5403 13.9657 13.4433C14.087 13.3462 14.1625 13.2168 14.1949 13.055L15.0495 13.2491C14.9713 13.5835 14.7988 13.8423 14.5292 14.0283C14.2623 14.2144 13.9279 14.3087 13.5289 14.3087Z"
                                                            fill="white"></path>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_155_3346">
                                                            <rect width="20" height="20" fill="white"></rect>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat__item">
                                    <div class="d-flex">
                                        <div class="chat__user-img">
                                            <img src="./assets/img/avatar.png" alt="">
                                        </div>
                                        <div class="chat__user-info">
                                            <div class="chat__user-name">??????????????</div>
                                            <div class="chat__date">01.01.2021 12:49</div>
                                        </div>
                                    </div>
                                    <div class="chat__mess">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                        consequat.
                                    </div>
                                </div>
                            </div>
                            <form name="message" action="" class="message-form">
                                <div class="d-flex">
                                    <button name="" type="button" class="chose-file">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M18.4243 19.4626C19.174 18.7753 19.2202 17.5342 18.3929 16.7068L10.2929 8.60681C10.0834 8.39734 9.81657 8.39734 9.6071 8.60681C9.39762 8.81629 9.39762 9.08312 9.6071 9.2926L16.3071 15.9926C16.6976 16.3831 16.6976 17.0163 16.3071 17.4068C15.9166 17.7973 15.2834 17.7973 14.8929 17.4068L8.19289 10.7068C7.20236 9.71629 7.20236 8.18312 8.19289 7.1926C9.18341 6.20208 10.7166 6.20208 11.7071 7.1926L19.8071 15.2926C21.3746 16.8601 21.4256 19.4075 19.7918 20.922C18.2234 22.4743 15.6884 22.52 14.1787 20.8926L4.69289 11.4068C2.50236 9.21629 2.50236 5.78312 4.69289 3.5926C6.88341 1.40208 10.3166 1.40208 12.5071 3.5926L20.6071 11.6926C20.9976 12.0831 20.9976 12.7163 20.6071 13.1068C20.2166 13.4973 19.5834 13.4973 19.1929 13.1068L11.0929 5.00681C9.68341 3.59734 7.51658 3.59734 6.1071 5.00681C4.69762 6.41629 4.69762 8.58312 6.1071 9.9926L15.6071 19.4926C15.6173 19.5028 15.6274 19.5133 15.6371 19.524C16.3244 20.2737 17.5655 20.3199 18.3929 19.4926C18.4031 19.4824 18.4136 19.4723 18.4243 19.4626Z"
                                                fill="#536E9B"></path>
                                        </svg>
                                    </button>
                                    <input name="usermsg" type="text" placeholder="?????????? ??????????????????"
                                           class="message-input">
                                    <button name="" class="send-mess">
                                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M33 18C33 18.5682 32.679 19.0876 32.1708 19.3417L5.17082 32.8417C4.65325 33.1004 4.03298 33.0384 3.57688 32.6823C3.12078 32.3262 2.91019 31.7395 3.03572 31.1746L5.96341 18L3.03572 4.82541C2.91019 4.26053 3.12078 3.67382 3.57688 3.3177C4.03298 2.96159 4.65325 2.89958 5.17082 3.15837L32.1708 16.6584C32.679 16.9125 33 17.4319 33 18ZM8.70326 19.5L6.64793 28.749L25.1459 19.5L8.70326 19.5ZM25.1459 16.5L6.64793 7.25103L8.70326 16.5L25.1459 16.5Z"
                                                fill="#011A3F"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="caht__file-list d-flex">
                                    <div class="caht__file-item d-flex">
                                        <div class="caht__file-icon">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_155_3346)">
                                                    <path
                                                        d="M5.1213 0H11.8131L17.4777 5.90996V17.3982C17.4777 18.8353 16.313 20 14.876 20H5.1213C3.68425 20 2.51953 18.8353 2.51953 17.3982V2.60177C2.51953 1.16474 3.68425 0 5.1213 0V0Z"
                                                        fill="#0263D1"></path>
                                                    <path opacity="0.302" fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M11.8047 0V5.86141H17.4774L11.8047 0Z" fill="white"></path>
                                                    <path
                                                        d="M4.93359 14.271V10.9601H6.10642C6.34098 10.9601 6.55939 10.9952 6.7616 11.0599C6.96381 11.1273 7.14714 11.2243 7.31162 11.3538C7.47608 11.4832 7.60551 11.6557 7.69987 11.8714C7.79423 12.0871 7.84277 12.3352 7.84277 12.6156C7.84277 12.8959 7.79423 13.144 7.69987 13.3597C7.60551 13.5754 7.47608 13.7479 7.31162 13.8773C7.14717 14.0067 6.96381 14.1038 6.7616 14.1712C6.55939 14.2359 6.341 14.271 6.10642 14.271H4.93359ZM5.76131 13.5511H6.00666C6.13877 13.5511 6.26279 13.5349 6.37332 13.5053C6.48656 13.4729 6.589 13.4217 6.68606 13.3543C6.78313 13.2869 6.85861 13.1898 6.91253 13.0631C6.96916 12.9391 6.9961 12.7881 6.9961 12.6156C6.9961 12.443 6.96914 12.292 6.91253 12.1653C6.85861 12.0413 6.78313 11.9442 6.68606 11.8768C6.589 11.8067 6.48656 11.7582 6.37332 11.7258C6.26279 11.6962 6.13877 11.68 6.00666 11.68H5.76131V13.5511ZM9.85138 14.3087C9.3526 14.3087 8.94009 14.147 8.61386 13.8261C8.28762 13.5053 8.12584 13.1009 8.12584 12.6156C8.12584 12.1303 8.28762 11.7258 8.61386 11.405C8.94009 11.0842 9.3526 10.9224 9.85138 10.9224C10.3421 10.9224 10.7492 11.0842 11.0754 11.405C11.399 11.7259 11.5607 12.1303 11.5607 12.6156C11.5607 13.1009 11.399 13.5053 11.0754 13.8261C10.7492 14.147 10.3421 14.3087 9.85138 14.3087ZM9.21508 13.3139C9.37954 13.4972 9.58983 13.5889 9.84597 13.5889C10.1021 13.5889 10.3097 13.4972 10.4742 13.3139C10.6386 13.1278 10.7195 12.8959 10.7195 12.6156C10.7195 12.3352 10.6386 12.1033 10.4742 11.9172C10.3097 11.7339 10.1021 11.6422 9.84597 11.6422C9.58983 11.6422 9.37954 11.7339 9.21508 11.9172C9.05062 12.1033 8.96703 12.3352 8.96703 12.6156C8.96703 12.8959 9.05062 13.1278 9.21508 13.3139ZM13.5289 14.3087C13.0463 14.3087 12.6446 14.1577 12.3264 13.8612C12.0056 13.5619 11.8465 13.1467 11.8465 12.6156C11.8465 12.0871 12.0083 11.6719 12.3318 11.3726C12.6581 11.0734 13.0544 10.9224 13.5289 10.9224C13.9576 10.9224 14.3081 11.0275 14.5858 11.2405C14.8608 11.4508 15.0199 11.7312 15.0603 12.0817L14.2245 12.2516C14.1895 12.0682 14.1059 11.9199 13.9765 11.8094C13.8471 11.6988 13.6961 11.6422 13.5235 11.6422C13.2863 11.6422 13.0894 11.7258 12.9304 11.8957C12.7713 12.0682 12.6904 12.3055 12.6904 12.6155C12.6904 12.9256 12.7713 13.1628 12.9277 13.3327C13.0867 13.5053 13.2836 13.5888 13.5235 13.5888C13.696 13.5888 13.8444 13.5403 13.9657 13.4433C14.087 13.3462 14.1625 13.2168 14.1949 13.055L15.0495 13.2491C14.9713 13.5835 14.7988 13.8423 14.5292 14.0283C14.2623 14.2144 13.9279 14.3087 13.5289 14.3087Z"
                                                        fill="white"></path>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_155_3346">
                                                        <rect width="20" height="20" fill="white"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </div>
                                        dogovor-na-otvetstvennoe-hranenie
                                        <button class="remove-min-btn"></button>
                                    </div>
                                    <div class="caht__file-item d-flex">
                                        <div class="caht__file-icon">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_155_3346)">
                                                    <path
                                                        d="M5.1213 0H11.8131L17.4777 5.90996V17.3982C17.4777 18.8353 16.313 20 14.876 20H5.1213C3.68425 20 2.51953 18.8353 2.51953 17.3982V2.60177C2.51953 1.16474 3.68425 0 5.1213 0V0Z"
                                                        fill="#0263D1"></path>
                                                    <path opacity="0.302" fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M11.8047 0V5.86141H17.4774L11.8047 0Z" fill="white"></path>
                                                    <path
                                                        d="M4.93359 14.271V10.9601H6.10642C6.34098 10.9601 6.55939 10.9952 6.7616 11.0599C6.96381 11.1273 7.14714 11.2243 7.31162 11.3538C7.47608 11.4832 7.60551 11.6557 7.69987 11.8714C7.79423 12.0871 7.84277 12.3352 7.84277 12.6156C7.84277 12.8959 7.79423 13.144 7.69987 13.3597C7.60551 13.5754 7.47608 13.7479 7.31162 13.8773C7.14717 14.0067 6.96381 14.1038 6.7616 14.1712C6.55939 14.2359 6.341 14.271 6.10642 14.271H4.93359ZM5.76131 13.5511H6.00666C6.13877 13.5511 6.26279 13.5349 6.37332 13.5053C6.48656 13.4729 6.589 13.4217 6.68606 13.3543C6.78313 13.2869 6.85861 13.1898 6.91253 13.0631C6.96916 12.9391 6.9961 12.7881 6.9961 12.6156C6.9961 12.443 6.96914 12.292 6.91253 12.1653C6.85861 12.0413 6.78313 11.9442 6.68606 11.8768C6.589 11.8067 6.48656 11.7582 6.37332 11.7258C6.26279 11.6962 6.13877 11.68 6.00666 11.68H5.76131V13.5511ZM9.85138 14.3087C9.3526 14.3087 8.94009 14.147 8.61386 13.8261C8.28762 13.5053 8.12584 13.1009 8.12584 12.6156C8.12584 12.1303 8.28762 11.7258 8.61386 11.405C8.94009 11.0842 9.3526 10.9224 9.85138 10.9224C10.3421 10.9224 10.7492 11.0842 11.0754 11.405C11.399 11.7259 11.5607 12.1303 11.5607 12.6156C11.5607 13.1009 11.399 13.5053 11.0754 13.8261C10.7492 14.147 10.3421 14.3087 9.85138 14.3087ZM9.21508 13.3139C9.37954 13.4972 9.58983 13.5889 9.84597 13.5889C10.1021 13.5889 10.3097 13.4972 10.4742 13.3139C10.6386 13.1278 10.7195 12.8959 10.7195 12.6156C10.7195 12.3352 10.6386 12.1033 10.4742 11.9172C10.3097 11.7339 10.1021 11.6422 9.84597 11.6422C9.58983 11.6422 9.37954 11.7339 9.21508 11.9172C9.05062 12.1033 8.96703 12.3352 8.96703 12.6156C8.96703 12.8959 9.05062 13.1278 9.21508 13.3139ZM13.5289 14.3087C13.0463 14.3087 12.6446 14.1577 12.3264 13.8612C12.0056 13.5619 11.8465 13.1467 11.8465 12.6156C11.8465 12.0871 12.0083 11.6719 12.3318 11.3726C12.6581 11.0734 13.0544 10.9224 13.5289 10.9224C13.9576 10.9224 14.3081 11.0275 14.5858 11.2405C14.8608 11.4508 15.0199 11.7312 15.0603 12.0817L14.2245 12.2516C14.1895 12.0682 14.1059 11.9199 13.9765 11.8094C13.8471 11.6988 13.6961 11.6422 13.5235 11.6422C13.2863 11.6422 13.0894 11.7258 12.9304 11.8957C12.7713 12.0682 12.6904 12.3055 12.6904 12.6155C12.6904 12.9256 12.7713 13.1628 12.9277 13.3327C13.0867 13.5053 13.2836 13.5888 13.5235 13.5888C13.696 13.5888 13.8444 13.5403 13.9657 13.4433C14.087 13.3462 14.1625 13.2168 14.1949 13.055L15.0495 13.2491C14.9713 13.5835 14.7988 13.8423 14.5292 14.0283C14.2623 14.2144 13.9279 14.3087 13.5289 14.3087Z"
                                                        fill="white"></path>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_155_3346">
                                                        <rect width="20" height="20" fill="white"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </div>
                                        dogovor-na-otvetstvennoe-hranenie
                                        <button class="remove-min-btn"></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



