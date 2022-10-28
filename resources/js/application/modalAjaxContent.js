const modalAjaxContent = {
    init() {


        $("body").on('change', '.theme-back-white', function (e) {
            //this is just getting the value that is selected
            var value = $(this).val();
            if (value == 'approved')
                $('#ModeratorConfirmationModal').modal('show');
            if (value == 'reject') {
                $('#ModeratorRejectionModal').modal('show');
                console.log('populated');
            }

        });
        $(`.car-show-modal, .car-show-info, .app-notification`).on('click', {self: this}, this.getModalContent);
        $('body').on('click', `.app-notification.chat`, {self: this}, this.getModalContentChat);
        $('body').on('click', `.show-modal-chat`, {self: this}, this.getModalContentChat);
        $('body').on('click', `.send-mess`, {self: this}, this.sendMessage);
        $('body').on('keyup', `#storage_message`, {self: this}, this.triggerSubmit);
        $('body').on('keyup', `#partner_message`, {self: this}, this.triggerSubmit);
    },
    listen(){
        window.Echo.channel('chat')
            .listen('.message',(e) => {
                this.appendToModalChat(e.data);
            }).listen('.notification',(e) => {
                this.appendToNotification(e.message);
            })
            ;
    },
    appendToNotification(data) {
        console.log(data.users);

        let html = `
        <li class="new-notif app-notification chat" data-app-id="${data.id}"><a href="#">${data.short}</a><span>сейчас</span>
        </li>
        `;
        data.users.forEach(id =>{
                $(`ul.notification__dd-list.${id}`).prepend(html);
                let count = $(`.bell.notification__count.${id}`).text();
                $(`.bell.notification__count.${id}`).empty();
                $(`.bell.notification__count.${id}`).html(Number(count)+1);
        });

    },
    triggerSubmit(event) {

            if (event.which === 13) {
                $(".send-mess").trigger('click');

            }
    },
    sendMessage(e) {
        e.preventDefault();
        let self = e.data.self;
        let applicationId = $(this).data('app-id');
        let type = 'storage';
        let message = $('#storage_message').val();
        $('#storage_message').val('');
        if($(this).hasClass('partner')){
            type = 'partner';
            message = $('#partner_message').val();
            $('#partner_message').val('');
        }


        $('#message').val('');
        if(message=='') return;
        axios.post(`${APP_URL}/application/send-chat-message/${applicationId}`, {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'message': message,
            'type':type,

        })
            .then(response => {
                // self.appendToModalChat(message);
                if (response.data.success) {
                    // self.setHtml(response.data.html,`.chat__list.${type}`);
                    // self.initSlick();
                }

            }).catch(error => {
            console.log('error:', error);
        });

    },
    getModalContent(e) {

        let self = e.data.self
        let applicationId = $(this).data('app-id');
        let applicationTitle = $(this).data('app-title');
        let applicationUserId = $(this).data('app-user-id');
        let message_url = `${APP_URL}/application/send-chat-message/${applicationId}`;
        let notification = $(this).data('notification');
        let additionalVar = '';

        $('#messageForm').attr('action', message_url);
        $('.applicationToBeApproved').val(applicationId);
        $('#rejectAppTitle').text(applicationTitle);


        if (notification) {
            $(this).removeClass('new-notif');
            additionalVar = `?notification=${notification}`
        }
        axios.get(`${APP_URL}/application/get-model-content/${applicationId}${additionalVar}`)
            .then(response => {
                if (response.data.success) {
                    self.setHtml(response.data.html, '.modal-block');
                    self.initSlick();
                }
            }).catch(error => {
            console.log('error:', error);
        });
    },
    getModalContentChat(e) {
        console.log('chat')
        let self = e.data.self
        let applicationId = $(this).data('app-id');
        let additionalVar = '';
        axios.get(`${APP_URL}/application/get-model-content-app-chat/${applicationId}${additionalVar}`)
            .then(response => {
                if (response.data.success) {
                    self.setHtml(response.data.html, '.modal-block');
                    self.initSlick();
                }
            }).catch(error => {
            console.log('error:', error);
        });
    },
    getConfirmModalContent(e) {
        console.log('clicked')
        let self = e.data.self
        let applicationId = $(this).data('app-id');
        let notification = $(this).data('notification');
        let additionalVar = '';
        if (notification) {
            $(this).removeClass('new-notif');
            additionalVar = `?notification=${notification}`
        }
        axios.get(`${APP_URL}/application/get-confirm-model-content/${applicationId}${additionalVar}`)
            .then(response => {
                if (response.data.success) {
                    // self.setHtml(response.data.html, '.modal-block');
                    self.initSlick();
                }
            }).catch(error => {
            console.log('error:', error);
        });
    },
    appendToModalChat(data) {
        let html = '';
        let classname = '.chat__list.'+data.type+'.'+data.app_id;
        let user_id = $('#app').data('user-id');
        if(user_id == data.user_id){
             html = `
                <div class="chat__item user-mess">
                    <div class="d-flex">
                        <div class="chat__user-img">
                            <img src="${window.location.origin}/img/avatar.png" alt="">
                        </div>
                        <div class="chat__user-info">
                            <div
                                class="chat__user-name">${data.role}
                                (Вы)
                            </div>
                            <div class="chat__date">${data.date}</div>
                        </div>
                    </div>
                    <div class="chat__mess">
                        ${data.message}
                    </div>
                    <div>
                    </div>
                </div>


        `;
        }else {
             html = `
             <div class="chat__item">

                    <div class="d-flex">
                        <div class="chat__user-img">
                            <img src="${window.location.origin}/img/avatar.png" alt="">
                        </div>
                        <div class="chat__user-info">
                            <div
                                class="chat__user-name">${data.role}</div>
                             <div class="chat__date">${data.date}</div>
                        </div>
                    </div>
                    <div class="chat__mess">
                        ${data.message}
                    </div>
                    <div>
                    </div>
                </div>
            `;
        }
        $('.cunter-info.'+data.type+'.'+data.app_id).empty();
        $('.cunter-info.'+data.type+'.'+data.app_id).html(data.count);
        $(classname).prepend(html);
    },
    setHtml(html, classname) {
        $(`${classname}`).empty();
        $(`${classname}`).html(html);
        $(`.overlay`);
    },
    appendHtml(html,classname){
        $(`${classname}`).append(html);

        html = `
                <div class="chat__item user-mess">
                    <div class="d-flex">
                        <div class="chat__user-img">
                            <img src="./assets/img/avatar.png" alt="">
                        </div>
                        <div class="chat__user-info">
                            <div
                                class="chat__user-name">{{ auth()->user()->getRole() }}
                                (Вы)
                            </div>
                            <div class="chat__date">{{ ($notification->created_at->format('d.m.Y H:i')) }}</div>
                        </div>
                    </div>
                    <div class="chat__mess">
                        {{ json_decode($notification)->data->message }}
                    </div>
                    <div>
                    </div>
                </div>
            @else

                <div class="chat__item">

                    <div class="d-flex">
                        <div class="chat__user-img">
                            <img src="./assets/img/avatar.png" alt="">
                        </div>
                        <div class="chat__user-info">
                            <div
                                class="chat__user-name">{{ optional(json_decode($notification)->data)->role }}</div>
                            <div class="chat__date">{{ ($notification->created_at->format('d.m.Y H:i')) }}</div>
                        </div>
                    </div>
                    <div class="chat__mess">
                        {{ json_decode($notification)->data->message }}
                    </div>
                    <div>
                    </div>
                </div>
            @endif
        @endforeach
        <div>

        </div>


        `;
    },
    initSlick() {
        $(`.modal-block`).addClass('active')
            .delay(50).queue(function () {
            $(".car-slide", $(this)).slick({
                dots: false,
                infinite: false,
                slidesToShow: 1,
                slidesToScroll: 1,
            });
            $(this).dequeue();
        });

        $(`.overlay`).addClass('active');
    }
}
modalAjaxContent.init();
modalAjaxContent.listen();
