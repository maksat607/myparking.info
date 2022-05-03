const imageUpload = {
    dt: new DataTransfer(),
    imageDiv:null,
    appId:0,
    images:null,
    form:null,
    doc:false,
    docs:null,
    files:null,
    init() {


        $(`body`).on('click', `.page-add-file`,{self:this}, function (e) {
            let self = e.data.self;
            if($(this).hasClass("docs")){
                self.doc = true;

            }
            self.imageDiv = $(this).parent();
            if($(this).hasClass("no-ajax")){
                console.log(9999)
                $(`#noAjaxFileUploader`).trigger('click');
                return;
            }


            $(`#uploader`).trigger('click');

        });
        $(`body`).on('change',`#noAjaxFileUploader`,{self:this}, function (e) {
            var fd = new FormData();
            let self = e.data.self;

            for (let i = 0; i < this.files.length; i++) {
                let file = this.files.item(i);
                self.writeImage(file);
            }
            self.files = this.files;

        });
        $(`body`).on('change',`#uploader`,{self:this}, function (e) {
            var fd = new FormData();
            let self = e.data.self;

            let images = $('#uploader')[0];
            for (let i = 0; i < this.files.length; i++) {
                let file = this.files.item(i);
                fd.append(i, images.files[i]);

                if (!$('#appId').length){
                    self.writeImage(file);
                }

            }
            self.files = this.files;
            console.log($('#appId').length)

            if ($('#appId').length){
                console.log('inside')
                fd.append('doc', self.doc);
                self.form = fd;
                self.appId = $('#appId').val();

                self.uploadImage().then( v => {
                    console.log(v)
                    self.docs = v;
                    self.loopFiles();
                });
            }



            self.updateBlopFiles(self.dt.files);
        });

        $(`#images`).on('click', `.transfer__delete`, {self:this}, function (e) {
            let self = e.data.self;
            let parent = $(this).parents(`.transfer`);
            let i = parent.index() - (($(`.page-file-item:not(.transfer)`).length > 0) ? ($(`.page-file-item:not(.transfer)`).length +1) : 1);



            self.dt.items.remove(i);
            parent.remove();
            self.updateBlopFiles(self.dt.files);
        } );
    },
    updateBlopFiles(files) {
        $(`#uploader`).prop('files', files);
    },
    loopFiles(){

        for (let i = 0; i < this.files.length; i++) {
            let file = this.files.item(i);
            this.writeHtml(file);
        }
    }
    ,
    writeHtml(file){

        let html = "";
        let ext = "image";
        ext = file.name.split('.').pop();

        if(ext =='pdf'){
            html = `<div class="page-file-item doc">
                                <div class="file-icon pdf-icon"></div>
                                <span>${file.name}</span>
                                <div class="page-file__option">
                                    <button type="button" class="page-file__download"></button>
                                    <button type="button" class="page-file__delete" data-img-id="${this.docs[file.name]}"></button>
                                </div>
                             </div>`;
        }else if(ext=='doc' || ext=='docx'){
            html = `<div class="page-file-item doc">
                                    <div class="file-icon doc-icon"></div>
                                    <span>${file.name}</span>
                                    <div class="page-file__option">
                                        <button type="button" class="page-file__download"></button>
                                        <button type="button" class="page-file__delete" data-img-id="${this.docs[file.name]}"></button>
                                    </div>
                                </div>`;
        }else if(ext=='xls' || ext=='xlsx'|| ext=="csv"){
            html = `<div class="page-file-item doc">
                    <div class="file-icon xls-icon"></div>
                                <span>${file.name}</span>
                                <div class="page-file__option">
                                    <button type="button" class="page-file__download"></button>
                                    <button type="button" class="page-file__delete" data-img-id="${this.docs[file.name]}"></button>
                                </div>
                            </div>`;
        }else{
            html = `<div class="page-file-item transfer" data-src="${URL.createObjectURL(file)}">
                                <img src="${URL.createObjectURL(file)}" alt="">
                                <div class="page-file__option">
                                    <button type="button" class="page-file__zoom"></button>
                                     <button type="button" class="page-file__delete transfer__delete" data-img-id="${this.docs[file.name]}"></button>
                                </div>
                            </div>`;
        }


        this.imageDiv.append(html);
        // $(`#images`).append(html);

    },
    writeImage(file){

        let html = "";
        let ext = "image";
        ext = file.name.split('.').pop();

        if(ext =='pdf'){
            html = `<div class="page-file-item doc">
                                <div class="file-icon pdf-icon"></div>
                                <span>${file.name}</span>
                                <div class="page-file__option">
                                    <button type="button" class="page-file__download"></button>
                                    <button type="button" class="page-file__delete"></button>
                                </div>
                             </div>`;
        }else if(ext=='doc' || ext=='docx'){
            html = `<div class="page-file-item doc">
                                    <div class="file-icon doc-icon"></div>
                                    <span>${file.name}</span>
                                    <div class="page-file__option">
                                        <button type="button" class="page-file__download"></button>
                                        <button type="button" class="page-file__delete"></button>
                                    </div>
                                </div>`;
        }else if(ext=='xls' || ext=='xlsx'|| ext=="csv"){
            html = `<div class="page-file-item doc">
                    <div class="file-icon xls-icon"></div>
                                <span>${file.name}</span>
                                <div class="page-file__option">
                                    <button type="button" class="page-file__download"></button>
                                    <button type="button" class="page-file__delete"></button>
                                </div>
                            </div>`;
        }else{
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
    async  uploadImage(){
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
    }
}
imageUpload.init();


