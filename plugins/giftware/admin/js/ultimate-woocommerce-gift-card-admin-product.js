/**
 * All of the code for javascript on your admin-facing JavaScript source
 * should reside in this file.
 *
 * @package           Ultimate Woocommerce Gift Cards
 */

jQuery( document ).ready(
	function($){
		jQuery( "#wps_wgm_exclude_per_category" ).select2();
		jQuery( "#wps_wgm_include_per_category" ).select2();

		if (jQuery( 'input[id="wps_wgm_overwrite"]' ).is( ":checked" )) {
			jQuery( '#wps_wgm_email_to_recipient' ).parent().show();
			jQuery( '#wps_wgm_download' ).parent().show();
			jQuery( '#wps_wgm_shipping' ).parent().show();
		}
		// overwrite delivery method on giftcard product edit page.
		jQuery( '#wps_wgm_overwrite' ).change(
			function(){

				if (jQuery( this ).is( ":checked" )) {
					jQuery( '#wps_wgm_email_to_recipient' ).parent().show();
					jQuery( '#wps_wgm_download' ).parent().show();
					jQuery( '#wps_wgm_shipping' ).parent().show();
				} else {
					jQuery( '#wps_wgm_email_to_recipient' ).parent().hide();
					jQuery( '#wps_wgm_download' ).parent().hide();
					jQuery( '#wps_wgm_shipping' ).parent().hide();
				}

			}
		);
		// hide discount checkbox for selected price type.
		jQuery( '#wps_wgm_pricing' ).change(
			function() {
				var pricing_option = jQuery( this ).val();
				if (pricing_option == 'wps_wgm_selected_price') {
					  jQuery( '#wps_wgm_discount' ).parent().hide();
				} else {
					jQuery( '#wps_wgm_discount' ).parent().show();
				}
			}
		);

		// Resend mail on order edit page.
		jQuery( '#wps_uwgc_resend_mail_button' ).click(
			function(){
				$( "#wps_uwgc_resend_mail_notification" ).html( "" );
				var order_id = $( this ).data( 'id' );
				$( "#wps_wgm_loader" ).show();
				var data = {
					action:'wps_uwgc_resend_mail_order_edit',
					order_id:order_id,
					wps_nonce:wps_wgm_object.wps_wgm_nonce
				};

				$.ajax(
					{
						url: wps_wgm_object.ajax_url,
						type: "POST",
						data: data,
						dataType :'json',
						success: function(response)
					{
							$( "#wps_wgm_loader" ).hide();
							if (response.result == true) {
								var message = response.message;
								var html = '<b style="color:green;">' + message + '</b>'
							} else {
								var message = response.message;
								var html = '<b style="color:red;">' + message + '</b>'

							}
							$( "#wps_uwgc_resend_mail_notification" ).html( html );
						}
					}
				);
			}
		);

		// increment coupon amount on order edit page.

		jQuery( '#wps__uwgc_inc_money_coupon' ).click(
			function(){
				var selectedcoupons = $( "#wps_uwgc_select_coupon_product" ).select2( "val" );
				var selectedprice = $( "#wps_uwgc_inc_amount" ).val();

				if (selectedcoupons == null) {
					$( "#wps_uwgc_resend_coupon_amount_msg" ).html( '<b style="color:red;">Please select coupon first</b>' );
					return;
				}
				if (selectedprice == "") {
					$( "#wps_uwgc_resend_coupon_amount_msg" ).html( '<b style="color:red;">Please enter valid price</b>' );
					return;
				}

				var order_id = $( this ).data( 'id' );

				$( "#wps_uwgc_resend_coupon_amount_msg" ).html( "" );
				$( "#wps_wgm_loader" ).show();
				var data = {
					action:'wps_uwgc_resend_coupon_amount',
					order_id:order_id,
					selectedcoupon:selectedcoupons,
					selectedprice: selectedprice,
					wps_nonce:wps_wgm_object.wps_wgm_nonce
				};
				$.ajax(
					{
						url: wps_wgm_object.ajax_url,
						type: "POST",
						data: data,
						dataType :'json',
						success: function(response)
					{

							$( "#wps_wgm_loader" ).hide();
							if (response.result == true) {
								var message = response.message;
								var html = '<b style="color:green;">' + message + '</b>'
							} else {
								var message = response.message;
								var html = '<b style="color:red;">' + message + '</b>'

							}
							$( "#wps_uwgc_resend_coupon_amount_msg" ).html( html );
						}
					}
				);
			}
		);

		// update email addres on order edit page.

		jQuery( '#wps_uwgc_update_item_meta' ).click(
			function(){
				$( "#wps_wgm_resend_confirmation_msg" ).html( "" );
				var order_id = $( this ).data( 'id' );
				var new_email_id = $( '#wps_uwgc_new_email' ).val();
				var correct_email_format = false;
				var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,5})+$/;
				if (new_email_id != null) {
					if ( ! new_email_id.match( mailformat )) {
						var correct_email_format = false;
					} else {
						var correct_email_format = true;
					}
				}
				$( "#wps_wgm_loader" ).show();
				var data = {
					action:'wps_uwgc_update_item_meta_with_new_email',
					order_id:order_id,
					new_email_id:new_email_id,
					correct_email_format: correct_email_format,
					wps_nonce:wps_wgm_object.wps_wgm_nonce
				};
				$.ajax(
					{
						url: wps_wgm_object.ajax_url,
						type: "POST",
						data: data,
						dataType :'json',
						success: function(response)
					{
							$( "#wps_wgm_loader" ).hide();
							if (response.result == true) {
								var message = response.message;
								var html = '<b style="color:green;">' + message + '</b>'
								location.reload();
							} else {
								var message = response.message;
								var html = '<b style="color:red;">' + message + '</b>';
							}
							$( "#wps_wgm_resend_confirmation_msg" ).html( html );
						}
					}
				);
			}
		);
		jQuery(jQuery('#activate-ultimate-woocommerce-gift-cards-pro').parent()).html('');

	}
);

// show inventory tab.
jQuery(window).on('load',function () {
		jQuery( document ).find( "#inventory_product_data ._manage_stock_field" ).css( "display","block","important" );
		jQuery( document ).find( "#inventory_product_data .options_group" ).css( "display","block","important" );
		jQuery( document ).find( "#inventory_product_data ._sold_individually_field" ).css( "display","block","important" );
	}
);
