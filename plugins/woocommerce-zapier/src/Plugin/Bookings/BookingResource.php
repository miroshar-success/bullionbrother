<?php

namespace OM4\WooCommerceZapier\Plugin\Bookings;

use OM4\WooCommerceZapier\Helper\FeatureChecker;
use OM4\WooCommerceZapier\Logger;
use OM4\WooCommerceZapier\Plugin\Bookings\Payload;
use OM4\WooCommerceZapier\Plugin\Bookings\V1Controller;
use OM4\WooCommerceZapier\Webhook\Trigger\Trigger;
use OM4\WooCommerceZapier\WooCommerceResource\CustomPostTypeResource;
use WC_Bookings_REST_Booking_Controller;

defined( 'ABSPATH' ) || exit;

/**
 * Definition of the Bookings resource type.
 *
 * This resource is only enabled if WooCommerce Bookings is available.
 *
 * WooCommerce Bookings does not have webhook payload, topic and delivery functionality built-in,
 * so this class implements those.
 *
 * @since 2.2.0
 */
class BookingResource extends CustomPostTypeResource {

	/**
	 * Controller instance.
	 *
	 * @var V1Controller
	 */
	protected $controller;

	/**
	 * Feature Checker instance.
	 *
	 * @var FeatureChecker
	 */
	protected $checker;

	/**
	 * Logger instance.
	 *
	 * @var Logger
	 */
	protected $logger;

	/**
	 * {@inheritDoc}
	 *
	 * @param V1Controller   $controller Controller instance.
	 * @param FeatureChecker $checker    FeatureChecker instance.
	 * @param Logger         $logger     Logger instance.
	 */
	public function __construct( V1Controller $controller, FeatureChecker $checker, Logger $logger ) {
		$this->controller          = $controller;
		$this->checker             = $checker;
		$this->logger              = $logger;
		$this->key                 = 'booking';
		$this->name                = __( 'Booking', 'woocommerce-zapier' );
		$this->metabox_screen_name = 'wc_booking';
	}

	/**
	 * {@inheritDoc}
	 */
	public function is_enabled() {
		return $this->checker->class_exists( WC_Bookings_REST_Booking_Controller::class );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_controller_name() {
		return V1Controller::class;
	}

	/**
	 * Get the Bookings REST API controller's REST API version.
	 *
	 * Bookings uses a REST API v1 payload.
	 *
	 * This is because the Bookings endpoint is a REST API v1 controller, we need to always deliver a v1 payload.
	 *
	 * @inheritDoc
	 */
	public function get_controller_rest_api_version() {
		return 1;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_webhook_triggers() {
		return array(
			/**
			 * Trigger when a booking is created.
			 *
			 * @link https://docs.om4.io/woocommerce-zapier/trigger-rules/#booking-created
			 *
			 * @hook: woocommerce_new_booking
			 * @see \WC_Booking_Data_Store::create()
			 * @param integer $booking_id ID of the booking.
			 *
			 * @return void
			 */
			new Trigger(
				'booking.created',
				__( 'Booking created', 'woocommerce-zapier' ),
				array( 'woocommerce_new_booking' )
			),
			/**
			 * Trigger when a booking is ordered (changes status from `in-cart` to any status except `cancelled`).
			 *
			 * @link https://docs.om4.io/woocommerce-zapier/trigger-rules/#booking-ordered
			 *
			 * @hook: woocommerce_booking_status_changed
			 * @see \WC_Booking::status_transitioned_handler()
			 * @param integer $booking_id ID of the booking.
			 *
			 * @return void
			 */
			new Trigger(
				'booking.ordered',
				__( 'Booking ordered', 'woocommerce-zapier' ),
				array( 'wc_zapier_woocommerce_booking_ordered' )
			),
			/**
			 * Trigger when a booking is deleted (trashed).
			 *
			 * @link https://docs.om4.io/woocommerce-zapier/trigger-rules/#booking-deleted
			 *
			 * @hook: trashed_post
			 * @see wp_trash_post()
			 * @param integer $booking_id ID of the booking.
			 *
			 * @return void
			 */
			new Trigger(
				'booking.deleted',
				__( 'Booking deleted', 'woocommerce-zapier' ),
				array( 'wc_zapier_woocommerce_booking_deleted' )
			),
			/**
			 * Trigger when a booking is restored from the trash.
			 *
			 * @link https://docs.om4.io/woocommerce-zapier/trigger-rules/#booking-restored
			 *
			 * @hook: untrashed_post
			 * @see \wp_untrash_post()
			 * @param integer $booking_id ID of the booking.
			 *
			 * @return void
			 */
			new Trigger(
				'booking.restored',
				__( 'Booking restored', 'woocommerce-zapier' ),
				array( 'wc_zapier_woocommerce_booking_restored' )
			),
			/**
			 * Trigger when a booking changes status.
			 *
			 * @link https://docs.om4.io/woocommerce-zapier/trigger-rules/#booking-status-changed
			 *
			 * @hook: woocommerce_booking_status_changed
			 * @see \WC_Booking::status_transitioned_handler()
			 * @param int $booking_id Booking ID.
			 *
			 * @return void
			 */
			new Trigger(
				'booking.status_changed',
				__( 'Booking status changed', 'woocommerce-zapier' ),
				array( 'wc_zapier_woocommerce_booking_status_changed' )
			),
			/**
			 * Trigger when a booking is cancelled.
			 *
			 * @link https://docs.om4.io/woocommerce-zapier/trigger-rules/#booking-cancelled
			 *
			 * @hook: woocommerce_booking_{new_status}
			 * @see \WC_Booking::status_transitioned_handler()
			 * @param integer $booking_id ID of the booking.
			 *
			 * @return void
			 */
			new Trigger(
				'booking.cancelled',
				__( 'Booking cancelled', 'woocommerce-zapier' ),
				array( 'woocommerce_booking_cancelled' )
			),
			/**
			 * Trigger when a booking is updated (including when it is first created).
			 *
			 * @link https://docs.om4.io/woocommerce-zapier/trigger-rules/#booking-updated
			 *
			 * @hook: save_post_wc_booking
			 * @see wp_insert_post()
			 * @param integer $post_ID ID of the booking.
			 * @param WP_Post $post    Booking object.
			 * @param bool    $update  Whether this is an existing post being updated.
			 *
			 * @return void
			 */
			new Trigger(
				'booking.updated',
				__( 'Booking updated', 'woocommerce-zapier' ),
				array( 'wc_zapier_woocommerce_booking_updated', 'woocommerce_new_booking' )
			),
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_webhook_payload() {
		return new Payload( $this->key, $this->controller, $this->logger );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param int $resource_id Resource ID.
	 */
	public function get_description( $resource_id ) {
		$object = get_wc_booking( $resource_id );
		if ( false !== $object && is_a( $object, 'WC_Booking' ) && 'trash' !== $object->get_status() ) {
			// Use the corresponding order's billing name.
			$order = wc_get_order( $object->get_order_id() );
			if ( is_callable( array( $order, 'get_formatted_billing_full_name' ) ) ) {
				return $order->get_formatted_billing_full_name();
			}
		}
		return null;
	}
}
