<?php
/**
 * Woocommerce Trigger Cart when Product added Options.
 *
 * @package Kadence_Pro
 */

namespace Kadence_Pro;

use Kadence\Theme_Customizer;
use function Kadence\kadence;
ob_start(); ?>
<div class="kadence-compontent-info-box wp-clearfix">
	<p><?php echo esc_html__( 'You must add or remove a product from your active cart to see your settings for this take place.', 'kadence-pro' ); ?></p>
	<p><?php echo esc_html__( 'Use {cart_difference} placeholder in message to output the amount needed for free shipping.', 'kadence-pro' ); ?></p>

</div>
<?php
$compontent_tabs = ob_get_clean();
Theme_Customizer::add_settings(
	array(
		'cart_pop_show_free_shipping' => array(
			'control_type' => 'kadence_switch_control',
			'section'      => 'cart',
			'priority'     => 21,
			'default'      => kadence()->default( 'cart_pop_show_free_shipping' ),
			'label'        => esc_html__( 'Show amount left in order to receive free shipping in cart Popout', 'kadence-pro' ),
			'transport'    => 'refresh',
			'context'      => array(
				array(
					'setting'  => 'header_cart_style',
					'operator' => '!=',
					'value'    => 'link',
				),
			),
		),
		'cart_pop_free_shipping_info' => array(
			'control_type' => 'kadence_blank_control',
			'section'      => 'cart',
			'settings'     => false,
			'priority'     => 21,
			'description'  => $compontent_tabs,
			'context'      => array(
				array(
					'setting'  => 'cart_pop_show_free_shipping',
					'operator' => '=',
					'value'    => true,
				),
			),
		),
		'cart_pop_free_shipping_price' => array(
			'type'         => 'number',
			'section'      => 'cart',
			'sanitize'     => 'sanitize_text_field',
			'priority'     => 21,
			'default'      => kadence()->default( 'cart_pop_free_shipping_price' ),
			'label'        => esc_html__( 'Amount needed for Free Shipping.', 'kadence-pro' ),
			'transport'    => 'refresh',
			'context'      => array(
				array(
					'setting'  => 'cart_pop_show_free_shipping',
					'operator' => '=',
					'value'    => true,
				),
			),
		),
		'cart_pop_free_shipping_message' => array(
			'control_type' => 'kadence_text_control',
			'section'      => 'cart',
			'sanitize'     => 'sanitize_text_field',
			'priority'     => 21,
			'default'      => kadence()->default( 'cart_pop_free_shipping_message' ),
			'label'        => esc_html__( 'Cart Notice Message', 'kadence-pro' ),
			'transport'    => 'refresh',
			'context'      => array(
				array(
					'setting'  => 'cart_pop_show_free_shipping',
					'operator' => '=',
					'value'    => true,
				),
			),
		),
	)
);
