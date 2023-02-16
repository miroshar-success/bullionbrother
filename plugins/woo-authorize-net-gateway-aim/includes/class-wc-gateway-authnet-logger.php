<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Authnet logging class which saves important data to the log
 *
 * @since 2.6.10
 */
class WC_Authnet_Logger {

	public static $logger;

	/**
	 * What rolls down stairs
	 * alone or in pairs,
	 * and over your neighbor's dog?
	 * What's great for a snack,
	 * And fits on your back?
	 * It's log, log, log
	 *
	 * @since 2.6.10
	 */
	public static function log( $message ) {

		if ( ! class_exists( 'WC_Logger' ) ) {
			return;
 		}

		if ( empty( self::$logger ) ) {
			self::$logger = wc_get_logger();
		}

		self::$logger->debug( $message, array( 'source' => 'woocommerce-gateway-authnet' ) );

	}
}

new WC_Authnet_Logger();
