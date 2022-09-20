const partnerModalAjaxContent = {
    init() {
        console.log('inside')
        $(`.partner-users-show-modal`).on('click', {self: this}, this.getModalContent);
        $(`.partner-user-activate`).on('change', {self: this}, this.togglePartnerUser);
        $(document).on('click', '.partner-user-activate',{self: this}, this.togglePartnerUser );
        $(document).on('click', '.addPartnerUser',{self: this}, this.addUser );
    },
    addUser(e){
        let partnerId = $(this).data('user-partner-id');
        let selected = $('.partnerAdmin').find(":selected");
        let email = selected.text();
        let userId = selected.val();

        axios.get(`${APP_URL}/partner/add-user/${partnerId}/${userId}`);
        $('#modal-table-partner-users').modal('toggle')
        $(`button[data-partner-id=${partnerId}]`).trigger('click');

        // const result = $.ajax({
        //     url: `/partner/add-user/${partnerId}/${id}`,
        //     type: 'get',
        //     contentType: false,
        //     processData: false
        // });
        // result.done(function(msg) {
        //     $('#modal-table-partner-users').modal('toggle')
        //     $(`button[data-partner-id=${partnerId}]`).trigger('click');
        // });





    },
    getModalContent(e) {
        let self = e.data.self
        let partnerId = $(this).data('partner-id');
        axios.get(`${APP_URL}/partner/get-modal-users-content/${partnerId}`)
            .then(response => {
                if (response.data.success) {
                    self.setHtml(response.data.html);
                    self.initModal();
                }
            }).catch(error => {
            console.log('error:', error);
        });
    },
    togglePartnerUser(e) {
        let self = e.data.self
        let userId = $(this).data('user-id');
        var fd = new FormData();
        fd.append('partnerId', $(this).data('partner-id'));
        const result = $.ajax({
            url: `/partner/user/${userId}`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            data: fd,
            contentType: false,
            processData: false
        });
        result.done(function(msg) {
            console.log( msg );
        });

    },
    setHtml(html) {
        $(`.partner-users-modal-block`).html(html);
        $(`.partner-overlay`);
    },
    initModal() {
        // $('body').addClass('modal-open');
        $('#modal-table-partner-users').modal('toggle')
    }
}
partnerModalAjaxContent.init();
