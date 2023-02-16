<?php
/**
 * Custom Payment Gateways for WooCommerce - Gateways Form Fields
 *
 * @version 1.6.3
 * @since   1.0.0
 * @author  Imaginate Solutions
 * @package cpgw
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$div_style = 'background-color: #fefefe; padding: 10px; border: 1px solid #d8d8d8; width: fit-content; font-style: italic; font-size: small;';

$fields = array(
	'enabled'                => array(
		'title'   => __( 'Enable/Disable', 'woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable custom gateway', 'custom-payment-gateways-woocommerce' ),
		'default' => 'no',
	),
	'title'                  => array(
		'title'       => __( 'Title', 'woocommerce' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
		'default'     => __( 'Custom Payment Gateway', 'custom-payment-gateways-woocommerce' ),
		'desc_tip'    => true,
	),
	'description'            => array(
		'title'       => __( 'Description', 'woocommerce' ),
		'type'        => 'textarea',
		'description' => __( 'Payment method description that the customer will see on your checkout.', 'woocommerce' ),
		'default'     => __( 'Custom Payment Gateway Description.', 'custom-payment-gateways-woocommerce' ),
		'desc_tip'    => true,
	),
	'instructions'           => array(
		'title'       => __( 'Instructions', 'woocommerce' ),
		'type'        => 'textarea',
		'description' => __( 'Instructions that will be added to the thank you page.', 'custom-payment-gateways-woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
	),
	'instructions_in_email'  => array(
		'title'       => __( 'Email instructions', 'custom-payment-gateways-woocommerce' ),
		'type'        => 'textarea',
		'description' => __( 'Instructions that will be added to the emails.', 'custom-payment-gateways-woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
	),
	'icon'                   => array(
		'title'       => __( 'Icon', 'custom-payment-gateways-woocommerce' ),
		'type'        => 'text',
		'desc_tip'    => __( 'If you want to show an image next to the gateway\'s name on the frontend, enter a URL to an image.', 'custom-payment-gateways-woocommerce' ),
		'default'     => '',
		'description' => $icon_desc,
		'css'         => 'width:100%',
	),
	'advanced_options'       => array(
		'title' => __( 'Advanced Options', 'custom-payment-gateways-woocommerce' ),
		'type'  => 'title',
	),
	'min_amount'             => array(
		'title'             => __( 'Minimum order amount', 'custom-payment-gateways-woocommerce' ),
		'type'              => 'number',
		'desc_tip'          => __( 'If you want to set minimum order amount (excluding fees) to show this gateway on frontend, enter a number here. Set to 0 to disable.', 'custom-payment-gateways-woocommerce' ),
		'default'           => 0,
		'description'       => apply_filters(
			'alg_wc_custom_payment_gateways_settings',
			sprintf(
				'<div style="' . $div_style . '">You will need <a target="_blank" href="%s">Custom Payment Gateways for WooCommerce Pro plugin</a> to use minimum order amount option.</div>',
				'https://imaginate-solutions.com/downloads/custom-payment-gateways-for-woocommerce/'
			)
		),
		'custom_attributes' => apply_filters( 'alg_wc_custom_payment_gateways_settings', array( 'disabled' => 'disabled' ), 'array_min_amount' ),
	),
	'enable_for_methods'     => array(
		'title'             => __( 'Enable for shipping methods', 'woocommerce' ),
		'type'              => 'multiselect',
		'class'             => 'chosen_select',
		'default'           => '',
		'description'       => __( 'If gateway is only available for certain shipping methods, set it up here. Leave blank to enable for all methods.', 'custom-payment-gateways-woocommerce' ),
		'options'           => $shipping_methods,
		'desc_tip'          => true,
		'custom_attributes' => array( 'data-placeholder' => __( 'Select shipping methods', 'woocommerce' ) ),
	),
	'enable_for_virtual'     => array(
		'title'       => __( 'Accept for virtual orders', 'woocommerce' ),
		'label'       => __( 'Accept', 'custom-payment-gateways-woocommerce' ),
		'description' => __( 'Accept gateway if the order is virtual.', 'custom-payment-gateways-woocommerce' ),
		'type'        => 'checkbox',
		'default'     => 'yes',
	),
	'default_order_status'   => array(
		'title'       => __( 'Default order status', 'custom-payment-gateways-woocommerce' ),
		'description' => sprintf(
			'In case you need more custom order statuses - we suggest using free <a target="_blank" href="%s">Order Status for WooCommerce plugin</a>.',
			'https://wordpress.org/plugins/custom-order-statuses-woocommerce/'
		),
		'default'     => apply_filters( 'woocommerce_default_order_status', 'pending' ),
		'type'        => 'select',
		'class'       => 'chosen_select',
		'options'     => alg_wc_custom_payment_gateways_get_order_statuses(),
	),
	'send_email_to_admin'    => array(
		'title'       => __( 'Send additional emails', 'custom-payment-gateways-woocommerce' ),
		'description' => sprintf(
			// translators: %s points a link to new order email settings.
			__( 'This may help if you are using pending or custom default order status and not receiving %s emails.', 'custom-payment-gateways-woocommerce' ),
			'<a target="_blank" href="' . admin_url( 'admin.php?page=wc-settings&tab=email&section=wc_email_new_order' ) . '">' .
			__( 'admin new order', 'custom-payment-gateways-woocommerce' ) . '</a>'
		),
		'label'       => __( 'Send to admin', 'custom-payment-gateways-woocommerce' ),
		'default'     => 'no',
		'type'        => 'checkbox',
	),
	'send_email_to_customer' => array(
		'label'       => __( 'Send to customer', 'custom-payment-gateways-woocommerce' ),
		'description' => sprintf(
			// translators: %s points a link to processing order email settings.
			__( 'This may help if you are using pending or custom default order status and not receiving %s emails.', 'custom-payment-gateways-woocommerce' ),
			'<a target="_blank" href="' . admin_url( 'admin.php?page=wc-settings&tab=email&section=wc_email_customer_processing_order' ) . '">' .
			__( 'customer processing order', 'custom-payment-gateways-woocommerce' ) . '</a>'
		),
		'default'     => 'no',
		'type'        => 'checkbox',
	),
	'custom_return_url'      => array(
		'title'       => __( 'Custom return URL (Thank You page)', 'custom-payment-gateways-woocommerce' ),
		'label'       => __( 'URL', 'custom-payment-gateways-woocommerce' ),
		'desc_tip'    => __( 'Enter full URL with http(s).', 'custom-payment-gateways-woocommerce' ),
		'description' => __( 'Optional. Leave blank to use default URL.', 'custom-payment-gateways-woocommerce' ) . ' ' .
			sprintf(
				// translators: %s Available placeholders.
				__( 'Available placeholders: %s.', 'custom-payment-gateways-woocommerce' ),
				'<code>' . implode( '</code>, <code>', array( '%order_id%', '%order_key%', '%order_total%' ) ) . '</code>'
			),
		'default'     => '',
		'type'        => 'text',
		'css'         => 'width:100%',
	),
);
if ( 'yes' === get_option( 'alg_wc_cpg_fees_enabled', 'yes' ) ) {
	$fields = array_merge(
		$fields,
		array(
			'fees_options' => array(
				'title'       => __( 'Fees Options', 'custom-payment-gateways-woocommerce' ),
				'type'        => 'title',
				'description' => __( 'This section allows you to set extra checkout fees.', 'custom-payment-gateways-woocommerce' ) . ' ' .
					sprintf(
						// translators: %s points a link to CPG General settings.
						__( 'General options are in %s.', 'custom-payment-gateways-woocommerce' ),
						'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_custom_payment_gateways&section=fees' ) . '" target="_blank">' .
						__( 'WooCommerce > Settings > Custom Payment Gateways > Fees', 'custom-payment-gateways-woocommerce' ) . '</a>'
					),
			),
			'fees_total'   => array(
				'title'             => __( 'Number of fees', 'custom-payment-gateways-woocommerce' ),
				'desc_tip'          => __( 'Save changes to see new options.', 'custom-payment-gateways-woocommerce' ),
				'description'       => apply_filters(
					'alg_wc_custom_payment_gateways_settings',
					sprintf(
						'<div style="' . $div_style . '">You will need <a target="_blank" href="%s">Custom Payment Gateways for WooCommerce Pro plugin</a> to add more than one fee.</div>',
						'https://imaginate-solutions.com/downloads/custom-payment-gateways-for-woocommerce/'
					),
					'total_number'
				),
				'default'           => 1,
				'type'              => 'number',
				'custom_attributes' => apply_filters( 'alg_wc_custom_payment_gateways_settings', array( 'readonly' => 'readonly' ), 'array_fees' ),
			),
		)
	);
	for ( $i = 1; $i <= apply_filters( 'alg_wc_custom_payment_gateways_values', 1, 'total_fees', $this ); $i++ ) { // phpcs:ignore
		$fields = array_merge(
			$fields,
			array(
				'fee_enabled_' . $i    => array(
					// translators: %s points to Fee number.
					'title'   => sprintf( __( 'Fee %s', 'custom-payment-gateways-woocommerce' ), '#' . $i ),
					'label'   => __( 'Enabled', 'custom-payment-gateways-woocommerce' ),
					'type'    => 'checkbox',
					'default' => 'yes',
				),
				'fee_name_' . $i       => array(
					'description' => __( 'Title', 'custom-payment-gateways-woocommerce' ),
					'desc_tip'    => __( 'Name for the fee.', 'custom-payment-gateways-woocommerce' ) . ' ' .
						__( 'Multiple fees of the same name will be merged into one (with tax options from the first fee).', 'custom-payment-gateways-woocommerce' ),
					'type'        => 'text',
					'default'     => '',
				),
				'fee_type_' . $i       => array(
					'description' => __( 'Type', 'custom-payment-gateways-woocommerce' ),
					'desc_tip'    => __( 'Percent is calculated from cart total.', 'custom-payment-gateways-woocommerce' ),
					'type'        => 'select',
					'class'       => 'chosen_select',
					'default'     => 'fixed',
					'options'     => array(
						'fixed'   => __( 'Fixed', 'custom-payment-gateways-woocommerce' ),
						'percent' => __( 'Percent', 'custom-payment-gateways-woocommerce' ),
					),
				),
				'fee_amount_' . $i     => array(
					'description'       => __( 'Amount', 'custom-payment-gateways-woocommerce' ),
					'desc_tip'          => __( 'Fee amount.', 'custom-payment-gateways-woocommerce' ) . ' ' .
						__( 'This field is required.', 'custom-payment-gateways-woocommerce' ),
					'type'              => 'number',
					'default'           => '',
					'custom_attributes' => array(
						'step' => '0.00001',
						'min'  => 0,
					),
				),
				'fee_amount_min_' . $i => array(
					'description'       => __( 'Min amount', 'custom-payment-gateways-woocommerce' ),
					'desc_tip'          => __( 'Minimum fee amount.', 'custom-payment-gateways-woocommerce' ) . ' ' .
						__( 'Used for "Percent" type fees.', 'custom-payment-gateways-woocommerce' ),
					'type'              => 'number',
					'default'           => '',
					'custom_attributes' => array(
						'step' => '0.00001',
						'min'  => 0,
					),
				),
				'fee_amount_max_' . $i => array(
					'description'       => __( 'Max amount', 'custom-payment-gateways-woocommerce' ),
					'desc_tip'          => __( 'Maximum fee amount.', 'custom-payment-gateways-woocommerce' ) . ' ' .
						__( 'Used for "Percent" type fees.', 'custom-payment-gateways-woocommerce' ),
					'type'              => 'number',
					'default'           => '',
					'custom_attributes' => array(
						'step' => '0.00001',
						'min'  => 0,
					),
				),
				'fee_taxable_' . $i    => array(
					'label'    => __( 'Taxable', 'custom-payment-gateways-woocommerce' ),
					'desc_tip' => __( 'Is the fee taxable?', 'custom-payment-gateways-woocommerce' ),
					'type'     => 'checkbox',
					'default'  => 'no',
				),
				'fee_tax_class_' . $i  => array(
					'description' => __( 'Tax class', 'custom-payment-gateways-woocommerce' ),
					'desc_tip'    => __( 'The tax class for the fee if taxable. A blank string is standard tax class.', 'custom-payment-gateways-woocommerce' ),
					'type'        => 'text',
					'default'     => '',
				),
				'fee_cart_min_' . $i   => array(
					'description'       => __( 'Min cart total', 'custom-payment-gateways-woocommerce' ),
					'desc_tip'          => __( 'Minimum cart total for fee to be applied.', 'custom-payment-gateways-woocommerce' ),
					'type'              => 'number',
					'default'           => '',
					'custom_attributes' => array(
						'step' => '0.00001',
						'min'  => 0,
					),
				),
				'fee_cart_max_' . $i   => array(
					'description'       => __( 'Max cart total', 'custom-payment-gateways-woocommerce' ),
					'desc_tip'          => __( 'Maximum cart total for fee to be applied.', 'custom-payment-gateways-woocommerce' ),
					'type'              => 'number',
					'default'           => '',
					'custom_attributes' => array(
						'step' => '0.00001',
						'min'  => 0,
					),
				),
			)
		);
	}
}
if ( 'yes' === get_option( 'alg_wc_cpg_input_fields_enabled', 'yes' ) ) {
	$fields = array_merge(
		$fields,
		array(
			'input_fields_options' => array(
				'title'       => __( 'Input Fields Options', 'custom-payment-gateways-woocommerce' ),
				'type'        => 'title',
				'description' => __( 'This section allows you to collect data from customers on checkout.', 'custom-payment-gateways-woocommerce' ) . ' ' .
					sprintf(
						// translators: %s points a link to General settings of CPG.
						__( 'General options are in %s.', 'custom-payment-gateways-woocommerce' ),
						'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_custom_payment_gateways&section=input_fields' ) . '" target="_blank">' .
						__( 'WooCommerce > Settings > Custom Payment Gateways > Input Fields', 'custom-payment-gateways-woocommerce' ) . '</a>'
					),
			),
			'input_fields_total'   => array(
				'title'             => __( 'Number of input fields', 'custom-payment-gateways-woocommerce' ),
				'desc_tip'          => __( 'Save changes to see new options.', 'custom-payment-gateways-woocommerce' ),
				'description'       => apply_filters(
					'alg_wc_custom_payment_gateways_settings',
					sprintf(
						'<div style="' . $div_style . '">You will need <a target="_blank" href="%s">Custom Payment Gateways for WooCommerce Pro plugin</a> to add more than one input field.</div>',
						'https://imaginate-solutions.com/downloads/custom-payment-gateways-for-woocommerce/'
					),
					'total_number'
				),
				'default'           => 1,
				'type'              => 'number',
				'custom_attributes' => apply_filters( 'alg_wc_custom_payment_gateways_settings', array( 'readonly' => 'readonly' ), 'array_input_fields' ),
			),
		)
	);
	for ( $i = 1; $i <= apply_filters( 'alg_wc_custom_payment_gateways_values', 1, 'total_input_fields', $this ); $i++ ) { // phpcs:ignore
		$fields = array_merge(
			$fields,
			array(
				'input_fields_title_' . $i       => array(
					// translators: %s Input Field Number.
					'title'       => sprintf( __( 'Input field #%s', 'custom-payment-gateways-woocommerce' ), $i ),
					'description' => __( 'Title', 'custom-payment-gateways-woocommerce' ) . ' (' . __( 'required', 'custom-payment-gateways-woocommerce' ) . ')',
					'desc_tip'    => __( 'The field will not be added to the frontend, if no title is set.', 'custom-payment-gateways-woocommerce' ),
					'default'     => '',
					'type'        => 'text',
					'css'         => 'width:100%;',
				),
				'input_fields_required_' . $i    => array(
					'label'       => __( 'Required', 'custom-payment-gateways-woocommerce' ),
					'description' => __( 'Is field required to fill in on checkout', 'custom-payment-gateways-woocommerce' ),
					'default'     => 'no',
					'type'        => 'checkbox',
				),
				'input_fields_type_' . $i        => array(
					'description' => __( 'Type', 'custom-payment-gateways-woocommerce' ),
					'default'     => 'text',
					'type'        => 'select',
					'class'       => 'chosen_select',
					'options'     => array(
						'text'     => __( 'Text', 'custom-payment-gateways-woocommerce' ),
						'number'   => __( 'Number', 'custom-payment-gateways-woocommerce' ),
						'select'   => __( 'Select (drop-down list)', 'custom-payment-gateways-woocommerce' ),
						'color'    => __( 'Color', 'custom-payment-gateways-woocommerce' ),
						'date'     => __( 'Date', 'custom-payment-gateways-woocommerce' ),
						'email'    => __( 'Email', 'custom-payment-gateways-woocommerce' ),
						'range'    => __( 'Range', 'custom-payment-gateways-woocommerce' ),
						'tel'      => __( 'Tel', 'custom-payment-gateways-woocommerce' ),
						'time'     => __( 'Time', 'custom-payment-gateways-woocommerce' ),
						'url'      => __( 'URL', 'custom-payment-gateways-woocommerce' ),
						'week'     => __( 'Week', 'custom-payment-gateways-woocommerce' ),
						'month'    => __( 'Month', 'custom-payment-gateways-woocommerce' ),
						'password' => __( 'Password', 'custom-payment-gateways-woocommerce' ),
						'checkbox' => __( 'Checkbox', 'custom-payment-gateways-woocommerce' ),
						'textarea' => __( 'Textarea', 'custom-payment-gateways-woocommerce' ),
					),
				),
				'input_fields_placeholder_' . $i => array(
					'description' => __( 'Placeholder', 'custom-payment-gateways-woocommerce' ) . ' (' . __( 'optional', 'custom-payment-gateways-woocommerce' ) . ')',
					'default'     => '',
					'type'        => 'text',
					'css'         => 'width:100%;',
				),
				'input_fields_class_' . $i       => array(
					'description' => __( 'Class', 'custom-payment-gateways-woocommerce' ) . ' (' . __( 'optional', 'custom-payment-gateways-woocommerce' ) . ')',
					'default'     => '',
					'type'        => 'text',
					'css'         => 'width:100%;',
				),
				'input_fields_value_' . $i       => array(
					'description' => __( 'Default value', 'custom-payment-gateways-woocommerce' ) . ' (' . __( 'optional', 'custom-payment-gateways-woocommerce' ) . ')',
					'default'     => '',
					'type'        => 'text',
					'css'         => 'width:100%;',
				),
				'input_fields_options_' . $i     => array(
					'description' => __( 'Options', 'custom-payment-gateways-woocommerce' ) . ' (' . __( 'for "Select" type; one option per line', 'custom-payment-gateways-woocommerce' ) . ')',
					'default'     => '',
					'type'        => 'textarea',
					'css'         => 'width:100%;',
				),
			)
		);
	}
}
return $fields;
