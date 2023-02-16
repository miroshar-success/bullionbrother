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
		'header_scripts' => array(
			'type'         => 'code_editor',
			'section'      => 'scripts',
			'priority'     => 11,
			'code_type'   => 'text/js',
			'default'      => kadence()->default( 'header_scripts' ),
			'label'        => esc_html__( 'Add scripts into your header', 'kadence-pro' ),
		),
		'after_body_scripts' => array(
			'type'         => 'code_editor',
			'section'      => 'scripts',
			'priority'     => 11,
			'code_type'   => 'text/js',
			'default'      => kadence()->default( 'after_body_scripts' ),
			'label'        => esc_html__( 'Add scripts right after opening body tag', 'kadence-pro' ),
		),
		'footer_scripts' => array(
			'type'         => 'code_editor',
			'section'      => 'scripts',
			'priority'     => 11,
			'code_type'   => 'text/js',
			'default'      => kadence()->default( 'footer_scripts' ),
			'label'        => esc_html__( 'Add scripts into your footer', 'kadence-pro' ),
		),
	)
);
