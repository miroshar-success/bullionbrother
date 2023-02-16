<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Pre_Order_AddTo_Cart extends Woolentor_Pre_Orders{

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

        // Single Product
        add_filter( 'woocommerce_product_single_add_to_cart_text', [ $this, 'add_to_cart_text' ], 20, 2 );

        // Product Loop
		add_action( 'woocommerce_product_add_to_cart_text', [ $this, 'add_to_cart_text' ], 10, 2 );

        // Add cart status
        add_filter('woocommerce_add_cart_item_data', [ $this, 'add_cart_item_data' ], 10, 3 );

    }

    /**
     * Button text
     *
     * @param [string] $button_text
     * @param [object] $product
     * @return void
     */
    public function add_to_cart_text( $button_text, $product ){
		if ( $this->get_pre_order_status( $product->get_id() ) ) {
			$button_text = $this->get_saved_data( $product->get_id(), 'add_to_cart_btn_text', 'add_to_cart_btn_text', 'Pre Order' );
		}
		return $button_text;
    }

    /**
     * Product add to cart with partial payment status
     *
     * @param [array] $cart_item_data
     * @param [int] $product_id
     * @param [int] $variation_id
     * @return void
     */
    public function add_cart_item_data( $cart_item_data, $product_id, $variation_id ){

        if( $this->get_pre_order_status( $product_id ) ){
            $cart_item_data['woolentor_pre_order'] = true;
        }

        return $cart_item_data;

    }


}