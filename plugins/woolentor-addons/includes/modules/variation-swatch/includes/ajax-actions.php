<?php
namespace Woolentor\Modules\Swatchly;
use Woolentor\Modules\Swatchly\Helper as Helper;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Ajax_Actions{
    private static $_instance = null;

    /**
     * Instance
     */
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct(){
        if( Helper::get_option('pl_enable_ajax_add_to_cart') && 
            Helper::get_option('pl_enable_cart_popup_notice', '', 1)
        ){
            // generate ajax add to cart notice
            add_action( 'wp_ajax_swatchly_ajax_add_to_cart_notice', array($this, 'ajax_add_to_cart_notice') );
            add_action( 'wp_ajax_nopriv_swatchly_ajax_add_to_cart_notice', array($this, 'ajax_add_to_cart_notice') );
        }

        /**
         * AJAX add to cart.
         */
        add_action( 'wp_ajax_swatchly_ajax_add_to_cart', array($this, 'ajax_add_to_cart') );
        add_action( 'wp_ajax_nopriv_swatchly_ajax_add_to_cart', array($this, 'ajax_add_to_cart') );

        /**
         * Reload metabox data on variation update
         */
        add_action( 'wp_ajax_swatchly_ajax_reload_metabox_panel', array($this, 'ajax_reload_metabox_panel') );

        /**
         * Save product metabox data (ajax)
         */
        add_action( 'wp_ajax_swatchly_ajax_save_product_meta', array($this, 'ajax_save_product_meta') );

        /**
         * Reset product metabox data (ajax)
         */
        add_action( 'wp_ajax_swatchly_ajax_reset_product_meta', array($this, 'ajax_reset_product_meta') );
    }

    /**
     * Ajax WooCommerce notices.
     */
    public function ajax_add_to_cart_notice() {
        wc_print_notices();
        wp_die();
    }

    /**
     * AJAX add to cart.
     */
    public function ajax_add_to_cart() {
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }

        $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
        $product_title     = get_the_title( $product_id );
        $quantity          = ! empty( $_POST['quantity'] ) ? wc_stock_amount( absint( $_POST['quantity'] ) ) : 1;
        $product_status    = get_post_status( $product_id );
        $variation_id      = ! empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
        $variation         = ! empty( $_POST['variation'] ) ? array_map( 'sanitize_text_field', $_POST['variation'] ) : array();
        $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variation );
        $cart_page_url     = wc_get_cart_url();

        if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) && 'publish' === $product_status ) {

            do_action( 'woocommerce_ajax_added_to_cart', $product_id );

            if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
                wc_add_to_cart_message( array( $product_id => $quantity ), true );
            } else {
                $added_to_cart_notice = sprintf(
                    /* translators: %s: Product title */
                    esc_html__( '"%1$s" has been added to your cart. %2$s', 'woolentor' ),
                    esc_html( $product_title ),
                    '<a href="' . esc_url( $cart_page_url ) . '">' . esc_html__( 'View Cart', 'woolentor' ) . '</a>'
                );

                wc_add_notice( $added_to_cart_notice );
            }

            \WC_AJAX::get_refreshed_fragments();

        } else {

            // If there was an error adding to the cart, redirect to the product page to show any errors.
            $data = array(
                'error'       => true,
                'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
            );

            wp_send_json( $data );
        }
    }

    /**
     * Reload metabox data on variation update
     */
    public function ajax_reload_metabox_panel(){
        // Verify nonce
        $nonce = sanitize_text_field($_REQUEST['nonce']);
        if ( !wp_verify_nonce( $nonce, 'swatchly_product_metabox_save_nonce' ) ) {
            wp_send_json_error(array(
                'message' => esc_html__( 'No naughty business please!', 'woolentor' )
            ));
        }

        $product_id = absint($_REQUEST['product_id']);
        $product    = wc_get_product($product_id);
        if ( $product && !$product->is_type('variable') ) {
            wp_die( -1 );
        }
    

        // Panel inner HTML
        ob_start();
        Admin\Product_Metabox::metabox_panel_inner_html($product_id);
        $html = ob_get_clean();
        
        wp_send_json_success($html);
    }

    /**
     * Save product metabox data (ajax)
     */
    public function ajax_save_product_meta(){
        // verify nonce
        $nonce = sanitize_text_field($_REQUEST['nonce']);
        if ( !wp_verify_nonce( $nonce, 'swatchly_product_metabox_save_nonce' ) ) {
            wp_send_json_error(array(
                'message' => esc_html__( 'No naughty business please!', 'woolentor' )
            ));
        }

        // check current user privilege
        if ( !current_user_can( 'edit_products' ) ) {
            wp_die( -1 );
        }

        $product_id = absint($_REQUEST['product_id']);
        $product    = wc_get_product($product_id);
        if ( $product && !$product->is_type('variable') ) {
            wp_die( -1 );
        }
        
        $meta_data  = map_deep( wp_unslash( $_REQUEST['input_fields']['swatchly_product_meta'] ), 'sanitize_text_field' );
        $updated    = update_post_meta( $product_id, '_swatchly_product_meta', $meta_data );

        wp_send_json_success(array(
            'message' => esc_html__('Saved!', 'woolentor')
        ));
    }

    /**
     * Reset product metabox data (ajax)
     */
    public function ajax_reset_product_meta(){
        // verify nonce
        $nonce = sanitize_text_field($_REQUEST['nonce']);
        if ( !wp_verify_nonce( $nonce, 'swatchly_product_metabox_save_nonce' ) ) {
            wp_send_json_error(array(
                'message' => esc_html__( 'No naughty business please!', 'woolentor' )
            ));
        }

        // check current user privilege
        if ( !current_user_can( 'edit_products' ) ) {
            wp_die( -1 );
        }

        $product_id = absint($_REQUEST['product_id']);
        $updated    = update_post_meta( $product_id, '_swatchly_product_meta', '' );

        wp_send_json_success(array(
            'message' => esc_html__('Reset Done!', 'woolentor')
        ));
    }
}

Ajax_Actions::instance();