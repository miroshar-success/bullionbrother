<?php
/**
 * Activator.
 */

namespace WLEA;

/**
 * Class.
 */
class Activator {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->activator_init();
    }

    /**
     * Init activator.
     */
    protected function activator_init() {
        $key = 'wlea_activator_inited';
        $inited = get_option( $key, false );

        if ( true === rest_sanitize_boolean( $inited ) ) {
            return;
        }

        $this->create_database_tables();

        update_option( $key, true );
    }

    /**
     * Create database tables.
     */
    protected function create_database_tables() {
        $this->create_database_workflow_events_table();
        $this->create_database_scheduled_tasks_table();
        $this->create_database_performed_tasks_table();
    }

    /**
     * Create database workflow events table.
     */
    protected function create_database_workflow_events_table() {
        global $wpdb;

        $show_errors = $wpdb->hide_errors();
        $table_name  = $wpdb->prefix . 'wlea_workflow_events';
        $collate     = $wpdb->has_cap( 'collation' ) ? $wpdb->get_charset_collate() : '';

        $create_ddl = "CREATE TABLE $table_name (
            `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `event` VARCHAR(255) NOT NULL,
            `workflow_id` BIGINT(20) UNSIGNED NOT NULL,
            `active` TINYINT(1) NOT NULL,
            `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            `modified_date_gmt` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            `added_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            `added_date_gmt` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`ID`)
        ) $collate;";

        $exists = $this->maybe_create_table( $table_name, $create_ddl );

        if ( $show_errors ) {
            $wpdb->show_errors();
        }

        if ( ! $exists ) {
            return $this->add_create_table_notice( $table_name );
        }
    }

    /**
     * Create database scheduled tasks table.
     */
    protected function create_database_scheduled_tasks_table() {
        global $wpdb;

        $show_errors = $wpdb->hide_errors();
        $table_name  = $wpdb->prefix . 'wlea_scheduled_tasks';
        $collate     = $wpdb->has_cap( 'collation' ) ? $wpdb->get_charset_collate() : '';

        $create_ddl = "CREATE TABLE $table_name (
            `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `action` VARCHAR(255) NOT NULL,
            `event` VARCHAR(255) NOT NULL,
            `recipient` VARCHAR(255) NOT NULL,
            `template` BIGINT(20) UNSIGNED NOT NULL,
            `wait_for` BIGINT(20) UNSIGNED NOT NULL,
            `elements` LONGTEXT NOT NULL,
            `schedule_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            `schedule_date_gmt` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            `active` TINYINT(1) NOT NULL,
            `tried` BIGINT(20) UNSIGNED NOT NULL,
            `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            `modified_date_gmt` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            `added_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            `added_date_gmt` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`ID`)
        ) $collate;";

        $exists = $this->maybe_create_table( $table_name, $create_ddl );

        if ( $show_errors ) {
            $wpdb->show_errors();
        }

        if ( ! $exists ) {
            return $this->add_create_table_notice( $table_name );
        }
    }

    /**
     * Create database performed tasks table.
     */
    protected function create_database_performed_tasks_table() {
        global $wpdb;

        $show_errors = $wpdb->hide_errors();
        $table_name  = $wpdb->prefix . 'wlea_performed_tasks';
        $collate     = $wpdb->has_cap( 'collation' ) ? $wpdb->get_charset_collate() : '';

        $create_ddl = "CREATE TABLE $table_name (
            `ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `task_id` BIGINT(20) UNSIGNED NOT NULL,
            `action` VARCHAR(255) NOT NULL,
            `event` VARCHAR(255) NOT NULL,
            `recipient` VARCHAR(255) NOT NULL,
            `template` BIGINT(20) UNSIGNED NOT NULL,
            `wait_for` BIGINT(20) UNSIGNED NOT NULL,
            `elements` LONGTEXT NOT NULL,
            `schedule_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            `schedule_date_gmt` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            `perform_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            `perform_date_gmt` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            `success` TINYINT(1) NOT NULL,
            `tried` BIGINT(20) UNSIGNED NOT NULL,
            `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            `modified_date_gmt` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            `added_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            `added_date_gmt` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`ID`)
        ) $collate;";

        $exists = $this->maybe_create_table( $table_name, $create_ddl );

        if ( $show_errors ) {
            $wpdb->show_errors();
        }

        if ( ! $exists ) {
            return $this->add_create_table_notice( $table_name );
        }
    }

    /**
     * Creates a table in the database, if it doesn't already exist.
     */
    protected function maybe_create_table( $table_name, $create_ddl ) {
        global $wpdb;

        $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

        if ( $wpdb->get_var( $query ) === $table_name ) {
            return true;
        }

        // Didn't find it, so try to create it.
        $wpdb->query( $create_ddl );

        // We cannot directly tell that whether this succeeded!
        if ( $wpdb->get_var( $query ) === $table_name ) {
            return true;
        }

        return false;
    }

    /**
     * Add a notice if table creation fails.
     */
    protected function add_create_table_notice( $table_name ) {
        add_action( 'admin_notices', function() use ( $table_name ) {
            echo '<div class="error"><p>';
            printf(
                /* Translators: %1$s table name, %2$s database user, %3$s database name. */
                esc_html__( 'WooLentor %1$s table creation failed. Does the %2$s user have CREATE privileges on the %3$s database?', 'woolentor-pro' ),
                '<code>' . esc_html( $table_name ) . '</code>',
                '<code>' . esc_html( DB_USER ) . '</code>',
                '<code>' . esc_html( DB_NAME ) . '</code>'
            );
            echo '</p></div>';
        } );
    }

}