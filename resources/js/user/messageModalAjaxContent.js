const partnerModalAjaxContent = {
    init() {
        console.log('inside')
        $(`.message-user-show-modal`).on('click', {self:this}, this.getModalContent);
    },
    getModalContent(e) {
        let self = e.data.self
        let userId = $(this).data('user-id');
        axios.get(`${APP_URL}/users/message/${userId}`)
            .then(response => {
                if(response.data.success) {

                    self.setHtml(response.data.html);
                    self.initModal();
                }
            }).catch(error => {
            console.log('error:', error);
        });
    },
    setHtml(html) {
        $(`.message-to-users-modal-block`).html(html);
        $(`.message-to-users-overlay`);
    },
    initModal() {
        $('body').addClass('modal-open');
        $('#modal-message-to-users').modal('toggle')
    }
}
partnerModalAjaxContent.init();
