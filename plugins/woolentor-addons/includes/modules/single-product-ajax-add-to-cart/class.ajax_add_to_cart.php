<?php
namespace WooLentor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
*  Single product Ajax add to cart
*/
class Single_Product_Ajax_Add_To_Cart{

    private static $instance = null;
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    function __construct(){
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    // Ajax Cart Script
    public function enqueue_scripts(){
        if( is_product() ){
            wp_enqueue_script( 'jquery-single-product-ajax-cart' );
        }
    }

}

Single_Product_Ajax_Add_To_Cart::instance();
