const ptsType = {
    init() {
        $(`#pts_type`).on('change', {self:this}, this.ptsDoc);
        $(`#pts_type_input`).on('input', {self:this}, this.ptsInputDoc);
    },
    ptsDoc(e) {
        let self = e.data.self;
        let docType = $(this).val();
        $(`#pts_type_input`).val(docType);
    },
    ptsInputDoc(e) {
        let self = e.data.self;
        if(Boolean($(this).val())) {
            $(`#pts_type`).prop('disabled', true);
        } else {
            $(`#pts_type`).prop('disabled', false);
        }
    }
}

// ptsType.init();
