<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wpswings.com
 * @since      1.0.0
 *
 * @package    Ultimate Woocommerce Gift Cards
 * @subpackage Ultimate Woocommerce Gift Cards/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ultimate Woocommerce Gift Cards
 * @subpackage Ultimate Woocommerce Gift Cards/public
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Ultimate_Woocommerce_Gift_Card_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * Instance of Commom Function.
	 *
	 * @var string
	 */
	public $wps_common_fun;

	/**
	 * Instance of Customized Giftcard.
	 *
	 * @var string
	 */
	public $wps_custmizable_obj;


	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		require_once WPS_UWGC_DIRPATH . 'includes/class-wps-uwgc-giftcard-common-function.php';
		$this->wps_common_fun = new WPS_UWGC_Giftcard_Common_Function();
		require_once WPS_UWGC_DIRPATH . 'Qrcode/class-wps-uwgc-qrcode-barcode.php';
		require_once WPS_UWGC_DIRPATH . 'custmizable-gift-card/class-wps-uwgc-custmizable-gift-card-product.php';
		$this->wps_custmizable_obj = new Wps_Uwgc_Custmizable_Gift_Card_Product();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ultimate_Woocommerce_Gift_Card_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ultimate_Woocommerce_Gift_Card_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		global $post;
		$page         = get_post( $post->ID );
		$page_content = ! empty( $page->post_content ) ? $page->post_content : '';

		if ( is_shop() || is_product() || is_wc_endpoint_url( 'view-order' ) || is_wc_endpoint_url( 'order-received' ) || apply_filters( 'wps_wgm_load_product_script', false ) || str_contains( $page_content, 'wps_check_your_gift_card_balance' ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ultimate-woocommerce-gift-card-public.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . 'wps_uwgc_jquery-ui-datepicker', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 * s
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ultimate_Woocommerce_Gift_Card_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ultimate_Woocommerce_Gift_Card_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$general_settings = get_option( 'wps_wgm_general_settings', array() );
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();

		$product_settings   = get_option( 'wps_wgm_product_settings', array() );
		$disable_from_field = $wps_public_obj->wps_wgm_get_template_data( $product_settings, 'wps_wgm_from_field' );
		$disable_message_field = $wps_public_obj->wps_wgm_get_template_data( $product_settings, 'wps_wgm_message_field' );
		$disable_to_email_field = $wps_public_obj->wps_wgm_get_template_data( $product_settings, 'wps_wgm_to_email_field' );

		$wps_uwgc_schedule_date = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable_selected_date' );
		$wps_uwgc_date_format = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable_selected_format' );

		$other_settings = get_option( 'wps_wgm_other_settings', array() );
		$remove_validation_to = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_remove_validation_to' );
		$remove_validation_from = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_remove_validation_from' );
		$remove_validation_msg = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_remove_validation_msg' );
		$remove_validation_to_name = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_remove_validation_to_name' );

		$discount_settings = get_option( 'wps_wgm_discount_settings', array() );
		$discount_enable = $wps_public_obj->wps_wgm_get_template_data( $discount_settings, 'wps_wgm_discount_enable' );

		$notification_settings = get_option( 'wps_wgm_notification_settings', array() );
		$enable_sms_notification = $wps_public_obj->wps_wgm_get_template_data( $notification_settings, 'wps_wgm_enable_sms_notification' );

		$wps_uwgc_date_format = $this->wps_common_fun->wps_uwgc_selected_date_format_for_js( $wps_uwgc_date_format );

		$wps_uwgc = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'send_date' => __( 'Send Date: Field is empty', 'giftware' ),
			'to_name' => __( 'Recipient Name: Field is empty', 'giftware' ),
			'browse_error' => __( 'Please browse image files only', 'giftware' ),
			'discount_price_message' => __( 'Discounted Giftcard Price: ', 'giftware' ),
			'coupon_message' => __( 'Giftcard Value: ', 'giftware' ),
			'invalid_contact' => __( 'Enter Valid Contact Number', 'giftware' ),
		);

		$wps_uwgc['wps_uwgc_nonce'] = wp_create_nonce( 'wps-uwgc-verify-nonce' );
		$wps_uwgc['schedule_date'] = $wps_uwgc_schedule_date;
		$wps_uwgc['selected_date'] = $wps_uwgc_date_format;
		$wps_uwgc['remove_validation_to'] = $remove_validation_to;
		$wps_uwgc['remove_validation_from'] = $remove_validation_from;
		$wps_uwgc['remove_validation_msg'] = $remove_validation_msg;
		$wps_uwgc['remove_validation_to_name'] = $remove_validation_to_name;
		$wps_uwgc['discount_enable'] = $discount_enable;
		$wps_uwgc['enable_sms_notification'] = $enable_sms_notification;

		global $post;
		$page         = get_post( $post->ID );
		$page_content = ! empty( $page->post_content ) ? $page->post_content : '';

		if ( is_product() ) {
			global $post;
			$product_id = $post->ID;
			$product_types = wp_get_object_terms( $product_id, 'product_type' );
			if ( isset( $product_types[0] ) ) {
				$product_type = $product_types[0]->slug;
				$sell_as_a_giftcard = get_post_meta( $product_id, '_sell_as_a_giftcard' );
				if ( 'wgm_gift_card' == $product_type || ( isset( $sell_as_a_giftcard[0] ) && 'yes' === $sell_as_a_giftcard[0] ) ) {
					$is_customizable = get_post_meta( $product_id, 'woocommerce_customizable_giftware', true );
					$wps_wgm_pricing = get_post_meta( $product_id, 'wps_wgm_pricing', true );
					$wps_wgm_discount = get_post_meta( $product_id, 'wps_wgm_discount', true );

					$wps_uwgc['product_id'] = $product_id;
					$wps_uwgc['is_customizable'] = $is_customizable;
					$wps_uwgc['pricing_type'] = $wps_wgm_pricing;
					$wps_uwgc['wps_wgm_discount'] = $wps_wgm_discount;

					$wps_uwgc['disable_from_field'] = $disable_from_field;
					$wps_uwgc['disable_message_field'] = $disable_message_field;
					$wps_uwgc['disable_to_email_field'] = $disable_to_email_field;
					$wps_uwgc['is_addon_active'] = $this->is_addon_active();

					wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ultimate-woocommerce-gift-card-public.js', array( 'jquery', 'jquery-ui-datepicker' ), $this->version, false );
					wp_localize_script( $this->plugin_name, 'wps_uwgc_param', $wps_uwgc );
				}
			}
		} else if ( apply_filters( 'wps_wgm_load_product_script', false ) ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ultimate-woocommerce-gift-card-public.js', array( 'jquery', 'jquery-ui-datepicker' ), $this->version, false );
			wp_localize_script( $this->plugin_name, 'wps_uwgc_param', $wps_uwgc );
		} else if ( str_contains( $page_content, 'wps_check_your_gift_card_balance' ) ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ultimate-woocommerce-gift-card-public.js', array( 'jquery', 'jquery-ui-datepicker' ), $this->version, false );
			wp_localize_script( $this->plugin_name, 'wps_uwgc_param', $wps_uwgc );
		}

		if ( is_wc_endpoint_url( 'order-received' ) || is_wc_endpoint_url( 'view-order' ) ) {
			wp_enqueue_script( 'wps-thankyoupage-script', WPS_UWGC_URL . 'public/js/wps-thankyoupage-whatsapp.js', array( 'jquery' ), time(), false );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ultimate-woocommerce-gift-card-public.js', array( 'jquery', 'jquery-ui-datepicker' ), $this->version, false );
			wp_localize_script( $this->plugin_name, 'wps_uwgc_param', $wps_uwgc );
		}
	}

	/**
	 * Wps_wgm_load_product_script_on_custom_page
	 *
	 * @param boolean $flag flag.
	 */
	public function wps_wgm_load_product_script_on_custom_page( $flag ) {
		$other_settings = get_option( 'wps_wgm_other_settings', array() );
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_wgm_gc_custom_page = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_render_product_custom_page' );
		global $wp_query;
		$page_title = ! empty( $wp_query->post ) ? $wp_query->post->post_title : '';
		if ( 'on' == $wps_wgm_gc_custom_page ) {
			$wps_wgm_custom_page_selection = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_custom_page_selection' );
			if ( 'Select Custom Page' !== $wps_wgm_custom_page_selection ) {
				$page_name = get_the_title( $wps_wgm_custom_page_selection );
				if ( $page_title == $page_name ) {
					global $post;
					$slug = $post->post_name;
					$url = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
					$url_last_index = explode( '/', $url );
					if ( in_array( $slug, $url_last_index ) ) {
						wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ultimate-woocommerce-gift-card-public.js', array( 'jquery', 'jquery-ui-datepicker' ), $this->version, false );
						$flag = true;
					}
				}
			}
		}
		return $flag;
	}

	/**
	 * This function is used to show the product price at shop as well as product single page
	 *
	 * @name wps_uwgc_pricing_html
	 * @param string $price_html Contains html to show price.
	 * @param object $product Contains product object.
	 * @param array  $product_pricing Contains product price.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_pricing_html( $price_html, $product, $product_pricing ) {
		$product_id = $product->get_id();
		$wps_wgm_discount_data = $this->wps_uwgc_common_discount_function( $product_id );
		$discount_min = $wps_wgm_discount_data['discount_min'];
		$discount_max = $wps_wgm_discount_data['discount_max'];
		$discount_type = $wps_wgm_discount_data['discount_type'];
		$discount_value = $wps_wgm_discount_data['discount_value'];
		$discount_applicable = false;

		if ( isset( $product_pricing['type'] ) && 'wps_wgm_default_price' == $product_pricing['type'] ) {

			$default_price = $product_pricing['default_price'];
			if ( isset( $wps_wgm_discount_data['wps_wgm_discount'] ) && 'yes' == $wps_wgm_discount_data['wps_wgm_discount'] ) {
				if ( isset( $wps_wgm_discount_data['discount_enable'] ) && 'on' == $wps_wgm_discount_data['discount_enable'] ) {
					if ( isset( $discount_min ) && null !== $discount_min && isset( $discount_max ) && null !== $discount_max && isset( $discount_value ) && null !== $discount_value ) {
						foreach ( $discount_min as $key => $value ) {
							if ( $discount_min[ $key ] <= $default_price && $default_price <= $discount_max[ $key ] ) {
								if ( 'Percentage' == $discount_type ) {
									$new_price = $default_price - ( $default_price * $discount_value[ $key ] ) / 100;
								} else {
									$new_price = $default_price - $discount_value[ $key ];
								}
								$discount_applicable = true;
							}
						}
					}
				}
				if ( $discount_applicable ) {
					// for price based on country.
					if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {

						if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
							$default_price = wcpbc_the_zone()->get_exchange_rate_price( $default_price );
							$new_price = wcpbc_the_zone()->get_exchange_rate_price( $new_price );
						}
						$price_html = '<del>' . wc_price( $default_price ) . $product->get_price_suffix() . '</del><ins>' . wc_price( $new_price ) . $product->get_price_suffix() . '</ins>';
					} elseif ( ! is_admin() && function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
						$default_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $default_price );
						$new_price     = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $new_price );
						$price_html    = '<del>' . wps_mmcsfw_get_custom_currency_symbol( '' ) . ( $default_price ) . $product->get_price_suffix() . '</del><ins>' . wps_mmcsfw_get_custom_currency_symbol( '' ) . ( $new_price ) . $product->get_price_suffix() . '</ins>';
					} else {
						$price_html = '<del>' . wc_price( $default_price ) . $product->get_price_suffix() . '</del><ins>' . wc_price( $new_price ) . $product->get_price_suffix() . '</ins>';
					}
				}
			}
		}
		return $price_html;
	}
	/**
	 * Function to get discount settings data.
	 *
	 * @name wps_uwgc_common_discount_function
	 * @param mixed $product_id contains product id.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_common_discount_function( $product_id ) {

		$wps_wgm_discount_settings = get_option( 'wps_wgm_discount_settings', array() );
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_wgm_discount_array = array(
			'wps_wgm_discount' => get_post_meta( $product_id, 'wps_wgm_discount', true ),
			'discount_enable'   => $wps_public_obj->wps_wgm_get_template_data( $wps_wgm_discount_settings, 'wps_wgm_discount_enable' ),
			'discount_min'     => $wps_public_obj->wps_wgm_get_template_data( $wps_wgm_discount_settings, 'wps_wgm_discount_minimum' ),
			'discount_max'     => $wps_public_obj->wps_wgm_get_template_data( $wps_wgm_discount_settings, 'wps_wgm_discount_maximum' ),
			'discount_value'   => $wps_public_obj->wps_wgm_get_template_data( $wps_wgm_discount_settings, 'wps_wgm_discount_current_type' ),
			'discount_type'    => $wps_public_obj->wps_wgm_get_template_data( $wps_wgm_discount_settings, 'wps_wgm_discount_type' ),
		);
		return $wps_wgm_discount_array;
	}

	/**
	 * Adds Discount fields for Default Pricing Type Gift Card Product
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_default_price_discount().
	 * @param array $product Contains product.
	 * @param array $product_pricing Contains product price.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_default_price_discount( $product, $product_pricing ) {

		global $post;
		$product_id = $post->ID;

		$wps_wgm_discount_data = $this->wps_uwgc_common_discount_function( $product_id );
		if ( isset( $wps_wgm_discount_data['discount_enable'] ) && 'on' == $wps_wgm_discount_data['discount_enable'] ) {
			if ( isset( $wps_wgm_discount_data['wps_wgm_discount'] ) && 'yes' == $wps_wgm_discount_data['wps_wgm_discount'] ) {
				$default_price = $product_pricing['default_price'];
				// for price based on country.
				if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
					if ( null !== wcpbc_the_zone() && wcpbc_the_zone() ) {
						$default_price = wcpbc_the_zone()->get_exchange_rate_price( $default_price );
					}
					?>
					<span style="color:green;">
					<?php
					esc_html_e( 'Coupon Amount will be: ', 'giftware' );
					echo wp_kses_post( wc_price( $default_price ) );
					?>
					</span>
					<?php
				} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
					$default_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $default_price );
					?>
					<span style="color:green;">
					<?php
					esc_html_e( 'Coupon Amount will be: ', 'giftware' );
					echo wp_kses_post( wc_price( $default_price ) );
					?>
					</span>
					<?php
				} else {
					?>
					<span style="color:green;">
					<?php
					esc_html_e( 'Coupon Amount will be: ', 'giftware' );
					echo esc_html( $default_price );
					?>
					</span>
					<?php
				}
			}
		}
	}
	/**
	 * This function is used update product price on cart page
	 *
	 * @name wps_uwgc_before_calculate_totals
	 * @param mixed $gift_price price of giftcard.
	 * @param mixed $value gifcard value.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_before_calculate_totals( $gift_price, $value ) {

		$product_id = $value['product_id'];
		$wps_wgm_discount_data = $this->wps_uwgc_common_discount_function( $product_id );

		$discount_min = $wps_wgm_discount_data['discount_min'];
		$discount_max = $wps_wgm_discount_data['discount_max'];
		$discount_type = $wps_wgm_discount_data['discount_type'];
		$discount_value = $wps_wgm_discount_data['discount_value'];
		$discount_applicable = false;
		if ( isset( $wps_wgm_discount_data['discount_enable'] ) && 'on' == $wps_wgm_discount_data['discount_enable'] ) {
			if ( isset( $wps_wgm_discount_data['wps_wgm_discount'] ) && 'yes' == $wps_wgm_discount_data['wps_wgm_discount'] ) {

				if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {

					if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
						$exchange_rate = wcpbc_the_zone()->get_exchange_rate();
						$gift_price = floatval( $gift_price / $exchange_rate );
					}
				}

				if ( isset( $discount_min ) && null !== $discount_min && isset( $discount_max ) && null !== $discount_max && isset( $discount_value ) && null !== $discount_value ) {

					foreach ( $discount_min as $key => $values ) {

						if ( $discount_min[ $key ] <= $gift_price && $gift_price <= $discount_max[ $key ] ) {

							if ( 'Percentage' == $discount_type ) {
								$new_price = $gift_price - ( $gift_price * $discount_value[ $key ] ) / 100;
							} else {
								$new_price = $gift_price - $discount_value[ $key ];
							}
							$discount_applicable = true;

						}
					}
				}
			}
		}
		if ( $discount_applicable ) {
			if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {

				if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
					$new_price = wcpbc_the_zone()->get_exchange_rate_price( $new_price );
				}
			}

			$gift_price = apply_filters( 'wps_uwgc_before_calculate_totals', $new_price, $value );

		}
		return $gift_price;

	}

	/**
	 * Adds Discount fields for Range Pricing Type Gift Card Product
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_range_price_discount()
	 * @param array $product Conatins product data.
	 * @param array $product_pricing Contains product price.
	 * @param mixed $text_box_price Contains text box price.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_range_price_discount( $product, $product_pricing, $text_box_price ) {
		$product_id = $product->get_id();
		$wps_wgm_discount_data = $this->wps_uwgc_common_discount_function( $product_id );
		$discount_min = $wps_wgm_discount_data['discount_min'];
		$discount_max = $wps_wgm_discount_data['discount_max'];
		$discount_type = $wps_wgm_discount_data['discount_type'];
		$discount_value = $wps_wgm_discount_data['discount_value'];
		$discount_applicable = false;

		if ( isset( $wps_wgm_discount_data['discount_enable'] ) && 'on' == $wps_wgm_discount_data['discount_enable'] ) {
			if ( isset( $wps_wgm_discount_data['wps_wgm_discount'] ) && 'yes' == $wps_wgm_discount_data['wps_wgm_discount'] ) {
				if ( isset( $discount_min ) && null !== $discount_min && isset( $discount_max ) && null !== $discount_max && isset( $discount_value ) && null !== $discount_value ) {
					foreach ( $discount_min as $key => $value ) {
						if ( $discount_min[ $key ] <= $text_box_price && $text_box_price <= $discount_max[ $key ] ) {
							if ( 'Percentage' == $discount_type ) {
								$new_price_range = $text_box_price - ( $text_box_price * $discount_value[ $key ] ) / 100;
							} else {
								$new_price_range = $text_box_price - $discount_value[ $key ];
							}
							$discount_applicable = true;
						}
					}
				}
			}
		}
		if ( $discount_applicable ) {
			if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
				if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
					$new_price_range = wcpbc_the_zone()->get_exchange_rate_price( $new_price_range );
					$text_box_price = wcpbc_the_zone()->get_exchange_rate_price( $text_box_price );
				}
				?>
				<div class="wps_wgm_price_content">
					<b style="color:green;">
					<?php
					esc_html_e( 'Discounted Gift Card Price: ', 'giftware' );
					echo wp_kses_post( wc_price( $new_price_range ) );
					?>
					</b><br/>
					<b style="color:green;">
					<?php
					esc_html_e( 'Giftcard Value: ', 'giftware' );
					echo wp_kses_post( wc_price( $text_box_price ) );
					?>
					</b>
				</div>
				<?php
			} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
				$new_price_range = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $new_price_range );
				$text_box_price  = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $text_box_price );
				?>
				<div class="wps_wgm_price_content">
					<b style="color:green;">
					<?php
					esc_html_e( 'Discounted Gift Card Price: ', 'giftware' );
					echo wp_kses_post( wc_price( $new_price_range ) );
					?>
					</b><br/>
					<b style="color:green;">
					<?php
					esc_html_e( 'Giftcard Value: ', 'giftware' );
					echo wp_kses_post( wc_price( $text_box_price ) );
					?>
					</b>
				</div>
				<?php
			} else {
				?>
				<div class="wps_wgm_price_content">
					<b style="color:green;">
					<?php
					esc_html_e( 'Discounted Gift Card Price: ', 'giftware' );
					echo wp_kses_post( wc_price( $new_price_range ) );
					?>
					</b><br/>
					<b style="color:green;">
					<?php
					esc_html_e( 'Giftcard Value: ', 'giftware' );
					echo wp_kses_post( wc_price( $text_box_price ) );
					?>
					</b>
				</div>
				<?php
			}
		}
	}

	/**
	 * Adds Discount fields for User Pricing Type Gift Card Product
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_user_price_discount()
	 * @param array $product Conatins product data.
	 * @param array $product_pricing Contains product price.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_user_price_discount( $product, $product_pricing ) {
		$product_id = $product->get_id();
		$wps_wgm_discount_data = $this->wps_uwgc_common_discount_function( $product_id );
		$discount_min = $wps_wgm_discount_data['discount_min'];
		$discount_max = $wps_wgm_discount_data['discount_max'];
		$discount_type = $wps_wgm_discount_data['discount_type'];
		$discount_value = $wps_wgm_discount_data['discount_value'];
		$discount_applicable = false;
		$default_price = $product_pricing['default_price'];

		if ( isset( $wps_wgm_discount_data['discount_enable'] ) && 'on' == $wps_wgm_discount_data['discount_enable'] ) {
			if ( isset( $wps_wgm_discount_data['wps_wgm_discount'] ) && 'yes' == $wps_wgm_discount_data['wps_wgm_discount'] ) {
				if ( isset( $discount_min ) && null !== $discount_min && isset( $discount_max ) && null !== $discount_max && isset( $discount_value ) && null !== $discount_value ) {
					foreach ( $discount_min as $key => $value ) {
						if ( $discount_min[ $key ] <= $default_price && $default_price <= $discount_max[ $key ] ) {
							if ( 'Percentage' == $discount_type ) {
								$new_price_user = $default_price - ( $default_price * $discount_value[ $key ] ) / 100;
							} else {
								$new_price_user = $default_price - $discount_value[ $key ];
							}
							$discount_applicable = true;
						}
					}
				}
			}
		}
		if ( $discount_applicable ) {

			if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
				if ( null !== wcpbc_the_zone() && wcpbc_the_zone() ) {
					$new_price_user = wcpbc_the_zone()->get_exchange_rate_price( $new_price_user );
					$default_price = wcpbc_the_zone()->get_exchange_rate_price( $default_price );
				}
				?>
				<div class="wps_wgm_price_content">
					<b style="color:green;">
					<?php
					esc_html_e( 'Discounted Gift Card Price: ', 'giftware' );
					echo wp_kses_post( wc_price( $new_price_user ) );
					?>
					</b><br/>
					<b style="color:green;">
					<?php
					esc_html_e( 'Giftcard Value: ', 'giftware' );
					echo wp_kses_post( wc_price( $default_price ) );
					?>
					</b>
				</div>
				<?php
			} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
				$new_price_user = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $new_price_user );
				$default_price  = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $default_price );
				?>
				<div class="wps_wgm_price_content">
					<b style="color:green;">
					<?php
					esc_html_e( 'Discounted Gift Card Price: ', 'giftware' );
					echo wp_kses_post( wc_price( $new_price_user ) );
					?>
					</b><br/>
					<b style="color:green;">
					<?php
					esc_html_e( 'Giftcard Value: ', 'giftware' );
					echo wp_kses_post( wc_price( $default_price ) );
					?>
					</b>
				</div>
				<?php
			} else {
				?>
				<div class="wps_wgm_price_content">

					<b style="color:green;">
					<?php
					esc_html_e( 'Discounted Gift Card Price: ', 'giftware' );
					echo wp_kses_post( wc_price( $new_price_user ) );
					?>
					</b><br/>
					<b style="color:green;">
					<?php
					esc_html_e( 'Giftcard Value: ', 'giftware' );
					echo wp_kses_post( wc_price( $default_price ) );
					?>
					</b>

				</div>
				<?php
			}
		}

	}
	/**
	 * Add Schedule Date fields for  Gift Card Product
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_select_date_feature()
	 * @param mixed  $wps_additional_section contains additional info.
	 * @param string $product_id Contains product id.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_select_date_feature( $wps_additional_section, $product_id ) {
		$general_settings = get_option( 'wps_wgm_general_settings', array() );
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_additional_section = '';

		$wps_uwgc_schedule_date = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable_selected_date' );

		if ( isset( $wps_uwgc_schedule_date ) && 'on' == $wps_uwgc_schedule_date ) {
			$wps_additional_section .= '<p class="wps_wgm_section select_date">
				<label class="wps_wgc_label">' . __( 'Select Date', 'giftware' ) . '</label>	
				<input type="text"  name="wps_uwgc_send_date" id="wps_uwgc_send_date" class="wps_uwgc_send_date" placeholder="">
				<span class="wps_uwgc_info">' . __( '(Recipient will receive the Gift Card on selected date)', 'giftware' ) . '</span>

			</p>';
		}
		return $wps_additional_section;

	}
	/**
	 * Add Delivery method for  Gift Card Product
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_add_delivery_method()
	 * @param mixed  $wps_additional_section contains additional info.
	 * @param string $product_id Contains product id.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_add_delivery_method( $wps_additional_section, $product_id ) {
		$wps_additional_section = '';
		$delivery_settings = get_option( 'wps_wgm_delivery_settings', array() );
		$other_settings = get_option( 'wps_wgm_other_settings', array() );

		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_uwgc_delivery_method = $wps_public_obj->wps_wgm_get_template_data( $delivery_settings, 'wps_wgm_send_giftcard' );

		if ( isset( $wps_uwgc_delivery_method ) && 'shipping' == $wps_uwgc_delivery_method ) {
			$wps_additional_section .= '
			<div class="wps_wgm_delivery_method">
				<input type="radio" name="wps_wgm_send_giftcard" value="shipping" class="wps_wgm_send_giftcard" checked="checked" id="wps_wgm_send_giftcard_ship">
				<span class="wps_wgm_method">' . __( 'Want To Ship Your Card', 'giftware' ) . '</span>
				<div class="wps_wgm_delivery_via_admin">
					<input type="text"  name="wps_wgm_to_email_ship" id="wps_wgm_to_ship" class="wps_wgm_to_email" placeholder="' . __( 'Enter the Recipient Name', 'giftware' ) . '">
					<span class= "wps_wgm_msg_info">' . __( 'We will ship your card', 'giftware' ) . '</span>
				</div>
			</div>';
		}
		if ( isset( $wps_uwgc_delivery_method ) && 'customer_choose' == $wps_uwgc_delivery_method ) {
			$wps_uwgc_is_overwrite = get_post_meta( $product_id, 'wps_wgm_overwrite', true );
			$wps_wgm_email_to_recipient = get_post_meta( $product_id, 'wps_wgm_email_to_recipient', true );
			$wps_wgm_download = get_post_meta( $product_id, 'wps_wgm_download', true );
			$wps_wgm_shipping = get_post_meta( $product_id, 'wps_wgm_shipping', true );
			if ( isset( $wps_uwgc_is_overwrite ) && 'yes' == $wps_uwgc_is_overwrite ) {
				if ( isset( $wps_wgm_email_to_recipient ) && 'yes' == $wps_wgm_email_to_recipient ) {
					$wps_additional_section .= '
					<div class="wps_wgm_delivery_method">
						<input type="radio" name="wps_wgm_send_giftcard" value="Mail to recipient" class="wps_wgm_send_giftcard" id="wps_wgm_to_email_send" checked="checked" ><span class="wps_wgm_method">' . __( 'Mail To Recipient', 'giftware' ) . '</span>	
						<div class="wps_wgm_delivery_via_email">
							<input type="text"  name="wps_wgm_to_email" id="wps_wgm_to_email" class="wps_wgm_to_email" placeholder="' . __( 'Enter the Recipient Email', 'giftware' ) . '">
							<input type="text"  name="wps_wgm_to_name_optional" id="wps_wgm_to_name_optional" class="wps_wgm_to_email" placeholder="' . __( 'Enter the Recipient Name', 'giftware' ) . '">
							<span class="wps_wgm_msg_info">' . __( 'We will send it to recipient email address.', 'giftware' ) . '</span>
						</div>
					</div>';
				}
				if ( isset( $wps_wgm_download ) && 'yes' == $wps_wgm_download ) {
					$wps_additional_section .= '	
					<div class="wps_wgm_delivery_method">
						<input type="radio" name="wps_wgm_send_giftcard" value="Downloadable" class="wps_wgm_send_giftcard" id="wps_wgm_send_giftcard_download"><span class="wps_wgm_method">' . __( 'You Print & Give To Recipient', 'giftware' ) . '</span>
						<div class="wps_wgm_delivery_via_buyer">
							<input type="text"  name="wps_wgm_to_email_name" id="wps_wgm_to_download" class="wps_wgm_to_email wps_wgm_disable" placeholder="' . __( 'Enter the Recipient Name', 'giftware' ) . '" readonly><span class= "wps_wgm_msg_info">' . __( 'After checking out, you can print your Gift Card', 'giftware' ) . '</span>
						</div>
					</div>';
				}
				if ( isset( $wps_wgm_shipping ) && 'yes' == $wps_wgm_shipping ) {
					$wps_additional_section .= '
					<div class="wps_wgm_delivery_method">
						<input type="radio" name="wps_wgm_send_giftcard" value="shipping" class="wps_wgm_send_giftcard" id="wps_wgm_send_giftcard_ship">
						<span class="wps_wgm_method">' . __( 'Want To Ship Your Card', 'giftware' ) . '</span>
						<div class="wps_wgm_delivery_via_admin">
							<input type="text"  name="wps_wgm_to_email_ship" id="wps_wgm_to_ship" class="wps_wgm_to_email wps_wgm_disable" placeholder="' . __( 'Enter the Recipient Name', 'giftware' ) . '" readonly><span class= "wps_wgm_msg_info">' . __( 'We will ship your card', 'giftware' ) . '</span>
						</div>
					</div>';
				}
				$wps_additional_section = apply_filters( 'wps_wgm_add_overwrite_method', $wps_additional_section, $product_id );
			} else {

				if ( ! isset( $delivery_settings['wps_wgm_email_to_recipient'] ) && ! isset( $delivery_settings['wps_wgm_downloadable'] ) && ! isset( $delivery_settings['wps_wgm_shipping'] ) ) {
					$wps_additional_section .= '
					<div class="wps_wgm_delivery_method">
						<input type="radio" name="wps_wgm_send_giftcard" value="Mail to recipient" class="wps_wgm_send_giftcard" id="wps_wgm_to_email_send" checked="checked" ><span class="wps_wgm_method">' . __( 'Mail To Recipient', 'giftware' ) . '</span>	
						<div class="wps_wgm_delivery_via_email">
							<input type="text"  name="wps_wgm_to_email" id="wps_wgm_to_email" class="wps_wgm_to_email" placeholder="' . __( 'Enter the Recipient Email', 'giftware' ) . '">
							<input type="text"  name="wps_wgm_to_name_optional" id="wps_wgm_to_name_optional" class="wps_wgm_to_email" placeholder="' . __( 'Enter the Recipient Name', 'giftware' ) . '">
							<span class= "wps_wgm_msg_info">' . __( 'We will send it to recipient email address.', 'giftware' ) . '</span>
						</div>
					</div>';
				}

				if ( isset( $delivery_settings['wps_wgm_email_to_recipient'] ) && 'on' == $delivery_settings['wps_wgm_email_to_recipient'] ) {
					$wps_additional_section .= '
					<div class="wps_wgm_delivery_method">
						<input type="radio" name="wps_wgm_send_giftcard" value="Mail to recipient" class="wps_wgm_send_giftcard" id="wps_wgm_to_email_send" checked="checked"><span class="wps_wgm_method">' . __( 'Mail To Recipient', 'giftware' ) . '</span>	
						<div class="wps_wgm_delivery_via_email">
							<input type="text"  name="wps_wgm_to_email" id="wps_wgm_to_email" class="wps_wgm_to_email" placeholder="' . __( 'Enter the Recipient Email', 'giftware' ) . '">
							<input type="text"  name="wps_wgm_to_name_optional" id="wps_wgm_to_name_optional" class="wps_wgm_to_email" placeholder="' . __( 'Enter the Recipient Name', 'giftware' ) . '">
							<span class= "wps_wgm_msg_info">' . __( 'We will send it to recipient email address.', 'giftware' ) . '</span>
						</div>
					</div>';
				}
				if ( isset( $delivery_settings['wps_wgm_downloadable'] ) && 'on' == $delivery_settings['wps_wgm_downloadable'] ) {
					$wps_additional_section .= '
					<div class="wps_wgm_delivery_method">
						<input type="radio" name="wps_wgm_send_giftcard" value="Downloadable" class="wps_wgm_send_giftcard" id="wps_wgm_send_giftcard_download"><span class="wps_wgm_method">' . __( 'You Print & Give To Recipient', 'giftware' ) . '</span>
						<div class="wps_wgm_delivery_via_buyer">
							<input type="text"  name="wps_wgm_to_email_name" id="wps_wgm_to_download" class="wps_wgm_to_email wps_wgm_disable" placeholder="' . __( 'Enter the Recipient Name', 'giftware' ) . '" readonly><span class= "wps_wgm_msg_info">' . __( 'After checking out, you can print your Gift Card', 'giftware' ) . '</span>
						</div>
					</div>';
				}
				if ( isset( $delivery_settings['wps_wgm_shipping'] ) && 'on' == $delivery_settings['wps_wgm_shipping'] ) {
					$wps_additional_section .= '
					<div class="wps_wgm_delivery_method">
						<input type="radio" name="wps_wgm_send_giftcard" value="shipping" class="wps_wgm_send_giftcard" id="wps_wgm_send_giftcard_ship">
						<span class="wps_wgm_method">' . __( 'Want To Ship Your Card', 'giftware' ) . '</span>
						<div class="wps_wgm_delivery_via_admin">
							<input type="text"  name="wps_wgm_to_email_ship" id="wps_wgm_to_ship" class="wps_wgm_to_email wps_wgm_disable" placeholder="' . __( 'Enter the Recipient Name', 'giftware' ) . '" readonly><span class= "wps_wgm_msg_info">' . __( 'We will ship your card', 'giftware' ) . '</span>
						</div>
					</div>';
				}
			}
		}
		$wps_uwgc_browse_image = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_other_setting_browse' );
		if ( isset( $wps_uwgc_browse_image ) && 'on' == $wps_uwgc_browse_image ) {
			$wps_additional_section .= '
			<div class="wps_demo_browse">

				<p class="wps_wgm_section">
					<label class="wps_wgc_label">' . __( 'Upload Image : ', 'giftware' ) . '</label>	
					<input type="file"  name="wps_uwgc_browse_img" id="wps_uwgc_browse_img" class="wps_uwgc_browse_img"><span class="wps_wgm_info">' . __( '(Uploaded Image will replace the product image in template)', 'giftware' ) . '</span>
					<img id="wps_wgm_browse_src">
				</p>
			</div>';
		}

		$single_page_nonce = wp_create_nonce( 'wps_verify_nonce_single_' );
		return apply_filters( 'wps_uwgc_after_browse_section', $wps_additional_section, $product_id );
	}

	/**
	 * Upload Image for Giftcard product
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_upload_featured_image()
	 * @param array $post Contains post.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_upload_featured_image( $post ) {

		if ( isset( $_FILES['file']['type'] ) && ! empty( $_FILES['file']['type'] ) ) {

			$upload_dir_path = wp_upload_dir()['basedir'] . '/wps_browse';

			if ( ! is_dir( $upload_dir_path ) ) {
				wp_mkdir_p( $upload_dir_path );
				chmod( $upload_dir_path, 0775 );
			}

			if ( ( 'image/gif' == $_FILES['file']['type'] )
				|| ( 'image/jpeg' == $_FILES['file']['type'] )
				|| ( 'image/jpg' == $_FILES['file']['type'] )
				|| ( 'image/pjpeg' == $_FILES['file']['type'] )
				|| ( 'image/x-png' == $_FILES['file']['type'] )
				|| ( 'image/png' == $_FILES['file']['type'] ) ) {
				$file_name = isset( $_FILES['file']['name'] ) ? sanitize_text_field( wp_unslash( $_FILES['file']['name'] ) ) : '';
				$file_name = sanitize_file_name( $file_name );
				if ( ! file_exists( wp_upload_dir()['basedir'] . '/wps_browse/' . $file_name ) ) {
						$wps_temp = isset( $_FILES['file']['tmp_name'] ) ? sanitize_text_field( wp_unslash( $_FILES['file']['tmp_name'] ) ) : '';
						move_uploaded_file( $wps_temp, wp_upload_dir()['basedir'] . '/wps_browse/' . $file_name );
				}
				$post['name'] = $file_name;
			}
		}
		return $post;
	}

	/**
	 * Add fields on Preview Template on Giftcard single page
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_preview_template_fields()
	 * @param mixed $args conatins argument.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_preview_template_fields( $args ) {
		if ( isset( $_GET['name'] ) && null !== $_GET['name'] ) {
			$wps_name = isset( $_GET['name'] ) ? sanitize_text_field( wp_unslash( $_GET['name'] ) ) : '';
			$args['browse_image'] = $wps_name;
		}
		if ( isset( $_GET['send_date'] ) && null !== $_GET['send_date'] && 'undefined' !== $_GET['send_date'] ) {
			$send_date = isset( $_GET['send_date'] ) ? sanitize_text_field( wp_unslash( $_GET['send_date'] ) ) : '';
			$args['send_date'] = $send_date;
		}
		return $args;
	}
	/**
	 * Add Default event Images by users for giftcard
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_default_event_html()
	 * @param mixed $giftcard_event_html Contains HTML.
	 * @param array $args Contains argument.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_default_event_html( $giftcard_event_html, $args ) {
		if ( isset( $args['browse_image'] ) && null !== $args['browse_image'] ) {
			$giftcard_event_html = "<img src='" . content_url( 'uploads/wps_browse/' . $args['browse_image'] ) . "' style='width:100%;' />";
		}
		return $giftcard_event_html;
	}

	/**
	 * Add fields on Email template in common functionality.
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_common_functionality_template_args()
	 * @param array $args Contains argument.
	 * @param array $wps_uwgc_common_arr Common arguments.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_common_functionality_template_args( $args, $wps_uwgc_common_arr ) {
		if ( isset( $wps_uwgc_common_arr['gift_img_name'] ) && '' !== $wps_uwgc_common_arr['gift_img_name'] ) {
			$args['browse_image'] = $wps_uwgc_common_arr['gift_img_name'];
		}
		if ( isset( $wps_uwgc_common_arr['to_name'] ) && '' !== $wps_uwgc_common_arr['to_name'] ) {
			$args['to'] = $wps_uwgc_common_arr['to_name'];
		}
		if ( isset( $wps_uwgc_common_arr['choosen_image'] ) && '' !== $wps_uwgc_common_arr['choosen_image'] ) {
			$args['choosen_image'] = $wps_uwgc_common_arr['choosen_image'];
		}
		if ( isset( $wps_uwgc_common_arr['cgc_file_name'] ) && '' !== $wps_uwgc_common_arr['cgc_file_name'] ) {
			$args['cgc_file_name'] = $wps_uwgc_common_arr['cgc_file_name'];
		}
		if ( isset( $wps_uwgc_common_arr['send_date'] ) && '' !== $wps_uwgc_common_arr['send_date'] ) {
			$args['send_date'] = $wps_uwgc_common_arr['send_date'];
		}
		$args = apply_filters( 'wps_uwgc_common_functionality_args', $args, $wps_uwgc_common_arr );
		return $args;
	}

	/**
	 * Add html on preview template for giftcard
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_email_template_html()
	 * @param array $templatehtml Contains HTML.
	 * @param array $args Contains arguments.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_email_template_html( $templatehtml, $args ) {

		// PRODUCTNAME SHORTCODE.
		$product_id = isset( $args['product_id'] ) ? $args['product_id'] : '';
		if ( isset( $product_id ) && ! empty( $product_id ) ) {
			$product = wc_get_product( $product_id );
		}
		$product_title = '';
		$pro_permalink = '';
		$product_format = '';
		$pro_short_desc = '';
		if ( isset( $product ) && ! empty( $product ) ) {
			$product_title  = $product->get_name();
			$pro_permalink  = $product->get_permalink();
			$pro_short_desc = '<br>' . get_the_excerpt( $product_id ) . '</br>';

			$product_format = "<a href='$pro_permalink'>$product_title</a>";
		}
		$order_id = isset( $args['order_id'] ) ? $args['order_id'] : '';

		$couponurl = wc_get_cart_url() . '?wps_giftcard_code=' . $args['coupon'];

		$applytocart = __( 'Apply This Coupon to Cart', 'giftware' );
		$couponlink  = "<a href='$couponurl' target='blank'>$applytocart</a>";

		$schedule_date = isset( $args['send_date'] ) ? $args['send_date'] : '';

		$templatehtml = str_replace( '[PRODUCTNAME]', $product_format, $templatehtml );
		$templatehtml = str_replace( '[PRODUCT]', $product_title, $templatehtml );
		$templatehtml = str_replace( '[ORDERID]', $order_id, $templatehtml );
		$templatehtml = str_replace( '[SHORTDESCRIPTION]', $pro_short_desc, $templatehtml );
		$templatehtml = str_replace( '[COUPONURL]', $couponlink, $templatehtml );
		$templatehtml = str_replace( '[SCHEDULEDATE]', $schedule_date, $templatehtml );
		return $templatehtml;
	}

	/**
	 * Adds the meta data into the Cart Item
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_add_cart_item_data()
	 * @param array $item_meta Contains item meta.
	 * @param array $the_cart_data Contains cart data.
	 * @param int   $product_id Contains product id.
	 * @param int   $variation_id Contains product id.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_add_cart_item_data( $item_meta, $the_cart_data, $product_id, $variation_id ) {

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		check_admin_referer( 'wps_wgm_single_nonce', 'wps_wgm_single_nonce_field' );
		$is_customizable = get_post_meta( $product_id, 'woocommerce_customizable_giftware', true );
		if ( isset( $_POST['wps_wgm_to_email_ship'] ) && ! empty( $_POST['wps_wgm_to_email_ship'] ) ) {
			$temp_meta['wps_wgm_to_email'] = sanitize_text_field( wp_unslash( $_POST['wps_wgm_to_email_ship'] ) );
			$item_meta = array_merge( $temp_meta, $item_meta );
		}
		if ( isset( $_POST['wps_wgm_selected_temp'] ) && empty( $is_customizable ) ) {
			$item_meta['wps_wgm_selected_temp'] = sanitize_text_field( wp_unslash( $_POST['wps_wgm_selected_temp'] ) );
		}
		if ( isset( $_FILES['wps_uwgc_browse_img']['type'] ) && ! empty( $_FILES['wps_uwgc_browse_img']['type'] ) ) {
			$upload_dir_path = wp_upload_dir()['basedir'] . '/wps_browse';
			if ( ! is_dir( $upload_dir_path ) ) {
				wp_mkdir_p( $upload_dir_path );
				chmod( $upload_dir_path, 0775 );
			}
			if ( ( 'image/gif' == $_FILES['wps_uwgc_browse_img']['type'] )
				|| ( 'image/jpeg' == $_FILES['wps_uwgc_browse_img']['type'] )
				|| ( 'image/jpg' == $_FILES['wps_uwgc_browse_img']['type'] )
				|| ( 'image/pjpeg' == $_FILES['wps_uwgc_browse_img']['type'] )
				|| ( 'image/x-png' == $_FILES['wps_uwgc_browse_img']['type'] )
				|| ( 'image/png' == $_FILES['wps_uwgc_browse_img']['type'] ) ) {

				$file_name = isset( $_FILES['wps_uwgc_browse_img']['name'] ) ? sanitize_text_field( wp_unslash( $_FILES['wps_uwgc_browse_img']['name'] ) ) : '';
				if ( ! file_exists( wp_upload_dir()['basedir'] . '/wps_browse/' . $file_name ) ) {
					$wps_temp = isset( $_FILES['wps_uwgc_browse_img']['tmp_name'] ) ? sanitize_text_field( wp_unslash( $_FILES['wps_uwgc_browse_img']['tmp_name'] ) ) : '';
					move_uploaded_file( $wps_temp, wp_upload_dir()['basedir'] . '/wps_browse/' . $file_name );
				}
				$item_meta['wps_uwgc_browse_img'] = $file_name;
			}
		}
		if ( isset( $_POST['wps_uwgc_send_date'] ) && ! empty( $_POST['wps_uwgc_send_date'] ) ) {
			$item_meta['wps_uwgc_send_date'] = isset( $_POST['wps_uwgc_send_date'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_uwgc_send_date'] ) ) : '';
		}
		if ( isset( $_POST['wps_wgm_to_name_optional'] ) && ! empty( $_POST['wps_wgm_to_name_optional'] ) ) {
			$item_meta['wps_wgm_to_name_optional'] = isset( $_POST['wps_wgm_to_name_optional'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_to_name_optional'] ) ) : '';
		}
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$notification_settings = get_option( 'wps_wgm_notification_settings', array() );
		$username = $wps_public_obj->wps_wgm_get_template_data( $notification_settings, 'wps_wgm_account_sid' );
		$password = $wps_public_obj->wps_wgm_get_template_data( $notification_settings, 'wps_wgm_auth_token' );
		if ( isset( $username ) && '' !== $username && isset( $password ) && '' !== $password ) {
			if ( isset( $_POST['wps_whatsapp_contact'] ) && ! empty( $_POST['wps_whatsapp_contact'] ) ) {
				$item_meta['wps_whatsapp_contact'] = sanitize_text_field( wp_unslash( $_POST['wps_whatsapp_contact'] ) );
			}
		}
		if ( isset( $_POST['wps_gift_this_product'] ) ) {
			$item_meta['sell_as_a_gc'] = sanitize_text_field( wp_unslash( $_POST['wps_gift_this_product'] ) );
		}
		$item_meta = apply_filters( 'wps_uwgc_item_meta_data', $item_meta, $product_id );
		return $item_meta;
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}

	/**
	 * This function is used to append the prices through ajax request
	 *
	 * @name wps_uwgc_append_prices
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_append_prices() {
		check_ajax_referer( 'wps-uwgc-verify-nonce', 'wps_uwgc_nonce' );
		$response['result'] = false;
		$new_price = '';
		$discount_applicable = false;
		$product_id = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
		if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
			if ( null !== wcpbc_the_zone() && wcpbc_the_zone() ) {
				$wps_uwgc_range_price = isset( $_POST['wps_uwgc_price'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_uwgc_price'] ) ) : '';
				if ( isset( $wps_uwgc_range_price ) && ! empty( $wps_uwgc_range_price ) ) {
					$exchange_rate = wcpbc_the_zone()->get_exchange_rate();
					$wps_uwgc_range_price = floatval( $wps_uwgc_range_price / $exchange_rate );
				}
			}
		} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_to_base_currency' ) ) {
			$wps_uwgc_range_price = isset( $_POST['wps_uwgc_price'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_uwgc_price'] ) ) : '';
			if ( isset( $wps_uwgc_range_price ) && ! empty( $wps_uwgc_range_price ) ) {
				$wps_uwgc_range_price = wps_mmcsfw_admin_fetch_currency_rates_to_base_currency( '', $wps_uwgc_range_price );
			}
		} else {
			$wps_uwgc_range_price = isset( $_POST['wps_uwgc_price'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_uwgc_price'] ) ) : '';
		}

		$wps_wgm_discount_data = $this->wps_uwgc_common_discount_function( $product_id );

		$discount_min = $wps_wgm_discount_data['discount_min'];
		$discount_max = $wps_wgm_discount_data['discount_max'];
		$discount_type = $wps_wgm_discount_data['discount_type'];
		$discount_value = $wps_wgm_discount_data['discount_value'];

		if ( isset( $wps_uwgc_range_price ) && ! empty( $wps_uwgc_range_price ) ) {
			if ( isset( $discount_min ) && null !== $discount_min && isset( $discount_max ) && null !== $discount_max && isset( $discount_value ) && null !== $discount_value ) {
				foreach ( $discount_min as $key => $value ) {
					if ( $discount_min[ $key ] <= $wps_uwgc_range_price && $wps_uwgc_range_price <= $discount_max[ $key ] ) {
						if ( 'Percentage' == $discount_type ) {
							$new_price = $wps_uwgc_range_price - ( $wps_uwgc_range_price * $discount_value[ $key ] ) / 100;
						} else {
							$new_price = $wps_uwgc_range_price - $discount_value[ $key ];
						}
						$discount_applicable = true;
					}
				}
			}
			if ( $discount_applicable ) {
				if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
					if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
						$new_price = wcpbc_the_zone()->get_exchange_rate_price( $new_price );
						$wps_uwgc_range_price = wcpbc_the_zone()->get_exchange_rate_price( $wps_uwgc_range_price );
					}
					$response['result'] = true;
					$response['new_price'] = wc_price( $new_price );
					$response['wps_uwgc_price'] = wc_price( $wps_uwgc_range_price );
					echo json_encode( $response );
				} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
					$new_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $new_price );
					$wps_uwgc_range_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $wps_uwgc_range_price );
					$response['result'] = true;
					$response['new_price'] = wc_price( $new_price );
					$response['wps_uwgc_price'] = wc_price( $wps_uwgc_range_price );
					echo json_encode( $response );
				} else {
					$response['result'] = true;
					$response['new_price'] = wc_price( $new_price );
					$response['wps_uwgc_price'] = wc_price( $wps_uwgc_range_price );
					echo json_encode( $response );
				}
			} else {
				$response['result'] = false;
				echo json_encode( $response );
			}
			wp_die();
		}
	}

	/**
	 * This function is used to add metadata with item
	 *
	 * @name wps_uwgc_get_item_meta
	 * @param array $item_meta Item meta.
	 * @param mixed $key Key.
	 * @param mixed $val value.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_get_item_meta( $item_meta, $key, $val ) {

		if ( 'wps_uwgc_send_date' == $key ) {
			$item_meta [] = array(
				'name' => __( 'Send Date', 'giftware' ),
				'value' => stripslashes( $val ),
			);
		}
		if ( 'wps_whatsapp_contact' == $key ) {
			$item_meta [] = array(
				'name' => __( 'Reciever Contact', 'giftware' ),
				'value' => stripslashes( $val ),
			);
		}
	
		$item_meta = apply_filters( 'wps_uwgc_product_item_meta', $item_meta, $key, $val );
		return $item_meta;
	}

	/**
	 * This function is used to add metadata on checkout.
	 *
	 * @name wps_uwgc_checkout_create_order_line_item
	 * @param array  $item Item.
	 * @param string $key key.
	 * @param array  $order_val Order value.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_checkout_create_order_line_item( $item, $key, $order_val ) {
		if ( 'sell_as_a_gc' == $key ) {
			$item->add_meta_data( 'Purchase as a Gift', $order_val );
		}
		if ( 'wps_wgm_to_name_optional' == $key ) {
			$item->add_meta_data( 'To Name', $order_val );
		}
		if ( 'wps_uwgc_send_date' == $key ) {
			$item->add_meta_data( 'Send Date', $order_val );
		}
		if ( 'wps_uwgc_browse_img' == $key ) {
			$item->add_meta_data( 'Image', $order_val );
		}
		if ( 'wps_cgc_image' == $key ) {
			$item->add_meta_data( 'Choosen Image', $order_val );
		}
		if ( 'wps_cgc_custom_img' == $key ) {
			$item->add_meta_data( 'File Name', $order_val );
		}
		if ( 'wps_whatsapp_contact' == $key ) {
			$item->add_meta_data( 'Reciever Contact', $order_val );
		}
		do_action( 'wps_uwgc_create_order_line_item', $item, $key, $order_val );
	}

	/**
	 * Add Extra coupon fields on coupon creation
	 *
	 * @name wps_uwgc_add_more_coupon_fields
	 * @param mixed $extra_data Contains extra data.
	 * @param mixed $new_coupon_id Contains Coupon id.
	 * @param mixed $product_id contains product id.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_add_more_coupon_fields( $extra_data, $new_coupon_id, $product_id ) {
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		if ( ( isset( $new_coupon_id ) && isset( $product_id ) ) && ( '' !== $new_coupon_id && '' !== $product_id ) ) {
			$todaydate = date_i18n( 'Y-m-d' );
			$is_imported_product = get_post_meta( $product_id, 'is_imported', true );

			// set the expiry date of imported giftcard.
			if ( isset( $is_imported_product ) && ! empty( $is_imported_product ) && 'yes' == $is_imported_product ) {

				$expiry_date = get_post_meta( $product_id, 'expiry_after_days', true );

				if ( $expiry_date > 0 || 0 === $expiry_date ) {
					$expirydate = date_i18n( 'Y-m-d', strtotime( "$todaydate +$expiry_date day" ) );
				} else {
					$expirydate = '';
				}
				$extra_data['expiry_date'] = $expirydate;
			}

			$product_settings = get_option( 'wps_wgm_product_settings', array() );

			$include_categories = $wps_public_obj->wps_wgm_get_template_data( $product_settings, 'wps_wgm_product_setting_include_category' );

			$wps_wgm_include_per_category = get_post_meta( $product_id, 'wps_wgm_include_per_category', true );

			$include_products = $wps_public_obj->wps_wgm_get_template_data( $product_settings, 'wps_wgm_product_setting_include_product' );
			$include_products = ( is_array( $include_products ) && ! empty( $include_products ) ) ? implode( ',', $include_products ) : '';

			$include_per_products = get_post_meta( $product_id, 'wps_wgm_include_per_product', true );

			$include_per_products = ( is_array( $include_per_products ) && ! empty( $include_per_products ) ) ? implode( ',', $include_per_products ) : '';

			$exclude_per_product_category = get_post_meta( $product_id, 'wps_wgm_exclude_per_category', true );
			$extra_data['exclude_per_product_category'] = $exclude_per_product_category;

			$exclude_per_products = get_post_meta( $product_id, 'wps_wgm_exclude_per_product', true );

			$exclude_per_products = ( is_array( $exclude_per_products ) && ! empty( $exclude_per_products ) ) ? implode( ',', $exclude_per_products ) : '';

			$extra_data['exclude_per_products'] = $exclude_per_products;

			// include products.
			if ( isset( $include_per_products ) && '' !== $include_per_products ) {
				update_post_meta( $new_coupon_id, 'product_ids', $include_per_products );
			} else {
				update_post_meta( $new_coupon_id, 'product_ids', $include_products );
			}
			// include category.
			if ( isset( $wps_wgm_include_per_category ) && is_array( $wps_wgm_include_per_category ) && ! empty( $wps_wgm_include_per_category ) ) {
				update_post_meta( $new_coupon_id, 'product_categories', $wps_wgm_include_per_category );
			} else {
				update_post_meta( $new_coupon_id, 'product_categories', $include_categories );
			}
		}
		return $extra_data;
	}

	/**
	 * Add Extra Aditional feature on pdf settings.
	 *
	 * @name wps_uwgc_add_more_coupon_fields
	 * @param array  $wps_uwgc_common_arr Contains common arguments.
	 * @param string $message contains message.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_add_pdf_settings( $wps_uwgc_common_arr, $message ) {

		if ( isset( $wps_uwgc_common_arr ) && is_array( $wps_uwgc_common_arr ) && ! empty( $wps_uwgc_common_arr ) ) {
			$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();

			$wps_uwgc_other_settings = get_option( 'wps_wgm_other_settings', array() );
			$wps_wgm_delivery_settings = get_option( 'wps_wgm_delivery_settings', array() );
			$general_setting = get_option( 'wps_wgm_general_settings', array() );
			$giftcard_pdf_prefix = $wps_public_obj->wps_wgm_get_template_data( $general_setting, 'wps_wgm_general_setting_pdf_prefix' );

			$wps_uwgc_pdf_enable = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_other_settings, 'wps_wgm_addition_pdf_enable' );

			if ( isset( $wps_uwgc_pdf_enable ) && 'on' == $wps_uwgc_pdf_enable ) {
				$site_name = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '';
				$time = time();
				$this->wps_common_fun->wps_uwgc_attached_pdf( $message, $site_name, $time, $wps_uwgc_common_arr['order_id'], $wps_uwgc_common_arr['gift_couponnumber'] );
				if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
					$attachments = array( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/' . $giftcard_pdf_prefix . $wps_uwgc_common_arr['gift_couponnumber'] . '.pdf' );
				} else {
					$attachments = array( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/giftcard' . $time . $site_name . '.pdf' );
				}
				$wps_uwgc_common_arr['attachments'] = $attachments;

			} else {

				$wps_uwgc_common_arr['attachments'] = array();
			}

			if ( isset( $wps_uwgc_common_arr['delivery_method'] ) && 'shipping' == $wps_uwgc_common_arr['delivery_method'] ) {
				$admin_email = get_option( 'admin_email' );

				$wps_change_admin_email = $wps_public_obj->wps_wgm_get_template_data( $wps_wgm_delivery_settings, 'wps_wgm_change_admin_email_for_shipping' );
				$alternate_email = ! empty( $wps_change_admin_email ) ? $wps_change_admin_email : $admin_email;
				$wps_uwgc_common_arr['to'] = $alternate_email;
			}
			$wps_uwgc_bcc_enable = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_other_settings, 'wps_wgm_addition_bcc_option_enable' );

			if ( isset( $wps_uwgc_bcc_enable ) && 'on' == $wps_uwgc_bcc_enable ) {

				if ( isset( $wps_uwgc_common_arr['order_id'] ) && ! empty( $wps_uwgc_common_arr['order_id'] ) ) {

					$order = wc_get_order( $wps_uwgc_common_arr['order_id'] );
					$woo_ver = WC()->version;
					if ( $woo_ver < '3.0.0' ) {
						$from = $order->billing_email;
					} else {
						$from = $order->get_billing_email();
					}
					$headers[] = 'Bcc:' . $from;
					$wps_uwgc_common_arr['header'] = $headers;

				}
			}
			$disable_buyer_notification = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_other_settings, 'wps_wgm_disable_buyer_notification' );
			if ( isset( $disable_buyer_notification ) && 'on' == $disable_buyer_notification ) {
				$wps_uwgc_common_arr['disable_buyer_notice'] = 'on';
			}

			$wps_uwgc_mail_template_settings = get_option( 'wps_wgm_mail_settings', array() );
			if ( isset( $wps_uwgc_common_arr['delivery_method'] ) && 'Downloadable' == $wps_uwgc_common_arr['delivery_method'] ) {

				$send_subject = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_mail_template_settings, 'wps_wgm_mail_setting_giftcard_subject_downloadable' );

				$wps_uwgc_common_arr['send_subject'] = $send_subject;
			}
			if ( isset( $wps_uwgc_common_arr['delivery_method'] ) && 'shipping' == $wps_uwgc_common_arr['delivery_method'] ) {

				$send_subject = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_mail_template_settings, 'wps_wgm_mail_setting_giftcard_subject_shipping' );

				$order_id = isset( $wps_uwgc_common_arr['order_id'] ) ? $wps_uwgc_common_arr['order_id'] : '';
				$send_subject = str_replace( '[ORDERID]', $order_id, $send_subject );
				$wps_uwgc_common_arr['send_subject'] = $send_subject;
			}

			$receive_subject = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_mail_template_settings, 'wps_wgm_mail_setting_receive_subject' );
			$receive_message = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_mail_template_settings, 'wps_wgm_mail_setting_receive_message' );

			$wps_uwgc_common_arr['receive_subject'] = $receive_subject;
			$wps_uwgc_common_arr['receive_message'] = $receive_message;
		}
		return $wps_uwgc_common_arr;
	}

	/**
	 * Update remaining amount of offline/Imported coupons.
	 *
	 * @name wps_uwgc_offline_giftcard_coupon
	 * @param mixed $coupon_id Coupon id.
	 * @param mixed $item Item data.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_offline_giftcard_coupon( $coupon_id, $item ) {
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_wgm_discount = $item->get_discount();
		$wps_wgm_discount_tax = $item->get_discount_tax();
		$couponpost = get_post( $coupon_id );
		$couponcontent = $couponpost->post_content;
		if ( ( false !== strpos( $couponcontent, 'GIFTCARD ORDER #' ) ) || ( false !== strpos( $couponcontent, 'OFFLINE GIFTCARD ORDER #' ) ) || ( false !== strpos( $couponcontent, 'Imported Offline Coupon' ) || ( false !== strpos( $couponcontent, 'ThankYou ORDER #' ) ) ) ) {
			$amount = get_post_meta( $coupon_id, 'coupon_amount', true );
			$total_discount = $wps_public_obj->wps_wgm_calculate_coupon_discount( $wps_wgm_discount, $wps_wgm_discount_tax );
			if ( $amount < $total_discount ) {
				$remaining_amount = 0;
			} else {
				$remaining_amount = $amount - $total_discount;
				$remaining_amount = round( $remaining_amount, 2 );
			}
			update_post_meta( $coupon_id, 'coupon_amount', $remaining_amount );
			$coupon_id = $this->wps_uwgc_send_mail_to_user( $coupon_id, $remaining_amount );
			$coupon_id = apply_filters( 'wps_uwgc_coupon_after_mail_send', $coupon_id );
		}
	}

	/**
	 * Function to send Remaining amount after giftcard coupon is used.
	 *
	 * @name wps_uwgc_send_mail_to_user
	 * @param mixed  $coupon_id Coupon id.
	 * @param string $remaining_amount Remaining amount.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_send_mail_to_user( $coupon_id, $remaining_amount ) {
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$to = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_mail_to', true );
		$couponpost = get_post( $coupon_id );
		if ( isset( $couponpost ) ) {
			$couponcode = $couponpost->post_title;
		}
		$couponcode = isset( $couponcode ) ? $couponcode : '';

		$wps_wgm_mail_settings = get_option( 'wps_wgm_mail_settings', array() );

		$subject                    = $wps_public_obj->wps_wgm_get_template_data( $wps_wgm_mail_settings, 'wps_wgm_mail_setting_receive_coupon_subject' );
		$message                    = $wps_public_obj->wps_wgm_get_template_data( $wps_wgm_mail_settings, 'wps_wgm_mail_setting_receive_coupon_message' );
		$coupon_amount_notification = $wps_public_obj->wps_wgm_get_template_data( $wps_wgm_mail_settings, 'wps_wgm_mail_setting_disable_coupon_notification_mail' );
		$giftcard_disclaimer        = $wps_public_obj->wps_wgm_get_template_data( $wps_wgm_mail_settings, 'wps_wgm_mail_setting_disclaimer' );

		$bloginfo = get_bloginfo();
		if ( ! isset( $subject ) || empty( $subject ) ) {
			$subject = "$bloginfo:";
			$subject .= __( 'Coupon Amount Notification', 'giftware' );
		}
		if ( empty( $message ) ) {
			ob_start();
			include_once WPS_UWGC_DIRPATH . 'public/partials/wps-uwgc-coupon-template.php';
			$content = ob_get_clean();
			$message = $content;
		}
		if ( isset( $giftcard_disclaimer ) && empty( $giftcard_disclaimer ) ) {
			$giftcard_disclaimer = __( 'Disclaimer Text', 'giftware' );
		}
		$subject = str_replace( '[SITENAME]', $bloginfo, $subject );
		$subject = stripcslashes( $subject );
		$subject = html_entity_decode( $subject, ENT_QUOTES, 'UTF-8' );

		if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
			if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {

				$remaining_amount = wcpbc_the_zone()->get_exchange_rate_price( $remaining_amount );

				$message = str_replace( '[COUPONAMOUNT]', get_woocommerce_currency_symbol() . $remaining_amount, $message );
			} else {
				$message = str_replace( '[COUPONAMOUNT]', get_woocommerce_currency_symbol() . $remaining_amount, $message );
			}
		} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
			$remaining_amount = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $remaining_amount );
			$message = str_replace( '[COUPONAMOUNT]', get_woocommerce_currency_symbol() . $remaining_amount, $message );
		} else {
			$message = str_replace( '[COUPONAMOUNT]', get_woocommerce_currency_symbol() . $remaining_amount, $message );
		}
		$message = str_replace( '[SITENAME]', $bloginfo, $message );
		$message = str_replace( '[DISCLAIMER]', $giftcard_disclaimer, $message );
		$message = str_replace( '[COUPONCODE]', $couponcode, $message );
		$message = stripcslashes( $message );
		$message = html_entity_decode( $message, ENT_QUOTES, 'UTF-8' );

		$other_settings = get_option( 'wps_wgm_other_settings', array() );

		$wps_uwgc_bcc_enable = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_addition_bcc_option_enable' );
		$from = '';

		if ( isset( $wps_uwgc_bcc_enable ) && 'on' == $wps_uwgc_bcc_enable ) {
			$order_id = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon', true );
			if ( isset( $order_id ) && '' !== $order_id ) {
				$order = wc_get_order( $order_id );
				if ( ! empty( $order ) ) {
					$woo_ver = WC()->version;
					if ( $woo_ver < '3.0.0' ) {
						$from = $order->billing_email;
					} else {
						$from = $order->get_billing_email();
					}
				}
			}
			$headers[] = 'Bcc:' . $from;
			if ( empty( $coupon_amount_notification ) ) {
				wc_mail( $to, $subject, $message, $headers );
			}
		} else {
			if ( empty( $coupon_amount_notification ) ) {
				wc_mail( $to, $subject, $message );
			}
		}
		return $coupon_id;
	}

	/**
	 * This function is used to return the remaining coupon amount according to Tax setting you have in your system
	 *
	 * @name wps_calculate_coupon_discount
	 * @param mixed $wps_wgm_discount Discount.
	 * @param mixed $wps_wgm_discount_tax Tax.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_calculate_coupon_discount( $wps_wgm_discount, $wps_wgm_discount_tax ) {
		$price_in_ex_option = get_option( 'woocommerce_prices_include_tax' );
		$tax_display_shop = get_option( 'woocommerce_tax_display_shop', 'excl' );
		$tax_display_cart = get_option( 'woocommerce_tax_display_cart', 'excl' );

		if ( isset( $tax_display_shop ) && isset( $tax_display_cart ) ) {
			if ( 'excl' == $tax_display_cart && 'excl' == $tax_display_shop ) {

				if ( 'yes' == $price_in_ex_option || 'no' == $price_in_ex_option ) {

					return $wps_wgm_discount;
				}
			} elseif ( 'incl' == $tax_display_cart && 'incl' == $tax_display_shop ) {

				if ( 'yes' == $price_in_ex_option || 'no' == $price_in_ex_option ) {

					return $wps_wgm_discount + $wps_wgm_discount_tax;
				}
			} else {
				return $wps_wgm_discount;
			}
		}
	}

	/**
	 * This function is used to send remaining amount of coupon for online giftcard coupon
	 *
	 * @name wps_uwgc_send_mail_remaining_amount
	 * @param mixed $coupon_id coupon id.
	 * @param mixed $remaining_amount Remaining amount.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_send_mail_remaining_amount( $coupon_id, $remaining_amount ) {
		$coupon_id = $this->wps_uwgc_send_mail_to_user( $coupon_id, $remaining_amount );
	}

	/**
	 * Enable the selected payment gateways for giftcard product
	 *
	 * @name wps_uwgc_available_payment_gateways
	 * @param mixed $payment_gateways Payment gateways.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_available_payment_gateways( $payment_gateways ) {
		global $product_type;
		global $woocommerce;
		$wps_uwgc_gift_exist = false;

		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();

		$whole_cart = WC()->cart;
		if ( isset( $whole_cart ) && ! empty( $whole_cart ) ) {
			$wps_uwgc_not_giftcard = false;
			$get_cart = $whole_cart->get_cart();
			foreach ( $get_cart as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				$product_types = wp_get_object_terms( $product_id, 'product_type' );

				if ( isset( $product_types[0] ) ) {
					$product_type = $product_types[0]->slug;

					if ( 'wgm_gift_card' == $product_type ) {
						$wps_uwgc_gift_exist = true;
					} else {
						$wps_uwgc_not_giftcard = true;
					}
				}
			}

			if ( $wps_uwgc_gift_exist ) {
				if ( is_checkout() ) {
					$general_settings = get_option( 'wps_wgm_general_settings', array() );
					$giftcard_payment_gateways = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_payment' );

					if ( ! $wps_uwgc_not_giftcard ) {

						if ( isset( $giftcard_payment_gateways ) && ! empty( $giftcard_payment_gateways ) ) {
							if ( isset( $payment_gateways ) && ! empty( $payment_gateways ) && is_array( $payment_gateways ) ) {
								foreach ( $payment_gateways as $key => $payment_gateway ) {
									if ( ! in_array( $key, $giftcard_payment_gateways ) ) {
										unset( $payment_gateways[ $key ] );
									}
								}
							}
						} else {
							if ( isset( $payment_gateways ) && ! empty( $payment_gateways ) && is_array( $payment_gateways ) ) {
								foreach ( $payment_gateways as $key => $payment_gateway ) {
									unset( $payment_gateways['cod'] );
								}
							}
						}
					} else {
						if ( isset( $giftcard_payment_gateways ) && ! empty( $giftcard_payment_gateways ) ) {
							if ( isset( $payment_gateways ) && ! empty( $payment_gateways ) && is_array( $payment_gateways ) ) {
								foreach ( $payment_gateways as $key => $payment_gateway ) {
									if ( ! in_array( $key, $giftcard_payment_gateways ) && 'cod' == $key ) {
										unset( $payment_gateways['cod'] );
									}
								}
							}
						} else {
							if ( isset( $payment_gateways ) && ! empty( $payment_gateways ) && is_array( $payment_gateways ) ) {
								foreach ( $payment_gateways as $key => $payment_gateway ) {
									unset( $payment_gateways['cod'] );
								}
							}
						}
					}
				}
			}
		}
		return $payment_gateways;
	}

	/**
	 * This function is used to Add qrcode/barcode for coupon code.
	 *
	 * @name wps_uwgc_qrcode_coupon
	 * @param mixed $coupon contains coupons.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_qrcode_coupon( $coupon ) {

		$qrcode_object = new Wps_Uwgc_Qrcode_Barcode();
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();

		$site_name = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '';
		$time_stamp = time();

		$wps_uwgc_qrcode_settings = get_option( 'wps_wgm_qrcode_settings', array() );

		$wps_wgm_qrcode_enable = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_qrcode_settings, 'wps_wgm_qrcode_enable' );
		$qrcode_level = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_qrcode_settings, 'wps_wgm_qrcode_ecc_level' );
		$qrcode_size = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_qrcode_settings, 'wps_wgm_qrcode_size' );
		$qrcode_margin = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_qrcode_settings, 'wps_wgm_qrcode_margin' );

		if ( 'qrcode' == $wps_wgm_qrcode_enable ) {

			$qrcode_level = ( '' !== $qrcode_level ) ? $qrcode_level : 'L';
			$qrcode_size = ( '' !== $qrcode_size ) ? $qrcode_size : 3;
			$qrcode_margin = ( '' !== $qrcode_margin ) ? $qrcode_margin : 4;

			$qrcode_object->getqrcode( $coupon, $qrcode_level, $qrcode_size, $qrcode_margin, $time_stamp, $site_name );

			return '<img class = "wps_wgm_coupon_img" id = "' . $time_stamp . $site_name . '" src="' . WPS_UWGC_UPLOAD_URL . '/qrcode_barcode/wps__' . $time_stamp . $coupon . '.png">';
		} elseif ( 'barcode' == $wps_wgm_qrcode_enable ) {

			$barcode_display = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_qrcode_settings, 'wps_wgm_barcode_display_enable' );
			$barcode_type = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_qrcode_settings, 'wps_wgm_barcode_codetype' );
			$barcode_size = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_qrcode_settings, 'wps_wgm_barcode_size' );

			$barcode_type = ( '' !== $barcode_type ) ? $barcode_type : 'code39';
			$barcode_size = ( '' !== $barcode_size ) ? $barcode_size : '20';

			$qrcode_object->getbarcode( $coupon, $barcode_display, $barcode_type, $barcode_size, $time_stamp, $site_name );
			return '<img class = "wps_wgm_coupon_img" id = "' . $time_stamp . $site_name . '" src="' . WPS_UWGC_UPLOAD_URL . '/qrcode_barcode/wps__' . $time_stamp . $coupon . '.png">';
		} else {
			return $coupon;
		}
	}

	/**
	 * This function is used to Add Preview link on shop page for Giftcard product.
	 *
	 * @name wps_uwgc_preview_link_shop_page
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_preview_link_shop_page() {
		$wps_uwgc_enable = wps_wgm_giftcard_enable();
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_uwgc_other_settings = get_option( 'wps_wgm_other_settings', array() );
		$wps_uwgc_preview_disable = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_other_settings, 'wps_wgm_additional_preview_disable' );

		if ( $wps_uwgc_enable ) {
			global $post;
			$product_id = $post->ID;
			$product_types = wp_get_object_terms( $product_id, 'product_type' );
			if ( isset( $product_types[0] ) ) {
				$product_type = $product_types[0]->slug;
				if ( 'wgm_gift_card' == $product_type ) {
					add_thickbox();

					$is_customizable = get_post_meta( $product_id, 'woocommerce_customizable_giftware', true );

					if ( 'on' !== $wps_uwgc_preview_disable && 'yes' !== $is_customizable ) {
						?>
						<span class="wps_uwgc_price" >
							<a href="<?php echo esc_url( home_url( "?wps_uwgc_preview_email_shop_page=wps_uwgc_preview_email_shop_page&product_id=$product_id" ) ); ?>&TB_iframe=true&width=100&height=200" class="thickbox button"><?php esc_html_e( 'Preview', 'giftware' ); ?></a>	
						</span>
						<?php
					}
				}
			}
		}
	}

	/**
	 * This function is used to Show Email Template on shop Page.
	 *
	 * @name wps_uwgc_preview_email_template_shop_page
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_preview_email_template_shop_page() {
		if ( isset( $_GET['wps_uwgc_preview_email_shop_page'] ) && 'wps_uwgc_preview_email_shop_page' == $_GET['wps_uwgc_preview_email_shop_page'] ) {
			if ( isset( $_GET['product_id'] ) ) {
				$product_id = sanitize_text_field( wp_unslash( $_GET['product_id'] ) );
				$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
				$wps_uwgc_general_settings = get_option( 'wps_wgm_general_settings', array() );
				$todaydate = date_i18n( 'Y-m-d' );
				$expiry_date = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_general_settings, 'wps_wgm_general_setting_giftcard_expiry' );
				$selected_date_format = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_general_settings, 'wps_wgm_general_setting_enable_selected_format' );

				if ( $expiry_date > 0 || 0 === $expiry_date ) {
					$expirydate = date_i18n( 'Y-m-d', strtotime( "$todaydate +$expiry_date day" ) );
					$expirydate_format = date_create( $expirydate );

					if ( isset( $selected_date_format ) && null !== $selected_date_format && '' !== $selected_date_format ) {

						$selected_date_format = $this->wps_common_fun->wps_uwgc_selected_date_format( $selected_date_format );
						$expirydate_format = date_i18n( $selected_date_format, strtotime( "$todaydate +$expiry_date day" ) );
					} else {
						$expirydate_format = date_format( $expirydate_format, 'jS M Y' );
					}
				} else {
					$expirydate_format = __( 'No Expiration', 'giftware' );
				}

				$wps_uwgc_coupon_length = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_general_settings, 'wps_wgm_general_setting_giftcard_coupon_length' );

				if ( '' == $wps_uwgc_coupon_length ) {
					$wps_uwgc_coupon_length = 5;
				}
				$password = '';
				for ( $i = 0;$i < $wps_uwgc_coupon_length;$i++ ) {
					$password .= 'x';
				}
				$giftcard_prefix = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_general_settings, 'wps_wgm_general_setting_giftcard_prefix' );
				$coupon = $giftcard_prefix . $password;

				$wps_wgm_pricing = get_post_meta( $product_id, 'wps_wgm_pricing', true );

				$templateids = isset( $wps_wgm_pricing['template'] ) && ! empty( $wps_wgm_pricing['template'] ) ? $wps_wgm_pricing['template'] : false;

				$preferedid = isset( $wps_wgm_pricing['by_default_tem'] ) ? $wps_wgm_pricing['by_default_tem'] : '';

				$prefered_template_id = '';
				if ( is_array( $templateids ) && ! empty( $preferedid ) ) {
					$prefered_template_id = $preferedid;
				} elseif ( is_array( $templateids ) && ! empty( $templateids ) ) {
					$prefered_template_id = $templateids[0];
				} elseif ( is_array( $templateids ) && empty( $preferedid ) ) {
					$prefered_template_id = $templateids[0];
				} elseif ( ! is_array( $templateids ) && ! empty( $templateids ) ) {
					$prefered_template_id = $templateids;
				}

				$args['from'] = __( 'from@example.com', 'giftware' );
				$args['to'] = __( 'to@example.com', 'giftware' );
				$args['message'] = __( 'Your gift message will appear here which you send to your receiver. ', 'giftware' );
				$args['coupon'] = apply_filters( 'wps_wgm_static_coupon_img', $coupon );
				$args['expirydate'] = $expirydate_format;
				$args['amount'] = wc_price( 100 );
				$args['templateid'] = $prefered_template_id;
				$args['product_id'] = $product_id;
				$args['order_id'] = '';
				$message = $wps_public_obj->wps_wgm_create_gift_template( $args );
				$finalhtml = $message;
				$wps_admin_obj = new Woocommerce_Gift_Cards_Common_Function();
				$allowed_tags = $wps_admin_obj->wps_allowed_html_tags();
				echo wp_kses( $finalhtml, $allowed_tags );
				die;
			}
		}
	}

	/**
	 * This function is used set the selected Date Format for Email template.
	 *
	 * @name wps_uwgc_select_date_format_enable
	 * @param data $date_format date format.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_select_date_format_enable( $date_format ) {

		if ( isset( $date_format ) && ! empty( $date_format ) ) {
			$date_format = $this->wps_common_fun->wps_uwgc_selected_date_format( $date_format );
		}
		return $date_format;
	}

	/**
	 * This function is used to add notification about expiry days after product purchase
	 *
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 * @name wps_uwgc_gift_card_expiry_notice
	 */
	public function wps_uwgc_gift_card_expiry_notice() {

		global $post;
		$woo_ver = WC()->version;
		$product_id = $post->ID;
		$product_types = wp_get_object_terms( $product_id, 'product_type' );

		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_uwgc_other_settings = get_option( 'wps_wgm_other_settings', array() );

		$wps_uwgc_hide_giftcard_exp_notice = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_other_settings, 'wps_wgm_hide_giftcard_notice' );

		if ( isset( $product_types[0] ) ) {
			$product_type = $product_types[0]->slug;

			if ( 'wgm_gift_card' == $product_type && 'on' !== $wps_uwgc_hide_giftcard_exp_notice ) {
				$args = array(
					'posts_per_page'   => -1,
					'orderby'          => 'title',
					'order'            => 'asc',
					'post_type'        => 'shop_coupon',
					'post_status'      => 'publish',
				);
				$args['meta_query'] = array(
					array(
						'key' => 'wps_wgm_imported_coupon',
						'value' => 'yes',
						'compare' => '==',
					),
				);
				$imported_coupons = get_posts( $args );
				$is_imported = get_post_meta( $product_id, 'is_imported', true );
				if ( isset( $is_imported ) && ! empty( $is_imported ) && 'yes' == $is_imported ) {
					$giftcard_expiry = get_post_meta( $product_id, 'expiry_after_days', true );
				} elseif ( ! empty( $imported_coupons ) ) {
					$imported_code = $imported_coupons[0]->post_title;
					$the_coupon = new WC_Coupon( $imported_code );
					if ( $woo_ver < '3.0.0' ) {
						$import_coupon_id = $the_coupon->id;
					} else {
						$import_coupon_id = $the_coupon->get_id();
					}
					$giftcard_expiry = get_post_meta( $import_coupon_id, 'wps_wgm_expiry_date', true );
				} else {
					$wps_uwgc_general_settings = get_option( 'wps_wgm_general_settings', array() );
					$giftcard_expiry = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_general_settings, 'wps_wgm_general_setting_giftcard_expiry' );
				}
				if ( $giftcard_expiry > 0 ) {
					$days = $giftcard_expiry;
					?>
					<div class="wps_uwgc_expiry_notice clear">
						<h4><?php esc_html_e( 'Giftcard Notice', 'giftware' ); ?></h4>
						<?php /* translators: %s: search term */ ?>
						<p><?php echo esc_html( sprintf( __( 'This Gift Card will expire  %s days after purchase.', 'giftware' ), $days ) ); ?></p>
					</div>
					<?php

				} elseif ( 0 === $giftcard_expiry ) {
					$days = 'same';
					?>
					<div class="wps_uwgc_expiry_notice clear">
						<h4><?php esc_html_e( 'Giftcard Notice', 'giftware' ); ?></h4>
						<p>
						<?php
						/* translators: %s: search term */
						echo esc_html( sprintf( __( 'This Gift Card will expire %s days after purchase.', 'giftware' ), $days ) );
						?>
						</p>
					</div>
					<?php
				} else {
					?>
					<div class="wps_uwgc_expiry_notice clear">
						<h4><?php esc_html_e( 'Giftcard Notice', 'giftware' ); ?></h4>
						<p>
						<?php
						/* translators: %s: search term */
						echo esc_html( sprintf( __( 'The Gift Card has no expiration.', 'giftware' ) ) );
						?>
						</p>
					</div>
					<?php
				}
			}

			if ( 'wgm_gift_card' == $product_type ) {
				do_action( 'wps_uwgc_terms_and_condition' );
			}
		}
	}

	/**
	 * Settings for terms and condition.
	 *
	 * @name wps_uwgc_terms_and_condition_content
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_terms_and_condition_content() {
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_uwgc_other_settings = get_option( 'wps_wgm_other_settings', array() );
		$wps_wgm_hide_terms_and_condition = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_other_settings, 'wps_wgm_hide_terms_and_condition' );
		$wps_wgm_terms_condition_content = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_other_settings, 'wps_wgm_terms_condition_content' );
		if ( '' === $wps_wgm_terms_condition_content ) {
			$wps_wgm_terms_condition_content = __( 'Products Cannot be exchanged', 'giftware' );
		}
		if ( '' === $wps_wgm_hide_terms_and_condition ) {
			?>
			<div class="wps_gw_expiry_notice clear">
				<h4><?php esc_html_e( 'Terms And Conditions', 'giftware' ); ?></h4>
				<p><?php echo esc_html( $wps_wgm_terms_condition_content ); ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Compatible with flatsome theme (wmini_cart)
	 *
	 * @name wps_uwgc_return_actual_price
	 * @param mixed $price Contains price.
	 * @param array $cart_item Contains cart item .
	 * @param mixed $cart_item_key Contains cart item key.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_return_actual_price( $price, $cart_item, $cart_item_key ) {
		$product_type = $cart_item['data']->get_type();
		$test = get_option( 'woocommerce_prices_include_tax' );
		if ( 'wgm_gift_card' == $product_type ) {
			$woo_ver = WC()->version;
			if ( $woo_ver >= '4.4.0' ) {
				if ( 'excl' === WC()->cart->get_tax_price_display_mode() ) {
					return wc_price( ( $cart_item['line_subtotal'] ) / $cart_item['quantity'] );
				} else {
					return wc_price( ( $cart_item['line_subtotal'] + $cart_item['line_subtotal_tax'] ) / $cart_item['quantity'] );
				}
			} else {
				if ( 'excl' === WC()->cart->tax_display_cart ) {
					return wc_price( ( $cart_item['line_subtotal'] ) / $cart_item['quantity'] );
				} else {
					return wc_price( ( $cart_item['line_subtotal'] + $cart_item['line_subtotal_tax'] ) / $cart_item['quantity'] );
				}
			}
		} else {
			return $price;
		}
	}

	/**
	 * This function is used to send a Thankyou Gift Coupon to customers when the option is selected "Order Creation"
	 *
	 * @name wps_uwgc_thankyou_coupon_order_creation
	 * @param mixed $order_id Contains order id.
	 * @param array $data Contains data.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_thankyou_coupon_order_creation( $order_id, $data ) {
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$order = wc_get_order( $order_id );
		$order_status  = $order->get_status();
		$wps_uwgc_thankyou_coupon_settings = get_option( 'wps_wgm_thankyou_order_settings', array() );
		$wps_wgm_thankyouorder_enable = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_enable' );
		$wps_wgm_thankyouorder_time = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_time' );
		$thankyou_status = '';
		switch ( $wps_wgm_thankyouorder_time ) {
			case 'wps_wgm_order_processing':
				$thankyou_status = 'processing';
				break;
			case 'wps_wgm_order_completed':
				$thankyou_status = 'completed';
				break;
			default:
				$thankyou_status = 'other';
				break;
		}
		if ( isset( $wps_wgm_thankyouorder_enable ) && ! empty( $wps_wgm_thankyouorder_enable ) && 'on' == $wps_wgm_thankyouorder_enable ) {
			if ( $thankyou_status === $order_status || 'other' === $thankyou_status ) {
				$thanku_coupon = $this->wps_common_fun->wps_uwgc_thankyou_coupon_handle( $order_id, $data );
			}
		}
	}

	/**
	 * This is function is used for hiding quantity field for gift card type product
	 *
	 * @name wps_uwgc_hide_quantity_fields
	 * @param bool  $return Contains return value.
	 * @param mixed $product Contains product.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_hide_quantity_fields( $return, $product ) {
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_uwgc_other_settings = get_option( 'wps_wgm_other_settings', array() );
		$wps_uwgc_disable_quantity_field = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_other_settings, 'wps_wgm_additional_quantity_disable' );
		$product_id  = $product->get_id();
		$is_imported = get_post_meta( $product_id, 'is_imported', true );
		if ( $product->is_type( 'wgm_gift_card' ) && ( 'on' == $wps_uwgc_disable_quantity_field || 'yes' == $is_imported ) ) {
			return true;
		} else {
			return $return;
		}
	}

	/**
	 * This is function is used to Add meta Info on Email Tempalte.
	 *
	 * @name wps_uwgc_common_arr_data
	 * @param array $wps_wgm_common_arr Instance of common class.
	 * @param array $item Array of items.
	 * @param array $order Order data.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_common_arr_data( $wps_wgm_common_arr, $item, $order ) {
		$item_meta_data = $item->get_meta_data();
		if ( isset( $item_meta_data ) && ! empty( $item_meta_data ) && is_array( $item_meta_data ) ) {
			foreach ( $item_meta_data as $key => $value ) {
				if ( isset( $value->key ) && 'To Name' == $value->key && ! empty( $value->value ) ) {
					$wps_wgm_common_arr['to_name'] = $value->value;
				}
				if ( isset( $value->key ) && 'Image' == $value->key && ! empty( $value->value ) ) {
					$wps_wgm_common_arr['gift_img_name'] = $value->value;
				}
				if ( isset( $value->key ) && 'Reciever Contact' == $value->key && ! empty( $value->value ) ) {
					$wps_wgm_common_arr['contact_no'] = $value->value;
				}
				if ( isset( $value->key ) && 'Send Date' == $value->key && ! empty( $value->value ) ) {
					$wps_wgm_common_arr['send_date'] = $value->value;
				}
			}
		}
		$wps_wgm_common_arr = apply_filters( 'wps_uwgc_custmizable_common_arr', $wps_wgm_common_arr, $item, $order );
		return $wps_wgm_common_arr;
	}

	/**
	 * This is function is used to Locate The Custmizable Giftcard template.
	 *
	 * @name wps_uwgc_locate_custmizable_gift_template
	 * @param string $template template of giftcard.
	 * @param string $template_name Template name of giftcard.
	 * @param string $template_path Contains template path.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_locate_custmizable_gift_template( $template, $template_name, $template_path ) {
		$template = $this->wps_custmizable_obj->wps_uwgc_create_custmizable_gift_template( $template, $template_name, $template_path );

		return $template;

	}
	/**
	 * This is function is used to Include The Custmizable Giftcard template.
	 *
	 * @name wps_uwgc_include_custmizable_template
	 * @param string $template Contains template data.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_include_custmizable_template( $template ) {
		$template = $this->wps_custmizable_obj->wps_uwgc_include_custmizable_gift_template( $template );
		return $template;
	}

	/**
	 * This is function is used to Add Delivery Method for Custmizable Giftcard.
	 *
	 * @name wps_uwgc_add_custmizable_giftcard_delivery_methods
	 * @param string $product_id Conatins product id.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_add_custmizable_giftcard_delivery_methods( $product_id ) {
		$this->wps_custmizable_obj->wps_uwgc_custmizable_giftcard_delivery_methods_html( $product_id );
	}

	/**
	 * This is function is used to Add Custom div for Custmizable Giftcard.
	 *
	 * @name wps_uwgc_custmizable_before_main_content
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_custmizable_before_main_content() {
		$this->wps_custmizable_obj->wps_uwgc_custmizable_before_main_content_html();
	}

	/**
	 * This is function is used to Add Email Template for  Custmizable Giftcard.
	 *
	 * @name wps_uwgc_customizable_email_template
	 * @param array $return Contains data to return.
	 * @param array $args Contain arguments.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_customizable_email_template( $return, $args ) {
		if ( isset( $args['product_id'] ) && ! empty( $args['product_id'] ) ) {
			$product_id = $args['product_id'];
			$is_customizable = get_post_meta( $product_id, 'woocommerce_customizable_giftware', true );
			if ( isset( $is_customizable ) && ! empty( $is_customizable ) && 'yes' == $is_customizable ) {
				$message = $this->wps_custmizable_obj->wps_uwgc_customized_giftcard_email_template( $args );
				return $message;
			}
		}
		return $return;
	}

	/**
	 * This is function is used to Upload custom Image for Custmizable Giftcard On Ajax Call.
	 *
	 * @name wps_cgc_upload_own_img
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_cgc_upload_own_img() {
		$this->wps_custmizable_obj->wps_cgc_custmizable_upload_own_img();
	}

	/**
	 * This is function is used to Upload Image for Custmizable Giftcard On Ajax Call.
	 *
	 * @name wps_cgc_admin_uploads_name
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_cgc_admin_uploads_name() {
		$this->wps_custmizable_obj->wps_cgc_custmizable_admin_uploads_name();
	}

	/**
	 * This is function is used to Add Item Meta for Custmizable Giftcard.
	 *
	 * @name wps_cgc_add_item_meta_data
	 * @param array $item_meta Contains item meta.
	 * @param int   $product_id Contains product id.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_cgc_add_item_meta_data( $item_meta, $product_id ) {
		$item_meta = $this->wps_custmizable_obj->wps_cgc_custmizable_item_meta_data( $item_meta, $product_id );
		return $item_meta;
	}

	/**
	 * This is function is used to Add meta Info on Email Template for Custmizable Giftcard.
	 *
	 * @name wps_cgc_custmizable_common_arr
	 * @param object $wps_cgc_common_arr Contains instance.
	 * @param array  $item Contains item.
	 * @param array  $order Contains order.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_cgc_custmizable_common_arr( $wps_cgc_common_arr, $item, $order ) {
		$wps_cgc_common_arr = $this->wps_custmizable_obj->wps_cgc_custmizable_gift_common_arr( $wps_cgc_common_arr, $item, $order );
		return $wps_cgc_common_arr;
	}

	/**
	 * This is function is used to Add meta Info Resend Mail for Custmizable Giftcard.
	 *
	 * @name wps_cgc_resend_mail_arr_update
	 * @param string $args Contains argument.
	 * @param array  $item Contains item.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_cgc_resend_mail_arr_update( $args, $item ) {
		$args = $this->wps_custmizable_obj->wps_cgc_custmizable_resend_mail_arr_update( $args, $item );
		return $args;
	}

	/**
	 * This is function is used to create shortcode to check giftcard balance.
	 *
	 * @name wps_uwgc_add_short_code_giftcard_balance
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_add_short_code_giftcard_balance() {
		add_shortcode( 'wps_check_your_gift_card_balance', array( $this, 'wps_uwgc_gift_card_balance' ) );
	}

	/**
	 * This is function is used to display giftcard remaining balance.
	 *
	 * @name wps_uwgc_gift_card_balance
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_gift_card_balance() {
		$html = '<div class="wps_gift_card_balance_wrapper">';
		$html .= '<p class="gift_card_balance_email"><input type="email" id="gift_card_balance_email" class="wps_gift_balance" placeholder="' . __( 'Enter Recipient Email/Name or Sender Email.', 'giftware' ) . '" required="required"></p>';
		$html .= '<p class="gift_card_code"><input type="text" id="gift_card_code" class="wps_gift_balance" placeholder="' . __( 'Enter Gift Card Code', 'giftware' ) . '" required="required"></p>';
		$html .= '<p class="wps_check_balance"><input type="button" id="wps_check_balance" value="' . __( 'Check Balance', 'giftware' ) . '"><span id="wps_notification"></span></p>';
		$html .= '<div style="display: none;" class="loading-style-bg" id="wps_wgm_loader"><img src="' . WPS_UWGC_URL . 'assets/images/loading.gif"></div></div>';
		return $html;
	}

	/**
	 * This is function is used to check giftcard balance on Ajax Call.
	 *
	 * @name wps_uwgc_check_gift_balance
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_check_gift_balance() {
		check_ajax_referer( 'wps-uwgc-verify-nonce', 'wps_uwgc_nonce' );
		$response['result'] = false;
		$response['message'] = __( 'Balance cannot be checked yet, Please Try again later!', 'giftware' );
		$wps_check_email = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
		$coupon = isset( $_POST['coupon'] ) ? sanitize_text_field( wp_unslash( $_POST['coupon'] ) ) : '';
		if ( isset( $coupon ) && ! empty( $coupon ) && isset( $wps_check_email ) && ! empty( $wps_check_email ) ) {
			$the_coupon = new WC_Coupon( $coupon );
			if ( isset( $the_coupon ) && ! empty( $coupon ) ) {
				$coupon_id = $the_coupon->get_id();
				if ( isset( $coupon_id ) && ! empty( $coupon_id ) && 0 != $coupon_id ) {
					$left_amount = $the_coupon->get_amount();
					$coupon_type = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_unique', true );
					$user_email = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_mail_to', true );
					if ( 'offline' === $coupon_type ) {
						if ( isset( $user_email ) && ! empty( $user_email ) ) {
							if ( $user_email == $wps_check_email ) {
								$html = '<div class="amount_wrapper">' . __( 'Amount Left is: ', 'giftware' ) . wc_price( $left_amount ) . '</div>';
								$response['result'] = true;
								$response['html'] = $html;
								$response['message'] = __( 'Data Match Successfully!!', 'giftware' );
							} else {
								$response['result'] = false;
								$response['message'] = __( 'Recipient Email should be correct!!', 'giftware' );
							}
						}
					} elseif ( 'online' === $coupon_type ) {
						$order_id = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon', true );
						if ( isset( $order_id ) && ! empty( $order_id ) ) {
							$order = wc_get_order( $order_id );
							$sender_email = $order->get_billing_email();
							$user_id = $order->get_user_id();
							$wps_user_name = get_userdata( $user_id );
							$wps_sender_name = $wps_user_name->first_name . ' ' . $wps_user_name->last_name;
							if ( ( isset( $user_email ) && ! empty( $user_email ) ) || ( isset( $sender_email ) && ! empty( $sender_email ) ) ) {
								if ( $user_email == $wps_check_email ) {
									$html = '<div class="amount_wrapper">' . __( 'Amount Left is: ', 'giftware' ) . wc_price( $left_amount ) . '</div>';
									$response['result'] = true;
									$response['html'] = $html;
									$response['message'] = __( 'Data Match Successfully!!', 'giftware' );
								} elseif ( $sender_email == $wps_check_email ) {
									$html = '<div class="amount_wrapper">' . __( 'Amount Left is: ', 'giftware' ) . wc_price( $left_amount ) . '</div>';
									$response['result'] = true;
									$response['html'] = $html;
									$response['message'] = __( 'Data Match Successfully!!', 'giftware' );
								} elseif ( $wps_sender_name == $wps_check_email ) {
									$html = '<div class="amount_wrapper">' . __( 'Amount Left is: ', 'giftware' ) . wc_price( $left_amount ) . '</div>';
									$response['result'] = true;
									$response['html'] = $html;
									$response['message'] = __( 'Data Match Successfully!!', 'giftware' );
								} else {
									$response['result'] = false;
									$response['message'] = __( 'Recipient Email or Sender Email|Name should be correct!!', 'giftware' );
								}
							}
						}
					} else {
						$response['result'] = false;
						$response['message'] = __( 'Balance cannot be checked for this coupon.', 'giftware' );
					}
				} else {
					$response['result'] = false;
					$response['message'] = __( 'Coupon is Invalid!', 'giftware' );
				}
			}
		} else {
			$response['result'] = false;
			$response['message'] = __( 'Fields cannot be empty!', 'giftware' );
		}
		echo json_encode( $response );
		wp_die();

	}

	/**
	 * This is function is used to Add meta data on order status change hooks.
	 *
	 * @name wps_uwgc_mail_templates_settings
	 * @param array $settings_data Contains setting data.
	 * @param array $items Contains item data.
	 * @param int   $order_id Contains order id.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_mail_templates_settings( $settings_data, $items, $order_id ) {

		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_uwgc_saved_data = get_option( 'wps_wgm_general_settings', array() );

		$selected_date = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_saved_data, 'wps_wgm_general_setting_enable_selected_format' );
		$giftcard_pdf_prefix = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_saved_data, 'wps_wgm_general_setting_pdf_prefix' );

		update_post_meta( $order_id, 'wps_wgm_pdf_name_prefix', $giftcard_pdf_prefix );

		$mailsend = $settings_data['mail_send'];
		$gift_date_check = false;
		$order = wc_get_order( $order_id );

		if ( is_array( $items ) && ! empty( $items ) ) {
			foreach ( $items as $item_id => $item ) {
				// Handle extra coupon generation issue.
				$product = $item->get_product();
				$wps_gift_product = get_post_meta( $order_id, 'sell_as_a_gc' . $item_id, true );

				if ( ! $product->is_type( 'wgm_gift_card' ) && 'on' != $wps_gift_product ) {
					continue;
				}
				// Handle scheduling coupon issue.
				if ( $item_id != $settings_data['item_id'] ) {
					continue;
				}
				$item_meta_data = $item->get_meta_data();

				if ( is_array( $item_meta_data ) && ! empty( $item_meta_data ) ) {
					foreach ( $item_meta_data as $key => $value ) {
						if ( isset( $value->key ) && 'To Name' == $value->key && ! empty( $value->value ) ) {
							$mailsend = true;
							$to_name = $value->value;
							$settings_data['to_name'] = $to_name;
						}
						if ( isset( $value->key ) && 'Image' == $value->key && ! empty( $value->value ) ) {
							$mailsend = true;
							$gift_img_name = $value->value;
							$settings_data['gift_img_name'] = $gift_img_name;
						}
						if ( isset( $value->key ) && 'Send Date' == $value->key && ! empty( $value->value ) ) {
							$gift_date_check = true;
							$gift_date = $value->value;
							$settings_data['gift_date'] = $gift_date;
						}
						if ( isset( $value->key ) && 'Reciever Contact' == $value->key && ! empty( $value->value ) ) {
							$mailsend = true;
							$settings_data['contact_no'] = $value->value;
						}
					}

					if ( ! isset( $settings_data['to'] ) && empty( $settings_data['to'] ) ) {

						if ( isset( $settings_data['delivery_method'] ) && 'Mail to recipient' == $settings_data['delivery_method'] ) {
							$to = $order->get_billing_email();
						} else {
							$to = '';
						}

						$settings_data['to'] = $to;
					}
					if ( $gift_date_check ) {
						$itemgiftsend = get_post_meta( $order_id, "$order_id#$item_id#send", true );
						if ( 'send' == $itemgiftsend ) {
							$settings_data['mail_send'] = false;
							continue;
						}
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

						if ( isset( $settings_data['delivery_method'] ) && 'Mail to recipient' == $settings_data['delivery_method'] ) {
							if ( $giftdiff > 0 ) {
								$datecheck = false;
								$settings_data['datecheck'] = $datecheck;
								update_post_meta( $order_id, "$order_id#$item_id#send", 'notsend' );
								continue;
							} else {
								update_post_meta( $order_id, "$order_id#$item_id#send", 'send' );
								$note = __( 'Gift Card Email has been sent.', 'giftware' );
								$order->add_order_note( $note );
								continue;
							}
						} else {
							update_post_meta( $order_id, "$order_id#$item_id#send", 'send' );
							$note = __( 'Gift Card Email has been sent.', 'giftware' );
							$order->add_order_note( $note );
						}
					} else {
						update_post_meta( $order_id, "$order_id#$item_id#giftcard_send", 'send' );
						$note = __( 'Gift Card Email has been sent.', 'giftware' );
						$order->add_order_note( $note );
					}

					$settings_data['mail_send'] = $mailsend;
					// Handle extra coupon generation issue.
					if ( isset( $settings_data['product_id'] ) && ! empty( $settings_data['product_id'] ) ) {
						$product = wc_get_product( $settings_data['product_id'] );
						$wps_gift_product = get_post_meta( $order_id, 'sell_as_a_gc' . $item_id, true );
						if ( ! $product->is_type( 'wgm_gift_card' ) && 'on' != $wps_gift_product ) {
							$settings_data['mail_send'] = false;
						}
					}
				}
				$settings_data = apply_filters( 'wps_wgm_add_additional_meta_data', $settings_data, $item_meta_data, $order_id );
			}
		}
		return $settings_data;
	}

	/**
	 * This is function is used to create imported giftcard coupon on order status change.
	 *
	 * @name wps_uwgc_check_coupon_creation
	 * @param array  $wps_uwgc_temp_arr Contains template array.
	 * @param string $order_id Contain order id.
	 * @param array  $item Contain item data.
	 * @param string $validaity Validaity of coupon.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_check_coupon_creation( $wps_uwgc_temp_arr, $order_id, $item, $validaity ) {

		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();

		if ( isset( $order_id ) ) {
			$order = wc_get_order( $order_id );
			if ( isset( $order ) ) {
				$wps_uwgc_general_settings = get_option( 'wps_wgm_general_settings', array() );
				$giftcard_coupon_length = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_general_settings, 'wps_wgm_general_setting_giftcard_coupon_length' );
				$giftcard_coupon_length = ( '' !== $giftcard_coupon_length ) ? $giftcard_coupon_length : 5;

				$pro_id = isset( $wps_uwgc_temp_arr['product_id'] ) ? $wps_uwgc_temp_arr['product_id'] : '';
				$args = array(
					'posts_per_page'   => -1,
					'orderby'          => 'title',
					'order'            => 'asc',
					'post_type'        => 'shop_coupon',
					'post_status'      => 'publish',
				);
				$args['meta_query'] = array(
					array(
						'key' => 'wps_wgm_imported_coupon',
						'value' => 'yes',
						'compare' => '==',
					),
				);
				$imported_coupons = get_posts( $args );

				$wps_uwgc_common_arr = array();

				if ( isset( $pro_id ) && '' !== $pro_id ) {
					$is_imported_product = get_post_meta( $pro_id, 'is_imported', true );
				} else {
					$is_imported_product = '';
				}
				$inc_tax_status = get_option( 'woocommerce_prices_include_tax', false );
				if ( 'yes' == $inc_tax_status ) {
					$inc_tax_status = true;
				} else {
					$inc_tax_status = false;
				}
				$couponamont = isset( $wps_uwgc_temp_arr['original_price'] ) ? $wps_uwgc_temp_arr['original_price'] : '';

				if ( isset( $is_imported_product ) && ! empty( $is_imported_product ) && 'yes' == $is_imported_product ) {
					if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
						$pro_price = ! empty( get_post_meta( $pro_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $pro_id, 'wps_wgm_pricing', true ) : get_post_meta( $pro_id, 'wps_wgm_pricing_details', true );
						if ( isset( $pro_price ) && is_array( $pro_price ) ) {
							$couponamont = $pro_price['default_price'];
						}
					} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
						$pro_price = ! empty( get_post_meta( $pro_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $pro_id, 'wps_wgm_pricing', true ) : get_post_meta( $pro_id, 'wps_wgm_pricing_details', true );
						if ( isset( $pro_price ) && is_array( $pro_price ) ) {
							$couponamont = $pro_price['default_price'];
						}
					} else {
						$couponamont = $order->get_line_subtotal( $item, $inc_tax_status );
					}
					$gift_couponnumber = get_post_meta( $pro_id, 'coupon_code', true );
					if ( empty( $gift_couponnumber ) && ! isset( $gift_couponnumber ) ) {
						$gift_couponnumber = wps_wgm_coupon_generator( $giftcard_coupon_length );

					}
					if ( $wps_public_obj->wps_wgm_create_gift_coupon( $gift_couponnumber, $couponamont, $order_id, $item['product_id'], $wps_uwgc_temp_arr['to'] ) ) {
						$expiry_date = get_post_meta( $pro_id, 'expiry_after_days', true );
						$expirydate_format = $wps_public_obj->wps_wgm_check_expiry_date( $expiry_date );
						$wps_uwgc_common_arr['order_id'] = $order_id;
						$wps_uwgc_common_arr['product_id'] = $pro_id;
						$wps_uwgc_common_arr['gift_couponnumber'] = $gift_couponnumber;
						$wps_uwgc_common_arr['expirydate_format'] = $expirydate_format;
						$wps_uwgc_common_arr['couponamont'] = $couponamont;

						$wps_uwgc_common_arr['to'] = isset( $wps_uwgc_temp_arr['to'] ) ? $wps_uwgc_temp_arr['to'] : '';
						$wps_uwgc_common_arr['from'] = isset( $wps_uwgc_temp_arr['from'] ) ? $wps_uwgc_temp_arr['from'] : '';
						$wps_uwgc_common_arr['to_name'] = isset( $wps_uwgc_temp_arr['to_name'] ) ? $wps_uwgc_temp_arr['to_name'] : '';
						$wps_uwgc_common_arr['gift_msg'] = isset( $wps_uwgc_temp_arr['gift_msg'] ) ? $wps_uwgc_temp_arr['gift_msg'] : '';
						$wps_uwgc_common_arr['selected_template'] = isset( $wps_uwgc_temp_arr['selected_template'] ) ? $wps_uwgc_temp_arr['selected_template'] : '';
						$wps_uwgc_common_arr['delivery_method'] = isset( $wps_uwgc_temp_arr['delivery_method'] ) ? $wps_uwgc_temp_arr['delivery_method'] : '';
						$wps_uwgc_common_arr['gift_img_name'] = isset( $wps_uwgc_temp_arr['gift_img_name'] ) ? $wps_uwgc_temp_arr['gift_img_name'] : '';
						$wps_uwgc_common_arr['item_id'] = isset( $wps_uwgc_temp_arr['item_id'] ) ? $wps_uwgc_temp_arr['item_id'] : '';
						$wps_uwgc_common_arr['send_date'] = isset( $wps_uwgc_temp_arr['gift_date'] ) ? $wps_uwgc_temp_arr['gift_date'] : '';

						$wps_uwgc_common_arr = apply_filters( 'wps_uwgc_custmizable_common_arr', $wps_uwgc_common_arr, $item, $order );

						if ( $wps_public_obj->wps_wgm_common_functionality( $wps_uwgc_common_arr, $order ) ) {
							update_post_meta( $pro_id, '_stock_status', 'outofstock' );
						}
					}
					$validaity = false;
				} elseif ( ! empty( $imported_coupons ) ) {
					$item_quantity = isset( $wps_uwgc_temp_arr['item_quantity'] ) ? $wps_uwgc_temp_arr['item_quantity'] : '';
					$woo_ver = WC()->version;

					if ( isset( $item_quantity ) && '' !== $item_quantity ) {
						for ( $i = 0; $i < $item_quantity; $i++ ) {
							$imported_code = $imported_coupons[ $i ]->post_title;
							if ( isset( $imported_code ) && ! empty( $imported_code ) ) {
								$the_coupon = new WC_Coupon( $imported_code );
								if ( $woo_ver < '3.0.0' ) {
									$import_coupon_id = $the_coupon->id;
								} else {
									$import_coupon_id = $the_coupon->get_id();
								}
								$expiry_date = get_post_meta( $import_coupon_id, 'wps_wgm_expiry_date', true );
								$expirydate_format = $wps_public_obj->wps_wgm_check_expiry_date( $expiry_date );
								$wps_uwgc_common_arr['order_id'] = $order_id;
								$wps_uwgc_common_arr['product_id'] = $pro_id;
								$wps_uwgc_common_arr['gift_couponnumber'] = $imported_code;
								$wps_uwgc_common_arr['expirydate_format'] = $expirydate_format;
								$wps_uwgc_common_arr['couponamont'] = $couponamont;

								$wps_uwgc_common_arr['to'] = isset( $wps_uwgc_temp_arr['to'] ) ? $wps_uwgc_temp_arr['to'] : '';
								$wps_uwgc_common_arr['from'] = isset( $wps_uwgc_temp_arr['from'] ) ? $wps_uwgc_temp_arr['from'] : '';
								$wps_uwgc_common_arr['to_name'] = isset( $wps_uwgc_temp_arr['to_name'] ) ? $wps_uwgc_temp_arr['to_name'] : '';
								$wps_uwgc_common_arr['gift_msg'] = isset( $wps_uwgc_temp_arr['gift_msg'] ) ? $wps_uwgc_temp_arr['gift_msg'] : '';
								$wps_uwgc_common_arr['selected_template'] = isset( $wps_uwgc_temp_arr['selected_template'] ) ? $wps_uwgc_temp_arr['selected_template'] : '';
								$wps_uwgc_common_arr['delivery_method'] = isset( $wps_uwgc_temp_arr['delivery_method'] ) ? $wps_uwgc_temp_arr['delivery_method'] : '';
								$wps_uwgc_common_arr['gift_img_name'] = isset( $wps_uwgc_temp_arr['gift_img_name'] ) ? $wps_uwgc_temp_arr['gift_img_name'] : '';
								$wps_uwgc_common_arr['item_id'] = isset( $wps_uwgc_temp_arr['item_id'] ) ? $wps_uwgc_temp_arr['item_id'] : '';
								$wps_uwgc_common_arr['send_date'] = isset( $wps_uwgc_temp_arr['gift_date'] ) ? $wps_uwgc_temp_arr['gift_date'] : '';

								$wps_uwgc_common_arr = apply_filters( 'wps_uwgc_custmizable_common_arr', $wps_uwgc_common_arr, $item, $order );

								if ( $wps_public_obj->wps_wgm_common_functionality( $wps_uwgc_common_arr, $order ) ) {
									update_post_meta( $import_coupon_id, 'coupon_amount', $couponamont );
									update_post_meta( $import_coupon_id, 'wps_wgm_coupon_amount', $couponamont );
									update_post_meta( $import_coupon_id, 'wps_wgm_imported_coupon', 'purchased' );
									update_post_meta( $import_coupon_id, 'wps_wgm_giftcard_coupon', $order_id );
									update_post_meta( $import_coupon_id, 'wps_wgm_giftcard_coupon_unique', 'online' );
									update_post_meta( $import_coupon_id, 'wps_wgm_giftcard_coupon_product_id', $pro_id );
									update_post_meta( $import_coupon_id, 'wps_wgm_giftcard_coupon_mail_to', $wps_uwgc_temp_arr['to'] );
									// for price based on country.
									if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
										update_post_meta( $import_coupon_id, 'zone_pricing_type', 'exchange_rate' );
									}

									$woo_ver = WC()->version;
									if ( ! strtotime( $expirydate_format ) ) {

										$expirydate_format = null;
									}
									if ( $woo_ver < '3.6.0' ) {
										update_post_meta( $import_coupon_id, 'expiry_date', $expirydate_format );
									} else {

										$expirydate_format = strtotime( $expirydate_format );
										update_post_meta( $import_coupon_id, 'date_expires', $expirydate_format );
									}
								}
							} elseif ( empty( $imported_code ) ) {

								$random_code = wps_wgm_coupon_generator( $giftcard_coupon_length );
								$expiry_date = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_general_settings, 'wps_wgm_general_setting_giftcard_expiry' );

								if ( $wps_public_obj->wps_wgm_create_gift_coupon( $random_code, $couponamont, $order_id, $item['product_id'], $wps_uwgc_temp_arr['to'] ) ) {

									$expirydate_format = $wps_public_obj->wps_wgm_check_expiry_date( $expiry_date );
									$wps_uwgc_common_arr['order_id'] = $order_id;
									$wps_uwgc_common_arr['product_id'] = $pro_id;
									$wps_uwgc_common_arr['gift_couponnumber'] = $random_code;
									$wps_uwgc_common_arr['expirydate_format'] = $expirydate_format;
									$wps_uwgc_common_arr['couponamont'] = $couponamont;

									$wps_uwgc_common_arr['to'] = isset( $wps_uwgc_temp_arr['to'] ) ? $wps_uwgc_temp_arr['to'] : '';
									$wps_uwgc_common_arr['from'] = isset( $wps_uwgc_temp_arr['from'] ) ? $wps_uwgc_temp_arr['from'] : '';
									$wps_uwgc_common_arr['to_name'] = isset( $wps_uwgc_temp_arr['to_name'] ) ? $wps_uwgc_temp_arr['to_name'] : '';
									$wps_uwgc_common_arr['gift_msg'] = isset( $wps_uwgc_temp_arr['gift_msg'] ) ? $wps_uwgc_temp_arr['gift_msg'] : '';
									$wps_uwgc_common_arr['selected_template'] = isset( $wps_uwgc_temp_arr['selected_template'] ) ? $wps_uwgc_temp_arr['selected_template'] : '';
									$wps_uwgc_common_arr['delivery_method'] = isset( $wps_uwgc_temp_arr['delivery_method'] ) ? $wps_uwgc_temp_arr['delivery_method'] : '';
									$wps_uwgc_common_arr['gift_img_name'] = isset( $wps_uwgc_temp_arr['gift_img_name'] ) ? $wps_uwgc_temp_arr['gift_img_name'] : '';
									$wps_uwgc_common_arr['item_id'] = isset( $wps_uwgc_temp_arr['item_id'] ) ? $wps_uwgc_temp_arr['item_id'] : '';

									$wps_uwgc_common_arr = apply_filters( 'wps_uwgc_custmizable_common_arr', $wps_uwgc_common_arr, $item, $order );
								}
							}
						}
					}
					$validaity = false;
				}
			}
		}
		return $validaity;
	}

	/**
	 * Cron for set giftcard on specific date
	 *
	 * @name wps_uwgc_do_this_hourly
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_do_this_hourly() {

		$woo_ver = WC()->version;
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$general_settings = get_option( 'wps_wgm_general_settings', array() );
		$giftcard_pdf_prefix = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_pdf_prefix' );
		$giftcard_selected_date  = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable_selected_date' );

		$selected_date  = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable_selected_format' );

		$discount_settings = get_option( 'wps_wgm_discount_settings', array() );
		$discount_enable  = $wps_public_obj->wps_wgm_get_template_data( $discount_settings, 'wps_wgm_discount_enable' );
		if ( 'on' == $giftcard_selected_date ) {
			// Fetch all giftcard order which need to be send on specific date.
			$order_statuses = array(
				'wc-processing' => __( 'Processing', 'Order status', 'woocommerce' ),
				'wc-completed'  => __( 'Completed', 'Order status', 'woocommerce' ),
			);

			$shop_orders = new WP_Query(
				array(
					'numberposts' => -1,
					'post_type'   => wc_get_order_types(),
					'post_status' => array_keys( $order_statuses ),
					'meta_query' => array(
						'relation' => 'OR',
						array(
							'key' => 'wps_gw_order_giftcard',
							'value' => 'notsend',
						),
						array(
							'key' => 'wps_wgm_order_giftcard',
							'value' => 'notsend',
						),
					),
				)
			);

			if ( isset( $shop_orders ) && ! empty( $shop_orders ) ) {
				if ( isset( $shop_orders->posts ) && ! empty( $shop_orders->posts ) ) {
					foreach ( $shop_orders->posts as $shop_order ) {
						$order_id = $shop_order->ID;
						$gift_msg = '';
						$original_price = 0;
						$to = '';
						$from = '';
						$gift_order = false;
						$order = wc_get_order( $order_id );
						$datecheck = true;
						$contact_no = '';
						foreach ( $order->get_items() as $item_id => $item ) {
							$mailsend = false;
							$gift_img_name = '';
							$item_quantity = wc_get_order_item_meta( $item_id, '_qty', true );
							$product = $item->get_product();
							$pro_id = '';
							if ( ! empty( $product ) ) {
								$pro_id = $product->get_id();
							}
							$item_meta_data = $item->get_meta_data();
							$gift_date_check = false;
							$gift_date = '';
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
									$gift_date_check = true;
									$gift_date = $value->value;
								}
								if ( isset( $value->key ) && 'Delivery Method' == $value->key && ! empty( $value->value ) ) {
									$mailsend = true;
									$delivery_method = $value->value;
								}
								if ( isset( $value->key ) && 'Original Price' == $value->key && ! empty( $value->value ) ) {
									$mailsend = true;
									$original_price = $value->value;
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
							if ( $gift_date_check ) {
								$itemgiftsend = get_post_meta( $order_id, "$order_id#$item_id#send", true );
								if ( 'send' == $itemgiftsend ) {
									continue;
								}

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
								$todaytime = current_time( 'timestamp' );
								$todaydate = date_i18n( 'Y-m-d', $todaytime );
								$senddatetime = strtotime( "$senddate" );
								$todaytime = strtotime( "$todaydate" );

								$giftdiff = $senddatetime - $todaytime;

								if ( 'Mail to recipient' == $delivery_method ) {
									if ( $giftdiff > 0 ) {
										$datecheck = false;
										update_post_meta( $order_id, "$order_id#$item_id#send", 'notsend' );
										continue;
									} else {
										update_post_meta( $order_id, "$order_id#$item_id#send", 'send' );
										$note = __( 'Gift Card Email has been sent.', 'giftware' );
										$order->add_order_note( $note );
									}
								} else {
									update_post_meta( $order_id, "$order_id#$item_id#send", 'send' );
									$note = __( 'Gift Card Email has been sent.', 'giftware' );
									$order->add_order_note( $note );
								}
							} else {
								update_post_meta( $order_id, "$order_id#$item_id#giftcard_send", 'send' );
								$note = __( 'Gift Card Email has been sent.', 'giftware' );
								$order->add_order_note( $note );
							}
							if ( $mailsend ) {

								$gift_order = true;
								// gift total.
								$inc_tax_status = get_option( 'woocommerce_prices_include_tax', false );
								if ( 'yes' == $inc_tax_status ) {
									$inc_tax_status = true;
								} else {
									$inc_tax_status = false;
								}
								$wps_wgm_discount = get_post_meta( $item['product_id'], 'wps_wgm_discount', false );
								$couponamont = $original_price;
								$args = array(
									'posts_per_page'   => -1,
									'orderby'          => 'title',
									'order'            => 'asc',
									'post_type'        => 'shop_coupon',
									'post_status'      => 'publish',
								);
								$args['meta_query'] = array(
									array(
										'key' => 'wps_wgm_imported_coupon',
										'value' => 'yes',
										'compare' => '==',
									),
								);
								$imported_coupons = get_posts( $args );
								$wps_uwgc_common_arr = array();
								$is_imported_product = get_post_meta( $pro_id, 'is_imported', true );
								$wps_wgm_pricing = ! empty( get_post_meta( $pro_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $pro_id, 'wps_wgm_pricing', true ) : get_post_meta( $pro_id, 'wps_wgm_pricing_details', true );
								$templateid = isset( $wps_wgm_pricing['template'] ) ? $wps_wgm_pricing['template'] : '';
								if ( is_array( $templateid ) && ! empty( $templateid[0] ) ) {
									$temp = $templateid[0];
								} else {
									$temp = $templateid;
								}
								if ( isset( $is_imported_product ) && ! empty( $is_imported_product ) && 'yes' == $is_imported_product ) {
									if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
										$pro_price = ! empty( get_post_meta( $pro_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $pro_id, 'wps_wgm_pricing', true ) : get_post_meta( $pro_id, 'wps_wgm_pricing_details', true );
										if ( isset( $pro_price ) && is_array( $pro_price ) ) {
											$couponamont = $pro_price['default_price'];
										}
									} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
										$pro_price = ! empty( get_post_meta( $pro_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $pro_id, 'wps_wgm_pricing', true ) : get_post_meta( $pro_id, 'wps_wgm_pricing_details', true );
										if ( isset( $pro_price ) && is_array( $pro_price ) ) {
											$couponamont = $pro_price['default_price'];
										}
									} else {
										$couponamont = $order->get_line_subtotal( $item, $inc_tax_status );
									}
									$gift_couponnumber = get_post_meta( $pro_id, 'coupon_code', true );
									if ( empty( $gift_couponnumber ) && ! isset( $gift_couponnumber ) ) {
										$gift_couponnumber = wps_wgm_coupon_generator( $giftcard_coupon_length );

									}
									if ( $wps_public_obj->wps_wgm_create_gift_coupon( $gift_couponnumber, $couponamont, $order_id, $item['product_id'], $to ) ) {
										$todaydate = date_i18n( 'Y-m-d' );
										$expiry_date = get_post_meta( $pro_id, 'expiry_after_days', true );
										$expirydate_format = $wps_public_obj->wps_wgm_check_expiry_date( $expiry_date );
										$wps_uwgc_common_arr['order_id'] = $order_id;
										$wps_uwgc_common_arr['product_id'] = $pro_id;
										$wps_uwgc_common_arr['to'] = $to;
										$wps_uwgc_common_arr['from'] = $from;
										$wps_uwgc_common_arr['to_name'] = $to_name;
										$wps_uwgc_common_arr['gift_couponnumber'] = $gift_couponnumber;
										$wps_uwgc_common_arr['gift_msg'] = $gift_msg;
										$wps_uwgc_common_arr['expirydate_format'] = $expirydate_format;
										$wps_uwgc_common_arr['selected_template'] = ! empty( $selected_template ) ? $selected_template : $temp;
										$wps_uwgc_common_arr['couponamont'] = $couponamont;
										$wps_uwgc_common_arr['delivery_method'] = $delivery_method;
										$wps_uwgc_common_arr['gift_img_name'] = $gift_img_name;
										$wps_uwgc_common_arr['item_id'] = $item_id;
										$wps_uwgc_common_arr['contact_no'] = $contact_no;
										$wps_uwgc_common_arr['send_date'] = $gift_date;

										$wps_uwgc_common_arr = apply_filters( 'wps_uwgc_custmizable_common_arr', $wps_uwgc_common_arr, $item, $order );

										if ( $wps_public_obj->wps_wgm_common_functionality( $wps_uwgc_common_arr, $order ) ) {
											update_post_meta( $pro_id, '_stock_status', 'outofstock' );
										}
									}
								} elseif ( ! empty( $imported_coupons ) ) {
									for ( $i = 0; $i < $item_quantity; $i++ ) {
										$imported_code = $imported_coupons[ $i ]->post_title;
										if ( isset( $imported_code ) && ! empty( $imported_code ) ) {
											$the_coupon = new WC_Coupon( $imported_code );
											if ( $woo_ver < '3.0.0' ) {
												$import_coupon_id = $the_coupon->id;
											} else {
												$import_coupon_id = $the_coupon->get_id();
											}
											$expiry_date = get_post_meta( $import_coupon_id, 'wps_wgm_expiry_date', true );
											$expirydate_format = $wps_public_obj->wps_wgm_check_expiry_date( $expiry_date );
											$wps_uwgc_common_arr['order_id'] = $order_id;
											$wps_uwgc_common_arr['product_id'] = $pro_id;
											$wps_uwgc_common_arr['to'] = $to;
											$wps_uwgc_common_arr['from'] = $from;
											$wps_uwgc_common_arr['to_name'] = $to_name;
											$wps_uwgc_common_arr['gift_couponnumber'] = $imported_code;
											$wps_uwgc_common_arr['gift_msg'] = $gift_msg;
											$wps_uwgc_common_arr['expirydate_format'] = $expirydate_format;
											$wps_uwgc_common_arr['selected_template'] = $selected_template;
											$wps_uwgc_common_arr['couponamont'] = $couponamont;
											$wps_uwgc_common_arr['delivery_method'] = $delivery_method;
											$wps_uwgc_common_arr['gift_img_name'] = $gift_img_name;
											$wps_uwgc_common_arr['item_id'] = $item_id;
											$wps_uwgc_common_arr['contact_no'] = $contact_no;
											$wps_uwgc_common_arr['send_date'] = $gift_date;
											$wps_uwgc_common_arr = apply_filters( 'wps_uwgc_custmizable_common_arr', $wps_uwgc_common_arr, $item, $order );

											if ( $wps_public_obj->wps_wgm_common_functionality( $wps_uwgc_common_arr, $order ) ) {
												update_post_meta( $import_coupon_id, 'coupon_amount', $couponamont );
												update_post_meta( $import_coupon_id, 'wps_wgm_coupon_amount', $couponamont );
												update_post_meta( $import_coupon_id, 'wps_wgm_imported_coupon', 'purchased' );
												update_post_meta( $import_coupon_id, 'wps_wgm_giftcard_coupon', $order_id );
												update_post_meta( $import_coupon_id, 'wps_wgm_giftcard_coupon_unique', 'online' );
												update_post_meta( $import_coupon_id, 'wps_wgm_giftcard_coupon_product_id', $product_id );
												update_post_meta( $import_coupon_id, 'wps_wgm_giftcard_coupon_mail_to', $to );

												$woo_ver = WC()->version;

												if ( ! strtotime( $expirydate_format ) ) {

													$expirydate_format = null;
												}
												if ( $woo_ver < '3.6.0' ) {
													update_post_meta( $import_coupon_id, 'expiry_date', $expirydate_format );
												} else {
													$expirydate_format = strtotime( $expirydate_format );
													update_post_meta( $import_coupon_id, 'date_expires', $expirydate_format );
												}
											}
										} elseif ( empty( $imported_code ) ) {

											$general_settings = get_option( 'wps_wgm_general_settings', array() );
											$giftcard_coupon_length  = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_coupon_length' );
											$giftcard_coupon_length = ( '' !== $giftcard_coupon_length ) ? $giftcard_coupon_length : 5;
											$random_code = wps_wgm_coupon_generator( $giftcard_coupon_length );
											if ( $wps_public_obj->wps_wgm_create_gift_coupon( $random_code, $couponamont, $order_id, $item['product_id'], $to ) ) {
												$todaydate = date_i18n( 'Y-m-d' );
												$expiry_date = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_expiry' );
												$expirydate_format = $wps_public_obj->wps_wgm_check_expiry_date( $expiry_date );
												$wps_uwgc_common_arr['order_id'] = $order_id;
												$wps_uwgc_common_arr['product_id'] = $pro_id;
												$wps_uwgc_common_arr['to'] = $to;
												$wps_uwgc_common_arr['from'] = $from;
												$wps_uwgc_common_arr['to_name'] = $to_name;
												$wps_uwgc_common_arr['gift_couponnumber'] = $random_code;
												$wps_uwgc_common_arr['gift_msg'] = $gift_msg;
												$wps_uwgc_common_arr['expirydate_format'] = $expirydate_format;
												$wps_uwgc_common_arr['selected_template'] = $selected_template;
												$wps_uwgc_common_arr['couponamont'] = $couponamont;
												$wps_uwgc_common_arr['delivery_method'] = $delivery_method;
												$wps_uwgc_common_arr['gift_img_name'] = $gift_img_name;
												$wps_uwgc_common_arr['item_id'] = $item_id;
												$wps_uwgc_common_arr['contact_no'] = $contact_no;
												$wps_uwgc_common_arr['send_date'] = $gift_date;

												$wps_uwgc_common_arr = apply_filters( 'wps_uwgc_custmizable_common_arr', $wps_uwgc_common_arr, $item, $order );
												if ( $wps_public_obj->wps_wgm_common_functionality( $wps_uwgc_common_arr, $order ) ) {
												}
											}
										}
									}
								} else {
									$general_settings = get_option( 'wps_wgm_general_settings', array() );
									$giftcard_coupon_length  = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_coupon_length' );
									$giftcard_coupon_length = ( '' !== $giftcard_coupon_length ) ? $giftcard_coupon_length : 5;

									for ( $i = 1; $i <= $item_quantity; $i++ ) {
										$gift_couponnumber = wps_wgm_coupon_generator( $giftcard_coupon_length );
										if ( $wps_public_obj->wps_wgm_create_gift_coupon( $gift_couponnumber, $couponamont, $order_id, $item['product_id'], $to ) ) {
											$todaydate = date_i18n( 'Y-m-d' );
											$expiry_date = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_expiry' );
											$expirydate_format = $wps_public_obj->wps_wgm_check_expiry_date( $expiry_date );
											$wps_uwgc_common_arr['order_id'] = $order_id;
											$wps_uwgc_common_arr['product_id'] = $pro_id;
											$wps_uwgc_common_arr['to'] = $to;
											$wps_uwgc_common_arr['from'] = $from;
											$wps_uwgc_common_arr['to_name'] = $to_name;
											$wps_uwgc_common_arr['gift_couponnumber'] = $gift_couponnumber;
											$wps_uwgc_common_arr['gift_msg'] = $gift_msg;
											$wps_uwgc_common_arr['expirydate_format'] = $expirydate_format;
											$wps_uwgc_common_arr['selected_template'] = ! empty( $selected_template ) ? $selected_template : $temp;
											$wps_uwgc_common_arr['couponamont'] = $couponamont;
											$wps_uwgc_common_arr['delivery_method'] = $delivery_method;
											$wps_uwgc_common_arr['gift_img_name'] = $gift_img_name;
											$wps_uwgc_common_arr['item_id'] = $item_id;
											$wps_uwgc_common_arr['contact_no'] = $contact_no;
											$wps_uwgc_common_arr['send_date'] = $gift_date;

											$wps_uwgc_common_arr = apply_filters( 'wps_uwgc_custmizable_common_arr', $wps_uwgc_common_arr, $item, $order );
											if ( $wps_public_obj->wps_wgm_common_functionality( $wps_uwgc_common_arr, $order ) ) {
											}
										}
									}
								}
							}
						}
						if ( $gift_order && $datecheck ) {
							update_post_meta( $order_id, 'wps_gw_order_giftcard', 'send' );
							update_post_meta( $order_id, 'wps_wgm_order_giftcard', 'send' );
						}
					}
				}
			}
		}
		global $wpdb;
		$table_name = $wpdb->prefix . 'offline_giftcard';
		$query = "SELECT * FROM $table_name WHERE `mail` != 1";
		$giftresults = $wpdb->get_results( $query, ARRAY_A );
		if ( isset( $giftresults ) && ! empty( $giftresults ) && null !== $giftresults ) {
			foreach ( $giftresults as $key => $value ) {

				if ( isset( $value['schedule'] ) && null !== $value['schedule'] && '' !== $value['schedule'] ) {
					$schedule_date = $value['schedule'];
					if ( is_string( $schedule_date ) ) {
						if ( isset( $selected_date ) && null !== $selected_date && '' !== $selected_date ) {
							if ( 'd/m/Y' == $selected_date ) {
								$gift_date = str_replace( '/', '-', $schedule_date );
							}
						}
						$senddatetime = strtotime( $schedule_date );
					}
				} else {
					$schedule_date = date_i18n( 'Y-m-d' );
					$senddatetime = strtotime( $schedule_date );
				}
				$senddate = date_i18n( 'Y-m-d', $senddatetime );
				$todaytime = time();
				$todaydate = date_i18n( 'Y-m-d', $todaytime );
				$senddatetime = strtotime( "$senddate" );
				$todaytime = strtotime( "$todaydate" );
				$giftdiff = $senddatetime - $todaytime;

				if ( $giftdiff <= 0 ) {

					$couponcreated = $this->wps_common_fun->wps_uwgc_create_offline_gift_coupon( $value['coupon'], $value['amount'], $value['id'], $value['template'], $value['to'] );
					$product_id = $value['template'];
					$wps_wgm_pricing = ! empty( get_post_meta( $product_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $product_id, 'wps_wgm_pricing', true ) : get_post_meta( $product_id, 'wps_wgm_pricing_details', true );
					$templateid = $wps_wgm_pricing['template'];
					if ( is_array( $templateid ) && array_key_exists( 0, $templateid ) ) {
						$temp = $templateid[0];
					} else {
						$temp = $templateid;
					}
					$args['from'] = $value['from'];
					$args['to'] = $value['to'];
					$args['message'] = stripcslashes( $value['message'] );
					$args['coupon'] = apply_filters( 'wps_wgm_qrcode_coupon', $value['coupon'] );
					$to = $args['to'];
					$from = $args['from'];
					$couponcode = $value['coupon'];
					$coupon = new WC_Coupon( $couponcode );
					if ( isset( $coupon->id ) ) {
						$expirydate = $coupon->expiry_date;
						if ( empty( $expirydate ) ) {
							$expirydate_format = __( 'No Expiration', 'giftware' );
						} else {
							$expirydate = date_i18n( 'Y-m-d', $expirydate );
							$expirydate_format = date_create( $expirydate );

							$selected_date  = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable_selected_format' );
							if ( isset( $selected_date ) && null !== $selected_date && '' !== $selected_date ) {
								$selected_date = $this->wps_common_fun->wps_uwgc_selected_date_format( $selected_date );
								$expirydate_format = date_i18n( $selected_date, strtotime( "$todaydate +$expiry_date day" ) );
							} else {
								$expirydate_format = date_format( $expirydate_format, 'jS M Y' );
							}
						}
						$args['expirydate'] = $expirydate_format;
						$args['amount'] = wc_price( $value['amount'] );
						$args['templateid'] = $temp;
						$args['product_id'] = $product_id;
						$args['order_id'] = '';
						$args['send_date'] = $schedule_date;
						$message = $wps_public_obj->wps_wgm_create_gift_template( $args );

						$other_settings = get_option( 'wps_wgm_other_settings', array() );
						$wps_wgm_pdf_enable  = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_addition_pdf_enable' );

						if ( isset( $wps_wgm_pdf_enable ) && 'on' == $wps_wgm_pdf_enable ) {
							$site_name = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '';
							$time = time();
							$this->wps_common_fun->wps_uwgc_attached_pdf( $message, $site_name, $time, '', $couponcode );
							if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
								$attachments = array( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/' . $giftcard_pdf_prefix . $couponcode . '.pdf' );
							} else {
								$attachments = array( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/giftcard' . $time . $site_name . '.pdf' );
							}
						} else {
							$attachments = array();
						}
						$mail_settings = get_option( 'wps_wgm_mail_settings', array() );
						$subject  = $wps_public_obj->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_giftcard_subject' );

						$bloginfo = get_bloginfo();
						if ( empty( $subject ) || ! isset( $subject ) ) {

							$subject = "$bloginfo:";
							$subject .= __( ' Hurry!!! Gift Card is Received', 'giftware' );
						}
						$subject = str_replace( '[SITENAME]', $bloginfo, $subject );
						$subject = str_replace( '[FROM]', $from, $subject );
						$subject = stripcslashes( $subject );
						$subject = html_entity_decode( $subject, ENT_QUOTES, 'UTF-8' );
						// Send mail to Receiver.

						$wps_wugc_bcc_enable  = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_addition_bcc_option_enable' );

						if ( isset( $wps_wugc_bcc_enable ) && 'on' == $wps_wugc_bcc_enable ) {
							$headers[] = 'Bcc:' . $from;
							wc_mail( $to, $subject, $message, $headers, $attachments );
							if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
								unlink( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/' . $giftcard_pdf_prefix . $couponcode . '.pdf' );
							} elseif ( isset( $time ) && isset( $site_name ) && ! empty( $time ) && ! empty( $site_name ) ) {
								unlink( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/giftcard' . $time . $site_name . '.pdf' );
							}
						} else {
							$headers = array( 'Content-Type: text/html; charset=UTF-8' );
							wc_mail( $to, $subject, $message, $headers, $attachments );
							if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
								unlink( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/' . $giftcard_pdf_prefix . $couponcode . '.pdf' );
							} elseif ( isset( $time ) && isset( $site_name ) && ! empty( $time ) && ! empty( $site_name ) ) {
								unlink( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/giftcard' . $time . $site_name . '.pdf' );
							}
						}

						$mail_settings = get_option( 'wps_wgm_mail_settings', array() );
						$subject  = $wps_public_obj->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_receive_subject' );
						$subject = str_replace( '[TO]', $to, $subject );
						$message  = $wps_public_obj->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_receive_message' );
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
						// send acknowledge mail to sender.

						$wps_wgm_disable_buyer_notification  = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_disable_buyer_notification' );
						if ( 'on' !== $wps_wgm_disable_buyer_notification ) {
							wc_mail( $from, $subject, $message );
						}
						$data_to_update = array( 'mail' => 1 );
						$where = array( 'id' => $value['id'] );
						$update_data = $wpdb->update( $table_name, $data_to_update, $where );
					}
				}
			}
		}
	}

		/**
		 * This function is used to delete old images via a scheduler
		 *
		 * @name wps_gw_do_this_delete_img
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link http://www.wpswings.com/
		 */
	public function wps_uwgc_do_this_delete_img() {
		$time = time();
		$files = glob( wp_upload_dir()['basedir'] . '/qrcode_barcode/*.*' );
		if ( isset( $files ) && is_array( $files ) && ! empty( $files ) ) {
			foreach ( $files as $filename ) {
				$file1 = explode( 'wps__', $filename );
				$time = time();
				$timestamp = array();
				$timestamp[] = end( $file1 );
				if ( isset( $timestamp ) && is_array( $timestamp ) && ! empty( $timestamp ) ) {
					foreach ( $timestamp as $key => $val ) {
						if ( end( $file1 ) < $time . '.png' ) {
							unlink( wp_upload_dir()['basedir'] . '/qrcode_barcode/wps__' . $val );
						}
					}
				}
			}
		}

	}

	/**
	 * Send mail forcefully html
	 *
	 * @name wps_send_mail_forcefully
	 * @param int   $item_id Holds item id.
	 * @param array $item Holds item.
	 * @param array $order Holds order.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_send_mail_forcefully_html( $item_id, $item, $order ) {
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$woo_ver = WC()->version;
		$order_status = $order->get_status();
		if ( 'completed' == $order_status || 'processing' == $order_status ) {
			if ( $woo_ver < '3.0.0' ) {
				$_product = $order->get_product_from_item( $item );
				$product_id = $_product->id;
			} else {
				$_product = $item->get_product();
				if ( ! empty( $_product ) ) {
					$product_id = $_product->get_id();
				}
			}
			if ( isset( $product_id ) && ! empty( $product_id ) ) {
				$product_types = wp_get_object_terms( $product_id, 'product_type' );
				$order_id = $order->get_id();
				$wps_gift_product = get_post_meta( $order_id, 'sell_as_a_gc' . $item_id, true );
				if ( isset( $product_types[0] ) || 'on' === $wps_gift_product ) {
					$product_type = isset( $product_types[0] ) ? $product_types[0]->slug : '';
					if ( 'wgm_gift_card' == $product_type || 'on' === $wps_gift_product ) {
							$item_data = $item->get_meta_data();
						foreach ( $item_data as $key => $value ) {
								$value = $value->get_data();
							if ( isset( $value['key'] ) && 'Send Date' == $value['key'] && '' !== $value['value'] ) {
								$itemgiftsend = get_post_meta( $order_id, "$order_id#$item_id#send", true );
								$other_settings = get_option( 'wps_wgm_other_settings', array() );
								$wps_uwgc_sendtoday_disable  = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_additional_sendtoday_disable' );
								if ( 'send' !== $itemgiftsend ) {
									if ( 'on' !== $wps_uwgc_sendtoday_disable ) {
										?>
											<div id="wps_wgm_loader" style="display: none;">
												<img src="<?php echo esc_url( WPS_UWGC_URL ); ?>assets/images/loading.gif">
											</div>
											<p id="wps_uwgc_send_mail_force_notification_<?php echo esc_attr( $item_id ); ?>"></p>
											<div id="wps_send_force_div_<?php echo esc_attr( $item_id ); ?>">
												<input type="button" data-id="<?php echo esc_attr( $order_id ); ?>" data-num = "<?php echo esc_attr( $item_id ); ?>" class="wps_uwgc_send_mail_force" class="button button-primary" value="<?php esc_attr_e( 'Send Today', 'giftware' ); ?>">
											</div>
										<?php
									}
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Send mail forcefully for Schedule Giftcard when click on send Today Button.
	 *
	 * @name wps_uwgc_send_mail_force
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_send_mail_forcefully() {
		check_ajax_referer( 'wps-uwgc-verify-nonce', 'wps_uwgc_nonce' );
		$response['result'] = false;
		$response['message'] = __( 'Mail sending failed due to some issue. Please try again.', 'giftware' );

		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$discount_settings = get_option( 'wps_wgm_discount_settings', array() );
		$discount_enable  = $wps_public_obj->wps_wgm_get_template_data( $discount_settings, 'wps_wgm_discount_enable' );

		$general_settings = get_option( 'wps_wgm_general_settings', array() );
		$giftcard_coupon_length  = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_coupon_length' );
		$giftcard_coupon_length = ( '' !== $giftcard_coupon_length ) ? $giftcard_coupon_length : 5;

		if ( isset( $_POST['order_id'] ) && ! empty( $_POST['order_id'] ) && isset( $_POST['item_id'] ) && ! empty( $_POST['item_id'] ) ) {

			$order_id = sanitize_text_field( wp_unslash( $_POST['order_id'] ) );
			$item_id = sanitize_text_field( wp_unslash( $_POST['item_id'] ) );
			$order = wc_get_order( $order_id );

			foreach ( $order->get_items() as $item_id_arr => $item ) {
				if ( $item_id_arr == $item_id ) {
					$mailsend = false;
					$original_price = 0;
					$woo_ver = WC()->version;
					$gift_img_name = '';
					$from = '';
					$gift_msg = '';
					$to_name = '';
					$contact_no = '';
					$item_quantity = wc_get_order_item_meta( $item_id, '_qty', true );
					$product = $item->get_product();
					$item_meta_data = $item->get_meta_data();
					$pro_id = $product->get_id();
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
						if ( isset( $value->key ) && 'Delivery Method' == $value->key && ! empty( $value->value ) ) {
							$mailsend = true;
							$delivery_method = $value->value;
						}
						if ( isset( $value->key ) && 'Original Price' == $value->key && ! empty( $value->value ) ) {
							$mailsend = true;
							$original_price = $value->value;
						}
						if ( isset( $value->key ) && 'Selected Template' == $value->key && ! empty( $value->value ) ) {
							$mailsend = true;
							$selected_template = $value->value;
						}
						if ( isset( $value->key ) && 'Reciever Contact' == $value->key && ! empty( $value->value ) ) {
							$mailsend = true;
							$contact_no = $value->value;
						}
						if ( isset( $value->key ) && 'Send Date' == $value->key && ! empty( $value->value ) ) {
							$itemgiftsend = get_post_meta( $order_id, "$order_id#$item_id#send", true );
							if ( 'send' == $itemgiftsend ) {
								$response['result'] = false;
								$response['message'] = __( 'Mail already send on the scheduled date.', 'giftware' );
								echo json_encode( $response );
								wp_die();
							}

							$mailsend = true;
							update_post_meta( $order_id, "$order_id#$item_id#send", 'send' );
							$note = __( 'Gift Card Email has been sent.', 'giftware' );
							$order->add_order_note( $note );
						} else {
							update_post_meta( $order_id, "$order_id#$item_id#giftcard_send", 'send' );
						}
					}
					if ( ! isset( $to ) && empty( $to ) ) {
						if ( 'Mail to recipient' == $delivery_method ) {
							$to = $order->get_billing_email();
						} else {
							$to = '';
						}
					}
					if ( $mailsend ) {
						$gift_order = true;
						// gift total.
						$inc_tax_status = get_option( 'woocommerce_prices_include_tax', false );
						if ( 'yes' == $inc_tax_status ) {
							$inc_tax_status = true;
						} else {
							$inc_tax_status = false;
						}
						$couponamont = $original_price;
						$args = array(
							'posts_per_page'   => -1,
							'orderby'          => 'title',
							'order'            => 'asc',
							'post_type'        => 'shop_coupon',
							'post_status'      => 'publish',
						);
						$args['meta_query'] = array(
							array(
								'key' => 'wps_wgm_imported_coupon',
								'value' => 'yes',
								'compare' => '==',
							),
						);
						$imported_coupons = get_posts( $args );
						$wps_uwgc_common_arr = array();
						$is_imported_product = get_post_meta( $pro_id, 'is_imported', true );
						$wps_wgm_pricing = ! empty( get_post_meta( $pro_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $pro_id, 'wps_wgm_pricing', true ) : get_post_meta( $pro_id, 'wps_wgm_pricing_details', true );
						$templateid = $wps_wgm_pricing['template'];
						if ( is_array( $templateid ) && array_key_exists( 0, $templateid ) ) {
							$temp = $templateid[0];
						} else {
							$temp = $templateid;
						}
						if ( isset( $is_imported_product ) && ! empty( $is_imported_product ) && 'yes' == $is_imported_product ) {
							if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
								$pro_price = ! empty( get_post_meta( $pro_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $pro_id, 'wps_wgm_pricing', true ) : get_post_meta( $pro_id, 'wps_wgm_pricing_details', true );
								if ( isset( $pro_price ) && is_array( $pro_price ) ) {
									$couponamont = $pro_price['default_price'];
								}
							} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
								$pro_price = ! empty( get_post_meta( $pro_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $pro_id, 'wps_wgm_pricing', true ) : get_post_meta( $pro_id, 'wps_wgm_pricing_details', true );
								if ( isset( $pro_price ) && is_array( $pro_price ) ) {
									$couponamont = $pro_price['default_price'];
								}
							} else {
								$couponamont = $order->get_line_subtotal( $item, $inc_tax_status );
							}
							$gift_couponnumber = get_post_meta( $pro_id, 'coupon_code', true );
							if ( empty( $gift_couponnumber ) && ! isset( $gift_couponnumber ) ) {
								$gift_couponnumber = wps_wgm_coupon_generator( $giftcard_coupon_length );
							}
							if ( $wps_public_obj->wps_wgm_create_gift_coupon( $gift_couponnumber, $couponamont, $order_id, $item['product_id'], $to ) ) {
								$todaydate = date_i18n( 'Y-m-d' );
								$expiry_date = get_post_meta( $pro_id, 'expiry_after_days', true );
								$expirydate_format = $wps_public_obj->wps_wgm_check_expiry_date( $expiry_date );
								wc_update_order_item_meta( $item_id, 'Send Date', $todaydate );
								$wps_uwgc_common_arr['order_id'] = $order_id;
								$wps_uwgc_common_arr['product_id'] = $pro_id;
								$wps_uwgc_common_arr['to'] = $to;
								$wps_uwgc_common_arr['from'] = $from;
								$wps_uwgc_common_arr['to_name'] = isset( $to_name ) ? $to_name : '';
								$wps_uwgc_common_arr['gift_couponnumber'] = $gift_couponnumber;
								$wps_uwgc_common_arr['gift_msg'] = $gift_msg;
								$wps_uwgc_common_arr['expirydate_format'] = $expirydate_format;
								$wps_uwgc_common_arr['selected_template'] = ! empty( $selected_template ) ? $selected_template : $temp;
								$wps_uwgc_common_arr['couponamont'] = $couponamont;
								$wps_uwgc_common_arr['delivery_method'] = $delivery_method;
								$wps_uwgc_common_arr['gift_img_name'] = $gift_img_name;
								$wps_uwgc_common_arr['item_id'] = $item_id;
								$wps_uwgc_common_arr['contact_no'] = $contact_no;
								$wps_uwgc_common_arr['send_date'] = $todaydate;
								$wps_uwgc_common_arr = apply_filters( 'wps_uwgc_custmizable_common_arr', $wps_uwgc_common_arr, $item, $order );

								if ( $wps_public_obj->wps_wgm_common_functionality( $wps_uwgc_common_arr, $order ) ) {
									update_post_meta( $pro_id, '_stock_status', 'outofstock' );
									$response['result'] = true;
									$response['message'] = __( 'Gift card  is Sent Successfully', 'giftware' );
									echo json_encode( $response );
									wp_die();
								}
							}
						} elseif ( ! empty( $imported_coupons ) ) {

							for ( $i = 0; $i < $item_quantity; $i++ ) {
								$imported_code = $imported_coupons[ $i ]->post_title;
								if ( isset( $imported_code ) && ! empty( $imported_code ) ) {
									$the_coupon = new WC_Coupon( $imported_code );
									if ( $woo_ver < '3.0.0' ) {
										$import_coupon_id = $the_coupon->id;
									} else {
										$import_coupon_id = $the_coupon->get_id();
									}
									$todaydate = date_i18n( 'Y-m-d' );
									$expiry_date = get_post_meta( $import_coupon_id, 'wps_wgm_expiry_date', true );
									$expirydate_format = $wps_public_obj->wps_wgm_check_expiry_date( $expiry_date );
									$wps_uwgc_common_arr['order_id'] = $order_id;
									$wps_uwgc_common_arr['product_id'] = $pro_id;
									$wps_uwgc_common_arr['to'] = $to;
									$wps_uwgc_common_arr['from'] = $from;
									$wps_uwgc_common_arr['to_name'] = isset( $to_name ) ? $to_name : '';
									$wps_uwgc_common_arr['gift_couponnumber'] = $imported_code;
									$wps_uwgc_common_arr['gift_msg'] = $gift_msg;
									$wps_uwgc_common_arr['expirydate_format'] = $expirydate_format;
									$wps_uwgc_common_arr['selected_template'] = $selected_template;
									$wps_uwgc_common_arr['couponamont'] = $couponamont;
									$wps_uwgc_common_arr['delivery_method'] = $delivery_method;
									$wps_uwgc_common_arr['gift_img_name'] = $gift_img_name;
									$wps_uwgc_common_arr['item_id'] = $item_id;
									$wps_uwgc_common_arr['contact_no'] = $contact_no;
									$wps_uwgc_common_arr['send_date'] = $todaydate;

									$wps_uwgc_common_arr = apply_filters( 'wps_uwgc_custmizable_common_arr', $wps_uwgc_common_arr, $item, $order );

									if ( $wps_public_obj->wps_wgm_common_functionality( $wps_uwgc_common_arr, $order ) ) {
										update_post_meta( $import_coupon_id, 'coupon_amount', $couponamont );
										update_post_meta( $import_coupon_id, 'wps_wgm_coupon_amount', $couponamont );
										update_post_meta( $import_coupon_id, 'wps_wgm_imported_coupon', 'purchased' );
										update_post_meta( $import_coupon_id, 'wps_wgm_giftcard_coupon', $order_id );
										update_post_meta( $import_coupon_id, 'wps_wgm_giftcard_coupon_unique', 'online' );
										update_post_meta( $import_coupon_id, 'wps_wgm_giftcard_coupon_product_id', $pro_id );
										update_post_meta( $import_coupon_id, 'wps_wgm_giftcard_coupon_mail_to', $to );
										$woo_ver = WC()->version;

										if ( ! strtotime( $expirydate_format ) ) {
											$expirydate_format = null;
										}
										if ( $woo_ver < '3.6.0' ) {
											update_post_meta( $import_coupon_id, 'expiry_date', $expirydate_format );
										} else {
											$expirydate_format = strtotime( $expirydate_format );
											update_post_meta( $import_coupon_id, 'date_expires', $expirydate_format );
										}
									}
								} elseif ( empty( $imported_code ) ) {
									$random_code = wps_wgm_coupon_generator( $giftcard_coupon_length );
									if ( $wps_public_obj->wps_wgm_create_gift_coupon( $random_code, $couponamont, $order_id, $item['product_id'], $to ) ) {
										$todaydate = date_i18n( 'Y-m-d' );
										$expiry_date  = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_expiry' );
										$expirydate_format = $wps_public_obj->wps_wgm_check_expiry_date( $expiry_date );
										$wps_uwgc_common_arr['order_id'] = $order_id;
										$wps_uwgc_common_arr['product_id'] = $pro_id;
										$wps_uwgc_common_arr['to'] = $to;
										$wps_uwgc_common_arr['from'] = $from;
										$wps_uwgc_common_arr['to_name'] = $to_name;
										$wps_uwgc_common_arr['gift_couponnumber'] = $random_code;
										$wps_uwgc_common_arr['gift_msg'] = $gift_msg;
										$wps_uwgc_common_arr['expirydate_format'] = $expirydate_format;
										$wps_uwgc_common_arr['selected_template'] = $selected_template;
										$wps_uwgc_common_arr['couponamont'] = $couponamont;
										$wps_uwgc_common_arr['delivery_method'] = $delivery_method;
										$wps_uwgc_common_arr['gift_img_name'] = $gift_img_name;
										$wps_uwgc_common_arr['item_id'] = $item_id;
										$wps_uwgc_common_arr['contact_no'] = $contact_no;
										$wps_uwgc_common_arr = apply_filters( 'wps_uwgc_custmizable_common_arr', $wps_uwgc_common_arr, $item, $order );
									}
								}
							}
						} else {

							for ( $i = 1; $i <= $item_quantity; $i++ ) {
								$gift_couponnumber = wps_wgm_coupon_generator( $giftcard_coupon_length );

								if ( $wps_public_obj->wps_wgm_create_gift_coupon( $gift_couponnumber, $couponamont, $order_id, $item['product_id'], $to ) ) {
									$todaydate = date_i18n( 'Y-m-d' );
									$expiry_date  = $wps_public_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_giftcard_expiry' );
									$expirydate_format = $wps_public_obj->wps_wgm_check_expiry_date( $expiry_date );
									$wps_uwgc_common_arr['order_id'] = $order_id;
									$wps_uwgc_common_arr['product_id'] = $pro_id;
									$wps_uwgc_common_arr['to'] = $to;
									$wps_uwgc_common_arr['from'] = $from;
									$wps_uwgc_common_arr['to_name'] = $to_name;
									$wps_uwgc_common_arr['gift_couponnumber'] = $gift_couponnumber;
									$wps_uwgc_common_arr['gift_msg'] = $gift_msg;
									$wps_uwgc_common_arr['expirydate_format'] = $expirydate_format;
									$wps_uwgc_common_arr['selected_template'] = ! empty( $selected_template ) ? $selected_template : $temp;
									$wps_uwgc_common_arr['couponamont'] = $couponamont;
									$wps_uwgc_common_arr['delivery_method'] = $delivery_method;
									$wps_uwgc_common_arr['gift_img_name'] = $gift_img_name;
									$wps_uwgc_common_arr['item_id'] = $item_id;
									$wps_uwgc_common_arr['contact_no'] = $contact_no;
									$wps_uwgc_common_arr['send_date'] = $todaydate;
									$wps_uwgc_common_arr = apply_filters( 'wps_uwgc_custmizable_common_arr', $wps_uwgc_common_arr, $item, $order );

									if ( $wps_public_obj->wps_wgm_common_functionality( $wps_uwgc_common_arr, $order ) ) {
										$response['result'] = true;
										$response['message'] = __( 'Gift card  is Sent Successfully', 'giftware' );
										echo json_encode( $response );
										wp_die();
									}
								}
							}
						}
					}
					break;
				}
			}
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * This function is used to add resend email button at order detail page on front end
	 *
	 * @name wps_uwgc_woocommerce_order_details_after_order_table
	 * @param  array $order Conatins order data.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_resend_mail_view_order_frontend( $order ) {
		$resend_view_gift = false;
		$resend_view_ship = false;
		$resend_view_other_pro = false;
		$resend_view = false;
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$woo_ver = WC()->version;
		if ( $woo_ver < '3.0.0' ) {
			$order_id = $order->id;
			$order_status = $order->status;
		} else {
			$order_id = $order->get_id();
			$order_status = $order->get_status();
		}
		if ( 'completed' == $order_status || 'processing' == $order_status ) {
			$giftcard = false;
			foreach ( $order->get_items() as $item_id => $item ) {
				if ( $woo_ver < '3.0.0' ) {
					$_product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
				} else {
					$_product = apply_filters( 'woocommerce_order_item_product', $product = $item->get_product(), $item );
				}
				if ( isset( $_product ) && ! empty( $_product ) ) {
					$product_id = $_product->get_id();
				}
				if ( isset( $product_id ) && ! empty( $product_id ) ) {

					$product_types = wp_get_object_terms( $product_id, 'product_type' );

					if ( isset( $product_types[0] ) ) {
						$product_type = $product_types[0]->slug;
						$wps_gift_product = get_post_meta( $order_id, 'sell_as_a_gc' . $item_id, true );
						if ( 'wgm_gift_card' == $product_type || 'on' == $wps_gift_product ) {
							$giftcard = true;
						}
					} else {
						$wps_gift_product = get_post_meta( $order_id, 'sell_as_a_gc' . $item_id, true );
						if ( 'on' == $wps_gift_product ) {
							$giftcard = true;
						}
					}
				}
				if ( $woo_ver < '3.0.0' ) {
					$product = $order->get_product_from_item( $item );
					if ( isset( $item['item_meta']['Delivery Method'] ) && ! empty( $item['item_meta']['Delivery Method'] ) ) {
						$delivery_method = $item['item_meta']['Delivery Method'][0];
					}
				} else {
					$product = $item->get_product();
					$item_meta_data = $item->get_meta_data();
					foreach ( $item_meta_data as $key => $value ) {
						if ( isset( $value->key ) && 'Delivery Method' == $value->key && ! empty( $value->value ) ) {
							$delivery_method = $value->value;
						}
					}
				}
				if ( isset( $delivery_method ) ) {
					if ( 'Mail to recipient' == $delivery_method || 'Downloadable' == $delivery_method ) {
						$resend_view_gift = true;
					} else {
						$resend_view_ship = false;
					}
				} else {
					$resend_view_other_pro = false;
				}
				// DOWNLOAD PDF IN THANKYOU ORDER PAGE.
				if ( isset( $delivery_method ) && ! empty( $delivery_method ) && 'Downloadable' == $delivery_method ) {

					$giftcoupon = get_post_meta( $order_id, "$order_id#$item_id", true );
					if ( isset( $giftcoupon ) && ! empty( $giftcoupon ) ) {
						foreach ( $giftcoupon as $key => $value ) {
							$upload_dir_path = WPS_UWGC_UPLOAD_URL . '/giftcard_pdf/giftcard' . $order_id . $value . '.pdf';
							?>
							<a href="<?php echo esc_attr( $upload_dir_path ); ?>" class="wps_download_pdf" target="_blank"><?php esc_html_e( 'Download PDF', 'giftware' ); ?><i class="fas fa-file-download wps_wgm_download_pdf"></i></a><br/>
							<?php
						}
					}
				}
			}
			if ( $resend_view_gift && ! $resend_view_ship && ! $resend_view_other_pro ) {
				$resend_view = true;
			} else {
				$resend_view = false;
			}
			$other_settings = get_option( 'wps_wgm_other_settings', array() );
			$wps_uwgc_resend_disable  = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_additional_resend_disable' );
			if ( $giftcard && $resend_view ) {
				?>
				<style>
				#wps_wgm_loader {
					background-color: rgba(255, 255, 255, 0.6);
					bottom: 0;
					height: 100%;
					left: 0;
					position: fixed;
					right: 0;
					top: 0;
					width: 100%;
					z-index: 99999;
				}

				#wps_wgm_loader img {
					display: block;
					left: 0;
					margin: 0 auto;
					position: absolute;
					right: 0;
					top: 40%;
				}
			</style>
				<?php
				if ( 'on' !== $wps_uwgc_resend_disable ) {
					?>
					<div class="resend_mail_wrapper">
						<span id="wps_uwgc_resend_mail_frontend_notification"></span>
						<h4>
							<strong><?php esc_html_e( 'Resend Giftcard Email', 'giftware' ); ?></strong>
						</h4>
						<div id="wps_wgm_loader" style="display: none;">
							<img src="<?php echo esc_url( WPS_UWGC_URL ); ?>assets/images/loading.gif">
						</div>
						<span class="wps_resend_content"><?php esc_html_e( "Press the icon to resend mail if the receiver hasn't received the mail you sent.", 'giftware' ); ?>
						</span>
						<a href="javascript:void(0);" data-id="<?php echo esc_attr( $order_id ); ?>" class="wps_uwgc_resend_mail" id="wps_uwgc_resend_mail_button_frontend">
							<img src="<?php echo esc_url( WPS_UWGC_URL ); ?>assets/images/send_mail.png" class="wps_resend_image">
						</a>
					</div>
					<?php
				}
			}
		}
	}

	/**
	 * This function is used to  resend email  at order detail page on front end
	 *
	 * @name wps_uwgc_resend_mail_order_deatils_frontend
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_resend_mail_order_deatils_frontend() {
		check_ajax_referer( 'wps-uwgc-verify-nonce', 'wps_uwgc_nonce' );
		$this->wps_common_fun->wps_uwgc_resend_mail_common_function();
	}

	/**
	 * This function is used to create thankyou coupon on order status change.
	 *
	 * @name wps_uwgc_thankyou_coupon_order_status_change
	 * @param int    $order_id Conatins order id.
	 * @param string $old_status Conatins order status.
	 * @param string $new_status Conatins order status.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_thankyou_coupon_order_status_change( $order_id, $old_status, $new_status ) {
		if ( ! wps_wgm_giftcard_enable() ) {
			return;
		}
		if ( $old_status != $new_status ) {
			$order = wc_get_order( $order_id );
			$user_id = $order->get_user_id();
			if ( isset( $user_id ) && ! empty( $user_id ) ) {
				if ( 'completed' == $new_status || 'processing' == $new_status ) {
					$thankyou_user_order = get_user_meta( $user_id, 'thankyou_order_number', true );
					if ( isset( $thankyou_user_order ) && ! empty( $thankyou_user_order ) ) {
						++$thankyou_user_order;
						update_user_meta( $user_id, 'thankyou_order_number', $thankyou_user_order );
					} else {
						update_user_meta( $user_id, 'thankyou_order_number', 1 );
					}
				}
			}

			$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
			$wps_uwgc_thankyou_coupon_settings = get_option( 'wps_wgm_thankyou_order_settings', array() );

			$wps_wgm_thankyouorder_enable = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_enable' );
			$wps_wgm_thankyouorder_time = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_thankyou_coupon_settings, 'wps_wgm_thankyouorder_time' );
			$thankyou_status = '';
			switch ( $wps_wgm_thankyouorder_time ) {
				case 'wps_wgm_order_processing':
					$thankyou_status = 'processing';
					break;
				case 'wps_wgm_order_completed':
					$thankyou_status = 'completed';
					break;
				default:
					$thankyou_status = 'other';
					break;
			}
			if ( isset( $wps_wgm_thankyouorder_enable ) && ! empty( $wps_wgm_thankyouorder_enable ) && 'on' == $wps_wgm_thankyouorder_enable ) {
				if ( $thankyou_status === $new_status || 'other' === $thankyou_status ) {
					$thanku_coupon = $this->wps_common_fun->wps_uwgc_thankyou_coupon_handle( $order_id, $new_status );
				}
			}
		}
	}

	/**
	 * This function is for validating the ajax add to cart request on single product page
	 *
	 * @param bool   $validate Check if valid.
	 * @param int    $product_id Contains product id.
	 * @param string $quantity Contains quantity.
	 * @return boolean
	 * @name wps_uwgc_add_to_cart_validation
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_add_to_cart_validation( $validate, $product_id, $quantity ) {
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_uwgc_other_settings = get_option( 'wps_wgm_other_settings', array() );

		$wps_wgm_remove_validation_to = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_other_settings, 'wps_wgm_remove_validation_to' );
		$wps_wgm_remove_validation_from = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_other_settings, 'wps_wgm_remove_validation_from' );
		$wps_wgm_remove_validation_msg = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_other_settings, 'wps_wgm_remove_validation_msg' );
		$wps_wgm_remove_validation_to_name = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_other_settings, 'wps_wgm_remove_validation_to_name' );

		$wps_uwgc_mail_settings = get_option( 'wps_wgm_mail_settings', array() );
		$giftcard_message_length = $wps_public_obj->wps_wgm_get_template_data( $wps_uwgc_mail_settings, 'wps_wgm_mail_setting_giftcard_message_length' );

		$giftcard_message_length = ( '' !== $giftcard_message_length ) ? $giftcard_message_length : 300;

		$product_types = wp_get_object_terms( $product_id, 'product_type' );
		if ( isset( $product_types[0] ) ) {
			$product_type = $product_types[0]->slug;
			if ( 'wgm_gift_card' == $product_type ) {
				$is_customizable = get_post_meta( $product_id, 'woocommerce_customizable_giftware', true );
				if ( ! isset( $_POST['wps_wgm_send_giftcard'] ) || empty( $_POST['wps_wgm_send_giftcard'] ) ) {
					$validate = false;
					wc_add_notice( __( 'Delivery Method: Please Select One Method', 'giftware' ), 'error' );
				} else {
					$wps_wgm_method = sanitize_text_field( wp_unslash( $_POST['wps_wgm_send_giftcard'] ) );

					if ( 'on' !== $wps_wgm_remove_validation_to ) {
						if ( 'Mail to recipient' == $wps_wgm_method ) {
							if ( ! isset( $_POST['wps_wgm_to_email'] ) || empty( $_POST['wps_wgm_to_email'] ) ) {
								$validate = false;
								wc_add_notice( __( 'Recipient Email: Field is empty.', 'giftware' ), 'error' );
							} elseif ( ! filter_var( $this->wps_uwgc_test_input( sanitize_text_field( wp_unslash( $_POST['wps_wgm_to_email'] ) ) ), FILTER_VALIDATE_EMAIL ) ) {
								$validate = false;
								wc_add_notice( __( 'Recipient Email: Invalid email format', 'giftware' ), 'error' );
							}
						}
					}

					if ( 'yes' !== $is_customizable ) {
						if ( 'Mail to recipient' == $wps_wgm_method ) {
							if ( 'on' !== $wps_wgm_remove_validation_to_name ) {
								if ( ! isset( $_POST['wps_wgm_to_name_optional'] ) || empty( $_POST['wps_wgm_to_name_optional'] ) ) {
									$validate = false;
									wc_add_notice( __( 'Recipient Name: Field is empty.', 'giftware' ), 'error' );
								}
							}
						}
					}
				}
				if ( 'on' !== $wps_wgm_remove_validation_msg ) {
					if ( ! isset( $_POST['wps_wgm_message'] ) || empty( $_POST['wps_wgm_message'] ) ) {
						$validate = false;
						wc_add_notice( __( 'Message: Field is empty.', 'giftware' ), 'error' );
					} elseif ( strlen( trim( sanitize_text_field( wp_unslash( $_POST['wps_wgm_message'] ) ) ) ) > $giftcard_message_length ) {
						$validate = false;
						/* translators: %s: search term */
						$error_mesage = sprintf( __( '%1$sMessage: %2$sMessage length cannot exceed %3$s characters.', 'giftware' ), '<b>', '</b>', $giftcard_message_length );
						wc_add_notice( $error_mesage, 'error' );
					}
				}
				if ( 'on' !== $wps_wgm_remove_validation_from ) {
					if ( ! isset( $_POST['wps_wgm_from_name'] ) || empty( $_POST['wps_wgm_from_name'] ) ) {
						$validate = false;
						wc_add_notice( __( 'From: Field is empty.', 'giftware' ), 'error' );
					}
				}
			}
		}
		return $validate;
	}

	/**
	 * This function is used to test input.
	 *
	 * @name wps_uwgc_test_input
	 * @param array $data Conatins data.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_test_input( $data ) {
		$data = trim( $data );
		$data = stripslashes( $data );
		$data = htmlspecialchars( $data );
		return $data;
	}

	/**
	 * This function is used to hide giftcard thumbnail on single product page
	 *
	 * @name wps_uwgc_hide_giftcard_product_thumbnail
	 * @param array $thumbnail_settings thumbnail settings.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_hide_giftcard_product_thumbnail( $thumbnail_settings ) {
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$other_settings = get_option( 'wps_wgm_other_settings', array() );
		$wps_wgm_hide_giftcard_thumbnail = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_hide_giftcard_thumbnail' );
		return $wps_wgm_hide_giftcard_thumbnail;
	}

	/**
	 * This is function is used for hiding some  item meta from thankyou order page and also from Emails
	 *
	 * @name wps_uwgc_hide_order_metafields_from_email
	 * @param array $item_metas  Conatins item meta.
	 * @param array $formatted_meta contains formatted data.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_hide_order_metafields_from_email( $item_metas, $formatted_meta ) {
		$temp_metas = array();
		foreach ( $item_metas as $key => $meta ) {
			if ( isset( $meta->key ) && ! in_array( $meta->key, array( 'To Name', 'Selected Template', 'Image', 'Choosen Image', 'Purchase as a Gift' ) ) ) {
				$temp_metas[ $key ] = $meta;
			}
		}
		return $temp_metas;
	}

	/**
	 * This function is used to make the meta keys translatable
	 *
	 * @name wps_uwgc_woocommerce_order_item_display_meta_key
	 * @param string $display_key show display key.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_woocommerce_order_item_display_meta_key( $display_key ) {
		if ( 'To Name' == $display_key ) {
			$display_key = __( 'To Name', 'giftware' );
		}
		if ( 'To' == $display_key ) {
			$display_key = __( 'To', 'giftware' );
		}
		if ( 'From' == $display_key ) {
			$display_key = __( 'From', 'giftware' );
		}
		if ( 'Message' == $display_key ) {
			$display_key = __( 'Message', 'giftware' );
		}
		if ( 'Delivery Method' == $display_key ) {
			$display_key = __( 'Delivery Method', 'giftware' );
		}
		if ( 'Send Date' == $display_key ) {
			$display_key = __( 'Send Date', 'giftware' );
		}
		if ( 'Original Price' == $display_key ) {
			$display_key = __( 'Original Price', 'giftware' );
		}
		if ( 'Selected Template' == $display_key ) {
			$display_key = __( 'Selected Template', 'giftware' );
		}
		if ( 'Image' == $display_key ) {
			$display_key = __( 'Image', 'giftware' );
		}
		return $display_key;
	}

	/**
	 * This function is used to make the meta values translatable
	 *
	 * @name wps_uwgc_woocommerce_order_item_display_meta_value
	 * @param string $display_value show display value.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_woocommerce_order_item_display_meta_value( $display_value ) {

		if ( 'Mail to recipient' == $display_value ) {
			$display_value = __( 'Mail to recipient', 'giftware' );
		}
		if ( 'Downloadable' == $display_value ) {
			$display_value = __( 'Downloadable', 'giftware' );
		}
		if ( 'Shipping' == $display_value ) {
			$display_value = __( 'Shipping', 'giftware' );
		}
		return $display_value;
	}

	/**
	 * This is function of class where coupon on shipping is applied
	 *
	 * @name add_hooks_and_filters
	 * @param array $cart Conatins cart data.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_apply_coupon_on_cart_total( $cart ) {
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$delivery_settings = get_option( 'wps_wgm_delivery_settings', array() );
		$gift_cart_ship = $wps_public_obj->wps_wgm_get_template_data( $delivery_settings, 'wps_wgm_general_cart_shipping_enable' );
		if ( 'on' == $gift_cart_ship ) {
			$wps_cart_discount = $cart->discount_cart;
			$woo_ver = WC()->version;
			if ( $woo_ver >= '4.4.0' ) {
				if ( 'incl' === WC()->cart->get_tax_price_display_mode() ) {
					if ( isset( $cart->discount_cart_tax ) && null !== $cart->discount_cart_tax ) {
						$wps_cart_discount += $cart->discount_cart_tax;
					}
				}
			} else {
				if ( 'incl' === WC()->cart->tax_display_cart ) {
					if ( isset( $cart->discount_cart_tax ) && null !== $cart->discount_cart_tax ) {
						$wps_cart_discount += $cart->discount_cart_tax;
					}
				}
			}
			$applied_coupons = $cart->get_applied_coupons();
			if ( is_array( $applied_coupons ) && ! empty( $applied_coupons ) ) {
				$wps_coupon_arr = array();
				foreach ( $applied_coupons as $key => $code ) {
					$the_coupon = new WC_Coupon( $code );
					$coupon_id = $the_coupon->get_id();
					if ( isset( $coupon_id ) && ! empty( $coupon_id ) ) {
						$coupon_type = get_post_meta( $coupon_id, 'discount_type', true );
						if ( isset( $coupon_type ) && 'fixed_cart' == $coupon_type ) {

							$wps_coupon_total = $this->wps_uwgc_coupons_total( $cart->get_coupons() );
							$wps_uwgc_coupon_amount_left = $wps_coupon_total - $wps_cart_discount;
							$total_shipping_tax = $cart->shipping_total + $cart->shipping_tax_total;
							if ( $wps_uwgc_coupon_amount_left > 0 && ! empty( $cart->shipping_total ) ) {

								if ( $wps_uwgc_coupon_amount_left >= $total_shipping_tax ) {
									$cart->discount_cart += $total_shipping_tax;
									$this->wps_uwgc_adjust_coupon_amount( $total_shipping_tax );

									$cart->total -= $total_shipping_tax;
									return $cart;
								} elseif ( $wps_uwgc_coupon_amount_left < $total_shipping_tax ) {

									$cart->discount_cart += $wps_uwgc_coupon_amount_left;
									$this->wps_uwgc_adjust_coupon_amount( $wps_uwgc_coupon_amount_left );
									$cart->total -= $wps_uwgc_coupon_amount_left;
									return $cart;
								}
							} else {
								return $cart;
							}
						} else {
							return $cart;
						}
					} else {
						return $cart;
					}
				}
			} else {
				return $cart;
			}
		} else {
			return $cart;
		}
	}

	/**
	 * This is used to get all coupon total amount
	 *
	 * @name wps_uwgc_coupons_total
	 * @param array $wps_coupon contains coupon data.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_coupons_total( $wps_coupon ) {
		$wps_coupon_total = 0;
		foreach ( $wps_coupon as $coupon ) {
			$woo_ver = WC()->version;
			if ( $woo_ver < '3.0.0' ) {
				$wps_coupon_total = $wps_coupon_total + $coupon->coupon_amount;
			} else {
				$wps_coupon_total = $wps_coupon_total + $coupon->get_amount();
			}
		}
		return $wps_coupon_total;
	}

	/**
	 * This is used to adjust the coupon price
	 *
	 * @name wps_uwgc_adjust_coupon_amount
	 * @param mixed $more_amount Coupon Amount.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_adjust_coupon_amount( $more_amount ) {
		$coupons = WC()->cart->get_coupons();
		$woo_ver = WC()->version;
		if ( $woo_ver < '3.0.0' ) {
			foreach ( $coupons as $coupon ) {
				$wps_already_applied_coupon_amount = isset( WC()->cart->coupon_discount_amounts[ $coupon->code ] ) ? round( WC()->cart->coupon_discount_amounts[ $coupon->code ] ) : 0;
				if ( $wps_already_applied_coupon_amount < $coupon->coupon_amount ) {
					$remaining_coupon_amount = $coupon->coupon_amount - $wps_already_applied_coupon_amount;
					if ( $more_amount <= $remaining_coupon_amount ) {
						WC()->cart->coupon_discount_amounts[ $coupon->code ] = ( isset( WC()->cart->coupon_discount_amounts[ $coupon->code ] ) ? WC()->cart->coupon_discount_amounts[ $coupon->code ] : 0 ) + $more_amount;
						$more_amount = 0;
					} elseif ( $more_amount > $remaining_coupon_amount ) {
						$more_amount = $more_amount - $remaining_coupon_amount;
						WC()->cart->coupon_discount_amounts[ $coupon->code ] += $remaining_coupon_amount;
					}
				}
				if ( 0 == $more_amount ) {
					break;
				}
			}
		} else {
			foreach ( $coupons as $coupon ) {
				$wps_already_applied_coupon_amount = isset( WC()->cart->coupon_discount_amounts[ $coupon->get_code() ] ) ? round( WC()->cart->coupon_discount_amounts[ $coupon->get_code() ] ) : 0;
				if ( $wps_already_applied_coupon_amount < $coupon->get_amount() ) {
					$remaining_coupon_amount = $coupon->get_amount() - $wps_already_applied_coupon_amount;
					if ( $more_amount <= $remaining_coupon_amount ) {
						WC()->cart->coupon_discount_amounts[ $coupon->get_code() ] = ( isset( WC()->cart->coupon_discount_amounts[ $coupon->get_code() ] ) ? WC()->cart->coupon_discount_amounts[ $coupon->get_code() ] : 0 ) + $more_amount;
						$more_amount = 0;
					} elseif ( $more_amount > $remaining_coupon_amount ) {
						$more_amount = $more_amount - $remaining_coupon_amount;
						WC()->cart->coupon_discount_amounts[ $coupon->get_code() ] += $remaining_coupon_amount;
					}
				}
				if ( 0 == $more_amount ) {
					break;
				}
			}
		}
	}

	/**
	 * Create a section to input mobile number from user.
	 *
	 * @name wps_wgm_input_mobileno_section
	 * @param mixed $wps_additional_section Additional data.
	 * @param mixed $product_id Product id.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_wgm_input_mobileno_section( $wps_additional_section, $product_id ) {
		$wps_additional_section = '';
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$notification_settings = get_option( 'wps_wgm_notification_settings', array() );
		$wps_uwgc_sms_notification = $wps_public_obj->wps_wgm_get_template_data( $notification_settings, 'wps_wgm_enable_sms_notification' );
		if ( ! $wps_uwgc_sms_notification || '' === $wps_uwgc_sms_notification ) {
			$wps_uwgc_sms_notification = 'off';
		}
		$delivery_settings = get_option( 'wps_wgm_delivery_settings', array() );
		$wps_uwgc_method_enable = $wps_public_obj->wps_wgm_get_template_data( $delivery_settings, 'wps_wgm_send_giftcard' );
		if ( isset( $wps_uwgc_method_enable ) && 'shipping' !== $wps_uwgc_method_enable ) {
			if ( 'off' !== $wps_uwgc_sms_notification ) {
				$wps_additional_section .= '<p class="wps_wgm_section wps_notification" id="wps_notification_div">
					<label class="wps_wgc_label">' . __( 'Share Giftcard over SMS : ', 'giftware' ) . '</label>	
					<input type="tel" name="wps_whatsapp_contact" id="wps_whatsapp_contact" class="wps_uwgc_from_name">
					<span class= "wps_uwgc_msg_info">' . __( "Enter contact number with country code. Ex : 1XXXXXXX987 ( '+' not allowed)", 'giftware' ) . '</span><span class= "wps_uwgc_msg_info">' . __( 'NOTE : No special characters & spaces are allowed.', 'giftware' ) . '</span>
				</p>';
			}
		}
		return apply_filters( 'wps_uwgc_after_notification_section', $wps_additional_section, $product_id );
	}

	/**
	 * This function is used to validate the phone number fot sms notification
	 *
	 * @name wps_gw_validate_twilio_contact_number
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_wgm_validate_twilio_contact_number() {
		check_ajax_referer( 'wps-uwgc-verify-nonce', 'wps_uwgc_nonce' );
		$wps_contact = isset( $_POST['wps_contact'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_contact'] ) ) : '';
		if ( isset( $wps_contact ) && '' !== $wps_contact ) {
			$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
			$url = 'https://lookups.twilio.com/v1/PhoneNumbers/' . $wps_contact;
			$notification_settings = get_option( 'wps_wgm_notification_settings', array() );
			$username = $wps_public_obj->wps_wgm_get_template_data( $notification_settings, 'wps_wgm_account_sid' );
			$password = $wps_public_obj->wps_wgm_get_template_data( $notification_settings, 'wps_wgm_auth_token' );
			if ( isset( $username ) && '' !== $username && isset( $password ) && '' !== $password ) {
				$send_sms_from = $wps_public_obj->wps_wgm_get_template_data( $notification_settings, 'wps_wgm_twilio_number' );
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, $url );
				curl_setopt( $ch, CURLOPT_TIMEOUT, 30 ); // timeout after 30 seconds.
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY );
				curl_setopt( $ch, CURLOPT_USERPWD, "$username:$password" );
				$response_obj = curl_exec( $ch );
				$response_obj = json_decode( $response_obj );
				if ( isset( $response_obj ) && ! empty( $response_obj ) && 'null' != $response_obj ) {
					$response['result'] = 'Valid';
				} else {
					$response['result'] = 'Invalid';
				}
				echo json_encode( $response );
				wp_die();
			}
		}
	}

	/**
	 * This function is resend the message over phone.
	 *
	 * @name wps_uwgc_resend_message
	 * @param array $args Contains argument.
	 * @param array $wps_uwgc_common_arr Contains common arguments.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_resend_message( $args, $wps_uwgc_common_arr ) {
		$coupon_code = isset( $wps_uwgc_common_arr['coupon'] ) ? $wps_uwgc_common_arr['coupon'] : '';
		$contact_no  = isset( $wps_uwgc_common_arr['contact_no'] ) ? $wps_uwgc_common_arr['contact_no'] : '';
		$order_id    = isset( $wps_uwgc_common_arr['order_id'] ) ? $wps_uwgc_common_arr['order_id'] : '';
		$item_id     = isset( $wps_uwgc_common_arr['item_id'] ) ? $wps_uwgc_common_arr['item_id'] : '';
		global $woocommerce;
		$coupon          = new WC_Coupon( $coupon_code );
		$coupon_amount   = $coupon->get_amount();
		$wps_common_args = array();
		$wps_common_args['couponamont'] = $coupon_amount;
		$wps_common_args['contact_no'] = $contact_no;
		$wps_common_args['order_id'] = $order_id;
		$wps_common_args['item_id'] = $item_id;
		return $args;
	}

	/**
	 * This function is used to send giftcard over whatsapp
	 *
	 * @name wps_uwgc_enable_whatspp_sharing
	 * @param array $order contains order data.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_enable_whatspp_sharing( $order ) {
		$order_id = $order->get_id();
		$order_status = $order->get_status();
		if ( 'completed' == $order_status || 'processing' == $order_status ) {
			$message = '';
			$to_name = '';
			$to_email = '';
			$from = '';
			$coupon_code = '';
			$expiry_date = '';
			$amount = 0;
			$contact_no = '';
			$couponamont = 0;
			$gift_date = '';
			$itemgiftsend = '';
			foreach ( $order->get_items() as $item_id => $item ) {
				$giftcard = false;
				$product = $item->get_product();

				if ( isset( $product ) && ! empty( $product ) ) {
					$product_id = $product->get_id();
				}
				if ( isset( $product_id ) && ! empty( $product_id ) ) {
					$product_types = wp_get_object_terms( $product_id, 'product_type' );

					if ( isset( $product_types[0] ) ) {
						$product_type = $product_types[0]->slug;
						$wps_gift_product = get_post_meta( $order_id, 'sell_as_a_gc' . $item_id, true );
						if ( 'wgm_gift_card' == $product_type || 'on' == $wps_gift_product ) {
							$giftcard = true;
						}
					} else {
						$wps_gift_product = get_post_meta( $order_id, 'sell_as_a_gc' . $item_id, true );
						if ( 'on' == $wps_gift_product ) {
							$giftcard = true;
						}
					}
				}
				$item_meta_data = $item->get_meta_data();
				foreach ( $item_meta_data as $key => $value ) {
					if ( isset( $value->key ) && 'To Name' == $value->key && ! empty( $value->value ) ) {
						$to_name = $value->value;
					}
					if ( isset( $value->key ) && 'To' == $value->key && ! empty( $value->value ) ) {
						$to_email = $value->value;
					}
					if ( isset( $value->key ) && 'From' == $value->key && ! empty( $value->value ) ) {
						$from = $value->value;
					}
					if ( isset( $value->key ) && 'Message' == $value->key && ! empty( $value->value ) ) {
						$message = $value->value;
					}
					if ( isset( $value->key ) && 'Delivery Method' == $value->key && ! empty( $value->value ) ) {
						$delivery_method = $value->value;
					}
					if ( isset( $value->key ) && 'Send Date' == $value->key && ! empty( $value->value ) ) {
						$gift_date = $value->value;
					}
				}
				$itemgiftsend = get_post_meta( $order_id, "$order_id#$item_id#send", true );
				if ( '' == $itemgiftsend ) {
					$itemgiftsend = get_post_meta( $order_id, "$order_id#$item_id#giftcard_send", true );
				}

				if ( $giftcard ) {
					$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
					$wps_notification_settings = get_option( 'wps_wgm_notification_settings', array() );
					$wps_enable_whatsap_sharing = $wps_public_obj->wps_wgm_get_template_data( $wps_notification_settings, 'wps_wgm_share_on_whatsapp' );
					$wps_enable_pdf_link = $wps_public_obj->wps_wgm_get_template_data( $wps_notification_settings, 'wps_wgm_share_pdf_link' );

					if ( 'on' == $wps_enable_whatsap_sharing ) {

						$wps_wgm_whatsapp_message = $wps_public_obj->wps_wgm_get_template_data( $wps_notification_settings, 'wps_wgm_whatsapp_message' );
						if ( '' == $wps_wgm_whatsapp_message ) {
							$wps_wgm_whatsapp_message = __(
								'Hello [TO],
							[MESSAGE] 
							You have received a gift card from  [FROM]
							Coupon code : [COUPONCODE]
							Amount : [AMOUNT]
							Expiry Date : [EXPIRYDATE]',
								'giftware'
							);
						}
						$wps_wgm_whatsapp_message = preg_replace( '/\s*$^\s*/m', "\n", $wps_wgm_whatsapp_message );
						$wps_wgm_whatsapp_message = preg_replace( '/[ \t]+/', ' ', $wps_wgm_whatsapp_message );
						if ( isset( $delivery_method ) && 'Shipping' !== $delivery_method ) {
							if ( 'send' == $itemgiftsend ) {
								if ( '' !== $to_name && ! empty( $to_name ) ) {
									$wps_wgm_whatsapp_message = str_replace( '[TO]', $to_name, $wps_wgm_whatsapp_message );
								} else {
									$wps_wgm_whatsapp_message = str_replace( '[TO]', $to_email, $wps_wgm_whatsapp_message );
								}
								$wps_wgm_whatsapp_message = str_replace( '[MESSAGE]', $message, $wps_wgm_whatsapp_message );
								$wps_wgm_whatsapp_message = str_replace( '[FROM]', $from, $wps_wgm_whatsapp_message );
								$product_id = $product->get_id();
								$gift_couponnumber = get_post_meta( $order_id, "$order_id#$item_id", true );
								if ( empty( $gift_couponnumber ) ) {
									$gift_couponnumber = get_post_meta( $order_id, "$order_id#$product_id", true );
								}
								foreach ( $gift_couponnumber as $key => $value ) {
									$the_coupon = new WC_Coupon( $value );
								}
								$expiry_date_timestamp = $the_coupon->get_date_expires();
								$couponamont = $the_coupon->get_amount();
								$other_settings = get_option( 'wps_wgm_other_settings', array() );
								if ( empty( $expiry_date_timestamp ) ) {
									$expirydate_format = __( 'No Expiration', 'giftware' );
								} else {
									$expiry_date_timestamp = strtotime( $expiry_date_timestamp );
									$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
									$wps_general_settings = get_option( 'wps_wgm_general_settings', array() );
									$selected_date = $wps_public_obj->wps_wgm_get_template_data( $wps_general_settings, 'wps_wgm_general_setting_enable_selected_format' );
									if ( isset( $selected_date ) && null !== $selected_date && '' !== $selected_date ) {
										$expirydate_format = gmdate( $selected_date, $expiry_date_timestamp );
									} else {
										$expirydate_format = date_format( $expirydate_format, 'jS M Y' );
									}
								}

								$coupon_amount = $couponamont . ' ' . get_woocommerce_currency();
								$wps_wgm_whatsapp_message = str_replace( '[COUPONCODE]', $value, $wps_wgm_whatsapp_message );
								$wps_wgm_whatsapp_message = str_replace( '[AMOUNT]', $coupon_amount, $wps_wgm_whatsapp_message );
								$wps_wgm_whatsapp_message = str_replace( '[EXPIRYDATE]', $expirydate_format, $wps_wgm_whatsapp_message );

								if ( isset( $other_settings['wps_wgm_addition_pdf_enable'] ) && 'on' == $other_settings['wps_wgm_addition_pdf_enable'] && 'on' == $wps_enable_pdf_link ) {
									$dwnld_pdf = ( WPS_UWGC_UPLOAD_URL . '/giftcard_pdf/giftcard' . $order_id . $value . '.pdf' );
								}

								$wps_message = __( 'Share On', 'giftware' );

								echo '<a target="_blank" class="wps_whatsapp_share" href="https://api.whatsapp.com/send?text=', rawurlencode( $wps_wgm_whatsapp_message ), isset( $dwnld_pdf ) ? rawurlencode( ' PDF Link : ' . $dwnld_pdf ) : '', '">' . esc_html( $wps_message ) . '<img src="' . esc_url( WPS_UWGC_URL ) . 'assets/images/watsap.png"></a>';
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Wps_uwgc_display_thumbnail_temmplates
	 *
	 * @param string $wps_additional_section additional_section.
	 * @param int    $product_id id.
	 */
	public function wps_uwgc_display_thumbnail_temmplates( $wps_additional_section, $product_id ) {
		$wps_additional_section = '';
		$wps_return_value = array();
		$wps_wgm_pricing = ! empty( get_post_meta( $product_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $product_id, 'wps_wgm_pricing', true ) : get_post_meta( $product_id, 'wps_wgm_pricing_details', true );
		$templateid = $wps_wgm_pricing['template'];
		$assigned_temp = '';
		$default_selected = isset( $wps_wgm_pricing['by_default_tem'] ) ? $wps_wgm_pricing['by_default_tem'] : false;

		$wps_wgm_hide_giftcard_thumbnail = '';
		$wps_wgm_hide_giftcard_thumbnail = apply_filters( 'wps_wgm_hide_giftcard_product_thumbnail', $wps_wgm_hide_giftcard_thumbnail );
		if ( is_array( $templateid ) && ! empty( $templateid ) ) {
			foreach ( $templateid as $key => $temp_id ) {

				$featured_img = wp_get_attachment_image_src( get_post_thumbnail_id( $temp_id ), 'single-post-thumbnail' );
				if ( empty( $featured_img[0] ) ) {
					$featured_img[0] = WPS_WGC_URL . 'assets/images/placeholder.png';
				}
				$selected_class = '';
				if ( isset( $default_selected ) && null !== $default_selected && $default_selected == $temp_id ) {
					$selected_class = 'wps_wgm_pre_selected_temp';
					$choosed_temp = $temp_id;
				} elseif ( isset( $default_selected ) && is_array( $default_selected ) && null !== $default_selected && $default_selected[0] == $temp_id ) {
					$selected_class = 'wps_wgm_pre_selected_temp';
					$choosed_temp = $temp_id;
				}
				$assigned_temp .= '<img class = "wps_wgm_featured_img ' . $selected_class . '" id="' . $temp_id . '" style="width: 70px; height: 70px; display: inline-block;margin-right:5px;" src="' . $featured_img[0] . '">';
			}
		}
		if ( isset( $wps_wgm_hide_giftcard_thumbnail ) && 'on' !== $wps_wgm_hide_giftcard_thumbnail ) {
			$wps_additional_section .= '<div class="wps_wgm_selected_template" style="display: inline-block; text-decoration: none; padding-right:20px;">' . $assigned_temp . '</div>';
		}
		$wps_return_value['choosen_temp_id'] = $choosed_temp;
		$wps_return_value['html'] = $wps_additional_section;
		return $wps_return_value;
	}

	/**
	 * This function is used to send sms notification via twilio.
	 *
	 * @param array  $wps_wgm_common_arr array.
	 * @param object $order order.
	 * @name wps_wgm_send_gc_sms_via_twilio
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_wgm_send_gc_sms_via_twilio( $wps_wgm_common_arr, $order ) {
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_notification_settings = get_option( 'wps_wgm_notification_settings', array() );
		$wps_wgm_enable_sms_notification = $wps_public_obj->wps_wgm_get_template_data( $wps_notification_settings, 'wps_wgm_enable_sms_notification' );
		$contact_no = '';
		$item_id = '';
		$to = '';
		$from = '';
		$message = '';
		$coupon = '';
		$order_id = $order->get_id();
		if ( array_key_exists( 'contact_no', $wps_wgm_common_arr ) ) {
			$contact_no = $wps_wgm_common_arr['contact_no'];
		}
		if ( array_key_exists( 'delivery_method', $wps_wgm_common_arr ) ) {
			$delivery_method = $wps_wgm_common_arr['delivery_method'];
		} else {
			$delivery_method = 'Mail to recipient';
		}
		if ( array_key_exists( 'item_id', $wps_wgm_common_arr ) ) {
			$item_id = $wps_wgm_common_arr['item_id'];
		}
		if ( array_key_exists( 'to_name', $wps_wgm_common_arr ) ) {
			$to = $wps_wgm_common_arr['to_name'];
		} elseif ( array_key_exists( 'to', $wps_wgm_common_arr ) ) {
			$to = $wps_wgm_common_arr['to'];
		}
		if ( array_key_exists( 'from', $wps_wgm_common_arr ) ) {
			$from = $wps_wgm_common_arr['from'];
		}
		if ( array_key_exists( 'gift_msg', $wps_wgm_common_arr ) ) {
			$message = $wps_wgm_common_arr['gift_msg'];
		}
		if ( array_key_exists( 'product_id', $wps_wgm_common_arr ) ) {
			$product_id = $wps_wgm_common_arr['product_id'];
		}
		$itemgiftsend = get_post_meta( $order_id, "$order_id#$item_id#send", true );
		if ( '' == $itemgiftsend ) {
			$itemgiftsend = get_post_meta( $order_id, "$order_id#$item_id#giftcard_send", true );
		}
		if ( '' !== $wps_wgm_enable_sms_notification ) {
			if ( '' !== $contact_no ) {
				$wps_wgm_whatsapp_message = $wps_public_obj->wps_wgm_get_template_data( $wps_notification_settings, 'wps_wgm_whatsapp_message' );
				if ( '' == $wps_wgm_whatsapp_message ) {
					$wps_wgm_whatsapp_message = __(
						'Hello [TO],
					[MESSAGE] 
					You have received a gift card from  [FROM]
					Coupon code : [COUPONCODE]
					Amount : [AMOUNT]
					Expiry Date : [EXPIRYDATE]',
						'giftware'
					);
				}
				$wps_wgm_whatsapp_message = preg_replace( '/\s*$^\s*/m', "\n", $wps_wgm_whatsapp_message );
				$wps_wgm_whatsapp_message = preg_replace( '/[ \t]+/', ' ', $wps_wgm_whatsapp_message );
				if ( isset( $delivery_method ) && 'Shipping' !== $delivery_method ) {
					$gift_couponnumber = get_post_meta( $order_id, "$order_id#$item_id", true );
					if ( empty( $gift_couponnumber ) ) {
						$gift_couponnumber = get_post_meta( $order_id, "$order_id#$product_id", true );
					}
					foreach ( $gift_couponnumber as $key => $value ) {
						$coupon = $value;
						$the_coupon = new WC_Coupon( $value );
					}
					$expiry_date_timestamp = $the_coupon->get_date_expires();
					$couponamont = $the_coupon->get_amount();
					if ( empty( $expiry_date_timestamp ) ) {
						$expirydate_format = __( 'No Expiration', 'giftware' );
					} else {
						$expiry_date_timestamp = strtotime( $expiry_date_timestamp );
						$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
						$wps_general_settings = get_option( 'wps_wgm_general_settings', array() );
						$selected_date = $wps_public_obj->wps_wgm_get_template_data( $wps_general_settings, 'wps_wgm_general_setting_enable_selected_format' );
						if ( isset( $selected_date ) && null !== $selected_date && '' !== $selected_date ) {
							$expirydate_format = gmdate( $selected_date, $expiry_date_timestamp );
						} else {
							$expirydate_format = date_format( $expirydate_format, 'jS M Y' );
						}
					}
					if ( 'send' == $itemgiftsend ) {
						$coupon_amount = $couponamont . ' ' . get_woocommerce_currency();
						$wps_wgm_whatsapp_message = str_replace( '[TO]', $to, $wps_wgm_whatsapp_message );
						$wps_wgm_whatsapp_message = str_replace( '[MESSAGE]', $message, $wps_wgm_whatsapp_message );
						$wps_wgm_whatsapp_message = str_replace( '[FROM]', $from, $wps_wgm_whatsapp_message );
						$wps_wgm_whatsapp_message = str_replace( '[COUPONCODE]', $coupon, $wps_wgm_whatsapp_message );
						$wps_wgm_whatsapp_message = str_replace( '[AMOUNT]', $coupon_amount, $wps_wgm_whatsapp_message );
						$wps_wgm_whatsapp_message = str_replace( '[EXPIRYDATE]', $expirydate_format, $wps_wgm_whatsapp_message );
						/*sms notify */
						$send_contact = '+' . $contact_no;
						$username = $wps_public_obj->wps_wgm_get_template_data( $wps_notification_settings, 'wps_wgm_account_sid' );
						$password = $wps_public_obj->wps_wgm_get_template_data( $wps_notification_settings, 'wps_wgm_auth_token' );
						$send_sms_from = $wps_public_obj->wps_wgm_get_template_data( $wps_notification_settings, 'wps_wgm_twilio_number' );
						$wps_enable_pdf_link = $wps_public_obj->wps_wgm_get_template_data( $wps_notification_settings, 'wps_wgm_share_pdf_link' );
						$other_settings = get_option( 'wps_wgm_other_settings', array() );

						if ( isset( $username ) && '' !== $username && isset( $password ) && '' !== $password ) {
							$url = 'https://api.twilio.com/2010-04-01/Accounts/' . $username . '/Messages.json';
							$ch = curl_init();

							if ( isset( $other_settings['wps_wgm_addition_pdf_enable'] ) && 'on' == $other_settings['wps_wgm_addition_pdf_enable'] && 'on' == $wps_enable_pdf_link ) {
								$dwnld_pdf = ( WPS_UWGC_UPLOAD_URL . '/giftcard_pdf/giftcard' . $order_id . $coupon . '.pdf' );
								$curl_data = array(
									'From' => $send_sms_from,
									'Body' => __( $wps_wgm_whatsapp_message . 'PDF Link : ' . $dwnld_pdf, 'giftware' ),
									'To' => $send_contact,
								);
							} else {
								$curl_data = array(
									'From' => $send_sms_from,
									'Body' => __( $wps_wgm_whatsapp_message, 'giftware' ),
									'To' => $send_contact,
								);
							}

							curl_setopt( $ch, CURLOPT_URL, $url );
							curl_setopt( $ch, CURLOPT_TIMEOUT, 30 ); // timeout after 30 seconds.
							curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
							curl_setopt( $ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY );
							curl_setopt( $ch, CURLOPT_POSTFIELDS, $curl_data );
							curl_setopt( $ch, CURLOPT_USERPWD, "$username:$password" );
							$response = curl_exec( $ch );
							$response = json_decode( $response );
							$status_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
						}
					}
				}
			}
		}
	}

	/**
	 * This function is used to initiate gc refund process.
	 *
	 * @param int    $order_id order_id.
	 * @param string $old_status old_status.
	 * @param string $new_status new_status.
	 * @name wps_wgm_initiate_refund_gc
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_wgm_initiate_refund_gc( $order_id, $old_status, $new_status ) {
		$wps_wgc_enable = wps_wgm_giftcard_enable();
		$gc_item_in_order = 0;
		if ( $wps_wgc_enable ) {
			if ( $old_status != $new_status ) {
				if ( ( 'processing' == $old_status || 'completed' == $old_status ) &&
					( 'refunded' == $new_status || 'cancelled' == $new_status ) ) {
					$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
					$woo_ver = WC()->version;
					$order = wc_get_order( $order_id );
					$order_total_quantity = $order->get_item_count();
					// The loop to get the order items which are WC_Order_Item_Product objects since WC 3+.
					$woo_ver = WC()->version;
					foreach ( $order->get_items() as $item_id => $item ) {
						if ( $woo_ver < '3.0.0' ) {
							$_product = $order->get_product_from_item( $item );
							$product_id = $_product->id;
						} else {
							$_product = $item->get_product();
							if ( ! empty( $_product ) ) {
								$product_id = $_product->get_id();
							}
						}
						if ( isset( $product_id ) && ! empty( $product_id ) ) {
							$product_types = wp_get_object_terms( $product_id, 'product_type' );
							if ( isset( $product_types[0] ) ) {
								$product_type = $product_types[0]->slug;
								if ( 'wgm_gift_card' == $product_type ) {
									$gc_item_in_order++;
								}
							}
						}
					}
					if ( $gc_item_in_order === $order_total_quantity ) {
						foreach ( $order->get_items() as $item_id => $item ) {
							$giftcoupon = get_post_meta( $order_id, "$order_id#$item_id", true );
							if ( isset( $giftcoupon ) && ! empty( $giftcoupon ) ) {
								foreach ( $giftcoupon as $key => $value ) {
									global $woocommerce;
									$coupon_data = new WC_Coupon( $value );
									$coupon_usage = $coupon_data->get_usage_count();
									$coupon_amount = $coupon_data->get_amount();
									$expiry_date_timestamp = $coupon_data->get_date_expires();

									if ( empty( $expiry_date_timestamp ) ) {
										$expirydiff = 1;
									} else {
										$expiry_date_timestamp = strtotime( $expiry_date_timestamp );
										$timestamp = strtotime( gmdate( 'Y-m-d' ) );
										$expirydiff = $expiry_date_timestamp - $timestamp;
									}
									$coupon_original_amount = get_post_meta( $coupon_data->get_id(), 'wps_wgm_coupon_amount', true );
									if ( 0 == $coupon_usage && isset( $coupon_original_amount ) && $coupon_amount == $coupon_original_amount && 0 < $expirydiff ) {
										update_post_meta( $coupon_data->get_id(), 'coupon_amount', 0 );
										update_post_meta( $coupon_data->get_id(), 'date_expires', $timestamp );
									}
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * This function is used to set discount price on cart.
	 *
	 * @name wps_mini_cart_product_product_discount_price
	 * @param string $html price html.
	 * @param array  $cart_item cart_item.
	 * @param string $cart_item_key cart_item_key.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_mini_cart_product_product_discount_price( $html, $cart_item, $cart_item_key ) {
		$new_price = $html;
		if ( isset( $cart_item['product_meta']['meta_data']['wps_wgm_price'] ) && ! empty( $cart_item['product_meta']['meta_data']['wps_wgm_price'] ) ) {
			$product_id = $cart_item['product_id'];
			$gift_price = $cart_item['product_meta']['meta_data']['wps_wgm_price'];
			$wps_wgm_discount_data = $this->wps_uwgc_common_discount_function( $product_id );

			$discount_min = $wps_wgm_discount_data['discount_min'];
			$discount_max = $wps_wgm_discount_data['discount_max'];
			$discount_type = $wps_wgm_discount_data['discount_type'];
			$discount_value = $wps_wgm_discount_data['discount_value'];
			$discount_applicable = false;
			if ( isset( $wps_wgm_discount_data['discount_enable'] ) && 'on' == $wps_wgm_discount_data['discount_enable'] ) {
				if ( isset( $wps_wgm_discount_data['wps_wgm_discount'] ) && 'yes' == $wps_wgm_discount_data['wps_wgm_discount'] ) {

					if ( isset( $discount_min ) && null !== $discount_min && isset( $discount_max ) && null !== $discount_max && isset( $discount_value ) && null !== $discount_value ) {

						foreach ( $discount_min as $key => $values ) {

							if ( $discount_min[ $key ] <= $gift_price && $gift_price <= $discount_max[ $key ] ) {

								if ( 'Percentage' == $discount_type ) {
									$new_price = $gift_price - ( $gift_price * $discount_value[ $key ] ) / 100;
								} else {
									$new_price = $gift_price - $discount_value[ $key ];
								}
							}
						}
					}
				}
			}
		}
		$gift_price = apply_filters( 'wps_uwgc_minicart_discount_price', $new_price, $product_id );
		if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
			if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
				$gift_price = wcpbc_the_zone()->get_exchange_rate_price( $gift_price );
			}
		} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
			$gift_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $gift_price );
		}
		$gift_price = str_replace( ',', '.', $gift_price );
		$gift_price = wc_price( $gift_price );
		return $gift_price;
	}

	/**
	 * Set coupon meta for product as a gift.
	 *
	 * @name wps_set_coupon_meta_for_product_as_a_gift
	 * @param int $order_id order_id.
	 * @param int $item_id item_id.
	 * @param int $new_coupon_id new_coupon_id.
	 * @param int $product_id product_id.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_set_coupon_meta_for_product_as_a_gift( $order_id, $item_id, $new_coupon_id, $product_id ) {
		$wps_gift_product = get_post_meta( $order_id, 'sell_as_a_gc' . $item_id, true );
		if ( isset( $wps_gift_product ) && 'on' === $wps_gift_product ) {
			update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
			update_post_meta( $new_coupon_id, 'product_ids', $product_id );
			update_post_meta( $new_coupon_id, 'usage_limit', '1' );
			update_post_meta( $new_coupon_id, 'minimum_amount', '' );
			update_post_meta( $new_coupon_id, 'maximum_amount', '' );
			update_post_meta( $new_coupon_id, 'exclude_sale_items', 'on' );
			update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
			update_post_meta( $new_coupon_id, 'exclude_product_categories', '' );
		}
	}

	/**
	 * Enable purchase product as a gift.
	 *
	 * @param array $wps_product product details.
	 * @return void
	 * @since 1.0.0
	 * @name wps_wgm_apply_already_created_giftcard_coupons
	 * @authorWP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_enable_sell_as_a_gc( $wps_product ) {
		if ( '' === $wps_product ) {
			global $product;
			$product_id = $product->get_id();
		} else {
			$product_id = $wps_product;
		}
		$sell_as_a_giftcard = get_post_meta( $product_id, '_sell_as_a_giftcard' );
		if ( isset( $sell_as_a_giftcard[0] ) && 'yes' === $sell_as_a_giftcard[0] ) {
			
			echo '<input type="checkbox" id="wps_gift_this_product" name="wps_gift_this_product" data-product="' . esc_html( $product->get_id() ) . '" value="on">
				<label for="wps_gift_this_product">' . __( 'Gift This Product', 'giftware' ) . '</label><br><br>';
		}

		?>
		<div id="wps_purchase_as_a_gc"></div>
		<?php
	}

	/**
	 * This function take the product array and return product id.
	 *
	 * @param object $product product.
	 * @return int $product_id
	 * @since 1.0.0
	 * @name wps_ajax_product_as_a_gift
	 * @authorWP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_ajax_product_as_a_gift( $product ) {

		$product_id = $product->get_id();
		$price      = get_post_meta( $product_id, '_price', true );

		$params   = array(
			'post_type'   => 'giftcard',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		);
		$wc_query = new WP_Query( $params );

		while ( $wc_query->have_posts() ) {
			$wc_query->the_post();
			if ( get_the_title() === 'Purchase as a Gift' ) {
				$temp_id = get_the_ID();
			}
		}

		$details = array(
			'default_price'  => $price,
			'type'           => 'wps_wgm_default_price',
			'template'       => array( $temp_id ),
			'by_default_tem' => $temp_id,
		);

		update_post_meta( $product_id, 'wps_wgm_pricing_details', $details );

		return $product_id;
	}

	/**
	 * This function tells whether a order item is purchase as a gift or not
	 *
	 * @param object $item item.
	 * @param int    $item_id item_id.
	 * @param int    $order_id order.
	 * @return string
	 * @since 1.0.0
	 * @name wps_update_item_meta_as_a_gift
	 * @authorWP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_update_item_meta_as_a_gift( $item, $item_id, $order_id ) {
		$data = $item->get_meta_data();
		foreach ( $data as $k => $val ) {
			foreach ( $val->get_data() as $key => $value ) {
				if ( 'Purchase as a Gift' === $value ) {
					update_post_meta( $order_id, 'sell_as_a_gc' . $item_id, 'on' );
				}
			}
		}
		$wps_gift_product = get_post_meta( $order_id, 'sell_as_a_gc' . $item_id, true );
		return $wps_gift_product;
	}

	/**
	 * This function is used to stop reduction of stock quantity of sell as a gift
	 *
	 * @param object $order order.
	 * @return void
	 * @since 1.0.0
	 * @name wps_stop_reduce_order_stock_for_sell_as_a_gift
	 * @authorWP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_stop_reduce_order_stock_for_sell_as_a_gift( $order ) {
		$order_id = $order->get_id();
		foreach ( $order->get_items() as $item_id => $item ) {
			$product = $item->get_product();

			$item_quantity = wc_get_order_item_meta( $item_id, '_qty', true );

			$wps_gift_product = apply_filters( 'wps_wgm_update_item_meta_as_a_gift', $item, $item_id, $order_id );

			if ( isset( $wps_gift_product ) && 'on' === $wps_gift_product ) {
				$pro_id = $product->get_id();
				if ( 'yes' === get_post_meta( $pro_id, '_manage_stock', true ) ) {
					$stock = ( get_post_meta( $pro_id, '_stock', true ) );
					update_post_meta( $pro_id, '_stock', $stock + $item_quantity );
				}
			}
		}
	}

	/**
	 * Adding dynamically GC form for Purchase as a gift.
	 *
	 * @return void
	 * @since 1.0.0
	 * @name wps_cart_form_for_product_as_a_gift
	 * @authorWP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_cart_form_for_product_as_a_gift() {

		if ( isset( $_REQUEST['wps_gc_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wps_gc_nonce'] ) ), 'wps-gc-verify-nonce' ) ) {
			if ( isset( $_POST['wps_product'] ) ) {

				$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();

				$product_id = sanitize_text_field( wp_unslash( $_POST['wps_product'] ) );
				$product    = wc_get_product( $product_id );

				$product_id = apply_filters( 'wps_wgm_ajax_product_as_a_gift', $product );

				$sell_as_a_giftcard = get_post_meta( $product_id, '_sell_as_a_giftcard' );

				if ( isset( $product ) && ! empty( $product ) ) {
					$wps_wgc_enable = wps_wgm_giftcard_enable();
					if ( $wps_wgc_enable && isset( $sell_as_a_giftcard[0] ) && 'yes' === $sell_as_a_giftcard[0] ) {
						if ( isset( $product_id ) && ! empty( $product_id ) ) {
							$cart_html = '';
							$wps_additional_section = '';
							$product_pricing = get_post_meta( $product_id, 'wps_wgm_pricing_details', true );
							if ( isset( $product_pricing ) && ! empty( $product_pricing ) ) {
								$cart_html .= '<div class="wps_wgm_added_wrapper" id="wps_product_as_a_gift_form">';
								wp_nonce_field( 'wps_wgm_single_nonce', 'wps_wgm_single_nonce_field' );
								if ( isset( $product_pricing['type'] ) ) {
									$product_pricing_type = $product_pricing['type'];
									if ( 'wps_wgm_range_price' == $product_pricing_type ) {
										$default_price = $product_pricing['default_price'];
										$from_price = $product_pricing['from'];
										$to_price = $product_pricing['to'];
										$text_box_price = ( $default_price >= $from_price && $default_price <= $to_price ) ? $default_price : $from_price;
											// hooks for discount features.
										do_action( 'wps_wgm_range_price_discount', $product, $product_pricing, $text_box_price );

										if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
											if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
												$default_price = wcpbc_the_zone()->get_exchange_rate_price( $default_price );
												$to_price = wcpbc_the_zone()->get_exchange_rate_price( $to_price );
												$from_price = wcpbc_the_zone()->get_exchange_rate_price( $from_price );
											}
											$wps_new_price = ( $default_price >= $from_price && $default_price <= $to_price ) ? $default_price : $from_price;
											$cart_html .= '<p class="wps_wgm_section selected_price_type">
												<label>' . __( 'Enter Price Within Above Range', 'giftware' ) . '</label>	
												<input type="text" class="input-text wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" value="' . $wps_new_price . '" max="' . $to_price . '" min="' . $from_price . '">
												</p>';
										} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
											$default_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $default_price );
											$to_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $to_price );
											$from_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $from_price );
											$wps_new_price = ( $default_price >= $from_price && $default_price <= $to_price ) ? $default_price : $from_price;
											$cart_html .= '<p class="wps_wgm_section selected_price_type">
												<label>' . __( 'Enter Price Within Above Range', 'giftware' ) . '</label>	
												<input type="text" class="input-text wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" value="' . $wps_new_price . '" max="' . $to_price . '" min="' . $from_price . '">
												</p>';
										} else {
											$wps_new_price = ( $default_price >= $from_price && $default_price <= $to_price ) ? $default_price : $from_price;
											$cart_html .= '<p class="wps_wgm_section selected_price_type">
												<label>' . __( 'Enter Price Within Above Range', 'giftware' ) . '</label>	
												<input type="text" class="input-text wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" value="' . $wps_new_price . '" max="' . $to_price . '" min="' . $from_price . '">
												</p>';
										}
									}
									if ( 'wps_wgm_default_price' == $product_pricing_type ) {
										$default_price = $product_pricing['default_price'];
										$cart_html .= '<input type="hidden" class="wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" value="' . $default_price . '">';
											// hooks for discount features.
										do_action( 'wps_wgm_default_price_discount', $product, $product_pricing );
									}
									if ( 'wps_wgm_selected_price' == $product_pricing_type ) {
										$default_price = $product_pricing['default_price'];
										$selected_price = $product_pricing['price'];
										if ( ! empty( $selected_price ) ) {
											$label = __( 'Choose Gift Card Selected Price: ', 'giftware' );
											$cart_html .= '<p class="wps_wgm_section selected_price_type">
														<label class="wps_wgc_label">' . $label . '</label><br/>';
												$selected_prices = explode( '|', $selected_price );
											if ( isset( $selected_prices ) && ! empty( $selected_prices ) ) {
												$cart_html .= '<select name="wps_wgm_price" class="wps_wgm_price" id="wps_wgm_price" >';
												foreach ( $selected_prices as $price ) {
													if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {

														if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
															$default_price = wcpbc_the_zone()->get_exchange_rate_price( $default_price );
															$prices = wcpbc_the_zone()->get_exchange_rate_price( $price );
															if ( $prices == $default_price ) {
																$cart_html .= '<option  value="' . $price . '" selected>' . wc_price( $prices ) . '</option>';
															} else {
																$cart_html .= '<option  value="' . $price . '" selected>' . wc_price( $prices ) . '</option>';
															}
														} else {
															if ( $price == $default_price ) {
																$cart_html .= '<option  value="' . $price . '" selected>' . wc_price( $price ) . '</option>';
															} else {
																$cart_html .= '<option  value="' . $price . '" selected>' . wc_price( $price ) . '</option>';
															}
														}
													} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
														$default_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $default_price );
														$prices = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $price );
														if ( $prices == $default_price ) {
															$cart_html .= '<option  value="' . $price . '" selected>' . wc_price( $prices ) . '</option>';
														} else {
															$cart_html .= '<option  value="' . $price . '" selected>' . wc_price( $prices ) . '</option>';
														}
													} else {
														if ( $price == $default_price ) {
															$cart_html .= '<option  value="' . $price . '" selected>' . wc_price( $price ) . '</option>';
														} else {
															$cart_html .= '<option  value="' . $price . '">' . wc_price( $price ) . '</option>';
														}
													}
												}
												$cart_html .= '</select>';
											}
												$cart_html .= '</p>';
										}
									}
									if ( 'wps_wgm_user_price' == $product_pricing_type ) {
										$default_price = $product_pricing['default_price'];
											// hooks for discount features.
										do_action( 'wps_wgm_user_price_discount', $product, $product_pricing );
											// price based on country.
										if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
											$default_price = $product_pricing['default_price'];
											if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
												$default_price = wcpbc_the_zone()->get_exchange_rate_price( $default_price );
											}
											$cart_html .= '<p class="wps_wgm_section selected_price_type"">
												<label class="wps_wgc_label">' . __( 'Enter Gift Card Price : ', 'giftware' ) . '</label>	
												<input type="text" class="wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" min="1" value = ' . $default_price . '>
												</p>';
										} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
											$default_price = $product_pricing['default_price'];
											$default_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $default_price );
											$cart_html .= '<p class="wps_wgm_section selected_price_type"">
												<label class="wps_wgc_label">' . __( 'Enter Gift Card Price : ', 'giftware' ) . '</label>	
												<input type="text" class="wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" min="1" value = ' . $default_price . '>
												</p>';
										} else {
											$cart_html .= '<p class="wps_wgm_section selected_price_type"">
												<label class="wps_wgc_label">' . __( 'Enter Gift Card Price : ', 'giftware' ) . '</label>	
												<input type="text" class="wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" min="1" value = ' . $default_price . '>
												</p>';
										}
									}
									$cart_html .= apply_filters( 'wps_wgm_add_price_types', $wps_additional_section, $product, $product_pricing );
								}
								$cart_html .= '<p class="wps_wgm_section wps_from">
								<label class="wps_wgc_label">' . __( 'From', 'giftware' ) . '</label>	
								<input type="text"  name="wps_wgm_from_name" id="wps_wgm_from_name" class="wps_wgm_from_name" placeholder="' . __( 'Enter the sender name', 'giftware' ) . '" required="required">
								</p>';
								$mail_settings = get_option( 'wps_wgm_mail_settings', array() );
								$default_giftcard_message = $wps_public_obj->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_default_message' );
								$cart_html .= '<p class="wps_wgm_section wps_message">
								<label class="wps_wgc_label">' . __( 'Gift Message ', 'giftware' ) . '</label>	
								<textarea name="wps_wgm_message" id="wps_wgm_message" class="wps_wgm_message">' . $default_giftcard_message . '</textarea>';
								$giftcard_message_length = $wps_public_obj->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_giftcard_message_length' );
								if ( '' == $giftcard_message_length ) {
									$giftcard_message_length = 300;
								}
								$cart_html .= '<span class = "wps_wgm_message_length" >';
								$cart_html .= __( 'Characters: ( ', 'giftware' ) . '<span class="wps_box_char">0</span>/' . $giftcard_message_length . ')</span>							
								</p>';
								$cart_html .= apply_filters( 'wps_wgm_add_notiication_section', $wps_additional_section, $product_id );
								$delivery_settings = get_option( 'wps_wgm_delivery_settings', true );
								$wps_wgm_delivery_setting_method = $wps_public_obj->wps_wgm_get_template_data( $delivery_settings, 'wps_wgm_send_giftcard' );
								if ( ! wps_uwgc_pro_active() ) {
									if ( 'customer_choose' == $wps_wgm_delivery_setting_method || 'shipping' == $wps_wgm_delivery_setting_method ) {
										$wps_wgm_delivery_setting_method = 'Mail to recipient';
									}
								}
									$cart_html .= '<div class="wps_wgm_section wps_delivery_method">';
										$cart_html .= '<label class = "wps_wgc_label">' . __( 'Delivery Method', 'giftware' ) . '</label>';
								if ( ( isset( $wps_wgm_delivery_setting_method ) && 'Mail to recipient' == $wps_wgm_delivery_setting_method ) || ( '' == $wps_wgm_delivery_setting_method ) ) {
									$cart_html .= '<div class="wps_wgm_delivery_method">
												<input type="radio" name="wps_wgm_send_giftcard" value="Mail to recipient" class="wps_wgm_send_giftcard" checked="checked" id="wps_wgm_to_email_send" >
												<span class="wps_wgm_method">' . __( 'Mail To Recipient', 'giftware' ) . '</span>
												<div class="wps_wgm_delivery_via_email">
													<input type="text"  name="wps_wgm_to_email" id="wps_wgm_to_email" class="wps_wgm_to_email" placeholder="' . __( 'Enter the Recipient Email', 'giftware' ) . '">
													<input type="text"  name="wps_wgm_to_name_optional" id="wps_wgm_to_name_optional" class="wps_wgm_to_email" placeholder="' . __( 'Enter the Recipient Name', 'giftware' ) . '">
													<span class= "wps_wgm_msg_info">' . __( 'We will send it to the recipient\'s email address.', 'giftware' ) . '</span>
												</div>
											</div>';
								}
								if ( isset( $wps_wgm_delivery_setting_method ) && 'Downloadable' == $wps_wgm_delivery_setting_method ) {
									$cart_html .= '<div class="wps_wgm_delivery_method">
												<input type="radio" name="wps_wgm_send_giftcard" value="Downloadable" class="wps_wgm_send_giftcard" checked="checked" id="wps_wgm_send_giftcard_download">
												<span class="wps_wgm_method">' . __( 'You Print & Give To Recipient', 'giftware' ) . '</span>
												<div class="wps_wgm_delivery_via_buyer">
													<input type="text"  name="wps_wgm_to_email_name" id="wps_wgm_to_download" class="wps_wgm_to_email" placeholder="' . __( 'Enter the Recipient Name', 'giftware' ) . '">
													<span class= "wps_wgm_msg_info">' . __( 'After Checkout, you can print your gift card', 'giftware' ) . '</span>
												</div>
											</div>';
								}
								$cart_html .= apply_filters( 'wps_wgm_add_delivery_method', $wps_additional_section, $product_id );
								$cart_html .= '</div>';
								$cart_html .= apply_filters( 'wps_wgm_add_section_after_delivery', $wps_additional_section, $product_id );
								$wps_wgm_pricing = get_post_meta( $product_id, 'wps_wgm_pricing_details', true );
								if ( array_key_exists( 'template', $wps_wgm_pricing ) ) {
									$templateid = $wps_wgm_pricing['template'];
								} else {
									$templateid = $wps_public_obj->wps_get_org_selected_template();
								}
								$choosed_temp = '';
								if ( ! wps_uwgc_pro_active() ) {
									if ( '1' < count( $templateid ) ) {
										$wps_get_pro_templates = get_option( 'wps_uwgc_templateid', array() );
										if ( ! empty( $wps_get_pro_templates ) ) {
											$wps_get_lite_temp = array_diff( $templateid, $wps_get_pro_templates );
											if ( ! empty( $wps_get_lite_temp ) ) {
												$wps_index = array_keys( $wps_get_lite_temp )[0];
												if ( 0 !== count( $wps_get_lite_temp ) ) {
													$choosed_temp = $wps_get_lite_temp[ $wps_index ];
												}
											} else {
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
												if ( ! empty( $template ) ) {
													$wps_get_lite_temp = array_diff( array_keys( $template ), $wps_get_pro_templates );
													$wps_index = array_keys( $wps_get_lite_temp )[0];
													if ( 0 !== count( $wps_get_lite_temp ) ) {
														$choosed_temp = $wps_get_lite_temp[ $wps_index ];
													}
												}
											}
										} else {
											$choosed_temp = $templateid[0];
										}
									} else {
										$choosed_temp = $templateid[0];
									}
								}

								$cart_html .= '<input name="add-to-cart" value="' . $product_id . '" type="hidden" class="wps_wgm_hidden_pro_id">';
								if ( is_array( $templateid ) && ! empty( $templateid ) ) {
									$cart_html .= '<input name="wps_wgm_selected_temp" id="wps_wgm_selected_temp" value="' . $choosed_temp . '" type="hidden">';
								}
								$other_settings = get_option( 'wps_wgm_other_settings', array() );
								$wps_wgm_preview_disable = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_additional_preview_disable' );

								if ( empty( $wps_wgm_preview_disable ) ) {
									$cart_html .= '<span class="mwg_wgm_preview_email"><a id="mwg_wgm_preview_email" href="javascript:void(0);">' . __( 'PREVIEW', 'giftware' ) . '</a></span>';
								}
								$cart_html .= apply_filters( 'wps_wgm_after_preview_section', $wps_additional_section, $product_id );
								$cart_html .= '</div>';
							}
							// @codingStandardsIgnoreStart.
							$wps_admin_obj = new Woocommerce_Gift_Cards_Common_Function();
							$allowed_tags = $wps_admin_obj->wps_allowed_html_tags();
							echo wp_kses( $cart_html, $allowed_tags );
							// @codingStandardsIgnoreEnd.
							wp_die();
						}
					}
				}
			}
		}
	}

	/**
	 * Apply Coupon through Coupon url.
	 *
	 * @return void
	 * @since 3.3.0
	 * @name wps_uwgc_apply_coupon_through_url
	 * @authorWP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_apply_coupon_through_url() {
		if ( isset( $_GET['wps_giftcard_code'] ) && ! empty( $_GET['wps_giftcard_code'] ) ) {
			$wps_giftcard_code = sanitize_text_field( wp_unslash( $_GET['wps_giftcard_code'] ) );
			if ( isset( $wps_giftcard_code ) && ! empty( $wps_giftcard_code ) ) {
				WC()->cart->add_discount( $wps_giftcard_code );
			}
		}
	}

	/**
	 * Is_addon_active
	 *
	 * @return boolean
	 */
	public function is_addon_active() {
		return apply_filters( 'wps_wgm_is_addon_active', false );
	}
}

