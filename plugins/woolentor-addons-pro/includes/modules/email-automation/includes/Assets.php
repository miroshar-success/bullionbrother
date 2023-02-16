<?php
/**
 * Assets.
 */

namespace WLEA;

/**
 * Class.
 */
class Assets {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', function () {
            $this->register_admin_assets();
            $this->enqueue_admin_assets();
        } );
    }

    /**
     * Get admin styles.
     */
    protected function get_admin_styles() {
        return array(
            'wlea-admin' => array(
                'src' => WLEA_ASSETS . '/css/admin.css',
            ),
            'wlea-admin-post' => array(
                'src' => WLEA_ASSETS . '/css/admin-post.css',
            ),
        );
    }

    /**
     * Get admin scripts.
     */
    protected function get_admin_scripts() {
        return array(
            'wlea-admin' => array(
                'src' => WLEA_ASSETS . '/js/admin.js',
                'deps' => array( 'jquery' ),
            ),
            'wlea-admin-post' => array(
                'src' => WLEA_ASSETS . '/js/admin-post.js',
                'deps' => array( 'jquery' ),
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
    }

    /**
     * Enqueue admin assets.
     */
    public function enqueue_admin_assets() {
        $screen = get_current_screen();
        $base = isset( $screen->base ) ? $screen->base : '';
        $post_type = isset( $screen->post_type ) ? $screen->post_type : '';

        // Admin local object.
        $admin_local_obj = array(
            'ajax_url'   => admin_url( 'admin-ajax.php' ),
            'ajax_nonce' => wp_create_nonce( 'wlea-ajax-nonce' ),
        );

        // Admin post local object.
        $admin_post_local_obj = array(
            'editor_button_text'    => esc_html__( 'Placeholders', 'woolentor-pro' ),
            'editor_button_tooltip' => esc_html__( 'WooLentor Available Placeholders', 'woolentor-pro' ),
        );

        if ( 'post' === $base ) {
            if ( 'wlea-email' === $post_type ) {
                $admin_post_local_obj['back_button'] = sprintf( '<a href="%1$s" class="page-title-action"><span class="wlea-page-title-action"><span class="wlea-icon wlea-icon-undo"></span><span>%2$s</span></span></a>', admin_url( 'edit.php?post_type=wlea-email' ), esc_html__( 'Back', 'woolentor-pro' ) );
            } elseif ( 'wlea-workflow' === $post_type ) {
                $admin_post_local_obj['back_button'] = sprintf( '<a href="%1$s" class="page-title-action"><span class="wlea-page-title-action"><span class="wlea-icon wlea-icon-undo"></span><span>%2$s</span></span></a>', admin_url( 'edit.php?post_type=wlea-workflow' ), esc_html__( 'Back', 'woolentor-pro' ) );
            }
        }

        // Admin local script.
        wp_localize_script( 'wlea-admin', 'wlea_local_obj', $admin_local_obj );

        // Admin post local script.
        wp_localize_script( 'wlea-admin-post', 'wlea_local_obj', $admin_post_local_obj );

        // Admin & admin post scripts.
        if ( ( 'wlea-email' === $post_type ) || ( 'wlea-workflow' === $post_type ) ) {
            if ( 'edit' === $base ) {
                wp_enqueue_style( 'wlea-admin' );
                wp_enqueue_script( 'wlea-admin' );
            } elseif ( 'post' === $base ) {
                add_thickbox();

                wp_enqueue_style( 'wlea-admin-post' );
                wp_enqueue_script( 'wlea-admin-post' );
            }
        } elseif ( ( 'woolentor_page_wlea-tasks' === $base ) || ( 'woolentor_page_wlea-logs' === $base ) ) {
            wp_enqueue_style( 'wlea-admin' );
            wp_enqueue_script( 'wlea-admin' );
        }
    }

}