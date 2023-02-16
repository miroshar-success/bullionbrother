/**
 * Admin scripts.
 */

/* global jQuery */
;( function ( $ ) {
    'use strict';

    $( document ).ready( function () {

        /**
         * Copy shortcode.
         */
        $( document ).on( 'click', '.wlpf-dynamic-shortcode-copy', function ( e ) {
            e.preventDefault();

            let thisButton = $( this ),
                shortcode = thisButton.closest( '.woolentor-admin-input' ).find( 'input' ).val(),
                tempInput = $( '<input class="wlpf-dynamic-shortcode-temp-input">' );

            if ( 'string' === typeof shortcode ) {
                $( 'body' ).append( tempInput );
                tempInput.val( shortcode ).select();
                document.execCommand( 'copy' );
                tempInput.remove();
                thisButton.addClass( 'wlpf-dynamic-shortcode-copy-success' );
                thisButton.removeClass( 'wlpf-dynamic-shortcode-copy-error' );

                setTimeout( function () {
                    thisButton.removeClass( 'wlpf-dynamic-shortcode-copy-success' );
                    thisButton.removeClass( 'wlpf-dynamic-shortcode-copy-error' );
                }, 1000 );
            }
        } );

        /**
         * Rewrite dynamic title.
         */
        $( document ).on( 'keyup change', '.wlpf-dynamic-label .woolentor-admin-input input', function ( e ) {
            let thisField = $( this ),
                label = thisField.val(),
                item = thisField.closest( '.woolentor-option-repeater-item' ),
                fieldsArea = item.closest( '.woolenor-reapeater-fields-area' ),
                uniqueIdWrap = item.find( '.wlpf-dynamic-unique-id' ),
                uniqueId = uniqueIdWrap.find( '.woolentor-admin-input input' ).val(),
                titleWrap = item.find( '.woolentor-option-repeater-tools .woolentor-option-repeater-item-title' ),
                title = '';

            label = label.trim();
            uniqueId = uniqueId.trim();

            if ( 0 < label.length ) {
                title = wlpfGetItemTitleWithLabelStructure();
            } else {
                title = wlpfGetItemTitleStructure();
            }

            title = title.replace( '_WLPF_ID_', uniqueId );
            title = title.replace( '_WLPF_LABEL_', label );

            titleWrap.html( title );

            if ( ( true === uniqueIdWrap.hasClass( 'wlpf-filter-unique-id' ) ) && ( 'undefined' !== typeof fieldsArea ) ) {
                $( document ).trigger( 'group_filters_select_refresh', [ fieldsArea ] );
            }
        } );

        /**
         * Generate unique ID & shortcode to new item.
         */
        $( document ).on( 'repeater_field_item_added', function ( e, item ) {
            let shortcodeWrap = item.find( '.wlpf-dynamic-shortcode' ),
                shortcodeField = shortcodeWrap.find( '.woolentor-admin-input input' ),
                uniqueIdField = item.find( '.wlpf-dynamic-unique-id .woolentor-admin-input input' ),
                labelField = item.find( '.wlpf-dynamic-label .woolentor-admin-input input' ),
                uniqueId = uniqueIdField.val(),
                shortcode = '',
                copyIcon = '',
                copyButton = '';

            uniqueId = wlpfGetUniqueId( uniqueId );

            if ( shortcodeWrap.hasClass( 'wlpf-filter-shortcode' ) ) {
                shortcode = '[wlpf_filter id="' + uniqueId + '"]';
            } else if ( shortcodeWrap.hasClass( 'wlpf-group-shortcode' ) ) {
                shortcode = '[wlpf_group id="' + uniqueId + '"]';
            }

            uniqueIdField.val( uniqueId );
            shortcodeField.val( shortcode );

            copyIcon = '<span class="dashicons dashicons-admin-page"></span>';
            copyButton = '<span class="wlpf-dynamic-shortcode-copy">' + copyIcon + '</span>';

            shortcodeField.attr( 'disabled', 'disabled' );
            shortcodeField.closest( '.woolentor-admin-input' ).append( copyButton );

            labelField.trigger( 'change' );
        } );

        /**
         * Generate shortcode to existing item.
         */
        $( document ).on( 'repeater_field_item_active', function ( e, item ) {
            let fieldsArea = item.closest( '.woolenor-reapeater-fields-area' ),
                shortcodeWrap = item.find( '.wlpf-dynamic-shortcode' ),
                shortcodeField = shortcodeWrap.find( '.woolentor-admin-input input' ),
                uniqueIdField = item.find( '.wlpf-dynamic-unique-id .woolentor-admin-input input' ),
                uniqueId = uniqueIdField.val(),
                shortcode = '',
                copyIcon = '',
                copyButton = '';

            if ( shortcodeWrap.hasClass( 'wlpf-filter-shortcode' ) ) {
                shortcode = '[wlpf_filter id="' + uniqueId + '"]';
            } else if ( shortcodeWrap.hasClass( 'wlpf-group-shortcode' ) ) {
                shortcode = '[wlpf_group id="' + uniqueId + '"]';
            }

            shortcodeField.val( shortcode );

            copyIcon = '<span class="dashicons dashicons-admin-page"></span>';
            copyButton = '<span class="wlpf-dynamic-shortcode-copy">' + copyIcon + '</span>';

            shortcodeField.attr( 'disabled', 'disabled' );
            shortcodeField.closest( '.woolentor-admin-input' ).append( copyButton );
        } );

        /**
         * Generate shortcode to existing item.
         */
        $( document ).on( 'repeater_field_item_removed', function ( e, item, fieldsArea ) {
            let uniqueIdWrap = item.find( '.wlpf-dynamic-unique-id' );

            if ( ( true === uniqueIdWrap.hasClass( 'wlpf-filter-unique-id' ) ) && ( 'undefined' !== typeof fieldsArea ) ) {
                $( document ).trigger( 'group_filters_select_refresh', [ fieldsArea ] );
            }
        } );

        /**
         * Group filters select refresh.
         */
        $( document ).on( 'group_filters_select_refresh', function ( e, itemsArea ) {
            if ( 0 < itemsArea.length ) {
                let items = itemsArea.find( '.woolentor-option-repeater-item:not(.woolentor-repeater-hidden)' ),
                    selects = $( document ).find( '.wlpf-group-filters .woolentor-admin-select select' ),
                    options = {};

                $.each( items, function () {
                    let thisItem = $( this ),
                        label = thisItem.find( '.wlpf-filter-label .woolentor-admin-input input' ).val(),
                        uniqueId = thisItem.find( '.wlpf-filter-unique-id .woolentor-admin-input input' ).val(),
                        title = '';

                    if ( ( 'undefined' !== typeof label ) && ( 'undefined' !== typeof uniqueId ) ) {
                        label = label.trim();
                        uniqueId = uniqueId.trim();

                        if ( 0 < label.length ) {
                            title = wlpfGetItemTitleWithLabelStructure();
                        } else {
                            title = wlpfGetItemTitleStructure();
                        }

                        title = title.replace( '_WLPF_ID_', uniqueId );
                        title = title.replace( '_WLPF_LABEL_', label );

                        options[ uniqueId ] = title;
                    }
                } );

                $.each( selects, function() {
                    let thisSelect = $( this ),
                        value = thisSelect.val(),
                        parent = thisSelect.parent(),
                        optionsHTML = '',
                        selectValue = [];

                    for ( let i = 0; i < value.length; i++ ) {
                        let currentValue = wlpfNumberToAbsInt( value[ i ] );

                        if ( ( 0 < currentValue ) && ( options.hasOwnProperty( currentValue ) ) ) {
                            selectValue.push( currentValue );
                        }
                    }

                    $.each( options, function ( optionId, optionTitle ) {
                        optionsHTML += '<option value="' + optionId + '">' + optionTitle + '</option>';
                    } );

                    thisSelect.html( optionsHTML ).val( selectValue ).change();

                    if ( thisSelect.hasClass( 'select2-hidden-accessible' ) ) {
                        thisSelect.select2( 'destroy' ).select2( {
                            dropdownParent: parent,
                        } );
                    }
                } );
            }
        } );

        /**
         * Show rewritten dynamic title.
         */
        $( document ).on( 'module_setting_loaded', function () {
            $( '.wlpf-dynamic-label .woolentor-admin-input input' ).trigger( 'change' );
        } );

    } );

} )( jQuery );