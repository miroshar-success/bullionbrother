/* global kadenceProMegaConfig */
/**
 * File kadence-mega-menu.js.
 * Gets mega menu working.
 */

(function() {
	'use strict';
	window.kadenceMegaMenu = {
		/**
		 * Find the cart and open it.
		 */
		runSubMenuContentSize: function() {
			var contentSubmenus = document.querySelectorAll( '.site-header-wrap .kadence-menu-mega-width-content > ul.sub-menu' );
			for ( let i = 0; i < contentSubmenus.length; i++ ) {
				var parentMenuItem = contentSubmenus[ i ].parentNode;
				var row = contentSubmenus[ i ].closest( '.site-header-row' );
				contentSubmenus[ i ].style.left = '';
				contentSubmenus[ i ].style.width = row.offsetWidth + 'px';
				contentSubmenus[ i ].style.left = -1 * Math.abs( parentMenuItem.getBoundingClientRect().left - row.getBoundingClientRect().left ).toString() + 'px';
			}
		},
		/**
		 * Initiate the script to toggle cart when product is added.
		 */
		initContentSubMenuSize: function() {
			var contentSubmenus = document.querySelectorAll( '.site-header-wrap .kadence-menu-mega-width-content > ul.sub-menu' );
			// No point if no submenus.
			if ( ! contentSubmenus.length ) {
				return;
			}
			var timeout;
			window.addEventListener( 'resize', function() {
				clearTimeout( timeout );
				timeout = setTimeout( window.kadenceMegaMenu.runSubMenuContentSize, 500 );
			}, false );
			window.addEventListener( 'load', window.kadenceMegaMenu.runSubMenuContentSize );
			window.kadenceMegaMenu.runSubMenuContentSize();

		},
		/**
		 * Setup the Fullwith Menu.
		 */
		runSubMenuFullSize: function() {
			var contentSubmenus = document.querySelectorAll( '.site-header-wrap .kadence-menu-mega-width-full > ul.sub-menu' );
			for ( let i = 0; i < contentSubmenus.length; i++ ) {
				var parentMenuItem = contentSubmenus[ i ].parentNode;
				contentSubmenus[ i ].style.left = '';
				contentSubmenus[ i ].style.width = window.innerWidth + 'px';
				contentSubmenus[ i ].style.left = -1 * Math.abs( parentMenuItem.getBoundingClientRect().left ).toString() + 'px';
			}
		},
		/**
		 * Initiate the script to toggle cart when product is added.
		 */
		initFullSubMenuSize: function() {
			var contentSubmenus = document.querySelectorAll( '.site-header-wrap .kadence-menu-mega-width-full > ul.sub-menu' );
			// No point if no submenus.
			if ( ! contentSubmenus.length ) {
				return;
			}
			var timeout;
			window.addEventListener( 'resize', function() {
				clearTimeout( timeout );
				timeout = setTimeout( window.kadenceMegaMenu.runSubMenuFullSize, 500 );
			}, false );
			window.kadenceMegaMenu.runSubMenuFullSize();

		},
		// Initiate the menus when the DOM loads.
		init: function() {
			window.kadenceMegaMenu.initContentSubMenuSize();
			window.kadenceMegaMenu.initFullSubMenuSize();
		}
	}
	if ( 'loading' === document.readyState ) {
		// The DOM has not yet been loaded.
		document.addEventListener( 'DOMContentLoaded', window.kadenceMegaMenu.init );
	} else {
		// The DOM has already been loaded.
		window.kadenceMegaMenu.init();
	}
})();
