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
$wps_uwgc_notification_settings = array(
	array(
		'title' => __( 'Enable PDF Link', 'giftware' ),
		'id' => 'wps_wgm_share_pdf_link',
		'type' => 'checkbox',
		'class' => 'input-text',
		'desc_tip' => __( 'Check this box to enable pdf link sharing', 'giftware' ),
		'desc' => __( 'Enable PDF Link Sharing ( First you have to enable pdf feature in other setting tab )', 'giftware' ),
	),
	array(
		'title' => __( 'Enable Whatsapp Sharing', 'giftware' ),
		'id' => 'wps_wgm_share_on_whatsapp',
		'type' => 'checkbox',
		'class' => 'input-text',
		'desc_tip' => __( 'Check this box to enable WhatsApp sharing', 'giftware' ),
		'desc' => __( 'Enable Whatsapp Sharing Notification', 'giftware' ),
	),
	array(
		'title' => __( 'Message Content', 'giftware' ),
		'id' => 'wps_wgm_whatsapp_message',
		'class' => 'input-text',
		'type' => 'textarea',
		'custom_attribute' => array(
			'rows' => '"7"',
		),
		'default' => __(
			'Hello [TO],
[MESSAGE] 
You have received a gift card from  [FROM]
Coupon code : [COUPONCODE]
Amount : [AMOUNT]
Expiry Date : [EXPIRYDATE]',
			'giftware'
		),
		'desc_tip' => __( 'Write the message you want to send to the user', 'giftware' ),
		'desc2' => __( 'Use [TO],[FROM],[MESSAGE],[COUPONCODE],[AMOUNT],[EXPIRYDATE] shortcodes to be placed dynamically.', 'giftware' ),
	),
	array(
		'title' => __( 'Enable SMS Notification', 'giftware' ),
		'id' => 'wps_wgm_enable_sms_notification',
		'type' => 'checkbox',
		'class' => 'input-text',
		'desc_tip' => __( 'Check this box to enable SMS Notification', 'giftware' ),
		'desc' => __( 'Enable SMS Notification on Phone', 'giftware' ),
	),
	array(
		'type' => 'TwilioDetailBox',
	),
);
$wps_uwgc_notification_settings = apply_filters( 'wps_uwgc_notification_settings', $wps_uwgc_notification_settings );
