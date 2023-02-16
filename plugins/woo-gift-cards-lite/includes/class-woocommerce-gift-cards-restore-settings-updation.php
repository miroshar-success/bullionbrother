<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Woocommerce_Gift_Cards_Restore_Settings_Updation' ) ) {
	/**
	 * This is class to restore the saved data on particular keys.
	 *
	 * @name    Woocommerce_Gift_Cards_Restore_Settings_Updation
	 * @category Class
	 * @author   WP Swings <webmaster@wpswings.com>
	 */
	class Woocommerce_Gift_Cards_Restore_Settings_Updation {
		/**
		 * This function is used to restore the overall functionality of plugin
		 *
		 * @since 2.0.0
		 * @name wps_wgm_restore_data_on_updation
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_wgm_restore_data_on_updation() {

			/*General setting tab data*/
			$wps_check_enable = false;
			$giftcard_enable = get_option( 'wps_wgm_general_setting_enable', false );
			if ( isset( $giftcard_enable ) && 'on' == $giftcard_enable ) {
				$wps_check_enable = true;
			}
			if ( $wps_check_enable ) {
				$general_process_completion_flag = false;
				$general_flag = false;
				$product_flag = false;
				$mail_flag = false;
				$delivery_flag = false;
				$other_flag = false;

				$general_flag = $this->restore_general_settings_data( $general_process_completion_flag );
				if ( $general_flag ) {
					$product_process_completion_flag = false;
					$product_flag = $this->restore_product_settings_data( $product_process_completion_flag );
				}
				if ( $product_flag ) {
					$mail_process_completion_flag = false;
					$mail_flag = $this->restore_mail_settings_data( $mail_process_completion_flag );
				}
				if ( $mail_flag ) {
					$delivery_process_completion_flag = false;
					$delivery_flag = $this->restore_delivery_settings_data( $delivery_process_completion_flag );
				}
				if ( $delivery_flag ) {
					$other_process_completion_flag = false;
					$other_flag = $this->restore_other_settings_data( $other_process_completion_flag );
				}
				if ( $other_flag ) {
					$this->delete_additional_data();
				}
			}
		}

		/**
		 * Function for Product setting tab data
		 *
		 * @since 2.0.0
		 * @name restore_general_settings_data
		 * @param boolean $general_process_completion_flag contains the flag value.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function restore_general_settings_data( $general_process_completion_flag ) {
			$wps_general_settings = get_option( 'wps_wgm_general_settings', array() );
			$general_setting_flag = false;
			if ( empty( $wps_general_settings ) ) {
				$giftcard_enable = get_option( 'wps_wgm_general_setting_enable', false );
				$giftcard_tax_cal_enable = get_option( 'wps_wgm_general_setting_tax_cal_enable', false );
				$giftcard_shop_page = get_option( 'wps_wgm_general_setting_shop_page_enable', false );
				$giftcard_individual_use = get_option( 'wps_wgm_general_setting_giftcard_individual_use', false );
				$giftcard_freeshipping = get_option( 'wps_wgm_general_setting_giftcard_freeshipping', false );
				$giftcard_coupon_length = get_option( 'wps_wgm_general_setting_giftcard_coupon_length', false );
				$giftcard_prefix = get_option( 'wps_wgm_general_setting_giftcard_prefix', false );
				$giftcard_prefix_sanitize = preg_replace( '/\\\\/', '', $giftcard_prefix );
				$giftcard_prefix_sanitize = sanitize_text_field( $giftcard_prefix_sanitize );
				$giftcard_expiry = get_option( 'wps_wgm_general_setting_giftcard_expiry', 0 );
				$giftcard_minspend = get_option( 'wps_wgm_general_setting_giftcard_minspend', false );
				$giftcard_maxspend = get_option( 'wps_wgm_general_setting_giftcard_maxspend', false );
				$giftcard_use = get_option( 'wps_wgm_general_setting_giftcard_use', 0 );
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
				);
				update_option( 'wps_wgm_general_settings', $wps_wgm_general_settings );
				$general_setting_flag = true;
			}
			if ( $general_setting_flag ) {
				delete_option( 'wps_wgm_general_setting_enable' );
				delete_option( 'wps_wgm_general_setting_tax_cal_enable' );
				delete_option( 'wps_wgm_general_setting_shop_page_enable' );
				delete_option( 'wps_wgm_general_setting_giftcard_individual_use' );
				delete_option( 'wps_wgm_general_setting_giftcard_freeshipping' );
				delete_option( 'wps_wgm_general_setting_giftcard_coupon_length' );
				delete_option( 'wps_wgm_general_setting_giftcard_prefix' );
				delete_option( 'wps_wgm_general_setting_giftcard_expiry' );
				delete_option( 'wps_wgm_general_setting_giftcard_minspend' );
				delete_option( 'wps_wgm_general_setting_giftcard_maxspend' );
				delete_option( 'wps_wgm_general_setting_giftcard_use' );
				$general_process_completion_flag = true;
			}
			return $general_process_completion_flag;
		}
		/**
		 * Function for Product setting tab data
		 *
		 * @since 2.0.0
		 * @name restore_product_settings_data
		 * @param boolean $product_process_completion_flag contains the flag value.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function restore_product_settings_data( $product_process_completion_flag ) {
			$product_setting_flag = false;
			$wps_product_settings = get_option( 'wps_wgm_product_settings', array() );
			if ( empty( $wps_product_settings ) ) {
				$giftcard_exclude_product = get_option( 'wps_wgm_product_setting_exclude_product', array() );
				$giftcard_exclude_category = get_option( 'wps_wgm_product_setting_exclude_category', array() );
				$giftcard_ex_sale = get_option( 'wps_wgm_product_setting_giftcard_ex_sale', false );
				$wps_wgm_product_settings = array(
					'wps_wgm_product_setting_giftcard_ex_sale' => $giftcard_ex_sale,
					'wps_wgm_product_setting_exclude_product' => $giftcard_exclude_product,
					'wps_wgm_product_setting_exclude_category' => $giftcard_exclude_category,
				);
				update_option( 'wps_wgm_product_settings', $wps_wgm_product_settings );
				$product_setting_flag = true;
			}
			if ( $product_setting_flag ) {
				delete_option( 'wps_wgm_product_setting_giftcard_ex_sale' );
				delete_option( 'wps_wgm_product_setting_exclude_product' );
				delete_option( 'wps_wgm_product_setting_exclude_category' );
				$product_process_completion_flag = true;
			}
			return $product_process_completion_flag;
		}

		/**
		 * Function for Email setting tab data.
		 *
		 * @since 2.0.0
		 * @name restore_mail_settings_data
		 * @param boolean $mail_process_completion_flag contains the flag value.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function restore_mail_settings_data( $mail_process_completion_flag ) {

			$mail_setting_flag = false;
			$wps_mail_settings = get_option( 'wps_wgm_mail_settings', array() );
			if ( empty( $wps_mail_settings ) ) {
				$wps_wgm_other_setting_upload_logo = get_option( 'wps_wgm_other_setting_upload_logo', false );
				$giftcard_giftcard_subject = get_option( 'wps_wgm_other_setting_giftcard_subject', false );
				$giftcard_giftcard_subject = stripcslashes( $giftcard_giftcard_subject );
				$wps_wgm_mail_settings = array(
					'wps_wgm_mail_setting_upload_logo' => $wps_wgm_other_setting_upload_logo,
					'wps_wgm_mail_setting_giftcard_subject' => $giftcard_giftcard_subject,
				);
				update_option( 'wps_wgm_mail_settings', $wps_wgm_mail_settings );
				$mail_setting_flag = true;
			}
			if ( $mail_setting_flag ) {
				delete_option( 'wps_wgm_other_setting_upload_logo' );
				delete_option( 'wps_wgm_other_setting_giftcard_subject' );
				delete_option( 'wps_wgm_other_setting_giftcard_html' );
				$mail_process_completion_flag = true;
			}
			return $mail_process_completion_flag;
		}

		/**
		 * Function for Delivery setting tab data.
		 *
		 * @since 2.0.0
		 * @name restore_delivery_settings_data
		 * @param boolean $delivery_process_completion_flag contains the flag value.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function restore_delivery_settings_data( $delivery_process_completion_flag ) {
			$delivery_setting_flag = false;
			$wps_delivery_settings = get_option( 'wps_wgm_delivery_settings', array() );
			if ( empty( $wps_delivery_settings ) ) {
				$wps_wgm_delivery_setting_method = get_option( 'wps_wgm_delivery_setting_method', false );
				if ( 'Mail_To_Recipient' == $wps_wgm_delivery_setting_method ) {
					$wps_wgm_delivery_setting_method = 'Mail to recipient';
				}
				$wps_wgm_delivery_settings = array(
					'wps_wgm_send_giftcard' => $wps_wgm_delivery_setting_method,
				);
				update_option( 'wps_wgm_delivery_settings', $wps_wgm_delivery_settings );
				$delivery_setting_flag = true;
			}
			if ( $delivery_setting_flag ) {
				delete_option( 'wps_wgm_delivery_setting_method' );
				$delivery_process_completion_flag = true;
			}
			return $delivery_process_completion_flag;
		}

		/**
		 * Function for Other setting tab data.
		 *
		 * @since 2.0.0
		 * @name restore_other_settings_data
		 * @param boolean $other_process_completion_flag contains the flag value.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function restore_other_settings_data( $other_process_completion_flag ) {
			$other_setting_flag = false;
			$wps_other_settings = get_option( 'wps_wgm_other_settings', array() );
			if ( empty( $wps_other_settings ) ) {
				$wps_wgm_apply_coupon_disable = get_option( 'wps_wgm_additional_apply_coupon_disable', false );
				$wps_wgm_other_settings = array(
					'wps_wgm_additional_apply_coupon_disable' => $wps_wgm_apply_coupon_disable,
				);
				update_option( 'wps_wgm_other_settings', $wps_wgm_other_settings );
				$other_setting_flag = true;
			}
			if ( $other_setting_flag ) {
				delete_option( 'wps_wgm_additional_apply_coupon_disable' );
				$other_process_completion_flag = true;
			}
			return $other_process_completion_flag;
		}

		/**
		 * Removed fields in new lite plugin
		 *
		 * @since 2.0.0
		 * @name delete_additional_data
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function delete_additional_data() {
			delete_option( 'wps_wgm_general_setting_giftcard_applybeforetx' );
			delete_option( 'wps_wgm_product_setting_exclude_product_format' );
		}
	}
}
