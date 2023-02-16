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
 $wps_wgm_other_setting = array(
	 array(
		 'title' => esc_html__( 'Disable Apply Coupon Fields', 'woo-gift-cards-lite' ),
		 'id' => 'wps_wgm_additional_apply_coupon_disable',
		 'type' => 'checkbox',
		 'class' => 'input-text',
		 'desc_tip' => esc_html__( 'Check this if you want to disable Apply Coupon Fields if there only Gift Card Products are in Cart/Checkout Page', 'woo-gift-cards-lite' ),
		 'desc' => esc_html__( 'Disable Apply Coupon Fields on Cart/Checkout page', 'woo-gift-cards-lite' ),
	 ),
	 array(
		 'title' => esc_html__( 'Disable Preview Button', 'woo-gift-cards-lite' ),
		 'id' => 'wps_wgm_additional_preview_disable',
		 'type' => 'checkbox',
		 'class' => 'input-text',
		 'desc_tip' => esc_html__( 'Check this if you want to disable Preview Button At Front End', 'woo-gift-cards-lite' ),
		 'desc' => esc_html__( 'Disable Preview Button At Front End', 'woo-gift-cards-lite' ),
	 ),
 );
 $wps_wgm_other_setting = apply_filters( 'wps_wgm_other_setting', $wps_wgm_other_setting );
