<?php
/**
 * Exit if accessed directly
 *
 * @package    Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( 'Wps_Uwgc_Setting_Html_Function' ) ) {
	/**This class is for generating the html for the settings.
	 *
	 * This file use to display the function fot the html
	 *
	 * @package    Ultimate Woocommerce Gift Cards
	 * @author     WP Swings <webmaster@wpswings.com>
	 */
	class Wps_Uwgc_Setting_Html_Function {

		/**
		 * Function to generate single selct drop down
		 *
		 * @since 1.0.0
		 * @name wps_uwgc_generate_single_select_dropdown().
		 * @param array $value Array of html.
		 * @param array $saved_settings Array of html.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_uwgc_generate_single_select_dropdown( $value, $saved_settings ) {
			$selectedvalue = isset( $saved_settings[ $value['id'] ] ) ? ( $saved_settings[ $value['id'] ] ) : array();
			if ( '' == $selectedvalue ) {
				$selectedvalue = '';
			}
			?>
			<select name="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>" class="<?php echo esc_attr( array_key_exists( 'class', $value ) ? $value['class'] : '' ); ?>">
				<?php
				if ( ! empty( $value['custom_attribute'] ) && is_array( $value['custom_attribute'] ) ) {
					foreach ( $value['custom_attribute'] as $option ) {
						$select = 0;
						if ( $selectedvalue == $option ) {
							$select = 1;
						}
						?>
						<option value="<?php echo esc_attr( $option ); ?>" <?php echo selected( 1, $select ); ?> ><?php echo esc_attr( $option ); ?></option>
						<?php
					}
				}
				?>
					 
			</select>
			<?php
		}

		/**
		 * Function to generate single selct drop dowm
		 *
		 * @since 1.0.0
		 * @name wps_wgm_generate_single_select_drop_down_with_key_value_pair().
		 * @param array $value Array of html.
		 * @param array $saved_settings Array of html.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_wgm_generate_single_select_drop_down_with_key_value_pair( $value, $saved_settings ) {
			$selectedvalue = isset( $saved_settings[ $value['id'] ] ) ? ( $saved_settings[ $value['id'] ] ) : array();
			if ( '' == $selectedvalue ) {
				$selectedvalue = '';
			}
			?>
			<select name="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>" class="<?php echo esc_attr( array_key_exists( 'class', $value ) ? $value['class'] : '' ); ?>">
				<?php
				if ( is_array( $value['custom_attribute'] ) && ! empty( $value['custom_attribute'] ) ) {
					foreach ( $value['custom_attribute'] as $option ) {
						$select = 0;
						if ( $option['id'] == $selectedvalue && ! empty( $selectedvalue ) ) {
							$select = 1;
						}
						?>
						<option value="<?php echo esc_attr( $option['id'] ); ?>" <?php echo selected( 1, $select ); ?> ><?php echo esc_attr( $option['name'] ); ?></option>
						<?php
					}
				}
				?>

			</select>
			<?php
		}

		/**
		 * This function is used to generate additional html fields.
		 *
		 * @name wps_uwgc_generate_additional_info_field
		 * @param String $text text to display.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_generate_additional_info_field( $text ) {
			?>
			<p class = "wps_uwgc_additional_info">
			<?php
			esc_html_e( $text, 'giftware' );  /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
			?>
			</p> 
			<?php
		}

		/**
		 * This function is used to generate email html
		 *
		 * @name wps_wgm_generate_email_html
		 * @param array $value Array of html.
		 * @param array $saved_settings Array of html.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_wgm_generate_email_html( $value, $saved_settings ) {
			$wps_wgm_value = isset( $saved_settings[ $value ['id'] ] ) ? $saved_settings[ $value['id'] ] : '';
			?>
			<label for="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>">
				<input type="email" 
				<?php
				if ( array_key_exists( 'custom_attribute', $value ) ) {

					foreach ( $value['custom_attribute'] as $attribute_name => $attribute_val ) {
						echo wp_kses_post( $attribute_name . '=' . $attribute_val );
					}
				}
				?>
				value="<?php echo esc_attr( $wps_wgm_value ); ?>" name="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>" id="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>"
				class="<?php echo esc_attr( array_key_exists( 'class', $value ) ? $value['class'] : '' ); ?>"><?php echo esc_attr( array_key_exists( 'desc', $value ) ? $value['desc'] : '' ); ?>
			</label>
			<?php
		}

		/**
		 * This function is used to get available payment methods for giftcard
		 *
		 * @name wps_uwgc_payment_method
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_payment_method() {
			$giftcard_available_gateways = array();
			$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
			if ( isset( $available_gateways ) ) {
				foreach ( $available_gateways as $key => $available_gateway ) {
					$giftcard_available_gateways[] = array(
						'id' => $key,
						'name' => $available_gateway->title,
					);
				}
			}
			return $giftcard_available_gateways;
		}

		/**
		 * This function is used to create the mail notification template to show the amount left
		 *
		 * @name wps_uwgc_mail_notification_to_show_amount_left
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_mail_notification_to_show_amount_left() {
			$wps_uwgc_mail_settings = get_option( 'wps_wgm_mail_settings', array() );
			if ( array_key_exists( 'wps_wgm_mail_setting_receive_coupon_message', $wps_uwgc_mail_settings ) ) {
				$giftcard_receive_coupon_message = $wps_uwgc_mail_settings['wps_wgm_mail_setting_receive_coupon_message'];
			}
			if ( empty( $giftcard_receive_coupon_message ) || null == $giftcard_receive_coupon_message ) {
				$giftcard_receive_coupon_message = '<center style="width: 100%;">
				<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px; background-color:#0467A2;">
				<tr>
				<td style="padding: 20px 0; text-align: center">
				<p style="font-size: 20px; color: #fff; font-family: sans-serif; text-align: center;">[SITENAME]</p>
				</td>
				</tr>
				</table>
				<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
				<tr>
				<td style="padding: 40px 10px;width: 100%;font-size: 12px; font-family: sans-serif; mso-height-rule: exactly; line-height:18px; text-align: center; color: #888888;">
				<p style="font-size: 18px; color: #575757; text-align: center; font-family: sans-serif;">' . __( 'Hello, This is the notification for your coupon amount. ', 'giftware' ) . '<br/>' . __( 'You have left with amount of ', 'giftware' ) . '[COUPONAMOUNT] ' . __( 'With coupon code. ', 'giftware' ) . '[COUPONCODE]</p>
				<span style="font-size: 16px; color: #575757; text-align: center; font-family: sans-serif;">' . __( 'Thank You', 'giftware' ) . '</span>
				</td>
				</tr>
				</table>
				<!-- Email Header : END -->
				<!-- Email Footer : BEGIN -->
				<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px; background-color: #FCD347;">
				<tr>
				<td style="padding: 10px 10px;width: 100%;font-size: 12px; font-family: sans-serif; mso-height-rule: exactly; line-height:18px; text-align: center; color: #888888;">
				<p style="font-size: 14px; font-family: sans-serif; color: #fff; text-align: center;">[DISCLAIMER]</p>
				</td>
				</tr>
				</table>
				</div>
				</center>';
			}
			return $giftcard_receive_coupon_message;
		}

		/**
		 * This function is used to create custom posts
		 *
		 * @name wps_uwgc_get_custom_pages
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_get_custom_pages() {
			$args = array(
				'post_type'        => 'page',
				'post_status'      => 'publish',
			);

			$loop = new WP_Query( $args );
			$wps_uwgc_custom_pages = array();
			if ( $loop->have_posts() ) :
				global $product;
				while ( $loop->have_posts() ) :
					$loop->the_post();
					$wps_uwgc_custom_pages[] = array(
						'id' => $loop->post->ID,
						'name' => $loop->post->post_title,
					);
				endwhile;
			endif;
			return $wps_uwgc_custom_pages;
		}

		/**
		 * This function is used to create custom posts
		 *
		 * @name wps_uwgc_additional_common_settings_generate_html
		 * @param array $value Array of html.
		 * @param array $saved_settings Array of saved html.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
		public function wps_uwgc_additional_common_settings_generate_html( $value, $saved_settings ) {
			$wps_uwgc_html = new WPS_UWGC_SETTING_HTML_FUNCTION();
			if ( 'singleSelectDropDown' == $value['type'] ) {
				$wps_uwgc_html->wps_uwgc_generate_single_select_dropdown( $value, $saved_settings );
			} elseif ( 'email' == $value['type'] ) {
				$this->wps_wgm_generate_email_html( $value, $saved_settings );
			} elseif ( 'button' == $value['type'] ) {
				$this->wps_wgm_generate_button_html( $value, $saved_settings );
			} elseif ( 'DiscountBox' == $value['type'] ) {
				$this->wps_wgm_generate_discount_box_html( $saved_settings );
			} elseif ( 'thankyouBox' == $value['type'] ) {
				$this->wps_uwgc_create_thankyou_box_html();
			} elseif ( 'textWithButtonForMultipleUpload' == $value['type'] ) {
				$this->wps_uwgc_text_with_button_for_multiple_upload( $value, $saved_settings );
			} elseif ( 'singleSelectDropDownWithKeyvalue' == $value['type'] ) {
				$this->wps_wgm_generate_single_select_drop_down_with_key_value_pair( $value, $saved_settings );
			} elseif ( 'tryNowSpan' == $value['type'] ) {
				$this->wps_uwgc_send_fastest_pdf_html();
			} elseif ( 'search&selectWithDesc' == $value['type'] ) {
				$settings_obj = new Woocommerce_Giftcard_Admin_Settings();
				$settings_obj->wps_wgm_generate_search_select_html( $value, $saved_settings );
				$wps_uwgc_html->wps_uwgc_generate_additional_info_field( 'Note: Enabling COD is just for testing purpose for Shipping functionality, Try to avoid it if Shipping functionality is not enable' );
			} elseif ( 'checkboxWithDesc' == $value['type'] ) {
				$settings_obj = new Woocommerce_Giftcard_Admin_Settings();
				$settings_obj->wps_wgm_generate_checkbox_html( $value, $saved_settings );
				$wps_uwgc_html->wps_uwgc_generate_additional_info_field( 'Note: Check this box only if you want to change the category for Giftcard Product.You have to select the category everytime you create a Gift Card product. Default Gift Card category will not be assigned automatically.' );
			} elseif ( 'multipleCheckbox' == $value['type'] ) {
				$this->wps_wgm_generate_multiple_checkbox_html( $saved_settings );
			} elseif ( 'TwilioDetailBox' == $value['type'] ) {
				$this->wps_wgm_generate_twilio_detail_box_html( $saved_settings );
			} elseif ( 'multipleCheckboxCheck' == $value['type'] ) {
				$this->wps_wgm_generate_multiple_checkbox_check_html( $saved_settings );
				$wps_uwgc_html->wps_uwgc_generate_additional_info_field( 'Note: Make Sure You Remove Validation from the fields which you enable here, go to OTHER SETTINGS.' );
			}

		}

		/**
		 * Wps_wgm_generate_multiple_checkbox_check_html
		 *
		 * @param array $saved_settings setting.
		 * @return void
		 */
		public function wps_wgm_generate_multiple_checkbox_check_html( $saved_settings ) {
			if ( isset( $saved_settings ) ) {
				$wps_wgm_from_field     = isset( $saved_settings['wps_wgm_from_field'] ) ? 1 : 0;
				$wps_wgm_message_field  = isset( $saved_settings['wps_wgm_message_field'] ) ? 1 : 0;
				$wps_wgm_to_email_field = isset( $saved_settings['wps_wgm_to_email_field'] ) ? 1 : 0;
				?>
				<label for="wps_wgm_from_field">
					<input type="checkbox" <?php checked( $wps_wgm_from_field, 1 ); ?> name="wps_wgm_from_field" id="wps_wgm_from_field" class="input-text"><?php esc_html_e( 'From', 'giftware' ); ?>
				</label>
				<label for="wps_wgm_message_field">
					<input type="checkbox" <?php checked( $wps_wgm_message_field, 1 ); ?> name="wps_wgm_message_field" id="wps_wgm_message_field" class="input-text"><?php esc_html_e( 'Message', 'giftware' ); ?>
				</label>
				<label for="wps_wgm_to_email_field">
					<input type="checkbox" <?php checked( $wps_wgm_to_email_field, 1 ); ?> name="wps_wgm_to_email_field" id="wps_wgm_to_email_field" class="input-text"><?php esc_html_e( 'Email and Name', 'giftware' ); ?>
				</label>
				<?php
			}
		}

		/**
		 * Function to send fastest pdf html
		 *
		 * @since 1.0.0
		 * @name wps_uwgc_send_fastest_pdf_html()
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_uwgc_send_fastest_pdf_html() {
			$wps_uwgc_new_pdf = get_option( 'wps_wgm_next_step_for_pdf_value', 'no' );
			$wps_uwgc_wkhtmltopdf = file_exists( WPS_UWGC_DIRPATH . 'wkhtmltox/bin/wkhtmltopdf' );
			if ( 'yes' !== $wps_uwgc_new_pdf || ! $wps_uwgc_wkhtmltopdf ) {
				?>
				<div class="wps_uwgc_pdf_deprecated_row wps_ml-35">
					<span><?php esc_html_e( 'Try a faster way to send PDF to the customer.', 'giftware' ); ?></span><input type="button" name="wps_uwgc_pdf_deprecated" class="wps_uwgc_pdf_deprecated" id="wps_uwgc_pdf_deprecated" value="Try Now">
				</div>
				<?php
			}
		}

		/**
		 * Function to generate multiple checkbox
		 *
		 * @since 1.0.0
		 * @name wps_wgm_generate_multiple_checkbox_html()
		 * @param array $saved_settings Array for generating mutiple checkbox.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_wgm_generate_multiple_checkbox_html( $saved_settings ) {
			if ( isset( $saved_settings ) ) {
				$wps_wgm_email_to_recipient = isset( $saved_settings['wps_wgm_email_to_recipient'] ) ? 1 : 0;
				$wps_wgm_downloadable = isset( $saved_settings['wps_wgm_downloadable'] ) ? 1 : 0;
				$wps_wgm_shipping = isset( $saved_settings['wps_wgm_shipping'] ) ? 1 : 0;
				if ( array_key_exists( 'wps_wgm_send_giftcard', $saved_settings ) && 'customer_choose' == $saved_settings['wps_wgm_send_giftcard'] ) {
					if ( array_key_exists( 'wps_wgm_customer_select_setting_enable', $saved_settings ) && is_array( $saved_settings['wps_wgm_customer_select_setting_enable'] ) ) {
						$wps_wgm_email_to_recipient = isset( $saved_settings['wps_wgm_customer_select_setting_enable']['Email_to_recipient'] ) ? $saved_settings['wps_wgm_customer_select_setting_enable']['Email_to_recipient'] : 0;
						$wps_wgm_downloadable = isset( $saved_settings['wps_wgm_customer_select_setting_enable']['Downloadable'] ) ? $saved_settings['wps_wgm_customer_select_setting_enable']['Downloadable'] : 0;
						$wps_wgm_shipping = isset( $saved_settings['wps_wgm_customer_select_setting_enable']['Shipping'] ) ? $saved_settings['wps_wgm_customer_select_setting_enable']['Shipping'] : 0;
					}
					if ( '0' == $wps_wgm_email_to_recipient && '0' == $wps_wgm_shipping && '0' == $wps_wgm_downloadable ) {
						$wps_wgm_email_to_recipient = 1;
					}
				} else {
					$wps_wgm_email_to_recipient = 0;
					$wps_wgm_shipping = 0;
					$wps_wgm_downloadable = 0;
				}
				?>
				<label for="wps_wgm_email_to_recipient">
					<input type="checkbox" <?php checked( $wps_wgm_email_to_recipient, 1 ); ?> name="wps_wgm_email_to_recipient" id="wps_wgm_email_to_recipient" class="input-text"><?php esc_html_e( 'Email To Recipient', 'giftware' ); ?>
				</label>
				<label for="wps_wgm_downloadable">
					<input type="checkbox" <?php checked( $wps_wgm_downloadable, 1 ); ?> name="wps_wgm_downloadable" id="wps_wgm_downloadable" class="input-text"><?php esc_html_e( 'Downloadable', 'giftware' ); ?>
				</label>
				<label for="wps_wgm_shipping">
					<input type="checkbox" <?php checked( $wps_wgm_shipping, 1 ); ?> name="wps_wgm_shipping" id="wps_wgm_shipping" class="input-text"><?php esc_html_e( 'Shipping', 'giftware' ); ?>
				</label>					
				<?php
			}
		}

		/**
		 * Function to generate normal button html
		 *
		 * @since 1.0.0
		 * @name wps_wgm_generate_button_html()
		 * @param array $value Array of buttons.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_wgm_generate_button_html( $value ) {
			?>
			<input type="button" name="<?php echo esc_attr( array_key_exists( 'name', $value ) ? $value['name'] : '' ); ?>" class="button-primary" id="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>" value="<?php echo esc_attr( array_key_exists( 'value', $value ) ? $value['value'] : '' ); ?>">
			<?php
			$setting_obj = new Woocommerce_Giftcard_Admin_Settings();
			$setting_obj->wps_wgm_generate_bottom_description_field( $value );
		}

		/**
		 * Function to generate Discount boc for discount tab
		 *
		 * @since 1.0.0
		 * @name wps_wgm_generate_discount_box_html()
		 * @param array $saved_settings Array of html.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_wgm_generate_discount_box_html( $saved_settings ) {
			if ( isset( $saved_settings ) && ! empty( $saved_settings ) ) {
				if ( array_key_exists( 'wps_wgm_discount_type', $saved_settings ) && ! empty( $saved_settings['wps_wgm_discount_type'] ) ) {
					$wps_wgm_discount_type = $saved_settings['wps_wgm_discount_type'];
				}
			} else {
				$wps_wgm_discount_type = 'Fixed';
			}
			?>
			<tr valign="top" class="wps_wgm_discount_row" style="display: none;" id="DiscountBox">
				<th>
					<label for="wps_wgm_discount_fields"><?php esc_html_e( 'Enter Discount within Price Range', 'giftware' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<table class="form-table wp-list-table widefat fixed striped wps_wgm_discount_table">
						<tbody class="wps_wgm_discount_tbody">	
							<tr valign="top">
								<th><?php esc_html_e( 'Minimum', 'giftware' ); ?></th>
								<th><?php esc_html_e( 'Maximum', 'giftware' ); ?></th>
								<?php
								if ( 'Fixed' == $wps_wgm_discount_type ) {
									?>
									<th><?php esc_html_e( 'Discount Amount', 'giftware' ); ?></th>
									<?php
								} elseif ( 'Percentage' == $wps_wgm_discount_type ) {
									?>
									<th><?php esc_html_e( 'Discount Percentage(%)', 'giftware' ); ?></th>
									<?php
								}
								?>
								<th class="wps_wgm_remove_discount_content"><?php esc_html_e( 'Action', 'giftware' ); ?></th>
							</tr>
							<?php
							if ( isset( $saved_settings['wps_wgm_discount_minimum'] ) && null !== $saved_settings['wps_wgm_discount_minimum'] && isset( $saved_settings['wps_wgm_discount_maximum'] ) && null !== $saved_settings['wps_wgm_discount_maximum'] && isset( $saved_settings['wps_wgm_discount_current_type'] ) && null !== $saved_settings['wps_wgm_discount_current_type'] ) {
								if ( count( $saved_settings['wps_wgm_discount_minimum'] ) == count( $saved_settings['wps_wgm_discount_maximum'] ) && count( $saved_settings['wps_wgm_discount_maximum'] ) == count( $saved_settings['wps_wgm_discount_current_type'] ) ) {
									foreach ( $saved_settings['wps_wgm_discount_minimum'] as $key => $value ) {
										?>
										<tr valign="top">
											<td class="forminp forminp-text">
												<label for="wps_wgm_discount_minimum">
													<input type="text" name="wps_wgm_discount_minimum[]" class="wps_wgm_discount_minimum input-text wc_input_price wps_price_range" required="" value="<?php echo esc_attr( $saved_settings['wps_wgm_discount_minimum'][ $key ] ); ?>">
												</label>
											</td>
											<td class="forminp forminp-text">
												<label for="wps_wgm_discount_maximum">
													<input type="text" name="wps_wgm_discount_maximum[]" class="wps_wgm_discount_maximum input-text wc_input_price wps_price_range" required="" value="<?php echo esc_attr( $saved_settings['wps_wgm_discount_maximum'][ $key ] ); ?>">
												</label>
											</td>
											<td class="forminp forminp-text">
												<label for="wps_wgm_discount_current_type">
													<input type="text" name="wps_wgm_discount_current_type[]" class="wps_wgm_discount_current_type input-text wc_input_price wps_price_range" required=""  value="<?php echo esc_attr( $saved_settings['wps_wgm_discount_current_type'][ $key ] ); ?>">
												</label>
											</td>							
											<td class="wps_wgm_remove_discount_content forminp forminp-text">
												<input type="button" value="<?php esc_html_e( 'Remove', 'giftware' ); ?>" class="wps_wgm_remove_discount button" >
											</td>
										</tr>
										<?php
									}
								}
							} else {
								?>
								<tr valign="top">
									<td class="forminp forminp-text">
										<label for="wps_wgm_discount_minimum">
											<input type="text" name="wps_wgm_discount_minimum[]" class="wps_wgm_discount_minimum input-text wc_input_price wps_price_range" required="">
										</label>
									</td>
									<td class="forminp forminp-text">
										<label for="wps_wgm_discount_maximum">
											<input type="text" name="wps_wgm_discount_maximum[]" class="wps_wgm_discount_maximum input-text wc_input_price wps_price_range" required="">
										</label>
									</td>
									<td class="forminp forminp-text">
										<label for="wps_wgm_discount_current_type">
											<input type="text" name="wps_wgm_discount_current_type[]" class="wps_wgm_discount_current_type input-text wc_input_price wps_price_range" required="">
										</label>
									</td>							
									<td class="wps_wgm_remove_discount_content forminp forminp-text">
										<input type="button" value="<?php esc_html_e( 'Remove', 'giftware' ); ?>" class="wps_wgm_remove_discount button" >
									</td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
					<input type="button" value="<?php esc_html_e( 'Add More', 'giftware' ); ?>" class="wps_wgm_add_more button wps_ml-35" id="wps_wgm_add_more">
				</td>
			</tr>
			<?php
		}

		/**
		 * Function to generate input text with button with add more feature
		 *
		 * @since 1.0.0
		 * @name wps_uwgc_text_with_button_for_multiple_upload()
		 * @param array $array_html Array of html.
		 * @param array $general_settings Array of general settings.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_uwgc_text_with_button_for_multiple_upload( $array_html, $general_settings ) {
			?>
			<div id="browse_img_section">
				<?php
				$count = 0;
				if ( isset( $general_settings ) && array_key_exists( 'wps_wgm_customize_email_template_image', $general_settings ) && ! empty( $general_settings['wps_wgm_customize_email_template_image'][0] ) && isset( $general_settings['wps_wgm_customize_email_template_image'] ) ) {

					foreach ( $general_settings['wps_wgm_customize_email_template_image'] as $key => $value ) {
						?>
						<div class="wps_upload_email_template_div">
							<input type="text" id="wps_upload_url_<?php echo esc_attr( $count ); ?>" readonly class="wps_uwgc_custamize_upload_giftcard_template_image" data-count="0" name="<?php echo esc_attr( array_key_exists( 'id', $array_html ) ? $array_html['id'] : '' ); ?>[]" value="<?php echo esc_attr( $value ); ?>"/>
							<input type="button" class="wps_wgm_customize_email_template_image button" value="<?php esc_attr_e( 'Upload', 'giftware' ); ?>" />
							<p class="wps_uwgc_customize_remove_email_template_image_para" style="display:block;">
								<span class="wps_uwgc_customize_remove_email_template_image">
									<img src="<?php echo esc_url( $value ); ?>" width="150px" height="150px">
									<span class="wps_uwgc_customize_remove_email_template_image_span" data-value="0">X</span>
								</span>
							</p>
						</div>
						<?php
						$count++;
					}
					?>
					<input class="button wps_uwgc_add_more_image"  id="<?php echo esc_attr( 'wps_uwgc_add_more_image_' . $count ); ?>" type ="button" data-count='<?php echo esc_attr( $count ); ?>' value="Add more">
					<?php
					$count++;
				} else {
					$count = 0;
					?>
					<div class="wps_upload_email_template_div">
						<input type="text" id="wps_upload_url_<?php echo esc_attr( $count ); ?>" readonly class="wps_uwgc_custamize_upload_giftcard_template_image" data-count="0" name="<?php echo esc_attr( array_key_exists( 'id', $array_html ) ? $array_html['id'] : '' ); ?>[]"/>
						<input type="button" class="wps_wgm_customize_email_template_image button" value="<?php esc_attr_e( 'Upload', 'giftware' ); ?>" />
						<p class="wps_uwgc_customize_remove_email_template_image_para">
							<span class="wps_uwgc_customize_remove_email_template_image">
								<img src="" width="150px" height="150px">
								<span class="wps_uwgc_customize_remove_email_template_image_span" data-value="0">X</span>
							</span>
						</p>
					</div>
					<?php
				}
				?>
				<p class="description"><?php esc_attr_e( 'Note: Suggested Dimension is (600*400)', 'giftware' ); ?></p>	
			</div>
			<input type="hidden" value="0">
			<?php
		}

		/**
		 * Function to generate input text with button with add more feature
		 *
		 * @since 1.0.0
		 * @name wps_uwgc_create_thankyou_box_html()
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_uwgc_create_thankyou_box_html() {
			$wps_uwgc_thankyou_settings = get_option( 'wps_wgm_thankyou_order_settings', array() );
			if ( ! empty( $wps_uwgc_thankyou_settings ) ) {
				if ( array_key_exists( 'wps_wgm_thankyouorder_type', $wps_uwgc_thankyou_settings ) ) {
					$thankyouorder_type = isset( $wps_uwgc_thankyou_settings['wps_wgm_thankyouorder_type'] ) ? $wps_uwgc_thankyou_settings['wps_wgm_thankyouorder_type'] : 'wps_wgm_fixed_thankyou';
				}
				if ( array_key_exists( 'wps_wgm_thankyouorder_minimum', $wps_uwgc_thankyou_settings ) ) {
					$thankyouorder_min = isset( $wps_uwgc_thankyou_settings['wps_wgm_thankyouorder_minimum'] ) ? $wps_uwgc_thankyou_settings['wps_wgm_thankyouorder_minimum'] : array();
				}
				if ( array_key_exists( 'wps_wgm_thankyouorder_maximum', $wps_uwgc_thankyou_settings ) ) {
					$thankyouorder_max = isset( $wps_uwgc_thankyou_settings['wps_wgm_thankyouorder_maximum'] ) ? $wps_uwgc_thankyou_settings['wps_wgm_thankyouorder_maximum'] : array();
				}
				if ( array_key_exists( 'wps_wgm_thankyouorder_current_type', $wps_uwgc_thankyou_settings ) ) {
					$thankyouorder_value = isset( $wps_uwgc_thankyou_settings['wps_wgm_thankyouorder_current_type'] ) ? $wps_uwgc_thankyou_settings['wps_wgm_thankyouorder_current_type'] : array();
				}
			} else {
				$thankyouorder_type = 'wps_wgm_fixed_thankyou';
			}
			?>
			<tr valign="top" class="wps_uwgc_thankyouorder_row" id="thankyou_box" style="display: none;">
				<th>
					<label for="wps_uwgc_thankyouorder_fields"><?php esc_html_e( 'Enter Coupon Amount within Order Range', 'giftware' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<table class="form-table wp-list-table widefat fixed striped">
						<tbody class="wps_uwgc_thankyouorder_tbody">	
							<tr valign="top">
								<th><?php esc_html_e( 'Minimum', 'giftware' ); ?></th>
								<th><?php esc_html_e( 'Maximum', 'giftware' ); ?></th>
								<?php
								if ( isset( $thankyouorder_type ) && 'wps_wgm_fixed_thankyou' == $thankyouorder_type ) {
									?>
											<th><?php esc_html_e( 'ThankYou Gift Coupon Amount', 'giftware' ); ?></th>
										<?php
								} elseif ( isset( $thankyouorder_type ) && 'wps_wgm_percentage_thankyou' == $thankyouorder_type ) {
									?>
											<th><?php esc_html_e( 'ThankYou Gift Coupon Percentage(%)', 'giftware' ); ?></th>
										<?php
								}
								?>
								<th class="wps_uwgc_remove_thankyouorder_content"><?php esc_html_e( 'Action', 'giftware' ); ?></th>
							</tr>
							<?php
							if ( isset( $thankyouorder_min ) && null !== $thankyouorder_min && isset( $thankyouorder_max ) && null !== $thankyouorder_max && isset( $thankyouorder_value ) && null !== $thankyouorder_value ) {
								if ( count( $thankyouorder_min ) == count( $thankyouorder_max ) && count( $thankyouorder_max ) == count( $thankyouorder_value ) ) {
									foreach ( $thankyouorder_min as $key => $value ) {
										?>
												<tr valign="top">
													<td class="forminp forminp-text">
														<label for="wps_wgm_thankyouorder_minimum">
															<input type="text" name="wps_wgm_thankyouorder_minimum[]" class="wps_wgm_thankyouorder_minimum input-text wc_input_price" required="" placeholder = "No minimum" value="<?php echo esc_attr( $thankyouorder_min[ $key ] ); ?>">
														</label>
													</td>
													<td class="forminp forminp-text">
														<label for="wps_wgm_thankyouorder_maximum">
															<input type="text" name="wps_wgm_thankyouorder_maximum[]" class="wps_wgm_thankyouorder_maximum input-text wc_input_price" required="" placeholder = "No maximum" value="<?php echo esc_attr( $thankyouorder_max[ $key ] ); ?>">
														</label>
													</td>
													<td class="forminp forminp-text">
														<label for="wps_wgm_thankyouorder_current_type">
															<input type="text" name="wps_wgm_thankyouorder_current_type[]" class="wps_wgm_thankyouorder_current_type input-text wc_input_price" required=""  value="<?php echo esc_attr( $thankyouorder_value[ $key ] ); ?>">
														</label>
													</td>							
													<td class="wps_uwgc_remove_thankyouorder_content forminp forminp-text">
														<input type="button" value="<?php esc_attr_e( 'Remove', 'giftware' ); ?>" class="wps_uwgc_remove_thankyouorder button" >
													</td>
												</tr>
											<?php
									}
								}
							} else {
								?>
										<tr valign="top">
											<td class="forminp forminp-text">
												<label for="wps_wgm_thankyouorder_minimum">
													<input type="text" name="wps_wgm_thankyouorder_minimum[]" class="wps_wgm_thankyouorder_minimum input-text wc_input_price" required="">
												</label>
											</td>
											<td class="forminp forminp-text">
												<label for="wps_wgm_thankyouorder_maximum">
													<input type="text" name="wps_wgm_thankyouorder_maximum[]" class="wps_wgm_thankyouorder_maximum input-text wc_input_price" required="">
												</label>
											</td>
											<td class="forminp forminp-text">
												<label for="wps_wgm_thankyouorder_current_type">
													<input type="text" name="wps_wgm_thankyouorder_current_type[]" class="wps_wgm_thankyouorder_current_type input-text wc_input_price" required="">
												</label>
											</td>							
											<td class="wps_uwgc_remove_thankyouorder_content forminp forminp-text">
												<input type="button" value="<?php esc_attr_e( 'Remove', 'giftware' ); ?>" class="wps_uwgc_remove_thankyouorder button" >
											</td>
										</tr>
									<?php
							}
							?>
						</tbody>
					</table>
					<input type="button" value="<?php esc_attr_e( 'Add More', 'giftware' ); ?>" class="wps_uwgc_add_more button" id="wps_uwgc_add_more">
				</td>
			</tr>			
			<?php
		}

		/**
		 * Function to generate input text with button with add more feature
		 *
		 * @since 1.0.0
		 * @name wps_uwgc_create_thankyou_box_html()
		 * @param array $saved_settings Array of seeting html.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		public function wps_wgm_generate_twilio_detail_box_html( $saved_settings ) {
			$wps_wgm_account_sid = '';
			$wps_wgm_auth_token = '';
			$wps_wgm_twilio_number = '';
			if ( isset( $saved_settings ) && ! empty( $saved_settings && is_array( $saved_settings ) ) ) {
				if ( array_key_exists( 'wps_wgm_account_sid', $saved_settings ) && ! empty( $saved_settings['wps_wgm_account_sid'] ) ) {
					$wps_wgm_account_sid = $saved_settings['wps_wgm_account_sid'];
				}
				if ( array_key_exists( 'wps_wgm_auth_token', $saved_settings ) && ! empty( $saved_settings['wps_wgm_auth_token'] ) ) {
					$wps_wgm_auth_token = $saved_settings['wps_wgm_auth_token'];
				}
				if ( array_key_exists( 'wps_wgm_twilio_number', $saved_settings ) && ! empty( $saved_settings['wps_wgm_twilio_number'] ) ) {
					$wps_wgm_twilio_number = $saved_settings['wps_wgm_twilio_number'];
				}
			}
			?>
			<tr valign="top">
				<table class="form-table wp-list-table widefat fixed striped">
					<tbody class="twilo_credentials" style="display: none;">	
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="wps_gw_notice"><?php esc_attr_e( 'Notice ', 'giftware' ); ?></label>
							</th>
							<td>
								<p><?php echo wp_kses_post( 'To view Twilio API credentials visit&nbsp;<a href="https://www.twilio.com/user/account/voice-sms-mms">Twilio Website</a>', 'giftware' ); ?></p>						
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="wps_wgm_account_sid"><?php esc_html_e( 'Account SID', 'giftware' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<?php
								$attribute_description = __( 'Enter a valid Twilio Account SID', 'giftware' );
								echo wp_kses_post( wc_help_tip( $attribute_description ) );/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
								?>
								<input type="text" value="<?php echo esc_attr( $wps_wgm_account_sid ); ?>" name="wps_wgm_account_sid" id="wps_wgm_account_sid" class="input-text wps_gw_new_woo_ver_style_text" > 	
							</td> 
						</tr>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="wps_wgm_auth_token"><?php esc_html_e( 'Account Auth Token', 'giftware' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<?php
								$attribute_description = __( 'Enter valid Auth Token', 'giftware' );
								echo wp_kses_post( wc_help_tip( $attribute_description ) );/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */

								?>
								<input type="text" value="<?php echo esc_attr( $wps_wgm_auth_token ); ?>" name="wps_wgm_auth_token" id="wps_wgm_auth_token" class="input-text wps_gw_new_woo_ver_style_text" > 	
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="wps_wgm_twilio_number"><?php esc_html_e( 'Twilio Number', 'giftware' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<?php
								$attribute_description = __( 'Enter a valid Twilio number to send messages from.', 'giftware' );
								echo wp_kses_post( wc_help_tip( $attribute_description ) );/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
								?>
								<input type="text" value="<?php echo esc_attr( $wps_wgm_twilio_number ); ?>" name="wps_wgm_twilio_number" id="wps_wgm_twilio_number" class="input-text wps_gw_new_woo_ver_style_text" > 	
								<p><?php echo wp_kses_post( 'Enter a valid twilio number to send messages from. To Buy a Twilio Number&nbsp;<a href="https://www.twilio.com/console/phone-numbers/search">Click</a>&nbsp;Here', 'giftware' ); ?></p>	
							</td>
						</tr>
					</tbody>
				</table>
			</tr>
			<?php
		}
	}
}
?>
