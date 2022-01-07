const modalAjaxContent = {
    init() {
        $(`.car-show-modal`).on('click', {self:this}, this.getModalContent);
    },
    getModalContent(e) {
        let self = e.data.self
        let applicationId = $(this).data('app-id');
        axios.get(`${APP_URL}/application/get-model-content/${applicationId}`)
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
