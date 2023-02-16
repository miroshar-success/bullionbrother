<?php
/**
 * Custom Payment Gateways for WooCommerce - Advanced Section Settings
 *
 * @version 1.4.0
 * @since   1.4.0
 * @author  Imaginate Solutions
 * @package cpgw
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Custom_Payment_Gateways_Settings_Advanced' ) ) :

	/**
	 * Advanced Settings class.
	 */
	class Alg_WC_Custom_Payment_Gateways_Settings_Advanced extends Alg_WC_Custom_Payment_Gateways_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 1.4.0
		 * @since   1.4.0
		 */
		public function __construct() {
			$this->id   = 'advanced';
			$this->desc = __( 'Advanced', 'custom-payment-gateways-woocommerce' );
			parent::__construct();
		}

		/**
		 * Get settings.
		 *
		 * @return array Settings Array.
		 * @version 1.4.0
		 * @since   1.4.0
		 */
		public function get_settings() {
			$settings = array(
				array(
					'title' => __( 'Advanced Options', 'custom-payment-gateways-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_cpg_advanced_options',
				),
				array(
					'title'    => __( 'Shipping methods', 'custom-payment-gateways-woocommerce' ),
					'desc_tip' => __( 'Used in "Enable for shipping methods" custom payment gateway\'s option.', 'custom-payment-gateways-woocommerce' ),
					'type'     => 'select',
					'class'    => 'chosen_select',
					'id'       => 'alg_wc_cpg_load_shipping_method_instances',
					'default'  => 'yes',
					'options'  => array(
						'yes'     => __( 'Load shipping methods and instances', 'custom-payment-gateways-woocommerce' ),
						'no'      => __( 'Load shipping methods only', 'custom-payment-gateways-woocommerce' ),
						'disable' => __( 'Do not load', 'custom-payment-gateways-woocommerce' ),
					),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_cpg_advanced_options',
				),
			);
			return $settings;
		}

	}

endif;

return new Alg_WC_Custom_Payment_Gateways_Settings_Advanced();
