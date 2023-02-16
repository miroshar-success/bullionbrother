<?php

namespace OM4\Zapier\Trigger\Order;

use OM4\Zapier\Feed\Feed;
use OM4\Zapier\Trigger\Order\Base;

defined( 'ABSPATH' ) || exit;

/**
 * Describe Order Status change Trigger
 *
 * @deprecated 2.0.0
 */
class StatusChange extends Base {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Prefix the trigger key with wc. to denote that this is a trigger that relates to a WooCommerce order.
		$this->trigger_key = 'wc.order_status_change';

		$this->sort_order = 3;

		$this->trigger_title = __( 'New Order Status Change', 'woocommerce-zapier' );

		// Translators: %1$s: URL of the Advanced usage in the WooCommerce Zapier documentation.
		$this->trigger_description = sprintf( __( 'Advanced: triggers every time an order changes status.<br />Consider using with a Filter.<br />See the <a href="%1$s" target="_blank">Advanced Zaps documentation</a> for more information.', 'woocommerce-zapier' ), 'https://docs.om4.io/woocommerce-zapier/legacy/#advanced-zaps' );

		// This hook accepts 3 parameters, and we need all of them.
		// The first parameter is the Order ID (an integer).
		// The second parameter is the old/previous status (a string).
		// The third parameter is the new status (a string).
		$this->actions['woocommerce_order_status_changed'] = 3;

		parent::__construct();
	}

	/**
	 * After sending order data to Zapier, log the previous and new order status as part of the order note.
	 *
	 * @param Feed   $feed         Feed data.
	 * @param array  $result       Response from the wp_remote_post() call.
	 * @param string $action_name  Hook/action name (needed to be able to retry failed attempts).
	 * @param array  $arguments    Hook/action arguments (needed to be able to retry failed attempts).
	 * @param int    $num_attempts The number of attempts it took to successfully send the data to Zapier.
	 *
	 * @return string
	 */
	protected function data_sent_note_suffix( Feed $feed, $result, $action_name, $arguments, $num_attempts = 0 ) {
		if ( isset( $arguments[1] ) && isset( $arguments[2] ) ) {
			$previous_status = $arguments[1];
			$new_status      = $arguments[2];
			return "<br />(<small>$previous_status &rarr; $new_status</small>)";
		}
	}
}
