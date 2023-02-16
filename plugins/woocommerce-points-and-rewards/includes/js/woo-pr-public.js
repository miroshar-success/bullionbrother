"use strict";
jQuery( function( $ ) {
	
	//Trigger when checkout data updated
	$( document.body ).on( "updated_checkout", function( e, data ) {

		//Check if message exists
		var ele_message = $( 'body .woocommerce-info.woo-pr-earn-points-message' );
		if( ele_message.length > 0 ) {
			var total_points = $( '#woo_pr_total_points_will_earn' ).val();
			if( total_points ) {
				var new_message = ele_message.html().replace(/-?[0-9]*\.?[0-9]+/, total_points);
				ele_message.html( new_message );
			}
		}
	});
	
	$( ".variations_form" ).on( "woocommerce_variation_select_change", function () {
		
		
		var i = 'input.variation_id', f = 'form.variations_form', s = 'table.variations select';
		var id;
		var proudct_id = $(f).attr('data-product_id');
		
		setTimeout( function(){
			id =  $(i).val() ;
			
			var data = {
				action: 'woo_pr_change_points_meesage_variation_wise',
				variation_id: id,
				proudct_id:proudct_id
			};				
			
			$.post(WooPointsPublic.ajaxurl, data, function(response) {
				
				$('.woopr-product-message').html(response);
							
			});
			
			 
		}, 300 );
		
		
	} );

});

//function for ajax pagination
function woo_pr_ajax_pagination(pid){
	var data = {
					action: 'woo_pr_next_page',
					paging: pid
				};

			jQuery('.woo-pr-sales-loader').show();
			jQuery('.woo-pr-paging').hide();

			jQuery.post(WooPointsPublic.ajaxurl, data, function(response) {
				var newresponse = jQuery( response ).filter( '.woo-pr-user-log' ).html();
				jQuery('.woo-pr-sales-loader').hide();
				jQuery('.woo-pr-user-log').html( newresponse );
			});
	return false;
}

