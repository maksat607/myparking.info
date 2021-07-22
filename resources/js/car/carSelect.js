const carSelect = {
    select: null,
    selector: null,
    inputSearch: null,
    items: null,
    init(selector = `.select`) {
        this.selector = selector;
        this.select = $(this.selector);
        this.inputSearch = this.select.find(`.select-search`);
        this.inputSearch.on('input', {self:this}, this.search);
    },
    search(e) {
        let self = e.data.self;

        self.items = $(`.select-item`, $(this).parents(self.selector));
        self.items.hide().filter((index, element) => {
            let result = $(element).text().toLowerCase().indexOf($(this).val().toLowerCase());
            if (-1 !== result) return true;
        }).show();
    }
}

carSelect.init();
