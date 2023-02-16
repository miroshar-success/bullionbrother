<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
if ( ! class_exists( 'Woocommerce_Gift_Cards_Common_Function' ) ) {

	/**
	 * Define the common functionality.
	 *
	 * @since      1.0.0
	 * @package    woo-gift-cards-lite
	 * @author     WP Swings <webmaster@wpswings.com>
	 */
	class Woocommerce_Gift_Cards_Common_Function {

		/**
		 * This function is used to create giftcard template.
		 *
		 * @since 1.0.0
		 * @name wps_wgm_create_gift_template
		 * @param array $args args.
		 * @return $templatehtml.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_wgm_create_gift_template( $args ) {

			if ( isset( $args ) && is_array( $args ) && ! empty( $args ) ) {
				$templateid = $args['templateid'];
				$product_id = array_key_exists( 'product_id', $args ) ? $args['product_id'] : '';

				$wps_wgm_recommanded_per_product = get_post_meta( $product_id, 'wps_wgm_recommanded_per_product', true );
				$recommand_product = '';
				if ( isset( $wps_wgm_recommanded_per_product ) && ! empty( $wps_wgm_recommanded_per_product ) ) {

					foreach ( $wps_wgm_recommanded_per_product as $key => $val ) {
						$recommand_prod = wc_get_product( $val );
						$recommand_product .= '<style>.ugcfw__product_table_img img {width: 125px;height: 125px;}.ugcfw__product_table_name h2 {text-align:center; padding: 0;margin: 0;font-size: 24px;line-height: 28px;text-transform: capitalize;}</style><table class="ugcfw__product_table"><tr class="ugcfw__product_table_row"><td class="ugcfw__product_table_img" style="vertical-align: top;padding-bottom:15px;width:125px;">' . wp_kses_post( $recommand_prod->get_image() ) . '</td><td class="ugcfw__product_table_name" style="padding: 0 0 15px 25px;width: 450px;text-align: center;"><h2 style="text-align:center;padding: 0;margin: 0;font-size: 24px!important;line-height: 28px;text-transform: capitalize;">' . esc_html( $recommand_prod->get_name() ) . '</h2><p class="ugcfw__product_table_price" style="padding: 10px 0;margin: 0;font-size: 20px;line-height: 28px;font-weight: bold;">' . esc_html( get_woocommerce_currency_symbol() ) . esc_html( $recommand_prod->get_price() ) . '</p><p class="ugcfw__product_table_link" style="margin: 0;padding: 0;line-height: 28px;color: #208fe9;text-align: center;"><a href=' . esc_url( $recommand_prod->get_permalink() ) . '>' . esc_html( $recommand_prod->get_name() ) . '</a></p></td></tr></table>';
					}
				}
				$template = get_post( $templateid, ARRAY_A );
				$templatehtml = $template['post_content'];
				$giftcard_logo_html = '';
				$giftcard_featured = '';
				$giftcard_event_html = '';
				$wps_wgm_mail_settings = get_option( 'wps_wgm_mail_settings', array() );
				$giftcard_upload_logo = $this->wps_wgm_get_template_data( $wps_wgm_mail_settings, 'wps_wgm_mail_setting_upload_logo' );

				$giftcard_logo_height = $this->wps_wgm_get_template_data( $wps_wgm_mail_settings, 'wps_wgm_mail_setting_upload_logo_dimension_height' );

				$giftcard_logo_width = $this->wps_wgm_get_template_data( $wps_wgm_mail_settings, 'wps_wgm_mail_setting_upload_logo_dimension_width' );
				if ( empty( $giftcard_logo_height ) ) {
					$giftcard_logo_height = 70;
				}
				if ( empty( $giftcard_logo_width ) ) {
					$giftcard_logo_width = 70;
				}

				if ( isset( $giftcard_upload_logo ) && ! empty( $giftcard_upload_logo ) ) {
					$giftcard_logo_html = "<img src='$giftcard_upload_logo' width='" . $giftcard_logo_width . "px' height='" . $giftcard_logo_height . "px'/>";
				}

				$giftcard_disclaimer = $this->wps_wgm_get_template_data( $wps_wgm_mail_settings, 'wps_wgm_mail_setting_disclaimer' );
				$giftcard_disclaimer = stripcslashes( $giftcard_disclaimer );

				$background_image = wp_get_attachment_url( get_post_thumbnail_id( $product_id ) );
				if ( empty( $background_image ) ) {
					$background_image = $this->wps_wgm_get_template_data( $wps_wgm_mail_settings, 'wps_wgm_mail_setting_background_logo_value' );

				}
				$featured_image = wp_get_attachment_url( get_post_thumbnail_id( $templateid ) );

				if ( isset( $background_image ) && ! empty( $background_image ) ) {

					$giftcard_event_html = "<img src='$background_image' 
					width='100%' />";
				}
				$giftcard_event_html = apply_filters( 'wps_wgm_default_events_html', $giftcard_event_html, $args );
				if ( isset( $featured_image ) && ! empty( $featured_image ) ) {
					$giftcard_featured = "<img src='$featured_image'/>";
				}
				$template_css = '';
				$template_css = apply_filters( 'wps_wgm_template_custom_css', $template_css, $templateid );

				if ( null != $template_css && '' != $template_css ) {
					$template_css = "<style>$template_css</style>";
				}

				if ( isset( $args['message'] ) && ! empty( $args['message'] ) ) {
					$templatehtml = str_replace( '[MESSAGE]', $args['message'], $templatehtml );
				} else {
					$templatehtml = str_replace( '[MESSAGE]', '', $templatehtml );
				}
				if ( isset( $args['to'] ) && ! empty( $args['to'] ) ) {
					$templatehtml = str_replace( '[TO]', $args['to'], $templatehtml );
				} else {
					$templatehtml = str_replace( 'To:', '', $templatehtml );
					$templatehtml = str_replace( 'To :', '', $templatehtml );
					$templatehtml = str_replace( 'To-', '', $templatehtml );
					$templatehtml = str_replace( '[TO]', '', $templatehtml );
				}
				if ( isset( $args['from'] ) && ! empty( $args['from'] ) ) {
					$templatehtml = str_replace( '[FROM]', $args['from'], $templatehtml );
				} else {
					$templatehtml = str_replace( 'From :', '', $templatehtml );
					$templatehtml = str_replace( 'From-', '', $templatehtml );
					$templatehtml = str_replace( 'From:', '', $templatehtml );
					$templatehtml = str_replace( '[FROM]', '', $templatehtml );
				}
				$general_settings = get_option( 'wps_wgm_general_settings', array() );

				$wps_obj = new Woocommerce_Gift_Cards_Common_Function();
				$selected_date = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable_selected_format' );

				if ( empty( $selected_date ) ) {
					$selected_date = 'Y-m-d';

				}
				if ( 'No Expiration' != $args['expirydate'] ) {
					$args['expirydate'] = gmdate( $selected_date, strtotime( '-1 day', strtotime( $args['expirydate'] ) ) );
				}

				if ( ! isset( $args['delivery_method'] ) ) {
					$args['delivery_method'] = '';
				}
				// Background Image for Mothers Day.
				$mothers_day_backimg = WPS_WGC_URL . 'assets/images/back.png';

				$mothers_day_backimg = "<span class='back_bubble_img'><img src='$mothers_day_backimg'/></span>";

				// Arrow Image for Mothers Day.
				$arrow_img = WPS_WGC_URL . 'assets/images/arrow.png';
				$arrow_img = "<img src='$arrow_img'  class='center-on-narrow' style='height: auto;font-family: sans-serif; font-size: 15px; line-height: 20px; color: rgb(85, 85, 85); border-radius: 5px;' width='135' height='170' border='0'>";

				$bgimg = "background='$featured_image'";

				$templatehtml = str_replace( '[ARROWIMAGE]', $arrow_img, $templatehtml );
				$templatehtml = str_replace( '[BACK]', $mothers_day_backimg, $templatehtml );
				$templatehtml = str_replace( '[LOGO]', $giftcard_logo_html, $templatehtml );
				$templatehtml = str_replace( '[AMOUNT]', $args['amount'], $templatehtml );
				$templatehtml = str_replace( '[COUPON]', $args['coupon'], $templatehtml );
				$templatehtml = str_replace( '[EXPIRYDATE]', $args['expirydate'], $templatehtml );
				$templatehtml = str_replace( '[DISCLAIMER]', $giftcard_disclaimer, $templatehtml );
				$templatehtml = str_replace( '[DELIVERYMETHOD]', $args['delivery_method'], $templatehtml );
				$templatehtml = str_replace( '[RECOMMENDEDPRODUCT]', $recommand_product, $templatehtml );
				$templatehtml = str_replace( '[DEFAULTEVENT]', $giftcard_event_html, $templatehtml );
				$templatehtml = str_replace( '[FEATUREDIMAGE]', $giftcard_featured, $templatehtml );
				$templatehtml = str_replace( '[BGIMAGE]', $bgimg, $templatehtml );
				$templatehtml = $template_css . $templatehtml;
				$templatehtml = apply_filters( 'wps_wgm_email_template_html', $templatehtml, $args );
				return $templatehtml;
			}
		}

		/**
		 * Function to get template data from data base.
		 *
		 * @since 1.0.0
		 * @name wps_wgm_get_template_data
		 * @param array  $wps_wgm_settings wps_wgm_settings.
		 * @param string $key databse key name.
		 * @return $wps_wgm_data.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_wgm_get_template_data( $wps_wgm_settings, $key ) {
			$wps_wgm_data = '';
			if ( isset( $wps_wgm_settings ) && is_array( $wps_wgm_settings ) && ! empty( $wps_wgm_settings ) ) {
				if ( array_key_exists( $key, $wps_wgm_settings ) ) {
					$wps_wgm_data = $wps_wgm_settings[ $key ];
				}
				return $wps_wgm_data;
			} else {
				return $wps_wgm_data;
			}
		}

		/**
		 * Create the Gift Certificate(Coupon)
		 *
		 * @since 1.0.0
		 * @name wps_wgm_create_gift_coupon()
		 * @param string $gift_couponnumber coupo code.
		 * @param int    $couponamont coupo amount.
		 * @param int    $order_id order id.
		 * @param int    $product_id product id.
		 * @param int    $to email id or name to whome coupon send.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_wgm_create_gift_coupon( $gift_couponnumber, $couponamont, $order_id, $product_id, $to ) {
			$wps_wgc_enable = wps_wgm_giftcard_enable();
			if ( $wps_wgc_enable ) {
				$alreadycreated = get_post_meta( $order_id, 'wps_wgm_order_giftcard', true );
				if ( 'send' != $alreadycreated ) {
					$coupon_code = $gift_couponnumber; // Code.
					$amount = $couponamont; // Amount.
					$discount_type = apply_filters( 'wps_wgm_discount_type', 'fixed_cart' );
					$coupon_description = "GIFTCARD ORDER #$order_id";
					$coupon = array(
						'post_title' => $coupon_code,
						'post_content' => $coupon_description,
						'post_excerpt' => $coupon_description,
						'post_status' => 'publish',
						'post_author' => get_current_user_id(),
						'post_type'     => 'shop_coupon',
					);
					$new_coupon_id = wp_insert_post( $coupon );
					if ( $new_coupon_id ) {
						$coupon_obj = new WC_Coupon( $new_coupon_id );
						$coupon_obj->save();
					}
					$general_settings = get_option( 'wps_wgm_general_settings', array() );
					$product_settings = get_option( 'wps_wgm_product_settings', array() );
					$individual_use = $this->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_individual_use' );
					$individual_use = ( 'on' == $individual_use ) ? 'yes' : 'no';

					$usage_limit = $this->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_use' );
					$usage_limit = ( '' != $usage_limit ) ? $usage_limit : 0;
					$expiry_date = $this->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_expiry' );
					$expiry_date = ( '' != $expiry_date ) ? $expiry_date : 0;

					$free_shipping = $this->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_freeshipping' );
					$free_shipping = ( 'on' == $free_shipping ) ? 'yes' : 'no';

					$minimum_amount = $this->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_minspend' );
					$maximum_amount = $this->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_maxspend' );
					$exclude_sale_items = $this->wps_wgm_get_template_data( $product_settings, 'wps_wgm_product_setting_giftcard_ex_sale' );
					$exclude_sale_items = ( 'on' == $exclude_sale_items ) ? 'yes' : 'no';

					$exclude_products = $this->wps_wgm_get_template_data( $product_settings, 'wps_wgm_product_setting_exclude_product' );
					$exclude_products = ( is_array( $exclude_products ) && ! empty( $exclude_products ) ) ? implode( ',', $exclude_products ) : '';
					$exclude_category = $this->wps_wgm_get_template_data( $product_settings, 'wps_wgm_product_setting_exclude_category' );
					if ( ! isset( $exclude_category ) || empty( $exclude_category ) ) {
						$exclude_category = ' ';
					}
					$wps_wgm_extra_data = array();
					$wps_wgm_extra_data = apply_filters( 'wps_wgm_add_more_coupon_fields', $wps_wgm_extra_data, $new_coupon_id, $product_id );
					if ( isset( $wps_wgm_extra_data['expiry_date'] ) && ! empty( $wps_wgm_extra_data['expiry_date'] ) ) {
						$expirydate = $wps_wgm_extra_data['expiry_date'];
					} else {
						$todaydate = date_i18n( 'Y-m-d' );
						if ( 0 < $expiry_date || 0 === $expiry_date ) {
							$expirydate = date_i18n( 'Y-m-d', strtotime( "$todaydate +$expiry_date day" ) );
						} else {
							$expirydate = '';
						}
					}
					$is_imported_product = get_post_meta( $product_id, 'is_imported', true );
					if ( isset( $is_imported_product ) && 'yes' === $is_imported_product && isset( $wps_wgm_extra_data ) && '' === $wps_wgm_extra_data['expiry_date'] ) {
						$expirydate = '';
					}

					// Add meta.
					// price based on country.
					if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {

						update_post_meta( $new_coupon_id, 'zone_pricing_type', 'exchange_rate' );
					}
					update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
					update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
					update_post_meta( $new_coupon_id, 'individual_use', $individual_use );
					update_post_meta( $new_coupon_id, 'usage_limit', $usage_limit );
					update_post_meta( $new_coupon_id, 'free_shipping', $free_shipping );
					update_post_meta( $new_coupon_id, 'minimum_amount', $minimum_amount );
					update_post_meta( $new_coupon_id, 'maximum_amount', $maximum_amount );
					update_post_meta( $new_coupon_id, 'exclude_sale_items', $exclude_sale_items );
					update_post_meta( $new_coupon_id, 'wps_wgm_giftcard_coupon', $order_id );
					update_post_meta( $new_coupon_id, 'wps_wgm_giftcard_coupon_unique', 'online' );
					update_post_meta( $new_coupon_id, 'wps_wgm_giftcard_coupon_product_id', $product_id );
					update_post_meta( $new_coupon_id, 'wps_wgm_giftcard_coupon_mail_to', $to );
					// This key is used for updating coupon amount.
					update_post_meta( $new_coupon_id, 'wps_wgm_coupon_amount', $amount );

					// exclude products.
					if ( isset( $wps_wgm_extra_data['exclude_per_products'] ) && '' != $wps_wgm_extra_data['exclude_per_products'] ) {
						update_post_meta( $new_coupon_id, 'exclude_product_ids', $wps_wgm_extra_data['exclude_per_products'] );
					} else {
						update_post_meta( $new_coupon_id, 'exclude_product_ids', $exclude_products );
					}
					// exclude category.
					if ( isset( $wps_wgm_extra_data['exclude_per_product_category'] ) && is_array( $wps_wgm_extra_data['exclude_per_product_category'] ) && ! empty( $wps_wgm_extra_data['exclude_per_product_category'] ) ) {
						update_post_meta( $new_coupon_id, 'exclude_product_categories', $wps_wgm_extra_data['exclude_per_product_category'] );

					} else {
						update_post_meta( $new_coupon_id, 'exclude_product_categories', $exclude_category );
					}

					$woo_ver = WC()->version;
					if ( $woo_ver < '3.6.0' ) {
						update_post_meta( $new_coupon_id, 'expiry_date', $expirydate );
					} else {
						$expirydate = strtotime( $expirydate );
						update_post_meta( $new_coupon_id, 'date_expires', $expirydate );
					}

					// purchase as a product.
					$item_id = get_post_meta( $order_id, 'temp_item_id', true );
					do_action( 'wps_wgm_set_coupon_meta_for_product_as_a_gift', $order_id, $item_id, $new_coupon_id, $product_id );

					return true;
				} else {
					return false;
				}
			}
		}

		/**
		 * Some common mail functionality handles here
		 *
		 * @since 1.0.0
		 * @name wps_wgm_common_functionality()
		 * @param array  $wps_wgm_common_arr email template data.
		 * @param object $order order.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_wgm_common_functionality( $wps_wgm_common_arr, $order ) {

			if ( ! empty( $wps_wgm_common_arr ) ) {
				$to = $wps_wgm_common_arr['to'];
				$from = $wps_wgm_common_arr['from'];
				$item_id = $wps_wgm_common_arr['item_id'];
				$product_id = $wps_wgm_common_arr['product_id'];
				$wps_wgm_pricing = ! empty( get_post_meta( $product_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $product_id, 'wps_wgm_pricing', true ) : get_post_meta( $product_id, 'wps_wgm_pricing_details', true );
				if ( is_array( $wps_wgm_pricing ) && array_key_exists( 'template', $wps_wgm_pricing ) ) {
					$templateid = $wps_wgm_pricing['template'];
				} else {
					$templateid = $this->wps_get_org_selected_template();
				}
				if ( is_array( $templateid ) && array_key_exists( 0, $templateid ) ) {
					$temp = $templateid[0];
				} else {
					$temp = $templateid;
				}
				$args['from'] = $from;
				$args['order_id'] = $order->get_id();
				$args['to'] = $to;
				$args['message'] = stripcslashes( $wps_wgm_common_arr['gift_msg'] );
				$args['coupon'] = apply_filters( 'wps_wgm_qrcode_coupon', $wps_wgm_common_arr['gift_couponnumber'] );
				$args['expirydate'] = $wps_wgm_common_arr['expirydate_format'];
				$args['delivery_method'] = $wps_wgm_common_arr['delivery_method'];
				// price based on country.
				if ( class_exists( 'WCPBC_Pricing_Zones' ) ) {

					$billing_country = $order->get_billing_country();
					$wcpbc_the_zone = WCPBC_Pricing_Zones::get_zone_by_country( $billing_country );
					if ( isset( $wcpbc_the_zone ) && null != $wcpbc_the_zone ) {
						$cur = $wcpbc_the_zone->get_currency();
						$amt = $wcpbc_the_zone->get_exchange_rate_price( $wps_wgm_common_arr['couponamont'] );
						$args['amount'] = get_woocommerce_currency_symbol( $cur ) . $amt;
					} else {
						$args['amount'] = wc_price( $wps_wgm_common_arr['couponamont'] );
					}
				} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
					$to_currency    = get_post_meta( $order->get_id(), '_order_currency', true );
					$args['amount'] = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( $to_currency, $wps_wgm_common_arr['couponamont'] );
					$args['amount'] = wps_mmcsfw_get_custom_currency_symbol( $to_currency ) . $args['amount'];
				} else {
					$decimal_separator                 = get_option( 'woocommerce_price_decimal_sep' );
					$wps_wgm_common_arr['couponamont'] = floatval( str_replace( $decimal_separator, '.', $wps_wgm_common_arr['couponamont'] ) );
					$args['amount']                    = wc_price( $wps_wgm_common_arr['couponamont'] );
				}
				$args['templateid'] = isset( $wps_wgm_common_arr['selected_template'] ) && ! empty( $wps_wgm_common_arr['selected_template'] ) ? $wps_wgm_common_arr['selected_template'] : $temp;
				$args['product_id'] = $product_id;

				$args = apply_filters( 'wps_wgm_common_functionality_template_args', $args, $wps_wgm_common_arr );

				$message = apply_filters( 'wps_wgm_customizable_email_template', $this->wps_wgm_create_gift_template( $args ), $args );

				$order_id = $wps_wgm_common_arr['order_id'];
				$wps_wgm_pre_gift_num = get_post_meta( $order_id, "$order_id#$item_id", true );

				if ( is_array( $wps_wgm_pre_gift_num ) && ! empty( $wps_wgm_pre_gift_num ) ) {
					$wps_wgm_pre_gift_num[] = $wps_wgm_common_arr['gift_couponnumber'];
					update_post_meta( $order_id, "$order_id#$item_id", $wps_wgm_pre_gift_num );
				} else {
					$wps_wgm_code_arr = array();
					$wps_wgm_code_arr[] = $wps_wgm_common_arr['gift_couponnumber'];
					update_post_meta( $order_id, "$order_id#$item_id", $wps_wgm_code_arr );
				}

				$headers = array( 'Content-Type: text/html; charset=UTF-8' );

				$wps_wgm_common_arr = apply_filters( 'wps_wgm_add_pdf_settings', $wps_wgm_common_arr, $message );

				$attachments = isset( $wps_wgm_common_arr['attachments'] ) ? $wps_wgm_common_arr['attachments'] : array();
				$to = isset( $wps_wgm_common_arr['to'] ) ? $wps_wgm_common_arr['to'] : $to;
				$headers = isset( $wps_wgm_common_arr['header'] ) ? $wps_wgm_common_arr['header'] : $headers;
				$disable_buyer_notice = isset( $wps_wgm_common_arr['disable_buyer_notice'] ) ? $wps_wgm_common_arr['disable_buyer_notice'] : 'off';

				$get_mail_status = true;
				$get_mail_status = apply_filters( 'wps_send_mail_status', $get_mail_status );

				if ( empty( $to ) ) {
					if ( 'Mail to recipient' == $wps_wgm_common_arr['delivery_method'] ) {
						$to = $order->get_billing_email();
					} else {
						$to = '';
					}
				}

				if ( $get_mail_status ) {
					$wps_wgm_mail_settings = get_option( 'wps_wgm_mail_settings', array() );

					$send_subject = $this->wps_wgm_get_template_data( $wps_wgm_mail_settings, 'wps_wgm_mail_setting_giftcard_subject' );

					if ( isset( $wps_wgm_common_arr['send_subject'] ) && ! empty( $wps_wgm_common_arr['send_subject'] ) ) {
						$send_subject = $wps_wgm_common_arr['send_subject'];
					}

					$bloginfo = get_bloginfo();

					if ( empty( $send_subject ) ) {

						$send_subject = "$bloginfo:";
						$send_subject .= __( ' Hurry!  the gift card is Received', 'woo-gift-cards-lite' );
					}
					$buyer_email = $order->get_billing_email();
					$buyer_email = ! empty( $buyer_email ) ? $buyer_email : '';
					$send_subject = str_replace( '[SITENAME]', $bloginfo, $send_subject );
					$send_subject = str_replace( '[FROM]', $from, $send_subject );
					$send_subject = stripcslashes( $send_subject );
					$send_subject = html_entity_decode( $send_subject, ENT_QUOTES, 'UTF-8' );
					if ( isset( $wps_wgm_common_arr['delivery_method'] ) ) {
						if ( 'Mail to recipient' == $wps_wgm_common_arr['delivery_method'] ) {
							$woo_ver = WC()->version;
							if ( $woo_ver < '3.0.0' ) {
								$from = $order->billing_email;
							} else {
								$from = $order->get_billing_email();
							}
						}
						if ( 'Downloadable' == $wps_wgm_common_arr['delivery_method'] ) {
							$woo_ver = WC()->version;
							if ( $woo_ver < '3.0.0' ) {
								$to = $order->billing_email;
							} else {
								$to = $order->get_billing_email();
							}
						}
						if ( 'shipping' == $wps_wgm_common_arr['delivery_method'] ) {
							$to = $to;
						}
					}

					wc_mail( $to, $send_subject, $message, $headers, $attachments );
					do_action( 'wps_wgm_send_mail_to_others', $send_subject, $message, $attachments );
					if ( isset( $attachments ) && is_array( $attachments ) && ! empty( $attachments ) ) {
						unlink( $attachments[0] );
					}

					if ( isset( $wps_wgm_common_arr['receive_subject'] ) && ! empty( $wps_wgm_common_arr['receive_subject'] ) ) {
						$receive_subject = $wps_wgm_common_arr['receive_subject'];
					} else {
						$receive_subject = "$bloginfo:";
						$receive_subject .= __( ' Gift Card is Sent Successfully', 'woo-gift-cards-lite' );
					}

					if ( isset( $wps_wgm_common_arr['receive_message'] ) && ! empty( $wps_wgm_common_arr['receive_message'] ) ) {
						$receive_message = $wps_wgm_common_arr['receive_message'];
					} else {
						$receive_message = "$bloginfo:";
						$receive_message .= __( ' Gift Card is Sent Successfully to the Email Id: [TO]', 'woo-gift-cards-lite' );

					}
					$receive_message = stripcslashes( $receive_message );
					$receive_message = str_replace( '[TO]', $to, $receive_message );
					$receive_subject = stripcslashes( $receive_subject );

					if ( 'off' == $disable_buyer_notice && 'Mail to recipient' == $wps_wgm_common_arr['delivery_method'] ) {
						wc_mail( $from, $receive_subject, $receive_message );
					}
				}
				do_action( 'wps_wgm_send_giftcard_over_sms', $wps_wgm_common_arr, $order );
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Check the Expiry Date for priniting this out inside the Email template
		 *
		 * @since 1.0.0
		 * @name wps_wgm_check_expiry_date()
		 * @param string $expiry_date expiry date of giftcard coupon.
		 * @return $expirydate_format.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_wgm_check_expiry_date( $expiry_date ) {
			$general_settings = get_option( 'wps_wgm_general_settings', array() );
			$selected_date = $this->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable_selected_format' );
			$todaydate = date_i18n( 'Y-m-d' );
			if ( isset( $expiry_date ) && ! empty( $expiry_date ) ) {
				if ( 0 < $expiry_date || 0 === $expiry_date ) {
					if ( isset( $_GET['send_date'] ) && null != $_GET['send_date'] && '' != $_GET['send_date'] && 'undefined' != $_GET['send_date'] ) {
						$todaydate = sanitize_text_field( wp_unslash( $_GET['send_date'] ) );
						if ( is_string( $todaydate ) ) {
							if ( isset( $selected_date ) && null != $selected_date && '' != $selected_date ) {
								if ( 'd/m/Y' == $selected_date ) {
									$todaydate = str_replace( '/', '-', $todaydate );
								}
							}
						}
					}
					if ( isset( $selected_date ) && null != $selected_date && '' != $selected_date && wps_uwgc_pro_active() ) {
						$selected_date = apply_filters( 'wps_wgm_selected_date_format', $selected_date );
						$expirydate_format = date_i18n( $selected_date, strtotime( "$todaydate +$expiry_date day" ) );
					} else {
						$expirydate_format = date_i18n( 'Y-m-d', strtotime( "$todaydate +$expiry_date day" ) );
					}
				}
			} else {
				$expirydate_format = __( 'No Expiration', 'woo-gift-cards-lite' );
			}
			return $expirydate_format;
		}

		/**
		 * This function is used to return the remaining coupon amount according to Tax setting you have in your system.
		 *
		 * @since 1.0.0
		 * @name wps_wgm_calculate_coupon_discount
		 * @param int $wps_wgm_discount coupon discount.
		 * @param int $wps_wgm_discount_tax coupon discount tax.
		 * @return $total_discount.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_wgm_calculate_coupon_discount( $wps_wgm_discount, $wps_wgm_discount_tax ) {
			$price_in_ex_option = get_option( 'woocommerce_prices_include_tax' );
			$tax_display_shop = get_option( 'woocommerce_tax_display_shop', 'excl' );
			$tax_display_cart = get_option( 'woocommerce_tax_display_cart', 'excl' );

			if ( isset( $tax_display_shop ) && isset( $tax_display_cart ) ) {
				if ( 'excl' == $tax_display_cart && 'excl' == $tax_display_shop ) {

					if ( 'yes' == $price_in_ex_option || 'on' == $price_in_ex_option ) {

						return $wps_wgm_discount;
					}
				} elseif ( 'incl' == $tax_display_cart && 'incl' == $tax_display_shop ) {

					if ( 'yes' == $price_in_ex_option || 'no' == $price_in_ex_option ) {
						$total_discount = $wps_wgm_discount + $wps_wgm_discount_tax;
						return $total_discount;
					}
				} else {
					return $wps_wgm_discount;
				}
			}
			return $wps_wgm_discount;
		}

		/**
		 * This function is used to return the allowed html tags in the preview template.
		 *
		 * @since 1.0.0
		 * @name wps_allowed_html_tags
		 * @return $allowed_tags.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_allowed_html_tags() {
			$allowed_tags = array(
				'a' => array(
					'class' => array(),
					'href'  => array(),
					'rel'   => array(),
					'title' => array(),
				),
				'abbr' => array(
					'title' => array(),
				),
				'b' => array(),
				'blockquote' => array(
					'cite'  => array(),
				),
				'cite' => array(
					'title' => array(),
				),
				'code' => array(),
				'del' => array(
					'datetime' => array(),
					'title' => array(),
				),
				'dd' => array(),
				'div' => array(
					'class' => array(),
					'title' => array(),
					'style' => array(
						'display',
					),
				),
				'dl' => array(),
				'dt' => array(),
				'em' => array(),
				'h1' => array(),
				'h2' => array(),
				'h3' => array(),
				'h4' => array(),
				'h5' => array(),
				'h6' => array(),
				'i'  => array(),
				'br' => array(),
				'img' => array(
					'alt'    => array(),
					'id'    => array(),
					'class'  => array(),
					'height' => array(),
					'src'    => array(),
					'width'  => array(),
					'style'  => array(
						'srcset'   => array(),
						'sizes'    => array(),
						'id'       => array(),
						'longdesc' => array(),
						'usemap'   => array(),
						'align'    => array(),
						'border'   => array(),
						'hspace'   => array(),
						'vspace'   => array(),
					),
				),
				'li' => array(
					'class' => array(),
				),
				'ol' => array(
					'class' => array(),
				),
				'p' => array(
					'class' => array(),
					'style' => array(),
				),
				'q' => array(
					'cite' => array(),
					'title' => array(),
				),
				'span' => array(
					'class' => array(),
					'title' => array(),
					'style' => array(),
					'img'   => array(),
					'color' => array(),
				),
				'strike' => array(),
				'strong' => array(),
				'ul' => array(
					'class' => array(),
				),
				'table' => array(
					'class'       => array(),
					'style'       => array(),
					'role'        => array(),
					'border'      => array(),
					'width'       => array(),
					'cellspacing' => array(),
					'cellpadding' => array(),
					'align'       => array(),
					'bgcolor'     => array(),
				),
				'tbody'      => array(),
				'tr'         => array(
					'style' => array(),
				),
				'td'         => array(
					'dir'         => array(),
					'style'       => array(),
					'align'       => array(),
					'bgcolor'     => array(),
					'width'       => array(),
					'valign'      => array(),
					'class'       => array(),
					'span'       => array(),
				),
				'th'         => array(),
				'style'      => array(),
				'center'     => array(
					'style' => array(),
				),
				'select'      => array(
					'id'         => array(),
					'class'       => array(),
					'name'       => array(),
				),
				'option'      => array(
					'value'         => array(),
				),
				'label'      => array(
					'class'      => array(),
					'id'         => array(),
				),
				'input'      => array(
					'class'      => array(),
					'id'         => array(),
					'type'         => array(),
					'name'         => array(),
					'placeholder'  => array(),
					'value'         => array(),
					'checked'         => array(),
					'selected'         => array(),
				),
				'textarea'      => array(
					'class'      => array(),
					'id'         => array(),
					'name'         => array(),
					'placeholder'         => array(),
				),
				'a'      => array(
					'id'         => array(),
				),

			);
			return apply_filters( 'wps_allowed_html_for_preview', $allowed_tags );
		}

		/**
		 * Get the templates selected in Giftcard lite plugin earlier.
		 *
		 * @since 1.0.0
		 * @name wps_get_org_selected_template
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_get_org_selected_template() {
			$wps_wgm_select_email_format = get_option( 'wps_wgm_select_email_format', 'normal' );
			$custom_email_template = get_option( 'wps_wgm_general_setting_select_template', 'off' );
			$selected_template_name = '';
			if ( 'on' == $custom_email_template ) {
				$selected_template_name = 'Custom Template';
			} else {
				switch ( $wps_wgm_select_email_format ) {
					case 'normal':
						$selected_template_name = 'Gift for You';
						break;
					case 'mom':
						$selected_template_name = 'Love You Mom';
						break;
					case 'christmas':
						$selected_template_name = 'Merry Christmas Template';
						break;
				}
			}
			if ( isset( $selected_template_name ) && ! empty( $selected_template_name ) ) {
				$args = array(
					'post_type' => 'giftcard',
					'posts_per_page' => -1,
				);
				$loop = new WP_Query( $args );
				$template = array();
				foreach ( $loop->posts as $key => $value ) {
					$template_id = $value->ID;
					$template_title = $value->post_title;
					$template[ $template_id ] = $template_title;
				}
				$selected_temp_id = array_search( $selected_template_name, $template );
				$template_id = array( 0 => $selected_temp_id );
				return $template_id;
			}
		}

		/**
		 * Get the templates selected in Giftcard lite plugin earlier.
		 *
		 * @since 1.0.0
		 * @param array  $wps_wgm_settings wps_wgm_settings.
		 * @param string $key databse key name.
		 * @name wps_get_org_selected_template
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function mwb_wgm_get_template_data( $wps_wgm_settings, $key ) {
			$this->wps_wgm_get_template_data( $wps_wgm_settings, $key );
		}
	}
}
