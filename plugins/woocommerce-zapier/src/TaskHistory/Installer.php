<?php

namespace OM4\WooCommerceZapier\TaskHistory;

use OM4\WooCommerceZapier\Helper\WordPressDB;
use OM4\WooCommerceZapier\Logger;

defined( 'ABSPATH' ) || exit;

/**
 * Stores task history for WooCommerce Zapier outgoing data (Triggers),
 * and incoming data (actions).
 *
 * @since 2.0.0
 */
class Installer {

	/**
	 * WordPressDB instance.
	 *
	 * @var WordPressDB
	 */
	protected $wp_db;

	/**
	 * TaskDataStore instance.
	 *
	 * @var TaskDataStore
	 */
	protected $task_data_store;

	/**
	 * Task History database table name.
	 *
	 * @var string
	 */
	protected $db_table;

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Constructor.
	 *
	 * @param Logger        $logger     The Logger.
	 * @param WordPressDB   $wp_db       WordPressDB instance.
	 * @param TaskDataStore $data_store WordPressDB instance.
	 */
	public function __construct( Logger $logger, WordPressDB $wp_db, TaskDataStore $data_store ) {
		$this->logger          = $logger;
		$this->wp_db           = $wp_db;
		$this->db_table        = $data_store->get_table_name();
		$this->task_data_store = $data_store;
	}

	/**
	 * Instructs the installer functionality to initialise itself.
	 *
	 * @return void
	 */
	public function initialise() {
		add_action( 'wc_zapier_db_upgrade_v_5_to_6', array( $this, 'install_database_table' ) );
		add_action( 'wc_zapier_db_upgrade_v_13_to_14', array( $this, 'delete_cron_jobs' ) );
		add_action( 'wc_zapier_db_upgrade_v_13_to_14', array( $this, 'update_messages_to_remove_view_edit_zap_link' ) );
	}

	/**
	 * Installs (or updates) the database table where history is stored.
	 *
	 * @return bool
	 */
	public function install_database_table() {
		$collate = '';

		if ( $this->wp_db->has_cap( 'collation' ) ) {
			$collate = $this->wp_db->get_charset_collate();
		}

		$schema = <<<SQL
CREATE TABLE {$this->db_table} (
  history_id bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  date_time datetime NOT NULL,
  webhook_id bigint UNSIGNED,
  resource_type varchar(32) NOT NULL,
  resource_id bigint UNSIGNED NOT NULL,
  message text NOT NULL,
  type varchar(32) NOT NULL,
  PRIMARY KEY  (history_id)
) $collate
SQL;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$result = dbDelta( $schema );

		if ( ! $this->database_table_exists() ) {
			$this->logger->critical(
				'Error creating history database table (%s). Error: %s',
				array(
					$this->db_table,
					isset( $result[ $this->db_table ] ) ? $result[ $this->db_table ] : '',
				)
			);
			return false;
		}

		return true;

	}

	/**
	 * Delete Task History related Action Scheduler cron job(s).
	 *
	 * Executed during plugin deactivation.
	 *
	 * @return void
	 */
	public function delete_cron_jobs() {
		WC()->queue()->cancel( 'wc_zapier_history_cleanup' );
	}

	/**
	 * Whether or not the Installer database table exists.
	 *
	 * @return bool
	 */
	public function database_table_exists() {
		return $this->db_table === $this->wp_db->get_var( strval( $this->wp_db->prepare( 'SHOW TABLES LIKE %s', $this->db_table ) ) );
	}

	/**
	 * Update all existing messages in the Task History Table,
	 * removing the `View/Edit Zap` link.
	 *
	 * @since 2.3.0
	 *
	 * @return void
	 */
	public function update_messages_to_remove_view_edit_zap_link() {
		// Bulk update all messages, and only keep the message text up to the <br /> tag.
		$result = $this->wp_db->query(
			'UPDATE ' . $this->task_data_store->get_table_name() . " SET `message` = (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(message,'<br />',1),'<br />',-1) AS columName) WHERE message LIKE '%<br />%'"
		);
		$this->logger->info( '%d task history record(s) updated to remove View/Edit Zap link.', array( $result ) );
	}
}
