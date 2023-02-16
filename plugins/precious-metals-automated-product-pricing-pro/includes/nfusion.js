(function ($) {
    $('.wrap .clear-cache[value=clear_cache]').click(function () {
        let currency = $(this).attr('currency');
        if (!currency) {
            currency = 'USD';
        }

        let transients = ['nfs_catalog_products_all_' + currency, 'nfs_catalog_products_all_secondary_' + currency];

        $.ajax({
            type: 'POST',
            url: nfObj.ajaxurl,
            data: {
                'action': 'cleartransient',
                'transients': transients
            }
        })
    });

    $(".single_variation_wrap").on("show_variation", function (event, variation) {
        // remove active class from siblings
        $(".nfs_catalog_plugin_productbid").removeClass("active");
        $(".nfs_catalog_plugin_wrapper").removeClass("active");

        // add active class to selected variable product
        $(".nfs_catalog_plugin_productbid." + variation.sku).addClass("active");
        $(".nfs_catalog_plugin_wrapper." + variation.sku).addClass("active");
    });

})(jQuery);