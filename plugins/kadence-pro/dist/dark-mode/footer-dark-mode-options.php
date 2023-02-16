<?php
/**
 * Dark Mode Options.
 *
 * @package Kadence_Pro
 */

namespace Kadence_Pro;

use Kadence\Theme_Customizer;
use function Kadence\kadence;
Theme_Customizer::add_settings(
	array(
		'footer_dark_mode_align' => array(
			'control_type' => 'kadence_radio_icon_control',
			'section'      => 'footer_dark_mode',
			'label'        => esc_html__( 'Content Align', 'kadence-pro' ),
			'priority'     => 10,
			'default'      => kadence()->default( 'footer_dark_mode_align' ),
			'live_method'     => array(
				array(
					'type'     => 'class',
					'selector' => '.footer-dark-mode',
					'pattern'  => array(
						'desktop' => 'content-align-$',
						'tablet'  => 'content-tablet-align-$',
						'mobile'  => 'content-mobile-align-$',
					),
					'key'      => '',
				),
			),
			'input_attrs'  => array(
				'layout' => array(
					'left'   => array(
						'tooltip'  => __( 'Left Align', 'kadence-pro' ),
						'dashicon' => 'editor-alignleft',
					),
					'center' => array(
						'tooltip'  => __( 'Center Align', 'kadence-pro' ),
						'dashicon' => 'editor-aligncenter',
					),
					'right'  => array(
						'tooltip'  => __( 'Right Align', 'kadence-pro' ),
						'dashicon' => 'editor-alignright',
					),
				),
				'responsive' => true,
			),
		),
		'footer_dark_mode_vertical_align' => array(
			'control_type' => 'kadence_radio_icon_control',
			'section'      => 'footer_dark_mode',
			'label'        => esc_html__( 'Content Vertical Align', 'kadence-pro' ),
			'priority'     => 10,
			'default'      => kadence()->default( 'footer_dark_mode_vertical_align' ),
			'live_method'  => array(
				array(
					'type'     => 'class',
					'selector' => '.footer-dark-mode',
					'pattern'  => array(
						'desktop' => 'content-valign-$',
						'tablet'  => 'content-tablet-valign-$',
						'mobile'  => 'content-mobile-valign-$',
					),
					'key'      => '',
				),
			),
			'input_attrs'  => array(
				'layout' => array(
					'top' => array(
						'tooltip' => __( 'Top Align', 'kadence-pro' ),
						'icon'    => 'aligntop',
					),
					'middle' => array(
						'tooltip' => __( 'Middle Align', 'kadence-pro' ),
						'icon'    => 'alignmiddle',
					),
					'bottom' => array(
						'tooltip' => __( 'Bottom Align', 'kadence-pro' ),
						'icon'    => 'alignbottom',
					),
				),
				'responsive' => true,
			),
		),
		'info_footer_dark_mode_switch' => array(
			'control_type' => 'kadence_title_control',
			'section'      => 'footer_dark_mode',
			'priority'     => 10,
			'label'        => esc_html__( '*You must Enable Dark Mode', 'kadence-pro' ),
			'description'  => esc_html__( 'To view the color switcher you must enable dark mode under General then Color Switch (Dark Mode)', 'kadence-pro' ),
			'settings'     => false,
			'context'      => array(
				array(
					'setting'  => 'dark_mode_enable',
					'operator' => '!=',
					'value'    => true,
				),
			),
		),
		'footer_dark_mode_switch_type' => array(
			'control_type' => 'kadence_select_control',
			'section'      => 'footer_dark_mode',
			'priority'     => 11,
			'default'      => kadence()->default( 'footer_dark_mode_switch_type' ),
			'label'        => esc_html__( 'Switch Type', 'kadence-pro' ),
			'input_attrs'  => array(
				'options' => array(
					'icon' => array(
						'name' => __( 'Icons', 'kadence-pro' ),
					),
					'text' => array(
						'name' => __( 'Text', 'kadence-pro' ),
					),
					'both' => array(
						'name' => __( 'Icons and Text', 'kadence-pro' ),
					),
				),
			),
			'partial'      => array(
				'selector'            => '.kadence-color-palette-footer-switcher',
				'container_inclusive' => true,
				'render_callback'     => 'Kadence_Pro\footer_color_switcher',
			),
		),
		'footer_dark_mode_switch_style' => array(
			'control_type' => 'kadence_select_control',
			'section'      => 'footer_dark_mode',
			'priority'     => 11,
			'default'      => kadence()->default( 'footer_dark_mode_switch_style' ),
			'label'        => esc_html__( 'Switch Style', 'kadence-pro' ),
			'input_attrs'  => array(
				'options' => array(
					'button' => array(
						'name' => __( 'Button', 'kadence-pro' ),
					),
					'switch' => array(
						'name' => __( 'Switch', 'kadence-pro' ),
					),
				),
			),
			'live_method'     => array(
				array(
					'type'     => 'class',
					'selector' => '.kadence-color-palette-footer-switcher .kadence-color-palette-switcher',
					'pattern'  => 'kcps-style-$',
					'key'      => '',
				),
			),
		),
		'footer_dark_mode_light_icon' => array(
			'control_type' => 'kadence_radio_icon_control',
			'section'      => 'footer_dark_mode',
			'priority'     => 12,
			'default'      => kadence()->default( 'footer_dark_mode_light_icon' ),
			'label'        => esc_html__( 'Light Mode Icon', 'kadence-pro' ),
			'input_attrs'  => array(
				'layout' => array(
					'sun' => array(
						'icon' => 'sun',
					),
					'sunAlt' => array(
						'icon' => 'sunAlt',
					),
					'sunrise' => array(
						'icon' => 'sunrise',
					),
				),
				'responsive' => false,
			),
			'partial'      => array(
				'selector'            => '.kadence-color-palette-footer-switcher',
				'container_inclusive' => true,
				'render_callback'     => 'Kadence_Pro\footer_color_switcher',
			),
		),
		'footer_dark_mode_dark_icon' => array(
			'control_type' => 'kadence_radio_icon_control',
			'section'      => 'footer_dark_mode',
			'priority'     => 12,
			'default'      => kadence()->default( 'footer_dark_mode_dark_icon' ),
			'label'        => esc_html__( 'Dark Mode Icon', 'kadence-pro' ),
			'input_attrs'  => array(
				'layout' => array(
					'moon' => array(
						'icon' => 'moon',
					),
					'moonAlt' => array(
						'icon' => 'moonAlt',
					),
					'sunset' => array(
						'icon' => 'sunset',
					),
				),
				'responsive' => false,
			),
			'partial'      => array(
				'selector'            => '.kadence-color-palette-footer-switcher',
				'container_inclusive' => true,
				'render_callback'     => 'Kadence_Pro\footer_color_switcher',
			),
		),
		'footer_dark_mode_icon_size' => array(
			'control_type' => 'kadence_range_control',
			'section'      => 'footer_dark_mode',
			'priority'     => 12,
			'label'        => esc_html__( 'Icon Size', 'kadence-pro' ),
			'live_method'     => array(
				array(
					'type'     => 'css',
					'selector' => '.kadence-color-palette-footer-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-icon',
					'property' => 'font-size',
					'pattern'  => '$',
					'key'      => 'size',
				),
			),
			'default'      => kadence()->default( 'footer_dark_mode_icon_size' ),
			'input_attrs'  => array(
				'min'        => array(
					'px'  => 0,
					'em'  => 0,
					'rem' => 0,
				),
				'max'        => array(
					'px'  => 200,
					'em'  => 12,
					'rem' => 12,
				),
				'step'       => array(
					'px'  => 1,
					'em'  => 0.01,
					'rem' => 0.01,
				),
				'units'      => array( 'px', 'em', 'rem' ),
				'responsive' => true,
			),
		),
		'footer_dark_mode_colors' => array(
			'control_type' => 'kadence_color_control',
			'section'      => 'footer_dark_mode',
			'priority'     => 12,
			'label'        => esc_html__( 'Switch Colors', 'kadence-pro' ),
			'default'      => kadence()->default( 'footer_dark_mode_colors' ),
			'live_method'     => array(
				array(
					'type'     => 'css',
					'selector' => '.kadence-color-palette-footer-switcher',
					'property' => '--global-light-toggle-switch',
					'pattern'  => '$',
					'key'      => 'light',
				),
				array(
					'type'     => 'css',
					'selector' => '.kadence-color-palette-footer-switcher',
					'property' => '--global-dark-toggle-switch',
					'pattern'  => '$',
					'key'      => 'dark',
				),
			),
			'input_attrs'  => array(
				'colors' => array(
					'light' => array(
						'tooltip' => __( 'Light Color', 'kadence-pro' ),
						'palette' => false,
					),
					'dark' => array(
						'tooltip' => __( 'Dark Color', 'kadence-pro' ),
						'palette' => false,
					),
				),
			),
		),
		'footer_dark_mode_typography' => array(
			'control_type' => 'kadence_typography_control',
			'section'      => 'footer_dark_mode',
			'priority'     => 12,
			'label'        => esc_html__( 'Text Label Font', 'kadence-pro' ),
			'default'      => kadence()->default( 'footer_dark_mode_typography' ),
			'context'      => array(
				array(
					'setting'    => 'footer_dark_mode_switch_type',
					'operator'   => '!=',
					'value'      => 'icon',
				),
			),
			'live_method'     => array(
				array(
					'type'     => 'css_typography',
					'selector' => '.kadence-color-palette-footer-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-label',
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
				'id'      => 'footer_dark_mode_typography',
				'options' => 'no-color',
			),
		),
		'footer_dark_mode_switch_tooltip' => array(
			'control_type' => 'kadence_switch_control',
			'section'      => 'footer_dark_mode',
			'priority'     => 20,
			'default'      => kadence()->default( 'footer_dark_mode_switch_tooltip' ),
			'label'        => esc_html__( 'Enable Tooltip?', 'kadence-pro' ),
			'partial'      => array(
				'selector'            => '.kadence-color-palette-footer-switcher',
				'container_inclusive' => true,
				'render_callback'     => 'Kadence_Pro\footer_color_switcher',
			),
			'context'      => array(
				array(
					'setting'  => 'footer_dark_mode_switch_style',
					'operator' => '==',
					'value'    => 'button',
				),
				array(
					'setting'  => 'footer_dark_mode_switch_type',
					'operator' => '==',
					'value'    => 'icon',
				),
			),
		),
		'footer_dark_mode_light_switch_title' => array(
			'control_type' => 'kadence_text_control',
			'sanitize'     => 'sanitize_text_field',
			'section'      => 'footer_dark_mode',
			'priority'     => 20,
			'label'        => esc_html__( 'Light Palette Title', 'kadence-pro' ),
			'default'      => kadence()->default( 'footer_dark_mode_light_switch_title' ),
			'partial'      => array(
				'selector'            => '.kadence-color-palette-footer-switcher',
				'container_inclusive' => true,
				'render_callback'     => 'Kadence_Pro\footer_color_switcher',
			),
		),
		'footer_dark_mode_dark_switch_title' => array(
			'control_type' => 'kadence_text_control',
			'sanitize'     => 'sanitize_text_field',
			'section'      => 'footer_dark_mode',
			'priority'     => 20,
			'label'        => esc_html__( 'Dark Palette Title', 'kadence-pro' ),
			'default'      => kadence()->default( 'footer_dark_mode_dark_switch_title' ),
			'partial'      => array(
				'selector'            => '.kadence-color-palette-footer-switcher',
				'container_inclusive' => true,
				'render_callback'     => 'Kadence_Pro\footer_color_switcher',
			),
		),
		'footer_dark_mode_switch_margin' => array(
			'control_type' => 'kadence_measure_control',
			'section'      => 'footer_dark_mode',
			'priority'     => 20,
			'default'      => kadence()->default( 'footer_dark_mode_switch_margin' ),
			'label'        => esc_html__( 'Margin', 'kadence-pro' ),
			'live_method'     => array(
				array(
					'type'     => 'css',
					'selector' => '.kadence-color-palette-footer-switcher .kadence-color-palette-switcher',
					'property' => 'margin',
					'pattern'  => '$',
					'key'      => 'measure',
				),
			),
			'input_attrs'  => array(
				'responsive' => false,
			),
		),
	)
);
