<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_GTM_Conversion_Tracking{

    private static $_instance = null;

    /**
     * Get Instance
     */
    public static function get_instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    function __construct(){

        define( 'WOOLENTOR_GTM_DATALAYER', 'woolentor_datalayer' );

        // Enqueue scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // Include Nessary file
        $this->include();

        // GTM Snippet manager
        WooLentor_GTM_Snippet::get_instance();

        // Datalayer manager
        WooLentor_Manage_Data_Layer::get_instance();
        
    }

    /**
     * Inclode Nessery file
     *
     * @return void
     */
    public function include(){
        // GTM Snippet
        require_once( __DIR__. '/includes/class.gtm_snippet.php' );
        // Manage data layer
        require_once( __DIR__. '/includes/class.manage_data_layer.php' );
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts(){
       
        //Script
        wp_enqueue_script( 'woolenor-gtm-tracking', plugin_dir_url( __FILE__ ) . 'assets/js/gtm-tracking.js', array('jquery'), WOOLENTOR_VERSION, 'all' );

        // Localize Scripts
        $option_data = array(
            'currency'           => function_exists('get_woocommerce_currency') ? esc_js( get_woocommerce_currency() ) : '',
            'add_to_cart'        => $this->get_saved_data( 'add_to_cart_enable', 'on' ),
            'single_add_to_cart' => $this->get_saved_data( 'single_add_to_cart_enable', 'on' ),
            'remove_from_cart'   => $this->get_saved_data( 'remove_from_cart_enable', 'on' ),
        );
        
        $localize_data = [
            'ajaxurl'     => admin_url( 'admin-ajax.php' ),
            'nonce'       => wp_create_nonce( 'woolentor_gtm_tracking_nonce' ),
            'option_data' => $option_data,
        ];
        wp_localize_script( 'woolenor-gtm-tracking', 'WLGTM', $localize_data );

    }

    /**
     * Get option data
     *
     * @param [string] $option_key
     * @param string $default
     * @return void
     */
    public function get_saved_data( $option_key, $default = '' ) {
		$get_save_data = woolentor_get_option_pro( $option_key, 'woolentor_gtm_convertion_tracking_settings', $default );
		return $get_save_data;
	}


}

Woolentor_GTM_Conversion_Tracking::get_instance();    