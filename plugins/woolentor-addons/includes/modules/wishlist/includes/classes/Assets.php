<?php
namespace WishSuite;
/**
 * Assets handlers class
 */
class Assets {

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
     * Class constructor
     */
    private function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );
    }

    /**
     * All available scripts
     *
     * @return array
     */
    public function get_scripts() {
        return [
            'wishsuite-admin' => [
                'src'     => WISHSUITE_ASSETS . '/js/admin.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery' ]
            ],
            'wishsuite-frontend' => [
                'src'     => WISHSUITE_ASSETS . '/js/frontend.js',
                'version' => WOOLENTOR_VERSION,
                'deps'    => [ 'jquery', 'wc-add-to-cart-variation' ]
            ],
        ];
    }

    /**
     * All available styles
     *
     * @return array
     */
    public function get_styles() {
        return [
            'wishsuite-admin' => [
                'src'     => WISHSUITE_ASSETS . '/css/admin.css',
                'version' => WOOLENTOR_VERSION,
            ],
            'wishsuite-frontend' => [
                'src'     => WISHSUITE_ASSETS . '/css/frontend.css',
                'version' => WOOLENTOR_VERSION,
            ],
        ];
    }

    /**
     * Register scripts and styles
     *
     * @return void
     */
    public function register_assets() {
        $scripts = $this->get_scripts();
        $styles  = $this->get_styles();

        foreach ( $scripts as $handle => $script ) {
            $deps = isset( $script['deps'] ) ? $script['deps'] : false;
            wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
        }

        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;
            wp_register_style( $handle, $style['src'], $deps, $style['version'] );
        }

        // Inline CSS
        wp_add_inline_style( 'wishsuite-frontend', $this->inline_style() );
        
        // Frontend Localize data
        $option_data = array(
            'after_added_to_cart' => woolentor_get_option( 'after_added_to_cart', 'wishsuite_table_settings_tabs', 'on' ),
        );
        
        if( is_user_logged_in() &&  woolentor_get_option( 'enable_login_limit', 'wishsuite_general_tabs', 'off' ) === 'on' ){
            $option_data['btn_limit_login_off'] = 'off';
        }else if( !is_user_logged_in() && woolentor_get_option( 'enable_login_limit', 'wishsuite_general_tabs', 'off' ) === 'on' ){
            $option_data['btn_limit_login_off'] = 'on';
        }

        $localize_data = array(
            'ajaxurl'     => admin_url( 'admin-ajax.php' ),
            'option_data' => $option_data,
        );

        // Admin Localize data
        $setting_page = 0;
        if( isset( $_GET['page'] ) && $_GET['page'] == 'wishsuite' ){
            $setting_page = 1;
        }
        $admin_option_data = array(
            'btn_icon_type'        => woolentor_get_option( 'button_icon_type', 'wishsuite_style_settings_tabs', 'default' ),
            'added_btn_icon_type'  => woolentor_get_option( 'addedbutton_icon_type', 'wishsuite_style_settings_tabs', 'default' ),
            'shop_btn_position'    => woolentor_get_option( 'shop_btn_position', 'wishsuite_settings_tabs', 'after_cart_btn' ),
            'product_btn_position' => woolentor_get_option( 'product_btn_position', 'wishsuite_settings_tabs', 'after_cart_btn' ),
            'button_style'         => woolentor_get_option( 'button_style', 'wishsuite_style_settings_tabs', 'default' ),
            'table_style'          => woolentor_get_option( 'table_style', 'wishsuite_style_settings_tabs', 'default' ),
            'enable_social_share'  => woolentor_get_option( 'enable_social_share','wishsuite_table_settings_tabs','on' ),
            'enable_login_limit'   => woolentor_get_option( 'enable_login_limit','wishsuite_general_tabs','off' ),
        );
        $admin_localize_data = array(
            'ajaxurl'    => admin_url( 'admin-ajax.php' ),
            'is_settings'=> $setting_page,
            'option_data'=> $admin_option_data,
        );

        wp_localize_script( 'wishsuite-frontend', 'WishSuite', $localize_data );
        wp_localize_script( 'wishsuite-admin', 'WishSuite', $admin_localize_data );

        if( class_exists( '\Elementor\Plugin' ) && ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) ){
            wp_enqueue_style( 'wishsuite-frontend' );
            wp_enqueue_script( 'wishsuite-frontend' );
        }
        
    }

    /**
     * [inline_style]
     * @return [CSS String]
     */
    public function inline_style(){

        $button_custom_css = $table_custom_css = '';

        // Button Custom Style
        if( 'custom' === woolentor_get_option( 'button_style', 'wishsuite_style_settings_tabs', 'default' ) ){

            $btn_padding = wishsuite_dimensions( 'button_custom_padding','wishsuite_style_settings_tabs','padding' );
            $btn_margin  = wishsuite_dimensions( 'button_custom_margin','wishsuite_style_settings_tabs','margin' );
            $btn_border_radius = wishsuite_dimensions( 'button_custom_border_radius','wishsuite_style_settings_tabs','border-radius' );

            $btn_color    = wishsuite_generate_css('button_color','wishsuite_style_settings_tabs','color');
            $btn_bg_color = wishsuite_generate_css('background_color','wishsuite_style_settings_tabs','background-color');

            // Hover
            $btn_hover_color    = wishsuite_generate_css('button_hover_color','wishsuite_style_settings_tabs','color');
            $btn_hover_bg_color = wishsuite_generate_css('hover_background_color','wishsuite_style_settings_tabs','background-color');

            $button_custom_css = "
                .wishsuite-button{
                    {$btn_padding}
                    {$btn_margin}
                    {$btn_color}
                    {$btn_bg_color}
                    {$btn_border_radius}
                }
                .wishsuite-button:hover{
                    {$btn_hover_color}
                    {$btn_hover_bg_color}
                }
            ";
        }

        // Wishlist table style
        if( 'custom' === woolentor_get_option( 'table_style', 'wishsuite_style_settings_tabs', 'default' ) ){

            $heading_color    = wishsuite_generate_css('table_heading_color','wishsuite_style_settings_tabs','color');
            $heading_bg_color = wishsuite_generate_css('table_heading_bg_color','wishsuite_style_settings_tabs','background-color');
            $heading_border_color = wishsuite_generate_css('table_heading_border_color','wishsuite_style_settings_tabs','border-color');

            $border_color = wishsuite_generate_css('table_border_color','wishsuite_style_settings_tabs','border-color');

            // Add To cart Button
            $button_color = wishsuite_generate_css('table_cart_button_color','wishsuite_style_settings_tabs','color');
            $button_bg_color = wishsuite_generate_css('table_cart_button_bg_color','wishsuite_style_settings_tabs','background-color');
            $button_hover_color = wishsuite_generate_css('table_cart_button_hover_color','wishsuite_style_settings_tabs','color');
            $button_hover_bg_color = wishsuite_generate_css('table_cart_button_hover_bg_color','wishsuite_style_settings_tabs','background-color');

            $table_custom_css = "
                .wishsuite-table-content table thead > tr{
                    {$heading_border_color}
                }
                .wishsuite-table-content table thead > tr th{
                    {$heading_color}
                    {$heading_bg_color}
                }
                .wishsuite-table-content table,.wishsuite-table-content table tbody > tr{
                    {$border_color}
                }
            ";

            if( $button_color || $button_bg_color ){
                $table_custom_css .= "
                    .wishsuite-table-content table .wishsuite-addtocart{
                        {$button_color}
                        {$button_bg_color}
                    }
                ";
            }
            if( $button_hover_color || $button_hover_bg_color ){
                $table_custom_css .= "
                    .wishsuite-table-content table .wishsuite-addtocart:hover{
                        {$button_hover_color}
                        {$button_hover_bg_color}
                    }
                ";
            }

        }
        
        return $button_custom_css.$table_custom_css;

    }


}
