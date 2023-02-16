<?php

namespace OM4\Zapier\Payload\Item;

defined( 'ABSPATH' ) || exit;

/**
 * Base structure for Shipping.
 *
 * @deprecated 2.0.0
 */
trait ShippingTrait {

	/**
	 * Shipping First Name. The customer's first name
	 *
	 * @var  string
	 */
	protected $shipping_first_name;

	/**
	 * Shipping Last Name (Surname). The customer's last name
	 *
	 * @var  string
	 */
	protected $shipping_last_name;

	/**
	 * Shipping Company Name. The customer's company name
	 *
	 * @var  string
	 */
	protected $shipping_company;

	/**
	 * Shipping Address. Single line shipping address separated by commas. Can
	 *   be used instead of having to use the individual shipping address
	 *   components below.
	 *
	 * @var  string
	 */
	protected $shipping_address;

	/**
	 * Shipping Address (Line 1). Line 1 of the customer's address
	 *
	 * @var  string
	 */
	protected $shipping_address_1;

	/**
	 * Shipping Address (Line 2). Line 2 of the customer's address
	 *
	 * @var  string
	 */
	protected $shipping_address_2;

	/**
	 * Shipping City. The customer's city
	 *
	 * @var  string
	 */
	protected $shipping_city;

	/**
	 * Shipping State/Province. The customer's state/province
	 *
	 * @var  string
	 */
	protected $shipping_state;

	/**
	 * Shipping State/Province Name. The customer's state/province ame
	 *
	 * @var  string
	 */
	protected $shipping_state_name;

	/**
	 * Shipping Postcode / Zip Code. The customer's post code / zip ode
	 *
	 * @var  string
	 */
	protected $shipping_postcode;

	/**
	 * Shipping Country (2 letter code). The customer's 2-letter country code
	 *
	 * @var  string
	 */
	protected $shipping_country;

	/**
	 * Shipping Country Name. The customer's country name
	 *
	 * @var  string
	 */
	protected $shipping_country_name;
}
