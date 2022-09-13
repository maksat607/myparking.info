const applicationFilter = {
    form: '#appPartnerFilter',
    init() {
        $(`
            ${this.form} input[type="text"],
            ${this.form} input[type="checkbox"],
            ${this.form} input[type="radio"]`,)
            .on('change', {self:this}, this.send);
    },
    send(e) {
        checkbox = e.target.name;
        console.log(checkbox)
        if(e.target.type=="checkbox" && $(`input[name=${checkbox}]`).is(':checked')){
            console.log(checkbox)

            if (checkbox=='public'){
                $('input[name="user"]').prop('checked', false);
            }else {
                $('input[name="public"]').prop('checked', false);
            }
        }

        e.preventDefault();
        // console.log($('input[name="public"]').is(':checked'))
        let self = e.data.self;

        $(`${self.form}`).trigger('submit');
    }
}
applicationFilter.init();
