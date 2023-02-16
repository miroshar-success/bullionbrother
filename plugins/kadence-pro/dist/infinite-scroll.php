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
class Infinite_Addon {
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
		'infinite',
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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Get woocommerce hooks template.
	 */
	public function load_actions() {
		require_once KTP_PATH . 'dist/infinite-scroll/hooks.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
	/**
	 * Add Defaults
	 *
	 * @access public
	 * @param array $defaults registered option defaults with kadence theme.
	 * @return array
	 */
	public function add_option_defaults( $defaults ) {
		$infinite_addons = array(
			'infinite_posts'        => true,
			'infinite_single_posts' => false,
			'infinite_search'       => true,
			'infinite_products'     => true,
			'infinite_custom'       => false,
			'infinite_end_of_content' => esc_html__( 'End of content', 'kadence-pro' ),
		);
		$defaults = array_merge(
			$defaults,
			$infinite_addons
		);
		return $defaults;
	}
	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts() {
		wp_register_script( 'kadence-infinite-scroll', KTP_URL . 'dist/infinite-scroll/infinite-scroll.min.js', array(), KTP_VERSION, true );
		//wp_register_script( 'kadence-single-infinite-scroll', KTP_URL . 'dist/infinite-scroll/post-infinite-scroll.min.js', array( 'kadence-infinite-scroll' ), KTP_VERSION, true );
	}
	/**
	 * Add Sections
	 *
	 * @access public
	 * @param array $sections registered sections with kadence theme.
	 * @return array
	 */
	public function add_customizer_sections( $sections ) {
		$sections['infinite_scroll'] = array(
			'title'    => __( 'Infinite Scroll', 'kadence-pro' ),
			'panel'    => 'general',
			'priority' => 24,
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
			require_once KTP_PATH . 'dist/infinite-scroll/' . $key . '-options.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}
}

Infinite_Addon::get_instance();
