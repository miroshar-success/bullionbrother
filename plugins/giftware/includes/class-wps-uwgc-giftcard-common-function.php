<?php
/**
 * Exit if accessed directly
 *
 * @package    Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'WPS_UWGC_Giftcard_Common_Function' ) ) {

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * @package    Ultimate Woocommerce Gift Cards
	 * @subpackage Ultimate Woocommerce Gift Cards/admin
	 * @author     WP Swings <webmaster@wpswings.com>
	 */
	class WPS_UWGC_Giftcard_Common_Function {

		/**
		 * This function is used to create offline giftcard
		 *
		 * @name enqueue_scripts_for_license_validation
		 * @param string $gift_couponnumber Contains gift coupon number.
		 * @param mixed  $couponamount Contains gift coupon amount.
		 * @param int    $order_id Contains order id.
		 * @param int    $product_id Contains product id.
		 * @param string $to Contains the name.
		 * @since 1.0.0
		 */
		public function wps_uwgc_create_offline_gift_coupon( $gift_couponnumber, $couponamount, $order_id, $product_id, $to ) {
			$wps_wgm_enable = wps_wgm_giftcard_enable();
			if ( $wps_wgm_enable ) {

				$coupon_code = $gift_couponnumber; // Code.
				$amount = $couponamount; // Amount.
				$discount_type = 'fixed_cart';
				$coupon_description = "OFFLINE GIFTCARD ORDER #$order_id";

				$coupon = array(
					'post_title' => $coupon_code,
					'post_content' => $coupon_description,
					'post_excerpt' => $coupon_description,
					'post_status' => 'publish',
					'post_author' => get_current_user_id(),
					'post_type'     => 'shop_coupon',
				);

				$new_coupon_id = wp_insert_post( $coupon );
				$general_settings = get_option( 'wps_wgm_general_settings', array() );
				$wps_obj = new Woocommerce_Gift_Cards_Common_Function();
				$individual_use = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_individual_use' );
				$individual_use = ( 'on' == $individual_use ) ? 'yes' : 'no';

				$usage_limit = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_use' );
				$usage_limit = ( '' !== $usage_limit ) ? $usage_limit : 1;

				$expiry_date = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_expiry' );
				$expiry_date = ( '' !== $expiry_date ) ? $expiry_date : 1;

				$free_shipping = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_freeshipping' );
				$free_shipping = ( 'on' == $free_shipping ) ? 'yes' : 'no';

				$minimum_amount = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_minspend' );
				$maximum_amount = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_maxspend' );

				$products_settings = get_option( 'wps_wgm_product_settings', array() );
				$exclude_sale_items = $wps_obj->wps_wgm_get_template_data( $products_settings, 'wps_wgm_product_setting_giftcard_ex_sale' );
				$exclude_sale_items = ( 'on' == $exclude_sale_items ) ? 'yes' : 'no';

				$exclude_products = $wps_obj->wps_wgm_get_template_data( $products_settings, 'wps_wgm_product_setting_exclude_product' );
				$exclude_products = ( is_array( $exclude_products ) && ! empty( $exclude_products ) ) ? implode( ',', $exclude_products ) : '';

				$exclude_category = $wps_obj->wps_wgm_get_template_data( $products_settings, 'wps_wgm_product_setting_exclude_category' );

				$include_products = $wps_obj->wps_wgm_get_template_data( $products_settings, 'wps_wgm_product_setting_include_product' );
				$include_products = ( is_array( $include_products ) && ! empty( $include_products ) ) ? implode( ',', $include_products ) : '';

				$include_category = $wps_obj->wps_wgm_get_template_data( $products_settings, 'wps_wgm_product_setting_include_category' );

				$todaydate = date_i18n( 'Y-m-d' );

				if ( $expiry_date > 0 || 0 === $expiry_date ) {
					$expirydate = date_i18n( 'Y-m-d', strtotime( "$todaydate +$expiry_date day" ) );
				} else {
					$expirydate = '';
				}
				// Add meta.
				update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
				update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
				update_post_meta( $new_coupon_id, 'individual_use', $individual_use );
				update_post_meta( $new_coupon_id, 'usage_limit', $usage_limit );
				$woo_ver = WC()->version;
				if ( $woo_ver < '3.6.0' ) {
					update_post_meta( $new_coupon_id, 'expiry_date', $expirydate );
				} else {
					$expirydate = strtotime( $expirydate );
					update_post_meta( $new_coupon_id, 'date_expires', $expirydate );
				}
				update_post_meta( $new_coupon_id, 'free_shipping', $free_shipping );
				update_post_meta( $new_coupon_id, 'minimum_amount', $minimum_amount );
				update_post_meta( $new_coupon_id, 'maximum_amount', $maximum_amount );
				update_post_meta( $new_coupon_id, 'exclude_sale_items', $exclude_sale_items );
				update_post_meta( $new_coupon_id, 'exclude_product_categories', $exclude_category );
				update_post_meta( $new_coupon_id, 'exclude_product_ids', $exclude_products );
				update_post_meta( $new_coupon_id, 'wps_wgm_giftcard_coupon', $order_id );
				update_post_meta( $new_coupon_id, 'wps_wgm_giftcard_coupon_unique', 'offline' );
				update_post_meta( $new_coupon_id, 'wps_wgm_giftcard_coupon_product_id', $product_id );
				update_post_meta( $new_coupon_id, 'wps_wgm_giftcard_coupon_mail_to', $to );
				update_post_meta( $new_coupon_id, 'product_ids', $include_products );
				update_post_meta( $new_coupon_id, 'product_categories', $include_category );

				return true;
			}
			return false;
		}
		/**
		 * This function is used to convert the templates to pdf format
		 *
		 * @name wps_uwgc_attached_pdf
		 * @param string $message Contains the message.
		 * @param string $site_name Contains the site name.
		 * @param string $time Contains the time.
		 * @param string $order_id Contains the order id.
		 * @param string $coupon_code Contains coupon code.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_attached_pdf( $message, $site_name, $time, $order_id = '', $coupon_code = '' ) {
			$wps_uwgc_wkhtmltopdf = file_exists( WPS_UWGC_DIRPATH . 'wkhtmltox/bin/wkhtmltopdf' );
			$wps_uwgc_new_way_of_pdf = get_option( 'wps_wgm_next_step_for_pdf_value', 'no' );
			$wps_uwgc_new_way_of_pdf = 'yes';
			if ( 'yes' == $wps_uwgc_new_way_of_pdf && $wps_uwgc_wkhtmltopdf ) {
				$other_settings = get_option( 'wps_wgm_other_settings', array() );
				$wps_obj = new Woocommerce_Gift_Cards_Common_Function();
				$wps_wgm_pdf_template_size = $wps_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_pdf_template_size' );
				$wps_wgm_pdf_template_size = ( '' !== $wps_wgm_pdf_template_size ) ? $wps_wgm_pdf_template_size : 'A3';

				$giftcard_pdf_content = $message;
				$upload_dir_path = WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf';
				if ( ! is_dir( $upload_dir_path ) ) {
					wp_mkdir_p( $upload_dir_path );
					chmod( $upload_dir_path, 0775 );
				}
				$handle = fopen( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/giftcard' . $time . $site_name . '.html', 'w' );
				fwrite( $handle, $giftcard_pdf_content );
				fclose( $handle );
				$url = WPS_UWGC_UPLOAD_URL . '/giftcard_pdf/giftcard' . $time . $site_name . '.html';
				if ( 'A3' == $wps_wgm_pdf_template_size ) {
					$result = exec( WPS_UWGC_DIRPATH . 'wkhtmltox/bin/wkhtmltopdf --page-size A3 --encoding utf-8 ' . esc_url( $url ) . ' ' . $upload_dir_path . '/giftcard' . $time . $site_name . '.pdf', $output );
					if ( '' !== $order_id && '' !== $coupon_code ) {
						$result = exec( WPS_UWGC_DIRPATH . 'wkhtmltox/bin/wkhtmltopdf --page-size A3 --encoding utf-8 ' . esc_url( $url ) . ' ' . $upload_dir_path . '/giftcard' . $order_id . '-' . $coupon_code . '.pdf', $output, $return );
					}
				} else if ( 'A4' == $wps_wgm_pdf_template_size ) {

					$result = exec( WPS_UWGC_DIRPATH . 'wkhtmltox/bin/wkhtmltopdf --page-size A4 --encoding utf-8 ' . esc_url( $url ) . ' ' . $upload_dir_path . '/giftcard' . $time . $site_name . '.pdf', $output, $return );

					if ( '' !== $order_id && '' !== $coupon_code ) {
						$result = exec( WPS_UWGC_DIRPATH . 'wkhtmltox/bin/wkhtmltopdf --page-size A4 --encoding utf-8 ' . esc_url( $url ) . ' ' . $upload_dir_path . '/giftcard' . $order_id . $coupon_code . '.pdf', $output, $return );
					}
				}
			} else {
				$other_settings = get_option( 'wps_wgm_other_settings', array() );
				$wps_obj = new Woocommerce_Gift_Cards_Common_Function();
				$wps_wgm_pdf_template_size = $wps_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_pdf_template_size' );
				$wps_wgm_pdf_template_size = ( '' != $wps_wgm_pdf_template_size ) ? $wps_wgm_pdf_template_size : 'A3';
				$general_setting           = get_option( 'wps_wgm_general_settings', array() );
				$giftcard_pdf_prefix       = $wps_obj->wps_wgm_get_template_data( $general_setting, 'wps_wgm_general_setting_pdf_prefix' );

				$giftcard_pdf_content = $message;
				$url = 'https://wpswings.com/gift-card-api/api.php?f=get_giftcart_pdf&domain=' . $site_name . '&type=' . $wps_wgm_pdf_template_size;
				$output = wp_remote_post(
					$url,
					array(
						'body'      => $giftcard_pdf_content,
						'sslverify' => false,
					)
				);
				$output = wp_remote_retrieve_body( $output );
				$upload_dir_path = WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf';
				if ( ! is_dir( $upload_dir_path ) ) {
					wp_mkdir_p( $upload_dir_path );
					chmod( $upload_dir_path, 0755 );
				}

				if ( ! empty( $giftcard_pdf_prefix ) && ! empty( $coupon_code ) ) {
					$handle = fopen( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/' . esc_html( $giftcard_pdf_prefix ) . esc_html( $coupon_code ) . '.pdf', 'w' ) or die( 'Cannot open file: ' . esc_html( $giftcard_pdf_prefix ) . esc_html( $coupon_code ) . '.pdf' );
					fwrite( $handle, $output );
					fclose( $handle );
				} else {
					$handle = fopen( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/giftcard' . esc_html( $time ) . esc_html( $site_name ) . '.pdf', 'w' ) or die( 'Cannot open file:  giftcard' . esc_html( $time ) . esc_html( $site_name ) . '.pdf' );
					fwrite( $handle, $output );
					fclose( $handle );
				}

				if ( ! empty( $order_id ) && ! empty( $coupon_code ) ) {
					$dwnld_pdf = fopen( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/giftcard' . esc_html( $order_id ) . esc_html( $coupon_code ) . '.pdf', 'w' ) or die( 'Cannot open file: giftcard' . esc_html( $order_id ) . esc_html( $coupon_code ) . '.pdf' );
					fwrite( $dwnld_pdf, $output );
					fclose( $dwnld_pdf );
				}
			}
		}

		/**
		 * This function is used to set the Selected date format for Email Template.
		 *
		 * @name wps_uwgc_selected_date_format
		 * @param date $selected_date_format contains the date format.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_selected_date_format( $selected_date_format ) {

			if ( isset( $selected_date_format ) && ! empty( $selected_date_format ) ) {
				if ( 'yy/mm/dd' == $selected_date_format ) {
					$selected_date_format = 'Y/m/d';
				} elseif ( 'mm/dd/yy' == $selected_date_format ) {
					$selected_date_format = 'm/d/Y';
				} elseif ( 'd M, yy' == $selected_date_format ) {
					$selected_date_format = 'd M, Y';
				} elseif ( 'DD, d MM, yy' == $selected_date_format ) {
					$selected_date_format = 'l, d F, Y';
				} elseif ( 'yy-mm-dd' == $selected_date_format ) {
					$selected_date_format = 'Y-m-d';
				} elseif ( 'dd/mm/yy' == $selected_date_format ) {
					$selected_date_format = 'd/m/Y';
				} elseif ( 'd.m.Y' == $selected_date_format ) {
					$selected_date_format = 'd.m.Y';
				}
			}
			return $selected_date_format;
		}

		/**
		 * This function is used to set the Selected date format for Email Template.
		 *
		 * @name wps_uwgc_selected_date_format_for_js
		 * @param date $selected_date_format contains the date format.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_selected_date_format_for_js( $selected_date_format ) {

			if ( isset( $selected_date_format ) && ! empty( $selected_date_format ) ) {
				if ( 'Y/m/d' == $selected_date_format ) {
					$selected_date_format = 'yy/mm/dd';
				} elseif ( 'm/d/Y' == $selected_date_format ) {
					$selected_date_format = 'mm/dd/yy';
				} elseif ( 'd M, Y' == $selected_date_format ) {
					$selected_date_format = 'd M, yy';
				} elseif ( 'l, d F, Y' == $selected_date_format ) {
					$selected_date_format = 'DD, d MM, yy';
				} elseif ( 'Y-m-d' == $selected_date_format ) {
					$selected_date_format = 'yy-mm-dd';
				} elseif ( 'd/m/Y' == $selected_date_format ) {
					$selected_date_format = 'dd/mm/yy';
				} elseif ( 'd.m.Y' == $selected_date_format ) {
					$selected_date_format = 'dd.mm.yy';
				}
			} else {
				$selected_date_format = 'yy/mm/dd';
			}
			return $selected_date_format;
		}

		/**
		 * This function is used to create Thankyou Coupon Email Template.
		 *
		 * @name wps_uwgc_thankyou_coupon_template
		 * @param string $mail_header Conatins mail hearder.
		 * @param int    $order_id Conatins order id.
		 * @param string $thankyou_message Conatins thankyou message.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_thankyou_coupon_email_template( $mail_header, $order_id, $thankyou_message ) {

			$wps_uwgc_thankyou_coupon_msg = '<html>
			<body>
			<style>
			body {
				box-shadow: 2px 2px 10px #ccc;
				color: #767676;
				font-family: Arial,sans-serif;
				margin: 80px auto;
				max-width: 700px;
				padding-bottom: 30px;
				width: 100%;
			}

			h2 {
				font-size: 30px;
				margin-top: 0;
				color: #fff;
				padding: 40px;
				background-color: #557da1;
			}

			h4 {
				color: #557da1;
				font-size: 20px;
				margin-bottom: 10px;
			}

			.content {
				padding: 0 40px;
			}

			.Customer-detail ul li p {
				margin: 0;
			}

			.details .Shipping-detail {
				width: 40%;
				float: right;
			}

			.details .Billing-detail {
				width: 60%;
				float: left;
			}

			.details .Shipping-detail ul li,.details .Billing-detail ul li {
				list-style-type: none;
				margin: 0;
			}

			.details .Billing-detail ul,.details .Shipping-detail ul {
				margin: 0;
				padding: 0;
			}

			.clear {
				clear: both;
			}

			table,td,th {
				border: 2px solid #ccc;
				padding: 15px;
				text-align: left;
			}

			table {
				border-collapse: collapse;
				width: 100%;
			}

			.info {
				display: inline-block;
			}

			.bold {
				font-weight: bold;
			}

			.footer {
				margin-top: 30px;
				text-align: center;
				color: #99B1D8;
				font-size: 12px;
			}
			dl.variation dd {
				font-size: 12px;
				margin: 0;
			}
			</style>

			<div style="padding: 36px 48px; background-color:#557DA1;color: #fff; font-size: 30px; font-weight: 300; font-family:helvetica;" class="header">
			' . $mail_header . '
			</div>		

			<div class="content">
			<div class="Order">
			<h4>Order #' . $order_id . '</h4>
			<table>
			<tbody>' . $thankyou_message . '</tbody>
			</table>
			</div>
			</div>
			<div style="text-align: center; padding: 10px;" class="footer">
			</div>
			</body>
			</html>';

			return $wps_uwgc_thankyou_coupon_msg;
		}

		/**
		 * This function is used to generate a Thankyou Gift Coupon
		 *
		 * @name wps_uwgc_create_thankyou_coupon
		 * @param string $thanku_couponnumber Contains coupon number.
		 * @param int    $thnku_couponamount Contains coupon amount.
		 * @param int    $order_id Contains order id.
		 * @param string $wps_wgm_thankyouorder_type Contains order type.
		 * @param int    $user_id Contains order id.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_create_thankyou_coupon( $thanku_couponnumber, $thnku_couponamount, $order_id, $wps_wgm_thankyouorder_type, $user_id ) {

			$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
			$wps_uwgc_thankyou_coupon_settings = get_option( 'wps_wgm_thankyou_order_settings', array() );
			$wps_wgm_thankyouorder_enable = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_enable' );

			if ( isset( $wps_wgm_thankyouorder_enable ) && ! empty( $wps_wgm_thankyouorder_enable ) && 'on' == $wps_wgm_thankyouorder_enable ) {
				$alreadycreated = get_post_meta( $order_id, 'wps_wgm_thankyou_coupon_created', true );
				if ( 'send' !== $alreadycreated ) {
					$coupon_code = $thanku_couponnumber; // Code.
					$amount = $thnku_couponamount; // Amount.
					if ( 'wps_wgm_fixed_thankyou' == $wps_wgm_thankyouorder_type ) {
						$discount_type = 'fixed_cart';
					} else if ( 'wps_wgm_percentage_thankyou' == $wps_wgm_thankyouorder_type ) {
						$discount_type = 'percent';
					}
					$coupon_description = "ThankYou ORDER #$order_id";

					$coupon = array(
						'post_title' => $coupon_code,
						'post_content' => $coupon_description,
						'post_excerpt' => $coupon_description,
						'post_status' => 'publish',
						'post_author' => get_current_user_id(),
						'post_type'     => 'shop_coupon',
					);

					$new_coupon_id = wp_insert_post( $coupon );

					$expiry_date = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thnku_giftcard_expiry' );
					$expiry_date = ( '' == $expiry_date ) ? 1 : $expiry_date;

					$general_settings = get_option( 'wps_wgm_general_settings', array() );
					$individual_use = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_individual_use' );
					$individual_use = ( 'on' == $individual_use ) ? 'yes' : 'no';

					$usage_limit = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_use' );
					$usage_limit = ( '' !== $usage_limit ) ? $usage_limit : 1;

					$free_shipping = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_freeshipping' );
					$free_shipping = ( 'on' == $free_shipping ) ? 'yes' : 'no';

					$minimum_amount = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_minspend' );
					$maximum_amount = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_maxspend' );

					$products_settings = get_option( 'wps_wgm_product_settings', array() );
					$exclude_sale_items = $wps_public_obj->wps_wgm_get_template_data( $products_settings, 'wps_wgm_product_setting_giftcard_ex_sale' );
					$exclude_sale_items = ( 'on' == $exclude_sale_items ) ? 'yes' : 'no';

					$exclude_products = $wps_public_obj->wps_wgm_get_template_data( $products_settings, 'wps_wgm_product_setting_exclude_product' );
					$exclude_products = ( is_array( $exclude_products ) && ! empty( $exclude_products ) ) ? implode( ',', $exclude_products ) : '';

					$exclude_category = $wps_public_obj->wps_wgm_get_template_data( $products_settings, 'wps_wgm_product_setting_exclude_category' );

					$include_products = $wps_public_obj->wps_wgm_get_template_data( $products_settings, 'wps_wgm_product_setting_include_product' );
					$include_products = ( is_array( $include_products ) && ! empty( $include_products ) ) ? implode( ',', $include_products ) : '';

					$include_category = $wps_public_obj->wps_wgm_get_template_data( $products_settings, 'wps_wgm_product_setting_include_category' );

					$todaydate = date_i18n( 'Y-m-d' );
					if ( $expiry_date > 0 || 0 === $expiry_date ) {
						$expirydate = date_i18n( 'Y-m-d', strtotime( "$todaydate +$expiry_date day" ) );
					} else {
						$expirydate = '';
					}
					// Add meta
					// price based on country.
					if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {

						update_post_meta( $new_coupon_id, 'zone_pricing_type', 'exchange_rate' );
					}
					update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
					update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
					update_post_meta( $new_coupon_id, 'individual_use', $individual_use );
					update_post_meta( $new_coupon_id, 'usage_limit', $usage_limit );

					$woo_ver = WC()->version;

					if ( $woo_ver < '3.6.0' ) {
						update_post_meta( $new_coupon_id, 'expiry_date', $expirydate );
					} else {
						$expirydate = strtotime( $expirydate );
						update_post_meta( $new_coupon_id, 'date_expires', $expirydate );
					}

					update_post_meta( $new_coupon_id, 'free_shipping', $free_shipping );
					update_post_meta( $new_coupon_id, 'minimum_amount', $minimum_amount );
					update_post_meta( $new_coupon_id, 'maximum_amount', $maximum_amount );
					update_post_meta( $new_coupon_id, 'exclude_sale_items', $exclude_sale_items );
					update_post_meta( $new_coupon_id, 'exclude_product_categories', $exclude_category );
					update_post_meta( $new_coupon_id, 'exclude_product_ids', $exclude_products );
					update_post_meta( $new_coupon_id, 'product_ids', $include_products );
					update_post_meta( $new_coupon_id, 'product_categories', $include_category );
					update_post_meta( $new_coupon_id, 'wps_uwgc_thankyou_coupon', $order_id );
					update_post_meta( $new_coupon_id, 'wps_uwgc_thankyou_coupon_user', $user_id );
					return true;
				} else {
					return false;
				}
			}

		}

		/**
		 * This function is used to Handle Thankyou Coupon.
		 *
		 * @name wps_uwgc_thankyou_coupon_handle
		 * @param int    $order_id Conatins order id.
		 * @param string $data Conatins data.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_thankyou_coupon_handle( $order_id, $data ) {
			
			$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
			$wps_uwgc_thankyou_coupon_settings = get_option( 'wps_wgm_thankyou_order_settings', array() );

			$wps_uwgc_thankyou_coupon_msg = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyou_message' );

			if ( empty( $wps_uwgc_thankyou_coupon_msg ) ) {
				$wps_uwgc_thankyou_coupon_msg = 'You have received a coupon [COUPONCODE], having amount of [COUPONAMOUNT] with the expiration date of [COUPONEXPIRY]';
			}

			$subject = __( 'Hurry! Thankyou Coupon is received', 'giftware' );
			$mail_header = __( 'Thankyou Gift card Coupon', 'giftware' );

			$wps_uwgc_thankyou_coupon_msg = $this->wps_uwgc_thankyou_coupon_email_template( $mail_header, $order_id, $wps_uwgc_thankyou_coupon_msg );
			// price based on country.
			if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
				if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
					$wps_uwgc_thankyouorder_min = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_minimum' );
					$wps_uwgc_thankyouorder_min = ( '' !== $wps_uwgc_thankyouorder_min ) ? $wps_uwgc_thankyouorder_min : array();

					$wps_uwgc_thankyouorder_max = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_maximum' );
					$wps_uwgc_thankyouorder_max = ( '' !== $wps_uwgc_thankyouorder_max ) ? $wps_uwgc_thankyouorder_max : array();

					$wps_uwgc_thankyouorder_value = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_current_type' );
					$wps_uwgc_thankyouorder_value = ( '' !== $wps_uwgc_thankyouorder_value ) ? $wps_uwgc_thankyouorder_value : array();

					if ( is_array( $wps_uwgc_thankyouorder_value ) && ! empty( $wps_uwgc_thankyouorder_value ) && count( $wps_uwgc_thankyouorder_value ) ) {
						foreach ( $wps_uwgc_thankyouorder_value as $key => $value ) {
							if ( isset( $wps_uwgc_thankyouorder_min[ $key ] ) && ! empty( $wps_uwgc_thankyouorder_min[ $key ] ) && isset( $wps_uwgc_thankyouorder_max[ $key ] ) && ! empty( $wps_uwgc_thankyouorder_max[ $key ] ) ) {
								$wps_uwgc_thankyouorder_min[ $key ] = wcpbc_the_zone()->get_exchange_rate_price( $wps_uwgc_thankyouorder_min[ $key ] );
								$wps_uwgc_thankyouorder_max[ $key ] = wcpbc_the_zone()->get_exchange_rate_price( $wps_uwgc_thankyouorder_max[ $key ] );

							} else if ( isset( $wps_uwgc_thankyouorder_min[ $key ] ) && ! empty( $wps_uwgc_thankyouorder_min[ $key ] ) && empty( $wps_uwgc_thankyouorder_max[ $key ] ) ) {

								$wps_uwgc_thankyouorder_min[ $key ] = wcpbc_the_zone()->get_exchange_rate_price( $wps_uwgc_thankyouorder_min[ $key ] );
							}
						}
					}
				}
			} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
				$wps_uwgc_thankyouorder_min = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_minimum' );
				$wps_uwgc_thankyouorder_min = ( '' !== $wps_uwgc_thankyouorder_min ) ? $wps_uwgc_thankyouorder_min : array();

				$wps_uwgc_thankyouorder_max = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_maximum' );
				$wps_uwgc_thankyouorder_max = ( '' !== $wps_uwgc_thankyouorder_max ) ? $wps_uwgc_thankyouorder_max : array();

				$wps_uwgc_thankyouorder_value = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_current_type' );
				$wps_uwgc_thankyouorder_value = ( '' !== $wps_uwgc_thankyouorder_value ) ? $wps_uwgc_thankyouorder_value : array();

				if ( is_array( $wps_uwgc_thankyouorder_value ) && ! empty( $wps_uwgc_thankyouorder_value ) && count( $wps_uwgc_thankyouorder_value ) ) {
					foreach ( $wps_uwgc_thankyouorder_value as $key => $value ) {
						if ( isset( $wps_uwgc_thankyouorder_min[ $key ] ) && ! empty( $wps_uwgc_thankyouorder_min[ $key ] ) && isset( $wps_uwgc_thankyouorder_max[ $key ] ) && ! empty( $wps_uwgc_thankyouorder_max[ $key ] ) ) {
							$to_currency                        = get_post_meta( $order_id, '_order_currency', true );
							$wps_uwgc_thankyouorder_min[ $key ] = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( $to_currency, $wps_uwgc_thankyouorder_min[ $key ] );
							$wps_uwgc_thankyouorder_max[ $key ] = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( $to_currency, $wps_uwgc_thankyouorder_max[ $key ] );

						} else if ( isset( $wps_uwgc_thankyouorder_min[ $key ] ) && ! empty( $wps_uwgc_thankyouorder_min[ $key ] ) && empty( $wps_uwgc_thankyouorder_max[ $key ] ) ) {
							$to_currency                        = get_post_meta( $order_id, '_order_currency', true );
							$wps_uwgc_thankyouorder_min[ $key ] = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( $to_currency, $wps_uwgc_thankyouorder_min[ $key ] );
						}
					}
				}
			} else {
				$wps_uwgc_thankyouorder_min = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_minimum' );
				$wps_uwgc_thankyouorder_min = ( '' !== $wps_uwgc_thankyouorder_min ) ? $wps_uwgc_thankyouorder_min : array();

				$wps_uwgc_thankyouorder_max = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_maximum' );
				$wps_uwgc_thankyouorder_max = ( '' !== $wps_uwgc_thankyouorder_max ) ? $wps_uwgc_thankyouorder_max : array();

				$wps_uwgc_thankyouorder_value = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_current_type' );
				$wps_uwgc_thankyouorder_value = ( '' !== $wps_uwgc_thankyouorder_value ) ? $wps_uwgc_thankyouorder_value : array();
			}

			$wps_wgm_thankyouorder_enable = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_enable' );
			$wps_wgm_thankyouorder_type = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_type' );
			$wps_wgm_thankyouorder_type = ( '' !== $wps_wgm_thankyouorder_type ) ? $wps_wgm_thankyouorder_type : 'wps_wgm_fixed_thankyou';

			$wps_wgm_thankyouorder_time = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_time' );
			$wps_wgm_thankyouorder_time = ( '' !== $wps_wgm_thankyouorder_time ) ? $wps_wgm_thankyouorder_time : 'wps_wgm_order_completed';
			$wps_wgm_thankyouorder_number = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_number' );
			$wps_wgm_thankyouorder_number = ( '' !== $wps_wgm_thankyouorder_number ) ? $wps_wgm_thankyouorder_number : 1;
			$wps_wgm_general_settings = get_option( 'wps_wgm_general_settings', array() );
			$wps_uwgc_thankyou_coupon_length = $wps_public_obj->wps_wgm_get_template_data( $wps_wgm_general_settings, 'wps_wgm_general_setting_giftcard_coupon_length' );
			$wps_uwgc_thankyou_coupon_length = ( '' !== $wps_uwgc_thankyou_coupon_length ) ? $wps_uwgc_thankyou_coupon_length : 5;

			$order       = wc_get_order( $order_id );
			$order_total = $order->get_total();
			if ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_to_base_currency' ) ) {
				$from_currency = get_post_meta( $order_id, '_order_currency', true );
				$order_total   = wps_mmcsfw_admin_fetch_currency_rates_to_base_currency( $from_currency, $order_total );
			}
			if ( isset( $wps_wgm_thankyouorder_enable ) && ! empty( $wps_wgm_thankyouorder_enable ) && 'on' == $wps_wgm_thankyouorder_enable ) {
				$coupon_alreadycreated = get_post_meta( $order_id, 'wps_wgm_thankyou_coupon_created', true );

				if ( 'send' == $coupon_alreadycreated ) {
					return;
				}
				$user_email = $order->get_billing_email();
				$user_id = $order->get_user_id();
				$user = get_user_by( 'ID', $user_id );
				if ( 'wps_wgm_order_creation' == $wps_wgm_thankyouorder_time || 'wps_wgm_order_processing' == $wps_wgm_thankyouorder_time || 'wps_wgm_order_completed' == $wps_wgm_thankyouorder_time ) {
					$thankyou_user_order = (int) get_user_meta( $user_id, 'thankyou_order_number', true );
					if ( $thankyou_user_order >= $wps_wgm_thankyouorder_number ) {
						if ( is_array( $wps_uwgc_thankyouorder_value ) && ! empty( $wps_uwgc_thankyouorder_value ) ) {
							foreach ( $wps_uwgc_thankyouorder_value as $key => $value ) {
								$coupon_alreadycreated = get_post_meta( $order_id, 'wps_wgm_thankyou_coupon_created', true );
								if ( 'send' == $coupon_alreadycreated ) {
									return;
								}

								if ( isset( $wps_uwgc_thankyouorder_min[ $key ] ) && ( ! empty( $wps_uwgc_thankyouorder_min[ $key ] ) || '0' == $wps_uwgc_thankyouorder_min[ $key ] ) && isset( $wps_uwgc_thankyouorder_max[ $key ] ) && ! empty( $wps_uwgc_thankyouorder_max[ $key ] ) ) {
									if ( $wps_uwgc_thankyouorder_min[ $key ] <= $order_total && $order_total <= $wps_uwgc_thankyouorder_max[ $key ] ) {
										$thanku_couponnumber = wps_wgm_coupon_generator( $wps_uwgc_thankyou_coupon_length );
										$thnku_couponamount = $wps_uwgc_thankyouorder_value[ $key ];
										if ( $this->wps_uwgc_create_thankyou_coupon( $thanku_couponnumber, $thnku_couponamount, $order_id, $wps_wgm_thankyouorder_type, $user_id ) ) {
											$coupon_creation = true;
											$the_coupon = new WC_Coupon( $thanku_couponnumber );
											$thnku_couponamount = $the_coupon->get_amount();
											$expiry_date_timestamp = $the_coupon->get_date_expires();
											$date_format = get_option( 'date_format' );
											if ( ! isset( $date_format ) && empty( $date_format ) ) {
												$date_format = 'Y-m-d';
											}
											if ( ! empty( $expiry_date_timestamp ) && isset( $expiry_date_timestamp ) ) {
												$expiry_date_timestamp = strtotime( $expiry_date_timestamp );
											}
											if ( empty( $expiry_date_timestamp ) ) {
												$expirydate_format = __( 'No Expiration', 'giftware' );
											} else {
												$expirydate_format = date_i18n( $date_format, $expiry_date_timestamp );
											}
											$bloginfo = get_bloginfo();
											$headers = array( 'Content-Type: text/html; charset=UTF-8' );
											$wps_uwgc_thankyou_coupon_msg = str_replace( '[COUPONCODE]', $thanku_couponnumber, $wps_uwgc_thankyou_coupon_msg );
											if ( 'wps_wgm_fixed_thankyou' == $wps_wgm_thankyouorder_type ) {
												if ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
													$to_currency                  = get_post_meta( $order_id, '_order_currency', true );
													$thnku_couponamount           = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( $to_currency, $thnku_couponamount );
													$wps_uwgc_thankyou_coupon_msg = str_replace( '[COUPONAMOUNT]', wps_mmcsfw_get_custom_currency_symbol( $to_currency ) . ( $thnku_couponamount ), $wps_uwgc_thankyou_coupon_msg );
												} else {
													$wps_uwgc_thankyou_coupon_msg = str_replace( '[COUPONAMOUNT]', wc_price( $thnku_couponamount ), $wps_uwgc_thankyou_coupon_msg );
												}
											} else if ( 'wps_wgm_percentage_thankyou' == $wps_wgm_thankyouorder_type ) {
												$wps_uwgc_thankyou_coupon_msg = str_replace( '[COUPONAMOUNT]', $thnku_couponamount . '%', $wps_uwgc_thankyou_coupon_msg );
											}
											$wps_uwgc_thankyou_coupon_msg = str_replace( '[COUPONEXPIRY]', $expirydate_format, $wps_uwgc_thankyou_coupon_msg );
											wc_mail( $user_email, $subject, $wps_uwgc_thankyou_coupon_msg, $headers );

											update_post_meta( $order_id, 'wps_wgm_thankyou_coupon_created', 'send' );
										}
									}
								} else if ( isset( $wps_uwgc_thankyouorder_min[ $key ] ) && ( ! empty( $wps_uwgc_thankyouorder_min[ $key ] ) || '0' == $wps_uwgc_thankyouorder_min[ $key ] ) && empty( $wps_uwgc_thankyouorder_max[ $key ] ) ) {

									if ( $wps_uwgc_thankyouorder_min[ $key ] <= $order_total ) {

										$thanku_couponnumber = wps_wgm_coupon_generator( $wps_uwgc_thankyou_coupon_length );
										$thnku_couponamount = $wps_uwgc_thankyouorder_value[ $key ];
										if ( $this->wps_uwgc_create_thankyou_coupon( $thanku_couponnumber, $thnku_couponamount, $order_id, $wps_wgm_thankyouorder_type, $user_id ) ) {
											$coupon_creation = true;
											$the_coupon = new WC_Coupon( $thanku_couponnumber );
											$thnku_couponamount = $the_coupon->get_amount();
											$expiry_date_timestamp = $the_coupon->get_date_expires();
											$date_format = get_option( 'date_format' );
											if ( ! isset( $date_format ) && empty( $date_format ) ) {
												$date_format = 'Y-m-d';
											}
											if ( ! empty( $expiry_date_timestamp ) && isset( $expiry_date_timestamp ) ) {
												$expiry_date_timestamp = strtotime( $expiry_date_timestamp );
											}
											if ( empty( $expiry_date_timestamp ) ) {
												$expirydate_format = __( 'No Expiration', 'giftware' );
											} else {
												$expirydate_format = date_i18n( $date_format, $expiry_date_timestamp );
											}
											$bloginfo = get_bloginfo();
											$headers = array( 'Content-Type: text/html; charset=UTF-8' );
											$wps_uwgc_thankyou_coupon_msg = str_replace( '[COUPONCODE]', $thanku_couponnumber, $wps_uwgc_thankyou_coupon_msg );
											if ( 'wps_wgm_fixed_thankyou' == $wps_wgm_thankyouorder_type ) {
												if ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
													$to_currency                  = get_post_meta( $order_id, '_order_currency', true );
													$thnku_couponamount           = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( $to_currency, $thnku_couponamount );
													$wps_uwgc_thankyou_coupon_msg = str_replace( '[COUPONAMOUNT]', wps_mmcsfw_get_custom_currency_symbol( $to_currency ) . ( $thnku_couponamount ), $wps_uwgc_thankyou_coupon_msg );
												} else {
													$wps_uwgc_thankyou_coupon_msg = str_replace( '[COUPONAMOUNT]', wc_price( $thnku_couponamount ), $wps_uwgc_thankyou_coupon_msg );
												}
											} else if ( 'wps_wgm_percentage_thankyou' == $wps_wgm_thankyouorder_type ) {
												$wps_uwgc_thankyou_coupon_msg = str_replace( '[COUPONAMOUNT]', $thnku_couponamount . '%', $wps_uwgc_thankyou_coupon_msg );
											}
											$wps_uwgc_thankyou_coupon_msg = str_replace( '[COUPONEXPIRY]', $expirydate_format, $wps_uwgc_thankyou_coupon_msg );
											wc_mail( $user_email, $subject, $wps_uwgc_thankyou_coupon_msg, $headers );

											update_post_meta( $order_id, 'wps_wgm_thankyou_coupon_created', 'send' );
										}
									}
								} else if ( isset( $wps_uwgc_thankyouorder_value[ $key ] ) && ! empty( $wps_uwgc_thankyouorder_value[ $key ] ) && empty( $wps_uwgc_thankyouorder_min[ $key ] ) && empty( $wps_uwgc_thankyouorder_max[ $key ] ) ) {
									$thanku_couponnumber = wps_wgm_coupon_generator( $wps_uwgc_thankyou_coupon_length );
									$thnku_couponamount = $wps_uwgc_thankyouorder_value[ $key ];
									if ( $this->wps_uwgc_create_thankyou_coupon( $thanku_couponnumber, $thnku_couponamount, $order_id, $wps_wgm_thankyouorder_type, $user_id ) ) {
										$coupon_creation = true;
										$the_coupon = new WC_Coupon( $thanku_couponnumber );
										$thnku_couponamount = $the_coupon->get_amount();
										if ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
											$to_currency        = get_post_meta( $order_id, '_order_currency', true );
											$thnku_couponamount = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( $to_currency, $thnku_couponamount );
										}
										$expiry_date_timestamp = $the_coupon->get_date_expires();
										$date_format = get_option( 'date_format' );
										if ( ! isset( $date_format ) && empty( $date_format ) ) {
											$date_format = 'Y-m-d';
										}
										if ( ! empty( $expiry_date_timestamp ) && isset( $expiry_date_timestamp ) ) {
											$expiry_date_timestamp = strtotime( $expiry_date_timestamp );
										}
										if ( empty( $expiry_date_timestamp ) ) {
											$expirydate_format = __( 'No Expiration', 'giftware' );
										} else {
											$expirydate_format = date_i18n( $date_format, $expiry_date_timestamp );
										}
										$bloginfo = get_bloginfo();
										$headers = array( 'Content-Type: text/html; charset=UTF-8' );
										$wps_uwgc_thankyou_coupon_msg = str_replace( '[COUPONCODE]', $thanku_couponnumber, $wps_uwgc_thankyou_coupon_msg );
										if ( 'wps_wgm_fixed_thankyou' == $wps_wgm_thankyouorder_type ) {
											if ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
												$to_currency                  = get_post_meta( $order_id, '_order_currency', true );
												$thnku_couponamount           = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( $to_currency, $thnku_couponamount );
												$wps_uwgc_thankyou_coupon_msg = str_replace( '[COUPONAMOUNT]', wps_mmcsfw_get_custom_currency_symbol( $to_currency ) . ( $thnku_couponamount ), $wps_uwgc_thankyou_coupon_msg );
											} else {
												$wps_uwgc_thankyou_coupon_msg = str_replace( '[COUPONAMOUNT]', wc_price( $thnku_couponamount ), $wps_uwgc_thankyou_coupon_msg );
											}
										} else if ( 'wps_wgm_percentage_thankyou' == $wps_wgm_thankyouorder_type ) {
											$wps_uwgc_thankyou_coupon_msg = str_replace( '[COUPONAMOUNT]', $thnku_couponamount . '%', $wps_uwgc_thankyou_coupon_msg );
										}
										$wps_uwgc_thankyou_coupon_msg = str_replace( '[COUPONEXPIRY]', $expirydate_format, $wps_uwgc_thankyou_coupon_msg );
										wc_mail( $user_email, $subject, $wps_uwgc_thankyou_coupon_msg, $headers );

										update_post_meta( $order_id, 'wps_wgm_thankyou_coupon_created', 'send' );
									}
								}
							}
						}
					}
				}
			}
		}

		/**
		 * This function is used for Resend Mail.
		 *
		 * @name wps_uwgc_resend_mail_common_function
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_resend_mail_common_function() {
			$response['result'] = false;
			$response['message'] = __( 'Mail sending failed due to some issue. Please try again.', 'giftware' );
			$woo_ver = WC()->version;
			$wps_admin_obj = new Woocommerce_Gift_Cards_Common_Function();
			$general_setting = get_option( 'wps_wgm_general_settings', array() );
			$selected_date = $wps_admin_obj->wps_wgm_get_template_data( $general_setting, 'wps_wgm_general_setting_enable_selected_format' );
			$giftcard_pdf_prefix = $wps_admin_obj->wps_wgm_get_template_data( $general_setting, 'wps_wgm_general_setting_pdf_prefix' );

			$other_settings = get_option( 'wps_wgm_other_settings', array() );
			$delivery_settings = get_option( 'wps_wgm_delivery_settings', array() );

			$mail_template_settings = get_option( 'wps_wgm_mail_settings', array() );
			if ( isset( $_POST['order_id'] ) && ! empty( $_POST['order_id'] ) ) {
				$order_id = isset( $_POST['order_id'] ) ? sanitize_text_field( wp_unslash( $_POST['order_id'] ) ) : 0;
				$order = wc_get_order( $order_id );
				foreach ( $order->get_items() as $item_id => $item ) {
					if ( $woo_ver < '3.0.0' ) {
						$product = $order->get_product_from_item( $item );
					} else {
						$product = $item->get_product();
					}

					$gift_img_name = '';
					$mailsend = false;
					$from = '';
					$gift_msg = '';
					$item_meta_data = $item->get_meta_data();
					$giftcard_date_check = false;
					$gift_date = '';
					$from = '';
					$gift_msg = '';
					foreach ( $item_meta_data as $key => $value ) {
						if ( isset( $value->key ) && 'To' == $value->key && ! empty( $value->value ) ) {
							$mailsend = true;
							$to = $value->value;
						}
						if ( isset( $value->key ) && 'To Name' == $value->key && ! empty( $value->value ) ) {
							$mailsend = true;
							$to_name = $value->value;
						}
						if ( isset( $value->key ) && 'From' == $value->key && ! empty( $value->value ) ) {
							$mailsend = true;
							$from = $value->value;
						}
						if ( isset( $value->key ) && 'Message' == $value->key && ! empty( $value->value ) ) {
							$mailsend = true;
							$gift_msg = $value->value;
						}
						if ( isset( $value->key ) && 'Image' == $value->key && ! empty( $value->value ) ) {
							$mailsend = true;
							$gift_img_name = $value->value;
						}
						if ( isset( $value->key ) && 'Send Date' == $value->key && ! empty( $value->value ) ) {
							$giftcard_date_check = true;
							$gift_date = $value->value;
						}
						if ( isset( $value->key ) && 'Delivery Method' == $value->key && ! empty( $value->value ) ) {
							$mailsend = true;
							$delivery_method = $value->value;
						}
						if ( isset( $value->key ) && 'Selected Template' == $value->key && ! empty( $value->value ) ) {
							$mailsend = true;
							$selected_template = $value->value;
						}
						if ( isset( $value->key ) && 'Reciever Contact' == $value->key && ! empty( $value->value ) ) {
							$mailsend = true;
							$contact_no = $value->value;
						}
					}
					if ( ! isset( $to ) && empty( $to ) ) {
						if ( 'Mail to recipient' == $delivery_method ) {
							$to = $order->get_billing_email();
						} else {
							$to = '';
						}
					}
					if ( $giftcard_date_check ) {
						$mailsend = true;

						if ( is_string( $gift_date ) ) {
							if ( isset( $selected_date ) && null !== $selected_date && '' !== $selected_date ) {
								if ( 'd/m/Y' == $selected_date ) {
									$gift_date = str_replace( '/', '-', $gift_date );
								}
							}
							$senddatetime = strtotime( $gift_date );
						}
						$senddate = date_i18n( 'Y-m-d', $senddatetime );
						$todaytime = time();
						$todaydate = date_i18n( 'Y-m-d', $todaytime );
						$senddatetime = strtotime( "$senddate" );
						$todaytime = strtotime( "$todaydate" );
						$giftdiff = $senddatetime - $todaytime;

						$giftdiff = $senddatetime - $todaytime;
						if ( isset( $delivery_method ) && 'Mail to recipient' == $delivery_method ) {
							if ( $giftdiff > 0 ) {
								$gift_couponnumber = get_post_meta( $order_id, "$order_id#$item_id", true );
								if ( empty( $gift_couponnumber ) ) {
									$response['message'] = __( 'Gift card Scheduled Date has not been reached for some products.', 'giftware' );
									continue;
								}
							}
						}
					}
					if ( $mailsend ) {
						$gift_order = true;
						$product_id = $product->get_id();
						$gift_couponnumber = get_post_meta( $order_id, "$order_id#$item_id", true );

						if ( empty( $gift_couponnumber ) ) {
							$gift_couponnumber = get_post_meta( $order_id, "$order_id#$product_id", true );
						}
						foreach ( $gift_couponnumber as $key => $value ) {
							$the_coupon = new WC_Coupon( $value );
							if ( $the_coupon->is_type( 'fixed_cart' ) ) {
								$coupon_amount = $the_coupon->get_amount();
								if ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
									$to_currency   = get_post_meta( $order_id, '_order_currency', true );
									$coupon_amount = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( $to_currency, $coupon_amount );
									$coupon_amount = wps_mmcsfw_get_custom_currency_symbol( $to_currency ) . $coupon_amount;
								} else {
									$coupon_amount = wc_price( $coupon_amount );
								}
							}
							$currenttime = time();
							$expiry_date_timestamp = $the_coupon->get_date_expires();
							if ( isset( $expiry_date_timestamp ) && ! empty( $expiry_date_timestamp ) ) {
								$expiry_date_timestamp = date_format( $expiry_date_timestamp, 'Y-m-d' );
								$expiry_date_timestamp = strtotime( $expiry_date_timestamp );
							} else {
								$expiry_date_timestamp = '';
							}
							if ( empty( $expiry_date_timestamp ) ) {
								$expirydate_format = __( 'No Expiration', 'giftware' );
							} else {
								$expirydate = date_i18n( 'Y-m-d', $expiry_date_timestamp );
								$expirydate_format = date_create( $expirydate );

								if ( isset( $selected_date ) && null !== $selected_date && '' !== $selected_date ) {

									$selected_date = $this->wps_uwgc_selected_date_format( $selected_date );
									$expirydate_format = date_i18n( $selected_date, $expiry_date_timestamp );

								} else {
									$expirydate_format = date_format( $expirydate_format, 'jS M Y' );
								}
								if ( $currenttime > $expiry_date_timestamp ) {
									$response['result'] = false;
									$response['message'] = __( 'Your Giftcard Coupon is expired.', 'giftware' );
									echo json_encode( $response );
									wp_die();
								}
							}
							$wps_wgm_pricing = ! empty( get_post_meta( $product_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $product_id, 'wps_wgm_pricing', true ) : get_post_meta( $product_id, 'wps_wgm_pricing_details', true );

							$templateid = $wps_wgm_pricing['template'];

							if ( is_array( $templateid ) && array_key_exists( 0, $templateid ) ) {
								$temp = $templateid[0];
							} else {
								$temp = $templateid;
							}
							$args['from'] = $from;
							$args['to'] = isset( $to_name ) ? $to_name : $to;
							$args['message'] = stripcslashes( $gift_msg );
							$args['coupon'] = apply_filters( 'wps_wgm_qrcode_coupon', $value );
							$args['expirydate'] = $expirydate_format;
							$args['amount'] = $coupon_amount;
							$args['templateid'] = isset( $selected_template ) && ! empty( $selected_template ) ? $selected_template : $temp;
							$args['product_id'] = isset( $product_id ) ? $product_id : '';
							$args['contact_no'] = isset( $contact_no ) ? $contact_no : '';
							$args['order_id'] = isset( $order_id ) ? $order_id : '';
							$args['item_id'] = isset( $item_id ) ? $item_id : '';
							$args['send_date'] = isset( $gift_date ) ? $gift_date : '';
							$args['delivery_method']   = isset( $delivery_method ) ? $delivery_method : '';
							$browse_enable = $wps_admin_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_other_setting_browse' );

							if ( 'on' == $browse_enable ) {

								if ( '' !== $gift_img_name ) {

									$args['browse_image'] = $gift_img_name;
								}
							}

							// Update the array according to the Customized giftcard.
							$updated_arr = apply_filters( 'wps_wgm_resend_mail_arr_update', $args, $item );
							$message = apply_filters( 'wps_wgm_customizable_email_template', $wps_admin_obj->wps_wgm_create_gift_template( $args ), $updated_arr );
							$wps_uwgc_pdf_enable = $wps_admin_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_addition_pdf_enable' );

							if ( isset( $wps_uwgc_pdf_enable ) && 'on' == $wps_uwgc_pdf_enable ) {
								$site_name = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '';
								$time = time();

								$this->wps_uwgc_attached_pdf( $message, $site_name, $time, $order_id, $value );
								if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
									$attachments = array( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/' . $giftcard_pdf_prefix . $value . '.pdf' );
								} else {
									$attachments = array( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/giftcard' . $time . $site_name . '.pdf' );
								}
							} else {
								$attachments = array();
							}
							$get_mail_status = true;
							$get_mail_status = apply_filters( 'wps_send_mail_status', $get_mail_status );
							if ( $get_mail_status ) {
								if ( isset( $delivery_method ) && 'Mail to recipient' == $delivery_method ) {
									$subject = $wps_admin_obj->wps_wgm_get_template_data( $mail_template_settings, 'wps_wgm_mail_setting_giftcard_subject' );
								}
								if ( isset( $delivery_method ) && 'Downloadable' == $delivery_method ) {
									$subject = $wps_admin_obj->wps_wgm_get_template_data( $mail_template_settings, 'wps_wgm_mail_setting_giftcard_subject_downloadable' );
								}
								if ( isset( $delivery_method ) && 'shipping' == $delivery_method ) {
									$subject = $wps_admin_obj->wps_wgm_get_template_data( $mail_template_settings, 'wps_wgm_mail_setting_giftcard_subject_shipping' );

								}
								$bloginfo = get_bloginfo();
								if ( empty( $subject ) || ! isset( $subject ) ) {

									$subject = "$bloginfo:";
									$subject .= __( ' Hurry!!! Gift Card is Received', 'giftware' );
								}
								$subject = str_replace( '[SITENAME]', $bloginfo, $subject );
								$subject = str_replace( '[FROM]', $from, $subject );
								$subject = str_replace( '[ORDERID]', $order_id, $subject );
								$subject = html_entity_decode( $subject, ENT_QUOTES, 'UTF-8' );

								if ( isset( $delivery_method ) ) {
									if ( 'Mail to recipient' == $delivery_method ) {
										$woo_ver = WC()->version;
										if ( $woo_ver < '3.0.0' ) {
											$from = $order->billing_email;
										} else {
											$from = $order->get_billing_email();
										}
									}
									if ( 'Downloadable' == $delivery_method ) {
										$woo_ver = WC()->version;
										if ( $woo_ver < '3.0.0' ) {
											$to = $order->billing_email;
										} else {
											$to = $order->get_billing_email();
										}
									}
									if ( 'shipping' == $delivery_method ) {
										$admin_email = get_option( 'admin_email' );
										$wps_change_admin_email = $wps_admin_obj->wps_wgm_get_template_data( $delivery_settings, 'wps_wgm_change_admin_email_for_shipping' );
										$alternate_email = ! empty( $wps_change_admin_email ) ? $wps_change_admin_email : $admin_email;
										$to = $alternate_email;
									}
								}

								$wps_uwgc_bcc_enable = $wps_admin_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_addition_bcc_option_enable' );
								if ( isset( $wps_uwgc_bcc_enable ) && 'on' == $wps_uwgc_bcc_enable ) {
									$headers[] = 'Bcc:' . $from;

									wc_mail( $to, $subject, $message, $headers, $attachments );
									do_action( 'wps_uwgc_mail_send_to_someone', $subject, $message, $attachments );
									if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
										unlink( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/' . $giftcard_pdf_prefix . $value . '.pdf' );
									} elseif ( isset( $time ) && isset( $site_name ) && ! empty( $time ) && ! empty( $site_name ) ) {
										unlink( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/giftcard' . $time . $site_name . '.pdf' );
									}
								} else {
									$headers = array( 'Content-Type: text/html; charset=UTF-8' );
									wc_mail( $to, $subject, $message, $headers, $attachments );
									do_action( 'wps_uwgc_mail_send_to_someone', $subject, $message, $attachments );
									if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
										unlink( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/' . $giftcard_pdf_prefix . $value . '.pdf' );
									} elseif ( isset( $time ) && isset( $site_name ) && ! empty( $time ) && ! empty( $site_name ) ) {
										unlink( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/giftcard' . $time . $site_name . '.pdf' );
									}
								}

								$subject = $wps_admin_obj->wps_wgm_get_template_data( $mail_template_settings, 'wps_wgm_mail_setting_receive_subject' );
								$subject = str_replace( '[TO]', $to, $subject );
								$message = $wps_admin_obj->wps_wgm_get_template_data( $mail_template_settings, 'wps_wgm_mail_setting_receive_message' );

								if ( empty( $subject ) || ! isset( $subject ) ) {

									$subject = "$bloginfo:";
									$subject .= __( ' Gift Card is Sent Successfully', 'giftware' );
								}

								if ( empty( $message ) || ! isset( $message ) ) {

									$message = "$bloginfo:";
									$message .= __( ' Gift Card is Sent Successfully to the Email Id: [TO]', 'giftware' );
								}

								$message = stripcslashes( $message );
								$message = str_replace( '[TO]', $to, $message );
								$subject = stripcslashes( $subject );

								$disable_buyer_notification = $wps_admin_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_disable_buyer_notification' );

								if ( 'on' !== $disable_buyer_notification && 'Mail to recipient' == $delivery_method ) {
									wc_mail( $from, $subject, $message );
								}
							}

							$response['result'] = true;
							$response['message'] = __( 'Email Sent Successfully!', 'giftware' );
						}
					}
				}
			}
			echo json_encode( $response );
			wp_die();
		}

	}
}


