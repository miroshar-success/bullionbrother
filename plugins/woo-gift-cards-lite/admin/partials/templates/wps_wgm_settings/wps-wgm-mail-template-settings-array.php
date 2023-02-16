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
 $wps_wgm_mail_template_settings = array(
	 'top' => array(
		 array(
			 'title' => esc_html__( 'Upload Default Logo', 'woo-gift-cards-lite' ),
			 'id' => 'wps_wgm_mail_setting_upload_logo',
			 'type' => 'textWithButton',
			 'custom_attribute' => array(
				 array(
					 'type' => 'text',
					 'custom_attributes' => array( 'readonly' => 'readonly' ),
					 'class' => 'wps_wgm_mail_setting_upload_logo_value wps_wgm_new_woo_ver_style_text',
					 'id' => 'wps_wgm_mail_setting_upload_logo',
				 ),
				 array(
					 'type' => 'button',
					 'value' => esc_html__( 'Upload Logo', 'woo-gift-cards-lite' ),
					 'class' => 'wps_wgm_mail_setting_upload_logo button',
				 ),
				 array(
					 'type' => 'paragraph',
					 'id' => 'wps_wgm_mail_setting_remove_logo',
					 'imgId' => 'wps_wgm_mail_setting_upload_image',
					 'spanX' => 'wps_wgm_mail_setting_remove_logo_span',
				 ),
			 ),
			 'class' => 'wps_wgm_mail_setting_upload_logo_value wps_wgm_new_woo_ver_style_text',
			 'desc_tip' => esc_html__( 'Upload the image which is used as a logo on your Email Template.', 'woo-gift-cards-lite' ),
		 ),
		 array(
			 'title' => esc_html__( 'Logo Height (in "px")', 'woo-gift-cards-lite' ),
			 'id' => 'wps_wgm_mail_setting_upload_logo_dimension_height',
			 'type' => 'number',
			 'custom_attribute' => array( 'min' => '0' ),
			 'default' => 70,
			 'class' => 'wps_wgm_new_woo_ver_style_text',
			 'desc_tip' => esc_html__( 'Set the height of the logo in the email template.', 'woo-gift-cards-lite' ),
		 ),
		 array(
			 'title' => esc_html__( 'Logo Width (in "px")', 'woo-gift-cards-lite' ),
			 'id' => 'wps_wgm_mail_setting_upload_logo_dimension_width',
			 'type' => 'number',
			 'custom_attribute' => array( 'min' => '0' ),
			 'default' => 70,
			 'class' => 'wps_wgm_new_woo_ver_style_text',
			 'desc_tip' => esc_html__( 'Set the width of the logo in the email template.', 'woo-gift-cards-lite' ),
		 ),
		 array(
			 'title' => esc_html__( 'Email Default Event Image', 'woo-gift-cards-lite' ),
			 'id' => 'wps_wgm_mail_setting_background_logo',
			 'type' => 'textWithButton',
			 'desc_tip' => esc_html__( 'Upload an image which is used as a default Event/Occasion in Email Template.', 'woo-gift-cards-lite' ),
			 'custom_attribute' => array(
				 array(
					 'type' => 'text',
					 'custom_attributes' => array( 'readonly' => 'readonly' ),
					 'class' => 'wps_wgm_mail_setting_background_logo_value',
					 'id' => 'wps_wgm_mail_setting_background_logo_value',
				 ),
				 array(
					 'type' => 'button',
					 'value' => esc_html__( 'Upload Image', 'woo-gift-cards-lite' ),
					 'class' => 'wps_wgm_mail_setting_background_logo button',
				 ),
				 array(
					 'type' => 'paragraph',
					 'id' => 'wps_wgm_mail_setting_remove_background',
					 'imgId' => 'wps_wgm_mail_setting_background_logo_image',
					 'spanX' => 'wps_wgm_mail_setting_remove_background_span',
				 ),
			 ),
		 ),
		 array(
			 'title' => esc_html__( 'Gift Card Message Length', 'woo-gift-cards-lite' ),
			 'id' => 'wps_wgm_mail_setting_giftcard_message_length',
			 'type' => 'number',
			 'default' => 300,
			 'class' => 'input-text wps_wgm_new_woo_ver_style_text',
			 'custom_attribute' => array( 'min' => 0 ),
			 'desc_tip' => esc_html__( 'Enter the Gift Card Message length, used to limit the number of characters entered by the customers.', 'woo-gift-cards-lite' ),

		 ),
		 array(
			 'title'    => esc_html__( 'Default Gift Card Message', 'woo-gift-cards-lite' ),
			 'id'       => 'wps_wgm_mail_setting_default_message',
			 'type'     => 'text',
			 'desc_tip' => esc_html__( 'Set the Default Message for Gift Card.', 'woo-gift-cards-lite' ),
		 ),
		 array(
			 'title' => esc_html__( 'Disclaimer Text', 'woo-gift-cards-lite' ),
			 'id' => 'wps_wgm_mail_setting_disclaimer',
			 'type' => 'wp_editor',
			 'desc_tip' => esc_html__( 'Set the Disclaimer Text for Email Template.', 'woo-gift-cards-lite' ),
		 ),
	 ),
	 'middle' => array(
		 array(
			 'title' => esc_html__( 'Gift Card Email Subject', 'woo-gift-cards-lite' ),
			 'id' => 'wps_wgm_mail_setting_giftcard_subject',
			 'type' => 'textWithDesc',
			 'class' => 'description',
			 'desc_tip' => esc_html__( 'Email Subject for notifying receiver about Gift Card Mail send.', 'woo-gift-cards-lite' ),
			 'bottom_desc' => esc_html__( 'Use [SITENAME] shortcode as the name of the site and [FROM] shortcode as buyer name to be placed dynamically.', 'woo-gift-cards-lite' ),
		 ),
	 ),
 );
 $wps_wgm_mail_template_settings = apply_filters( 'wps_wgm_mail_template_settings', $wps_wgm_mail_template_settings );
