<?php
/**
 * Custom Payment Gateways for WooCommerce - Fees Section Settings
 *
 * @version 1.6.0
 * @since   1.6.0
 * @author  Imaginate Solutions
 * @package cpgw
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Custom_Payment_Gateways_Settings_Fees' ) ) :

	/**
	 * Fees settings class.
	 */
	class Alg_WC_Custom_Payment_Gateways_Settings_Fees extends Alg_WC_Custom_Payment_Gateways_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 1.6.0
		 * @since   1.6.0
		 */
		public function __construct() {
			$this->id   = 'fees';
			$this->desc = __( 'Fees', 'custom-payment-gateways-woocommerce' );
			parent::__construct();
		}

		/**
		 * Get settings.
		 *
		 * @return array Settings Array.
		 * @version 1.6.0
		 * @since   1.6.0
		 */
		public function get_settings() {
			$settings = array(
				array(
					'title' => __( 'Fees', 'custom-payment-gateways-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_cpg_fees_section_options',
				),
				array(
					'title'   => __( 'Fees', 'custom-payment-gateways-woocommerce' ),
					'desc'    => '<strong>' . __( 'Enable section', 'custom-payment-gateways-woocommerce' ) . '</strong>',
					'type'    => 'checkbox',
					'id'      => 'alg_wc_cpg_fees_enabled',
					'default' => 'yes',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_cpg_fees_section_options',
				),
				array(
					'title' => __( 'Cart Total Options', 'custom-payment-gateways-woocommerce' ),
					'desc'  => __( 'This section sets how cart total should be calculated for the fees.', 'custom-payment-gateways-woocommerce' ) . ' ' .
						__( 'Affects "Min cart total", "Max cart total" options and "Percent" based fees.', 'custom-payment-gateways-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_cpg_fees_cart_total_options',
				),
				array(
					'title'   => __( 'Taxes', 'custom-payment-gateways-woocommerce' ),
					'desc'    => __( 'Include', 'custom-payment-gateways-woocommerce' ),
					'type'    => 'checkbox',
					'id'      => 'alg_wc_cpg_fees_cart_total_taxes',
					'default' => 'yes',
				),
				array(
					'title'   => __( 'Shipping', 'custom-payment-gateways-woocommerce' ),
					'desc'    => __( 'Include', 'custom-payment-gateways-woocommerce' ),
					'type'    => 'checkbox',
					'id'      => 'alg_wc_cpg_fees_cart_total_shipping',
					'default' => 'yes',
				),
				array(
					'title'   => __( 'Discounts', 'custom-payment-gateways-woocommerce' ),
					'desc'    => __( 'Include', 'custom-payment-gateways-woocommerce' ),
					'type'    => 'checkbox',
					'id'      => 'alg_wc_cpg_fees_cart_total_discounts',
					'default' => 'yes',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_cpg_fees_cart_total_options',
				),
			);
			return $settings;
		}

	}

endif;

return new Alg_WC_Custom_Payment_Gateways_Settings_Fees();
