<?php
namespace WishSuite\Admin;
/**
 * Dashboard handlers class
 */
class Dashboard {

    /**
     * Menu capability
     */
    const MENU_CAPABILITY = 'manage_options';

    /**
     * Parent Menu Page Slug
     */
    const MENU_PAGE_SLUG = 'wishsuite';

    /**
     * [$admin_menu_hook] Parent Menu Hook
     * @var string
     */
    static $admin_menu_hook = '';


    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Admin]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Initialize the class
     */
    private function __construct() {

        require_once( __DIR__. '/Admin_Fields.php' );

        Admin_Fields::instance();

        add_action( 'admin_menu', [ $this, 'add_menu' ], 225 );

        // Add a post display state for special WishSuite page.
        add_filter( 'display_post_states', [ $this, 'add_display_post_states' ], 10, 2 );

    }

    /**
     * [add_menu] Admin Menu
     */
    public function add_menu(){

        self::$admin_menu_hook = add_submenu_page(
            'woolentor_page',
            esc_html__( 'Wishlist', 'woolentor' ),
            esc_html__( 'Wishlist', 'woolentor' ),
            'manage_options',
            self::MENU_PAGE_SLUG,
            [ $this,'dashboard' ]
        );

        add_action( 'load-' . self::$admin_menu_hook, [ $this, 'init_hooks'] );
        

    }

    /**
     * Initialize our hooks for the admin page
     *
     * @return void
     */
    public function init_hooks() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * [enqueue_scripts] Add Scripts Base Menu Slug
     * @param  [string] $hook
     * @return [void]
     */
    public function enqueue_scripts() {
        wp_enqueue_style( 'wishsuite-admin' );
        wp_enqueue_script( 'wishsuite-admin' );
    }

    /**
     * [dashboard] Dashboard plugin page
     * @return [HTML]
     */
    public function dashboard(){
        Admin_Fields::instance()->plugin_page();
    }

    /**
     * Add a post display state for special WishSuite page in the page list table.
     *
     * @param array   $post_states An array of post display states.
     * @param WP_Post $post  The current post object.
     */
    public function add_display_post_states( $post_states, $post ){
        if ( (int)woolentor_get_option( 'wishlist_page', 'wishsuite_table_settings_tabs' ) === $post->ID ) {
            $post_states['wishsuite_page_for_wishlist_table'] = __( 'WishSuite', 'wishsuite' );
        }
        return $post_states;
    }
    

}