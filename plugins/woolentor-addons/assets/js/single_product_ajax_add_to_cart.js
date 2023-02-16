;(function($){
"use strict";

    $(document).on( 'click', '.single_add_to_cart_button:not(.disabled,.lumise-customize-button)', function (e) {
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

        $( document.body ).trigger('adding_to_cart', [$this, alldata]);

        $.ajax({
            type: 'post',
            url: $form.attr('action') + '/?wc-ajax=woolentor_single_insert_to_cart',
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

})(jQuery);