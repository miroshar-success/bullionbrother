<?php
namespace Woolentor\Modules\Order_Bump;

// If this file is accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


class Helper{
    protected static $_instance = null;
    
    /**
     * Instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * It writes a log message to the debug.log file.
     * 
     * @param args An array of arguments.
     * 
     * @return the value of the variable .
     */
    public static function maybe_write_debug_log( $args ){
        $case            = !empty($args['case']) ? $args['case'] : '';
        $order_bump_name = !empty($args['order_bump_name']) ? $args['order_bump_name'] : '';

        if( $case === 'discount_exceed_original_price' ){
            $message = sprintf( esc_html__( 'Order Bump:%s -Price did not changed, the discount amount exceed the original price.', 'woolentor-pro' ), $order_bump_name );

            self::write_debug_log( $message );
        }
    }

    /**
     * It writes a message to the debug log.
     * 
     * @param message The message to write to the log.
     */
    public static function write_debug_log( $message ){
        if( !defined('WP_DEBUG_LOG') && !WP_DEBUG_LOG ){
            return;
        }

        error_log( $message );
    }

    /**
     * Get the global option value by the given key, if meta value is set override the global value.
     * 
     * @param order_bump_id The ID of the order bump.
     * @param key The key of the option value.
     * @param default The default value to return if the option isn't set.
     */
    public static function get_option( $order_bump_id, $key, $default = '' ){
        $module_options = get_option( 'woolentor_order_bump_settings');
        $default = isset( $module_options[$key] ) ? $module_options[$key] : $default;

        $meta_data = get_post_meta( $order_bump_id, '_woolentor_order_bump', true );
        return !empty( $meta_data[$key] ) ? $meta_data[$key] : $default;
    }

    /**
     * Check and determine if the product price should be discounted or not.
     * 
     * @param order_bump_id The ID of the order bump.
     */
    public static function should_adjust_price( $order_bump_id ){
        $meta_data       = get_post_meta( $order_bump_id, '_woolentor_order_bump', true );
        $discount_type   = !empty( $meta_data['discount_type'] ) ? $meta_data['discount_type'] : '';
        $discount_amount = !empty( $meta_data['discount_amount'] ) ? $meta_data['discount_amount'] : '';

        if( $discount_type && $discount_amount ){
            return true;
        }

        return false;
    }

    /**
     * Check if test mode is active.
     */
    public static function is_test_mode(){
        $module_options = get_option( 'woolentor_order_bump_settings');
        $test_mode = isset( $module_options['enable_test_mode'] ) ? $module_options['enable_test_mode'] : '';

        if( $test_mode ){
            return true;
        }

        return false;
    }

    /**
     * It takes the base price of the product, and applies the discount to it
     * 
     * @param order_bump_id The ID of the order bump
     * @param product The product object
     * @param base_price (optional) The base price in which the discount is applied.
     * 
     * @return The discounted price of the product.
     */
    public static function get_discounted_price( $order_bump_id, $product, $base_price ){
        // Return if the product is not set
        if( !is_object($product) ){
            return $base_price;
        }

        $meta_data          = get_post_meta( $order_bump_id, '_woolentor_order_bump', true );
        $discount_type      = !empty( $meta_data['discount_type'] ) ? $meta_data['discount_type'] : '';
        $discount_amount    = !empty( $meta_data['discount_amount'] ) ? (float) $meta_data['discount_amount'] : 0;

        // Don't go further if there is not discount type is set
        if( !$discount_type){
            return $base_price;
        }

        // Add support for defined base price type
        $discount_base_price = Helper::get_option($order_bump_id, 'discount_base_price', 'regular_price');
        
        // Base price in which the discount is applied
        if( !empty($base_price) ){
            $base_price = (float) $base_price;
        } elseif( $discount_base_price === 'regular_price' ){
            $base_price = (float) $product->get_regular_price();
        } else {
            $base_price = (float) $product->get_price();
        }
        
        switch ( $discount_type ) {
            case 'percent_amount':
                $discounted_price = $base_price - ( $base_price * ( $discount_amount / 100 ) );
                $discounted_price = $discounted_price >= 0 ? $discounted_price : $base_price;

                Helper::maybe_write_debug_log(array(
                    'case'            => 'discount_exceed_original_price',
                    'order_bump_name' => get_the_title( $order_bump_id ),
                ));

                return $discounted_price;
                break;

            case 'fixed_amount':
                $discounted_price = $base_price - $discount_amount;
                $discounted_price = $discounted_price >= 0 ? $discounted_price : $base_price;

                Helper::maybe_write_debug_log(array(
                    'case'            => 'discount_exceed_original_price',
                    'order_bump_name' => get_the_title( $order_bump_id ),
                ));

                return $discounted_price;
                break;

            case 'fixed_price':
                $discounted_price = $discount_amount;
                return $discounted_price;
                break;
        }

        // Return the base price the case of any other error
        return $base_price;
    }

