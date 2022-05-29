$(document).on( 'click', '#btn-systemic .edit-systemic', function (e) {
    console.log('customize')
    e.stopPropagation();
    e.preventDefault();
    $('#systemic .pseudo-field1').addClass('active');
    $('#systemic .pseudo-field1 div').attr('contenteditable', 'true');
    $(this).parent().addClass('active');

    $('.dropdownEditible').addClass('d-none');
    // $('.date-select').addClass('position-absolute');
    $('.custom-select').removeClass('d-none')




    $('.partner-select').trigger('click');
    $('.date-select').trigger('focus');


    $('.user-select').trigger('click');
    $('.parking-select').trigger('click');
    $(".date-select").prop('disabled', false);

});
$(document).on( 'click','#btn-systemic .save-systemic', function (e) {
    e.stopPropagation();
    e.preventDefault();

    let acceptedName = $('.user-select.accepted').find(":selected").text();
    let issuedName = $('.user-select.issued').find(":selected").text();
    let parkingName = $('.parking-select.parking').find(":selected").text();
    let partnerName = $('.partner-select.partner').find(":selected").text();

    let acceptedId = $('.user-select.accepted').find(":selected").val();
    let issuedId = $('.user-select.issued').find(":selected").val();
    let parkingId = $('.parking-select.parking').find(":selected").val();
    let partnerId = $('.partner-select.partner').find(":selected").val();

    let arriving_at_modal = $('#arriving_at_modal').val()
    let issued_at_modal = $('#issued_at_modal').val()

    let  vin = $('div#vinnumber').text();
    let  plate = $('div#licenceplate').text();
    let appid = $('#appId').val();

    var fd = new FormData();
    fd.append('acceptedId', acceptedId);
    fd.append('issuedId', issuedId);
    fd.append('parkingId', parkingId);
    fd.append('partnerId', partnerId);

    fd.append('arriving_at_modal', arriving_at_modal);
    fd.append('issued_at_modal', issued_at_modal);
    fd.append('vin', vin);
    fd.append('plate', plate);
    fd.append('appid', appid);
    let r = changeSystemData(fd);
    console.log(r);

    console.log(vin)
    console.log(plate)

    $('.dropdownEditible').removeClass('d-none');
    // $('.date-select').addClass('position-absolute');
    $('.custom-select').addClass('d-none')
    $('.select2').addClass('d-none')

    $('div.issued').empty();
    $('div.issued').append(issuedName);
    $('div.accepted').empty();
    $('div.accepted').append(acceptedName);
    $('div.parking').empty();
    $('div.parking').append(parkingName);
    $('div.partner').empty();
    $('div.partner').append(partnerName);
    $('div#issued_at_div').empty();
    $('div#issued_at_div').append(issued_at_modal);
    $('div#arriving_at_div').empty();
    $('div#arriving_at_div').append(arriving_at_modal);




    console.log(acceptedName )
    console.log(issuedName )
    console.log(parkingName )
    console.log(partnerName )
    $('#systemic .pseudo-field1').removeClass('active');
    $('#systemic .pseudo-field1 div').attr('contenteditable', 'false');
    $(this).parent().removeClass('active');

    $(".date-select").prop('disabled', true);




});


// $(document).on( 'click', '.editAcceptedBy', function () {
//
//
// });

console.log('arriving_at1')

// $('#arriving_at1').flatpickr({
//     static:true
// });
$(function() {
    $("body").delegate("#arriving_at1", "focusin", function(){
        $(this).flatpickr();
    });
});


$(document).on('change','.status-select', function() {
    var appid = $('#appId').val();
    var statusid = this.value;

    var fd = new FormData();
    fd.append('appid', appid);
    fd.append('statusid', statusid);
    let r = changeStatus(fd);
    console.log(r)

    // $('.clicked').addClass('d-none');
    // $('.user-select').removeClass('d-none')
});
function  changeStatus(form){
    const result =  $.ajax({
        url: `/application/change-status`,
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
function  changeSystemData(form){
    const result =  $.ajax({
        url: `/application/change-system-data`,
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
