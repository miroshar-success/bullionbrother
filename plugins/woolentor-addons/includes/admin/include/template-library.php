<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class Woolentor_Template_Library{

    // Get Instance
    private static $_instance = null;
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct(){
        if ( is_admin() ) {
            add_action( 'admin_menu', [ $this, 'admin_menu' ], 225 );
            add_action( 'wp_ajax_woolentor_ajax_request', [ $this, 'templates_ajax_request' ] );

            add_action( 'wp_ajax_woolentor_ajax_get_required_plugin', [ $this, 'ajax_plugin_data' ] );
            add_action( 'wp_ajax_woolentor_ajax_plugin_activation', [ $this, 'ajax_plugin_activation' ] );
            add_action( 'wp_ajax_woolentor_ajax_theme_activation', [ $this, 'ajax_theme_activation' ] );
        }
        
        add_action( 'admin_enqueue_scripts', [ $this, 'scripts' ], 999 );

    }

    // Plugins Library Register
    public function admin_menu() {
        add_submenu_page(
            'woolentor_page', 
            esc_html__( 'Template Library', 'woolentor' ),
            esc_html__( 'Template Library', 'woolentor' ), 
            'manage_options', 
            'woolentor_templates', 
            [ $this, 'library_render_html' ] 
        );
    }

    public function library_render_html(){
        require_once WOOLENTOR_ADDONS_PL_PATH . 'includes/admin/include/template-library/templates_list.php';
        require_once WOOLENTOR_ADDONS_PL_PATH . 'includes/admin/include/template-library/popup-template.php';
    }

    /**
     * Admin Scripts.
     */
    public function scripts( $hook ) {

        if( 'shoplentor_page_woolentor_templates' == $hook ){

            // CSS
            wp_enqueue_style( 'woolentor-selectric' );
            wp_enqueue_style( 'woolentor-temlibray-style' );

            // JS
            wp_enqueue_script( 'woolentor-modernizr' );
            wp_enqueue_script( 'jquery-selectric' );
            wp_enqueue_script( 'jquery-ScrollMagic' );
            wp_enqueue_script( 'babel-min' );
            wp_enqueue_script( 'woolentor-templates' );
            wp_enqueue_script( 'woolentor-install-manager' );

        }

    }

    /**
     * Ajax request.
     */
    public function templates_ajax_request(){

        if ( ! current_user_can( 'manage_options') ) {
            echo json_encode(
                array(
                    'message' => esc_html__( 'You are not permitted to import the template.', 'woolentor' )
                )
            );
        }else{
            if ( isset( $_REQUEST ) ) {

                $template_id        = sanitize_text_field( $_REQUEST['httemplateid'] );
                $template_parentid  = sanitize_text_field( $_REQUEST['htparentid'] );
                $template_title     = sanitize_text_field( $_REQUEST['httitle'] );
                $page_title         = sanitize_text_field( $_REQUEST['pagetitle'] );

                $templateurl    = sprintf( \Woolentor_Template_Library_Manager::get_api_templateapi(), $template_id );
                $response_data  = \Woolentor_Template_Library_Manager::get_content_remote_request( $templateurl );
                $defaulttitle   = ucfirst( $template_parentid ) .' -> '.$template_title;


                $args = [
                    'post_type'    => !empty( $page_title ) ? 'page' : 'elementor_library',
                    'post_status'  => !empty( $page_title ) ? 'draft' : 'publish',
                    'post_title'   => !empty( $page_title ) ? $page_title : $defaulttitle,
                    'post_content' => '',
                ];

                $new_post_id = wp_insert_post( $args );

                $json_value  = wp_slash( wp_json_encode( $response_data['content']['content'] ) );
                update_post_meta( $new_post_id, '_elementor_data', $json_value );
                update_post_meta( $new_post_id, '_elementor_template_type', $response_data['type'] );
                update_post_meta( $new_post_id, '_elementor_edit_mode', 'builder' );
                
                if( isset( $response_data['page_settings'] ) ){
                    update_post_meta( $new_post_id, '_elementor_page_settings', $response_data['page_settings'] );
                }

                if ( $new_post_id && ! is_wp_error( $new_post_id ) ) {
                    update_post_meta( $new_post_id, '_wp_page_template', !empty( $response_data['page_template'] ) ? $response_data['page_template'] : 'elementor_header_footer' );
                }

                echo json_encode(
                    array( 
                        'id'      => $new_post_id,
                        'edittxt' => !empty( $page_title ) ? esc_html__( 'Edit Page', 'woolentor' ) : esc_html__( 'Edit Template', 'woolentor' )
                    )
                );
            }
        }

        wp_die();
    }

    /*
    * Ajax response required data
    */
    public function ajax_plugin_data(){
        if ( isset( $_POST ) ) {
            $freeplugins = explode( ',', sanitize_text_field( $_POST['freeplugins'] ) );
            $proplugins = explode( ',', sanitize_text_field( $_POST['proplugins'] ) );
            $themeinfo = explode( ',', sanitize_text_field( $_POST['requiredtheme'] ) );
            if(!empty($_POST['freeplugins'])){$this->required_plugins( $freeplugins, 'free' );}
            if(!empty($_POST['proplugins'])){ $this->required_plugins( $proplugins, 'pro' );}
            if(!empty($_POST['requiredtheme'])){ $this->required_theme( $themeinfo, 'free' );}
        }
        wp_die();
    }

    /*
    * Required Plugins
    */
    public function required_plugins( $plugins, $type ) {
        foreach ( $plugins as $key => $plugin ) {

            $plugindata = explode( '//', $plugin );
            $data = array(
                'slug'      => isset( $plugindata[0] ) ? $plugindata[0] : '',
                'location'  => isset( $plugindata[1] ) ? $plugindata[0].'/'.$plugindata[1] : '',
                'name'      => isset( $plugindata[2] ) ? $plugindata[2] : '',
                'pllink'    => isset( $plugindata[3] ) ? 'https://'.$plugindata[3] : '#',
            );

            if ( ! is_wp_error( $data ) ) {

                // Installed but Inactive.
                if ( file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) && is_plugin_inactive( $data['location'] ) ) {

                    $button_classes = 'button activate-now button-primary';
                    $button_text    = esc_html__( 'Activate', 'woolentor' );

                // Not Installed.
                } elseif ( ! file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) ) {

                    $button_classes = 'button install-now';
                    $button_text    = esc_html__( 'Install Now', 'woolentor' );

                // Active.
                } else {
                    $button_classes = 'button disabled';
                    $button_text    = esc_html__( 'Activated', 'woolentor' );
                }

                ?>
                    <li class="htwptemplata-plugin-<?php echo $data['slug']; ?>">
                        <h3><?php echo $data['name']; ?></h3>
                        <?php
                            if ( $type == 'pro' && ! file_exists( WP_PLUGIN_DIR . '/' . $data['location'] ) ) {
                                echo '<a class="button" href="'.esc_url( $data['pllink'] ).'" target="_blank">'.esc_html__( 'Buy Now', 'woolentor' ).'</a>';
                            }else{
                        ?>
                            <button class="<?php echo $button_classes; ?>" data-pluginopt='<?php echo wp_json_encode( $data ); ?>'><?php echo $button_text; ?></button>
                        <?php } ?>
                    </li>
                <?php

            }

        }
    }

    /*
    * Required Theme
    */
    public function required_theme( $themes, $type ){
        foreach ( $themes as $key => $theme ) {
            $themedata = explode( '//', $theme );
            $data = array(
                'slug'      => isset( $themedata[0] ) ? $themedata[0] : '',
                'name'      => isset( $themedata[1] ) ? $themedata[1] : '',
                'prolink'   => isset( $themedata[2] ) ? $themedata[2] : '',
            );

            if ( ! is_wp_error( $data ) ) {

                $theme = wp_get_theme();

                // Installed but Inactive.
                if ( file_exists( get_theme_root(). '/' . $data['slug'] . '/functions.php' ) && ( $theme->stylesheet != $data['slug'] ) ) {

                    $button_classes = 'button themeactivate-now button-primary';
                    $button_text    = esc_html__( 'Activate', 'woolentor' );

                // Not Installed.
                } elseif ( ! file_exists( get_theme_root(). '/' . $data['slug'] . '/functions.php' ) ) {

                    $button_classes = 'button themeinstall-now';
                    $button_text    = esc_html__( 'Install Now', 'woolentor' );

                // Active.
                } else {
                    $button_classes = 'button disabled';
                    $button_text    = esc_html__( 'Activated', 'woolentor' );
                }

                ?>
                    <li class="htwptemplata-theme-<?php echo $data['slug']; ?>">
                        <h3><?php echo $data['name']; ?></h3>
                        <?php
                            if ( !empty( $data['prolink'] ) ) {
                                echo '<a class="button" href="'.esc_url( $data['prolink'] ).'" target="_blank">'.esc_html__( 'Buy Now', 'woolentor' ).'</a>';
                            }else{
                        ?>
                            <button class="<?php echo $button_classes; ?>" data-themeopt='<?php echo wp_json_encode( $data ); ?>'><?php echo $button_text; ?></button>
                        <?php } ?>
                    </li>
                <?php
            }


        }

    }

    /**
     * Ajax plugins activation request
     */
    public function ajax_plugin_activation() {

        if ( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['location'] ) || ! $_POST['location'] ) {
            wp_send_json_error(
                array(
                    'success' => false,
                    'message' => esc_html__( 'Plugin Not Found', 'woolentor' ),
                )
            );
        }

        $plugin_location = ( isset( $_POST['location'] ) ) ? esc_attr( $_POST['location'] ) : '';
        $activate    = activate_plugin( $plugin_location, '', false, true );

        if ( is_wp_error( $activate ) ) {
            wp_send_json_error(
                array(
                    'success' => false,
                    'message' => $activate->get_error_message(),
                )
            );
        }

        wp_send_json_success(
            array(
                'success' => true,
                'message' => esc_html__( 'Plugin Successfully Activated', 'woolentor' ),
            )
        );

    }

    /*
    * Required Theme Activation Request
    */
    public function ajax_theme_activation() {

        if ( ! current_user_can( 'install_themes' ) || ! isset( $_POST['themeslug'] ) || ! $_POST['themeslug'] ) {
            wp_send_json_error(
                array(
                    'success' => false,
                    'message' => esc_html__( 'Sorry, you are not allowed to install themes on this site.', 'woolentor' ),
                )
            );
        }

        $theme_slug = ( isset( $_POST['themeslug'] ) ) ? esc_attr( $_POST['themeslug'] ) : '';
        switch_theme( $theme_slug );

        wp_send_json_success(
            array(
                'success' => true,
                'message' => __( 'Theme Activated', 'woolentor' ),
            )
        );
    }


}

Woolentor_Template_Library::instance();