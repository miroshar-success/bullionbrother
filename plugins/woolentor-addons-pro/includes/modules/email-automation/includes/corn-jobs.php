<?php
/**
 * Corn jobs.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Hooks.
add_action( 'wlea_scheduled_minute_tasks', 'wlea_scheduled_minute_tasks' );

// Scheduled minute tasks.
if ( ! function_exists( 'wlea_scheduled_minute_tasks' ) ) {
	/**
	 * Scheduled minute tasks.
	 */
	function wlea_scheduled_minute_tasks() {
		do_action( 'wlea_perform_minute_tasks' );
	}
}