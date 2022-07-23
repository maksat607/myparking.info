const stepTab = {
        init() {
            this.buttons = $(`.tabs a`);
            this.showHidePrev(this.activeTab().index());
            this.showHideNext(this.activeTab().index());
            this.showHideSave(this.activeTab().index());

            $(`#appStore`).on('submit', this.submit);

            $(`#tabNext, #tabPrev`).on('click', {self: this}, this.slide);


            $(`.checkbox-unknown`).on('click', {self: this}, function (e) {
                if (this.checked) {
                    $("#" + $(this).data('for')).attr("disabled", true);
                    $("#" + $(this).data('for')).parent().addClass('disabled')
                    $("#" + $(this).data('for')).val(null);

                } else {
                    $("#" + $(this).data('for')).attr("disabled", false);
                    $("#" + $(this).data('for')).parent().removeClass('disabled')
                    $("#" + $(this).data('for')).val(null);
                }
            });
        },
        buttons: null,
        selector: null,
        slide(event) {
            event.preventDefault();
            let self = event.data.self;

            let activeBtn = self.activeTab();
            if (validate.filters(activeBtn.index()) && ($(this).attr('id') == 'tabNext')) return;
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
            self.changeVinLicenseInputs();


        },
        changeVinLicenseInputs(){
            if($('.checkbox-unknown.clicense').is(":checked")){
                $('.license_plate').parent().addClass('disabled');
                $('.license_plate').attr("disabled", true);
                $('.license_plate').val(null);
            }else if(!$('.checkbox-unknown.clicense').is(":checked")){
                $('.license_plate').attr("disabled", false);
                $('.license_plate').parent().removeClass('disabled');
                $('.license_plate').val($('#license_plate').val())

            }

            if($(`.checkbox-unknown.cvin`).is(":checked")){
                $('.vin').parent().addClass('disabled');
                $('.vin').attr("disabled", true);
                $('.vin').val(null);

            }else if(!$(`.checkbox-unknown.cvin`).is(":checked")){
                $('.vin').attr("disabled", false);
                $('.vin').parent().removeClass('disabled');
                $('.vin').val($('#vin').val())

        }
    }
,
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
                    let heightEl = $(`.select-item.active`, element).height();
                    let topEl = $(`.select-item.active`, element).position().top - (heightEl + 9);
                    if(topEl > 290) {
                        $('ul.select-list', element).scrollTop( topEl );
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
        console.log(this.filtered)

        if($('.checkbox-unknown.clicense').is(":checked")){
            console.log('license checked')
            this.filtered = this.filtered.filter(e => e !== '#license_plate');
        }else if(!$('.checkbox-unknown.clicense').is(":checked")){
            console.log('license not checked')
            if (!this.filtered.includes('#license_plate')) {
                // this.filtered.push('#license_plate');
            }
        }

        if($(`.checkbox-unknown.cvin`).is(":checked")){
            console.log('vin checked')
            this.filtered = this.filtered.filter(e => e !== '#vin');
        }else if(!$(`.checkbox-unknown.cvin`).is(":checked")){
            console.log('vin not checked')
            if (!this.filtered.includes('#vin')) {
                // this.filtered.push('#vin');
            }
        }
        console.log(this.filtered)
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
        // if(this.filtered.length > 0) {
        let type_id = $('.type-list li.select-item.tabform__li.active a').data('id');
        console.log('sdfst'+type_id)
        console.log(els)
        if(this.filtered.includes(".car_mark_id")) {
            this.filtered = ['.car_mark_id'];
            if(type_id!=5){
                this.addCar();
                return true
            }
            // console.log(this.filtered)
            // if(this.filtered.includes(".car_model_id")){
            //
            // }


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

$('a[data-id="v-pills-2"]').on('click',function(){
    $('#tabNext').trigger('click');
});


$('a[data-id="v-pills-1"]').on('click',function(){
    $('#tabPrev').trigger('click');
});
