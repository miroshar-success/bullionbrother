/**
 * Admin post scripts.
 */

/* global jQuery, wlea_local_obj */
;( function ( $ ) {
    'use strict';

    $( document ).ready( function () {

        // Local object.
        let backButton = ( wlea_local_obj.hasOwnProperty( 'back_button' ) ? wlea_local_obj.back_button : '' );

        /**
         * Add back button.
         */
        ( function () {
            if ( ( 'string' === typeof backButton ) && ( 0 < backButton.length ) ) {
                $( document ).find( '#wpbody-content .wrap .wp-header-end' ).before( backButton );
            }
        } )();

        /**
         * Insert placeholder.
         */
        ( function () {
            $( '.wlea-placeholder-insert' ).on( 'click', function ( e ) {
                e.preventDefault();

                let thisButton = $( this ),
                    placeholder = thisButton.closest( '.wlea-placeholder-item' ).find( '.wlea-placeholder-content' ).text();

                if ( ( 'string' === typeof placeholder ) && ( 0 < placeholder.length ) ) {
                    tinymce.activeEditor.execCommand( 'mceReplaceContent', false, placeholder );
                    tb_remove();
                }
            } );
        } )();

    } );

} )( jQuery );