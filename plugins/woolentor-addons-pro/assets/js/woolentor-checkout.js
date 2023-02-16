;(function($){
    "use strict";
    
        // Handle Quentity Increment and decrement
        var woolentor_checkout_quentity = {
    
            init: function(){
                $( document.body ).on( 'click', '.woolentor-order-review-product input.qty', this.update_order_review )
            },
    
            update_order_review: function( event ){
    
                var data = {
                    action: 'update_order_review',
                    security: wc_checkout_params.update_order_review_nonce,
                    post_data: $( 'form.checkout' ).serialize()
                };
                $.post(
                    wc_checkout_params.ajax_url,
                    data, 
                    function( response ){
                        $( document.body ).trigger( 'update_checkout' );
                    }
                );
    
            }
    
        };
    
        // Handle Coupon Form
        var woolentor_checkout_coupons = {
    
            init: function() {
                $( document.body ).on( 'click', '.woolentor-checkout-coupon-form a.show-coupon', this.show_coupon_form )
                $( ':is(.woolentor-checkout-coupon-form .coupon-form,.woolentor-checkout-coupon-form .checkout_coupon) button[name="apply_coupon"]' ).on( 'click', this.submit_coupon );
            },
    
            show_coupon_form: function( event ) {
                event.preventDefault();
    
                $( '.woolentor-checkout-coupon-form .coupon-form' ).slideToggle( 400, function() {
                    $( '.coupon-form' ).find( ':input:eq(0)' ).trigger( 'focus' );
                });
    
                return false;
            },
    
            submit_coupon: function( event ) {
                event.preventDefault();
    
                var $form = $('.woolentor-checkout-coupon-form').find(".coupon-form");
                if( $form.length === 0 ){
                    $form = $('.woolentor-checkout-coupon-form').find(".checkout_coupon");
                }
    
                if ( $form.is( '.processing' ) ) {
                    return false;
                }
    
                $form.addClass( 'processing' ).block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
    

                var $cart_form = $( '.woocommerce-cart-form' );

                if( $cart_form.length && typeof wc_cart_params != 'undefined' ){
                    woolentor_checkout_coupons.apply_coupon_cart($form);
                } else if( typeof wc_checkout_params != 'undefined' ) {
                    woolentor_checkout_coupons.apply_coupon_checkout($form);
                }

                return false;
            },

            apply_coupon_checkout: function( $form ){
                var data = {
                    security:    wc_checkout_params.apply_coupon_nonce,
                    coupon_code: $form.find( 'input[name="coupon_code"]' ).val()
                };
    
                $.ajax({
                    type: 'POST',
                    url:  wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'apply_coupon' ),
                    data: data,
                    success: function( code ) {
                        $( '.woocommerce-error, .woocommerce-message' ).remove();
                        $form.removeClass( 'processing' ).unblock();
    
                        if ( code ) {
                            if( $('.woolentor-checkout-coupon-form').hasClass('woolentor-coupon-1') ){
                                $('.woolentor-checkout-coupon-form').before( code );
                            } else {
                                $('.woolentor-checkout-coupon-form').after( code );
                            }
                            
                            $form.slideUp();
    
                            $( document.body ).trigger( 'applied_coupon_in_checkout', [ data.coupon_code ] );
                            $( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );
                        }
                    },
                    dataType: 'html'
                });
    
                return false;
            },
    

            /**
             * Gets a url for a given AJAX endpoint.
             *
             * @param {String} endpoint The AJAX Endpoint
             * @return {String} The URL to use for the request
             */
            get_url: function( endpoint ) {
                return wc_cart_params.wc_ajax_url.toString().replace(
                    '%%endpoint%%',
                    endpoint
                );
            },

            /**
             * Shows new notices on the page.
             *
             * @param {Object} The Notice HTML Element in string or object form.
             */
            show_notice: function( html_element, $target ) {
                if ( ! $target ) {
                    $target = $( '.woocommerce-notices-wrapper:first' ) ||
                        $( '.cart-empty' ).closest( '.woocommerce' ) ||
                        $( '.woocommerce-cart-form' );
                }
                $target.prepend( html_element );
            },

            apply_coupon_cart: function($form){
                var $text_field = $form.find( '#coupon_code' );
                var coupon_code = $text_field.val();

                var data = {
                    security: wc_cart_params.apply_coupon_nonce,
                    coupon_code: coupon_code
                };

                $.ajax( {
                    type:     'POST',
                    url:      woolentor_checkout_coupons.get_url( 'apply_coupon' ),
                    data:     data,
                    dataType: 'html',
                    success: function( response ) {
                        $( '.woocommerce-error, .woocommerce-message, .woocommerce-info' ).remove();
                        woolentor_checkout_coupons.show_notice( response );
                        $( document.body ).trigger( 'applied_coupon', [ coupon_code ] );
                    },
                    complete: function() {
                        $form.removeClass('processing').unblock();
                        $text_field.val( '' );
                        woolentor_checkout_coupons.update_cart_totals( true );
                        $.scroll_to_notices( $( '[role="alert"]' ) );
                    }
                } );
            },

            /**
             * Update the cart after something has changed.
             */
            update_cart_totals: function() {
                
                $( 'div.cart_totals' ).block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });

                $.ajax( {
                    url:      woolentor_checkout_coupons.get_url( 'get_cart_totals' ),
                    dataType: 'html',
                    success:  function( response ) {
                        woolentor_checkout_coupons.update_cart_totals_div( response );
                    },
                    complete: function() {
                        $( 'div.cart_totals' ).unblock();
                    }
                } );
            },

            /**
             * Update the .cart_totals div with a string of html.
             *
             * @param {String} html_str The HTML string with which to replace the div.
             */
            update_cart_totals_div: function( html_str ) {
                $( '.cart_totals' ).replaceWith( html_str );
                $( document.body ).trigger( 'updated_cart_totals' );
            },

        };
    
        // Handle Loggin Form
        var woolentor_checkout_login = {
    
            init: function() {
                $(':is(.elementor-widget-wl-checkout-login-form,.woolentor_block_checkout_login_form)').find('.woocommerce-form-login').removeAttr('method');
                $( document.body ).on( 'click', '.elementor-widget-wl-checkout-login-form a.showlogin,.woolentor_block_checkout_login_form a.showlogin', this.show_login_form );
                $( ':is(.elementor-widget-wl-checkout-login-form,.woolentor-msc-step-login .woocommerce-form-login,.woolentor_block_checkout_login_form) button[name="login"]' ).on( 'click', this.submit_login );
            },
    
            show_login_form: function() {
                $( ':is(.elementor-widget-wl-checkout-login-form,.woolentor_block_checkout_login_form) div.login,.woocommerce-form-login' ).slideToggle();
                return false;
            },
    
            submit_login: function( event ){
                event.preventDefault();
    
                var $form = $('.elementor-widget-wl-checkout-login-form,.woolentor-msc-step-login,.woolentor_block_checkout_login_form').find(".woocommerce-form-login");
    
                if ( $form.is( '.processing' ) ) {
                    return false;
                }
    
                $form.addClass( 'processing' ).block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
    
                // All Field value sent
                var item = {};
                var generateData = '';
                var loginformArea = $( '.elementor-widget-wl-checkout-login-form,.woolentor-msc-step-login,.woolentor_block_checkout_login_form');
                loginformArea.find('input:text, input:password, input:file, input:hidden, select, textarea').each(function() {
                    var $thisitem = $( this ),
                        attributeName = $thisitem.attr( 'name' ),
                        attributevalue = $thisitem.val();
    
                    if ( attributevalue.length === 0 ) {
                        generateData = generateData + '&' + attributeName + '=' + '';
                    } else {
                        item[attributeName] = attributevalue;
                        generateData = generateData + '&' + attributeName + '=' + attributevalue;
                    }
    
                });
                loginformArea.find('input:radio, input:checkbox').each(function() {
                    var $thisitem = $( this ),
                        attributeName = $thisitem.attr( 'name' ),
                        attributevalue = $thisitem.val();
    
                    if( $thisitem.is(":checked") ){
                        generateData = generateData + '&' + attributeName + '=' + attributevalue;
                    }
    
                });
    
                var generateData = '&login=login&action=woolentor_ajax_login' + generateData;
    
                $.ajax({  
                    type: 'POST',
                    url:  wc_checkout_params.ajax_url,
                    data: JSON.stringify( generateData ),
                    success: function( response ){
                        if( response.data ){
                            loginformArea.find('.woocommerce-error').remove();
                            $form.removeClass( 'processing' ).unblock();
                            $form.before( response.data.notices );
                        }else{
                            window.location.replace( loginformArea.find('input[name="redirect"]').val() );
                        }
                    }  
                });
    
            }
    
        };
    
        woolentor_checkout_coupons.init();
        woolentor_checkout_login.init();
    
        /**
         * Select2 Activation
         */
        $("select.woolentor-enhanced-select").selectWoo({
            allowClear:!0,
            placeholder:$(this).data("placeholder")
        }).addClass("enhanced");
    
    
    })(jQuery);