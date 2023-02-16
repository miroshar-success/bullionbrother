<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wpswings.com
 * @since      1.0.0
 *
 * @package    Ultimate Woocommerce Gift Cards
 * @subpackage Ultimate Woocommerce Gift Cards/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ultimate Woocommerce Gift Cards
 * @subpackage Ultimate Woocommerce Gift Cards/includes
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Ultimate_Woocommerce_Gift_Card_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'giftware',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
