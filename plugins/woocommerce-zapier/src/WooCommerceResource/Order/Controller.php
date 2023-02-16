<?php

namespace OM4\WooCommerceZapier\WooCommerceResource\Order;

use OM4\WooCommerceZapier\API\API;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\TaskHistory\Listener\APIListenerTrait;
use OM4\WooCommerceZapier\TaskHistory\TaskDataStore;
use WC_REST_Orders_Controller;

defined( 'ABSPATH' ) || exit;

/**
 * Exposes WooCommerce's REST API v3 Orders endpoint via the WooCommerce Zapier endpoint namespace.
 *
 * @since 2.0.0
 */
class Controller extends WC_REST_Orders_Controller {

	use APIListenerTrait;

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = API::REST_NAMESPACE;

	/**
	 * Resource Type (used for Task History items).
	 *
	 * @var string
	 */
	protected $resource_type = 'order';

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * TaskDataStore instance.
	 *
	 * @var TaskDataStore
	 */
	protected $data_store;

	/**
	 * Constructor.
	 *
	 * @param Logger        $logger     Logger instance.
	 * @param TaskDataStore $data_store TaskDataStore instance.
	 */
	public function __construct( Logger $logger, TaskDataStore $data_store ) {
		$this->logger     = $logger;
		$this->data_store = $data_store;
	}
}
