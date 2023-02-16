<?php

namespace WooLentorPro;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Plugin Base Class
 */
final class Base {

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
     * [__construct] Class construcotr
     */
    private function __construct() {
        if ( ! function_exists('is_plugin_active')){ include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); }

        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'delete_transient_key' ] );
        
        // Register Plugin Active Hook
        register_activation_hook( WOOLENTOR_ADDONS_PL_ROOT_PRO, [ $this, 'plugin_activate_hook'] );

        // Plugin Deactive Hook
        register_deactivation_hook( WOOLENTOR_ADDONS_PL_ROOT_PRO, [ $this, 'plugin_deactive_hook'] );

    }

    /**
     * [i18n] Load Text Domain
     * @return [void]
     */
    public function i18n() {
        load_plugin_textdomain( 'woolentor-pro', false, dirname( plugin_basename( WOOLENTOR_ADDONS_PL_ROOT_PRO ) ) . '/languages/' );
    }

    /**
     * [init] Plugins Loaded Init Hook
     * @return [void]
     */
    public function init() {

        // Check WooLentor Free version
        if( !is_plugin_active('woolentor-addons/woolentor_addons_elementor.php') ){
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        // Include File
        $this->include_files();

        // After Active Plugin then redirect to setting page
        // $this->plugin_redirect_option_page();

    }

    /**
     * [admin_notice_missing_main_plugin] Admin Notice If WooLentor Free Version Deactive | Not install
     * @return [void]
     */
    public function admin_notice_missing_main_plugin() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
        $woolentor = 'woolentor-addons/woolentor_addons_elementor.php';
        if( $this->is_plugins_active( $woolentor ) ) {
            if( ! current_user_can( 'activate_plugins' ) ) {
                return;
            }
            $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $woolentor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $woolentor );
            $message = sprintf( __( '%1$sWooLentor Addons Pro%2$s requires WooLentor plugin to be active. Please activate WooLentor to continue.', 'woolentor-pro' ), '<strong>', '</strong>' );
            $button_text = esc_html__( 'Activate WooLentor', 'woolentor-pro' );
        } else {
            if( ! current_user_can( 'activate_plugins' ) ) {
                return;
            }
            $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=woolentor-addons' ), 'install-plugin_woolentor-addons' );
            $message = sprintf( __( ' %1$sWooLentor Addons Pro %2$s requires %1$s"WooLetor Addons"%2$s plugin to be installed and activated. Please install WooLentor to continue.', 'woolentor-pro' ), '<strong>', '</strong>' );
            $button_text = esc_html__( 'Install WooLentor', 'woolentor-pro' );
        }
        $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
        printf( '<div class="error"><p>%1$s</p>%2$s</div>', $message, $button );

    }

    /**
     * [is_plugins_active] Check Plugin installation status
     * @param  [string]  $pl_file_path plugin location
     * @return boolean  True | False
     */
    public function is_plugins_active( $pl_file_path = NULL ){
        $installed_plugins_list = get_plugins();
        return isset( $installed_plugins_list[$pl_file_path] );
    }

    /**
     * [plugin_activate_hook] Plugin Activation Hook
     * @return [void]
     */
    public function plugin_activate_hook() {
        // add_option( 'woolentor_do_activation_redirect', TRUE );
        add_option( 'woolentor_do_activation_library_cache', TRUE );
    }

    /**
     * [plugin_redirect_option_page] After Install plugin then redirect setting page
     * @return [void]
     */
    public function plugin_redirect_option_page() {
        if ( get_option( 'woolentor_do_activation_redirect', FALSE ) ) {
            delete_option('woolentor_do_activation_redirect');
            if( !isset( $_GET['activate-multi'] ) ){
                wp_redirect( admin_url("admin.php?page=woolentor-pro") );
            }
        }
    }

    /**
     * [plugin_deactive_hook] Plugin Deactivation Hook
     * @return [void]
     */
    public function plugin_deactive_hook(){
        delete_transient( 'woolentor_template_info' );
        delete_metadata( 'user', null, 'woolentor_dismissed_lic_notice', null, true );
    }

    /**
     * [delete_transient_key]
     * @param  [string] $hook
     * @return [void]
     */
    public function delete_transient_key( $hook ){
        if( $hook === 'shoplentor_page_woolentor_templates' ){
            if ( get_option( 'woolentor_do_activation_library_cache', FALSE ) ) {
                delete_transient( 'woolentor_template_info' );
                delete_option('woolentor_do_activation_library_cache');
            }
        }
    }

    /**
     * [include_files] Required Necessary file
     * @return [void]
     */
    public function include_files(){

        require( WOOLENTOR_ADDONS_PL_PATH_PRO.'includes/helper-function.php' );
        require( WOOLENTOR_ADDONS_PL_PATH_PRO.'classes/class.ajax_actions.php' );
        require( WOOLENTOR_ADDONS_PL_PATH_PRO.'classes/class.assest_management.php' );
        require( WOOLENTOR_ADDONS_PL_PATH_PRO.'classes/class.widgets_control.php' );
        require( WOOLENTOR_ADDONS_PL_PATH_PRO.'classes/class.my_account.php' );
        require( WOOLENTOR_ADDONS_PL_PATH_PRO.'classes/class.wc-shortcode-products.php' );
        // Block Manager
        if ( class_exists( 'WooLentorBlocks' ) ){
            require( WOOLENTOR_ADDONS_PL_PATH_PRO.'blocks/blocks.php' );
        }
        // For Checkout page
        require( WOOLENTOR_ADDONS_PL_PATH_PRO.'classes/class.checkout_page.php' );
        require( WOOLENTOR_ADDONS_PL_PATH_PRO.'classes/class.checkout_field_manager.php' );

        // Admin Setting file
        if( is_admin() ){
            require( WOOLENTOR_ADDONS_PL_PATH_PRO.'includes/licence/WooLentorPro.php' );
            require( WOOLENTOR_ADDONS_PL_PATH_PRO.'includes/custom-metabox.php' );
        }

        // Builder File
        if( woolentor_get_option_pro( 'enablecustomlayout', 'woolentor_woo_template_tabs', 'on' ) == 'on' ){
            include( WOOLENTOR_ADDONS_PL_PATH_PRO.'includes/wl_woo_shop.php' );
            // For Cart page
            require( WOOLENTOR_ADDONS_PL_PATH_PRO.'classes/class.cart_page.php' );
            require( WOOLENTOR_ADDONS_PL_PATH_PRO.'classes/class.third_party.php' );
        }
        
        // WooLentor Extension
        require( WOOLENTOR_ADDONS_PL_PATH_PRO.'classes/class.extension.php' );

    }


}

/**
 * Initializes the main plugin
 *
 * @return \Base
 */
function woolentor_pro() {
    return Base::instance();
}