<?php
/**
 * Header Builder Options
 *
 * @package Kadence
 */

namespace Kadence;

use Kadence\Theme_Customizer;
use function Kadence\kadence;

$settings = array(
	'mobile_secondary_navigation_tabs' => array(
		'control_type' => 'kadence_tab_control',
		'section'      => 'mobile_secondary_navigation',
		'settings'     => false,
		'priority'     => 1,
		'input_attrs'  => array(
			'general' => array(
				'label'  => __( 'General', 'kadence-pro' ),
				'target' => 'mobile_secondary_navigation',
			),
			'design' => array(
				'label'  => __( 'Design', 'kadence-pro' ),
				'target' => 'mobile_secondary_navigation_design',
			),
			'active' => 'general',
		),
	),
	'mobile_secondary_navigation_tabs_design' => array(
		'control_type' => 'kadence_tab_control',
		'section'      => 'mobile_secondary_navigation_design',
		'settings'     => false,
		'priority'     => 1,
		'input_attrs'  => array(
			'general' => array(
				'label'  => __( 'General', 'kadence-pro' ),
				'target' => 'mobile_secondary_navigation',
			),
			'design' => array(
				'label'  => __( 'Design', 'kadence-pro' ),
				'target' => 'mobile_secondary_navigation_design',
			),
			'active' => 'design',
		),
	),
	'mobile_secondary_navigation_link' => array(
		'control_type' => 'kadence_focus_button_control',
		'section'      => 'mobile_secondary_navigation',
		'settings'     => false,
		'priority'     => 5,
		'label'        => esc_html__( 'Select Menu', 'kadence-pro' ),
		'input_attrs'  => array(
			'section' => 'menu_locations',
		),
	),
	'mobile_secondary_navigation_color' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'mobile_secondary_navigation_design',
		'label'        => esc_html__( 'Colors', 'kadence-pro' ),
		'default'      => kadence()->default( 'mobile_secondary_navigation_color' ),
		'live_method'  => array(
			array(
				'type'     => 'css',
				'selector' => '#mobile-secondary-site-navigation ul li:not(.menu-item-has-children) > a, .mobile-secondary-navigation ul li.menu-item-has-children > .drawer-nav-drop-wrap',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '#mobile-secondary-site-navigation ul li:not(.menu-item-has-children) > a:hover, .mobile-secondary-navigation ul li.menu-item-has-children > .drawer-nav-drop-wrap:hover',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'hover',
			),
			array(
				'type'     => 'css',
				'selector' => '#mobile-secondary-site-navigation ul li.current-menu-item:not(.menu-item-has-children) > a, .mobile-secondary-navigation ul li.current-menu-item.menu-item-has-children > .drawer-nav-drop-wrap',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'active',
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
				'active' => array(
					'tooltip' => __( 'Active Color', 'kadence-pro' ),
					'palette' => true,
				),
			),
		),
	),
	'mobile_secondary_navigation_background' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'mobile_secondary_navigation_design',
		'priority'     => 20,
		'label'        => esc_html__( 'Background', 'kadence-pro' ),
		'default'      => kadence()->default( 'mobile_secondary_navigation_background' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '#mobile-secondary-site-navigation ul li:not(.menu-item-has-children) > a, .mobile-secondary-navigation ul li.menu-item-has-children > .drawer-nav-drop-wrap',
				'property' => 'background',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '#mobile-secondary-site-navigation ul li:not(.menu-item-has-children) > a:hover, .mobile-secondary-navigation ul li.menu-item-has-children > .drawer-nav-drop-wrap:hover',
				'property' => 'background',
				'pattern'  => '$',
				'key'      => 'hover',
			),
			array(
				'type'     => 'css',
				'selector' => '#mobile-secondary-site-navigation ul li.current-menu-item:not(.menu-item-has-children) > a, .mobile-secondary-navigation ul li.current-menu-item.menu-item-has-children > .drawer-nav-drop-wrap',
				'property' => 'background',
				'pattern'  => '$',
				'key'      => 'active',
			),
		),
		'input_attrs'  => array(
			'colors' => array(
				'color' => array(
					'tooltip' => __( 'Initial Background', 'kadence-pro' ),
					'palette' => true,
				),
				'hover' => array(
					'tooltip' => __( 'Hover Background', 'kadence-pro' ),
					'palette' => true,
				),
				'active' => array(
					'tooltip' => __( 'Active Background', 'kadence-pro' ),
					'palette' => true,
				),
			),
		),
	),
	'mobile_secondary_navigation_divider' => array(
		'control_type' => 'kadence_border_control',
		'section'      => 'mobile_secondary_navigation_design',
		'priority'     => 20,
		'label'        => esc_html__( 'Item Divider', 'kadence-pro' ),
		'default'      => kadence()->default( 'mobile_secondary_navigation_divider' ),
		'live_method'     => array(
			array(
				'type'     => 'css_border',
				'selector' => '#mobile-secondary-site-navigation ul li.menu-item-has-children .drawer-nav-drop-wrap, .mobile-secondary-navigation ul li:not(.menu-item-has-children) a ',
				'pattern'  => '$',
				'property' => 'border-bottom',
				'pattern'  => '$',
				'key'      => 'border',
			),
			array(
				'type'     => 'css_border',
				'selector' => '#mobile-secondary-site-navigation ul li.menu-item-has-children .drawer-nav-drop-wrap button',
				'pattern'  => '$',
				'property' => 'border-left',
				'pattern'  => '$',
				'key'      => 'border',
			),
		),
		'input_attrs'  => array(
			'responsive' => false,
		),
	),
	'mobile_secondary_navigation_typography' => array(
		'control_type' => 'kadence_typography_control',
		'section'      => 'mobile_secondary_navigation_design',
		'priority'     => 20,
		'label'        => esc_html__( 'Font', 'kadence-pro' ),
		'default'      => kadence()->default( 'mobile_secondary_navigation_typography' ),
		'live_method'     => array(
			array(
				'type'     => 'css_typography',
				'selector' => '#mobile-secondary-site-navigation ul li',
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
			'id'      => 'mobile_secondary_navigation_typography',
			'options' => 'no-color',
		),
	),
	'mobile_secondary_navigation_vertical_spacing' => array(
		'control_type' => 'kadence_range_control',
		'section'      => 'mobile_secondary_navigation',
		'priority'     => 20,
		'label'        => esc_html__( 'Items Vertical Spacing', 'kadence-pro' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '#mobile-secondary-site-navigation ul li a',
				'property' => 'padding-top',
				'pattern'  => '$',
				'key'      => 'size',
			),
			array(
				'type'     => 'css',
				'selector' => '#mobile-secondary-site-navigation ul li a',
				'property' => 'padding-bottom',
				'pattern'  => '$',
				'key'      => 'size',
			),
		),
		'default'      => kadence()->default( 'mobile_secondary_navigation_vertical_spacing' ),
		'input_attrs'  => array(
			'min'        => array(
				'px'  => 0,
				'em'  => 0,
				'rem' => 0,
				'vh'  => 0,
			),
			'max'        => array(
				'px'  => 100,
				'em'  => 12,
				'rem' => 12,
				'vh'  => 12,
			),
			'step'       => array(
				'px'  => 1,
				'em'  => 0.01,
				'rem' => 0.01,
				'vh'  => 0.01,
			),
			'units'      => array( 'px', 'em', 'rem', 'vh' ),
			'responsive' => false,
		),
	),
	'mobile_secondary_navigation_collapse' => array(
		'control_type' => 'kadence_switch_control',
		'sanitize'     => 'kadence_sanitize_toggle',
		'section'      => 'mobile_secondary_navigation',
		'priority'     => 20,
		'default'      => kadence()->default( 'mobile_secondary_navigation_collapse' ),
		'label'        => esc_html__( 'Collapse sub menu items?', 'kadence-pro' ),
		'partial'      => array(
			'selector'            => '#mobile-secondary-site-navigation',
			'container_inclusive' => true,
			'render_callback'     => 'Kadence\mobile_secondary_navigation',
		),
	),
	'mobile_secondary_navigation_parent_toggle' => array(
		'control_type' => 'kadence_switch_control',
		'sanitize'     => 'kadence_sanitize_toggle',
		'section'      => 'mobile_secondary_navigation',
		'priority'     => 20,
		'default'      => kadence()->default( 'mobile_secondary_navigation_parent_toggle' ),
		'label'        => esc_html__( 'Entire parent menu item expands sub menu', 'kadence-pro' ),
		'context'      => array(
			array(
				'setting' => 'mobile_secondary_navigation_collapse',
				'value'   => true,
			),
		),
		'live_method'     => array(
			array(
				'type'     => 'class',
				'selector' => '#mobile-secondary-site-navigation',
				'pattern'  => 'drawer-navigation-parent-toggle-$',
				'key'      => '',
			),
		),
	),
);
Theme_Customizer::add_settings( $settings );
