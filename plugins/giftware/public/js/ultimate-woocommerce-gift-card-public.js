/**
 * All of the public javascript code should reside in this file.
 *
 * @package           Ultimate Woocommerce Gift Cards
 */

(function( $ ) {
	'use strict';

	jQuery( document ).ready(
		function(){
			// date picker for schedule date for giftcard.
			$( '#wps_uwgc_send_date' ).datepicker(
				{
					dateFormat : wps_uwgc_param.selected_date,
					minDate: 0
				}
			).datepicker( "setDate", "0" );

			// for delivery method.
			var radio_on_load = $( "input[name='wps_wgm_send_giftcard']:checked" ).val();
			wps_wgm_check_which_radio_has_been_selected( radio_on_load );
			function wps_wgm_check_which_radio_has_been_selected(radioVal){
				if (radioVal == "Mail to recipient") {
					$( "#wps_wgm_to_download" ).val( "" );
					$( "#wps_wgm_to_ship" ).val( "" );
					$( ".wps_wgm_delivery_via_admin" ).hide();
					$( ".wps_wgm_delivery_via_email" ).show();
					$( ".wps_wgm_delivery_via_buyer" ).hide();
					$( "#wps_wgm_to_email" ).attr( "readonly", false );
					$( "#wps_wgm_to_name_optional" ).attr( "readonly", false );
				} else if ( radioVal == "Downloadable" ) {
					$( "#wps_wgm_to_email" ).val( "" );
					$( "#wps_wgm_to_ship" ).val( "" );
					$( "#wps_wgm_to_name_optional" ).val( "" );
					$( ".wps_wgm_delivery_via_admin" ).hide();
					$( ".wps_wgm_delivery_via_email" ).hide();
					$( ".wps_wgm_delivery_via_buyer" ).show();
					$( "#wps_wgm_to_download" ).attr( "readonly", false );
				} else if ( radioVal == "shipping" ) {

					$( "#wps_wgm_to_email" ).val( "" );
					$( "#wps_wgm_to_download" ).val( "" );
					$( "#wps_wgm_to_name_optional" ).val( "" );
					$( "#wps_wgm_to_ship" ).attr( "readonly", false );
					$( ".wps_wgm_delivery_via_admin" ).show();
					$( ".wps_wgm_delivery_via_email" ).hide();
					$( ".wps_wgm_delivery_via_buyer" ).hide();
				}
			}

			$('body').on('click', '#wps_gift_this_product', function() {
				$(document).ajaxComplete(function() {
					var radio_on_load = $( "input[name='wps_wgm_send_giftcard']:checked" ).val();
					wps_wgm_check_which_radio_has_been_selected( radio_on_load );
					function wps_wgm_check_which_radio_has_been_selected(radioVal){
						if (radioVal == "Mail to recipient") {
							$( "#wps_wgm_to_download" ).val( "" );
							$( "#wps_wgm_to_ship" ).val( "" );
							$( ".wps_wgm_delivery_via_admin" ).hide();
							$( ".wps_wgm_delivery_via_email" ).show();
							$( ".wps_wgm_delivery_via_buyer" ).hide();
							$( "#wps_wgm_to_email" ).attr( "readonly", false );
							$( "#wps_wgm_to_name_optional" ).attr( "readonly", false );
						} else if ( radioVal == "Downloadable" ) {
							$( "#wps_wgm_to_email" ).val( "" );
							$( "#wps_wgm_to_ship" ).val( "" );
							$( "#wps_wgm_to_name_optional" ).val( "" );
							$( ".wps_wgm_delivery_via_admin" ).hide();
							$( ".wps_wgm_delivery_via_email" ).hide();
							$( ".wps_wgm_delivery_via_buyer" ).show();
							$( "#wps_wgm_to_download" ).attr( "readonly", false );
						} else if ( radioVal == "shipping" ) {

							$( "#wps_wgm_to_email" ).val( "" );
							$( "#wps_wgm_to_download" ).val( "" );
							$( "#wps_wgm_to_name_optional" ).val( "" );
							$( "#wps_wgm_to_ship" ).attr( "readonly", false );
							$( ".wps_wgm_delivery_via_admin" ).show();
							$( ".wps_wgm_delivery_via_email" ).hide();
							$( ".wps_wgm_delivery_via_buyer" ).hide();
						}
					}

					$( '#wps_uwgc_send_date' ).datepicker(
						{
							dateFormat : wps_uwgc_param.selected_date,
							minDate: 0
						}
					).datepicker( "setDate", "0" );
				});
			});

			$( 'body' ).on( 'change', '.wps_wgm_send_giftcard',
				function(){
					var radioVal = $( this ).val();
					wps_wgm_check_which_radio_has_been_selected( radioVal );
				}
			);
			// to check the image type for giftcard on single product page.
			jQuery( 'body' ).on( 'change', '#wps_uwgc_browse_img',
				function(){
					var error = false;
					var html = "<ul>";
					var image_br = jQuery( this ).val();
					var extension = image_br.substring( image_br.lastIndexOf( '.' ) + 1 ).toLowerCase();
					var all_ext = ["gif", "png", "jpeg", "jpg", "pjpeg", "x-png"];
					var exists = all_ext.indexOf( extension );
					if (exists == -1 ) {
						$( "#wps_wgm_error_notice" ).hide();
						error = true;
						$( "#wps_wgm_to_email" ).addClass( "wps_wgm_error" );
						html += "<li><b>";
						html += wps_uwgc_param.browse_error;
						html += "</li>";
						$( this ).val( "" );
					}
					if (error) {
						$( "#wps_wgm_error_notice" ).html( html );
						$( "#wps_wgm_error_notice" ).show();
						jQuery( 'html, body' ).animate(
							{
								scrollTop: jQuery( ".woocommerce-page" ).offset().top
							},
							800
						);
					}
				}
			);
			// appends discount price for giftcard.
			$( document ).on(
				'change',
				'#wps_wgm_price',
				function(){
					var wps_uwgc_price = $( this ).val();
					var product_id = wps_uwgc_param.product_id;
					var wps_wgm_discount = wps_uwgc_param.wps_wgm_discount;
					var discount_enable = wps_uwgc_param.discount_enable;
					var html = '';
					var new_price = '';
					$( document ).find( '.wps_wgm_price_content' ).remove();
					if (wps_wgm_discount == 'yes' && discount_enable == 'on') {

						block( $( '.summary.entry-summary' ) );
						var data = {
							action:'wps_uwgc_append_prices',
							wps_uwgc_price:wps_uwgc_price,
							product_id:product_id,
							wps_uwgc_nonce:wps_uwgc_param.wps_uwgc_nonce
						};
						$.ajax(
							{
								url: wps_uwgc_param.ajaxurl,
								type: "POST",
								data: data,
								dataType: 'json',
								success: function(response)
							{
									if (response.result == true) {
										var new_price = response.new_price;
										var wps_uwgc_price = response.wps_uwgc_price;
										var html = '';
										html += '<div class="wps_wgm_price_content"><b style="color:green;">' + wps_uwgc_param.discount_price_message + '</b>';
										html += '<b style="color:green;">' + new_price + '</b><br/>';
										html += '<b style="color:green;">' + wps_uwgc_param.coupon_message + '</b>';
										html += '<b style="color:green;">' + wps_uwgc_price + '</b></div>';
									}

									$( html ).insertAfter( $( 'p.price' ) );

								},
								complete: function()
							{
									unblock( $( '.summary.entry-summary' ) );
								}
							}
						);
					}
				}
			);

			// Giftcard balance checker js.
			$( document ).on(
				'click',
				'#wps_check_balance',
				function(){
					var email = $( '#gift_card_balance_email' ).val();
					var coupon = $( '#gift_card_code' ).val();
					$( "#wps_wgm_loader" ).show();
					var data = {
						action:'wps_uwgc_check_gift_balance',
						email:email,
						coupon:coupon,
						wps_uwgc_nonce:wps_uwgc_param.wps_uwgc_nonce
					};
					$.ajax(
						{
							url: wps_uwgc_param.ajaxurl,
							type: "POST",
							data: data,
							dataType :'json',
							success: function(response) {

								$( "#wps_wgm_loader" ).hide();
								if (response.result == true) {
									var html = response.html;
								} else {
									var message = response.message;
									var html = '<b style="color:red; margin-left:2%">' + message + '</b>';
								}
								$( "#wps_notification" ).html( html );
							}
						}
					);
				}
			);
			// send mail forcefully when click on Send Toaday Button.
			$( '.wps_uwgc_send_mail_force' ).click(
				function() {
					var order_id = $( this ).data( 'id' );
					var item_id = $( this ).data( 'num' );
					$( "#wps_uwgc_send_mail_force_notification_" + item_id ).html( "" );
					$( "#wps_wgm_loader" ).show();
					var data = {
						action:'wps_uwgc_send_mail_forcefully',
						order_id:order_id,
						item_id:item_id,
						wps_uwgc_nonce:wps_uwgc_param.wps_uwgc_nonce
					};
					$.ajax(
						{
							url: wps_uwgc_param.ajaxurl,
							type: "POST",
							data: data,
							dataType :'json',
							success: function(response)
						{
										 $( "#wps_wgm_loader" ).hide();
								if (response.result == true) {
									var message = response.message;
									var html = '<b style="color:green;">' + message + '</b>';
									$( '#wps_send_force_div_' + item_id ).hide();
								} else {
									var message = response.message;
									var html = '<b style="color:red;">' + message + '</b>';

								}
										$( "#wps_uwgc_send_mail_force_notification_" + item_id ).html( html );
							}
						}
					);
				}
			);

			// resend mail from order details page at front-end.
			$( '#wps_uwgc_resend_mail_button_frontend' ).click(
				function(e) {
					e.preventDefault();
					$( "#wps_uwgc_resend_mail_frontend_notification" ).html( "" );
					var order_id = $( this ).data( 'id' );
					$( "#wps_wgm_loader" ).show();
					var data = {
						action:'wps_uwgc_resend_mail_order_deatils_frontend',
						order_id:order_id,
						wps_uwgc_nonce:wps_uwgc_param.wps_uwgc_nonce
					};
					$.ajax(
						{
							url: wps_uwgc_param.ajaxurl,
							type: "POST",
							data: data,
							dataType :'json',
							success: function(response)
						{
										 $( "#wps_wgm_loader" ).hide();
								if (response.result == true) {
									var message = response.message;
									var html = '<b style="color:green;">' + message + '</b>';
								} else {
									var message = response.message;
									var html = '<b style="color:red;">' + message + '</b>';
								}
										$( "#wps_uwgc_resend_mail_frontend_notification" ).html( html );
							}
						}
					);
				}
			);

			$('#wps_gift_this_product').on( 'click', function() {
				if ( $(this).prop("checked") == true ) {
					var wps_product = $(this).data( 'product' );
					$.ajax({
						type: "POST",
						url: wps_wgm.ajaxurl,
						data: {action: "wps_get_data", wps_product: wps_product, wps_gc_nonce:wps_wgm.wps_gc_nonce},
						success: function(data) {
							$('#wps_purchase_as_a_gc').html(data);
						},
					});
				} else {
					$('#wps_purchase_as_a_gc').html('');
				}
			});

			if ( wps_uwgc_param.disable_from_field ) {
				$( '.wps_from' ).hide();
			}
			if ( wps_uwgc_param.disable_message_field ) {
				$( '.wps_message' ).hide();
			}
			if ( wps_uwgc_param.disable_to_email_field ) {
				$( '.wps_wgm_to_email' ).hide();
			} else {
				$( '.wps_wgm_to_email' ).show();
			}
		}
	);
})( jQuery );

