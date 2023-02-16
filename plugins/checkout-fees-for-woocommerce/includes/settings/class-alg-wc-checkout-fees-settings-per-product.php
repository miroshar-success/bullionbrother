<?php
/**
 * Checkout Fees for WooCommerce - Per Product Meta Boxes
 *
 * @version 2.5.1
 * @since   1.1.0
 * @author  Tyche Softwares
 *
 * @package checkout-fees-for-woocommerce/settings/Product
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Checkout_Fees_Settings_Per_Product' ) ) :

	/**
	 * Product based settings.
	 */
	class Alg_WC_Checkout_Fees_Settings_Per_Product {

		/**
		 * Constructor.
		 *
		 * @version 2.5.0
		 */
		public function __construct() {

			$this->id   = 'per_product';
			$this->desc = __( 'Payment Gateway Based Fees and Discounts', 'checkout-fees-for-woocommerce' );

			if ( 'yes' === get_option( 'alg_woocommerce_checkout_fees_per_product_enabled', 'no' ) ) {
				add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
				add_action( 'save_post_product', array( $this, 'save_meta_box' ), PHP_INT_MAX, 2 );
				add_action( 'admin_init', array( $this, 'enqueue_styles_and_scripts' ) );
			}

		}

		/**
		 * Enqueue_styles_and_scripts.
		 *
		 * @version 2.5.0
		 */
		public function enqueue_styles_and_scripts() {
			if ( array_key_exists( 'post', $_GET ) || ( isset( $_GET['post_type'] ) && 'product' === $_GET['post_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				wp_enqueue_style( 'checkout-fees-admin', alg_wc_cf()->plugin_url() . '/includes/css/checkout-fees-admin.css', array(), alg_wc_cf()->version );
				wp_enqueue_script( 'checkout-fees-admin-js', alg_wc_cf()->plugin_url() . '/includes/js/checkout-fees-admin.js', array(), alg_wc_cf()->version, false );
			}
		}

		/**
		 * Get_meta_box_options.
		 *
		 * @version 2.5.0
		 */
		public function get_meta_box_options() {
			return array(
				array(
					'name'    => 'alg_checkout_fees_enabled',
					'default' => 'no',
					'title'   => '',
					'type'    => 'select',
					'options' => array(
						'no'  => __( 'Disabled', 'checkout-fees-for-woocommerce' ),
						'yes' => __( 'Enabled', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'type'  => 'title',
					'title' => __( 'Fee/Discount', 'checkout-fees-for-woocommerce' ),
				),
				array(
					'name'    => 'alg_checkout_fees_title',
					'default' => '',
					'type'    => 'text',
					'title'   => __( 'Fee/Discount', 'checkout-fees-for-woocommerce' ) . ' ' . __( 'title', 'checkout-fees-for-woocommerce' ),
				),
				array(
					'name'    => 'alg_checkout_fees_global_override',
					'default' => 'no',
					'type'    => 'select',
					'title'   => __( 'Override global fee', 'checkout-fees-for-woocommerce' ),
					'options' => array(
						'no'  => __( 'No', 'checkout-fees-for-woocommerce' ),
						'yes' => __( 'Yes', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'name'    => 'alg_checkout_fees_type',
					'default' => 'fixed',
					'type'    => 'select',
					'title'   => __( 'Fee type', 'checkout-fees-for-woocommerce' ),
					'options' => array(
						'fixed'   => __( 'Fixed', 'checkout-fees-for-woocommerce' ),
						'percent' => __( 'Percent', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'name'        => 'alg_checkout_fees_value',
					'default'     => '',
					'type'        => 'number',
					'title'       => __( 'Fee value', 'checkout-fees-for-woocommerce' ),
					'custom_atts' => ' step=0.0001',
				),
				array(
					'name'        => 'alg_checkout_fees_min_fee',
					'default'     => '',
					'type'        => 'number',
					'title'       => __( 'Minimum fee value', 'checkout-fees-for-woocommerce' ),
					'custom_atts' => ' step=0.0001',
				),
				array(
					'name'        => 'alg_checkout_fees_max_fee',
					'default'     => '',
					'type'        => 'number',
					'title'       => __( 'Maximum fee value', 'checkout-fees-for-woocommerce' ),
					'custom_atts' => ' step=0.0001',
				),
				array(
					'name'    => 'alg_checkout_fees_coupons_rule',
					'default' => 'disabled',
					'type'    => 'select',
					'title'   => __( 'Coupons rule', 'checkout-fees-for-woocommerce' ),
					'options' => array(
						'disabled'           => __( 'Disabled', 'checkout-fees-for-woocommerce' ),
						'only_if_no_coupons' => __( 'Apply fees only if no coupons were applied', 'checkout-fees-for-woocommerce' ),
						'only_if_coupons'    => __( 'Apply fees only if any coupons were applied', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'type'  => 'title',
					'title' => __( 'Additional Fee/Discount (Optional)', 'checkout-fees-for-woocommerce' ),
				),
				array(
					'name'    => 'alg_checkout_fees_title_2',
					'default' => '',
					'type'    => 'text',
					'title'   => __( 'Fee/Discount', 'checkout-fees-for-woocommerce' ) . ' ' . __( 'title', 'checkout-fees-for-woocommerce' ),
				),
				array(
					'name'    => 'alg_checkout_fees_global_override_fee_2',
					'default' => 'no',
					'type'    => 'select',
					'title'   => __( 'Override global fee', 'checkout-fees-for-woocommerce' ),
					'options' => array(
						'no'  => __( 'No', 'checkout-fees-for-woocommerce' ),
						'yes' => __( 'Yes', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'name'    => 'alg_checkout_fees_type_2',
					'default' => 'fixed',
					'type'    => 'select',
					'title'   => __( 'Fee type', 'checkout-fees-for-woocommerce' ),
					'options' => array(
						'fixed'   => __( 'Fixed', 'checkout-fees-for-woocommerce' ),
						'percent' => __( 'Percent', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'name'        => 'alg_checkout_fees_value_2',
					'default'     => '',
					'type'        => 'number',
					'title'       => __( 'Fee value', 'checkout-fees-for-woocommerce' ),
					'custom_atts' => ' step=0.0001',
				),
				array(
					'name'        => 'alg_checkout_fees_min_fee_2',
					'default'     => '',
					'type'        => 'number',
					'title'       => __( 'Minimum fee value', 'checkout-fees-for-woocommerce' ),
					'custom_atts' => ' step=0.0001',
				),
				array(
					'name'        => 'alg_checkout_fees_max_fee_2',
					'default'     => '',
					'type'        => 'number',
					'title'       => __( 'Maximum fee value', 'checkout-fees-for-woocommerce' ),
					'custom_atts' => ' step=0.0001',
				),
				array(
					'name'    => 'alg_checkout_fees_coupons_rule_2',
					'default' => 'disabled',
					'type'    => 'select',
					'title'   => __( 'Coupons rule', 'checkout-fees-for-woocommerce' ),
					'options' => array(
						'disabled'           => __( 'Disabled', 'checkout-fees-for-woocommerce' ),
						'only_if_no_coupons' => __( 'Apply fees only if no coupons were applied', 'checkout-fees-for-woocommerce' ),
						'only_if_coupons'    => __( 'Apply fees only if any coupons were applied', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'type'  => 'title',
					'title' => __( 'General Options', 'checkout-fees-for-woocommerce' ),
				),
				array(
					'name'        => 'alg_checkout_fees_min_cart_amount',
					'default'     => '',
					'type'        => 'number',
					'title'       => __( 'Minimum cart amount', 'checkout-fees-for-woocommerce' ),
					'custom_atts' => ' step=0.0001 min=0',
				),
				array(
					'name'        => 'alg_checkout_fees_max_cart_amount',
					'default'     => '',
					'type'        => 'number',
					'title'       => __( 'Maximum cart amount', 'checkout-fees-for-woocommerce' ),
					'custom_atts' => ' step=0.0001 min=0',
				),
				array(
					'name'    => 'alg_checkout_fees_rounding_enabled',
					'default' => 'no',
					'type'    => 'select',
					'options' => array(
						'no'  => __( 'Disabled', 'checkout-fees-for-woocommerce' ),
						'yes' => __( 'Enabled', 'checkout-fees-for-woocommerce' ),
					),
					'title'   => __( 'Rounding', 'checkout-fees-for-woocommerce' ),
				),
				array(
					'name'        => 'alg_checkout_fees_rounding_precision',
					'default'     => '',
					'type'        => 'number',
					'title'       => __( 'Rounding precision', 'checkout-fees-for-woocommerce' ),
					'custom_atts' => ' step=1 min=0',
					'tooltip'     => __( 'Number of decimals', 'woocommerce' ),
				),
				array(
					'name'    => 'alg_checkout_fees_tax_enabled',
					'default' => 'no',
					'type'    => 'select',
					'options' => array(
						'no'  => __( 'Disabled', 'checkout-fees-for-woocommerce' ),
						'yes' => __( 'Enabled', 'checkout-fees-for-woocommerce' ),
					),
					'title'   => __( 'Taxes', 'checkout-fees-for-woocommerce' ),
				),
				array(
					'name'    => 'alg_checkout_fees_tax_class',
					'default' => '',
					'type'    => 'select',
					'title'   => __( 'Tax class', 'checkout-fees-for-woocommerce' ),
					'options' => array_merge( array( __( 'Standard rate', 'checkout-fees-for-woocommerce' ) ), WC_Tax::get_tax_classes() ),
				),
				array(
					'name'    => 'alg_checkout_fees_exclude_shipping',
					'default' => 'no',
					'type'    => 'select',
					'options' => array(
						'no'  => __( 'No', 'checkout-fees-for-woocommerce' ),
						'yes' => __( 'Yes', 'checkout-fees-for-woocommerce' ),
					),
					'title'   => __( 'Exclude shipping', 'checkout-fees-for-woocommerce' ),
				),
				array(
					'name'    => 'alg_checkout_fees_add_taxes',
					'default' => 'no',
					'type'    => 'select',
					'options' => array(
						'no'  => __( 'No', 'checkout-fees-for-woocommerce' ),
						'yes' => __( 'Yes', 'checkout-fees-for-woocommerce' ),
					),
					'title'   => __( 'Add taxes', 'checkout-fees-for-woocommerce' ),
				),
				array(
					'name'    => 'alg_checkout_fees_percent_usage',
					'default' => 'for_all_cart',
					'type'    => 'select',
					'title'   => __( 'Fee calculation (for Percent fees)', 'checkout-fees-for-woocommerce' ),
					'options' => array(
						'for_all_cart' => __( 'For all cart', 'checkout-fees-for-woocommerce' ),
						'by_product'   => __( 'Only for current product', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'name'    => 'alg_checkout_fees_fixed_usage',
					'default' => 'once',
					'type'    => 'select',
					'title'   => __( 'Fee calculation (for Fixed fees)', 'checkout-fees-for-woocommerce' ),
					'options' => array(
						'once'        => __( 'Once', 'checkout-fees-for-woocommerce' ),
						'by_quantity' => __( 'Multiply by product quantity', 'checkout-fees-for-woocommerce' ),
					),
				),
			);
		}

		/**
		 * Save_meta_box.
		 *
		 * @param int        $post_id Product ID.
		 * @param WC_Product $post Product object.
		 * @version 2.5.0
		 */
		public function save_meta_box( $post_id, $post ) {
			// Check if we are saving with current metabox displayed.
			if ( ! isset( $_POST[ 'alg_checkout_fees_' . $this->id . '_save_post' ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				return;
			}
			// Save options.
			$available_gateways = WC()->payment_gateways->payment_gateways();
			foreach ( $available_gateways as $gateway_key => $gateway ) {
				if ( 'bacs' !== $gateway_key && ! apply_filters( 'alg_wc_checkout_fees_option', false, 'per_product' ) ) {
					continue;
				}
				foreach ( $this->get_meta_box_options() as $option ) {
					if ( 'title' === $option['type'] ) {
						continue;
					}
					$option_name  = $option['name'] . '_' . $gateway_key;
					$option_value = ( isset( $_POST[ $option_name ] ) ? sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) ) : $option['default'] ); // phpcs:ignore WordPress.Security.NonceVerification
					update_post_meta( $post_id, '_' . $option_name, $option_value );
				}
			}
		}

		/**
		 * Add_meta_box.
		 *
		 * @version 2.5.0
		 */
		public function add_meta_box() {
			add_meta_box( 'alg-' . $this->id, $this->desc, array( $this, 'create_meta_box' ), 'product', 'normal', 'default' );
		}

		/**
		 * Create_meta_box.
		 *
		 * @version 2.5.1
		 */
		public function create_meta_box() {

			$current_post_id    = get_the_ID();
			$available_gateways = WC()->payment_gateways->payment_gateways();

			$html  = '';
			$html .= '<div class="alg_checkout_fees">';
			$html .= '<ul class="tabs">';

			/* translators: %s: Upgrade to pro url */
			$upgrade_url = sprintf( __( 'You will need <a target="_blank" href="%s">Pro version</a> of the plugin to set fees for this gateway on per product basis.', 'checkout-fees-for-woocommerce' ), 'https://www.tychesoftwares.com/store/premium-plugins/payment-gateway-based-fees-and-discounts-for-woocommerce-plugin/' );

			// Tab Labels.
			$html .= '<li class="labels">';
			$i     = 0;
			foreach ( $available_gateways as $gateway_key => $gateway ) {
				$i++;
				$gateway_title = ( '' === $gateway->title ? $gateway_key : $gateway->title );
				$label_class   = ( 1 == $i ? 'alg-clicked' : '' );
				$html         .= '<label for="tab-' . $gateway_key . '" id="label-' . $gateway_key . '" class="' . $label_class . '">' . $gateway_title . '</label>';
			}
			$html .= '</li>';

			// Tab Content.
			$i = 0;
			foreach ( $available_gateways as $gateway_key => $gateway ) {
				$i++;
				$html             .= '<li>';
					$gateway_title = ( '' === $gateway->title ) ? $gateway_key : $gateway->title;
					$html         .= '<input type="radio" id="tab-' . $gateway_key . '" name="tabs"' . checked( $i, 1, false ) . '>';
					$html         .= '<div class="tab-content" id="tab-content-' . $gateway_key . '">';
						$html     .= ( 1 != $i && ! apply_filters( 'alg_wc_checkout_fees_option', false, 'per_product' ) ) ?
							'<div style="padding: 20px; background-color: #d6d5d3; margin-bottom: 15px;">'
								. __( 'In free version only <strong>Direct Bank Transfer (BACS)</strong> fees are available on per product basis.', 'checkout-fees-for-woocommerce' ) . ' '
								. $upgrade_url
							. '</div>' : '';
						$html     .= '<table>';
				foreach ( $this->get_meta_box_options() as $option ) {
					if ( 'title' === $option['type'] ) {
						$html .= '<tr>';
						$html .= '<th style="text-align:right;padding:10px;" colspan="2"><span style="font-size:larger;font-weight:bold;">' . $option['title'] . '</span></th>';
						$html .= '</tr>';
						continue;
					}
					if ( ! isset( $option['custom_atts'] ) ) {
						$option['custom_atts'] = '';
					}
					$option['custom_atts'] .= ( 'bacs' != $gateway_key && ! apply_filters( 'alg_wc_checkout_fees_option', false, 'per_product' ) ? ' disabled="disabled"' : '' );
					$option_name            = $option['name'] . '_' . $gateway_key;
					$option_value           = get_post_meta( $current_post_id, '_' . $option_name, true );
					$option_title           = ( '' === $option['title'] ) ? '<span style="font-size:large;font-weight:bold;">' . $gateway_title . '</span>' : $option['title'];
					$input_ending           = ' id="' . $option_name . '" name="' . $option_name . '" value="' . $option_value . '"' . $option['custom_atts'] . '>';
					$select_options         = '';
					if ( isset( $option['options'] ) ) {
						foreach ( $option['options'] as $select_option_key => $select_option_value ) {
							$select_options .= '<option value="' . $select_option_key . '"' . selected( $option_value, $select_option_key, false ) . '>' . $select_option_value . '</option>';
						}
					}
					$field_html = '';
					switch ( $option['type'] ) {
						case 'text':
							$field_html = '<input style="min-width:300px;" class="short" type="' . $option['type'] . '"' . $input_ending;
							break;
						case 'number':
							$field_html = '<input style="min-width:300px;" class="short" type="' . $option['type'] . '"' . $input_ending;
							break;
						case 'select':
							$ro         = ( 'bacs' !== $gateway_key && ! apply_filters( 'alg_wc_checkout_fees_option', false, 'per_product' ) ) ? ' disabled="disabled"' : '';
							$field_html = '<select style="min-width:300px;" name="' . $option_name . '" id="' . $option_name . '" style="" class=""' . $ro . '>' . $select_options . '</select>';
							break;
					}
					$tooltip = ( isset( $option['tooltip'] ) ? wc_help_tip( $option['tooltip'], true ) : '' );
					$html   .= '<tr>';
					$html   .= '<th style="text-align:right;padding:10px;">' . $tooltip . $option_title . '</th>';
					$html   .= '<td>' . $field_html . '</td>';
					$html   .= '</tr>';
				}
						$html .= '</table>';
					$html     .= '</div>';
				$html         .= '</li>';
			}
			$html .= '</ul>';
			$html .= '</div>';
			echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<input type="hidden" name="alg_checkout_fees_' . $this->id . '_save_post" value="alg_checkout_fees_' . $this->id . '_save_post">';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		}

	}

endif;

return new Alg_WC_Checkout_Fees_Settings_Per_Product();
