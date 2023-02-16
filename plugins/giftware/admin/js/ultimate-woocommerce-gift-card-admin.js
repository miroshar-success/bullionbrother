/**
 * All of the code for javascript on your admin-facing JavaScript source
 * should reside in this file.
 *
 * @package           Ultimate Woocommerce Gift Cards
 */

(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 var ajax_url = wps_wgm_params.ajax_url;
	$( document ).ready(
		function(){
			$( "#wps_wgm_product_setting_include_category" ).select2();
			$( "#wps_wgm_general_setting_giftcard_payment" ).select2();
			jQuery( "#wps_wgm_offline_gift_preview" ).click(
				function(e){
					var error = true;
					var to_mail = jQuery( "#wps_wgm_offline_gift_to" ).val().trim();
					var from_mail = jQuery( "#wps_wgm_offline_gift_from" ).val().trim();
					var price = jQuery( "#wps_wgm_offline_gift_amount" ).val().trim();
					var message = jQuery( "#wps_wgm_offline_gift_message" ).val().trim();
					var product_id = jQuery( "#wps_wgm_offline_gift_template" ).val();
					var gift_manual_code = jQuery( "#wps_wgm_offline_gift_coupon_manual" ).val();
					if (price == null || price == "") {
						  error = false;
						  jQuery( "#wps_wgm_offline_gift_amount" ).addClass( "wps_wgm_error" );
					} else {
						 jQuery( "#wps_wgm_offline_gift_amount" ).removeClass( "wps_wgm_error" );
					}

					if (to_mail == null || to_mail == "") {
						error = false;
						jQuery( "#wps_wgm_offline_gift_to" ).addClass( "wps_wgm_error" );
					} else {
						jQuery( "#wps_wgm_offline_gift_to" ).removeClass( "wps_wgm_error" );
					}
					if (from_mail == null || from_mail == "") {
						error = false;
						jQuery( "#wps_wgm_offline_gift_from" ).addClass( "wps_wgm_error" );
					} else {
						jQuery( "#wps_wgm_offline_gift_from" ).removeClass( "wps_wgm_error" );
					}
					if (message == null || message == "") {
						error = false;
						jQuery( "#wps_wgm_offline_gift_message" ).addClass( "wps_wgm_error" );
					} else {
						jQuery( "#wps_wgm_offline_gift_message" ).removeClass( "wps_wgm_error" );
					}
					if (product_id == null || product_id == "") {
						error = false;
						jQuery( "#wps_wgm_offline_gift_template" ).addClass( "wps_wgm_error" );
					} else {
						jQuery( "#wps_wgm_offline_gift_template" ).removeClass( "wps_wgm_error" );
					}
					var send_date = $( "#wps_wgm_offline_gift_schedule" ).val();
					if (error) {
						var data = {
							action:'wps_uwgc_offline_preview',
							price:price,
							to:to_mail,
							from:from_mail,
							message:message,
							product_id:product_id,
							send_date:send_date,
							gift_manual_code:gift_manual_code,
							wps_nonce:wps_wgm_params.wps_wgm_nonce
						};
						$.ajax(
							{
								url: ajax_url,
								type: "POST",
								data: data,
								success: function(response)
								{
									tb_show( "", response );
								}
							}
						);
					}
				}
			);
			jQuery( ".wps_wgm_offline_resend_mail" ).click(
				function(){
					jQuery( "#wps_wgm_loader" ).show();
					var id = jQuery( this ).data( "id" );
					var current = jQuery( this );
					var data = {
						action:'wps_uwgc_offline_resend_mail',
						id:id,
						wps_nonce:wps_wgm_params.wps_wgm_nonce
					};
					$.ajax(
						{
							url: wps_wgm_params.ajax_url,
							type: "POST",
							data: data,
							dataType: 'json',
							success: function(response)
							{
								jQuery( "#wps_wgm_loader" ).hide();
								if (response.result == true) {
									var message = response.message;
									var html = '<b style="color:green;">' + message + '</b>';
								} else {
									var message = response.message;
									var html = '<b style="color:red;">' + message + '</b>';
								}
									current.next().html( html );
							}
						}
					);
				}
			);

			jQuery( '#wps_wgm_offline_gift_save' ).click(
				function(e){
					var error = true;
					var to_mail = jQuery( "#wps_wgm_offline_gift_to" ).val().trim();
					var from_mail = jQuery( "#wps_wgm_offline_gift_from" ).val().trim();
					var price = jQuery( "#wps_wgm_offline_gift_amount" ).val().trim();
					var message = jQuery( "#wps_wgm_offline_gift_message" ).val().trim();
					var product_id = jQuery( "#wps_wgm_offline_gift_template" ).val();
					var gift_manual_code = jQuery( "#wps_wgm_offline_gift_coupon_manual" ).val();
					if (price == null || price == "") {
						  error = false;
						  jQuery( "#wps_wgm_offline_gift_amount" ).addClass( "wps_wgm_error" );
					} else {
						 jQuery( "#wps_wgm_offline_gift_amount" ).removeClass( "wps_wgm_error" );
					}

					if (to_mail == null || to_mail == "") {
						error = false;
						jQuery( "#wps_wgm_offline_gift_to" ).addClass( "wps_wgm_error" );
					} else {
						jQuery( "#wps_wgm_offline_gift_to" ).removeClass( "wps_wgm_error" );
					}
					if (from_mail == null || from_mail == "") {
						error = false;
						jQuery( "#wps_wgm_offline_gift_from" ).addClass( "wps_wgm_error" );
					} else {
						jQuery( "#wps_wgm_offline_gift_from" ).removeClass( "wps_wgm_error" );
					}
					if (message == null || message == "") {
						error = false;
						jQuery( "#wps_wgm_offline_gift_message" ).addClass( "wps_wgm_error" );
					} else {
						jQuery( "#wps_wgm_offline_gift_message" ).removeClass( "wps_wgm_error" );
					}
					if (product_id == null || product_id == "") {
						error = false;
						jQuery( "#wps_wgm_offline_gift_template" ).addClass( "wps_wgm_error" );
					} else {
						jQuery( "#wps_wgm_offline_gift_template" ).removeClass( "wps_wgm_error" );
					}
					if ( ! error) {
						e.preventDefault();
					}
				}
			);
			jQuery( '#wps_wgm_offline_gift_schedule' ).datepicker(
				{
					dateFormat : wps_wgm_params.dateformat,
					minDate: 0
				}
			).datepicker( "setDate", "0" );

			jQuery( '#wps_wgm_offline_gift_coupon_manual' ).on(
				'change',
				function(){
					var wps_uwgc_manual_code = jQuery( "#wps_wgm_offline_gift_coupon_manual" ).val();
					var html_err = '<span style="color:red;">Gift Coupon Code already exist! Try another</span>';
					var html_succ = '<span style="color:green;">Valid Code</span>';
					if (wps_uwgc_manual_code !== null) {
						$.ajax(
							{
								url:ajax_url,
								type:"POST",
								dataType :'json',
								data:{
									action:'wps_uwgc_check_manual_code_exist',
									wps_manual_code:wps_uwgc_manual_code,
									wps_nonce:wps_wgm_params.wps_wgm_nonce
								},success : function(response){
									if (response.result == 'invalid') {
										$( "#wps_wgm_invalid_code_notice" ).html( html_err );
									} else if (response.result == 'valid') {
										$( "#wps_wgm_invalid_code_notice" ).html( html_succ );
									}
								}
							}
						);
					}

				}
			);

			jQuery( "#wps_uwgc_coupon_mail_setting" ).click(
				function(){
					jQuery( "#wps_uwgc_coupon_mail_setting_wrapper" ).slideToggle();
				}
			);
			jQuery(document).on('change','#wps_wgm_enable_sms_notification',function()
		    {
		      if(jQuery(this).prop("checked") == true){
		        jQuery(document).find(".twilo_credentials").show();
		      }
		      else{
		        jQuery(document).find(".twilo_credentials").hide();
		      }
		    }); 
		    if(jQuery('#wps_wgm_enable_sms_notification').prop("checked") == true){
		      jQuery(document).find(".twilo_credentials").show();
		    }
		}
	);
})( jQuery );
