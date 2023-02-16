<?php

namespace OM4\Zapier\Payload\Item;

defined( 'ABSPATH' ) || exit;
/**
 * Base structure for Billing.
 *
 * @deprecated 2.0.0
 */
trait BillingTrait {

	/**
	 * Billing First Name. The customer's first name
	 *
	 * @var  string
	 */
	protected $billing_first_name;

	/**
	 * Billing Last Name (Surname). The customer's last name
	 *
	 * @var  string
	 */
	protected $billing_last_name;

	/**
	 * Billing Company Name. The customer's company name
	 *
	 * @var  string
	 */
	protected $billing_company;

	/**
	 * Billing Email Address. The customer's email address
	 *
	 * @var  string
	 */
	protected $billing_email;

	/**
	 * Billing Phone Number. The customer's phone number
	 *
	 * @var  string
	 */
	protected $billing_phone;

	/**
	 * Billing Address. Single line billing address separated by commas. Can be
	 *   used instead of having to use the individual billing address components
	 *   below.
	 *
	 * @var  string
	 */
	protected $billing_address;

	/**
	 * Billing Address (Line 1). Line 1 of the customer's address
	 *
	 * @var  string
	 */
	protected $billing_address_1;

	/**
	 * Billing Address (Line 2). Line 2 of the customer's address
	 *
	 * @var  string
	 */
	protected $billing_address_2;

	/**
	 * Billing City. The customer's city
	 *
	 * @var  string
	 */
	protected $billing_city;

	/**
	 * Billing State/Province. The customer's state/province
	 *
	 * @var  string
	 */
	protected $billing_state;

	/**
	 * Billing State/Province Name. The customer's state/province name
	 *
	 * @var  string
	 */
	protected $billing_state_name;

	/**
	 * Billing Postcode / Zip Code. The customer's post code / zip ode
	 *
	 * @var  string
	 */
	protected $billing_postcode;

	/**
	 * Billing Country (2 letter code). The customer's 2-letter country code
	 *
	 * @var  string
	 */
	protected $billing_country;

	/**
	 * Billing Country Name. The customer's country name
	 *
	 * @var  string
	 */
	protected $billing_country_name;
}
