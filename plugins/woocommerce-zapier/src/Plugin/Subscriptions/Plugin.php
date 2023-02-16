<?php

namespace OM4\WooCommerceZapier\Plugin\Subscriptions;

use OM4\WooCommerceZapier\API\API;
use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Plugin\Base;
use OM4\WooCommerceZapier\Plugin\Subscriptions\SubscriptionResource;
use WC_Subscription;
use WC_Subscriptions;

defined( 'ABSPATH' ) || exit;

/**
 * Functionality that is enabled when the WooCommerce Subscriptions plugin is active.
 *
 * @since 2.0.0
 */
class Plugin extends Base {

	/**
	 * FeatureChecker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $checker;

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Definition instance.
	 *
	 * @var SubscriptionResource
	 */
	protected $resource_definition;

	/**
	 * Name of the third party plugin.
	 */
	const PLUGIN_NAME = 'WooCommerce Subscriptions';

	/**
	 * The minimum WooCommerce Subscriptions version that this plugin supports.
	 */
	const MINIMUM_SUPPORTED_VERSION = '3.0.0';

	/**
	 * Constructor.
	 *
	 * @param FeatureChecker       $checker FeatureChecker instance.
	 * @param Logger               $logger Logger instance.
	 * @param SubscriptionResource $resource_definition Subscriptions Resource Definition.
	 */
	public function __construct( FeatureChecker $checker, Logger $logger, SubscriptionResource $resource_definition ) {
		$this->checker             = $checker;
		$this->logger              = $logger;
		$this->resource_definition = $resource_definition;
		$this->resource            = SubscriptionResource::class;
	}

	/**
	 * Instructs the Subscriptions functionality to initialise itself.
	 *
	 * @return bool
	 */
	public function initialise() {
		if ( ! parent::initialise() ) {
			return false;
		}

		foreach ( $this->resource_definition->get_webhook_triggers() as $trigger ) {
			foreach ( $trigger->get_actions() as $action ) {
				if ( 0 === strpos( $action, 'wc_zapier_' ) ) {
					$action = str_replace( 'wc_zapier_', '', $action );
					add_action( $action, array( $this, 'convert_arg_to_subscription_id_then_execute' ) );
				}
			}
		}
		return true;
	}

	/**
	 * Get the WooCommerce Subscriptions version number.
	 *
	 * @var string
	 */
	public function get_plugin_version() {
		return WC_Subscriptions::$version;
	}

	/**
	 * Whenever a relevant WooCommerce Subscriptions built-in action/event occurs,
	 * convert the args WC_Subscription object into a numerical subscription ID,
	 * and then trigger our own built-in action which then queues the webhook for delivery.
	 *
	 * @param WC_Subscription $arg Subscription object.
	 *
	 * @return void
	 */
	public function convert_arg_to_subscription_id_then_execute( $arg ) {
		if ( ! is_a( $arg, WC_Subscription::class ) ) {
			return;
		}
		$arg = $arg->get_id();
		/**
		 * Execute the WooCommerce Zapier handler for this hook/action.
		 *
		 * @internal
		 * @since 2.0.4
		 *
		 * @param int $arg Subscription ID.
		 */
		do_action( 'wc_zapier_' . current_action(), $arg );
	}

	/**
	 * Remove Subscriptions endpoints that are not required by WooCommerce Zapier, including:
	 *
	 * - /wc-zapier/v1/subscriptions/(?P<id>[\d]+)/orders
	 * - /wc-zapier/v1/subscriptions/statuses
	 *
	 * @param array $endpoints Registered WP REST API endpoints.
	 *
	 * @return array
	 */
	public function filter_rest_endpoints( $endpoints ) {
		foreach ( $endpoints as $route => $endpoint ) {
			if ( 0 === strpos( $route, sprintf( '/%s/subscriptions/', API::REST_NAMESPACE ) ) ) {
				if (
					false !== strpos( $route, '/(?P<id>[\d]+)/orders' ) ||
					false !== strpos( $route, '/statuses' )
				) {
					unset( $endpoints[ $route ] );
				}
			}
		}
		return $endpoints;
	}

	/**
	 * Whether not not the user has the WooCommerce Subscriptions plugin active.
	 *
	 * @return bool
	 */
	protected function is_active() {
		return $this->checker->class_exists( '\WC_Subscriptions' );
	}

}
