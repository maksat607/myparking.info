const imageMask = {
    imgId: null,
    imageUrl: null,
    ratio: 1,
    fourPoints: [],
    init() {

        $(`body`).on('click', `.page-file__mask`, {self: this}, this.maskFromDb);
        $(`body`).on('click', `#putMask`, {self: this}, this.uploadImage);
        $(`body`).on('hidden.bs.modal', `#MaskImagesModal`, {self: this}, function (e) {
            let self = e.data.self;
            self.imgId = null;
            self.imageUrl = null;
            self.ratio = 1;
            self.fourPoints = [];
            console.log('on hidden')

            $('.output').empty();


        });


    },
    remove(el) {
        el.remove();
    },
    uploadImage(e) {
        event.preventDefault()
        let self = e.data.self;
        console.log(self)
        console.log($('.points-info').text())
        console.log(self.imgId)
        console.log(self.fourPoints)
        console.log(self.ratio)
        console.log(self.imageUrl)
        axios.post(`${APP_URL}/upload`, {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'filename': self.imageUrl,
            'id': self.imgId,
            'coordinates': self.fourPoints,
            'ratio': self.ratio


        })
            .then(response => {
                self.fourPoints = [];
                self.ratio = 1;
                // self.appendToModalChat(message);
                console.log("response.data")
                console.log(response.data)
                console.log(response.data.attId)
                console.log(response.data.thumbnail)
                $(`.thumbnail.${response.data.attId}`).remove();
                $(`.page-file-item.transfer.${response.data.attId}`).prepend(`
                    <img class="thumbnail ${response.data.attId}" src="${response.data.thumbnail}" alt="">
                `);
                console.log(`
                    <img class="thumbnail ${response.data.attId}" src="${response.data.thumbnail}" alt="">
                `)


                if (response.data.success) {

                    // self.setHtml(response.data.html,`.chat__list.${type}`);
                    // self.initSlick();
                }

            }).catch(error => {
            console.log('error:', error);
        });
        $('#MaskImagesModal').modal('hide');
        $('.output').empty();
    },
    maskFromDb(e) {
        let self = e.data.self;
        $('#MaskImagesModal').modal('show');
        let imageUrl = $(this).data('url').replaceAll(' ', '%20');
        let id = $(this).data('id');
        self.imgId = id;
        self.imageUrl = imageUrl;
        $('.output').prepend('<canvas></canvas>')
        var canvas = document.querySelector("canvas");
        var context = canvas.getContext("2d");
        context.clearRect(0, 0, canvas.width, canvas.height);
        var infoPoints = document.querySelector(".points-info");
        var picker = document.querySelector("input")
        var zoomWindow = document.querySelector(".zoom")
        var clickPoints = [];

        canvas.addEventListener("click", evt => {
            console.log(evt)
            clickPoints.push([evt.offsetX + 1, evt.offsetY + 1])
            // $('.points').data(count,id);
            self.fourPoints.push([evt.offsetX + 1, evt.offsetY + 1])
            console.log(evt.offsetX + 1 + " - " + evt.offsetY + 1)
            drawDot(evt.offsetX + 1, evt.offsetY + 1)
            infoPoints.textContent = clickPoints.join(" : ")
            if (clickPoints.length >= 4) {
                drawPoly(clickPoints);

            }
        })

        picker.addEventListener("change", evt => {
            newImage(evt.target.value)
        })

        // draw polygon from a list of 4 points
        const drawPoly = points => {

            const object = points;
            console.log(self)
            context.fillStyle = "#FF0000";
            context.lineWidth = 2
            context.clearRect(0, 0, canvas.width, canvas.height)
            var split = points.splice(0, 4)


            context.beginPath()
            context.moveTo(split[0][0], split[0][1])
            for (i of split.reverse()) context.lineTo(i[0], i[1])
            context.strokeStyle = '#ff0000';
            context.stroke()
        }
        console.log(self)

        // draw a dot.
        const drawDot = (x, y) => {
            context.fillStyle = "#FF0000";
            context.beginPath()
            context.arc(x, y, 3, 0, 2 * Math.PI);
            context.fill()
        }

        // resize the canvas for the image
        var biggest = 800;
        var axis
        const resize = (x, y) => {
            // biggest = x > y ? x : y;

            // so that the biggest axis is always {biggest} px
            x > y ? console.log(x + '/' + biggest) : console.log(y + '/' + biggest)
            console.log()
            var ratio = x > y ? x / biggest : y / biggest
            console.log('ration is: ' + ratio)
            self.ratio = ratio
            axis = [x / ratio, y / ratio]
            canvas.height = axis[0]
            canvas.width = axis[1]
            console.log(Math.ceil(axis[1]) + ":" + Math.ceil(axis[0]))
            $("#MaskImagesModal .output").css('width', axis[1])
            $("#MaskImagesModal .output").css('height', axis[0])
        }

        // load a new image
        var rawImg = new Image()
        const newImage = src => {
            console.log('in loading')

            console.log('src: ' + src)
            rawImg.src = src
            const randomId = new Date().getTime();

            rawImg.onload = () => {
                canvas.style.backgroundImage = "url(" + src + `?random=${randomId}` + ")"
                // zoomWindow.style.backgroundImage = "url(" + src + ")"
                // console.log(canvas.style.backgroundImage, zoomWindow.style.backgroundImage)
                resize(rawImg.height, rawImg.width)

            };
        }


        newImage(imageUrl);


        // move the preview to the mouse
        // canvas.addEventListener("mousemove", (evt) => {drawZoom≥'/
        //     drawZoom(evt.clientX, evt.clientY)
        // })

        // const drawZoom = (x, y) => {
        //     zoomWindow.style.backgroundPosition = x + "% " + y + "%"
        //     zoomWindow.position.top = x + "px"
        // }
    },
    // async deleteFromDb(e) {
    //     console.log('deleting..');
    //     let self = e.data.self;
    //     self.imgId = $(this).data('img-id');
    //     // if(!self.imgId){
    //     //     self.remove($(this).parents(`.page-file-item`));
    //     //     console.log($('#noAjaxFileUploader')[0].files)
    //     //     return;
    //     // }
    //
    // }

}
imageMask.init();

$(document).ready(function () {
    $(".page-file-item.transfer").draggable({
        helper: "clone"
    });
    $(".page-file-item.transfer").droppable({
        drop: swapDivs
    });

    function swapDivs(event, ui) {
        var target = $(event.target);
        var source = ui.draggable;
        var data = {};
        var form = new FormData();
        source.insertBefore(target);
        data[source.data('id')] = source.index();
        form.append(source.data('id'), source.index());
        let divs = $('div#images div.transfer');
        let i = 0;

        divs.each(function (i) {
            if (!data.hasOwnProperty($(this).data('id'))) {
                data[$(this).data('id')] = $(this).index();
                form.append($(this).data('id'), $(this).index());
            }

        });
        console.log(data)
        delete data[undefined];


        const result = $.ajax({
            url: `${APP_URL}/api/v1/applications/attachment`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            data: form,
            contentType: false,
            processData: false
        });
        console.log(result)
    }
});
