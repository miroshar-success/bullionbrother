<?php
/**
 * Custom Payment Gateways for WooCommerce - Section Settings
 *
 * @version 1.2.0
 * @since   1.0.0
 * @author  Imaginate Solutions
 * @package cpgw
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Custom_Payment_Gateways_Settings_Section' ) ) :

	/**
	 * Settings Section.
	 */
	class Alg_WC_Custom_Payment_Gateways_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function __construct() {
			add_filter( 'woocommerce_get_sections_alg_wc_custom_payment_gateways', array( $this, 'settings_section' ) );
			add_filter( 'woocommerce_get_settings_alg_wc_custom_payment_gateways_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
		}

		/**
		 * Settings section.
		 *
		 * @param array $sections Settings Section.
		 * @return array
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function settings_section( $sections ) {
			$sections[ $this->id ] = $this->desc;
			return $sections;
		}

	}

endif;
