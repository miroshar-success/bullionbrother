<?php
/**
 * Class for the Customizer
 *
 * @package Kadence
 */

namespace Kadence_Pro;

use function Kadence\kadence;

/**
 * Output header scripts.
 */
function header_scripts() {
	$script = kadence()->option( 'header_scripts' );
	if ( ! empty( $script ) ) {
		echo $script;
	}
}
add_action( 'wp_head', 'Kadence_Pro\header_scripts', 50 );

/**
 * Output after body scripts.
 */
function after_body_scripts() {
	$script = kadence()->option( 'after_body_scripts' );
	if ( ! empty( $script ) ) {
		echo $script;
	}
}
add_action( 'wp_body_open', 'Kadence_Pro\after_body_scripts', 50 );

/**
 * Output footer scripts.
 */
function footer_scripts() {
	$script = kadence()->option( 'footer_scripts' );
	if ( ! empty( $script ) ) {
		echo $script;
	}
}
add_action( 'wp_footer', 'Kadence_Pro\footer_scripts', 50 );
