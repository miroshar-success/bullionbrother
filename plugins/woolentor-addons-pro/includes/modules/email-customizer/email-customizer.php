<?php
/**
 * Email Customizer
 */

// If this file is accessed directly, exit.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Email Customizer class.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'Woolentor_Email_Customizer' ) ) {
	class Woolentor_Email_Customizer {

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
			$this->init_module();
		}

		/**
	     * Define the required constants.
	     */
	    private function define_constants() {
	        define( 'WOOLENTOR_EMAIL_CUSTOMIZER_FILE', __FILE__ );
	        define( 'WOOLENTOR_EMAIL_CUSTOMIZER_PATH', __DIR__ );
	        define( 'WOOLENTOR_EMAIL_CUSTOMIZER_RENDER_PATH', WOOLENTOR_EMAIL_CUSTOMIZER_PATH . '/render' );
	        define( 'WOOLENTOR_EMAIL_CUSTOMIZER_TEMPLATES_PATH', WOOLENTOR_EMAIL_CUSTOMIZER_PATH . '/templates' );
	        define( 'WOOLENTOR_EMAIL_CUSTOMIZER_WIDGETS_PATH', WOOLENTOR_EMAIL_CUSTOMIZER_PATH . '/widgets' );
	        define( 'WOOLENTOR_EMAIL_CUSTOMIZER_URL', plugins_url( '', WOOLENTOR_EMAIL_CUSTOMIZER_FILE ) );
	        define( 'WOOLENTOR_EMAIL_CUSTOMIZER_ASSETS', WOOLENTOR_EMAIL_CUSTOMIZER_URL . '/assets' );
	        define( 'WOOLENTOR_EMAIL_CUSTOMIZER_ASSETS_PATH', WOOLENTOR_EMAIL_CUSTOMIZER_PATH . '/assets' );
	    }

		/**
		 * Include required files and libraries.
		 */
		private function includes() {
			include_once WOOLENTOR_EMAIL_CUSTOMIZER_PATH . '/includes/functions.php';
			include_once WOOLENTOR_EMAIL_CUSTOMIZER_PATH . '/includes/placeholders.php';

			include_once WOOLENTOR_EMAIL_CUSTOMIZER_PATH . '/includes/Assets.php';
			include_once WOOLENTOR_EMAIL_CUSTOMIZER_PATH . '/includes/Admin.php';
			include_once WOOLENTOR_EMAIL_CUSTOMIZER_PATH . '/includes/Admin/Fields.php';
			include_once WOOLENTOR_EMAIL_CUSTOMIZER_PATH . '/includes/Admin/Utilities.php';
			include_once WOOLENTOR_EMAIL_CUSTOMIZER_PATH . '/includes/Templates.php';
			include_once WOOLENTOR_EMAIL_CUSTOMIZER_PATH . '/includes/Widgets.php';
			include_once WOOLENTOR_EMAIL_CUSTOMIZER_PATH . '/includes/Editor.php';
		}

		/**
		 * Initialize the module.
		 */
		public function init_module() {
			new \Woolentor_Email_Customizer\Assets();
			new \Woolentor_Email_Customizer\Admin();
			new \Woolentor_Email_Customizer\Templates();
			new \Woolentor_Email_Customizer\Widgets();
			new \Woolentor_Email_Customizer\Editor();
		}

	}

	/**
	 * Returns the instance.
	 */
	function woolentor_email_customizer() {
		return Woolentor_Email_Customizer::get_instance();
	}

	// Kick-off.
	woolentor_email_customizer();
}