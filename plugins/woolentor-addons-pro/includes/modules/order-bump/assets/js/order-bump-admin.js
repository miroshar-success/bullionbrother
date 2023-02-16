/* Order Bump Module JS
======================================================= */
;(function($){
    "use strict";
    
    if( typeof woolentor_order_bump_params === 'undefined' ){
        return false;
    }

    var order_bump_admin = {
        init: function() {
            $(document).on( 'click', '.woolentor-order-bump-status-switch input', this.quickStatusChange );
        },

        // Set post status from switcher.
        quickStatusChange: function( event ){
            var $this         = $(this),
                post_id       = $this.val() ? $this.val(): '0',
                post_status   = $this.is(":checked") ? 'publish' : 'draft';
                
            $.ajax({
                url: woolentor_order_bump_params.ajax_url,
                dataType: 'json',
                type: 'POST',
                data: {
                    'action': 'woolentor_order_bump_quick_status_change',
                    'post_id': post_id,
                    'post_status': post_status,
                    'nonce' : woolentor_order_bump_params.nonce
                },
        
                beforeSend:function(){
                    $this.closest('label').addClass('woolentor-loading');
                },
        
                success:function(response) {},
        
                complete:function( response ){
                    $this.closest('label').removeClass('woolentor-loading');
                },
        
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        },
    }

    $(document).ready(function(){
        order_bump_admin.init();
    });
    
})(jQuery);