<?php

namespace OM4\Zapier\Payload\Plugin\Subscription;

use OM4\Zapier\Payload\Order as OrderPayload;

defined( 'ABSPATH' ) || exit;

/**
 * Implement base structure requirements for Subscription Object.
 *
 * @deprecated 2.0.0
 */
class Subscription extends OrderPayload {

	/**
	 * Holds the type information for validate
	 *
	 * @var array
	 */
	protected static $property_types = array(
		'id'                      => 'int',
		'number'                  => 'string',
		'date'                    => 'string',
		'status'                  => 'string',
		'status_previous'         => 'string',
		'payment_method'          => 'string',
		'transaction_id'          => 'string',
		'view_url'                => 'string',
		'user_id'                 => 'int',
		'billing_first_name'      => 'string',
		'billing_last_name'       => 'string',
		'billing_company'         => 'string',
		'billing_email'           => 'string',
		'billing_phone'           => 'string',
		'billing_address'         => 'string',
		'billing_address_1'       => 'string',
		'billing_address_2'       => 'string',
		'billing_city'            => 'string',
		'billing_state'           => 'string',
		'billing_state_name'      => 'string',
		'billing_postcode'        => 'string',
		'billing_country'         => 'string',
		'billing_country_name'    => 'string',
		'shipping_first_name'     => 'string',
		'shipping_last_name'      => 'string',
		'shipping_company'        => 'string',
		'shipping_address'        => 'string',
		'shipping_address_1'      => 'string',
		'shipping_address_2'      => 'string',
		'shipping_city'           => 'string',
		'shipping_state'          => 'string',
		'shipping_state_name'     => 'string',
		'shipping_postcode'       => 'string',
		'shipping_country'        => 'string',
		'shipping_country_name'   => 'string',
		'currency'                => 'string',
		'currency_symbol'         => 'string',
		'item_count'              => 'int|double|string',
		'line_items'              => '\\OM4\\Zapier\\Payload\\Collection\\LineItems',
		'prices_include_tax'      => 'bool',
		'total'                   => 'string',
		'subtotal'                => 'string',
		'tax_total'               => 'string',
		'cart_discount'           => 'string',
		'discount_total'          => 'string',
		'coupons'                 => 'string',
		'shipping_total'          => 'string',
		'shipping_tax'            => 'string',
		'shipping_method'         => 'string',
		'has_downloadable_item'   => 'bool',
		'downloadable_files'      => '\\OM4\\Zapier\\Payload\\Collection\\DownloadableFiles',
		'customer_note'           => 'string',
		'notes'                   => '\\OM4\\Zapier\\Payload\\Collection\\Notes',
		'meta_data'               => '\\OM4\\Zapier\\Payload\\Item\\MetaData',
		'start_date'              => 'string',
		'trial_end_date'          => 'string',
		'next_payment_date'       => 'string',
		'end_date'                => 'string',
		'last_payment_date'       => 'string',
		'billing_period'          => 'string',
		'billing_interval'        => 'string',
		'completed_payment_count' => 'int',
		'failed_payment_count'    => 'int',
	);

	/**
	 * Subscription Start Date. The Subscription's Start Date.
	 *
	 * @var  string
	 */
	protected $start_date;

	/**
	 * Subscription Trial End Date. The Subscription's Trial End Date.
	 *
	 * @var  string
	 */
	protected $trial_end_date;

	/**
	 * Subscription Next Payment Date. The Subscription's Next Payment Date.
	 *
	 * @var  string
	 */
	protected $next_payment_date;

	/**
	 * Subscription End Date. The Subscription's End Date.
	 *
	 * @var  string
	 */
	protected $end_date;

	/**
	 * Subscription Last Payment Date. The Subscription's Last Payment Date.
	 *
	 * @var  string
	 */
	protected $last_payment_date;

	/**
	 * Billing Period. The Subscription's billing period (day/week/month/year).
	 *
	 * @var  string
	 */
	protected $billing_period;

	/**
	 * Billing Interval. The Subscription's billing interval.
	 *
	 * @var  string
	 */
	protected $billing_interval;

	/**
	 * Completed Payment Count. The number of payments completed for this subscription.
	 *
	 * @var  int
	 */
	protected $completed_payment_count;

	/**
	 * Failed Payment Count. The number of payments failed for this subscription.
	 *
	 * @var  int
	 */
	protected $failed_payment_count;
}
