/**
 * All of the javascript code for whatsapp sharing should reside in this file.
 *
 * @package           Ultimate Woocommerce Gift Cards
 */

jQuery( document ).ready(
	function(){
		if ( jQuery( document ).find( '.wps_whatsapp_share' ).length > 0) {
			var newUrl = jQuery( document ).find( '.wps_whatsapp_share' ).attr( 'href' );
			if ( newUrl != '') {
				window.setTimeout(
					function(){
						var newTab = window.open( newUrl, '_blank' );
					},
					2000
				);
			}
		}
	}
);
