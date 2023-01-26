const imageUpload = {
    dt: new DataTransfer(),
    imageDiv: null,
    appId: 0,
    name: 0,
    url: null,
    images: null,
    form: null,
    attId: 0,
    doc: false,
    docs: [],
    files: null,
    init() {
        $(`body`).on('click', `.upload-docs`, {self: this}, function (e) {
            $(`#docUploader`).trigger('click');
        })
        $(`body`).on('click', `.upload-file`, {self: this}, function (e) {
            console.log('upload-file')
            let self = e.data.self;
            if ($(this).hasClass("docs")) {
                self.doc = true;

            }
            self.imageDiv = $(this).parent();
            if ($(this).hasClass("no-ajax")) {
                if ($(this).hasClass("doc")) {
                    $(`#noAjaxFileUploaderDoc`).trigger('click');
                    return;
                }
                $(`#noAjaxFileUploader`).trigger('click');
                return;
            }


            $(`#uploader`).trigger('click');

        });
        $(`body`).on('change', `#noAjaxFileUploader`, {self: this}, function (e) {
            console.log('noAjaxFileUploader')
            var fd = new FormData();
            let self = e.data.self;

            // fileList.push.apply(fileList,self.files);

            for (let i = 0; i < this.files.length; i++) {
                let file = this.files.item(i);
                self.writeImage(file);
            }
            self.files = this.files;

        });
        $(`body`).on('change', `#noAjaxFileUploaderDoc`, {self: this}, function (e) {
            var fd = new FormData();
            let self = e.data.self;
            // self.docs.push(fileList,this.files);
            for (let i = 0; i < this.files.length; i++) {
                let file = this.files.item(i);
                self.writeImage(file);
            }
            self.docs = this.files;
        });
        $(`body`).on('change', `#docUploader`, {self: this}, function (e) {
            var fd = new FormData();
            let self = e.data.self;
            self.imageDiv = $('#docsFiles');
            let images = $('#docUploader')[0];
            for (let i = 0; i < this.files.length; i++) {
                let file = this.files.item(i);
                fd.append(i, images.files[i]);

            }
            self.files = this.files;
                fd.append('doc', 'true');
                self.form = fd;
                console.log(fd)
                self.appId = $('#appId').val();

                self.uploadDoc().then(v => {
                    console.log(v)
                    self.docs = v;
                    self.loopFiles();
                });

        })
        $(`body`).on('change', `#uploader`, {self: this}, function (e) {
            var fd = new FormData();
            let self = e.data.self;
            let images = $('#uploader')[0];
            for (let i = 0; i < this.files.length; i++) {
                let file = this.files.item(i);

                upload(file);


                fd.append(i, images.files[i]);

                // if (!$('#appId').length) {
                //     self.writeImage(file);
                // }

            }
            self.files = this.files;
            console.log($('#appId').length)
            console.log("$('#appId').length")

            self.updateBlopFiles(self.dt.files);

            function upload(file) {

                let srcData
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function () {
                    // console.log(reader.result);
                    self.minifyImg(reader.result, 'image/jpeg', (data) => {
                        var fd = new FormData();
                        // console.log(file.name)
                        // fd.append('name',$('#pictures')[0].files[i].name )
                        fd.append('name', file.name)
                        fd.append("file", data);
                        fd.append("token", $('meta[name="csrf-token"]').attr('content'));

                        let id = -9999;
                        if ($('#appId').length > 0) {
                            id = $('#appId').val();
                        }
                        upload_image(fd,id).then(v => {
                            console.log(v)
                            self.url = v['url'];
                            self.attId = v['attachments'];
                            self.writeHtml(file)

                        });
                    }, 0.5);


                    async function upload_image(fd, id) {


                        const result = await $.ajax({
                            type: 'post',
                            url: `/api/v1/applications/upload/${id}`,
                            data: fd,
                            contentType: false,
                            processData: false,
                        })
                        return result;
                    }/**/

                };
                reader.onerror = function (error) {
                    console.log('Error: ', error);
                };

            }

        });

        $(`#images`).on('click', `.transfer__delete`, {self: this}, function (e) {
            let self = e.data.self;
            let parent = $(this).parents(`.transfer`);
            let i = parent.index() - (($(`.page-file-item:not(.transfer)`).length > 0) ? ($(`.page-file-item:not(.transfer)`).length + 1) : 1);


            self.dt.items.remove(i);
            parent.remove();
            self.updateBlopFiles(self.dt.files);
        });
    },
    updateBlopFiles(files) {
        $(`#uploader`).prop('files', files);
    },
    minifyImg(dataUrl, imageType = "image/jpeg", resolve, imageArguments = 0.5) {
        var image, newHeight, canvas, ctx, newDataUrl;
        (new Promise(function (resolve) {
            image = new Image();
            image.src = dataUrl;
            setTimeout(() => {
                resolve('Done : ');
            }, 1000);

        })).then((d) => {
            console.log(image.width)
            console.log(image.height)
            let newHeight = image.height;
            let newWidth = image.width;

            if (newWidth > 1600) {
                let ratio = newWidth / 1600
                newWidth = newWidth / ratio;
                newHeight = newHeight / ratio;
            }


            canvas = document.createElement("canvas");
            canvas.width = newWidth;
            canvas.height = newHeight;
            ctx = canvas.getContext("2d");
            ctx.drawImage(image, 0, 0, newWidth, newHeight);

            newDataUrl = canvas.toDataURL(imageType, imageArguments);


            resolve(newDataUrl);
        });
    },
    loopFiles() {
        for (let i = 0; i < this.files.length; i++) {
            let file = this.files.item(i);
            // this.writeImage(file);
            this.writeHtml(file);


        }
    }
    ,


    urltoFile(url, filename, mimeType) {
        return (fetch(url)
                .then(function (res) {
                    return res.arrayBuffer();
                })
                .then(function (buf) {
                    return new File([buf], filename, {type: mimeType});
                })
        );
    }

//Usage example:


    ,
    writeHtml(file) {
        console.log(file)
        let html = "";
        let ext = "image";
        ext = file.name.split('.').pop();
        console.log(ext)
        if (ext == 'pdf') {
            html = `<div class="page-file-item doc">
                                <div class="file-icon pdf-icon"></div>
                                <span>${file.name}</span>
                                <div class="page-file__option">
                                    <button type="button" class="page-file__download"></button>
                                    <button type="button" class="page-file__delete" data-img-id="${this.docs[file.name]}"></button>
                                </div>
                             </div>`;
        } else if (ext == 'doc' || ext == 'docx') {
            html = `<div class="page-file-item doc">
                                    <div class="file-icon doc-icon"></div>
                                    <span>${file.name}</span>
                                    <div class="page-file__option">
                                        <button type="button" class="page-file__download"></button>
                                        <button type="button" class="page-file__delete" data-img-id="${this.docs[file.name]}"></button>
                                    </div>
                                </div>`;
        } else if (ext == 'xls' || ext == 'xlsx' || ext == "csv") {
            html = `<div class="page-file-item doc">
                    <div class="file-icon xls-icon"></div>
                                <span>${file.name}</span>
                                <div class="page-file__option">
                                    <button type="button" class="page-file__download"></button>
                                    <button type="button" class="page-file__delete" data-img-id="${this.docs[file.name]}"></button>
                                </div>
                            </div>`;
        } else {
            html = `<div class="page-file-item transfer" data-src="${URL.createObjectURL(file)}">
                                <img src="${URL.createObjectURL(file)}" alt="">
                                <div class="page-file__option">
                                    <button type="button" class="page-file__zoom"></button>
                                    <button data-url="${this.url}" data-id="${this.attId}" type="button" id="file__mask"  class="page-file__mask"  ></button>
                                     <button  type="button" class="page-file__delete " data-img-id="${this.attId}"></button>
                                </div>
                            </div>`;
        }
        // `<button data-url="http://myparking.loc/uploads/63ce94ecef166^hd car wallpapers for mobile (24).jpg" data-id="28193" type="button" id="file__mask" class="page-file__mask"></button>`
        // `<button data-url="http://myparking.loc/uploads/63ced0183463f^hd car wallpapers for mobile (24).jpg" data-id="28206" type="button" id="file__mask" class="page-file__mask"></button>`
        // <button data-url="http://myparking.loc/uploads/63c7db5bc216b_image.^egor-vikhrev-K63ks3iwaj0-unsplash.jpg"
        //         data-id="27971" type="button" id="file__mask" className="page-file__mask"></button>


        this.imageDiv.append(html);
        // $(`#images`).append(html);

    },
    writeImage(file) {

        let html = "";
        let ext = "image";
        ext = file.name.split('.').pop();

        if (ext == 'pdf') {
            html = `<div class="page-file-item doc">
                                <div class="file-icon pdf-icon"></div>
                                <span>${file.name}</span>
                                <div class="page-file__option">
                                    <button type="button" class="page-file__download"></button>
                                    <button type="button" class="page-file__delete"></button>
                                </div>
                             </div>`;
        } else if (ext == 'doc' || ext == 'docx') {
            html = `<div class="page-file-item doc">
                                    <div class="file-icon doc-icon"></div>
                                    <span>${file.name}</span>
                                    <div class="page-file__option">
                                        <button type="button" class="page-file__download"></button>
                                        <button type="button" class="page-file__delete"></button>
                                    </div>
                                </div>`;
        } else if (ext == 'xls' || ext == 'xlsx' || ext == "csv") {
            html = `<div class="page-file-item doc">
                    <div class="file-icon xls-icon"></div>
                                <span>${file.name}</span>
                                <div class="page-file__option">
                                    <button type="button" class="page-file__download"></button>
                                    <button type="button" class="page-file__delete"></button>
                                </div>
                            </div>`;
        } else {
            html = `<div class="page-file-item transfer" data-src="${URL.createObjectURL(file)}">
                                <img src="${URL.createObjectURL(file)}" alt="">
                                <div class="page-file__option">

                                    <button type="button" class="page-file__zoom"></button>
                                     <button type="button" class="page-file__delete transfer__delete"></button>
                                </div>
                            </div>`;
        }


        this.imageDiv.append(html);
    },
    async uploadDoc() {
        // let result = 'kjbkjhbkjbjh';
        const result = await $.ajax({
            url: `/application/${this.appId}/upload`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            data: this.form,
            contentType: false,
            processData: false
        });
        return result;
    },

}
console.log('Modal');
imageUpload.init();


