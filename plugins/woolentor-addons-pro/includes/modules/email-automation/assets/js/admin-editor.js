/**
 * Admin editor scripts.
 */

/* global jQuery, wlea_local_obj */
;( function ( $ ) {
    'use strict';

    // Local object.
    let editorButtonText = ( wlea_local_obj.hasOwnProperty( 'editor_button_text' ) ? wlea_local_obj.editor_button_text : '' ),
        editorButtonTooltip = ( wlea_local_obj.hasOwnProperty( 'editor_button_tooltip' ) ? wlea_local_obj.editor_button_tooltip : '' );

    /**
     * Add back button.
     */
    ( function() {
        tinymce.PluginManager.add( 'wlea_placeholders', function( editor, url ) {
            editor.addButton( 'wlea_placeholders', {
                cmd: 'wlea_placeholders',
                icon: 'plus',
                text: editorButtonText,
                title: editorButtonTooltip,
                classes: 'wlea-placeholders-btn',
            } );

            editor.addCommand( 'wlea_placeholders', function() {
                $( '#TB_ajaxContent' ).html( '' );

                tb_show( '', '#TB_inline?&inlineId=wlea-admin-popup' );

                $( 'body' ).addClass( 'wlea-admin-popup-open' );
                $( '#TB_overlay' ).addClass( 'wlea-admin-popup-overlay' );
                $( '#TB_window' ).addClass( 'wlea-admin-popup-window' ).css( 'width', '430px' );

                return;
            } );

        } );
    } )();

} )( jQuery );