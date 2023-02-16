<?php
/**
 * Assets.
 */

namespace WLPF;

/**
 * Class.
 */
class Assets {

    /**
     * Constructor.
     */
    public function __construct( $contexts = 'both' ) {
        if ( ( 'both' === $contexts ) || ( 'admin' === $contexts ) ) {
            add_action( 'admin_enqueue_scripts', function () {
                $this->register_admin_assets();
                $this->enqueue_admin_assets();
            } );
        }

        if ( ( 'both' === $contexts ) || ( 'frontend' === $contexts ) ) {
            add_action( 'wp_enqueue_scripts', function () {
                $this->register_frontend_assets();
                $this->enqueue_frontend_assets();
            } );
        }
    }

    /**
     * Get admin styles.
     */
    protected function get_admin_styles() {
        $styles = array();

        if ( true === $this->debug_mode() ) {
            $styles['wlpf-admin'] = array(
                'src' => WLPF_ASSETS . '/css/wlpf-admin.css',
            );
        } else {
            $styles['wlpf-admin-bundle'] = array(
                'src' => WLPF_ASSETS . '/css/wlpf-admin-bundle.min.css',
            );
        }

        return $styles;
    }

    /**
     * Get admin scripts.
     */
    protected function get_admin_scripts() {
        $scripts = array();

        if ( true === $this->debug_mode() ) {
            $scripts['wlpf-base'] = array(
                'src' => WLPF_ASSETS . '/js/wlpf-base.js',
            );
            $scripts['wlpf-admin'] = array(
                'src' => WLPF_ASSETS . '/js/wlpf-admin.js',
                'deps' => array( 'jquery', 'wlpf-base' ),
            );
        } else {
            $scripts['wlpf-admin-bundle'] = array(
                'src' => WLPF_ASSETS . '/js/wlpf-admin-bundle.min.js',
                'deps' => array( 'jquery' ),
            );
        }

        return $scripts;
    }

    /**
     * Get admin localize_data.
     */
    protected function get_admin_localize_data() {
        return array(
            'item_title_structure'            => wlpf_get_item_title_structure(),
            'item_title_with_label_structure' => wlpf_get_item_title_with_label_structure(),
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
            $style_version = isset( $style['version'] ) ? $style['version'] : WOOLENTOR_VERSION_PRO;

            wp_register_style( $handle, $style['src'], $style_deps, $style_version );
        }

        // Scripts.
        $scripts = $this->get_admin_scripts();

        foreach ( $scripts as $handle => $script ) {
            $script_deps = isset( $script['deps'] ) ? $script['deps'] : array();
            $script_version = isset( $script['version'] ) ? $script['version'] : WOOLENTOR_VERSION_PRO;
            $in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : true;

            wp_register_script( $handle, $script['src'], $script_deps, $script_version, $in_footer );
        }

        // Localize script.
        if ( true === $this->debug_mode() ) {
            wp_localize_script( 'wlpf-base', 'wlpf_data', $this->get_admin_localize_data() );
        } else {
            wp_localize_script( 'wlpf-admin-bundle', 'wlpf_data', $this->get_admin_localize_data() );
        }
    }

    /**
     * Enqueue admin assets.
     */
    public function enqueue_admin_assets() {
        $screen = get_current_screen();
        $base = isset( $screen->base ) ? $screen->base : '';

        if ( 'shoplentor_page_woolentor' === $base ) {
            if ( true === $this->debug_mode() ) {
                wp_enqueue_style( 'wlpf-admin' );
                wp_enqueue_script( 'wlpf-admin' );
            } else {
                wp_enqueue_style( 'wlpf-admin-bundle' );
                wp_enqueue_script( 'wlpf-admin-bundle' );
            }
        }
    }

    /**
     * Get frontend styles.
     */
    protected function get_frontend_styles() {
        $styles = array();

        $styles['nice-select'] = array(
            'src'     => WLPF_ASSETS . '/css/nice-select.min.css',
            'version' => '1.0',
        );

        if ( true === $this->debug_mode() ) {
            $styles['wlpf-icon'] = array(
                'src' => WLPF_ASSETS . '/css/wlpf-icon.css',
            );
            $styles['wlpf-frontend'] = array(
                'src' => WLPF_ASSETS . '/css/wlpf-frontend.css',
                'deps' => array( 'nice-select', 'wlpf-icon' ),
            );
        } else {
            $styles['wlpf-frontend-bundle'] = array(
                'src' => WLPF_ASSETS . '/css/wlpf-frontend-bundle.min.css',
                'deps' => array( 'nice-select' ),
            );
        }

        return $styles;
    }

