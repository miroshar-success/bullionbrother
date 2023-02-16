<?php
namespace EverCompare;

/**
 * Frontend handlers class
 */
class Frontend {

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Base]
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
        $this->includes();
        Frontend\Manage_Compare::instance();
        Frontend\Shortcode::instance();
    }

    public function includes(){
        require_once( __DIR__. '/Frontend/Manage_Compare.php' );
        require_once __DIR__ . '/Frontend/Shortcode.php';
    }

}