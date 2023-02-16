<?php

namespace OM4\Zapier\Trigger\Subscription;

use OM4\Zapier\Trigger\Subscription\Base;

defined( 'ABSPATH' ) || exit;

/**
 * Describe New Subscription Trigger
 *
 * @deprecated 2.0.0
 */
class NewSubscription extends Base {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->trigger_title = __( 'Subscription Created', 'woocommerce-zapier' );

		$this->trigger_description = __( 'Triggers when a subscription is created.', 'woocommerce-zapier' );

		// Prefix the trigger key with wc. to denote that this is a trigger that relates to a WooCommerce order.
		$this->trigger_key = 'wc.new_subscription';

		$this->sort_order = 4;

		$this->actions['wcs_create_subscription'] = 1;

		parent::__construct();
	}
}
