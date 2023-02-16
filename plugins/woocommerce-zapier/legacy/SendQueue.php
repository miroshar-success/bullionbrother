<?php

namespace OM4\Zapier;

use OM4\WooCommerceZapier\Settings;
use OM4\Zapier\Logger;
use OM4\Zapier\Trigger\Base;
use OM4\Zapier\Trigger\TriggerFactory;

defined( 'ABSPATH' ) || exit;

/**
 * A simple (in memory) queue of data that needs be sent to Zapier.
 *
 * Queued data is sent to Zapier at the end of the current page load (during the shutdown hook).
 *
 * A queue is used in order to prevent data being sent twice, and also so that data is sent *after* the
 * corresponding hook has been executed (rather than during it).
 *
 * This also ensures that the "Order sent to Zapier" notes appear in the order notes section after
 * the order status changed note.
 *
 * @deprecated 2.0.0 Replaced by WooCommerce's webhook delivery.
 */
class SendQueue {

	/**
	 * The single instance of the class
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * The queue of items, sorted chronologically based on date added.
	 *
	 * @var array
	 */
	protected $queue = array();

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Main SendQueue Instance.
	 * Ensures only one instance of SendQueue is loaded or can be loaded.
	 *
	 * @since 1.7.0
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initialise the Queue
	 * Constructor is private so nobody else can create an instance.
	 *
	 * @return void
	 */
	private function __construct() {
		$this->logger = new Logger();
		add_action( 'shutdown', array( $this, 'process_queue' ) );

		// Also process the queue during other footer hooks in case the shutdown hook never gets called.
		add_action( 'wp_footer', array( $this, 'process_queue' ) );
		add_action( 'admin_footer', array( $this, 'process_queue' ) );

		// Also process the queue after a PayPal IPN message is validated by WooCommerce core.
		add_action( 'valid-paypal-standard-ipn-request', array( $this, 'process_queue' ), 1000, 0 );
	}

	/**
	 * Add a new task to the queue
	 * If the task is already in the queue, it isn't re-added.
	 *
	 * @param Base   $trigger     The currently used Trigger Class.
	 * @param string $action_name The action (hook) name.
	 * @param array  $arguments   The arguments for the task.
	 *
	 * @return false|string False if already in the queue, or the unique key.
	 */
	public function add_to_queue( Base $trigger, $action_name, $arguments ) {
		if ( ! $this->is_in_queue( $trigger, $action_name, $arguments ) ) {
			$key           = $this->generate_item_key( $trigger, $action_name, $arguments );
			$this->queue[] = array(
				'key'                         => $key,
				// Unique key for this trigger and arguments.
									'trigger' => $trigger->get_trigger_key(),
				'action_name'                 => $action_name,
				'arguments'                   => $arguments,
			);
			$this->logger->debug(
				'Task %s successfully added to queue. Trigger: %s Arguments: %s Action: %s',
				array( $key, $trigger->get_trigger_key(), json_encode( $arguments ), $action_name )
			);
			return $key;
		}
		return false;
	}

	/**
	 * Generates a unique key (identifier) for the specified task
	 * This key is based on the Trigger, and the arguments. It ignores the
	 * action name so that only one pending task for each trigger and arguments
	 * are added at once.
	 *
	 * @param Base   $trigger     The currently used Trigger Class.
	 * @param string $action_name The action (hook) name.
	 * @param array  $arguments   The arguments for the task.
	 *
	 * @return string
	 */
	protected function generate_item_key( Base $trigger, $action_name, $arguments ) {
		return md5( $trigger->get_trigger_key() . wp_json_encode( $arguments ) );
	}

	/**
	 * Whether or not the specified task is currently in the queue
	 *
	 * @param Base   $trigger     The currently used Trigger Class.
	 * @param string $action_name The action (hook) name.
	 * @param array  $arguments   The arguments for the task.
	 *
	 * @return boolean
	 */
	public function is_in_queue( Base $trigger, $action_name, $arguments ) {
		$key = $this->generate_item_key( $trigger, $action_name, $arguments );
		foreach ( $this->queue as $queue_item ) {
			if ( $queue_item['key'] === $key ) {
				$this->logger->notice( "Task $key is already in the queue." );
				return true;
			}
		}
		return false;
	}

	/**
	 * Process (run) all of the tasks currently in the queue
	 * By default the queue is processed on each page load during WordPress'
	 * 'shutdown' hook, but it can also be processed on demand by calling this
	 * function.
	 *
	 * @return false|int False if the queue was empty, or the number of items that were processed.
	 */
	public function process_queue() {
		$num_items = count( $this->queue );
		if ( ! $num_items ) {
			// Empty queue.
			return false;
		}
		$start     = microtime( true );
		$hook_name = current_action();
		$this->logger->debug( 'Processing queue during %s. Queue contains %s task(s)...', array( $hook_name, $num_items ) );
		foreach ( $this->queue as $queue_item_key => $queue_item ) {
			// Remove it from the queue.
			unset( $this->queue[ $queue_item_key ] );

			// Run/execute this queue item.
			$trigger = TriggerFactory::get_trigger_with_key( $queue_item['trigger'] );
			$trigger->do_send( $queue_item['action_name'], $queue_item['arguments'] );
		}
		$time_elapsed_secs = microtime( true ) - $start;
		$this->logger->debug( 'Queue processing for %s task(s) completed in %s seconds.', array( $num_items, $time_elapsed_secs ) );
		return $num_items;
	}
}
