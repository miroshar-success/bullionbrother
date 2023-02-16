<?php

namespace OM4\WooCommerceZapier\Plugin\Subscriptions;

use OM4\WooCommerceZapier\API\API;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\TaskHistory\Listener\APIListenerTrait;
use OM4\WooCommerceZapier\TaskHistory\TaskDataStore;
use WC_REST_Subscriptions_Controller;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( '\WC_REST_Subscriptions_Controller' ) ) {
	// // Running WooCommerce Subscriptions 3.1 or newer. This class should not be defined or instantiated.
	return;
}

/**
 * Exposes WooCommerce Subscriptions' REST API v1 Subscriptions endpoint via the WooCommerce Zapier endpoint namespace.
 *
 * Used when running WooCommerce Subscriptions v3.0 or older.
 * If running WooCommerce Subscriptions 3.1 or newer, the `OM4\WooCommerceZapier\Plugin\Subscriptions\V1Controller` class is used instead.
 *
 * @since 2.0.0
 */
class Controller extends WC_REST_Subscriptions_Controller {

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
	protected $resource_type = 'subscription';

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
		parent::__construct();
	}
}
