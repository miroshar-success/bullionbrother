<?php

namespace OM4\WooCommerceZapier\Webhook\Trigger;

use OM4\WooCommerceZapier\Webhook\Trigger\Definition;

defined( 'ABSPATH' ) || exit;


/**
 * Implements an individual REST API based Trigger definition.
 *
 * A trigger is an event that users can use to send data to Zapier.
 *
 * @since 2.2.0
 */
class Trigger implements Definition {

	/**
	 * Trigger Key.
	 *
	 * Must begin a-z lowercase characters only, in the format {$resource_key}.key
	 *
	 * Eg order.updated, coupon.created, order.paid, etc
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Trigger Name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * List of WooCommerce hooks/actions that this trigger should fire on.
	 *
	 * All actions need a resource ID as the first argument when the action is called.
	 *
	 * @var string[]
	 */
	protected $actions;

	/**
	 * Trigger constructor.
	 *
	 * @param string   $key Trigger Key.
	 * @param string   $name Trigger Name.
	 * @param string[] $actions Array of WooCommerce hooks/actions that this trigger should fire on.
	 */
	public function __construct( $key, $name, $actions ) {
		$this->key     = $key;
		$this->name    = $name;
		$this->actions = $actions;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_event() {
		$data = $this->get_topic_parts();
		if ( false === $data ) {
			return false;
		}
		return $data['event'];
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_resource() {
		$data = $this->get_topic_parts();
		if ( false === $data ) {
			return false;
		}
		return $data['resource'];
	}

	/**
	 * Get this trigger's webhook topic details.
	 *
	 * @return array|false
	 */
	protected function get_topic_parts() {
		$data = explode( '.', $this->get_key() );
		if ( 2 !== count( $data ) ) {
			return false;
		}
		return array(
			'resource' => $data[0],
			'event'    => $data[1],
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_actions() {
		return $this->actions;
	}
}
