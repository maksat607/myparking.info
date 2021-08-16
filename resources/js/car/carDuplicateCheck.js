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
        $(`#vin, #license_plate`).on('change', {self:this}, function(e){
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
            }).catch(error => {
                console.log('error:', error);
            });
        }
    },
    setHtml() {
        let vinsHtml = null;
        let licensePlate = null;
        if(this.vinDuplicates.length) {
            this.vinDuplicates.forEach((element) => {
                vinsHtml = `<a href="${APP_URL}/applications/${element.id}/edit">`;
                    vinsHtml += `<span class="tag">${element.vin}</span>`
                    vinsHtml += `<span class="tag is-danger">${this.statusLabels[element.status_code]}</span>`
                vinsHtml += `</a>`;
            });
        }

        if(this.licensePlateDuplicates.length) {
            this.licensePlateDuplicates.forEach((element) => {
                licensePlate = `<a href="${APP_URL}/applications/${element.id}/edit">`;
                    licensePlate += `<span class="tag">${element.license_plate}</span>`
                    licensePlate += `<span class="tag is-danger">${this.statusLabels[element.status_code]}</span>`
                licensePlate += `</a>`;
            });
        }

        $(`#vinDuplicates`).html(vinsHtml);
        $(`#licensePlateDuplicates`).html(licensePlate);
        this.addDanderClass();
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
