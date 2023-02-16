<?php

namespace OM4\WooCommerceZapier\Plugin;

use OM4\WooCommerceZapier\Exception\InvalidImplementationException;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Plugin\Bookings\BookingResource;
use OM4\WooCommerceZapier\Plugin\Definition as PluginDefinition;

defined( 'ABSPATH' ) || exit;

/**
 * Base implementation of a Third Party plugin that is supported by WooCommerce Zapier.
 *
 * Note: any class extending this abstract class must:
 * - define the `PLUGIN_NAME` constant.
 * - define the `MINIMUM_SUPPORTED_VERSION` constant.
 * - Set the `resource` property.
 * - Set the `logger` property.
 * - Override the is_active() method.
 *
 * @since 2.2.0
 */
abstract class Base implements PluginDefinition {

	/**
	 * Name of the third party plugin.
	 */
	const PLUGIN_NAME = '';

	/**
	 * The minimum version that this plugin supports.
	 */
	const MINIMUM_SUPPORTED_VERSION = '';

	/**
	 * The FQN of the third party plugin's Resource Definition.
	 *
	 * Class must be implement `OM4\WooCommerceZapier\WooCommerceResource\Definition`.
	 *
	 * @var class-string
	 */
	protected $resource;

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Instructs the functionality to initialise itself.
	 *
	 * @return bool
	 * @throws InvalidImplementationException If logger instance is not set.
	 */
	public function initialise() {
		if ( ! $this->is_active() ) {
			// Plugin not active.
			return false;
		}
		if ( ! $this->is_supported_version() ) {
			// Plugin not running a supported version.
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
			if ( empty( $this->logger ) ) {
				throw new InvalidImplementationException( 'Logger instance must be set.' );
			}
			$this->logger->alert(
				// Translators: 1: Plugin Name. 2: Plugin Version. 3: Minimum Support Plugin Version.
				'%s plugin version (%s) is less than %s',
				array( static::PLUGIN_NAME, $this->get_plugin_version(), static::MINIMUM_SUPPORTED_VERSION )
			);
			return false;
		}

		add_filter( 'wc_zapier_additional_resource_classes', array( $this, 'wc_zapier_additional_resource_classes' ) );
		add_filter( 'rest_endpoints', array( $this, 'filter_rest_endpoints' ) );
		return true;
	}

	/**
	 * Whether or not the running a version of this plugin is newer than our minimum supported version.
	 *
	 * @return bool
	 */
	protected function is_supported_version() {
		return version_compare( $this->get_plugin_version(), static::MINIMUM_SUPPORTED_VERSION, '>=' );
	}

	/**
	 * Whether not the third party plugin is active.
	 *
	 * @return bool
	 */
	protected function is_active() {
		return false;
	}

	/**
	 * Remove REST API endpoints that are not required by this third party plugin.
	 *
	 * @param array $endpoints Registered WP REST API endpoints.
	 */
	public function filter_rest_endpoints( $endpoints ) {
		return $endpoints;
	}

	/**
	 * Adds the third party plugin's Resource class to the WC Zapier Plugins' Resource Manager.
	 *
	 * @param array $resources Resource Class Name(s).
	 *
	 * @throws InvalidImplementationException If resource is not set.
	 */
	public function wc_zapier_additional_resource_classes( $resources ) {
		if ( empty( $this->resource ) ) {
			throw new InvalidImplementationException( 'Resource must be defined.' );
		}
		$resources[] = $this->resource;
		return $resources;
	}

	/**
	 * Displays a message if the user isn't using a supported version of the plugin.
	 *
	 * @return void
	 */
	public function admin_notice() {
		?>
		<div id="message" class="error">
			<p>
				<?php
				// Translators: 1: Plugin Name. 2: Supported Version Number. 3: Plugin Name.
				echo esc_html( sprintf( __( 'WooCommerce Zapier is only compatible with %1$s version %2$s or later. Please update %3$s.', 'woocommerce-zapier' ), static::PLUGIN_NAME, static::MINIMUM_SUPPORTED_VERSION, static::PLUGIN_NAME ) );
				?>
			</p>
		</div>
		<?php
	}

}
