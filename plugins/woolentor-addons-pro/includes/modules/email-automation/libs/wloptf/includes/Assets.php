<?php
/**
 * Assets.
 */

namespace WLOPTF;

/**
 * Class.
 */
class Assets {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ) );
    }

    /**
     * Get admin styles.
     */
    protected function get_admin_styles() {
        return array(
            'wloptf' => array(
                'src' => WLOPTF_ASSETS . '/css/wloptf.css',
                'deps' => array( 'jquery-ui-style', 'woocommerce_admin_styles' ),
            ),
        );
    }

    /**
     * Get admin scripts.
     */
    protected function get_admin_scripts() {
        return array(
            'select2' => array(
                'src' => WLOPTF_ASSETS . '/js/select2.full.min.js',
                'deps' => array( 'jquery' ),
            ),
            'wloptf' => array(
                'src' => WLOPTF_ASSETS . '/js/wloptf.js',
                'deps' => array( 'jquery', 'select2', 'jquery-ui-datepicker', 'jquery-ui-sortable' ),
            ),
        );
    }

    /**
     * Register admin assets.
     */
    public function register_admin_assets() {
        // Styles.
        $styles = $this->get_admin_styles();

        foreach ( $styles as $handle => $style ) {
            $style_deps = isset( $style['deps'] ) ? $style['deps'] : array();
            $style_version = isset( $style['version'] ) ? $style['version'] : WLOPTF_VERSION;

            wp_register_style( $handle, $style['src'], $style_deps, $style_version );
        }

        // Scripts.
        $scripts = $this->get_admin_scripts();

        foreach ( $scripts as $handle => $script ) {
            $script_deps = isset( $script['deps'] ) ? $script['deps'] : array();
            $script_version = isset( $script['version'] ) ? $script['version'] : WLOPTF_VERSION;
            $in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : true;

            wp_register_script( $handle, $script['src'], $script_deps, $script_version, $in_footer );
        }

        // Local object.
        wp_localize_script( 'wloptf', 'wloptf_local_obj', array(
            'ajax_url'   => admin_url( 'admin-ajax.php' ),
            'ajax_nonce' => wp_create_nonce( 'wloptf-ajax-nonce' ),
        ) );
    }

}