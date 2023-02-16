<?php

namespace OM4\WooCommerceZapier\Webhook;

use WC_Webhook;

defined( 'ABSPATH' ) || exit;

/**
 * Loads WooCommerce's list of available Webhook Topics.
 *
 * WC doesn't provide a standard function to do this, so we need to manually
 * load a WooCommerce admin settings page in order to access the list of topics.
 *
 * @since 2.0.0
 */
class TopicsRetriever {

	/**
	 * List of WooCommerce Webhook Topics.
	 *
	 * @var array
	 */
	private $webhook_topics = array();

	/**
	 *
	 * Executed by WooCommerce's `woocommerce_webhook_topics` filter.
	 *
	 * @internal
	 *
	 * @param array $topics List of WooCommerce Webhook Topics.
	 *
	 * @return mixed
	 */
	public function woocommerce_webhook_topics( $topics ) {
		$this->webhook_topics = $topics;
		return $topics;
	}

	/**
	 * Get WooCommerce's list of webhook topics.
	 *
	 * @return array
	 */
	public function get_woocommerce_webhook_topics() {
		add_filter( 'woocommerce_webhook_topics', array( $this, 'woocommerce_webhook_topics' ), 1000000 );
		$this->load_webhook_topic_definitions();
		remove_filter( 'woocommerce_webhook_topics', array( $this, 'woocommerce_webhook_topics' ), 1000000 );
		return $this->webhook_topics;
	}

	/**
	 * Load WooCommerce's webhook edit screen, in order to access it's array of webhook topics.
	 *
	 * @return void
	 */
	protected function load_webhook_topic_definitions() {
		global $webhook;
		$webhook = new WC_Webhook();
		$file    = \WC()->plugin_path() . '/includes/admin/settings/views/html-webhooks-edit.php';

		if ( ! file_exists( $file ) ) {
			// TODO: log this as an Error.
			return;
		}
		ob_start();
		include $file;
		ob_get_contents();
		ob_end_clean();
	}
}
