const stepTab = {
    buttons: null,
    init() {
        this.buttons = $(`.newtopbar .buttonWrapper button`);
        this.showHidePrev(this.activeTab().index());
        this.showHideNext(this.activeTab().index());
        this.showHideSave(this.activeTab().index());

        $(`#tabNext, #tabPrev`).on('click', {self:this}, this.slide);

    },
    slide(event) {
        event.preventDefault();
        let self = event.data.self;

        let activeBtn = self.activeTab();
        if(validate.filters(activeBtn) && ($(this).attr('id') == 'tabNext')) return;
        self.buttons.removeClass('active');

        let btnNext = self.buttons.eq(activeBtn.index() + self.prevNext(this));
        btnNext.addClass('active');

        let activeTab = btnNext.data('id');

        $(`.tabform__content`).removeClass('active');
        $(`#${activeTab}`).addClass('active');

        self.showHidePrev(self.activeTab().index());
        self.showHideNext(self.activeTab().index());
        self.showHideSave(self.activeTab().index());

        self.scrollActive($(`.select:visible`));

    },
    prevNext(self) {
        if($(self).attr('id') == 'tabNext')
            return 1;
        return -1;
    },
    showHidePrev(index) {
        if(index <= 0) {
            $(`#tabPrev`).hide();
        } else {
            $(`#tabPrev`).show();
        }
    },
    showHideNext(index) {
        if(index === (this.buttons.length -1)) {
            $(`#tabNext`).hide();
        } else {
            $(`#tabNext`).show();
        }
    },
    showHideSave(index) {
        if(index === (this.buttons.length -1)) {
            $(`#save`).show();
        } else {
            $(`#save`).hide();
        }
    },
    activeTab() {
        return this.buttons.filter('.active');
    },
    scrollActive(selects) {
        selects.each(function(index, element){
            $.when(element).then(response => {
                if($(`.select-item.active`, element).length > 0) {
                    let topEl = $(`.select-item.active`, element).position().top;
                    if(topEl > 250) {
                        $('ul.select-list', $(element)).scrollTop( topEl );
                    }
                }
            });
        });

    },
}

const validate = {
    filterable: null,
    filtered: [],
    filters(activeTab) {
        switch (activeTab.index()) {
            case 0:
                this.filtered = [];
                return this.filterApp();
            case 1:
                this.filtered = [];
                return this.filterCar();
            default:
                return true;
        }
    },
    filterApp() {
        this.filterable = $(`#vin, #license_plate, #external_id, #partner_id, #parking_id, #arriving_interval`);
        this.filterable.each((index, element) => {

            if($(element).val() == '' ) {
                this.filtered.push(`#${$(element).attr('id')}`);
            }

        });
        this.removeError();
        if(this.filtered.length == 1 && (this.filtered.includes('#vin') || this.filtered.includes('#license_plate'))) {
            this.filtered = [];
        }
        if(this.filtered.length > 0 && (this.filtered.indexOf()) ) {
            this.vinLicense();
            this.addError();
            return true
        }
        return false;
    },
    filterCar() {
        let els = ['car_type_id', 'car_mark_id', 'car_model_id', 'year'];

        els.forEach((value) => {

            if(!$(`#${value}`).val()) {
                this.filtered.push(`.${value}`);
            }

        });
        this.removeErrorCar(els);
        if(this.filtered.length > 0) {
            this.addError();
            return true
        }
        return false;
    },
    vinLicense() {
        if(!this.filtered.includes('#vin') && this.filtered.includes('#license_plate')) {
            this.filtered.splice(this.filtered.indexOf('#license_plate'), 1);
        }
        if (this.filtered.includes('#vin') && !this.filtered.includes('#license_plate')) {
            this.filtered.splice(this.filtered.indexOf('#vin'), 1);
        }
    },
    addError() {
        $(this.filtered.join()).addClass('is-invalid');
    },
    removeError() {
        this.filterable.on('change', function () {
            $(this).removeClass('is-invalid');
        });
    },
    removeErrorCar(els) {
        $(els.join(', .')).on('click', function () {
            $(this).removeClass('is-invalid');
        });
    }
}

stepTab.init();
