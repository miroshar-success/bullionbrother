<?php
/**
  Plugin Name: File Manager Advanced
  Plugin URI: https://wordpress.org/plugins/file-manager-advanced
  Description: Cpanel for files management in wordpress
  Author: modalweb
  Version: 5.0.1
  Author URI: https://advancedfilemanager.com
  License: GPLv2
**/
define('FMAFILEPATH', plugin_dir_path( __FILE__ ));
/**
 * Advanced File Manager
 * Text Domain
 */
add_action('plugins_loaded', 'advanced_file_manager_load_text_domain');
function advanced_file_manager_load_text_domain()
{
    $domain = dirname(plugin_basename(__FILE__));
    $locale = apply_filters('plugin_locale', get_locale(), $domain);
    load_textdomain($domain, trailingslashit(WP_LANG_DIR).'plugins'.'/'.$domain.'-'.$locale.'.mo');
    load_plugin_textdomain($domain, false, basename(dirname(__FILE__)).'/languages/');
}
/**
 * Main application
 */
if(is_admin()) {
	include('application/class_fma_main.php');
	new class_fma_main;
}
/**
 * Shortcode class
 */
include('application/class_fma_shortcode.php');
new class_fma_shortcode;