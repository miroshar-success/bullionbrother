<?php

namespace OM4\WooCommerceZapier\API;

use OM4\WooCommerceZapier\API\Controller\PingController;
use OM4\WooCommerceZapier\API\Controller\WebhookController;
use OM4\WooCommerceZapier\API\Controller\WebhookTopicsController;
use OM4\WooCommerceZapier\ContainerService;
use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Helper\HTTPHeaders;
use OM4\WooCommerceZapier\WooCommerceResource\Manager as ResourceManager;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

/**
 * The WooCommerce Zapier REST API.
 * Initialises our REST API endpoints/controllers.
 * Adds necessary headers to all REST API Responses.
 *
 * @since 2.0.0
 */
class API {

	/**
	 * Our REST API Controller instances.
	 *
	 * @var array
	 */
	protected $controllers = array();

	/**
	 * Namespace for our REST API.
	 */
	const REST_NAMESPACE = 'wc-zapier/v1';

	/**
	 * ContainerService instance.
	 *
	 * @var ContainerService
	 */
	protected $container;

	/**
	 * FeatureChecker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $check;

	/**
	 * ResourceManager instance.
	 *
	 * @var ResourceManager
	 */
	protected $resource_manager;

	/**
	 * HTTPHeaders instance.
	 *
	 * @var HTTPHeaders
	 */
	protected $http_headers;

	/**
	 * API constructor.
	 *
	 * @param FeatureChecker   $check FeatureChecker instance.
	 * @param ResourceManager  $resource_manager ResourceManager instance.
	 * @param HTTPHeaders      $http_headers HTTPHeaders instance.
	 * @param ContainerService $container ContainerService instance.
	 */
	public function __construct(
		FeatureChecker $check,
		ResourceManager $resource_manager,
		HTTPHeaders $http_headers,
		ContainerService $container
	) {
		$this->check            = $check;
		$this->resource_manager = $resource_manager;
		$this->http_headers     = $http_headers;
		$this->container        = $container;
	}

	/**
	 * Initialise our REST API functionality by hooking into the relevant WordPress hooks/filters.
	 *
	 * @return void
	 */
	public function initialise() {

		// Priority 11 is one more after WooCommerce initialise its own REST Routes.
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ), 11 );
		add_filter( 'rest_endpoints', array( $this, 'rest_endpoints' ), 11 );

		add_filter( 'rest_post_dispatch', array( $this, 'rest_post_dispatch' ), 10, 3 );
	}

	/**
	 * Register and initialise our REST API Controllers.
	 * Executed during WordPress' `rest_api_init` hook.
	 *
	 * @return void
	 */
	public function rest_api_init() {

		// Non-resource specific controllers.
		$controllers = array(
			PingController::class,
			WebhookController::class,
			WebhookTopicsController::class,
		);

		// Resource-specific controllers.
		foreach ( $this->resource_manager->get_enabled() as $resource ) {
			$controllers[] = $resource->get_controller_name();
		}

		// Alphabetical sort order so that schema definitions are in alphabetical order.
		sort( $controllers );

		foreach ( $controllers as $controller_class ) {
			$controller = $this->container->get( $controller_class );
			$controller->register_routes();
			$this->controllers[] = $controller;
		}
	}

	/**
	 * Remove the WooCommerce /batch endpoint from each resource because they aren't used by WooCommerce Zapier.
	 *
	 * @param array $endpoints Registered WP REST API endpoints.
	 *
	 * @return array
	 */
	public function rest_endpoints( $endpoints ) {
		foreach ( $endpoints as $route => $endpoint ) {
			if ( 0 === strpos( $route, '/' . self::REST_NAMESPACE ) && false !== strpos( $route, '/batch' ) ) {
				unset( $endpoints[ $route ] );
			}
		}
		return $endpoints;
	}

	/**
	 * For all WooCommerce Zapier REST API responses, include the our headers.
	 *
	 * @param WP_HTTP_Response $result Result to send to the client. Usually a WP_REST_Response.
	 * @param WP_REST_Server   $server Server instance.
	 * @param WP_REST_Request  $request Request used to generate the response.
	 *
	 * @return WP_HTTP_Response
	 */
	public function rest_post_dispatch( $result, $server, $request ) {
		if ( ! $result instanceof WP_REST_Response ) {
			return $result;
		}
		if ( 0 === strpos( $result->get_matched_route(), '/' . self::REST_NAMESPACE ) ) {
			// The response is from a WooCommerce Zapier endpoint.
			foreach ( $this->http_headers->get_headers() as $header_name => $header_value ) {
				$result->header( $header_name, $header_value );
			}
		}
		return $result;
	}

}
