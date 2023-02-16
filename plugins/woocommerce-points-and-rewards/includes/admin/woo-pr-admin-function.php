<?php
/**
 * Handles to add plugin settings in Woocommerce -> Settings
 * 
 * @package WooCommerce - Points and Rewards
 * @since 1.0.0
 */

function woo_pr_admin_settings_tab($settings) {

    // Add settings in array
    $settings[] = include( WOO_PR_ADMIN_DIR . '/class-woo-pr-admin-settings-tabs.php' );

    return $settings; // Return
}

	