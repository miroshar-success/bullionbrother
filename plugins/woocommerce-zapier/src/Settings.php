<?php

namespace OM4\WooCommerceZapier;

defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce Zapier Settings.
 * Also provides helper methods to determine if detailed logging is enabled.
 *
 * @since 2.0.0
 */
class Settings {

	/**
	 * Prefix for option/settings when stored in the database.
	 *
	 * @var string
	 */
	protected $prefix = 'wc_zapier_';

	/**
	 * Instructs the functionality to initialise itself.
	 *
	 * @return void
	 */
	public function initialise() {
		add_filter(
			'woocommerce_settings_tabs_array',
			function ( $tabs ) {
				$tabs['wc_zapier'] = __( 'Zapier', 'woocommerce-zapier' );
				return $tabs;
			},
			29
		);

		add_action(
			'woocommerce_settings_tabs_wc_zapier',
			array( $this, 'output_settings_tab' )
		);

		add_action(
			'woocommerce_update_options_wc_zapier',
			array( $this, 'process_admin_options' )
		);
	}

	/**
	 * Save settings when the user submits the form.
	 *
	 * @return void
	 */
	public function process_admin_options() {
		woocommerce_update_options( $this->get_settings_for_settings_screen() );
	}

	/**
	 * Output the settings tab/screen.
	 *
	 * @return void
	 */
	public function output_settings_tab() {
		woocommerce_admin_fields( $this->get_settings_for_settings_screen() );
	}

	/**
	 * Tells WooCommerce which settings to display on our Settings screen.
	 *
	 * @return array
	 */
	public function get_settings_for_settings_screen() {
		return array(
			'section_title' => array(
				'name' => __( 'WooCommerce Zapier', 'woocommerce-zapier' ),
				'type' => 'title',
				'desc' => '<p>' . __( 'Configure your WooCommerce Zapier integration here.', 'woocommerce-zapier' ) . '</p>',
				'id'   => $this->prefix . 'section_title',
			),
			'debug'         => array(
				'name'     => __( 'Logging', 'woocommerce-zapier' ),
				'desc'     => __( 'Enable Detailed Logging', 'woocommerce-zapier' ),
				'desc_tip' => __( 'By default, only unexpected errors are logged.<br />Enabling this option will log additional information that can assist in troubleshooting problems or issues.', 'woocommerce-zapier' ),
				'type'     => 'checkbox',
				'id'       => $this->prefix . 'debug',
			),
			'section_end'   => array(
				'type' => 'sectionend',
				'id'   => $this->prefix . 'section_title_end',
			),
		);
	}

	/**
	 * Get the specified setting/option from the database.
	 *
	 * @param string $setting_name Setting name (with no prefix).
	 *
	 * @return mixed
	 */
	public function get_setting( $setting_name ) {
		$value = get_option( $this->prefix . $setting_name );
		if ( '1' === $value || 'yes' === $value ) {
			return true;
		}
		if ( '0' === $value || 'no' === $value ) {
			return false;
		}
		return $value;
	}

	/**
	 * Set the specified setting/option.
	 *
	 * @param string $setting_name Setting name (with no prefix).
	 * @param mixed  $value Setting value.
	 *
	 * @return mixed
	 */
	public function set_setting( $setting_name, $value ) {
		update_option( $this->prefix . $setting_name, $value );
	}

	/**
	 * Delete the specified setting/option.
	 *
	 * @param string $setting_name Setting name (with no prefix).
	 *
	 * @return void
	 */
	public function delete_setting( $setting_name ) {
		delete_option( $this->prefix . $setting_name );
	}

	/**
	 * Whether or not detailed logging is enabled.
	 *
	 * @return bool
	 */
	public function is_detailed_logging_enabled() {
		return $this->get_setting( 'debug' );
	}

	/**
	 * Enable detailed logging.
	 *
	 * @return void
	 */
	public function set_detailed_logging_enabled() {
		$this->set_setting( 'debug', 'yes' );
	}

	/**
	 * Whether or not to the 1.9.x Legacy Zapier Feed functionality is enabled.
	 *
	 * @return bool
	 */
	public function is_legacy_mode_enabled() {
		if ( true === $this->get_setting( 'legacy_mode_enabled' ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Enable Legacy Mode.
	 *
	 * @return void
	 */
	public function set_legacy_mode_enabled() {
		$this->set_setting( 'legacy_mode_enabled', true );
	}

	/**
	 * Disable Legacy Mode.
	 *
	 * @return void
	 */
	public function set_legacy_mode_disabled() {
		$this->delete_setting( 'legacy_mode_enabled' );
	}

	/**
	 * Get the full URL to the plugin's settings page.
	 *
	 * @return string
	 */
	public function get_settings_page_url() {
		return add_query_arg(
			array(
				'page' => 'wc-settings',
				'tab'  => 'wc_zapier',
			),
			admin_url( 'admin.php' )
		);
	}
}
