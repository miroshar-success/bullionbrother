<?php
/**
 * Header Builder Options
 *
 * @package Kadence
 */

namespace Kadence_Pro;

use Kadence\Theme_Customizer;
use function Kadence\kadence;

$settings = array(
	'header_contact_tabs' => array(
		'control_type' => 'kadence_tab_control',
		'section'      => 'header_contact',
		'settings'     => false,
		'priority'     => 1,
		'input_attrs'  => array(
			'general' => array(
				'label'  => __( 'General', 'kadence-pro' ),
				'target' => 'header_contact',
			),
			'design' => array(
				'label'  => __( 'Design', 'kadence-pro' ),
				'target' => 'header_contact_design',
			),
			'active' => 'general',
		),
	),
	'header_contact_tabs_design' => array(
		'control_type' => 'kadence_tab_control',
		'section'      => 'header_contact_design',
		'settings'     => false,
		'priority'     => 1,
		'input_attrs'  => array(
			'general' => array(
				'label'  => __( 'General', 'kadence-pro' ),
				'target' => 'header_contact',
			),
			'design' => array(
				'label'  => __( 'Design', 'kadence-pro' ),
				'target' => 'header_contact_design',
			),
			'active' => 'design',
		),
	),
	'header_contact_items' => array(
		'control_type' => 'kadence_contact_control',
		'section'      => 'header_contact',
		'priority'     => 6,
		'default'      => kadence()->default( 'header_contact_items' ),
		'label'        => esc_html__( 'Contact Items', 'kadence-pro' ),
		'partial'      => array(
			'selector'            => '.header-contact-wrap',
			'container_inclusive' => true,
			'render_callback'     => 'Kadence_Pro\header_contact',
		),
	),
	'header_contact_item_spacing' => array(
		'control_type' => 'kadence_range_control',
		'section'      => 'header_contact_design',
		'label'        => esc_html__( 'Item Spacing', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_contact_item_spacing' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.header-contact-wrap .element-contact-inner-wrap .header-contact-item',
				'property' => 'margin-top',
				'pattern'  => '$',
				'key'      => 'size',
			),
			array(
				'type'     => 'css',
				'selector' => '.header-contact-wrap .element-contact-inner-wrap .header-contact-item',
				'property' => 'margin-left',
				'pattern'  => 'calc($ / 2)',
				'key'      => 'size',
			),
			array(
				'type'     => 'css',
				'selector' => '.header-contact-wrap .element-contact-inner-wrap .header-contact-item',
				'property' => 'margin-right',
				'pattern'  => 'calc($ / 2)',
				'key'      => 'size',
			),
			array(
				'type'     => 'css',
				'selector' => '.header-contact-wrap .element-contact-inner-wrap',
				'property' => 'margin-top',
				'pattern'  => '-$',
				'key'      => 'size',
			),
			array(
				'type'     => 'css',
				'selector' => '.header-contact-wrap .element-contact-inner-wrap',
				'property' => 'margin-left',
				'pattern'  => 'calc(-$ / 2)',
				'key'      => 'size',
			),
			array(
				'type'     => 'css',
				'selector' => '.header-contact-wrap .element-contact-inner-wrap',
				'property' => 'margin-right',
				'pattern'  => 'calc(-$ / 2)',
				'key'      => 'size',
			),
		),
		'input_attrs'  => array(
			'min'        => array(
				'px'  => 0,
				'em'  => 0,
				'rem' => 0,
			),
			'max'        => array(
				'px'  => 50,
				'em'  => 3,
				'rem' => 3,
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
	'header_contact_icon_size' => array(
		'control_type' => 'kadence_range_control',
		'section'      => 'header_contact_design',
		'label'        => esc_html__( 'Icon Size', 'kadence-pro' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '#main-header .element-contact-inner-wrap .header-contact-item .kadence-svg-iconset',
				'property' => 'font-size',
				'pattern'  => '$',
				'key'      => 'size',
			),
		),
		'default'      => kadence()->default( 'header_contact_icon_size' ),
		'input_attrs'  => array(
			'min'        => array(
				'px'  => 0,
				'em'  => 0,
				'rem' => 0,
			),
			'max'        => array(
				'px'  => 100,
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
	'header_contact_color' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'header_contact_design',
		'label'        => esc_html__( 'Colors', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_contact_color' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '#main-header .header-contact-wrap .header-contact-item',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '#main-header .header-contact-wrap a.header-contact-item:hover',
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
	'header_contact_link_style' => array(
		'control_type' => 'kadence_select_control',
		'section'      => 'header_contact_design',
		'default'      => kadence()->default( 'header_contact_link_style' ),
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
				'selector' => '#main-header .header-contact-wrap',
				'pattern'  => 'inner-link-style-$',
				'key'      => '',
			),
		),
	),
	'header_contact_typography' => array(
		'control_type' => 'kadence_typography_control',
		'section'      => 'header_contact_design',
		'label'        => esc_html__( 'Font', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_contact_typography' ),
		'live_method'     => array(
			array(
				'type'     => 'css_typography',
				'selector' => '#main-header .header-contact-wrap .header-contact-item',
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
			'id' => 'header_contact_typography',
			'options' => 'no-color',
		),
	),
	'header_contact_margin' => array(
		'control_type' => 'kadence_measure_control',
		'section'      => 'header_contact_design',
		'priority'     => 10,
		'default'      => kadence()->default( 'header_contact_margin' ),
		'label'        => esc_html__( 'Margin', 'kadence-pro' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '#main-header .header-contact-wrap',
				'property' => 'margin',
				'pattern'  => '$',
				'key'      => 'measure',
			),
		),
		'input_attrs'  => array(
			'responsive' => false,
		),
	),
	'sticky_header_contact_color' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'header_sticky_design',
		'label'        => esc_html__( 'Contact Colors', 'kadence-pro' ),
		'default'      => kadence()->default( 'sticky_header_contact_color' ),
		'priority'     => 11,
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .header-contact-wrap .header-contact-item, #masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .header-mobile-contact-wrap .element-contact-inner-wrap .header-contact-item',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .header-contact-wrap a.header-contact-item:hover, #masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .header-mobile-contact-wrap .element-contact-inner-wrap .header-contact-item:hover',
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
				'hover' => array(
					'tooltip' => __( 'Hover', 'kadence-pro' ),
					'palette' => true,
				),
			),
		),
	),
	'transparent_header_contact_color' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'transparent_header_design',
		'label'        => esc_html__( 'Contact Colors', 'kadence-pro' ),
		'default'      => kadence()->default( 'transparent_header_contact_color' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.transparent-header #main-header .header-contact-wrap .header-contact-item',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '.transparent-header #main-header .header-contact-wrap a.header-contact-item:hover',
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
				'hover' => array(
					'tooltip' => __( 'Hover', 'kadence-pro' ),
					'palette' => true,
				),
			),
		),
	),
);

Theme_Customizer::add_settings( $settings );
