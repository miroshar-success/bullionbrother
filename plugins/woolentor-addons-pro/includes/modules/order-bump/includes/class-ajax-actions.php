<?php
namespace Woolentor\Modules\Order_Bump;

// If this file is accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


class Ajax_Actions{
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
     * Constructor
     */
    private function __construct() {
        // Quick status chagnes
        add_action( 'wp_ajax_woolentor_order_bump_quick_status_change', [ $this, 'chagne_status' ] );
        add_action( 'wp_ajax_nopriv_woolentor_order_bump_quick_status_change', [ $this, 'chagne_status' ] );

        // Add to cart
        add_action( 'wp_ajax_woolentor_order_bump_add_to_cart', [ $this, 'add_to_cart' ] );
        add_action( 'wp_ajax_nopriv_woolentor_order_bump_add_to_cart', [ $this, 'add_to_cart' ] );

        // Remove product from cart
        add_action( 'wp_ajax_woolentor_order_bump_remove_product_from_cart', [ $this, 'remove_product_from_cart' ] );
        add_action( 'wp_ajax_nopriv_woolentor_order_bump_remove_product_from_cart', [ $this, 'remove_product_from_cart' ] );
        
        // For variations popup
        add_action( 'wp_ajax_woolentor_order_bump_variations_popup', [ $this, 'render_variations_popup' ] );
        add_action( 'wp_ajax_nopriv_woolentor_order_bump_variations_popup', [ $this, 'render_variations_popup' ] );
    }

    /**
     * Open variations poup
     */
    public static function render_variations_popup() {
        $post_data = wp_unslash($_POST);

        global $post, $product, $woocommerce;
        $id      = $post_data['id'];
        $post    = get_post( $id );
        $product = wc_get_product( $id );

        if ( isset( $post_data['id'] ) && $post_data['id'] ) {
            $product_id = (int) $post_data['id'];
            $product    = wc_get_product( $product_id );

            if ( $product && is_object($product) ) { 
                wc_get_template( 
                    'variations-popup.php',
                    array(
                        'product' => $product
                    ),
                    'wl-woo-templates/order-bump',
                    MODULE_PATH. '/templates/'
                );
            }
        }

        wp_die();
    }

    /**
     * Change status
     */
    public function chagne_status(){
        $post_data = wp_unslash($_POST);
        $nonce = !empty($post_data['nonce']) ? $post_data['nonce'] : '';

        // Verify nonce
        $nonce = sanitize_text_field($_REQUEST['nonce']);
        if ( !wp_verify_nonce( $nonce, 'woolentor_order_bump_nonce' ) ) {
            wp_send_json_error(array(
                'message' => esc_html__( 'No naughty business please!', 'woolentor-pro' )
            ));
        }

        // Update status
        $post_id = !empty($post_data['post_id']) ? $post_data['post_id'] : '';
        $status = !empty($post_data['post_status']) ? $post_data['post_status'] : 'draft';

        if( $post_id && $status ){
            wp_update_post( array(
                'ID'            => $post_id,
                'post_status'   => $status
            ) );
            
            wp_send_json_success(array(
                'message' => esc_html__( 'Status changed successfully', 'woolentor-pro' )
            ));
        }
    }

    /**
     * Add to cart
     */
    public function add_to_cart(){
        $post_data = wp_unslash($_POST);
        $nonce = !empty($post_data['nonce']) ? $post_data['nonce'] : '';
    
        // Verify nonce
        $nonce = sanitize_text_field($_REQUEST['nonce']);
        if ( !wp_verify_nonce( $nonce, 'woolentor_order_bump_nonce' ) ) {
            wp_send_json_error(array(
                'message' => esc_html__( 'No naughty business please!', 'woolentor-pro' )
            ));
        }

        // Order bump ID
        $order_bump_id = !empty($post_data['order_bump_id']) ? $post_data['order_bump_id'] : 0;
        if( !Manage_Rules::instance()->validate_order_bump($order_bump_id) ){
            wp_send_json_error(array(
                'message' => esc_html__( 'Order bump is not valid!', 'woolentor-pro' )
            ));
        }

        // Meta data
        $order_bump_meta       = get_post_meta( $order_bump_id, '_woolentor_order_bump', true );
        $product_id            = !empty($order_bump_meta['product']) ? $order_bump_meta['product'] : 0;
        $product               = wc_get_product( $product_id );
        $variation_id          = !empty($order_bump_meta['variation_id']) ? $order_bump_meta['variation_id'] : 0;
        $variation             = !empty($order_bump_meta['variation']) ? $order_bump_meta['variation'] : 0;
        $quantity              = !empty($order_bump_meta['qty']) ? $order_bump_meta['qty'] : 1;

        // Add to cart
        if( $product_id ){
            $product_data = wc_get_product( $product_id );

            if( $product_data ){
                $cart_item_key = WC()->cart->add_to_cart( 
                    $product->get_id(), 
                    $quantity, 
                    $variation_id, 
                    $variation, 
                    array()
                );

                wp_send_json_success(array(
                    'message'       => esc_html__( 'Product added to cart successfully', 'woolentor-pro' ),
                    'cart_item_key' => $cart_item_key,
                ));
            }
        }

        wp_send_json_error(array(
            'message' => esc_html__( 'Something went wrong', 'woolentor-pro' )
        ));
    }

    /**
     * Remove product from cart
     */
    public function remove_product_from_cart(){
        $post_data = wp_unslash($_POST);
        $nonce = !empty($post_data['nonce']) ? $post_data['nonce'] : '';
    
        // Verify nonce
        $nonce = sanitize_text_field($_REQUEST['nonce']);
        if ( !wp_verify_nonce( $nonce, 'woolentor_order_bump_nonce' ) ) {
            wp_send_json_error(array(
                'message' => esc_html__( 'No naughty business please!', 'woolentor-pro' )
            ));
        }

        // Remove product from cart
        $cart_item_key = !empty($post_data['cart_item_key']) ? $post_data['cart_item_key'] : '';
        if( $cart_item_key ){
            WC()->cart->remove_cart_item( $cart_item_key );
            wp_send_json_success(array(
                'status'  => true,
                'message' => esc_html__( 'Product removed from cart successfully', 'woolentor-pro' )
            ));
        }

        // Something went wrong
        wp_send_json_error(array(
            'message' => esc_html__( 'Something went wrong', 'woolentor-pro' )
        ));
    }
}