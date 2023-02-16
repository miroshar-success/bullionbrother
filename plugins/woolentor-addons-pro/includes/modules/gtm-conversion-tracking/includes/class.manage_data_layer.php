<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooLentor_Manage_Data_Layer handler class
 */
class WooLentor_Manage_Data_Layer extends Woolentor_GTM_Conversion_Tracking {

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [$product_array_filter, $cart_item_filter, $order_item_filter] Get product filter
     * @var string
     */
    public $product_array_filter = 'woolentor_get_product_array';
    public $cart_item_filter 	 = 'woolentor_get_cart_item';
    public $order_item_filter 	 = 'woolentor_get_order_item';

    /**
     * [$items_by_product_id, $ga4_items_by_product_id] Product array
     * @var [array]
     */
    private $items_by_product_id;
    private $ga4_items_by_product_id;

    /**
     * [instance] Initializes a singleton instance
     * @return [WooLentor_Manage_Data_Layer]
     */
    public static function get_instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * [__construct] Class construct
     */
    function __construct() {
        if ( function_exists('woolentor_is_woocommerce') && woolentor_is_woocommerce() ) {
			add_filter( WOOLENTOR_GTM_DATALAYER, [ $this, 'datalayer_filter_items' ], 10, 1 );
			add_action( 'the_post', [ $this, 'the_post_action' ], 11 );
			add_action( 'wp_footer', [ $this, 'wp_footer_action' ], 10 );
		}
    }

    /**
     * [the_post] Post callable function
     * @return [void]
     */
    public function the_post_action(){
    	$this->product_loop();
    }

    /**
     * [product_loop] product loop
     * @return [void]
     */
    public function product_loop() {
        global $product;

        if ( is_a ( $product, 'WC_Product' ) ) {
            $item = $this->prepare_product( $product, array(), 'view_item_list' );
            $ga4_item = $this->prepare_product_ga4( $item );
            $this->items_by_product_id[$product->get_id()] = $item;
            $this->ga4_items_by_product_id[$product->get_id()] = $ga4_item;
        }

    }

    /**
     * [wp_footer_action] wp footer callable function
     * @return [void]
     */
    public function wp_footer_action(){

		wc_enqueue_js('
			window.gtm_for_wc_list_products     = ' . json_encode( $this->items_by_product_id ) . ';
			window.gtm_for_wc_list_products_ga4 = ' . json_encode( $this->ga4_items_by_product_id ) . ';'
		);

    }

    /**
     * [datalayer_filter_items]
     * @param  [type] $dataLayer Existing data layer
     * @return [array] data layer array
     */
    public function datalayer_filter_items( $dataLayer ){
    	$woo = WC();

    	$get_currency_code = get_woocommerce_currency();

    	// Get option data
    	$shop_page = $this->get_saved_data( 'shop_enable', 'on' );

    	$product_page = $this->get_saved_data( 'product_enable', 'on' );

    	$cart_page = $this->get_saved_data( 'cart_enable', 'on' );

    	$checkout_page = $this->get_saved_data( 'checkout_enable', 'on' );

    	$thankyou_page = $this->get_saved_data( 'thankyou_enable', 'on' );

    	$termobj 			= get_queried_object();
        $get_all_taxonomies = woolentor_get_taxonomies();
    	if ( is_shop() || ( is_tax('product_cat') && is_product_category() ) || ( is_tax('product_tag') && is_product_tag() ) || ( isset( $termobj->taxonomy ) && is_tax( $termobj->taxonomy ) && array_key_exists( $termobj->taxonomy, $get_all_taxonomies ) ) ){

    		if( $shop_page == 'on' ){
	    		wc_enqueue_js('window.gtm_for_wc_shop = true;');
	    	}

    	}

    	/**
    	 * Product details page
    	 */
    	elseif ( is_product() && $product_page == 'on' ) {

    		$get_post_id 	= get_the_ID();
			$product    	= wc_get_product( $get_post_id );

			$get_product_array 		= $this->prepare_product( $product, array(), 'product_details' );
			$get_ga4_product_array 	= $this->prepare_product_ga4( $get_product_array );

			$dataLayer['event'] = 'view_item';
			$dataLayer['productRatingCounts']  = $product->get_rating_counts();
			$dataLayer['productAverageRating'] = (float) $product->get_average_rating();
			$dataLayer['productReviewCount']   = (int) $product->get_review_count();
			$dataLayer['productType']          = $product->get_type();

			$dataLayer['ecommerce']['items'][] = $get_ga4_product_array;

    	}

    	/**
    	 * Cart page
    	 */
    	elseif ( is_cart() && version_compare( $woo->version, "3.2", ">=" ) && $cart_page == 'on' ) {
			
			$current_cart = $woo->cart;

			$dataLayer['event'] = 'view_cart';
			$dataLayer['cartContent'] = array(
				'totals' => array(
					'applied_coupons' => $current_cart->get_applied_coupons(),
					'discount_total'  => $current_cart->get_discount_total(),
					'subtotal'        => $current_cart->get_subtotal(),
					'total'           => $current_cart->get_cart_contents_total()
				),
				'items' => array()
			);

			foreach( $current_cart->get_cart() as $cart_item_id => $cart_item_data) {
				$product = apply_filters( 'woocommerce_cart_item_product', $cart_item_data["data"], $cart_item_data, $cart_item_id );
				if (
					!apply_filters( $this->cart_item_filter, true, $cart_item_data )
					|| !apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item_data, $cart_item_id )
					) {
					continue;
				}

				$get_product_array = $this->prepare_product( $product, array(
					'quantity' => $cart_item_data["quantity"]
				), 'cart' );

				$get_ga4_product_array = $this->prepare_product_ga4( $get_product_array );

				$dataLayer['cartContent']['items'][] = $get_ga4_product_array;
				$dataLayer['ecommerce']['items'][] = $get_ga4_product_array;

				$this->items_by_product_id[$product->get_id()] = $get_ga4_product_array;

			}

		}

