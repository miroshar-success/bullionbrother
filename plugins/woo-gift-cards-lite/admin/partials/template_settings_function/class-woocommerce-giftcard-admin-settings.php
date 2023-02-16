<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/admin
 */

/**This class is for generating the html for the settings.
 *
 * This file use to display the function fot the html
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/admin
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Woocommerce_Giftcard_Admin_Settings {

	/**
	 * This function is for generating for the checkbox for the Settings
	 *
	 * @name wps_wgm_generate_checkbox_html
	 * @param array $value contains the setting array.
	 * @param array $general_settings contains the setting array.
	 * @since 2.0.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_generate_checkbox_html( $value, $general_settings ) {
		if ( ( isset( $general_settings [ $value ['id'] ] ) && ( 'on' == $general_settings [ $value ['id'] ] ) ) || ( isset( $general_settings [ $value ['id'] ] ) && ( 'yes' == $general_settings [ $value ['id'] ] ) ) ) {
			$enable_wps_wgm = 1;
		} else {
			$enable_wps_wgm = 0;
		}
		?>
		<label for="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>">
			<input type="checkbox" name="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>" <?php checked( $enable_wps_wgm, 1 ); ?> id="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>" class="<?php echo esc_attr( array_key_exists( 'class', $value ) ? $value['class'] : '' ); ?>"> <?php echo esc_attr( array_key_exists( 'desc', $value ) ? $value['desc'] : '' ); ?>
		</label>
		<?php
	}

	/**
	 * This function is for generating for the radio buttons for the Settings
	 *
	 * @name wps_wgm_generate_radiobuttons_html
	 * @param array $value contains array of html.
	 * @param array $general_settings contains array of html.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 2.0.0
	 */
	public function wps_wgm_generate_radiobuttons_html( $value, $general_settings ) {
		if ( ! empty( $general_settings[ $value['name'] ] ) ) {
			$enable_wps_wgm = ( isset( $general_settings[ $value['name'] ] ) && ( $general_settings[ $value['name'] ] == $value['value'] ) ) ? 1 : 0;
		} else {
			if ( array_key_exists( 'default_value', $value ) && 1 == $value['default_value'] ) {
				$enable_wps_wgm = 1;
			} else {
				$enable_wps_wgm = 0;
			}
		}
		?>
		<label for="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>">
			<input value = "<?php echo esc_attr( array_key_exists( 'value', $value ) ? $value['value'] : '' ); ?>" type="radio" name="<?php echo esc_attr( array_key_exists( 'name', $value ) ? $value['name'] : '' ); ?>" <?php checked( $enable_wps_wgm, 1 ); ?> id="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>" class="<?php echo esc_attr( array_key_exists( 'class', $value ) ? $value['class'] : '' ); ?>"> <?php echo esc_attr( array_key_exists( 'desc', $value ) ? $value['desc'] : '' ); ?>
		</label>
		<?php
	}

	/**
	 * This function is for generating for the number field for the Settings
	 *
	 * @name wps_wgm_generate_number_html
	 * @param array $value contains array of html.
	 * @param array $general_settings contains array of html.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 2.0.0
	 */
	public function wps_wgm_generate_number_html( $value, $general_settings ) {

		$wps_wgm_value = isset( $general_settings[ $value ['id'] ] ) ? intval( $general_settings[ $value['id'] ] ) : '';
		if ( ( '' == $wps_wgm_value ) && ( array_key_exists( 'default', $value ) ) ) {
			$wps_wgm_value = $value['default'];
		}

		?>
		<label for="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>">
			<input type="number" 
			<?php
			if ( array_key_exists( 'custom_attribute', $value ) ) {

				foreach ( $value['custom_attribute'] as $attribute_name => $attribute_val ) {// @codingStandardsIgnoreLine
					 echo wp_kses_post( $attribute_name . '=' . $attribute_val );  //phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped.

				}
			}
			?>
			 value="<?php echo esc_attr( $wps_wgm_value ); ?>" name="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>" id="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>"
			class="<?php echo esc_attr( array_key_exists( 'class', $value ) ? $value['class'] : '' ); ?>"><?php echo esc_attr( array_key_exists( 'desc', $value ) ? $value['desc'] : '' ); ?>
		</label>
		<?php
	}

	/**
	 * This function is for generating for the wp_editor for the Settings
	 *
	 * @name wps_wgm_generate_label
	 * @param array $value contains array of html.
	 * @param array $notification_settings contains array of html.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 2.0.0
	 */
	public function wps_wgm_generate_wp_editor( $value, $notification_settings ) {
		if ( isset( $value['id'] ) && ! empty( $value['id'] ) ) {
			if ( array_key_exists( 'content', $value ) ) {
				$wps_wgm_content = isset( $value['content'] ) ? $value['content'] : '';
			} else {
				$wps_wgm_content = isset( $notification_settings[ $value['id'] ] ) ? $notification_settings[ $value['id'] ] : '';
			}
			$value_id = ( array_key_exists( 'id', $value ) ) ? $value['id'] : '';
			?>
			<label for="<?php echo esc_attr( $value_id ); ?>">
				<?php
				$content = stripcslashes( $wps_wgm_content );
				$editor_id = $value_id;
				$settings = array(
					'media_buttons'    => false,
					'drag_drop_upload' => true,
					'dfw'              => true,
					'teeny'            => true,
					'editor_height'    => 200,
					'editor_class'       => 'wps_wgm_new_woo_ver_style_textarea',
					'textarea_name'    => esc_attr( $value_id ),
				);
				wp_editor( $content, $editor_id, $settings );
				?>
			</label>	
			<?php
		}
	}

	/**
	 * This function is for generating for the Label for the Settings
	 *
	 * @name wps_wgm_generate_label
	 * @param array $value contains array of html.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 2.0.0
	 */
	public function wps_wgm_generate_label( $value ) {
		?>
		<label for="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>"><?php echo esc_attr( array_key_exists( 'title', $value ) ? $value['title'] : '' ); ?></label>		
		<?php
	}

	/**
	 * This function is for generating for the Tool tip for the Settings
	 *
	 * @name wps_wgm_generate_tool_tip
	 * @param array $value contains array of html.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 2.0.0
	 */
	public function wps_wgm_generate_tool_tip( $value ) {
		$allowed_tags = $this->wps_wgm_allowed_html_for_tool_tip();
		if ( array_key_exists( 'desc_tip', $value ) ) {
			echo wp_kses( wc_help_tip( $value['desc_tip'] ), $allowed_tags );

		}
		if ( array_key_exists( 'additional_info', $value ) ) {
			?>
			<span class="description"><?php echo wp_kses( $value['additional_info'], $allowed_tags ); ?></span>
			<?php
		}
	}

	/**
	 * This function is for generating for the text html
	 *
	 * @name wps_wgm_generate_textarea_html
	 * @param array $value contains array of html.
	 * @param array $general_settings contains array of html.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 2.0.0
	 */
	public function wps_wgm_generate_textarea_html( $value, $general_settings ) {
		$wps_wgm_value = isset( $general_settings[ $value['id'] ] ) ? ( $general_settings[ $value['id'] ] ) : $value['default'];
		?>
		<span class="description"><?php echo esc_attr( array_key_exists( 'desc', $value ) ? $value['desc'] : '' ); ?></span>	
		<label for="wps_wgm_general_text_points" class="wps_wgm_label">
			<textarea 
			<?php
			if ( array_key_exists( 'custom_attribute', $value ) ) {
				foreach ( $value['custom_attribute'] as $attribute_name => $attribute_val ) {
					echo wp_kses_post( $attribute_name . '=' . $attribute_val );

				}
			}
			?>
			  name="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>" id="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>"
			class="<?php echo esc_attr( array_key_exists( 'class', $value ) ? $value['class'] : '' ); ?>"><?php echo esc_attr( array_key_exists( 'desc', $value ) ? $value['desc'] : '' ); ?><?php echo esc_attr( $wps_wgm_value ); ?>
		</textarea>
	</label>
	<p class="description"><?php echo esc_attr( array_key_exists( 'desc2', $value ) ? $value['desc2'] : '' ); ?></p>
		<?php
	}

	/**
	 * This function is for generating the notice of the save settings
	 *
	 * @name wps_wgm_generate_textarea_html
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 2.0.0
	 */
	public function wps_wgm_settings_saved() {
		?>
		<div class="notice notice-success is-dismissible">
			<p><strong><?php esc_html_e( 'Settings saved.', 'woo-gift-cards-lite' ); ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e( 'Dismiss notice.', 'woo-gift-cards-lite' ); ?></span>
			</button>
		</div>
		<?php
	}

	/**
	 * Generate save button html for setting page
	 *
	 * @since 2.0.0
	 * @name wps_wgm_save_button_html()
	 * @param string $name name of button.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_save_button_html( $name ) {
		?>
		<p class="submit">
			<input type="submit" value="<?php esc_attr_e( 'Save changes', 'woo-gift-cards-lite' ); ?>" class="wps_wgm_save_button" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $name ); ?>" >
			</p>
			<?php
	}

	/**
	 * This function is for generating for the text html
	 *
	 * @name wps_wgm_generate_text_html
	 * @param array $value contains array of settings.
	 * @param array $general_settings contains array of settings.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 2.0.0
	 */
	public function wps_wgm_generate_text_html( $value, $general_settings ) {
		$wps_wgm_value = isset( $general_settings[ $value['id'] ] ) ? ( $general_settings[ $value['id'] ] ) : '';
		?>
		<label for="
		<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>">
		<input type="text" 
		<?php
		if ( array_key_exists( 'custom_attribute', $value ) ) {
			foreach ( $value['custom_attribute'] as $attribute_name => $attribute_val ) {
				echo wp_kses_post( $attribute_name . '=' . $attribute_val );
			}
		}
		?>
		 
		style ="<?php echo esc_attr( array_key_exists( 'style', $value ) ? $value['style'] : '' ); ?>"
		value="<?php echo esc_attr( $wps_wgm_value ); ?>" name="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>" id="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>"
		class="<?php echo esc_attr( array_key_exists( 'class', $value ) ? $value['class'] : '' ); ?>"><?php echo esc_attr( array_key_exists( 'desc', $value ) ? $value['desc'] : '' ); ?>
	</label>
		<?php
	}

	/**
	 * Generate Drop down menu fields
	 *
	 * @since 2.0.0
	 * @name wps_wgm_generate_search_select_html()
	 * @param array $value contains array of settings.
	 * @param array $general_settings contains array of settings.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_generate_search_select_html( $value, $general_settings ) {
		$selectedvalue = isset( $general_settings[ $value['id'] ] ) ? ( $general_settings[ $value['id'] ] ) : array();
		if ( '' == $selectedvalue ) {
			$selectedvalue = '';
		}

		?>
		<select name="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>[]" id="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>" multiple = "<?php echo esc_attr( array_key_exists( 'multiple', $value ) ? $value['multiple'] : '' ); ?>"
			<?php
			if ( array_key_exists( 'custom_attribute', $value ) ) {
				foreach ( $value['custom_attribute'] as $attribute_name => $attribute_val ) {
					echo wp_kses_post( $attribute_name . '=' . $attribute_val );
				}
			}
			if ( is_array( $value['options'] ) && ! empty( $value['options'] ) ) {
				foreach ( $value['options'] as $option ) {
					$select = 0;
					if ( is_array( $selectedvalue ) && in_array( $option['id'], $selectedvalue ) && ! empty( $selectedvalue ) ) {
						$select = 1;
					}
					?>
					><option value="<?php echo esc_attr( $option['id'] ); ?>" <?php echo selected( 1, $select ); ?> ><?php echo esc_attr( $option['name'] ); ?></option>
					<?php
				}
			}
			?>
		</select>
	</label>
		<?php
	}

	/**
	 * Get the entire category in store
	 *
	 * @since 2.0.0
	 * @name wps_wgm_get_category()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_get_category() {
		$args = array( 'taxonomy' => 'product_cat' );
		$categories = get_terms( $args );
		$category_data = $this->wps_wgm_show_category( $categories );
		return $category_data;
	}

	/**
	 * Returns the category id and name
	 *
	 * @since 2.0.0
	 * @name wps_wgm_show_category()
	 * @param array $categories contain array of categories.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_show_category( $categories ) {
		if ( isset( $categories ) && ! empty( $categories ) ) {
			$category = array();
			foreach ( $categories as $cat ) {
				$category[] = array(
					'id' => $cat->term_id,
					'name' => $cat->name,
				);
			}
			return $category;
		}
	}

	/**
	 * Returns globally excluded products
	 *
	 * @since 1.0.0
	 * @name wps_wgm_get_product()
	 * @param string $id contain id of tag.
	 * @param string $tag contain tag.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_get_product( $id, $tag ) {
		$wps_wgm_exclude_product = get_option( $tag, false );
		if ( is_array( $wps_wgm_exclude_product ) && isset( $wps_wgm_exclude_product[ $id ] ) && ! empty( $wps_wgm_exclude_product[ $id ] ) && is_array( $wps_wgm_exclude_product[ $id ] ) ) {
			$wps_wgm_get_product = array();
			foreach ( $wps_wgm_exclude_product[ $id ] as $pro_id ) {
				$product      = wc_get_product( $pro_id );
				if ( ! empty( $product ) ) {
					$wps_wgm_get_product[] = array(
						'id' => $pro_id,
						'name' => $product->get_formatted_name(),
					);
				}
			}
			return $wps_wgm_get_product;
		} else {
			$wps_wgm_exclude_product = array();
			return $wps_wgm_exclude_product;
		}
	}

	/**
	 * Generates input text with button
	 *
	 * @since 2.0.0
	 * @name wps_wgm_generate_input_text_with_button_html()
	 * @param array $value contain array of html.
	 * @param array $general_settings Contains array of html.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_generate_input_text_with_button_html( $value, $general_settings ) {
		if ( isset( $value['custom_attribute'] ) && ! empty( $value['custom_attribute'] ) && is_array( $value['custom_attribute'] ) ) {
			foreach ( $value['custom_attribute'] as $key => $val ) {
				if ( 'text' == $val['type'] ) {
					$this->wps_wgm_generate_text_html( $val, $general_settings );
				} elseif ( 'button' == $val['type'] ) {
					$this->wps_wgm_generate_button_html( $val );
				} elseif ( 'paragraph' == $val['type'] ) {
					$this->wps_wgm_generate_showbox( $val );
				}
			}
		}
		$this->wps_wgm_generate_bottom_description_field( $value );
	}

	/**
	 * Generates button
	 *
	 * @since 2.0.0
	 * @name wps_wgm_generate_input_text_with_button_html()
	 * @param array $val Contains array of html.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_generate_button_html( $val ) {
		?>
		<input class = "<?php echo esc_attr( array_key_exists( 'class', $val ) ? $val['class'] : '' ); ?>"  type = "button" value = "<?php echo esc_attr( array_key_exists( 'value', $val ) ? $val['value'] : '' ); ?>" />
		<?php
	}

	/**
	 * Generates paragraph to show picture
	 *
	 * @since 2.0.0
	 * @name wps_wgm_generate_showbox()
	 * @param array $val Contains array of html.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_generate_showbox( $val ) {
		?>
		<p id="<?php echo esc_attr( array_key_exists( 'id', $val ) ? $val['id'] : '' ); ?>">
			<span class="<?php echo esc_attr( array_key_exists( 'id', $val ) ? $val['id'] : '' ); ?>">
				<img src="" width="150px" height="150px" id="<?php echo esc_attr( array_key_exists( 'imgId', $val ) ? $val['imgId'] : '' ); ?>">
				<span class="<?php echo esc_attr( array_key_exists( 'spanX', $val ) ? $val['spanX'] : '' ); ?>">X</span>
			</span>
		</p>
		<?php
	}

	/**
	 * This function is for generating common settings html
	 *
	 * @name wps_wgm_sanitize_settings_data
	 * @param array $setting_html_array Contains array of settings.
	 * @param array $saved_settings Contains array of saved settings.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 2.0.0
	 */
	public function wps_wgm_generate_common_settings( $setting_html_array, $saved_settings ) {
		if ( isset( $setting_html_array ) && is_array( $setting_html_array ) && ! empty( $setting_html_array ) ) {
			foreach ( $setting_html_array  as $key => $value ) {
				?>
				<tr valign="top">			
					<th scope="row" class="titledesc">
						<?php $this->wps_wgm_generate_label( $value ); ?>
					</th>
					<td class="forminp forminp-text">
						<?php
						$this->wps_wgm_generate_tool_tip( $value );
						if ( 'checkbox' == $value['type'] ) {
							$this->wps_wgm_generate_checkbox_html( $value, $saved_settings );
						} elseif ( 'number' == $value['type'] ) {
							$this->wps_wgm_generate_number_html( $value, $saved_settings );
						} elseif ( 'text' == $value['type'] ) {
							$this->wps_wgm_generate_text_html( $value, $saved_settings );
						} elseif ( 'search&select' == $value['type'] ) {
							$this->wps_wgm_generate_search_select_html( $value, $saved_settings );
						} elseif ( 'radio' == $value['type'] ) {
							$this->wps_wgm_generate_radiobuttons_html( $value, $saved_settings );
						} elseif ( 'textWithButton' == $value['type'] ) {
							$this->wps_wgm_generate_input_text_with_button_html( $value, $saved_settings );
						} elseif ( 'wp_editor' == $value['type'] ) {
							$this->wps_wgm_generate_wp_editor( $value, $saved_settings );
						} elseif ( 'textWithDesc' == $value['type'] ) {
							$this->wps_wgm_generate_text_with_description( $value, $saved_settings );
						} elseif ( 'textarea' == $value['type'] ) {
							$this->wps_wgm_generate_textarea_html( $value, $saved_settings );
						}
						do_action( 'wps_wgm_admin_setting_fields_html', $value, $saved_settings );
						?>
																		
					</td>
				</tr>
				<?php
			}
		}
	}

	/**
	 * This function is used to generate text with description
	 *
	 * @name wps_wgm_generate_text_with_description
	 * @param array $setting_html_array Contains array of settings.
	 * @param array $saved_settings Contains array of saved settings.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 2.0.0
	 */
	public function wps_wgm_generate_text_with_description( $setting_html_array, $saved_settings ) {
		$this->wps_wgm_generate_text_html( $setting_html_array, $saved_settings );
		$this->wps_wgm_generate_bottom_description_field( $setting_html_array );
	}

	/**
	 * This function is used to generate bottom description field.
	 *
	 * @name wps_wgm_generate_bottom_description_field
	 * @param array $setting_html_array contains array of setting html.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 2.0.0
	 */
	public function wps_wgm_generate_bottom_description_field( $setting_html_array ) {
		?>
		<p class="<?php echo esc_attr( array_key_exists( 'class', $setting_html_array ) ? $setting_html_array['class'] : '' ); ?>"><?php echo esc_attr( array_key_exists( 'bottom_desc', $setting_html_array ) ? $setting_html_array['bottom_desc'] : '' ); ?></p>
		<?php
	}

	/**
	 * This function is used to sanitize email settings data
	 *
	 * @name wps_wgm_sanitize_email_settings_data
	 * @param array $posted_data contains array of posted data.
	 * @param array $setting_html_array contains array of setting html.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 2.0.0
	 */
	public function wps_wgm_sanitize_email_settings_data( $posted_data, $setting_html_array ) {
		if ( is_array( $setting_html_array ) && ! empty( $setting_html_array ) && is_array( $posted_data ) ) {
			if ( isset( $setting_html_array['top'] ) && is_array( $setting_html_array['top'] ) ) {
				foreach ( $setting_html_array['top'] as $top_section_setting ) {
					if ( isset( $top_section_setting['id'] ) && array_key_exists( $top_section_setting['id'], $posted_data ) ) {
						if ( isset( $top_section_setting['type'] ) && ( 'text' === $top_section_setting['type'] || 'textWithDesc' === $top_section_setting['type'] ) && 'wp_editor' !== $top_section_setting['type'] ) {
							$posted_data[ $top_section_setting['id'] ] = sanitize_text_field( wp_unslash( $posted_data[ $top_section_setting['id'] ] ) );
						}
						if ( isset( $top_section_setting['type'] ) && 'wp_editor' === $top_section_setting['type'] ) {
							$posted_data[ $top_section_setting['id'] ] = wp_kses_post( wp_unslash( $posted_data[ $top_section_setting['id'] ] ) );
						}
					}
				}
			}
			if ( isset( $setting_html_array['middle'] ) && is_array( $setting_html_array['middle'] ) ) {
				foreach ( $setting_html_array['middle'] as $mid_section_setting ) {
					if ( isset( $mid_section_setting['id'] ) && array_key_exists( $mid_section_setting['id'], $posted_data ) ) {
						if ( isset( $mid_section_setting['type'] ) && ( 'text' === $mid_section_setting['type'] || 'textWithDesc' === $mid_section_setting['type'] ) && 'wp_editor' !== $mid_section_setting['type'] ) {
							$posted_data[ $mid_section_setting['id'] ] = sanitize_text_field( wp_unslash( $posted_data[ $mid_section_setting['id'] ] ) );
						}
						if ( isset( $mid_section_setting['type'] ) && 'wp_editor' === $mid_section_setting['type'] ) {
							$posted_data[ $mid_section_setting['id'] ] = wp_kses_post( wp_unslash( $posted_data[ $mid_section_setting['id'] ] ) );
						}
					}
				}
			}
			if ( isset( $setting_html_array['bottom'] ) && is_array( $setting_html_array['bottom'] ) ) {
				foreach ( $setting_html_array['bottom'] as $bottom_section_setting ) {
					if ( isset( $bottom_section_setting['id'] ) && array_key_exists( $bottom_section_setting['id'], $posted_data ) ) {
						if ( isset( $bottom_section_setting['type'] ) && ( 'text' === $bottom_section_setting['type'] || 'textWithDesc' === $mid_section_setting['type'] ) && 'wp_editor' !== $mid_section_setting['type'] ) {
							$posted_data[ $bottom_section_setting['id'] ] = sanitize_text_field( wp_unslash( $posted_data[ $bottom_section_setting['id'] ] ) );

						}
						if ( isset( $bottom_section_setting['type'] ) && 'wp_editor' === $bottom_section_setting['type'] ) {
							$posted_data[ $bottom_section_setting['id'] ] = wp_kses_post( wp_unslash( $posted_data[ $bottom_section_setting['id'] ] ) );
						}
					}
				}
			}
		}
		return $posted_data;
	}

	/**
	 * This function is used to sanitize data
	 *
	 * @name wps_wgm_sanitize_settings_data
	 * @param array $posted_data contains array of posted data.
	 * @param array $setting_html_array contains array of setting html.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 2.0.0
	 */
	public function wps_wgm_sanitize_settings_data( $posted_data, $setting_html_array ) {
		if ( isset( $posted_data ) && is_array( $posted_data ) && ! empty( $posted_data ) ) {
			foreach ( $posted_data as $posted_key => $posted_values ) {
				foreach ( $setting_html_array as $htmlkey => $htmlvalue ) {
					if ( is_array( $setting_html_array ) && in_array( $posted_key, $htmlvalue ) ) {
						if ( 'text' == $htmlvalue['type'] || 'textarea' == $htmlvalue['type'] ) {
							$posted_values = preg_replace( '/\\\\/', '', $posted_values );
							$posted_data[ $posted_key ] = sanitize_text_field( $posted_values );
						}
					}
				}
			}
		}
		return $posted_data;
	}

	/**
	 * This is function is used for the validating the data.
	 *
	 * @name wps_wgm_allowed_html
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 * @since 2.0.0
	 */
	public function wps_wgm_allowed_html_for_tool_tip() {
		$allowed_tags = array(
			'span' => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
				'data-tip' => array(),
			),
			'min' => array(),
			'max' => array(),
			'class' => array(),
			'style' => array(),
			'<br>'  => array(),
		);
		return $allowed_tags;
	}
}
