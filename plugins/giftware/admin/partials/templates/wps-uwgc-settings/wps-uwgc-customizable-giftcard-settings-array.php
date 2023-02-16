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
$wps_uwgc_customizable_settings = array(
	array(
		'title'    => __( 'Enable', 'giftware' ),
		'id'       => 'wps_wgm_customizable_enable',
		'type'     => 'checkbox',
		'class'    => 'input-text',
		'desc_tip' => __( 'Check this box to enable Woocommerce Customizable Gift Card', 'giftware' ),
		'desc'     => __( 'Enable Customizable Gift Card', 'giftware' ),
	),
	array(
		'title'         => __( 'Allow default template images', 'giftware' ),
		'id'            => 'wps_wgm_default_enable',
		'type'          => 'radio',
		'class'         => 'input-text',
		'name'          => 'wps_wgm_image_enable',
		'value'         => 'default_img',
		'desc_tip'      => __( 'Check this box to enable normal functionality for sending mails to recipients on Gift Card Products.', 'giftware' ),
		'desc'          => __( 'Check this box to allow default template images.', 'giftware' ),
		'default_value' => 1,
	),
	array(
		'title'         => __( 'Allow uploaded template images', 'giftware' ),
		'id'            => 'wps_wgm_upload_enable',
		'type'          => 'radio',
		'name'          => 'wps_wgm_image_enable',
		'class'         => 'input-text',
		'value'         => 'upload_img',
		'desc_tip'      => __( 'Check this box to upload images.', 'giftware' ),
		'desc'          => __( 'Check this box to upload images.', 'giftware' ),
		'default_value' => 0,
	),
	array(
		'title'         => __( 'Allow Uploaded images and retain default images', 'giftware' ),
		'id'            => 'wps_wgm_default_and_upload_enable',
		'type'          => 'radio',
		'name'          => 'wps_wgm_image_enable',
		'class'         => 'input-text',
		'value'         => 'upload_and_default_img',
		'desc_tip'      => __( 'Allow Uploaded images and retain default images', 'giftware' ),
		'desc'          => __( 'Check this box to upload images and retain default images.', 'giftware' ),
		'default_value' => 0,
	),
	array(
		'title' => __( 'Upload images for email template', 'giftware' ),
		'id'    => 'wps_wgm_customize_email_template_image',
		'type'  => 'textWithButtonForMultipleUpload',
	),
	array(
		'title'            => __( 'Upload Default Gift Card image', 'giftware' ),
		'type'             => 'textWithButton',
		'bottom_desc'      => __( 'Note: Suggested Dimension is (600*400)', 'giftware' ),
		'custom_attribute' => array(
			array(
				'type'              => 'text',
				'custom_attributes' => array( 'readonly' => 'readonly' ),
				'id'                => 'wps_wgm_customize_default_giftcard',
			),
			array(
				'type'  => 'button',
				'value' => __( 'Upload Default Image', 'giftware' ),
				'class' => 'wps_wgm_customize_default_giftcard button',
			),
			array(
				'type'  => 'paragraph',
				'id'    => 'wps_wgm_customize_remove_giftcard_para',
				'imgId' => 'wps_wgm_custamize_upload_giftcard_image',
				'spanX' => 'wps_wgm_customize_remove_giftcard_span',
			),
		),
		'class'    => 'wps_wgm_custamize_upload_giftcard_image_value wps_wgm_new_woo_ver_style_text wps_ml-35',
		'desc_tip' => __( 'Upload the image which is used as default image on your Email Template.', 'giftware' ),
	),
	array(
		'title'       => __( 'Create another Gift Card', 'giftware' ),
		'id'          => 'wps_wgm_custom_giftcard',
		'type'        => 'button',
		'value'       => 'Create Gift Card',
		'class'       => 'wps_ml-35',
		'desc_tip'    => __( 'Check this box to upload images and retain default images.', 'giftware' ),
		'bottom_desc' => __( 'If you have deleted your customizable Gift Card, you can create another one!', 'giftware' ),
	),
	array(
		'title'    => __( 'Select Color for background', 'giftware' ),
		'id'       => 'wps_wgm_custom_giftcard_bg_color',
		'type'     => 'text',
		'desc_tip' => __( 'You can also choose the color for your background.', 'giftware' ),
		'class'    => 'my-color-field',
		'default'  => '#55b3a5',
	),
	array(
		'title'    => __( 'Select Color for middle section template', 'giftware' ),
		'id'       => 'wps_wgm_custom_giftcard_middle_color',
		'type'     => 'text',
		'desc_tip' => __( 'You can also choose the color for your middle section.', 'giftware' ),
		'class'    => 'my-color-field',
		'default'  => '#55b3a5',
	),
	array(
		'title'    => __( 'Select Color for disclaimer section template', 'giftware' ),
		'id'       => 'wps_wgm_custom_giftcard_desclaimer_color',
		'type'     => 'text',
		'desc_tip' => __( 'You can also choose the color for your disclaimer section.', 'giftware' ),
		'class'    => 'my-color-field',
		'default'  => '#55b3a5',
	),

);
$wps_uwgc_customizable_settings = apply_filters( 'wps_uwgc_customizable_settings', $wps_uwgc_customizable_settings );
