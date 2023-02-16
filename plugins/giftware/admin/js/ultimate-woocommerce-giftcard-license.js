/**
 * All of the code for license for giftcard
 * should reside in this file.
 *
 * @package           Ultimate Woocommerce Gift Cards
 */

var ajaxurl,nonce;
jQuery( document ).ready(
	function() {

		nonce = license_ajax_object.license_nonce;
		ajaxurl = license_ajax_object.ajaxurl;
		jQuery( 'div#wps_uwgc-ajax-loading-gif' ).hide();

		// On License form submit.
		jQuery( '#wps_uwgc-license-activate' ).on(
			'click',
			function() {
				jQuery( 'div#wps_uwgc-ajax-loading-gif' ).css( 'display', 'inline-block' );
				var license_key = jQuery( 'input#wps_uwgc-license-key' ).val();
				wps_uwgc_license_request( license_key );
			}
		);
		function wps_uwgc_license_request( license_key ) {

			jQuery.ajax(
				{

					type:'POST',
					dataType: 'json',
					url: ajaxurl,
					data: {
						'action': 'validate_license_handle',
						'wps_uwgc_purchase_code': license_key,
						'wps_uwgc-license-nonce': nonce,
					},

					success:function( data ) {

						jQuery( 'div#wps_uwgc-ajax-loading-gif' ).hide();

						if ( false === data.status ) {

							jQuery( "p#wps_uwgc-license-activation-status" ).css( "color", "#ff3333" );
						} else {

							jQuery( "p#wps_uwgc-license-activation-status" ).css( "color", "#42b72a" );
						}

						jQuery( 'p#wps_uwgc-license-activation-status' ).html( data.msg );

						if ( true === data.status ) {

							setTimeout(
								function() {
									window.location = license_ajax_object.reloadurl;
								},
								500
							);
						}
					}
				}
			);
		}
	}
);
