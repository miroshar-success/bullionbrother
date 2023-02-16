<?php
/**
 * Custom Payment Gateways for WooCommerce - Functions
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Imaginate Solutions
 * @package cpgw
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'alg_wc_custom_payment_gateways_get_order_statuses' ) ) {
	/**
	 * Get Woo Order Statuses.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wc_custom_payment_gateways_get_order_statuses() {
		$result   = array();
		$statuses = function_exists( 'wc_get_order_statuses' ) ? wc_get_order_statuses() : array();
		foreach ( $statuses as $status => $status_name ) {
			$result[ substr( $status, 3 ) ] = $statuses[ $status ];
		}
		return $result;
	}
}
