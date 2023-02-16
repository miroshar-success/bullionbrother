<?php

namespace OM4\WooCommerceZapier\API\Controller;

use OM4\WooCommerceZapier\API\API;
use OM4\WooCommerceZapier\WooCommerceResource\Manager as ResourceManager;
use WC_REST_Webhooks_Controller;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

/**
 * Exposes WooCommerce's REST API v3 Webhooks endpoint via the WooCommerce Zapier endpoint namespace.
 *
 * @since 2.0.0
 */
class WebhookController extends WC_REST_Webhooks_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = API::REST_NAMESPACE;

	/**
	 * ResourceManager instance.
	 *
	 * @var ResourceManager
	 */
	protected $resource_manager;

	/**
	 * Target resource.
	 *
	 * @var string
	 */
	protected $resource;

	/**
	 * WebhookController constructor.
	 *
	 * @param ResourceManager $resource_manager ResourceManager instance.
	 *
	 * @since 2.1.0
	 */
	public function __construct( ResourceManager $resource_manager ) {
		$this->resource_manager = $resource_manager;
	}

	/**
	 * Handler for whenever a webhook is created via this controller.
	 *
	 * Allows us to filter/override the newly created webhook.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 *
	 * @since 2.1.0
	 */
	public function create_item( $request ) {
		// Validate webhook topic as it's required in order to determine the resource.
		// Topic validation is the same as `WC_REST_Webhooks_V1_Controller::create_item()`.
		if ( empty( $request['topic'] ) || ! wc_is_webhook_valid_topic( strtolower( $request['topic'] ) ) ) {
			return new WP_Error( "woocommerce_rest_{$this->post_type}_invalid_topic", __( 'Webhook topic is required and must be valid.', 'woocommerce-zapier' ), array( 'status' => 400 ) );
		}
		$key_parts      = explode( '.', $request['topic'] );
		$this->resource = $key_parts[0];
		return parent::create_item( $request );
	}

	/**
	 * Get the corresponding REST API version.
	 *
	 * @since 2.1.0
	 * @return string
	 */
	protected function get_default_api_version() {
		$resource = $this->resource_manager->get_resource( $this->resource );
		if ( ! $resource ) {
			// Not one of our resources.
			return parent::get_default_api_version();
		}
		$version = (string) $resource->get_controller_rest_api_version();
		return "wp_api_v$version";
	}
}
