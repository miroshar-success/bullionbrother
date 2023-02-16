/**
 * All of the code for public-facing JavaScript source
 * should reside in this file.
 *
 * @package           woo-gift-cards-lite
 */

(function( $ ) {

	'use strict';
	jQuery( document ).ready(
		function($){

			$('#wps_wgm_price').keyup(function() {
				this.value = this.value.replace(/[^0-9]/g, '');
				var price = parseInt(this.value);
				if ( wps_wgm.pricing_type.type == 'wps_wgm_user_price' && price < wps_wgm.pricing_type.min_user_price ) {
					jQuery('.wps_wgm_min_user_price').show();
				} else {
					jQuery('.wps_wgm_min_user_price').hide();
				}
			});

			//variable price for giftcard.
			wps_wgm_variable_price_change_page_load(jQuery('#wps_wgm_price').val());
			jQuery('#wps_wgm_price').change(function(){

				if (wps_wgm.pricing_type.type == 'wps_wgm_variable_price') {

					var wps_wgm_price = jQuery(this).val();
					wps_wgm_variable_price_change(wps_wgm_price);
				}
			});

			function wps_wgm_variable_price_change(wps_wgm_price) {
				if (wps_wgm.pricing_type.type == 'wps_wgm_variable_price') {
					var product_id = wps_wgm.product_id;
				
					if ($('.summary.entry-summary').length > 0) {
						
					}
					
					var data = {
						action:'wps_wgm_append_variable_price',
						wps_wgm_price:wps_wgm_price,
						product_id:product_id,
						wps_nonce:wps_wgm.wps_wgm_nonce
					};
					$.ajax({
						url: wps_wgm.ajaxurl, 
						type: "POST",  
						data: data,
						dataType: 'json',
						success: function(response) 
						{
							if(response.result == true)
							{
								jQuery('#wps_wgm_text').html(response.new_price);
							} 
						},
						complete: function() 
						{
							if ($('.summary.entry-summary').length > 0) {
								
							}
						}
					});
				}
			}
			function wps_wgm_variable_price_change_page_load(wps_wgm_price) {
				if (wps_wgm.pricing_type.type == 'wps_wgm_variable_price') {
					var product_id = wps_wgm.product_id;
					var data = {
						action:'wps_wgm_append_variable_price',
						wps_wgm_price:wps_wgm_price,
						product_id:product_id,
						wps_nonce:wps_wgm.wps_wgm_nonce
					};
					$.ajax({
						url: wps_wgm.ajaxurl, 
						type: "POST",  
						data: data,
						dataType: 'json',
						success: function(response) 
						{
							if(response.result == true)
							{
								jQuery('#wps_wgm_text').html(response.new_price);
							} 
						},
						
					});
				}
			}

			$("#wps_wgm_price").attr( "min", 1);
			var check_elementor = $(document).find('.wps_wgm_added_wrapper').parents('.elementor-product-wgm_gift_card').length;
			if (check_elementor != 0) {
				if ($(document).find('.wps_wgm_added_wrapper').length) {
					$(document).find('.wps_wgm_added_wrapper').siblings().wrapAll('<div class="wps_wgm_elementor"></div>');
					var modified_div = $(document).find('.wps_wgm_elementor');
					$(document).find('.wps_wgm_added_wrapper').append(modified_div);
				}
			}

			$('body').on('click', '#wps_gift_this_product', function() {
				$(document).ajaxComplete(function() {
					var msg_length = $(document).find('#wps_wgm_message').val().length;
					$('.wps_box_char').text(msg_length);
				});
			});
			
			$(window).on( 'load', function() {
					var msg_length = $(document).find('#wps_wgm_message').val().length;
					$('.wps_box_char').text(msg_length);
				}
			);

			$("body").on( 'keyup', '#wps_wgm_message', 
				function(){
					var max_length = wps_wgm.msg_length;
					var msg_length = $(document).find('#wps_wgm_message').val().length;
					var html = '<ul>';
					var error = false;
					if ( msg_length > max_length ) {
						this.value = this.value.substring( 0, max_length );
						error = true;
						$("#wps_wgm_message").addClass("wps_gw_error");
						html+="<li><b>";
						html+=wps_wgm.msg_length_err;
						html+="</li>";
					}
					if(msg_length == 0){
						$('.wps_box_char').text(0);
					}
					else if( msg_length > max_length ){
						$('.wps_box_char').text(max_length);
					} else {
						$('.wps_box_char').text(msg_length);
					}

					html += "</ul>";
					if(error)
					{
						$("#wps_wgm_error_notice").html(html);
						$("#wps_wgm_error_notice").show();
						jQuery('html, body').animate({
							scrollTop: jQuery(".woocommerce-page").offset().top
						}, 800);
					} else {
						$("#wps_wgm_error_notice").hide();
					}
				}
			);


			/*Js for select template on single product page*/
			$( 'body' ).on( 'click', '.wps_wgm_featured_img',
				function(){
					$( '.wps_wgm_selected_template' ).find( '.wps_wgm_featured_img' ).removeClass( 'wps_wgm_pre_selected_temp' );
					var img_id = $( this ).attr( 'id' );
					$( '#' + img_id ).addClass( 'wps_wgm_pre_selected_temp' );
					$( '#wps_wgm_selected_temp' ).val( img_id );
				}
			);

			/*
			Adds the Validation for some required fields for Single Product Page.
			*/
			jQuery( "body" ).on( 'click', '.single_add_to_cart_button',
				function(e){
					if ( ( typeof wps_wgm.pricing_type.type != 'undefined' || $( '#wps_gift_this_product' ).prop("checked") == true ) ) {
						e.preventDefault();
						$( "#wps_wgm_error_notice" ).hide();
						var from_mail = $( "#wps_wgm_from_name" ).val();
						var message = $( "#wps_wgm_message" ).val();
						message = message.trim();
						var price = $( "#wps_wgm_price" ).val();
						var error = false;
						var product_type = wps_wgm.pricing_type.type;
						var html = "";
						var to_mail = '';
						var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,5})+$/;
						html = "<ul>";

						var delivery_method = jQuery( document ).find( 'input[name="wps_wgm_send_giftcard"]:checked' ).val();
						
						// remove validation from to fields.
						if (wps_wgm.is_pro_active != null && wps_wgm.is_pro_active != '' && wps_wgm_remove_validation_to() == 'on') {
							if (delivery_method == 'Mail to recipient') {
								to_mail = $( "#wps_wgm_to_email" ).val();
								error = false;
							}
							if (delivery_method == 'Downloadable') {
								to_mail = $( "#wps_wgm_to_download" ).val();
								error = false;
							}
						} else {
							if (delivery_method == 'Mail to recipient') {
								to_mail = $( "#wps_wgm_to_email" ).val();
								if (to_mail == null || to_mail == "") {
									error = true;
									$( "#wps_wgm_to_email" ).addClass( "wps_wgm_error" );
									html += "<li><b>";
									html += wps_wgm.to_empty;
									html += "</li>";
								} else if ( ! to_mail.match( mailformat )) {
									error = true;
									$( "#wps_wgm_to_email" ).addClass( "wps_wgm_error" );
									html += "<li><b>";
									html += wps_wgm.to_invalid;
									html += "</li>";
								}
							}
						}

						if (wps_wgm.is_pro_active != null && wps_wgm.is_pro_active != '' && wps_wgm_remove_validation_to_name() == 'on') {
							
						} else {
							if (delivery_method == 'Downloadable') {
								to_mail = $( "#wps_wgm_to_download" ).val();
								if (to_mail == null || to_mail == "") {
									error = true;
									$( "#wps_wgm_to_download" ).addClass( "wps_wgm_error" );
									html += "<li><b>";
									html += wps_wgm.to_empty_name;
									html += "</li>";
								}
							}
							if (delivery_method == 'shipping') {
								to_mail = $( "#wps_wgm_to_ship" ).val();
								if (to_mail == null || to_mail == "") {
									error = true;
									$( "#wps_wgm_to_ship" ).addClass( "wps_wgm_error" );
									html += "<li><b>";
									html += wps_wgm.to_empty_name;
									html += "</li>";
								}
							}
						}

						if (price == null || price == "") {
							error = true;
							$( "#wps_wgm_price" ).addClass( "wps_wgm_error" );
							html += "<li><b>";
							html += wps_wgm.price_field;
							html += "</li>";
						}
						// Remove validation from field.
						if (wps_wgm.is_pro_active != null && wps_wgm.is_pro_active != '' && wps_wgm_remove_validation_from() == 'on') {
							
						} else {
							if (from_mail == null || from_mail == "") {
								error = true;
								$( "#wps_wgm_from_name" ).addClass( "wps_wgm_error" );
								html += "<li><b>";
								html += wps_wgm.from_empty;
								html += "</li>";
							}
						}
						// for validation from message field.
						if (wps_wgm.is_pro_active != null && wps_wgm.is_pro_active != '' && wps_wgm_remove_validation_msg() == 'on') {
							
						} else {
							if (message == null || message == "") {
								error = true;
								$( "#wps_wgm_message" ).addClass( "wps_wgm_error" );
								html += "<li><b>";
								html += wps_wgm.msg_empty;
								html += "</li>";
							} else if ( message.length > wps_wgm.msg_length ) {
								error = true;
								$( "#wps_wgm_message" ).addClass( "wps_wgm_error" );
								html += "<li><b>";
								html += wps_wgm.msg_length_err;
								html += "</li>";
							}
						}

						if (product_type == "wps_wgm_range_price") {
							var from = parseInt( wps_wgm.pricing_type.from );
							var to = parseInt( wps_wgm.pricing_type.to );

							to = parseFloat( to );
							from = parseFloat( from );
							price = parseFloat( price );

							if (price > to || price < from) {
								error = true;
								$( "#wps_wgm_price" ).addClass( "wps_wgm_error" );
								html += "<li><b>";
								html += wps_wgm.price_range;
								html += "</li>";
							}
						}
						if (product_type == 'wps_wgm_user_price' ) {
							price = parseInt( price );
							if ( price < wps_wgm.pricing_type.min_user_price ) {
								error = true;
								html += "<li><b>";
								html += wps_wgm.min_user_price;
								html += "</li>";
							}
						}
						// if pro is active.
						if (wps_wgm.is_pro_active != null && wps_wgm.is_pro_active != '') {
							var response = wps_wgm_add_to_card_validation( html,error );
							error = response.error;
							html += response.html;
							to_mail = response.to_mail;
						}
						html += "</ul>";
						if (error) {
							$( "#wps_wgm_error_notice" ).html( html );
							$( "#wps_wgm_error_notice" ).show();
							jQuery( 'html, body' ).animate(
								{
									scrollTop: jQuery( ".woocommerce-page" ).offset().top
								},
								800
							);
							$( ".single_add_to_cart_button" ).removeClass( "loading" );
						} else {
							$( "#wps_wgm_error_notice" ).html( "" );
							$( "#wps_wgm_error_notice" ).hide();
							$( this ).closest( "form.cart" ).submit();
							return true;
						}
					}
				}
			);

			/* Adds the Preview Validtion Here*/

			$( 'body' ).on('click', '#mwg_wgm_preview_email',
				function() {
					var form_Data = new FormData();
					
					$( "#wps_wgm_error_notice" ).hide();
					var from_mail = $( "#wps_wgm_from_name" ).val();
					var message = $( "#wps_wgm_message" ).val();
					message = message.trim();
					var regex = /(<([^>]+)>)/ig;
					var message = message.replace( regex,'' );
					var price = $( "#wps_wgm_price" ).val();
					var error = false;
					var product_type = wps_wgm.pricing_type.type;
					var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,5})+$/;
					var to_mail = '';
					var send_date = '';
					var html = "<ul>";
					var delivery_method = jQuery( document ).find( 'input[name="wps_wgm_send_giftcard"]:checked' ).val();
					// remove validation from to fields.
					if (wps_wgm.is_pro_active != null && wps_wgm.is_pro_active != '' && wps_wgm_remove_validation_to() == 'on') {
						if (delivery_method == 'Mail to recipient') {
							to_mail = $( "#wps_wgm_to_email" ).val();
							error = false;
						}
						if (delivery_method == 'Downloadable') {
							to_mail = $( "#wps_wgm_to_download" ).val();
							error = false;
						}
					} else {
						if (delivery_method == 'Mail to recipient') {
								to_mail = $( "#wps_wgm_to_email" ).val();
							if (to_mail == null || to_mail == "") {
								error = true;
								$( "#wps_wgm_to_email" ).addClass( "wps_wgm_error" );
								html += "<li><b>";
								html += wps_wgm.to_empty;
								html += "</li>";
							} else if ( ! to_mail.match( mailformat )) {
								error = true;
								$( "#wps_wgm_to_email" ).addClass( "wps_wgm_error" );
								html += "<li><b>";
								html += wps_wgm.to_invalid;
								html += "</li>";
							}
						}
					}

					if (wps_wgm.is_pro_active != null && wps_wgm.is_pro_active != '' && wps_wgm_remove_validation_to_name() == 'on') {

					} else {
						if (delivery_method == 'Downloadable') {
							to_mail = $( "#wps_wgm_to_download" ).val();
							if (to_mail == null || to_mail == "") {
								error = true;
								$( "#wps_wgm_to_download" ).addClass( "wps_wgm_error" );
								html += "<li><b>";
								html += wps_wgm.to_empty_name;
								html += "</li>";
							}
						}
					}
					
					if (price == null || price == "") {
						error = true;
						$( "#wps_wgm_price" ).addClass( "wps_wgm_error" );
						html += "<li><b>";
						html += wps_wgm.price_field;
						html += "</li>";
					}
					// remove validation from field.
					if (wps_wgm.is_pro_active != null && wps_wgm.is_pro_active != '' && wps_wgm_remove_validation_from() == 'on') {
						
					} else {
						if (from_mail == null || from_mail == "") {
								error = true;
								$( "#wps_wgm_from_name" ).addClass( "wps_wgm_error" );
								html += "<li><b>";
								html += wps_wgm.from_empty;
								html += "</li>";
						}
					}
					// for validation from message.
					if (wps_wgm.is_pro_active != null && wps_wgm.is_pro_active != '' && wps_wgm_remove_validation_msg() == 'on') {
						
					} else {
						if (message == null || message == "") {
								error = true;
								$( "#wps_wgm_message" ).addClass( "wps_wgm_error" );
								html += "<li><b>";
								html += wps_wgm.msg_empty;
								html += "</li>";
						} else if ( message.length > wps_wgm.msg_length ) {
							error = true;
							$( "#wps_wgm_message" ).addClass( "wps_wgm_error" );
							html += "<li><b>";
							html += wps_wgm.msg_length_err;
							html += "</li>";
						}
					}

					if (product_type == "wps_wgm_range_price") {
						var from = wps_wgm.pricing_type.from;
						var to = wps_wgm.pricing_type.to;
						to = parseFloat( to );
						from = parseFloat( from );
						price = parseFloat( price );

						if (price > to || price < from) {
							error = true;
							$( "#wps_wgm_price" ).addClass( "wps_wgm_error" );
							html += "<li><b>";
							html += wps_wgm.price_range;
							html += "</li>";
						}
					}
					if (product_type == 'wps_wgm_user_price' ) {
						price = parseInt( price );
						if ( price < wps_wgm.pricing_type.min_user_price ) {
							error = true;
							html += "<li><b>";
							html += wps_wgm.min_user_price;
							html += "</li>";
						}
					}
					// if pro is active.
					if (wps_wgm.is_pro_active != null && wps_wgm.is_pro_active != '') {
						var response = wps_wgm_preview_validation( html,error,form_Data );
						error = response.error;
						html += response.html;
						to_mail = response.to_mail;
						form_Data = response.form_Data;
						send_date = response.send_date;
					
					}
					html += "</ul>";
					if (error) {
						$( "#wps_wgm_error_notice" ).html( html );
						$( "#wps_wgm_error_notice" ).show();
						// WPS code for woodmart theme.
						$( "#wps_wgm_error_notice" ).removeClass( 'hidden-notice' );
						// WPS code for woodmart theme.
						jQuery( 'html, body' ).animate(
							{
								scrollTop: jQuery( ".woocommerce-page" ).offset().top
							   },
							800
						);
					} else {

						var product_id = wps_wgm.product_id;
						var tempId = $( document ).find( '#wps_wgm_selected_temp' ).val();
						form_Data.append( 'action', 'wps_wgc_preview_thickbox_rqst' );
						form_Data.append( 'wps_nonce', wps_wgm.wps_wgm_nonce );
						form_Data.append( 'price', price );
						form_Data.append( 'from', from_mail );
						form_Data.append( 'to', to_mail );
						form_Data.append( 'message', message );
						form_Data.append( 'product_id', product_id );
						form_Data.append( 'tempId', tempId );
						form_Data.append( 'send_date', send_date );
						if ( wps_wgm.is_pro_active ) {
							form_Data.append( 'delivery_method', delivery_method );
						}
						
						$.ajax(
							{
								url: wps_wgm.ajaxurl,
								type: "POST",
								data: form_Data,
								processData: false,
								contentType: false,
								success: function(response)
								{
									$( "#mwg_wgm_preview_email" ).show();
									tb_show( "", response );
								}
							}
						);
					}
				}
			);
			
			$( 'body' ).on( 'click', '#wps_recharge_wallet_giftcard',
				function() {
					$( '.error' ).hide();
					var wps_gc_code = $( '#wps_giftcard_code' ).val();
					var wps_wgm_nonce = wps_wgm.wps_wgm_nonce;
					$.ajax({
						url: wps_wgm.ajaxurl,
						type: 'POST',
						data: { wps_gc_code : wps_gc_code, wps_wgm_nonce: wps_wgm_nonce, action: 'wps_recharge_wallet_via_giftcard' },
						dataType: 'json',
						success: function( response ) {
							if ( response['status'] == 'success' ){
								$( '.success' ).css( 'color', 'green' );
								$( '.success' ).html( 'Wallet Recharge with amount of ' + wps_wgm.wps_currency + response['message'] );
								setTimeout(location.reload.bind(location), 3000);
							} else if( response['status'] == 'failed' ) {
								$( '.error' ).show();
								$( '.error' ).html( response['message'] );
							}
						}
					});
				}
			);

			$( '#wps_giftcard_code' ).keyup(function() {
				$( '.error' ).hide();
			});
		}
	);

})( jQuery );
