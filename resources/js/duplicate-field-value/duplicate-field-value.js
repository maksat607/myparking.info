function duplicateFieldValue(selector = null) {
    if(!selector) return;
    $(selector).on('change', function(){
        let v = $(selector).not(this).val($(this).val());
    });
}

console.log('ngng  '+$('.checkbox-unknown.clicense').val())
// if($('.checkbox-unknown.clicense').is(":checked")){
//
//
//     $('.license_plate').addClass('disabled-border');
//     $('.license_plate').addClass('disabled-text');
//
// }else if(!$('.checkbox-unknown.clicense').is(":checked")){
//     console.log(88888888)
//     $('.license_plate').removeClass('disabled-border');
//     $('.license_plate').removeClass('disabled-text');
//     duplicateFieldValue(`.license_plate`);
// }
//
// if($(`.checkbox-unknown.cvin`).is(":checked")){
//     $('.vin').addClass('disabled-border');
//     $('.vin').addClass('disabled-text');
//
// }else if(!$(`.checkbox-unknown.cvin`).is(":checked")){
//     $('.vin').removeClass('disabled-border');
//     $('.vin').removeClass('disabled-text');
//     duplicateFieldValue(`.vin`);
// }
