/**
 * Editor script.
 */

/* global jQuery */
;( function ( $ ) {
    'use strict';

    $( document ).ready( function () {

        /**
         * Trigger section style control.
         */
        elementor.hooks.addAction( 'panel/open_editor/section', function ( panel, model, view ) {
            panel.$el.find( '#elementor-panel-content-wrapper #elementor-panel-page-editor .elementor-panel-navigation .elementor-tab-control-style a' ).trigger( 'click' );
            panel.$el.find( '#elementor-panel-content-wrapper #elementor-panel-page-editor' ).addClass( 'woolentor-email-section-editor' );
            panel.$el.find( '#elementor-panel-content-wrapper #elementor-panel-page-editor #elementor-controls .elementor-control-section_background .elementor-section-title' ).text( woolentor_email_customizer_editor.section_style_title );
        } );

        /**
         * Trigger column style control.
         */
        elementor.hooks.addAction( 'panel/open_editor/column', function ( panel, model, view ) {
            panel.$el.find( '#elementor-panel-content-wrapper #elementor-panel-page-editor .elementor-panel-navigation .elementor-tab-control-style a' ).trigger( 'click' );
            panel.$el.find( '#elementor-panel-content-wrapper #elementor-panel-page-editor' ).addClass( 'woolentor-email-column-editor' );
            panel.$el.find( '#elementor-panel-content-wrapper #elementor-panel-page-editor #elementor-controls .elementor-control-section_style .elementor-section-title' ).text( woolentor_email_customizer_editor.column_style_title );
        } );

    } );

} )( jQuery );
