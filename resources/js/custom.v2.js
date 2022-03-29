function slickSlide() {
    if ($(".car-slide").length) {
        let $slickElement = $(".car-slide");
        $slickElement.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
            let $status = $(this).siblings('.pagingInfo');
            let i = (currentSlide ? currentSlide : 0) + 1;
            $status.html(`<span>${i}</span><span class="delimiter">/</span>${slick.slideCount}`);
        });

        $(".car-slide").slick({
            dots: false,
            infinite: false,
            slidesToShow: 1,
            slidesToScroll: 1,
        });
    };
}
slickSlide();

$('.car-close-dd').on('click', function () {
    $(this).toggleClass('active');
    $(this).parent().toggleClass('active');
});

$(".table-dd-show").click(function () {
    let tabid = $(this).attr('id');
    let item = $(`.table-dd[data-id="${tabid}"]`);

    if ($(this).hasClass("active")) {
        $(this).removeClass("active");
        item.slideUp();
        item.removeClass('active');
    } else {
        $(this).addClass("active");
        item.slideDown();
        item.addClass('active');
    }
});

$('body').on('click', '.password-control', function () {

    let input = $(this).siblings('input');
    if (input.attr('type') == 'password') {
        $(this).addClass('view');
        input.attr('type', 'text');
    } else {
        $(this).removeClass('view');
        input.attr('type', 'password');
    }
    return false;
});

$('#btn-systemic .edit-systemic').click(function (e) {
    e.stopPropagation();
    e.preventDefault();
    $('#systemic .pseudo-field').addClass('active');
    $('#systemic .pseudo-field div').attr('contenteditable', 'true');
    $(this).parent().addClass('active');
});
$('#btn-systemic .save-systemic').click(function (e) {
    e.stopPropagation();
    e.preventDefault();
    $('#systemic .pseudo-field').removeClass('active');
    $('#systemic .pseudo-field div').attr('contenteditable', 'false');
    $(this).parent().removeClass('active');
});

$('.tab-checkbox a').click(function () {
    $('.tab-checkbox a').removeClass('active');
})

$('#tab-checkbox input[type="checkbox"]').on('change', function (){
    let check = $(this).prop('checked');
    if (check) {
        $(this).parent().find('a').removeClass('active');
        let id = $(this).parent().find('a').attr('href');
        $(id).removeClass('show active');
        $('#tab-checkbox input[type="checkbox"]:not(:checked)').eq(0).parent().find('a').trigger('click');
    } else {
        $('.tab-checkbox a').removeClass('active');
        $(this).parent().find('a').trigger('click');
    }
    unCheck();
});

function unCheck() {
    let status = [];

    $('#tab-checkbox input').each(function () {
        status.push($(this).prop('checked'));
    })

    if (!status.includes(false)) {
        $('#tab-info').addClass('show active');
    }

}

unCheck();

$(`.overlay`).on('click', function(){
    $('.modal-block').removeClass('active');
    $(this).removeClass('active');
});

$(`body`).on('click', `.delete`, confirmUp);
$(`body`).on('click', `.deny`, confirmUp);
$(`body`).on('click', `#confirmPopup button, #confirmPopup .confirm-popup__close`, function(event){
    event.preventDefault();
    let confirmId = $(this).data('confirm-id');
    let confirmType = $(this).data('confirm-type');
    let confirmUrl = $(this).data('confirm-url');

    if(confirmId && confirmType == "delete") {
        $(`#${confirmId}`).submit();
    } else if(confirmId && confirmUrl != '' && confirmType == "deny") {
        window.location.href = confirmUrl;
    } else {
        closeConfirmUp(this)
    }
});

function closeConfirmUp(self) {
    $(self).parents(`#confirmPopup`)
        .addClass('hide')
        .delay(500).queue(function(){
        $(this).remove().dequeue();
    });
}


function confirmUp(event) {
    event.preventDefault();
    let confirmId = $(this).data('confirm-id'),
        confirmType = $(this).data('confirm-type'),
        confirmUrl = $(this).data('confirm-url'),
        message = $(this).data('confirm-message') ? $(this).data('confirm-message') : 'Удалить выбранный элемент?';


    let popupHtml = `<div id="confirmPopup" class="confirm-popup hide">`;
            popupHtml += `<div class="confirm-popup__main">`;
                popupHtml += `<div class="confirm-popup__close"></div>`;
                popupHtml += `<div class="confirm-popup__top">`;
                    popupHtml += `<div class="confirm-popup__body">`;
                        popupHtml += message;
                    popupHtml += `</div>`;
                popupHtml += `</div>`;
                popupHtml += `<div class="confirm-popup__bottom">`;
                    popupHtml += `<button class="btn btn-success" type="button" \
                                    data-confirm-type="${confirmType}" \
                                    data-confirm-url="${confirmUrl}" \
                                    data-confirm-id="${confirmId}">Да</button>`;
                    popupHtml += `<button class="btn btn-danger" type="button">Нет</button>`;
                popupHtml += `</div>`;
            popupHtml += `</div>`;
        popupHtml += `</div>`;

    $(`#confirmPopup`, `body`).remove()
    $(`body`).append(popupHtml);
    $(`#confirmPopup`, `body`).delay(500).queue(function(){
        $(this).removeClass("hide").dequeue();
    });
}

$('.chech-dd').on('change', function() {
    if ($(this).is(":checked")) {
        $(this).parent().siblings('.chech-dd-list').slideDown();
        $(this).parent().siblings('.chech-dd-list').addClass('active');
    } else {
        $(this).parent().siblings('.chech-dd-list').slideUp();
        $(this).parent().siblings('.chech-dd-list').removeClass('active');
    }
})
