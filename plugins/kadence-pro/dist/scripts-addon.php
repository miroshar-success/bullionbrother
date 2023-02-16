<?php
/**
 * Class for the Customizer
 *
 * @package Kadence
 */

namespace Kadence_Pro;

use function Kadence\kadence;
use Kadence\Kadence_CSS;
use function __return_true;
use Kadence_Blocks_Frontend;

/**
 * Main plugin class
 */
class Scripts_Addon {
	/**
	 * Instance Control
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Holds theme settings array sections.
	 *
	 * @var the theme settings sections.
	 */
	public static $settings_sections = array(
		'scripts',
	);

	/**
	 * Instance Control.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
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
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning instances of the class is Forbidden', 'kadence-pro' ), '1.0' );
	}

	/**
	 * Disable un-serializing of the class.
	 *
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing instances of the class is forbidden', 'kadence-pro' ), '1.0' );
	}

	/**
	 * Constructor function.
	 */
	public function __construct() {
		add_filter( 'kadence_theme_options_defaults', array( $this, 'add_option_defaults' ), 10 );
		add_filter( 'kadence_theme_customizer_sections', array( $this, 'add_customizer_sections' ), 10 );
		add_action( 'customize_register', array( $this, 'create_pro_settings_array' ), 1 );
		add_action( 'after_setup_theme', array( $this, 'load_actions' ), 20 );
	}

	/**
	 * Get woocommerce hooks template.
	 */
	public function load_actions() {
		require_once KTP_PATH . 'dist/scripts-addon/hooks.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
	/**
	 * Add Defaults
	 *
	 * @access public
	 * @param array $defaults registered option defaults with kadence theme.
	 * @return array
	 */
	public function add_option_defaults( $defaults ) {
		$script_addons = array(
			'header_scripts'     => '',
			'after_body_scripts' => '',
			'footer_scripts'     => '',
		);
		$defaults = array_merge(
			$defaults,
			$script_addons
		);
		return $defaults;
	}
	/**
	 * Add Sections
	 *
	 * @access public
	 * @param array $sections registered sections with kadence theme.
	 * @return array
	 */
	public function add_customizer_sections( $sections ) {
		$sections['scripts'] = array(
			'title'    => __( 'Custom Scripts', 'kadence-pro' ),
			'priority' => 889,
		);
		return $sections;
	}
	/**
	 * Add settings
	 *
	 * @access public
	 * @param object $wp_customize the customizer object.
	 * @return void
	 */
	public function create_pro_settings_array( $wp_customize ) {
		// Load Settings files.
		foreach ( self::$settings_sections as $key ) {
			require_once KTP_PATH . 'dist/scripts-addon/' . $key . '-options.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}
}

Scripts_Addon::get_instance();
