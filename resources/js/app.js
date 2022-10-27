require('./bootstrap');

$(function() {
    require('./image-uploader/previewUp');
    require('./search/search');
    require('./image-uploader/imadeDelete');
    require('./fancybox/fancybox');
    require('./select2/selcte2');
    require('./flatpickr/flatpickr');
    require('./car/carSelect');
    require('./car/carSelectAjax');
    require('./car/carDuplicateCheck');
    require('./duplicate-field-value/duplicate-field-value');
    require('./inputmask/inputmask');
    require('./application/issueAcception');
    require('./application/modalAjaxContent');
    require('./application-filter/applicationFilter');
    require('./application/favorite');
    require('./application/stepTabAndValidete');
    require('./application/ptsType');
    require('./card/editCard');
    require('./partner/search');
    require('./partner/filter');
    require('./partner/partnerModalAjaxContent');
    require('./user/messageModalAjaxContent');

    require('./common');
    require('./custom.v2');
    require('./custom');


});

window.Echo.channel('chat')
    .listen('.message',(e) => {
        console.log(e);
        console.log('sending');
    });

