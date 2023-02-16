<?php
/**
 * Checkout Fees for WooCommerce - Settings Section
 *
 * @version 2.5.0
 * @since   2.5.0
 * @author  Tyche Softwares
 *
 * @package checkout-fees-for-woocommerce/settings/sections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Checkout_Fees_Settings_Section' ) ) :

	/**
	 * Add a settings section in WooCommerce settings.
	 */
	abstract class Alg_WC_Checkout_Fees_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 2.5.0
		 * @since   2.5.0
		 */
		public function __construct() {
			add_filter( 'woocommerce_get_sections_alg_checkout_fees', array( $this, 'settings_section' ) );
			add_filter( 'woocommerce_get_settings_alg_checkout_fees_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
		}

		/**
		 * Settings_section.
		 *
		 * @param array $sections Different tabs in settings.
		 * @version 2.5.0
		 * @since   2.5.0
		 */
		public function settings_section( $sections ) {
			$sections[ $this->id ] = $this->desc;
			return $sections;
		}

		/**
		 * Get_settings.
		 *
		 * @version 2.5.0
		 * @since   2.5.0
		 */
		abstract public function get_settings();

	}

endif;
