<?php 
/*
Plugin Name: Advanced Woocommerce Product Gallery Slider
Plugin URI: https://wordpress.org/plugins/advanced-woo-product-gallery-images-slider/
Description: Instantly transform the gallery on your WooCommerce Product page into a fully Responsive Stunning Carousel Slider.
Author: UnikInfotech
Version: 1.0.0
Author URI: http://www.unikinfotech.in
License: GPL2
-------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

include_once('inc/settings.php');    // Include required settings

/**
 * Include JS Files 
 */
function wpgis_enqueue_scripts() {
	if (!is_admin()) {	
		if ( class_exists( 'WooCommerce' ) && is_product() ) {
			wp_enqueue_script('wpgis-slick-js', plugins_url('assets/js/slick.min.js', __FILE__),array('jquery'),'1.6.0', false);
			wp_enqueue_script('wpgis-fancybox-js', plugins_url('assets/js/jquery.fancybox.js', __FILE__),array('jquery'),'1.0', true);
			wp_enqueue_script('wpgis-zoom-js', plugins_url('assets/js/jquery.zoom.min.js', __FILE__),array('jquery'),'1.0', true);
			wp_enqueue_style('wpgis-fancybox-css', plugins_url('assets/css/fancybox.css', __FILE__),'1.0', true);
			wp_enqueue_style('wpgis-fontawesome-css', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css','1.0', true);
			wp_enqueue_style('wpgis-front-css', plugins_url('assets/css/wpgis-front.css', __FILE__),'1.0', true);
			wp_register_script('wpgis-front-js', plugins_url('assets/js/wpgis.front.js', __FILE__),array('jquery'),'1.0', true);
			wp_enqueue_style( 'dashicons');
			
			$options = get_option('wpgis_options');
			
			$translation_array = array(
				'wpgis_slider_layout'   => $options['slider_layout'],
				'wpgis_slidetoshow'  => $options['slidetoshow'],
				'wpgis_slidetoscroll'    => $options['slidetoscroll'],
				'wpgis_sliderautoplay'   => $options['sliderautoplay'],
				'wpgis_arrowdisable'=> $options['arrowdisable'],
				'wpgis_arrowinfinite'=> $options['arrowinfinite'],
				'wpgis_arrowcolor'=> $options['arrowcolor'],
				'wpgis_arrowbgcolor'=> $options['arrowbgcolor'],
				'wpgis_show_lightbox'=> $options['show_lightbox'],
				'wpgis_show_zoom'=> $options['show_zoom'],
			);
			
			wp_localize_script( 'wpgis-front-js', 'object_name', $translation_array );
			
			// Enqueued script with localized data.
			wp_enqueue_script( 'wpgis-front-js' );
			
		}
	}
}
add_action( 'wp_enqueue_scripts', 'wpgis_enqueue_scripts' ); 

add_action( 'admin_enqueue_scripts', 'wpgis_enqueue_color_picker' );
function wpgis_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
	
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wpgis-script-handle', plugins_url('assets/js/wpgis-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

// Call plugin loaded action
add_action('plugins_loaded','wpgis_remove_woo_hooks');
function wpgis_remove_woo_hooks() {
	remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
	add_action( 'woocommerce_product_thumbnails', 'wpgis_show_product_thumbnails', 20 );
	add_action( 'woocommerce_before_single_product_summary', 'wpgis_show_product_image', 10 ); 
	wpgis_plugin_settings();
}

// Single Product Image
function wpgis_show_product_image() {
	// Woocmmerce 3.0+ Slider Fix 
	require_once 'inc/product-image.php';
}

// Single Product Thumbnails 
function wpgis_show_product_thumbnails() {
	// Woocmmerce 3.0+ Slider Fix 
	require_once 'inc/product-thumbnails.php';	
}

// get plugin version
function wpgis_get_version(){
	if ( ! function_exists( 'get_plugins' ) )
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$plugin_file = basename( ( __FILE__ ) );
	return $plugin_folder[$plugin_file]['Version'];
}