let uploaded = document.querySelector(`.uploaded`);
if(uploaded) {
    let mutationObserver = new MutationObserver(function (mutations) {

        mutations.forEach(function (mutation) {
            $(mutation.addedNodes[0]).on('click', function () {
                $.fancybox.open({
                    src: $(this).data('src')
                });
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
}
