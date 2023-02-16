<?php
namespace Woolentor\Modules\Order_Bump;

// If this file is accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


class Admin{
    public $template_type;

    protected static $_instance = null;
    
    /**
     * Instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->template_type = isset( $_GET['template_type'] ) ? sanitize_text_field( wp_unslash( $_GET['template_type'] ) ) : '';

        // Enqueue scripts and styles
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        
        // Tweak admin menu
        add_action( 'admin_menu', [ $this, 'add_submenu_page_and_render_custom_posts' ], 225 );

        // Tweak woolentor-template post type
        Customize_CPT::instance();

        // Add metaboxes
        Metaboxes::instance();

        // Manage rules
        if( !is_admin() ){
            Manage_Rules::instance();
        }

        // Add our custom page to WooCommerce's screen to use the tooltip functionality
        add_filter('woocommerce_screen_ids', [ $this, 'set_wc_screen_ids' ] );
    }

    /**
     * It enqueues the CSS and JS files
     */
    public function enqueue_scripts( $hook_suffix ){
        wp_enqueue_style( 'woolentor-order-bump-admin', MODULE_URL . '/assets/css/order-bump-admin.css', [], WOOLENTOR_VERSION );
        wp_enqueue_script( 'woolentor-order-bump-admin', MODULE_URL . '/assets/js/order-bump-admin.js', [ 'jquery' ], WOOLENTOR_VERSION, true );

        // Localize script
        wp_localize_script( 'woolentor-order-bump-admin', 'woolentor_order_bump_params', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'woolentor_order_bump_nonce' ),
            'wp_debug_log' => WP_DEBUG_LOG,
        ] );
    }

    /**
     * It adds a submenu page to the WooLentor menu.
     */
    public function add_submenu_page_and_render_custom_posts(){
        // Add submenu
        add_submenu_page( 
            'woolentor_page',   
            __( 'Order Bump', 'woolentor-pro' ),
            __( 'Order Bump', 'woolentor-pro' ),
            'manage_woocommerce', 
            'woolentor-order-bump', 
            [$this, 'render_order_bump_list_table']
        );
    }

    /**
     * It creates a new instance of the Order_Bumps_List_Table, prepares the items, and then displays the table.
     */
    public function render_order_bump_list_table(){
        $order_bumps_list_table = new Order_Bumps_List_Table();
        $order_bumps_list_table->prepare_items();
        ?>
            <div class="wrap">
                <h1 class="wp-heading-inline"><?php echo esc_html__( 'Order Bumps', 'woolentor-pro' ) ?></h1>
                <a href="<?php echo esc_url(admin_url('post-new.php?post_type=woolentor-template&template_type=order-bump')) ?>" class="page-title-action"><?php echo esc_html__('Add New', 'woolentor-pro') ?></a>
                <hr class="wp-header-end">

                <?php $order_bumps_list_table->display(); ?>
            </div>
        <?php
    }

    /**
     * Set our page to the WooCommerce screen IDs
     * so WooCommerce will treat our page as a WooCommerce screen and use the tooltip functionality
     */
    public function set_wc_screen_ids( $screen ){
        $screen[] = 'shoplentor_page_woolentor-order-bump';
        return $screen;
    }
}