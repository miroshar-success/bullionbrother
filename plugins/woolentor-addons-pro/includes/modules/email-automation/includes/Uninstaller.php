<?php
/**
 * Uninstaller.
 */

namespace WLEA;

/**
 * Class.
 */
class Uninstaller {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->uninstaller_init();
    }

    /**
     * Init uninstaller.
     */
    protected function uninstaller_init() {
        $this->drop_database_tables();

        update_option( 'wlea_activator_inited', false );
    }

	/**
	 * Drop database tables.
	 */
	protected function drop_database_tables() {
		global $wpdb;

		$tables = array(
            "{$wpdb->prefix}wlea_workflow_events",
            "{$wpdb->prefix}wlea_scheduled_tasks",
            "{$wpdb->prefix}wlea_performed_tasks",
        );

		foreach ( $tables as $table ) {
			$wpdb->query( "DROP TABLE IF EXISTS {$table}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
	}

}