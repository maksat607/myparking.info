const imageDelete = {
    imgId: null,
    init() {
        $(`body`).on('click', `.page-file__delete`, {self:this}, this.deleteFromDb);
    },
    remove(el) {
        el.remove();
    },
    async deleteFromDb(e) {
        console.log('deleting..');
        let self = e.data.self;
        self.imgId = $(this).data('img-id');
        if(!self.imgId) return;
        await axios.get(`${APP_URL}/application/remove/attachment/${self.imgId}`)
            .then(response => {
                self.remove($(this).parents(`.page-file-item`));
            }).catch(error => {
                console.log(error);
            });
    }
}
imageDelete.init();
