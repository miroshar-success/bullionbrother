<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Partial_Payment{

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
    function __construct(){

        define( 'WOOLENTOR_PARTIAL_PAYMENT_TEMPLATE_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) . 'templates/' );

        // Admin scripts
        add_action('admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
        // Enqueue scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // Include Nessary file
        $this->include();

        add_action( 'init', [ $this, 'register_partial_payment_post_type' ], 99 );

        add_filter( 'woocommerce_order_item_get_formatted_meta_data', [ $this, 'item_get_formatted_meta_data' ], 10, 4 );
        add_filter('woocommerce_order_formatted_line_subtotal', [ $this, 'order_formatted_line_subtotal' ], 10, 3 );

        if( is_admin() ){
            Woolentor_Partial_Payment_Admin::get_instance();
        }

        // Add To cart button manager
        Woolentor_Partial_Payment_AddTo_Cart::get_instance();
        // Cart Page
        Woolentor_Partial_Payment_Cart::get_instance();
        // Checkout page
        Woolentor_Partial_Payment_Checkout::get_instance();
        // Manage Order
        Woolentor_Partial_Payment_Orders::get_instance();
        // Manage Email
        Woolentor_Partial_Payment_Email::get_instance();
        
    }

    public function admin_enqueue_scripts(){

        //Script
        wp_enqueue_script( 'woolenor-admin-partial-payment', plugin_dir_url( __FILE__ ) . 'assets/js/admin-partial-payment.js', array('jquery'), WOOLENTOR_VERSION, 'all' );

    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts(){
        // Styles
        wp_enqueue_style( 'woolenor-partial-payment', plugin_dir_url( __FILE__ ) . 'assets/css/partial-payment.css', array(), WOOLENTOR_VERSION );
        //Script
        wp_enqueue_script( 'woolenor-partial-payment', plugin_dir_url( __FILE__ ) . 'assets/js/partial-payment.js', array('jquery'), WOOLENTOR_VERSION, 'all' );

        $localize_data = [
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'woolentor_partial_payment_nonce' ),
        ];
        wp_localize_script( 'woolenor-partial-payment', 'WLPP', $localize_data );
    }

    /**
     * Register Order Type
     *
     * @return void
     */
    public function register_partial_payment_post_type(){

        wc_register_order_type(
            'woolentor_pp_payment',
            [
                'labels' => [
                    'name'          => esc_html__('Partial Payments', 'woolentor-pro'),
                    'singular_name' => esc_html__('Partial Payment', 'woolentor-pro'),
                    'edit_item'     => esc_html_x('Edit Partial Payment', 'custom post type settings', 'woolentor-pro'),
                    'search_items'  => esc_html__('Search Partial Payments', 'woolentor-pro'),
                    'parent'        => esc_html_x('Order', 'custom post type settings', 'woolentor-pro'),
                    'menu_name'     => esc_html__('Partial Payments', 'woolentor-pro'),
                ],
                'public'            => false,
                'show_ui'           => true,
                'capability_type'   => 'shop_order',
                'capabilities'      => [
                    'create_posts'  => 'do_not_allow',
                ],
                'map_meta_cap'      => true,
                'publicly_queryable'=> false,
                'exclude_from_search'=> true,
                'show_in_menu'      => 'woocommerce',
                'hierarchical'      => false,
                'show_in_nav_menus' => false,
                'rewrite'           => false,
                'query_var'         => false,
                'supports'          => [ 'title', 'comments', 'custom-fields' ],
                'has_archive'       => false,

                'exclude_from_orders_screen' => true,
                'add_order_meta_boxes'       => true,
                'exclude_from_order_count'   => true,
                'exclude_from_order_views'   => true,
                'exclude_from_order_webhooks'=> true,
                'exclude_from_order_reports' => true,
                'exclude_from_order_sales_reports' => true,
                'class_name'                 => 'Woolentor_Create_Partial_Payment',
            ]

        );

    }

    /**
     * Inclode Nessery file
     *
     * @return void
     */
    public function include(){
        // Add to cart button
        require_once( __DIR__. '/includes/class.partial-payment-add-to-cart.php' );
        // Cart Page
        require_once( __DIR__. '/includes/class.partial-payment-cart.php' );
        // Checkout Page
        require_once( __DIR__. '/includes/class.partial-payment-checkout.php' );
        //Create Order
        require_once( __DIR__. '/includes/class.create-partial-payment.php' );
        // Manange Order
        require_once( __DIR__. '/includes/class.partial-payment-orders.php' );
        // Manage order post column and table in admin area
        require_once( __DIR__. '/admin/class.partial-payment-admin.php' );
        // Manage Order Email
        require_once( __DIR__. '/includes/class.partial-payment-email.php' );
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
     * Partial Amount Calculate
     *
     * @param [int] $product_id
     * @param [int] $product_price
     * @return void
     */
    public function get_partial_amount_calculate( $product_id, $product_price ) {

		$amount_type  = $this->get_saved_data( $product_id, 'woolentor_partial_payment_amount_type', 'amount_type', 'percentage' );
		$amount       = (float)$this->get_saved_data( $product_id, 'woolentor_partial_payment_amount', 'amount', '50' );

		$partial_amount = 0;

		if ( $amount_type === 'fixedamount' ) {
			$partial_amount = $amount;
		}

		if ( $amount_type === 'percentage' ) {
			$partial_amount = $product_price * ( $amount / 100 );
		}

		return $partial_amount;
	}

    /**
     * Check partial payment status
     *
     * @param [int] $product_id
     * @return void
     */
    public function get_partial_payment_status( $product_id ){
        return get_post_meta( $product_id, 'woolentor_partial_payment_enable', true ) === 'yes';
    }

    /**
     * Check has partial payment in cart
     *
     * @return boolean
     */
    public function has_partial_payment_in_cart(){
        $has_partial_payment_in_cart = false;
        if ( WC()->cart ) {
            foreach ( WC()->cart->get_cart() as $key => $cart_item ) {
                if ( isset( $cart_item['woolentor_partial_payment'] ) && $cart_item['woolentor_partial_payment']['enable'] === true  ) {
                    $has_partial_payment_in_cart = true;
                    break;
                }
            }
        }
        return $has_partial_payment_in_cart;
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
			$get_save_data = woolentor_get_option_pro( $option_key, 'woolentor_partial_payment_settings', $default );
		}
		return $get_save_data;
	}

    /**
     * Get Option data
     *
     * @param [string] $option_key
     * @param string $default
     * @return void
     */
    public function get_option_data( $option_key, $default = '' ){
        $get_data = null;
        if ( $option_key ) {
			$get_data = woolentor_get_option_pro( $option_key, 'woolentor_partial_payment_settings', $default );
		}
        return $get_data;
    }

    /**
     * Manage Order Item in thank you page
     *
     * @param [array] $formatted_meta
     * @param [type] $item
     * @return void
     */
    public function item_get_formatted_meta_data( $formatted_meta, $item ) {

		foreach ( $formatted_meta as $key => $meta ) {
 
			if ( $meta->key == 'woolentor_partial_payment_amount_type_meta' ) {

                $meta_value         = strip_tags( trim( $meta->display_value ) );
                $meta->display_key  = esc_html__('Partial amount type','woolentor-pro');

                if( $meta_value == 'percentage' ){
                    $meta->display_value = '%';
                }else{
                    $meta->display_value = esc_html__('Fixed','woolentor-pro');
                }

			}

			if ( $meta->key == 'woolentor_partial_payment_amount_meta' ) {

                $meta_value = strip_tags( trim( $meta->display_value ) );
                $meta->display_key   = esc_html__('Partial amount','woolentor-pro');
                $meta->display_value = $meta_value;

			}

		}

		return $formatted_meta;
	}

    /**
     * Manage Inline subtotal
     *
     * @param [string] $subtotal
     * @param [array] $item
     * @param [object] $order
     * @return string
     */
    public function order_formatted_line_subtotal( $subtotal, $item, $order ){

        if ( did_action('woocommerce_email_order_details') ) return $subtotal;

        if( $item['woolentor_partial_payment_amount_meta'] ){

            $product = $item->get_product();
            if ( !$product ) return $subtotal;
            if ( $product->get_type() === 'bundle' || isset( $item['_bundled_by'] ) ) return $subtotal;

            // The product ID
            $product_id   = $item->get_product_id(); 
            $variation_id = $item->get_variation_id();
            if( $variation_id ){
                $product_id = $variation_id;
            }

            $partial_amount = $this->get_partial_amount_calculate( $product_id, $product->get_price() ) * $item->get_quantity();

            return $subtotal . '<br/>('.esc_html__('Partial payment: ','woolentor-pro'). ' ' .wc_price( $partial_amount ). ')';

        }else {
            return $subtotal;
        }

    }


}

Woolentor_Partial_Payment::get_instance();    