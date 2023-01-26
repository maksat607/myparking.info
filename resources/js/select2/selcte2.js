$('select').select2({
    // theme: 'bootstrap4',
    minimumResultsForSearch: Infinity,
    dropdownCssClass: 'page-select-dd',
    width: 'resolve',
    // placeholder: '',
});

$('select.multiple').select2();

$('select.user-select').select2();

$(function() {
    $("body").delegate(".user-select", "click", function(){
        $(this).select2();
    });
});
$(function() {
    $("body").delegate(".parking-select", "click", function(){
        $(this).select2();
    });
});
$(function() {
    $("body").delegate(".partner-select", "click", function(){
        $(this).select2();
    });
});



let select2Ajax = $('.get-parking-ajax').select2({
    theme: 'bootstrap4',
    placeholder: 'Поиск по ИНН или Названию стоянки',
    minimumInputLength: 3,
    width: '100%',
    ajax: {
        url: '/partner/parkings/search',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            return {
                results:  $.map(data, function (item) {
                    return {
                        text: item.title + ' - ИНН: ' + item.inn,
                        id: item.id
                    }
                })
            };
        },
        cache: true
    }
});

select2Ajax.on('select2:open', () => {
    document.querySelector('.select2-search__field').focus();
});
$(document).ready(function() {
    $('.page-select').select2();
});
$(document).ready(function() {
    $('.select-multiple').select2();
});
