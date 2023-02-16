"use strict";
jQuery( function( $ ) {
	
	$(document).ready(function(e){

		e.preventDefault;

		$(".bundled_item_cart_content .variations select").on("change", function () {
			
			var i = 'input.variation_id', f = '.bundled_item_cart_content.variations_form';
			var id;
			var proudct_id = $(f).attr('data-product_id');
			var bundle_id = $(f).attr('data-bundle_id');

			setTimeout( function(){
				id =  $(i).val() ;
				
				var data = {
					action: 'woo_pr_change_points_meesage_variation_wise',
					variation_id: id,
					proudct_id:proudct_id,
					bundle_id:bundle_id
				};				
				
				$.post(WooPointsPublic.ajaxurl, data, function(response) {

					console.log(response);
					
					$('.woopr-product-message').html(response);
								
				});
				
				
			}, 300 );
			
			
		} );
	});

});
