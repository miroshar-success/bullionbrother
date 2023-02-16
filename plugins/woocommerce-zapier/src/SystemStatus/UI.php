<?php

namespace OM4\WooCommerceZapier\SystemStatus;

use OM4\WooCommerceZapier\Auth\KeyDataStore;
use OM4\WooCommerceZapier\Installer;
use OM4\WooCommerceZapier\Plugin;
use OM4\WooCommerceZapier\Settings;
use OM4\WooCommerceZapier\TaskHistory\TaskDataStore;
use OM4\WooCommerceZapier\Webhook\DataStore as WebhookDataStore;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

defined( 'ABSPATH' ) || exit;

/**
 * Adds additional WC Zapier related information to the bottom of the
 * WooCommerce System Status screen and report.
 *
 * @since 2.0.0
 */
class UI {

	/**
	 * Settings instance.
	 *
	 * @var Settings
	 */
	protected $settings;

	/**
	 * TaskDataStore instance.
	 *
	 * @var TaskDataStore
	 */
	protected $task_data_store;

	/**
	 * SessionAuthenticate instance.
	 *
	 * @var KeyDataStore
	 */
	protected $key_data_store;

	/**
	 * WebhookDataStore instance.
	 *
	 * @var WebhookDataStore
	 */
	protected $webhook_data_store;

	/**
	 * Installer instance.
	 *
	 * @var Installer
	 */
	protected $installer;

	/**
	 * UI constructor.
	 *
	 * @param Settings         $settings Settings instance.
	 * @param TaskDataStore    $task_data_store TaskDataStore instance.
	 * @param KeyDataStore     $key_data_store KeyDataStore instance.
	 * @param WebhookDataStore $webhook_data_store WebhookDataStore instance.
	 * @param Installer        $installer Installer instance.
	 */
	public function __construct(
		Settings $settings,
		TaskDataStore $task_data_store,
		KeyDataStore $key_data_store,
		WebhookDataStore $webhook_data_store,
		Installer $installer
	) {
		$this->settings           = $settings;
		$this->task_data_store    = $task_data_store;
		$this->key_data_store     = $key_data_store;
		$this->webhook_data_store = $webhook_data_store;
		$this->installer          = $installer;
	}

	/**
	 * Instructs the functionality to initialise itself.
	 *
	 * @return void
	 */
	public function initialise() {
		add_action(
			'woocommerce_system_status_report',
			array( $this, 'woocommerce_system_status_report' ),
			100000
		);
	}

	/**
	 * Output the WooCommerce Zapier System Status information.
	 * Executed during the `woocommerce_system_status_report` hook.
	 *
	 * @return void
	 */
	public function woocommerce_system_status_report() {
		$section_title = __( 'WooCommerce Zapier', 'woocommerce-zapier' );
		/**
		 * Override the rows of data that is displayed on the status screen.
		 *
		 * @internal
		 * @since 2.0.0
		 *
		 * @param array $rows The rows of information to be displayed.
		 */
		$rows = apply_filters( 'wc_zapier_system_status_rows', $this->wc_zapier_system_status_rows() );

		include plugin_dir_path( WC_ZAPIER_PLUGIN_FILE ) . 'templates/system-status.php';
	}

	/**
	 * Retrieve our own information for display on the WooCommerce Status screen.
	 *
	 * @return array
	 */
	protected function wc_zapier_system_status_rows() {
		return array_merge(
			$this->get_status_rows_settings(),
			$this->get_status_rows_api(),
			$this->get_status_rows_task_history(),
			$this->get_status_rows_triggers(),
			$this->get_status_rows_actions()
		);
	}

	/**
	 * Get the Status Screen Rows that relate to the plugin's settings.
	 *
	 * @return array
	 */
	protected function get_status_rows_settings() {
		$rows[] = array(
			'name'    => __( 'Database Version', 'woocommerce-zapier' ),
			'success' => $this->installer->is_up_to_date(),
			'note'    => (string) $this->installer->get_db_version(),
		);
		$rows[] = array(
			'name' => __( 'Detailed Logging Enabled', 'woocommerce-zapier' ),
			'note' => $this->settings->is_detailed_logging_enabled() ? __( 'Yes', 'woocommerce-zapier' ) : __( 'No', 'woocommerce-zapier' ),
		);

		$rows[] = array(
			'name'    => __( 'Legacy Mode Disabled', 'woocommerce-zapier' ),
			'success' => ! $this->settings->is_legacy_mode_enabled(),
			'note'    => $this->settings->is_legacy_mode_enabled() ? __( 'No', 'woocommerce-zapier' ) : __( 'Yes', 'woocommerce-zapier' ),
		);
		return $rows;
	}

