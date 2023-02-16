<?php

namespace OM4\WooCommerceZapier\Webhook;

use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Webhook\DataStore as WebhookDataStore;

defined( 'ABSPATH' ) || exit;

/**
 * Webhook-related functionality during plugin activation and deactivation:
 * - Pauses existing webhooks during deactivation.
 * - Re-activated paused webhooks during plugin reactivation.
 *
 * @since 2.0.0
 */
class Installer {

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * WebhookDataStore instance.
	 *
	 * @var WebhookDataStore
	 */
	protected $webhook_data_store;

	/**
	 * Constructor.
	 *
	 * @param Logger           $logger             The Logger.
	 * @param WebhookDataStore $webhook_data_store WebhookDataStore instance.
	 */
	public function __construct( Logger $logger, WebhookDataStore $webhook_data_store ) {
		$this->logger             = $logger;
		$this->webhook_data_store = $webhook_data_store;
	}

	/**
	 * Instructs the installer functionality to initialise itself.
	 *
	 * @return void
	 */
	public function initialise() {
		add_action( 'wc_zapier_plugin_deactivate', array( $this, 'pause_zapier_webhooks' ) );
		add_action( 'wc_zapier_db_upgrade_v_9_to_10', array( $this, 'unpause_zapier_webhooks' ) );
		add_action( 'wc_zapier_db_upgrade_v_13_to_14', array( $this, 'rename_existing_webhooks' ) );
	}

	/**
	 * When a user deactivates the plugin, pause any existing Zapier webhooks so that no data is sent to them
	 * while the plugin is deactivated.
	 *
	 * @return void
	 */
	public function pause_zapier_webhooks() {
		$webhooks = $this->webhook_data_store->get_active_zapier_webhooks();

		if ( empty( $webhooks ) ) {
			return;
		}

		foreach ( $webhooks as $webhook ) {
			$webhook->set_status( 'paused' );
			$webhook->save();
			$this->logger->info( 'Active Webhook ID %d (%s) set to paused.', array( $webhook->get_id(), $webhook->get_name() ) );
		}
		$this->logger->info( '%d active webhook(s) paused.', array( count( $webhooks ) ) );
	}

	/**
	 * When a user activates the plugin, unpause any paused Zapier webhooks so that data will be
	 * (once again) sent to them.
	 *
	 * @return void
	 */
	public function unpause_zapier_webhooks() {
		// look for paused webhook in pre-2.3.0 format webhooks.
		$old_webhooks = $this->webhook_data_store->get_paused_zapier_webhooks( 'Zapier #' );
		// look for new (2.3.0) format webhooks.
		$new_webhooks = $this->webhook_data_store->get_paused_zapier_webhooks();
		$webhooks     = \array_merge( $old_webhooks, $new_webhooks );

		if ( empty( $webhooks ) ) {
			return;
		}

		foreach ( $webhooks as $webhook ) {
			$webhook->set_status( 'active' );
			$webhook->save();
			$this->logger->info( 'Paused Webhook ID %d (%s) set to active.', array( $webhook->get_id(), $webhook->get_name() ) );
		}
		$this->logger->info( '%d paused webhook(s) reactivated.', array( count( $webhooks ) ) );
	}

	/**
	 * Rename any existing WooCommerce Zapier webhooks to a new standardised name.
	 *
	 * Executed when users upgraded to version 2.3.0.
	 *
	 * @since 2.3.0
	 *
	 * @return void
	 */
	public function rename_existing_webhooks() {
		$webhooks = $this->webhook_data_store->get_zapier_webhooks( 'Zapier #' );

		if ( empty( $webhooks ) ) {
			return;
		}

		foreach ( $webhooks as $webhook ) {
			$name = $webhook->get_name();
			$webhook->set_name( 'WooCommerce Zapier' );
			$webhook->save();
			$this->logger->warning(
				'%s Webhook ID %d (%s) renamed to `WooCommerce Zapier`.',
				array( $webhook->get_status(), $webhook->get_id(), $name )
			);
		}
		$this->logger->warning( '%d webhook(s) renamed to `WooCommerce Zapier`.', array( count( $webhooks ) ) );
	}
}
