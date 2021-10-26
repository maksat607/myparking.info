const favorite = {
    application_id: null,
    parent: null,
    init() {
        $(`.favorite`).on('click', {self:this}, function(e){
            let self = e.data.self
            self.parent = $(this).parent();
            self.application_id = $(this).data('app-id');
            self.setFavorite();
        });
    },
    setFavorite() {
        axios.get(`${APP_URL}/application/favorite/${this.application_id}`)
            .then(response => {
                if(response.data.message) {
                    toastr.info(response.data.message);
                    this.parent.removeClass(response.data.remove_class).addClass(response.data.class);
                }
                if(this.isFilter()) {
                    this.parent.parent('.newcart__item').remove();
                }
            }).catch(error => {
            console.log('error:', error);
        });
    },
    isFilter() {
        const urlSearchParams = new URLSearchParams(window.location.search);
        return urlSearchParams.has('favorite');
    }
}
favorite.init();
