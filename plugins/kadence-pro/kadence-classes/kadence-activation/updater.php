<?php
/**
 * Class file to check for active license
 *
 * @package Kadence Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load activation API.
require_once KTP_PATH . 'dist/dashboard/class-kadence-pro-dashboard.php';
if ( is_multisite() ) {
	$show_local_activation = apply_filters( 'kadence_activation_individual_multisites', false );
	if ( $show_local_activation ) {
		if ( 'Activated' === get_option( 'kadence_pro_api_manager_activated' ) ) {
			$kadence_pro_updater = Kadence_Update_Checker::buildUpdateChecker( 'https://kernl.us/api/v1/updates/5eee71ef08f6d93d2b905870/', KTP_PATH . 'kadence-pro.php', 'kadence-pro' );
		}
	} else {
		if ( 'Activated' === get_site_option( 'kadence_pro_api_manager_activated' ) ) {
			$kadence_pro_updater = Kadence_Update_Checker::buildUpdateChecker( 'https://kernl.us/api/v1/updates/5eee71ef08f6d93d2b905870/', KTP_PATH . 'kadence-pro.php', 'kadence-pro' );
		}
	}
} elseif ( 'Activated' === get_option( 'kadence_pro_api_manager_activated' ) ) {
	$kadence_pro_updater = Kadence_Update_Checker::buildUpdateChecker( 'https://kernl.us/api/v1/updates/5eee71ef08f6d93d2b905870/', KTP_PATH . 'kadence-pro.php', 'kadence-pro' );
}
