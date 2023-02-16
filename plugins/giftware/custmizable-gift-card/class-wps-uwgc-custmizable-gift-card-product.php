<?php
/**
 * Exit if accessed directly
 *
 * @package     Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Wps_Uwgc_Custmizable_Gift_Card_Product' ) ) {

	/**
	 * This is class for Creating and Managing The Custmizable Giftcard product.
	 *
	 * @name    WPS_UWGC_Custmizable_Gift_Card_Product
	 * @category Class
	 * @author   WP Swings <webmaster@wpswings.com>
	 */
	class Wps_Uwgc_Custmizable_Gift_Card_Product {

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'wps_cgc_enqueue_styles' ), 10, 1 );
			add_action( 'wp_enqueue_scripts', array( $this, 'wps_cgc_enqueue_scripts' ) );
		}

		/**
		 * This is function is used to enqueue CSS for  Custmizable Giftcard template.
		 *
		 * @name wps_cgc_enqueue_styles
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_cgc_enqueue_styles() {
			if ( is_product() ) {

				global $post;
				$product_id = $post->ID;

				$is_customizable = get_post_meta( $product_id, 'woocommerce_customizable_giftware', true );
				if ( isset( $is_customizable ) && ! empty( $is_customizable ) && 'yes' == $is_customizable ) {
					wp_enqueue_style( 'customized_css', WPS_UWGC_URL . 'custmizable-gift-card/css/wps_customized_temp.css', array(), WPS_UWGC_PLUGIN_VERSION, 'all' );
				}
			}

		}

		/**
		 * This is function is used to enqueue JS for  Custmizable Giftcard template.
		 *
		 * @name wps_cgc_enqueue_scripts
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_cgc_enqueue_scripts() {

			global $post;
			$product_id      = $post->ID;
			$product_pricing = get_post_meta( $product_id, 'wps_wgm_pricing', true );

			$wps_custom                 = array();
			$wps_custom['pricing_type'] = isset( $product_pricing['type'] ) ? $product_pricing['type'] : '';
			$wps_custom['ajaxurl']      = admin_url( 'admin-ajax.php' );
			$wps_custom['currency']     = get_woocommerce_currency_symbol();
			$wps_custom['wps_nonce']    = wp_create_nonce( 'wps-cgc-verify-nonce' );

			wp_enqueue_script( 'custmizable-temp-js', WPS_UWGC_URL . 'custmizable-gift-card/js/woocommerce-customizable-giftcard-public.js', array( 'jquery' ), WPS_UWGC_PLUGIN_VERSION, 'all' );
			wp_localize_script( 'custmizable-temp-js', 'wps_custom', $wps_custom );
		}

		/**
		 * This is function is used to Locate The Custmizable Giftcard template.
		 *
		 * @name wps_uwgc_create_custmizable_gift_template
		 * @param mixed $template Template.
		 * @param mixed $template_name Template name.
		 * @param mixed $template_path Template path.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_create_custmizable_gift_template( $template, $template_name, $template_path ) {
			if ( ! is_admin() ) {
				global $woocommerce;
				$_template = $template;

				if ( ! $template_path ) {
					$template_path = $woocommerce->template_url;
				}

				$plugin_path  = untrailingslashit( WPS_UWGC_URL ) . '/custmizable-gift-card/woocommerce/';

				$template = locate_template(
					array(
						$template_path . $template_name,
						$template_name,
					)
				);

				// Modification: Get the template from this plugin, if it exists.
				if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
					$template = $plugin_path . $template_name;
				}

				// Use default template.
				if ( ! $template ) {
					$template = $_template;
				}
			}
			return $template;
		}

		/**
		 * This is function is used to Include The Custmizable Giftcard template.
		 *
		 * @name wps_uwgc_include_custmizable_gift_template
		 * @param mixed $template template.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_include_custmizable_gift_template( $template ) {
			$pre_template = $template;
			global $post;

			if ( isset( $post ) && ! empty( $post ) ) {
				$product_id = $post->ID;
				$is_customizable = get_post_meta( $product_id, 'woocommerce_customizable_giftware', true );
			}
			if ( is_single() && 'product' == get_post_type() ) {

				$template = locate_template( array( 'woocommerce/single-product.php' ) );
				if ( $template || ! $template ) {

					if ( isset( $is_customizable ) && ! empty( $is_customizable ) && 'yes' == $is_customizable ) {

						$template = WPS_UWGC_DIRPATH . 'custmizable-gift-card/woocommerce/customized-temp.php';
					} else {
						return $pre_template;
					}
				}
			}
			return $template;
		}

		/**
		 * This is function is used to Add Price for Custmizable Giftcard template.
		 *
		 * @name wps_uwgc_get_custmizable_price_type
		 * @param mixed $product_pricing Product pricing.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_get_custmizable_price_type( $product_pricing ) {
			$price_html = '';
			if ( isset( $product_pricing ) && ! empty( $product_pricing ) ) {

				if ( isset( $product_pricing['type'] ) ) {

					$product_pricing_type = $product_pricing['type'];

					if ( 'wps_wgm_default_price' == $product_pricing_type ) {

						if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {  // for price based on country.
							$default_price = $product_pricing['default_price'];
							if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
								$default_price = wcpbc_the_zone()->get_exchange_rate_price( $default_price );
							}
						} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
							$default_price = $product_pricing['default_price'];
							$default_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $default_price );
						} else {
							$default_price = $product_pricing['default_price'];
						}
						$price_html .= '<ins><span class="woocommerce-Price-amount amount">' . wc_price( $default_price ) . '</span></ins>';
					}
					if ( 'wps_wgm_range_price' == $product_pricing_type ) {

						$price_html = '';
						$from_price = $product_pricing['from'];
						$to_price = $product_pricing['to'];

						if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {  // for price based on country.
							if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
								$from_price = wcpbc_the_zone()->get_exchange_rate_price( $from_price );
								$to_price   = wcpbc_the_zone()->get_exchange_rate_price( $to_price );
							}
						} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
							$from_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $from_price );
							$to_price   = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $to_price );
						}
						$price_html .= '<ins><span class="woocommerce-Price-amount amount">' . wc_price( $from_price ) . ' - ' . wc_price( $to_price ) . '</span></ins>';
					}
					if ( 'wps_wgm_selected_price' == $product_pricing_type ) {

						$selected_price = $product_pricing['price'];
						if ( ! empty( $selected_price ) ) {
							$selected_prices = explode( '|', $selected_price );
							$price_html = __( 'Select your Gift Card Value', 'giftware' );
						}
					}
					if ( 'wps_wgm_user_price' == $product_pricing_type ) {
						$price_html = apply_filters( 'wps_wgm_user_price_text', __( 'Enter Gift Card Value ', 'giftware' ), $product_pricing );
					}
					if ( 'wps_wgm_variable_price' == $product_pricing_type ) {
						$price_html = apply_filters( 'wps_wgm_variable_price_text', __( 'Select Gift Card Price ', 'giftware' ), $product_pricing );
					}
				}
			}
			return $price_html;
		}

		/**
		 * This is function is used to add price html for custmizable giftcard.
		 *
		 * @param mixed $product_pricing product pricing.
		 */
		public function wps_uwgc_get_custmizable_price_html( $product_pricing ) {
			$price_html = '';
			if ( isset( $product_pricing ) && ! empty( $product_pricing ) ) {

				if ( isset( $product_pricing['type'] ) ) {
					$product_pricing_type = $product_pricing['type'];
					$default_price = $product_pricing['default_price'];

					if ( 'wps_wgm_range_price' == $product_pricing_type ) {
						$from_price = $product_pricing['from'];
						$to_price = $product_pricing['to'];
						$text_box_price = ( $default_price >= $from_price && $default_price <= $to_price ) ? $default_price : $from_price;

						if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {  // for price based on country.
							if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
								$from_price = wcpbc_the_zone()->get_exchange_rate_price( $from_price );
								$to_price = wcpbc_the_zone()->get_exchange_rate_price( $to_price );
								$text_box_price = wcpbc_the_zone()->get_exchange_rate_price( $text_box_price );
							}
						} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
							$from_price     = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $from_price );
							$to_price       = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $to_price );
							$text_box_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $text_box_price );
						}
						$price_html = '<p class="wps_wgm_section"><input type="number" class="input-text wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" value="' . $text_box_price . '" max="' . $to_price . '" min="' . $from_price . '"></p>';
					} elseif ( 'wps_wgm_default_price' == $product_pricing_type ) {
						$price_html = '<input type="hidden" class="wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" value="' . $default_price . '">';
					} elseif ( 'wps_wgm_selected_price' == $product_pricing_type ) {
						$selected_price = $product_pricing['price'];
						if ( ! empty( $selected_price ) ) {
							$selected_prices = explode( '|', $selected_price );
							foreach ( $selected_prices as $price ) {

								if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {  // for price based on country.
									if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
										$prices      = wcpbc_the_zone()->get_exchange_rate_price( $price );
										$price_html .= '<input type="button" class="wps_cgc_price_button" value="' . $prices . '">';
									}
								} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
									$prices      = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $price );
									$price_html .= '<input type="button" class="wps_cgc_price_button" value="' . $prices . '">';
								} else {
									$price_html .= '<input type="button" class="wps_cgc_price_button" value="' . $price . '">';
								}
							}
							$price_html .= '<input type="hidden" class="wps_wgm_price_select" id="wps_wgm_price" name="wps_wgm_price" value="' . $default_price . '">';
						}
					} elseif ( 'wps_wgm_user_price' == $product_pricing_type ) {
						$min_user_price = $product_pricing['min_user_price'];
						if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {  // for price based on country.
							if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
								$default_price = wcpbc_the_zone()->get_exchange_rate_price( $default_price );

							}
						} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
							$default_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $default_price );
						}
						$price_html = '<p class="wps_wgm_section"><input type="number" class="wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" min="1" value="' . $default_price . '"><span class="wps_wgm_min_user_price">' . __( 'Minimum Price is : ', 'giftware' ) . $min_user_price . '</span></p>';
					} elseif ( 'wps_wgm_variable_price' == $product_pricing_type ) {
						$wps_variation_price = $product_pricing['wps_wgm_variation_price'];
						$decimal_separator   = get_option( 'woocommerce_price_decimal_sep' );
						foreach ( $wps_variation_price as $key => $value ) {
							$value                       = floatval( str_replace( $decimal_separator, '.', $value ) );
							$wps_variation_price[ $key ] = $value;
						}
						if ( isset( $wps_variation_price ) && ! empty( $wps_variation_price ) && is_array( $wps_variation_price ) ) {
							$start_price = min( $wps_variation_price );
							$end_price = ( max( $wps_variation_price ) == '' ) ? $start_price : max( $wps_variation_price );
							if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
								if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
									$start_price = wcpbc_the_zone()->get_exchange_rate_price( $start_price );
									$end_price = wcpbc_the_zone()->get_exchange_rate_price( $end_price );
								}
							}
							$start_price = floatval( str_replace( $decimal_separator, '.', $start_price ) );
							$end_price = floatval( str_replace( $decimal_separator, '.', $end_price ) );
							$price_html  = '<span class="wps_wgm_variable_range">' . wc_price( $start_price ) . ' - ' . wc_price( $end_price ) . '</span><br class="wps_wgm_variable_range">';
						}
						$variation_amount = $product_pricing['wps_wgm_variation_price'];
						$varable_text     = $product_pricing['wps_wgm_variation_text'];

						if ( isset( $variation_amount ) && is_array( $variation_amount ) && ! empty( $variation_amount ) ) {
							$wps_price = ( '' != $variation_amount[0] ) ? $variation_amount[0] : 0;
							if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
								if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
									$wps_price = wcpbc_the_zone()->get_exchange_rate_price( $wps_price );
								}
							}
							$decimal_separator = get_option( 'woocommerce_price_decimal_sep' );
							$wps_price        .= floatval( str_replace( $decimal_separator, '.', $wps_price ) );
							?>
												<p class="wps_wgm_section">
							<?php $price_html .= '<span id="wps_wgm_text" class="wps_variable_currency">' . wc_price( $wps_price ) . '</span>'; ?>

												</p>
												<p class="wps_wgm_section">
							<?php
							$price_html .= '<select name="wps_wgm_price" class="wps_wgm_price" id="wps_wgm_price">';

							foreach ( $variation_amount as $key => $value ) {
								if ( isset( $value ) && ! empty( $value ) ) {
									?>
									<?php $price_html .= ' <option value="' . $value . '">' . $varable_text[ $key ] . '</option>'; ?>
									<?php
								}
							}
							?>
							<?php
							$price_html .= '	</select>';
							?>
							</p>
							<?php
							$price_html = apply_filters( 'wps_wgm_get_custmizable_price_html', $price_html );
						}
					}
				}
			}
			return $price_html;
		}

		/**
		 * This is function is used to get the prefix on customizable coupon.
		 */
		public function wps_uwgc_get_custmizable_coupon_prefix() {
			$general_settings = get_option( 'wps_wgm_general_settings', array() );
			$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
			return $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_prefix' );
		}

		/**
		 * This is function is used to get Expriey date format.
		 */
		public function wps_uwgc_get_custmizable_expiry_date_format() {
			$general_settings = get_option( 'wps_wgm_general_settings', array() );
			$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
			$expiry_date = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_expiry' );
			$expiry_date = $wps_public_obj->wps_wgm_check_expiry_date( $expiry_date );
			return $expiry_date;
		}

		/**
		 * This is function is used to add disclaimer for custmizable giftcard.
		 */
		public function wps_uwgc_get_custmizable_disclaimer() {
			$mail_settings = get_option( 'wps_wgm_mail_settings', array() );
			$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
			$disclaimer_text = $wps_public_obj->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_disclaimer' );
			return $disclaimer_text;
		}

		/**
		 * This is function is used to check if schedules date is enabled.
		 */
		public function wps_uwgc_check_custmizable_schedule_date_enable() {
			$general_settings = get_option( 'wps_wgm_general_settings', array() );
			$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
			$gift_card_schedule_date = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable_selected_date' );
			return $gift_card_schedule_date;

		}

		/**
		 * This is function is used to Add Delivery Method for Custmizable Giftcard.
		 *
		 * @name wps_uwgc_custmizable_giftcard_delivery_methods_html
		 * @param mixed $product_id Product Id.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_custmizable_giftcard_delivery_methods_html( $product_id ) {

			$delivery_settings = get_option( 'wps_wgm_delivery_settings', array() );
			$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
			$wps_uwgc_method_enable = $wps_public_obj->wps_wgm_get_template_data( $delivery_settings, 'wps_wgm_send_giftcard' );

			if ( isset( $wps_uwgc_method_enable ) && 'Mail to recipient' == $wps_uwgc_method_enable ) {
				?>
				<p class="wps-cgw-radio">
					<input type="radio" name="wps_wgm_send_giftcard" id="wps_wgm_send_giftcard" value="Mail to recipient" checked="checked"><label for="wps_wgm_send_giftcard"><?php esc_html_e( 'Email To Recipient', 'giftware' ); ?></label>
					<input type="email" name="wps_wgm_to_email"  id="wps_wgm_to_email" class="wps-cgw-text" placeholder="<?php esc_attr_e( "Receiver's Email", 'giftware' ); ?>">	
				</p>
				<?php
			}
			if ( isset( $wps_uwgc_method_enable ) && 'Downloadable' == $wps_uwgc_method_enable ) {
				?>
				<p class="wps-cgw-radio">
					<input type="radio" id="wps_wgm_send_giftcard_download"  name="wps_wgm_send_giftcard" value="Downloadable" checked="checked"> <label for="wps_wgm_send_giftcard_download"><?php esc_html_e( 'You Print & Give To Recipient', 'giftware' ); ?></label>
					<input type="text" id="wps_wgm_to_download" name="wps_wgm_to_email_name" class="wps-cgw-text" placeholder="<?php esc_attr_e( "Receiver's Name", 'giftware' ); ?>">
				</p>
				<?php
			}
			if ( isset( $wps_uwgc_method_enable ) && 'shipping' == $wps_uwgc_method_enable ) {
				?>
				<p class="wps-cgw-radio">
					<input type="radio" value="shipping" id="wps_wgm_send_giftcard_ship"  name="wps_wgm_send_giftcard" checked="checked"> <label for="wps_wgm_send_giftcard_ship"><?php esc_html_e( 'We will ship your card', 'giftware' ); ?></label>
					<input type="text" id="wps_wgm_to_ship" name="wps_wgm_to_email_ship" class="wps-cgw-text" placeholder="<?php esc_attr_e( "Receiver's Name", 'giftware' ); ?>">
				</p>
				<?php
			}
			if ( isset( $wps_uwgc_method_enable ) && 'customer_choose' == $wps_uwgc_method_enable ) {

				$wps_uwgc_is_overwrite = get_post_meta( $product_id, 'wps_wgm_overwrite', true );
				$wps_wgm_email_to_recipient = get_post_meta( $product_id, 'wps_wgm_email_to_recipient', true );
				$wps_wgm_download = get_post_meta( $product_id, 'wps_wgm_download', true );
				$wps_wgm_shipping = get_post_meta( $product_id, 'wps_wgm_shipping', true );

				if ( isset( $wps_uwgc_is_overwrite ) && 'yes' == $wps_uwgc_is_overwrite ) {
					if ( isset( $wps_wgm_email_to_recipient ) && 'yes' == $wps_wgm_email_to_recipient ) {
						?>
						<p class="wps-cgw-radio">
							<input type="radio" class="wps_wgm_send_giftcard" name="wps_wgm_send_giftcard" id="wps_wgm_to_email_send" value="Mail to recipient" checked="checked"><label for="wps_wgm_to_email_send"><?php esc_html_e( 'Email To Recipient', 'giftware' ); ?></label>
							<input type="email" name="wps_wgm_to_email"  id="wps_wgm_to_email" class="wps-cgw-text" placeholder="<?php esc_attr_e( "Receiver's Email", 'giftware' ); ?>">	
						</p>
						<?php
					}
					if ( isset( $wps_wgm_download ) && 'yes' == $wps_wgm_download ) {
						?>
						<p class="wps-cgw-radio">
							<input type="radio" id="wps_wgm_send_giftcard_download" class="wps_wgm_send_giftcard" name="wps_wgm_send_giftcard" value="Downloadable" > <label for="wps_wgm_send_giftcard_download"><?php esc_html_e( 'You Print & Give To Recipient', 'giftware' ); ?></label>
							<input type="text"  id="wps_wgm_to_download"  name="wps_wgm_to_email_name" class="wps-cgw-text" placeholder="<?php esc_attr_e( "Receiver's Name", 'giftware' ); ?>">	
						</p>
						<?php
					}
					if ( isset( $wps_wgm_shipping ) && 'yes' == $wps_wgm_shipping ) {
						?>
						<p class="wps-cgw-radio">
							<input type="radio" value="shipping" id="wps_wgm_send_giftcard_ship" class="wps_wgm_send_giftcard" name="wps_wgm_send_giftcard" > <label for="wps_wgm_send_giftcard_ship"><?php esc_html_e( 'We will ship your card', 'giftware' ); ?></label>
							<input type="text"  id="wps_wgm_to_ship" name="wps_wgm_to_email_ship" class="wps-cgw-text" placeholder="<?php esc_attr_e( "Receiver's Name", 'giftware' ); ?>">
						</p>
						<?php
					}
				} else {

					if ( ! isset( $delivery_settings['wps_wgm_email_to_recipient'] ) && ! isset( $delivery_settings['wps_wgm_downloadable'] ) && ! isset( $delivery_settings['wps_wgm_shipping'] ) ) {
						?>
						<p class="wps-cgw-radio">
							<input type="radio" name="wps_wgm_send_giftcard" class="wps_wgm_send_giftcard" id="wps_wgm_to_email_send" value="Mail to recipient" checked="checked"><label for="wps_wgm_to_email_send"><?php esc_html_e( 'Email To Recipient', 'giftware' ); ?></label>
							<input type="email" name="wps_wgm_to_email"  id="wps_wgm_to_email" class="wps-cgw-text" placeholder="<?php esc_attr_e( "Receiver's Email", 'giftware' ); ?>">
						</p>
						<?php
					}
					if ( isset( $delivery_settings['wps_wgm_email_to_recipient'] ) && 'on' == $delivery_settings['wps_wgm_email_to_recipient'] ) {
						?>
						<p class="wps-cgw-radio">
							<input type="radio" name="wps_wgm_send_giftcard" class="wps_wgm_send_giftcard" id="wps_wgm_to_email_send" value="Mail to recipient" checked="checked"><label for="wps_wgm_to_email_send"><?php esc_html_e( 'Email To Recipient', 'giftware' ); ?></label>
							<input type="email" name="wps_wgm_to_email"  id="wps_wgm_to_email" class="wps-cgw-text" placeholder="<?php esc_attr_e( "Receiver's Email", 'giftware' ); ?>">
						</p>
						<?php
					}
					if ( isset( $delivery_settings['wps_wgm_downloadable'] ) && 'on' == $delivery_settings['wps_wgm_downloadable'] ) {
						?>
						<p class="wps-cgw-radio">
							<input type="radio" id="wps_wgm_send_giftcard_download" class="wps_wgm_send_giftcard" name="wps_wgm_send_giftcard" value="Downloadable" > <label for="wps_gw_send_giftcard_download"><?php esc_html_e( 'You Print & Give To Recipient', 'giftware' ); ?></label>
							<input type="text"  id="wps_wgm_to_download" name="wps_wgm_to_email_name" class="wps-cgw-text" placeholder="<?php esc_attr_e( "Receiver's Name", 'giftware' ); ?>">	
						</p>
						<?php
					}
					if ( isset( $delivery_settings['wps_wgm_shipping'] ) && 'on' == $delivery_settings['wps_wgm_shipping'] ) {
						?>
						<p class="wps-cgw-radio">
							<input type="radio" value="shipping" id="wps_wgm_send_giftcard_ship" class="wps_wgm_send_giftcard"  name="wps_wgm_send_giftcard" > <label for="wps_wgm_send_giftcard_ship"><?php esc_html_e( 'We will ship your card', 'giftware' ); ?></label>
							<input type="text"  id="wps_wgm_to_ship"  name="wps_wgm_to_email_ship" class="wps-cgw-text" placeholder="<?php esc_attr_e( "Receiver's Name", 'giftware' ); ?>">
						</p>
						<?php
					}
				}
			}
		}

		/**
		 * Display Customizable gift section.
		 */
		public function wps_uwgc_custmizable_before_main_content_html() {
			?>
			<div class="woocommerce-error" id="wps_wgm_error_notice" style="display:none;">

			</div>
			<?php
		}

		/**
		 * This is function is used to Add Email Template for  Custmizable Giftcard.
		 *
		 * @name wps_uwgc_customized_giftcard_email_template
		 * @param array $args Arguments.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_customized_giftcard_email_template( $args ) {

			$custmizable_giftcard_settings = get_option( 'wps_wgm_customizable_settings', array() );

			$html = '';
			if ( ! empty( $args ) ) {

				$disclaimer = $this->wps_uwgc_get_custmizable_disclaimer();
				if ( isset( $args['choosen_image'] ) && ! empty( $args['choosen_image'] ) ) {
					if ( 'Giftcard' == $args['choosen_image'] ) {
						$featured_image = WPS_UWGC_URL . 'custmizable-gift-card/images/gift card-1.jpg';
					} elseif ( 'Christmas' == $args['choosen_image'] ) {
						$featured_image = WPS_UWGC_URL . 'custmizable-gift-card/images/christmas.jpg';
					} elseif ( 'Newyear' == $args['choosen_image'] ) {
						$featured_image = WPS_UWGC_URL . 'custmizable-gift-card/images/new year.jpg';
					} elseif ( 'Anniversary' == $args['choosen_image'] ) {
						$featured_image = WPS_UWGC_URL . 'custmizable-gift-card/images/anniversary.jpg';
					} elseif ( 'Birthday' == $args['choosen_image'] ) {
						$featured_image = WPS_UWGC_URL . 'custmizable-gift-card/images/happy birthday.jpg';
					} elseif ( 'Custom' == $args['choosen_image'] ) {
						$featured_image = WPS_UWGC_UPLOAD_URL . '/cgc_own_img/' . $args['cgc_file_name'];
					} else {
						$featured_image = $args['choosen_image'];
					}
				}
				$featured_image = "<img src='$featured_image'  class='center-on-narrow' style='width: 100%; margin: 0 auto; line-height: 1.5;font-family: Helvetica;box-sizing: border-box;border-collapse: collapse';>";

				$html = '<div class="main-container" width="600px" style="width: 600px; margin: 0 auto;line-height: 1.5;font-family: Helvetica;background-color: #ffffff;box-shadow: 2px 3px 10px rgba(0,0,0,0.22);padding: 15px 20px;border: 1px solid #e4dada;box-sizing: border-box;"><table class="main-container" width="100%" cellpadding="0" cellpadding="0" style="width: 100%; margin: 0 auto;line-height: 1.5;font-family: Helvetica;box-sizing: border-box;border-collapse: collapse;"><tbody><tr><td>[FEATUREDIMAGE]</td></tr><tr style="background-color: [BGCOLOR];color: #ffffff;"><td style="padding:20px 15px;"><p style="text-align: center;margin: 0;"><span style="font-size: 30px;font-weight: bold;border: 2px solid #fff;padding: 8px 40px;display: inline-block;background-color: #ffffFF;border-radius: 5px;color: #000000;">[COUPONCODE]</span></p><div style="font-size:22px; clear:both; display: block;margin-top: 20px;"><div style="float: left;"><span style="font-weight: bold;">' . __( 'ED-', 'giftware' ) . '</span> [EXPIRYDATE]</div><div style="float: right;font-weight: bold;">[AMOUNT]</div></div></td></tr><tr><td style="text-align: center;background-color: [MIDDLECOLOR];padding: 20px 10px;font-size: 16px;"><div>[MESSAGE]</div><div style="text-align: right;font-size: 22px;padding-top: 10px;"><span style="font-weight: bold;">' . __( 'From:', 'giftware' ) . '</span>[FROM]</div></td></tr><tr style="background-color: [DESCLAIMERCOLOR];color: #ffffff;;text-align: center;margin-top: 15px;"><td style="padding: 30px 10px;">[DISCLAIMERTEXT]</td></tr></tbody></table></div><style>@media screen and (max-width: 600px){.main-container{width: 100% !important;}}</style>';

				$html = str_replace( '[FEATUREDIMAGE]', $featured_image, $html );
				$html = str_replace( '[EXPIRYDATE]', $args['expirydate'], $html );
				$html = str_replace( '[AMOUNT]', $args['amount'], $html );
				$html = str_replace( '[MESSAGE]', nl2br( $args['message'] ), $html );
				$html = str_replace( '[FROM]', $args['from'], $html );
				$html = str_replace( '[DISCLAIMERTEXT]', $disclaimer, $html );
				$html = str_replace( '[COUPONCODE]', $args['coupon'], $html );

				if ( ! empty( $custmizable_giftcard_settings['wps_wgm_custom_giftcard_bg_color'] ) ) {
					$html = str_replace( '[BGCOLOR]', $custmizable_giftcard_settings['wps_wgm_custom_giftcard_bg_color'], $html );
				} else {
					$html = str_replace( '[BGCOLOR]', '#e33b3b', $html );
				}

				if ( ! empty( $custmizable_giftcard_settings['wps_wgm_custom_giftcard_middle_color'] ) ) {
					$html = str_replace( '[MIDDLECOLOR]', $custmizable_giftcard_settings['wps_wgm_custom_giftcard_middle_color'], $html );
				} else {
					$html = str_replace( '[MIDDLECOLOR]', '#f1f1f1', $html );
				}

				if ( ! empty( $custmizable_giftcard_settings['wps_wgm_custom_giftcard_desclaimer_color'] ) ) {
					$html = str_replace( '[DESCLAIMERCOLOR]', $custmizable_giftcard_settings['wps_wgm_custom_giftcard_desclaimer_color'], $html );
				} else {
					$html = str_replace( '[DESCLAIMERCOLOR]', '#9b9090', $html );
				}

				$html = apply_filters( 'wps_cgc_customized_giftcard_email_template', $html, $disclaimer, $featured_image, $args );
			}
			return $html;
		}

		/**
		 * Upload images for customizable giftcard.
		 */
		public function wps_cgc_custmizable_upload_own_img() {
			check_ajax_referer( 'wps-cgc-verify-nonce', 'wps_nonce' );
			$response['result'] = false;
			$response['message'] = __( 'Image cannot upload right now, please try later!', 'giftware' );
			$upload_dir_path = WPS_UWGC_UPLOAD_DIR . '/cgw_own_img';
			if ( ! is_dir( $upload_dir_path ) ) {
				wp_mkdir_p( $upload_dir_path );
				chmod( $upload_dir_path, 0775 );
			}
			$wps_file_type = isset( $_FILES['file']['type'] ) ? sanitize_text_field( wp_unslash( $_FILES['file']['type'] ) ) : '';
			$wps_file_type = isset( $_FILES['file']['type'] ) ? sanitize_text_field( wp_unslash( $_FILES['file']['type'] ) ) : '';
			if ( ( 'image/gif' == $wps_file_type ) || ( 'image/jpeg' == $wps_file_type ) || ( 'image/jpg' == $wps_file_type ) || ( 'image/pjpeg' == $wps_file_type ) || ( 'image/x-png' == $wps_file_type ) || ( 'image/png' == $wps_file_type ) ) {
				$file_name = isset( $_FILES['file']['name'] ) ? sanitize_text_field( wp_unslash( $_FILES['file']['name'] ) ) : '';
				if ( ! file_exists( WPS_UWGC_UPLOAD_DIR . '/cgw_own_img/' . $file_name ) ) {
					$wps_temp_name = isset( $_FILES['file']['tmp_name'] ) ? sanitize_text_field( wp_unslash( $_FILES['file']['tmp_name'] ) ) : '';
					move_uploaded_file( $wps_temp_name, WPS_UWGC_UPLOAD_DIR . '/cgw_own_img/' . $file_name );
				}
				$response['result'] = true;
				$response['message'] = __( 'Successfully Uploaded!', 'giftware' );
			}
			echo json_encode( $response );
			wp_die();
		}

		/**
		 * Upload images for customizable giftcard.
		 */
		public function wps_cgc_custmizable_admin_uploads_name() {
			check_ajax_referer( 'wps-cgc-verify-nonce', 'wps_nonce' );
			$image = isset( $_POST['image_name'] ) ? sanitize_text_field( wp_unslash( $_POST['image_name'] ) ) : '';
			if ( isset( $image ) && ! empty( $image ) ) {
				print_r( $image );
				die;
			}
		}

		/**
		 * Item meta data for customizable giftcard.
		 *
		 * @param array $item_meta Item meta.
		 * @param mixed $product_id Product id.
		 */
		public function wps_cgc_custmizable_item_meta_data( $item_meta, $product_id ) {

			// phpcs:disable WordPress.Security.NonceVerification.Missing
			check_admin_referer( 'wps_wgm_single_nonce', 'wps_wgm_single_nonce_field' );
			if ( isset( $_POST['selected_image'] ) && ! empty( $_POST['selected_image'] ) ) {
				$image_entire_url = sanitize_text_field( wp_unslash( $_POST['selected_image'] ) );
				$item_meta['wps_cgc_image'] = $image_entire_url;
				if ( 'Custom' == $_POST['selected_image'] ) {
					$upload_dir_path = WPS_UWGC_UPLOAD_DIR . '/cgc_own_img';
					if ( ! is_dir( $upload_dir_path ) ) {
						wp_mkdir_p( $upload_dir_path );
						chmod( $upload_dir_path, 0775 );
					}
					$wps_file_custom_type = isset( $_FILES['wps_cgc_custom_img']['type'] ) ? sanitize_text_field( wp_unslash( $_FILES['wps_cgc_custom_img']['type'] ) ) : '';
					if ( ( 'image/gif' == $wps_file_custom_type ) || ( 'image/jpeg' == $wps_file_custom_type ) || ( 'image/jpg' == $wps_file_custom_type ) || ( 'image/pjpeg' == $wps_file_custom_type ) || ( 'image/x-png' == $wps_file_custom_type ) || ( 'image/png' == $wps_file_custom_type ) ) {
						$file_name = isset( $_FILES['wps_cgc_custom_img']['name'] ) ? sanitize_text_field( wp_unslash( $_FILES['wps_cgc_custom_img']['name'] ) ) : '';
						if ( ! file_exists( WPS_UWGC_UPLOAD_DIR . '/cgc_own_img/' . $file_name ) ) {
							$wps_temp_name = isset( $_FILES['wps_cgc_custom_img']['tmp_name'] ) ? sanitize_text_field( wp_unslash( $_FILES['wps_cgc_custom_img']['tmp_name'] ) ) : '';
							move_uploaded_file( $wps_temp_name, WPS_UWGC_UPLOAD_DIR . '/cgc_own_img/' . $file_name );
						}
						$item_meta['wps_cgc_custom_img'] = $file_name;
					}
				}
			}
			$item_meta = apply_filters( 'wps_cgc_item_meta_data', $item_meta, $product_id );
			return $item_meta;
			// phpcs:enable WordPress.Security.NonceVerification.Missing
		}

		/**
		 * Item meta data for customizable giftcard.
		 *
		 * @param array $wps_cgc_common_arr Instance.
		 * @param array $item Item.
		 * @param array $order Order.
		 */
		public function wps_cgc_custmizable_gift_common_arr( $wps_cgc_common_arr, $item, $order ) {

			$product_id = $wps_cgc_common_arr['product_id'];
			$is_customizable = get_post_meta( $product_id, 'woocommerce_customizable_giftware', true );
			$cgc_file_name = '';
			$choosen_image = '';
			if ( isset( $is_customizable ) && ! empty( $is_customizable ) && 'yes' == $is_customizable ) {

				$item_meta_data = $item->get_meta_data();

				foreach ( $item_meta_data as $key => $value ) {
					if ( isset( $value->key ) && 'Choosen Image' == $value->key && ! empty( $value->value ) ) {

						$choosen_image = $value->value;
					}
					if ( isset( $value->key ) && 'File Name' == $value->key && ! empty( $value->value ) ) {

						$cgc_file_name = $value->value;
					}
				}
				$wps_cgc_common_arr['choosen_image'] = $choosen_image;
				$wps_cgc_common_arr['cgc_file_name'] = $cgc_file_name;

				$wps_cgc_common_arr = apply_filters( 'wps_cgc_customizable_gc_common_arr', $wps_cgc_common_arr, $item, $order );
				return $wps_cgc_common_arr;
			} else {
				return $wps_cgc_common_arr;
			}
		}

		/**
		 * Item meta data for customizable giftcard.
		 *
		 * @param array $args Argument.
		 * @param array $item Item.
		 */
		public function wps_cgc_custmizable_resend_mail_arr_update( $args, $item ) {
			$product_id = $args['product_id'];
			$is_customizable = get_post_meta( $product_id, 'woocommerce_customizable_giftware', true );
			$cgc_file_name = '';
			$choosen_image = '';
			$cgc_uploaded_file_name = '';
			if ( isset( $is_customizable ) && ! empty( $is_customizable ) && 'yes' == $is_customizable ) {

				$item_meta_data = $item->get_meta_data();
				if ( isset( $item_meta_data ) && ! empty( $item_meta_data ) && is_array( $item_meta_data ) ) {
					foreach ( $item_meta_data as $key => $value ) {
						if ( isset( $value->key ) && 'Choosen Image' == $value->key && ! empty( $value->value ) ) {

							$choosen_image = $value->value;
						}
						if ( isset( $value->key ) && 'File Name' == $value->key && ! empty( $value->value ) ) {

							$cgc_file_name = $value->value;
						}
						if ( isset( $value->key ) && 'Uploaded File Name' == $value->key && ! empty( $value->value ) ) {

							$cgc_uploaded_file_name = $value->value;
						}
					}
				}
				$args['choosen_image'] = $choosen_image;
				$args['cgc_file_name'] = $cgc_file_name;
				$args['cgc_uploaded_file_name'] = $cgc_uploaded_file_name;

				$args = apply_filters( 'wps_cgc_customizable_gc_common_arr', $args, $item, '' );

				return $args;
			} else {
				return $args;
			}
		}
	}
}
