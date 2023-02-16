<?php

use OM4\WooCommerceZapier\ContainerService;
use OM4\WooCommerceZapier\Plugin;

defined( 'ABSPATH' ) || exit;

/*
Plugin Name: WooCommerce Zapier
Plugin URI: https://woocommerce.com/products/woocommerce-zapier/
Description: Integrates WooCommerce with <a href="https://zapier.com/" target="_blank">Zapier</a>. Send WooCommerce data to 5000+ cloud services. Create or update WooCommerce data from 5000+ cloud services via Zaps.
Version: 2.3.1
Author: OM4 Software
Author URI: https://om4.io/
Text Domain: woocommerce-zapier
Domain Path: /languages/
Woo: 243589:0782bdbe932c00f4978850268c6cfe40
WC requires at least: 4.2
WC tested up to: 7.0
*/

/*
Copyright 2013-2022 OM4 (email: plugins@om4.io    web: https://om4.io/)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define( 'WC_ZAPIER_PLUGIN_FILE', __FILE__ );
define( 'WC_ZAPIER_MINIMUM_SUPPORTED_PHP_VERSION', '7.2.0' );

/**
 * Displays a message if PHP version isn't supported.
 *
 * @return void
 */
function wc_zapier_incompatible_php_version_admin_notice() {
	$class = 'notice notice-error';
	// Translators: 1: Minimum supported PHP Version. 2: Currently running PHP version.
	$message = __( 'WooCommerce Zapier is disabled because it is only compatible with PHP version %1$s or later. Please contact your web host to upgrade from PHP version %2$s to a newer version. We recommend using PHP 7.4 or greater.', 'woocommerce-zapier' );
	$message = sprintf( $message, WC_ZAPIER_MINIMUM_SUPPORTED_PHP_VERSION, PHP_VERSION );
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}

/**
 * Check PHP compatibility.
 */
if ( version_compare( PHP_VERSION, WC_ZAPIER_MINIMUM_SUPPORTED_PHP_VERSION, '<=' ) ) {
	add_action( 'admin_notices', 'wc_zapier_incompatible_php_version_admin_notice' );
	return;
}
require_once 'autoload.php';

// Initialise plugin during the `plugins_loaded` hook.
add_action(
	'plugins_loaded',
	function() {
		( new ContainerService() )->get( Plugin::class )->plugins_loaded();
	}
);
