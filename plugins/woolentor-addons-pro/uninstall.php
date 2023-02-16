<?php
/**
 * Uninstall.
 */

/**
 * Drop Email Automation database tables.
 */
function drop_email_automation_database_tables() {
    include_once plugin_dir_path( __FILE__ ) .'includes/modules/email-automation/email-automation.php';

    new \WLEA\Uninstaller();
}

// Run to remove data tables.
if ( defined( 'WOOLENTOR_REMOVE_DATA_TABLES' ) && ( true === rest_sanitize_boolean( WOOLENTOR_REMOVE_DATA_TABLES ) ) ) {
    drop_email_automation_database_tables();
}