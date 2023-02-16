/**
 * Frontend map scripts.
 */

/* global jQuery */
;( function ( $ ) {
    'use strict';

    /**
     * Taxonomy map.
     */
    function taxonomyMap( url, prefix, data ) {
        if ( ! data.hasOwnProperty( 'tax_filter' ) ) {
            return url;
        }

        let taxonomies = data.tax_filter;

        for ( const property in taxonomies ) {
            let taxonomy = taxonomies[ property ],
                terms = taxonomy.terms_info,
                key = prefix + property,
                value = '';

            if ( 'object' === typeof terms ) {
                for ( const property in terms ) {
                    let term = terms[ property ],
                        slug = term.slug;

                    value += ( ( 0 < value.length ) ? ( ',' + slug ) : slug );
                }
            }

            url.searchParams.delete( key );

            if ( ( 'string' === typeof value ) && ( 0 < value.length ) ) {
                url.searchParams.set( key, value );
            }
        }

        return url;
    }

    /**
     * Attribute map.
     */
    function attributeMap( url, prefix, data ) {
        if ( ! data.hasOwnProperty( 'attr_filter' ) ) {
            return url;
        }

        let attributes = data.attr_filter;

        for ( const property in attributes ) {
            let attribute = attributes[ property ],
                terms = attribute.terms_info,
                key = prefix + property,
                value = '';

            if ( 'object' === typeof terms ) {
                for ( const property in terms ) {
                    let term = terms[ property ],
                        slug = term.slug;

                    value += ( ( 0 < value.length ) ? ( ',' + slug ) : slug );
                }
            }

            url.searchParams.delete( key );

            if ( ( 'string' === typeof value ) && ( 0 < value.length ) ) {
                url.searchParams.set( key, value );
            }
        }

        return url;
    }

    /**
     * Author map.
     */
    function authorMap( url, prefix, data ) {
        if ( ! data.hasOwnProperty( 'author' ) ) {
            return url;
        }

        let author = data.author,
            terms = author.terms_info,
            key = prefix + 'vendor',
            value = '';

        if ( 'object' === typeof terms ) {
            for ( const property in terms ) {
                let term = terms[ property ],
                    username = term.username;

                value += ( ( 0 < value.length ) ? ( ',' + username ) : username );
            }
        }

        url.searchParams.delete( key );

        if ( ( 'string' === typeof value ) && ( 0 < value.length ) ) {
            url.searchParams.set( key, value );
        }

        return url;
    }

    /**
     * Sorting map.
     */
    function sortingMap( url, prefix, data ) {
        if ( ! data.hasOwnProperty( 'sorting' ) ) {
            return url;
        }

        let sorting = data.sorting,
            key = prefix + 'sorting',
            value = sorting.term;

        url.searchParams.delete( key );
        url.searchParams.delete( 'orderby' );
        url.searchParams.delete( 'order' );

        if ( ( 'string' === typeof value ) && ( 0 < value.length ) ) {
            url.searchParams.set( key, value );
        }

        return url;
    }

    /**
     * Prices map.
     */
    function pricesMap( url, prefix, data ) {
        if ( ! data.hasOwnProperty( 'prices' ) ) {
            return url;
        }

        let prices = data.prices,
            minKey = prefix + 'min_price',
            maxKey = prefix + 'max_price',
            minValue = ( prices.hasOwnProperty( 'min' ) ? prices.min : '' ),
            maxValue = ( prices.hasOwnProperty( 'max' ) ? prices.max : minValue );

        url.searchParams.delete( minKey );
        url.searchParams.delete( maxKey );

        if ( ( 'string' === typeof minValue ) && ( 0 < minValue.length ) ) {
            url.searchParams.set( minKey, minValue );
            url.searchParams.set( maxKey, maxValue );
        }

        return url;
    }

    /**
     * Search map.
     */
    function searchMap( url, prefix, data ) {
        if ( ! data.hasOwnProperty( 'search' ) ) {
            return url;
        }

        let value = data.search,
            key = prefix + 'search';

        url.searchParams.delete( key );
        url.searchParams.delete( 's' );

        if ( ( 'string' === typeof value ) && ( 0 < value.length ) ) {
            url.searchParams.set( key, value );
        }

        return url;
    }

    /**
     * Page map.
     */
    function pageMap( url, prefix, data ) {
        let pathnameArray = url.pathname.split( '/' ),
            pageIndexInPathname = pathnameArray.indexOf( 'page' ),
            page = ( data.hasOwnProperty( 'page' ) ? data.page : 0 ),
            key = prefix + 'page';

        url.searchParams.delete( 'paged' );
        url.searchParams.delete( 'product-page' );

        if ( 1 < page ) {
            url.searchParams.set( key, page );
        } else {
            url.searchParams.delete( key );
        }

        if ( 0 <= pageIndexInPathname ) {
            pathnameArray.splice( pageIndexInPathname, 2 );
            url.pathname = pathnameArray.join( '/' );
        }

        return url;
    }

    /**
     * Clear map.
     */
    function clearMap( url, prefix ) {
        let params = url.searchParams;

        params.forEach( ( value, key ) => {
            if ( key.startsWith( prefix ) ) {
                url.searchParams.delete( key );
            }
        } );

        return url;
    }

    /**
     * Do filters map.
     */
    function doFiltersMap( data ) {
        let url = wlpfGetFilterPageUrl(),
            prefix = wlpfGetQueryArgsPrefix();

        url = clearMap( url, prefix );

        if ( true === wlpfGetActiveMap() ) {
            url = taxonomyMap( url, prefix, data );
            url = attributeMap( url, prefix, data );
            url = authorMap( url, prefix, data );
            url = sortingMap( url, prefix, data );
            url = pricesMap( url, prefix, data );
            url = searchMap( url, prefix, data );
            url = pageMap( url, prefix, data );
        }

        url = url.toString();
        url = url.replaceAll( '%2C', ',' );

        wlpf_data.filter_page_url = url;

        window.history.pushState( {}, '', url );
    };

    /**
     * Process filters map.
     */
    $( document ).on( 'wlpf_process_filters_map', ( e, data ) => {
        e.preventDefault();

        doFiltersMap( data );
    } );

} )( jQuery );