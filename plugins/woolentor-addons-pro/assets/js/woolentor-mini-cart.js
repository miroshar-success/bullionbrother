;(function($){
"use strict";

    // Open cart
    var minicart_open = function() {
        $('body,.woolentor_mini_cart_area').addClass('woolentor_mini_cart_open');
        var contentarea = $('.woolentor_cart_content_container').outerWidth();
        if( $( ".woolentor_mini_cart_area" ).hasClass( "woolentor_mini_cart_pos_right" ) ){
            $('.woolentor_mini_cart_icon_area').css('right',(contentarea+10)+'px' );
        }else{
            $('.woolentor_mini_cart_icon_area').css('left',(contentarea+10)+'px' );
        }
    };

    // Close Cart
    var minicart_close = function(){
        $('body,.woolentor_mini_cart_area').removeClass('woolentor_mini_cart_open');
        if( $( ".woolentor_mini_cart_area" ).hasClass( "woolentor_mini_cart_pos_right" ) ){
            $('.woolentor_mini_cart_icon_area').css('right',10+'px' );
        }else{
            $('.woolentor_mini_cart_icon_area').css('left',10+'px' );
        }
    };

    // Cart Open If click on icon
    $('.woolentor_mini_cart_icon_area').on( 'click', minicart_open );

    // Cart Close If click on close | body opacity
    $('body').on('click','.woolentor_mini_cart_close , .woolentor_body_opacity', function(){
        minicart_close();
    });

    // Cart Open when item is added if no Ajax Action
    $(document).on('wc_fragments_refreshed', function(){
        if( woolentorMiniCart.addedToCart ){
            var opened = false;
            if( opened === false ){
                setTimeout( minicart_open, 1 );
                opened = true;
            }
        }
    });
    
    // Open cart when item is added if Ajax Action
    $(document).on('added_to_cart',function(e){
        setTimeout( minicart_open, 1 );
    });

    // Set Content area Height
    $( document.body ).on( 'wc_fragments_refreshed wc_fragments_loaded', function(){
        minicart_content_height();
    });

    // Content Area Height
    // var minicart_content_height = function() {
    //     var headerarea = $('.woolentor_mini_cart_header').outerHeight(), 
    //         footerarea = $('.woolentor_mini_cart_footer').outerHeight(),
    //         windowHeight = $(window).height();

    //     var content_height = windowHeight - ( headerarea + footerarea );
    //     $('.woolentor_mini_cart_content').css('height',content_height);

    // };

    var minicart_content_height = function() {

        var headerarea = 0,
            footerarea = 0,
            content_height = 300,
            windowHeight = $(window).height();
        
        if( $('.woolentor_cart_content_container > .elementor').length ){

            var loop_area = $('.woolentor_mini_cart_content').closest('.elementor-widget-wl-mini-cart'),
                loop = 0;

            loop_area.prevAll().each(function(){
                if( loop > 0 ){
                    headerarea += $(this).outerHeight()+20;
                }else{
                    headerarea += $(this).outerHeight();
                }
                loop++;
            });

            loop_area.nextAll().each(function(){
                footerarea += $(this).outerHeight()+20;
            });

            content_height = windowHeight - ( ( headerarea + footerarea ) - 20 ); // Drecease last element margin
            $('.elementor-element:not(.elementor-element-edit-mode) .woolentor_mini_cart_content').css('height', content_height );

        }else{
            headerarea = $('.woolentor_mini_cart_header').outerHeight();
            footerarea = $('.woolentor_mini_cart_footer').outerHeight();
            content_height = windowHeight - ( headerarea + footerarea );
            $('.woolentor_mini_cart_content').css('height',content_height);
        }

    };

    var minicart_refresh_cart_checkout = function(){

        //Checkout page
        if( window.wc_checkout_params && wc_checkout_params.is_checkout === "1" ){
            // if( $( 'form.checkout' ).length === 0 ){
            //     location.reload();
            //     return;
            // }
            $( document.body ).trigger( "update_checkout" );
        }

        //Cart page
        if( window.wc_add_to_cart_params && window.wc_add_to_cart_params.is_cart && wc_add_to_cart_params.is_cart === "1" ){
            if( $( 'body.woolentor-empty-cart' ).length === 0 ){
                if( $('body.buddypress').hasClass('woocommerce-cart') === false ){
                    $( document.body ).trigger( "wc_update_cart" );
                }
            }
        }

    }

    // After load the window then refresh cart and set height
    $(window).on( 'load', function() {
        minicart_content_height();
        // if Do not get add-to-cart action refresh cart fragment
        if( !woolentorMiniCart.addedToCart ){
            $( document.body ).trigger( 'wc_fragment_refresh' );
        }
        minicart_refresh_cart_checkout();
    });

    // Set Content height if window is resize.
    $(window).resize(function(){
        minicart_content_height();
    });

})(jQuery);