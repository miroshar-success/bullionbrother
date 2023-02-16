/**
 * Admin scripts.
 */

/* global jQuery, wlea_local_obj */
;( function ( $ ) {
    'use strict';

    $( document ).ready( function () {

        // Local object.
        let ajaxUrl = ( wlea_local_obj.hasOwnProperty( 'ajax_url' ) ? wlea_local_obj.ajax_url : '' ),
            ajaxNonce = ( wlea_local_obj.hasOwnProperty( 'ajax_nonce' ) ? wlea_local_obj.ajax_nonce : '' );

        /**
         * Trigger duplicate button.
         */
        ( function () {
            $( document ).on( 'click', '.wlea-duplicator-button', function ( e ) {
                e.preventDefault();

                let thisButton = $( this ),
                    postId = thisButton.attr( 'data-wlea-post-id' ),
                    label = thisButton.attr( 'data-wlea-label' ),
                    duplicatingLabel = thisButton.attr( 'data-wlea-duplicating-label' ),
                    location = window.location.href;

                if ( thisButton.hasClass( 'wlea-duplicating' ) ) {
                    return;
                }

                postId = wleaNumberToAbsInt( postId );

                $.ajax( {
                    type: 'POST',
                    url: ajaxUrl,
                    data: {
                        action: 'wlea_admin_ajax_duplicate',
                        ajax_nonce: ajaxNonce,
                        post_id: postId,
                    },
                    beforeSend: function() {
                        thisButton.addClass( 'wlea-duplicating' ).text( duplicatingLabel );
                    },
                    success: function( response ) {
                        if ( ! response ) {
                            window.location.href = location;
                            return;
                        }

                        if ( 'string' === typeof response ) {
                            response = JSON.parse( response );
                        }

                        if ( response.hasOwnProperty( 'duplicate_post_id' ) ) {
                            let duplicatePostId = response.duplicate_post_id;

                            if ( ! isNaN( duplicatePostId ) ) {
                                duplicatePostId = wleaNumberToAbsInt( duplicatePostId );

                                let successFormStyle = 'display: none !important; width: 0 !important; height: 0 !important; overflow: hidden !important; visibility: hidden !important; opacity: 0 !important;',
                                    successForm = $( '<form method="post" action="' + location + '" class="wlea-duplicate-success-form" style="' + successFormStyle + '"><input type="hidden" name="wlea-duplicate-post-id" value="' + duplicatePostId + '"></form>' );

                                $( 'body' ).append( successForm );
                                $( '.wlea-duplicate-success-form' ).submit().remove();

                                return;
                            }
                        }

                        window.location.href = location;
                    },
                    error: function() {
                        thisButton.removeClass( 'wlea-duplicating' ).text( label );
                    },
                } );
            } );
        } )();

        /**
         * Fix bulk action on filter submit.
         */
        ( function () {
            let form = $( '#posts-filter' ),
                eventSelect = form.find( '#filter-by-event' ),
                statusSelect = form.find( '#filter-by-status' ),
                filterSubmit = form.find( '#filter-submit' ),
                topBulkAction = form.find( '#bulk-action-selector-top' ),
                bottomBulkAction = form.find( '#bulk-action-selector-bottom' );

            eventSelect.on( 'change', function() {
                topBulkAction.val( '-1' );
                bottomBulkAction.val( '-1' );
            } );

            statusSelect.on( 'change', function() {
                topBulkAction.val( '-1' );
                bottomBulkAction.val( '-1' );
            } );

            filterSubmit.on( 'click', function() {
                topBulkAction.val( '-1' );
                bottomBulkAction.val( '-1' );
            } );
        } )();

        /**
         * Remove args after actions.
         */
        ( function () {
            let url = new URL( window.location );

            if ( 'object' === typeof url && 'undefined' !== typeof url.searchParams ) {
                url.searchParams.delete( 'wlea-activated' );
                url.searchParams.delete( 'wlea-deactivated' );
                url.searchParams.delete( 'wlea-marked-success' );
                url.searchParams.delete( 'wlea-marked-failed' );
                url.searchParams.delete( 'wlea-deleted' );

                window.history.pushState( {}, '', url );
            }
        } )();

        /**
         * Number to absolute integer.
         */
        function wleaNumberToAbsInt( number ) {
            if ( ! isNaN( number ) ) {
                number = parseFloat( number );
                number = number.toFixed(0);
                number = Math.abs( number );
            } else {
                number = 0;
            }

            return number;
        }

    } );

} )( jQuery );