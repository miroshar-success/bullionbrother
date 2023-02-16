<?php
namespace WishSuite;
/**
 * Installer class
 */
class Installer {

    /**
     * Run the installer
     *
     * @return void
     */
    public function run() {
        $this->create_tables();
        $this->create_page();
    }

    /**
     * [create_tables]
     * @return [void]
     */
    public function create_tables() {
        global $wpdb;
        
        $charset_collate = '';
        if ( $wpdb->has_cap( 'collation' ) ) {
            $charset_collate = $wpdb->get_charset_collate();
        }

        $schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wishsuite_list` (
          `id` bigint( 20 ) unsigned NOT NULL AUTO_INCREMENT,
          `user_id` bigint( 20 ) NULL DEFAULT NULL,
          `product_id` bigint(20) NULL DEFAULT NULL,
          `quantity` int(11) NULL DEFAULT NULL,
          `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) $charset_collate";

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta( $schema );
    }

    /**
     * [create_page] Create page
     * @return [void]
     */
    private function create_page() {
        if ( function_exists( 'WC' ) ) {
            if ( !function_exists( 'wc_create_page' ) ) { 
                require_once WC_ABSPATH . '/includes/admin/wc-admin-functions.php';
            }
            $create_page_id = wc_create_page(
                sanitize_title_with_dashes( _x( 'wishsuite', 'page_slug', 'wishsuite' ) ),
                '',
                __( 'WishSuite', 'wishsuite' ),
                '<!-- wp:shortcode -->[wishsuite_table]<!-- /wp:shortcode -->'
            );
            if( $create_page_id ){
                woolentor_update_option( 'wishsuite_table_settings_tabs','wishlist_page', $create_page_id );
            }
        }
    }

    /**
     * [drop_tables] Delete table
     * @return [void]
     */
    public static function drop_tables() {
        global $wpdb;
        $tables = [
            "{$wpdb->prefix}wishsuite_list",
        ];
        foreach ( $tables as $table ) {
            $wpdb->query( "DROP TABLE IF EXISTS {$table}" );
        }
    }


}