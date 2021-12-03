const checkDuplicate = {
    application_id: null,
    vin: null,
    licensePlate: null,
    vinDuplicates: null,
    licensePlateDuplicates: null,
    statusLabels : {
        'storage': 'Х',
        'issued': 'В',
        'draft': 'Ч',
        'pending': 'Ож',
        'denied-for-storage': 'ОХ',
        'cancelled-by-partner':'ОП',
        'cancelled-by-us': 'ОН'
    },
    init() {
        $(`#vin, #license_plate`).on('input', {self:this}, function(e){
            let self = e.data.self;
            self.vin = $(`#vin`).val().split(',');
            self.licensePlate = $(`#license_plate`).val();
            self.duplicateExist();
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
        let vinsHtml = '';
        let licensePlate = '';
        if(this.vinDuplicates.length) {
            this.vinDuplicates.forEach((element) => {
                vinsHtml += `<a href="${APP_URL}/applications/create/${element.id}">`;
                    vinsHtml += `<span class="tag">${element.vin}</span>`
                    vinsHtml += `<span class="tag bgpink">${this.statusLabels[element.status_code]}</span>`
                vinsHtml += `</a>`;
            });
        }

        if(this.licensePlateDuplicates.length) {
            this.licensePlateDuplicates.forEach((element) => {
                licensePlate += `<a href="${APP_URL}/applications/create/${element.id}">`;
                    licensePlate += `<span class="tag">${element.license_plate}</span>`
                    licensePlate += `<span class="tag bgpink">${this.statusLabels[element.status_code]}</span>`
                licensePlate += `</a>`;
            });
        }

        $(`#vinDuplicates`).html(vinsHtml);
        $(`#licensePlateDuplicates`).html(licensePlate);
        this.addDanderClass();
    },
    setCheckboxReturned() {
        let checkbox = '';
        if(this.vinDuplicates.length || this.licensePlateDuplicates.length) {
            checkbox += `<label class="tabform__checkbox" id="returned">`;
                checkbox += `<input type="checkbox" name="car_data[returned]" value="1">`;
                checkbox += `<span class="tabform__checkboxnew"></span> Повтор`;
            checkbox += `</label>`;

            if($(`#returned`).length === 0){
                $(`#statusId`).after(checkbox);
            }
        } else {
            $(`#returned`).remove();
        }
    },
    addDanderClass() {
        if(this.vinDuplicates.length) {
            $(`#vin`).addClass('is-invalid');
        } else {
            $(`#vin`).removeClass('is-invalid');
        }

        if(this.licensePlateDuplicates.length) {
            $(`#license_plate`).addClass('is-invalid');
        } else {
            $(`#license_plate`).removeClass('is-invalid');
        }
    }
}
checkDuplicate.init();
