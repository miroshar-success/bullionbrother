/**
 * Frontend scripts.
 */

/* global jQuery */
;( function ( $ ) {
    'use strict';

    /**
     * Frontend initializer.
     */
    function frontendInitializer() {

        /**
         * Update nice select options.
         */
        $( document ).on( 'wlpf_update_nice_select_options', function () {
            $( '.nice-select.wlpf-terms-select .list .option' ).each( function () {
                var thisOption = $( this ),
                    optionHtml = thisOption.html();

                for ( let i = 5; i > 0; i-- ) {
                    let search = '',
                        replace = '<span class="wlpf-nbsp-' + i + '"></span>';

                    for ( let j = 0; j < i; j++ ) {
                        search += '&nbsp;';
                    }

                    if ( true === optionHtml.includes( search ) ) {
                        optionHtml = optionHtml.replace( search, replace );
                        thisOption.html( optionHtml );
                        break;
                    }
                }
            } );
        } );

        /**
         * Initialize terms select.
         */
        ( function () {
            $( '.wlpf-terms-nice-select' ).niceSelect( 'destroy' );
            $( '.wlpf-terms-select' ).niceSelect().addClass( 'wlpf-terms-nice-select' );
            $( document ).trigger( 'wlpf_update_nice_select_options' );
        } )();

        /**
         * Initialize price range.
         */
        $( '.wlpf-price-range' ).each( function () {
            var thisRange = $( this ),
                thisFilter = thisRange.closest( '.wlpf-price-filter' ),
                minPrice = thisFilter.attr( 'data-wlpf-range-min-price' ),
                maxPrice = thisFilter.attr( 'data-wlpf-range-max-price' ),
                minValue = thisFilter.attr( 'data-wlpf-range-min-value' ),
                maxValue = thisFilter.attr( 'data-wlpf-range-max-value' ),
                rangeUI = thisRange.find( '.wlpf-price-range-ui' );

            minPrice = wlpfNumberToAbsInt( minPrice );
            maxPrice = wlpfNumberToAbsInt( maxPrice );

            minValue = ( 'undefined' !== typeof minValue && '' !== minValue ) ? wlpfNumberToAbsInt( minValue ) : minPrice;
            maxValue = ( 'undefined' !== typeof maxValue && '' !== maxValue ) ? wlpfNumberToAbsInt( maxValue ) : maxPrice;

            rangeUI.slider( {
                range: true,
                min: minPrice,
                max: maxPrice,
                values: [ minValue, maxValue ],
                slide: function ( event, ui ) {
                    var fields = thisRange.find( '.wlpf-price-range-fields' ),
                        values = [],
                        minValue = minValue,
                        maxValue = maxValue;

                    if ( 'object' === typeof ui && ui.hasOwnProperty( 'values' ) ) {
                        values = ui.values;

                        if ( 'object' === typeof values && values.hasOwnProperty( 0 ) && values.hasOwnProperty( 1 ) ) {
                            minValue = values[ 0 ];
                            maxValue = values[ 1 ];

                            fields.find( '.wlpf-min-price-dispaly .wlpf-price-number' ).text( minValue );
                            fields.find( '.wlpf-max-price-dispaly .wlpf-price-number' ).text( maxValue );

                            fields.find( '.wlpf-min-price-field' ).val( minValue ).trigger( 'change' );
                            fields.find( '.wlpf-max-price-field' ).val( maxValue ).trigger( 'change' );
                        }
                    }
                }
            } )
        } );

        /**
         * Collapse and expand filter.
         */
        $( document ).on( 'click', '.wlpf-filter-collapse', function ( e ) {
            e.preventDefault();

            var thisButton = $( this ),
                filter = thisButton.closest( '.wlpf-filter-wrap' ),
                content = filter.find( '.wlpf-filter-content' );

            if ( filter.hasClass( 'wlpf-filter-collapsed' ) ) {
                filter.removeClass( 'wlpf-filter-collapsed' );
                content.slideDown( 500 );
            } else {
                filter.addClass( 'wlpf-filter-collapsed' );
                content.slideUp( 500 );
            }
        } );

        /**
         * Collapse and expand group.
         */
        $( document ).on( 'click', '.wlpf-group-collapse', function ( e ) {
            e.preventDefault();

            var thisButton = $( this ),
                group = thisButton.closest( '.wlpf-group-wrap' ),
                content = group.find( '.wlpf-group-content' );

            if ( group.hasClass( 'wlpf-group-collapsed' ) ) {
                group.removeClass( 'wlpf-group-collapsed' );
                content.slideDown( 500 );
            } else {
                group.addClass( 'wlpf-group-collapsed' );
                content.slideUp( 500 );
            }
        } );

        /**
         * Process filter real apply action.
         */
        $( document ).on( 'wlpf_process_filter_real_apply_action', function ( e, thisFilter ) {
            e.preventDefault();

            let groupItem = thisFilter.attr( 'data-wlpf-group-item' ),
                applyAction = thisFilter.attr( 'data-wlpf-apply-action' ),
                groupApplyAction = thisFilter.attr( 'data-wlpf-group-apply-action' ),
                realApplyAction = '';

            if ( '1' === groupItem ) {
                if ( 'auto' === groupApplyAction ) {
                    realApplyAction = 'auto';
                } else if ( 'button' === groupApplyAction ) {
                    realApplyAction = 'button';
                } else if ( 'individual' === groupApplyAction ) {
                    if ( 'auto' === applyAction ) {
                        realApplyAction = 'auto';
                    } else {
                        realApplyAction = 'button';
                    }
                }
            } else if ( 'auto' === applyAction ) {
                realApplyAction = 'auto';
            } else {
                realApplyAction = 'button';
            }

            return realApplyAction;
        } );

        /**
         * Process filter real clear action.
         */
        $( document ).on( 'wlpf_process_filter_real_clear_action', function ( e, thisFilter ) {
            e.preventDefault();

            let groupItem = thisFilter.attr( 'data-wlpf-group-item' ),
                clearAction = thisFilter.attr( 'data-wlpf-clear-action' ),
                groupClearAction = thisFilter.attr( 'data-wlpf-group-clear-action' ),
                realClearAction = '';

            if ( '1' === groupItem ) {
                if ( 'auto' === groupClearAction ) {
                    realClearAction = 'auto';
                } else if ( 'button' === groupClearAction ) {
                    realClearAction = 'button';
                } else if ( 'individual' === groupClearAction ) {
                    if ( 'auto' === clearAction ) {
                        realClearAction = 'auto';
                    } else {
                        realClearAction = 'button';
                    }
                }
            } else if ( 'auto' === clearAction ) {
                realClearAction = 'auto';
            } else {
                realClearAction = 'button';
            }

            return realClearAction;
        } );

        /**
         * Fix page query parameter.
         */
        ( function () {
            let url = wlpfGetFilterPageUrl(),
                prefix = wlpfGetQueryArgsPrefix(),
                key = prefix + 'page',
                pathnameArray = url.pathname.split( '/' ),
                pageIndexInPathname = pathnameArray.indexOf( 'page' ),
                searchParam = url.searchParams.get( key );

            if ( 'undefined' === typeof searchParam || null === searchParam || isNaN( searchParam ) ) {
                return;
            }

            if ( 0 <= pageIndexInPathname ) {
                url.searchParams.delete( key );
            }

            window.history.pushState( {}, '', url );
        } )();

    }

    // Frontend init.
    if ( wlpfGetElementorEditorMode() ) {
        $( window ).on( 'elementor/frontend/init', function () {
            elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', function ( $scope, $ ) {
                frontendInitializer();
            } );
        } );
    } else {
        $( document ).ready( function () {
            frontendInitializer();
        } );
    }

} )( jQuery );