<?php
/**
 * Woocommerce Product Catalog Options.
 *
 * @package Kadence_Pro
 */

namespace Kadence_Pro;

use Kadence\Theme_Customizer;
use function Kadence\kadence;

Theme_Customizer::add_settings(
	array(
		'info_product_sticky_add_to_cart' => array(
			'control_type' => 'kadence_title_control',
			'priority'     => 20,
			'section'      => 'product_layout',
			'label'        => esc_html__( 'Sticky Add To Cart', 'kadence-pro' ),
			'settings'     => false,
		),
		'product_sticky_add_to_cart' => array(
			'control_type' => 'kadence_switch_control',
			'section'      => 'product_layout',
			'priority'     => 20,
			'default'      => kadence()->default( 'product_sticky_add_to_cart' ),
			'label'        => esc_html__( 'Enabled Sticky Add to Cart', 'kadence-pro' ),
			'input_attrs'  => array(
				'help' => esc_html__( 'Adds a Sticky Bar with add to cart when you scroll down the product page.', 'kadence-pro' ),
			),
			'transport'    => 'refresh',
		),
		'product_sticky_add_to_cart_placement' => array(
			'control_type' => 'kadence_radio_icon_control',
			'section'      => 'product_layout',
			'default'      => kadence()->default( 'product_sticky_add_to_cart_placement' ),
			'label'        => esc_html__( 'Sticky Placement', 'kadence-pro' ),
			'priority'     => 20,
			'context'      => array(
				array(
					'setting'    => 'product_sticky_add_to_cart',
					'operator'   => '=',
					'value'      => true,
				),
			),
			'live_method'     => array(
				array(
					'type'     => 'class',
					'selector' => '#kadence-sticky-add-to-cart',
					'pattern'  => 'kadence-sticky-add-to-cart-$',
					'key'      => '',
				),
			),
			'input_attrs'  => array(
				'layout' => array(
					'header' => array(
						'name' => __( 'Top', 'kadence-pro' ),
					),
					'footer' => array(
						'name' => __( 'Bottom', 'kadence-pro' ),
					),
				),
				'responsive' => false,
				'class'      => 'kadence-two-forced',
			),
		),
		'product_sticky_mobile_add_to_cart' => array(
			'control_type' => 'kadence_switch_control',
			'section'      => 'product_layout',
			'priority'     => 20,
			'default'      => kadence()->default( 'product_sticky_mobile_add_to_cart' ),
			'label'        => esc_html__( 'Enabled for mobile', 'kadence-pro' ),
			'transport'    => 'refresh',
			'context'      => array(
				array(
					'setting'    => 'product_sticky_add_to_cart',
					'operator'   => '=',
					'value'      => true,
				),
			),
		),
		'product_sticky_mobile_add_to_cart_placement' => array(
			'control_type' => 'kadence_radio_icon_control',
			'section'      => 'product_layout',
			'default'      => kadence()->default( 'product_sticky_mobile_add_to_cart_placement' ),
			'label'        => esc_html__( 'Mobile Placement', 'kadence-pro' ),
			'priority'     => 20,
			'context'      => array(
				array(
					'setting'    => 'product_sticky_add_to_cart',
					'operator'   => '=',
					'value'      => true,
				),
				array(
					'setting'    => 'product_sticky_mobile_add_to_cart',
					'operator'   => '=',
					'value'      => true,
				),
			),
			'live_method'     => array(
				array(
					'type'     => 'class',
					'selector' => '#kadence-sticky-mobile-add-to-cart',
					'pattern'  => 'kadence-sticky-add-to-cart-$',
					'key'      => '',
				),
			),
			'input_attrs'  => array(
				'layout' => array(
					'header' => array(
						'name' => __( 'Top', 'kadence-pro' ),
					),
					'footer' => array(
						'name' => __( 'Bottom', 'kadence-pro' ),
					),
				),
				'responsive' => false,
				'class'      => 'kadence-two-forced',
			),
		),
	)
);

