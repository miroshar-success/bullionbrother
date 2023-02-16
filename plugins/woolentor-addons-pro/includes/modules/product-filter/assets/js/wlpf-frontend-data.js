/**
 * Frontend data scripts.
 */

/* global jQuery */
;( function ( $ ) {
    'use strict';

    /**
     * Format terms.
     */
    function fotmatTerms( terms ) {
        if ( 'object' === typeof terms ) {
            terms = Object.keys( terms );
        } else {
            terms = [];
        }

        return terms;
    }

    /**
     * Get taxonomy data.
     */
    function getTaxonomyData( filter, data ) {
        let key = filter.attr( 'data-wlpf-taxonomy' ),
            selectedTerms = filter.attr( 'data-wlpf-selected-terms' ),
            withChildren = filter.attr( 'data-wlpf-with-children-terms' ),
            termsOperator = filter.attr( 'data-wlpf-terms-operator' ),
            taxFilter = ( data.hasOwnProperty( 'tax_filter' ) ? data.tax_filter : {} ),
            terms = [];

        if ( 'string' === typeof selectedTerms && 0 < selectedTerms.length ) {
            selectedTerms = JSON.parse( selectedTerms );
            terms = fotmatTerms( selectedTerms );
        }

        if ( 0 < terms.length ) {
            withChildren = ( '1' === withChildren ? true : false );

            if ( 'AND' === termsOperator ) {
                withChildren = false;
            }

            taxFilter[ key ] = {
                taxonomy: key,
                field: 'term_id',
                terms: terms,
                operator: termsOperator,
                include_children: withChildren,
                terms_info: selectedTerms,
            };
        } else {
            taxFilter[ key ] = {};
        }

        data.tax_filter = taxFilter;

        return data;
    }

    /**
     * Get attribute data.
     */
    function getAttributeData( filter, data ) {
        let key = filter.attr( 'data-wlpf-attribute' ),
            selectedTerms = filter.attr( 'data-wlpf-selected-terms' ),
            withChildren = filter.attr( 'data-wlpf-with-children-terms' ),
            termsOperator = filter.attr( 'data-wlpf-terms-operator' ),
            attrFilter = ( data.hasOwnProperty( 'attr_filter' ) ? data.attr_filter : {} ),
            terms = [];

        if ( 'string' === typeof selectedTerms && 0 < selectedTerms.length ) {
            selectedTerms = JSON.parse( selectedTerms );
            terms = fotmatTerms( selectedTerms );
        }

        if ( 0 < terms.length ) {
            withChildren = ( '1' === withChildren ? true : false );

            if ( 'AND' === termsOperator ) {
                withChildren = false;
            }

            attrFilter[ key ] = {
                taxonomy: key,
                field: 'term_id',
                terms: terms,
                operator: termsOperator,
                include_children: withChildren,
                terms_info: selectedTerms,
            };
        } else {
            attrFilter[ key ] = {};
        }

        data.attr_filter = attrFilter;

        return data;
    }

    /**
     * Get author data.
     */
    function getAuthorData( filter, data ) {
        let key = 'author',
            selectedTerms = filter.attr( 'data-wlpf-selected-terms' ),
            terms = [];

        if ( 'string' === typeof selectedTerms && 0 < selectedTerms.length ) {
            selectedTerms = JSON.parse( selectedTerms );
            terms = fotmatTerms( selectedTerms );
        }

        if ( 0 < terms.length ) {
            data[ key ] = {
                terms: terms,
                terms_info: selectedTerms,
            };
        } else {
            data[ key ] = {};
        }

        return data;
    }

    /**
     * Get sorting data.
     */
    function getSortingData( filter, data ) {
        let key = 'sorting',
            selectedTerms = filter.attr( 'data-wlpf-selected-terms' ),
            terms = [];

        if ( 'string' === typeof selectedTerms && 0 < selectedTerms.length ) {
            selectedTerms = JSON.parse( selectedTerms );
            terms = fotmatTerms( selectedTerms );
        }

        if ( 0 < terms.length ) {
            data[ key ] = {
                term: terms[ 0 ],
                terms_info: selectedTerms,
            };
        } else {
            data[ key ] = '';
        }

        return data;
    }

    /**
     * Get prices data.
     */
    function getPricesData( filter, data ) {
        let key = 'prices',
            minPrice = filter.attr( 'data-wlpf-range-min-price' ),
            maxPrice = filter.attr( 'data-wlpf-range-max-price' ),
            minValue = filter.attr( 'data-wlpf-range-min-value' ),
            maxValue = filter.attr( 'data-wlpf-range-max-value' ),
            prices = {};

        if ( ( 0 < minValue.length ) && ( 0 < maxValue.length ) && ( ( minPrice !== minValue ) || ( maxPrice !== maxValue ) ) ) {
            prices = {
                min: minValue,
                max: maxValue,
            };

            data[ key ] = prices;
        } else {
            data[ key ] = {};
        }

        return data;
    }

    /**
     * Get search data.
     */
    function getSearchData( filter, data ) {
        let key = 'search',
            insertedKeyword = filter.attr( 'data-inserted-keyword' );

        if ( 0 < insertedKeyword.length ) {
            data[ key ] = insertedKeyword;
        } else {
            data[ key ] = '';
        }

        return data;
    }

    /**
     * Get fixed data.
     */
    function getFixedData( filter, data ) {
        let fixedArgs = filter.attr( 'data-wlpf-fixed-filter-args' ),
            search = '',
            sorting = '',
            taxonomy = '',
            taxonomyTerm = '';

        if ( 'string' === typeof fixedArgs && 0 < fixedArgs.length ) {
            fixedArgs = JSON.parse( fixedArgs );

            search = ( fixedArgs.hasOwnProperty( 'search' ) ? fixedArgs.search : '' );
            sorting = ( fixedArgs.hasOwnProperty( 'sorting' ) ? fixedArgs.sorting : '' );
            taxonomy = ( fixedArgs.hasOwnProperty( 'taxonomy' ) ? fixedArgs.taxonomy : '' );
            taxonomyTerm = ( fixedArgs.hasOwnProperty( 'taxonomy_term' ) ? fixedArgs.taxonomy_term : '' );

            data.fixed_filter = {
                search: search,
                sorting: sorting,
                taxonomy: taxonomy,
                taxonomy_term: taxonomyTerm,
            };
        }

        return data;
    }

    /**
     * Get filter items data.
     */
    function getFilterItemsData( filters, ignoreApplyActionTaken ) {
        let data = {};

        for ( let i = 0; i < filters.length; i++ ) {
            let filter = $( filters[i] ),
                applyActionTaken = filter.attr( 'data-wlpf-apply-action-taken' );

            if ( ( true === ignoreApplyActionTaken ) || ( '1' === applyActionTaken ) ) {
                if ( filter.hasClass( 'wlpf-taxonomy-filter' ) ) {
                    data = getTaxonomyData( filter, data );
                } else if ( filter.hasClass( 'wlpf-attribute-filter' ) ) {
                    data = getAttributeData( filter, data );
                } else if ( filter.hasClass( 'wlpf-author-filter' ) ) {
                    data = getAuthorData( filter, data );
                } else if ( filter.hasClass( 'wlpf-sorting-filter' ) ) {
                    data = getSortingData( filter, data );
                } else if ( filter.hasClass( 'wlpf-price-filter' ) ) {
                    data = getPricesData( filter, data );
                } else if ( filter.hasClass( 'wlpf-search-filter' ) ) {
                    data = getSearchData( filter, data );
                }
            }

            if ( ! data.hasOwnProperty( 'fixed_filter' ) ) {
                data = getFixedData( filter, data );
            }
        }

        return data;
    }

    /**
     * Get filters data.
     */
    function getFiltersData( thisFilter, groupItem ) {
        let filters = {},
            data = {};

        if ( '1' === groupItem ) {
            filters = thisFilter.closest( '.wlpf-group-content' ).find( '.wlpf-filter-wrap' );
        } else {
            filters = $( '.wlpf-filter-wrap[data-wlpf-group-item="0"]' );
        }

        data = getFilterItemsData( filters );

        return data;
    }

    /**
     * Get group data.
     */
    function getGroupData( thisGroup ) {
        let filters = thisGroup.find( '.wlpf-filter-wrap' ),
            data = getFilterItemsData( filters, true );

        return data;
    }

    /**
     * Process filters data.
     */
    $( document ).on( 'wlpf_process_filters_data', function( e, thisFilter, groupItem ) {
        e.preventDefault();

        return getFiltersData( thisFilter, groupItem );
    } );

    /**
     * Process group data.
     */
    $( document ).on( 'wlpf_process_group_data', function( e, thisGroup ) {
        e.preventDefault();

        return getGroupData( thisGroup );
    } );

} )( jQuery );