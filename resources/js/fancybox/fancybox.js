/*let uploaded = document.querySelector(`.uploaded`);
if(uploaded) {
    let mutationObserver = new MutationObserver(function (mutations) {

        mutations.forEach(function (mutation) {
            $(mutation.addedNodes[0]).on('click', function () {
                $.fancybox.open({
                    src: $(this).data('src')
                });
                // $(mutation.target).append();
            });
        });

    });
    mutationObserver.observe(uploaded, {
        attributes: true,
        characterData: true,
        childList: true,
        subtree: true,
        attributeOldValue: true,
        characterDataOldValue: true
    });

    $(`.uploaded-image`, uploaded).on('click', function () {
        $.fancybox.open({
            src: $(this).data('src')
        });
    });
}*/

$(`body`).on('click', `.newcart__imgwrap a`, function(e){
    e.preventDefault();
    let parentSlider = $(this).parents(`.car-slide`);
    let parentSlide = $(this).parents(`.slick-slide`);
    let gallery = $(`.slick-slide:not(.slick-cloned) a`, parentSlider);
    let totalSlides = parentSlider.slick("getSlick").slideCount,
        dataIndex = parentSlide.data('slick-index');

    $.fancybox.open(gallery, {
        beforeClose : function( instance, current, e ) {
            parentSlider.slick("slickGoTo", current.index)
        }
    }, dataIndex);

});

$(`body`).on('click', `.page-file__zoom`, function(e){
    e.preventDefault();
    let gallery = $(`#images .page-file-item`);
    let dataIndex = $(this).parents(`.page-file-item`).index() -1;

    console.log(dataIndex)
    $.fancybox.open(gallery, null, dataIndex);

});
