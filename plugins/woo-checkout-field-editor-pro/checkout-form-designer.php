<?php
/**
 * Plugin Name: Checkout Field Editor for WooCommerce
 * Description: Customize WooCommerce checkout fields(Add, Edit, Delete and re-arrange fields).
 * Author:      ThemeHigh
 * Version:     1.8.1
 * Author URI:  https://www.themehigh.com
 * Plugin URI:  https://www.themehigh.com
 * Text Domain: woo-checkout-field-editor-pro
 * Domain Path: /languages
 * WC requires at least: 3.0.0
 * WC tested up to: 7.2
 */
 
if(!defined( 'ABSPATH' )) exit;

if (!function_exists('is_woocommerce_active')){
	function is_woocommerce_active(){
	    $active_plugins = (array) get_option('active_plugins', array());
	    if(is_multisite()){
		   $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
	    }
	    return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins) || class_exists('WooCommerce');
	}
}

if(is_woocommerce_active()) {
	define('THWCFD_VERSION', '1.8.1');
	!defined('THWCFD_BASE_NAME') && define('THWCFD_BASE_NAME', plugin_basename( __FILE__ ));
	!defined('THWCFD_PATH') && define('THWCFD_PATH', plugin_dir_path( __FILE__ ));
	!defined('THWCFD_URL') && define('THWCFD_URL', plugins_url( '/', __FILE__ ));

	#require THWCFD_PATH . 'classes/class-thwcfd.php';
	require plugin_dir_path( __FILE__ ) . 'includes/class-thwcfd.php';

	function run_thwcfd() {
		$plugin = new THWCFD();
	}
	run_thwcfd();
}
