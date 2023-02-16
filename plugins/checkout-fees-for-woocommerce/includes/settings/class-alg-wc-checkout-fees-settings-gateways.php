<?php
/**
 * Checkout Fees for WooCommerce - Gateways Section(s) Settings
 *
 * @version 2.5.3
 * @since   1.0.0
 * @author  Tyche Softwares
 *
 * @package checkout-fees-for-woocommerce/settings/Gateways
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Checkout_Fees_Settings_Gateways' ) ) :

	/**
	 * Payment Gateways settings section.
	 */
	class Alg_WC_Checkout_Fees_Settings_Gateways {

		/**
		 * Constructor.
		 *
		 * @version 2.5.3
		 */
		public function __construct() {
			add_filter( 'woocommerce_get_sections_alg_checkout_fees', array( $this, 'settings_section' ) );
			add_filter( 'init', array( $this, 'add_get_settings_hook' ), PHP_INT_MAX );
		}

		/**
		 * Settings_section.
		 *
		 * @param array $sections Sections for payment gateways.
		 * @version 2.5.0
		 * @todo    [dev] add option to show available (i.e. enabled) gateways only
		 */
		public function settings_section( $sections ) {
			if ( function_exists( 'WC' ) ) {
				$available_gateways = WC()->payment_gateways->payment_gateways();
				foreach ( $available_gateways as $key => $gateway ) {
					$sections[ sanitize_title( $key ) ] = $gateway->title;
					if ( $key === 'zipmoney' ) { //phpcs:ignore
						$sections[ sanitize_title( $key ) ] = $gateway->method_title;
					}
				}
			}
			return $sections;
		}

		/**
		 * Add_get_settings_hook.
		 *
		 * @version 2.5.0
		 */
		public function add_get_settings_hook() {
			if ( ! isset( $_GET['page'] ) || 'wc-settings' !== $_GET['page'] || ! isset( $_GET['tab'] ) || 'alg_checkout_fees' !== $_GET['tab'] ) { // phpcs:ignore WordPress.Security.NonceVerification
				return;
			}
			if ( function_exists( 'WC' ) ) {
				$available_gateways = WC()->payment_gateways->payment_gateways();
				foreach ( $available_gateways as $key => $gateway ) {
					add_filter( 'woocommerce_get_settings_alg_checkout_fees_' . sanitize_title( $key ), array( $this, 'get_settings' ), PHP_INT_MAX );
				}
			}
		}

		/**
		 * Get_settings.
		 *
		 * @version 2.5.1
		 */
		public function get_settings() {

			// Getting current gateway (section).
			if ( ! isset( $_GET['section'] ) || ! function_exists( 'WC' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				return array();
			}
			$available_gateways = WC()->payment_gateways->payment_gateways();
			$key                = sanitize_title( wp_unslash( $_GET['section'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
			if ( ! isset( $available_gateways[ $key ] ) && ! isset( $available_gateways[ strtoupper( $key ) ] ) ) {
				return array();
			}
			$gateway = '';
			if ( isset( $available_gateways[ $key ] ) ) {
				$gateway = $available_gateways[ $key ];
			}
			if ( null === $gateway || '' === $gateway ) {
				$gateway = $available_gateways[ strtoupper( $key ) ];
			}
			// Countries.
			$countries = array_merge( alg_checkout_fees_get_countries_sets(), alg_checkout_fees_get_countries() );

			// Cats.
			$product_cats       = array();
			$product_categories = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
			if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
				foreach ( $product_categories as $product_category ) {
					$product_cats[ $product_category->term_id ] = $product_category->name;
				}
			}

			/* translators: %s: Upgrade to Pro URL */
			$upgrade_url = sprintf( __( 'You will need <a target="_blank" href="%s">Pro version</a> of the plugin to set this option.', 'checkout-fees-for-woocommerce' ), 'https://www.tychesoftwares.com/store/premium-plugins/payment-gateway-based-fees-and-discounts-for-woocommerce-plugin/?utm_source=pgfupgradetopro&utm_medium=link&utm_campaign=PaymentGatewayFeesLite' );

			if ( $key === 'zipmoney' ) { //phpcs:ignore
				$gateway->title = $gateway->method_title;
			}
			// Adding settings.
			$settings = array(
				array(
					'title' => $gateway->title,
					'type'  => 'title',
					'id'    => 'alg_gateways_fees_options',
				),
				array(
					/* translators: %s: title */
					'title'    => sprintf( __( '"%s" fees and discounts', 'checkout-fees-for-woocommerce' ), $gateway->title ),
					'desc'     => '<strong>' . __( 'Enable', 'checkout-fees-for-woocommerce' ) . '</strong>',
					/* translators: %s: title */
					'desc_tip' => sprintf( __( 'Add fee/discount to "%s" gateway.', 'checkout-fees-for-woocommerce' ), $gateway->title ),
					'id'       => 'alg_gateways_fees_enabled_' . $key,
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_gateways_fees_options',
				),
				array(
					'title' => __( 'Fee', 'checkout-fees-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_gateways_fees_fee_options',
				),
				array(
					'title'    => __( 'Fee title', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'Fee (or discount) title to show to customer.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_gateways_fees_text_' . $key,
					'default'  => '',
					'type'     => 'text',
				),
				array(
					'title'    => __( 'Fee type', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'Fee (or discount) type. Percent or fixed value.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_gateways_fees_type_' . $key,
					'default'  => 'fixed',
					'type'     => 'select',
					'options'  => array(
						'fixed'   => __( 'Fixed', 'checkout-fees-for-woocommerce' ),
						'percent' => __( 'Percent', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'title'             => __( 'Fee value', 'checkout-fees-for-woocommerce' ),
					'desc_tip'          => __( 'Fee (or discount) value. For discount enter a negative number.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_value_' . $key,
					'default'           => 0,
					'type'              => 'number',
					'custom_attributes' => array( 'step' => '0.0001' ),
				),
				array(
					'title'             => __( 'Minimum fee value', 'checkout-fees-for-woocommerce' ),
					'desc_tip'          => __( 'Minimum fee (or discount).', 'checkout-fees-for-woocommerce' ) . ' ' . __( 'Ignored if set to zero.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_min_fee_' . $key,
					'default'           => 0,
					'type'              => 'number',
					'custom_attributes' => array( 'step' => '0.0001' ),
				),
				array(
					'title'             => __( 'Maximum fee value', 'checkout-fees-for-woocommerce' ),
					'desc_tip'          => __( 'Maximum fee (or discount).', 'checkout-fees-for-woocommerce' ) . ' ' . __( 'Ignored if set to zero.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_max_fee_' . $key,
					'default'           => 0,
					'type'              => 'number',
					'custom_attributes' => array( 'step' => '0.0001' ),
				),
				array(
					'title'   => __( 'Coupons rule', 'checkout-fees-for-woocommerce' ),
					'id'      => 'alg_gateways_fees_coupons_rule_' . $key,
					'default' => 'disabled',
					'type'    => 'select',
					'options' => array(
						'disabled'           => __( 'Disabled', 'checkout-fees-for-woocommerce' ),
						'only_if_no_coupons' => __( 'Apply fees only if no coupons were applied', 'checkout-fees-for-woocommerce' ),
						'only_if_coupons'    => __( 'Apply fees only if any coupons were applied', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'title'             => __( 'Customer countries', 'checkout-fees-for-woocommerce' ),
					'desc'              => __( 'Countries to include', 'checkout-fees-for-woocommerce' ) . apply_filters(
						'alg_wc_checkout_fees_option',
						'<br>' . $upgrade_url,
						'settings'
					),
					'desc_tip'          => __( 'Fee (or discount) will only be added if customer\'s billing country is in the list.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'Ignored if empty.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_countries_include_fee_1_' . $key,
					'default'           => '',
					'type'              => 'multiselect',
					'class'             => 'chosen_select',
					'options'           => $countries,
					'custom_attributes' => apply_filters( 'alg_wc_checkout_fees_option', array( 'disabled' => 'disabled' ), 'settings' ),
				),
				array(
					'desc'              => __( 'Countries to exclude', 'checkout-fees-for-woocommerce' ) . apply_filters(
						'alg_wc_checkout_fees_option',
						'<br>' . $upgrade_url,
						'settings'
					),
					'desc_tip'          => __( 'Fee (or discount) will only be added if customer\'s billing country is NOT in the list.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'Ignored if empty.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_countries_exclude_fee_1_' . $key,
					'default'           => '',
					'type'              => 'multiselect',
					'class'             => 'chosen_select',
					'options'           => $countries,
					'custom_attributes' => apply_filters( 'alg_wc_checkout_fees_option', array( 'disabled' => 'disabled' ), 'settings' ),
				),
				array(
					'title'             => __( 'Customer states', 'checkout-fees-for-woocommerce' ),
					'desc'              => __( 'States to include', 'checkout-fees-for-woocommerce' ) . apply_filters(
						'alg_wc_checkout_fees_option',
						'<br>' . $upgrade_url,
						'settings'
					),
					'desc_tip'          => __( 'Fee (or discount) will only be added if customer\'s billing state is in the list. Comma separated list of states codes.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'Ignored if empty.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_states_include_fee_1_' . $key,
					'default'           => '',
					'type'              => 'text',
					'custom_attributes' => apply_filters( 'alg_wc_checkout_fees_option', array( 'readonly' => 'readonly' ), 'settings' ),
				),
				array(
					'desc'              => __( 'States to exclude', 'checkout-fees-for-woocommerce' ) . apply_filters(
						'alg_wc_checkout_fees_option',
						'<br>' . $upgrade_url,
						'settings'
					),
					'desc_tip'          => __( 'Fee (or discount) will only be added if customer\'s billing state is NOT in the list. Comma separated list of states codes.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'Ignored if empty.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_states_exclude_fee_1_' . $key,
					'default'           => '',
					'type'              => 'text',
					'custom_attributes' => apply_filters( 'alg_wc_checkout_fees_option', array( 'readonly' => 'readonly' ), 'settings' ),
				),
				array(
					'title'             => __( 'Product categories', 'checkout-fees-for-woocommerce' ),
					'desc'              => __( 'Categories to include', 'checkout-fees-for-woocommerce' ) . apply_filters(
						'alg_wc_checkout_fees_option',
						'<br>' . $upgrade_url,
						'settings'
					),
					'desc_tip'          => __( 'Fee (or discount) will only be added if product of selected category(-ies) is in the cart.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'Ignored if empty.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_cats_include_' . $key,
					'default'           => '',
					'type'              => 'multiselect',
					'class'             => 'chosen_select',
					'options'           => $product_cats,
					'custom_attributes' => apply_filters( 'alg_wc_checkout_fees_option', array( 'disabled' => 'disabled' ), 'settings' ),
				),
				array(
					'desc'              => __( 'Categories to exclude', 'checkout-fees-for-woocommerce' ) . apply_filters(
						'alg_wc_checkout_fees_option',
						'<br>' . $upgrade_url,
						'settings'
					),
					'desc_tip'          => __( 'Fee (or discount) will only be added if NO product of selected category(-ies) is in the cart.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'Ignored if empty.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_cats_exclude_' . $key,
					'default'           => '',
					'type'              => 'multiselect',
					'class'             => 'chosen_select',
					'options'           => $product_cats,
					'custom_attributes' => apply_filters( 'alg_wc_checkout_fees_option', array( 'disabled' => 'disabled' ), 'settings' ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_gateways_fees_fee_options',
				),
				array(
					'title' => __( 'Additional Fee (Optional)', 'checkout-fees-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_gateways_fees_additional_fee_options',
				),
				array(
					'title'    => __( 'Fee title', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'Fee (or discount) title to show to customer.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'To display each (i.e. main and additional) fees on different lines in cart (and checkout), you must set different titles. If titles are equal they will be merged into single line.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_gateways_fees_text_2_' . $key,
					'default'  => '',
					'type'     => 'text',
				),
				array(
					'title'    => __( 'Fee type', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'Fee (or discount) type. Percent or fixed value.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_gateways_fees_type_2_' . $key,
					'default'  => 'fixed',
					'type'     => 'select',
					'options'  => array(
						'fixed'   => __( 'Fixed', 'checkout-fees-for-woocommerce' ),
						'percent' => __( 'Percent', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'title'             => __( 'Fee value', 'checkout-fees-for-woocommerce' ),
					'desc_tip'          => __( 'Fee (or discount) value. For discount enter a negative number.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_value_2_' . $key,
					'default'           => 0,
					'type'              => 'number',
					'custom_attributes' => array( 'step' => '0.0001' ),
				),
				array(
					'title'             => __( 'Minimum fee value', 'checkout-fees-for-woocommerce' ),
					'desc_tip'          => __( 'Minimum fee (or discount).', 'checkout-fees-for-woocommerce' ) . ' ' . __( 'Ignored if set to zero.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_min_fee_2_' . $key,
					'default'           => 0,
					'type'              => 'number',
					'custom_attributes' => array( 'step' => '0.0001' ),
				),
				array(
					'title'             => __( 'Maximum fee value', 'checkout-fees-for-woocommerce' ),
					'desc_tip'          => __( 'Maximum fee (or discount).', 'checkout-fees-for-woocommerce' ) . ' ' . __( 'Ignored if set to zero.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_max_fee_2_' . $key,
					'default'           => 0,
					'type'              => 'number',
					'custom_attributes' => array( 'step' => '0.0001' ),
				),
				array(
					'title'   => __( 'Coupons rule', 'checkout-fees-for-woocommerce' ),
					'id'      => 'alg_gateways_fees_coupons_rule_2_' . $key,
					'default' => 'disabled',
					'type'    => 'select',
					'options' => array(
						'disabled'           => __( 'Disabled', 'checkout-fees-for-woocommerce' ),
						'only_if_no_coupons' => __( 'Apply fees only if no coupons were applied', 'checkout-fees-for-woocommerce' ),
						'only_if_coupons'    => __( 'Apply fees only if any coupons were applied', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'title'             => __( 'Customer countries', 'checkout-fees-for-woocommerce' ),
					'desc'              => __( 'Countries to include', 'checkout-fees-for-woocommerce' ) . apply_filters(
						'alg_wc_checkout_fees_option',
						'<br>' . $upgrade_url,
						'settings'
					),
					'desc_tip'          => __( 'Fee (or discount) will only be added if customer\'s billing country is in the list.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'Ignored if empty.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_countries_include_fee_2_' . $key,
					'default'           => '',
					'type'              => 'multiselect',
					'class'             => 'chosen_select',
					'options'           => $countries,
					'custom_attributes' => apply_filters( 'alg_wc_checkout_fees_option', array( 'disabled' => 'disabled' ), 'settings' ),
				),
				array(
					'desc'              => __( 'Countries to exclude', 'checkout-fees-for-woocommerce' ) . apply_filters(
						'alg_wc_checkout_fees_option',
						'<br>' . $upgrade_url,
						'settings'
					),
					'desc_tip'          => __( 'Fee (or discount) will only be added if customer\'s billing country is NOT in the list.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'Ignored if empty.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_countries_exclude_fee_2_' . $key,
					'default'           => '',
					'type'              => 'multiselect',
					'class'             => 'chosen_select',
					'options'           => $countries,
					'custom_attributes' => apply_filters( 'alg_wc_checkout_fees_option', array( 'disabled' => 'disabled' ), 'settings' ),
				),
				array(
					'title'             => __( 'Customer states', 'checkout-fees-for-woocommerce' ),
					'desc'              => __( 'States to include', 'checkout-fees-for-woocommerce' ) . apply_filters(
						'alg_wc_checkout_fees_option',
						'<br>' . $upgrade_url,
						'settings'
					),
					'desc_tip'          => __( 'Fee (or discount) will only be added if customer\'s billing state is in the list. Comma separated list of states codes.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'Ignored if empty.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_states_include_fee_2_' . $key,
					'default'           => '',
					'type'              => 'text',
					'custom_attributes' => apply_filters( 'alg_wc_checkout_fees_option', array( 'readonly' => 'readonly' ), 'settings' ),
				),
				array(
					'desc'              => __( 'States to exclude', 'checkout-fees-for-woocommerce' ) . apply_filters(
						'alg_wc_checkout_fees_option',
						'<br>' . $upgrade_url,
						'settings'
					),
					'desc_tip'          => __( 'Fee (or discount) will only be added if customer\'s billing state is NOT in the list. Comma separated list of states codes.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'Ignored if empty.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_states_exclude_fee_2_' . $key,
					'default'           => '',
					'type'              => 'text',
					'custom_attributes' => apply_filters( 'alg_wc_checkout_fees_option', array( 'readonly' => 'readonly' ), 'settings' ),
				),
				array(
					'title'             => __( 'Product categories', 'checkout-fees-for-woocommerce' ),
					'desc'              => __( 'Categories to include', 'checkout-fees-for-woocommerce' ) . apply_filters(
						'alg_wc_checkout_fees_option',
						'<br>' . $upgrade_url,
						'settings'
					),
					'desc_tip'          => __( 'Fee (or discount) will only be added if product of selected category(-ies) is in the cart.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'Ignored if empty.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_cats_include_fee_2_' . $key,
					'default'           => apply_filters(
						'alg_wc_checkout_fees_option',
						'',
						'cats',
						array(
							'type'            => 'include',
							'fee_num'         => '',
							'current_gateway' => $key,
						)
					),
					'type'              => 'multiselect',
					'class'             => 'chosen_select',
					'options'           => $product_cats,
					'custom_attributes' => apply_filters( 'alg_wc_checkout_fees_option', array( 'disabled' => 'disabled' ), 'settings' ),
				),
				array(
					'desc'              => __( 'Categories to exclude', 'checkout-fees-for-woocommerce' ) . apply_filters(
						'alg_wc_checkout_fees_option',
						'<br>' . $upgrade_url,
						'settings'
					),
					'desc_tip'          => __( 'Fee (or discount) will only be added if NO product of selected category(-ies) is in the cart.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'Ignored if empty.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_cats_exclude_fee_2_' . $key,
					'default'           => apply_filters(
						'alg_wc_checkout_fees_option',
						'',
						'cats',
						array(
							'type'            => 'exclude',
							'fee_num'         => '',
							'current_gateway' => $key,
						)
					),
					'type'              => 'multiselect',
					'class'             => 'chosen_select',
					'options'           => $product_cats,
					'custom_attributes' => apply_filters( 'alg_wc_checkout_fees_option', array( 'disabled' => 'disabled' ), 'settings' ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_gateways_fees_additional_fee_options',
				),
				array(
					'title' => __( 'General Options', 'checkout-fees-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_gateways_fees_general_options',
				),
				array(
					'title'             => __( 'Minimum cart amount', 'checkout-fees-for-woocommerce' ),
					'desc_tip'          => __( 'Minimum cart amount for adding the fee (or discount).', 'checkout-fees-for-woocommerce' ) . ' ' . __( 'Ignored if set to zero.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_min_cart_amount_' . $key,
					'default'           => 0,
					'type'              => 'number',
					'custom_attributes' => array(
						'step' => '0.0001',
						'min'  => '0',
					),
				),
				array(
					'title'             => __( 'Maximum cart amount', 'checkout-fees-for-woocommerce' ),
					'desc_tip'          => __( 'Maximum cart amount for adding the fee (or discount).', 'checkout-fees-for-woocommerce' ) . ' ' . __( 'Ignored if set to zero.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_max_cart_amount_' . $key,
					'default'           => 0,
					'type'              => 'number',
					'custom_attributes' => array(
						'step' => '0.0001',
						'min'  => '0',
					),
				),
				array(
					'title'    => __( 'Rounding', 'checkout-fees-for-woocommerce' ),
					'desc'     => __( 'Enable', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'Round the fee (or discount) value before adding to the cart.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_gateways_fees_round_' . $key,
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'desc'              => __( 'Rounding precision', 'checkout-fees-for-woocommerce' ),
					'desc_tip'          => __( 'If rounding is enabled, set precision (i.e. number of decimals) here.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_round_precision_' . $key,
					'default'           => 0,
					'type'              => 'number',
					'custom_attributes' => array(
						'step' => '1',
						'min'  => '0',
					),
				),
				array(
					'title'    => __( 'Taxes', 'checkout-fees-for-woocommerce' ),
					'desc'     => __( 'Enable', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'Is taxable?', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_gateways_fees_is_taxable_' . $key,
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'desc'     => __( 'Tax class', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'Only if "Taxes" option above is enabled.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_gateways_fees_tax_class_id_' . $key,
					'default'  => '',
					'type'     => 'select',
					'options'  => array_merge( array( __( 'Standard rate', 'checkout-fees-for-woocommerce' ) ), WC_Tax::get_tax_classes() ),
				),
				array(
					'title'    => __( 'Exclude shipping', 'checkout-fees-for-woocommerce' ),
					'desc'     => __( 'Exclude', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'Exclude shipping from total cart sum, when calculating fees.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_gateways_fees_exclude_shipping_' . $key,
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'title'    => __( 'Add taxes', 'checkout-fees-for-woocommerce' ),
					'desc'     => __( 'Add', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'Add taxes to total cart sum, when calculating fees.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_gateways_fees_add_taxes_' . $key,
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'title'             => __( 'Customer countries', 'checkout-fees-for-woocommerce' ),
					'desc'              => __( 'Countries to include', 'checkout-fees-for-woocommerce' ) . apply_filters(
						'alg_wc_checkout_fees_option',
						'<br>' . $upgrade_url,
						'settings'
					),
					'desc_tip'          => __( 'Fee (or discount) will only be added if customer\'s billing country is in the list.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'Ignored if empty.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'This is applied to both main and additional fees. Alternatively you can also set customer countries for each fee individually.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_countries_include_' . $key,
					'default'           => '',
					'type'              => 'multiselect',
					'class'             => 'chosen_select',
					'options'           => $countries,
					'custom_attributes' => apply_filters( 'alg_wc_checkout_fees_option', array( 'disabled' => 'disabled' ), 'settings' ),
				),
				array(
					'desc'              => __( 'Countries to exclude', 'checkout-fees-for-woocommerce' ) . apply_filters(
						'alg_wc_checkout_fees_option',
						'<br>' . $upgrade_url,
						'settings'
					),
					'desc_tip'          => __( 'Fee (or discount) will only be added if customer\'s billing country is NOT in the list.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'Ignored if empty.', 'checkout-fees-for-woocommerce' ) . ' ' .
						__( 'This is applied to both main and additional fees. Alternatively you can also set customer countries for each fee individually.', 'checkout-fees-for-woocommerce' ),
					'id'                => 'alg_gateways_fees_countries_exclude_' . $key,
					'default'           => '',
					'type'              => 'multiselect',
					'class'             => 'chosen_select',
					'options'           => $countries,
					'custom_attributes' => apply_filters( 'alg_wc_checkout_fees_option', array( 'disabled' => 'disabled' ), 'settings' ),
				),
				array(
					'title'   => __( 'Product categories - Calculation type', 'checkout-fees-for-woocommerce' ),
					'desc'    => __( 'Categories to include', 'checkout-fees-for-woocommerce' ),
					'id'      => 'alg_gateways_fees_cats_include_calc_type_' . $key,
					'default' => 'for_all_cart',
					'type'    => 'select',
					'options' => array(
						'for_all_cart'               => __( 'For all cart', 'checkout-fees-for-woocommerce' ),
						'only_for_selected_products' => __( 'Only for selected products', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'desc'    => __( 'Categories to exclude', 'checkout-fees-for-woocommerce' ),
					'id'      => 'alg_gateways_fees_cats_exclude_calc_type_' . $key,
					'default' => 'for_all_cart',
					'type'    => 'select',
					'options' => array(
						'for_all_cart'               => __( 'For all cart', 'checkout-fees-for-woocommerce' ),
						'only_for_selected_products' => __( 'Only for selected products', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_gateways_fees_general_options',
				),
			);

			return $settings;
		}

	}

endif;

return new Alg_WC_Checkout_Fees_Settings_Gateways();
