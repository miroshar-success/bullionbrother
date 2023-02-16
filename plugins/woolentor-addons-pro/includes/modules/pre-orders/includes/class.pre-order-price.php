<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Pre_Order_Price extends Woolentor_Pre_Orders{

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

        // Product Price HTML
        add_filter( 'woocommerce_get_price_html', [ $this, 'pre_order_display_price' ], 10, 2 );

        //Change Price simple
        add_filter( 'woocommerce_product_get_price', [ $this, 'pre_order_price_simple' ], 10, 2 );
        add_filter( 'woocommerce_product_get_sale_price', [ $this, 'pre_order_price_simple' ], 10, 2 );

        //Change Price variable
        add_action( 'woocommerce_product_variation_get_price', [ $this, 'pre_order_price_variable' ], 10, 2 );
        add_filter( 'woocommerce_product_variation_get_sale_price', [ $this, 'pre_order_price_variable' ], 10, 2 );
        add_filter( 'woocommerce_variation_prices_price', [ $this, 'pre_order_price_variable' ], 10, 2 );
        add_filter( 'woocommerce_variation_prices_sale_price', [ $this, 'pre_order_price_variable' ], 10, 2 );

		add_filter( 'woocommerce_available_variation', [ $this,'available_variation' ], 10, 3);

    }

    /**
     * Display Calculate price
     *
     * @param [HTML] $price
     * @param [object] $product
     * @return void
     */
    public function pre_order_display_price( $price, $product ){

        if ( ! $product || class_exists( 'WC_Bundles' ) )  return $price;

        $product_id = $product->get_id();
        $parent_id  = $product->get_parent_id() == 0 ? $product_id : $product->get_parent_id();
        $enable_status = $this->get_pre_order_status( $parent_id );
		if (  ! $enable_status ) return $price;

        $original_price_html = $price;

        if( ! $product->is_type('variable') ){
            $price = wc_price( $this->get_calculate_price( $product_id, $product->get_price() ) );
        }else{
            $price_min = wc_get_price_to_display( $product, [ 'price' => $product->get_variation_regular_price( 'min' ) ] );
			$price_max = wc_get_price_to_display( $product, [ 'price' => $product->get_variation_regular_price( 'max' ) ] );
            $original_price_html = wc_format_price_range( $price_min, $price_max );
        }

        $manage_price_lavel = $this->get_saved_data( $parent_id, 'manage_price_lavel', 'manage_price_lavel', '' );
        
        $manage_price_lavel = $this->replace_placeholder( '{original_price}', '<span class="woolentor-pre-order-original-price">'.$original_price_html.'</span><br/>', $manage_price_lavel );
        $manage_price_lavel = $this->replace_placeholder( '{preorder_price}', '<span class="woolentor-pre-order-price">'.$price.'</span>', $manage_price_lavel );

        if( is_admin() || empty( $manage_price_lavel ) ){
            return $price;
        }else{
            return '<span class="woolentor-pre-order-price-area">'.$manage_price_lavel.'</span>';
        }

    }

    /**
     * Manage simple product price
     *
     * @param [html] $price
     * @param [object] $product
     * @return void
     */
    public function pre_order_price_variable( $price, $product ){

        $product_id = $product->get_id();
        $parent_id  = $product->get_parent_id();
        $enable_status = $this->get_pre_order_status( $parent_id );
		if ( ! $enable_status ){
            return $price;
        }

        $regular_price = get_post_meta( $product_id, '_regular_price', true );
        $sale_price    = get_post_meta( $product_id, '_sale_price', true );

        if ( $regular_price ) {
            if ( $product->is_on_sale( $product_id ) ) {
                $price = $this->get_calculate_price( $parent_id, $sale_price );
            }else{
                $price = $this->get_calculate_price( $parent_id, $regular_price );
            }
        }

        return $price;

    }

    /**
     * Manage Variable product price
     *
     * @param [html] $price
     * @param [object] $product
     * @return void
     */
    public function pre_order_price_simple( $price, $product ){

        $product_id = $product->get_id();
        $enable_status = $this->get_pre_order_status( $product_id );
		if ( ! $enable_status ) {
            return $price;
        }

        $regular_price = get_post_meta( $product_id, '_regular_price', true );
        $sale_price    = get_post_meta( $product_id, '_sale_price', true );

        if ( $regular_price && ( is_cart() || is_checkout() ) ) {
            if ( $product->is_on_sale( $product_id ) ) {
                $price = $this->get_calculate_price( $product_id, $sale_price );
            }else{
                $price = $this->get_calculate_price( $product_id, $regular_price );
            }
        }

        return $price;

    }

    /**
     * Single Variation product html
     *
     * @param [array] $data
     * @param [object] $product
     * @param [object] $variation
     * @return html
     */
    public function available_variation( $data, $product, $variation ) {
        $display_price          = wc_price( $data['display_price'] );
        $display_regular_price  = '<del>'.wc_price( $data['display_regular_price'] ).'</del>';

        if( !empty( $this->get_saved_data( $product->get_id(), 'manage_price_lavel', 'manage_price_lavel', '' ) ) ){
            if ( $product->is_on_sale( $data['variation_id'] ) ) {
                $data['price_html'] = '<span class="price">'.$display_regular_price.' '.$display_price.'</span>';
            }else{
                $data['price_html'] = '<span class="price">'.$display_price.'</span>';
            }
        }

        return $data;
    }


}