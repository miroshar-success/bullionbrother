<?php

namespace OM4\WooCommerceZapier\Plugin\Subscriptions;

use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Plugin\Subscriptions\Controller;
use OM4\WooCommerceZapier\Plugin\Subscriptions\V1Controller;
use OM4\WooCommerceZapier\Webhook\Trigger\Trigger;
use OM4\WooCommerceZapier\WooCommerceResource\CustomPostTypeResource;
use WC_REST_Subscriptions_Controller;
use WC_REST_Subscriptions_V1_Controller;

defined( 'ABSPATH' ) || exit;

/**
 * Definition of the Subscription resource type.
 *
 * This resource is only enabled if WooCommerce Subscriptions is available.
 *
 * WooCommerce Subscriptions has webhook payload, topic and delivery functionality built-in,
 * so this class extends the built-in trigger rules.
 *
 * @since 2.2.0
 */
class SubscriptionResource extends CustomPostTypeResource {

	/**
	 * Feature Checker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $checker;

	/**
	 * {@inheritDoc}
	 *
	 * @param FeatureChecker $checker FeatureChecker instance.
	 */
	public function __construct( FeatureChecker $checker ) {
		$this->checker             = $checker;
		$this->key                 = 'subscription';
		$this->name                = __( 'Subscription', 'woocommerce-zapier' );
		$this->metabox_screen_name = 'shop_subscription';
	}

	/**
	 * {@inheritDoc}
	 */
	public function is_enabled() {
		return $this->checker->class_exists( WC_REST_Subscriptions_V1_Controller::class ) || $this->checker->class_exists( WC_REST_Subscriptions_Controller::class );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_controller_name() {
		if ( $this->checker->class_exists( WC_REST_Subscriptions_V1_Controller::class ) ) {
			// WooCommerce Subscriptions 3.1 (or newer).
			return V1Controller::class;
		}
		// WooCommerce Subscriptions 3.0 (or older).
		return Controller::class;
	}

	/**
	 * Get the Subscriptions REST API controller's REST API version.
	 *
	 * Subscriptions uses a REST API v1 payload.
	 *
	 * This is because the Subscriptions endpoint is a REST API v1 controller, we need to always deliver a v1 payload
	 * and not a v3 payload that is introduced in Subscriptions v3.1.
	 *
	 * @inheritDoc
	 */
	public function get_controller_rest_api_version() {
		return 1;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_webhook_triggers() {
		return array(
			new Trigger(
				'subscription.status_changed',
				__( 'Subscription status changed', 'woocommerce-zapier' ),
				// `woocommerce_subscription_status_updated` hook with our own prefix/handler to convert the arg from a WC_Subscription object to a subscription ID.
				array( 'wc_zapier_woocommerce_subscription_status_updated' )
			),
			new Trigger(
				'subscription.renewed',
				__( 'Subscription renewed', 'woocommerce-zapier' ),
				// `woocommerce_subscription_renewal_payment_complete` hook with our own prefix/handler to convert the arg from a WC_Subscription object to a subscription ID.
				array( 'wc_zapier_woocommerce_subscription_renewal_payment_complete' )
			),
			new Trigger(
				'subscription.renewal_failed',
				__( 'Subscription renewal failed', 'woocommerce-zapier' ),
				// `woocommerce_subscription_renewal_payment_failed` hook with our own prefix/handler to convert the arg from a WC_Subscription object to a subscription ID.
				array( 'wc_zapier_woocommerce_subscription_renewal_payment_failed' )
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
		$object = wcs_get_subscription( $resource_id );
		if ( false !== $object && is_a( $object, 'WC_Subscription' ) && 'trash' !== $object->get_status() ) {
			return $object->get_formatted_billing_full_name();
		}
		return null;
	}
}
