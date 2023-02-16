<?php

namespace WooLentor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Assest Management
*/
class Assets_Management{
    
    /**
     * [$instance]
     * @var null
     */
    private static $instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Assets_Management]
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * [__construct] Class Constructor
     */
    function __construct(){
        $this->init();
    }

    /**
     * [init] Init
     * @return [void]
     */
    public function init() {

        // Register Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );

        // Frontend Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );

        add_filter( 'body_class', [ $this, 'body_classes' ] );

    }

    /**
     * [body_classes]
     * @param  [array] $classes
     * @return [array] 
     */    
    public function body_classes( $classes ){

        $current_theme = wp_get_theme();
        $classes[] = 'woolentor_current_theme_'.$current_theme->get( 'TextDomain' );

        return $classes;
    }

    /**
     * All available styles
     *
     * @return array
     */
    public function get_styles() {

        $style_list = [
            'htflexboxgrid' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/css/htflexboxgrid.css',
                'version' => WOOLENTOR_VERSION
            ],
            'simple-line-icons-wl' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/css/simple-line-icons.css',
                'version' => WOOLENTOR_VERSION
            ],
            'font-awesome-four' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/css/font-awesome.min.css',
                'version' => WOOLENTOR_VERSION
            ],
            'woolentor-select2' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/css/select2.min.css',
                'version' => WOOLENTOR_VERSION
            ],
            'woolentor-animate' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/css/animate.css',
                'version' => WOOLENTOR_VERSION
            ],
            'woolentor-widgets' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/css/woolentor-widgets.css',
                'version' => WOOLENTOR_VERSION
            ],
            'slick' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/css/slick.css',
                'version' => WOOLENTOR_VERSION
            ],
            'magnific-popup' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/lib/css/magnific-popup.css',
                'version' => WOOLENTOR_VERSION
            ],
            'woolentor-widgets-rtl' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/css/woolentor-widgets-rtl.css',
                'version' => WOOLENTOR_VERSION
            ],
            'woolentor-ajax-search' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/addons/ajax-search/css/ajax-search.css',
                'version' => WOOLENTOR_VERSION
            ],
            'woolentor-flash-sale-module' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/modules/flash-sale/assets/css/flash-sale.css',
                'version' => WOOLENTOR_VERSION,
            ],
            'woolentor-store-feature' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/css/store-feature.css',
                'version' => WOOLENTOR_VERSION
            ],
            'woolentor-faq' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/css/faq.css',
                'version' => WOOLENTOR_VERSION
            ],
            'woolentor-category-grid' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/css/category-grid.css',
                'version' => WOOLENTOR_VERSION
            ],
            'woolentor-slider' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/css/slider.css',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'magnific-popup' ]
            ],
            'woolentor-testimonial' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/css/testimonial.css',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'slick' ]
            ],
            'woolentor-product-grid' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/css/product-grid.css',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'slick','simple-line-icons-wl' ]
            ],

            'woolentor-admin' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/css/woolentor-admin.css',
                'version' => WOOLENTOR_VERSION
            ],
            'woolentor-selectric' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/lib/css/selectric.css',
                'version' => WOOLENTOR_VERSION
            ],
            'woolentor-sweetalert' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/lib/css/sweetalert2.min.css',
                'version' => WOOLENTOR_VERSION
            ],
            'woolentor-temlibray-style' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/css/tmp-style.css',
                'version' => WOOLENTOR_VERSION
            ],
            

        ];
        return $style_list;

    }

    /**
     * All available scripts
     *
     * @return array
     */
    public function get_scripts() {

        $script_list = [
            'slick' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/js/slick.min.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'countdown-min' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/js/jquery.countdown.min.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'woolentor-accordion-min' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/js/accordion.min.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'select2-min' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/js/select2.min.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'wow' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/lib/js/wow.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'jarallax' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/lib/js/jarallax.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'magnific-popup' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/lib/js/magnific-popup.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'one-page-nav' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/lib/js/one-page-nav.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jarallax','magnific-popup','wow','jquery' ]
            ],
            'woolentor-widgets-scripts' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/js/woolentor-widgets-active.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery','slick','wc-add-to-cart-variation' ]
            ],
            'woolentor-ajax-search' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/addons/ajax-search/js/ajax-search.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'woolentor-widgets-scripts' ]
            ],
            'jquery-single-product-ajax-cart' =>[
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'assets/js/single_product_ajax_add_to_cart.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'woolentor-flash-sale-module' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/modules/flash-sale/assets/js/flash-sale.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery', 'countdown-min' ]
            ],

            'woolentor-jquery-interdependencies' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/lib/js/jquery-interdependencies.min.js', 
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ],
            ],
            'woolentor-condition' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/js/woolentor-condition.js', 
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery'],
            ],
            'woolentor-admin-main' =>[
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/js/woolentor-admin.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery', 'wp-util', 'serializejson' ]
            ],
            'woolentor-sweetalert' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/lib/js/sweetalert2.min.js',
                'version' => WOOLENTOR_VERSION
            ],
            'woolentor-modernizr' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/lib/js/modernizr.custom.63321.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'jquery-selectric' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/lib/js/jquery.selectric.min.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'jquery-ScrollMagic' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/lib/js/ScrollMagic.min.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'babel-min' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/lib/js/babel.min.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'woolentor-templates' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/js/template_library_manager.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery', 'wp-util' ]
            ],
            'woolentor-install-manager' => [
                'src'     => WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/js/install_manager.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'wp-util', 'updates' ]
            ],
            
        ];

        return $script_list;

    }

    /**
     * Register scripts and styles
     *
     * @return void
     */
    public function register_assets() {
        $scripts = $this->get_scripts();
        $styles  = $this->get_styles();

        // Register Scripts
        foreach ( $scripts as $handle => $script ) {
            $deps = ( isset( $script['deps'] ) ? $script['deps'] : false );
            wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
        }

        // Register Styles
        foreach ( $styles as $handle => $style ) {
            $deps = ( isset( $style['deps'] ) ? $style['deps'] : false );
            wp_register_style( $handle, $style['src'], $deps, $style['version'] );
        }

        //Localize Scripts
        $localizeargs = array(
            'woolentorajaxurl' => admin_url( 'admin-ajax.php' ),
            'ajax_nonce'       => wp_create_nonce( 'woolentor_psa_nonce' ),
        );
        wp_localize_script( 'woolentor-widgets-scripts', 'woolentor_addons', $localizeargs );

        // For Admin
        if( is_admin() ){

            $datalocalize = array(
                'nonce' => wp_create_nonce( 'woolentor_save_opt_nonce' ),
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'message'=>[
                    'btntxt'  => esc_html__( 'Save Changes', 'woolentor' ),
                    'loading' => esc_html__( 'Saving...', 'woolentor' ),
                    'success' => esc_html__( 'Saved All Data', 'woolentor' ),
                    'yes'     => esc_html__( 'Yes', 'woolentor' ),
                    'cancel'  => esc_html__( 'Cancel', 'woolentor' ),
                    'sure'    => esc_html__( 'Are you sure?', 'woolentor' ),
                    'reseting'=> esc_html__( 'Resetting...', 'woolentor' ),
                    'reseted' => esc_html__( 'Reset All Settings', 'woolentor' ),
                ],
                'option_data' => [],

            );
            wp_localize_script( 'woolentor-admin-main', 'WOOLENTOR_ADMIN', $datalocalize );

            //Localize Scripts For template Library
            $current_user  = wp_get_current_user();
            $localize_data = [
                'ajaxurl'          => admin_url( 'admin-ajax.php' ),
                'adminURL'         => admin_url(),
                'elementorURL'     => admin_url( 'edit.php?post_type=elementor_library' ),
                'version'          => WOOLENTOR_VERSION,
                'pluginURL'        => plugin_dir_url( __FILE__ ),
                'alldata'          => !empty( base::$template_info['templates'] ) ? base::$template_info['templates'] : array(),
                'prolink'          => 'https://hasthemes.com/plugins/woolentor-pro-woocommerce-page-builder/?fd',
                'prolabel'         => esc_html__( 'Pro', 'woolentor' ),
                'loadingimg'       => WOOLENTOR_ADDONS_PL_URL . 'includes/admin/assets/images/loading.gif',
                'message'          =>[
                    'packagedesc'=> esc_html__( 'in this package', 'woolentor' ),
                    'allload'    => esc_html__( 'All Items have been Loaded', 'woolentor' ),
                    'notfound'   => esc_html__( 'Nothing Found', 'woolentor' ),
                ],
                'buttontxt'      =>[
                    'tmplibrary' => esc_html__( 'Import to Library', 'woolentor' ),
                    'tmppage'    => esc_html__( 'Import to Page', 'woolentor' ),
                    'import'     => esc_html__( 'Import', 'woolentor' ),
                    'buynow'     => esc_html__( 'Buy Now', 'woolentor' ),
                    'preview'    => esc_html__( 'Preview', 'woolentor' ),
                    'installing' => esc_html__( 'Installing..', 'woolentor' ),
                    'activating' => esc_html__( 'Activating..', 'woolentor' ),
                    'active'     => esc_html__( 'Active', 'woolentor' ),
                ],
                'user'           => [
                    'email' => $current_user->user_email,
                ],
            ];
            wp_localize_script( 'woolentor-templates', 'WLTM', $localize_data );
            wp_localize_script( 'woolentor-install-manager', 'WLIM', $localize_data );
        }
        
    }

    /**
     * [enqueue_frontend_scripts Load frontend scripts]
     * @return [void]
     */
    public function enqueue_frontend_scripts() {

        $current_theme = wp_get_theme( 'oceanwp' );
        // CSS File
        if ( $current_theme->exists() ){
            wp_enqueue_style( 'font-awesome-four' );
        }else{
            wp_enqueue_style( 'font-awesome' );
        }
        wp_enqueue_style( 'simple-line-icons-wl' );
        wp_enqueue_style( 'htflexboxgrid' );
        wp_enqueue_style( 'slick' );
        wp_enqueue_style( 'woolentor-widgets' );
        
        // If RTL
        if ( is_rtl() ) {
            wp_enqueue_style(  'woolentor-widgets-rtl' );
        }

        // .woocommerce div.product .woocommerce-tabs ul.tabs
        // wp_enqueue_script('wc-single-product');

    }



}

Assets_Management::instance();