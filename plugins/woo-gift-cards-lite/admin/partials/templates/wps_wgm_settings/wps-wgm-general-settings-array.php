<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once WPS_WGC_DIRPATH . 'admin/partials/template_settings_function/class-woocommerce-giftcard-admin-settings.php';
$settings_obj = new Woocommerce_Giftcard_Admin_Settings();
$wps_wgm_general_setting = array(
	array(
		'title' => esc_html__( 'Enable', 'woo-gift-cards-lite' ),
		'id' => 'wps_wgm_general_setting_enable',
		'type' => 'checkbox',
		'class' => 'input-text',
		'desc_tip' => esc_html__( 'Check this box to enable gift card', 'woo-gift-cards-lite' ),
		'desc' => esc_html__( 'Enable WooCommerce Gift Card', 'woo-gift-cards-lite' ),
	),
	array(
		'title' => esc_html__( 'Enable Tax', 'woo-gift-cards-lite' ),
		'id' => 'wps_wgm_general_setting_tax_cal_enable',
		'type' => 'checkbox',
		'class' => 'input-text',
		'desc_tip' => esc_html__( 'Check this box to enable tax for gift card products.', 'woo-gift-cards-lite' ),
		'desc' => esc_html__( 'Enable Tax Calculation for Gift Card', 'woo-gift-cards-lite' ),
	),
	array(
		'title' => esc_html__( 'Enable Listing Shop Page', 'woo-gift-cards-lite' ),
		'id' => 'wps_wgm_general_setting_shop_page_enable',
		'type' => 'checkbox',
		'class' => 'input-text',
		'desc_tip' => esc_html__( 'Check this box to enable gift card product listing on the shop page.', 'woo-gift-cards-lite' ),
		'desc' => esc_html__( 'Enable Gift Card Product listing on shop page', 'woo-gift-cards-lite' ),
	),
	array(
		'title' => esc_html__( 'Individual Use', 'woo-gift-cards-lite' ),
		'id' => 'wps_wgm_general_setting_giftcard_individual_use',
		'type' => 'checkbox',
		'class' => 'input-text',
		'desc_tip' => esc_html__( 'Check this box if the Gift Card Coupon cannot be used in conjunction with other Giftcards/Coupons.', 'woo-gift-cards-lite' ),
		'desc' => esc_html__( 'Allow Gift Card to use Individually', 'woo-gift-cards-lite' ),
	),
	array(
		'title' => esc_html__( 'Free Shipping', 'woo-gift-cards-lite' ),
		'id' => 'wps_wgm_general_setting_giftcard_freeshipping',
		'type' => 'checkbox',
		'class' => 'input-text',
		'desc_tip' => esc_html__( 'Check this box if the coupon grants free shipping. A free shipping method must be enabled in your shipping zone and be set to require "a valid free shipping coupon" (see the "Free Shipping Requires" setting).', 'woo-gift-cards-lite' ),
		'desc' => esc_html__( 'Allow Giftcard on Free Shipping', 'woo-gift-cards-lite' ),
	),
	array(
		'title' => esc_html__( 'Gift Card Coupon Length', 'woo-gift-cards-lite' ),
		'id' => 'wps_wgm_general_setting_giftcard_coupon_length',
		'type' => 'number',
		'custom_attribute' => array(
			'min' => '"5"',
			'max' => '"10"',
		),
		'class' => 'input-text wps_wgm_new_woo_ver_style_text',
		'default' => 5,
		'desc_tip' => esc_html__( 'Enter gift card coupon length excluding the prefix. (Minimum length is set to 5)', 'woo-gift-cards-lite' ),
	),
	array(
		'title' => esc_html__( 'Gift Card Prefix', 'woo-gift-cards-lite' ),
		'id' => 'wps_wgm_general_setting_giftcard_prefix',
		'type' => 'text',
		'class' => 'input-text wps_wgm_new_woo_ver_style_text',
		'style' => 'width:160px',
		'desc_tip' => esc_html__( 'Enter Gift Card Prefix. Ex: PREFIX_CODE', 'woo-gift-cards-lite' ),
	),
	array(
		'title' => esc_html__( 'Gift Card Expiry After Days', 'woo-gift-cards-lite' ),
		'id' => 'wps_wgm_general_setting_giftcard_expiry',
		'type' => 'number',
		'custom_attribute' => array( 'min' => '0' ),
		'default' => 0,
		'class' => 'input-text wps_wgm_new_woo_ver_style_text',
		'desc_tip' => esc_html__( 'Enter number of days after purchased Gift Card is expired. Keep value "1" for one-day expiry when order is completed. Keep value "0" for no expiry.', 'woo-gift-cards-lite' ),
	),
	array(
		'title' => esc_html__( 'Minimum Spend', 'woo-gift-cards-lite' ),
		'id' => 'wps_wgm_general_setting_giftcard_minspend',
		'type' => 'number',
		'custom_attribute' => array(
			'min' => '"0"',
			'placeholder' => '"No Minimum"',
		),
		'default' => '',
		'class' => 'input-text wps_wgm_new_woo_ver_style_text wps_wgm_gc_price_range',
		'desc_tip' => esc_html__( 'This field allows you to set the minimum spend (subtotal, including taxes) allowed to use the Gift card coupon.', 'woo-gift-cards-lite' ),
	),
	array(
		'title' => esc_html__( 'Maximum Spend', 'woo-gift-cards-lite' ),
		'id' => 'wps_wgm_general_setting_giftcard_maxspend',
		'type' => 'number',
		'custom_attribute' => array(
			'min' => '"0"',
			'placeholder' => '"No Maximum"',
		),
		'default' => '',
		'class' => 'input-text wps_wgm_new_woo_ver_style_text wps_wgm_gc_price_range',
		'desc_tip' => esc_html__( 'This field allows you to set the maximum spend (subtotal, including taxes) allowed when using the Gift card coupon.', 'woo-gift-cards-lite' ),
	),
	array(
		'title' => esc_html__( 'Gift Card No of time usage', 'woo-gift-cards-lite' ),
		'id' => 'wps_wgm_general_setting_giftcard_use',
		'type' => 'number',
		'custom_attribute' => array( 'min' => '0' ),
		'default' => 0,
		'class' => 'input-text wps_gw_new_woo_ver_style_text',
		'desc_tip' => esc_html__( 'How many times this coupon can be used before Gift card is void. keep value "0" for unlimited use.', 'woo-gift-cards-lite' ),
	),
);
 $wps_wgm_general_setting = apply_filters( 'wps_wgm_general_setting', $wps_wgm_general_setting );
