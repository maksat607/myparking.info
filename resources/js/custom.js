// if ($(".car-slide").length) {
//     let $slickElement = $(".car-slide");
//     $slickElement.on('init reInit afterChange', function(event, slick, currentSlide, nextSlide) {
//         let $status = $(this).siblings('.pagingInfo');
//         let i = (currentSlide ? currentSlide : 0) + 1;
//         $status.html(`<span>${i}</span><span class="delimiter">/</span>${slick.slideCount}`);
//     });
//
//     $(".car-slide").slick({
//         dots: false,
//         infinite: false,
//         slidesToShow: 1,
//         slidesToScroll: 1,
//     });
// };
//
// $('.car-close-dd').on('click', function() {
//     $(this).toggleClass('active');
//     $(this).parent().toggleClass('active');
// });
//
// $(".table-dd-show").click(function() {
//     let tabid = $(this).attr('id');
//     let item = $(`.table-dd[data-id="${tabid}"]`);
//
//     if ($(this).hasClass("active")) {
//         $(this).removeClass("active");
//         item.slideUp();
//         item.removeClass('active');
//     } else {
//         $(this).addClass("active");
//         item.slideDown();
//         item.addClass('active');
//     }
// });
//
// $('body').on('click', '.password-control', function() {
//
//     let input = $(this).siblings('input');
//     if (input.attr('type') == 'password') {
//         $(this).addClass('view');
//         input.attr('type', 'text');
//     } else {
//         $(this).removeClass('view');
//         input.attr('type', 'password');
//     }
//     return false;
// });
//
// $('#btn-systemic .edit-systemic').click(function(e) {
//     e.stopPropagation();
//     e.preventDefault();
//     $('#systemic .pseudo-field').addClass('active');
//     $('#systemic .pseudo-field div').attr('contenteditable', 'true');
//     $(this).parent().addClass('active');
// });
// $('#btn-systemic .save-systemic').click(function(e) {
//     e.stopPropagation();
//     e.preventDefault();
//     $('#systemic .pseudo-field').removeClass('active');
//     $('#systemic .pseudo-field div').attr('contenteditable', 'false');
//     $(this).parent().removeClass('active');
// });
//
// $('.tab-checkbox a').click(function() {
//     $('.tab-checkbox a').removeClass('active');
// })
//
// $('#tab-checkbox input[type="checkbox"]').on('change', function() {
//     let check = $(this).prop('checked');
//     if (check) {
//         $(this).parent().find('a').removeClass('active');
//         let id = $(this).parent().find('a').attr('href');
//         $(id).removeClass('show active');
//         $('#tab-checkbox input[type="checkbox"]:not(:checked)').eq(0).parent().find('a').trigger('click');
//     } else {
//         $('.tab-checkbox a').removeClass('active');
//         $(this).parent().find('a').trigger('click');
//     }
//     unCheck();
// });
//
// function unCheck() {
//     let status = [];
//
//     $('#tab-checkbox input').each(function() {
//         status.push($(this).prop('checked'));
//     })
//
//     if (!status.includes(false)) {
//         $('#tab-info').addClass('show active');
//     }
//
// }
//
// unCheck();
//
// $('.chech-dd').on('change', function() {
//     if ($(this).is(":checked")) {
//         $(this).parent().siblings('.chech-dd-list').slideDown();
//         $(this).parent().siblings('.chech-dd-list').addClass('active');
//     } else {
//         $(this).parent().siblings('.chech-dd-list').slideUp();
//         $(this).parent().siblings('.chech-dd-list').removeClass('active');
//     }
// });
//
// $('.inner-page-search').on('keyup', function() {
//     if ($(this).val() != '') {
//         $(this).parent().next('.d-dowen-body').addClass('active');
//     } else {
//         $(this).parent().next('.d-dowen-body').removeClass('active');
//     }
// });
//
// $('.d-dowen-select li').on('click', function() {
//     $(this).parent().find('li').removeClass('active');
//     $(this).addClass('active');
// });
//
//
$(document).on('click', '.addUserOfPartner',function() {
    console.log('========')
    $('.modal-add-user').addClass('active');
    $('.modal-default-head').addClass('hide');
});
$(document).on('click','.modal-add-user .close-add', function() {
    $('.modal-add-user').removeClass('active');
    $('.modal-default-head').removeClass('hide');
});
// $('body').on('change', '.table_sort .switch-radio-wrap input', function() {
//     if ($(this).is(":checked")) {
//         $(this).parent().find('.check-st').remove();
//         $(this).parent().prepend('<span class="d-none check-st">1<span>');
//     } else {
//         $(this).parent().find('.check-st').remove();
//         $(this).parent().prepend('<span class="d-none check-st">2<span>');
//     }
// })
// $('.table_sort .switch-radio-wrap input').each(function(index) {
//     if ($(this).is(":checked")) {
//         $(this).parent().find('.check-st').remove();
//         $(this).parent().prepend('<span class="d-none check-st">1<span>');
//     } else {
//         $(this).parent().find('.check-st').remove();
//         $(this).parent().prepend('<span class="d-none check-st">2<span>');
//     }
// });
// //
// // document.addEventListener('DOMContentLoaded', () => {
// //
// //     const getSort = ({ target }) => {
// //         const order = (target.dataset.order = -(target.dataset.order || -1));
// //         const index = [...target.parentNode.cells].indexOf(target);
// //         const collator = new Intl.Collator(['en', 'ru'], { numeric: true });
// //         const comparator = (index, order) => (a, b) => order * collator.compare(
// //             a.children[index].innerHTML,
// //             b.children[index].innerHTML
// //         );
// //
// //         for (const tBody of target.closest('table').tBodies)
// //             tBody.append(...[...tBody.rows].sort(comparator(index, order)));
// //
// //         for (const cell of target.parentNode.cells) {
// //             cell.classList.remove('sorted', cell === target);
// //             cell.classList.toggle('sorted', cell === target);
// //         }
// //     };
// //
// //     document.querySelectorAll('.table_sort thead').forEach(tableTH => tableTH.addEventListener('click', () => getSort(event)));
// //
// // });
