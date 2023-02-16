<?php
/**
 * WooLentor Options Framework
 */

// If this file is accessed directly, exit.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class.
 */
if ( ! class_exists( 'WLOPTF' ) ) {
	class WLOPTF {

		/**
	     * Version.
	     */
		private $version = '1.0.0';

		/**
		 * Instance.
		 */
		private static $_instance = null;

		/**
		 * Get instance.
		 */
		public static function get_instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Constructor.
		 */
		private function __construct() {
			$this->constants();
			$this->includes();
			$this->initialize();
		}

		/**
	     * Constants.
	     */
	    private function constants() {
			define( 'WLOPTF_VERSION', $this->version );
	        define( 'WLOPTF_FILE', __FILE__ );
	        define( 'WLOPTF_PATH', __DIR__ );
	        define( 'WLOPTF_URL', plugins_url( '', WLOPTF_FILE ) );
	        define( 'WLOPTF_ASSETS', WLOPTF_URL . '/assets' );
	        define( 'WLOPTF_ASSETS_PATH', WLOPTF_PATH . '/assets' );
	    }

		/**
		 * Include required files and libraries.
		 */
		private function includes() {
			require trailingslashit( WLOPTF_PATH ) . 'includes/functions.php';
			require trailingslashit( WLOPTF_PATH ) . 'includes/ajax-functions.php';

			spl_autoload_register( array( $this, 'autoloader' ) );
		}

		/**
		 * Autoloader.
		 */
		private function autoloader( $class ) {
			if ( 0 === strpos( $class, 'WLOPTF' ) ) {
				$file = str_replace( array( '\\', 'WLOPTF' ), array( DIRECTORY_SEPARATOR, 'includes' ), $class );
				$file = sprintf( '%1$s%2$s.php', trailingslashit( WLOPTF_PATH ), $file );
				$file = realpath( $file );

				if ( false !== $file && file_exists( $file ) ) {
					require $file;
				}
			}
		}

		/**
		 * Initialize.
		 */
		public function initialize() {
			new \WLOPTF\Assets();
		}

	}

	/**
	 * Returns the instance.
	 */
	function wloptf() {
		return WLOPTF::get_instance();
	}

	// Kick-off.
	wloptf();
}