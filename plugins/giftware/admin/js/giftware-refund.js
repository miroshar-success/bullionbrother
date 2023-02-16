/**
 * All of the code for activation on your admin-facing JavaScript source
 * should reside in this file.
 *
 * @package           Ultimate Woocommerce Gift Cards
 */

jQuery( document ).ready(
	function($){
		var selectclass = $(document).find('#order_status').children('option');
		$( selectclass ).each(function() {
			var current_status = $( this ).val();
		    if( current_status == 'wc-refunded' || current_status == 'wc-cancelled' ){
		    	$( this ).remove();
		    } 
		});
	}

);