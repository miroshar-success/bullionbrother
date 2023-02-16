<?php
namespace Woolentor\Modules\Order_Bump;

// If this file is accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main class
 *
 * @since 1.0.0
 */
final class Order_Bump{
    /**
     * The single instance of the class.
     *
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * Main Instance
     *
     * Ensures only one instance of this pluin is loaded.
     *
     * @since 1.0.0
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    private function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init();
    }

    /**
     * Define the required constants.
     *
     * @since 1.0.0
     */
    private function define_constants(){
        define( 'Woolentor\Modules\Order_Bump\MODULE_FILE', __FILE__ );
        define( 'Woolentor\Modules\Order_Bump\MODULE_PATH', __DIR__ );
        define( 'Woolentor\Modules\Order_Bump\MODULE_URL', plugins_url( '', MODULE_FILE ) );
        define( 'Woolentor\Modules\Order_Bump\MODULE_ASSETS', MODULE_URL . '/assets' );
    }

    /**
     * Include required core files.
     *
     * @since 1.0.0
     */
    public function includes() {
        require_once WOOLENTOR_ADDONS_PL_PATH_PRO .'includes/modules/email-automation/libs/wloptf/wloptf.php';
        require_once MODULE_PATH .'/includes/class-helper.php';
        require_once MODULE_PATH .'/includes/class-ajax-actions.php';
        require_once MODULE_PATH .'/includes/class-manage-rules.php';
        
        // Admin
        require_once MODULE_PATH .'/includes/Admin/class-admin.php';
        require_once MODULE_PATH .'/includes/Admin/class-metaboxes.php';
        require_once MODULE_PATH .'/includes/Admin/class-order-bumps-list-table.php';
        require_once MODULE_PATH .'/includes/Admin/class-customize-cpt.php';

        // Frontend
        require_once MODULE_PATH .'/includes/Frontend/class-frontend.php';
    }

    /**
     * Initialize the plugin.
     */
    public function init(){
        Ajax_Actions::instance();


        $list_table_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
        if( $list_table_page === 'woolentor-order-bump' || !is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ){
            Frontend::instance();
        }

        if( is_admin() ){
            Admin::instance();
        }
    }
}

/**
 * Returns the main instance of Order Bump Module.
 */
function order_bump() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
    return Order_Bump::instance();
}

// Kick-off the module
order_bump();