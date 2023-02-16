<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Post Type Functions
 *
 * Handles all custom post types
 * functions
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */

/**
 * Register Post Type
 *
 * Handles to registers the Points Logs 
 * post type
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */
function woo_pr_register_post_types() {

	// register WooCommerce Points Logs post type
	$points_log_labels = array(
		'name'					=> esc_html__( 'Points Logs', 'woopoints' ),
		'singular_name'			=> esc_html__( 'Points Log', 'woopoints' ),
		'add_new'				=> esc_html_x( 'Add New', WOO_POINTS_LOG_POST_TYPE, 'woopoints' ),
		'add_new_item'			=> sprintf( esc_html__( 'Add New %s' , 'woopoints' ), esc_html__( 'Points Log' , 'woopoints' ) ),
		'edit_item'				=> sprintf( esc_html__( 'Edit %s' , 'woopoints' ), esc_html__( 'Points Log' , 'woopoints' ) ),
		'new_item'				=> sprintf( esc_html__( 'New %s' , 'woopoints' ), esc_html__( 'Points Log' , 'woopoints' ) ),
		'all_items'				=> sprintf( esc_html__( '%s' , 'woopoints' ), esc_html__( 'Points Logs' , 'woopoints' ) ),
		'view_item'				=> sprintf( esc_html__( 'View %s' , 'woopoints' ), esc_html__( 'Points Log' , 'woopoints' ) ),
		'search_items'			=> sprintf( esc_html__( 'Search %s' , 'woopoints' ), esc_html__( 'Points Logs' , 'woopoints' ) ),
		'not_found'				=> sprintf( esc_html__( 'No %s Found' , 'woopoints' ), esc_html__( 'Points Logs' , 'woopoints' ) ),
		'not_found_in_trash'	=> sprintf( esc_html__( 'No %s Found In Trash' , 'woopoints' ), esc_html__( 'Points Logs' , 'woopoints' ) ),
		'parent_item_colon'		=> '',
		'menu_name' 			=> esc_html__( 'Points Logs' , 'woopoints' )
	);

	$points_log_args = array(
		'labels'				=> $points_log_labels,
		'public' 				=> false,
	    'exclude_from_search'	=> true,
	    'query_var' 			=> false,
	    'rewrite' 				=> false,
	    'capability_type' 		=> WOO_POINTS_LOG_POST_TYPE,
	    'hierarchical' 			=> false,
	    'supports' 				=> array( 'title' )
	);
	
	// finally register post type
	register_post_type( WOO_POINTS_LOG_POST_TYPE, $points_log_args );
}
add_action( 'init', 'woo_pr_register_post_types' );
