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
        $(`.car-show-modal, .car-show-info, .app-notification, .show-modal-chat`).on('click', {self: this}, this.getModalContent);
        $('body').on('click', `.show-modal-chat`, {self: this}, this.getModalContentChat);
        $('body').on('click', `.send-mess`, {self: this}, this.sendMessage);
        $('body').on('keyup', `#message`, {self: this}, this.triggerSubmit);
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
        let message = $('#message').val();
        $('#message').val('');
        if(message=='') return;
        axios.post(`${APP_URL}/application/send-chat-message/${applicationId}`, {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'message': message,

        })
            .then(response => {
                console.log(response.data.html)
                self.appendToModalChat(message);
                if (response.data.success) {

                    self.setHtml(response.data.html,'.chat__list');
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
                    self.setHtml(response.data.html, '.modal-block');
                    self.initSlick();
                }
            }).catch(error => {
            console.log('error:', error);
        });
    },
    appendToModalChat(message) {

        let html = `
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
        `;
        $('.chat__list').append(html);
    },
    setHtml(html, classname) {
        $(`${classname}`).empty();
        $(`${classname}`).html(html);
        $(`.overlay`);
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
