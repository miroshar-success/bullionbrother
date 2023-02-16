;(function($){
    "use strict";

    // Stock options.
    $(document).ready(function(){
        // On change manage stock checkbox
        $( '#inventory_product_data input#_manage_stock' ).on( 'change', function() {
            if ( $( this ).is( ':checked' ) ) {
                $('#inventory_product_data .woolentor-backorder-fields').removeClass('wl_manage_stock--no').addClass('wl_manage_stock--yes');
            } else {
                $('#inventory_product_data .woolentor-backorder-fields').removeClass('wl_manage_stock--yes').addClass('wl_manage_stock--no');
            }
        });

        // On change stock status
        $( '#inventory_product_data select#_stock_status' ).on( 'change', function() {
            if( $(this).val() == 'onbackorder' ){
                $('#inventory_product_data .woolentor-backorder-fields').addClass( 'wl_stock_status--onbackorder' );
            } else{
                $('#inventory_product_data .woolentor-backorder-fields').removeClass('wl_stock_status--onbackorder' );
            }
        });

        // On change allow backorder
        $( '#inventory_product_data select#_backorders' ).on( 'change', function() {
            $('#inventory_product_data .woolentor-backorder-fields').removeClass('wl_allow_backorder--yes wl_allow_backorder--notify wl_allow_backorder--no');
            $('#inventory_product_data .woolentor-backorder-fields').addClass('wl_allow_backorder--' + $(this).val());
        });
    });

})(jQuery);