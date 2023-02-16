<?php

namespace OM4\WooCommerceZapier\Webhook;

use OM4\WooCommerceZapier\Helper\HTTPHeaders;
use OM4\WooCommerceZapier\Webhook\ZapierWebhook;
use WC_Webhook;

defined( 'ABSPATH' ) || exit;

/**
 * Improvements to WooCommerce Core's webhook mechanism:
 *
 * Prevents duplicate deliveries of Zapier Webhooks due to a WooCommerce Core Bug (https://github.com/woocommerce/woocommerce/pull/25183),
 * that causes multiple deliveries to occur if a Webhook Topic defines multiple hooks defined.
 *
 * This affects the following built-in WooCommerce Webhook Topics:
 * - Coupon created
 * - Coupon updated
 * - Customer created
 * - Customer updated
 * - Order updated
 * - Product created
 * - Product updated
 *
 * As well as our own custom Triggers/Topics:
 * - Order paid
 *
 * This de-duplication functionality only affects WooCommerce Webhooks that are created via a Zapier Zap.
 * WooCommerce webhooks that aren't created via a Zapier Zap are unaffected and unchanged.
 *
 * Also sends a X-WordPress-GMT-Offset header so that triggers can interpret dates correctly.
 *
 * @since 2.0.0
 */
class DeliveryFilter {

	/**
	 * HTTPHeaders instance.
	 *
	 * @var HTTPHeaders
	 */
	protected $http_headers;

	/**
	 * Store the WooCommerce Zapier Webhook deliveries that occurred during the current page request.
	 *
	 * Each item in the array is in the format "{webhook_id}-{resource_id}"
	 *
	 * @var string[]
	 */
	protected $deliveries = array();

	/**
	 * Constructor.
	 *
	 * @param HTTPHeaders $http_headers HTTPHeaders instance.
	 */
	public function __construct( HTTPHeaders $http_headers ) {
		$this->http_headers = $http_headers;
	}

	/**
	 * Initialise our functionality by hooking into the relevant WooCommerce hooks/filters.
	 *
	 * @return void
	 */
	public function initialise() {
		add_filter( 'woocommerce_webhook_should_deliver', array( $this, 'woocommerce_webhook_should_deliver' ), 10, 3 );
		add_action( 'woocommerce_webhook_process_delivery', array( $this, 'woocommerce_webhook_process_delivery' ), 20, 3 );
		add_filter( 'woocommerce_webhook_http_args', array( $this, 'woocommerce_webhook_http_args' ), 10, 3 );
	}

	/**
	 * Prevent duplication of WooCommerce webhook deliveries for Zapier Webhooks.
	 *
	 * Executed by the `woocommerce_webhook_should_deliver` filter.
	 *
	 * @param bool       $should_deliver True if the webhook should be sent, or false to not send it.
	 * @param WC_Webhook $wc_webhook     The Webhook.
	 * @param mixed      $arg            The Webhook argument.
	 *
	 * @return bool
	 */
	public function woocommerce_webhook_should_deliver( $should_deliver, $wc_webhook, $arg ) {
		if ( false === $should_deliver ) {
			// WooCommerce has already determined not to deliver this webhook.
			return $should_deliver;
		}

		$webhook = new ZapierWebhook( $wc_webhook );

		if ( ! $webhook->is_zapier_webhook() ) {
			// Webhook isn't created by WooCommerce Zapier.
			return $should_deliver;
		}

		if ( false !== array_search( $webhook->get_id() . '-' . $arg, $this->deliveries, true ) ) {
			// WooCommerce Zapier Webhook has already been delivered in this request, so don't send it again.
			return false;
		}

		return $should_deliver;
	}

	/**
	 * After WooCommerce has processed the delivery of a webhook, save a record of this delivery
	 * so that we can prevent duplicate webhook deliveries for the same resource.
	 *
	 * Executed by the `woocommerce_webhook_process_delivery` hook.
	 *
	 * @param WC_Webhook $wc_webhook WooCommerce Webhook.
	 * @param mixed      $arg     Webhook arg (usually the resource ID).
	 *
	 * @return void
	 */
	public function woocommerce_webhook_process_delivery( WC_Webhook $wc_webhook, $arg ) {

		$webhook = new ZapierWebhook( $wc_webhook );

		if ( ! $webhook->is_zapier_webhook() ) {
			return;
		}
		// The webhook delivery that just occurred is a Zapier webhook delivery.
		$this->deliveries[] = $webhook->get_id() . '-' . $arg;
	}


	/**
	 * For all WooCommerce Zapier webhook deliveries to Zapier, include our HTTP headers.
	 *
	 * @param array $http_args HTTP request args.
	 * @param mixed $arg Webhook arg (usually the resource ID).
	 * @param int   $webhook_id Webhook ID.
	 *
	 * @return array
	 */
	public function woocommerce_webhook_http_args( $http_args, $arg, $webhook_id ) {
		$webhook = new ZapierWebhook( $webhook_id );
		if ( ! $webhook->is_zapier_webhook() ) {
			return $http_args;
		}
		foreach ( $this->http_headers->get_headers() as $header_name => $header_value ) {
			$http_args['headers'][ $header_name ] = $header_value;
		}
		return $http_args;
	}

}
