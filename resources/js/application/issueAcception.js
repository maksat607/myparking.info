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

        $(`#issuanceDocument, #issuanceDocumentInput`).on('change', {self:this}, this.issuanceDocType)
    },
    acceptions(e, self) {
        if(!self.application_id) return null;
        axios.get(`${APP_URL}/application/acceptions/${self.application_id}`)
            .then(response => {
                if(response.data.id) {
                    self.setHtml(response.data.id, response.data.html);
                }
            }).catch(error => {
            console.log('error:', error);
        });

    },
    issuanceDocType(e) {
        let self = e.data.self;
        let docType = $(this).val();
        let docTypeId = $(this).attr('id');
        if(docTypeId === 'issuanceDocument') {
            $(`#issuanceDocumentInput`).val(docType);
        } else if(docTypeId === 'issuanceDocumentInput' && docType) {
            $(`#issuanceDocument`).prop('disabled', true);
            let newOption = new Option(docType, docType, true, true);
            $(`#issuanceDocument`).append(newOption);
        } else {
            $(`#issuanceDocument`).prop('disabled', false);
            $(`#issuanceDocument`).find(':last').remove();
            $(`#issuanceDocument`).val('').trigger('change');
        }
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
