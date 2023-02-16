<?php

namespace OM4\WooCommerceZapier\Plugin\Bookings;

use OM4\WooCommerceZapier\API\API;
use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Plugin\Bookings\BookingResource;
use OM4\WooCommerceZapier\Plugin\Base;

defined( 'ABSPATH' ) || exit;

/**
 * Functionality that is enabled when the WooCommerce Bookings plugin is active.
 *
 * @since 2.2.0
 */
class Plugin extends Base {

	/**
	 * FeatureChecker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $checker;

	/**
	 * FeatureChecker instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Name of the third party plugin.
	 */
	const PLUGIN_NAME = 'WooCommerce Bookings';

	/**
	 * The minimum WooCommerce Bookings version that this plugin supports.
	 */
	const MINIMUM_SUPPORTED_VERSION = '1.15.35';

	/**
	 * Constructor.
	 *
	 * @param FeatureChecker $checker FeatureChecker instance.
	 * @param Logger         $logger Logger instance.
	 */
	public function __construct( FeatureChecker $checker, Logger $logger ) {
		$this->checker  = $checker;
		$this->logger   = $logger;
		$this->resource = BookingResource::class;
	}

	/**
	 * Instructs the Bookings functionality to initialise itself.
	 *
	 * @return bool
	 */
	public function initialise() {
		if ( ! parent::initialise() ) {
			return false;
		}

		add_action(
			'woocommerce_booking_status_changed',
			function ( $transitioned_from, $transitioned_to, $booking_id ) {
				// Booking status changed.
				/**
				 * Execute the WooCommerce Zapier handler for woocommerce_booking_status_changed.
				 *
				 * @param int $booking_id Booking ID.
				 *
				 * @since 2.2.0
				 *
				 * @internal
				 */
				do_action( 'wc_zapier_woocommerce_booking_status_changed', $booking_id );
				if ( 'in-cart' === $transitioned_from && 'cancelled' !== $transitioned_to ) {
					// Booking ordered.
					/**
					 * Execute the WooCommerce Zapier handler for woocommerce_booking_ordered.
					 * Run only when a status transitioned from `in-cart` and not to `cancelled`.
					 *
					 * @param int $booking_id Booking ID.
					 *
					 * @since 2.2.0
					 *
					 * @internal
					 */
					do_action( 'wc_zapier_woocommerce_booking_ordered', $booking_id );
				}
			},
			10,
			3
		);

		add_action(
			'trashed_post',
			function ( $booking_id ) {
				// Booking deleted. Should trigger when a booking is trashed but not force deleted (similar to orders).
				if ( get_wc_booking( $booking_id ) ) {
					/**
					 * Execute the WooCommerce Zapier handler for wp_trash_post.
					 * Run for booking only.
					 *
					 * @param int $booking_id Booking ID.
					 *
					 * @since 2.2.0
					 *
					 * @internal
					 */
					do_action( 'wc_zapier_woocommerce_booking_deleted', $booking_id );
				}
			}
		);

		add_action(
			'pre_post_update',
			function ( $booking_id, $data ) {
				// A post is about to be updated in WordPress.
				if ( 'wc_booking' !== $data['post_type'] ) {
					return;
				}
				// Booking updated should not trigger when a booking is trashed or restored (similar to orders).
				$booking = get_wc_booking( $booking_id );
				if ( ! $booking || 'trash' === $data['post_status'] || 'trash' === $booking->get_status() ) {
					return;
				}
				/**
				 * Execute the WooCommerce Zapier handler for pre_post_update.
				 *
				 * @param int $booking_id Booking ID.
				 *
				 * @since 2.2.0
				 *
				 * @internal
				 */
				do_action( 'wc_zapier_woocommerce_booking_updated', $booking_id );
			},
			10,
			2
		);

		add_action(
			'untrashed_post',
			function ( $booking_id ) {
				// Booking restored.
				if ( get_wc_booking( $booking_id ) ) {
					/**
					 * Execute the WooCommerce Zapier handler for untrashed_post.
					 * Run for booking only.
					 *
					 * @param int $booking_id Booking ID.
					 *
					 * @since 2.2.0
					 *
					 * @internal
					 */
					do_action( 'wc_zapier_woocommerce_booking_restored', $booking_id );
				}
			}
		);

		return true;

	}

	/**
	 * Get the WooCommerce Bookings version number.
	 *
	 * @var string
	 */
	public function get_plugin_version() {
		return WC_BOOKINGS_VERSION;
	}

	/**
	 * Remove Bookings endpoints that are not required by WooCommerce Zapier, including:
	 *
	 * - /wc-zapier/v1/bookings/(?P<id>[\d]+)
	 * - /wc-zapier/v1/bookings/ (POST, PUT, PATCH, DELETE)
	 *
	 * @param array $endpoints Registered WP REST API endpoints.
	 *
	 * @return array
	 */
	public function filter_rest_endpoints( $endpoints ) {
		foreach ( $endpoints as $route => $endpoint ) {
			if ( 0 === strpos( $route, '/' . API::REST_NAMESPACE . '/bookings' ) ) {
				// Remove individual access.
				if ( false !== strpos( $route, '/(?P<id>[\d]+)' ) ) {
					unset( $endpoints[ $route ] );
				}

				// Keep only the HTTP GET method.
				foreach ( $endpoint as $index => $entry ) {
					if ( isset( $entry['methods'] ) && 'GET' !== $entry['methods'] ) {
						unset( $endpoints[ $route ][ $index ] );
					}
				}
			}
		}

		return $endpoints;
	}

	/**
	 * Whether not not the user has the WooCommerce Bookings plugin active.
	 *
	 * @return bool
	 */
	protected function is_active() {
		return $this->checker->class_exists( '\WC_Bookings' );
	}

}
