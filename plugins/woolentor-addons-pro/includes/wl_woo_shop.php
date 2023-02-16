<?php
/*
*  Woolentor Pro Manage WooCommerce Builder Page.
*/
class Woolentor_Woo_Custom_Template_Layout_Pro{

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct(){

        add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'woolentor_init_cart' ) );
        add_action('init', array( $this, 'init' ) );

        // if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() ) {
        //     add_action( 'init', array( $this, 'register_wc_hooks' ), 5 );
        // }

    }

    public function init(){

        add_filter( 'wc_get_template', array( $this, 'woolentor_page_template' ), 50, 3 );
        
        // Cart
        add_action( 'woolentor_cart_content_build', array( $this, 'woolentor_cart_content' ) );
        add_action( 'woolentor_cartempty_content_build', array( $this, 'woolentor_emptycart_content' ) );
        
        // Checkout
        add_action( 'woolentor_checkout_content', array( $this, 'woolentor_checkout_content' ) );
        add_action( 'woolentor_checkout_top_content', array( $this, 'woolentor_checkout_top_content' ) );

        // Thank you Page
        add_action( 'woolentor_thankyou_content', array( $this, 'woolentor_thankyou_content' ) );

        // MyAccount
        add_action( 'woolentor_woocommerce_account_content', array( $this, 'woolentor_account_content' ) );
        add_action( 'woolentor_woocommerce_account_content_form_login', array( $this, 'woolentor_account_login_content' ) );

        // Quick View Content
        add_action( 'woolentor_quick_view_content', array( $this, 'woolentor_quick_view_content' ) );

        add_filter( 'template_include', array( $this, 'woolentor_woocommerce_page_template' ), 999);
    }

    /**
     *  Include WC fontend.
     */
    // public function register_wc_hooks() {
    //     WC()->frontend_includes();
    // }
    public function woolentor_init_cart() {
        $has_cart = is_a( WC()->cart, 'WC_Cart' );
        if ( ! $has_cart ) {
            $session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
            WC()->session = new $session_class();
            WC()->session->init();
            WC()->cart = new \WC_Cart();
            WC()->customer = new \WC_Customer( get_current_user_id(), true );
        }
    }

    /**
     * Get Template ID
     */
    public function get_template_id( $field_key = ''){
        $wltemplateid = method_exists( 'Woolentor_Manage_WC_Template', 'get_template_id' ) ? Woolentor_Manage_WC_Template::instance()->get_template_id( $field_key ) : '0';
        return $wltemplateid;
    }

    public function woolentor_page_template( $template, $slug, $args ){

        if( $slug === 'cart/cart-empty.php'){
            $wlemptycart_page_id = $this->get_template_id( 'productemptycartpage' );
            if( !empty( $wlemptycart_page_id ) ) {
                $template = WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/cart-empty-elementor.php';
            }
        }
        elseif( $slug === 'cart/cart.php' ){
            $wlcart_page_id = $this->get_template_id( 'productcartpage' );
            if( !empty( $wlcart_page_id ) ) {
                $template = WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/cart-elementor.php';
            }
        }elseif( $slug === 'checkout/form-checkout.php' ){
            $wlcheckout_page_id = $this->get_template_id( 'productcheckoutpage' );
            if( !empty( $wlcheckout_page_id ) ) {
                $template = WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/form-checkout.php';
            }
        }elseif( $slug === 'checkout/thankyou.php' ){
            $wlthankyou_page_id = $this->get_template_id( 'productthankyoupage' );
            if( !empty( $wlthankyou_page_id ) ) {
                $template = WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/thankyou.php';
            }
        }elseif( $slug === 'myaccount/my-account.php' || $slug === 'myaccount/form-lost-password.php' || $slug === 'myaccount/form-reset-password.php' ){
            $wlmyaccount_page_id = $this->my_account_page_manage();
            if( !empty( $wlmyaccount_page_id ) ) {
                $template = WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/my-account.php';
            }
        }elseif( $slug === 'myaccount/form-login.php' ){
            $wlmyaccount_login_page_id = $this->get_template_id( 'productmyaccountloginpage' );
            if( !empty( $wlmyaccount_login_page_id ) ) {
                $template = WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/form-login.php';
            }
        }

        return $template;
    }

    public function woolentor_emptycart_content(){
        $elementor_page_id = $this->get_template_id( 'productemptycartpage' );
        if( !empty( $elementor_page_id ) ){
            if( class_exists('\WooLentorBlocks\Manage_Styles') && function_exists('wc_notice_count') && wc_notice_count() > 0 ){
                \WooLentorBlocks\Manage_Styles::instance()->generate_inline_css( $elementor_page_id );
            }
            echo $this->build_page_content( $elementor_page_id );
        }
    }

    public function woolentor_cart_content(){
        $elementor_page_id = $this->get_template_id( 'productcartpage' );
        if( !empty( $elementor_page_id ) ){
            echo $this->build_page_content( $elementor_page_id );
        }
    }

    public function woolentor_checkout_content(){
        $elementor_page_id = $this->get_template_id( 'productcheckoutpage' );
        if( !empty( $elementor_page_id ) ){
            echo $this->build_page_content( $elementor_page_id );
        }else{ the_content(); }
    }

    public function woolentor_checkout_top_content(){
        $elementor_page_id = $this->get_template_id( 'productcheckouttoppage' );
        if( !empty( $elementor_page_id ) ){
            echo $this->build_page_content( $elementor_page_id );
        }
    }

    public function woolentor_thankyou_content(){
        $elementor_page_id = $this->get_template_id( 'productthankyoupage' );
        if( !empty( $elementor_page_id ) ){
            echo $this->build_page_content( $elementor_page_id );
        }else{ the_content(); }
    }

    public function woolentor_account_content(){
        $elementor_page_id = $this->my_account_page_manage();
        if ( !empty($elementor_page_id) ){
            echo $this->build_page_content( $elementor_page_id );
        }else{ the_content(); }
    }

    public function woolentor_account_login_content(){
        $elementor_page_id = $this->get_template_id( 'productmyaccountloginpage' );
        if ( !empty($elementor_page_id) ){
            echo $this->build_page_content( $elementor_page_id );
        }else{ the_content(); }
    }

    public function woolentor_quick_view_content(){
        $elementor_page_id = $this->get_template_id( 'productquickview' );
        if( !empty( $elementor_page_id ) ){
            echo $this->build_page_content( $elementor_page_id );
        }
    }

    public function woolentor_get_page_template_path( $page_template ) {
        $template_path = '';
        if( ( $page_template === 'elementor_header_footer' ) || ( $page_template === 'woolentor_fullwidth' ) ){
            $template_path = WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/page/header-footer.php';
        }elseif( ( $page_template === 'elementor_canvas' ) || ( $page_template === 'woolentor_canvas' ) ){
            $template_path = WOOLENTOR_ADDONS_PL_PATH_PRO . 'wl-woo-templates/page/canvas.php';
        }
        return $template_path;
    }

    public function woolentor_woocommerce_page_template( $template ){

        $elementor_page_slug = 0;
        $template_id = 0;

        if ( class_exists( 'WooCommerce' ) ) {
            if( is_cart() ){
                $empty_cart_page_id = $this->get_template_id( 'productemptycartpage' );
                if ( WC()->cart->is_empty() && !empty( $empty_cart_page_id ) ) {
                    $template_id = $empty_cart_page_id;
                    $elementor_page_slug = get_page_template_slug( $empty_cart_page_id );
                }else{
                    $cart_page_id = $this->get_template_id( 'productcartpage' );
                    if( !empty( $cart_page_id ) ){
                        $template_id = $cart_page_id;
                        $elementor_page_slug = get_page_template_slug( $cart_page_id );
                    }
                }
            }elseif ( is_checkout() ){
                $wl_checkout_page_id = !empty( is_wc_endpoint_url('order-received') ) ? $this->get_template_id( 'productthankyoupage' ) : $this->get_template_id( 'productcheckoutpage' );
                if( !empty($wl_checkout_page_id) ){
                    $template_id = $wl_checkout_page_id;
                    $elementor_page_slug = get_page_template_slug( $wl_checkout_page_id );
                }
            }elseif ( is_account_page() ){
                $wl_myaccount_page_id = $this->my_account_page_manage();
                if( !empty( $wl_myaccount_page_id ) ){
                    $template_id = $wl_myaccount_page_id;
                    $elementor_page_slug = get_page_template_slug( $wl_myaccount_page_id );
                }else{
                    if( !is_user_logged_in() ){
                        $elementor_page_id = $this->get_template_id( 'productmyaccountloginpage' );
                        $template_id = $elementor_page_id;
                        $elementor_page_slug = get_page_template_slug( $elementor_page_id );
                    }
                } 
            }

        }
        
        if( !empty($elementor_page_slug) ){
            $template_path = $this->woolentor_get_page_template_path( $elementor_page_slug );
            if ( $template_path ) {
                $template = $template_path;
            }
            add_filter('woolentor_builder_template_id',function( $build_template_id ) use( $template_id ){
                $build_template_id = $template_id;
                return $build_template_id;
            });
            add_filter('woolentor_builder_template_width',function( $template_width ) use( $template_id ){
                $template_width = $this->get_template_width( $template_id );
                return $template_width;
            });
        }
        
        return $template;
    }

    // Get Template width
    public function get_template_width( $template_id ){
        $get_width = get_post_meta( $template_id, '_woolentor_container_width', true );
		return $get_width ? $get_width : '';
    }

    // Build page content
    public function build_page_content( $id ){

        $output = '';
        $document = class_exists('\Elementor\Plugin') ? Elementor\Plugin::instance()->documents->get( $id ) : false;

        if( $document && $document->is_built_with_elementor() ){
            $output = Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id );
        }else{
            $content = get_the_content( null, false, $id );

            if ( has_blocks( $content ) ) {
                $blocks = parse_blocks( $content );
                foreach ( $blocks as $block ) {
                    $output .= do_shortcode( render_block( $block ) );
                }
            }else{
                $content = apply_filters( 'the_content', $content );
                $content = str_replace(']]>', ']]&gt;', $content );
                return $content;
            }

        }
        return $output;

    }

    // Manage My Accont Custom template
    public function my_account_page_manage(){
        global $wp;

        $request = explode( '/', $wp->request );

        $account_page_slugs = [
            'orders',
            'downloads',
            'edit-address',
            'edit-account',
            'lost-password',
            'reset-password'
        ];

        $page_slug = '';
        if( ( end( $request ) === basename( get_permalink(wc_get_page_id( 'myaccount' )) )) && is_user_logged_in() ){
            $page_slug = 'dashboard';
        }else if( in_array( end( $request ), $account_page_slugs ) ){
            if( ! empty( $_GET['show-reset-form'] ) ){
                if ( isset( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ) && 0 < strpos( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ], ':' ) ) {
                    list( $rp_id, $rp_key ) = array_map( 'wc_clean', explode( ':', wp_unslash( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ), 2 ) ); // @codingStandardsIgnoreLine
                    $userdata               = get_userdata( absint( $rp_id ) );
                    $rp_login               = $userdata ? $userdata->user_login : '';
                    $user                   = check_password_reset_key( $rp_key, $rp_login );
                    if ( ! is_wp_error( $user ) ) {
                        $page_slug = 'reset-password';
                    }else{
                        $page_slug = end( $request );
                    }
                }
            }else{
                $page_slug = end( $request );
            }
        }else{
            if( is_user_logged_in() ){
                $page_slug = 'productmyaccountpage';
            }
        }

        $template_id = $this->get_template_id( $page_slug );

        if( $page_slug == 'reset-password' ){
            return $template_id;
        }
        if( empty( $template_id ) && is_user_logged_in() ){
            $template_id = $this->get_template_id( 'productmyaccountpage' );
        }

        return $template_id;

    }

}

Woolentor_Woo_Custom_Template_Layout_Pro::instance();