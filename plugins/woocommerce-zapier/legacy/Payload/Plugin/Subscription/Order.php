<?php

namespace OM4\Zapier\Payload\Plugin\Subscription;

use OM4\Zapier\Payload\Base\Item;

defined( 'ABSPATH' ) || exit;

/**
 * Implement additional structure requirements for Order Object if Subscription
 * plugin is active.
 *
 * @deprecated 2.0.0
 */
class Order extends Item {

	/**
	 * Holds the type information for validate
	 *
	 * @var array
	 */
	protected static $property_types = array(
		'subscription_id'         => 'int',
		'is_subscription_renewal' => 'bool',
	);

	/**
	 * The Subscription ID that this order relates to. Empty if the order doesn't relate to a subscription.
	 *
	 * @var  int
	 */
	protected $subscription_id;

	/**
	 * Is Subscription Renewal?. Whether or not this order is a subscription renewal order. (if the
	 *   WooCommerce Subscriptions Extension is active)
	 *
	 * @since  1.6.0
	 * @var  bool
	 */
	protected $is_subscription_renewal;
}
