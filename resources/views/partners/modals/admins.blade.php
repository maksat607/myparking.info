<div class="table-modal modal fade" id="modal-table-partner-users" data-backdrop="static" data-keyboard="false"
     tabindex="-1" aria-labelledby="modal-tableLabel" aria-modal="true" role="dialog" style="display: block;">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-tableLabel">Управление партнёром {{ $partner->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M1.07268 0.851982C1.44693 0.494001 2.05372 0.494001 2.42797 0.851982L7.50033 5.7038L12.5727 0.851982C12.9469 0.494001 13.5537 0.494001 13.928 0.851982C14.3022 1.20996 14.3022 1.79036 13.928 2.14834L8.85561 7.00016L13.928 11.852C14.3022 12.21 14.3022 12.7904 13.928 13.1483C13.5537 13.5063 12.9469 13.5063 12.5727 13.1483L7.50033 8.29653L2.42797 13.1483C2.05372 13.5063 1.44693 13.5063 1.07268 13.1483C0.698429 12.7904 0.698429 12.21 1.07268 11.852L6.14504 7.00016L1.07268 2.14834C0.698429 1.79036 0.698429 1.20996 1.07268 0.851982Z"
                            fill="#D0D0D0"></path>
                    </svg>

                </button>
            </div>
            <div class="scroll-modal">
                <div class="table-modal-head">
                    <div class="d-flex modal-default-head">
                        <label class="field-style">
                            <span>Поиск пользователей</span>
                            <input type="text" placeholder="E-mail" id="searchAdminKeywoard">Добавить
                        </label>
                        <button type="button" class="addUserOfPartner">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.99967 3.3335C10.4599 3.3335 10.833 3.70659 10.833 4.16683V9.16683H15.833C16.2932 9.16683 16.6663 9.53993 16.6663 10.0002C16.6663 10.4604 16.2932 10.8335 15.833 10.8335H10.833V15.8335C10.833 16.2937 10.4599 16.6668 9.99967 16.6668C9.53944 16.6668 9.16634 16.2937 9.16634 15.8335V10.8335H4.16634C3.7061 10.8335 3.33301 10.4604 3.33301 10.0002C3.33301 9.53993 3.7061 9.16683 4.16634 9.16683H9.16634V4.16683C9.16634 3.70659 9.53944 3.3335 9.99967 3.3335Z"
                                    fill="#536E9B"></path>
                            </svg>
                            Добавить пользователя
                        </button>
                    </div>
{{--                    <form method="POST" action="{{ route('add.partner.user') }}">--}}

                        <div class="d-flex modal-add-user">
                            <div class="d-down-field field-style">
                                <span>Выберите пользователя</span>
                                <select name="user_id" id="partner_user" class="partnerAdmin">
                                    @foreach($absentUsers as $user)
                                        @if($loop->count == 1||$absentUsers->count()==1)
                                            <option selected value="{{ $user->id }}">{{ $user->email }}</option>
                                            @continue
                                        @else
                                            <option value="{{ $user->id }}">{{ $user->email }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="addPartnerUser" data-user-partner-id="{{ $partner->id }}">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.99967 3.3335C10.4599 3.3335 10.833 3.70659 10.833 4.16683V9.16683H15.833C16.2932 9.16683 16.6663 9.53993 16.6663 10.0002C16.6663 10.4604 16.2932 10.8335 15.833 10.8335H10.833V15.8335C10.833 16.2937 10.4599 16.6668 9.99967 16.6668C9.53944 16.6668 9.16634 16.2937 9.16634 15.8335V10.8335H4.16634C3.7061 10.8335 3.33301 10.4604 3.33301 10.0002C3.33301 9.53993 3.7061 9.16683 4.16634 9.16683H9.16634V4.16683C9.16634 3.70659 9.53944 3.3335 9.99967 3.3335Z"
                                        fill="#536E9B"></path>
                                </svg>
                                Добавить
                            </button>
                            <button type="button" class="close-add" style="margin-left: auto;">
                                <svg width="15" height="14" viewBox="0 0 15 14" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M1.07268 0.851982C1.44693 0.494001 2.05372 0.494001 2.42797 0.851982L7.50033 5.7038L12.5727 0.851982C12.9469 0.494001 13.5537 0.494001 13.928 0.851982C14.3022 1.20996 14.3022 1.79036 13.928 2.14834L8.85561 7.00016L13.928 11.852C14.3022 12.21 14.3022 12.7904 13.928 13.1483C13.5537 13.5063 12.9469 13.5063 12.5727 13.1483L7.50033 8.29653L2.42797 13.1483C2.05372 13.5063 1.44693 13.5063 1.07268 13.1483C0.698429 12.7904 0.698429 12.21 1.07268 11.852L6.14504 7.00016L1.07268 2.14834C0.698429 1.79036 0.698429 1.20996 1.07268 0.851982Z"
                                        fill="#D0D0D0"></path>
                                </svg>
                            </button>
                        </div>
{{--                    </form>--}}
                </div>
                <table class="table table_sort adminsEmail">
                    <thead>
                    <tr>
                        <th></th>
                        <th class="sortable">Пользователь</th>
                        <th class="sortable">Статус</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($partnerUsers as $partnerUser)
                        <tr class="tr">
                            <td class="tr-id">{{ $loop->index +1 }}</td>
                            <td>
                                <div class="first-info d-flex align-items-center">
                                        <span>
                                            {{ $partnerUser->user->email }}
                                        </span>
                                </div>
                                <div>Авто: {{ $partnerUser->user->cars->count()  }}</div>
                            </td>
                            <td style="width: 135px;">
                                <label class="switch-radio-wrap"><span class="d-none check-st">2<span></span></span>
                                    <input type="checkbox" name="switch-radio" class="partner-user-activate"
                                           data-partner-id="{{ $partnerUser->partner->id }}"
                                           data-user-id="{{ $partnerUser->user->id }}"
                                           @if($partnerUser->active) checked @endif
                                    >
                                    <span class="switcher-radio"></span>
                                    <span class="table-modal-status-active">Активен</span>
                                    <span class="table-modal-status-disabled">Не активен</span>
                                </label>
                            </td>
                        </tr>
                    @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
