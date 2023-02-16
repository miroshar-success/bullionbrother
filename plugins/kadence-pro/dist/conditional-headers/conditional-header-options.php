<?php
/**
 * Header Account Options.
 *
 * @package Kadence_Pro
 */

namespace Kadence_Pro;

use Kadence\Theme_Customizer;
use function Kadence\kadence;
$preview_options = array(
	'' => array(
		'name' => __( 'Default', 'kadence-pro' ),
	),
);
$headers = kadence()->option( 'conditional_headers' );
if ( ! empty( $headers['items'] ) && is_array( $headers['items'] ) ) {
	foreach ( $headers['items'] as $header ) {
		$preview_options[ $header['id'] ] = array(
			'name' => $header['label'],
		);
	}
}
$settings = array(
	'header_conditional_heading' => array(
		'control_type' => 'kadence_conditional_heading_control',
		'section'      => 'header_layout',
		'settings'     => false,
		'priority'     => 5,
		'label'        => esc_html__( 'Previewing Header:', 'kadence-pro' ),
	),
	'header_conditional_link' => array(
		'control_type' => 'kadence_focus_button_control',
		'section'      => 'header_layout',
		'settings'     => false,
		'priority'     => 21,
		'label'        => esc_html__( 'Conditional Header', 'kadence-pro' ),
		'input_attrs'  => array(
			'section' => 'kadence_customizer_conditional_header',
		),
	),
	'current_header_preview' => array(
		'control_type' => 'kadence_conditional_select_control',
		'section'      => 'conditional_header',
		'transport'    => 'refresh',
		'default'      => kadence()->default( 'current_header_preview' ),
		'label'        => esc_html__( 'Current Previewing Header', 'kadence-pro' ),
		'input_attrs'  => array(
			'options' => $preview_options,
		),
	),
	'conditional_headers' => array(
		'control_type' => 'kadence_conditional_control',
		'section'      => 'conditional_header',
		'default'      => kadence()->default( 'conditional_headers' ),
		'label'        => esc_html__( 'Conditional Headers', 'kadence-pro' ),
	),
);

Theme_Customizer::add_settings( $settings );
