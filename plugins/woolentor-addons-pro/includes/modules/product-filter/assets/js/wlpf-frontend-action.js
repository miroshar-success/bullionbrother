/**
 * Frontend action scripts.
 */

/* global jQuery */
;( function ( $ ) {
    'use strict';

    /**
     * Do ajax filter.
     */
    function doAjaxFilter( productsWrap, isCustom, addon, settings, filters, allCompleted = false ) {
        $.ajax( {
            type: 'POST',
            url: wlpfGetAjaxUrl(),
            data: {
                action: 'wlpf_ajax_filter',
                nonce: wlpfGetAjaxNonce(),
                addon: addon,
                settings: settings,
                filters: filters,
            },
            beforeSend: function() {
                productsWrap.addClass( 'wlpf-loading' );
            },
            success: function( response ) {
                if ( ! response ) {
                    productsWrap.removeClass( 'wlpf-loading' );
                    return;
                }

                if ( 'string' === typeof response ) {
                    response = JSON.parse( response );
                }

                let content = ( response.hasOwnProperty( 'content' ) ? response.content : '' );

                if ( 'string' === typeof content && 0 < content.length ) {
                    if ( true === isCustom ) {
                        productsWrap.find( '.wl-filterable-products-content' ).html( content );
                    } else {
                        productsWrap.html( content );
                    }
                }

                $( document ).trigger( 'wlpf_ajax_filter_completed', [ allCompleted ] );

                productsWrap.removeClass( 'wlpf-loading' );
            },
            error: function() {
                productsWrap.removeClass( 'wlpf-loading' );
            },
        } );
    };

    /**
     * Do none ajax filter.
     */
    function doNoneAjaxFilter() {
        let url = wlpfGetFilterPageUrl(),
            href = url.href;

        if ( ( 'string' === typeof href ) && ( 0 < href.length ) ) {
            window.location.replace( url.href );
        }
    }

    /**
     * Run ajax filter.
     */
    function runAjaxFilter( productsWrap, isCustom, addon, settings, filters, allCompleted ) {
        wlpfActionTimeout = setTimeout( function () {
            doAjaxFilter( productsWrap, isCustom, addon, settings, filters, allCompleted );
        }, wlpfGetTimeToTakeAjaxAction() );
    }

    /**
     * Run none ajax filter.
     */
    function runNoneAjaxFilter() {
        wlpfActionTimeout = setTimeout( function () {
            doNoneAjaxFilter();
        }, wlpfGetTimeToTakeNoneAjaxAction() );
    }

    /**
     * Process ajax filter.
     */
    $( document ).on( 'wlpf_process_ajax_filter', function( e, filters ) {
        e.preventDefault();

        let wrapperSelectorsArray = ['.wl-filterable-products-wrap', wlpfGetProductsWrapperSelector() ],
            wrapperSelectors = wrapperSelectorsArray.join(','),
            wrapperObjects = $( wrapperSelectors ),
            wrapperLength = wrapperObjects.length,
            countCall = 0,
            allCompleted = false;

        clearTimeout( wlpfActionTimeout );

        wrapperObjects.each( function () {
            let productsWrap = $( this ),
                isCustom = ( productsWrap.hasClass( 'wl-filterable-products-wrap' ) ? true : false ),
                addon = '',
                settings = '';

            countCall++;

            if ( true === isCustom ) {
                addon = productsWrap.attr( 'data-wl-widget-name' );
                settings = productsWrap.attr( 'data-wl-widget-settings' );
            } else if ( 0 < productsWrap.closest( '.wl-filterable-products-wrap' ).length ) {
                return;
            }

            allCompleted = ( ( wrapperLength === countCall ) ? true : false );

            runAjaxFilter( productsWrap, isCustom, addon, settings, filters, allCompleted );
        } );
    } );

    /**
     * Process none ajax filter.
     */
    $( document ).on( 'wlpf_process_none_ajax_filter', function() {
        clearTimeout( wlpfActionTimeout );
        runNoneAjaxFilter();
    } );

} )( jQuery );