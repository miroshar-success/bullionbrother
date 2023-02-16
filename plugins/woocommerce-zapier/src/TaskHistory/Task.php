<?php

namespace OM4\WooCommerceZapier\TaskHistory;

use OM4\WooCommerceZapier\Exception\InvalidTaskException;
use OM4\WooCommerceZapier\TaskHistory\TaskDataStore;
use OM4\WooCommerceZapier\Webhook\ZapierWebhook;
use WC_Data;
use WC_DateTime;

defined( 'ABSPATH' ) || exit;

/**
 * Represents as single Task History Task record.
 *
 * @since 2.0.0
 */
class Task extends WC_Data {

	/**
	 * Stores Task data.
	 *
	 * @var array
	 */
	protected $data = array(
		'date_time'     => null,
		'webhook_id'    => null,
		'resource_type' => null,
		'resource_id'   => null,
		'message'       => '',
		'type'          => '',
	);

	/**
	 * Name of this data type.
	 *
	 * Used by WooCommerce core, which executes WordPress filters during save/updates.
	 *
	 * @var string
	 */
	protected $object_type = 'zapier_task';

	/**
	 * TaskDataStore instance.
	 *
	 * @var TaskDataStore
	 */
	protected $data_store;

	/**
	 * Constructor. Creates a new Task or loads and existing Task if specified.
	 *
	 * @param TaskDataStore  $data_store TaskDataStore instance.
	 * @param int|Task|array $task       Task ID to load from the DB (optional) or already queried data.
	 */
	public function __construct( TaskDataStore $data_store, $task = 0 ) {
		$this->data_store = $data_store;
		parent::__construct( $task );

		if ( $task instanceof Task ) {
			$this->set_id( $task->get_id() );
		} elseif ( is_numeric( $task ) ) {
			$this->set_id( $task );
		} elseif ( is_array( $task ) ) {
			$this->set_id( $task['history_id'] );
			$this->set_props( $task );
			$this->set_object_read( true );
		}

		// If we have an ID, load the webhook from the DB.
		if ( 0 !== $this->get_id() ) {
			try {
				$this->data_store->read( $this );
			} catch ( InvalidTaskException $e ) {
				$this->set_id( 0 );
				$this->set_object_read( true );
			}
		} else {
			// Creating a brand new Task.
			$this->set_date_time( new WC_DateTime() );
			$this->set_object_read( true );
		}

	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get date/time.
	 *
	 * @param  string $context Get context.
	 *
	 * @return WC_DateTime Date/time object.
	 */
	public function get_date_time( $context = 'view' ) {
		return $this->get_prop( 'date_time', $context );
	}

	/**
	 * Get webhook id.
	 *
	 * @param  string $context Get context.
	 *
	 * @return integer
	 */
	public function get_webhook_id( $context = 'view' ) {
		return $this->get_prop( 'webhook_id', $context );
	}

	/**
	 * Get the webhook.
	 *
	 * @return ZapierWebhook The webhook.
	 */
	public function get_webhook() {
		return new ZapierWebhook( $this->get_webhook_id() );
	}

	/**
	 * Get resource type.
	 *
	 * @param  string $context Get context.
	 *
	 * @return string
	 */
	public function get_resource_type( $context = 'view' ) {
		return $this->get_prop( 'resource_type', $context );
	}

	/**
	 * Get resource id.
	 *
	 * @param  string $context Get context.
	 *
	 * @return integer
	 */
	public function get_resource_id( $context = 'view' ) {
		return $this->get_prop( 'resource_id', $context );
	}

	/**
	 * Get message.
	 *
	 * @param  string $context Get context.
	 *
	 * @return string
	 */
	public function get_message( $context = 'view' ) {
		return $this->get_prop( 'message', $context );
	}

	/**
	 * Get type.
	 *
	 * @param  string $context Get context.
	 *
	 * @return string
	 */
	public function get_type( $context = 'view' ) {
		return $this->get_prop( 'type', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set date/time.
	 *
	 * @param string|integer|null $date UTC timestamp, or ISO 8601 DateTime. If the DateTime string has no timezone or offset, WordPress site timezone will be assumed. Null if there is no date.
	 *
	 * @return void
	 */
	public function set_date_time( $date = null ) {
		if ( is_null( $date ) ) {
			$date = '';
		}
		$this->set_date_prop( 'date_time', $date );
	}

	/**
	 * Set webhook id.
	 *
	 * @param int $value Value to set.
	 *
	 * @return void
	 */
	public function set_webhook_id( $value ) {
		$this->set_prop( 'webhook_id', absint( $value ) );
	}

	/**
	 * Set resource type (eg product, order, customer, etc).
	 *
	 * @param string $value Value to set.
	 *
	 * @return void
	 */
	public function set_resource_type( $value ) {
		$this->set_prop( 'resource_type', $value );
	}

	/**
	 * Set resource id.
	 *
	 * @param int $value Value to set.
	 *
	 * @return void
	 */
	public function set_resource_id( $value ) {
		$this->set_prop( 'resource_id', absint( $value ) );
	}

	/**
	 * Set message.
	 *
	 * @param string $value Value to set.
	 *
	 * @return void
	 */
	public function set_message( $value ) {
		$this->set_prop( 'message', $value );
	}

	/**
	 * Set type.
	 *
	 * @param string $value Value to set.
	 *
	 * @return void
	 */
	public function set_type( $value ) {
		$this->set_prop( 'type', $value );
	}
}
