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
		'dark_mode_learndash_enable' => array(
			'control_type' => 'kadence_switch_control',
			'section'      => 'sfwd_lesson_layout',
			'priority'     => 10,
			'default'      => kadence()->default( 'dark_mode_learndash_enable' ),
			'label'        => esc_html__( 'Enable Dark Mode Switch in LearnDash Focus Mode Header?', 'kadence-pro' ),
			'transport'    => 'refresh',
		),
		'dark_mode_learndash_lesson_only' => array(
			'control_type' => 'kadence_switch_control',
			'section'      => 'sfwd_lesson_layout',
			'priority'     => 10,
			'default'      => kadence()->default( 'dark_mode_learndash_lesson_only' ),
			'label'        => esc_html__( 'Only show dark mode in Lessons?', 'kadence-pro' ),
			'transport'    => 'refresh',
			'context'      => array(
				array(
					'setting'  => 'dark_mode_learndash_enable',
					'operator' => '=',
					'value'    => true,
				),
			),
		),
		'dark_mode_learndash_lesson_logo' => array(
			'control_type' => 'media',
			'section'      => 'sfwd_lesson_layout',
			'priority'     => 10,
			'transport'    => 'refresh',
			'mime_type'    => 'image',
			'default'      => '',
			'label'        => esc_html__( 'LearnDash Focus Mode Dark Mode Logo', 'kadence-pro' ),
			'context'      => array(
				array(
					'setting'  => 'dark_mode_learndash_enable',
					'operator' => '=',
					'value'    => true,
				),
			),
		),
	)
);