// preview button validation.
var wps_wgm_preview_validation = function(html,error,form_Data){

	var html = '';
	var to_mail = '';
	var schedule_date = jQuery( document ).find( '#wps_uwgc_send_date' ).val();

	if (schedule_date != undefined) {
		if (schedule_date == null || schedule_date == '') {
			error = true;
			jQuery( document ).find( "#wps_uwgc_send_date" ).addClass( "wps_wgm_error" );
			html += "<li><b>";
			html += wps_uwgc_param.send_date;
			html += "<b></li>";
		}
	}

	var delivery_method = jQuery( document ).find( 'input[name="wps_wgm_send_giftcard"]:checked' ).val();

	// remove validation from to name field.
	if (wps_wgm_remove_validation_to_name() == 'on') {
		if (delivery_method == 'Mail to recipient') {
			to_mail = jQuery( document ).find( '#wps_wgm_to_name_optional' ).val();
			if (to_mail == null || to_mail == '') {
				to_mail = jQuery( "#wps_wgm_to_email" ).val();
			}
		}
	} else {
		if (delivery_method == 'Mail to recipient') {
			to_mail = jQuery( document ).find( '#wps_wgm_to_name_optional' ).val();
			if (to_mail == null || to_mail == '') {
				error = true;
				jQuery( document ).find( "#wps_wgm_to_name_optional" ).addClass( "wps_wgm_error" );
				html += "<li><b>";
				html += wps_uwgc_param.to_name;
				html += "<b></li>";
			}
		}
		if (delivery_method == 'shipping') {
			to_mail = jQuery( document ).find( '#wps_wgm_to_ship' ).val();
			if (to_mail == null || to_mail == '') {
				error = true;
				jQuery( document ).find( "#wps_wgm_to_ship" ).addClass( "wps_wgm_error" );
				html += "<li><b>";
				html += wps_wgm.to_empty_name;
				html += "<b></li>";
			}
		}
	}
	if (delivery_method == 'Downloadable') {
		to_mail = jQuery( document ).find( '#wps_wgm_to_download' ).val();
	}
	// remove validation  from to field.
	if (wps_wgm_remove_validation_to() == 'on') {

		if (delivery_method == 'shipping') {
			to_mail = jQuery( document ).find( '#wps_wgm_to_ship' ).val();
		}
	}
	if (jQuery( document ).find( '#wps_uwgc_browse_img' ).val() != undefined) {
		form_Data.append( 'file', jQuery( 'input[type=file]' )[0].files[0] );
	}

	var wps_wgm_preview_data = {'html':html, 'error':error,'to_mail':to_mail,'form_Data':form_Data, 'send_date':schedule_date};
	return wps_wgm_preview_data;
};

