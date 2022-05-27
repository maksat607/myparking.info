

$(document).on( 'click', '.editAcceptedBy', function () {

    $('.clicked').addClass('d-none');
    $('.user-select').removeClass('d-none')
    console.log('mkmkmkmkmk')

    $('.user-select').on('change', function() {
        var appid = $('#appId').val();
        var userid = this.value;

        var fd = new FormData();
        fd.append('appid', appid);
        fd.append('userid', userid);
        let r = uploadImage(fd);
        console.log(r)

        $('.clicked').addClass('d-none');
        $('.user-select').removeClass('d-none')


    });


});
function  uploadImage(form){
    const result =  $.ajax({
        url: `/application/acceptedby`,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'post',
        data: form,
        contentType: false,
        processData: false
    });

    return result;
}
