<?php

namespace OM4\WooCommerceZapier\TaskHistory\Listener;

use OM4\WooCommerceZapier\TaskHistory\Task;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) || exit;

/**
 * Improves create/update REST API requests so that they are added to our task history,
 * and also logged if there is an error with the request.
 *
 * Delete API requests aren't currently supported in the Zapier App, so if a delete request
 * occurs then log it.
 *
 * @since 2.0.0
 */
trait APIListenerTrait {

	/**
	 * Item Create.
	 *
	 * @uses WC_REST_CRUD_Controller::create_item() as parent::create_item() Create a single item.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response REST API Response.
	 */
	public function create_item( $request ) {
		$response = parent::create_item( $request );
		if ( is_a( $response, 'WP_Error' ) ) {
			$this->log_error_response( $request, $response );
			return $response;
		}
		$this->create_task( $response->data['id'], __( 'Created via Zapier', 'woocommerce-zapier' ) );
		return $response;
	}

	/**
	 * Item Delete.
	 *
	 * @uses WC_REST_CRUD_Controller::delete_item() as parent::delete_item() Delete a single item.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function delete_item( $request ) {
		$response = parent::delete_item( $request );
		if ( is_a( $response, 'WP_Error' ) ) {
			$this->log_error_response( $request, $response );
			return $response;
		}
		$this->logger->critical(
			'Unsupported REST API access on resource_id %d, resource_type %s, message: %s',
			array(
				$request['id'],
				$this->resource_type,
				__( 'Deleted via Zapier', 'woocommerce-zapier' ),
			)
		);
		return $response;
	}

	/**
	 * Item update.
	 *
	 * @uses WC_REST_CRUD_Controller::update_item() as parent::update_item() Update a single item.

	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item( $request ) {
		$response = parent::update_item( $request );
		if ( is_a( $response, 'WP_Error' ) ) {
			$this->log_error_response( $request, $response );
			return $response;
		}
		$this->create_task( $response->data['id'], __( 'Updated via Zapier', 'woocommerce-zapier' ) );
		return $response;
	}

	/**
	 * Create a Task History record.
	 *
	 * @uses self::$data_store TaskDataStore instance.
	 *
	 * @param int    $resource_id Resource ID.
	 * @param string $message     Message.
	 *
	 * @return void
	 */
	protected function create_task( $resource_id, $message ) {
		/**
		 * Task instance.
		 *
		 * @var Task
		 */
		$task = $this->data_store->new_task();
		$task->set_type( 'action' );
		$task->set_resource_id( $resource_id );
		$task->set_resource_type( $this->resource_type );
		$task->set_message( $message );
		if ( 0 === $task->save() ) {
			$this->logger->critical(
				'Error creating task history record for resource_id %d, resource_type %s, message: %s',
				array(
					$resource_id,
					$this->resource_type,
					$message,
				)
			);
		}
	}

	/**
	 * Log a REST API response error.
	 *
	 * @param WP_REST_Request $request REST API Request.
	 * @param WP_Error        $error REST API Error Response.
	 *
	 * @return void
	 */
	protected function log_error_response( $request, $error ) {
		$this->logger->error(
			'REST API Error Response for Request Route: %s. Request Method: %s. Resource Type: %s. Error Code: %s. Error Message: %s',
			array(
				$request->get_route(),
				$request->get_method(),
				$this->resource_type,
				$error->get_error_code(),
				$error->get_error_message(),
			)
		);
	}
}
