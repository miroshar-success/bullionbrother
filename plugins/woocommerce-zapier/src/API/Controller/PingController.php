<?php

namespace OM4\WooCommerceZapier\API\Controller;

use OM4\WooCommerceZapier\API\API;
use OM4\WooCommerceZapier\Logger;
use WC_REST_Controller;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

/**
 * REST API controller class that gives WooCommerce Zapier app a performant way of
 * ensuring authentication credentials are still valid.
 *
 * @since 2.0.0
 */
class PingController extends WC_REST_Controller {

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Constructor.
	 *
	 * @param Logger $logger Logger instance.
	 */
	public function __construct( Logger $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'ping';

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = API::REST_NAMESPACE;

	/**
	 * Registers the routes for the objects of the controller.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				'args' => array(),
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
				),
			)
		);
	}

	/**
	 * Check whether a given request has permission to ping.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {
		// WooCommerce's REST API does not perform Basic Authentication if is_ssl() is false: https://github.com/woocommerce/woocommerce/blob/4.0.1/includes/class-wc-rest-authentication.php#L81.
		if ( ! is_ssl() ) {
			$this->error_log_request( $request );

			$this->logger->critical( 'WooCommerce REST API Basic Authentication was not performed during ping because is_ssl() returned false.' );

			return new WP_Error(
				'wc_zapier_not_implemented',
				__( 'Sorry, SSL is not configured correctly.', 'woocommerce-zapier' ),
				array( 'status' => 501 )
			);
		}

		if ( ! wc_rest_check_manager_permissions( 'webhooks', 'read' ) ) {
			$this->error_log_request( $request );

			if ( is_user_logged_in() ) {
				$user = wp_get_current_user();
				$this->logger->critical( 'Authentication attempt failed for user: %s. Insufficient user permissions.', $user->user_login );
				return new WP_Error(
					'wc_zapier_rest_cannot_authorize',
					__( 'This user does not have the correct permissions.', 'woocommerce-zapier' ),
					array( 'status' => 403 )
				);
			}

			$this->logger->critical( 'Authentication attempt failed. could not authenticate user.' );
			return new WP_Error(
				'wc_zapier_rest_cannot_view',
				__( 'Sorry, you cannot list resources.', 'woocommerce-zapier' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}

	/**
	 * (Fast) ping response.
	 *
	 * @param WP_REST_Request $request The incoming request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		return rest_ensure_response( array() );
	}

	/**
	 * Log request and environment data as error.
	 *
	 * @param WP_REST_Request $request The incoming request.
	 *
	 * @return void
	 */
	protected function error_log_request( $request ) {
		$this->logger->error( 'Ping Endpoint Error.' );
		$this->logger->debug(
			'Current Filter %s. Route: %s. Query Params: %s. Request URI: %s.',
			array(
				current_filter(),
				$request->get_route(),
				wp_json_encode( $request->get_params() ),
				isset( $_SERVER['REQUEST_URI'] ) ? wc_clean( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '',
			)
		);
	}
}
