;(function($){
"use strict";

   /* 
    * Product Slider 
    */
    var WidgetProductSliderHandler = function ($scope, $) {

        var slider_elem = $scope.find('.product-slider').eq(0);

        if (slider_elem.length > 0) {

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
    };

    /*
    * Custom Tab
    */
    function woolentor_tabs( $tabmenus, $tabpane ){
        $tabmenus.on('click', 'a', function(e){
            e.preventDefault();
            var $this = $(this),
                $target = $this.attr('href');
            $this.addClass('htactive').parent().siblings().children('a').removeClass('htactive');
            $( $tabpane + $target ).addClass('htactive').siblings().removeClass('htactive');

            // slick refresh
            if( $('.slick-slider').length > 0 ){
                var $id = $this.attr('href');
                $( $id ).find('.slick-slider').slick('refresh');
            }

        });
    }

    /* 
    * Universal product 
    */
    function productImageThumbnailsSlider( $slider ){
        $slider.slick({
            dots: true,
            arrows: true,
            prevArrow: '<button class="slick-prev"><i class="sli sli-arrow-left"></i></button>',
            nextArrow: '<button class="slick-next"><i class="sli sli-arrow-right"></i></button>',
        });
    }
    if( $(".ht-product-image-slider").length > 0 ) {
        productImageThumbnailsSlider( $(".ht-product-image-slider") );
    }

    var WidgetThumbnaisImagesHandler = function thumbnailsimagescontroller(){
        woolentor_tabs( $(".ht-product-cus-tab-links"), '.ht-product-cus-tab-pane' );
        woolentor_tabs( $(".ht-tab-menus"), '.ht-tab-pane' );

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

    /*
    * woolentorquickview slider
    */
    function woolentorquickviewMainImageSlider(){
        $('.ht-quick-view-learg-img').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.ht-quick-view-thumbnails'
        });
    }
    function woolentorquickviewThumb(){
        $('.ht-quick-view-thumbnails').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            asNavFor: '.ht-quick-view-learg-img',
            dots: false,
            arrows: true,
            focusOnSelect: true,
            prevArrow: '<button class="woolentor-slick-prev"><i class="sli sli-arrow-left"></i></button>',
            nextArrow: '<button class="woolentor-slick-next"><i class="sli sli-arrow-right"></i></button>',
        });
    }

    /*
    * Tool Tip
    */
    function woolentor_tool_tips(element, content) {
        if ( content == 'html' ) {
            var tipText = element.html();
        } else {
            var tipText = element.attr('title');
        }
        element.on('mouseover', function() {
            if ( $('.woolentor-tip').length == 0 ) {
                element.before('<span class="woolentor-tip">' + tipText + '</span>');
                $('.woolentor-tip').css('transition', 'all 0.5s ease 0s');
                $('.woolentor-tip').css('margin-left', 0);
            }
        });
        element.on('mouseleave', function() {
            $('.woolentor-tip').remove();
        });
    }

    /*
    * Tooltip Render
    */
    var WidgetWoolentorTooltipHandler = function woolentor_tool_tip(){
        $('a.woolentor-compare').each(function() {
            woolentor_tool_tips( $(this), 'title' );
        });
        $('.woolentor-cart a.add_to_cart_button,.woolentor-cart a.added_to_cart,.woolentor-cart a.button').each(function() {
            woolentor_tool_tips( $(this), 'html');
        });
    }

    /* Quick View ajax Add to cart */
    function woolentorquickviewAjaxCart( $selector ){

        $(document).on('click', $selector, function (e) {
            e.preventDefault();

            var $thisbutton = $(this),
                $form           = $thisbutton.closest('form.cart'),
                product_qty     = $form.find('input[name=quantity]').val() || 1,
                product_id      = $form.find('input[name=product_id]').val() || $thisbutton.val(),
                variation_id    = $form.find('input[name=variation_id]').val() || 0;

            /* For Variation product */    
            var item = {},
                variations = $form.find( 'select[name^=attribute]' );
                if ( !variations.length) {
                    variations = $form.find( '[name^=attribute]:checked' );
                }
                if ( !variations.length) {
                    variations = $form.find( 'input[name^=attribute]' );
                }

                variations.each( function() {
                    var $thisitem = $( this ),
                        attributeName = $thisitem.attr( 'name' ),
                        attributevalue = $thisitem.val(),
                        index,
                        attributeTaxName;
                        $thisitem.removeClass( 'error' );
                    if ( attributevalue.length === 0 ) {
                        index = attributeName.lastIndexOf( '_' );
                        attributeTaxName = attributeName.substring( index + 1 );
                        $thisitem.addClass( 'required error' );
                    } else {
                        item[attributeName] = attributevalue;
                    }
                });

            var data = {
                action: 'woolentor_insert_to_cart',
                product_id: product_id,
                product_sku: '',
                quantity: product_qty,
                variation_id: variation_id,
                variations: item,
            };

            $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

            $.ajax({
                type: 'post',
                url: woolentor_addons.woolentorajaxurl,
                data: data,
                beforeSend: function (response) {
                    $thisbutton.removeClass('added').addClass('loading');
                },
                complete: function (response) {
                    $thisbutton.addClass('added').removeClass('loading');
                },
                success: function (response) {
                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    } else {
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                    }
                },
            });

            return false;
        });


    }

    /*
    * Quick view
    */
    $(document).on('click', '.woolentorquickview', function (event) {
        event.preventDefault();

        var $this = $(this);
        var productID = $this.data('quick-id');

        $('.htwl-modal-body').html(''); /*clear content*/
        $('#htwlquick-viewmodal').addClass('woolentorquickview-open wlloading');
        $('#htwlquick-viewmodal .htcloseqv').hide();
        $('.htwl-modal-body').html('<div class="woolentor-loading"><div class="wlds-css"><div style="width:100%;height:100%" class="wlds-ripple"><div></div><div></div></div>');

        var data = {
            id: productID,
            action: "woolentor_quickview",
        };
        $.ajax({
            url: woolentor_addons.woolentorajaxurl,
            data: data,
            method: 'POST',
            success: function (response) {
                setTimeout(function () {
                    $('.htwl-modal-body').html(response);
                    $('#htwlquick-viewmodal .htcloseqv').show();
                    woolentorquickviewMainImageSlider();
                    woolentorquickviewThumb();
                    woolentor_render_variation_quick_view_data( $('.woolentorquickview-open') );
                    woolentorquickviewAjaxCart( ".htwl-modal-content .single_add_to_cart_button:not(.disabled)" );

                    $(document).trigger('woolentor_quick_view_rendered');
                }, 300 );
            },
            complete: function () {
                $('#htwlquick-viewmodal').removeClass('wlloading');
                $('.htwl-modal-dialog').css("background-color","#ffffff");
            },
            error: function () {
                console.log("Quick View Not Loaded");
            },
        });

    });
    $('.htcloseqv').on('click', function(event){
        $('#htwlquick-viewmodal').removeClass('woolentorquickview-open');
        $('body').removeClass('woolentorquickview');
        $('.htwl-modal-dialog').css("background-color","transparent");
    });

    function woolentor_render_variation_quick_view_data( $product ) {
        $product.find('.variations_form').wc_variation_form().find('.variations select:eq(0)').change();
        $product.find('.variations_form').trigger('wc_variation_form');

        var $default_data = {
            src:'',
            srcfull:'',
            srcset:'',
            sizes:'',
            width:'',
            height:'',
        };        
        $product.find( '.single_variation_wrap' ).on( 'show_variation', function ( event, variation ) {

            // Get First image data
            if( $default_data.src.length === 0 ){
                $default_data.src = $('.ht-quick-view-learg-img').find('.wl-quickview-first-image .wp-post-image').attr('src');
                $default_data.srcset = $('.ht-quick-view-learg-img').find('.wl-quickview-first-image .wp-post-image').attr('srcset');
                $default_data.srcfull = $('.ht-quick-view-learg-img').find('.wl-quickview-first-image .wp-post-image').attr('data-src');
            }

            $('.ht-qwick-view-left').find('.ht-quick-view-learg-img').slick('slickGoTo', 0);

            $('.ht-quick-view-learg-img').find('.wl-quickview-first-image .wp-post-image').wc_set_variation_attr('src',variation.image.full_src);
            $('.ht-quick-view-learg-img').find('.wl-quickview-first-image .wp-post-image').wc_set_variation_attr('srcset',variation.image.srcset);
            $('.ht-quick-view-learg-img').find('.wl-quickview-first-image .wp-post-image').wc_set_variation_attr('data-src',variation.image.full_src);
            $('.ht-quick-view-learg-img').find('.wl-quickview-first-image .wp-post-image').wc_set_variation_attr('data-large_image',variation.image.full_src);

            // Reset data
            $('.variations').find('.reset_variations').on('click', function(e){
                $('.ht-quick-view-learg-img').find('.wl-quickview-first-image .wp-post-image').wc_set_variation_attr('src', $default_data.src );
                $('.ht-quick-view-learg-img').find('.wl-quickview-first-image .wp-post-image').wc_set_variation_attr('srcset', $default_data.srcset);
                $('.ht-quick-view-learg-img').find('.wl-quickview-first-image .wp-post-image').wc_set_variation_attr('data-src', $default_data.srcfull );
                $('.ht-quick-view-learg-img').find('.wl-quickview-first-image .wp-post-image').wc_set_variation_attr('data-large_image', $default_data.srcfull );
            });

        });

    }

    /*
    * Product Tab
    */
    var  WidgetProducttabsHandler = woolentor_tabs( $(".ht-tab-menus"),'.ht-tab-pane' );

    /*
    * Single Product Video Gallery tab
    */
    var WidgetProductVideoGallery = function thumbnailsvideogallery(){
        woolentor_tabs( $(".woolentor-product-video-tabs"), '.video-cus-tab-pane' );
    }

    /**
     * WoolentorAccordion
     */
    var WoolentorAccordion = function ( $scope, $ ){
        var accordion_elem = $scope.find('.htwoolentor-faq').eq(0);

        var data_opt = accordion_elem.data('settings');

        if ( accordion_elem.length > 0 ) {
            var $id = accordion_elem.attr('id');
            new Accordion('#' + $id, {
                duration: 500,
                showItem: data_opt.showitem,
                elementClass: 'htwoolentor-faq-card',
                questionClass: 'htwoolentor-faq-head',
                answerClass: 'htwoolentor-faq-body',
            });
        }
        
    };


    /**
     * WoolentorOnePageSlider
     */
    var WoolentorOnePageSlider = function ( $scope, $ ){

        var slider_elem = $scope.find('.ht-full-slider-area').eq(0);

        if ( slider_elem.length > 0 ) {

            /* Jarallax active  */
            $('.ht-parallax-active').jarallax({
                speed: 0.4,
            });
            
            $('#ht-nav').onePageNav({
                currentClass: 'current',
                changeHash: false,
                scrollSpeed: 750,
                scrollThreshold: 0.5,
                filter: '',
                easing: 'swing',
            });
            
            /*------ Wow Active ----*/
            new WOW().init();

            /*---------------------
            Video popup
            --------------------- */
            $('.ht-video-popup').magnificPopup({
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,
                zoom: {
                    enabled: true,
                }
            });
    
        }

    };

    /*
    * Run this code under Elementor.
    */
    $(window).on('elementor/frontend/init', function () {

        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-product-tab.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-product-tab.default', WidgetProducttabsHandler);

        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-universal-product.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-universal-product.default', WidgetWoolentorTooltipHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-universal-product.default', WidgetThumbnaisImagesHandler);

        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-cross-sell-product-custom.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-cross-sell-product-custom.default', WidgetWoolentorTooltipHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-cross-sell-product-custom.default', WidgetThumbnaisImagesHandler);

        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-upsell-product-custom.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-upsell-product-custom.default', WidgetWoolentorTooltipHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-upsell-product-custom.default', WidgetThumbnaisImagesHandler);

        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-related-product-custom.default', WidgetProductSliderHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-related-product-custom.default', WidgetWoolentorTooltipHandler);
        elementorFrontend.hooks.addAction( 'frontend/element_ready/woolentor-related-product-custom.default', WidgetThumbnaisImagesHandler);

        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-product-video-gallery.default', WidgetProductVideoGallery );
        
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-brand-logo.default', WidgetProductSliderHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-faq.default', WoolentorAccordion );
        
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-category-grid.default', WidgetProductSliderHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-testimonial.default', WidgetProductSliderHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-product-grid.default', WidgetProductSliderHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wl-onepage-slider.default', WoolentorOnePageSlider );

    });


})(jQuery);