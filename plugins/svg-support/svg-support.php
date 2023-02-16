<?php
/*
Plugin Name: 	SVG Support
Plugin URI:		http://wordpress.org/plugins/svg-support/
Description: 	Upload SVG files to the Media Library and render SVG files inline for direct styling/animation of an SVG's internal elements using CSS/JS.
Version: 		2.5.5
Author: 		Benbodhi
Author URI: 	https://benbodhi.com
Text Domain: 	svg-support
Domain Path:	/languages
License: 		GPLv2 or later
License URI:	http://www.gnu.org/licenses/gpl-2.0.html

	Copyright 2013 and beyond | Benbodhi (email : wp@benbodhi.com)

*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Global variables
 */
global $bodhi_svgs_options;
$bodhi_svgs_options = array();										// Defining global array
$svgs_plugin_version = '2.5.5';										// for use on admin pages
$plugin_file = plugin_basename(__FILE__);							// plugin file for reference
define( 'BODHI_SVGS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );	// define the absolute plugin path for includes
define( 'BODHI_SVGS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );		// define the plugin url for use in enqueue
$bodhi_svgs_options = get_option('bodhi_svgs_settings');			// retrieve our plugin settings from the options table

/*
 * SVG Sanitizer class
 */
use enshrined\svgSanitize\Sanitizer;								// init svg sanitizer for usage

if ( ( !empty($bodhi_svgs_options['sanitize_svg']) && $bodhi_svgs_options['sanitize_svg'] === 'on' ) || ( !empty($bodhi_svgs_options['minify_svg']) && $bodhi_svgs_options['minify_svg'] === 'on' ) ) {

	include( BODHI_SVGS_PLUGIN_PATH . 'vendor/autoload.php' );		// svg sanitizer

	// interfaces to enable custom whitelisting of svg tags and attributes
	include( BODHI_SVGS_PLUGIN_PATH . 'includes/svg-tags.php' );
	include( BODHI_SVGS_PLUGIN_PATH . 'includes/svg-attributes.php' );

	$sanitizer = new Sanitizer();									// initialize if enabled

}

/**
 * Includes - keeping it modular
 */
include( BODHI_SVGS_PLUGIN_PATH . 'admin/admin-init.php' );					// initialize admin menu & settings page
include( BODHI_SVGS_PLUGIN_PATH . 'admin/plugin-action-meta-links.php' );	// add links to the plugin on the plugins page
include( BODHI_SVGS_PLUGIN_PATH . 'functions/mime-types.php' );				// setup mime types support for SVG (with fix for WP 4.7.1 - 4.7.2)
include( BODHI_SVGS_PLUGIN_PATH . 'functions/thumbnail-display.php' );		// make SVG thumbnails display correctly in media library
include( BODHI_SVGS_PLUGIN_PATH . 'functions/attachment.php' );				// make SVG thumbnails display correctly in attachment modals and generate attachment sizes
include( BODHI_SVGS_PLUGIN_PATH . 'functions/enqueue.php' );				// enqueue js & css for inline replacement & admin
include( BODHI_SVGS_PLUGIN_PATH . 'functions/localization.php' );			// setup localization & languages
include( BODHI_SVGS_PLUGIN_PATH . 'functions/attribute-control.php' );		// auto set SVG class & remove dimensions during insertion
include( BODHI_SVGS_PLUGIN_PATH . 'functions/featured-image.php' );			// allow inline SVG for featured images

/**
 * Version based conditional / Check for stored plugin version
 *
 * Versions prior to 2.3 did not store the version number,
 * If no version number is stored, store current plugin version number.
 * If there is a version number stored, update it with the new version number.
 */
// get the stored plugin version
$svgs_plugin_version_stored = get_option( 'bodhi_svgs_plugin_version' );
// only run this if there is no stored version number (have never stored the number in previous versions)
if ( empty( $svgs_plugin_version_stored ) ) {

	// add plugin version number to options table
	update_option( 'bodhi_svgs_plugin_version', $svgs_plugin_version );

} else {

	// update plugin version number in options table
	update_option( 'bodhi_svgs_plugin_version', $svgs_plugin_version );

}

/**
 * Defaults for better security in versions >= 2.5
 */
// Enable 'sanitize_svg_front_end' by default
if ( !isset($bodhi_svgs_options['sanitize_svg_front_end']) ) {
	$bodhi_svgs_options['sanitize_svg_front_end'] = 'on';
	update_option( 'bodhi_svgs_settings', $bodhi_svgs_options );
}

// Allow only admins to upload SVGs by default
if ( !isset($bodhi_svgs_options['restrict']) || $bodhi_svgs_options['restrict'] == "on" ) {
	$bodhi_svgs_options['restrict'] = array('administrator');
	update_option( 'bodhi_svgs_settings', $bodhi_svgs_options );
}
elseif (isset($bodhi_svgs_options['restrict']) && $bodhi_svgs_options['restrict'] == "none" ) {
	$bodhi_svgs_options['restrict'] = array("none");
	update_option( 'bodhi_svgs_settings', $bodhi_svgs_options );
}

// By default turn on "Sanitize SVG while uploading" option
if ( !isset($bodhi_svgs_options['sanitize_svg']) ) {
	$bodhi_svgs_options['sanitize_svg'] = "on";
	update_option( 'bodhi_svgs_settings', $bodhi_svgs_options );
}

// By default sanitize on upload for everyone except administrator and editor roles
if ( !isset($bodhi_svgs_options['sanitize_on_upload_roles']) ) {
	$bodhi_svgs_options['sanitize_on_upload_roles'] = array('administrator', 'editor');
	update_option( 'bodhi_svgs_settings', $bodhi_svgs_options );
}
elseif ( isset($bodhi_svgs_options['sanitize_on_upload_roles']) && $bodhi_svgs_options['sanitize_on_upload_roles'] == "none") {
	$bodhi_svgs_options['sanitize_on_upload_roles'] = array("none");
	update_option( 'bodhi_svgs_settings', $bodhi_svgs_options );
}
