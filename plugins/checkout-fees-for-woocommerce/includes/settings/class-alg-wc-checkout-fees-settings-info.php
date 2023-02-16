<?php
/**
 * Checkout Fees for WooCommerce - Settings Section - Info
 *
 * @version 2.5.0
 * @since   2.4.0
 * @author  Tyche Softwares
 *
 * @package checkout-fees-for-woocommerce/Settings/Info
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Checkout_Fees_Settings_Info' ) ) :
	/**
	 * Info Settings section.
	 */
	class Alg_WC_Checkout_Fees_Settings_Info extends Alg_WC_Checkout_Fees_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 2.5.0
		 * @since   2.4.0
		 */
		public function __construct() {
			$this->id   = 'info';
			$this->desc = __( 'Info', 'checkout-fees-for-woocommerce' );
			parent::__construct();
		}

		/**
		 * Get_settings.
		 *
		 * @version 2.5.0
		 * @since   2.4.0
		 */
		public function get_settings() {
			$settings = array(
				array(
					'title' => __( 'Info on Single Product', 'checkout-fees-for-woocommerce' ),
					'type'  => 'title',
					'desc'  =>
						sprintf(
							__( 'Values that will be replaced in templates below are: %s.', 'checkout-fees-for-woocommerce' ),
							'<code>' . implode(
								'</code>, <code>',
								array(
									'%gateway_title%',
									'%gateway_description%',
									'%gateway_icon%',
									'%product_title%',
									'%product_gateway_price%',
									'%product_variation_atts%',
									'%product_original_price%',
									'%product_price_diff%',
									'%product_price_diff_percent%',
								)
							) . '</code>'
						) . '<br><br>' .
						sprintf(
							__( 'You can also use %1$s and %2$s shortcodes. Or %3$s and %4$s functions.', 'checkout-fees-for-woocommerce' ),
							'<code>[alg_show_checkout_fees_full_info]</code>',
							'<code>[alg_show_checkout_fees_lowest_price_info]</code>',
							'<code>do_shortcode( \'[alg_show_checkout_fees_full_info]\' );</code>',
							'<code>do_shortcode( \'[alg_show_checkout_fees_lowest_price_info]\' );</code>'
						),
					'id'    => 'alg_woocommerce_checkout_fees_info_options',
				),
				array(
					'title'    => __( 'Info on single product page', 'checkout-fees-for-woocommerce' ),
					'desc'     => __( 'Show', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'This will add gateway fee/discount info on single product frontend page.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_woocommerce_checkout_fees_info_enabled',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'desc'                              => __( 'Start HTML', 'checkout-fees-for-woocommerce' ),
					'id'                                => 'alg_woocommerce_checkout_fees_info_start_template',
					'default'                           => '<table>',
					'type'                              => 'textarea',
					'css'                               => 'width:100%',
					'alg_woocommerce_checkout_fees_raw' => true,
				),
				array(
					'desc'                              => __( 'Row template HTML', 'checkout-fees-for-woocommerce' ),
					'id'                                => 'alg_woocommerce_checkout_fees_info_row_template',
					'default'                           => '<tr><td><strong>%gateway_title%</strong></td><td>%product_original_price%</td><td>%product_gateway_price%</td><td>%product_price_diff%</td></tr>',
					'type'                              => 'textarea',
					'css'                               => 'width:100%',
					'alg_woocommerce_checkout_fees_raw' => true,
				),
				array(
					'desc'                              => __( 'End HTML', 'checkout-fees-for-woocommerce' ),
					'id'                                => 'alg_woocommerce_checkout_fees_info_end_template',
					'default'                           => '</table>',
					'type'                              => 'textarea',
					'css'                               => 'width:100%',
					'alg_woocommerce_checkout_fees_raw' => true,
				),
				array(
					'desc'    => __( 'Position', 'checkout-fees-for-woocommerce' ),
					'id'      => 'alg_woocommerce_checkout_fees_info_hook',
					'default' => 'woocommerce_single_product_summary',
					'type'    => 'select',
					'options' => array(
						'woocommerce_single_product_summary' => __( 'Inside product summary', 'checkout-fees-for-woocommerce' ),
						'woocommerce_before_single_product_summary' => __( 'Before product summary', 'checkout-fees-for-woocommerce' ),
						'woocommerce_after_single_product_summary' => __( 'After product summary', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'desc'    => __( 'Position priority (i.e. order)', 'checkout-fees-for-woocommerce' ),
					'id'      => 'alg_woocommerce_checkout_fees_info_hook_priority',
					'default' => 20,
					'type'    => 'number',
				),
				array(
					'title'    => __( 'Lowest price info on single product page', 'checkout-fees-for-woocommerce' ),
					'desc'     => __( 'Show', 'checkout-fees-for-woocommerce' ),
					'desc_tip' => __( 'This will add gateway fee/discount lowest price info on single product frontend page.', 'checkout-fees-for-woocommerce' ),
					'id'       => 'alg_woocommerce_checkout_fees_lowest_price_info_enabled',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'desc'                              => __( 'Template HTML', 'checkout-fees-for-woocommerce' ),
					'id'                                => 'alg_woocommerce_checkout_fees_lowest_price_info_template',
					'default'                           => '<p><strong>%gateway_title%</strong> %product_gateway_price% (%product_price_diff%)</p>',
					'type'                              => 'textarea',
					'css'                               => 'width:100%',
					'alg_woocommerce_checkout_fees_raw' => true,
				),
				array(
					'desc'    => __( 'Position', 'checkout-fees-for-woocommerce' ),
					'id'      => 'alg_woocommerce_checkout_fees_lowest_price_info_hook',
					'default' => 'woocommerce_single_product_summary',
					'type'    => 'select',
					'options' => array(
						'woocommerce_single_product_summary' => __( 'Inside product summary', 'checkout-fees-for-woocommerce' ),
						'woocommerce_before_single_product_summary' => __( 'Before product summary', 'checkout-fees-for-woocommerce' ),
						'woocommerce_after_single_product_summary' => __( 'After product summary', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'desc'    => __( 'Position priority (i.e. order)', 'checkout-fees-for-woocommerce' ),
					'id'      => 'alg_woocommerce_checkout_fees_lowest_price_info_hook_priority',
					'default' => 20,
					'type'    => 'number',
				),
				array(
					'title'   => __( 'Variable products info', 'checkout-fees-for-woocommerce' ),
					'id'      => 'alg_woocommerce_checkout_fees_variable_info',
					'default' => 'for_each_variation',
					'type'    => 'select',
					'options' => array(
						'for_each_variation' => __( 'For each variation', 'checkout-fees-for-woocommerce' ),
						'ranges'             => __( 'As price range', 'checkout-fees-for-woocommerce' ),
					),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_woocommerce_checkout_fees_info_options',
				),
			);
			return $settings;
		}

	}

endif;

return new Alg_WC_Checkout_Fees_Settings_Info();
