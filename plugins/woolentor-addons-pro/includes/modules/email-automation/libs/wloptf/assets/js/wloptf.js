/**
 * WooLentor Options Framework Scripts.
 */

/* global jQuery */
;( function ( $ ) {
    'use strict';

    $( document ).ready( function () {

        // Local object.
        let ajaxUrl = ( wloptf_local_obj.hasOwnProperty( 'ajax_url' ) ? wloptf_local_obj.ajax_url : '' ),
            ajaxNonce = ( wloptf_local_obj.hasOwnProperty( 'ajax_nonce' ) ? wloptf_local_obj.ajax_nonce : '' );

        /**
         * Sortable.
         */
        ( function () {
            $( '.wloptf-sortable' ).each( function () {
                $( this ).sortable( {
                    handle: '.wloptf-sort-handle',
                    update: function ( e, ui ) {
                        let groupWrap = ui.item.closest( '.wloptf-group-wrapper' );

                        wloptfGroupFieldsName( groupWrap );
                        wloptfGroupItemsSerial( groupWrap );

                        wloptCallSelect2( groupWrap );
                        wloptCallDatepicker( groupWrap );
                    },
                } );
            } );
        } )();

        /**
         * Select value.
         */
        ( function () {
            $( document ).on( 'change', '.wloptf-field select', function ( e ) {
                let field = $( this ),
                    value = field.val();

                value = ( ( 'object' === typeof( value ) ) ? JSON.stringify( value ) : value );

                field.attr( 'data-wloptf-value', value );
            } );
        } )();

        /**
         * Group: add item.
         */
        ( function () {
            $( document ).on( 'click', '.wloptf-group-items-controls .wloptf-add', function ( e ) {
                e.preventDefault();

                let addButton = $( this ),
                    groupWrap = addButton.closest( '.wloptf-group-wrapper' ),
                    itemsContentWrap = groupWrap.find( '.wloptf-group-items-content' ),
                    sampleItemHTML = groupWrap.find( '.wloptf-group-sample' ).html();

                if ( 'string' !== typeof sampleItemHTML ) {
                    return;
                }

                itemsContentWrap.append( sampleItemHTML );

                wloptfGroupFieldsName( groupWrap );
                wloptfGroupItemsSerial( groupWrap );

                wloptCallSelect2( groupWrap );
                wloptCallDatepicker( groupWrap );
            } );
        } )();

        /**
         * Group: clone item.
         */
        ( function () {
            $( document ).on( 'click', '.wloptf-group-item-controls .wloptf-clone', function ( e ) {
                e.preventDefault();

                let cloneButton = $( this ),
                    itemWrap = cloneButton.closest( '.wloptf-group-item' ),
                    groupWrap = itemWrap.closest( '.wloptf-group-wrapper' ),
                    item = itemWrap.prop('outerHTML');

                item = $( item );
                item = wloptfRemoveSelect2( item );
                item = wloptfRemoveDatepicker( item );

                itemWrap.after( item );
                itemWrap.next().removeClass( 'wloptf-group-item-expanded' );

                wloptfGroupFieldsName( groupWrap );
                wloptfGroupItemsSerial( groupWrap );

                wloptCallSelect2( groupWrap );
                wloptCallDatepicker( groupWrap );
            } );
        } )();

        /**
         * Group: remove item.
         */
        ( function () {
            $( document ).on( 'click', '.wloptf-group-item-controls .wloptf-remove', function ( e ) {
                e.preventDefault();

                let removeButton = $( this ),
                    itemWrap = removeButton.closest( '.wloptf-group-item' ),
                    groupWrap = itemWrap.closest( '.wloptf-group-wrapper' );

                itemWrap.remove();

                wloptfGroupFieldsName( groupWrap );
                wloptfGroupItemsSerial( groupWrap );

                wloptCallSelect2( groupWrap );
                wloptCallDatepicker( groupWrap );
            } );
        } )();

        /**
         * Group: expand item.
         */
        ( function () {
            $( document ).on( 'click', '.wloptf-group-items .wloptf-group-item-title', function ( e ) {
                e.preventDefault();

                let heading = $( this ),
                    itemWrap = heading.closest( '.wloptf-group-item' ),
                    itemsWrap = itemWrap.siblings(),
                    groupWrap = itemWrap.closest( '.wloptf-group-wrapper' );

                if ( itemWrap.hasClass( 'wloptf-group-item-expanded' ) ) {
                    itemWrap.removeClass( 'wloptf-group-item-expanded' );
                } else {
                    itemWrap.addClass( 'wloptf-group-item-expanded' );
                    itemsWrap.removeClass( 'wloptf-group-item-expanded' );
                }

                wloptCallSelect2( groupWrap );
                wloptCallDatepicker( groupWrap );
            } );
        } )();

        /**
         * Group: item title.
         */
        ( function () {
            $( '.wloptf-group-item' ).each( function () {
                let item = $( this ),
                    field = item.find( '.wloptf-field:nth-child(1) *[data-wloptf-tname]' ),
                    title = field.val(),
                    titleText = item.find( '.wloptf-group-item-title-text' );

                titleText.html( title );
            } );
        } )();

        /**
         * Group: item title change.
         */
        ( function () {
            $( document ).on( 'change keyup', '.wloptf-group-items .wloptf-field:nth-child(1) *[data-wloptf-tname]', function ( e ) {
                e.preventDefault();

                let field = $( this ),
                    title = field.val(),
                    item = field.closest( '.wloptf-group-item' ),
                    titleText = item.find( '.wloptf-group-item-title-text' );

                titleText.html( title );
            } );
        } )();

        /**
         * Rules: add group.
         */
        ( function () {
            $( document ).on( 'click', '.wloptf-rules-groups-controls .wloptf-add', function ( e ) {
                e.preventDefault();

                let addButton = $( this ),
                    rulesWrap = addButton.closest( '.wloptf-rules-wrapper' ),
                    groupsContentWrap = rulesWrap.find( '.wloptf-rules-groups-content' ),
                    sampleGroupHTML = rulesWrap.find( '.wloptf-rules-sample' ).html();

                if ( 'string' !== typeof sampleGroupHTML ) {
                    return;
                }

                groupsContentWrap.append( sampleGroupHTML );

                wloptfRulesStoreValue( rulesWrap );
                wloptfRulesFieldsName( rulesWrap );

                wloptCallSelect2( rulesWrap );
                wloptCallDatepicker( rulesWrap );
            } );
        } )();

        /**
         * Rules: add item.
         */
        ( function () {
            $( document ).on( 'click', '.wloptf-rules-item-controls .wloptf-add', function ( e ) {
                e.preventDefault();

                let addButton = $( this ),
                    itemWrap = addButton.closest( '.wloptf-rules-item' ),
                    rulesWrap = itemWrap.closest( '.wloptf-rules-wrapper' ),
                    sampleItemHTML = rulesWrap.find( '.wloptf-rules-sample .wloptf-rules-items' ).html();

                if ( 'string' !== typeof sampleItemHTML ) {
                    return;
                }

                itemWrap.after( sampleItemHTML );

                wloptfRulesStoreValue( rulesWrap );
                wloptfRulesFieldsName( rulesWrap );

                wloptCallSelect2( rulesWrap );
                wloptCallDatepicker( rulesWrap );
            } );
        } )();

        /**
         * Rules: remove item.
         */
        ( function () {
            $( document ).on( 'click', '.wloptf-rules-item-controls .wloptf-remove', function ( e ) {
                e.preventDefault();

                let removeButton = $( this ),
                    itemWrap = removeButton.closest( '.wloptf-rules-item' ),
                    groupWrap = itemWrap.closest( '.wloptf-rules-group' ),
                    rulesWrap = groupWrap.closest( '.wloptf-rules-wrapper' ),
                    allItems = groupWrap.find( '.wloptf-rules-item' );

                if ( 1 < allItems.length ) {
                    itemWrap.remove();
                } else {
                    groupWrap.remove();
                }

                wloptfRulesStoreValue( rulesWrap );
                wloptfRulesFieldsName( rulesWrap );

                wloptCallSelect2( rulesWrap );
                wloptCallDatepicker( rulesWrap );
            } );
        } )();

        /**
         * Rules: base change.
         */
        ( function () {
            $( document ).on( 'change', '.wloptf-rules-item-base select', function () {
                let baseSelect = $( this ),
                    base = baseSelect.val(),
                    itemWrap = baseSelect.closest( '.wloptf-rules-item' ),
                    operatorWrap = itemWrap.find( '.wloptf-rules-item-operator' ),
                    valueWrap = itemWrap.find( '.wloptf-rules-item-value' ),
                    groupWrap = baseSelect.closest( '.wloptf-rules-group' ),
                    rulesWrap = baseSelect.closest( '.wloptf-rules-wrapper' ),
                    fields = rulesWrap.attr( 'data-wloptf-rules-fields-json' ),
                    control = rulesWrap.attr( 'data-wloptf-rules-control-value' ),
                    rIvalue = rulesWrap.attr( 'data-wloptf-rules-ivalue-json' );

                fields = JSON.parse( fields );
                rIvalue = JSON.parse( rIvalue );

                if ( ( 'object' !== typeof fields ) || ( 'object' !== typeof rIvalue ) ) {
                    return;
                }

                let crIvalue = ( rIvalue.hasOwnProperty( control ) ? rIvalue[ control ] : {} ),
                    iog = groupWrap.index(),
                    ioi = itemWrap.index();

                let grIvalue = ( crIvalue.hasOwnProperty( iog ) ? crIvalue[ iog ] : {} ),
                    irIvalue = ( grIvalue.hasOwnProperty( ioi ) ? grIvalue[ ioi ] : {} ),
                    brIvalue = ( irIvalue.hasOwnProperty( base ) ? irIvalue[ base ] : {} );

                let defaultOperator = ( brIvalue.hasOwnProperty( 'operator' ) ? brIvalue.operator : null ),
                    defaultValue = ( brIvalue.hasOwnProperty( 'value' ) ? brIvalue.value : null );

                let defaultOperatorAttr = ( ( 'object' === typeof defaultOperator ) ? JSON.stringify( defaultOperator ) : defaultOperator ),
                    defaultValueAttr = ( ( 'object' === typeof defaultValue ) ? JSON.stringify( defaultValue ) : defaultValue );

                let itemHTML = fields[ 'deps' ][ base ],
                    operatorHTML = itemHTML.operator,
                    valueHTML = itemHTML.value;

                let valueEnhanced = false;

                operatorHTML = $( operatorHTML );
                valueHTML = $( valueHTML );

                if ( null !== defaultOperator ) {
                    operatorHTML.val( defaultOperator );
                    operatorHTML.attr( 'data-wloptf-value', defaultOperatorAttr );
                }

                if ( null !== defaultValue ) {
                    valueHTML.val( defaultValue );
                    valueHTML.attr( 'data-wloptf-value', defaultValueAttr );
                }

                operatorWrap.html( operatorHTML );

                valueEnhanced = wloptAjaxSelect( valueHTML, valueWrap, rulesWrap );

                if ( true !== valueEnhanced ) {
                    valueWrap.html( valueHTML );

                    wloptfRulesStoreValue( rulesWrap );
                    wloptfRulesFieldsName( rulesWrap );

                    wloptCallSelect2( rulesWrap );
                    wloptCallDatepicker( rulesWrap );
                }
            } );
        } )();

        /**
         * Rules: value change.
         */
        ( function () {
            $( document ).on( 'change', '.wloptf-rules-item-value select', function () {
                wloptfRulesStoreValue( $( this ) );
            } );

            $( document ).on( 'change keyup', '.wloptf-rules-item-value input', function () {
                wloptfRulesStoreValue( $( this ) );
            } );
        } )();

        /**
         * Rules: update.
         */
         ( function () {
            $( '.wloptf-rules-wrapper' ).each( function () {
                let rulesWrap = $( this ),
                    controlName = rulesWrap.attr( 'data-wloptf-rules-control-name' ),
                    controlEvent = rulesWrap.attr( 'data-wloptf-rules-control-event' );

                $( document ).on( controlEvent, '*[name="' + controlName + '"]', function () {
                    wloptfUpdateRulesByControl( $( this ).val(), rulesWrap );
                } );
            } );
        } )();

        /**
         * Window resize.
         */
        ( function () {
            $( window ).on( 'resize', function () {
                wloptCallSelect2();
                wloptCallDatepicker();
            } );
        } )();

        /**
         * Group: re-arrange fields name.
         */
         function wloptfGroupFieldsName ( groupWrap = null ) {
            let items = groupWrap.find( '.wloptf-group-items .wloptf-group-item' );

            $.each( items, function ( ioi, item ) {
                let fields = $( item ).find( '*[data-wloptf-tname]' );

                $.each( fields, function () {
                    let field = $( this ),
                        name = field.attr( 'data-wloptf-tname' );

                    name = name.replace( 'WLOPTF9999', ioi );

                    field.attr( 'name', name );
                } );
            } );
        }

        /**
         * Group: re-arrange items serial.
         */
        function wloptfGroupItemsSerial ( groupWrap = null ) {
            let items = groupWrap.find( '.wloptf-group-items .wloptf-group-item' );

            $.each( items, function ( index, item ) {
                let serial = ( index + 1 ),
                    serialWrap = $( item ).find( '.wloptf-group-item-title-serial' );

                serialWrap.html( serial + '.' );
            } );
        }

        /**
         * Rules: store value.
         */
        function wloptfRulesStoreValue ( valueInput = null ) {
            let rulesWrap = valueInput.closest( '.wloptf-rules-wrapper' ),
                control = rulesWrap.attr( 'data-wloptf-rules-control-value' ),
                rValue = rulesWrap.attr( 'data-wloptf-rules-value-json' ),
                rIvalue = rulesWrap.attr( 'data-wloptf-rules-ivalue-json' ),
                groups = rulesWrap.find( '.wloptf-rules-groups .wloptf-rules-group' ),
                value = {},
                ivalue = {};

            rValue = JSON.parse( rValue );
            rIvalue = JSON.parse( rIvalue );

            if ( ( 'object' !== typeof rValue ) || ( 'object' !== typeof rIvalue ) ) {
                return;
            }

            let crValue = ( rValue.hasOwnProperty( control ) ? rValue[ control ] : {} ),
                crIvalue = ( rIvalue.hasOwnProperty( control ) ? rIvalue[ control ] : {} );

            $.each( groups, function ( iog, group ) {
                let items = $( group ).find( '.wloptf-rules-items .wloptf-rules-item' ),
                    grValue = ( crValue.hasOwnProperty( iog ) ? crValue[ iog ] : {} ),
                    grIvalue = ( crIvalue.hasOwnProperty( iog ) ? crIvalue[ iog ] : {} ),
                    itemsValue = {},
                    itemsIvalue = {};

                $.each( items, function ( ioi, item ) {
                    let baseField = $( item ).find( '.wloptf-rules-item-base *[data-wloptf-tname]' ),
                        operatorField = $( item ).find( '.wloptf-rules-item-operator *[data-wloptf-tname]' ),
                        valueField = $( item ).find( '.wloptf-rules-item-value *[data-wloptf-tname]' ),
                        irValue = ( grValue.hasOwnProperty( ioi ) ? grValue[ ioi ] : {} ),
                        irIvalue = ( grIvalue.hasOwnProperty( ioi ) ? grIvalue[ ioi ] : {} );

                    let defaultBase = baseField.val(),
                        defaultOperator = operatorField.val(),
                        defaultValue = valueField.val(),
                        defaulttValue = valueField.attr( 'data-wloptf-value' );

                    if ( ( 'object' === typeof defaultValue ) && ( 'string' === typeof defaulttValue ) && ( 0 < defaulttValue.length ) ) {
                        defaultValue = ( wloptfIsJSON( defaulttValue ) ? JSON.parse( defaulttValue ) : defaulttValue );
                    }

                    irValue = { [ defaultBase ]: {
                        operator: defaultOperator,
                        value: defaultValue,
                    } };

                    irIvalue[ defaultBase ] = {
                        operator: defaultOperator,
                        value: defaultValue,
                    };

                    itemsValue[ ioi ] = irValue;
                    value[ iog ] = itemsValue;

                    itemsIvalue[ ioi ] = irIvalue;
                    ivalue[ iog ] = itemsIvalue;
                } );
            } );

            rValue[ control ] = value;
            rIvalue[ control ] = ivalue;

            rulesWrap.attr( 'data-wloptf-rules-value-json', JSON.stringify( rValue ) );
            rulesWrap.attr( 'data-wloptf-rules-ivalue-json', JSON.stringify( rIvalue ) );
        }

        /**
         * Rules: re-arrange fields name.
         */
        function wloptfRulesFieldsName ( rulesWrap = null ) {
            let groups = rulesWrap.find( '.wloptf-rules-groups .wloptf-rules-group' );

            $.each( groups, function ( iog, group ) {
                let items = $( group ).find( '.wloptf-rules-items .wloptf-rules-item' );

                $.each( items, function ( ioi, item ) {
                    let fields = $( item ).find( '*[data-wloptf-tname]' );

                    $.each( fields, function () {
                        let field = $( this ),
                            name = field.attr( 'data-wloptf-tname' );

                        name = name.replace( 'WLOPTF8888', iog );
                        name = name.replace( 'WLOPTF9999', ioi );

                        field.attr( 'name', name );
                    } );
                } );
            } );
        }

        /**
         * Rules: update by control.
         */
        function wloptfUpdateRulesByControl( control, rulesWrap ) {
            let sampleWrap = rulesWrap.find( '.wloptf-rules-sample' ),
                itemsWrap = rulesWrap.find( '.wloptf-rules-groups-content' ),
                name = rulesWrap.attr( 'data-wloptf-rules-field-name' ),
                groups = rulesWrap.attr( 'data-wloptf-rules-value-json' ),
                settings = rulesWrap.attr( 'data-wloptf-rules-settings-json' );

            groups = JSON.parse( groups );
            settings = JSON.parse( settings );

            if ( ( 'object' !== typeof groups ) || ( 'object' !== typeof settings ) ) {
                return;
            }

            groups = ( groups.hasOwnProperty( control ) ? groups[ control ] : {} );
            settings = ( settings.hasOwnProperty( control ) ? settings[ control ] : {} );

            if ( ( 'object' !== typeof groups ) || ( 'object' !== typeof settings ) ) {
                return;
            }

            $.ajax( {
                type: 'POST',
                url: ajaxUrl,
                data: {
                    action: 'wloptf_ajax_update_rules',
                    nonce: ajaxNonce,
                    name: name,
                    groups: groups,
                    settings: settings,
                },
                beforeSend: function() {
                    rulesWrap.addClass( 'wloptf-loading' );
                },
                success: function( response ) {
                    if ( ! response ) {
                        rulesWrap.removeClass( 'wloptf-loading' );
                        return;
                    }

                    if ( 'string' === typeof response ) {
                        response = JSON.parse( response );
                    }

                    if ( 'object' !== typeof response ) {
                        rulesWrap.removeClass( 'wloptf-loading' );
                        return;
                    }

                    let items  = ( response.hasOwnProperty( 'items' ) ? response.items : '' ),
                        fields = ( response.hasOwnProperty( 'fields' ) ? response.fields : '' ),
                        sample = ( response.hasOwnProperty( 'sample' ) ? response.sample : '' );

                    itemsWrap.html( items );
                    sampleWrap.html( sample );

                    rulesWrap.attr( 'data-wloptf-rules-fields-json', fields );
                    rulesWrap.attr( 'data-wloptf-rules-control-value', control );

                    wloptCallSelect2( rulesWrap );
                    wloptCallDatepicker( rulesWrap );

                    rulesWrap.removeClass( 'wloptf-loading' );
                },
                error: function() {
                    rulesWrap.removeClass( 'wloptf-loading' );
                },
            } );
        }

        /**
         * Ajax select.
         */
        function wloptAjaxSelect( selectField = null, valueWrap = null, rulesWrap = null ) {
            let value = selectField.val(),
                tvalue = selectField.attr( 'data-wloptf-value' ),
                ajax = selectField.attr( 'data-wloptf-ajax' ),
                multiple = selectField.attr( 'data-wloptf-multiple' ),
                options = selectField.attr( 'data-wloptf-options' ),
                queryType = selectField.attr( 'data-wloptf-query-type' ),
                queryArgs = selectField.attr( 'data-wloptf-query-args' );

            if ( ( 'object' === typeof value ) && ( 'string' === typeof tvalue ) && ( 0 < tvalue.length ) ) {
                value = ( wloptfIsJSON( tvalue ) ? JSON.parse( tvalue ) : tvalue );
            }

            value = ( ( 'object' === typeof value ) ? Object.values( value ) : value );

            ajax = ( ( '1' === ajax ) ? true : false );
            multiple = ( ( '1' === multiple ) ? true : false );

            if ( ( true !== ajax ) && ( true !== multiple ) ) {
                return false;
            }

            options = JSON.parse( options );
            queryArgs = JSON.parse( queryArgs );

            $.ajax( {
                type: 'POST',
                url: ajaxUrl,
                data: {
                    action: 'wloptf_ajax_select',
                    nonce: ajaxNonce,
                    ajax: ajax,
                    multiple: multiple,
                    options: options,
                    value: value,
                    query_type: queryType,
                    query_args: queryArgs,
                },
                beforeSend: function() {
                    rulesWrap.addClass( 'wloptf-loading' );
                },
                success: function( response ) {
                    if ( ! response ) {
                        rulesWrap.removeClass( 'wloptf-loading' );
                        return;
                    }

                    if ( 'string' === typeof response ) {
                        response = JSON.parse( response );
                    }

                    if ( 'object' !== typeof response ) {
                        rulesWrap.removeClass( 'wloptf-loading' );
                        return;
                    }

                    let select_options = ( response.hasOwnProperty( 'select_options' ) ? response.select_options : null );

                    selectField.html( select_options );
                    selectField.val( value );
                    valueWrap.html( selectField );

                    wloptfRulesStoreValue( rulesWrap );
                    wloptfRulesFieldsName( rulesWrap );

                    wloptCallSelect2( rulesWrap );

                    rulesWrap.removeClass( 'wloptf-loading' );
                },
                error: function() {
                    rulesWrap.removeClass( 'wloptf-loading' );
                },
            } );

            return true;
        }

        /**
         * Call Select2.
         */
        function wloptCallSelect2( wrapper = null ) {
            if ( null === wrapper ) {
                $( '.wloptf-select2.enhanced' ).select2( 'destroy' ).removeClass( 'enhanced' );
                $( '.wloptf-select2' ).filter( ':not(.enhanced)' ).each( function() {
                    wloptInitSelect2( $( this ) );
                } );
            } else {
                wrapper.find( '.wloptf-select2.enhanced' ).select2( 'destroy' ).removeClass( 'enhanced' );
                wrapper.find( '.wloptf-select2' ).filter( ':not(.enhanced)' ).each( function() {
                    wloptInitSelect2( $( this ) );
                } );
            }
        }

        /**
         * Init Select2.
         */
        function wloptInitSelect2( selectField = null ) {
            let ajax = selectField.attr( 'data-wloptf-ajax' ),
                multiple = selectField.attr( 'data-wloptf-multiple' ),
                options = selectField.attr( 'data-wloptf-options' ),
                placeholder = selectField.attr( 'data-wloptf-placeholder' ),
                queryType = selectField.attr( 'data-wloptf-query-type' ),
                queryArgs = selectField.attr( 'data-wloptf-query-args' ),
                groupSample = selectField.closest( '.wloptf-group-sample' ),
                rulesSample = selectField.closest( '.wloptf-rules-sample' );

            ajax = ( ( '1' === ajax ) ? true : false );
            multiple = ( ( '1' === multiple ) ? true : false );

            options = JSON.parse( options );
            queryArgs = JSON.parse( queryArgs );

            if ( ( ( false == ajax ) && ( false === multiple ) ) || ( 0 < groupSample.length ) || ( 0 < rulesSample.length ) ) {
                return;
            }

            let select2Args = {
                allowClear: false,
                multiple: multiple,
                placeholder: placeholder,
                escapeMarkup: function( m ) {
                    return m;
                }
            };

            if ( true === ajax ) {
                select2Args.ajax = {
                    type: 'POST',
                    url: ajaxUrl,
                    dataType: 'json',
                    delay: 250,
                    data: function( params ) {
                        return {
                            action: 'wloptf_ajax_select2',
                            nonce: ajaxNonce,
                            options: options,
                            query_type: queryType,
                            query_args: queryArgs,
                            search_term: params.term,
                        };
                    },
                    processResults: function( data ) {
                        let terms = [];

                        if ( data ) {
                            $.each( data, function( id, text ) {
                                terms.push( { id: id, text: text } );
                            } );
                        }

                        return {
                            results: terms
                        };
                    },
                    cache: true
                };

                select2Args.minimumInputLength = 3;
            }

            selectField.select2( select2Args ).addClass( 'enhanced' );
        }

        /**
         * Call Datepicker.
         */
        function wloptCallDatepicker( wrapper = null ) {
            if ( null === wrapper ) {
                $( '.wloptf-datepicker.enhanced' ).datepicker( 'destroy' ).removeClass( 'enhanced' );
                $( '.wloptf-datepicker' ).filter( ':not(.enhanced)' ).each( function() {
                    wloptInitDatepicker( $( this ) );
                } );
            } else {
                wrapper.find( '.wloptf-datepicker.enhanced' ).datepicker( 'destroy' ).removeClass( 'enhanced' );
                wrapper.find( '.wloptf-datepicker' ).filter( ':not(.enhanced)' ).each( function() {
                    wloptInitDatepicker( $( this ) );
                } );
            }
        }

        /**
         * Init Datepicker.
         */
        function wloptInitDatepicker( field = null ) {
            let groupSample = field.closest( '.wloptf-group-sample' ),
                rulesSample = field.closest( '.wloptf-rules-sample' );

            if ( ( 0 < groupSample.length ) || ( 0 < rulesSample.length ) ) {
                return;
            }

            let datepickerArgs = {
                altFormat: 'yy-mm-dd',
                dateFormat: 'yy-mm-dd'
            }

            field.datepicker( datepickerArgs ).addClass( 'enhanced' );
        }

        /**
         * Remove Select2.
         */
        function wloptfRemoveSelect2( wrap = '' ) {
            wrap.find( '.wloptf-select2' ).removeClass( 'select2-hidden-accessible' );
            wrap.find( '.wloptf-select2' ).removeClass( 'enhanced' );
            wrap.find( '.wloptf-select2' ).removeAttr( 'data-select2-id' );
            wrap.find( '.select2' ).remove();

            return wrap;
        }

        /**
         * Remove Datepicker.
         */
        function wloptfRemoveDatepicker( wrap = '' ) {
            wrap.find( '.wloptf-datepicker' ).removeClass( 'hasDatepicker' );
            wrap.find( '.wloptf-datepicker' ).removeClass( 'enhanced' );
            wrap.find( '.wloptf-datepicker' ).removeAttr( 'id' );

            return wrap;
        }

        /**
         * Is JSON.
         */
        function wloptfIsJSON( jsonStr = '' ) {
            let result = true;

            try {
                const json = JSON.parse( jsonStr );
            } catch (e) {
                result = false;
            }

            return result;
        }

        // Run Select2.
        wloptCallSelect2();

        // Run Datepicker.
        wloptCallDatepicker();

    } );

} )( jQuery );