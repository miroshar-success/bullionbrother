<?php
/**
* Plugin Name: Login/Signup Popup
* Plugin URI: http://xootix.com/easy-login-for-woocommerce
* Author: XootiX
* Version: 2.3
* Text Domain: easy-login-woocommerce
* Domain Path: /languages
* Author URI: http://xootix.com
* Description: Allow users to login/signup using interactive popup design.
* Tags: login, signup, register, woocommerce, popup
*/


//Exit if accessed directly
if( !defined( 'ABSPATH' ) ){
	return;
}


define( 'XOO_EL', true);
define( 'XOO_EL_PLUGIN_FILE', __FILE__ );


if ( ! class_exists( 'Xoo_El_Core' ) ) {
	require_once 'includes/class-xoo-el-core.php';
}

if( !function_exists( 'xoo_el' ) ){
	function xoo_el(){
		
		do_action('xoo_el_before_plugin_activation');

		return Xoo_El_Core::get_instance();
		
	}
}
add_action( 'plugins_loaded', 'xoo_el', 8 );

function xoo_el_deactivate_free(){
	deactivate_plugins( 'easy-login-woocommerce/xoo-el-main.php' );
}
register_activation_hook( __FILE__, 'xoo_el_deactivate_free' );