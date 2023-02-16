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
$wps_wgm_delivery_settings = array(
	array(
		'title'         => esc_html__( 'Enable Email To Recipient', 'woo-gift-cards-lite' ),
		'id'            => 'wps_wgm_email_to_recipient_setting_enable',
		'type'          => 'radio',
		'class'         => 'wps_wgm_send_giftcard',
		'name'          => 'wps_wgm_send_giftcard',
		'value'         => 'Mail to recipient',
		'desc_tip'      => esc_html__( 'Check this box to enable normal functionality for sending mails to recipients on Gift Card Products.', 'woo-gift-cards-lite' ),
		'desc'          => esc_html__( 'Enable Email To Recipient.', 'woo-gift-cards-lite' ),
		'default_value' => 1,
	),
	array(
		'title'         => esc_html__( 'Enable Downloadable', 'woo-gift-cards-lite' ),
		'id'            => 'wps_wgm_downladable_setting_enable',
		'type'          => 'radio',
		'name'          => 'wps_wgm_send_giftcard',
		'class'         => 'wps_wgm_send_giftcard',
		'value'         => 'Downloadable',
		'desc_tip'      => esc_html__( 'Check this box to enable the downloadable feature for  Gift Card Products.', 'woo-gift-cards-lite' ),
		'desc'          => esc_html__( 'Enable Downloadable feature', 'woo-gift-cards-lite' ),
		'default_value' => 0,
	),
);
 $wps_wgm_delivery_settings = apply_filters( 'wps_wgm_delivery_settings', $wps_wgm_delivery_settings );
