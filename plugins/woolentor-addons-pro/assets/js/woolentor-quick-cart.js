;(function($){
"use strict";

    $(document).on( 'click', '.ht-product.quick-cart-enable .product_type_variable.add_to_cart_button', function (e) {
        e.preventDefault();

        var $this = $(this),
            $product = $this.parents('.ht-product').first(),
            $content = $product.find('.woolentor-quick-cart-form'),
            id = $product.data('id'),
            btn_loading_class = 'loading';

        if ($this.hasClass(btn_loading_class)) return;

        // Show Form
        if ( $product.hasClass('quick-cart-loaded') ) {
            $product.addClass('quick-cart-open');
            return;
        }

        var data = {
            action: 'woolentor_pro_quick_cart',
            id: id
        };
        $.ajax({
            type: 'post',
            url: woolentor_quick_cart.ajax_url,
            data: data,
            beforeSend: function (response) {
                $this.addClass(btn_loading_class);
                $product.addClass('loading-quick-cart');
            },
            success: function (response) {
                $content.append( response );
                woolentor_render_variation_data( $product );
                woolentor_variation_data( $product );
                woolentor_inser_to_cart();
            },
            complete: function (response) {
                setTimeout(function () {
                    $this.removeClass(btn_loading_class);
                    $product.removeClass('loading-quick-cart');
                    $product.addClass('quick-cart-open quick-cart-loaded');
                }, 100);
            },
        });

        return false;

    });

    $(document).on('click', '.woolentor-quick-cart-close', function () {
        var $this = $(this),
            $product = $this.parents('.ht-product');
        $product.removeClass('quick-cart-open');
    });

    $(document.body).on('added_to_cart', function () {
        $('.ht-product').removeClass('quick-cart-open');
    });

    /**
     * [woolentor_render_variation_data] show variation data
     * @param  {[selector]} $product
     * @return {[void]} 
     */
    function woolentor_render_variation_data( $product ) {
        $product.find('.variations_form').wc_variation_form().find('.variations select:eq(0)').change();
        $product.find('.variations_form').trigger('wc_variation_form');
    }

    /**
     * [woolentor_variation_data] change image data after variation change
     * @param  {[selector]} $product
     * @return {[void]}
     */
    function woolentor_variation_data( $product ){

        var $default_data = {
            src:'',
            srcset:'',
            sizes:'',
            width:'',
            height:'',
        };

        $( '.single_variation_wrap' ).on( 'show_variation', function ( event, variation ) {

            // Get First image data
            if( $default_data.src.length === 0 ){
                $default_data.src = $product.find('.ht-product-image img').attr('src');
                $default_data.srcset = $product.find('.ht-product-image img').attr('srcset');
                $default_data.sizes = $product.find('.ht-product-image img').attr('sizes');
                $default_data.width = $product.find('.ht-product-image img').attr('width');
                $default_data.height = $product.find('.ht-product-image img').attr('height');
            }

            // Set variation image
            $product.find('.ht-product-image img').wc_set_variation_attr('src',variation.image.full_src);
            $product.find('.ht-product-image img').wc_set_variation_attr('srcset',variation.image.srcset);
            $product.find('.ht-product-image img').wc_set_variation_attr('sizes',variation.image.sizes);
            $product.find('.ht-product-image img').wc_set_variation_attr('width',variation.image.full_src_w);
            $product.find('.ht-product-image img').wc_set_variation_attr('height',variation.image.full_src_h);

        });

        // Reset data
        $product.find('.reset_variations').on('click', function(e){
            woolentor_variation_data_reset( $product, $default_data );
        });

    }

    /**
     * [woolentor_variation_data_reset] data reset
     * @param  {[type]} $product target product
     * @param  {[type]} $data data
     * @return {[type]} void
     */
    function woolentor_variation_data_reset( $product, $data ){
        $product.find('.ht-product-image img').wc_set_variation_attr('src',$data.src);
        $product.find('.ht-product-image img').wc_set_variation_attr('srcset',$data.srcset);
        $product.find('.ht-product-image img').wc_set_variation_attr('sizes',$data.sizes);
        $product.find('.ht-product-image img').wc_set_variation_attr('width',$data.width);
        $product.find('.ht-product-image img').wc_set_variation_attr('height',$data.height);
    }

    /**
     * [woolentor_inser_to_cart] Add to cart
     * @return {[void]}
     */
    function woolentor_inser_to_cart(){

        $(document).on( 'click', '.woolentor-quick-cart-form .single_add_to_cart_button:not(.disabled)', function (e) {
            e.preventDefault();

            var $this = $(this),
                $form           = $this.closest('form.cart'),
                all_data        = $form.serialize(),
                product_qty     = $form.find('input[name=quantity]').val() || 1,
                product_id      = $form.find('input[name=product_id]').val() || $this.val(),
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
                // action: 'woolentor_insert_to_cart',
                product_id: product_id,
                product_sku: '',
                quantity: product_qty,
                variation_id: variation_id,
                variations: item,
                all_data: all_data,
            };

            var alldata = data.all_data + '&product_id='+ data.product_id + '&product_sku='+ data.product_sku + '&quantity='+ data.quantity + '&variation_id='+ data.variation_id + '&variations='+ JSON.stringify( data.variations ) +'&action=woolentor_single_insert_to_cart';

            $( document.body ).trigger('adding_to_cart', [$this, data]);

            $.ajax({
                type: 'post',
                url: woolentor_quick_cart.ajax_url,
                data: alldata,

                beforeSend: function (response) {
                    $this.removeClass('added').addClass('loading');
                },

                complete: function (response) {
                    $this.addClass('added').removeClass('loading');
                },

                success: function (response) {
                    if ( response.error & response.product_url ) {
                        window.location = response.product_url;
                        return;
                    } else {
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $this]);
                    }
                },

            });

            return false;
        });

    }

})(jQuery);