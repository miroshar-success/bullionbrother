;(function($){
"use strict";

    var $cartFormArea = $('.single-product .cart');
    var $stickyCartBtnArea = $('.woolentor-add-to-cart-sticky');

    if ( $stickyCartBtnArea.length <= 0 || $cartFormArea.length <= 0 ) return;

    var totalOffset = $cartFormArea.offset().top + $cartFormArea.outerHeight();

    var addToCartStickyToggler = function () {
        var windowScroll = $(window).scrollTop();
        var windowHeight = $(window).height();
        var documentHeight = $(document).height();

        if (totalOffset < windowScroll && windowScroll + windowHeight != documentHeight) {
            $stickyCartBtnArea.addClass('woolentor-sticky-shown');
        } else if (windowScroll + windowHeight == documentHeight || totalOffset > windowScroll) {
            $stickyCartBtnArea.removeClass('woolentor-sticky-shown');
        }
    };
    addToCartStickyToggler();
    $(window).scroll(addToCartStickyToggler);

    // If Variations Product
    $('.woolentor-sticky-add-to-cart').on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $('.single-product form.cart').offset().top - 30
        }, 500 );
    });

})(jQuery);