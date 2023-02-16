<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Pre_Order_Place extends Woolentor_Pre_Orders{

    private static $_instance = null;

    /**
     * Get Instance
     */
    public static function get_instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct(){
        add_filter( 'woocommerce_checkout_create_order', [ $this, 'checkout_create_order' ], 10, 2 );
    }

    /**
     * Add Pre order data
     *
     * @param WC_Order $order
     * @param [object] $checkout
     * @return void
     */
    public function checkout_create_order( WC_Order $order, $checkout ) {

		if ( $this->has_in_cart() ) {
			$order->update_meta_data( 'woolentor_pre_order', 'yes' );
		}
        
	}


}