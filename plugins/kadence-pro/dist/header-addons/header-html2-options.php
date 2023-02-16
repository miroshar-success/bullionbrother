<?php
/**
 * Header HTML2 Options
 *
 * @package Kadence_Pro
 */

namespace Kadence_Pro;

use Kadence\Theme_Customizer;
use function Kadence\kadence;

$settings = array(
	'header_html2_tabs' => array(
		'control_type' => 'kadence_tab_control',
		'section'      => 'header_html2',
		'settings'     => false,
		'priority'     => 1,
		'input_attrs'  => array(
			'general' => array(
				'label'  => __( 'General', 'kadence-pro' ),
				'target' => 'header_html2',
			),
			'design' => array(
				'label'  => __( 'Design', 'kadence-pro' ),
				'target' => 'header_html2_design',
			),
			'active' => 'general',
		),
	),
	'header_html2_tabs_design' => array(
		'control_type' => 'kadence_tab_control',
		'section'      => 'header_html2_design',
		'settings'     => false,
		'priority'     => 1,
		'input_attrs'  => array(
			'general' => array(
				'label'  => __( 'General', 'kadence-pro' ),
				'target' => 'header_html2',
			),
			'design' => array(
				'label'  => __( 'Design', 'kadence-pro' ),
				'target' => 'header_html2_design',
			),
			'active' => 'design',
		),
	),
	'header_html2_content' => array(
		'control_type' => 'kadence_editor_control',
		'sanitize'     => 'wp_kses_post',
		'section'      => 'header_html2',
		'priority'     => 4,
		'default'      => kadence()->default( 'header_html2_content' ),
		'partial'      => array(
			'selector'            => '.header-html2',
			'container_inclusive' => true,
			'render_callback'     => 'Kadence_Pro\header_html2',
		),
		'input_attrs'  => array(
			'id' => 'header_html2',
		),
	),
	'header_html2_wpautop' => array(
		'control_type' => 'kadence_switch_control',
		'section'      => 'header_html2',
		'default'      => kadence()->default( 'header_html2_wpautop' ),
		'label'        => esc_html__( 'Automatically Add Paragraphs', 'kadence-pro' ),
		'partial'      => array(
			'selector'            => '.header-html2',
			'container_inclusive' => true,
			'render_callback'     => 'Kadence\header_html2',
		),
	),
	'header_html2_typography' => array(
		'control_type' => 'kadence_typography_control',
		'section'      => 'header_html2_design',
		'label'        => esc_html__( 'Font', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_html2_typography' ),
		'live_method'     => array(
			array(
				'type'     => 'css_typography',
				'selector' => '#main-header .header-html2',
				'pattern'  => array(
					'desktop' => '$',
					'tablet'  => '$',
					'mobile'  => '$',
				),
				'property' => 'font',
				'key'      => 'typography',
			),
		),
		'input_attrs'  => array(
			'id' => 'header_html2_typography',
		),
	),
	'header_html2_link_style' => array(
		'control_type' => 'kadence_select_control',
		'section'      => 'header_html2_design',
		'default'      => kadence()->default( 'header_html2_link_style' ),
		'label'        => esc_html__( 'Link Style', 'kadence-pro' ),
		'input_attrs'  => array(
			'options' => array(
				'normal' => array(
					'name' => __( 'Underline', 'kadence-pro' ),
				),
				'plain' => array(
					'name' => __( 'No Underline', 'kadence-pro' ),
				),
			),
		),
		'live_method'     => array(
			array(
				'type'     => 'class',
				'selector' => '#main-header .header-html2',
				'pattern'  => 'inner-link-style-$',
				'key'      => '',
			),
		),
	),
	'header_html2_link_color' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'header_html2_design',
		'label'        => esc_html__( 'Link Colors', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_html2_link_color' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '#main-header .header-html2 a',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '#main-header .header-html2 a:hover',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'hover',
			),
		),
		'input_attrs'  => array(
			'colors' => array(
				'color' => array(
					'tooltip' => __( 'Initial Color', 'kadence-pro' ),
					'palette' => true,
				),
				'hover' => array(
					'tooltip' => __( 'Hover Color', 'kadence-pro' ),
					'palette' => true,
				),
			),
		),
	),
	'header_html2_margin' => array(
		'control_type' => 'kadence_measure_control',
		'section'      => 'header_html2_design',
		'priority'     => 10,
		'default'      => kadence()->default( 'header_html2_margin' ),
		'label'        => esc_html__( 'Margin', 'kadence-pro' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '#main-header .header-html2',
				'property' => 'margin',
				'pattern'  => '$',
				'key'      => 'measure',
			),
		),
		'input_attrs'  => array(
			'responsive' => false,
		),
	),
	'transparent_header_html2_color' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'transparent_header_design',
		'label'        => esc_html__( 'HTML2 Colors', 'kadence-pro' ),
		'default'      => kadence()->default( 'transparent_header_html2_color' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.transparent-header #main-header .header-html2,.mobile-transparent-header .mobile-html2',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '.transparent-header #main-header .header-html2 a, .mobile-transparent-header .mobile-html2 a',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'link',
			),
			array(
				'type'     => 'css',
				'selector' => '.transparent-header #main-header .header-html2 a:hover, .mobile-transparent-header .mobile-html2 a:hover',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'hover',
			),
		),
		'input_attrs'  => array(
			'colors' => array(
				'color' => array(
					'tooltip' => __( 'Color', 'kadence-pro' ),
					'palette' => true,
				),
				'link' => array(
					'tooltip' => __( 'Link Color', 'kadence-pro' ),
					'palette' => true,
				),
				'hover' => array(
					'tooltip' => __( 'Link Hover', 'kadence-pro' ),
					'palette' => true,
				),
			),
		),
	),
);

Theme_Customizer::add_settings( $settings );

