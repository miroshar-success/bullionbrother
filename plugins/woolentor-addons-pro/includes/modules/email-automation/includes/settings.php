<?php
/**
 * Settings.
 */

// Get email from name.
if ( ! function_exists( 'wlea_get_email_from_name' ) ) {
    /**
     * Get email from name.
     */
    function wlea_get_email_from_name() {
        $from_name = woolentor_get_option_pro( 'email_from_name','woolentor_email_automation_settings', '' );
        $from_name = wlea_cast( $from_name, 'text' );

        $from_name = ( 0 < strlen( $from_name ) ? $from_name : wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) );
        $from_name = wlea_cast( $from_name, 'text' );

        return $from_name;
    }
}

// Get email from address.
if ( ! function_exists( 'wlea_get_email_from_address' ) ) {
    /**
     * Get email from address.
     */
    function wlea_get_email_from_address() {
        $from_address = woolentor_get_option_pro( 'email_from_address','woolentor_email_automation_settings', '' );
        $from_address = wlea_cast( $from_address, 'email' );

        $from_address = ( 0 < strlen( $from_address ) ? $from_address : get_option( 'admin_email' ) );
        $from_address = wlea_cast( $from_address, 'email' );

        return $from_address;
    }
}