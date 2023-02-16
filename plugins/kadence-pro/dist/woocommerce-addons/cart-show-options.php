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
		'cart_pop_show_on_add' => array(
			'control_type' => 'kadence_switch_control',
			'section'      => 'cart_behavior',
			'priority'     => 11,
			'default'      => kadence()->default( 'cart_pop_show_on_add' ),
			'label'        => esc_html__( 'Show the cart popout on add to cart?', 'kadence-pro' ),
			'transport'    => 'refresh',
		),
		'ajax_add_single_products' => array(
			'control_type' => 'kadence_switch_control',
			'section'      => 'cart_behavior',
			'priority'     => 11,
			'default'      => kadence()->default( 'ajax_add_single_products' ),
			'label'        => esc_html__( 'Single Product Ajax Add to Cart', 'kadence-pro' ),
			'transport'    => 'refresh',
		),
	)
);
