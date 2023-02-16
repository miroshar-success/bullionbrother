/* global kadenceProDarkModeConfig */
/**
 * File dark-mode.js.
 * Gets color switch working..
 */

 (function() {
	'use strict';
	window.kadenceProDarkMode = {
		// Initiate scroll when the DOM loads.
		init: function() {
			var paletteCookie = window.kadenceProDarkMode.getCookie( 'paletteCookie' );
			// console.log( paletteCookie );
			// Check if we have a cookie set.
			if ( paletteCookie && ( 'dark' === paletteCookie || 'light' === paletteCookie ) ) {
				if ( 'dark' === paletteCookie && document.body.classList.contains( 'color-switch-light' ) ) {
					window.kadenceProDarkMode.switchToDark();
				} else if ( 'light' === paletteCookie && document.body.classList.contains( 'color-switch-dark' ) ) {
					window.kadenceProDarkMode.switchToLight();
				}
			} else if ( kadenceDarkModeConfig.auto ) {
				var prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");
				if ( prefersDarkScheme.matches && document.body.classList.contains( 'color-switch-light' ) ) {
					window.kadenceProDarkMode.switchToDark();
				} else if ( ! prefersDarkScheme.matches && document.body.classList.contains( 'color-switch-dark' ) ) {
					window.kadenceProDarkMode.switchToLight();
				}
			}
			window.kadenceProDarkMode.initToggleButtons();
		},
		switchToDark: function () {
			document.body.classList.add( 'color-switch-dark' );
			document.body.classList.remove( 'color-switch-light' );
		},
		switchToLight: function () {
			document.body.classList.add( 'color-switch-light' );
			document.body.classList.remove( 'color-switch-dark' );
		},
		createCookie: function( name, value, length, unit ) {
			if ( length ) {
				var date = new Date();
				if ( 'minutes' == unit ) {
					date.setTime( date.getTime() + ( length * 60 * 1000 ) );
				} else if ( 'hours' == unit ) {
					date.setTime( date.getTime() + ( length * 60 * 60 * 1000 ) );
				} else {
					date.setTime( date.getTime()+(length*24*60*60*1000));
				}
				var expires = "; expires="+date.toGMTString();
			} else {
				var expires = "";
			}
	
			document.cookie = kadenceDarkModeConfig.siteSlug + '-' + name+"="+value+expires+"; path=/";
		},
		getCookie: function ( name ) {
			var value = "; " + document.cookie;
			var parts = value.split("; " + kadenceDarkModeConfig.siteSlug + '-' + name + "=");
			if ( parts.length == 2 ) return parts.pop().split(";").shift();
		},
		initToggleButtons: function() {
			var toggles = document.querySelectorAll( '.kadence-color-toggle' );
			if ( ! toggles.length ) {
				return;
			}
			toggles.forEach( function( element ) {
				element.addEventListener( 'click', function( e ) {
					window.kadenceProDarkMode.onToggle( e );
				} );
			} );
		},
		onToggle: function() {
			if ( document.body.classList.contains( 'color-switch-light' ) ) {
				window.kadenceProDarkMode.switchToDark();
				window.kadenceProDarkMode.createCookie( 'paletteCookie', 'dark', 300, 'days' );
			} else {
				window.kadenceProDarkMode.switchToLight();
				window.kadenceProDarkMode.createCookie( 'paletteCookie', 'light', 300, 'days' );
			}
		}
	}
	if ( 'loading' === document.readyState ) {
		// The DOM has not yet been loaded.
		document.addEventListener( 'DOMContentLoaded', window.kadenceProDarkMode.init );
	} else {
		// The DOM has already been loaded.
		window.kadenceProDarkMode.init();
	}
})();