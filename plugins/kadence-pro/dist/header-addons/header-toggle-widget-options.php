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
	'header_toggle_widget_tabs' => array(
		'control_type' => 'kadence_tab_control',
		'section'      => 'header_toggle_widget',
		'settings'     => false,
		'priority'     => 1,
		'input_attrs'  => array(
			'general' => array(
				'label'  => __( 'General', 'kadence-pro' ),
				'target' => 'header_toggle_widget',
			),
			'design' => array(
				'label'  => __( 'Design', 'kadence-pro' ),
				'target' => 'header_toggle_widget_design',
			),
			'active' => 'general',
		),
	),
	'header_toggle_widget_tabs_design' => array(
		'control_type' => 'kadence_tab_control',
		'section'      => 'header_toggle_widget_design',
		'settings'     => false,
		'priority'     => 1,
		'input_attrs'  => array(
			'general' => array(
				'label'  => __( 'General', 'kadence-pro' ),
				'target' => 'header_toggle_widget',
			),
			'design' => array(
				'label'  => __( 'Design', 'kadence-pro' ),
				'target' => 'header_toggle_widget_design',
			),
			'active' => 'design',
		),
	),
	'header_toggle_widget_label' => array(
		'control_type' => 'kadence_text_control',
		'sanitize'     => 'sanitize_text_field',
		'section'      => 'header_toggle_widget',
		'default'      => kadence()->default( 'header_toggle_widget_label' ),
		'label'        => esc_html__( 'Trigger Label', 'kadence-pro' ),
		'live_method'     => array(
			array(
				'type'     => 'html',
				'selector' => '.widget-toggle-label',
				'pattern'  => '$',
				'key'      => '',
			),
		),
	),
	'header_toggle_widget_icon' => array(
		'control_type' => 'kadence_radio_icon_control',
		'section'      => 'header_toggle_widget',
		'default'      => kadence()->default( 'header_toggle_widget_icon' ),
		'label'        => esc_html__( 'Trigger Icon', 'kadence-pro' ),
		'partial'      => array(
			'selector'            => '.widget-toggle-icon',
			'container_inclusive' => false,
			'render_callback'     => 'Kadence_Pro\widget_toggle_icon',
		),
		'input_attrs'  => array(
			'layout' => array(
				'menu' => array(
					'icon' => 'menu',
				),
				'menu2' => array(
					'icon' => 'menu2',
				),
				'menu3' => array(
					'icon' => 'menu3',
				),
			),
			'responsive' => false,
		),
	),
	'header_toggle_widget_style' => array(
		'control_type' => 'kadence_radio_icon_control',
		'section'      => 'header_toggle_widget_design',
		'default'      => kadence()->default( 'header_toggle_widget_style' ),
		'label'        => esc_html__( 'Trigger Style', 'kadence-pro' ),
		'live_method'     => array(
			array(
				'type'     => 'class',
				'selector' => '.widget-toggle-open',
				'pattern'  => 'widget-toggle-style-$',
				'key'      => '',
			),
		),
		'input_attrs'  => array(
			'layout' => array(
				'default' => array(
					'name' => __( 'Default', 'kadence-pro' ),
				),
				'bordered' => array(
					'name' => __( 'Bordered', 'kadence-pro' ),
				),
			),
			'responsive' => false,
		),
	),
	'header_toggle_widget_border' => array(
		'control_type' => 'kadence_border_control',
		'section'      => 'header_toggle_widget_design',
		'label'        => esc_html__( 'Trigger Border', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_toggle_widget_border' ),
		'context'      => array(
			array(
				'setting'    => 'header_toggle_widget_style',
				'operator'   => 'sub_object_contains',
				'sub_key'    => 'layout',
				'responsive' => false,
				'value'      => 'bordered',
			),
		),
		'live_method'     => array(
			array(
				'type'     => 'css_border',
				'selector' => '.widget-toggle-open-container .widget-toggle-open.widget-toggle-style-bordered',
				'pattern'  => '$',
				'property' => 'border',
				'key'      => 'border',
			),
		),
		'input_attrs'  => array(
			'color'      => false,
			'responsive' => false,
		),
	),
	'header_toggle_widget_icon_size' => array(
		'control_type' => 'kadence_range_control',
		'section'      => 'header_toggle_widget_design',
		'label'        => esc_html__( 'Icon Size', 'kadence-pro' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.widget-toggle-open-container .widget-toggle-open .widget-toggle-icon',
				'property' => 'font-size',
				'pattern'  => '$',
				'key'      => 'size',
			),
		),
		'default'      => kadence()->default( 'header_toggle_widget_icon_size' ),
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
	'header_toggle_widget_color' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'header_toggle_widget_design',
		'label'        => esc_html__( 'Trigger Colors', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_toggle_widget_color' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.widget-toggle-open-container .widget-toggle-open',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '.widget-toggle-open-container .widget-toggle-open:hover',
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
	'header_toggle_widget_background' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'header_toggle_widget_design',
		'label'        => esc_html__( 'Trigger Background', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_toggle_widget_background' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.widget-toggle-open-container .widget-toggle-open',
				'property' => 'background',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '.widget-toggle-open-container .widget-toggle-open:hover',
				'property' => 'background',
				'pattern'  => '$',
				'key'      => 'hover',
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
			),
		),
	),
	'header_toggle_widget_typography' => array(
		'control_type' => 'kadence_typography_control',
		'section'      => 'header_toggle_widget_design',
		'label'        => esc_html__( 'Trigger Font', 'kadence-pro' ),
		'context'      => array(
			array(
				'setting'  => 'header_toggle_widget_label',
				'operator' => '!empty',
				'value'    => '',
			),
		),
		'default'      => kadence()->default( 'header_toggle_widget_typography' ),
		'live_method'     => array(
			array(
				'type'     => 'css_typography',
				'selector' => '.widget-toggle-open-container .widget-toggle-open',
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
			'id'      => 'header_toggle_widget_typography',
			'options' => 'no-color',
		),
	),
	'header_toggle_widget_padding' => array(
		'control_type' => 'kadence_measure_control',
		'section'      => 'header_toggle_widget_design',
		'default'      => kadence()->default( 'header_toggle_widget_padding' ),
		'label'        => esc_html__( 'Trigger Padding', 'kadence-pro' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.widget-toggle-open-container .widget-toggle-open',
				'property' => 'padding',
				'pattern'  => '$',
				'key'      => 'measure',
			),
		),
		'input_attrs'  => array(
			'responsive' => false,
		),
	),	
	'info_link_widget_toggle_container' => array(
		'control_type' => 'kadence_title_control',
		'section'      => 'header_toggle_widget',
		'label'        => esc_html__( 'Widget Items', 'kadence-pro' ),
		'settings'     => false,
	),
	'widget_toggle_drawer_link' => array(
		'control_type' => 'kadence_focus_button_control',
		'section'      => 'header_toggle_widget',
		'settings'     => false,
		'label'        => esc_html__( 'Add Widget Items', 'kadence-pro' ),
		'input_attrs'  => array(
			'section' => 'sidebar-widgets-header2',
		),
	),
);

