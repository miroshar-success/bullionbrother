/**
 * File kadence-sticky-add-to-cart.js.
 * Gets sticky add to cart working.
 */

(function() {
	'use strict';
	window.kadenceStickyAddToCart = {
		/**
		 * Get element's offset.
		 */
		getOffset: function( el ) {
			if ( el instanceof HTMLElement ) {
				var rect = el.getBoundingClientRect();

				return {
					top: rect.top + window.pageYOffset,
					left: rect.left + window.pageXOffset
				}
			}

			return {
				top: null,
				left: null
			};
		},
		/**
		 * Find the sticky items.
		 */
		stickScrollItem: function( element ) {
			var updateSticky = function( e ) {
				var offsetTop = window.kadenceStickyAddToCart.getOffset( document.getElementById( 'wrapper' ) ).top,
				scrollOffset = window.kadenceStickyAddToCart.getOffset( document.querySelector( '.product .single_add_to_cart_button' ) ).top;
				if ( window.scrollY >= scrollOffset ) {
					element.style.top = offsetTop + 'px';
					element.classList.add('item-is-fixed');
					element.classList.add('item-is-stuck');
					element.classList.remove('item-at-start');
				} else {
					if ( element.classList.contains( 'item-is-fixed' ) ) {
						element.classList.remove( 'item-is-fixed' );
						element.classList.remove('item-at-start');
						element.classList.remove('item-is-stuck');
						element.style.top = null;
					}
				}
			}
			window.addEventListener( 'resize', updateSticky, false );
			window.addEventListener( 'scroll', updateSticky, false );
			window.addEventListener( 'load', updateSticky, false );
			updateSticky();
		},
		/**
		 * Find the sticky items.
		 */
		stickScrollFooterItem: function( element ) {
			var updateSticky = function( e ) {
				var scrollOffset = window.kadenceStickyAddToCart.getOffset( document.querySelector( '.product .single_add_to_cart_button' ) ).top;
				if ( window.scrollY >= scrollOffset ) {
					element.classList.add('item-is-fixed');
					element.classList.add('item-is-stuck');
					element.classList.remove('item-at-start');
				} else {
					if ( element.classList.contains( 'item-is-fixed' ) ) {
						element.classList.remove( 'item-is-fixed' );
						element.classList.remove('item-at-start');
						element.classList.remove('item-is-stuck');
					}
				}
			}
			window.addEventListener( 'resize', updateSticky, false );
			window.addEventListener( 'scroll', updateSticky, false );
			window.addEventListener( 'load', updateSticky, false );
			updateSticky();
		},
		/**
		 * Find the sticky items.
		 */
		initStickyScrollItems: function() {
			var stickyScrollItems = document.querySelectorAll( '.kadence-sticky-add-to-cart-header' );
			// No point if no drawers.
			if ( ! stickyScrollItems.length ) {
				return;
			}
			for ( let i = 0; i < stickyScrollItems.length; i++ ) {
				window.kadenceStickyAddToCart.stickScrollItem( stickyScrollItems[ i ] );
			}
		},
		/**
		 * Find the sticky items.
		 */
		initStickyScrollFooterItems: function() {
			var stickyScrollFooterItems = document.querySelectorAll( '.kadence-sticky-add-to-cart-footer' );
			// No point if no drawers.
			if ( ! stickyScrollFooterItems.length ) {
				return;
			}
			for ( let i = 0; i < stickyScrollFooterItems.length; i++ ) {
				window.kadenceStickyAddToCart.stickScrollFooterItem( stickyScrollFooterItems[ i ] );
			}
		},
		// Initiate sticky when the DOM loads.
		init: function() {
			window.kadenceStickyAddToCart.initStickyScrollItems();
			window.kadenceStickyAddToCart.initStickyScrollFooterItems();
		}
	}
	if ( 'loading' === document.readyState ) {
		// The DOM has not yet been loaded.
		document.addEventListener( 'DOMContentLoaded', window.kadenceStickyAddToCart.init );
	} else {
		// The DOM has already been loaded.
		window.kadenceStickyAddToCart.init();
	}
})();