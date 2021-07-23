const carSelectAjax = {
    selectId: null,
    dataId: null,
    items: null,
    excluded: [5, 3],
    init() {
        $.when(`#types`).then(() => {
            $(`#types .select-item.active a`).trigger('click', {self:this});
        });

        $(`#types .select-item a`).on('click', {self:this}, this.getMarks);
        $(`body`).on('click', `#marks .select-item a`, {self:this}, this.getModels);
        $(`body`).on('click', `#models .select-item a`, {self:this}, this.getYears);
    },
    async getMarks(e) {
        e.preventDefault();
        let self = e.data.self;
        self.dataId = $(this).data(`id`);

        if(self.toggleTextArea()) return;

        if(self.dataId) {
            await axios.get(`${APP_URL}/car/mark/list/${self.dataId}`)
                .then(response => {
                    self.items = response.data;
                }).catch(error => {
                    self.items = null;
                });
        }
        self.setHTML(`marks`, `mark_id`);
        self.resetLists(['#types', '#marks']);
        self.setActive(this);
        self.addHiddenInput();
    },
    async getModels(e) {
        e.preventDefault();
        let self = e.data.self;
        self.dataId = $(this).data(`id`);

        if(self.dataId) {
            await axios.get(`${APP_URL}/car/model/list/${self.dataId}`)
                .then(response => {
                    self.items = response.data;
                }).catch(error => {
                    self.items = null;
                });
        }
        self.setHTML(`models`, `model_id`);
        self.resetLists(['#types', '#marks', '#models']);
        self.setActive(this);
        self.addHiddenInput();
    },
    async getYears(e) {
        e.preventDefault();
        let self = e.data.self;
        self.dataId = $(this).data(`id`);

        if(self.dataId) {
            await axios.get(`${APP_URL}/car/year/list/${self.dataId}`)
                .then(response => {
                    self.items = response.data;
                }).catch(error => {
                    self.items = null;
                });
        }

        self.items = self.filteredItems();
        self.setHTML(`years`, `year_id`);
        self.setActive(this);
        self.addHiddenInput();
    },
    setHTML(selectId, nameId = null) {
        let self = this;
        let html = '';
        if(!self.items) return;

        self.items.forEach((element)=>{
            html += `<li class="select-item"><a href="" data-name-id="${nameId}" data-id="${element.id}">${element.name}</a></li>`;
        });

        $(`#${selectId} .select-list`).html(html);
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
            $(element).html(`<li class="placeholder">${placeholder}</li>`);
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
        let actives = $(`.select .select-item a.active`);
        let inputs = '';
        actives.each((index, element) => {
            let name = $(element).data('name-id');
            let id = $(element).data('id');
            inputs += `<input type="hidden" id="${name}" name="${name}" value="${id}">`;
        });
        $(`#hiddenInputs`).html(inputs);
    },
    toggleTextArea() {
        let self = this;
        if(self.excluded.includes(self.dataId)) {
            $(`#selectGroup`).addClass(`d-none`);
            $(`#textArea`).removeClass(`d-none`);
            self.resetLists(['#types']);
            self.addHiddenInput();
            return true;
        }

        $(`#selectGroup`).removeClass(`d-none`);
        $(`#textArea`).addClass(`d-none`);
        return false;

    }
}

carSelectAjax.init();