    /**
     * It takes the base price of the variable product, and applies the discount to it
     * 
     * @param order_bump_id The ID of the order bump
     * @param product The product object
     * 
     * @return The discounted price of the product.
     */
    public static function get_discounted_prices( $order_bump_id, $product, $base_price = '' ){
        $base_price_type = Helper::get_option($order_bump_id, 'discount_base_price', 'regular_price');

        $discounted_prices = array(
            'min' => 0,
            'max' => 0,
        );

        if( $product->is_type('variable') ){
            $prices = $product->get_variation_prices( true );
            $min_price     = current( $prices['price'] );
			$max_price     = end( $prices['price'] );
			$min_reg_price = current( $prices['regular_price'] );
			$max_reg_price = end( $prices['regular_price'] );

            if( $base_price_type === 'regular_price' ){
                $discounted_prices['min'] = (float) $min_reg_price - (float) Helper::get_discounted_price( $order_bump_id, $product, $min_reg_price );
                $discounted_prices['max'] = (float) $max_reg_price - (float) Helper::get_discounted_price( $order_bump_id, $product, $max_reg_price );
            } else {
                $discounted_prices['min'] = (float) $min_price - (float) Helper::get_discounted_price( $order_bump_id, $product, $min_price );
                $discounted_prices['max'] = (float) $max_price - (float) Helper::get_discounted_price( $order_bump_id, $product, $max_price );
            }
        }

        return $discounted_prices;
    }

    /**
     * It returns an array of hooks and their labels
     * 
     * @param context This is the context of the hook. It can be either 'post_column' or
     * empty.
     * 
     * @return An array of hooks.
     */
    public static function get_postion_hooks( $context = '' ){
        if( $context === 'post_column' ){
            return array(
                'woocommerce_before_checkout_billing_form'   => __( 'Before Billing', 'woolentor-pro' ),
                'woocommerce_after_checkout_billing_form'    => __( 'After Billing', 'woolentor-pro' ),
                'woocommerce_before_checkout_shipping_form'  => __( 'Before Shipping', 'woolentor-pro' ),
                'woocommerce_after_checkout_shipping_form'   => __( 'After Shipping', 'woolentor-pro' ),
                'woocommerce_before_order_notes'             => __( 'Before Order Note', 'woolentor-pro' ),
                'woocommerce_after_order_notes'              => __( 'After Order Note', 'woolentor-pro' ),
                'woocommerce_checkout_before_order_review'   => __( 'Before Review Order', 'woolentor-pro' ),
                'woocommerce_checkout_after_order_review'    => __( ' After Review Order', 'woolentor-pro' ),
                'woocommerce_review_order_before_payment'    => __( 'Before Payment Gateways ', 'woolentor-pro' ),
                'woocommerce_review_order_after_payment'     => __( 'After Payment Gateways', 'woolentor-pro' ),
            );
        } 

        return array(
            'woocommerce_before_checkout_billing_form'   => __( 'Billing - Before', 'woolentor-pro' ),
            'woocommerce_after_checkout_billing_form'    => __( 'Billing - After', 'woolentor-pro' ),
            'woocommerce_before_checkout_shipping_form'  => __( 'Shipping - Before', 'woolentor-pro' ),
            'woocommerce_after_checkout_shipping_form'   => __( 'Shipping - After', 'woolentor-pro' ),
            'woocommerce_before_order_notes'             => __( 'Order Note - Before', 'woolentor-pro' ),
            'woocommerce_after_order_notes'              => __( 'Order Note - After', 'woolentor-pro' ),
            'woocommerce_checkout_before_order_review'   => __( 'Review Order - Before', 'woolentor-pro' ),
            'woocommerce_checkout_after_order_review'    => __( 'Review Order - After', 'woolentor-pro' ),
            'woocommerce_review_order_before_payment'    => __( 'Payment Gateways - Before', 'woolentor-pro' ),
            'woocommerce_review_order_after_payment'     => __( 'Payment Gateways - After', 'woolentor-pro' ),
        );
    }

    /**
     * Check if cart containts the targeted product.
     *
     * @param string product id
     * @return array
     */
    public static function find_product_in_cart( $product_id ){
        $defaults = array(
            'cart_item_key' => '',
        );

        if( !$product_id ){
            return $defaults;
        }

        $cart_items = WC()->cart->get_cart();
        if( !empty( $cart_items ) ){
            foreach( $cart_items as $cart_item_key => $cart_item ){
                // @todo: add support variation & bundle product here
                if( $cart_item['product_id'] == $product_id ){
                    return array(
                        'cart_item_key' => $cart_item_key,
                    );

                    break;
                }
            }
        }

        return $defaults;
    }

