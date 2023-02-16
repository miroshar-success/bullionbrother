;(function($){
"use strict";
    
    var WidgetThumbnailsSliderHandler = function ($scope, $) {

        var slider_elem = $scope.find('.wl-thumbnails-slider').eq(0);

        if ( slider_elem.length > 0 ) {

            slider_elem[0].style.display='block';

            var settings = slider_elem.data('settings');
            var arrows = settings['arrows'];
            var dots = settings['dots'];
            var autoplay = settings['autoplay'];
            var rtl = settings['rtl'];
            var autoplay_speed = parseInt(settings['autoplay_speed']) || 3000;
            var animation_speed = parseInt(settings['animation_speed']) || 300;
            var fade = settings['fade'];
            var pause_on_hover = settings['pause_on_hover'];
            var display_columns = parseInt(settings['product_items']) || 4;
            var scroll_columns = parseInt(settings['scroll_columns']) || 4;
            var tablet_width = parseInt(settings['tablet_width']) || 800;
            var tablet_display_columns = parseInt(settings['tablet_display_columns']) || 2;
            var tablet_scroll_columns = parseInt(settings['tablet_scroll_columns']) || 2;
            var mobile_width = parseInt(settings['mobile_width']) || 480;
            var mobile_display_columns = parseInt(settings['mobile_display_columns']) || 1;
            var mobile_scroll_columns = parseInt(settings['mobile_scroll_columns']) || 1;

            slider_elem.not('.slick-initialized').slick({
                arrows: arrows,
                prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
                dots: dots,
                infinite: true,
                autoplay: autoplay,
                autoplaySpeed: autoplay_speed,
                speed: animation_speed,
                fade: false,
                pauseOnHover: pause_on_hover,
                slidesToShow: display_columns,
                slidesToScroll: scroll_columns,
                rtl: rtl,
                responsive: [
                    {
                        breakpoint: tablet_width,
                        settings: {
                            slidesToShow: tablet_display_columns,
                            slidesToScroll: tablet_scroll_columns
                        }
                    },
                    {
                        breakpoint: mobile_width,
                        settings: {
                            slidesToShow: mobile_display_columns,
                            slidesToScroll: mobile_scroll_columns
                        }
                    }
                ]
            });
        };
    }

    // Product thumbnais varition with tab
    function woolentor_thumbnails_tab_with_variation( $thumbnais ){
        $thumbnais.on('click', 'li', function(e){
            e.preventDefault();
            var $this = $(this),
                $image = $this.data('wlimage');
            $('.elementor-widget-wl-product-thumbnails-image').find( '.woocommerce-product-gallery__image .wp-post-image' ).attr( "src", $image );
            $('.elementor-widget-wl-product-thumbnails-image').find( '.woocommerce-product-gallery__image .wp-post-image' ).attr( "srcset", $image );
        });
    }
    var WidgetThumbnaisImagesHandlerPro = function woolentorthumbnailspro(){
        woolentor_thumbnails_tab_with_variation( $(".woolentor-thumbanis-image") );
    }


    //Tool-tips
    function woolentor_tool_tips_pro(element, content) {
        if (content == 'html') {
            var tipText = element.html();
        } else {
            var tipText = element.attr('title');
        }
        element.on('mouseover', function() {
            if ($('.woolentor-tip').length == 0) {
                element.before('<span class="woolentor-tip">' + tipText + '</span>');
                $('.woolentor-tip').css('transition', 'all 0.5s ease 0s');
                $('.woolentor-tip').css('margin-left', 0);
            }
        });
        element.on('mouseleave', function() {
            $('.woolentor-tip').remove();
        });
    }

    // Custom Tab
    function woolentor_tabs_pro( $tabmenus, $tabpane ){
        $tabmenus.on('click', 'a', function(e){
            e.preventDefault();
            var $this = $(this),
                $target = $this.attr('href');
            $this.addClass('htactive').parent().siblings().children('a').removeClass('htactive');
            $( $tabpane + $target ).addClass('htactive').siblings().removeClass('htactive');

            // slick refresh
            var $id = $this.attr('href');
            $($id).find('.slick-slider').slick('refresh');

        });
    }

    //Tooltip
    var WidgetWoolentorTooltipHandlerpro = function woolentor_tool_tip_pro(){
        $('a.woolentor-compare').each(function() {
            woolentor_tool_tips_pro($(this), 'title');
        });
        $('.woolentor-cart a.add_to_cart_button,.woolentor-cart a.added_to_cart,.woolentor-cart a.button').each(function() {
            woolentor_tool_tips_pro($(this), 'html');
        });
    }

    // image handler
    var WidgetThumbnaisShopHandlerPro = function thumbnailsimagescontrollerpro(){
        woolentor_tabs_pro( $(".ht-product-cus-tab-links"), '.ht-product-cus-tab-pane' );
        woolentor_tabs_pro( $(".ht-tab-menus"), '.ht-tab-pane' );

        // Countdown
        var finalTime, daysTime, hours, minutes, second;
        $('.ht-product-countdown').each(function() {
            var $this = $(this), finalDate = $(this).data('countdown');
            var customlavel = $(this).data('customlavel');
            $this.countdown(finalDate, function(event) {
                $this.html(event.strftime('<div class="cd-single"><div class="cd-single-inner"><h3>%D</h3><p>'+customlavel.daytxt+'</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%H</h3><p>'+customlavel.hourtxt+'</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%M</h3><p>'+customlavel.minutestxt+'</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%S</h3><p>'+customlavel.secondstxt+'</p></div></div>'));
            });
        });

    }

     /**
     * [WooLentorIsotope]
     * @param {[string]} $scope
     */
    var WooLentorIsotope = function ( $scope, $ ) {

        var isotopeGrid = $scope.find('.woolentor-filter-grid').eq(0);
        
        if ( isotopeGrid.length > 0 ){
            
            var $grid = isotopeGrid,
                $gridId = $grid.attr('id'),
                $gridActive = $grid.data('active-item'),
                $isotopeFilter = $('[data-target="#' + $gridId + '"]').parent('.woolentor-filter-menu');
                $isotopeFilter.find('li[data-filter="' + $gridActive + '"]').addClass('active').siblings().removeClass('active');
            
            $isotopeFilter.on('click', '[data-filter]', function() {
                var $this = $(this),
                    $filterValue = $this.attr('data-filter'),
                    $targetIsotop = $this.parent().data('target');
                $this.addClass('active').siblings().removeClass('active');

                $( $targetIsotop ).isotope({
                    filter: $filterValue,
                    itemSelector: '.woolentor_filter_grid__item',
                    masonry: {
                        columnWidth: '.woolentor_filter_grid__item__sizer'
                    },
                    transitionDuration: '0.6s'
                });

            });
            
            $grid.imagesLoaded(function() {
                $grid.isotope({
                    itemSelector: '.woolentor_filter_grid__item',
                    masonry: {
                        columnWidth: '.woolentor_filter_grid__item__sizer'
                    },
                    transitionDuration: '0.6s'
                });
            });

        }
    }

    /**
     * Select2 Active under Elementor
     * @param {string} $scope
     */
    var WooLentorSelect2 = function( $scope, $ ){
        $('select.woolentor-enhanced-select').selectWoo({
            allowClear:!0,
            placeholder:$(this).data("placeholder")
        }).addClass("enhanced");
    }

    /**
     * Handler for order reivew addon
     * @param {[string]} $scope
     */
     var OrderReviewHandler = function ( $scope, $ ) {
        const quantity_controls_activate = function() {
            // Add plugs and minus icon
            var $quantity_control = $scope.find('.woolentor-product-quantity .quantity');
            $quantity_control.each(function(){
                var $quantity_buttons = $('<button class="woolentor-quantity-btn woolentor-quantity-increase">+</button><button class="woolentor-quantity-btn woolentor-quantity-decrease">-</button>');
                $(this).addClass('hello').append($quantity_buttons);
            });

            $quantity_control.on('click', '.woolentor-quantity-btn', function(e){
                e.preventDefault();
            
                const $this     = $(this),
                    $input      = $this.parent().find('input[type="number"]')[0];

                if( $this.hasClass('woolentor-quantity-increase') ) {
                    $input.value = Number($input.value) + 1;
                    
                    $($input).trigger('change');
                } else if($this.hasClass('woolentor-quantity-decrease') && Number($input.value) > 1) {
                    $input.value = Number($input.value) - 1
                    
                    $($input).trigger('change');
                }
            });
        }
        
        quantity_controls_activate();

        $( document.body ).on('wc_fragments_refreshed', function(e) {
            // Add plugs and minus icon
            if( $scope.find('.woolentor-product-quantity .woolentor-quantity-increase').length ){
                return;
            }

            quantity_controls_activate();
        });

        // Remove the cart item after clicked on the crossed button
        $('body').on('removed_from_cart', function(e, fragments, cart_hash, $thisbutton){
            if( $thisbutton != undefined && $thisbutton.hasClass('woolentor-product-remove') ){
                $thisbutton.closest('.woolentor-product').remove();

                if($('body').find('.woolentor-product').length){
                    $( 'body' ).trigger( 'update_checkout' );
                    $( 'body' ).trigger( 'updated_wc_div' );
                } else{
                    window.location = wc_add_to_cart_params.cart_url;
                }
            }
        });

        // Update cart item quantity via ajax while click on the plus/minus button
        $( ".woolentor-review-order-1" ).on( "change", "input.qty", function( e ) {
            $(this).closest('.woolentor-product').addClass( 'processing' ).block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });

            var data = {
                action: 'update_cart_item_quantity',
                security: wc_checkout_params.update_order_review_nonce,
                qty: this.value,
                cart_item_key: $(this).closest('.woocommerce-mini-cart-item').data('cart_item_key')
            };
            
            $.post( woocommerce_params.ajax_url, data, function( response ){
                $( 'body' ).trigger( 'update_checkout' );
                $( 'body' ).trigger( 'updated_wc_div' );
                $('.woocommerce-mini-cart-item.woolentor-product').removeClass('processing').unblock();
            });
        });
    }

    var CheckoutFormHandler = function( $scope, $ ) {
        // Initialize select 2
        $('select.woolentor-enhanced-select').selectWoo({
            allowClear:!0,
            placeholder:$(this).data("placeholder")
        }).addClass("enhanced");

        var $input_field = '.woolentor-fields-1 .woocommerce-input-wrapper input,.woolentor-fields-1 .woocommerce-form-login .form-row-first input,.woolentor-fields-1 .woocommerce-form-login .form-row-last input';

        $scope.find($input_field).focus(function(){
            $(this).closest('.form-row').addClass("focused");
        }).blur(function(){
            $(this).closest('.form-row').removeClass("focused");
        });

        $scope.find('.form-row').each(function (){
            if( $(this).find(':input').attr('type') == 'checkbox' ){
                return;
            }

            if( $(this).find(':input').val() && $(this).find(':input').val().length > 0 ){
                $(this).addClass("has-value");
            }
        });

        $scope.find($input_field).on('keyup', function(e){
            if( this.value ){
                $(this).closest('.form-row').addClass("has-value");
            } else {
                $(this).closest('.form-row').removeClass("has-value");
            }
        });
    }

    /**
     * Handler for payment method
     * @param {[string]} $scope
     */
     var PaymentMethodHandler = function ( $scope, $ ) {
        $('body').on('change', '.woolentor-payment-method-1 input#terms,.woolentor-payment-method-1 input#terms', function(){
            if( this.checked ){
                $(this).closest('.form-row').addClass('woolentor-cb-checked');
            } else {
                $(this).closest('.form-row').removeClass('woolentor-cb-checked');
            }
        })
     }

     /* Cart page handlers
     ======================================================= */

    var CartTableHandler = function ( $scope, $ ) {
        // Product Details Slide Toggle
        $('body').on("click", '.woolentor-cart-product-details-toggle', function (e) {
            e.preventDefault();
            
            const $target = $(this).data('target');

            if($(`[data-id="${$target}"]`).is(':hidden')) {
                $(`[data-id="${$target}"]`).slideDown();
            } else {
                $(`[data-id="${$target}"]`).slideUp();
            }
        });
    }
    
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-product-thumbnails-image.default', WidgetThumbnaisImagesHandlerPro);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-product-thumbnails-image.default', WidgetThumbnailsSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-custom-product-archive.default', WidgetThumbnaisShopHandlerPro);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-product-sale-schedule.default', WidgetThumbnaisShopHandlerPro);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-custom-product-archive.default', WidgetWoolentorTooltipHandlerpro);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-filtarable-grid-product.default', WooLentorIsotope );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-checkout-billing-form.default', CheckoutFormHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-checkout-shipping-form.default', CheckoutFormHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-checkout-additional-form.default', WooLentorSelect2 );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-checkout-login-form.default', CheckoutFormHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-checkout-order-review.default', OrderReviewHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-checkout-payment-method.default', PaymentMethodHandler );

        // Cart page
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-cart-table-list.default', CartTableHandler );
    });
})(jQuery);