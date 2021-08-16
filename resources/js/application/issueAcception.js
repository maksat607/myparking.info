const issueAcception = {
    application_id: null,
    init() {
        $(`.issue`).on('click', {self:this}, function(e){
            let self = e.data.self;
            self.application_id = $(this).data('app-id');
            self.acceptions(e, self);
        });

        $(`.deny`).on('click', {self:this}, function(e){
            let self = e.data.self;
            self.application_id = $(this).data('app-id');
            self.deny(e, self);
        });
    },
    acceptions(e, self) {
        if(!self.application_id) return null;
        axios.get(`${APP_URL}/application/acceptions/${self.application_id}`)
            .then(response => {
                if(response.data.id) {
                    self.setHtml(response.data.id, response.data.html,);
                }
            }).catch(error => {
            console.log('error:', error);
        });

    },
    deny(e, self) {
        if(!self.application_id) return null;
        axios.get(`${APP_URL}/application/deny/${self.application_id}`)
            .then(response => {
                if(response.data.id) {
                    self.setHtml(response.data.id, response.data.html,);
                }
            }).catch(error => {
            console.log('error:', error);
        });

    },
    setHtml(id, html) {
        let readyHtml = $(`#application_${id}`).html(html);
        $.when( readyHtml ).then(function( data, textStatus, jqXHR ) {
            $(`#application_${id} .lazy`).slick({
                lazyLoad: 'ondemand',
                infinite: true,
                dots: true,
                autoplay: false,
                arrows: true
            });
        });

    }
}
issueAcception.init();
