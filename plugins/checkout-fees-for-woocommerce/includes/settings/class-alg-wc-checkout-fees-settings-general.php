<?php
/**
 * Checkout Fees for WooCommerce - General Section Settings
 *
 * @version 2.5.0
 * @since   1.0.0
 * @author  Tyche Softwares
 *
 * @package checkout-fees-for-woocommerce/settings/General
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Checkout_Fees_Settings_General' ) ) :

	/**
	 * General Settings.
	 */
	class Alg_WC_Checkout_Fees_Settings_General extends Alg_WC_Checkout_Fees_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 2.5.0
		 */
		public function __construct() {
			$this->id   = '';
			$this->desc = __( 'General', 'checkout-fees-for-woocommerce' );
			parent::__construct();
		}

		/**
		 * Get_settings.
		 *
		 * @version 2.5.0
		 * @todo    [dev] maybe split into more separate sections (like "Info" and "Global Extra Fee"), e.g.: "Per Product", "Advanced" etc.
		 */
		public function get_settings() {
			$settings = array(
				array(
					'title' => __( 'Payment Gateway Based Fees and Discounts', 'checkout-fees-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_woocommerce_checkout_fees_options',
				),
				array(
					'title'    => __( 'Payment Gateway Based Fees and Discounts', 'checkout-fees-for-woocommerce' ),
					'desc'     => '<strong>' . __( 'Enable plugin', 'checkout-fees-for-woocommerce' ) . '</strong>',
					'desc_tip' => __( 'Enable extra fees or discounts for WooCommerce payment gateways.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_woocommerce_checkout_fees_enabled',
					'default'  => 'yes',
					'type'     => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_woocommerce_checkout_fees_options',
				),
				array(
					'title' => __( 'Fees/Discounts per Product', 'checkout-fees-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_woocommerce_checkout_fees_per_product_options',
				),
				array(
					'title'    => __( 'Payment gateways fees and discounts on per product basis', 'checkout-fees-for-woocommerce' ),
					'desc'     => __( 'Enable', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'This will add meta boxes with fees settings to each product\'s edit page.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_woocommerce_checkout_fees_per_product_enabled',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Add product title to fee/discount title', 'checkout-fees-for-woocommerce' ),
					'desc'     => __( 'Add', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'This can help when you adding fees/discounts for variable products.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_woocommerce_checkout_fees_per_product_add_product_name',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_woocommerce_checkout_fees_per_product_options',
				),
				array(
					'title' => __( 'General Options', 'checkout-fees-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_woocommerce_checkout_fees_general_options',
				),
				array(
					'title'    => __( 'Merge all fees', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'This will merge all fees for a gateway into single line (i.e. will display it as a single fee on front end).', 'checkout-fees-for-woocommerce' ),
					'desc'     => __( 'Enable', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_woocommerce_checkout_fees_merge_all_fees',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_woocommerce_checkout_fees_general_options',
				),
				array(
					'title' => __( 'Max Range Options', 'checkout-fees-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_woocommerce_checkout_fees_range_options',
				),
				array(
					'title'             => __( 'Max total discount', 'checkout-fees-for-woocommerce' ),
					'desc_tip'          => __( 'Negative number.', 'checkout-fees-for-woocommerce' ) . ' ' . __( 'Set 0 to disable.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_woocommerce_checkout_fees_range_max_total_discounts',
					'default'           => 0,
					'type'              => 'number',
					'custom_attributes' => array( 'max' => 0 ),
				),
				array(
					'title'             => __( 'Max total fee', 'checkout-fees-for-woocommerce' ),
					'desc_tip'          => __( 'Set 0 to disable.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_woocommerce_checkout_fees_range_max_total_fees',
					'default'           => 0,
					'type'              => 'number',
					'custom_attributes' => array( 'min' => 0 ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_woocommerce_checkout_fees_range_options',
				),
				array(
					'title' => __( 'Cart Options', 'checkout-fees-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_woocommerce_checkout_fees_cart_options',
				),
				array(
					'title'   => __( 'Hide gateways fees and discounts on cart page', 'checkout-fees-for-woocommerce' ),
					'desc'    => __( 'Hide', 'checkout-fees-for-woocommerce' ),
					'id'      => 'alg_woocommerce_checkout_fees_hide_on_cart',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_woocommerce_checkout_fees_cart_options',
				),
				array(
					'title' => __( 'Advanced Options', 'checkout-fees-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_woocommerce_checkout_fees_advanced_options',
				),
				array(
					'title' => __( 'Delete all plugin\'s data', 'checkout-fees-for-woocommerce' ),
					'link'  => '<a class="button-primary" href="' . wp_nonce_url(
						add_query_arg( 'alg_woocommerce_checkout_fees_delete_all_data', '1' ),
						'alg_woocommerce_checkout_fees_delete_all_data',
						'alg_woocommerce_checkout_fees_delete_all_data_nonce'
					) . '"' .
						' onclick="return confirm(\'' . __( 'Are you sure?', 'checkout-fees-for-woocommerce' ) . '\')"' .
						' style="background: red; border-color: red; box-shadow: 0 1px 0 red; text-shadow: 0 -1px 1px #a00,1px 0 1px #a00,0 1px 1px #a00,-1px 0 1px #a00;">' .
							__( 'Delete', 'checkout-fees-for-woocommerce' ) .
						'</a>',
					'id'    => 'alg_woocommerce_checkout_fees_delete_all_data',
					'type'  => 'alg_woocommerce_checkout_fees_custom_link',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_woocommerce_checkout_fees_advanced_options',
				),
			);
			return $settings;
		}

	}

endif;

return new Alg_WC_Checkout_Fees_Settings_General();