	/**
	 * Get the Status Screen Rows that UI to the plugin's API functionality.
	 *
	 * @return array
	 */
	protected function get_status_rows_api() {
		// Check if is_ssl() returns true.
		// Required because the WooCommerce REST API will only perform basic/query authentication if is_ssl() is true.
		// Ref: https://github.com/woocommerce/woocommerce/blob/4.0.1/includes/class-wc-rest-authentication.php#L81.
		$rows[] = array(
			'name'    => __( 'SSL', 'woocommerce-zapier' ),
			'help'    => __( 'Whether or not your store passes the WordPress is_ssl() check. This is required in order for authentication to work correctly.', 'woocommerce-zapier' ),
			'success' => is_ssl(),
			// Translators: 1: Opening Link HTML Tag. 2: Closing Link HTML Tag.
			'note'    => is_ssl() ? __( 'Yes', 'woocommerce-zapier' ) : sprintf( __( 'No. Authentication will not work until SSL is configured correctly. Please %1$ssee here for details on how to fix this%2$s.', 'woocommerce-zapier' ), '<a href=" ' . Plugin::DOCUMENTATION_URL . 'troubleshooting/#ssl-check-failing" target="_blank">', '</a>' ),
		);

		$count  = $this->key_data_store->count();
		$rows[] = array(
			'name'    => __( 'REST API Authentication Key(s)', 'woocommerce-zapier' ),
			'help'    => __( 'The number of Zapier-specific WooCommerce REST API Authentication Keys.', 'woocommerce-zapier' ),
			'success' => $count > 0,
			'note'    => (string) $count,
		);
		return $rows;
	}

	/**
	 * Get the Status Screen Rows that relate to the plugin's Task History functionality.
	 *
	 * @return array
	 */
	protected function get_status_rows_task_history() {
		$count  = $this->task_data_store->get_tasks_count();
		$rows[] = array(
			'name'    => __( 'Task History Record Count', 'woocommerce-zapier' ),
			'help'    => __( 'Indicates how many times that data has been sent to from WooCommerce to a Zapier Zap, and how many times data has been sent to WooCommerce from a Zapier Zap.', 'woocommerce-zapier' ),
			'success' => $count > 0,
			'note'    => (string) $count,
		);
		return $rows;
	}

	/**
	 * Get the Status Screen Rows that relate to the plugin's Trigger functionality.
	 *
	 * These rows include a list of existing WC Zapier Webhooks
	 *
	 * @return array
	 */
	protected function get_status_rows_triggers() {
		$rows                    = array();
		$webhooks                = $this->webhook_data_store->get_zapier_webhooks();
		$webhook_delivery_counts = $this->task_data_store->get_trigger_task_count();
		foreach ( $webhooks as $webhook ) {
			$count = 0;
			foreach ( $webhook_delivery_counts as $i => $webhook_count ) {
				if ( $webhook->get_id() === $webhook_count['webhook_id'] ) {
					$count = $webhook_count['count'];
					unset( $webhook_delivery_counts[ $i ] );
					break;
				}
			}

			$rows[] = array(
				// Translators: 1: Webhook ID.
				'name' => sprintf( __( 'Webhook #%1$s', 'woocommerce-zapier' ), $webhook->get_id() ),
				'note' => sprintf(
					// Both line breaks and <br /> tags are used below so that the HTML output (and the text output) both show a new line.
					// Translators: 1: Webhook Name. 2: Webhook Status. 3: Webhook Topic. 4: Webhook Delivery Count.
					__(
						'%1$s<br />
- Status: %2$s<br />
- Trigger: %3$s<br />
- Delivery Count: %4$s',
						'woocommerce-zapier'
					),
					$webhook->get_name(),
					$webhook->get_status(),
					$webhook->get_topic(),
					(string) $count
				),
			);
		}
		if ( ! empty( $webhook_delivery_counts ) ) {
			$count    = 0;
			$iterator = new RecursiveIteratorIterator( new RecursiveArrayIterator( $webhook_delivery_counts ) );
			foreach ( $iterator as $key => $value ) {
				if ( 'count' === $key ) {
					$count += $value;
				}
			}
			$rows[] = array(
				'name' => __( 'Deleted Webhook Delivery Count', 'woocommerce-zapier' ),
				'note' => $count,
			);
		}
		return $rows;
	}

	/**
	 * Get the Status Screen Rows that relate to the plugin's Actions functionality.
	 *
	 * @return array
	 */
	protected function get_status_rows_actions() {
		$rows          = array();
		$action_counts = $this->task_data_store->get_action_task_counts();
		// TODO: display all 5 resources, not just the ones that have we have task records for.
		// Can be implemented once we have a centralised way of getting the list of supported WooCommerce resources.
		foreach ( $action_counts as $action_count ) {
			$resource_type = ucfirst( $action_count['resource_type'] );
			$rows[]        = array(
				// Translators: 1: Resource Name.
				'name' => sprintf( __( '%1$s Action Count', 'woocommerce-zapier' ), $resource_type ),
				'note' => (string) $action_count['count'],
				// Translators: 1: Resource Name.
				'help' => sprintf( __( 'The number of times that a %1$s has been created or updated in WooCommerce from a Zapier Zap.', 'woocommerce-zapier' ), $resource_type ),
			);
		}
		return $rows;
	}
}
