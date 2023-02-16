<?php
/**
 * Email Automation
 */

// If this file is accessed directly, exit.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Email Automation class.
 */
if ( ! class_exists( 'Woolentor_Email_Automation' ) ) {
	class Woolentor_Email_Automation {

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
			$this->define_constants();
			$this->includes();
			$this->activator();
			$this->init_module();
		}

		/**
	     * Define the required constants.
	     */
	    private function define_constants() {
	        define( 'WLEA_FILE', __FILE__ );
	        define( 'WLEA_PATH', __DIR__ );
	        define( 'WLEA_RENDER_PATH', WLEA_PATH . '/render' );
	        define( 'WLEA_TEMPLATES_PATH', WLEA_PATH . '/templates' );
	        define( 'WLEA_WIDGETS_PATH', WLEA_PATH . '/widgets' );
	        define( 'WLEA_URL', plugins_url( '', WLEA_FILE ) );
	        define( 'WLEA_ASSETS', WLEA_URL . '/assets' );
	        define( 'WLEA_ASSETS_PATH', WLEA_PATH . '/assets' );
	    }

		/**
		 * Include required files and libraries.
		 */
		private function includes() {
			$path = trailingslashit( WLEA_PATH );

			require $path . 'libs/wloptf/wloptf.php';

			require $path . 'includes/functions.php';
			require $path . 'includes/settings.php';
			require $path . 'includes/placeholders.php';

			require $path . 'includes/ajax-functions.php';
			require $path . 'includes/corn-jobs.php';

			spl_autoload_register( array( $this, 'autoloader' ) );
		}

		/**
		 * Autoloader.
		 */
		private function autoloader( $class ) {
			if ( 0 === strpos( $class, 'WLEA' ) ) {
				$file = str_replace( array( '\\', 'WLEA' ), array( DIRECTORY_SEPARATOR, 'includes' ), $class );
				$file = sprintf( '%1$s%2$s.php', trailingslashit( WLEA_PATH ), $file );
				$file = realpath( $file );

				if ( false !== $file && file_exists( $file ) ) {
					require $file;
				}
			}
		}

		/**
		 * Activator.
		 */
		public function activator() {
			new WLEA\Activator();
		}

		/**
		 * Initialize the module.
		 */
		public function init_module() {
			new WLEA\Assets();
			new WLEA\Admin();
			new WLEA\Workflow();
		}

	}

	/**
	 * Returns the instance.
	 */
	function woolentor_email_automation() {
		return Woolentor_Email_Automation::get_instance();
	}

	// Kick-off.
	woolentor_email_automation();
}