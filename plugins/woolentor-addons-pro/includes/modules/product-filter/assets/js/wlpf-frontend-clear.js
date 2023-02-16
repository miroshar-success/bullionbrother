/**
 * Frontend clear scripts.
 */

/* global jQuery */
;( function ( $ ) {
    'use strict';

    /**
     * Clear term fields.
     */
    function clearTermFields( termFields ) {
        for ( let i = 0; i < termFields.length; i++ ) {
            let termField = $( termFields[i] );

            termField.prop( 'checked', false ).trigger( 'change' );
        }
    }

    /**
     * Clear terms select.
     */
    function clearTermsSelect( termsSelect ) {
        let value = termsSelect.val();

        value = ( ( '0' === value ) ? 0 : value );

        if ( 0 !== value ) {
            termsSelect.val( 0 ).trigger( 'change' ).niceSelect( 'update' );
        }
    }

    /**
     * Clear taxonomy data.
     */
    function clearTaxonomyData( filter ) {
        let termFields = filter.find( '.wlpf-term-field:checked' ),
            termsSelect = filter.find( '.wlpf-terms-nice-select' );

        if ( 0 < termFields.length ) {
            clearTermFields( termFields );
        } else if ( 0 < termsSelect.length ) {
            clearTermsSelect( termsSelect );
        }
    }

    /**
     * Clear attribute data.
     */
    function clearAttributeData( filter ) {
        let termFields = filter.find( '.wlpf-term-field:checked' ),
            termsSelect = filter.find( '.wlpf-terms-nice-select' );

        if ( 0 < termFields.length ) {
            clearTermFields( termFields );
        } else if ( 0 < termsSelect.length ) {
            clearTermsSelect( termsSelect );
        }
    }

    /**
     * Clear author data.
     */
    function clearAuthorData( filter ) {
        let termFields = filter.find( '.wlpf-term-field:checked' ),
            termsSelect = filter.find( '.wlpf-terms-nice-select' );

        if ( 0 < termFields.length ) {
            clearTermFields( termFields );
        } else if ( 0 < termsSelect.length ) {
            clearTermsSelect( termsSelect );
        }
    }

    /**
     * Clear sorting data.
     */
    function clearSortingData( filter ) {
        let termFields = filter.find( '.wlpf-term-field:checked' ),
            termsSelect = filter.find( '.wlpf-terms-nice-select' );

        if ( 0 < termFields.length ) {
            clearTermFields( termFields );
        } else if ( 0 < termsSelect.length ) {
            clearTermsSelect( termsSelect );
        }
    }

    /**
     * Clear prices data.
     */
    function clearPricesData( filter ) {
        let minPrice = filter.attr( 'data-wlpf-range-min-price' ),
            maxPrice = filter.attr( 'data-wlpf-range-max-price' ),
            minValue = filter.attr( 'data-wlpf-range-min-value' ),
            maxValue = filter.attr( 'data-wlpf-range-max-value' ),
            minPriceField = filter.find( '.wlpf-min-price-field' ),
            maxPriceField = filter.find( '.wlpf-max-price-field' ),
            rangeUI = filter.find( '.wlpf-price-range-ui' );

        minPrice = wlpfNumberToAbsInt( minPrice );
        maxPrice = wlpfNumberToAbsInt( maxPrice );
        minValue = wlpfNumberToAbsInt( minValue );
        maxValue = wlpfNumberToAbsInt( maxValue );

        if ( ( minPrice !== minValue ) || ( maxPrice !== maxValue ) ) {
            minPriceField.val( minPrice ).trigger( 'change' );
            maxPriceField.val( maxPrice ).trigger( 'change' );

            rangeUI.slider( 'option', 'values', [ minPrice, maxPrice ] );
        }
    }

    /**
     * Clear search data.
     */
    function clearSearchData( filter ) {
        let searchField = filter.find( '.wlpf-search-field' ),
            searchValue = searchField.val();

        if ( '' !== searchValue.trim() ) {
            searchField.val( '' ).trigger( 'change' );
        }
    }

    /**
     * Clear filter items.
     */
    function clearFilterItems( filters ) {
        for ( let i = 0; i < filters.length; i++ ) {
            let filter = $( filters[i] ),
                applyAction = processFilterRealApplyAction( filter ),
                applyActionButton = filter.find( '.wlpf-filter-apply-action-button' );

            if ( filter.hasClass( 'wlpf-taxonomy-filter' ) ) {
                clearTaxonomyData( filter );
            } else if ( filter.hasClass( 'wlpf-attribute-filter' ) ) {
                clearAttributeData( filter );
            } else if ( filter.hasClass( 'wlpf-author-filter' ) ) {
                clearAuthorData( filter );
            } else if ( filter.hasClass( 'wlpf-sorting-filter' ) ) {
                clearSortingData( filter );
            } else if ( filter.hasClass( 'wlpf-price-filter' ) ) {
                clearPricesData( filter );
            } else if ( filter.hasClass( 'wlpf-search-filter' ) ) {
                clearSearchData( filter );
            }

            if ( ( 'button' === applyAction ) && ( 0 < applyActionButton.length ) ) {
                applyActionButton.trigger( 'click' );
            }
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
     * Process filter real clear action.
     */
    function processFilterRealClearAction( thisFilter ) {
        let event = $.Event( 'wlpf_process_filter_real_clear_action' );

        $( document ).trigger( event, [ thisFilter ] );

        return event.result;
    }

    /**
     * Filter clear action button on click.
     */
    $( document ).on( 'click', '.wlpf-filter-wrap .wlpf-filter-clear-action-button', function ( e ) {
        let thisButton = $( this ),
            thisFilter = thisButton.closest( '.wlpf-filter-wrap' ),
            clearAction = processFilterRealClearAction( thisFilter );

        if ( 'button' === clearAction ) {
            clearFilterItems( thisFilter );
        }
    } );

    /**
     * Group clear action button on click.
     */
    $( document ).on( 'click', '.wlpf-group-wrap .wlpf-group-clear-action-button', function ( e ) {
        let thisButton = $( this ),
            thisGroup = thisButton.closest( '.wlpf-group-wrap' ),
            clearAction = thisGroup.attr( 'data-wlpf-clear-action' ),
            applyAction = thisGroup.attr( 'data-wlpf-apply-action' ),
            applyActionButton = thisGroup.find( '.wlpf-group-apply-action-button' ),
            filters = thisGroup.find( '.wlpf-filter-wrap' );

        if ( 'button' === clearAction ) {
            clearFilterItems( filters );
        }

        if ( ( 'button' === applyAction ) && ( 0 < applyActionButton.length ) ) {
            applyActionButton.trigger( 'click' );
        }
    } );

} )( jQuery );