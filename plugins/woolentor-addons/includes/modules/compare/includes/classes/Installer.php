<?php
namespace EverCompare;
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
        $this->create_page();
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
                sanitize_title_with_dashes( _x( 'evercompare', 'page_slug', 'ever-compare' ) ),
                '',
                __( 'EverCompare', 'ever-compare' ),
                '<!-- wp:shortcode -->[evercompare_table]<!-- /wp:shortcode -->'
            );
            if( $create_page_id ){
                woolentor_update_option( 'ever_compare_table_settings_tabs','compare_page', $create_page_id );
            }
        }
    }


}