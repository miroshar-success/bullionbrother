<?php
/*
 * Plugin Name: Checkout Field Editor and Manager for WooCommerce
 * Version: 2.2.8
 * Description: WooCommerce checkout field editor and manager helps to manage checkout fields in WooCommerce
 * Author: Acowebs
 * Author URI: http://acowebs.com
 * Requires at least: 4.0
 * Tested up to: 6.1
 * Text Domain: checkout-field-editor-and-manager-for-woocommerce
 * WC requires at least: 4.0.0
 * WC tested up to: 7.3
 */

define('AWCFE_TOKEN', 'awcfe');
define('AWCFE_VERSION', '2.2.8');
define('AWCFE_FILE', __FILE__);
define('AWCFE_EMPTY_LABEL', 'awcfe_empty_label');
define('AWCFE_ORDER_META_KEY', '_awcfe_order_meta_key');// use _ not show in backend
define('AWCFE_FIELDS_KEY', 'awcfe_fields');
define('AWCFE_PLUGIN_NAME', 'WooCommerce checkout field editor and manager');
define('AWCFE_STORE_URL', 'https://api.acowebs.com');

require_once(realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes/helpers.php');

if (!function_exists('awcfe_init')) {

    function awcfe_init()
    {
        $plugin_rel_path = basename(dirname(__FILE__)) . '/languages'; /* Relative to WP_PLUGIN_DIR */
        load_plugin_textdomain('checkout-field-editor-and-manager-for-woocommerce', false, $plugin_rel_path);
    }

}

if (!function_exists('awcfe_autoloader')) {

    function awcfe_autoloader($class_name)
    {
        if (0 === strpos($class_name, 'AWCFE')) {
            $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
            $class_file = 'class-' . str_replace('_', '-', strtolower($class_name)) . '.php';
            require_once $classes_dir . $class_file;
        }
    }

}

if (!function_exists('AWCFE')) {

    function AWCFE()
    {
        $instance = AWCFE_Backend::instance(__FILE__, AWCFE_VERSION);
        return $instance;
    }

}
add_action('plugins_loaded', 'awcfe_init');
spl_autoload_register('awcfe_autoloader');
if (is_admin()) {
    AWCFE();
}
new AWCFE_Api();

new AWCFE_Front_End(__FILE__, AWCFE_VERSION);
