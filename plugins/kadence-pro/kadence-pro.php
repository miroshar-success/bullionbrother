<?php
/**
 * Plugin Name: Kadence Pro - Premium addon for the Kadence Theme
 * Plugin URI:  https://www.kadencewp.com/kadence-theme/premium/
 * Description: Extends the Kadence theme with premium features and addons.
 * Version:     1.0.7
 * Author:      Kadence WP
 * Author URI:  https://www.kadencewp.com/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages
 * Text Domain: kadence-pro
 *
 * @package Kadence Pro
 */

/**
 * Class KTP_Requirements_Check
 */
final class KTP_Requirements_Check {

	/**
	 * Plugin file
	 *
	 * @var string
	 */
	private $file = '';

	/**
	 * Plugin basename
	 *
	 * @var string
	 */
	private $base = '';

	/**
	 * Requirements array
	 *
	 * @var array
	 */
	private $requirements = array(

		// PHP.
		'php' => array(
			'minimum' => '7.0.0',
			'name'    => 'PHP',
			'exists'  => true,
			'current' => false,
			'checked' => false,
			'met'     => false,
		),

		// WordPress.
		'wp' => array(
			'minimum' => '5.2.0',
			'name'    => 'WordPress',
			'exists'  => true,
			'current' => false,
			'checked' => false,
			'met'     => false,
		),
		// Theme.
		'kadence' => array(
			'minimum' => '0.6.0',
			'name'    => 'Kadence',
			'exists'  => true,
			'current' => false,
			'checked' => false,
			'met'     => false,
		),
	);

	/**
	 * Setup plugin requirements
	 */
	public function __construct() {

		// Setup file & base.
		$this->file = __FILE__;
		$this->base = plugin_basename( $this->file );

		// Always load translations.
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'after_setup_theme', array( $this, 'check_and_load' ), 1 );
	}

	/**
	 * Quit without loading
	 */
	public function check_and_load() {
		// Load or quit.
		$this->met()
			? $this->load()
			: $this->quit();
	}
	/**
	 * Quit without loading
	 */
	private function quit() {
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'wp_loaded', array( $this, 'hide_inactive_theme_notice' ) );
	}

	/**
	 * Load normally
	 */
	private function load() {
		// Maybe include the bundled bootstrapper, make sure theme class is loaded else we may get an error.
		if ( class_exists( 'Kadence\Theme' ) && ! class_exists( 'Kadence_Theme_Pro' ) ) {
			require_once dirname( $this->file ) . '/class-kadence-theme-pro.php';
		}

		// Maybe hook-in the bootstrapper.
		if ( class_exists( 'Kadence_Theme_Pro' ) ) {

			// Bootstrap to after_setup_theme before priority 10 to make sure all hooks are added.
			add_action( 'after_setup_theme', array( $this, 'bootstrap' ), 4 );
			add_action( 'after_setup_theme', array( $this, 'updater' ), 5 );

		}
	}
	/**
	 * Update the plugin.
	 */
	public function updater() {
		require_once dirname( $this->file ) . '/kadence-update-checker/kadence-update-checker.php';
		require_once dirname( $this->file ) . '/kadence-classes/kadence-activation/updater.php';
	}
	/**
	 * Bootstrap everything.
	 */
	public function bootstrap() {
		Kadence_Theme_Pro::instance( $this->file );
	}

	/**
	 * Plugin agnostic method to output unmet requirements styling
	 */
	public function admin_head() {
		add_action( 'admin_notices', array( $this, 'admin_notice_need_kadence_theme' ) );
	}
	/**
	 * Hide Notice
	 */
	public function hide_inactive_theme_notice() {
		if ( isset( $_GET['kadence-theme-notice'] ) && isset( $_GET['_kt_notice_nonce'] ) ) {
			if ( ! wp_verify_nonce( wp_unslash( sanitize_key( $_GET['_kt_notice_nonce'] ) ), 'kadence_theme_hide_notice' ) ) {
				wp_die( esc_html__( 'Authorization failed. Please refresh the page and try again.', 'kadence-pro' ) );
			}
			update_option( 'kadence_theme_pro_no_theme_notice', true );
		}
	}
	/**
	 * Admin Notice
	 */
	public function admin_notice_need_kadence_theme() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( get_option( 'kadence_theme_pro_no_theme_notice' ) ) {
			return;
		}
		echo '<div class="notice notice-error kadence-pro-notice-wrapper" style="position:relative;">';
		// translators: %s is a link to kadence theme.
		echo '<p>' . sprintf( esc_html__( 'Kadence Theme Pro requires the %s to be active for it to work.', 'kadence-pro' ) . '</p>', '<a target="_blank" href="https://kadencewp.com/kadence-theme/">Kadence Theme</a>' );
		echo '<a href="' . esc_url( wp_nonce_url( add_query_arg( 'kadence-theme-notice', 'dismiss' ), 'kadence_theme_hide_notice', '_kt_notice_nonce' ) ) . '" style="text-decoration:none" class="notice-dismiss kt-close-theme-notice"><span class="screen-reader-text">' . esc_html__( 'hide', 'kadence-pro' ) . '</span></a>';
		echo '</div>';
	}
	/**
	 * Plugin specific requirements checker
	 */
	private function check() {

		// Loop through requirements.
		foreach ( $this->requirements as $dependency => $properties ) {

			// Which dependency are we checking?
			switch ( $dependency ) {

				// PHP.
				case 'php':
					$version = phpversion();
					break;

				// WP.
				case 'wp':
					$version = get_bloginfo( 'version' );
					break;
				// kadence.
				case 'kadence':
					$current_theme = wp_get_theme();
					if ( get_template_directory() !== get_stylesheet_directory() ) {
						$version = ( 'kadence' === $current_theme->get( 'Template' ) ? '1.0.0' : '0.0.1' );
					} else {
						$version = ( 'Kadence' === $current_theme->get( 'Name' ) ? '1.0.0' : '0.0.1' );
					}
					break;

				// Unknown.
				default :
					$version = false;
					break;
			}

			// Merge to original array.
			if ( ! empty( $version ) ) {
				$this->requirements[ $dependency ] = array_merge(
					$this->requirements[ $dependency ],
					array(
						'current' => $version,
						'checked' => true,
						'met'     => version_compare( $version, $properties['minimum'], '>=' ),
					)
				);
			}
		}
	}

	/**
	 * Have all requirements been met?
	 *
	 * @return boolean
	 */
	public function met() {

		// Run the check.
		$this->check();

		// Default to true (any false below wins).
		$retval  = true;
		$to_meet = wp_list_pluck( $this->requirements, 'met' );

		// Look for unmet dependencies, and exit if so.
		foreach ( $to_meet as $met ) {
			if ( empty( $met ) ) {
				$retval = false;
				continue;
			}
		}

		// Return.
		return $retval;
	}

	/**
	 * Plugin specific text-domain loader.
	 *
	 * @return void
	 */
	public function load_textdomain() {

		// Set filter for plugin's languages directory.
		$ktp_lang_dir = dirname( $this->base ) . '/languages/';
		$ktp_lang_dir = apply_filters( 'ktp_languages_directory', $ktp_lang_dir );

		// Load the default language files.
		load_plugin_textdomain( 'kadence-pro', false, $ktp_lang_dir );

	}
}

// Invoke the checker.
new KTP_Requirements_Check();
