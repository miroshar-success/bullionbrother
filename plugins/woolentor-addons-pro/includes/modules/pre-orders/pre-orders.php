<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Pre_Orders{

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

        // Admin scripts
        add_action('admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
        // Enqueue scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // Include Nessary file
        $this->include();

        if( is_admin() ){
            Woolentor_Admin_Pre_Orders::get_instance();
        }

        // Add To cart button manager
        Woolentor_Pre_Order_AddTo_Cart::get_instance();

        // Price manager
        Woolentor_Pre_Order_Price::get_instance();

        // Pre-Order Content manager
        Woolentor_Pre_Order_Content::get_instance();

        // Order manager
        Woolentor_Pre_Order_Place::get_instance();

        // Update schedule manager
        add_action('_woolentor_pre_order_schedule_date_cron',[ $this, 'schedule_date_cron' ] );

    }

    public function admin_enqueue_scripts(){
        //Script
        wp_enqueue_script( 'woolenor-admin-pre-order', plugin_dir_url( __FILE__ ) . 'assets/js/admin-pre-order.js', array('jquery'), WOOLENTOR_VERSION, 'all' );
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts(){
        // Styles
        wp_enqueue_style( 'woolenor-pre-order', plugin_dir_url( __FILE__ ) . 'assets/css/pre-order.css', array(), WOOLENTOR_VERSION );
        
        //Script
        wp_enqueue_script( 'woolenor-pre-order', plugin_dir_url( __FILE__ ) . 'assets/js/pre-order.js', array('countdown-min'), WOOLENTOR_VERSION, 'all' );
    }

    /**
     * Inclode Nessery file
     *
     * @return void
     */
    public function include(){
        // Add to cart button
        require_once( __DIR__. '/includes/class.pre-order-add-to-cart.php' );

        // Manage Price
        require_once( __DIR__. '/includes/class.pre-order-price.php' );

        // Manage Pre order content
        require_once( __DIR__. '/includes/class.pre-order-content.php' );

        // Manage Order
        require_once( __DIR__. '/includes/class.pre-order-place.php' );

        // Manage Pre Order Admin
        require_once( __DIR__. '/admin/class.admin-pre-order.php' );

    }

    /**
     * Check pre order status
     *
     * @param [int] $product_id
     * @return void
     */
    public function get_pre_order_status( $product_id ){

        $pre_order_date = $this->get_saved_data( $product_id, 'woolentor_pre_order_available_date', 'woolentor_pre_order_available_date' );
        $pre_order_time = $this->get_saved_data( $product_id, 'woolentor_pre_order_available_time', 'woolentor_pre_order_available_time' );
        $gmt_offdet     = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
        $time_str       = strtotime( $pre_order_date );
        $time_total     = $gmt_offdet + $time_str;
        $current_date   = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );

        $remainingTime  = ( strtotime( $pre_order_time ) - strtotime( 'TODAY' ) + strtotime( $pre_order_date ) ) - current_time( 'timestamp' );

        if( ( get_post_meta( $product_id, 'woolentor_pre_order_enable', true ) === 'yes' ) && ( $remainingTime > 0 ) ){
            return true;
        }else{
            return false;
        }

    }

     /**
     * Update schedule manager
     *
     * @param [int] $product_id
     * @return void
     */
    public function schedule_date_cron( $product_id ){
        wc_delete_product_transients( $product_id );
    }

    /**
     * Manage product transients
     *
     * @param [int] $product_id
     * @return void
     */
    public function delete_product_transients( $product_id ){
        $status = get_post_meta( $product_id, 'woolentor_pre_order_enable', true );
        if( $status === 'yes' ){
            $transient_key = $status . md5( serialize( array( 'id' => $product_id, 'wl_pre_order' => 'yes' ) ) );
            if( !get_transient( $transient_key ) ) {
                wc_delete_product_transients( $product_id );
                set_transient( $transient_key, $product_id, DAY_IN_SECONDS );
            }
        }
    }

     /**
     * Get amount type
     *
     * @param [Int] $post_id
     * @param [string] $meta_key
     * @param [string] $option_key
     * @param string $default
     * @return void
     */
    public function get_saved_data( $post_id, $meta_key, $option_key, $default = '' ) {
		$get_save_data = get_post_meta( $post_id, $meta_key, true );

		if ( ! $get_save_data ) {
			$get_save_data = woolentor_get_option_pro( $option_key, 'woolentor_pre_order_settings', $default );
		}
		return $get_save_data;
	}

    /**
     * Replace placeholder text
     *
     * @param [html] $replace
     * @param [string] $string
     * @return void
     */
    public function replace_placeholder( $pattern, $replace, $string ){
        return str_replace( $pattern, $replace, $string );
    }

    /**
     * Get Product data
     *
     * @param [int] $product_id
     * @return void
     */
    public function get_product_data( $product_id ) {
		$product = wc_get_product( $product_id );
        if ( !$product ) return '';

		if ( $product->is_type( 'variable' ) && isset( $_POST['variation_id'] ) ) {
			if ( $_POST['variation_id'] ) {
				$product = new WC_Product_Variation( $_POST['variation_id'] );
			}
		}

		return $product;

	}

    /**
     * Get Product data
     *
     * @param [int] $variation_id
     * @return void
     */
	public function get_variation_product( $variation_id ) {
        $product = new WC_Product_Variation( $variation_id );
        if ( !$product ) return '';
        return $product;
    }

    /**
     * Check has pre order product in cart
     *
     * @return boolean
     */
    public function has_in_cart(){
        $has_in_cart = false;
        if ( WC()->cart ) {
            foreach ( WC()->cart->get_cart() as $key => $cart_item ) {
                if ( isset( $cart_item['woolentor_pre_order'] ) && $cart_item['woolentor_pre_order'] === true  ) {
                    $has_in_cart = true;
                    break;
                }
            }
        }
        return $has_in_cart;
    }

    /**
     * Calculate Price
     *
     * @param [int] $product_id
     * @param [int] $product_price
     * @return void
     */
    public function get_calculate_price( $product_id, $product_price ) {

		$manage_price = $this->get_saved_data( $product_id, 'woolentor_pre_order_manage_price', 'woolentor_pre_order_manage_price', 'product_price' );
		$amount_type  = $this->get_saved_data( $product_id, 'woolentor_pre_order_amount_type', 'woolentor_pre_order_amount_type', 'percentage' );
		$amount       = (float)$this->get_saved_data( $product_id, 'woolentor_pre_order_amount', 'woolentor_pre_order_amount', '50' );

        if( $manage_price === 'product_price' ){
            return $product_price;
        }else if( $manage_price === 'fixed_price' ){
            return $amount;
        }else{
            $amount_value = 0;
            if ( $amount_type === 'fixed_amount' ) {
                $amount_value = $amount;
            }

            if ( $amount_type === 'percentage' ) {
                $amount_value = $product_price * ( $amount / 100 );
            }

            if( $manage_price === 'increase_price' ){
                $amount_value += $product_price;
            }else{
                if( $product_price > $amount_value ){
                    $amount_value = $product_price - $amount_value;
                }else{
                    $amount_value = 0;
                }
            }

            return $amount_value;
        }

	}



}
Woolentor_Pre_Orders::get_instance();