<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Ajax_Action{

	/**
	 * [$instance]
	 * @var null
	 */
	private static $instance = null;

	/**
	 * [instance]
	 * @return [Woolentor_Ajax_Action]
	 */
    public static function instance(){
        if( is_null( self::$instance ) ){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * [__construct]
     */
    function __construct(){

        // For Add to cart
		add_action( 'wp_ajax_woolentor_insert_to_cart', [ $this, 'insert_to_cart' ] );
		add_action( 'wp_ajax_nopriv_woolentor_insert_to_cart', [ $this, 'insert_to_cart' ] );

        // For Single Product ajax add to cart
        add_action( 'wc_ajax_woolentor_single_insert_to_cart', [ $this, 'single_product_insert_to_cart' ] );

        // For Quickview
        add_action( 'wp_ajax_woolentor_quickview', [ '\WooLentor\Quick_View_Manager', 'wc_quickview' ] );
        add_action( 'wp_ajax_nopriv_woolentor_quickview', [ '\WooLentor\Quick_View_Manager', 'wc_quickview' ] );

        // For ajax search
        add_action( 'wp_ajax_woolentor_ajax_search', [ $this, 'ajax_search_callback' ] );
        add_action( 'wp_ajax_nopriv_woolentor_ajax_search', [ $this, 'ajax_search_callback' ] );

    }

    /**
     * [insert_to_cart] Insert add to cart
     * @return [JSON]
     */
    public function insert_to_cart(){

        // phpcs:disable WordPress.Security.NonceVerification.Missing
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }

        $product_id         = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
        $quantity           = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
        $variation_id       = !empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
        $variations         = !empty( $_POST['variations'] ) ? array_map( 'sanitize_text_field', $_POST['variations'] ) : array();
        $passed_validation  = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );
        $product_status     = get_post_status( $product_id );

        if ( $passed_validation && \WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) && 'publish' === $product_status ) {
            do_action( 'woocommerce_ajax_added_to_cart', $product_id );
            if ( 'yes' === get_option('woocommerce_cart_redirect_after_add') ) {
                wc_add_to_cart_message( array( $product_id => $quantity ), true );
            }
            \WC_AJAX::get_refreshed_fragments();
        } else {
            $data = array(
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
            );
            echo wp_send_json( $data );
        }
        wp_send_json_success();
        
    }

    /**
     * [single_product_insert_to_cart] Single product ajax add to cart callable function
     * @return [JSON]
     */
    public function single_product_insert_to_cart(){
        
        // phpcs:disable WordPress.Security.NonceVerification.Missing
        if ( ! isset( $_POST['product_id'] ) ) {
            return;
        }

        $product_id         = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
        $product            = wc_get_product( $product_id );
        $quantity           = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
        $variation_id       = !empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
        $variations         = !empty( $_POST['variations'] ) ? array_map( 'sanitize_text_field', json_decode( stripslashes( $_POST['variations'] ), true ) ) : array();
        $passed_validation  = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );
        $product_status     = get_post_status( $product_id );

        $cart_item_data = $_POST;

        if ( $passed_validation && 'publish' === $product_status ) {

            if( count( $variations ) == 0 ){
                \WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations, $cart_item_data );
            }

            do_action( 'woocommerce_ajax_added_to_cart', $product_id );
            if ( 'yes' === get_option('woocommerce_cart_redirect_after_add') ) {
                wc_add_to_cart_message( array( $product_id => $quantity ), true );
            }
            \WC_AJAX::get_refreshed_fragments();
        } else {
            $data = array(
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
            );
            echo wp_send_json( $data );
        }
        wp_send_json_success();
        
    }

    /**
     * [ajax_search_callback] ajax search
     * @return [void]
     */
    public function ajax_search_callback(){
        WooLentor_Ajax_Search_Base::instance()->ajax_search_callback();
    }


}

Woolentor_Ajax_Action::instance();