Theme_Customizer::add_settings( $settings );

ob_start(); ?>
<div class="kadence-compontent-tabs nav-tab-wrapper wp-clearfix">
	<a href="#" class="nav-tab kadence-general-tab kadence-compontent-tabs-button nav-tab-active" data-tab="general">
		<span><?php esc_html_e( 'General', 'kadence-pro' ); ?></span>
	</a>
</div>
<?php
$compontent_tabs = ob_get_clean();

$widget_settings = array(
	'header_widget2_breaker' => array(
		'control_type' => 'kadence_blank_control',
		'section'      => 'sidebar-widgets-header2',
		'settings'     => false,
		'priority'     => 5,
		'description'  => $compontent_tabs,
	),
	'header_widget2_title' => array(
		'control_type' => 'kadence_typography_control',
		'section'      => 'sidebar-widgets-header2',
		'label'        => esc_html__( 'Widget Titles', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_widget2_title' ),
		'live_method'     => array(
			array(
				'type'     => 'css_typography',
				'selector' => '#widget-drawer .drawer-inner .header-widget2 .widget-title',
				'pattern'  => array(
					'desktop' => '$',
					'tablet'  => '$',
					'mobile'  => '$',
				),
				'property' => 'font',
				'key'      => 'typography',
			),
		),
		'context'      => array(
			array(
				'setting' => '__current_tab',
				'value'   => 'general',
			),
		),
		'input_attrs'  => array(
			'id' => 'header_widget2_title',
		),
	),
	'header_widget2_content' => array(
		'control_type' => 'kadence_typography_control',
		'section'      => 'sidebar-widgets-header2',
		'label'        => esc_html__( 'Widget Content', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_widget2_content' ),
		'live_method'     => array(
			array(
				'type'     => 'css_typography',
				'selector' => '#widget-drawer .drawer-inner .header-widget2',
				'pattern'  => array(
					'desktop' => '$',
					'tablet'  => '$',
					'mobile'  => '$',
				),
				'property' => 'font',
				'key'      => 'typography',
			),
		),
		'context'      => array(
			array(
				'setting' => '__current_tab',
				'value'   => 'general',
			),
		),
		'input_attrs'  => array(
			'id' => 'header_widget2_content',
		),
	),
	'header_widget2_link_colors' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'sidebar-widgets-header2',
		'label'        => esc_html__( 'Link Colors', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_widget2_link_colors' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '#widget-drawer .drawer-inner .header-widget2 a:not(.button), #widget-drawer .header-widget2 .drawer-sub-toggle',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '#widget-drawer .drawer-inner .header-widget2 a:not(.button):hover, #widget-drawer .header-widget2 .drawer-sub-toggle:hover',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'hover',
			),
		),
		'context'      => array(
			array(
				'setting' => '__current_tab',
				'value'   => 'general',
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
	'header_widget2_link_style' => array(
		'control_type' => 'kadence_select_control',
		'section'      => 'sidebar-widgets-header2',
		'default'      => kadence()->default( 'header_widget2_link_style' ),
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
		'context'      => array(
			array(
				'setting' => '__current_tab',
				'value'   => 'general',
			),
		),
		'live_method'     => array(
			array(
				'type'     => 'class',
				'selector' => '#widget-drawer .drawer-inner .header-widget2',
				'pattern'  => 'header-widget-2style-$',
				'key'      => '',
			),
		),
	),
	'header_widget2_padding' => array(
		'control_type' => 'kadence_measure_control',
		'section'      => 'sidebar-widgets-header2',
		'default'      => kadence()->default( 'header_widget2_padding' ),
		'label'        => esc_html__( 'Padding', 'kadence-pro' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '#widget-drawer .drawer-inner .header-widget2',
				'property' => 'padding',
				'pattern'  => '$',
				'key'      => 'measure',
			),
		),
		'context'      => array(
			array(
				'setting' => '__current_tab',
				'value'   => 'general',
			),
		),
		'input_attrs'  => array(
			'responsive' => false,
		),
	),
	'info_header_toggle_widget_design' => array(
		'control_type' => 'kadence_title_control',
		'section'      => 'sidebar-widgets-header2',
		'label'        => esc_html__( 'Popup Area Settings', 'kadence-pro' ),
		'settings'     => false,
		'context'      => array(
			array(
				'setting' => '__current_tab',
				'value'   => 'general',
			),
		),
	),
	'header_toggle_widget_layout' => array(
		'control_type' => 'kadence_radio_icon_control',
		'section'      => 'sidebar-widgets-header2',
		'default'      => kadence()->default( 'header_toggle_widget_layout' ),
		'label'        => esc_html__( 'Layout', 'kadence-pro' ),
		'live_method'     => array(
			array(
				'type'     => 'class',
				'selector' => '#widget-drawer',
				'pattern'  => 'popup-drawer-layout-$',
				'key'      => '',
			),
		),
		'context'      => array(
			array(
				'setting' => '__current_tab',
				'value'   => 'general',
			),
		),
		'input_attrs'  => array(
			'layout' => array(
				'fullwidth' => array(
					'tooltip' => __( 'Reveal as Fullwidth', 'kadence-pro' ),
					'name'    => __( 'Fullwidth', 'kadence-pro' ),
					'icon'    => '',
				),
				'sidepanel' => array(
					'tooltip' => __( 'Reveal as Side Panel', 'kadence-pro' ),
					'name'    => __( 'Side Panel', 'kadence-pro' ),
					'icon'    => '',
				),
			),
			'responsive' => false,
		),
	),
	'header_toggle_widget_side' => array(
		'control_type' => 'kadence_radio_icon_control',
		'section'      => 'sidebar-widgets-header2',
		'default'      => kadence()->default( 'header_toggle_widget_side' ),
		'label'        => esc_html__( 'Slide-Out Side', 'kadence-pro' ),
		'context'      => array(
			array(
				'setting' => '__current_tab',
				'value'   => 'general',
			),
			array(
				'setting'    => 'header_toggle_widget_layout',
				'operator'   => 'sub_object_contains',
				'sub_key'    => 'layout',
				'responsive' => false,
				'value'      => 'sidepanel',
			),
		),
		'live_method'     => array(
			array(
				'type'     => 'class',
				'selector' => '#widget-drawer',
				'pattern'  => 'popup-drawer-side-$',
				'key'      => '',
			),
		),
		'input_attrs'  => array(
			'layout' => array(
				'left' => array(
					'tooltip' => __( 'Reveal from Left', 'kadence-pro' ),
					'name'    => __( 'Left', 'kadence-pro' ),
					'icon'    => '',
				),
				'right' => array(
					'tooltip' => __( 'Reveal from Right', 'kadence-pro' ),
					'name'    => __( 'Right', 'kadence-pro' ),
					'icon'    => '',
				),
			),
			'responsive' => false,
		),
	),
	'header_toggle_widget_pop_width' => array(
		'control_type' => 'kadence_range_control',
		'section'      => 'sidebar-widgets-header2',
		'label'        => esc_html__( 'Popup Content Max Width', 'kadence-pro' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '#widget-drawer.popup-drawer-layout-fullwidth .drawer-content .header-widget2, #widget-drawer.popup-drawer-layout-sidepanel .drawer-inner',
				'property' => 'max-width',
				'pattern'  => '$',
				'key'      => 'size',
			),
		),
		'context'      => array(
			array(
				'setting' => '__current_tab',
				'value'   => 'general',
			),
		),
		'default'      => kadence()->default( 'header_toggle_widget_pop_width' ),
		'input_attrs'  => array(
			'min'        => array(
				'px' => 100,
				'%'  => 10,
			),
			'max'        => array(
				'px' => 1000,
				'%'  => 100,
			),
			'step'       => array(
				'px' => 1,
				'%'  => 1,
			),
			'units'      => array( 'px', '%' ),
			'responsive' => false,
		),
	),
	'header_toggle_widget_pop_background' => array(
		'control_type' => 'kadence_background_control',
		'section'      => 'sidebar-widgets-header2',
		'label'        => esc_html__( 'Popup Background', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_toggle_widget_pop_background' ),
		'live_method'     => array(
			array(
				'type'     => 'css_background',
				'selector' => '#widget-drawer .drawer-inner',
				'property' => 'background',
				'pattern'  => '$',
				'key'      => 'base',
			),
		),
		'context'      => array(
			array(
				'setting' => '__current_tab',
				'value'   => 'general',
			),
		),
		'input_attrs'  => array(
			'tooltip'  => __( 'Popup Background', 'kadence-pro' ),
		),
	),
	'header_toggle_widget_close_color' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'sidebar-widgets-header2',
		'label'        => esc_html__( 'Close Toggle Colors', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_toggle_widget_close_color' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '#widget-drawer .drawer-header .drawer-toggle',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '#widget-drawer .drawer-header .drawer-toggle:hover',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'hover',
			),
		),
		'context'      => array(
			array(
				'setting' => '__current_tab',
				'value'   => 'general',
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
	'transparent_toggle_widget_color' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'transparent_header_design',
		'label'        => esc_html__( 'Toggle Widget Colors', 'kadence-pro' ),
		'default'      => kadence()->default( 'transparent_toggle_widget_color' ),
		'priority'     => 30,
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '.transparent-header #main-header .widget-toggle-open-container .widget-toggle-open',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '.transparent-header #main-header .widget-toggle-open-container .widget-toggle-open:hover',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'hover',
			),
			array(
				'type'     => 'css',
				'selector' => '.transparent-header #main-header .widget-toggle-open-container .widget-toggle-open',
				'property' => 'background',
				'pattern'  => '$',
				'key'      => 'background',
			),
			array(
				'type'     => 'css',
				'selector' => '.transparent-header #main-header .widget-toggle-open-container .widget-toggle-open:hover',
				'property' => 'background',
				'pattern'  => '$',
				'key'      => 'backgroundHover',
			),
			array(
				'type'     => 'css',
				'selector' => '.transparent-header #main-header .widget-toggle-open-container .widget-toggle-open',
				'property' => 'border-color',
				'pattern'  => '$',
				'key'      => 'border',
			),
			array(
				'type'     => 'css',
				'selector' => '.transparent-header #main-header .widget-toggle-open-container .widget-toggle-open:hover',
				'property' => 'border-color',
				'pattern'  => '$',
				'key'      => 'borderHover',
			),
		),
		'input_attrs'  => array(
			'colors' => array(
				'color' => array(
					'tooltip' => __( 'Color', 'kadence-pro' ),
					'palette' => true,
				),
				'hover' => array(
					'tooltip' => __( 'Hover Color', 'kadence-pro' ),
					'palette' => true,
				),
				'background' => array(
					'tooltip' => __( 'Background', 'kadence-pro' ),
					'palette' => true,
				),
				'backgroundHover' => array(
					'tooltip' => __( 'Background Hover', 'kadence-pro' ),
					'palette' => true,
				),
				'border' => array(
					'tooltip' => __( 'Border', 'kadence-pro' ),
					'palette' => true,
				),
				'borderHover' => array(
					'tooltip' => __( 'Border Hover', 'kadence-pro' ),
					'palette' => true,
				),
			),
		),
	),
	'header_sticky_toggle_widget_color' => array(
		'control_type' => 'kadence_color_control',
		'section'      => 'header_sticky_design',
		'label'        => esc_html__( 'Toggle Widget Colors', 'kadence-pro' ),
		'default'      => kadence()->default( 'header_sticky_toggle_widget_color' ),
		'live_method'     => array(
			array(
				'type'     => 'css',
				'selector' => '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .widget-toggle-open-container .widget-toggle-open',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'color',
			),
			array(
				'type'     => 'css',
				'selector' => '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .widget-toggle-open-container .widget-toggle-open:hover',
				'property' => 'color',
				'pattern'  => '$',
				'key'      => 'hover',
			),
			array(
				'type'     => 'css',
				'selector' => '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .widget-toggle-open-container .widget-toggle-open',
				'property' => 'background',
				'pattern'  => '$',
				'key'      => 'background',
			),
			array(
				'type'     => 'css',
				'selector' => '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .widget-toggle-open-container .widget-toggle-open:hover',
				'property' => 'background',
				'pattern'  => '$',
				'key'      => 'backgroundHover',
			),
			array(
				'type'     => 'css',
				'selector' => '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .widget-toggle-open-container .widget-toggle-open',
				'property' => 'border-color',
				'pattern'  => '$',
				'key'      => 'border',
			),
			array(
				'type'     => 'css',
				'selector' => '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .widget-toggle-open-container .widget-toggle-open:hover',
				'property' => 'border-color',
				'pattern'  => '$',
				'key'      => 'borderHover',
			),
		),
		'input_attrs'  => array(
			'colors' => array(
				'color' => array(
					'tooltip' => __( 'Color', 'kadence-pro' ),
					'palette' => true,
				),
				'hover' => array(
					'tooltip' => __( 'Hover Color', 'kadence-pro' ),
					'palette' => true,
				),
				'background' => array(
					'tooltip' => __( 'Background', 'kadence-pro' ),
					'palette' => true,
				),
				'backgroundHover' => array(
					'tooltip' => __( 'Background Hover', 'kadence-pro' ),
					'palette' => true,
				),
				'border' => array(
					'tooltip' => __( 'Border', 'kadence-pro' ),
					'palette' => true,
				),
				'borderHover' => array(
					'tooltip' => __( 'Border Hover', 'kadence-pro' ),
					'palette' => true,
				),
			),
		),
	),
);
Theme_Customizer::add_settings( $widget_settings );