<?php
/**
 * Main plugin class
 */
final class Kadence_Theme_Pro {
	/**
	 * Instance Control
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Main Kadence_Theme_Pro Instance.
	 *
	 * Insures that only one instance of Kadence_Theme_Pro exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @static
	 * @staticvar array $instance
	 *
	 * @param string $file Main plugin file path.
	 *
	 * @return Kadence_Theme_Pro The one true Kadence_Theme_Pro
	 */
	public static function instance( $file = '' ) {

		// Return if already instantiated.
		if ( self::is_instantiated() ) {
			return self::$instance;
		}

		// Setup the singleton.
		self::setup_instance( $file );

		// Bootstrap.
		self::$instance->setup_constants();
		self::$instance->setup_files();

		// Return the instance.
		return self::$instance;

	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'kadence-pro' ), '1.0' );
	}

	/**
	 * Disable un-serializing of the class.
	 *
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'kadence-pro' ), '1.0' );
	}

	/**
	 * Return whether the main loading class has been instantiated or not.
	 *
	 * @access private
	 * @since  3.0
	 * @return boolean True if instantiated. False if not.
	 */
	private static function is_instantiated() {

		// Return true if instance is correct class.
		if ( ! empty( self::$instance ) && ( self::$instance instanceof Kadence_Theme_Pro ) ) {
			return true;
		}

		// Return false if not instantiated correctly.
		return false;
	}

	/**
	 * Setup the singleton instance
	 *
	 * @param string $file Path to main plugin file.
	 *
	 * @access private
	 */
	private static function setup_instance( $file = '' ) {
		self::$instance       = new Kadence_Theme_Pro();
		self::$instance->file = $file;
	}
	/**
	 * Setup plugin constants.
	 *
	 * @access private
	 * @since  3.0
	 * @return void
	 */
	private function setup_constants() {

		if ( ! defined( 'KTP_VERSION' ) ) {
			define( 'KTP_VERSION', '1.0.7' );
		}

		if ( ! defined( 'KTP_PLUGIN_FILE' ) ) {
			define( 'KTP_PLUGIN_FILE', $this->file );
		}

		if ( ! defined( 'KTP_PATH' ) ) {
			define( 'KTP_PATH', realpath( plugin_dir_path( KTP_PLUGIN_FILE ) ) . DIRECTORY_SEPARATOR );
		}

		if ( ! defined( 'KTP_URL' ) ) {
			define( 'KTP_URL', plugin_dir_url( KTP_PLUGIN_FILE ) );
		}

	}
	/**
	 * Include required files.
	 *
	 * @access private
	 * @return void
	 */
	private function setup_files() {
		$this->include_files();

		// Admin.
		if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			$this->include_admin();
		} else {
			$this->include_frontend();
		}
	}
	/**
	 * On Load
	 */
	public function include_files() {
		require_once KTP_PATH . 'dist/elements/post-select-rest-controller.php';
		add_action( 'rest_api_init', array( $this, 'register_api_endpoints' ) );
		$enabled = json_decode( get_option( 'kadence_pro_theme_config' ), true );
		if ( isset( $enabled ) && isset( $enabled['conditional_headers'] ) && true === $enabled['conditional_headers'] ) {
			require_once KTP_PATH . 'dist/conditional-headers.php';
		}
		if ( isset( $enabled ) && isset( $enabled['elements'] ) && true === $enabled['elements'] ) {
			require_once KTP_PATH . 'dist/elements/duplicate-elements.php';
			require_once KTP_PATH . 'dist/elements/elements-init.php';
		}
		if ( isset( $enabled ) && isset( $enabled['header_addons'] ) && true === $enabled['header_addons'] ) {
			require_once KTP_PATH . 'dist/header-addons.php';
		}
		if ( isset( $enabled ) && isset( $enabled['mega_menu'] ) && true === $enabled['mega_menu'] ) {
			require_once KTP_PATH . 'dist/mega-menu/mega-menu.php';
		}
		if ( class_exists( 'woocommerce' ) && isset( $enabled ) && isset( $enabled['woocommerce_addons'] ) && true === $enabled['woocommerce_addons'] ) {
			require_once KTP_PATH . 'dist/woocommerce-addons.php';
		}
		if ( isset( $enabled ) && isset( $enabled['scripts'] ) && true === $enabled['scripts'] ) {
			require_once KTP_PATH . 'dist/scripts-addon.php';
		}
		if ( isset( $enabled ) && isset( $enabled['infinite'] ) && true === $enabled['infinite'] ) {
			require_once KTP_PATH . 'dist/infinite-scroll.php';
		}
		if ( isset( $enabled ) && isset( $enabled['localgravatars'] ) && true === $enabled['localgravatars'] ) {
			require_once KTP_PATH . 'dist/local-gravatars.php';
		}
		if ( isset( $enabled ) && isset( $enabled['archive_meta'] ) && true === $enabled['archive_meta'] ) {
			require_once KTP_PATH . 'dist/archive-meta.php';
		}
		if ( isset( $enabled ) && isset( $enabled['dark_mode'] ) && true === $enabled['dark_mode'] ) {
			require_once KTP_PATH . 'dist/dark-mode.php';
		}
	}

	/**
	 * On Load
	 */
	public function include_admin() {
		// if ( ! class_exists( 'Kadence/Theme' ) ) {
		// 	add_action( 'admin_notices', array( $this, 'admin_notice_need_kadence_theme' ) );
		// }
		add_action( 'admin_enqueue_scripts', array( $this, 'basic_css_menu_support' ) );
	}

	/**
	 * Add a little css for submenu items.
	 */
	public function basic_css_menu_support() {
		wp_register_style( 'kadence-pro-admin', false );
		wp_enqueue_style( 'kadence-pro-admin' );
		$css = '#menu-appearance .wp-submenu a[href^="themes.php?page=kadence-"]:before, #menu-appearance .wp-submenu a[href^="edit.php?post_type=kadence_element"]:before, #menu-appearance .wp-submenu a[href^="edit.php?post_type=kt_font"]:before {content: "\21B3";margin-right: 0.5em;opacity: 0.5;}';
		wp_add_inline_style( 'kadence-pro-admin', $css );
	}
	/**
	 * On Load
	 */
	public function include_frontend() {
		add_shortcode( 'kadence_breadcrumbs', array( $this, 'output_kadence_breadcrumbs' ) );
	}
	/**
	 * On Load
	 */
	public function output_kadence_breadcrumbs( $atts ) {
		$args = shortcode_atts(
			array(
				'show_title' => true,
			),
			$atts
		);
		$output = '';
		if ( function_exists( 'Kadence\kadence' ) ) {
			ob_start();
				Kadence\kadence()->print_breadcrumb( $args );
			$output = ob_get_clean();
		}
		return $output;
	}
	/**
	 * Admin Notice
	 */
	public function admin_notice_need_kadence_theme() {
		if ( get_transient( 'kadence_theme_pro_free_theme_notice' ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		echo '<div class="notice notice-error is-dismissible kt-blocks-pro-notice-wrapper">';
		// translators: %s is a link to kadence theme.
		echo '<p>' . sprintf( esc_html__( 'Kadence Theme Pro requires the %s to be active for it to work.', 'kadence-pro' ) . '</p>', '<a target="_blank" href="https://kadencewp.com/kadence-theme/">Kadence Theme</a>' );
		echo '</div>';
	}
	/**
	 * Setup the post select API endpoint.
	 *
	 * @return void
	 */
	public function register_api_endpoints() {
		$controller = new Kadence_Pro\Post_Select_Controller();
		$controller->register_routes();
	}
}
/**
 * Function to get main class instance.
 */
function kadence_theme_pro() {
	return Kadence_Theme_Pro::instance();
}
