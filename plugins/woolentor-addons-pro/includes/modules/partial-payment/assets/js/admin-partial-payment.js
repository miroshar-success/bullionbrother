;(function($){
    "use strict";
    
    $('input#woolentor_partial_payment_enable').on('change',function( event ){
        var status = $(this).prop('checked'),
            parent = $(this).parents('p.form-field');
        if( status ){
            parent.siblings('p.form-field').removeClass('hidden');
        }else{
            parent.siblings('p.form-field').addClass('hidden');
        }
    });
    
})(jQuery);