/**
 * Frontend intac scripts.
 */

/* global jQuery */
;( function ( $ ) {
    'use strict';

    /**
     * Process term fields.
     */
    function processTermFields( thisField, thisFilter ) {
        let termFields = thisFilter.find( '.wlpf-term-field:checked' ),
            availableTerms = thisFilter.attr( 'data-wlpf-available-terms' ),
            selectedTerms = '';

        availableTerms = JSON.parse( availableTerms );

        for ( let i = 0; i < termFields.length; i++ ) {
            let termField = $( termFields[i] ),
                termFieldValue = termField.val();

            if ( termField.prop( 'checked' ) && availableTerms.hasOwnProperty( termFieldValue ) ) {
                selectedTerms = ( ( 'object' === typeof selectedTerms ) ? selectedTerms : {} );
                selectedTerms[ termFieldValue ] = availableTerms[ termFieldValue ];
            }
        }

        if ( 'object' === typeof selectedTerms ) {
            selectedTerms = JSON.stringify( selectedTerms );
        }

        thisFilter.attr( 'data-wlpf-selected-terms', selectedTerms );
    }

    /**
     * Process terms select.
     */
    function processTermsSelect( thisSelect, thisFilter ) {
        let termsSelectValue = thisSelect.val(),
            availableTerms = thisFilter.attr( 'data-wlpf-available-terms' ),
            selectedTerms = '';

        availableTerms = JSON.parse( availableTerms );

        if ( availableTerms.hasOwnProperty( termsSelectValue ) ) {
            selectedTerms = ( ( 'object' === typeof selectedTerms ) ? selectedTerms : {} );
            selectedTerms[ termsSelectValue ] = availableTerms[ termsSelectValue ];
        }

        if ( 'object' === typeof selectedTerms ) {
            selectedTerms = JSON.stringify( selectedTerms );
        }

        thisFilter.attr( 'data-wlpf-selected-terms', selectedTerms );
    }

    /**
     * Process prices field.
     */
    function processPricesField( thisField, thisFilter ) {
        let minPrice = thisFilter.find( '.wlpf-min-price-field' ).val(),
            maxPrice = thisFilter.find( '.wlpf-max-price-field' ).val();

        minPrice = minPrice.trim();
        maxPrice = maxPrice.trim();

        thisFilter.attr( 'data-wlpf-range-min-value', minPrice );
        thisFilter.attr( 'data-wlpf-range-max-value', maxPrice );
    }

    /**
     * Process keyword field.
     */
    function processKeywordField( thisField, thisFilter ) {
        let keywordFieldValue = thisField.val(),
            insertedTerms = keywordFieldValue.trim();

        thisFilter.attr( 'data-inserted-keyword', insertedTerms );
    }

    /**
     * Process page number.
     */
    function processPageNumber( link ) {
        let pageLink = wlpfLinkToAbsLink( link ),
            pageUrl = new URL( pageLink ),
            pageNumber = 0;

        let pagePathnameArray = pageUrl.pathname.split( '/' ),
            pagePageIndexInPathname = pagePathnameArray.indexOf( 'page' );

        if ( ( 0 === pageNumber ) && ( null !== pageUrl.searchParams.get( 'paged' ) ) ) {
            pageNumber = pageUrl.searchParams.get( 'paged' );
            pageNumber = wlpfNumberToAbsInt( pageNumber );
        }

        if ( ( 0 === pageNumber ) && ( null !== pageUrl.searchParams.get( 'product-page' ) ) ) {
            pageNumber = pageUrl.searchParams.get( 'product-page' );
            pageNumber = wlpfNumberToAbsInt( pageNumber );
        }

        if ( ( 0 === pageNumber ) && ( 0 <= pagePageIndexInPathname ) && pagePathnameArray.hasOwnProperty( pagePageIndexInPathname + 1 ) ) {
            pageNumber = pagePathnameArray[ pagePageIndexInPathname + 1 ];
            pageNumber = wlpfNumberToAbsInt( pageNumber );
        }

        wlpf_data.filter_page_number = pageNumber;
    }

    /**
     * Process filters data.
     */
    function processFiltersData( thisFilter, groupItem ) {
        let event = $.Event( 'wlpf_process_filters_data' );

        $( document ).trigger( event, [ thisFilter, groupItem ] );

        return event.result;
    }

    /**
     * Process group data.
     */
    function processGroupData( thisGroup ) {
        let event = $.Event( 'wlpf_process_group_data' );

        $( document ).trigger( event, [ thisGroup ] );

        return event.result;
    }

    /**
     * Process filters map.
     */
    function processFiltersMap( data ) {
        $( document ).trigger( 'wlpf_process_filters_map', [ data ] );
    }

    /**
     * Process ajax filter.
     */
    function processAjaxFilter( data, page ) {
        $( document ).trigger( 'wlpf_process_ajax_filter', [ data, page ] );
    }

    /**
     * Process none ajax filter.
     */
    function processNoneAjaxFilter() {
        $( document ).trigger( 'wlpf_process_none_ajax_filter' );
    }

    /**
     * Process filters store.
     */
    function processFiltersStore( data ) {
        wlpf_data.filters_data = data;
    }

    /**
     * Process page apply action.
     */
    function processPageApplyAction() {
        let data = wlpfGetFiltersData(),
            page = wlpfGetFilterPageNumber();

        data.page = ( ( 0 < page ) ? page : 1 );

        processFiltersMap( data );

        if ( true === wlpfGetAjaxFilter() ) {
            processAjaxFilter( data );
            processFiltersStore( data );
        } else {
            processNoneAjaxFilter();
        }
    }

    /**
     * Process filter apply action.
     */
    function processFilterApplyAction( thisFilter ) {
        let groupItem = thisFilter.attr( 'data-wlpf-group-item' ),
            data = processFiltersData( thisFilter, groupItem );

        processFiltersMap( data );

        if ( true === wlpfGetAjaxFilter() ) {
            processAjaxFilter( data );
            processFiltersStore( data );
        } else {
            processNoneAjaxFilter();
        }
    }

    /**
     * Process group apply action.
     */
    function processGroupApplyAction( thisGroup ) {
        let data = processGroupData( thisGroup );

        processFiltersMap( data );

        if ( true === wlpfGetAjaxFilter() ) {
            processAjaxFilter( data );
            processFiltersStore( data );
        } else {
            processNoneAjaxFilter();
        }
    }

    /**
     * Process filter real apply action.
     */
    function processFilterRealApplyAction( thisFilter ) {
        let event = $.Event( 'wlpf_process_filter_real_apply_action' );

        $( document ).trigger( event, [ thisFilter ] );

        return event.result;
    }

    /**
     * Process apply action taken.
     */
    function processApplyActionTaken( thisFilter, value ) {
        thisFilter.attr( 'data-wlpf-apply-action-taken', value );
    }

    /**
     * Taxonomy term field on change.
     */
    $( document ).on( 'change', '.wlpf-taxonomy-filter .wlpf-term-field', function ( e ) {
        let thisField = $( this ),
            thisFilter = thisField.closest( '.wlpf-taxonomy-filter' ),
            applyAction = processFilterRealApplyAction( thisFilter );

        processTermFields( thisField, thisFilter );

        if ( 'auto' === applyAction ) {
            processFilterApplyAction( thisFilter );
        } else {
            processApplyActionTaken( thisFilter, '0' );
        }
    } );

    /**
     * Taxonomy term select on change.
     */
    $( document ).on( 'change', '.wlpf-taxonomy-filter .wlpf-terms-select', function ( e ) {
        let thisSelect = $( this ),
            thisFilter = thisSelect.closest( '.wlpf-taxonomy-filter' ),
            applyAction = processFilterRealApplyAction( thisFilter );

        processTermsSelect( thisSelect, thisFilter );

        if ( 'auto' === applyAction ) {
            processFilterApplyAction( thisFilter );
        } else {
            processApplyActionTaken( thisFilter, '0' );
        }
    } );

    /**
     * Attribute term field on change.
     */
    $( document ).on( 'change', '.wlpf-attribute-filter .wlpf-term-field', function ( e ) {
        let thisField = $( this ),
            thisFilter = thisField.closest( '.wlpf-attribute-filter' ),
            applyAction = processFilterRealApplyAction( thisFilter );

        processTermFields( thisField, thisFilter );

        if ( 'auto' === applyAction ) {
            processFilterApplyAction( thisFilter );
        } else {
            processApplyActionTaken( thisFilter, '0' );
        }
    } );

    /**
     * Attribute term select on change.
     */
    $( document ).on( 'change', '.wlpf-attribute-filter .wlpf-terms-select', function ( e ) {
        let thisSelect = $( this ),
            thisFilter = thisSelect.closest( '.wlpf-attribute-filter' ),
            applyAction = processFilterRealApplyAction( thisFilter );

        processTermsSelect( thisSelect, thisFilter );

        if ( 'auto' === applyAction ) {
            processFilterApplyAction( thisFilter );
        } else {
            processApplyActionTaken( thisFilter, '0' );
        }
    } );

    /**
     * Author term field on change.
     */
    $( document ).on( 'change', '.wlpf-author-filter .wlpf-term-field', function ( e ) {
        let thisField = $( this ),
            thisFilter = thisField.closest( '.wlpf-author-filter' ),
            applyAction = processFilterRealApplyAction( thisFilter );

        processTermFields( thisField, thisFilter );

        if ( 'auto' === applyAction ) {
            processFilterApplyAction( thisFilter );
        } else {
            processApplyActionTaken( thisFilter, '0' );
        }
    } );

    /**
     * Author term select on change.
     */
    $( document ).on( 'change', '.wlpf-author-filter .wlpf-terms-select', function ( e ) {
        let thisSelect = $( this ),
            thisFilter = thisSelect.closest( '.wlpf-author-filter' ),
            applyAction = processFilterRealApplyAction( thisFilter );

        processTermsSelect( thisSelect, thisFilter );

        if ( 'auto' === applyAction ) {
            processFilterApplyAction( thisFilter );
        } else {
            processApplyActionTaken( thisFilter, '0' );
        }
    } );

    /**
     * Sorting term field on change.
     */
    $( document ).on( 'change', '.wlpf-sorting-filter .wlpf-term-field', function ( e ) {
        let thisField = $( this ),
            thisFilter = thisField.closest( '.wlpf-sorting-filter' ),
            applyAction = processFilterRealApplyAction( thisFilter );

        processTermFields( thisField, thisFilter );

        if ( 'auto' === applyAction ) {
            processFilterApplyAction( thisFilter );
        } else {
            processApplyActionTaken( thisFilter, '0' );
        }
    } );

    /**
     * Sorting term select on change.
     */
    $( document ).on( 'change', '.wlpf-sorting-filter .wlpf-terms-select', function ( e ) {
        let thisSelect = $( this ),
            thisFilter = thisSelect.closest( '.wlpf-sorting-filter' ),
            applyAction = processFilterRealApplyAction( thisFilter );

        processTermsSelect( thisSelect, thisFilter );

        if ( 'auto' === applyAction ) {
            processFilterApplyAction( thisFilter );
        } else {
            processApplyActionTaken( thisFilter, '0' );
        }
    } );

    /**
     * Prices field on change.
     */
    $( document ).on( 'change', '.wlpf-price-filter .wlpf-price-range-field input', function ( e ) {
        let thisField = $( this ),
            thisFilter = thisField.closest( '.wlpf-price-filter' ),
            applyAction = processFilterRealApplyAction( thisFilter );

        processPricesField( thisField, thisFilter );

        if ( 'auto' === applyAction ) {
            processFilterApplyAction( thisFilter );
        } else {
            processApplyActionTaken( thisFilter, '0' );
        }
    } );

    /**
     * Search keyword field on change.
     */
    $( document ).on( 'change keyup', '.wlpf-search-filter .wlpf-search-field', function ( e ) {
        let thisField = $( this ),
            thisFilter = thisField.closest( '.wlpf-search-filter' ),
            applyAction = processFilterRealApplyAction( thisFilter );

        if ( ( 'change' === e.type ) && ( thisFilter.attr( 'data-inserted-keyword' ) === thisField.val() ) ) {
            return;
        }

        processKeywordField( thisField, thisFilter );

        if ( 'auto' === applyAction ) {
            processFilterApplyAction( thisFilter );
        } else {
            processApplyActionTaken( thisFilter, '0' );
        }
    } );

    /**
     * Page number on click.
     */
    $( document ).on( 'click', '.wl-filterable-products-wrap a.page-numbers,' +
    '.wl-filterable-products-wrap a.page-number,' +
    '.wlpf-products-wrap a.page-numbers,' +
    '.wlpf-products-wrap a.page-number', function ( e ) {
        e.preventDefault();

        let pageLink = $( this ).attr( 'href' );

        if ( ( 'string' === typeof pageLink ) && ( 0 < pageLink.length ) ) {
            processPageNumber( pageLink );
            processPageApplyAction();
        }
    } );

    /**
     * Filter apply action button on click.
     */
    $( document ).on( 'click', '.wlpf-filter-wrap .wlpf-filter-apply-action-button', function ( e ) {
        let thisButton = $( this ),
            thisFilter = thisButton.closest( '.wlpf-filter-wrap' ),
            applyAction = processFilterRealApplyAction( thisFilter );

        if ( 'button' === applyAction ) {
            processApplyActionTaken( thisFilter, '1' );
            processFilterApplyAction( thisFilter );
        }
    } );

    /**
     * Group apply action button on click.
     */
    $( document ).on( 'click', '.wlpf-group-wrap .wlpf-group-apply-action-button', function ( e ) {
        let thisButton = $( this ),
            thisGroup = thisButton.closest( '.wlpf-group-wrap' ),
            applyAction = thisGroup.attr( 'data-wlpf-apply-action' );

        if ( 'button' === applyAction ) {
            processGroupApplyAction( thisGroup );
        }
    } );

} )( jQuery );