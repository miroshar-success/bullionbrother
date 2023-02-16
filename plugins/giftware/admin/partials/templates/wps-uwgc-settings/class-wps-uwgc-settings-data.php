<?php
/**
 * Exit if accessed directly
 *
 * @package Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Wps_Uwgc_Settings_Data' ) ) {
	/**This class is for generating the html for the settings.
	 *
	 * This file use to display the function fot the html
	 *
	 * @package    Ultimate Woocommerce Gift Cards
	 * @subpackage Ultimate Woocommerce Gift Cards/admin
	 * @author     WP Swings <webmaster@wpswings.com>
	 */
	class Wps_Uwgc_Settings_Data {
		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {

		}

		/**
		 * This function is for generating tabs when pro is activated.
		 *
		 * @name wps_ugc_get_pro_tab_additional_settings
		 * @since 1.0.0
		 */
		public function wps_ugc_get_pro_tab_additional_settings() {
			$wps_uwgc_tab_additional_settings = array(
				'offline-giftcard'      => array(
					'title'     => __( 'Offline Giftcard', 'giftware' ),
					'file_path' => WPS_UWGC_DIRPATH . 'admin/partials/templates/wps-uwgc-offline-giftcard-setting.php',
				),
				'export-coupon'         => array(
					'title'     => __( 'Import/Export', 'giftware' ),
					'file_path' => WPS_UWGC_DIRPATH . 'admin/partials/templates/wps-uwgc-export-coupon-setting.php',
				),
				'discount'              => array(
					'title'     => __( 'Discount', 'giftware' ),
					'file_path' => WPS_UWGC_DIRPATH . 'admin/partials/templates/wps-uwgc-discount-setting.php',
				),
				'thankyou-order'        => array(
					'title'     => __( 'Thank you Order', 'giftware' ),
					'file_path' => WPS_UWGC_DIRPATH . 'admin/partials/templates/wps-uwgc-thankyou-order-setting.php',
				),
				'qrcode-barcode'        => array(
					'title'     => __( 'Qrcode/Barcode', 'giftware' ),
					'file_path' => WPS_UWGC_DIRPATH . 'admin/partials/templates/wps-uwgc-qrcode-barcode-setting.php',
				),
				'customizable-giftcard' => array(
					'title'     => __( 'Customizable Giftcard', 'giftware' ),
					'file_path' => WPS_UWGC_DIRPATH . 'admin/partials/templates/wps-uwgc-customizable-giftcard-setting.php',
				),
				'sms-notification'      => array(
					'title'     => __( 'Notifications', 'giftware' ),
					'file_path' => WPS_UWGC_DIRPATH . 'admin/partials/templates/wps-uwgc-sms-notification-setting.php',
				),
			);
			return $wps_uwgc_tab_additional_settings;
		}

		/**
		 * Wps_ugc_date_fromat_type
		 *
		 * @return array $date date format.
		 */
		public function wps_ugc_date_fromat_type() {
			$date = array(
				array(
					'id'   => 'Y/m/d',
					'name' => 'yyyy/mm/dd',
				),
				array(
					'id'   => 'm/d/Y',
					'name' => 'mm/dd/yyyy',
				),
				array(
					'id'   => 'd M, Y',
					'name' => 'd M, yyyy',
				),
				array(
					'id'   => 'l, d F, Y',
					'name' => 'DD, d MM, yyyy',
				),
				array(
					'id'   => 'Y-m-d',
					'name' => 'yyyy-mm-dd',
				),
				array(
					'id'   => 'd/m/Y',
					'name' => 'dd/mm/yyyy',
				),
				array(
					'id'   => 'd.m.Y',
					'name' => 'd.m.yyyy',
				),
			);
			return $date;
		}

		/**
		 * This function is for generating html when pro is activated
		 *
		 * @name wps_ugc_get_pro_general_settings
		 * @since 1.0.0
		 */
		public function wps_ugc_get_pro_general_settings() {
			$wps_uwgc_html             = new WPS_UWGC_SETTING_HTML_FUNCTION();
			$wps_uwgc_general_settings = array(
				array(
					'title'    => __( 'Gift Card PDF Name Prefix', 'giftware' ),
					'id'       => 'wps_wgm_general_setting_pdf_prefix',
					'type'     => 'text',
					'class'    => 'input-text',
					'desc_tip' => __( 'Enter Gift Card PDF Name Prefix Ex: PREFIX_CODE.pdf, if empty then gift card TIMESTAMP_SITENAME.pdf', 'giftware' ),
				),
				array(
					'title'    => __( 'Enable Date feature', 'giftware' ),
					'id'       => 'wps_wgm_general_setting_enable_selected_date',
					'type'     => 'checkbox',
					'class'    => 'input-text',
					'desc_tip' => __( 'Check this box to enable gift card send to receiver on selected date', 'giftware' ),
					'desc'     => __( 'Enable Gift card Product send on selected date', 'giftware' ),
				),
				array(
					'title'            => __( 'Date format', 'giftware' ),
					'id'               => 'wps_wgm_general_setting_enable_selected_format',
					'type'             => 'singleSelectDropDownWithKeyvalue',
					'custom_attribute' => $this->wps_ugc_date_fromat_type(),
					'class'            => 'input-text',
					'desc_tip'         => __( 'Select the date format which is used on front end..', 'giftware' ),
				),
				array(
					'title'    => __( 'Enable Payment Gateways for Gift Card', 'giftware' ),
					'id'       => 'wps_wgm_general_setting_giftcard_payment',
					'type'     => 'search&selectWithDesc',
					'class'    => 'input-text',
					'desc_tip' => __( 'If you want to enable selected payment gateways then choose those payments gateways here Otherwise default payment gateways are enabled for the Gift Card.', 'giftware' ),
					'options'  => $wps_uwgc_html->wps_uwgc_payment_method(),
				),
				array(
					'title'    => __( 'Disable Category', 'giftware' ),
					'id'       => 'wps_wgm_general_setting_categ_enable',
					'type'     => 'checkboxWithDesc',
					'class'    => 'input-text',
					'desc_tip' => __( 'Check this box if you don\'t want to assign a Gift Card Category to a Gift Card product forcefully.', 'giftware' ),
					'desc'     => __( 'Enable it for changing the Gift Card category.', 'giftware' ),
				),
			);
			return $wps_uwgc_general_settings;
		}

		/**
		 * This function is for generating html when pro ia activated.
		 *
		 * @name wps_ugc_get_pro_product_settings
		 * @since 1.0.0
		 */
		public function wps_ugc_get_pro_product_settings() {
			require_once WPS_WGC_DIRPATH . 'admin/partials/template_settings_function/class-woocommerce-giftcard-admin-settings.php';
			$settings_obj              = new Woocommerce_Giftcard_Admin_Settings();
			$wps_uwgc_product_settings = array(
				array(
					'title' => __( 'Include Products', 'giftware' ),
					'id' => 'wps_wgm_product_setting_include_product',
					'type' => 'search&select',
					'multiple' => 'multiple',
					'custom_attribute' => array(
						'style' => '"width:50%;"',
						'class' => '"wc-product-search"',
						'data-action' => '"woocommerce_json_search_products_and_variations"',
						'data-placeholder' => __( 'Search for a product', 'giftware' ),
					),
					'desc_tip' => __( 'Product that the coupon will be applied to, or that need to be in the cart in order for the "Gift Card discount" to be applied.', 'giftware' ),
					'options' => $settings_obj->wps_wgm_get_product( 'wps_wgm_product_setting_include_product', 'wps_wgm_product_settings' ),
				),
				array(
					'title' => __( 'Include Product Category', 'giftware' ),
					'id' => 'wps_wgm_product_setting_include_category',
					'type' => 'search&select',
					'multiple' => 'multiple',
					'desc_tip' => __( 'Product categories that the coupon will be applied to, or that need to be in the cart in order for the "Gift Card discount" to be applied.', 'giftware' ),
					'options' => $settings_obj->wps_wgm_get_category(),
				),
				array(
					'title'         => __( 'Disable fields from Gift Card Product Page', 'giftware' ),
					'id'            => 'wps_wgm_product_setting_disable_fields_giftcard',
					'type'          => 'multipleCheckboxCheck',
					'desc_tip'      => __( 'You can disable unnecessary field which you don\'t want to display on gift card product page.', 'giftware' ),
					'default_value' => 0,
				),
			);
			return $wps_uwgc_product_settings;
		}

		/**
		 * This function is for generating html when pro is activated.
		 *
		 * @name wps_ugc_get_pro_mail_settings
		 * @since 1.0.0
		 */
		public function wps_ugc_get_pro_mail_settings() {
			$wps_uwgc_html = new WPS_UWGC_SETTING_HTML_FUNCTION();
			$wps_uwgc_additional_mail_field['middle'] = array(
				array(
					'title' => __( 'Gift Card Email Subject', 'giftware' ),
					'id' => 'wps_wgm_mail_setting_giftcard_subject',
					'type' => 'textWithDesc',
					'class' => 'description wps_ml-35',
					'desc_tip' => __( 'Email Subject for notifying Sender information about Gift card Mail send.', 'giftware' ),
					'bottom_desc' => __( 'Use [SITENAME] shortcode as the name of the site and [FROM] shortcode as buyer name to be placed dynamically.', 'giftware' ),
				),
				array(
					'title' => __( 'Email Subject to Sender', 'giftware' ),
					'id' => 'wps_wgm_mail_setting_receive_subject',
					'type' => 'text',
					'class' => 'wps_wgm_new_woo_ver_style_text',
					'desc_tip' => __( 'Email Subject for notifying receiver information about Gift card Mail send.', 'giftware' ),
				),
				array(
					'title' => __( 'Email Notification to Sender', 'giftware' ),
					'id' => 'wps_wgm_mail_setting_receive_message',
					'type' => 'wp_editor',
					'additional_info' => __( 'You may use shortcode [TO] for placing the Recipient Email dynamically', 'giftware' ),
					'desc_tip' => __( 'Write the Email Content for Buyer who should acknowledge that his/her Gift Card has been sent successfully', 'giftware' ),
				),
				array(
					'title' => __( 'Downloadable Gift Card Email Subject for Buyer', 'giftware' ),
					'id' => 'wps_wgm_mail_setting_giftcard_subject_downloadable',
					'type' => 'textWithDesc',
					'class' => 'description wps_ml-35',
					'desc_tip' => __( 'Downloadable Gift Card Email Subject for Gift card Mail when received by the buyer.', 'giftware' ),
					'bottom_desc' => __( 'Use [SITENAME] shortcode as the name of the site to be placed dynamically.', 'giftware' ),
				),
				array(
					'title' => __( 'Gift Card Email Subject for Admin', 'giftware' ),
					'id' => 'wps_wgm_mail_setting_giftcard_subject_shipping',
					'type' => 'textWithDesc',
					'class' => 'description wps_ml-35',
					'desc_tip' => __( 'This is the subject of the Gift Card mail that will be sent to the admin when the buyer purchases the Gift Card so that he can ship it to the shipping address.', 'giftware' ),
					'bottom_desc' => __( 'Use [SITENAME] shortcode as the name of the site and [ORDERID] shortcode as the order id of the product to be placed dynamically.', 'giftware' ),
				),
			);
			$wps_uwgc_additional_mail_field['bottom'] = array(
				array(
					'title' => esc_html__( 'Disable Coupn Amount Notification Email', 'giftware' ),
					'id' => 'wps_wgm_mail_setting_disable_coupon_notification_mail',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => esc_html__( 'Check this if you want to disable Coupon Amount Notification Email.', 'giftware' ),
					'desc' => esc_html__( 'Disable Coupon Amount Notification Email.', 'giftware' ),
				),
				array(
					'title' => __( 'Coupon Email Subject', 'giftware' ),
					'id' => 'wps_wgm_mail_setting_receive_coupon_subject',
					'type' => 'textWithDesc',
					'class' => 'description wps_ml-35',
					'desc_tip' => __( 'Email Subject for Coupon Mail.', 'giftware' ),
					'bottom_desc' => __( 'Use [SITENAME] shortcode as the name of the site to be placed dynamically', 'giftware' ),
				),
				array(
					'title' => __( 'Email Notification to Sender', 'giftware' ),
					'id' => 'wps_wgm_mail_setting_receive_coupon_message',
					'type' => 'wp_editor',
					'content' => $wps_uwgc_html->wps_uwgc_mail_notification_to_show_amount_left(),
					'additional_info' => __( 'Use [SITENAME] shortcode as the name of the site. Use [COUPONAMOUNT] shortcode as coupon amount to be placed dynamically. [COUPONCODE] Shortcode is for display the Coupon Code. Here the [DISCLAIMER] shortcode would be replaced by above Disclaimer text field.', 'giftware' ),
					'desc_tip' => __( 'Write the Email Content to notify the user about their usage of coupon amount.', 'giftware' ),
				),
			);
			return $wps_uwgc_additional_mail_field;
		}

		/**
		 * This function is for generating html when pro is activated.
		 *
		 * @name wps_ugc_get_pro_delivery_settings
		 * @since 1.0.0
		 */
		public function wps_ugc_get_pro_delivery_settings() {
			$wps_wgm_additional_delivery_setting = array(
				array(
					'title' => __( 'Enable Shipping on Gift Card', 'giftware' ),
					'id' => 'wps_wgm_shipping_setting_enable',
					'type' => 'radio',
					'class' => 'wps_wgm_send_giftcard',
					'name' => 'wps_wgm_send_giftcard',
					'value' => 'shipping',
					'desc_tip' => __( 'Check this box to enable Shipping on Gift Card Products.', 'giftware' ),
					'desc' => __( 'Enable Shipping for Gift Card.', 'giftware' ),
					'default_value' => 0,
				),
				array(
					'title' => __( 'Allow customer to choose', 'giftware' ),
					'id' => 'wps_wgm_customer_choose_setting_enable',
					'type' => 'radio',
					'name' => 'wps_wgm_send_giftcard',
					'class' => 'wps_wgm_send_giftcard',
					'value' => 'customer_choose',
					'desc_tip' => __( 'Check this box to provide the facility to select the above three methods for Gift Card products', 'giftware' ),
					'desc' => __( 'Customer can select below methods', 'giftware' ),
					'default_value' => 0,
					'custom_attribute' => array(),
				),
				array(
					'title' => __( 'Customer can select', 'giftware' ),
					'id' => 'wps_wgm_customer_select_setting_enable',
					'type' => 'multipleCheckbox',
					'value' => 'customer_choose',
					'desc_tip' => __( 'Check this box to allow customer to select methods on Gift Card Products.', 'giftware' ),
					'desc' => __( 'Customer can select below methods', 'giftware' ),
					'default_value' => 0,
				),
				array(
					'title' => __( 'Email for Ship your Card Delivery Method', 'giftware' ),
					'id' => 'wps_wgm_change_admin_email_for_shipping',
					'type' => 'email',
					'class' => 'input-text',
					'desc_tip' => __( 'Enter the email where you want to email your Gift Card when the customer has chosen the "Ship Your Card" delivery method, Leave blank if you want to send this to Admin Default Email-Id', 'giftware' ),
				),
				array(
					'title' => __( 'Apply Coupon on Shipping', 'giftware' ),
					'id' => 'wps_wgm_general_cart_shipping_enable',
					'type' => 'checkbox',
					'name' => 'wps_wgm_general_cart_shipping_enable',
					'class' => 'input-text',
					'desc_tip' => __(
						'Check this box to enable the Coupon to be applied on shipping price of product
						. The coupon will be applied on the Cart Total.',
						'giftware'
					),
					'desc' => __( 'Enable this field to apply Coupon on Cart Total instead of Cart Subtotal.', 'giftware' ),
				),
			);
			return $wps_wgm_additional_delivery_setting;
		}

		/**
		 * This function is for generating html when pro is activated.
		 *
		 * @name wps_ugc_get_pro_other_settings
		 * @since 1.0.0
		 */
		public function wps_ugc_get_pro_other_settings() {
			$wps_uwgc_html = new WPS_UWGC_SETTING_HTML_FUNCTION();
			$additional_other_settings = array(
				array(
					'title' => __( 'Enable Bcc option for Gift card Mails', 'giftware' ),
					'id' => 'wps_wgm_addition_bcc_option_enable',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'After Enabling this buyer will get exact same mail as recipient', 'giftware' ),
					'desc' => __( 'Enable Bcc Option For Gift Card Mails', 'giftware' ),
				),
				array(
					'title' => __( 'Disable Resend Button', 'giftware' ),
					'id' => 'wps_wgm_additional_resend_disable',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'Check this if you want to disable Resend Button At Front End', 'giftware' ),
					'desc' => __( 'Disable Resend Button', 'giftware' ),
				),
				array(
					'title' => __( 'Disable Quantity', 'giftware' ),
					'id' => 'wps_wgm_additional_quantity_disable',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'Check this if you want to disable quantity At Front End Cart Page', 'giftware' ),
					'desc' => __( 'Disable Quantity on Front End Cart Page', 'giftware' ),
				),
				array(
					'title' => __( 'Disable Send Today Button', 'giftware' ),
					'id' => 'wps_wgm_additional_sendtoday_disable',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'Check this if you want to disable Send Today Button At Front End', 'giftware' ),
					'desc' => __( 'Disable Send Today Button', 'giftware' ),
				),
				array(
					'title' => __( 'Enable Pdf Feature', 'giftware' ),
					'id' => 'wps_wgm_addition_pdf_enable',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'After Enabling this customer will get Gift Card mails along with attached pdf', 'giftware' ),
					'desc' => __( 'Enable PDF option for Gift Card Mails ( Please import pdf supported templates.)', 'giftware' ),
				),
				array(
					'title' => __( 'Select the Pdf Template Size', 'giftware' ),
					'id' => 'wps_wgm_pdf_template_size',
					'type' => 'singleSelectDropDownWithKeyvalue',
					'class' => 'input-text',
					'desc_tip' => __( 'Select the Pdf Template Size (i.e A3 or A4)', 'giftware' ),
					'custom_attribute' => array(
						array(
							'id' => 'A3',
							'name' => 'A3 Format',
						),
						array(
							'id' => 'A4',
							'name' => 'A4 Format',
						),
					),
				),
				array(
					'type' => 'tryNowSpan',
				),
				array(
					'title' => __( 'Enable Browse Image for Gift Card', 'giftware' ),
					'id' => 'wps_wgm_other_setting_browse',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'Check this box to enable image browse option for customers on purchasing Gift Card.', 'giftware' ),
					'desc' => __( 'Enable Browse image for customers for Gift Card products.', 'giftware' ),
				),
				array(
					'title' => __( 'Making Optional "To Email" Field', 'giftware' ),
					'id' => 'wps_wgm_remove_validation_to',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'Check this if you want to remove validation from "To" Field', 'giftware' ),
					'desc' => __( 'Remove Validation from "To Email" Field', 'giftware' ),
				),
				array(
					'title' => __( 'Making Optional "To Name" Field', 'giftware' ),
					'id' => 'wps_wgm_remove_validation_to_name',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'Check this if you want to remove validation from "To Name" Field', 'giftware' ),
					'desc' => __( 'Remove Validation from "To Name" Field', 'giftware' ),
				),
				array(
					'title' => __( 'Making Optional "From" Field', 'giftware' ),
					'id' => 'wps_wgm_remove_validation_from',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'Check this if you want to remove validation from "From" field', 'giftware' ),
					'desc' => __( 'Remove Validation from "From" Field', 'giftware' ),
				),
				array(
					'title' => __( 'Making Optional "Gift Message" Field', 'giftware' ),
					'id' => 'wps_wgm_remove_validation_msg',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'Check this if you want to remove validation from "Gift Message"', 'giftware' ),
					'desc' => __( 'Remove Validation from "Gift Message" Field', 'giftware' ),
				),
				array(
					'title' => __( 'Manual Increment usage count for Gift Coupon', 'giftware' ),
					'id' => 'wps_wgm_manually_increment_usage',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'Check this if you want to increment usage count of gift coupons manually', 'giftware' ),
					'desc' => __( 'Update usage count for Gift Coupons manually', 'giftware' ),
				),
				array(
					'title' => __( 'Hide Gift card Notice', 'giftware' ),
					'id' => 'wps_wgm_hide_giftcard_notice',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'Check this if you want to hide "Gift card Notice" from product page', 'giftware' ),
					'desc' => __( 'Hide Gift card Notice', 'giftware' ),
				),
				array(
					'title' => __( 'Hide Terms and Condition', 'giftware' ),
					'id' => 'wps_wgm_hide_terms_and_condition',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'Check this if you want to hide "Terms and Condition" from product page', 'giftware' ),
					'desc' => __( 'Hide Terms and Condition on Product Page', 'giftware' ),
				),
				array(
					'title' => __( 'Terms and Condition Content', 'giftware' ),
					'id' => 'wps_wgm_terms_condition_content',
					'class' => 'input-text',
					'type' => 'textarea',
					'custom_attribute' => array(
						'rows' => '"3"',
					),
					'default' => __(
						'Products Cannot be exchanged',
						'giftware'
					),
					'desc_tip' => __( 'Write the message you want to display on terms and condition', 'giftware' ),
				),
				array(
					'title' => __( 'Hide Featured/Thumbnail Image', 'giftware' ),
					'id' => 'wps_wgm_hide_giftcard_thumbnail',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'Check this if you want to hide "Featured/Thumbnail image from Single Product Page" from product page', 'giftware' ),
					'desc' => __( 'Hide Featured/Thumbnail Image', 'giftware' ),
				),
				array(
					'title' => __( 'Disable Buyer Notification', 'giftware' ),
					'id' => 'wps_wgm_disable_buyer_notification',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'Check this if you want to disable the Buyer Notification about the "Gift Card has been sent"', 'giftware' ),
					'desc' => __( 'Disable the Notification', 'giftware' ),
				),
				array(
					'title' => __( 'Enable Product for Custom Page', 'giftware' ),
					'id' => 'wps_wgm_render_product_custom_page',
					'type' => 'checkbox',
					'class' => 'input-text',
					'desc_tip' => __( 'Check this, If you want to display the gift card product in any custom pages you want (like: through product_page id="xyz" shortcode)', 'giftware' ),
					'desc' => __( 'Display Gift card Product in custom page', 'giftware' ),
				),
				array(
					'title' => __( 'Select Custom Page', 'giftware' ),
					'id' => 'wps_wgm_custom_page_selection',
					'type' => 'singleSelectDropDownWithKeyvalue',
					'class' => 'input-text',
					'custom_attribute' => $wps_uwgc_html->wps_uwgc_get_custom_pages(),
					'desc_tip' => __( 'Select that custom page where you want to display the gift card with the shortcode product_page id="xyz"', 'giftware' ),
				),
			);
			return $additional_other_settings;
		}
	}
}
