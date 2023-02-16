<?php
/**
 * Product Filter
 */

// If this file is accessed directly, exit.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product Filter class.
 */
if ( ! class_exists( 'Woolentor_Product_Filter' ) ) {
	class Woolentor_Product_Filter {

		/**
		 * Enabled.
		 */
		private static $_enabled = true;

		/**
		 * Instance.
		 */
		private static $_instance = null;

		/**
		 * Get instance.
		 */
		public static function get_instance( $enabled = true ) {
			self::$_enabled = $enabled;

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Constructor.
		 */
		private function __construct() {
			$this->define_constants();
			$this->includes();
			$this->activator();
			$this->init_module();
		}

		/**
	     * Define the required constants.
	     */
	    private function define_constants() {
	        define( 'WLPF_FILE', __FILE__ );
	        define( 'WLPF_PATH', __DIR__ );
	        define( 'WLPF_URL', plugins_url( '', WLPF_FILE ) );
	        define( 'WLPF_ASSETS', WLPF_URL . '/assets' );
	        define( 'WLPF_ASSETS_PATH', WLPF_PATH . '/assets' );
	    }

		/**
		 * Include required files and libraries.
		 */
		private function includes() {
			$path = trailingslashit( WLPF_PATH );

			require $path . 'functions/sitewide.php';
			require $path . 'functions/settings.php';
			require $path . 'functions/frontend.php';

			spl_autoload_register( array( $this, 'autoloader' ) );
		}

		/**
		 * Autoloader.
		 */
		private function autoloader( $class ) {
			if ( 0 === strpos( $class, 'WLPF' ) ) {
				$file = str_replace( array( '\\', 'WLPF' ), array( DIRECTORY_SEPARATOR, 'includes' ), $class );
				$file = sprintf( '%1$s%2$s.php', trailingslashit( WLPF_PATH ), $file );
				$file = realpath( $file );

				if ( ( false !== $file ) && file_exists( $file ) ) {
					require $file;
				}
			}
		}

		/**
		 * Activator.
		 */
		public function activator() {
			new \WLPF\Activator();
		}

		/**
		 * Initialize the module.
		 */
		public function init_module() {
			if ( self::$_enabled ) {
				new \WLPF\Assets();
				new \WLPF\Admin();
				new \WLPF\Frontend();
			} else {
				new \WLPF\Assets( 'admin' );
				new \WLPF\Admin();
			}
		}

	}

	/**
	 * Returns the instance.
	 */
	function woolentor_product_filter( $enabled = true ) {
		return Woolentor_Product_Filter::get_instance( $enabled );
	}
}