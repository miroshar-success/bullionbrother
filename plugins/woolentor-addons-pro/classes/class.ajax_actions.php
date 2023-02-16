<?php
namespace WooLentorPro;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Ajax_Action{

	/**
	 * [$instance]
	 * @var null
	 */
	private static $instance = null;

	/**
	 * [instance]
	 * @return [Ajax_Action]
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
        // Execute the ajax function under init hook to work it properly,
        // prior to that hook cart quantity update via ajax won't work properly
        add_action( 'init', [ $this, 'load_ajax' ] );
    }

    public function load_ajax(){
        add_action( 'wp_ajax_nopriv_update_cart_item_quantity', [ $this, 'update_cart_item_quantity' ] );
        add_action( 'wp_ajax_update_cart_item_quantity',        [ $this, 'update_cart_item_quantity' ] );
    }

    /**
     * [update_cart_item_quantity] Update cart item quantity
     * @return [JSON]
     */
    public function update_cart_item_quantity(){
        $qty            = absint($_POST['qty']);
        $cart_item_key  = sanitize_key($_POST['cart_item_key']);

        WC()->cart->set_quantity( $cart_item_key, $qty, true );
        WC()->cart->calculate_totals();
        woocommerce_cart_totals();

        wp_die();
    }
       
}

\WooLentorPro\Ajax_Action::instance();
