<?php
/**
 * Checkout Fees for WooCommerce - Args
 *
 * @version 2.5.0
 * @since   2.3.0
 * @author  Tyche Softwares
 *
 * @package checkout-fees-for-woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Checkout_Fees_Args' ) ) :
	/**
	 * Alg_WC_Checkout_Fees_Info Class
	 *
	 * @class   Alg_WC_Checkout_Fees_Info
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	class Alg_WC_Checkout_Fees_Args {

		/**
		 * Constructor.
		 *
		 * @version 2.3.0
		 * @since   2.3.0
		 */
		public function __construct() {
			return true;
		}

		/**
		 * Get_the_args_global.
		 *
		 * @param mixed $current_gateway Current Gateway.
		 * @version 2.5.0
		 * @since   2.0.0
		 */
		public function get_the_args_global( $current_gateway ) {
			$args                     = array();
			$args['current_gateway']  = $current_gateway;
			$args['fee_scope']        = 'global';
			$args['is_enabled']       = get_option( 'alg_gateways_fees_enabled_' . $current_gateway, 'no' );
			$args['min_cart_amount']  = alg_wc_cf()->core->convert_currency( get_option( 'alg_gateways_fees_min_cart_amount_' . $current_gateway, 0 ) );
			$args['max_cart_amount']  = alg_wc_cf()->core->convert_currency( get_option( 'alg_gateways_fees_max_cart_amount_' . $current_gateway, 0 ) );
			$args['min_fee']          = alg_wc_cf()->core->convert_currency( get_option( 'alg_gateways_fees_min_fee_' . $current_gateway, 0 ) );
			$args['max_fee']          = alg_wc_cf()->core->convert_currency( get_option( 'alg_gateways_fees_max_fee_' . $current_gateway, 0 ) );
			$args['min_fee_2']        = alg_wc_cf()->core->convert_currency( get_option( 'alg_gateways_fees_min_fee_2_' . $current_gateway, 0 ) );
			$args['max_fee_2']        = alg_wc_cf()->core->convert_currency( get_option( 'alg_gateways_fees_max_fee_2_' . $current_gateway, 0 ) );
			$args['coupons_rule']     = get_option( 'alg_gateways_fees_coupons_rule_' . $current_gateway, 'disabled' );
			$args['coupons_rule_2']   = get_option( 'alg_gateways_fees_coupons_rule_2_' . $current_gateway, 'disabled' );
			$args['fee_text']         = get_option( 'alg_gateways_fees_text_' . $current_gateway, '' );
			$args['fee_value']        = get_option( 'alg_gateways_fees_value_' . $current_gateway, 0 );
			$args['fee_type']         = get_option( 'alg_gateways_fees_type_' . $current_gateway, 'fixed' );
			$args['fee_text_2']       = get_option( 'alg_gateways_fees_text_2_' . $current_gateway, '' );
			$args['fee_value_2']      = get_option( 'alg_gateways_fees_value_2_' . $current_gateway, 0 );
			$args['fee_type_2']       = get_option( 'alg_gateways_fees_type_2_' . $current_gateway, 'fixed' );
			$args['do_round']         = get_option( 'alg_gateways_fees_round_' . $current_gateway, 'no' );
			$args['precision']        = get_option( 'alg_gateways_fees_round_precision_' . $current_gateway, 0 );
			$args['is_taxable']       = get_option( 'alg_gateways_fees_is_taxable_' . $current_gateway, 'no' );
			$args['tax_class_id']     = get_option( 'alg_gateways_fees_tax_class_id_' . $current_gateway, 0 );
			$args['exclude_shipping'] = get_option( 'alg_gateways_fees_exclude_shipping_' . $current_gateway, 'no' );
			$args['add_taxes']        = get_option( 'alg_gateways_fees_add_taxes_' . $current_gateway, 'no' );
			$args['product_id']       = 0;
			$args['product_qty']      = 0;
			$args['fixed_usage']      = 'once';
			return $args;
		}

		/**
		 * Get_the_args_local.
		 *
		 * @param string $current_gateway Current Gateway.
		 * @param int    $product_id Product ID.
		 * @param int    $variation_id Variation ID.
		 * @param int    $product_qty Product Quantity.
		 * @version 2.5.0
		 * @since   2.0.0
		 */
		public function get_the_args_local( $current_gateway, $product_id, $variation_id, $product_qty ) {
			$do_add_product_name = ( 'yes' === get_option( 'alg_woocommerce_checkout_fees_per_product_add_product_name', 'no' ) );
			if ( $do_add_product_name ) {
				if ( isset( $variation_id ) && 0 != $variation_id ) {
					$_product               = wc_get_product( $variation_id );
					$product_formatted_name = ' &ndash; ' . $_product->get_title() . ' &ndash; ' .
					( version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' ) ?
						$_product->get_formatted_variation_attributes( true ) : wc_get_formatted_variation( $_product, true ) );
				} else {
					$_product               = wc_get_product( $product_id );
					$product_formatted_name = ' &ndash; ' . $_product->get_title();
				}
			}
			$args                     = array();
			$args['current_gateway']  = $current_gateway;
			$args['fee_scope']        = 'local';
			$args['is_enabled']       = get_post_meta( $product_id, '_alg_checkout_fees_enabled_' . $current_gateway, true );
			$args['min_cart_amount']  = alg_wc_cf()->core->convert_currency( get_post_meta( $product_id, '_alg_checkout_fees_min_cart_amount_' . $current_gateway, true ) );
			$args['max_cart_amount']  = alg_wc_cf()->core->convert_currency( get_post_meta( $product_id, '_alg_checkout_fees_max_cart_amount_' . $current_gateway, true ) );
			$args['min_fee']          = alg_wc_cf()->core->convert_currency( get_post_meta( $product_id, '_alg_checkout_fees_min_fee_' . $current_gateway, true ) );
			$args['max_fee']          = alg_wc_cf()->core->convert_currency( get_post_meta( $product_id, '_alg_checkout_fees_max_fee_' . $current_gateway, true ) );
			$args['min_fee_2']        = alg_wc_cf()->core->convert_currency( get_post_meta( $product_id, '_alg_checkout_fees_min_fee_2_' . $current_gateway, true ) );
			$args['max_fee_2']        = alg_wc_cf()->core->convert_currency( get_post_meta( $product_id, '_alg_checkout_fees_max_fee_2_' . $current_gateway, true ) );
			$args['coupons_rule']     = get_post_meta( $product_id, '_alg_checkout_fees_coupons_rule_' . $current_gateway, true );
			$args['coupons_rule_2']   = get_post_meta( $product_id, '_alg_checkout_fees_coupons_rule_2_' . $current_gateway, true );
			$args['fee_text']         = ( $do_add_product_name ) ?
			get_post_meta( $product_id, '_alg_checkout_fees_title_' . $current_gateway, true ) . $product_formatted_name :
			get_post_meta( $product_id, '_alg_checkout_fees_title_' . $current_gateway, true );
			$args['fee_value']        = get_post_meta( $product_id, '_alg_checkout_fees_value_' . $current_gateway, true );
			$args['fee_type']         = get_post_meta( $product_id, '_alg_checkout_fees_type_' . $current_gateway, true );
			$args['fee_text_2']       = ( $do_add_product_name ) ?
			get_post_meta( $product_id, '_alg_checkout_fees_title_2_' . $current_gateway, true ) . $product_formatted_name :
			get_post_meta( $product_id, '_alg_checkout_fees_title_2_' . $current_gateway, true );
			$args['fee_value_2']      = get_post_meta( $product_id, '_alg_checkout_fees_value_2_' . $current_gateway, true );
			$args['fee_type_2']       = get_post_meta( $product_id, '_alg_checkout_fees_type_2_' . $current_gateway, true );
			$args['do_round']         = get_post_meta( $product_id, '_alg_checkout_fees_rounding_enabled_' . $current_gateway, true );
			$args['precision']        = get_post_meta( $product_id, '_alg_checkout_fees_rounding_precision_' . $current_gateway, true );
			$args['is_taxable']       = get_post_meta( $product_id, '_alg_checkout_fees_tax_enabled_' . $current_gateway, true );
			$args['tax_class_id']     = get_post_meta( $product_id, '_alg_checkout_fees_tax_class_' . $current_gateway, true );
			$args['exclude_shipping'] = get_post_meta( $product_id, '_alg_checkout_fees_exclude_shipping_' . $current_gateway, true );
			$args['add_taxes']        = get_post_meta( $product_id, '_alg_checkout_fees_add_taxes_' . $current_gateway, true );
			$args['product_id']       = ( 'by_product' === get_post_meta( $product_id, '_alg_checkout_fees_percent_usage_' . $current_gateway, true ) ) ?
			( isset( $variation_id ) && 0 != $variation_id ? $variation_id : $product_id ) :
			0;
			$args['product_qty']      = $product_qty;
			$args['fixed_usage']      = get_post_meta( $product_id, '_alg_checkout_fees_fixed_usage_' . $current_gateway, true );
			return $args;
		}

	}

endif;

return new Alg_WC_Checkout_Fees_Args();
