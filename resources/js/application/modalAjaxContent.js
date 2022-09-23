const modalAjaxContent = {
    init() {
        $(`.car-show-modal, .car-show-info, .app-notification`).on('click', {self:this}, this.getModalContent);

        $("body").on('click','.checkbox-approved',function() {
            let app_id = $(this).data('app-id')
            if(this.checked) {
                axios.get(`${APP_URL}/application/approved/${app_id}/1`);
            }else {
                axios.get(`${APP_URL}/application/approved/${app_id}/0`);
            }
        })
    },
    getModalContent(e) {
        console.log('clicked')
        let self = e.data.self
        let applicationId = $(this).data('app-id');
        let notification = $(this).data('notification');
        let additionalVar = '';
        if(notification){
            $(this).removeClass('new-notif');
            additionalVar = `?notification=${notification}`
        }
        axios.get(`${APP_URL}/application/get-model-content/${applicationId}${additionalVar}`)
            .then(response => {
                if(response.data.success) {
                    self.setHtml(response.data.html);
                    self.initSlick();
                }
            }).catch(error => {
            console.log('error:', error);
        });
    },
    setHtml(html) {
        $(`.modal-block`).html(html);
        $(`.overlay`);
    },
    initSlick() {
        $(`.modal-block`).addClass('active')
            .delay(50).queue(function(){
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
