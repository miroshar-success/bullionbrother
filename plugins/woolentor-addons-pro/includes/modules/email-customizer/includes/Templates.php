<?php
/**
 * Templates.
 */

namespace Woolentor_Email_Customizer;

/**
 * Templates class.
 */
class Templates {

	/**
     * Templates constructor.
     */
    public function __construct() {
        add_filter( 'woolentor_default_page_template', array( $this, 'default_page_template' ), 999, 2 );

        add_filter( 'woocommerce_locate_template', array( $this, 'locate_template' ), 999, 3 );
        add_action( 'woolentor_email_content', array( $this, 'email_content' ), 999, 2 );

        add_filter( 'woocommerce_email_styles', array( $this, 'email_styles' ), 999, 2 );

        add_filter( 'woolentor_footer_content_visibility', array( $this, 'footer_content_visibility' ), 999 );
    }

    /**
     * Default page template.
     */
    public function default_page_template( $template = '', $type = '' ) {
        $emails = woolentor_wc_get_emails( 'id' );
        $emails = array_map( function ( $email ) { return 'email_' . $email; }, $emails );

        if ( in_array( $type, $emails, true ) ) {
            $template = 'elementor_canvas';
        }

        return $template;
    }

    /**
     * Locate template.
     */
    public function locate_template( $template, $template_name, $template_path ) {
        $emails = woolentor_wc_get_emails( 'template_html' );

        if ( ! in_array( $template_name, $emails, true ) ) {
            return $template;
        }

        $email_id = array_search( $template_name, $emails );

        if ( empty( $email_id ) ) {
            return $template;
        }

        $template_key = 'email_' . $email_id;

        $template_id = method_exists( 'Woolentor_Template_Manager', 'get_template_id' ) ? Woolentor_Template_Manager::instance()->get_template_id( $template_key, 'woolentor_get_option_pro' ) : '0';
        $template_id = absint( $template_id );

        if ( empty( $template_id ) ) {
            return $template;
        }

        $_REQUEST['woolentor_email_type'] = $email_id;

        $template_file = WOOLENTOR_EMAIL_CUSTOMIZER_PATH . '/templates/base.php';

        if ( file_exists( $template_file ) ) {
            $template = $template_file;
        }

        return $template;
    }

    /**
     * Email styles.
     */
    public function email_styles( $styles = '', $email = null ) {
        if ( ! is_object( $email ) || empty( $email ) ) {
            return $styles;
        }

        $email_id = ( isset( $email->id ) ? sanitize_text_field( $email->id ) : '' );

        if ( empty( $email_id ) ) {
            return $styles;
        }

        $template_key = 'email_' . $email_id;

        $template_id = method_exists( 'Woolentor_Template_Manager', 'get_template_id' ) ? Woolentor_Template_Manager::instance()->get_template_id( $template_key, 'woolentor_get_option_pro' ) : '0';
        $template_id = absint( $template_id );

        if ( empty( $template_id ) ) {
            return $styles;
        }

        $styles = $this->get_styles( $template_id );

        return $styles;
    }

    /**
     * Get styles.
     */
    public function get_styles( $template_id = 0 ) {
        $wp_upload_dir = wp_upload_dir();

        if ( $wp_upload_dir['error'] ) {
            return '';
        }

        $generated_css_path = $wp_upload_dir['basedir'] . '/elementor/css';

        $frontend_css_path = realpath( WOOLENTOR_EMAIL_CUSTOMIZER_ASSETS_PATH . '/css/frontend.css' );
        $template_css_path = realpath( $generated_css_path . '/post-' . $template_id . '.css' );
        $settings_file_path = realpath( WOOLENTOR_EMAIL_CUSTOMIZER_TEMPLATES_PATH . '/settings.php' );

        ob_start();
        if ( false !== $frontend_css_path && file_exists( $frontend_css_path ) ) { include $frontend_css_path; }
        if ( false !== $template_css_path && file_exists( $template_css_path ) ) { include $template_css_path; }
        if ( false !== $settings_file_path && file_exists( $settings_file_path ) ) { include $settings_file_path; }
        $styles = ob_get_clean();

        return $styles;
    }

    /**
     * Email content.
     */
    public function email_content( $email = null, $order = null ) {
        if ( ! is_object( $email ) || empty( $email ) ) {
            return;
        }

        $email_id = ( isset( $email->id ) ? sanitize_text_field( $email->id ) : '' );

        if ( empty( $email_id ) ) {
            return;
        }

        $template_key = 'email_' . $email_id;

        $template_id = method_exists( 'Woolentor_Template_Manager', 'get_template_id' ) ? Woolentor_Template_Manager::instance()->get_template_id( $template_key, 'woolentor_get_option_pro' ) : '0';
        $template_id = absint( $template_id );

        if ( empty( $template_id ) ) {
            return;
        }

        $content = $this->get_content( $template_id );

        echo $content;
    }

    /**
     * Get content.
     */
    public function get_content( $template_id = 0 ) {
        return ( class_exists( '\Elementor\Plugin' ) ? ( \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id ) ) : '' );
    }

    /**
     * Footer content visibility.
     */
    public function footer_content_visibility( $visibility ) {
        return ( ! woolentor_is_email_customizer_template() ? $visibility : false );
    }

}