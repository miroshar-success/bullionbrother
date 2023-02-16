/**
 * All of the code for notices on your admin-facing JavaScript source
 * should reside in this file.
 *
 * @package          Ultimate Woocommerce Gift Cards
 */

(function( $ ) {
	'use strict';

	$( document ).ready(
		function(){

			if ( wps_custom.pricing_type == 'wps_wgm_variable_price' ) {
				var price = $( '#wps_wgm_price option:selected' ).val();
				var currency = wps_custom.currency;
				$( '.wps-cgw-pro-price' ).html( currency + price);
			}

			// Append Image Src to the Preview.
			$( '.wps_cgw_choose_img' ).click(
				function(){
					var img_src = $( this ).prop( 'src' );
					$( '.wps_cgw_preview_image' ).attr( 'src', img_src );
					var selected_img = $( this ).data( 'img' );
					$( '#selected_image' ).val( selected_img );
					$( "#uploaded_image_value" ).val( selected_img );
						$.ajax(
							{
								method: "POST",
								url: wps_custom.ajaxurl,
								data: {
									'action': 'wps_cgc_admin_uploads_name',
									'image_name': img_src,
									'wps_nonce' : wps_custom.wps_nonce 
								},
								success: function(response) {
								}
							}
						);
				}
			);

			// Append Price Value to the Email Template's Price.
			$( document ).on(
				'change',
				'.wps_wgm_price',
				function(){
					var price = $( '.wps_wgm_price' ).val();
					var decimal_price = price;
					var currency = wps_custom.currency;
					$( '.wps-cgw-pro-price' ).html( currency + decimal_price );
				}
			);

			// Append Gift Mesage to the Email Template's Preview.
			$( document ).on(
				'change',
				'#wps_wgm_message',
				function(){
					var message = $( '#wps_wgm_message' ).val();
					$( '.wps-cgw-gift-content' ).html( message );
				}
			);

			// Append From Name to the Email Template's Preview.
			$( '#wps_wgm_from_name' ).change(
				function(){
					var from = $( '#wps_wgm_from_name' ).val();
					$( '.wps_wgm_from_name' ).html( from );
				}
			);

			// Append the value of select price to one input hidden field.
			$( '.wps_cgc_price_button' ).click(
				function(){
					$( '.wps_wgm_price_select' ).val( $( this ).val() );
					var price = $( this ).val();
					var decimal_price = price;
					var currency = wps_custom.currency;
					$( '.wps-cgw-pro-price' ).html( currency + decimal_price );

					// add active class to curreent button and remove same class from other siblings.
					$( this ).siblings( 'input' ).removeClass( 'active' ).end().addClass( 'active' );

				}
			);

			// Append uploaded image to the Required DIV.
			$( '#wps_cgw_upload_img' ).change(
				function(){
					$( "#wps_wgm_loader" ).show();
					var formData = new FormData();
					formData.append( 'file', $( 'input[type=file]' )[0].files[0] );
					formData.append( 'action', 'wps_cgc_upload_own_img' );
					formData.append( 'wps_nonce', wps_custom.wps_nonce );
					var img_name = $( 'input[type=file]' )[0].files[0]['name'];
					var img_path = $( '#upload_path' ).val();
					$.ajax(
						{
							url: wps_custom.ajaxurl,
							type: "POST",
							data: formData,
							processData: false,
							contentType: false,
							dataType: 'json',
							success: function(response) {
								$( "#wps_wgm_loader" ).hide();
								if (response.result == true) {
									$( '.wps_cgw_preview_image' ).attr( 'src', img_path + '/cgw_own_img/' + img_name );
								}
							}
						}
					);
				}
			);
		}
	);

})( jQuery );
