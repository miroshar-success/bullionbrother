/**
 * Base scripts.
 */

// Action timeout.
var wlpfActionTimeout;

/**
 * Number to absolute integer.
 */
function wlpfNumberToAbsInt( num = 0 ) {
    if ( ! isNaN( num ) ) {
        num = parseFloat( num );
        num = Math.abs( num );
        num = Math.floor( num );
    } else {
        num = 0;
    }

    return num;
}

/**
 * Get random absolute integer.
 */
function wlpfGetRandomAbsInt( min = 0, max = 9 ) {
    min = wlpfNumberToAbsInt( min );
    max = wlpfNumberToAbsInt( max );

    return ( Math.floor( Math.random() * ( max - min + 1 ) + min ) );
}

/**
 * Get unique Id.
 */
function wlpfGetUniqueId( uniqueId = 0 ) {
    uniqueId = wlpfNumberToAbsInt( uniqueId );
    uniqueId = ( ( 0 < uniqueId ) ? uniqueId : ( Date.now()  + '' + wlpfGetRandomAbsInt( 1, 9 ) ) );

    return wlpfNumberToAbsInt( uniqueId );
}

/**
 * Link to absolute link.
 */
function wlpfLinkToAbsLink( link = '' ) {
    let location = window.location,
        protocol = location.protocol,
        origin = location.origin;

    if ( ( 0 !== link.indexOf( 'http://' ) ) && ( 0 !== link.indexOf( 'https://' ) ) ) {
        if ( 0 === link.indexOf( '//' ) ) {
            link = protocol + '' + link;
        } else if( 0 === link.indexOf( '/' ) ) {
            link = origin + '' + link;
        } else {
            link = origin + '/' + link;
        }
    }

    return link;
}

/**
 * Get data.
 */
function wlpfGetData() {
    let data = {};

    if ( 'object' === typeof wlpf_data ) {
        data = wlpf_data;
    }

    return data;
}

/**
 * Get ajax url.
 */
function wlpfGetAjaxUrl() {
    let data = wlpfGetData(),
        ajaxUrl = '';

    if ( data.hasOwnProperty( 'ajax_url' ) ) {
        ajaxUrl = data.ajax_url;
        ajaxUrl = ( ( ( 'string' === typeof ajaxUrl ) && ( 0 < ajaxUrl.length ) ) ? ajaxUrl : '' );
    }

    return ajaxUrl;
}

/**
 * Get ajax nonce.
 */
function wlpfGetAjaxNonce() {
    let data = wlpfGetData(),
        output = '';

    if ( data.hasOwnProperty( 'ajax_nonce' ) ) {
        output = data.ajax_nonce;
        output = ( ( ( 'string' === typeof output ) && ( 0 < output.length ) ) ? output : '' );
    }

    return output;
}

/**
 * Get ajax filter.
 */
function wlpfGetAjaxFilter() {
    let data = wlpfGetData(),
        output = '';

    if ( data.hasOwnProperty( 'ajax_filter' ) ) {
        output = data.ajax_filter;
        output = ( ( ( 'string' === typeof output ) && ( '1' === output ) ) ? true : false );
    }

    return output;
}

/**
 * Get add ajax query args to url.
 */
function wlpfGetAddAjaxQueryArgsToUrl() {
    let data = wlpfGetData(),
        output = '';

    if ( data.hasOwnProperty( 'add_ajax_query_args_to_url' ) ) {
        output = data.add_ajax_query_args_to_url;
        output = ( ( ( 'string' === typeof output ) && ( '1' === output ) ) ? true : false );
    }

    return output;
}

/**
 * Get time to take ajax action.
 */
function wlpfGetTimeToTakeAjaxAction() {
    let data = wlpfGetData(),
        output = '';

    if ( data.hasOwnProperty( 'time_to_take_ajax_action' ) ) {
        output = data.time_to_take_ajax_action;
        output = wlpfNumberToAbsInt( output );
    }

    return output;
}

/**
 * Get time to take none ajax action.
 */
