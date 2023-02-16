<?php
/**
 * Custom Payment Gateways for WooCommerce - General Section Settings
 *
 * @version 1.5.0
 * @since   1.0.0
 * @author  Imaginate Solutions
 * @package cpgw
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Custom_Payment_Gateways_Settings_General' ) ) :

	/**
	 * General Settings Class.
	 */
	class Alg_WC_Custom_Payment_Gateways_Settings_General extends Alg_WC_Custom_Payment_Gateways_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 1.2.1
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->id   = '';
			$this->desc = __( 'General', 'custom-payment-gateways-woocommerce' );
			parent::__construct();
		}

		/**
		 * Get settings.
		 *
		 * @return array Settings Array.
		 * @version 1.5.0
		 * @since   1.0.0
		 */
		public function get_settings() {
			$settings = array(
				array(
					'title' => __( 'Custom Payment Gateways Options', 'custom-payment-gateways-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_custom_payment_gateways_options',
					'desc'  => __( 'Here you can set number of custom payment gateways to add.', 'custom-payment-gateways-woocommerce' )
						. ' ' . sprintf(
							// translators: %s is link to payment gateway settings.
							__( 'After setting the number, visit %s to set each gateway\'s options.', 'custom-payment-gateways-woocommerce' ),
							'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout' ) . '">' .
							__( 'WooCommerce > Settings > Payments', 'custom-payment-gateways-woocommerce' ) . '</a>'
						),
				),
				array(
					'title'   => __( 'Custom Payment Gateways', 'custom-payment-gateways-woocommerce' ),
					'desc'    => '<strong>' . __( 'Enable plugin', 'custom-payment-gateways-woocommerce' ) . '</strong>',
					'id'      => 'alg_wc_custom_payment_gateways_enabled',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'title'             => __( 'Number of gateways', 'custom-payment-gateways-woocommerce' ),
					'desc'              => apply_filters(
						'alg_wc_custom_payment_gateways_settings',
						sprintf(
							'<p><div style="background-color: #fefefe; padding: 10px; border: 1px solid #d8d8d8; width: fit-content;">You will need <a target="_blank" href="%s">Custom Payment Gateways for WooCommerce Pro plugin</a> to add more than one custom payment gateway.</div></p>',
							'https://imaginate-solutions.com/downloads/custom-payment-gateways-for-woocommerce/'
						),
						'total_number'
					),
					'desc_tip'          => __( 'Number of custom payments gateways to be added.', 'custom-payment-gateways-woocommerce' ) . ' ' .
						__( 'Press "Save changes" after changing this number to see new options.', 'custom-payment-gateways-woocommerce' ),
					'id'                => 'alg_wc_custom_payment_gateways_number',
					'default'           => 1,
					'type'              => 'number',
					'custom_attributes' => apply_filters( 'alg_wc_custom_payment_gateways_settings', array( 'readonly' => 'readonly' ), 'array' ),
				),
			);
			for ( $i = 1; $i <= apply_filters( 'alg_wc_custom_payment_gateways_values', 1, 'total_gateways' ); $i++ ) { // phpcs:ignore
				$settings[] = array(
					'title'   => __( 'Admin title for Custom Gateway', 'custom-payment-gateways-woocommerce' ) . ' #' . $i,
					'id'      => 'alg_wc_custom_payment_gateways_admin_title_' . $i,
					'default' => __( 'Custom Gateway', 'custom-payment-gateways-woocommerce' ) . ' #' . $i,
					'type'    => 'text',
					'desc'    => '<a class="button" href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=alg_custom_gateway_' . $i ) . '" target="_blank">' .
						__( 'Settings', 'woocommerce' ) . '</a>',
				);
			}
			$settings = array_merge(
				$settings,
				array(
					array(
						'type' => 'sectionend',
						'id'   => 'alg_wc_custom_payment_gateways_options',
					),
				)
			);
			return $settings;
		}

	}

endif;

return new Alg_WC_Custom_Payment_Gateways_Settings_General();
