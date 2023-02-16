<?php
/**
 * Exit if accessed directly
 *
 * @package    Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Ultimate_Woocommerce_Gift_Cards_Activation' ) ) {
	/**
	 * This is class to restore the saved data on particular keys.
	 *
	 * @name    Ultimate_Woocommerce_Gift_Cards_Activation
	 * @category Class
	 * @author   WP Swings <webmaster@wpswings.com>
	 */
	class Ultimate_Woocommerce_Gift_Cards_Activation {
		/**
		 * This function is used to restore the overall functionality of plugin
		 *
		 * @name wps_wgm_restore_data
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_wgm_restore_data_pro() {

			$wps_general_settings = get_option( 'wps_wgm_general_settings', array() );
			if ( empty( $wps_general_settings ) ) {
				$this->restore_general_settings_data_pro();
			}
			$wps_product_settings = get_option( 'wps_wgm_product_settings', array() );
			if ( empty( $wps_product_settings ) ) {
				$this->restore_product_settings_data_pro();
			}
			$wps_mail_settings = get_option( 'wps_wgm_mail_settings', array() );
			if ( empty( $wps_mail_settings ) ) {
				$this->restore_mail_settings_data_pro();
			}
			$wps_delivery_settings = get_option( 'wps_wgm_delivery_settings', array() );
			if ( empty( $wps_delivery_settings ) ) {
				$this->restore_delivery_settings_data_pro();
			}
			$wps_other_settings = get_option( 'wps_wgm_other_settings', array() );
			if ( empty( $wps_other_settings ) ) {
				$this->restore_other_settings_data_pro();
			}
			$wps_discount_settings = get_option( 'wps_wgm_discount_settings', array() );
			if ( empty( $wps_discount_settings ) ) {
				$this->restore_discount_settings_data_pro();
			}
			$wps_thankyou_settings = get_option( 'wps_wgm_thankyou_order_settings', array() );
			if ( empty( $wps_thankyou_settings ) ) {
				$this->restore_thankyou_settings_data_pro();
			}
			$wps_qrcode_settings = get_option( 'wps_wgm_qrcode_settings', array() );
			if ( empty( $wps_qrcode_settings ) ) {
				$this->restore_qrcode_settings_data_pro();
			}
			$wps_wgm_customizable = get_option( 'wps_wgm_customizable_settings', array() );
			if ( empty( $wps_wgm_customizable ) ) {
				$this->restore_customizable_settings_data_pro();
			}
			$wps_wgm_notification = get_option( 'wps_wgm_notification_settings', array() );
			if ( empty( $wps_wgm_notification ) ) {
				$this->restore_notification_settings_data_pro();
			}
			$wps_wgm_coupon_meta = get_option( 'wps_wgm_coupons_changed_meta_name', false );
			if ( 'true' !== $wps_wgm_coupon_meta ) {
				$this->restore_giftcard_coupons_pro();
			}
			$wps_pro_product_restored = get_option( 'wps_pro_product_restored', 'no' );
			if ( 'no' === $wps_pro_product_restored ) {
				$this->wps_restore_giftcard_products_pro();
			}
			$wps_wgm_restore_other_options = get_option( 'wps_wgm_restore_other_options', false );
			if ( 'true' === $wps_wgm_restore_other_options ) {
				$this->wps_restore_other_options_pro();
			}
		}

		/**
		 * Function for general setting tab data
		 *
		 * @name restore_general_settings_data_pro
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function restore_general_settings_data_pro() {
			$giftcard_enable = get_option( 'wps_gw_general_setting_enable', false );
			$giftcard_tax_cal_enable = get_option( 'wps_gw_general_setting_tax_cal_enable', false );
			$giftcard_shop_page = get_option( 'wps_gw_general_setting_shop_page_enable', false );
			$giftcard_individual_use = get_option( 'wps_gw_general_setting_giftcard_individual_use', false );
			$giftcard_freeshipping = get_option( 'wps_gw_general_setting_giftcard_freeshipping', false );
			$giftcard_coupon_length = get_option( 'wps_gw_general_setting_giftcard_coupon_length', false );
			$giftcard_prefix = get_option( 'wps_gw_general_setting_giftcard_prefix', false );
			$giftcard_prefix_sanitize = preg_replace( '/\\\\/', '', $giftcard_prefix );
			$giftcard_prefix_sanitize = sanitize_text_field( $giftcard_prefix_sanitize );
			$giftcard_expiry = get_option( 'wps_gw_general_setting_giftcard_expiry', 0 );
			$giftcard_minspend = get_option( 'wps_gw_general_setting_giftcard_minspend', false );
			$giftcard_maxspend = get_option( 'wps_gw_general_setting_giftcard_maxspend', false );
			$giftcard_use = get_option( 'wps_gw_general_setting_giftcard_use', 0 );
			$giftcard_selected_date = get_option( 'wps_gw_general_setting_enable_selected_date', false );
			$selected_date = get_option( 'wps_gw_general_setting_enable_selected_format', false );
			$giftcard_payment_gateways = get_option( 'wps_gw_general_setting_giftcard_payment', array() );
			$gift_categ_enable = get_option( 'wps_gw_general_setting_categ_enable', false );

			$wps_wgm_general_settings = array(
				'wps_wgm_general_setting_enable' => $giftcard_enable,
				'wps_wgm_general_setting_tax_cal_enable' => $giftcard_tax_cal_enable,
				'wps_wgm_general_setting_shop_page_enable' => $giftcard_shop_page,
				'wps_wgm_general_setting_giftcard_individual_use' => $giftcard_individual_use,
				'wps_wgm_general_setting_giftcard_freeshipping' => $giftcard_freeshipping,
				'wps_wgm_general_setting_giftcard_coupon_length' => $giftcard_coupon_length,
				'wps_wgm_general_setting_giftcard_prefix' => $giftcard_prefix_sanitize,
				'wps_wgm_general_setting_giftcard_expiry' => $giftcard_expiry,
				'wps_wgm_general_setting_giftcard_minspend' => $giftcard_minspend,
				'wps_wgm_general_setting_giftcard_maxspend' => $giftcard_maxspend,
				'wps_wgm_general_setting_giftcard_use' => $giftcard_use,
				'wps_wgm_general_setting_enable_selected_date' => $giftcard_selected_date,
				'wps_wgm_general_setting_enable_selected_format' => $selected_date,
				'wps_wgm_general_setting_giftcard_payment' => $giftcard_payment_gateways,
				'wps_wgm_general_setting_categ_enable' => $gift_categ_enable,
			);
			update_option( 'wps_wgm_general_settings', $wps_wgm_general_settings );
		}

		/**
		 * Function for Product setting tab data
		 *
		 * @name restore_product_settings_data_pro
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function restore_product_settings_data_pro() {
			$giftcard_exclude_product = get_option( 'wps_gw_product_setting_exclude_product', array() );
			$giftcard_exclude_category = get_option( 'wps_gw_product_setting_exclude_category', array() );
			$giftcard_ex_sale = get_option( 'wps_gw_general_setting_giftcard_ex_sale', false );

			$wps_wgm_product_settings = array(
				'wps_wgm_product_setting_giftcard_ex_sale' => $giftcard_ex_sale,
				'wps_wgm_product_setting_exclude_product' => $giftcard_exclude_product,
				'wps_wgm_product_setting_exclude_category' => $giftcard_exclude_category,
			);
			update_option( 'wps_wgm_product_settings', $wps_wgm_product_settings );
		}

		/**
		 * Function for Email setting tab data.
		 *
		 * @name restore_mail_settings_data_pro.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function restore_mail_settings_data_pro() {
			$wps_wgm_other_setting_upload_logo = get_option( 'wps_gw_other_setting_upload_logo', false );
			$giftcard_giftcard_subject = get_option( 'wps_gw_other_setting_giftcard_subject', false );
			$giftcard_giftcard_subject = stripcslashes( $giftcard_giftcard_subject );
			$giftcard_receive_subject = get_option( 'wps_gw_other_setting_receive_subject', false );
			$giftcard_receive_subject = stripcslashes( $giftcard_receive_subject );
			$giftcard_receive_message = get_option( 'wps_gw_other_setting_receive_message', false );
			$giftcard_down_subject = get_option( 'wps_gw_other_setting_giftcard_subject_downloadable', false );
			$giftcard_ship_subject = get_option( 'wps_gw_other_setting_giftcard_subject_shipping', '' );
			$giftcard_coupon_subject = get_option( 'wps_gw_other_setting_receive_coupon_subject', false );
			$giftcard_coupon_subject = stripcslashes( $giftcard_coupon_subject );
			$giftcard_receive_coupon_message = get_option( 'wps_gw_other_setting_receive_coupon_message', false );
			$giftcard_logo_height = get_option( 'wps_gw_other_setting_logo_height', false );
			$giftcard_logo_width = get_option( 'wps_gw_other_setting_logo_width', false );
			$giftcard_background = get_option( 'wps_gw_other_setting_background_logo', false );
			$giftcard_message_length = trim( get_option( 'wps_gw_other_setting_giftcard_message_length', 300 ) );
			$giftcard_disclaimer = get_option( 'wps_gw_other_setting_disclaimer', false );
			$wps_wgm_mail_settings = array(
				'wps_wgm_mail_setting_upload_logo' => $wps_wgm_other_setting_upload_logo,
				'wps_wgm_mail_setting_giftcard_subject' => $giftcard_giftcard_subject,
				'wps_wgm_mail_setting_upload_logo_dimension_height' => $giftcard_logo_height,
				'wps_wgm_mail_setting_upload_logo_dimension_width' => $giftcard_logo_width,
				'wps_wgm_mail_setting_background_logo_value' => $giftcard_background,
				'wps_wgm_mail_setting_giftcard_message_length' => $giftcard_message_length,
				'wps_wgm_mail_setting_disclaimer' => $giftcard_disclaimer,
				'wps_wgm_mail_setting_receive_subject' => $giftcard_receive_subject,
				'wps_wgm_mail_setting_receive_message' => $giftcard_receive_message,
				'wps_wgm_mail_setting_giftcard_subject_downloadable' => $giftcard_down_subject,
				'wps_wgm_mail_setting_giftcard_subject_shipping' => $giftcard_ship_subject,
				'wps_wgm_mail_setting_receive_coupon_subject' => $giftcard_coupon_subject,
				'wps_wgm_mail_setting_receive_coupon_message' => $giftcard_receive_coupon_message,
			);
			update_option( 'wps_wgm_mail_settings', $wps_wgm_mail_settings );
		}

		/**
		 * Function for Delivery setting tab data.
		 *
		 * @name restore_delivery_settings_data_pro
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function restore_delivery_settings_data_pro() {
			$gift_cart_ship = get_option( 'wps_gw_general_cart_shipping_enable', false );
			$wps_gw_method_enable = get_option( 'wps_gw_send_giftcard', 'normal_mail' );
			$wps_gw_customer_selection = get_option( 'wps_gw_customer_selection', false );
			if ( 'normal_mail' == $wps_gw_method_enable ) {
				$wps_gw_method_enable = 'Mail to recipient';
			}
			if ( 'download' == $wps_gw_method_enable ) {
				$wps_gw_method_enable = 'Downloadable';
			}
			if ( 'customer_choose' == $wps_gw_method_enable ) {
				if ( ! empty( $wps_gw_customer_selection ) ) {
					$wps_gw_email_to_recipient = isset( $wps_gw_customer_selection['Email_to_recipient'] ) ? $wps_gw_customer_selection['Email_to_recipient'] : 0;
					$wps_gw_shipping = isset( $wps_gw_customer_selection['Shipping'] ) ? $wps_gw_customer_selection['Shipping'] : 0;
					$wps_gw_downloadable = isset( $wps_gw_customer_selection['Downloadable'] ) ? $wps_gw_customer_selection['Downloadable'] : 0;

					$wps_gw_customer_selection = array(
						'Email_to_recipient' => $wps_gw_email_to_recipient,
						'Downloadable' => $wps_gw_downloadable,
						'Shipping' => $wps_gw_shipping,
					);
				}
			}
			$wps_wgm_delivery_settings = array(
				'wps_wgm_send_giftcard' => $wps_gw_method_enable,
				'wps_wgm_customer_select_setting_enable' => $wps_gw_customer_selection,
				'wps_wgm_general_cart_shipping_enable' => $gift_cart_ship,
			);
			update_option( 'wps_wgm_delivery_settings', $wps_wgm_delivery_settings );
		}

		/**
		 * Function for Other setting tab data.
		 *
		 * @name restore_other_settings_data_pro.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function restore_other_settings_data_pro() {
			$wps_gw_disable_buyer_notification = get_option( 'wps_gw_disable_buyer_notification', 'off' );
			$wps_gw_change_admin_email_for_shipping = get_option( 'wps_gw_change_admin_email_for_shipping', '' );
			$wps_wgm_apply_coupon_disable = get_option( 'wps_gw_additional_apply_coupon_disable', false );
			$wps_wgc_bcc_enable = get_option( 'wps_gw_addition_bcc_option_enable', false );
			$wps_gw_resend_disable = get_option( 'wps_gw_additional_resend_disable', false );
			$wps_gw_quantity_disable = get_option( 'wps_gw_additional_quantity_disable', false );
			$wps_gw_sendtoday_disable = get_option( 'wps_gw_additional_sendtoday_disable', false );
			$wps_gw_pdf_enable = get_option( 'wps_gw_addition_pdf_enable', false );
			$wps_gw_pdf_template_size = get_option( 'wps_gw_pdf_template_size', 'A3' );
			$browse_enable = get_option( 'wps_gw_other_setting_browse', false );
			$wps_gw_remove_validation_to = get_option( 'wps_gw_remove_validation_to', false );
			$wps_gw_remove_validation_to_name = get_option( 'wps_gw_remove_validation_to_name', 'on' );
			$wps_gw_remove_validation_from = get_option( 'wps_gw_remove_validation_from', false );
			$wps_gw_remove_validation_msg = get_option( 'wps_gw_remove_validation_msg', false );
			$wps_gw_manually_increment_usage = get_option( 'wps_gw_manually_increment_usage', false );
			$wps_gw_render_product_custom_page = get_option( 'wps_gw_render_product_custom_page', 'off' );
			$wps_gw_hide_giftcard_notice = get_option( 'wps_gw_hide_giftcard_notice', 'off' );
			$wps_gw_hide_giftcard_thumbnail = get_option( 'wps_gw_wps_gw_hide_giftcard_thumbnail', 'off' );
			$wps_gw_selected_custom_page = get_option( 'wps_gw_custom_page_selection', array() );
			$wps_gw_preview_disable = get_option( 'wps_gw_additional_preview_disable', false );
			$wps_wgm_other_settings = array(
				'wps_wgm_additional_apply_coupon_disable' => $wps_wgm_apply_coupon_disable,
				'wps_wgm_additional_preview_disable' => $wps_gw_preview_disable,
				'wps_wgm_addition_bcc_option_enable' => $wps_wgc_bcc_enable,
				'wps_wgm_additional_resend_disable' => $wps_gw_resend_disable,
				'wps_wgm_additional_quantity_disable' => $wps_gw_quantity_disable,
				'wps_wgm_additional_sendtoday_disable' => $wps_gw_sendtoday_disable,
				'wps_wgm_addition_pdf_enable' => $wps_gw_pdf_enable,
				'wps_wgm_pdf_template_size' => $wps_gw_pdf_template_size,
				'wps_wgm_other_setting_browse' => $browse_enable,
				'wps_wgm_remove_validation_to' => $wps_gw_remove_validation_to,
				'wps_wgm_remove_validation_to_name' => $wps_gw_remove_validation_to_name,
				'wps_wgm_remove_validation_from' => $wps_gw_remove_validation_from,
				'wps_wgm_remove_validation_msg' => $wps_gw_remove_validation_msg,
				'wps_wgm_manually_increment_usage' => $wps_gw_manually_increment_usage,
				'wps_wgm_render_product_custom_page' => $wps_gw_render_product_custom_page,
				'wps_wgm_hide_giftcard_notice' => $wps_gw_hide_giftcard_notice,
				'wps_wgm_hide_giftcard_thumbnail' => $wps_gw_hide_giftcard_thumbnail,
				'wps_wgm_disable_buyer_notification' => $wps_gw_disable_buyer_notification,
				'wps_wgm_change_admin_email_for_shipping' => $wps_gw_change_admin_email_for_shipping,
				'wps_wgm_custom_page_selection' => $wps_gw_selected_custom_page,
			);
			update_option( 'wps_wgm_other_settings', $wps_wgm_other_settings );
		}

		/**
		 * Function for discount setting tab data.
		 *
		 * @name restore_discount_settings_data_pro.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function restore_discount_settings_data_pro() {
			$discount_enable = get_option( 'wps_gw_discount_enable', false );
			$discount_type = get_option( 'wps_gw_discount_type', 'Fixed' );
			if ( isset( $discount_type ) && ! empty( $discount_type ) ) {
				if ( 'wps_gw_fixed' === $discount_type ) {
					$discount_type = 'Fixed';
				} elseif ( 'wps_gw_percentage' === $discount_type ) {
					$discount_type = 'Percentage';
				}
			}
			$discount_min = get_option( 'wps_gw_discount_minimum', array() );
			$discount_max = get_option( 'wps_gw_discount_maximum', array() );
			$discount_value = get_option( 'wps_gw_discount_current_type', array() );

			$wps_wgm_discount_settings = array(
				'wps_wgm_discount_enable' => $discount_enable,
				'wps_wgm_discount_type' => $discount_type,
				'wps_wgm_discount_minimum' => $discount_min,
				'wps_wgm_discount_maximum' => $discount_max,
				'wps_wgm_discount_current_type' => $discount_value,
			);
			update_option( 'wps_wgm_discount_settings', $wps_wgm_discount_settings );
		}

		/**
		 * Function for thankyou setting tab data.
		 *
		 * @name restore_thankyou_settings_data_pro
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function restore_thankyou_settings_data_pro() {

			$thankyouorder_enable = get_option( 'wps_gw_thankyouorder_enable', false );
			$thankyouorder_type = get_option( 'wps_gw_thankyouorder_type', 'wps_wgm_fixed_thankyou' );
			if ( isset( $thankyouorder_type ) && ! empty( $thankyouorder_type ) ) {
				if ( 'wps_gw_fixed_thankyou' === $thankyouorder_type ) {
					$thankyouorder_type = 'wps_wgm_fixed_thankyou';
				} elseif ( 'wps_gw_percentage_thankyou' === $thankyouorder_type ) {
					$thankyouorder_type = 'wps_wgm_percentage_thankyou';
				}
			}
			$thankyouorder_time = get_option( 'wps_gw_thankyouorder_time', 'wps_wgm_order_completed' );
			if ( isset( $thankyouorder_time ) && ! empty( $thankyouorder_time ) ) {
				if ( 'wps_gw_order_creation' === $thankyouorder_time ) {
					$thankyouorder_time = 'wps_wgm_order_creation';
				} elseif ( 'wps_gw_processing_status' === $thankyouorder_time ) {
					$thankyouorder_time = 'wps_wgm_order_processing';
				} elseif ( 'wps_gw_complete_status' === $thankyouorder_time ) {
					$thankyouorder_time = 'wps_wgm_order_completed';
				}
			}

			$thankyouorder_min = get_option( 'wps_gw_thankyouorder_minimum', array() );
			$thankyouorder_max = get_option( 'wps_gw_thankyouorder_maximum', array() );
			$thankyouorder_value = get_option( 'wps_gw_thankyouorder_current_type', array() );
			$thankyouorder_number = get_option( 'wps_gw_thankyouorder_number', 1 );
			$thnku_giftcard_expiry = get_option( 'wps_gw_thnku_giftcard_expiry', 0 );
			$wps_gw_thankyou_message = get_option( 'wps_gw_thankyou_message', 'You have received a coupon [COUPONCODE], having amount of [COUPONAMOUNT] with the expiration date of [COUPONEXPIRY]' );
			if ( empty( $thankyouorder_number ) ) {
				$thankyouorder_number = 1;
			}
			$wps_wgm_thankyou_settings = array(
				'wps_wgm_thankyouorder_enable' => $thankyouorder_enable,
				'wps_wgm_thankyouorder_type' => $thankyouorder_type,
				'wps_wgm_thankyouorder_time' => $thankyouorder_time,
				'wps_wgm_thankyouorder_number' => $thankyouorder_number,
				'wps_wgm_thnku_giftcard_expiry' => $thnku_giftcard_expiry,
				'wps_wgm_thankyou_message' => $wps_gw_thankyou_message,
				'wps_wgm_thankyouorder_minimum' => $thankyouorder_min,
				'wps_wgm_thankyouorder_maximum' => $thankyouorder_max,
				'wps_wgm_thankyouorder_current_type' => $thankyouorder_value,
			);
			update_option( 'wps_wgm_thankyou_order_settings', $wps_wgm_thankyou_settings );
		}

		/**
		 * Function for qrcode settings.
		 *
		 * @name restore_qrcode_settings_data_pro.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function restore_qrcode_settings_data_pro() {

			$qrcode_enable = get_option( 'wps_gw_qrcode_enable', false );
			$qrcode_level = get_option( 'wps_gw_qrcode_ecc_level', 'L' );
			$qrcode_size = get_option( 'wps_gw_qrcode_size', 3 );
			$qrcode_margin = get_option( 'wps_gw_qrcode_margin', 4 );
			$barcode_display = get_option( 'wps_gw_barcode_display_enable', false );
			$barcode_type = get_option( 'wps_gw_barcode_codetype', 'code39' );
			$barcode_size = get_option( 'wps_gw_barcode_size', 40 );
			$wps_wgm_qrcode_settings = array(
				'wps_wgm_qrcode_enable' => $qrcode_enable,
				'wps_wgm_qrcode_ecc_level' => $qrcode_level,
				'wps_wgm_qrcode_size' => $qrcode_size,
				'wps_wgm_qrcode_margin' => $qrcode_margin,
				'wps_wgm_barcode_display_enable' => $barcode_display,
				'wps_wgm_barcode_codetype' => $barcode_type,
				'wps_wgm_barcode_size' => $barcode_size,
			);
			update_option( 'wps_wgm_qrcode_settings', $wps_wgm_qrcode_settings );
		}

		/**
		 * Function for customizable setting tab data.
		 *
		 * @name restore_customizable_settings_data_pro.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function restore_customizable_settings_data_pro() {
			$wcgw_plugin_enable = get_option( 'wcgw_plugin_enable', false );
			$wcgw_image_enable = get_option( 'wcgw_image_enable', false );
			$wps_gw_customize_email_template_image = get_option( 'wps_gw_customize_email_template_image', false );
			$wps_gw_customize_default_giftcard = get_option( 'wps_gw_customize_default_giftcard', false );
			$wps_wgm_customizable_settings = array(
				'wps_wgm_customizable_enable' => $wcgw_plugin_enable,
				'wps_wgm_image_enable' => $wcgw_image_enable,
				'wps_wgm_customize_email_template_image' => $wps_gw_customize_email_template_image,
				'wps_wgm_customize_default_giftcard' => $wps_gw_customize_default_giftcard,
			);
			update_option( 'wps_wgm_customizable_settings', $wps_wgm_customizable_settings );
		}


		/**
		 * Function for notification setting tab data.
		 *
		 * @name restore_notification_settings_data_pro
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function restore_notification_settings_data_pro() {
			$wps_gw_enable_sms_notification = get_option( 'wps_gw_enable_sms_notification', false );
			$wps_gw_account_sid = get_option( 'wps_gw_account_sid', false );
			$wps_gw_auth_token = get_option( 'wps_gw_auth_token', false );
			$wps_gw_twilio_number = get_option( 'wps_gw_twilio_number', false );
			$wps_gw_pdf_link = get_option( 'wps_wgm_share_pdf_link', false );
			$wps_share_on_whatsapp = get_option( 'wps_gw_share_on_whatsapp', false );
			$wps_gw_whatsapp_message = get_option( 'wps_gw_whatsapp_message', false );
			if ( '' == $wps_gw_whatsapp_message ) {
				$wps_gw_whatsapp_message = __(
					'Hello [TO],
					[MESSAGE] 
					You have received a gift card from  [FROM]
					Coupon code : [COUPONCODE]
					Amount : [AMOUNT]
					Expiry Date : [EXPIRYDATE]',
					'giftware'
				);
				$wps_gw_whatsapp_message = preg_replace( '/\s*$^\s*/m', "\n", $wps_gw_whatsapp_message );
				$wps_gw_whatsapp_message = preg_replace( '/[ \t]+/', ' ', $wps_gw_whatsapp_message );
			}
			$wps_wgm_notification_settings = array(
				'wps_wgm_share_pdf_link' => $wps_gw_pdf_link,
				'wps_wgm_share_on_whatsapp' => $wps_share_on_whatsapp,
				'wps_wgm_whatsapp_message' => $wps_gw_whatsapp_message,
				'wps_wgm_enable_sms_notification' => $wps_gw_enable_sms_notification,
				'wps_wgm_account_sid' => $wps_gw_account_sid,
				'wps_wgm_auth_token' => $wps_gw_auth_token,
				'wps_wgm_twilio_number' => $wps_gw_twilio_number,

			);
			update_option( 'wps_wgm_notification_settings', $wps_wgm_notification_settings );
		}

		/**
		 * Function for restoring giftcard coupon data.
		 *
		 * @name restore_giftcard_coupons_pro
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function restore_giftcard_coupons_pro() {
			$args = array(
				'posts_per_page'   => -1,
				'orderby'          => 'title',
				'order'            => 'asc',
				'post_type'        => 'shop_coupon',
				'post_status'      => 'publish',
			);

			$coupons = get_posts( $args );
			if ( null !== $coupons ) {
				foreach ( $coupons as $post_key ) {
					$coupons_code = $post_key->post_title;
					if ( isset( $coupons_code ) && ! empty( $coupons_code ) ) {
						$the_coupon = new WC_Coupon( $coupons_code );
						$coupon_id = $the_coupon->get_id();
						if ( '' !== $coupon_id && 0 !== $coupon_id ) {
							$offline_coupon_id = get_post_meta( $coupon_id, 'wps_gw_giftcard_coupon', true );
							if ( '' !== $offline_coupon_id ) {
								update_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon', $offline_coupon_id );
							}
							$coupon_type = get_post_meta( $coupon_id, 'wps_gw_giftcard_coupon_unique', true );
							if ( '' !== $coupon_type ) {
								update_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_unique', $coupon_type );
							}
							$user_email = get_post_meta( $coupon_id, 'wps_gw_giftcard_coupon_mail_to', true );
							if ( '' !== $user_email ) {
								update_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_mail_to', $user_email );
							}
							$product_id = get_post_meta( $coupon_id, 'wps_gw_giftcard_coupon_product_id', true );
							if ( '' !== $product_id ) {
								update_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_product_id', $product_id );
							}
							$coupon_amount = get_post_meta( $coupon_id, 'wps_gw_coupon_amount', true );
							if ( '' !== $product_id ) {
								update_post_meta( $coupon_id, 'wps_wgm_coupon_amount', $coupon_amount );
							}
							$giftcard_imported_coupon = get_post_meta( $coupon_id, 'wps_gw_imported_coupon', true );
							if ( '' !== $giftcard_imported_coupon ) {
								update_post_meta( $coupon_id, 'wps_wgm_imported_coupon', $giftcard_imported_coupon );
							}
							$giftcard_imported_offline = get_post_meta( $coupon_id, 'wps_gw_imported_offline', true );
							if ( '' !== $giftcard_imported_offline ) {
								update_post_meta( $coupon_id, 'wps_wgm_imported_offline', $giftcard_imported_offline );
							}
							update_option( 'wps_wgm_coupons_changed_meta_name', 'true' );
						}
					}
				}
			}
		}

		/**
		 * This function is used to restore the giftcard products.
		 *
		 * @name wps_restore_giftcard_products_pro
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_restore_giftcard_products_pro() {
			$args = array(
				'type' => 'gw_gift_card',
			);
			$products = wc_get_products( $args );
			if ( is_array( $products ) && ! empty( $products ) ) {
				foreach ( $products as $data ) {
					$product_id                    = $data->get_id();
					$wps_gw_overwrite              = get_post_meta( $product_id, 'wps_gw_overwrite', true );
					$wps_gw_discount               = get_post_meta( $product_id, 'wps_gw_discount', true );
					$wps_gw_exclude_per_product    = get_post_meta( $product_id, 'wps_gw_exclude_per_product', true );
					$wps_gw_exclude_per_pro_format = get_post_meta( $product_id, 'wps_gw_exclude_per_pro_format', true );
					$wps_gw_include_per_pro_format = get_post_meta( $product_id, 'wps_gw_include_per_pro_format', true );
					$wps_gw_include_per_product    = get_post_meta( $product_id, 'wps_gw_include_per_product', true );
					$wps_gw_exclude_per_category   = get_post_meta( $product_id, 'wps_gw_exclude_per_category', true );
					$wps_gw_include_per_category   = get_post_meta( $product_id, 'wps_gw_include_per_category', true );
					$wps_gw_email_to_recipient     = get_post_meta( $product_id, 'wps_gw_email_to_recipient', true );
					$wps_gw_download               = get_post_meta( $product_id, 'wps_gw_download', true );
					$wps_gw_shipping               = get_post_meta( $product_id, 'wps_gw_shipping', true );
					$wps_gw_pricing                = get_post_meta( $product_id, 'wps_gw_pricing', true );

					$wps_gw_default_price = isset( $wps_gw_pricing['default_price'] ) ? $wps_gw_pricing['default_price'] : 0;
					$wps_gw_price_type = isset( $wps_gw_pricing['type'] ) ? $wps_gw_pricing['type'] : 'wps_wgm_default_price';
					$wps_gw_selected_templates = isset( $wps_gw_pricing['template'] ) ? $wps_gw_pricing['template'] : '';
					$wps_gw_by_default_tem = isset( $wps_gw_pricing['by_default_tem'] ) ? $wps_gw_pricing['by_default_tem'] : '';

					$wps_uwgc_price_type = '';
					$wps_wgm_pricing = array();
					switch ( $wps_gw_price_type ) {
						case 'wps_gw_default_price':
							$wps_uwgc_price_type = 'wps_wgm_default_price';
							break;

						case 'wps_gw_range_price':
							$wps_uwgc_price_type = 'wps_wgm_range_price';
							$wps_wgm_pricing['from'] = $wps_gw_pricing['from'];
							$wps_wgm_pricing['to'] = $wps_gw_pricing['to'];
							break;

						case 'wps_gw_selected_price':
							$wps_uwgc_price_type = 'wps_wgm_selected_price';
							$wps_wgm_pricing['price'] = $wps_gw_pricing['price'];
							break;

						case 'wps_gw_user_price':
							$wps_uwgc_price_type = 'wps_wgm_user_price';
							break;

						default:
							$wps_uwgc_price_type = 'wps_wgm_default_price';
							break;
					}

					$wps_wgm_pricing['type'] = $wps_uwgc_price_type;
					$wps_wgm_pricing['default_price'] = $wps_gw_default_price;
					$wps_wgm_pricing['template'] = $wps_gw_selected_templates;
					$wps_wgm_pricing['by_default_tem'] = $wps_gw_by_default_tem;

					update_post_meta( $product_id, 'wps_wgm_overwrite', $wps_gw_overwrite );
					update_post_meta( $product_id, 'wps_wgm_discount', $wps_gw_discount );
					update_post_meta( $product_id, 'wps_wgm_exclude_per_product', $wps_gw_exclude_per_product );
					update_post_meta( $product_id, 'wps_wgm_include_per_product', $wps_gw_include_per_product );
					update_post_meta( $product_id, 'wps_wgm_exclude_per_category', $wps_gw_exclude_per_category );
					update_post_meta( $product_id, 'wps_wgm_include_per_category', $wps_gw_include_per_category );
					update_post_meta( $product_id, 'wps_wgm_email_to_recipient', $wps_gw_email_to_recipient );
					update_post_meta( $product_id, 'wps_wgm_download', $wps_gw_download );
					update_post_meta( $product_id, 'wps_wgm_shipping', $wps_gw_shipping );
					update_post_meta( $product_id, 'wps_wgm_pricing', $wps_wgm_pricing );
					wp_set_object_terms( $product_id, 'wgm_gift_card', 'product_type' );
				}
				update_option( 'wps_pro_product_restored', 'yes' );
			}
		}

		/**
		 * This function is used to restore other options.
		 *
		 * @name wps_restore_other_options_pro
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_restore_other_options_pro() {
			$wps_gw_new_pdf = get_option( 'wps_gw_next_step_for_pdf_value', 'no' );
			update_option( 'wps_wgm_next_step_for_pdf_value', $wps_gw_new_pdf );

			$add_schedule = get_option( 'wps_gw_add_schedule', false );
			update_option( 'wps_wgm_add_schedule', $add_schedule );
		}

	}
}
