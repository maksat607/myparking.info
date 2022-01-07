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
