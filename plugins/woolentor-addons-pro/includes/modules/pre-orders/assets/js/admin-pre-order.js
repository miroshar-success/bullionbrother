;(function($){
    "use strict";
    
    $('input#woolentor_pre_order_enable').on('change',function( event ){
        var status = $(this).prop('checked'),
            parent = $(this).parents('p.form-field');
        if( status ){
            parent.siblings('div.woolentor-pre-order-fields').removeClass('hidden');
        }else{
            parent.siblings('div.woolentor-pre-order-fields').addClass('hidden');
        }
    });

    $('select#woolentor_pre_order_manage_price').on('change',function( event ){
        var value = $(this).val();

        if( value != 'product_price' ){
            $('.woolentor-mange-price').removeClass('hidden');
            if( value == 'fixed_price' ){
                $('.woolentor_pre_order_amount_type_field').addClass('hidden');
            }else{
                $('.woolentor_pre_order_amount_type_field').removeClass('hidden');
            }
        }else{
            $('.woolentor-mange-price').addClass('hidden');
            $('.woolentor_pre_order_amount_type_field').addClass('hidden');
        }

    });
    
})(jQuery);