<?php

namespace OM4\WooCommerceZapier\API\Controller;

use OM4\WooCommerceZapier\API\API;
use OM4\WooCommerceZapier\Webhook\Resources;
use OM4\WooCommerceZapier\WooCommerceResource\Manager as ResourceManager;
use WC_REST_Controller;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

/**
 * REST API controller class that exposes WooCommerce's internal webhook topics.
 * Needed so the Zap Editor can dynamically load the list of Trigger events.
 *
 * @since 2.0.0
 */
class WebhookTopicsController extends WC_REST_Controller {

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = API::REST_NAMESPACE;

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'webhooks/topics';

	/**
	 * Resources instance.
	 *
	 * @var Resources
	 */
	protected $webhook_resources;

	/**
	 * ResourceManager instance.
	 *
	 * @var ResourceManager
	 */
	protected $resource_manager;

	/**
	 * Constructor.
	 *
	 * @param Resources       $webhook_resources Resources instance.
	 * @param ResourceManager $resource_manager  ResourceManager instance.
	 */
	public function __construct(
		Resources $webhook_resources,
		ResourceManager $resource_manager
	) {
		$this->webhook_resources = $webhook_resources;
		$this->resource_manager  = $resource_manager;
	}

	/**
	 * Registers the routes for the objects of the controller.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<resource>[a-z]+)',
			array(
				'args'   => array(
					'resource' => array(
						'description' => __( 'The resource type.', 'woocommerce-zapier' ),
						'type'        => 'string',
						'enum'        => array_keys( $this->resource_manager->get_enabled_resources_list() ),
						'required'    => true,
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Get the Webhook Topic's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'webhook_topic',
			'type'       => 'object',
			'properties' => array(
				'key'  => array(
					'description' => __( 'The unique key (identifier) for the webhook topic.', 'woocommerce-zapier' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
				),
				'name' => array(
					'description' => __( 'A friendly name for the webhook topic.', 'woocommerce-zapier' ),
					'type'        => 'string',
					'context'     => array( 'view' ),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	/**
	 * Check whether a given request has permission to read webhook topics.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {
		if ( ! wc_rest_check_manager_permissions( 'webhooks', 'read' ) ) {
			return new WP_Error(
				'woocommerce_rest_cannot_view',
				__( 'Sorry, you cannot list resources.', 'woocommerce-zapier' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}
		return true;
	}

	/**
	 * Get all webhook topics.
	 *
	 * @param WP_REST_Request $request The incoming request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {

		$response = array();
		foreach ( $this->webhook_resources->get_topics() as $topic_key => $topic_name ) {
			// Filtering by resource type/name.
			$topic_length  = strpos( $topic_key, '.' ) === false ? 0 : strpos( $topic_key, '.' );
			$resource_name = substr( $topic_key, 0, $topic_length ) . 's';
			if ( $resource_name !== $request['resource'] ) {
				continue;
			}

			if ( isset( $request['search'] ) && is_string( $request['search'] ) && strlen( $request['search'] ) > 0 ) {
				// Filter by search term (in key or in name).
				if ( false === stripos( $topic_key, $request['search'] ) && false === stripos( $topic_name, $request['search'] ) ) {
					continue;
				}
			}

			$response[] = $this->prepare_response_for_collection(
				new WP_REST_Response(
					array(
						'key'  => $topic_key,
						'name' => $topic_name,
					)
				)
			);
		}

		usort(
			$response,
			function( $a, $b ) {
				return strnatcasecmp( $a['name'], $b['name'] );
			}
		);

		return rest_ensure_response( $response );
	}
}
