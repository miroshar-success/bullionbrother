<?php

namespace OM4\Zapier\Payload;

use OM4\Zapier\Payload\Base\Item;
use OM4\Zapier\Payload\Item\BillingTrait;
use OM4\Zapier\Payload\Item\ShippingTrait;

defined( 'ABSPATH' ) || exit;

/**
 * Implement base structure requirements for Customer Object.
 *
 * @deprecated 2.0.0
 */
class Customer extends Item {

	/**
	 * Holds the type information for validate
	 *
	 * @var array
	 */
	protected static $property_types = array(
		'id'                    => 'int',
		'first_name'            => 'string',
		'last_name'             => 'string',
		'email_address'         => 'string',
		'username'              => 'string',
		'paying_customer'       => 'bool',
		'billing_first_name'    => 'string',
		'billing_last_name'     => 'string',
		'billing_company'       => 'string',
		'billing_email'         => 'string',
		'billing_phone'         => 'string',
		'billing_address'       => 'string',
		'billing_address_1'     => 'string',
		'billing_address_2'     => 'string',
		'billing_city'          => 'string',
		'billing_state'         => 'string',
		'billing_state_name'    => 'string',
		'billing_postcode'      => 'string',
		'billing_country'       => 'string',
		'billing_country_name'  => 'string',
		'shipping_first_name'   => 'string',
		'shipping_last_name'    => 'string',
		'shipping_company'      => 'string',
		'shipping_address'      => 'string',
		'shipping_address_1'    => 'string',
		'shipping_address_2'    => 'string',
		'shipping_city'         => 'string',
		'shipping_state'        => 'string',
		'shipping_state_name'   => 'string',
		'shipping_postcode'     => 'string',
		'shipping_country'      => 'string',
		'shipping_country_name' => 'string',
	);

	/**
	 * Object ID.
	 *
	 * @var  int
	 */
	protected $id;

	/**
	 * Customer First Name. The customer's First Name
	 *
	 * @var  string
	 */
	protected $first_name;

	/**
	 * Customer Last Name (Surname). The customer's Last Name Surname)
	 *
	 * @var  string
	 */
	protected $last_name;

	/**
	 * Customer Email Address. The customer's Email Address
	 *
	 * @var  string
	 */
	protected $email_address;

	/**
	 * Customer Username. The customer's WordPress user name (login)
	 *
	 * @var  string
	 */
	protected $username;

	/**
	 * Is Paying Customer?. Whether or not this customer has a paid order
	 *
	 * @var  bool
	 */
	protected $paying_customer;

	use BillingTrait;

	use ShippingTrait;
}
