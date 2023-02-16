<?php
/**
 * Assets.
 */

namespace Woolentor_Email_Customizer;

/**
 * Assets class.
 */
class Assets {

	/**
     * Assets constructor.
     */
    public function __construct() {
        add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_enqueue_styles' ), 999 );
        add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'editor_enqueue_scripts' ), 999 );

        add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'frontend_enqueue_scripts' ), 999 );

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 999 );
    }

    /**
     * Enqueue scripts.
     */
    public function enqueue_scripts() {
        if ( ! woolentor_is_email_customizer_template() ) {
            return;
        }

        $wp_upload_dir = wp_upload_dir();

        if ( $wp_upload_dir['error'] ) {
            return '';
        }

        $post_id = get_the_ID();

        $wp_styles = wp_styles();
        $wp_scripts = wp_scripts();

        $themes = get_theme_root_uri();
        $module = WOOLENTOR_EMAIL_CUSTOMIZER_URL;
        $elementor = WP_PLUGIN_URL . '/elementor/';
        $elementor_pro = WP_PLUGIN_URL . '/elementor-pro/';
        $elementor_generated = $wp_upload_dir['baseurl'] . '/elementor/css/';

        foreach ( $wp_scripts->registered as $wp_script ) {
            if ( strpos( $wp_script->src, $themes ) !== false ) {
                wp_dequeue_script( $wp_script->handle );
            }

            if ( strpos( $wp_script->src, WP_PLUGIN_URL ) !== false ) {
                if ( strpos( $wp_script->src, $module ) !== false ) {
                    continue;
                } elseif ( strpos( $wp_script->src, $elementor ) !== false ) {
                    continue;
                } elseif ( strpos( $wp_script->src, $elementor_pro ) !== false ) {
                    continue;
                }

                wp_dequeue_script( $wp_script->handle );
            }
        }

        foreach ( $wp_styles->registered as $wp_style ) {
            if ( strpos( $wp_style->src, $themes ) !== false ) {
                wp_dequeue_style( $wp_style->handle );
            }

            if ( strpos( $wp_style->src, $elementor_generated ) !== false ) {
                if ( 'elementor-post-' . $post_id === $wp_style->handle ) {
                    $wp_style->deps = array();
                    $wp_style->ver = time();
                    continue;
                }

                wp_dequeue_style( $wp_style->handle );
            }

            if ( strpos( $wp_style->src, WP_PLUGIN_URL ) !== false ) {
                if ( strpos( $wp_style->src, $module ) !== false ) {
                    continue;
                } elseif ( strpos( $wp_style->src, $elementor ) !== false ) {
                    if ( 'elementor-frontend-legacy' !== $wp_style->handle ) {
                        if ( 'elementor-frontend' === $wp_style->handle ) {
                            $wp_style->src = WP_PLUGIN_URL . '/elementor/assets/css/frontend-lite.min.css';
                        }

                        continue;
                    }
                } elseif ( strpos( $wp_style->src, $elementor_pro ) !== false ) {
                    continue;
                }

                wp_dequeue_style( $wp_style->handle );
            }
        }
    }

    /**
     * Editor enqueue styles.
     */
    public function editor_enqueue_styles() {
        if ( woolentor_is_email_customizer_template() ) {
            wp_enqueue_style( 'woolentor-email-customizer-editor', WOOLENTOR_EMAIL_CUSTOMIZER_ASSETS . '/css/editor.css', array(), WOOLENTOR_VERSION_PRO );
        }
    }

    /**
     * Editor enqueue scripts.
     */
    public function editor_enqueue_scripts() {
        if ( woolentor_is_email_customizer_template() ) {
            wp_enqueue_script( 'woolentor-email-customizer-editor', WOOLENTOR_EMAIL_CUSTOMIZER_ASSETS . '/js/editor.js', array( 'jquery' ), WOOLENTOR_VERSION_PRO );

            wp_localize_script( 'woolentor-email-customizer-editor', 'woolentor_email_customizer_editor', array(
                'section_style_title' => esc_html__( 'Section', 'woolentor-pro' ),
                'column_style_title' => esc_html__( 'Column', 'woolentor-pro' ),
            ) );
        }
    }

    /**
     * Editor enqueue scripts.
     */
    public function frontend_enqueue_scripts() {
        if ( woolentor_is_email_customizer_template() ) {
            $width = woolentor_get_option_pro( 'width','woolentor_email_customizer_settings', 600 );
            $width = absint( $width );
            $width = ! empty( $width ) ? $width : 600;

            $inline_style = '.elementor .elementor-inner, .elementor .elementor-section-wrap, .elementor-section, #elementor-add-new-section, #woolentor-email-wrapper { width: ' . $width . 'px; max-width: ' . $width . 'px; }';

            wp_enqueue_style( 'woolentor-email-customizer-frontend', WOOLENTOR_EMAIL_CUSTOMIZER_ASSETS . '/css/frontend.css', array(), WOOLENTOR_VERSION_PRO );
            wp_add_inline_style( 'woolentor-email-customizer-frontend', $inline_style );
        }
    }

}