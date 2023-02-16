;(function($){
    "use strict";
    
        var GtmWooLentor = {
    
            body: $('body'),
            products: [],
    
            /**
             * [init]
             * @return {[void]} Initial Function
             */
            init: function(){
    
                $( document ).ready( function() {
                    if( typeof gtm_for_wc_shop !== 'undefined' && gtm_for_wc_shop === true ){
                        GtmWooLentor.shopList();
                    }
                });
    
                if( WLGTM.option_data['add_to_cart'] == 'on' ){
                    $( document ).on( 'click.GtmWooLentor', '.add_to_cart_button:not(.product_type_variable, .product_type_grouped, .single_add_to_cart_button)', this.add_to_cart );
                }
    
                if( WLGTM.option_data['single_add_to_cart'] == 'on' ){
                    $( document ).on( 'click.GtmWooLentor', '.single_add_to_cart_button:not(.disabled)', this.single_product_add_to_cart );
                }
    
                if( WLGTM.option_data['remove_from_cart'] == 'on' ){
                    $( document ).on( 'click.GtmWooLentor', '.mini_cart_item a.remove,.product-remove a.remove', this.remove_from_cart );
                }
    
            },
    
            /**
             * [shopList] shop page event
             * @return {[void]}
             */
            shopList: function(){
    
                if( typeof gtm_for_wc_list_products_ga4 !== 'undefined' && gtm_for_wc_list_products_ga4 !== null ){
                    for ( const [key, value] of Object.entries( gtm_for_wc_list_products_ga4 ) ) {
                        GtmWooLentor.products.push( gtm_for_wc_list_products_ga4[key] );
                    }
                }
    
                dataLayer.push({
                    'event': 'view_item_list',
                    'ecommerce': {
                        'items': GtmWooLentor.products
                    }
                });
    
            },
    
            /**
             * [add_to_cart] added to cart event
             * @param {[object]} e button event
             */
            add_to_cart: function( e ){
    
                var quantity = $(e.currentTarget).data('quantity'),
                    product_id = $(e.currentTarget).data('product_id');
    
                if( typeof gtm_for_wc_list_products_ga4 !== 'undefined' && gtm_for_wc_list_products_ga4 !== null && gtm_for_wc_list_products_ga4[product_id] !== undefined ){
                    
                    var item = gtm_for_wc_list_products_ga4[product_id];
    
                    item.quantity = quantity;
    
                    dataLayer.push({ ecommerce: null });
                    dataLayer.push({
                        'event': 'add_to_cart',
                        'ecommerce': {
                            'currency': WLGTM.option_data['currency'],
                            'value': item.price * item.quantity,
                            'items': [item]
                        }
                    });
    
                }
    
            },
    
            /**
             * [single_product_add_to_cart] single product added to cart
             * @param  {[object]} e button event
             * @return {[void]}
             */
            single_product_add_to_cart: function( e ){
                var cart_form  = $( this ).closest( 'form.cart' ),
                    quantity = $( '[name=quantity]', cart_form ).val(),
                    variant_id = $( '[name=variation_id]', cart_form ).val(),
                    is_grouped = $( cart_form ).hasClass( 'grouped_form' );
    
    
                if( ( typeof variant_id == 'undefined' ) && typeof e.currentTarget.value !== 'undefined' && e.currentTarget.value != null ){
                    var product_id = e.currentTarget.value;
                }else{
                    var product_id = $( '[name=product_id]', cart_form ).val();
                }

                if( typeof gtm_for_wc_list_products_ga4 !== 'undefined' && gtm_for_wc_list_products_ga4 !== null && gtm_for_wc_list_products_ga4[product_id] !== undefined ){
    
                    var item = gtm_for_wc_list_products_ga4[product_id];
        
                    if ( typeof variant_id !== 'undefined' && variant_id.length > 0 ) {
                        item.item_variant = variant_id;
                    }
        
                    // e.preventDefault();
        
                    dataLayer.push({ ecommerce: null });
                    dataLayer.push({
                        'event': 'add_to_cart',
                        'ecommerce': {
                            'currency': WLGTM.option_data['currency'],
                            'value': item.price * quantity,
                            'quantity': quantity,
                            'items': [item]
                        }
                    });

                }
    
            },
    
            /**
             * [remove_from_cart] remove item from cart
             * @param  {[object]} e
             * @return {[void]} 
             */
            remove_from_cart: function( e ){
                var product_id = $(e.currentTarget).data('product_id'),
                    item = gtm_for_wc_list_products[product_id];
    
                if( typeof item !== 'undefined' && item !== null ){
                    dataLayer.push({ ecommerce: null });
                    dataLayer.push({ cartContent: null });
                    dataLayer.push({
                        'event': 'remove_from_cart',
                        'ecommerce': {
                            'currency': WLGTM.option_data['currency'],
                            'value': item.price * item.quantity,
                            'items': [item]
                        }
                    });
                }
    
            },
    
        };
    
        $( document ).ready( function() {
            GtmWooLentor.init();
        });
    
    })(jQuery);