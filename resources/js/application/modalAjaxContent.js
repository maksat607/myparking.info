const modalAjaxContent = {
    init() {
        $(`.newcart__moreinfo`).on('click', {self:this}, this.getModalContent);
    },
    getModalContent(e) {
        let self = e.data.self
        let applicationId = $(e.target).data('app-id');
        axios.get(`${APP_URL}/application/get-model-content/${applicationId}`)
            .then(response => {
                if(response.data.success) {
                    self.setHtml(response.data.html);
                }
            }).catch(error => {
            console.log('error:', error);
        });
    },
    setHtml(html) {
        $(`.newpopup .newpopup__top`).html(html);
    }
}
modalAjaxContent.init();
