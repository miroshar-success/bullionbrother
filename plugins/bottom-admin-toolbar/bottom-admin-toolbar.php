<?php
/**
 * Plugin Name:       Bottom Admin Toolbar
 * Plugin URI:        https://wordpress.org/plugins/bottom-admin-toolbar/
 * Description:       Change your admin bar position by activating that simple extension.
 * Version:           1.5.1
 * Tags:              admin, bar, adminbar, bottom bar, toolbar, WordPress, bottom
 * Requires at least: 3.0 or higher
 * Requires PHP:      5.6
 * Tested up to:      6.1.1
 * Stable tag:        1.5.1
 * Author:            DevlopEr
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Contributors:      DevlopEr
 * Donate link:       https://ko-fi.com/devloper
 */

if ( !class_exists( 'BottomAdminToolbar' ) ) :
    /**
     * BottomAdminToolbar
     */
    class BottomAdminToolbar {
        /**
         * Constructor
         */
        public function __construct() {
            define( 'BAB_PATH', plugin_dir_path( __FILE__ ) );
            define( 'BAB_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets/' );
            define( 'BAB_BASENAME', plugin_basename( __FILE__ ) );
            add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) ); // Remove loaded default style
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_files' ) ); // Enqueue Files
            add_action( 'admin_init', array($this, 'register_settings_section' ));
            add_action( 'admin_menu', array($this, 'register_submenu_page' ));
            $show_in_admin = get_option('bab_show_in_admin');
            if ($show_in_admin):
                $show_in_admin = reset($show_in_admin);
                if ($show_in_admin === 'yes'):
                    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_files' ) ); // Enqueue Files
                endif;
            endif;
        }

        /**
         * Enqueue custom files
         */
        public function enqueue_files() {
            if ( is_admin_bar_showing() ) :
                wp_enqueue_style( 'bab-css', BAB_ASSETS_URL . 'bab.css', array(), '1.0', 'all' );
                wp_enqueue_script( 'bab-js', BAB_ASSETS_URL . 'bab.js', array(), '1.0', true );
            endif;
        }

        /**
         * Register settings section
         */
        public function register_settings_section() {

            /**
             * Register setting
             */
            register_setting( 'bottom-admin-bar', 'bab_show_in_admin' );

            /**
             * Register setting section
             */
            add_settings_section(
                'bab_settings_section',
                false,
                false,
                'bottom-admin-bar'
            );

            /**
             * Register setting field
             */
            add_settings_field(
                'bab_show_in_admin',                     
                __( 'Activer dans l\'administration', 'bottom-admin-toolbar' ),
                array($this,'bab_show_in_admin_cb'),
                'bottom-admin-bar',
                'bab_settings_section',
                array(
                    'label_for'         => 'bab_show_in_admin',
                    'bab_custom_data' => 'custom',
                )
            );
        }

        /**
         * Show html output
         */
        public function bab_show_in_admin_cb( $args ) {
            $options = get_option( 'bab_show_in_admin' );
            ?>
            <select id="<?php echo esc_attr( $args['label_for'] ); ?>" data-custom="<?php echo esc_attr( $args['bab_custom_data'] ); ?>" name="bab_show_in_admin[<?php echo esc_attr( $args['label_for'] ); ?>]">
                <option value="yes" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'yes', false ) ) : ( '' ); ?>>
                    <?php esc_html_e( 'Oui', 'bottom-admin-toolbar' ); ?>
                </option>
                <option value="no" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'no', false ) ) : ( '' ); ?>>
                    <?php esc_html_e( 'Non', 'bottom-admin-toolbar' ); ?>
                </option>
            </select>
            <?php
        }

        /**
         * Register sub menu page
         */
        public function register_submenu_page() {
            add_submenu_page(
                'options-general.php',
                'Bottom Admin Toolbar',
                'Bottom Admin Toolbar',
                'manage_options',
                'bab-settings',
                array($this, 'bab_settings_display')
            );
        }

        /**
         * Display setings
         */
        public function bab_settings_display() {
            if ( ! current_user_can( 'manage_options' ) ) :
                return;
            endif;

            settings_errors( 'wporg_messages' ); ?>
            <div class="wrap">
                <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
                <form action="options.php" method="post">
                    <?php
                    settings_fields( 'bottom-admin-bar' );
                    do_settings_sections( 'bottom-admin-bar' );
                    submit_button( 'Sauvegarder' );
                    ?>
                </form>
            </div>
            <?php
        }

    }
    new BottomAdminToolbar();
endif;
