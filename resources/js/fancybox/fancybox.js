let uploaded = document.querySelector(`.uploaded`);
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
}

$(`.newcart__img a`).on('click', function(e){
    e.preventDefault();
    let parentSlider = $(this).parents(`.newcart__img`);
    let parentSlide = $(this).parents(`.slick-slide`);
    let gallery = $(`.slick-slide:not(.slick-cloned) a`, parentSlider);
    let totalSlides = parentSlider.slick("getSlick").slideCount,
        dataIndex = parentSlide.data('slick-index');

    $.fancybox.open(gallery, {
        beforeClose : function( instance, current, e ) {
            parentSlider.slick("slickGoTo", current.index)
            console.log(current);
        }
    }, dataIndex);

});
