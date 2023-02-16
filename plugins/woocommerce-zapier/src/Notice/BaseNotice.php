<?php

namespace OM4\WooCommerceZapier\Notice;

use OM4\WooCommerceZapier\AdminUI;
use OM4\WooCommerceZapier\Exception\InvalidImplementationException;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Settings;
use WC_Admin_Notices;

defined( 'ABSPATH' ) || exit;

/**
 * Base Admin Notice Class.
 *
 * A Notice is a message that is displayed to all WooCommerce Administrator
 * users while they are browsing the WordPress admin (wp-admin) screens. A
 * notice is displayed persistently until the a user chooses to dismiss it.
 * Extend this class to implement a Admin/Dashboard Notice that is displayed to
 * the user. When extending, the `id` must be set, and the corresponding
 * template file must be created as `templates/notices/{$id}.php` to define the
 * wording/content for the notice.
 *
 * @since 2.0.0
 */
abstract class BaseNotice {

	/**
	 * The unique ID for this notice.
	 *
	 * Should contain only alphanumeric characters (and underscores between words).
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 * Whether or not this notice needs to perform any custom code when it is dismissed.
	 *
	 * Defaults to false.
	 *
	 * To run custom code during dismiss, set this to true, and implement the dismissed() method.
	 *
	 * @var boolean
	 */
	protected $has_dismiss_action = false;

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Settings instance.
	 *
	 * @var Settings
	 */
	protected $settings;

	/**
	 * AdminUI instance.
	 *
	 * @var AdminUI
	 */
	protected $admin_ui;

	/**
	 * Constructor.
	 *
	 * @param Logger   $logger Logger instance.
	 * @param Settings $settings Settings instance.
	 * @param AdminUI  $admin_ui AdminUI instance.
	 *
	 * @throws InvalidImplementationException When the concrete class missing the id property.
	 */
	public function __construct( Logger $logger, Settings $settings, AdminUI $admin_ui ) {
		$this->logger   = $logger;
		$this->settings = $settings;
		$this->admin_ui = $admin_ui;
		if ( 0 === \strlen( $this->id ) ) {
			throw new InvalidImplementationException( '`id` needs to be set', 1 );
		}
	}

	/**
	 * Initialise hooks/filters for this notice.
	 *
	 * @return void
	 */
	public function initialise() {
		if ( $this->has_dismiss_action ) {
			add_action( 'woocommerce_hide_' . $this->get_id() . '_notice', array( $this, 'dismissed' ) );
		}

		add_action( 'wc_zapier_plugin_deactivate', array( $this, 'disable' ) );
	}

	/**
	 * Store an option/setting that instructs this notice to be displayed to the user.
	 *
	 * @return void
	 */
	public function enable() {
		WC_Admin_Notices::add_custom_notice( $this->get_id(), $this->get_content() );
		$this->logger->debug( '%s admin notice enabled.', $this->get_id() );
	}

	/**
	 * Get the unique ID for this notice.
	 * Used to help WooCommerce uniquely identify this notice.
	 *
	 * @return string
	 */
	public function get_id() {
		return "wc_zapier_{$this->id}";
	}

	/**
	 * Define the variables required for this notice.
	 * Override this method if your notice content requires specific variables to be available
	 * when generating the notice's content.
	 *
	 * @return array
	 */
	protected function template_variables() {
		return array();
	}

	/**
	 * Get this notice's wording/content (HTML) output from the corresponding template.
	 * This does not include the wrapper <div> etc, as that is generated in `display()`.
	 *
	 * @return string
	 */
	protected function get_content() {
		$template_name   = str_replace( '_', '-', $this->id );
		$notice_template = plugin_dir_path( WC_ZAPIER_PLUGIN_FILE ) . 'templates/notices/' . $template_name . '.php';
		if ( ! file_exists( $notice_template ) ) {
			$this->logger->error( 'Template %s not found.', $notice_template );
			return '';
		}
		// Extract the variables to a local namespace, so that they are available for use in the HTML template.
		$variables = $this->template_variables();
		if ( count( $variables ) > 0 ) {
			/**
			 * This usage of extract cannot easily be avoided.
			 * Passing the EXTR_SKIP flag is the safest option, ensuring globals and function variables cannot be overwritten.
			 */
			// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
			extract( $variables, EXTR_SKIP );
		}
		// Load the template.
		ob_start();
		include $notice_template;
		$notice_html = ob_get_contents();
		ob_end_clean();
		$notice_html = '<div class="wc-zapier-notice wc-zapier-notice-' . esc_attr( $template_name ) . '">' . (string) $notice_html . '</div>';
		return $notice_html;
	}

	/**
	 * Executed whenever this notice is dismissed by the user.
	 * Override this function if specific things need to occur when this notice is dismissed.
	 *
	 * Note: The self::$has_dismiss_action variable must also be set to true in order for this function to be executed.
	 *
	 * @return void
	 */
	public function dismissed() {
	}

	/**
	 * Disable this notice, which stops it showing to all users.
	 *
	 * @return void
	 */
	public function disable() {
		WC_Admin_notices::remove_notice( $this->get_id() );
		$this->logger->debug( '%s admin notice disabled.', $this->get_id() );
	}
}