var wps_wgm_remove_validation_msg = function() {
	return wps_uwgc_param.remove_validation_msg;
};
var wps_wgm_remove_validation_from = function() {
	return wps_uwgc_param.remove_validation_from;
};
var wps_wgm_remove_validation_to = function() {
	return wps_uwgc_param.remove_validation_to;
};
var wps_wgm_remove_validation_to_name = function() {
	return wps_uwgc_param.remove_validation_to_name;
};
// add_to_card button validation.
var wps_wgm_add_to_card_validation = function(html,error) {
	var html = '';
	var to_mail = '';
	var delivery_method = jQuery( document ).find( 'input[name="wps_wgm_send_giftcard"]:checked' ).val();
	var enable_sms_notification = wps_uwgc_param.enable_sms_notification;
	// remove validation from to name field.
	if (wps_wgm_remove_validation_to_name() == 'on') {
		if (delivery_method == 'Mail to recipient') {
			to_mail = jQuery( document ).find( '#wps_wgm_to_name_optional' ).val();
			if (to_mail == null || to_mail == '') {
				to_mail = jQuery( "#wps_wgm_to_email" ).val();
			}
		}
	} else {
		if (delivery_method == 'Mail to recipient') {
			if (wps_uwgc_param.is_customizable != 'yes') {
				to_mail = jQuery( document ).find( '#wps_wgm_to_name_optional' ).val();
				if (to_mail == null || to_mail == '') {
					error = true;
					jQuery( document ).find( "#wps_wgm_to_name_optional" ).addClass( "wps_wgm_error" );
					html += "<li><b>";
					html += wps_uwgc_param.to_name;
					html += "<b></li>";
				}
			}
		}
	}
	if (delivery_method == 'Downloadable') {
		to_mail = jQuery( document ).find( '#wps_wgm_to_download' ).val();
	}
	// remove validation  from to field.
	if (wps_wgm_remove_validation_to() == 'on') {

		if (delivery_method == 'shipping') {
			to_mail = jQuery( document ).find( '#wps_wgm_to_ship' ).val();
		}
	}
	if ( enable_sms_notification == 'on') {
		var contact = jQuery( document ).find( '#wps_whatsapp_contact' ).val();
		if ( contact != null || contact != "") {
			var data = {
				action:'wps_wgm_validate_twilio_contact_number',
				wps_contact:contact,
				wps_uwgc_nonce:wps_uwgc_param.wps_uwgc_nonce
			};

			jQuery.ajax(
				{
					url: wps_uwgc_param.ajaxurl,
					type:'POST',
					async: false,
					cache: false,
					dataType:'json',
					data:data,
					success: function(response) {
						if (response.result == 'Invalid') {
							error = true;
							jQuery( document ).find( "#wps_whatsapp_contact" ).addClass( "wps_wgm_error" );
							html += "<li><b>";
							html += wps_uwgc_param.invalid_contact;
							html += "</li>";
						}
					}
				}
			);
		}
	}
	if ( wps_uwgc_param.is_addon_active != null && wps_uwgc_param.is_addon_active != '' ) {
		var response = wps_wgm_addon_validation( error );
		html += response.html;
		error = response.error;
	}
	var wps_wgm_add_to_cart_data = {'html':html, 'error':error,'to_mail':to_mail};
	return wps_wgm_add_to_cart_data;
};
var block = function( $node ) {
	if ( ! is_blocked( $node ) ) {
		$node.addClass( 'processing' ).block(
			{
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			}
		);
	}
};
var is_blocked = function( $node ) {
	return $node.is( '.processing' ) || $node.parents( '.processing' ).length;
};
var unblock = function( $node ) {
	$node.removeClass( 'processing' ).unblock();
};
