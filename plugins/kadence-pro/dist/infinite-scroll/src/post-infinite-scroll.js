/* global kadenceProInfiniteConfig */
/**
 * File post-infinite-scroll.js.
 * Gets single post infinite scroll working.
 */

(function() {
	'use strict';
	window.kadenceProSingleInfinite = {
		getPath: function() {
			//console.log( this );
			var slug = kadenceProInfiniteConfig.slugs[ this.loadCount ];
			if ( slug ) {
				return slug;
			}
		},
		// Initiate scroll when the DOM loads.
		init: function() {
			var infScroll = new InfiniteScroll( '.content-wrap', {
				path: window.kadenceProSingleInfinite.getPath,
				append: '.single-entry',
				status: '.page-load-status',
			} );
		}
	}
	if ( 'loading' === document.readyState ) {
		// The DOM has not yet been loaded.
		document.addEventListener( 'DOMContentLoaded', window.kadenceProSingleInfinite.init );
	} else {
		// The DOM has already been loaded.
		window.kadenceProSingleInfinite.init();
	}
})();