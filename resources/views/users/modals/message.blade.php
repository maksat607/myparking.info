<div class="table-modal modal fade" id="modal-message-to-users" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal-tableLabel" aria-modal="true" role="dialog" style="display: block;">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-tableLabel">Сообщение для {{ $user->email }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.07268 0.851982C1.44693 0.494001 2.05372 0.494001 2.42797 0.851982L7.50033 5.7038L12.5727 0.851982C12.9469 0.494001 13.5537 0.494001 13.928 0.851982C14.3022 1.20996 14.3022 1.79036 13.928 2.14834L8.85561 7.00016L13.928 11.852C14.3022 12.21 14.3022 12.7904 13.928 13.1483C13.5537 13.5063 12.9469 13.5063 12.5727 13.1483L7.50033 8.29653L2.42797 13.1483C2.05372 13.5063 1.44693 13.5063 1.07268 13.1483C0.698429 12.7904 0.698429 12.21 1.07268 11.852L6.14504 7.00016L1.07268 2.14834C0.698429 1.79036 0.698429 1.20996 1.07268 0.851982Z" fill="#D0D0D0"></path>
                    </svg>

                </button>
            </div>
            <form method="POST" action="{{ route('user.message',$user->id) }}">
                @csrf
            <div class="scroll-modal mt-11px">
                <div class="table-modal-head">
                    <label class="field-style">
                        <span>Сообщение</span>
                        <textarea rows="4" cols="55" name="message"  id="message"></textarea>
                    </label>

                </div>

            </div>
            <div class="w-100 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary mb-3">Отправить</button>
            </div>
            </form>
        </div>
    </div>
</div>