		/**
		 * Order received page / Thankyou page
		 */
		elseif ( is_order_received_page() && $thankyou_page == 'on' ) {

			$order_id   = empty( $_GET['order'] ) ? ( $GLOBALS['wp']->query_vars['order-received'] ? $GLOBALS['wp']->query_vars['order-received'] : 0 ) : absint( $_GET['order'] );
			$order_id_filtered  = apply_filters( 'woocommerce_thankyou_order_id', $order_id );
			
			if ( '' != $order_id_filtered ) {
				$order_id = $order_id_filtered;
			}

			$order_key = apply_filters( 'woocommerce_thankyou_order_key', empty( $_GET['key'] ) ? '' : wc_clean( $_GET['key'] ) );

			if ( $order_id > 0 ) {

				$order = wc_get_order( $order_id );

				if ( is_a( $order, 'WC_Order' ) ) {

					$this_order_key = $order->get_order_key();

					if ( $this_order_key != $order_key ) {
						unset( $order );
					}

				} else {
					unset( $order );
				}

			}

			if ( isset( $order ) ) {

				$order_items = $this->prepare_order_items( $order );

				$dataLayer['event'] = 'purchase';
				$dataLayer['ecommerce'] = array(
					'transaction_id'=> $order->get_order_number(),
					'affiliation' 	=> '',
					'value' 		=> $order->get_total(),
					'tax'			=> $order->get_tax_totals(),
					'shipping' 		=> $order->get_shipping_total(),
					'currency' 		=> $order->get_currency(),
					'coupon' 		=> implode( ', ', ( version_compare( WC()->version, '3.7', '>=' ) ? $order->get_coupon_codes() : $order->get_used_coupons() ) ),
					'items' 		=> $order_items['products'],
				);

				$dataLayer['orderData'] = array(
					'attributes' => array(
						'date' => $order->get_date_created()->date( 'c' ),

						'order_number' => $order->get_order_number(),
						'order_key'    => $order->get_order_key(),

						'payment_method'       => esc_js( $order->get_payment_method() ),
						'payment_method_title' => esc_js( $order->get_payment_method_title()  ),

						'shipping_method' => esc_js( $order->get_shipping_method() ),

						'status' => esc_js( $order->get_status() ),

						'coupons' => implode( ', ', ( version_compare( WC()->version, '3.7', '>=' ) ? $order->get_coupon_codes() : $order->get_used_coupons() ) )
					),
					'totals' => array(
						'currency'       => esc_js( $order->get_currency() ),
						'discount_total' => esc_js( $order->get_discount_total() ),
						'discount_tax'   => esc_js( $order->get_discount_tax() ),
						'shipping_total' => esc_js( $order->get_shipping_total() ),
						'shipping_tax'   => esc_js( $order->get_shipping_tax() ),
						'cart_tax'       => esc_js( $order->get_cart_tax() ),
						'total'          => esc_js( $order->get_total() ),
						'total_tax'      => esc_js( $order->get_total_tax() ),
						'total_discount' => esc_js( $order->get_total_discount() ),
						'subtotal'       => esc_js( $order->get_subtotal() ),
						'tax_totals'     => $order->get_tax_totals()
					),
					'customer' => array(
						'id' => $order->get_customer_id(),

						'billing' => array(
							'first_name' => esc_js( $order->get_billing_first_name() ),
							'last_name'  => esc_js( $order->get_billing_last_name() ),
							'company'    => esc_js( $order->get_billing_company() ),
							'address_1'  => esc_js( $order->get_billing_address_1() ),
							'address_2'  => esc_js( $order->get_billing_address_2() ),
							'city'       => esc_js( $order->get_billing_city() ),
							'state'      => esc_js( $order->get_billing_state() ),
							'postcode'   => esc_js( $order->get_billing_postcode() ),
							'country'    => esc_js( $order->get_billing_country() ),
							'email'      => esc_js( $order->get_billing_email() ),
							'phone'      => esc_js( $order->get_billing_phone() )
						),

						'shipping' => array(
							'first_name' => esc_js( $order->get_shipping_first_name() ),
							'last_name'  => esc_js( $order->get_shipping_last_name() ),
							'company'    => esc_js( $order->get_shipping_company() ),
							'address_1'  => esc_js( $order->get_shipping_address_1() ),
							'address_2'  => esc_js( $order->get_shipping_address_2() ),
							'city'       => esc_js( $order->get_shipping_city() ),
							'state'      => esc_js( $order->get_shipping_state() ),
							'postcode'   => esc_js( $order->get_shipping_postcode() ),
							'country'    => esc_js( $order->get_shipping_country() )
						)

					),
					'items' => $order_items['products']
				);

			}

		}

