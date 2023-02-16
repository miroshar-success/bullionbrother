/* global kadenceProMegaConfig */
/**
 * File kadence-mega-menu.js.
 * Gets mega menu working.
 */

(function() {
	'use strict';
	window.kadenceProSticky = {
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
		stickHeadItem: function( element ) {
			var offsetTop = parseInt( window.kadenceProSticky.getOffset( document.getElementById( 'wrapper' ) ).top ),
				activeOffsetTop = parseInt( window.kadenceProSticky.getOffset( element ).top );
			var updateSticky = function( e ) {
				var parent = element.parentNode;
				parent.style.height = element.offsetHeight + 'px';
				if ( Math.max(0, window.scrollY ) === Math.floor( activeOffsetTop - offsetTop ) ) {
					element.style.top = offsetTop + 'px';
					element.classList.add('item-is-fixed');
					element.classList.add('item-at-start');
					element.classList.remove('item-is-stuck');
					parent.classList.add('child-is-fixed');
				} else if ( Math.max(0, window.scrollY ) > Math.floor( activeOffsetTop - offsetTop ) ) {
					element.style.top = offsetTop + 'px';
					element.classList.add('item-is-fixed');
					element.classList.add('item-is-stuck');
					element.classList.remove('item-at-start');
					parent.classList.add('child-is-fixed');
				} else {
					if ( element.classList.contains( 'item-is-fixed' ) ) {
						element.classList.remove( 'item-is-fixed' );
						element.classList.remove('item-at-start');
						element.classList.remove('item-is-stuck');
						element.style.height = null;
						element.style.top = null;
						parent.classList.remove('child-is-fixed');
					}
				}
			}
			window.addEventListener( 'resize', updateSticky, false );
			window.addEventListener( 'scroll', updateSticky, false );
			window.addEventListener( 'load', updateSticky, false );
			window.addEventListener( 'kadence-update-sticky', updateSticky, false );
			updateSticky();
		},
		/**
		 * Find the sticky items.
		 */
		stickTransHeadItem: function( element ) {
			var offsetTop = window.kadenceProSticky.getOffset( document.body ).top,
				activeOffsetTop = window.kadenceProSticky.getOffset( element ).top;
			var updateSticky = function( e ) {
				var parent = element.parentNode;
				parent.style.height = element.offsetHeight + 'px';
				if ( Math.max(0, window.scrollY ) === Math.floor( activeOffsetTop - offsetTop ) ) {
					element.style.top = offsetTop + 'px';
					element.classList.add('item-is-fixed');
					element.classList.add('item-at-start');
					element.classList.remove('item-is-stuck');
					parent.classList.add('child-is-fixed');
				} else if ( Math.max(0, window.scrollY ) > Math.floor( activeOffsetTop - offsetTop ) ) {
					element.style.top = offsetTop + 'px';
					element.classList.add('item-is-fixed');
					element.classList.add('item-is-stuck');
					element.classList.remove('item-at-start');
					parent.classList.add('child-is-fixed');
				} else {
					if ( element.classList.contains( 'item-is-fixed' ) ) {
						element.classList.remove( 'item-is-fixed' );
						element.classList.remove('item-at-start');
						element.classList.remove('item-is-stuck');
						element.style.height = null;
						element.style.top = null;
						parent.classList.remove('child-is-fixed');
					}
				}
			}
			window.addEventListener( 'resize', updateSticky, false );
			window.addEventListener( 'scroll', updateSticky, false );
			window.addEventListener( 'load', updateSticky, false );
			window.addEventListener( 'kadence-update-sticky', updateSticky, false );
			updateSticky();
		},
		/**
		 * Find the sticky items.
		 */
		stickScrollItem: function( element ) {
			var updateSticky = function( e ) {
				var offsetTop = window.kadenceProSticky.getOffset( document.getElementById( 'wrapper' ) ).top,
				scrollOffset = element.getAttribute('data-scroll-offset');
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
						element.style.height = null;
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
			var updateScrollSticky = function( e ) {
				var scrollOffset = parseInt( element.getAttribute('data-scroll-offset') );
				if ( window.scrollY >= scrollOffset ) {
					element.classList.add('item-is-fixed');
					element.classList.add('item-is-stuck');
					element.classList.remove('item-at-start');
				} else {
					if ( element.classList.contains( 'item-is-fixed' ) ) {
						element.classList.remove('item-is-fixed');
						element.classList.remove('item-at-start');
						element.classList.remove('item-is-stuck');
					}
				}
			}
			window.addEventListener( 'resize', updateScrollSticky, false );
			window.addEventListener( 'scroll', updateScrollSticky, { passive: true } );
			window.addEventListener( 'load', updateScrollSticky, false );
			updateScrollSticky();
		},
		/**
		 * Find the sticky items.
		 */
		stickScrollFooterItemSpace: function( element ) {
			var updateScrollSticky = function( e ) {
				var parent = element.parentNode;
				parent.style.height = element.offsetHeight + 'px';
				var scrollOffset = parseInt( element.getAttribute('data-scroll-offset') );
				if ( window.scrollY >= scrollOffset ) {
					element.classList.add('item-is-fixed');
					element.classList.add('item-is-stuck');
					element.classList.remove('item-at-start');
				} else {
					if ( element.classList.contains( 'item-is-fixed' ) ) {
						element.classList.remove('item-is-fixed');
						element.classList.remove('item-at-start');
						element.classList.remove('item-is-stuck');
					}
				}
			}
			window.addEventListener( 'resize', updateScrollSticky, false );
			window.addEventListener( 'scroll', updateScrollSticky, false );
			window.addEventListener( 'load', updateScrollSticky, false );
			updateScrollSticky();
		},
		/**
		 * Find the sticky items.
		 */
		stickFootItem: function( element ) {
			var updateSticky = function( e ) {
				element.classList.add('item-is-fixed');
				element.classList.add('item-is-stuck');
				element.classList.remove('item-at-start');
			}
			window.addEventListener( 'resize', updateSticky, false );
			updateSticky();
		},
		/**
		 * Find the sticky items.
		 */
		 stickFootItemBelow: function( element ) {
			var updateSticky = function( e ) {
				var parent = element.parentNode;
				parent.style.height = element.offsetHeight + 'px';
				element.classList.add('item-is-fixed');
				element.classList.add('item-is-stuck');
				element.classList.remove('item-at-start');
			}
			window.addEventListener( 'resize', updateSticky, false );
			updateSticky();
		},
		/**
		 * Find the sticky items.
		 */
		initStickyHeadItems: function() {
			var stickyHeadItems = document.querySelectorAll( '.kadence-pro-fixed-above' );
			// No point if no drawers.
			if ( ! stickyHeadItems.length ) {
				return;
			}
			for ( let i = 0; i < stickyHeadItems.length; i++ ) {
				window.kadenceProSticky.stickHeadItem( stickyHeadItems[ i ] );
			}
		},
		/**
		 * Find the sticky items.
		 */
		initStickyHeadTransItems: function() {
			var stickyHeadTransItems = document.querySelectorAll( '.kadence-pro-fixed-above-trans' );
			// No point if no drawers.
			if ( ! stickyHeadTransItems.length ) {
				return;
			}
			for ( let i = 0; i < stickyHeadTransItems.length; i++ ) {
				window.kadenceProSticky.stickTransHeadItem( stickyHeadTransItems[ i ] );
			}
		},
		/**
		 * Find the sticky items.
		 */
		initStickyScrollItems: function() {
			var stickyScrollItems = document.querySelectorAll( '.kadence-pro-fixed-on-scroll' );
			// No point if no drawers.
			if ( ! stickyScrollItems.length ) {
				return;
			}
			for ( let i = 0; i < stickyScrollItems.length; i++ ) {
				window.kadenceProSticky.stickScrollItem( stickyScrollItems[ i ] );
			}
		},
		/**
		 * Find the sticky items.
		 */
		initStickyScrollFooterItems: function() {
			var stickyScrollItemsFooter = document.querySelectorAll( '.kadence-pro-fixed-on-scroll-footer' );
			// No point if no drawers.
			if ( ! stickyScrollItemsFooter.length ) {
				return;
			}
			for ( let i = 0; i < stickyScrollItemsFooter.length; i++ ) {
				window.kadenceProSticky.stickScrollFooterItem( stickyScrollItemsFooter[ i ] );
			}
		},
		/**
		 * Find the sticky items.
		 */
		initStickyScrollFooterItemsSpace: function() {
			var stickyScrollItemsFooter = document.querySelectorAll( '.kadence-pro-fixed-on-scroll-footer-space' );
			// No point if no drawers.
			if ( ! stickyScrollItemsFooter.length ) {
				return;
			}
			for ( let i = 0; i < stickyScrollItemsFooter.length; i++ ) {
				window.kadenceProSticky.stickScrollFooterItemSpace( stickyScrollItemsFooter[ i ] );
			}
		},
		/**
		 * Find the sticky items.
		 */
		initStickyFootItems: function() {
			var stickyFootItems = document.querySelectorAll( '.kadence-pro-fixed-below' );
			// No point if no sticky
			if ( ! stickyFootItems.length ) {
				return;
			}
			for ( let i = 0; i < stickyFootItems.length; i++ ) {
				window.kadenceProSticky.stickFootItemBelow( stickyFootItems[ i ] );
			}
		},
		/**
		 * Find the sticky items.
		 */
		initStickyFootOnItems: function() {
			var stickyFootOnItems = document.querySelectorAll( '.kadence-pro-fixed-bottom' );
			// No point if no sticky
			if ( ! stickyFootOnItems.length ) {
				return;
			}
			for ( let i = 0; i < stickyFootOnItems.length; i++ ) {
				window.kadenceProSticky.stickFootItem( stickyFootOnItems[ i ] );
			}
		},
		// Initiate sticky when the DOM loads.
		init: function() {
			window.kadenceProSticky.initStickyHeadItems();
			window.kadenceProSticky.initStickyHeadTransItems();
			window.kadenceProSticky.initStickyScrollItems();
			window.kadenceProSticky.initStickyScrollFooterItems();
			window.kadenceProSticky.initStickyFootItems();
			window.kadenceProSticky.initStickyFootOnItems();
			window.kadenceProSticky.initStickyScrollFooterItemsSpace();
		}
	}
	if ( 'loading' === document.readyState ) {
		// The DOM has not yet been loaded.
		document.addEventListener( 'DOMContentLoaded', window.kadenceProSticky.init );
	} else {
		// The DOM has already been loaded.
		window.kadenceProSticky.init();
	}
})();