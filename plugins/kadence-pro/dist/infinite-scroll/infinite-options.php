<?php
/**
 * Woocommerce Trigger Cart when Product added Options.
 *
 * @package Kadence_Pro
 */

namespace Kadence_Pro;

use Kadence\Theme_Customizer;
use function Kadence\kadence;
Theme_Customizer::add_settings(
	array(
		'infinite_posts' => array(
			'control_type' => 'kadence_switch_control',
			'section'      => 'infinite_scroll',
			'priority'     => 11,
			'default'      => kadence()->default( 'infinite_posts' ),
			'label'        => esc_html__( 'Infinite Scroll for Blog?', 'kadence-pro' ),
			'input_attrs'  => array(
				'help' => esc_html__( 'This will use apply to all post archives.', 'kadence-pro' ),
			),
			'transport'    => 'refresh',
		),
		// 'infinite_single_posts' => array(
		// 	'control_type' => 'kadence_switch_control',
		// 	'section'      => 'infinite_scroll',
		// 	'priority'     => 11,
		// 	'default'      => kadence()->default( 'infinite_single_posts' ),
		// 	'label'        => esc_html__( 'Infinite Scroll for Single Blog Posts?', 'kadence-pro' ),
		// 	'input_attrs'  => array(
		// 		'help' => esc_html__( 'This will use apply to single posts.', 'kadence-pro' ),
		// 	),
		// 	'transport'    => 'refresh',
		// ),
		'infinite_search' => array(
			'control_type' => 'kadence_switch_control',
			'section'      => 'infinite_scroll',
			'priority'     => 11,
			'default'      => kadence()->default( 'infinite_search' ),
			'label'        => esc_html__( 'Infinite Scroll for Search?', 'kadence-pro' ),
			'transport'    => 'refresh',
		),
		'infinite_products' => array(
			'control_type' => 'kadence_switch_control',
			'section'      => 'infinite_scroll',
			'priority'     => 11,
			'default'      => kadence()->default( 'infinite_products' ),
			'label'        => esc_html__( 'Infinite Scroll for Products?', 'kadence-pro' ),
			'transport'    => 'refresh',
		),
		'infinite_custom' => array(
			'control_type' => 'kadence_switch_control',
			'section'      => 'infinite_scroll',
			'priority'     => 11,
			'default'      => kadence()->default( 'infinite_custom' ),
			'label'        => esc_html__( 'Infinite Scroll for Custom Post Types?', 'kadence-pro' ),
			'input_attrs'  => array(
				'help' => esc_html__( 'This will use apply to all custom post archives.', 'kadence-pro' ),
			),
			'transport'    => 'refresh',
		),
		'infinite_end_of_content' => array(
			'control_type' => 'kadence_text_control',
			'sanitize'     => 'sanitize_text_field',
			'section'      => 'infinite_scroll',
			'priority'     => 12,
			'label'        => esc_html__( 'End of Content Text', 'kadence-pro' ),
			'default'      => kadence()->default( 'infinite_end_of_content' ),
			'transport'    => 'refresh',
		),
	)
);
