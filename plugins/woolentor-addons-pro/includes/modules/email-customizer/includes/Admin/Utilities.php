<?php
/**
 * Utilities.
 */

namespace Woolentor_Email_Customizer\Admin;

/**
 * Utilities class.
 */
class Utilities {

	/**
     * Utilities constructor.
     */
    public function __construct() {
        $this->disable_query_monitor();
        $this->disable_debug_bar();
    }

    /**
     * Disable query monitor.
     */
    public function disable_query_monitor() {
        add_action( 'wp_footer', function () {
            if ( woolentor_is_email_customizer_template() ) {
                add_filter( 'qm/dispatch/html', '__return_false', 999, 2 );
            }
        }, 999 );
    }

    /**
     * Disable debug bar.
     */
    public function disable_debug_bar() {
        add_filter( 'debug_bar_enable', function ( $value ) {
            if ( woolentor_is_email_customizer_template() ) {
                $value = false;
            }

            return $value;
        }, 999 );
    }

}