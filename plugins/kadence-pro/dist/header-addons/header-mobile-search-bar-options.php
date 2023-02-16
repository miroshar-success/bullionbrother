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
	'header_mobile_search_bar_tabs' => array(
		'control_type' => 'kadence_tab_control',
		'section'      => 'header_mobile_search_bar',
		'settings'     => false,
		'priority'     => 1,
		'input_attrs'  => array(
			'general' => array(
				'label'  => __( 'General', 'kadence-pro' ),
				'target' => 'header_mobile_search_bar',
			),
			'design' => array(
				'label'  => __( 'Design', 'kadence-pro' ),
				'target' => 'header_mobile_search_bar_design',
			),
			'active' => 'general',
		),
	),
	'header_mobile_search_bar_tabs_design' => array(
		'control_type' => 'kadence_tab_control',
		'section'      => 'header_mobile_search_bar_design',
		'settings'     => false,
		'priority'     => 1,
		'input_attrs'  => array(
			'general' => array(
				'label'  => __( 'General', 'kadence-pro' ),
				'target' => 'header_mobile_search_bar',
			),
			'design' => array(
				'label'  => __( 'Design', 'kadence-pro' ),
				'target' => 'header_mobile_search_bar_design',
			),
			'active' => 'design',
		),
	),
	'header_mobile_search_bar_width' => array(
		'control_type' => 'kadence_range_control',
		'section'      => 'header_mobile_search_bar',
		'label'        => esc_html__( 'Search Bar Width', 'kadence-pro' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.header-mobile-search-bar form',
				'property' => 'width',
				'pattern'  => '$',
				'key'      => 'size',
			),
		),
		'default'      => kadence()->default( 'header_mobile_search_bar_width' ),
		'input_attrs'  => array(
			'min'        => array(
				'px'  => 100,
				'em'  => 4,
				'rem' => 4,
			),
			'max'        => array(
				'px'  => 600,
				'em'  => 12,
				'rem' => 12,
			),
			'step'       => array(
				'px'  => 1,
				'em'  => 0.01,
				'rem' => 0.01,
			),
			'units'      => array( 'px', 'em', 'rem' ),
			'responsive' => false,
		),
	),
	'header_mobile_search_bar_color' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'header_mobile_search_bar_design',
		'label'        => esc_html__( 'Input Text Colors', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_mobile_search_bar_color' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.header-mobile-search-bar form input.search-field, .header-mobile-search-bar form .kadence-search-icon-wrap',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '.header-mobile-search-bar form input.search-field:focus, .header-mobile-search-bar form input.search-submit:hover ~ .kadence-search-icon-wrap, .header-mobile-search-bar #main-header form button[type="submit"]:hover ~ .kadence-search-icon-wrap',
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
					'tooltip' => __( 'Focus Color', 'kadence-pro' ),
					'palette' => true,
				),
			),
		),
	),
	'header_mobile_search_bar_background' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'header_mobile_search_bar_design',
		'label'        => esc_html__( 'Input Background', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_mobile_search_bar_background' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.header-mobile-search-bar form input.search-field',
				'property' => 'background',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '.header-mobile-search-bar form input.search-field:focus',
				'property' => 'background',
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
					'tooltip' => __( 'Focus Color', 'kadence-pro' ),
					'palette' => true,
				),
			),
		),
	),
	'header_mobile_search_bar_border' => array(
		'control_type' => 'kadence_border_control',
		'section'      => 'header_mobile_search_bar_design',
		'label'        => esc_html__( 'Border', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_mobile_search_bar_border' ),
		'live_method'     => array(
			array(
				'type'     => 'css_border',
				'selector' => '.header-mobile-search-bar form input.search-field',
				'pattern'  => '$',
				'property' => 'border',
				'pattern'  => '$',
				'key'      => 'border',
			),
		),
		'input_attrs'  => array(
			'responsive' => false,
			'color'      => false,
		),
	),
	'header_mobile_search_bar_border_color' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'header_mobile_search_bar_design',
		'label'        => esc_html__( 'Input Border Color', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_mobile_search_bar_border_color' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.header-mobile-search-bar form input.search-field',
				'property' => 'border-color',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '.header-mobile-search-bar form input.search-field:focus',
				'property' => 'border-color',
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
					'tooltip' => __( 'Focus Color', 'kadence-pro' ),
					'palette' => true,
				),
			),
		),
	),
	'header_mobile_search_bar_typography' => array(
		'control_type' => 'kadence_typography_control',
		'section'      => 'header_mobile_search_bar_design',
		'label'        => esc_html__( 'Font', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_mobile_search_bar_typography' ),
		'live_method'     => array(
			array(
				'type'     => 'css_typography',
				'selector' => '.header-mobile-search-bar form input.search-field',
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
			'id' => 'header_mobile_search_bar_typography',
			'options' => 'no-color',
		),
	),
	'header_mobile_search_bar_margin' => array(
		'control_type' => 'kadence_measure_control',
		'section'      => 'header_mobile_search_bar_design',
		'default'      => kadence()->default( 'header_mobile_search_bar_margin' ),
		'label'        => esc_html__( 'Margin', 'kadence-pro' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.header-mobile-search-bar',
				'property' => 'margin',
				'pattern'  => '$',
				'key'      => 'measure',
			),
		),
		'input_attrs'  => array(
			'responsive' => false,
		),
	),
	'transparent_header_mobile_search_bar_color' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'transparent_header_design',
		'label'        => esc_html__( 'Search Bar Input Colors', 'kadence-pro' ),
		'context'      => array(
			array(
				'setting'  => '__device',
				'operator' => 'in',
				'value'    => array( 'tablet', 'mobile' ),
			),
		),
		'default'      => kadence()->default( 'transparent_header_mobile_search_bar_color' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.transparent-header .header-mobile-search-bar form input.search-field, .transparent-header .header-mobile-search-bar form .kadence-search-icon-wrap',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '.transparent-header .header-mobile-search-bar form input.search-field:focus, .transparent-header .header-mobile-search-bar form input.search-submit:hover ~ .kadence-search-icon-wrap, .transparent-header #main-header .header-mobile-search-bar form button[type="submit"]:hover ~ .kadence-search-icon-wrap',
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
					'tooltip' => __( 'Focus Color', 'kadence-pro' ),
					'palette' => true,
				),
			),
		),
	),
	'transparent_header_mobile_search_bar_background' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'transparent_header_design',
		'label'        => esc_html__( 'Search Bar Input Background', 'kadence-pro' ),
		'default'      => kadence()->default( 'transparent_header_mobile_search_bar_background' ),
		'context'      => array(
			array(
				'setting'  => '__device',
				'operator' => 'in',
				'value'    => array( 'tablet', 'mobile' ),
			),
		),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.transparent-header .header-mobile-search-bar form input.search-field',
				'property' => 'background',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '.transparent-header .header-mobile-search-bar form input.search-field:focus',
				'property' => 'background',
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
					'tooltip' => __( 'Focus Color', 'kadence-pro' ),
					'palette' => true,
				),
			),
		),
	),
	'transparent_header_mobile_search_bar_border' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'transparent_header_design',
		'label'        => esc_html__( 'Search Bar Border Color', 'kadence-pro' ),
		'default'      => kadence()->default( 'transparent_header_mobile_search_bar_border' ),
		'context'      => array(
			array(
				'setting'  => '__device',
				'operator' => 'in',
				'value'    => array( 'tablet', 'mobile' ),
			),
		),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.transparent-header .header-mobile-search-bar form input.search-field',
				'property' => 'border-color',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '.transparent-header .header-mobile-search-bar form input.search-field:focus',
				'property' => 'border-color',
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
					'tooltip' => __( 'Focus Color', 'kadence-pro' ),
					'palette' => true,
				),
			),
		),
	),
);
if ( class_exists( 'woocommerce' ) ) {
	$settings = array_merge(
		$settings,
		array(
			'header_mobile_search_bar_woo' => array(
				'control_type' => 'kadence_switch_control',
				'section'      => 'header_mobile_search_bar',
				'priority'     => 10,
				'default'      => kadence()->default( 'header_mobile_search_bar_woo' ),
				'label'        => esc_html__( 'Search only Products?', 'kadence-pro' ),
				'transport'    => 'refresh',
			),
		)
	);
}

Theme_Customizer::add_settings( $settings );

