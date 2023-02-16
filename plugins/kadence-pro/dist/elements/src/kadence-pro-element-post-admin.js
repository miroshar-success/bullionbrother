/*global kadence_elements_params */
;(function ( $, window ) {
	$( '.kadence-status-toggle' ).on( 'click', function( event ) {
		event.preventDefault();
		var $button = $( this );
		$button.find( '.spinner' ).addClass( 'is-active' );
		$.ajax( {
			type: 'POST',
			url: kadence_elements_params.ajax_url,
			data: {
				action           : 'kadence_elements_change_status',
				post_id          : $button.data( 'post-id' ),
				post_status      : $button.data( 'post-status' ),
				security         : kadence_elements_params.ajax_nonce
			},
			dataType: 'json',
			success: function( response ) {
				$button.find( '.spinner' ).removeClass('is-active');
				if ( response && response.success ) {
					if ( 'publish' === $button.data( 'post-status' ) ) {
						$button.removeClass( 'kadence-status-publish' );
						$button.addClass( 'kadence-status-draft' );
						$button.data( 'post-status', 'draft' );
						$button.find( '.kadence-status-label' ).html( kadence_elements_params.draft );
					} else {
						$button.removeClass( 'kadence-status-draft' );
						$button.addClass( 'kadence-status-publish' );
						$button.data( 'post-status', 'publish' );
						$button.find( '.kadence-status-label' ).html( kadence_elements_params.publish );
					}
					$button.closest( 'tr.type-kadence_element' ).find( '.column-title .post-state' ).hide();
				} else {
					alert( 'Failed to change post status, please reload and try again' );
					window.console.log( response );
				}
			}
		} ).fail( function( response ) {
			$button.find( '.spinner' ).removeClass('is-active');
			alert( 'Failed to change post status, please reload and try again' );
			window.console.log( response );
		} );
	} );

})( jQuery, window );
