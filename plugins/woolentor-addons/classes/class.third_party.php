<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit();

/**
* Third party
*/
class WooLentorThirdParty{

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Base]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    function __construct(){
        $this->woocommerce_german_market();
    }

    /**
     * WooCommerce German Market
     *
     * @return void
     */
    public function woocommerce_german_market(){
        if( class_exists('Woocommerce_German_Market') ){
            add_action( 'woolentor_universal_after_price', array( 'WGM_Template', 'woocommerce_de_price_with_tax_hint_loop' ) );
            add_action( 'woolentor_addon_after_price', array( 'WGM_Template', 'woocommerce_de_price_with_tax_hint_loop' ) );
        }
    }

    
}

WooLentorThirdParty::instance();