function wlpfGetTimeToTakeNoneAjaxAction() {
    let data = wlpfGetData(),
        output = '';

    if ( data.hasOwnProperty( 'time_to_take_none_ajax_action' ) ) {
        output = data.time_to_take_none_ajax_action;
        output = wlpfNumberToAbsInt( output );
    }

    return output;
}

/**
 * Get products wrapper selector.
 */
function wlpfGetProductsWrapperSelector() {
    let data = wlpfGetData(),
        output = '';

    if ( data.hasOwnProperty( 'products_wrapper_selector' ) ) {
        output = data.products_wrapper_selector;
        output = ( ( ( 'string' === typeof output ) && ( 0 < output.length ) && ( '.wl-filterable-products-wrap' !== output ) ) ? output : '.wlpf-products-wrap' );
    }

    return output;
}

/**
 * Get show filter arguments.
 */
function wlpfGetShowFilterArguments() {
    let data = wlpfGetData(),
        output = '';

    if ( data.hasOwnProperty( 'show_filter_arguments' ) ) {
        output = data.show_filter_arguments;
        output = ( ( ( 'string' === typeof output ) && ( '1' === output ) ) ? true : false );
    }

    return output;
}

/**
 * Get query args prefix.
 */
function wlpfGetQueryArgsPrefix() {
    let data = wlpfGetData(),
        output = '';

    if ( data.hasOwnProperty( 'query_args_prefix' ) ) {
        output = data.query_args_prefix;
        output = ( ( ( 'string' === typeof output ) && ( 0 < output.length ) ) ? output : 'wlpf_' );
    }

    return output;
}

/**
 * Get active map.
 */
function wlpfGetActiveMap() {
    let output = true;

    if ( ( true === wlpfGetAjaxFilter() ) && ( false === wlpfGetAddAjaxQueryArgsToUrl() ) ) {
        output = false;
    }

    return output;
}

/**
 * Get elementor editor mode.
 */
function wlpfGetElementorEditorMode() {
    let data = wlpfGetData(),
        output = '';

    if ( data.hasOwnProperty( 'elementor_editor_mode' ) ) {
        output = data.elementor_editor_mode;
        output = ( ( ( 'string' === typeof output ) && ( '1' === output ) ) ? true : false );
    }

    return output;
}

/**
 * Get filters data.
 */
function wlpfGetFiltersData() {
    let data = wlpfGetData(),
        output = '';

    if ( data.hasOwnProperty( 'filters_data' ) ) {
        output = data.filters_data;
        output = ( ( ( 'object' === typeof output ) && ( ! Array.isArray( output ) ) ) ? output : {} );
    }

    return output;
}

/**
 * Get filter page number.
 */
function wlpfGetFilterPageNumber() {
    let data = wlpfGetData(),
        output = '';

    if ( data.hasOwnProperty( 'filter_page_number' ) ) {
        output = data.filter_page_number;
        output = wlpfNumberToAbsInt( output );
    }

    return output;
}

/**
 * Get filter page url.
 */
function wlpfGetFilterPageUrl() {
    let data = wlpfGetData(),
        output = '';

    if ( data.hasOwnProperty( 'filter_page_url' ) ) {
        output = data.filter_page_url;
        output = ( ( ( 'string' === typeof output ) && ( 0 < output.length ) ) ? output : window.location );

        output = new URL( output );
    }

    return output;
}

/**
 * Get item title structure.
 */
function wlpfGetItemTitleStructure() {
    let data = wlpfGetData(),
        output = '';

    if ( data.hasOwnProperty( 'item_title_structure' ) ) {
        output = data.item_title_structure;
        output = ( ( ( 'string' === typeof output ) && ( 0 < output.length ) ) ? output : 'ID# _WLPF_ID_' );
    }

    return output;
}

/**
 * Get item title with label structure.
 */
function wlpfGetItemTitleWithLabelStructure() {
    let data = wlpfGetData(),
        output = '';

    if ( data.hasOwnProperty( 'item_title_with_label_structure' ) ) {
        output = data.item_title_with_label_structure;
        output = ( ( ( 'string' === typeof output ) && ( 0 < output.length ) ) ? output : 'ID# _WLPF_ID_ &mdash; _WLPF_LABEL_' );
    }

    return output;
}