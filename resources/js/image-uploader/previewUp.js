let dt = new DataTransfer();
let files = [];

$(`body`).on('click', `.page-add-file`, function () {
    console.log(this);
    $(`#uploader`).trigger('click');
});

/*window.onbeforeunload = function() {
    return "Есть несохранённые изменения. Всё равно уходим?";
};*/

/*$(`#uploader`).on('change', function () {
    for (let i = 0; i < this.files.length; i++) {
        let file = this.files.item(i);
        dt.items.add(file);
    }
    writeHtml(dt.files);
    updateBlopFiles(dt.files);

});

function updateBlopFiles(files) {
    $(`#uploader`).prop('files', files);
}

function writeHtml(files){
    let html = `<div class="page-add-file">
                                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.6"
                                              d="M20.0013 6.6665C20.9218 6.6665 21.668 7.4127 21.668 8.33317V18.3332H31.668C32.5884 18.3332 33.3346 19.0794 33.3346 19.9998C33.3346 20.9203 32.5884 21.6665 31.668 21.6665H21.668V31.6665C21.668 32.587 20.9218 33.3332 20.0013 33.3332C19.0808 33.3332 18.3346 32.587 18.3346 31.6665V21.6665H8.33464C7.41416 21.6665 6.66797 20.9203 6.66797 19.9998C6.66797 19.0794 7.41416 18.3332 8.33464 18.3332H18.3346V8.33317C18.3346 7.4127 19.0808 6.6665 20.0013 6.6665Z"
                                              fill="#536E9B" />
                                    </svg>
                                </div>`;
    for (let i = 0; i < files.length; i++) {
        let file = files.item(i);

        html += `<div class="page-file-item">
                                    <img src="${URL.createObjectURL(file)}" alt="">
                                    <div class="page-file__option">
                                        <button type="button" class="page-file__zoom"></button>
                                        <button type="button" class="page-file__delete" data-index="${i}"></button>
                                    </div>
                                </div>`;

    }
    $(`#images`).html(html);
}

$(`#images`).on('click', `.page-file__delete`, function () {
    let i = $(this).data('index');
    let parent = $(this).parents(`.page-file-item`);

    dt.items.remove(i);
    parent.remove();
    writeHtml(dt.files);
    updateBlopFiles(dt.files);
} );*/
