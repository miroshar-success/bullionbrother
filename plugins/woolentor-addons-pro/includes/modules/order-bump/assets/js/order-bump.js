/* Order Bump Module JS
======================================================= */
;(function($){
    "use strict";

    if( typeof woolentor_order_bump_params === 'undefined' ){
        return false;
    }
    
    var order_bump = {
        init: function() {
            $(document).ready(function(){
                $('body').on('change', '.product-type-simple .woolentor-order-bump-checkbox', function(event){
                    var product_id    = Number($(this).data('product_id')),
                        order_bump_id = Number($(this).data('order_bump_id'));
                    if( order_bump_id && product_id && $(this).is(':checked') ){
                        order_bump.add_to_cart( order_bump_id,product_id );
                    } else if( order_bump_id && product_id && !$(this).is(':checked') ) {
                        var cart_item_key = $(this).data('cart_item_key');
                            order_bump.remove_product_from_cart( $(this), product_id, $(this).data('cart_item_key') );
                    }
                })
            });

            // Render variations popup
            $('body').on('click', '.woolentor-order-bump-select-options', function (event) {
                order_bump.render_variations_popup( event, this );
            });

            $('.woolentor-order-bump-close').on('click', function(event){
                $('#woolentor-order-bump-variations-popup').removeClass('woolentor-order-bump-variations-popup-open');
                $('body').removeClass('woolentor-order-bump-variations-popup');
                $('.woolentor-order-bump-modal-dialog').css("background-color","transparent");
            });

            // Reload checkout page if any order bump exists and coupon is added or removed
            $( document.body ).on( 'removed_coupon_in_checkout', function () {
                if( $('body').find('.woolentor-order-bump-rule-cart_applied_coupons').length > 0 ){
                    location.reload();
                }
            } );
            
            $( document.body ).on( 'applied_coupon_in_checkout', function () {
                if( $('body').find('.woolentor-order-bump').length > 0 ){
                    location.reload();
                }
            } );
        },

        add_to_cart: function(order_bump_id, product_id) {
            var $this = $(this);

            $.ajax({
                url: woolentor_order_bump_params.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    'action': 'woolentor_order_bump_add_to_cart',
                    'order_bump_id': order_bump_id,
                    'product_id': product_id,
                    'nonce' : woolentor_order_bump_params.nonce
                },
        
                beforeSend:function(){
                    $('.woolentor-order-bump.post-' + product_id).block({
                        message: '',
                        overlayCSS: {
                            background: '#fff',
                            opacity: 0.6
                        }
                    });
                },
        
                success:function(response) {
                    if ( woolentor_order_bump_params.wp_debug_log ) {
                        // console.log(response);
                    }

                    // Add cart item key
                    if( typeof response.data.cart_item_key !== 'undefined' ){
                        $('.woolentor-order-bump.post-' + product_id).find('.woolentor-order-bump-checkbox').attr('data-cart_item_key', response.data.cart_item_key);

                        $('.woolentor-order-bump.post-' + product_id).block({
                            message: woolentor_order_bump_params.i18n.product_added,
                            showOverlay: false,
                            css: {
                                width: '200px',
                                border: 'none', 
                                padding: '10px', 
                                backgroundColor: '#000', 
                                '-webkit-border-radius': '5px', 
                                '-moz-border-radius': '5px', 
                                opacity: .7, 
                                color: '#fff' 
                            },
                        });

                        $( document.body ).trigger('added_to_cart', [response.fragments, response.cart_hash, $this]);
                        $( document.body ).trigger( 'update_checkout' );
                        $( document.body ).trigger( 'wc_fragment_refresh' );
                        $( document.body ).trigger( 'woolentor_order_bump_added_to_cart' );
                    }
                },
        
                complete:function( response ){
                    setTimeout(function(){
                        $('.woolentor-order-bump.post-' + product_id).unblock();
                    }, 1500);
                },
        
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        },

        add_to_cart_variable: function( $selector ) {
            var $this = $(this);

            $(document).on('click', $selector, function (e) {
                e.preventDefault();
    
                var $thisbutton     = $(this),
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
    
                $.ajax({
                    type: 'post',
                    url: woolentor_order_bump_params.ajax_url,
                    data: {
                        action: 'woolentor_insert_to_cart',
                        product_id: product_id,
                        product_sku: '',
                        quantity: product_qty,
                        variation_id: variation_id,
                        variations: item,
                    },
                    beforeSend: function (response) {
                        $thisbutton.removeClass('added').addClass('loading');
                    },
                    success: function (response) {
                        if ( woolentor_order_bump_params.wp_debug_log ) {
                            // console.log(response);
                        }

                        $( document.body ).trigger('added_to_cart').trigger('update_checkout').trigger('wc_fragment_refresh');
                        $( document.body ).trigger( 'woolentor_order_bump_added_to_cart' );
                        $('.woolentor-order-bump-close').trigger('click');
                    },
                    complete: function (response) {
                        $thisbutton.addClass('added').removeClass('loading');
                    },
                });
    
                return false;
            });
        },

        remove_product_from_cart: function($this, product_id, cart_item_key) {
            if( !cart_item_key ){
                return;
            }

            $.ajax({
                url: woolentor_order_bump_params.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    'action': 'woolentor_order_bump_remove_product_from_cart',
                    'cart_item_key': cart_item_key,
                    'nonce' : woolentor_order_bump_params.nonce
                },
        
                beforeSend:function(){
                    $('.woolentor-order-bump.post-' + product_id).block({
                        message: '',
                        overlayCSS: {
                            background: '#fff',
                            opacity: 0.7
                        }
                    });
                },
        
                success:function(response) {
                    // Add cart item key
                    if( typeof response.data.status !== 'undefined'  ){
                        $('.woolentor-order-bump.post-' + product_id).find('.woolentor-order-bump-checkbox').attr('data-cart_item_key', '');

                        $('.woolentor-order-bump.post-' + product_id).block({
                            message: woolentor_order_bump_params.i18n.product_removed,
                            showOverlay: false,
                            css: {
                                width: '200px',
                                border: 'none', 
                                padding: '10px', 
                                backgroundColor: '#000', 
                                '-webkit-border-radius': '5px', 
                                '-moz-border-radius': '5px', 
                                opacity: 0.7, 
                                color: '#fff' 
                            },
                        });

                        $( document.body ).trigger('removed_from_cart').trigger('update_checkout').trigger('wc_fragment_refresh');
                        $( document.body ).trigger( 'woolentor_order_bump_removed_from_cart' );
                    }
                },
        
                complete:function( response ){
                    setTimeout(function(){
                        $('.woolentor-order-bump.post-' + product_id).unblock();
                    }, 2000);
                },
        
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        },

        render_variations_popup: function( event, _this ){
            event.preventDefault();

            var $this = $(_this),
                product_id = $this.data('product_id');

            $('.woolentor-order-bump-modal-body').html(''); /*clear content*/
            $('#woolentor-order-bump-variations-popup').addClass('woolentor-order-bump-variations-popup-open wlloading');
            $('#woolentor-order-bump-variations-popup .woolentor-order-bump-close').hide();
            $('.woolentor-order-bump-modal-body').html('<div class="woolentor-loading"><div class="wlds-css"><div style="width:100%;height:100%" class="wlds-ripple"><div></div><div></div></div>');

            $.ajax({
                url: woolentor_order_bump_params.ajax_url + '?woolentor_order_bump_variations_popup',
                data: {
                    id: product_id,
                    action: "woolentor_order_bump_variations_popup",
                },
                method: 'POST',
                success: function (response) {
                    if ( woolentor_order_bump_params.wp_debug_log ) {
                        // console.log(response);
                    }

                    setTimeout(function () {
                        $('.woolentor-order-bump-modal-body').html(response);
                        $('#woolentor-order-bump-variations-popup .woolentor-order-bump-close').show();

                        order_bump.initialize_image_slider();
                        order_bump.initialize_thumbnail();
    
                        order_bump.initialize_variations_form( $('.woolentor-order-bump-variations-popup-open') );
                        order_bump.add_to_cart_variable( ".woolentor-order-bump-modal-content .single_add_to_cart_button:not(.disabled)" );
    
                        $(document).trigger('woolentor_quick_view_rendered');
                    }, 300 );
                },
                complete: function () {
                    $('#woolentor-order-bump-variations-popup').removeClass('wlloading');
                    $('.woolentor-order-bump-modal-dialog').css("background-color","#ffffff");
                },
                error: function (errorThrown) {
                    console.log(errorThrown);
                },
            });
        },

        initialize_image_slider: function(){
            $('.woolentor-order-bump-variations-popup-large-img').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: true,
                asNavFor: '.woolentor-order-bump-variations-popup-thumbnails'
            });
        },

        initialize_thumbnail: function(){
            $('.woolentor-order-bump-variations-popup-thumbnails').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                asNavFor: '.woolentor-order-bump-variations-popup-large-img',
                dots: false,
                arrows: true,
                focusOnSelect: true,
                prevArrow: '<button class="woolentor-slick-prev"><i class="sli sli-arrow-left"></i></button>',
                nextArrow: '<button class="woolentor-slick-next"><i class="sli sli-arrow-right"></i></button>',
            });
        },

        initialize_variations_form: function( $product ){
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
                    $default_data.src = $('.woolentor-order-bump-variations-popup-large-img').find('.woolentor-variations-popup-gallery-first-image .wp-post-image').attr('src');
                    $default_data.srcset = $('.woolentor-order-bump-variations-popup-large-img').find('.woolentor-variations-popup-gallery-first-image .wp-post-image').attr('srcset');
                    $default_data.srcfull = $('.woolentor-order-bump-variations-popup-large-img').find('.woolentor-variations-popup-gallery-first-image .wp-post-image').attr('data-src');
                }

                $('.ht-qwick-view-left').find('.woolentor-order-bump-variations-popup-large-img').slick('slickGoTo', 0);

                $('.woolentor-order-bump-variations-popup-large-img').find('.woolentor-variations-popup-gallery-first-image .wp-post-image').wc_set_variation_attr('src',variation.image.full_src);
                $('.woolentor-order-bump-variations-popup-large-img').find('.woolentor-variations-popup-gallery-first-image .wp-post-image').wc_set_variation_attr('srcset',variation.image.srcset);
                $('.woolentor-order-bump-variations-popup-large-img').find('.woolentor-variations-popup-gallery-first-image .wp-post-image').wc_set_variation_attr('data-src',variation.image.full_src);
                $('.woolentor-order-bump-variations-popup-large-img').find('.woolentor-variations-popup-gallery-first-image .wp-post-image').wc_set_variation_attr('data-large_image',variation.image.full_src);

                // Reset data
                $('.variations').find('.reset_variations').on('click', function(e){
                    $('.woolentor-order-bump-variations-popup-large-img').find('.woolentor-variations-popup-gallery-first-image .wp-post-image').wc_set_variation_attr('src', $default_data.src );
                    $('.woolentor-order-bump-variations-popup-large-img').find('.woolentor-variations-popup-gallery-first-image .wp-post-image').wc_set_variation_attr('srcset', $default_data.srcset);
                    $('.woolentor-order-bump-variations-popup-large-img').find('.woolentor-variations-popup-gallery-first-image .wp-post-image').wc_set_variation_attr('data-src', $default_data.srcfull );
                    $('.woolentor-order-bump-variations-popup-large-img').find('.woolentor-variations-popup-gallery-first-image .wp-post-image').wc_set_variation_attr('data-large_image', $default_data.srcfull );
                });
            });
        }
    }

    // Initialize the object
    order_bump.init();
    
})(jQuery);