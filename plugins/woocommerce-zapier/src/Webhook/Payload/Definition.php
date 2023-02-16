<?php

namespace OM4\WooCommerceZapier\Webhook\Payload;

defined( 'ABSPATH' ) || exit;

/**
 * Represents an individual REST API based Payload definition.
 *
 * Payload builds output for `woocommerce_webhook_payload` filter.
 *
 * @since 2.2.0
 */
interface Definition {
	/**
	 * Build payload upon webhook delivery.
	 *
	 * Compatible with `woocommerce_webhook_payload` filter.
	 *
	 * @param array   $payload       Data to be sent out by the webhook.
	 * @param string  $resource_type Type/name of the resource.
	 * @param integer $resource_id   ID of the resource.
	 * @param integer $webhook_id    ID of the webhook.
	 *
	 * @return array
	 */
	public function build( $payload, $resource_type, $resource_id, $webhook_id );
}
