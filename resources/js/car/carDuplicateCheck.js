const checkDuplicate = {
    application_id: null,
    vin: null,
    licensePlate: null,
    vinDuplicates: null,
    licensePlateDuplicates: null,
    allDuplicates: null,
    statusLabels: {
        'storage': 'Х',
        'issued': 'В',
        'draft': 'Ч',
        'pending': 'НХ',
        'denied-for-storage': 'ОХ',
        'cancelled-by-partner': 'ОП',
        'cancelled-by-us': 'ОН',
        'deleted': 'УН'
    },
    statusClass: {
        'storage': 'conformity-success',
        'issued': 'conformity-dark',
        'draft': 'conformity-warning',
        'pending': 'conformity-primary',
        'denied-for-storage': 'conformity-orange',
        'deleted': 'conformity-red',
        'cancelled-by-us': 'conformity-red',
        /*'cancelled-by-partner':'ОП',
        'cancelled-by-us': 'ОН'*/
    },
    init() {
        $(`#vin`).on('input', {self: this}, function (e) {
            if ($(this).val().length < 1 && !$('.repeat-checkbox').hasClass('d-none')) {
                $('#repeat-checkbox').prop('checked', false);
                $('.repeat-checkbox').addClass('d-none');
            }
            let self = e.data.self;
            self.vin = $(`#vin`).val().split(',');

            self.licensePlate = $(`#license_plate`).val();
            self.duplicateExist();
        });
        $(`#license_plate`).on('input', {self: this}, function (e) {
            let self = e.data.self;
            self.vin = $(`#vin`).val().split(',');

            self.licensePlate = $(`#license_plate`).val();
            self.duplicateExist();
        });
        $("#repeat-checkbox").change(function () {
            if (this.checked) {
                console.log('Repeated on')
            } else {
                console.log('Repeated off')
            }
        });
    },
    duplicateExist() {
        if (this.vin.length > 0 || this.licensePlate) {
            axios.get(`${APP_URL}/application/check-duplicate`, {
                params: {
                    vin: this.vin,
                    license_plate: this.licensePlate,
                    id: this.application_id
                }
            }).then(response => {
                this.vinDuplicates = response.data.vin;
                this.licensePlateDuplicates = response.data.license_plate;
                this.setHtml();
                this.setCheckboxReturned();
            }).catch(error => {
                console.log('error:', error);
            });
        }
    },
    setHtml() {
        let allHtml = '';
        let vinsHtml = '';
        let licensePlate = '';
        let arrMerge = [...this.vinDuplicates, ...this.licensePlateDuplicates];
        let set = new Set();
        this.allDuplicates = arrMerge.filter((item, index) => {
            if (!set.has(item.id)) {
                set.add(item.id);
                return true;
            }
            return false;
        }, set)

        if (this.allDuplicates) {
            this.allDuplicates.forEach((element) => {

                if (element.status.id == 3) {
                    $('.repeat-checkbox').removeClass('d-none')
                    // if (!$('#repeat-checkbox').prop('checked')) {
                    //     $('#repeat-checkbox').prop('checked', false);
                    // }

                    allHtml += `<a class="conformity-link">`;
                } else {
                    allHtml += `<a href="${APP_URL}/applications/${element.id}/edit" class="conformity-link">`;
                }
                allHtml += `<span class="conformity__info">${element.vin}</span>`;
                allHtml += `<span class="${this.statusClass[element.status.code]} conformity__icon">${this.statusLabels[element.status.code]}</span>`;
                allHtml += `</a>`;
            });
        } else if (!$('.repeat-checkbox').hasClass('d-none')) {
            $('#repeat-checkbox').prop('checked', false);
            $('.repeat-checkbox').addClass('d-none');
        }

        /*        if(this.vinDuplicates.length) {
                    this.vinDuplicates.forEach((element) => {
                        vinsHtml += `<a href="${APP_URL}/applications/create/${element.id}" class="conformity-link">`;
                            vinsHtml += `<span class="conformity__info">${element.vin}</span>`;
                            vinsHtml += `<span class="${this.statusClass[element.status_code]} conformity__icon">${this.statusLabels[element.status_code]}</span>`;
                        vinsHtml += `</a>`;
                    });
                }

                if(this.licensePlateDuplicates.length) {
                    this.licensePlateDuplicates.forEach((element) => {
                        licensePlate += `<a href="${APP_URL}/applications/create/${element.id}" class="conformity-link">`;
                            licensePlate += `<span class="conformity__info">${element.vin}</span>`;
                            licensePlate += `<span class="${this.statusClass[element.status_code]} conformity__icon">${this.statusLabels[element.status_code]}</span>`;
                        licensePlate += `</a>`;
                    });
                }*/

        $(`#allDuplicates`).html(allHtml);
        /*$(`#vinDuplicates`).html(vinsHtml);
        $(`#licensePlateDuplicates`).html(licensePlate);*/
        this.addDanderClass();
    },
    setCheckboxReturned() {
        let checkbox = '';
        if (this.vinDuplicates.length || this.licensePlateDuplicates.length) {
            checkbox += `<label class="tabform__checkbox" id="returned">`;
            checkbox += `<input type="checkbox" name="car_data[returned]" value="1">`;
            checkbox += `<span class="tabform__checkboxnew"></span> Повтор`;
            checkbox += `</label>`;

            if ($(`#returned`).length === 0) {
                $(`#statusId`).after(checkbox);
            }
        } else {
            $(`#returned`).remove();
        }
    },
    addDanderClass() {
        if (this.vinDuplicates.length) {
            $(`#vin`).addClass('invalid');
        } else {
            $(`#vin`).removeClass('invalid');
        }


        if (this.licensePlateDuplicates.length) {
            $(`#license_plate`).addClass('invalid');
        } else {
            $(`#license_plate`).removeClass('invalid');
        }
    }
}
checkDuplicate.init();