		/**
		 * Checkout page
		 */
		elseif ( is_checkout() && $checkout_page == 'on' ) {

			$checkout_products  = array();
			$total_value        = 0;

			foreach ( $woo->cart->get_cart() as $cart_item_id => $cart_item_data ) {
				$product = apply_filters( 'woocommerce_cart_item_product', $cart_item_data['data'], $cart_item_data, $cart_item_id );

				$get_product_array = $this->prepare_product( $product, array(
					'quantity' => $cart_item_data['quantity']
				), 'cart' );

				$checkout_products[] = $get_product_array;

				$total_value += $get_product_array['quantity'] * $get_product_array['price'];

			}

			$dataLayer['event'] 			= 'begin_checkout';
			$dataLayer['ecomm_pagetype']   	= 'cart';
			$dataLayer['ecomm_totalvalue'] 	= (float) $total_value;
			$dataLayer['currencyCode'] 		= $get_currency_code;

			$all_products = array();
			foreach( $checkout_products as $oneproduct ) {
				$all_products[] = $this->prepare_product_ga4( $oneproduct );
			}

			$dataLayer['ecommerce'] = array(
				'items' => $all_products,
			);

		}

		return $dataLayer;

    }

    /**
     * [get_term_name_by_id]
     * @param  [int] $product_id product id
     * @param  string $taxonomy product taxonomy name
     * @return [string] taxonomy name
     */
    public function get_term_name_by_id( $product_id, $taxonomy = 'product_cat' ){

    	$term_name = '';

		$get_product_terms = get_the_terms( $product_id, $taxonomy );

		if ( ( is_array( $get_product_terms ) ) && ( count( $get_product_terms ) > 0 ) ) {

			$get_first_product_term = array_pop( $get_product_terms );
			$term_name 				= $get_first_product_term->name;

		}

		return $term_name;

    }

    /**
     * [prepare_product]
     * @param  [object] $product product object
     * @param  [array] $additional_attributes product additional attributes
     * @param  [array] $attributes product attributes
     * @return [array] product data 
     */
    public function prepare_product( $product, $additional_attributes, $attributes ){

		if ( ! $product ) {
			return false;
		}

		if ( ! is_a( $product, 'WC_Product' ) ) {
			return false;
		}

		$product_id      = $product->get_id();
		$product_type    = $product->get_type();
		$product_sku     = $product->get_sku();
		$data_product_id = $product_id;

		if ( 'variation' == $product_type ) {
			$product_parent_id = $product->get_parent_id();
			$product_cat       = $this->get_term_name_by_id( $product_parent_id );
		} else {
			$product_cat       = $this->get_term_name_by_id( $product_id );
		}

		$use_sku = $this->get_saved_data( 'use_sku', 'off' );

		if ( ( $use_sku == 'on' ) && ( '' != $product_sku ) ) {
			$data_product_id = $product_sku;
		}

		$product_data = array(
			'id'         => $data_product_id,
			'sku'        => $product_sku ? $product_sku : $product_id,
			'name'       => $product->get_title(),
			'price'      => ( float ) wc_get_price_to_display( $product ),
			'category'   => $product_cat,
			'stocklevel' => $product->get_stock_quantity()
		);

		$band_taxonomy = $this->get_saved_data( 'product_brands', 'none' );

		if ( 'none' !== $band_taxonomy ) {

			if ( isset( $product_parent_id ) && ( 0 !== $product_parent_id ) ) {
				$product_id_to_query = $product_parent_id;
			} else {
				$product_id_to_query = $product_id;
			}

			$product_data[ 'brand' ] = $this->get_term_name_by_id( $product_id_to_query, $band_taxonomy );

		}

		if ( 'variation' == $product_type ) {
			$product_data['variant'] = implode( ',', $product->get_variation_attributes() );
		}

		$product_data = array_merge( $product_data, $additional_attributes );

		return apply_filters( $this->product_array_filter, $product_data, $attributes );


    }

    /**
     * [prepare_product_ga4]
     * @param  [array] $product_data product data array
     * @return [array]
     */
    public function prepare_product_ga4( $product_data ){

    	if ( !is_array( $product_data ) ) {
			return;
		}

		$category_path  = array_key_exists( 'category', $product_data ) ? $product_data[ 'category' ] : '';
		$category_parts = explode( '/', $category_path );

		// default data
		$generate_ga4_product = array(
			'item_id' 	=> array_key_exists( 'id', $product_data ) ? $product_data[ 'id' ] : '',
			'item_name' => array_key_exists( 'name', $product_data ) ? $product_data[ 'name' ] : '',
			'item_brand'=> array_key_exists( 'brand', $product_data ) ? $product_data[ 'brand' ] : '',
			'price' 	=> array_key_exists( 'price', $product_data ) ? $product_data[ 'price' ] : ''
		);

		if ( 1 == count($category_parts) ) {
			$generate_ga4_product[ 'item_category' ] = $category_parts[0];
		} else if ( count($category_parts) > 1 ) {
			$generate_ga4_product[ 'item_category' ] = $category_parts[0];
			for( $i=1; $i < min( 5, count( $category_parts ) ); $i++ ) {
				$generate_ga4_product[ 'item_category_' . (string)($i+1) ] = $category_parts[$i];
			}
		}

		// optional data
		if ( array_key_exists( 'variant', $product_data ) ) {
			$generate_ga4_product[ 'item_variant' ] = $product_data[ 'variant' ];
		}
		if ( array_key_exists( 'listname', $product_data ) ) {
			$generate_ga4_product['item_list_name' ] = $product_data[ 'listname' ];
		}
		if ( array_key_exists( 'listposition', $product_data ) ) {
			$generate_ga4_product[ 'index' ] = $product_data[ 'listposition' ];
		}
		if ( array_key_exists( 'quantity', $product_data ) ) {
			$generate_ga4_product[ 'quantity' ] = $product_data[ 'quantity' ];
		}
		if ( array_key_exists( 'coupon', $product_data ) ) {
			$generate_ga4_product[ 'coupon' ] = $product_data[ 'coupon' ];
		}

		return $generate_ga4_product;


    }

    /**
     * [prepare_order_items] order item prepare
     * @param  [object] $order
     * @return [array]
     */
    public function prepare_order_items( $order ) {

		$return_data = array(
			'products' 		=> [],
			'totalprice'	=> 0,
			'product_ids' 	=> []
		);

		if ( ! $order ) {
			return $return_data;
		}

		$order_items = $order->get_items();

		if ( $order_items ) {
			foreach ( $order_items as $item ) {

				if ( ! apply_filters( $this->order_item_filter, true, $item ) ) {
					continue;
				}

				$product = $item->get_product();
				$inc_tax = ( 'incl' === get_option( 'woocommerce_tax_display_shop' ) );
				$product_price = (float) $order->get_item_total( $item, $inc_tax );
				$get_product_array = $this->prepare_product( $product, array(
					'quantity' => $item->get_quantity(),
					'price'    => $product_price
				), 'purchase' );

				if ( $get_product_array ) {
					$return_data['products'][]    = $get_product_array;
					$return_data['totalprice']    += $product_price * $get_product_array['quantity'];
					$return_data['product_ids'][] = $get_product_array['id'];
				}
			}

		}

		return $return_data;

	}



}