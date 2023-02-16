<?php
/**
 * Checkout Fees for WooCommerce - Settings Section - Global Extra Fee
 *
 * @version 2.5.0
 * @since   2.5.0
 * @author  Tyche Softwares
 *
 * @package checkout-fees-for-woocommerce/settings/Fees
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Checkout_Fees_Settings_Global_Extra_Fee' ) ) :

	/**
	 * Global Extra Fee settings section.
	 */
	class Alg_WC_Checkout_Fees_Settings_Global_Extra_Fee extends Alg_WC_Checkout_Fees_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 2.5.0
		 * @since   2.5.0
		 */
		public function __construct() {
			$this->id   = 'global_extra_fee';
			$this->desc = __( 'Global Extra Fee', 'checkout-fees-for-woocommerce' );
			parent::__construct();
		}

		/**
		 * Get_settings.
		 *
		 * @version 2.5.0
		 * @since   2.5.0
		 */
		public function get_settings() {
			$available_gateways = array();
			if ( function_exists( 'WC' ) && isset( $_GET['page'] ) && 'wc-settings' === $_GET['page'] && isset( $_GET['tab'] ) && 'alg_checkout_fees' === $_GET['tab'] ) { // phpcs:ignore WordPress.Security.NonceVerification
				foreach ( WC()->payment_gateways->payment_gateways() as $key => $gateway ) {
					$available_gateways[ $key ] = $gateway->title;
				}
			}
			$settings = array(
				array(
					'title' => __( 'Global Extra Fee', 'checkout-fees-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_woocommerce_checkout_fees_global_fee_options',
				),
				array(
					'title'    => __( 'Global extra fee', 'checkout-fees-for-woocommerce' ),
					'desc'     => '<strong>' . __( 'Enable', 'checkout-fees-for-woocommerce' ) . '</strong>',
					'desc_tip' => __( 'This fee will be added to all gateways. Fee is fixed and not taxable.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_woocommerce_checkout_fees_global_fee_enabled',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Add as extra fee only', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'Check this, if you want fee to be added only if there are already any other fees added for the gateway.', 'checkout-fees-for-woocommerce' ),
					'desc'     => __( 'Enable', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_woocommerce_checkout_fees_global_fee_as_extra_enabled',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Exclude from gateways', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'Leave blank to include in all gateways.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_woocommerce_checkout_fees_global_fee_gateways_excl',
					'default'  => '',
					'type'     => 'multiselect',
					'class'    => 'chosen_select',
					'options'  => $available_gateways,
				),
				array(
					'title'    => __( 'Fee title', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'Fee (or discount) title to show to customer.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_woocommerce_checkout_fees_global_fee_title',
					'default'  => '',
					'type'     => 'text',
				),
				array(
					'title'             => __( 'Fee value', 'checkout-fees-for-woocommerce' ),
					'desc_tip'          => __( 'Fee (or discount) value. For discount enter a negative number.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_woocommerce_checkout_fees_global_fee_value',
					'default'           => 0,
					'type'              => 'number',
					'custom_attributes' => array( 'step' => '0.0001' ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_woocommerce_checkout_fees_global_fee_options',
				),
			);
			return $settings;
		}

	}

endif;

return new Alg_WC_Checkout_Fees_Settings_Global_Extra_Fee();
