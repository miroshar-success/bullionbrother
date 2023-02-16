;(function( $, window ){
    "use strict";

    var WooLentorBlocks = {

        /**
         * [init]
         * @return {[void]} Initial Function
         */
         init: function(){
            this.TabsMenu(  $(".ht-tab-menus"), '.ht-tab-pane' );
            if( $("[class*='woolentorblock-'] .ht-product-image-slider").length > 0 ) {
                this.productImageThumbnailsSlider( $(".ht-product-image-slider") );
            }
            this.thumbnailsimagescontroller();
        },

        /**
         * [TabsMenu] Active first menu item
         */
         TabsMenu: function( $tabmenus, $tabpane ){

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

        },

        /**
         * Slick Slider
         */
        initSlickSlider: function( $block ){

            var settings = $($block).data('settings');
            var arrows = settings['arrows'];
            var dots = settings['dots'];
            var autoplay = settings['autoplay'];
            var rtl = settings['rtl'];
            var autoplay_speed = parseInt(settings['autoplay_speed']) || 3000;
            var animation_speed = parseInt(settings['animation_speed']) || 300;
            var fade = false;
            var pause_on_hover = settings['pause_on_hover'];
            var display_columns = parseInt(settings['product_items']) || 4;
            var scroll_columns = parseInt(settings['scroll_columns']) || 4;
            var tablet_width = parseInt(settings['tablet_width']) || 800;
            var tablet_display_columns = parseInt(settings['tablet_display_columns']) || 2;
            var tablet_scroll_columns = parseInt(settings['tablet_scroll_columns']) || 2;
            var mobile_width = parseInt(settings['mobile_width']) || 480;
            var mobile_display_columns = parseInt(settings['mobile_display_columns']) || 1;
            var mobile_scroll_columns = parseInt(settings['mobile_scroll_columns']) || 1;

            $($block).not('.slick-initialized').slick({
                arrows: arrows,
                prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
                dots: dots,
                infinite: true,
                autoplay: autoplay,
                autoplaySpeed: autoplay_speed,
                speed: animation_speed,
                fade: fade,
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

        },

        /**
         * Accordion
         */
        initAccordion: function( $block ){

            var settings = $($block).data('settings');
            if ( $block.length > 0 ) {
                var $id = $block.attr('id');
                new Accordion('#' + $id, {
                    duration: 500,
                    showItem: settings.showitem,
                    elementClass: 'htwoolentor-faq-card',
                    questionClass: 'htwoolentor-faq-head',
                    answerClass: 'htwoolentor-faq-body',
                });
            }

        },

        /*
        * Tool Tip
        */
        woolentorToolTips: function (element, content) {
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
        },

        woolentorToolTipHandler: function(){
            $('a.woolentor-compare').each(function() {
                WooLentorBlocks.woolentorToolTips( $(this), 'title' );
            });
            $('.woolentor-cart a.add_to_cart_button,.woolentor-cart a.added_to_cart,.woolentor-cart a.button').each(function() {
                WooLentorBlocks.woolentorToolTips( $(this), 'html');
            });
        },

        /* 
        * Universal product 
        */
        productImageThumbnailsSlider: function ( $slider ){
            $slider.slick({
                dots: true,
                arrows: true,
                prevArrow: '<button class="slick-prev"><i class="sli sli-arrow-left"></i></button>',
                nextArrow: '<button class="slick-next"><i class="sli sli-arrow-right"></i></button>',
            });
        },

        thumbnailsimagescontroller: function(){
            this.TabsMenu( $(".ht-product-cus-tab-links"), '.ht-product-cus-tab-pane' );
            this.TabsMenu( $(".ht-tab-menus"), '.ht-tab-pane' );

            // Countdown
            $('.ht-product-countdown').each(function() {
                var $this = $(this), finalDate = $(this).data('countdown');
                var customlavel = $(this).data('customlavel');
                $this.countdown(finalDate, function(event) {
                    $this.html(event.strftime('<div class="cd-single"><div class="cd-single-inner"><h3>%D</h3><p>'+customlavel.daytxt+'</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%H</h3><p>'+customlavel.hourtxt+'</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%M</h3><p>'+customlavel.minutestxt+'</p></div></div><div class="cd-single"><div class="cd-single-inner"><h3>%S</h3><p>'+customlavel.secondstxt+'</p></div></div>'));
                });
            });

        },


    };

    $( document ).ready( function() {
        WooLentorBlocks.init();

        $("[class*='woolentorblock-'] .product-slider").each(function(){
            WooLentorBlocks.initSlickSlider( $(this) );
        });

        $("[class*='woolentorblock-'] .htwoolentor-faq").each(function(){
            WooLentorBlocks.initAccordion( $(this) );
        });

        /**
         * Tooltip Manager
         */
         WooLentorBlocks.woolentorToolTipHandler();
        
    });

})(jQuery, window);
