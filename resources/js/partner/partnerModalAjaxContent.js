const partnerModalAjaxContent = {
    init() {
        console.log('inside')
        $(`.partner-users-show-modal`).on('click', {self:this}, this.getModalContent);
    },
    getModalContent(e) {
        let self = e.data.self
        let partnerId = $(this).data('partner-id');
        axios.get(`${APP_URL}/partner/get-modal-users-content/${partnerId}`)
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
        $(`.partner-users-modal-block`).html(html);
        $(`.partner-overlay`);
    },
    initModal() {
        $('body').addClass('modal-open');
        $('#modal-table-partner-users').modal('toggle')
    }
}
partnerModalAjaxContent.init();
