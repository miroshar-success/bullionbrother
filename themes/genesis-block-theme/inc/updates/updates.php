<?php
/**
 * Genesis Blocks Theme update functionality.
 *
 * @package genesis-block-theme
 */

declare(strict_types=1);

namespace GenesisBlockTheme\Updates;

use stdClass;

/**
 * Checks the WPE Product Info API for new versions of the plugin
 * and returns the data required to update this plugin.
 *
 * @param object $update_themes_transient_data WordPress update object.
 *
 * @return object $update_themes_transient_data An updated object if an update exists, default object if not.
 */
function check_for_updates( $update_themes_transient_data ) {

	// No update object exists. Return early.
	if ( empty( $update_themes_transient_data ) ) {
		return $update_themes_transient_data;
	}

	$current_theme      = wp_get_theme();
	$new_theme_response = get_product_info();

	if ( ! isset( $new_theme_response->new_version ) ) {
		return $update_themes_transient_data;
	}

	// Only update the response if there's a newer version, otherwise WP shows an update notice for the same version.
	if ( version_compare( $current_theme->version, (string) $new_theme_response->new_version, '<' ) ) {
		$update_themes_transient_data->response[ get_template() ] = (array) $new_theme_response;
	}

	return $update_themes_transient_data;
}
add_filter( 'pre_set_site_transient_update_themes', __NAMESPACE__ . '\check_for_updates' );

/**
 * Fetches and returns the theme info from the WPE product info API.
 *
 * @return stdClass
 */
function get_product_info() {

	// Check for a cached response before making an API call.
	$cached_new_theme_response = get_transient( 'genesis_block_theme_product_info' );

	if ( false !== $cached_new_theme_response ) {
		return $cached_new_theme_response;
	}

	$current_theme = wp_get_theme();

	$request_args = [
		'timeout'    => ( ( defined( 'DOING_CRON' ) && DOING_CRON ) ? 30 : 3 ),
		'user-agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' ),
		'body'       => [
			'version' => $current_theme->version,
		],
	];

	$new_theme_response = wp_remote_get( 'https://wp-product-info.wpesvc.net/v1/themes/genesis-block-theme?format=json', $request_args );

	if ( is_wp_error( $new_theme_response ) || 200 !== wp_remote_retrieve_response_code( $new_theme_response ) ) {

		// Save the error code so we can use it elsewhere to display messages.
		if ( is_wp_error( $new_theme_response ) ) {
			update_option( 'genesis_block_theme_product_info_api_error', $new_theme_response->get_error_code(), false );
		} else {
			$new_theme_response_body = json_decode( wp_remote_retrieve_body( $new_theme_response ), false );
			$error_code              = ! empty( $new_theme_response_body->error_code ) ? $new_theme_response_body->error_code : 'unknown';
			update_option( 'genesis_block_theme_product_info_api_error', $error_code, false );
		}

		// Cache an empty object for 5 minutes to give the product info API time to recover.
		$new_theme_response = new stdClass();

		set_transient( 'genesis_block_theme_product_info', $new_theme_response, MINUTE_IN_SECONDS * 5 );

		return $new_theme_response;
	}

	// Delete any existing API error codes since we have a valid API response.
	delete_option( 'genesis_block_theme_product_info_api_error' );

	$new_theme_response = json_decode( wp_remote_retrieve_body( $new_theme_response ) );

	if ( ! isset( $new_theme_response->new_version ) || ! isset( $new_theme_response->download_link ) ) {
		return $cached_new_theme_response;
	}

	$new_theme_response->new_version = $new_theme_response->new_version;
	$new_theme_response->package     = $new_theme_response->download_link;
	$new_theme_response->url         = isset( $new_theme_response->url ) ? $new_theme_response->url : 'https://www.studiopress.com/genesis-block-theme/';
	$new_theme_response->theme       = get_template();

	// Cache the response for 12 hours.
	set_transient( 'genesis_block_theme_product_info', $new_theme_response, HOUR_IN_SECONDS * 12 );

	return $new_theme_response;
}

/**
 * Checks for plugin update API errors and shows
 * a message on the Dashboard > Updates page if errors exist.
 */
function handle_update_error_on_updates_page() {
	$api_error = get_option( 'genesis_block_theme_product_info_api_error', false );
	if ( empty( $api_error ) ) {
		return;
	}

	add_action(
		'admin_notices',
		function() use ( $api_error ) {
			printf( '<div class="error"><p>%s</p></div>', esc_html( api_error_notice_text( $api_error ) ) );
		}
	);
}
add_action( 'load-update-core.php', __NAMESPACE__ . '\handle_update_error_on_updates_page', 0 );

/**
 * Returns the text to be displayed to the user based on the
 * error code received from the Product Info Service API.
 *
 * @param string $reason The reason/error code received the API.
 *
 * @return string
 */
function api_error_notice_text( $reason ) {

	switch ( $reason ) {
		case 'asset-unknown':
			return __( 'The product you requested information for is unknown. Please contact support.', 'genesis-block-theme' );

		default:
			return __( 'There was an unknown error connecting to the update service for the Genesis Block Theme. This issue could be temporary. Please contact support if this error persists.', 'genesis-block-theme' );
	}
}
