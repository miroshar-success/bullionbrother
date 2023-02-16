;(function($){
    "use strict";

    // Partial payment amount show / hide based on amount type
    $('.woolentor-partial-payment-field input[name="woolentor_partial_payment_status"]').on('change',function(){
        if( $(this).is(":checked") ){
            var value = $(this).val();
            if( value == 'yes' ){
                $('.woolentor-partial-payment-calculate-amount').slideDown();
            }else{
                $('.woolentor-partial-payment-calculate-amount').slideUp();
            }
        }
    } );
    
    // Update discount variable product price
    var $default_data = {
        price:'',
    };
    $( '.single_variation_wrap' ).on( 'show_variation', function ( event, variation ) {

        // Get Previous data
        if( typeof $default_data.price !== 'undefined' && $default_data.price.length === 0 ){
            $default_data.price = $('.woolentor-partial-ammount').html();
        }

        var id      = variation.variation_id,
            price   = variation.display_price;
        
        $.ajax( {
            url: WLPP.ajaxurl,
            type: 'POST',
            data: {
                nonce   : WLPP.nonce,
                product_id : id,
                price  : price,
                action  : 'woolentor_partial_amount_update',
            },
            beforeSend: function(){
                $('.woolentor-partial-ammount').addClass('loading');
            },
            success: function( response ) {
                if( response.data.updateprice ){
                    $('.woolentor-partial-ammount').html( response.data.updateprice );
                }
            },
            complete: function( response ) {
                $('.woolentor-partial-ammount').removeClass('loading');
                if( response.responseJSON.data.updateprice ){
                    $('.woolentor-partial-ammount').html( response.responseJSON.data.updateprice );
                }
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }

        });

    });

    // Reset data
    $('.variations').find('.reset_variations').on('click', function( event ){
        $('.woolentor-partial-payment-calculate-amount').find('.woolentor-partial-ammount').html( $default_data.price );
    });
    
})(jQuery);