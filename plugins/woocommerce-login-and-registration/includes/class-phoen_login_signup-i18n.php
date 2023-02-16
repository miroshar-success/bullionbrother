<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://phoeniixx.com/
 * @since      1.0.0
 *
 * @package    Phoen_login_signup
 * @subpackage Phoen_login_signup/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Phoen_login_signup
 * @subpackage Phoen_login_signup/includes
 * @author     phoeniixx <contact@phoeniixx.com>
 */
class Phoen_login_signup_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'phoen_login_signup',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
