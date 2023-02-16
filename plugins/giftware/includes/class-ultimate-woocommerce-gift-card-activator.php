<?php
/**
 * Fired during plugin activation
 *
 * @link       https://wpswings.com
 * @since      1.0.0
 *
 * @package    Ultimate Woocommerce Gift Cards
 * @subpackage Ultimate Woocommerce Gift Cards/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ultimate Woocommerce Gift Cards
 * @subpackage Ultimate Woocommerce Gift Cards/includes
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Ultimate_Woocommerce_Gift_Card_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$timestamp = get_option( 'wps_gw_lcns_thirty_days', 'not_set' );
		if ( 'not_set' === $timestamp ) {

			$current_time = current_time( 'timestamp' );

			$thirty_days = strtotime( '+30 days', $current_time );

			update_option( 'wps_gw_lcns_thirty_days', $thirty_days );
		}
		if ( ! wp_next_scheduled( 'wps_gw_license_daily' ) ) {
			wp_schedule_event( time(), 'daily', 'wps_gw_license_daily' );
		}
	}


}