    /**
     * Prepare order bump classes.
     * 
     * @param array                 classes
     * @param string|int            $order_bump_id
     * @return array
     */
    public static function get_order_bump_class( $class = array(), $order_bump_id = '' ) {
        if( !$order_bump_id ){
            return array();
        }

        $meta_data           = get_post_meta( $order_bump_id, '_woolentor_order_bump', true );
        $offer_product_id    = !empty( $meta_data['product'] ) ? $meta_data['product'] : 0;
        $style               = !empty( $meta_data['style'] ) ? $meta_data['style'] : 4;

        // General class
        $classes = array(
            'woolentor-order-bump',
            'woolentor-order-bump-'. $order_bump_id,
            'wl-style-'. $style
        );
        
        // Checked class
        $checked_product_arr = self::find_product_in_cart( $offer_product_id );
        if( $checked_product_arr['cart_item_key'] ){
            $classes[] = 'woolentor-order-bump-checked';
        }

        // Matched rules class
        $available_offers = Manage_Rules::instance()->fetch_offers();
        foreach( $available_offers as $order_bump_id => $name ){
            $mathced_rules = Manage_Rules::instance()->get_matched_rules_group( $order_bump_id );

            if( is_array($mathced_rules) ){
                foreach( $mathced_rules as $i => $rule ){
                    $classes[] = 'woolentor-order-bump-rule-' . $rule['base'];
                }
            }
        }

        // Extra class
        if( $class ){
            $classes  = array_merge( $classes, $class );
        }
        
        return $classes;
    }

    /**
     * Modified function of wc_get_product_class() in woocommerce/includes/wc-template-functions.php
     * Removed post_claass filter from the function. Because it was causing a conflict with the astra theme.
     *
     * @param string|array           $class      One or more classes to add to the class list.
     * @param int|WP_Post|WC_Product $product Product ID or product object.
     * @return array
     */
    public static function wc_get_product_class( $class = '', $product = null ) {
        if ( is_null( $product ) && ! empty( $GLOBALS['product'] ) ) {
            // Product was null so pull from global.
            $product = $GLOBALS['product'];
        }

        if ( $product && ! is_a( $product, 'WC_Product' ) ) {
            // Make sure we have a valid product, or set to false.
            $product = wc_get_product( $product );
        }

        if ( $class ) {
            if ( ! is_array( $class ) ) {
                $class = preg_split( '#\s+#', $class );
            }
        } else {
            $class = array();
        }

        $post_classes = array_map( 'esc_attr', $class );

        if ( ! $product ) {
            return $post_classes;
        }

        // Run through the post_class hook so 3rd parties using this previously can still append classes.
        // Note, to change classes you will need to use the newer woocommerce_post_class filter.
        // @internal This removes the wc_product_post_class filter so classes are not duplicated.
        $filtered = has_filter( 'post_class', 'wc_product_post_class' );

        if ( $filtered ) {
            remove_filter( 'post_class', 'wc_product_post_class', 20 );
        }

        if ( $filtered ) {
            add_filter( 'post_class', 'wc_product_post_class', 20, 3 );
        }

        $classes = array_merge(
            $post_classes,
            array(
                'product',
                'type-product',
                'post-' . $product->get_id(),
                'status-' . $product->get_status(),
                wc_get_loop_class(),
                $product->get_stock_status(),
            ),
            wc_get_product_taxonomy_class( $product->get_category_ids(), 'product_cat' ),
            wc_get_product_taxonomy_class( $product->get_tag_ids(), 'product_tag' )
        );

        if ( $product->get_image_id() ) {
            $classes[] = 'has-post-thumbnail';
        }
        if ( $product->get_post_password() ) {
            $classes[] = post_password_required( $product->get_id() ) ? 'post-password-required' : 'post-password-protected';
        }
        if ( $product->is_on_sale() ) {
            $classes[] = 'sale';
        }
        if ( $product->is_featured() ) {
            $classes[] = 'featured';
        }
        if ( $product->is_downloadable() ) {
            $classes[] = 'downloadable';
        }
        if ( $product->is_virtual() ) {
            $classes[] = 'virtual';
        }
        if ( $product->is_sold_individually() ) {
            $classes[] = 'sold-individually';
        }
        if ( $product->is_taxable() ) {
            $classes[] = 'taxable';
        }
        if ( $product->is_shipping_taxable() ) {
            $classes[] = 'shipping-taxable';
        }
        if ( $product->is_purchasable() ) {
            $classes[] = 'purchasable';
        }
        if ( $product->get_type() ) {
            $classes[] = 'product-type-' . $product->get_type();
        }
        if ( $product->is_type( 'variable' ) && $product->get_default_attributes() ) {
            $classes[] = 'has-default-attributes';
        }

        // Include attributes and any extra taxonomies only if enabled via the hook - this is a performance issue.
        if ( apply_filters( 'woocommerce_get_product_class_include_taxonomies', false ) ) {
            $taxonomies = get_taxonomies( array( 'public' => true ) );
            $type       = 'variation' === $product->get_type() ? 'product_variation' : 'product';
            foreach ( (array) $taxonomies as $taxonomy ) {
                if ( is_object_in_taxonomy( $type, $taxonomy ) && ! in_array( $taxonomy, array( 'product_cat', 'product_tag' ), true ) ) {
                    $classes = array_merge( $classes, wc_get_product_taxonomy_class( (array) get_the_terms( $product->get_id(), $taxonomy ), $taxonomy ) );
                }
            }
        }

        /**
         * WooCommerce Post Class filter.
         *
         * @since 3.6.2
         * @param array      $classes Array of CSS classes.
         * @param WC_Product $product Product object.
         */
        $classes = apply_filters( 'woocommerce_post_class', $classes, $product );

        return array_map( 'esc_attr', array_unique( array_filter( $classes ) ) );
    }
}