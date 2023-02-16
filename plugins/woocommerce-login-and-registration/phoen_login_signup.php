<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://phoeniixx.com/
 * @since             1.0.0
 * @package           Phoen_login_signup
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Login / Sign-up Lite
 * Plugin URI:        https://www.phoeniixx.com/product/woocommerce-login-signup/
 * Description:       With this free Sign Up/ Login plugin, you can easily create a sign up and login process for your ecommerce site.
 * Version:           4.0
 * Author:            phoeniixx
 * Author URI:        http://phoeniixx.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       phoen_login_signup
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PHOEN_LOGIN_SIGNUP_VERSION', '1.0.0' );
define('PLUGINlSPDIRURL',plugin_dir_url( __FILE__ ));
define('PLUGINlSPDIRPATH',plugin_dir_path( __FILE__ ));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-phoen_login_signup-activator.php
 */
function activate_phoen_login_signup() {

	if(phoen_login_signup_is_woocommerce_plugin_activate(true)){
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-phoen_login_signup-activator.php';
		Phoen_login_signup_Activator::activate();
        Phoen_login_signup_Activator::phoen_add_by_default_login_data();
        Phoen_login_signup_Activator::phoen_add_by_default_register_data();
	}
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-phoen_login_signup-deactivator.php
 */
function deactivate_phoen_login_signup() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-phoen_login_signup-deactivator.php';
	Phoen_login_signup_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_phoen_login_signup' );
register_deactivation_hook( __FILE__, 'deactivate_phoen_login_signup' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-phoen_login_signup.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_phoen_login_signup() {

	if(!phoen_login_signup_dependencies()){

		return false;
	}

	$plugin = new Phoen_login_signup();
	$plugin->run();

}
run_phoen_login_signup();

function phoen_login_signup_deactivate(){

	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	deactivate_plugins(plugin_basename(__FILE__));
}

function phoen_login_signup_is_woocommerce_plugin_activate($message){

	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

		return true;

	}else{

		if (in_array( plugin_basename(__FILE__), apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || $message) {

			add_action('admin_notices',function() {
						echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Woocommerce custom mail requires the WooCommerce plugin to be installed and active. You can download %s here.', 'woocommerce-services' ), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
					}
				);

			phoen_login_signup_deactivate();
		}

		return false;
	}
}

function phoen_login_signup_dependencies(){

	return phoen_login_signup_is_woocommerce_plugin_activate(false);
}