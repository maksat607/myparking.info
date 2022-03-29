const carSelectAjax = {
    selectId: null,
    dataId: null,
    modelId: null,
    year: null,
    modificationId: null,
    items: null,
    excluded: [5, 3],
    timeoutPromise: 500,
    init() {
        if(typeof carDataApplication == 'undefined' || carDataApplication == null) {
            $.when(`#types .select-item.active a`).then((response) => {
                $(`${response}`).trigger('click', {self:this});
            });
        } else {
            this.modelId = carDataApplication.modelId;
            this.year = carDataApplication.year;
            this.modificationId = carDataApplication.modificationId;
            this.addHiddenInput();
        }

        $(`#types .select-item a`).on('click', {self:this}, this.getMarks);
        $(`body`).on('click', `#marks .select-item a`, {self:this}, this.getModels);
        $(`body`).on('click', `#models .select-item a`, {self:this}, this.getYears);
        $(`body`).on('click', `#years .select-item a`, {self:this}, this.getGenerations);
        $(`body`).on('click', `#generations .select-item a`, {self:this}, this.getSeries);
        $(`body`).on('click', `#series .select-item a`, {self:this}, this.getModifications);
        $(`body`).on('click', `#modifications .select-item a`, {self:this}, this.getEngines);
        $(`body`).on('click', `#engines .select-item a`, {self:this}, this.getTransmissions);
        $(`body`).on('click', `#transmissions .select-item a`, {self:this}, this.getGears);
        $(`body`).on('click', `#gears .select-item a`, {self:this}, function(e) {
            e.preventDefault();
            let self = e.data.self;
            self.setActive(this);
            self.addHiddenInput();
        });

        $(`body`).on('click', `.tabform__btn`, {self:this}, function(e) {
            let self = e.data.self;
            self.scrollActive($(`.select:visible`));
        })
    },
    scrollActive(selects) {
        selects.each(function(index, element){
            $.when(element).then(response => {
                let topEl = $(`.select-item.active`, element).position().top;
                if(topEl !== 0) {
                    $('ul.select-list', $(element)).scrollTop( topEl );
                }
            });
        });

    },
    async getMarks(e) {
        e.preventDefault();
        let self = e.data.self;
        self.dataId = $(this).data(`id`);

        self.setActive(this);
        self.addHiddenInput();
        self.resetLists(['#types', '#marks']);

        if(self.toggleTextArea()) return;

        if(self.dataId) {
            await axios.get(`${APP_URL}/car/mark/list/${self.dataId}`)
                .then(response => {
                    self.items = response.data;
                }).catch(error => {
                    self.items = null;
                    self.resetLists(['#types']);
                });
        }
        self.setHTML(`marks`, `car_mark_id`);
    },
    async getModels(e) {
        e.preventDefault();
        let self = e.data.self;
        self.dataId = $(this).data(`id`);

        self.setActive(this);
        self.addHiddenInput();
        self.resetLists(['#types', '#marks', '#models']);

        if(self.dataId) {
            await axios.get(`${APP_URL}/car/model/list/${self.dataId}`)
                .then(response => {
                    self.items = response.data;
                }).catch(error => {
                    self.items = null;
                });
        }
        self.setHTML(`models`, `car_model_id`);
    },
    async getYears(e) {
        e.preventDefault();
        let self = e.data.self;
        self.dataId = $(this).data(`id`);
        self.modelId = self.dataId;

        self.setActive(this);
        self.addHiddenInput();
        self.resetLists(['#types', '#marks', '#models', '#years']);

        if(self.dataId) {
            await axios.get(`${APP_URL}/car/year/list/${self.dataId}`)
                .then(response => {
                    self.items = response.data;
                }).catch(error => {
                    self.items = null;
                });
        }

        self.items = self.filteredItems();
        self.setHTML(`years`, `year`);

    },
    async getGenerations(e) {
        e.preventDefault();
        let self = e.data.self;
        self.dataId = $(this).data(`id`);
        self.year = self.dataId;

        self.setActive(this);
        self.addHiddenInput();
        self.resetLists(['#types', '#marks', '#models', '#years', '#generations']);

        if(self.dataId) {
            await axios.get(`${APP_URL}/car/generation/list/${self.modelId}/${self.dataId}`)
                .then(response => {
                    self.items = response.data;
                }).catch(error => {
                    self.items = null;
                });
        }

        self.setHTML(`generations`, `car_generation_id`);

    },
    async getSeries(e) {
        e.preventDefault();
        let self = e.data.self;
        self.dataId = $(this).data(`id`);

        self.setActive(this);
        self.addHiddenInput();
        self.resetLists(['#types', '#marks', '#models', '#years', '#generations', '#series']);

        if(self.dataId) {
            await axios.get(`${APP_URL}/car/series/list/${self.modelId}/${self.dataId}`)
                .then(response => {
                    self.items = response.data;
                }).catch(error => {
                    self.items = null;
                });
        }

        self.setHTML(`series`, `car_series_id`);

    },
    async getModifications(e) {
        e.preventDefault();
        let self = e.data.self;
        self.dataId = $(this).data(`id`);

        self.setActive(this);
        self.addHiddenInput();
        self.resetLists(['#types', '#marks', '#models', '#years', '#generations', '#series', '#modifications']);

        if(self.dataId) {
            await axios.get(`${APP_URL}/car/modification/list/${self.modelId}/${self.dataId}/${self.year}`)
                .then(response => {
                    self.items = response.data;
                }).catch(error => {
                    self.items = null;
                });
        }

        self.setHTML(`modifications`, `car_modification_id`);

    },
    async getEngines(e) {
        e.preventDefault();
        let self = e.data.self;
        self.dataId = $(this).data(`id`);
        self.modificationId = self.dataId;

        self.setActive(this);
        self.addHiddenInput();
        self.resetLists(['#types', '#marks', '#models', '#years', '#generations', '#series', '#modifications',
            '#engines']);

        if(self.dataId) {
            await axios.get(`${APP_URL}/car/characteristic/engine/${self.dataId}`)
                .then(response => {
                    self.items = response.data;
                }).catch(error => {
                    self.items = null;
                });
        }

        self.setHTML(`engines`, `car_engine_id`);

    },
    async getTransmissions(e) {
        e.preventDefault();
        let self = e.data.self;
        self.dataId = $(this).data(`id`);

        self.setActive(this);
        self.addHiddenInput();
        self.resetLists(['#types', '#marks', '#models', '#years', '#generations', '#series', '#modifications',
            '#engines', '#transmissions']);

        if(self.dataId) {
            await axios.get(`${APP_URL}/car/characteristic/transmission/${self.modificationId}`)
                .then(response => {
                    self.items = response.data;

                }).catch(error => {
                    self.items = null;
                });
        }

        self.setHTML(`transmissions`, `car_transmission_id`);

    },
    async getGears(e) {
        e.preventDefault();
        let self = e.data.self;
        self.dataId = $(this).data(`id`);

        self.setActive(this);
        self.addHiddenInput();
        self.resetLists(['#types', '#marks', '#models', '#years', '#generations', '#series', '#modifications',
            '#engines', '#transmissions', '#gears']);

        if(self.dataId) {
            await axios.get(`${APP_URL}/car/characteristic/gear/${self.modificationId}`)
                .then(response => {
                    self.items = response.data;

                }).catch(error => {
                    self.items = null;
                });
        }

        self.setHTML(`gears`, `car_gear_id`);

    },
    setHTML(selectId, nameId = null) {
        let self = this;
        let html = '';

        if(!self.items.length) return;

        self.items.forEach((element)=>{
            let bodyData = (element.body) ? element.body : null;
            html += `<li class="select-item">`;
                html += `<a href="" data-name-id="${nameId}" data-id="${element.id}" ${(bodyData)?`data-body="${bodyData}"`:''}>${element.name}</a>`;
            html += `</li>`;
        });

        self.activeOneElement(selectId);
        $(`#${selectId} .select-list`).html(html);
    },
    activeOneElement(selectId) {
        $.when($(`#${selectId} .select-list`))
            .then((response) => {
                    if(response.find('li').length == 1) {
                        response.find('a').trigger('click');
                    }
            });
    },
    filteredItems() {
        let self = this;
        if (self.items) {
            let filteredItems = [];
            if (("year_begin" in self.items) && ("year_end" in self.items)) {
                let currentYear = self.items.year_end;
                while (currentYear >= self.items.year_begin) {
                    filteredItems.push({'name': currentYear, 'id': currentYear});
                    currentYear--;
                }
            }
            else if ("year_begin" in self.items) {
                let endYear = new Date();
                let currentYear = endYear.getFullYear();
                while (currentYear >= self.items.year_begin) {
                    filteredItems.push({'name': currentYear, 'id': currentYear});
                    currentYear--;
                }
            }
            else {
                filteredItems.push({'name': 'Год Не Указан', 'id': 0});
            }

            return filteredItems;
        }
        else {
            return [{'name': 'Год Не Указан', 'id': 0}];
        }
    },
    resetLists(not = []) {
        let self = this;
        let ul = $(`.select`).not(not.join(',')).find(`.select-list`);
        ul.each((index, element) => {
            let placeholder = $(element).data('placeholder');
            $(element).html(`<li class="placeholder statuspink">${placeholder}</li>`);
        });

    },
    setActive(element) {
        let self = this;
        $(element).parents(`.select-list`)
            .find(`.select-item`).removeClass(`active`);
        $(element).parent(`li`).addClass(`active`);

    },
    addHiddenInput() {
        let self = this;
        let actives = $(`.select .select-item.active a`);
        let inputs = '';
        actives.each((index, element) => {
            let name = $(element).data('name-id');
            let id = $(element).data('id');
            let body = $(element).data('body');
            inputs += `<input type="hidden" id="${name}" name="car_data[${name}]" value="${id}">`;
            if(body) {
                inputs += `<input type="hidden" id="car_series_body" name="car_data[car_series_body]" value="${body}">`;
            }
        });
        $(`#hiddenInputs`).html(inputs);
    },
    toggleTextArea() {
        let self = this;
        if(self.excluded.includes(self.dataId)) {
            $(`.new-style-model[data-id="selectGroup"]`).addClass(`d-none`);
            $(`#textArea`).removeClass(`d-none`);
            self.resetLists(['#types']);
            self.addHiddenInput();
            return true;
        }

        $(`.new-style-model[data-id="selectGroup"]`).removeClass(`d-none`);
        $(`#textArea`).addClass(`d-none`);
        return false;

    }
}

carSelectAjax.init();
