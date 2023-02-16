<?php

namespace OM4\WooCommerceZapier\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * Defines a Third Party plugin that is supported by WooCommerce Zapier.
 *
 * @since 2.2.0
 */
interface Definition {

	/**
	 * Instructs the functionality to initialise itself.
	 *
	 * @return bool
	 */
	public function initialise();

	/**
	 * Get the version number of this currently active third party plugin.
	 *
	 * @return string
	 */
	public function get_plugin_version();

	/**
	 * Remove REST API endpoints that are not required by this third party plugin.
	 *
	 * @param array $endpoints Registered WP REST API endpoints.
	 *
	 * @return array
	 */
	public function filter_rest_endpoints( $endpoints );

	/**
	 * Adds the Plugin's Resource class to the WC Zapier Plugins' Resource Manager.
	 *
	 * Executed by the `wc_zapier_additional_resource_classes` filter.
	 *
	 * @param array $resources Resource Class Name(s).
	 *
	 * @return array
	 */
	public function wc_zapier_additional_resource_classes( $resources );
}
