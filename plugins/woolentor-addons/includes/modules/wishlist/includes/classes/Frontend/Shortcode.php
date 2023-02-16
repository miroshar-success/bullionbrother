<?php
namespace WishSuite\Frontend;
/**
 * Shortcode handler class
 */
class Shortcode {

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

    /**
     * Initializes the class
     */
    function __construct() {
        add_shortcode( 'wishsuite_button', [ $this, 'button_shortcode' ] );
        add_shortcode( 'wishsuite_table', [ $this, 'table_shortcode' ] );
        add_shortcode( 'wishsuite_counter', [ $this, 'counter_shortcode' ] );
    }

    /**
     * [button_shortcode] Button Shortcode callable function
     * @param  [type] $atts 
     * @param  string $content
     * @return [HTML] 
     */
    public function button_shortcode( $atts, $content = '' ){
        wp_enqueue_style( 'wishsuite-frontend' );
        wp_enqueue_script( 'wishsuite-frontend' );

        global $product;
        $product_id = '';
        if ( $product && is_a( $product, 'WC_Product' ) ) {
            $product_id = $product->get_id();
        }

        $has_product = false;
        if ( Manage_Wishlist::instance()->is_product_in_wishlist( $product_id ) ) {
            $has_product = true;
        }

        //my account url
        $myaccount_url =  get_permalink( get_option('woocommerce_myaccount_page_id') );

        // Fetch option data
        $button_text        = woolentor_get_option( 'button_text','wishsuite_settings_tabs', 'Wishlist' );
        $button_added_text  = woolentor_get_option( 'added_button_text','wishsuite_settings_tabs', 'Product Added' );
        $button_exist_text  = woolentor_get_option( 'exist_button_text','wishsuite_settings_tabs', 'Product already added' );
        $shop_page_btn_position     = woolentor_get_option( 'shop_btn_position', 'wishsuite_settings_tabs', 'after_cart_btn' );
        $product_page_btn_position  = woolentor_get_option( 'product_btn_position', 'wishsuite_settings_tabs', 'after_cart_btn' );
        $button_style               = woolentor_get_option( 'button_style', 'wishsuite_style_settings_tabs', 'default' );
        $enable_login_limit         = woolentor_get_option( 'enable_login_limit', 'wishsuite_general_tabs', 'off' );

        if ( !is_user_logged_in() && $enable_login_limit == 'on' ) {
            $button_text   = woolentor_get_option( 'logout_button','wishsuite_general_tabs', 'Please login' );
            $page_url      = $myaccount_url;
            $has_product   = false;
        }else{
            $button_text = woolentor_get_option( 'button_text','wishsuite_settings_tabs', 'Wishlist' );
            $page_url = wishsuite_get_page_url();
        }

        $button_class = array(
            'wishsuite-btn',
            'wishsuite-button',
            'wishsuite-shop-'.$shop_page_btn_position,
            'wishsuite-product-'.$product_page_btn_position,
        );

        if( $button_style === 'themestyle' ){
            $button_class[] = 'button';
        }

        if ( $has_product === true && ( $key = array_search( 'wishsuite-btn', $button_class ) ) !== false ) {
            unset( $button_class[$key] );
        }


        $button_icon        = $this->icon_generate();
        $added_button_icon  = $this->icon_generate('added');
        
        if( !empty( $button_text ) ){
            $button_text = '<span class="wishsuite-btn-text">'.$button_text.'</span>';
        }
        
        if( !empty( $button_exist_text ) ){
            $button_exist_text = '<span class="wishsuite-btn-text">'.$button_exist_text.'</span>';
        }

        if( !empty( $button_added_text ) ){
            $button_added_text = '<span class="wishsuite-btn-text">'.$button_added_text.'</span>';
        }

        // Shortcode atts
        $default_atts = array(
            'product_id'        => $product_id,
            'button_url'        => $page_url,
            'button_class'      => implode(' ', $button_class ),
            'button_text'       => $button_icon.$button_text,
            'button_added_text' => $added_button_icon.$button_added_text,
            'button_exist_text' => $added_button_icon.$button_exist_text,
            'has_product'       => $has_product,
            'template_name'     => ( $has_product === true ) ? 'exist' : 'add',
        );
        $atts = shortcode_atts( $default_atts, $atts, $content );
        return Manage_Wishlist::instance()->button_html( $atts );

    }

