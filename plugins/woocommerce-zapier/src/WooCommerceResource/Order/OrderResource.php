<?php

namespace OM4\WooCommerceZapier\WooCommerceResource\Order;

use OM4\WooCommerceZapier\Webhook\Trigger\Trigger;
use OM4\WooCommerceZapier\WooCommerceResource\CustomPostTypeResource;

defined( 'ABSPATH' ) || exit;


/**
 * Definition of the Order resource type.
 *
 * @since 2.1.0
 */
class OrderResource extends CustomPostTypeResource {

	/**
	 * {@inheritDoc}
	 */
	public function __construct() {
		$this->key                 = 'order';
		$this->name                = __( 'Order', 'woocommerce-zapier' );
		$this->metabox_screen_name = 'shop_order';
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_webhook_triggers() {
		return array(
			new Trigger(
				'order.status_changed',
				__( 'Order status changed', 'woocommerce-zapier' ),
				array( 'woocommerce_order_status_changed' )
			),
			// Order paid (previously New Order).
			new Trigger(
				'order.paid',
				__( 'Order paid', 'woocommerce-zapier' ),
				array( 'woocommerce_payment_complete' )
			),
		);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param int $resource_id Resource ID.
	 *
	 * @return string|null
	 */
	public function get_description( $resource_id ) {
		$object = \wc_get_order( $resource_id );
		if ( ! is_bool( $object ) && is_a( $object, 'WC_Order' ) && 'trash' !== $object->get_status() ) {
			return trim( $object->get_formatted_billing_full_name() );
		}
		return null;
	}
}
