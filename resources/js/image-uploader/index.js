let attachmentData = null;
if(typeof carAttachmentDataApplication != 'undefined' && carAttachmentDataApplication != null) {
    attachmentData = carAttachmentDataApplication;
}
else if(typeof carAttachmentDataViewRequest != 'undefined' && carAttachmentDataViewRequest != null){
    attachmentData = carAttachmentDataViewRequest;
}

$('.input-images').imageUploader({
    label: "Загрузите изображение",
    preloaded: (attachmentData) ? attachmentData : [],
});
