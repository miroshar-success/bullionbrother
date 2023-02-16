/* global kadenceProWooConfig */
/**
 * File Shop-init.js.
 * Gets Shop toggle working.
 */

(function() {
	'use strict';
	window.kadenceProWoo = {
		/**
		 * Find the cart and open it.
		 */
		triggerCart: function() {
			var drawerCartToggle = document.querySelector('*[data-toggle-target="#cart-drawer"]' );
			// No point if no drawers.
			if ( ! drawerCartToggle ) {
				return;
			}
			window.kadence.toggleDrawer( drawerCartToggle );
		},
		/**
		 * Initiate the script to toggle cart when product is added.
		 */
		initCartToggle: function() {
			jQuery( document.body ).on( 'added_to_cart', function() {
				window.kadenceProWoo.triggerCart();
			} );
			if ( kadenceProWooConfig.openCart ) {
				window.kadenceProWoo.triggerCart();
			}
		},
		// Initiate the menus when the DOM loads.
		init: function() {
			window.kadenceProWoo.initCartToggle();
		}
	}
	if ( 'loading' === document.readyState ) {
		// The DOM has not yet been loaded.
		document.addEventListener( 'DOMContentLoaded', window.kadenceProWoo.init );
	} else {
		// The DOM has already been loaded.
		window.kadenceProWoo.init();
	}
})();
