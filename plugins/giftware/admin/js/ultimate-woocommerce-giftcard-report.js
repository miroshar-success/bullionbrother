/**
 * All of the code for report of generated coupons
 * should reside in this file.
 *
 * @package           Ultimate Woocommerce Gift Cards
 */

jQuery( document ).ready(
	function($){
		// giftcard reporting feature.
		jQuery( document ).on(
			'click',
			'.wps_uwgc_gift_report_view',
			function(e){
				e.preventDefault();
				var coupon_id = jQuery( this ).attr( 'data-coupon-id' );
				var order_id = jQuery( this ).attr( 'data-order-id' );
				var data = {
					action:'wps_uwgc_gift_card_details',
					coupon_id:coupon_id,
					order_id:order_id,
					wps_uwgc_nonce:ajax_object.wps_uwgc_report_nonce
				};

				$.ajax(
					{
						url:ajax_object.ajaxurl,
						type:'POST',
						data:data,
						success: function(response) {
							tb_show( " ",response );
						}
					}
				);

			}
		);
	}
);
