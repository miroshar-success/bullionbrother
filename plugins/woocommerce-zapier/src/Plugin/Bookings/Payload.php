<?php

namespace OM4\WooCommerceZapier\Plugin\Bookings;

use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Plugin\Bookings\V1Controller;
use OM4\WooCommerceZapier\Webhook\Payload\Definition;
use WC_Webhook;
use WP_REST_Request;

defined( 'ABSPATH' ) || exit;

/**
 * Implements an individual REST API based Payload definition.
 *
 * Payload builds output for `woocommerce_webhook_payload` filter.
 *
 * @since 2.2.0
 */
class Payload implements Definition {

	/**
	 * Resource's key (internal name/type).
	 *
	 * Must be a-z lowercase characters only, and in singular (non plural) form.
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Controller instance.
	 *
	 * @var V1Controller
	 */
	protected $controller;

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Payload constructor.
	 *
	 * @param string       $key        Resource Key.
	 * @param V1Controller $controller V1Controller instance.
	 * @param Logger       $logger     Logger instance.
	 */
	public function __construct( $key, $controller, $logger ) {
		$this->key        = $key;
		$this->controller = $controller;
		$this->logger     = $logger;
	}

	/**
	 * Build payload upon webhook delivery.
	 *
	 * @param array   $payload       Data to be sent out by the webhook.
	 * @param string  $resource_type Type/name of the resource.
	 * @param integer $resource_id   ID of the resource.
	 * @param integer $webhook_id    ID of the webhook.
	 *
	 * @return array
	 */
	public function build( $payload, $resource_type, $resource_id, $webhook_id ) {
		$this->logger->debug(
			'Building webhook (ID: %d) payload for "%s" resource (ID: %d).',
			array( $webhook_id, $resource_type, $resource_id )
		);
		if ( $this->key === $resource_type && empty( $payload ) && get_wc_booking( $resource_id ) ) {
			// Force apply `woocommerce_webhook_event` filter.
			$webhook = new WC_Webhook( $webhook_id );
			$event   = $webhook->get_event();

			// switch user.
			$current_user = get_current_user_id();
			wp_set_current_user( $webhook->get_user_id() );

			// Build payload.
			if ( 'deleted' === $event ) {
				$payload = array(
					'id' => $resource_id,
				);
			} else {
				$request = new WP_REST_Request( 'GET' );
				$request->set_param( 'id', $resource_id );
				$result  = $this->controller->get_item( $request );
				$payload = isset( $result->data ) ? $result->data : array();
			}
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
			$this->logger->debug( 'Content: %s', array( var_export( $payload, true ) ) );

			// Restore current user.
			wp_set_current_user( $current_user );
		}

		return $payload;
	}
}
