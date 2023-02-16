<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WooLentor_Multi_Languages {
    
    /**
     * [$language_code]
     * @var string
     */
    public static $language_code;
    
    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [WooLentor_Multi_Languages]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * [__construct] Class constructor
    */
    public function __construct() {
        $this->set_language_code();
        add_filter( 'woolentor_current_language_code', [$this, 'get_language_code'] );
    }

    /**
     * [set_language_code]
     * @return [void]
    */
    public static function set_language_code() {
        
        if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
            self::$language_code = apply_filters( 'wpml_current_language', 'en' );
            
        } elseif ( function_exists( 'pll_current_language' ) ) {
            self::$language_code = pll_current_language();
        }
        
    }

    /**
     * [get_language_code]
     * @var $language_code
     * @return [string]
    */
    public static function get_language_code( $language_code ) {
        if ( self::$language_code ) {
            return self::$language_code;
        }
        return $language_code;
    }

}
WooLentor_Multi_Languages::instance();