    /**
     * [table_shortcode] Table List Shortcode callable function
     * @param  [type] $atts
     * @param  string $content
     * @return [HTML] 
     */
    public function table_shortcode( $atts, $content = '' ){
        wp_enqueue_style( 'wishsuite-frontend' );
        wp_enqueue_script( 'wishsuite-frontend' );

        /* Fetch From option data */
        $empty_text = woolentor_get_option( 'empty_table_text', 'wishsuite_table_settings_tabs' );

        /* Product and Field */
        $products   = Manage_Wishlist::instance()->get_products_data();
        $fields     = Manage_Wishlist::instance()->get_all_fields();

        $custom_heading = !empty( woolentor_get_option( 'table_heading', 'wishsuite_table_settings_tabs' ) ) ? woolentor_get_option( 'table_heading', 'wishsuite_table_settings_tabs' ) : array();
        $enable_login_limit = woolentor_get_option( 'enable_login_limit', 'wishsuite_general_tabs', 'off' );

        $default_atts = array(
            'wishsuite'    => Manage_Wishlist::instance(),
            'products'     => $products,
            'fields'       => $fields,
            'heading_txt'  => $custom_heading,
            'empty_text'   => !empty( $empty_text ) ? $empty_text : '',
        );

        if ( !is_user_logged_in() && $enable_login_limit == 'on' ) {
            return do_shortcode('[woocommerce_my_account]');
        }else{
            $atts = shortcode_atts( $default_atts, $atts, $content );
            return Manage_Wishlist::instance()->table_html( $atts );
        }
    }

    /**
     * WishList Counter Shortcode
     *
     * @param [array] $atts
     * @param string $content
     * @return void
     */
    public function counter_shortcode( $atts, $content = '' ){
        wp_enqueue_style( 'wishsuite-frontend' );

        $enable_login_limit = woolentor_get_option( 'enable_login_limit', 'wishsuite_general_tabs', 'off' );
        $myaccount_url      =  get_permalink( get_option('woocommerce_myaccount_page_id') );

        $products   = Manage_Wishlist::instance()->get_products_data();
        if ( !is_user_logged_in() && $enable_login_limit == 'on' ) {
            $button_text   = woolentor_get_option( 'logout_button','wishsuite_general_tabs', 'Please login' );
            $page_url      = $myaccount_url;
            $has_product   = false;
        }else{
            $button_text = woolentor_get_option( 'button_text','wishsuite_settings_tabs', 'Wishlist' );
            $page_url = wishsuite_get_page_url();
        }

        $default_atts = array(
            'products'      => $products,
            'item_count'    => count($products),
            'page_url'      => $page_url,
            'text'          => '',
        );

        $atts = shortcode_atts( $default_atts, $atts, $content );
        return Manage_Wishlist::instance()->count_html( $atts );

    }

    /**
     * [icon_generate]
     * @param  string $type
     * @return [HTML]
     */
    public function icon_generate( $type = '' ){

        $default_icon   = wishsuite_icon_list('default');
        $default_loader = '<span class="wishsuite-loader">'.wishsuite_icon_list('loading').'</span>';
        
        $button_icon = '';
        $button_text = ( $type === 'added' ) ? woolentor_get_option( 'added_button_text','wishsuite_settings_tabs', 'Wishlist' ) : woolentor_get_option( 'button_text','wishsuite_settings_tabs', 'Wishlist' );
        $button_icon_type  = woolentor_get_option( $type.'button_icon_type', 'wishsuite_style_settings_tabs', 'default' );

        if( $button_icon_type === 'custom' ){
            $button_icon = woolentor_get_option( $type.'button_custom_icon','wishsuite_style_settings_tabs', '' );
        }else{
            if( $button_icon_type !== 'none' ){
                return $default_icon;
            }
        }

        if( !empty( $button_icon ) ){
            $button_icon = '<img src="'.esc_url( $button_icon ).'" alt="'.esc_attr( $button_text ).'">';
        }

        return $button_icon.$default_loader;

    }


}