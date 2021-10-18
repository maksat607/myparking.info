const applicationFilter = {
    form: '#appFilter',
    init() {
        $(`${this.form} select,
            ${this.form} input[type="text"],
            ${this.form} input[type="checkbox"],
            ${this.form} input[type="radio"]`,)
            .on('change', {self:this}, this.send);
    },
    send(e) {
        e.preventDefault();
        let self = e.data.self;
        $(`${self.form}`).trigger('submit');
    }
}
applicationFilter.init();
