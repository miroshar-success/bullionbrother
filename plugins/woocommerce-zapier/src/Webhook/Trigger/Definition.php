<?php

namespace OM4\WooCommerceZapier\Webhook\Trigger;

defined( 'ABSPATH' ) || exit;


/**
 * Represents an individual REST API based Trigger definition.
 *
 * A trigger is an event that users can use to send data to Zapier.
 *
 * @since 2.2.0
 */
interface Definition {

	/**
	 * Get this Trigger's key.
	 *
	 * @return string
	 */
	public function get_key();

	/**
	 * Get this Trigger's event name.
	 *
	 * @return string|false
	 */
	public function get_event();

	/**
	 * Get this trigger's resource name.
	 *
	 * @return string|false
	 */
	public function get_resource();

	/**
	 * Get this Trigger's name.
	 *
	 * @return string
	 */
	public function get_name();

	/**
	 * Get this Trigger's list of WooCommerce hooks/actions that this trigger should fire on.
	 *
	 * @return string[]
	 */
	public function get_actions();
}