    /**
     * Get frontend scripts.
     */
    protected function get_frontend_scripts() {
        $scripts = array();

        $scripts['nice-select'] = array(
            'src'     => WLPF_ASSETS . '/js/jquery.nice-select.min.js',
            'deps'    => array( 'jquery' ),
            'version' => '1.0',
        );

        if ( true === $this->debug_mode() ) {
            $scripts['wlpf-base'] = array(
                'src' => WLPF_ASSETS . '/js/wlpf-base.js',
            );
            $scripts['wlpf-frontend'] = array(
                'src' => WLPF_ASSETS . '/js/wlpf-frontend.js',
                'deps' => array( 'jquery', 'jquery-ui-core', 'jquery-ui-slider', 'nice-select', 'wlpf-base' ),
            );
            $scripts['wlpf-frontend-fix'] = array(
                'src' => WLPF_ASSETS . '/js/wlpf-frontend-fix.js',
                'deps' => array( 'wlpf-frontend' ),
            );
            $scripts['wlpf-frontend-map'] = array(
                'src' => WLPF_ASSETS . '/js/wlpf-frontend-map.js',
                'deps' => array( 'wlpf-frontend-fix' ),
            );
            $scripts['wlpf-frontend-action'] = array(
                'src' => WLPF_ASSETS . '/js/wlpf-frontend-action.js',
                'deps' => array( 'wlpf-frontend-map' ),
            );
            $scripts['wlpf-frontend-data'] = array(
                'src' => WLPF_ASSETS . '/js/wlpf-frontend-data.js',
                'deps' => array( 'wlpf-frontend-action' ),
            );
            $scripts['wlpf-frontend-intac'] = array(
                'src' => WLPF_ASSETS . '/js/wlpf-frontend-intac.js',
                'deps' => array( 'wlpf-frontend-data' ),
            );
            $scripts['wlpf-frontend-clear'] = array(
                'src' => WLPF_ASSETS . '/js/wlpf-frontend-clear.js',
                'deps' => array( 'wlpf-frontend-intac' ),
            );
        } else {
            $scripts['wlpf-frontend-bundle'] = array(
                'src'  => WLPF_ASSETS . '/js/wlpf-frontend-bundle.min.js',
                'deps' => array( 'jquery', 'jquery-ui-core', 'jquery-ui-slider', 'nice-select' ),
            );
        }

        return $scripts;
    }

    /**
     * Get frontend localize_data.
     */
    protected function get_frontend_localize_data() {
        $localize_data = array(
            'ajax_url'                      => admin_url( 'admin-ajax.php' ),
            'ajax_nonce'                    => wp_create_nonce( 'wlpf-ajax-nonce' ),
            'ajax_filter'                   => wlpf_get_ajax_filter(),
            'add_ajax_query_args_to_url'    => wlpf_get_add_ajax_query_args_to_url(),
            'time_to_take_ajax_action'      => wlpf_get_time_to_take_ajax_action(),
            'time_to_take_none_ajax_action' => wlpf_get_time_to_take_none_ajax_action(),
            'products_wrapper_selector'     => wlpf_get_products_wrapper_selector(),
            'show_filter_arguments'         => wlpf_get_show_filter_arguments(),
            'query_args_prefix'             => wlpf_get_query_args_prefix(),
            'elementor_editor_mode'         => wlpf_get_elementor_editor_mode(),
            'filters_data'                  => wlpf_get_selected_filters_data(),
            'filter_page_number'            => 0,
            'filter_page_url'               => '',
        );

        return $localize_data;
    }

    /**
     * Register frontend assets.
     */
    public function register_frontend_assets() {
        // Styles.
        $styles = $this->get_frontend_styles();

        foreach ( $styles as $handle => $style ) {
            $style_deps = isset( $style['deps'] ) ? $style['deps'] : array();
            $style_version = isset( $style['version'] ) ? $style['version'] : WOOLENTOR_VERSION_PRO;

            wp_register_style( $handle, $style['src'], $style_deps, $style_version );
        }

        // Scripts.
        $scripts = $this->get_frontend_scripts();

        foreach ( $scripts as $handle => $script ) {
            $script_deps = isset( $script['deps'] ) ? $script['deps'] : array();
            $script_version = isset( $script['version'] ) ? $script['version'] : WOOLENTOR_VERSION_PRO;
            $in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : true;

            wp_register_script( $handle, $script['src'], $script_deps, $script_version, $in_footer );
        }

        // Localize script.
        if ( true === $this->debug_mode() ) {
            wp_localize_script( 'wlpf-base', 'wlpf_data', $this->get_frontend_localize_data() );
        } else {
            wp_localize_script( 'wlpf-frontend-bundle', 'wlpf_data', $this->get_frontend_localize_data() );
        }
    }

    /**
     * Enqueue frontend assets.
     */
    public function enqueue_frontend_assets() {
        if ( true === $this->debug_mode() ) {
            wp_enqueue_style( 'wlpf-frontend' );
            wp_enqueue_script( 'wlpf-frontend-clear' );
        } else {
            wp_enqueue_style( 'wlpf-frontend-bundle' );
            wp_enqueue_script( 'wlpf-frontend-bundle' );
        }
    }

    /**
     * Debug mode.
     */
    private function debug_mode() {
        return ( ( defined( 'SCRIPT_DEBUG' ) && ( true === rest_sanitize_boolean( SCRIPT_DEBUG ) ) ) ? true : false );
    }

}