$('.input-images').imageUploader({
    label: "Загрузите изображение",
    preloaded: (
        typeof carAttachmentDataApplication != 'undefined'
            && carAttachmentDataApplication != null) ? carAttachmentDataApplication : [],
});
