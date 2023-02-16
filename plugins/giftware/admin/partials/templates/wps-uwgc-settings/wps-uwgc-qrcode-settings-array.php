<?php
/**
 * Exit if accessed directly
 *
 * @package Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once WPS_WGC_DIRPATH . 'admin/partials/template_settings_function/class-woocommerce-giftcard-admin-settings.php';
$settings_obj = new Woocommerce_Giftcard_Admin_Settings();
$wps_uwgc_qrcode_settings = array(
	array(
		'title' => __( 'Enable QRCode', 'giftware' ),
		'id' => 'wps_wgm_qrcode_setting_enable',
		'name' => 'wps_wgm_qrcode_enable',
		'type' => 'radio',
		'value' => 'qrcode',
		'default_value' => 0,
		'class' => 'input-text',
		'desc_tip' => __( 'Check this box to enable QRCode. QRCode will be displayed instead of coupon Code', 'giftware' ),
		'desc' => __( 'Enable QRCode to display in Email Template', 'giftware' ),
	),
	array(
		'title' => __( 'Select ECC Level', 'giftware' ),
		'id' => 'wps_wgm_qrcode_ecc_level',
		'class' => 'wps_wgm_new_woo_ver_style_select',
		'type' => 'singleSelectDropDownWithKeyvalue',
		'desc_tip' => __( 'ECC (Error Correction Capability) level. This compensates for dirt, damage or fuzziness of the barcode.', 'giftware' ),
		'custom_attribute' => array(
			array(
				'id' => 'L',
				'name' => 'L-Smallest',
			),
			array(
				'id' => 'M',
				'name' => 'M',
			),
			array(
				'id' => 'Q',
				'name' => 'Q',
			),
			array(
				'id' => 'H',
				'name' => 'H-Best',
			),
		),
	),
	array(
		'title' => __( 'Size of QR Code', 'giftware' ),
		'id' => 'wps_wgm_qrcode_size',
		'type' => 'number',
		'default' => 3,
		'custom_attribute' => array( 'min' => '"1"' ),
		'class' => 'input-text wps_wgm_new_woo_ver_style_text',
		'desc_tip' => __( 'It is the Size of QR Code', 'giftware' ),
	),
	array(
		'title' => __( 'Margin of QR Code', 'giftware' ),
		'id' => 'wps_wgm_qrcode_margin',
		'type' => 'number',
		'default' => 4,
		'custom_attribute' => array( 'min' => '"1"' ),
		'class' => 'input-text wps_wgm_new_woo_ver_style_text',
		'desc_tip' => __( 'It is the Margin of QR Code', 'giftware' ),
	),
	array(
		'title' => __( 'Enable Barcode', 'giftware' ),
		'id' => 'wps_wgm_barcode_enable',
		'name' => 'wps_wgm_qrcode_enable',
		'type' => 'radio',
		'value' => 'barcode',
		'default_value' => 0,
		'class' => 'input-text',
		'desc_tip' => __( 'Check this box to enable Barcode. A QR code will be displayed instead of a coupon code.', 'giftware' ),
		'desc' => __( 'Enable Barcode to display in Email Template', 'giftware' ),
	),
	array(
		'title' => __( 'Display Code', 'giftware' ),
		'id' => 'wps_wgm_barcode_display_enable',
		'type' => 'checkbox',
		'class' => 'input-text',
		'desc_tip' => __( 'Check this box to display Coupon Code below Barcode.', 'giftware' ),
		'desc' => __( 'Enable this to display Coupon Code', 'giftware' ),
	),
	array(
		'title' => __( 'Select Code type', 'giftware' ),
		'id' => 'wps_wgm_barcode_codetype',
		'class' => 'wps_wgm_new_woo_ver_style_select',
		'type' => 'singleSelectDropDown',
		'desc_tip' => __( 'It is the Code Type of Barcode', 'giftware' ),
		'custom_attribute' => array( 'code39', 'code25', 'codabar', 'code128', 'code128a', 'code128b' ),
	),
	array(
		'title' => __( 'Size of bar Code', 'giftware' ),
		'id' => 'wps_wgm_barcode_size',
		'type' => 'number',
		'default' => 40,
		'custom_attribute' => array( 'min' => '"1"' ),
		'class' => 'input-text wps_wgm_new_woo_ver_style_text',
		'desc_tip' => __( 'It is the Size of Barcode', 'giftware' ),
	),
);
$wps_uwgc_qrcode_settings = apply_filters( 'wps_uwgc_qrcode_settings', $wps_uwgc_qrcode_settings );
