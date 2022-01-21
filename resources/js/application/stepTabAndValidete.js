const stepTab = {
    buttons: null,
    init() {
        this.buttons = $(`.tabs a`);
        this.showHidePrev(this.activeTab().index());
        this.showHideNext(this.activeTab().index());
        this.showHideSave(this.activeTab().index());

        $(`#appStore`).on('submit', this.submit);

        $(`#tabNext, #tabPrev`).on('click', {self:this}, this.slide);

    },
    slide(event) {
        event.preventDefault();
        let self = event.data.self;

        let activeBtn = self.activeTab();
        // if(validate.filters(activeBtn.index()) && ($(this).attr('id') == 'tabNext')) return;
        self.buttons.removeClass('active');

        let btnNext = self.buttons.eq(activeBtn.index() + self.prevNext(this));
        btnNext.addClass('active');

        let activeTab = btnNext.data('id');

        $(`.tab-pane`).removeClass('show active');
        $(`#${activeTab}`).addClass('show active');

        self.showHidePrev(self.activeTab().index());
        self.showHideNext(self.activeTab().index());
        self.showHideSave(self.activeTab().index());

        self.scrollActive($(`.select:visible`));
        self.scrollTopTab();

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
    scrollTopTab() {
        let topTab = $(`#app`).offset().top;
        $(`html, body`).stop().animate({
            scrollTop: topTab
        }, 500);
    },
    submit(event) {
        return !validate.filters(2);
    }
}

const validate = {
    filterable: null,
    filtered: [],
    filters(activeTabIndex) {
        switch (activeTabIndex) {
            case 0:
                this.filtered = [];
                return this.filterApp();
            case 1:
                this.filtered = [];
                return this.filterCar();
            case 2:
                this.filtered = [];
                return this.filterSubmit();
            default:
                return true;
        }
    },
    filterApp() {
        this.filterable = $(`#vin, #license_plate, #external_id, #partner_id, #parking_id, #arriving_interval`);
        this.filterable.each((index, element) => {

            if(!Boolean($(element).val())) {
                this.filtered.push(`#${$(element).attr('id')}`);
            }
        });
        this.removeError();

        if(this.filtered.length == 1 && (this.filtered.includes('#vin') || this.filtered.includes('#license_plate'))) {
            this.filtered = [];
        }
        if(this.filtered.length > 0) {
            this.vinLicense();
            this.addError();
            return true
        }
        return false;
    },
    filterCar() {
        let els = ['car_type_id', 'car_mark_id', 'car_model_id', 'year'];
        this.filtered = [];
        els.forEach((value) => {
            if(!$(`#${value}`).val()) {
                this.filtered.push(`.${value}`);
            }
        });

        this.removeErrorCar(els);
        if(this.filtered.length > 0) {
            this.addCar();
            return true
        }
        return false;
    },
    filterSubmit() {
        return (this.filterApp() || this.filterCar());
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
        $(this.filtered.join()).parents('label').addClass('invalid');
    },
    addCar() {
        $(this.filtered.join()).addClass('invalid');
    },
    removeError() {
        this.filterable.on('change', function () {
            console.log(['#vin', '#license_plate'].includes($(this).attr('id')))
            if(['#vin', '#license_plate'].includes(`#${$(this).attr('id')}`)) {
                $('#vin, #license_plate').parents('label').removeClass('invalid');
            } else {
                $(this).parents('label').removeClass('invalid');
            }
        });
    },
    removeErrorCar(els) {
        $(els.join(', .')).on('click', function () {
            $(this).removeClass('invalid');
        });
    }
}

stepTab.init();
