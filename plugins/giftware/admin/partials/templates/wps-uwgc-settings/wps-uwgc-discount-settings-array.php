<?php
/**
 * Exit if accessed directly
 *
 * @package    Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once WPS_WGC_DIRPATH . 'admin/partials/template_settings_function/class-woocommerce-giftcard-admin-settings.php';
$settings_obj = new Woocommerce_Giftcard_Admin_Settings();
$wps_wgm_discount_settings = array(
	array(
		'title' => __( 'Enable Discount on Gift Card Products', 'giftware' ),
		'id' => 'wps_wgm_discount_enable',
		'type' => 'checkbox',
		'class' => 'input-text',
		'desc_tip' => __( 'Check this box to enable Discount for Gift card Products', 'giftware' ),
		'desc' => __( 'Enable Discount on Gift card Products', 'giftware' ),
	),
	array(
		'title' => __( 'Select Discount Type', 'giftware' ),
		'id' => 'wps_wgm_discount_type',
		'class' => 'wps_wgm_new_woo_ver_style_select',
		'type' => 'singleSelectDropDown',
		'desc_tip' => __( 'Choose the Discount Type for Gift Card Products', 'giftware' ),
		'custom_attribute' => array( 'Fixed', 'Percentage' ),
	),
	array(
		'type' => 'DiscountBox',
	),
);
$wps_wgm_discount_settings = apply_filters( 'wps_wgm_discount_settings', $wps_wgm_discount_settings );
