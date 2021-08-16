function duplicateFieldValue(selector = null) {
    if(!selector) return;
    $(selector).on('change', function(){
        let v = $(selector).not(this).val($(this).val());
    });
}

duplicateFieldValue(`.vin`);
duplicateFieldValue(`.license_plate`);
