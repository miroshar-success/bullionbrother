<?php

namespace OM4\Zapier\Plugin;

use OM4\WooCommerceZapier\Plugin\Subscriptions\Plugin;
use OM4\Zapier\Payload\Plugin\Subscription\Order as Payload;
use OM4\Zapier\Trigger\Base;
use WC_Subscriptions;

defined( 'ABSPATH' ) || exit;

/**
 * Functionality that is enabled when the WooCommerce Subscriptions plugin is active.
 * Plugin URL: https://woocommerce.com/products/woocommerce-subscriptions/
 *
 * @deprecated 2.0.0
 */
class Subscriptions {

	/**
	 * Trigger keys that the subscriptions data should be added to.
	 *
	 * @var array
	 */
	private $trigger_keys = array(
		// New Order.
		'wc.new_order',
		// New Order Status Change.
		'wc.order_status_change',
	);

	/**
	 * Constructor
	 */
	public function __construct() {

		// Version check.
		if ( ! $this->is_available() ) {
			// Logging and displaying notice handled in OM4\WooCommerceZapier\Plugin\Subscriptions\Plugin.
			return;
		}

		foreach ( $this->trigger_keys as $trigger_key ) {
			add_filter( "wc_zapier_data_{$trigger_key}", array( $this, 'order_data_override' ), 10, 2 );
		}
	}

	/**
	 * Whether or not the WC Subscriptions plugin is active, and running a version newer than our minimum supported version.
	 *
	 * @return bool
	 */
	public static function is_available() {
		return class_exists( 'WC_Subscriptions' ) && version_compare( WC_Subscriptions::$version, Plugin::MINIMUM_SUPPORTED_VERSION, '>=' );
	}

	/**
	 * Load Subscriptions-related Triggers from the subscriptions sub directory.
	 *
	 * @deprecated 2.0.0
	 *
	 * @param array $directories The array of directories to scan for PHP files.
	 *
	 * @return array
	 */
	public function wc_zapier_trigger_directories( $directories ) {
		_deprecated_function( 'OM4\Zapier\Plugin\Subscriptions::wc_zapier_trigger_directories', '2.0' );
		return $directories;
	}

	/**
	 * When sending WooCommerce Order data to Zapier, also send any additional WC subscriptions fields.
	 *
	 * @param array                   $order_data Order data that will be overridden.
	 * @param OM4\Zapier\Trigger\Base $trigger    Trigger that initiated the data send.
	 *
	 * @return array
	 */
	public function order_data_override( $order_data, Base $trigger ) {
		if ( $trigger->is_sample() ) {
			$payload = Payload::from_sample();
		} else {
			// Sending live data.
			$payload    = new Payload();
			$renewal_id = get_post_meta( $order_data['id'], '_subscription_renewal', true );
			if ( ! empty( $renewal_id ) ) {
				$payload->is_subscription_renewal = true;
				$payload->subscription_id         = (int) $renewal_id;
			} else {
				$payload->is_subscription_renewal = false;
				$payload->subscription_id         = 0;
			}
		}

		return $order_data + $payload->to_array();
	}
}
