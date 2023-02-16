<?php
/**
 * Custom Payment Gateways for WooCommerce - Input Fields Section Settings
 *
 * @version 1.6.1
 * @since   1.4.0
 * @author  Imaginate Solutions
 * @package cpgw
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Alg_WC_Custom_Payment_Gateways_Settings_Input_Fields' ) ) :

	/**
	 * Input Fields Class.
	 */
	class Alg_WC_Custom_Payment_Gateways_Settings_Input_Fields extends Alg_WC_Custom_Payment_Gateways_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 1.4.0
		 * @since   1.4.0
		 */
		public function __construct() {
			$this->id   = 'input_fields';
			$this->desc = __( 'Input Fields', 'custom-payment-gateways-woocommerce' );
			parent::__construct();
		}

		/**
		 * Get settings.
		 *
		 * @return array Settings Array.
		 * @version 1.6.1
		 * @since   1.4.0
		 */
		public function get_settings() {
			$settings = array(
				array(
					'title' => __( 'Input Fields', 'custom-payment-gateways-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_cpg_input_fields_section_options',
				),
				array(
					'title'   => __( 'Input fields', 'custom-payment-gateways-woocommerce' ),
					'desc'    => '<strong>' . __( 'Enable section', 'custom-payment-gateways-woocommerce' ) . '</strong>',
					'type'    => 'checkbox',
					'id'      => 'alg_wc_cpg_input_fields_enabled',
					'default' => 'yes',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_cpg_input_fields_section_options',
				),
				array(
					'title' => __( 'Order Details Options', 'custom-payment-gateways-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_cpg_input_fields_order_details_options',
				),
				array(
					'title'    => __( 'Add to order details', 'custom-payment-gateways-woocommerce' ),
					'desc'     => '<strong>' . __( 'Enable', 'custom-payment-gateways-woocommerce' ) . '</strong>',
					'desc_tip' => __( 'After order table.', 'custom-payment-gateways-woocommerce' ) . ' ' .
						__( 'For example on "Thank You" page.', 'custom-payment-gateways-woocommerce' ),
					'type'     => 'checkbox',
					'id'       => 'alg_wc_cpg_input_fields_add_to_order_details',
					'default'  => 'no',
				),
				array(
					'title'          => __( 'Templates', 'custom-payment-gateways-woocommerce' ),
					'desc'           => __( 'Header', 'custom-payment-gateways-woocommerce' ),
					'type'           => 'textarea',
					'id'             => 'alg_wc_cpg_input_fields_add_to_order_details_template[header]',
					'default'        => '<table class="widefat striped"><tbody>',
					'css'            => 'width:100%;',
					'alg_wc_cpg_raw' => true,
				),
				array(
					'desc'           => __( 'Each field', 'custom-payment-gateways-woocommerce' ) . ' | ' .
						sprintf(
							// translators: %s indicates the placeholders available.
							__( 'Placeholders: %s', 'custom-payment-gateways-woocommerce' ),
							'<code>' . implode( '</code>, <code>', array( '%title%', '%value%' ) ) . '</code>'
						),
					'type'           => 'textarea',
					'id'             => 'alg_wc_cpg_input_fields_add_to_order_details_template[field]',
					'default'        => '<tr><th>%title%</th><td>%value%</td></tr>',
					'css'            => 'width:100%;',
					'alg_wc_cpg_raw' => true,
				),
				array(
					'desc'           => __( 'Footer', 'custom-payment-gateways-woocommerce' ),
					'type'           => 'textarea',
					'id'             => 'alg_wc_cpg_input_fields_add_to_order_details_template[footer]',
					'default'        => '</tbody></table>',
					'css'            => 'width:100%;',
					'alg_wc_cpg_raw' => true,
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_cpg_input_fields_order_details_options',
				),
				array(
					'title' => __( 'Emails Options', 'custom-payment-gateways-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_cpg_input_fields_emails_options',
				),
				array(
					'title'    => __( 'Add to emails', 'custom-payment-gateways-woocommerce' ),
					'desc'     => '<strong>' . __( 'Enable', 'custom-payment-gateways-woocommerce' ) . '</strong>',
					'desc_tip' => __( 'After order table.', 'custom-payment-gateways-woocommerce' ),
					'type'     => 'checkbox',
					'id'       => 'alg_wc_cpg_input_fields_add_to_emails',
					'default'  => 'no',
				),
				array(
					'title'   => __( 'Sent to', 'custom-payment-gateways-woocommerce' ),
					'type'    => 'select',
					'class'   => 'chosen_select',
					'id'      => 'alg_wc_cpg_input_fields_add_to_emails_sent_to',
					'default' => 'all',
					'options' => array(
						'all'      => __( 'All emails', 'custom-payment-gateways-woocommerce' ),
						'admin'    => __( 'Admin emails only', 'custom-payment-gateways-woocommerce' ),
						'customer' => __( 'Customer emails only', 'custom-payment-gateways-woocommerce' ),
					),
				),
				array(
					'title'          => __( 'HTML templates', 'custom-payment-gateways-woocommerce' ),
					'desc'           => __( 'Header', 'custom-payment-gateways-woocommerce' ),
					'type'           => 'textarea',
					'id'             => 'alg_wc_cpg_input_fields_add_to_emails_template[header]',
					'default'        => '',
					'css'            => 'width:100%;',
					'alg_wc_cpg_raw' => true,
				),
				array(
					'desc'           => __( 'Each field', 'custom-payment-gateways-woocommerce' ) . ' | ' .
						sprintf(
							// translators: %s indicates the placeholders available.
							__( 'Placeholders: %s', 'custom-payment-gateways-woocommerce' ),
							'<code>' . implode( '</code>, <code>', array( '%title%', '%value%' ) ) . '</code>'
						),
					'type'           => 'textarea',
					'id'             => 'alg_wc_cpg_input_fields_add_to_emails_template[field]',
					'default'        => '<p>%title%: %value%</p>',
					'css'            => 'width:100%;',
					'alg_wc_cpg_raw' => true,
				),
				array(
					'desc'           => __( 'Footer', 'custom-payment-gateways-woocommerce' ),
					'type'           => 'textarea',
					'id'             => 'alg_wc_cpg_input_fields_add_to_emails_template[footer]',
					'default'        => '',
					'css'            => 'width:100%;',
					'alg_wc_cpg_raw' => true,
				),
				array(
					'title'          => __( 'Plain text templates', 'custom-payment-gateways-woocommerce' ),
					'desc'           => __( 'Header', 'custom-payment-gateways-woocommerce' ),
					'type'           => 'textarea',
					'id'             => 'alg_wc_cpg_input_fields_add_to_emails_template_plain[header]',
					'default'        => '',
					'css'            => 'width:100%;',
					'alg_wc_cpg_raw' => true,
				),
				array(
					'desc'           => __( 'Each field', 'custom-payment-gateways-woocommerce' ) . ' | ' .
						sprintf(
							// translators: %s indicates the placeholders available.
							__( 'Placeholders: %s', 'custom-payment-gateways-woocommerce' ),
							'<code>' . implode( '</code>, <code>', array( '%title%', '%value%' ) ) . '</code>'
						),
					'type'           => 'textarea',
					'id'             => 'alg_wc_cpg_input_fields_add_to_emails_template_plain[field]',
					'default'        => '%title%: %value%' . "\n",
					'css'            => 'width:100%;',
					'alg_wc_cpg_raw' => true,
				),
				array(
					'desc'           => __( 'Footer', 'custom-payment-gateways-woocommerce' ),
					'type'           => 'textarea',
					'id'             => 'alg_wc_cpg_input_fields_add_to_emails_template_plain[footer]',
					'default'        => '',
					'css'            => 'width:100%;',
					'alg_wc_cpg_raw' => true,
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_cpg_input_fields_emails_options',
				),
				array(
					'title' => __( 'General Options', 'custom-payment-gateways-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_cpg_input_fields_general_options',
				),
				array(
					'title'   => __( 'Add to order notes', 'custom-payment-gateways-woocommerce' ),
					'desc'    => __( 'Enable', 'custom-payment-gateways-woocommerce' ),
					'type'    => 'checkbox',
					'id'      => 'alg_wc_cpg_input_fields_add_order_note',
					'default' => 'no',
				),
				array(
					'title'   => __( 'Process in "Advanced Order Export For WooCommerce" plugin', 'custom-payment-gateways-woocommerce' ),
					'desc'    => __( 'Enable', 'custom-payment-gateways-woocommerce' ),
					'type'    => 'checkbox',
					'id'      => 'alg_wc_cpg_input_fields_woe_enabled',
					'default' => 'no',
				),
				array(
					'desc'           => __( 'Template', 'custom-payment-gateways-woocommerce' ),
					'desc_tip'       => __( 'Template for a single input field output.', 'custom-payment-gateways-woocommerce' ),
					'type'           => 'text',
					'id'             => 'alg_wc_cpg_input_fields_woe_template',
					'default'        => '%title%: %value%',
					'alg_wc_cpg_raw' => true,
				),
				array(
					'desc'           => __( 'Glue', 'custom-payment-gateways-woocommerce' ),
					'desc_tip'       => __( 'Inserted between input field in output.', 'custom-payment-gateways-woocommerce' ),
					'type'           => 'text',
					'id'             => 'alg_wc_cpg_input_fields_woe_glue',
					'default'        => ' | ',
					'alg_wc_cpg_raw' => true,
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_cpg_input_fields_general_options',
				),
			);
			return $settings;
		}

	}

endif;

return new Alg_WC_Custom_Payment_Gateways_Settings_Input_Fields();
