<?php

namespace OM4\Zapier\Plugin;

use OM4\Zapier\Payload\Plugin\CheckoutFieldEditor as Payload;
use OM4\Zapier\Plugin;
use OM4\Zapier\Trigger\Base;

defined( 'ABSPATH' ) || exit;

/**
 * Functionality that is enabled when the Checkout Field Editor plugin is activated.
 *
 * Plugin URL: https://woocommerce.com/products/woocommerce-checkout-field-editor/
 *
 * Class CheckoutFieldEditor
 *
 * @deprecated 2.0.0
 */
class CheckoutFieldEditor {

	/**
	 * Option names that store the Checkout Field editor field specification(s).
	 *
	 * @var array
	 */
	private $checkout_field_sections = array(
		'wc_fields_billing',
		'wc_fields_shipping',
		'wc_fields_additional',
	);

	/**
	 * Trigger keys that the checkout field editor data should be added to.
	 *
	 * @var array
	 */
	private $trigger_keys = array(
		// New Order.
		'wc.new_order',
		// New Order Status Change.
		'wc.order_status_change',
	);

	/**
	 * Constructor
	 */
	public function __construct() {

		foreach ( $this->trigger_keys as $trigger_key ) {
			add_filter( "wc_zapier_data_{$trigger_key}", array( $this, 'order_data_override' ), 10, 2 );
		}

		foreach ( $this->checkout_field_sections as $field_section_name ) {
			add_action( "update_option_{$field_section_name}", array( $this, 'checkout_fields_updated' ), 10, 0 );
		}

	}

	/**
	 * When sending WooCommerce Order data to Zapier, also send any additional
	 * checkout fields that have been created by the Checkout Field Editor
	 * plugin.
	 *
	 * @param array                   $order_data Order data that will be overridden.
	 * @param OM4\Zapier\Trigger\Base $trigger    Trigger that initiated the data send.
	 *
	 * @return mixed
	 */
	public function order_data_override( $order_data, Base $trigger ) {

		$checkout_payload = new Payload();
		foreach ( $this->checkout_field_sections as $field_section_name ) {
			$field_specification = get_option( $field_section_name, array() );
			foreach ( $field_specification as $field_name => $field_data ) {
				if ( $field_data['enabled'] && ! isset( $order_data[ $field_name ] ) ) {
					if ( $trigger->is_sample() ) {
						// We're sending sample data.
						// Send the label of the custom checkout field as the field's value.
						$checkout_payload->$field_name = $field_data['label'];
					} else {
						// We're sending real data.
						// Send the saved value of this checkout field.
						// If the order doesn't contain this custom field, an empty string will be used as the value.
						$checkout_payload->$field_name = get_post_meta( $order_data['id'], $field_name, true );
					}
				}
			}
		}
		return $order_data + $checkout_payload->to_array();
	}

	/**
	 * Executed whenever the checkout field definitions are updated/saved.
	 * Schedule the feed refresh to occur asynchronously.
	 *
	 * @return void
	 */
	public function checkout_fields_updated() {
		Plugin::resend_sample_data_async( $this->trigger_keys );
	}
}
