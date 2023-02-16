<?php

namespace OM4\WooCommerceZapier\Webhook;

use OM4\WooCommerceZapier\Webhook\TopicsRetriever as WebhookTopicsRetriever;
use OM4\WooCommerceZapier\WooCommerceResource\Manager as ResourceManager;

defined( 'ABSPATH' ) || exit;

/**
 * Accesses and extends WooCommerce's standard webhook functionality.
 *
 * Provides trigger events.
 *
 * @since 2.1.0
 */
class Resources {

	/**
	 * List of Webhook Topics (cached for the lifetime of a request).
	 *
	 * @var array|null
	 */
	private static $topics;

	/**
	 * WebhookTopicsRetriever instance.
	 *
	 * @var WebhookTopicsRetriever
	 */
	protected $retriever;

	/**
	 * ResourceManager instance.
	 *
	 * @var ResourceManager
	 */
	protected $resource_manager;

	/**
	 * Constructor
	 *
	 * @param WebhookTopicsRetriever $retriever WebhookTopicsRetriever instance.
	 * @param ResourceManager        $resource_manager ResourceManager instance.
	 */
	public function __construct(
		WebhookTopicsRetriever $retriever,
		ResourceManager $resource_manager
	) {
		$this->retriever        = $retriever;
		$this->resource_manager = $resource_manager;
	}

	/**
	 * Initialise functionality including hooks/filters.
	 *
	 * @return void
	 */
	public function initialise() {
		add_filter( 'woocommerce_valid_webhook_resources', array( $this, 'woocommerce_valid_webhook_resources' ) );
		add_filter( 'woocommerce_webhook_topics', array( $this, 'woocommerce_webhook_topics' ) );
		add_filter( 'woocommerce_webhook_topic_hooks', array( $this, 'woocommerce_webhook_topic_hooks' ), 100 );
		add_filter( 'woocommerce_valid_webhook_events', array( $this, 'woocommerce_valid_webhook_events' ) );
		add_filter( 'woocommerce_webhook_payload', array( $this, 'woocommerce_webhook_payload' ), 10, 4 );
	}

	/**
	 * Add webhook resource if necessary.
	 *
	 * @since 2.2.0
	 *
	 * @param string[] $resources List of available resources.
	 * @return string[]
	 */
	public function woocommerce_valid_webhook_resources( $resources ) {
		foreach ( $this->resource_manager->get_enabled() as $resource ) {
			if ( ! is_null( $resource->get_webhook_payload() ) ) {
				$resources[] = $resource->get_key();
			}
		}
		return $resources;
	}

	/**
	 * Add our own Triggers to WooCommerce's default list of available Webhook keys and names.
	 *
	 * @param array $topics List of WooCommerce's standard webhook topics.
	 *
	 * @return array
	 */
	public function woocommerce_webhook_topics( $topics ) {
		foreach ( $this->resource_manager->get_enabled() as $resource ) {
			foreach ( $resource->get_webhook_triggers() as $trigger ) {
				$topics[ $trigger->get_key() ] = $trigger->get_name();
			}
		}
		return $topics;
	}

	/**
	 * Add our own Triggers to WooCommerce's default list of Webhook Topics and their associated WordPress hooks.
	 *
	 * Executed during the `woocommerce_webhook_topic_hooks` hook at priority 100, which is after WCS_Webhooks::add_topics()
	 * clobbers all existing webhook topic hooks for a subscription during priority 20.
	 *
	 * @param array $topic_hooks List of WooCommerce's standard webhook topics and hooks.
	 *
	 * @return mixed
	 */
	public function woocommerce_webhook_topic_hooks( $topic_hooks ) {
		foreach ( $this->resource_manager->get_enabled() as $resource ) {
			foreach ( $resource->get_webhook_triggers() as $trigger ) {
				$topic_hooks[ $trigger->get_key() ] = $trigger->get_actions();
			}
		}
		return $topic_hooks;
	}

	/**
	 * Extend WooCommerce's list of Webhook Topic Events so that our custom Triggers
	 * pass webhook validation during `wc_is_webhook_valid_topic()`.
	 *
	 * @param array $events WooCommerce's list of in-built topic events (created, updated, deleted, restored).
	 *
	 * @return array
	 */
	public function woocommerce_valid_webhook_events( $events ) {
		foreach ( $this->resource_manager->get_enabled() as $resource ) {
			foreach ( $resource->get_webhook_triggers() as $trigger ) {
				if ( false === array_search( $trigger->get_event(), $events, true ) ) {
					$events[] = $trigger->get_event();
				}
			}
		}
		return $events;
	}

	/**
	 * Build payload upon webhook delivery.
	 *
	 * @since 2.2.0
	 *
	 * @param array   $payload      Data to be sent out by the webhook.
	 * @param string  $resource_key Type/name of the resource.
	 * @param integer $resource_id  ID of the resource.
	 * @param integer $webhook_id   ID of the webhook.
	 *
	 * @return array
	 */
	public function woocommerce_webhook_payload( $payload, $resource_key, $resource_id, $webhook_id ) {
		foreach ( $this->resource_manager->get_enabled() as $resource ) {
			if (
				! is_null( $resource->get_webhook_payload() ) &&
				$resource_key === $resource->get_key()
			) {
				$payload = $resource->get_webhook_payload()->build( $payload, $resource_key, $resource_id, $webhook_id );
			}
		}
		return $payload;
	}

	/**
	 * Retrieve the list of available WooCommerce Webhook Topics.
	 *
	 * @return array
	 */
	public function get_topics() {
		if ( ! is_null( self::$topics ) ) {
			return self::$topics;
		}
		self::$topics = array();
		// Retrieve WooCommerce' list of built in topics and filter out any unnecessary ones.
		foreach ( $this->retriever->get_woocommerce_webhook_topics() as $topic_key => $topic_name ) {
			$key_parts = explode( '.', $topic_key );
			if ( 2 !== count( $key_parts ) ) {
				continue;
			}
			// WooCommerce Webhook Topic Resources are plural (eg orders instead or order).
			if ( ! array_key_exists( "{$key_parts[0]}s", $this->resource_manager->get_enabled_resources_list() ) ) {
				continue;
			}
			self::$topics[ $topic_key ] = trim( $topic_name );
		}
		return self::$topics;
	}

	/**
	 * Fill a list of available WooCommerce Webhook Topics.
	 *
	 * @param array $topics The topics array.
	 *
	 * @return void
	 */
	public function set_topics( $topics ) {
		self::$topics = $topics;
	}

	/**
	 * Retrieve the list of available WooCommerce Webhook Topics.
	 *
	 * @return boolean
	 */
	public function is_set() {
		return ! is_null( self::$topics );
	}
}
