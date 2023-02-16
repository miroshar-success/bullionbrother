<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wpswings.com
 * @since      1.0.0
 *
 * @package    Ultimate Woocommerce Gift Cards
 * @subpackage Ultimate Woocommerce Gift Cards/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Ultimate Woocommerce Gift Cards
 * @subpackage Ultimate Woocommerce Gift Cards/admin
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Ultimate_Woocommerce_Gift_Card_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	/**
	 * The object of common class file.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $wps_uwgc_settings    The common variable used for classes.
	 */
	private $wps_uwgc_settings;
	/**
	 * The object of common class file.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $wps_common_fun    The common variable used for classes.
	 */
	public $wps_common_fun;
	/**
	 * Array ro hold imported templates.
	 *
	 * @param string $plugin_name plugin name.
	 * @param string $version version.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array    $wps_uwgc_array    Hold imported templates.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		require_once WPS_UWGC_DIRPATH . 'admin/partials/templates/wps-uwgc-settings/class-wps-uwgc-setting-html-function.php';
		require_once WPS_UWGC_DIRPATH . 'admin/partials/templates/wps-uwgc-settings/class-wps-uwgc-settings-data.php';
		require_once WPS_UWGC_DIRPATH . 'includes/class-wps-uwgc-giftcard-common-function.php';
		$this->wps_uwgc_settings = new Wps_Uwgc_Settings_Data();

		$this->wps_common_fun = new WPS_UWGC_Giftcard_Common_Function();

		include_once WPS_UWGC_DIRPATH . 'Qrcode/phpqrcode/qrlib.php';
		include_once WPS_UWGC_DIRPATH . 'Qrcode/php-barcode-master/barcode.php';

	}


	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ultimate-woocommerce-gift-card-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . 'wps_wgm_jquery-ui-datepicker', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( $this->plugin_name . 'color-picker', plugin_dir_url( __FILE__ ) . 'js/color-script.js', array( 'wp-color-picker' ), $this->version, false );

		$general_settings = get_option( 'wps_wgm_general_settings', array() );
		$wps_obj = new Woocommerce_Gift_Cards_Common_Function();
		$selected_date = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable_selected_format' );
		$selected_date = $this->wps_common_fun->wps_uwgc_selected_date_format_for_js( $selected_date );

		$wps_wgm = array(
			'ajax_url'      => admin_url( 'admin-ajax.php' ),
			'wps_wgm_nonce' => wp_create_nonce( 'wps-wgm-verify-nonce' ),
			'dateformat'    => $selected_date,
		);

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ultimate-woocommerce-gift-card-admin.js', array( 'jquery', 'wp-color-picker', 'jquery-ui-datepicker', 'wc-enhanced-select' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'wps_wgm_params', $wps_wgm );

		if ( ( isset( $_GET['page'] ) && 'wps-wgc-setting-lite' == $_GET['page'] ) && isset( $_GET['tab'] ) && 'discount' == $_GET['tab'] ) {
			wp_enqueue_script( $this->plugin_name . '-admin-discount', plugin_dir_url( __FILE__ ) . 'js/ultimate-woocommerce-gift-card-admin-discount.js', array( 'jquery' ), $this->version, false );
		}
		if ( ( isset( $_GET['page'] ) && 'wps-wgc-setting-lite' == $_GET['page'] ) && isset( $_GET['tab'] ) && 'thankyou-order' == $_GET['tab'] ) {
			wp_register_script( $this->plugin_name . '-thankyou-order', plugin_dir_url( __FILE__ ) . 'js/ultimate-woocommerce-gift-card-admin-thankyou-order.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-thankyou-order' );
		}
		if ( ( isset( $_GET['page'] ) && 'wps-wgc-setting-lite' == $_GET['page'] ) && isset( $_GET['tab'] ) && 'customizable-giftcard' == $_GET['tab'] ) {

			wp_enqueue_script( $this->plugin_name . '-customizable', plugin_dir_url( __FILE__ ) . 'js/ultimate-woocommerce-giftcard-admin-customizable.js', array( 'jquery' ), $this->version, false );
			wp_localize_script(
				$this->plugin_name . '-customizable',
				'ajax_object',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'reloadurl' => admin_url( 'admin.php?page=wps-wgc-setting-lite&tab=customizable-giftcard' ),
					'license_nonce' => wp_create_nonce( 'woocommerce-customizable-gift-card-license-nonce-action' ),
				)
			);
		}
		if ( ( isset( $_GET['page'] ) && 'wps-wgc-setting-lite' == $_GET['page'] ) && isset( $_GET['tab'] ) && 'other_setting' == $_GET['tab'] ) {

			$url = home_url( '/wp-admin/admin.php?page=wps-wgc-setting-lite' );
			$wps_wgm = array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'dateformat' => $selected_date,
				'WPS_UWGC_URL' => $url,
				'wps_uwgc_nonce' => wp_create_nonce( 'wps-uwgc-verify-nonce' ),
			);
			wp_enqueue_script( $this->plugin_name . '-othersettings', plugin_dir_url( __FILE__ ) . 'js/ultimate-woocommerce-giftcard-admin-other-settings.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( $this->plugin_name . '-othersettings', 'wps_wgm', $wps_wgm );
		}

		// enqueue scripts for giftcard products edit page.
		wp_enqueue_script( $this->plugin_name . '-admin-products', plugin_dir_url( __FILE__ ) . 'js/ultimate-woocommerce-gift-card-admin-product.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name . '-admin-products', 'wps_wgm_object', $wps_wgm );
		// giftcard reporting js.
		if ( isset( $_GET['tab'] ) && 'giftcard_report' == $_GET['tab'] ) {
			$wps_uwgc_report_array = array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'wps_uwgc_report_nonce' => wp_create_nonce( 'wps-uwgc-giftcard-report-nonce' ),
			);
			wp_enqueue_script( 'wps_uwgc_report_js', plugin_dir_url( __FILE__ ) . 'js/ultimate-woocommerce-giftcard-report.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( 'wps_uwgc_report_js', 'ajax_object', $wps_uwgc_report_array );
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style( 'thickbox' );
		}
		/*Isotope Js to import template*/
		$wps_uwgc_import_gc_array = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'wps_import_temp_nonce' => wp_create_nonce( 'wps-uwgc-giftcard-import-nonce' ),
		);
		wp_enqueue_script( 'wps_isotope_js', plugin_dir_url( __FILE__ ) . 'js/isotope.pkgd.min.js', array( 'jquery' ), '1.2.1', false );
		if ( isset( $_GET['page'] ) && 'uwgc-import-giftcard-templates' == $_GET['page'] ) {

			wp_register_script( $this->plugin_name . '-import-template', plugin_dir_url( __FILE__ ) . 'js/ultimate-woocommerce-giftcard-isotope.js', array( 'jquery', 'wps_isotope_js' ), '3.0.0', true );
			wp_localize_script( $this->plugin_name . '-import-template', 'wps_import_gc', $wps_uwgc_import_gc_array );
			wp_enqueue_script( $this->plugin_name . '-import-template' );
		}

		/*Enqueue script for selecting multiple templates*/
		$wps_wgm = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'append_option_val' => __( 'Select the template from above field', 'giftware' ),
			'wps_wgm_nonce' => wp_create_nonce( 'wps-wgm-verify-nonce' ),
		);

		wp_register_script( $this->plugin_name . 'admin-product', plugin_dir_url( __FILE__ ) . 'js/woocommerce_gift_cards_lite-product.js', array( 'jquery' ), '3.0.0', false );

		wp_localize_script( $this->plugin_name . 'admin-product', 'wps_wgm', $wps_wgm );
		wp_enqueue_script( $this->plugin_name . 'admin-product' );

		$screen = get_current_screen();
		if ( isset( $screen->id ) ) {
			$pagescreen = $screen->id;
		}
		if ( 'shop_order' === $pagescreen ) {
			if ( $this->check_if_giftcard_is_refundable() ) {
				wp_register_script( $this->plugin_name . 'gc_refundable', plugin_dir_url( __FILE__ ) . 'js/giftware-refund.js', array( 'jquery' ), '3.0.2', false );
				wp_enqueue_script( $this->plugin_name . 'gc_refundable' );
			}
		}
	}

	/**
	 * This function is used to check if giftcard is refundable.
	 *
	 * @name check_if_giftcard_is_refundable
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function check_if_giftcard_is_refundable() {
		$result = false;
		$gc_item_in_order = 0;
		if ( isset( $_GET['post'] ) ) {
			$order_id = sanitize_text_field( wp_unslash( $_GET['post'] ) );
			$wps_wgc_enable = wps_wgm_giftcard_enable();
			if ( $wps_wgc_enable ) {
				$order = wc_get_order( $order_id );
				$order_status = $order->get_status();
				$order_total_quantity = $order->get_item_count();
				if ( 'processing' == $order_status || 'completed' == $order_status ) {
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
										$result = false;
									} else {
										$result = true;
									}
								}
							}
						}
					}
				}
			}
		}
		return $result;
	}

	/**
	 * This function is used fpor license verification
	 *
	 * @name enqueue_scripts_for_license_validation
	 * @since 1.0.0
	 */
	public function enqueue_scripts_for_license_validation() {
		if ( ( isset( $_GET['page'] ) && 'wps-wgc-setting-lite' == $_GET['page'] ) && isset( $_GET['tab'] ) && 'validate_license' == $_GET['tab'] ) {
			wp_enqueue_script( $this->plugin_name . '-license', plugin_dir_url( __FILE__ ) . 'js/ultimate-woocommerce-giftcard-license.js', array( 'jquery' ), $this->version, false );
			wp_localize_script(
				$this->plugin_name . '-license',
				'license_ajax_object',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'reloadurl' => admin_url( 'edit.php?post_type=giftcard&page=wps-wgc-setting-lite' ),
					'license_nonce' => wp_create_nonce( 'wps_uwgc-nonce-action' ),
				)
			);
		}
	}
	/**
	 * This function return true if ultimate giftcard is active
	 *
	 * @name wps_uwgc_ultimate_giftcard_active
	 * @since 1.0.0
	 */
	public function wps_uwgc_ultimate_giftcard_active() {
		return true;
	}

	/**
	 * This function is used to provide Add new Template capability
	 *
	 * @name wps_uwgc_template_capabilities
	 * @param array $capability Hold the capability.
	 * @since 1.0.0
	 */
	public function wps_uwgc_template_capabilities( $capability ) {
		$capability = array(
			'create_posts' => true,
		);
		return $capability;
	}

	/**
	 * This function is to add giftcard tab settings
	 *
	 * @name wps_uwgc_pro_gift_card_setting_tab
	 * @param array $settings Contains the setting array.
	 * @since 1.0.0
	 */
	public function wps_uwgc_pro_gift_card_setting_tab( $settings ) {
		$callname_lic = Ultimate_Woocommerce_Gift_Card::$lic_callback_function;
		$callname_lic_initial = Ultimate_Woocommerce_Gift_Card::$lic_ini_callback_function;
		$day_count = Ultimate_Woocommerce_Gift_Card::$callname_lic_initial();

		if ( is_array( $settings ) && ! empty( $settings ) ) {
			if ( ! Ultimate_Woocommerce_Gift_Card::$callname_lic() ) {
				if ( 0 <= $day_count ) {
					$wps_uwgc_data = $this->wps_uwgc_settings->wps_ugc_get_pro_tab_additional_settings();
					$settings = array_merge( $settings, $wps_uwgc_data );
				} else {
					$settings = $settings;
				}
			} else {
				$wps_uwgc_data = $this->wps_uwgc_settings->wps_ugc_get_pro_tab_additional_settings();
				$settings = array_merge( $settings, $wps_uwgc_data );
			}
		}
		return $settings;
	}

	/**
	 * This function is to add giftcard tab settings for license activation af
	 *
	 * @name wps_uwgc_add_license_setting_tab
	 * @param array $settings Contains the array of settings.
	 * @since 1.0.0
	 */
	public function wps_uwgc_add_license_setting_tab( $settings ) {
		$callname_lic = Ultimate_Woocommerce_Gift_Card::$lic_callback_function;
		$callname_lic_initial = Ultimate_Woocommerce_Gift_Card::$lic_ini_callback_function;
		$day_count = Ultimate_Woocommerce_Gift_Card::$callname_lic_initial();

		if ( is_array( $settings ) && ! empty( $settings ) ) {
			if ( ! Ultimate_Woocommerce_Gift_Card::$callname_lic() || 0 <= $day_count ) {
				if ( ! Ultimate_Woocommerce_Gift_Card::$callname_lic() ) {
					$settings['validate_license'] = array(
						'title' => __( 'Activate License', 'giftware' ),
						'file_path' => WPS_UWGC_DIRPATH . 'admin/partials/templates/wps-uwgc-license-activation.php',
					);
				} else {
					$settings = $settings;
				}
			}
		}
		return $settings;
	}

	/**
	 * This function is to add giftcard notice on license activation.
	 *
	 * @name wps_uwgc_show_license_activation_notice.
	 * @since 1.0.0
	 */
	public function wps_uwgc_show_license_activation_notice() {

		$callname_lic = Ultimate_Woocommerce_Gift_Card::$lic_callback_function;
		$callname_lic_initial = Ultimate_Woocommerce_Gift_Card::$lic_ini_callback_function;
		$day_count = Ultimate_Woocommerce_Gift_Card::$callname_lic_initial();

		if ( ! Ultimate_Woocommerce_Gift_Card::$callname_lic() ) {
			if ( 0 <= $day_count ) {
				$day_count_warning = floor( $day_count );
				/* translators: %s: search term */
				$day_string = sprintf( _n( '%s day', '%s days', $day_count_warning, 'giftware' ), number_format_i18n( $day_count_warning ) );
				?>
				<div class="update-nag">
					<strong>
						<?php esc_html_e( 'Activate your License Key before ', 'giftware' ); ?>
						<a href="<?php get_admin_url(); ?>edit.php?post_type=giftcard&page=wps-wgc-setting-lite&tab=validate_license"><?php echo esc_html( $day_string ); ?></a>
						<?php esc_html_e( ' of activation - You might risk losing data by then, and you will not be able to use the plugin !', 'giftware' ); ?>
						<a href="<?php get_admin_url(); ?>edit.php?post_type=giftcard&page=wps-wgc-setting-lite&tab=validate_license">
						<?php esc_html_e( ' Activate now ', 'giftware' ); ?></a>
					</strong>
				</div>
				<?php
			} else {
				?>
				<div class="update-nag">
					<strong><?php esc_html_e( 'Unfortunately, Your trial has been expired. Please ', 'giftware' ); ?>
					<a href="<?php get_admin_url(); ?>edit.php?post_type=giftcard&page=wps-wgc-setting-lite&tab=validate_license">
					   <?php esc_html_e( 'activate', 'giftware' ); ?></a>
				   <?php esc_html_e( ' your license key to avail the premium features.', 'giftware' ); ?>
					</strong>
				</div>
				<?php
			}
		}
	}

	/**
	 * This is used to add metabox
	 *
	 * @name wps_uwgc_css_metabox
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_css_metabox() {
		$screens = array( 'giftcard' );
		foreach ( $screens as $screen ) {
			add_meta_box(
				'wps_uwgc_css_field',           // Unique ID.
				__( 'Custom CSS', 'giftware' ),  // Box title.
				array( $this, 'wps_uwgc_template_css_metabox' ),  // Content callback.
				$screen                   // Post type.
			);
		}
	}

	/**
	 * This is the html of metabox
	 *
	 * @name wps_uwgc_template_css_metabox
	 * @author WP Swings <webmaster@wpswings.com>
	 * @param object $post Contains the posts.
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_template_css_metabox( $post ) {
		$value = get_post_meta( $post->ID, 'wps_css_field', true );
		?>
		<table class="form-table">

			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="wps_css_field"><?php esc_html_e( 'Custom CSS', 'giftware' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<label>
						<textarea name="wps_css_field" id="wps_css_field" class="wps_css_field" style="width:308px;height:100px;">
							<?php echo esc_html( $value ); ?> 
						</textarea>				
					</label>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * This is used to save meta fields of templates
	 *
	 * @name wps_save_meta_fields
	 * @param int $post_id Post Id.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_save_meta_fields( $post_id ) {
		if ( array_key_exists( 'wps_css_field', $_POST ) ) {
			if ( isset( $_POST['wps_css_field'] ) ) {
				update_post_meta(
					$post_id,
					'wps_css_field',
					trim( sanitize_text_field( wp_unslash( $_POST['wps_css_field'] ) ) )
				);
			}
		}
	}

	/**
	 * Function to preview offline giftcard using ajax
	 *
	 * @name wps_uwgc_offline_preview
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_offline_preview() {
		check_ajax_referer( 'wps-wgm-verify-nonce', 'wps_nonce' );
		unset( $_POST['action'] );
		$_POST['wps_uwgc_offline_preview'] = 'wps_uwgc_offline_preview';
		$_POST['tempId'] = isset( $_POST['tempId'] ) ? stripcslashes( sanitize_text_field( wp_unslash( $_POST['tempId'] ) ) ) : '';
		$_POST['message'] = isset( $_POST['message'] ) ? stripcslashes( sanitize_text_field( wp_unslash( $_POST['message'] ) ) ) : '';

		$upload_dir_path = wp_upload_dir()['basedir'] . '/wps_browse';
		if ( ! is_dir( $upload_dir_path ) ) {
			wp_mkdir_p( $upload_dir_path );
			chmod( $upload_dir_path, 0775 );
		}
		$wps_admin_obj = new Woocommerce_Gift_Cards_Common_Function();
		$other_settings = get_option( 'wps_wgm_other_settings', array() );
		$browse_enable = $wps_admin_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_other_setting_browse' );
		if ( 'on' == $browse_enable && isset( $_FILES['file']['type'] ) && ! empty( $_FILES['file']['type'] ) ) {

			if ( ( 'image/gif' == $_FILES['file']['type'] )
				|| ( 'image/jpeg' == $_FILES['file']['type'] )
				|| ( 'image/jpg' == $_FILES['file']['type'] )
				|| ( 'image/pjpeg' == $_FILES['file']['type'] )
				|| ( 'image/x-png' == $_FILES['file']['type'] )
				|| ( 'image/png' == $_FILES['file']['type'] ) ) {
				$file_name = isset( $_FILES['file']['name'] ) ? sanitize_text_field( wp_unslash( $_FILES['file']['name'] ) ) : '';
				$tmp_name = isset( $_FILES['file']['tmp_name'] ) ? sanitize_text_field( wp_unslash( $_FILES['file']['tmp_name'] ) ) : '';
				if ( ! file_exists( wp_upload_dir()['basedir'] . '/wps_browse/' . $file_name ) ) {
					move_uploaded_file( $tmp_name, wp_upload_dir()['basedir'] . '/wps_browse/' . $file_name );
				}
				$_POST['name'] = $file_name;
			}
		}
		$_POST['width'] = '500';
		$_POST['height'] = '500';
		$_POST['TB_iframe'] = true;
		$query = http_build_query( wp_unslash( $_POST ) );
		$ajax_url = home_url( "?$query" );
		echo esc_attr( $ajax_url );
		wp_die();
	}

	/**
	 * Function to preview offline giftcard in mail
	 *
	 * @name wps_uwgc_offline_email_preview
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_offline_email_preview() {
		if ( isset( $_GET['wps_uwgc_offline_preview'] ) && 'wps_uwgc_offline_preview' == $_GET['wps_uwgc_offline_preview'] ) {
			$wps_admin_obj = new Woocommerce_Gift_Cards_Common_Function();
			$product_id = isset( $_GET['product_id'] ) ? sanitize_text_field( wp_unslash( $_GET['product_id'] ) ) : '';
			$product_pricing = ! empty( get_post_meta( $product_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $product_id, 'wps_wgm_pricing', true ) : get_post_meta( $product_id, 'wps_wgm_pricing_details', true );
			if ( ! empty( $product_pricing ) ) {
				$product_pricing_type = $product_pricing['type'];
			}
			$is_imported = get_post_meta( $product_id, 'is_imported', true );
			if ( isset( $is_imported ) && ! empty( $is_imported ) && 'yes' == $is_imported ) {
				$coupon = 'XXXXX';
				$imported_exp_date = get_post_meta( $product_id, 'expiry_after_days', true );
				$expirydate_format = $wps_admin_obj->wps_wgm_check_expiry_date( $imported_exp_date );
			} else {
				$general_setting = get_option( 'wps_wgm_general_settings', array() );
				$giftcard_coupon_length_display = $wps_admin_obj->wps_wgm_get_template_data( $general_setting, 'wps_wgm_general_setting_giftcard_coupon_length' );
				if ( '' == $giftcard_coupon_length_display ) {
					$giftcard_coupon_length_display = 5;
				}
				$password = '';
				for ( $i = 0;$i < $giftcard_coupon_length_display;$i++ ) {
					$password .= 'x';
				}
				$giftcard_prefix = $wps_admin_obj->wps_wgm_get_template_data( $general_setting, 'wps_wgm_general_setting_giftcard_prefix' );
				$coupon = $giftcard_prefix . $password;
				$expiry_date = $wps_admin_obj->wps_wgm_get_template_data( $general_setting, 'wps_wgm_general_setting_giftcard_expiry' );
				$expirydate_format = $wps_admin_obj->wps_wgm_check_expiry_date( $expiry_date );
			}
			if ( isset( $_GET['gift_manual_code'] ) && ! empty( $_GET['gift_manual_code'] ) ) {
				$coupon = isset( $_GET['gift_manual_code'] ) ? sanitize_text_field( wp_unslash( $_GET['gift_manual_code'] ) ) : '';
			}
			$wps_wgm_pricing = get_post_meta( $product_id, 'wps_wgm_pricing', true );
			$templateid = isset( $wps_wgm_pricing['template'] ) ? $wps_wgm_pricing['template'] : '';
			if ( is_array( $templateid ) && array_key_exists( 0, $templateid ) ) {
				$temp = $templateid[0];
			} else {
				$temp = $templateid;
			}
			$args['from'] = isset( $_GET['from'] ) ? sanitize_text_field( wp_unslash( $_GET['from'] ) ) : '';
			$args['to'] = isset( $_GET['to'] ) ? sanitize_text_field( wp_unslash( $_GET['to'] ) ) : '';
			$args['message'] = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '';
			$args['coupon'] = apply_filters( 'wps_wgm_qrcode_coupon', $coupon );
			$args['expirydate'] = $expirydate_format;

			// Added for currency switcher.
			if ( class_exists( 'WOOCS' ) ) {
				global $WOOCS;
				$rate = 1;
				$currency = $WOOCS->current_currency;
				$currencies = $WOOCS->get_currencies();
				$rate = $currencies[ $currency ]['rate'];
				$cur_cur = $WOOCS->current_currency;
				$WOOCS->reset_currency();
				$WOOCS->set_currency( $cur_cur );
				$amt = isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '';
				if ( 'wps_wgm_range_price' == $product_pricing_type ) {
					$amt = isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '';
				} elseif ( 'wps_wgm_user_price' == $product_pricing_type ) {
					$amt = isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '';
				} else {
					$amt = floatval( $amt * $rate );
				}
				$args['amount'] = wc_price( $amt );
			} elseif ( class_exists( 'WCPBC_Pricing_Zone' ) ) {  // Added for price based on country.
				if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {

					if ( 'wps_wgm_range_price' == $product_pricing_type ) {
						$amt = isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '';
					} elseif ( 'wps_wgm_user_price' == $product_pricing_type ) {
							$amt = isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '';
					} else {
							$amt = isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '';
						$amt = wcpbc_the_zone()->get_exchange_rate_price( $amt );
					}
					$args['amount'] = wc_price( $amt );
				} else {
					$args['amount'] = wc_price( isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '' );
				}
			} else {
				$args['amount'] = wc_price( isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '' );
			}
			$args['templateid'] = isset( $temp_id ) && ! empty( $temp_id ) ? $temp_id : $temp;
			$args['product_id'] = $product_id;
			$args['order_id'] = '';
			$args['send_date'] = isset( $_GET['send_date'] ) ? sanitize_text_field( wp_unslash( $_GET['send_date'] ) ) : '';
			$other_settings = get_option( 'wps_wgm_other_settings', array() );
			$browse_enable = $wps_admin_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_other_setting_browse' );
			if ( 'on' == $browse_enable ) {
				if ( isset( $_GET['name'] ) && null !== $_GET['name'] ) {
					$args['browse_image'] = isset( $_GET['name'] ) ? sanitize_text_field( wp_unslash( $_GET['name'] ) ) : '';
				}
			}
			$style = '<style>
				table, th, tr, td {
					border: medium none;
				}
				table, th, tr, td {
					border: 0px !important;
				}
				#wps_wgm_email {
					width: 630px !important;
				}
				</style>';
			$giftcard_custom_css = get_option( 'wps_wgm_other_setting_mail_style', false );
			$giftcard_custom_css = stripcslashes( $giftcard_custom_css );
			$style .= "<style>$giftcard_custom_css</style>";
			$message = $wps_admin_obj->wps_wgm_create_gift_template( $args );
			$finalhtml = $style . $message;
			$wps_admin_obj = new Woocommerce_Gift_Cards_Common_Function();
			$allowed_tags = $wps_admin_obj->wps_allowed_html_tags();
			echo wp_kses( $finalhtml, $allowed_tags );
			die;
		}
	}

	/**
	 * Function to add product settings fields
	 *
	 * @name wps_uwgc_ultimate_product_settings
	 * @author WP Swings <webmaster@wpswings.com>
	 * @param array $product_settings Contains array for product setting.
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_ultimate_product_settings( $product_settings ) {
		$wps_uwgc_data = $this->wps_uwgc_settings->wps_ugc_get_pro_product_settings();
		return array_merge( $product_settings, $wps_uwgc_data );
	}

	/**
	 * Function to check offline coupon is valid or not
	 *
	 * @name wps_uwgc_ultimate_product_settings
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_check_manual_code_exist() {
		check_ajax_referer( 'wps-wgm-verify-nonce', 'wps_nonce' );
		$wps_manual_code = isset( $_POST['wps_manual_code'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_manual_code'] ) ) : '';
		$response['result'] = 'Fail due to some error!';
		if ( isset( $wps_manual_code ) && ! empty( $wps_manual_code ) ) {
			$the_coupon = new WC_Coupon( $wps_manual_code );
			$wps_manual_code_id = $the_coupon->get_id();
			if ( 0 == $wps_manual_code_id ) {
				$response['result'] = 'valid';
			} else {
				$response['result'] = 'invalid';
			}
			echo json_encode( $response );
			wp_die();
		}
	}

	/**
	 * This function is used to get all woocommerce orders
	 *
	 * @name wps_uwgc_get_all_woocommerce_orders
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_get_all_woocommerce_orders() {
		$wps_server_request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		if ( strpos( $wps_server_request_uri, 'admin.php?page=wps-wgc-setting-lite&wps_wugc_export_csv=' ) ) {
			$title = array();
			$content = array();
			$filename = 'wps_export.csv';
			$wps_export_csv = isset( $_GET['wps_wugc_export_csv'] ) ? sanitize_text_field( wp_unslash( $_GET['wps_wugc_export_csv'] ) ) : '';
			if ( 'wps_woo_gift_card_report' == $wps_export_csv ) {
				$gift_card_order_id = array();
				$coupons_args = array(
					'posts_per_page'   => -1,
					'orderby'          => 'title',
					'order'            => 'asc',
					'post_type'        => 'shop_coupon',
					'post_status'      => 'publish',
				);

				$coupons = get_posts( $coupons_args );
				if ( null !== $coupons ) {
					foreach ( $coupons as $post_key ) {
						$giftcardcoupon = get_post_meta( $post_key->ID, 'wps_wgm_giftcard_coupon', true );
						if ( ! empty( $giftcardcoupon ) ) {
							$gift_card_order_id[] = $giftcardcoupon;
						}
					}
					$gift_card_order_id = array_unique( $gift_card_order_id );
					$args = array(
						'post_type'   => wc_get_order_types(),
						'posts_per_page'   => -1,
						'post_status' => array_keys( wc_get_order_statuses() ),
						'post__in'    => $gift_card_order_id,
					);
					$loop = new WP_Query( $args );
					while ( $loop->have_posts() ) {
						$loop->the_post();

						$order = new WC_Order( $loop->post->ID );
						$order_items = $order->get_items();// Items Array.

						$all_item_keys = array_keys( $order_items );// Items Keys.

						$woo_ver = WC()->version;
						foreach ( $all_item_keys as $key => $value ) {
							$coupon_code = get_post_meta( $loop->post->ID, $loop->post->ID . '#' . $value, true );
							// check the coupon is array or not, as the previously it was just the string(before 2.4.3).
							if ( is_array( $coupon_code ) && ! empty( $coupon_code ) ) {
								foreach ( $coupon_code as $coupon_key => $coupon_val ) {
									if ( null !== $coupon_val ) {
										$coupon = new WC_Coupon( $coupon_val );
										if ( $woo_ver < '3.0.0' ) {
											$usage_amount = $coupon->usage_count;
											if ( null == $coupon->usage_count ) {
												$usage_amount = 0;
											}
											$coupon_amount_ = $coupon->coupon_amount;
											$to_type = gettype( $order_items[ $value ]['To'] );
											$from_type = gettype( $order_items[ $value ]['From'] );
											if ( preg_match( '/<[^<]+>/', $order_items[ $value ]['To'] ) ) {
												$to = new SimpleXMLElement( $order_items[ $value ]['To'] );
												$to_arr = substr( $to['href'], 7 );
											} else {
												$to = $order_items[ $value ]['To'];
												$to_arr = $to;
											}
											if ( preg_match( '/<[^<]+>/', $order_items[ $value ]['From'] ) ) {
												$from = new SimpleXMLElement( $order_items[ $value ]['From'] );
												$from_arr = substr( $from['href'], 7 );
											} else {
												$from = $order_items[ $value ]['From'];
												$from_arr = $from;
											}

											$content[] = array(
												$loop->post->ID,
												$coupon_val,
												$to_arr,
												$from_arr,
												$order_items[ $value ]['Message'],
												$usage_amount,
												$coupon_amount_,
											);
										} else {
											$usage_amount = $coupon->get_usage_count();
											if ( $coupon->get_usage_count() == null ) {
												$usage_amount = 0;
											}
											$coupon_amount_ = $coupon->get_amount();
											$to = $order_items[ $value ]['To'];
											$from = $order_items[ $value ]['From'];
											$content[] = array(
												$loop->post->ID,
												$coupon_val,
												$to,
												$from,
												$order_items[ $value ]['Message'],
												$usage_amount,
												$coupon_amount_,
											);
										}
									}
								}
							} else {
								if ( null !== $coupon_code ) {
									$coupon = new WC_Coupon( $coupon_code );
									if ( $woo_ver < '3.0.0' ) {
										$usage_amount = $coupon->usage_count;
										if ( null == $coupon->usage_count ) {
											$usage_amount = 0;
										}
										$coupon_amount_ = $coupon->coupon_amount;
										$to_type = gettype( $order_items[ $value ]['To'] );
										$from_type = gettype( $order_items[ $value ]['From'] );
										if ( preg_match( '/<[^<]+>/', $order_items[ $value ]['To'] ) ) {
											$to = new SimpleXMLElement( $order_items[ $value ]['To'] );
											$to_arr = substr( $to['href'], 7 );
										} else {
											$to = $order_items[ $value ]['To'];
											$to_arr = $to;
										}
										if ( preg_match( '/<[^<]+>/', $order_items[ $value ]['From'] ) ) {
											$from = new SimpleXMLElement( $order_items[ $value ]['From'] );
											$from_arr = substr( $from['href'], 7 );
										} else {
											$from = $order_items[ $value ]['From'];
											$from_arr = $from;
										}

										$content[] = array(
											$loop->post->ID,
											$coupon_code,
											$to_arr,
											$from_arr,
											$order_items[ $value ]['Message'],
											$usage_amount,
											$coupon_amount_,
										);

									} else {
										$usage_amount = $coupon->get_usage_count();
										if ( $coupon->get_usage_count() == null ) {
											$usage_amount = 0;
										}
										$coupon_amount_ = $coupon->get_amount();
										$to = $order_items[ $value ]['To'];
										$from = $order_items[ $value ]['From'];
										$content[] = array(
											$loop->post->ID,
											$coupon_code,
											$to,
											$from,
											$order_items[ $value ]['Message'],
											$usage_amount,
											$coupon_amount_,
										);
									}
								}
							}
						}
					}
				}
				$title = array(
					__( 'Order Id', 'giftware' ),
					__( 'Coupon Code', 'giftware' ),
					__( 'To', 'giftware' ),
					__( 'From', 'giftware' ),
					__( 'Message', 'giftware' ),
					__( 'Usage Count', 'giftware' ),
					__( 'Coupon Amount Left', 'giftware' ),
				);
				$filename = 'wps_woo_gift_card_report.csv';
			}
			$wps_export_csv = isset( $_GET['wps_wugc_export_csv'] ) ? sanitize_text_field( wp_unslash( $_GET['wps_wugc_export_csv'] ) ) : '';
			if ( 'wps_woo_offline_gift_card_report' == $wps_export_csv ) {
				global $wpdb;
				$table_name = $wpdb->prefix . 'offline_giftcard';
				$query = "SELECT * FROM $table_name";
				$giftresults = $wpdb->get_results( $query, ARRAY_A );
				foreach ( $giftresults as $key => $value ) {
					$content[] = array(
						$value['id'],
						$value['coupon'],
						$value['to'],
						$value['from'],
						$value['message'],
						$value['amount'],
					);
				}
				$filename = 'wps_woo_offline_gift_card_report.csv';
				$title = array(
					__( 'Id', 'giftware' ),
					__( 'Coupon Code', 'giftware' ),
					__( 'To', 'giftware' ),
					__( 'From', 'giftware' ),
					__( 'Message', 'giftware' ),
					__( 'Coupon Amount', 'giftware' ),
				);
			}
			$upload_dir_path = wp_upload_dir()['basedir'] . '/';
			$error_log_folder = 'wps_woo_gift_card_import_error/';

			$import_error_dir = $upload_dir_path . $error_log_folder;
			if ( ! is_dir( $import_error_dir ) ) {
				mkdir( $import_error_dir, $permissions = 0777 );
			}

			$output = fopen( $import_error_dir . $filename, 'w' );
			fputcsv( $output, $title );
			foreach ( $content as $con ) {
				fputcsv( $output, $con );
			}
			$file_name = sanitize_text_field( $filename );
			$upload_dir_path = wp_upload_dir()['basedir'] . '/';
			$error_log_folder = 'wps_woo_gift_card_import_error/';
			$path_of_file_to_download = $upload_dir_path . $error_log_folder . $file_name;

			if ( file_exists( $path_of_file_to_download ) ) {
				header( 'Content-Description: File Transfer' );
				header( 'Content-Type: application/csv' );
				header( 'Content-Disposition: attachment; filename="' . basename( $path_of_file_to_download ) . '"' );
				header( 'Expires: 0' );
				header( 'Cache-Control: must-revalidate' );
				header( 'Pragma: public' );
				header( 'Content-Length: ' . filesize( $path_of_file_to_download ) );
				readfile( $path_of_file_to_download );
				exit;
			}
		}
	}

	/**
	 * This function is used to create general setting fields
	 *
	 * @name wps_uwgc_general_settings_fields
	 * @param array $general_setting Contains array of general settings.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_general_settings_fields( $general_setting ) {
		$wps_uwgc_data = $this->wps_uwgc_settings->wps_ugc_get_pro_general_settings();
		return ( array_merge( $general_setting, $wps_uwgc_data ) );
	}

	/**
	 * This function is used to extend the common functions of ultimate woocommerce gift card to general settings html
	 *
	 * @name wps_uwgc_settings_fields_html
	 * @param array $value Contains array .
	 * @param array $saved_settings Contains array .
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_settings_fields_html( $value, $saved_settings ) {
		$wps_uwgc_html = new WPS_UWGC_SETTING_HTML_FUNCTION();
		$wps_uwgc_html->wps_uwgc_additional_common_settings_generate_html( $value, $saved_settings );
	}

	/**
	 * This function is used to extend the mail settings of ultimate woocommerce gift card
	 *
	 * @name wps_uwgc_email_settings
	 * @param array $settings Contains settings array .
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_email_settings( $settings ) {
		$wps_uwgc_data = $this->wps_uwgc_settings->wps_ugc_get_pro_mail_settings();
		return ( array_merge( $settings, $wps_uwgc_data ) );
	}

	/**
	 * This function is used to extend the bottom mail settings tab of ultimate woocommerce gift card
	 *
	 * @name wps_uwgc_additional_mail_settings
	 * @param array $wps_wgm_mail_template_settings Contains settings array .
	 * @param array $mail_settings Contains settings array .
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_additional_mail_settings( $wps_wgm_mail_template_settings, $mail_settings ) {
		?>
		<h3 id="wps_uwgc_coupon_mail_setting" class="wps_wgm_mail_setting_tab"><?php esc_html_e( ' Left Coupon Amount Mail Settings', 'giftware' ); ?></h3>
		<div id="wps_uwgc_coupon_mail_setting_wrapper" class="wps_wgm_table_wrapper">
			<table class="form-table wps_wgm_general_setting">		
				<tbody>
					<?php
					$settings_obj = new Woocommerce_Giftcard_Admin_Settings();
					$settings_obj->wps_wgm_generate_common_settings( $wps_wgm_mail_template_settings['bottom'], $mail_settings );
					?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * This function is used to extend the delivery settins of ultimate woocommerce gift card
	 *
	 * @name wps_wgm_additional_delivery_settings
	 * @param array $wps_wgm_delivery_settings Contains settings array .
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_wgm_additional_delivery_settings( $wps_wgm_delivery_settings ) {
		$wps_uwgc_data = $this->wps_uwgc_settings->wps_ugc_get_pro_delivery_settings();
		return ( array_merge( $wps_wgm_delivery_settings, $wps_uwgc_data ) );
	}

	/**
	 * This function is used to merge other settings.
	 *
	 * @name wps_wgm_additional_other_setting
	 * @param array $settings Contains settings array .
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_wgm_additional_other_setting( $settings ) {
		$wps_uwgc_data = $this->wps_uwgc_settings->wps_ugc_get_pro_other_settings();
		return ( array_merge( $settings, $wps_uwgc_data ) );
	}

	/**
	 * Provide multiple Fields for Gift Card Product
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_giftcard_product_type_field()
	 * @param int $product_id Contains product_id .
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_giftcard_product_type_field( $product_id ) {
		$wps_wgm_exclude_per_product = get_post_meta( $product_id, 'wps_wgm_exclude_per_product', true );
		$wps_wgm_exclude_per_category = get_post_meta( $product_id, 'wps_wgm_exclude_per_category', array() );
		$wps_wgm_include_per_product = get_post_meta( $product_id, 'wps_wgm_include_per_product', true );
		$wps_wgm_include_per_category = get_post_meta( $product_id, 'wps_wgm_include_per_category', array() );
		$wps_wgm_recommanded_per_product = get_post_meta( $product_id, 'wps_wgm_recommanded_per_product', true );
		$wps_wgm_pricing = get_post_meta( $product_id, 'wps_wgm_pricing', true );
		$selectedtemplate  = isset( $wps_wgm_pricing['template'] ) ? $wps_wgm_pricing['template'] : false;
		$default_selected = isset( $wps_wgm_pricing['by_default_tem'] ) ? $wps_wgm_pricing['by_default_tem'] : false;
		$wps_wgm_discount_settings = get_option( 'wps_wgm_discount_settings', array() );
		$wps_admin_obj = new Woocommerce_Gift_Cards_Common_Function();
		$discount_enable = $wps_admin_obj->wps_wgm_get_template_data( $wps_wgm_discount_settings, 'wps_wgm_discount_enable' );
		$is_customizable = get_post_meta( $product_id, 'woocommerce_customizable_giftware', false );
		if ( isset( $discount_enable ) && 'on' == $discount_enable && empty( $is_customizable ) ) {
			woocommerce_wp_checkbox(
				array(
					'id' => 'wps_wgm_discount',
					'class' => 'wps_wgm_discount',
					'label' => __( 'Give Discount ?', 'giftware' ),
					'name' => 'wps_wgm_discount',
				)
			);
		}
		if ( empty( $is_customizable ) ) {
			?>
		<p class="form-field wps_wgm_email_defualt_template">
			<label class = "wps_wgm_email_defualt_template" for="wps_wgm_email_defualt_template"><?php esc_html_e( 'Which template do you want to be selected by default?', 'giftware' ); ?></label>

			<select id="wps_wgm_email_defualt_template" name = "wps_wgm_email_defualt_template" style="width: 50%">
				<?php

				if ( empty( $default_selected ) ) {
					?>
					<option value=""><?php esc_html_e( 'Select the template from above field ', 'giftware' ); ?></option>
					<?php
				} elseif ( is_array( $selectedtemplate ) && ! empty( $selectedtemplate ) && ! empty( $default_selected ) ) {
					$args = array(
						'post_type' => 'giftcard',
						'post__in' => $selectedtemplate,
					);
					$loop = new WP_Query( $args );
					foreach ( $loop->posts as $key => $value ) {
						$template_id = $value->ID;
						$template_title = $value->post_title;
						$alreadyselected = '';
						if ( is_array( $selectedtemplate ) && in_array( $default_selected, $selectedtemplate ) && $default_selected == $template_id ) {

							$alreadyselected = " selected='selected'";
						}
						?>
						<option value="<?php echo esc_attr( $template_id ); ?>"<?php echo esc_attr( $alreadyselected ); ?>><?php echo esc_attr( $template_title ); ?></option>
						<?php
					}
				} elseif ( '' !== $selectedtemplate && '' !== $default_selected ) {
					$alreadyselected = '';
					if ( $selectedtemplate == $default_selected ) {
						$alreadyselected = " selected='selected'";
					}
					$template_id = $default_selected;
					$template_title = get_the_title( $default_selected );
					?>
					<option value="<?php echo esc_attr( $template_id ); ?>"<?php echo esc_attr( $alreadyselected ); ?>><?php echo esc_attr( $template_title ); ?></option>
					<?php
				}
				?>
			</select>
		</p>
			<?php
		}
		$woo_ver = WC()->version;
		if ( $woo_ver < '3.0.0' ) {
			?>
			<p class="form-field"><label><?php esc_html_e( 'Exclude Products', 'giftware' ); ?></label>
				<input type="hidden" class="wc-product-search" data-multiple="true" style="width: 50%;" name="wps_wgm_exclude_per_product" data-placeholder="<?php esc_attr_e( 'Search for a product', 'giftware' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-selected="" value="<?php echo esc_html( implode( ',', array_keys( $json_ids ) ) ); ?>" />
			</p>
			<?php
		} else {
			?>
			<p class="form-field wps_wgm_exclude_per_product_field">
				<label class = "wps_wgm_exclude_per_product" for="wps_wgm_exclude_per_product"><?php esc_html_e( 'Exclude Products', 'giftware' ); ?></label>
				<select class="wc-product-search" multiple="multiple" style="width: 50%;" name="wps_wgm_exclude_per_product[]" data-placeholder="<?php esc_attr_e( 'Search for a product', 'giftware' ); ?>" data-action="woocommerce_json_search_products_and_variations" id="wps_wgm_exclude_per_product"> 
					<?php
					if ( isset( $wps_wgm_exclude_per_product ) && ! empty( $wps_wgm_exclude_per_product ) ) {
						foreach ( $wps_wgm_exclude_per_product as $pro_id ) {
							$product      = wc_get_product( $pro_id );
							$product_title = $product->get_formatted_name();
							echo '<option value="' . esc_attr( $pro_id ) . '" selected="selected">' . esc_html( $product_title ) . '</option>';
						}
					}
					?>
				</select>
			</p>
			<?php
		}
		?>
		<p class="form-field wps_wgm_exclude_per_category_field">
			<label class = "wps_wgm_exclude_per_category" for="wps_wgm_exclude_per_category"><?php esc_html_e( 'Exclude Category', 'giftware' ); ?></label>
			<select id="wps_wgm_exclude_per_category" multiple="multiple" name="wps_wgm_exclude_per_category[]">
				<?php
				$args = array( 'taxonomy' => 'product_cat' );
				$categories = get_terms( $args );
				if ( isset( $categories ) && ! empty( $categories ) ) {
					foreach ( $categories as $category ) {
						$catid = $category->term_id;
						$catname = $category->name;
						$catselect = '';
						if ( is_array( $wps_wgm_exclude_per_category ) && ! empty( $wps_wgm_exclude_per_category ) ) {
							if ( is_array( $wps_wgm_exclude_per_category[0] ) && in_array( $catid, $wps_wgm_exclude_per_category[0] ) ) {
								$catselect = "selected='selected'";
							}
						}
						?>
						<option value="<?php echo esc_html( $catid ); ?>" <?php echo esc_html( $catselect ); ?>><?php echo esc_html( $catname ); ?></option>
						<?php
					}
				}
				?>
			</select>
		</p>
		<?php
		$woo_ver = WC()->version;
		if ( $woo_ver < '3.0.0' ) {
			?>
			<p class="form-field"><label><?php esc_html_e( 'Include Products', 'giftware' ); ?></label>
				<input type="hidden" class="wc-product-search" data-multiple="true" style="width: 50%;" name="wps_wgm_include_per_product" data-placeholder="<?php esc_attr_e( 'Search for a product', 'giftware' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-selected="" value="<?php echo esc_html( implode( ',', array_keys( $json_ids ) ) ); ?>" />
			</p>
			<?php
		} else {
			?>
			<p class="form-field wps_wgm_include_per_product_field">
				<label class = "wps_wgm_include_per_product" for="wps_wgm_include_per_product"><?php esc_html_e( 'Include Products', 'giftware' ); ?></label>
				<select class="wc-product-search" multiple="multiple" style="width: 50%;" name="wps_wgm_include_per_product[]" data-placeholder="<?php esc_attr_e( 'Search for a product', 'giftware' ); ?>" data-action="woocommerce_json_search_products_and_variations" id="wps_wgm_include_per_product"> 
					<?php
					if ( isset( $wps_wgm_include_per_product ) && ! empty( $wps_wgm_include_per_product ) ) {
						foreach ( $wps_wgm_include_per_product as $pro_id ) {
							$product      = wc_get_product( $pro_id );
							$product_title = $product->get_formatted_name();
							echo '<option value="' . esc_attr( $pro_id ) . '" selected="selected">' . esc_html( $product_title ) . '</option>';
						}
					}
					?>
				</select>
			</p>
			<?php
		}
		?>
		<p class="form-field wps_wgm_include_per_category_field">
			<label class = "wps_wgm_include_per_category" for="wps_wgm_include_per_category"><?php esc_html_e( 'Include Category', 'giftware' ); ?></label>
			<select id="wps_wgm_include_per_category" multiple="multiple" name="wps_wgm_include_per_category[]">
				<?php
				$args = array( 'taxonomy' => 'product_cat' );
				$categories = get_terms( $args );
				if ( isset( $categories ) && ! empty( $categories ) ) {
					foreach ( $categories as $category ) {
						$catid = $category->term_id;
						$catname = $category->name;
						$catselect = '';
						if ( is_array( $wps_wgm_include_per_category ) && ! empty( $wps_wgm_include_per_category ) ) {
							if ( is_array( $wps_wgm_include_per_category[0] ) && in_array( $catid, $wps_wgm_include_per_category[0] ) ) {
								$catselect = "selected='selected'";
							}
						}
						?>
						<option value="<?php echo esc_attr( $catid ); ?>" <?php echo esc_html( $catselect ); ?>><?php echo esc_html( $catname ); ?></option>
						<?php
					}
				}
				?>
			</select>
		</p>
		<p class="form-field wps_wgm_recommanded_product_field">
				<label class = "wps_wgm_recommanded_per_product" for="wps_wgm_recommanded_per_product"><?php esc_html_e( 'Recommended Products', 'giftware' ); ?></label>
				<select class="wc-product-search" multiple="multiple" style="width: 50%;" name="wps_wgm_recommanded_per_product[]" data-placeholder="<?php esc_attr_e( 'Search for a product', 'giftware' ); ?>" data-action="woocommerce_json_search_products_and_variations" id="wps_wgm_recommanded_per_product"> 
					<?php
					if ( isset( $wps_wgm_recommanded_per_product ) && ! empty( $wps_wgm_recommanded_per_product ) ) {
						foreach ( $wps_wgm_recommanded_per_product as $pro_id ) {
							$product      = wc_get_product( $pro_id );
							$product_title = $product->get_formatted_name();
							echo '<option value="' . esc_attr( $pro_id ) . '" selected="selected">' . esc_html( $product_title ) . '</option>';
						}
					}
					?>
				</select>
			</p>
		<?php
		woocommerce_wp_checkbox(
			array(
				'id' => 'wps_wgm_overwrite',
				'class' => 'wps_wgm_overwrite',
				'label' => __( 'Overwrite Delivery', 'giftware' ),
				'name' => 'wps_wgm_overwrite',
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id' => 'wps_wgm_email_to_recipient',
				'class' => 'wps_wgm_email_to_recipient',
				'label' => __( 'Email To Recipient', 'giftware' ),
				'name' => 'wps_wgm_email_to_recipient',
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id' => 'wps_wgm_download',
				'class' => 'wps_wgm_download',
				'label' => __( 'Download', 'giftware' ),
				'name' => 'wps_wgm_download',
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id' => 'wps_wgm_shipping',
				'class' => 'wps_wgm_shipping',
				'label' => __( 'Shipping', 'giftware' ),
				'name' => 'wps_wgm_shipping',
			)
		);

		do_action( 'wps_uwgc_giftcard_product_field', $product_id );
	}

	/**
	 * Saves the all required details for each giftcard product
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_giftcard_product_type_save_fields()
	 * @param int $product_id Contains product_id .
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_giftcard_product_type_save_fields( $product_id ) {
		$wps_wgm_pricing = get_post_meta( $product_id, 'wps_wgm_pricing', true );
		if ( ! isset( $_POST['wps_wgm_product_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wps_wgm_product_nonce_field'] ) ), 'wps_wgm_lite_nonce' ) ) {
			return;
		}
		$is_overwrite = isset( $_POST['wps_wgm_overwrite'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_overwrite'] ) ) : '';
		update_post_meta( $product_id, 'wps_wgm_overwrite', $is_overwrite );

		if ( isset( $is_overwrite ) && ! empty( $is_overwrite ) ) {
			$wps_wgm_email_to_recipient = isset( $_POST['wps_wgm_email_to_recipient'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_email_to_recipient'] ) ) : '';

			$wps_wgm_shipping = isset( $_POST['wps_wgm_shipping'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_shipping'] ) ) : '';
			$wps_wgm_download = isset( $_POST['wps_wgm_download'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_download'] ) ) : '';

			if ( empty( $wps_wgm_email_to_recipient ) && empty( $wps_wgm_shipping ) && empty( $wps_wgm_download ) ) {
				$wps_wgm_email_to_recipient = 'yes';
			}

			update_post_meta( $product_id, 'wps_wgm_email_to_recipient', $wps_wgm_email_to_recipient );
			update_post_meta( $product_id, 'wps_wgm_download', $wps_wgm_download );
			update_post_meta( $product_id, 'wps_wgm_shipping', $wps_wgm_shipping );
		}
		$wps_uwgc_is_discount = isset( $_POST['wps_wgm_discount'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_discount'] ) ) : 'no';
		if ( isset( $wps_wgm_pricing['type'] ) ) {
			if ( 'wps_wgm_default_price' == $wps_wgm_pricing['type'] || 'wps_wgm_range_price' == $wps_wgm_pricing['type'] || 'wps_wgm_user_price' == $wps_wgm_pricing['type'] ) {
				update_post_meta( $product_id, 'wps_wgm_discount', $wps_uwgc_is_discount );
			} else {
				$wps_uwgc_is_discount = 'no';
				update_post_meta( $product_id, 'wps_wgm_discount', $wps_uwgc_is_discount );
			}
		}
		$wps_wgm_exclude_per_product = array();
		$wps_wgm_exclude_per_product = isset( $_POST['wps_wgm_exclude_per_product'] ) ? map_deep( wp_unslash( $_POST['wps_wgm_exclude_per_product'] ), 'sanitize_text_field' ) : '';

		if ( isset( $wps_wgm_exclude_per_product ) && ! empty( $wps_wgm_exclude_per_product ) ) {
			update_post_meta( $product_id, 'wps_wgm_exclude_per_product', $wps_wgm_exclude_per_product );
		} else {
			update_post_meta( $product_id, 'wps_wgm_exclude_per_product', $wps_wgm_exclude_per_product );
		}

		$wps_wgm_include_per_product = array();
		$wps_wgm_include_per_product = isset( $_POST['wps_wgm_include_per_product'] ) ? map_deep( wp_unslash( $_POST['wps_wgm_include_per_product'] ), 'sanitize_text_field' ) : '';
		if ( isset( $wps_wgm_include_per_product ) && ! empty( $wps_wgm_include_per_product ) ) {
			update_post_meta( $product_id, 'wps_wgm_include_per_product', $wps_wgm_include_per_product );
		} else {
			update_post_meta( $product_id, 'wps_wgm_include_per_product', $wps_wgm_include_per_product );
		}

		$wps_wgm_recommanded_per_product = array();
		$wps_wgm_recommanded_per_product = isset( $_POST['wps_wgm_recommanded_per_product'] ) ? map_deep( wp_unslash( $_POST['wps_wgm_recommanded_per_product'] ), 'sanitize_text_field' ) : '';
		if ( isset( $wps_wgm_recommanded_per_product ) && ! empty( $wps_wgm_recommanded_per_product ) ) {
			update_post_meta( $product_id, 'wps_wgm_recommanded_per_product', $wps_wgm_recommanded_per_product );
		} else {
			update_post_meta( $product_id, 'wps_wgm_recommanded_per_product', $wps_wgm_recommanded_per_product );
		}

		$wps_wgm_exclude_per_category = array();
		$wps_wgm_exclude_per_category = isset( $_POST['wps_wgm_exclude_per_category'] ) ? map_deep( wp_unslash( $_POST['wps_wgm_exclude_per_category'] ), 'sanitize_text_field' ) : array();
		if ( isset( $wps_wgm_exclude_per_category ) && ! empty( $wps_wgm_exclude_per_category ) ) {
			update_post_meta( $product_id, 'wps_wgm_exclude_per_category', $wps_wgm_exclude_per_category );
		} else {
			update_post_meta( $product_id, 'wps_wgm_exclude_per_category', $wps_wgm_exclude_per_category );
		}

		$wps_wgm_include_per_category = array();
		$wps_wgm_include_per_category = isset( $_POST['wps_wgm_include_per_category'] ) ? map_deep( wp_unslash( $_POST['wps_wgm_include_per_category'] ), 'sanitize_text_field' ) : array();
		if ( isset( $wps_wgm_include_per_category ) && ! empty( $wps_wgm_include_per_category ) ) {
			update_post_meta( $product_id, 'wps_wgm_include_per_category', $wps_wgm_include_per_category );
		} else {
			update_post_meta( $product_id, 'wps_wgm_include_per_category', $wps_wgm_include_per_category );
		}
		do_action( 'wps_uwgc_giftcard_product_field_save', $product_id );
	}

	/**
	 * This function is used to create Custmizable Giftcard.
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_show_customizable_dialog()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_show_customizable_dialog() {
		$response['result'] = false;
		$response['message'] = __( 'Failed to create Product!', 'giftware' );
		$pro_img_url = WPS_UWGC_URL . 'assets/images/customized_card.jpg';
		if ( ! empty( $pro_img_url ) ) {
			$filename = array( $pro_img_url );
			foreach ( $filename as $key => $value ) {
				$upload_file = wp_upload_bits( basename( $value ), null, file_get_contents( $value ) );
				if ( ! $upload_file['error'] ) {
					$filename = $upload_file['file'];
					// The ID of the post this attachment is for.

					$parent_post_id = 0;

					// Check the type of file. We'll use this as the 'post_mime_type'.
					$filetype = wp_check_filetype( basename( $filename ), null );

					// Get the path to the upload directory.
					$wp_upload_dir = wp_upload_dir();

					// Prepare an array of post data for the attachment.
					$attachment = array(
						'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
						'post_mime_type' => $filetype['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
						'post_status'    => 'inherit',
					);
					// Insert the attachment.
					$attach_id = wp_insert_attachment( $attachment, $filename, 0 );
					// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
					require_once( ABSPATH . 'wp-admin/includes/image.php' );

					// Generate the metadata for the attachment, and update the database record.
					$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

					wp_update_attachment_metadata( $attach_id, $attach_data );
				}
			}
		}
		$product_array = array(
			'post_title' => 'Customize Your Gift Card',
			'post_content' => '',
			'post_excerpt' => '',
			'post_status' => 'draft',
			'post_author' => get_current_user_id(),
			'post_type'     => 'product',
		);

		$product_id = wp_insert_post( $product_array );

		if ( isset( $product_id ) && ! empty( $product_id ) ) {

			$wps_wgm_pricing['type'] = 'wps_wgm_default_price';
			$wps_wgm_pricing['default_price'] = 35;
			wp_set_object_terms( $product_id, 'wgm_gift_card', 'product_type' );
			update_post_meta( $product_id, '_regular_price', 35 );
			update_post_meta( $product_id, '_price', 35 );
			update_post_meta( $product_id, 'wps_wgm_pricing', $wps_wgm_pricing );
			update_post_meta( $product_id, 'woocommerce_customizable_giftware', 'yes' );
			update_option( 'wcgc_product_created', true );
			if ( ! empty( $attach_id ) ) {
				set_post_thumbnail( $product_id, $attach_id );
			}
			$response['result'] = true;
			$response['message'] = __( 'Successfully Created!', 'giftware' );
			$redirection = "post.php?post={$product_id}&action=edit";
			$response['redirect_url'] = admin_url( $redirection );
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * This function is used to validate_license_handle.
	 *
	 * @since 1.0.0
	 * @name validate_license_handle()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function validate_license_handle() {

		// First check the nonce, if it fails the function will break.
		check_ajax_referer( 'wps_uwgc-nonce-action', 'wps_uwgc-license-nonce' );
		$wps_license_key = ! empty( $_POST['wps_uwgc_purchase_code'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_uwgc_purchase_code'] ) ) : '';

		if ( is_multisite() ) {
			$domain = site_url();
		} else {
			$domain = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '';
		}

		// API query parameters.
		$api_params = array(
			'slm_action' => 'slm_activate',
			'secret_key' => WPS_UWGC_SPECIAL_SECRET_KEY,
			'license_key' => $wps_license_key,
			'registered_domain' => $domain,
			'item_reference' => urlencode( WPS_UWGC_ITEM_REFERENCE ),
			'product_reference' => 'WPSPK-67566',
		);
		// Send query to the license manager server.
		$query = esc_url_raw( add_query_arg( $api_params, WPS_UWGC_SERVER_URL ) );

		$response = wp_remote_get(
			$query,
			array(
				'timeout'    => 20,
				'sslverify'  => false,
				'user-agent' => 'Ultimate Woocommerce Gift Cards/' . $this->version,
			)
		);

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( isset( $license_data->result ) && 'success' === $license_data->result ) {
			global $wpdb;
			if ( is_multisite() ) {
				// Get all blogs in the network and activate plugins on each one.
				$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					update_option( 'wps_gw_lcns_key', $wps_license_key );
					update_option( 'wps_gw_lcns_status', 'true' );
					restore_current_blog();
				}
			} else {
				update_option( 'wps_gw_lcns_key', $wps_license_key );
				update_option( 'wps_gw_lcns_status', 'true' );
			}

			echo json_encode(
				array(
					'status' => true,
					'msg' => __(
						'Successfully Verified...',
						'giftware'
					),
				)
			);
		} else {

			$error_message = ! empty( $license_data->message ) ? $license_data->message : __( 'License Verification Failed.', 'giftware' );

			echo json_encode(
				array(
					'status' => false,
					'msg' => $error_message,
				)
			);
		}
		wp_die();
	}

	/**
	 * Validate License daily.
	 * name     validate_license_daily
	 *
	 * @since   1.0.0
	 */
	public function validate_license_daily() {

		$wps_license_key = get_option( 'wps_gw_lcns_key', '' );

		if ( is_multisite() ) {
			$domain = site_url();
		} else {
			$domain = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '';
		}

		// API query parameters.
		$api_params = array(
			'slm_action' => 'slm_check',
			'secret_key' => WPS_UWGC_SPECIAL_SECRET_KEY,
			'license_key' => $wps_license_key,
			'registered_domain' => $domain,
			'item_reference' => urlencode( WPS_UWGC_ITEM_REFERENCE ),
			'product_reference' => 'WPSPK-67566',
		);

		$query = esc_url_raw( add_query_arg( $api_params, WPS_UWGC_SERVER_URL ) );

		$wps_response = wp_remote_get(
			$query,
			array(
				'timeout'    => 20,
				'sslverify'  => false,
				'user-agent' => 'Ultimate Woocommerce Gift Cards/' . $this->version,
			)
		);

		$license_data = json_decode( wp_remote_retrieve_body( $wps_response ) );
		if ( isset( $license_data->result ) && 'success' === $license_data->result && isset( $license_data->status ) && 'active' === $license_data->status ) {

			update_option( 'wps_gw_lcns_key', $wps_license_key );
			update_option( 'wps_gw_lcns_status', 'true' );
		} else {

			delete_option( 'wps_gw_lcns_key' );
			update_option( 'wps_gw_lcns_status', 'false' );
		}
	}

	/**
	 * This function is used to add meta box on order detail page
	 *
	 * @name wps_uwgc_order_edit_meta_box
	 * @param String $post_type Contains post type.
	 * @param object $post Contains Post .
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_order_edit_meta_box( $post_type, $post ) {

		$woo_ver = WC()->version;
		global $post;
		if ( isset( $post->ID ) && 'shop_order' == $post->post_type ) {
			$order_id = $post->ID;
			$order = new WC_Order( $order_id );
			$order_status = $order->get_status();

			if ( 'completed' == $order_status || 'processing' == $order_status ) {
				$giftcard = false;
				foreach ( $order->get_items() as $item_id => $item ) {
					if ( $woo_ver < '3.0.0' ) {
						$_product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
					} else {
						$_product = apply_filters( 'woocommerce_order_item_product', $item->get_product(), $item );
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
						}
					}
				}

				if ( $giftcard ) {
					add_meta_box( 'wps_uwgc_resend_mail', __( 'Resend Gift Card Mail', 'giftware' ), array( $this, 'wps_uwgc_resend_mail' ), 'shop_order' );

					add_meta_box( 'wps_uwgc_resend_coupon_add_more', __( 'Resend Gift Card by changing amount', 'giftware' ), array( $this, 'wps_uwgc_resend_coupon_add_more' ), 'shop_order' );

					add_meta_box( 'wps_uwgc_edit_email_address', __( 'Edit Email Address', 'giftware' ), array( $this, 'wps_uwgc_edit_email_address' ), 'shop_order' );
				}
			}
		}
	}
	/**
	 * This function is used to add resend email button on order detal page
	 *
	 * @name wps_uwgc_resend_mail
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_resend_mail() {
		global $post;
		if ( isset( $post->ID ) ) {
			$order_id = $post->ID;
			?>
			<div id="wps_wgm_loader" style="display: none;">
				<img src="<?php echo esc_url( WPS_UWGC_URL ); ?>assets/images/loading.gif">
			</div>
			<p><?php esc_html_e( 'If the user is not received a Gift Cards email then resend mail.', 'giftware' ); ?> </p>
			<p id="wps_uwgc_resend_mail_notification"></p>
			<input type="button" data-id="<?php echo esc_html( $order_id ); ?>" id="wps_uwgc_resend_mail_button" class="button button-primary" value="<?php esc_html_e( 'Resend Mail', 'giftware' ); ?>">
			<?php
		}
	}

	/**
	 * This is used to add html for adding more amount to coupon
	 *
	 * @name wps_uwgc_resend_coupon_add_more
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_resend_coupon_add_more() {
		global $post;
		$wps_admin_obj = new Woocommerce_Gift_Cards_Common_Function();

		$general_setting = get_option( 'wps_wgm_general_settings', array() );

		$selected_date = $wps_admin_obj->wps_wgm_get_template_data( $general_setting, 'wps_wgm_general_setting_enable_selected_format' );

		if ( isset( $post->ID ) ) {
			$order_id = $post->ID;
			$order = wc_get_order( $order_id );
			$select_coupon = array();
			$woo_ver = WC()->version;
			foreach ( $order->get_items() as $item_id => $item ) {
				if ( $woo_ver < '3.0.0' ) {
					$product = $order->get_product_from_item( $item );
					$product_title = $product->post->post_title;
					$product_id = $product->id;
				} else {
					$product = $item->get_product();
					$product_title = $product->get_name();
					$product_id = $product->get_id();
				}
				$giftcoupon = get_post_meta( $order_id, "$order_id#$item_id", true );
				if ( empty( $giftcoupon ) ) {
					$giftcoupon = get_post_meta( $order_id, "$order_id#$product_id", true );
				}
				if ( is_array( $giftcoupon ) && ! empty( $giftcoupon ) ) {
					foreach ( $giftcoupon as $key => $value ) {
						$coupon = new WC_Coupon( $value );
						$today = date_i18n( 'Y-m-d' );
						$today = strtotime( $today );
						if ( $woo_ver < '3.0.0' ) {
							$coupon_expiry = $coupon->expiry_date;
							if ( is_string( $coupon_expiry ) ) {
								if ( isset( $selected_date ) && null !== $selected_date && '' !== $selected_date ) {
									if ( 'd/m/Y' == $selected_date ) {
										$coupon_expiry = str_replace( '/', '-', $coupon_expiry );
									}
								}
								$coupon_expiry = strtotime( $coupon_expiry );
							}
							if ( null == $coupon_expiry || $today < $coupon_expiry ) {

								if ( isset( $coupon->usage_count ) && null == $coupon->usage_count && '' == $coupon->usage_count && $coupon->usage_count < 1 ) {
									$select_coupon[ $product_title . '#wps#' . $value . '#wps#' . $item_id ] = $product_title . '#wps#' . $value;
								}
							}
						} else {
							$coupon_expiry = $coupon->get_date_expires();

							if ( isset( $coupon_expiry ) && ! empty( $coupon_expiry ) ) {
								$coupon_expiry = date_format( $coupon_expiry, 'Y-m-d' );
								$coupon_expiry = strtotime( $coupon_expiry );
							}

							if ( null == $coupon_expiry || $today < $coupon_expiry ) {
								$usage_count = $coupon->get_usage_count();
								if ( isset( $usage_count ) && null == $usage_count && '' == $usage_count && $usage_count < 1 ) {
									$select_coupon[ $product_title . '#wps#' . $value . '#wps#' . $item_id ] = $product_title . '#wps#' . $value;
								}
							}
						}
					}
				}
			}
			if ( ! empty( $select_coupon ) ) {
				?>
				<div id="wps_wgm_loader" style="display: none;">
					<img src="<?php echo esc_url( WPS_UWGC_URL ); ?>assets/images/loading.gif">
				</div>
				<p><?php esc_html_e( 'You can resend the Gift Card Coupon by increasing its amount.', 'giftware' ); ?> </p>
				<p id="wps_uwgc_resend_coupon_amount_msg"></p>
				<table class="form-table">
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_uwgc_select_coupon_product"><?php esc_html_e( 'Select the product', 'giftware' ); ?>
						</label>
					</th>
					<td class="forminp forminp-text">
						<?php
						$attribute_description = __( 'Select the product coupon for changing the amount', 'giftware' );
						/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
						echo wp_kses_post( wc_help_tip( $attribute_description ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
						?>
						<select multiple="multiple" id="wps_uwgc_select_coupon_product" data-placeholder="<?php esc_html_e( 'Select Coupons', 'giftware' ); ?>" class="wps_uwgc_select_coupon_product wc-enhanced-select">
							<?php
							foreach ( $select_coupon as $key => $val ) {
								echo ( '<option value="' . esc_attr( $key ) . '">' . esc_attr( $val ) . '</option>' );
							}
							?>
											
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="wps_uwgc_inc_amount"><?php esc_html_e( 'Enter the price', 'giftware' ); ?>
					</label>
				</th>
				<td class="forminp forminp-text">
					<?php
					$attribute_description = __( 'Enter the new amount of the coupon.', 'giftware' );
					/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
					echo wp_kses_post( wc_help_tip( $attribute_description ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
					?>
					<input class="wc_input_price" style="" id="wps_uwgc_inc_amount" value="" placeholder="" type="text">
				</td>
			</tr>
			<tr valign="top">
				<td class="forminp forminp-text">
					<label for="wps_uwgc_inc_amount">
						<a href="javascript:void(0)" class="button" id="wps__uwgc_inc_money_coupon" data-id="<?php echo esc_html( $order_id ); ?>"><?php esc_html_e( 'Change amount and send mail', 'giftware' ); ?></a>
					</label>
				</td>
			</tr>							
		</table>
				<?php
			}
		}
	}
	/**
	 * This function is used for adding the HTML for providing another way to the Admin for editing the Email from backend, after the order has been placed successfully
	 *
	 * @name wps_uwgc_edit_email_address
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_edit_email_address() {
		global $post;
		if ( isset( $post->ID ) ) {
			$woo_ver = WC()->version;
			$order_id = $post->ID;
			$order = wc_get_order( $order_id );
			$order_items = $order->get_items();
			foreach ( $order_items as $item_id => $item ) {
				if ( $woo_ver < '3.0.0' ) {
					$product = $order->get_product_from_item( $item );
				} else {
					$product = $item->get_product();
				}
				if ( $woo_ver < '3.0.0' ) {
					if ( isset( $item['item_meta']['Delivery Method'] ) && ! empty( $item['item_meta']['Delivery Method'] ) ) {
						$delivery_method = $item['item_meta']['Delivery Method'][0];
					}
				} else {
					$item_meta_data = $item->get_meta_data();
					foreach ( $item_meta_data as $key => $value ) {
						if ( isset( $value->key ) && 'Delivery Method' == $value->key && ! empty( $value->value ) ) {
							$delivery_method = $value->value;
						}
					}
				}
			}
			if ( 'Mail to recipient' == $delivery_method ) {
				?>
				<div id="wps_wgm_loader" style="display: none;">
					<img src="<?php echo esc_url( WPS_UWGC_URL ); ?>assets/images/loading.gif">
				</div>
				<p><?php esc_html_e( 'Update recipient email address if the previous one was incorrect. Make sure to click the resend mail button once the email is successfully updated.', 'giftware' ); ?></p>
				<p id="wps_wgm_resend_confirmation_msg"></p>
				<table class="form-table">
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_uwgc_new_email"><?php esc_html_e( 'Enter the new Email', 'giftware' ); ?>
						</label>
					</th>
					<td class="forminp forminp-text">
						<input type="email" class="wps_uwgc_new_email" id="wps_uwgc_new_email">
					</td>
				</tr>
				<tr valign="top">
					<td class="forminp forminp-text">
						<label for="wps_uwgc_update_item_meta">
							<a href="javascript:void(0)" class="button button-primary" id="wps_uwgc_update_item_meta" data-id="<?php echo esc_html( $order_id ); ?>"><?php esc_html_e( 'Update Email', 'giftware' ); ?></a>
						</label>
					</td>
				</tr>							
			</table>
				<?php
			}
		}
	}

	/**
	 * This function is used for Resend Mail for giftcard on order edit page
	 *
	 * @name wps_uwgc_resend_mail_order_edit
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_resend_mail_order_edit() {
		check_ajax_referer( 'wps-wgm-verify-nonce', 'wps_nonce' );
		$this->wps_common_fun->wps_uwgc_resend_mail_common_function();
	}

	/**
	 * This function is used for Resend Mail for offline giftcard
	 *
	 * @name wps_uwgc_offline_resend_mail
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_offline_resend_mail() {
		check_ajax_referer( 'wps-wgm-verify-nonce', 'wps_nonce' );
		$response['result'] = false;
		$response['message'] = esc_html( 'Mail sending failed due to some issue. Please try again.', 'giftware' );
		global $wpdb;
		$offline_orderid = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';
		$table_name = $wpdb->prefix . 'offline_giftcard';
		$query = "SELECT * FROM $table_name WHERE `id`=$offline_orderid";
		$giftresults = $wpdb->get_results( $query, ARRAY_A );
		$general_settings = get_option( 'wps_wgm_general_settings', array() );
		$wps_obj = new Woocommerce_Gift_Cards_Common_Function();
		$giftcard_pdf_prefix = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_pdf_prefix' );
		$selected_date = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable_selected_format' );
		$other_settings = get_option( 'wps_wgm_other_settings', array() );
		$mail_settings = get_option( 'wps_wgm_mail_settings', array() );

		$senddatetime = '';
		if ( isset( $giftresults[0] ) ) {
			$giftresult = $giftresults[0];
			if ( isset( $giftresult['mail'] ) && null == $giftresult['mail'] && 1 !== $giftresult['mail'] ) {

				$schedule_date = $giftresult['schedule'];

				if ( is_string( $schedule_date ) ) {
					if ( isset( $selected_date ) && null !== $selected_date && '' !== $selected_date ) {
						if ( 'd/m/Y' == $selected_date ) {
							$schedule_date = str_replace( '/', '-', $schedule_date );
						}
					}
					$senddatetime = strtotime( $schedule_date );
				}
				$senddate = date_i18n( 'Y-m-d', $senddatetime );
				$todaytime = time();
				$todaydate = date_i18n( 'Y-m-d', $todaytime );
				$senddatetime = strtotime( "$senddate" );
				$todaytime = strtotime( "$todaydate" );
				$giftdiff = $senddatetime - $todaytime;
				if ( $giftdiff > 0 ) {
					$response['result'] = false;
					$response['message'] = __( 'Mail does not send as the scheduled date is not reached.', 'giftware' );
					echo json_encode( $response );
					wp_die();
				} else {
					$couponcreated = $this->wps_common_fun->wps_uwgc_create_offline_gift_coupon( $giftresult['coupon'], $giftresult['amount'], $offline_orderid, $giftresult['template'], $giftresult['to'] );
				}
			}
			$woo_ver = WC()->version;
			$product_id = $giftresult['template'];
			$wps_wgm_pricing = get_post_meta( $product_id, 'wps_wgm_pricing', true );
			$templateid = $wps_wgm_pricing['template'];
			if ( is_array( $templateid ) && array_key_exists( 0, $templateid ) ) {
				$temp = $templateid[0];
			} else {
				$temp = $templateid;
			}

			$args['from'] = $giftresult['from'];
			$args['to'] = $giftresult['to'];
			$args['message'] = stripcslashes( $giftresult['message'] );
			$args['coupon'] = apply_filters( 'wps_wgm_qrcode_coupon', $giftresult['coupon'] );
			$to = $args['to'];
			$from = $args['from'];
			$couponcode = $giftresult['coupon'];
			$coupon = new WC_Coupon( $couponcode );

			if ( $woo_ver < '3.0.0' ) {
				$coupon_id = $coupon->id;

			} else {
				$coupon_id = $coupon->get_id();
			}
			if ( isset( $coupon_id ) ) {
				if ( $woo_ver < '3.0.0' ) {
					$expirydate = $coupon->expiry_date;
					if ( is_string( $expirydate ) ) {
						if ( isset( $selected_date ) && null !== $selected_date && '' !== $selected_date ) {
							if ( 'd/m/Y' == $selected_date ) {
								$expirydate = str_replace( '/', '-', $expirydate );
							}
						}

						$expirydate = strtotime( $expirydate );
					}
				} else {
					$expirydate = $coupon->get_date_expires();
					$expirydate = date_format( $expirydate, 'Y-m-d' );
					$expirydate = strtotime( $expirydate );
				}

				if ( empty( $expirydate ) ) {
					$expirydate_format = __( 'No Expiration', 'giftware' );
				} else {
					$selected_date = $wps_obj->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable_selected_format' );

					if ( isset( $selected_date ) && null !== $selected_date && '' !== $selected_date ) {
						$selected_date = $this->wps_common_fun->wps_uwgc_selected_date_format( $selected_date );
						$expirydate_format = date_i18n( $selected_date, $expirydate );
					} else {
						$expirydate_format = date_format( $expirydate_format, 'jS M Y' );
					}
				}
				$args['expirydate'] = $expirydate_format;
				$args['amount'] = wc_price( $giftresult['amount'] );
				$args['templateid'] = $temp;
				$args['product_id'] = $product_id;
				$args['send_date']  = $giftresult['schedule'];

				$message = $wps_obj->wps_wgm_create_gift_template( $args );
				$wps_uwgc_pdf_enable = $wps_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_addition_pdf_enable' );
				if ( isset( $wps_uwgc_pdf_enable ) && 'on' == $wps_uwgc_pdf_enable ) {
					$site_name = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '';
					$time = time();
					$this->wps_common_fun->wps_uwgc_attached_pdf( $message, $site_name, $time, '', $couponcode );
					if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
						$attachments = array( wp_upload_dir()['basedir'] . '/giftcard_pdf/' . $giftcard_pdf_prefix . $couponcode . '.pdf' );
					} else {
						$attachments = array( wp_upload_dir()['basedir'] . '/giftcard_pdf/giftcard' . $time . $site_name . '.pdf' );
					}
				} else {
					$attachments = array();
				}
				$subject = $wps_obj->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_giftcard_subject' );
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
				$wps_wgc_bcc_enable = $wps_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_addition_bcc_option_enable' );
				if ( isset( $wps_wgc_bcc_enable ) && 'on' == $wps_wgc_bcc_enable ) {
					$headers[] = 'Bcc:' . $from;
					wc_mail( $to, $subject, $message, $headers, $attachments );
					if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
						unlink( wp_upload_dir()['basedir'] . '/giftcard_pdf/' . $giftcard_pdf_prefix . $couponcode . '.pdf' );
					} elseif ( isset( $time ) && isset( $site_name ) && ! empty( $time ) && ! empty( $site_name ) ) {
						unlink( wp_upload_dir()['basedir'] . '/giftcard_pdf/giftcard' . $time . $site_name . '.pdf' );
					}
				} else {
					$headers = array( 'Content-Type: text/html; charset=UTF-8' );
					wc_mail( $to, $subject, $message, $headers, $attachments );
					if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
						unlink( wp_upload_dir()['basedir'] . '/giftcard_pdf/' . $giftcard_pdf_prefix . $couponcode . '.pdf' );
					} elseif ( isset( $time ) && isset( $site_name ) && ! empty( $time ) && ! empty( $site_name ) ) {
						unlink( wp_upload_dir()['basedir'] . '/giftcard_pdf/giftcard' . $time . $site_name . '.pdf' );
					}
				}

				$subject = $wps_obj->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_receive_subject' );
				$subject = str_replace( '[TO]', $to, $subject );
				$message = $wps_obj->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_receive_message' );
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

				$wps_wgm_disable_buyer_notification = $wps_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_disable_buyer_notification' );
				if ( 'on' !== $wps_wgm_disable_buyer_notification ) {
					wc_mail( $from, $subject, $message );
				}
				$data_to_update = array( 'mail' => 1 );
				$where = array( 'id' => $offline_orderid );
				$update_data = $wpdb->update( $table_name, $data_to_update, $where );
				$response['result'] = true;
				$response['message'] = __( 'Mail Sent Successfully.', 'giftware' );
			}
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * This function is used for Generate pdf new ways
	 *
	 * @name wps_uwgc_new_way_for_generating_pdfs
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_new_way_for_generating_pdfs() {
		if ( isset( $_POST['wps_uwgc_new_way_for_pdf'] ) && 'yes' == $_POST['wps_uwgc_new_way_for_pdf'] ) {
			$site_name = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '';
			$check = file_put_contents( WPS_UWGC_DIRPATH . 'wkhtmltox.zip', fopen( 'https://wpswings.com/gift-card-pdf/download.php?download_file=wkhtmltox.zip&domain=' . $site_name, 'r' ) );
			if ( 0 !== $check ) {
				$response['result'] = true;
				$response['message'] = __( 'Process completed successfully!!', 'giftware' );
			} else {
				$response['result'] = false;
				$response['message'] = __( 'Fail due to some error, Please Try once and if it happens again and again then please contact to our Support', 'giftware' );
			}
		} else {
			$response['result'] = false;
			$response['message'] = __( 'Fail due to some error, Please Try once and if it happens again and again then please contact to our Support', 'giftware' );
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * This function is used for Generate next step for pdf.
	 *
	 * @name wps_uwgc_next_step_for_generating_pdfs
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_next_step_for_generating_pdfs() {
		if ( isset( $_POST['wps_uwgc_next_step_for_pdf'] ) && 'yes' == $_POST['wps_uwgc_next_step_for_pdf'] ) {
			$wps_uwgc_zip = new ZipArchive();
			$result = $wps_uwgc_zip->open( WPS_UWGC_DIRPATH . 'wkhtmltox.zip' );
			if ( 'true' == $result ) {
				$wps_uwgc_zip->extractTo( WPS_UWGC_DIRPATH );
				$wps_wgc_file = chmod( WPS_UWGC_DIRPATH . 'wkhtmltox/bin/wkhtmltopdf', 0777 );
				update_option( 'wps_wgm_next_step_for_pdf_value', 'yes' );
				$response['result'] = true;
				$response['message'] = __( 'Process completed!!', 'giftware' );
			} else {
				$response['result'] = false;
				$response['message'] = __( 'Fail due to some error, Please Try once and if it happens again and again then please contact to our Support!', 'giftware' );
			}
		} else {
			$response['result'] = false;
			$response['message'] = __( 'Fail due to some error, Please Try once and if it happens again and again then please contact to our Support', 'giftware' );
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * This function is used for resend change coupon amount on order edit page.
	 *
	 * @name wps_uwgc_resend_coupon_amount
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_resend_coupon_amount() {
		check_ajax_referer( 'wps-wgm-verify-nonce', 'wps_nonce' );
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
			$order_id = isset( $_POST['order_id'] ) ? sanitize_text_field( wp_unslash( $_POST['order_id'] ) ) : '';
			$coupon_arr = isset( $_POST['selectedcoupon'] ) ? map_deep( wp_unslash( $_POST['selectedcoupon'] ), 'sanitize_text_field' ) : '';
			$new_price = isset( $_POST['selectedprice'] ) ? sanitize_text_field( wp_unslash( $_POST['selectedprice'] ) ) : '';
			foreach ( $coupon_arr as $key => $value ) {

				$coupon_arr_detail = explode( '#wps#', $value );
				$coupon_details = new WC_Coupon( $coupon_arr_detail[1] );
				if ( $woo_ver < '3.0.0' ) {
					$coupon_id = $coupon_details->id;
				} else {
					$coupon_id = $coupon_details->get_id();
				}
				update_post_meta( $coupon_id, 'coupon_amount', $new_price );
				$order = new WC_Order( $order_id );
				$order_items = $order->get_items();

				foreach ( $order_items as $item_id => $item ) {
					if ( $coupon_arr_detail[2] == $item_id ) {
						$mailsend = false;
						$woo_ver = WC()->version;
						$gift_img_name = '';
						if ( $woo_ver < '3.0.0' ) {
							$product = $order->get_product_from_item( $item );
							if ( isset( $item['item_meta']['To'] ) && ! empty( $item['item_meta']['To'] ) ) {
								$mailsend = true;
								$to = $item['item_meta']['To'][0];
							}
							if ( isset( $item['item_meta']['To Name'] ) && ! empty( $item['item_meta']['To Name'] ) ) {
								$mailsend = true;
								$to_name = $item['item_meta']['To Name'][0];
							}
							if ( isset( $item['item_meta']['From'] ) && ! empty( $item['item_meta']['From'] ) ) {
								$mailsend = true;
								$from = $item['item_meta']['From'][0];
							}
							if ( isset( $item['item_meta']['Image'] ) && ! empty( $item['item_meta']['Image'] ) ) {
								$mailsend = true;
								$gift_img_name = $item['item_meta']['Image'][0];
							}
							if ( isset( $item['item_meta']['Message'] ) && ! empty( $item['item_meta']['Message'] ) ) {
								$mailsend = true;
								$gift_msg = $item['item_meta']['Message'][0];
							}
							if ( isset( $item['item_meta']['Delivery Method'] ) && ! empty( $item['item_meta']['Delivery Method'] ) ) {
								$mailsend = true;
								$delivery_method = $item['item_meta']['Delivery Method'][0];
							}
							if ( isset( $item['item_meta']['Selected Template'] ) && ! empty( $item['item_meta']['Selected Template'] ) ) {
								$mailsend = true;
								$selected_template = $item['item_meta']['Selected Template'][0];
							}
							if ( ! isset( $to ) && empty( $to ) ) {
								if ( 'Mail to recipient' == $delivery_method ) {
									$to = $order->billing_email();
								} else {
									$to = '';
								}
							}
						} else {

							$product = $item->get_product();
							$item_meta_data = $item->get_meta_data();
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
								if ( isset( $value->key ) && 'Image' == $value->key && ! empty( $value->value ) ) {
									$mailsend = true;
									$gift_img_name = $value->value;
								}
								if ( isset( $value->key ) && 'Message' == $value->key && ! empty( $value->value ) ) {
									$mailsend = true;
									$gift_msg = $value->value;
								}
								if ( isset( $value->key ) && 'Send Date' == $value->key && ! empty( $value->value ) ) {
									$mailsend = true;
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
							}
							if ( ! isset( $to ) && empty( $to ) ) {
								if ( 'Mail to recipient' == $delivery_method ) {
									$to = $order->get_billing_email();
								} else {
									$to = '';
								}
							}
						}
						if ( $mailsend ) {
							$gift_order = true;
							if ( $woo_ver < '3.0.0' ) {
								$product_id = $product->id;
							} else {
								$product_id = $product->get_id();
							}
							$gift_couponnumber = get_post_meta( $order_id, "$order_id#$item_id", true );
							if ( empty( $gift_couponnumber ) ) {
								$gift_couponnumber = get_post_meta( $order_id, "$order_id#$product_id", true );
							}
							foreach ( $gift_couponnumber as $coupon_key => $coupon_val ) {
								$the_coupon = new WC_Coupon( $coupon_val );
								if ( $woo_ver < '3.0.0' ) {
									$expiry_date_timestamp = $the_coupon->expiry_date;
									$couponamont = $the_coupon->coupon_amount;
								} else {
									$expiry_date_timestamp = $the_coupon->get_date_expires();
									if ( isset( $expiry_date_timestamp ) && ! empty( $expiry_date_timestamp ) ) {

										$expiry_date_timestamp = date_format( $expiry_date_timestamp, 'Y-m-d' );
										$expiry_date_timestamp = strtotime( $expiry_date_timestamp );
									}
									$couponamont = $the_coupon->get_amount();
								}
								if ( empty( $expiry_date_timestamp ) ) {
									$expirydate_format = __( 'No Expiration', 'giftware' );
								} else {
									$expirydate = date_i18n( 'Y-m-d', $expiry_date_timestamp );

									$expirydate_format = date_create( $expirydate );

									if ( isset( $selected_date ) && null !== $selected_date && '' !== $selected_date ) {
										$selected_date = $this->wps_common_fun->wps_uwgc_selected_date_format( $selected_date );
										$expirydate_format = date_i18n( $selected_date, $expiry_date_timestamp );

									} else {
										$expirydate_format = date_format( $expirydate_format, 'jS M Y' );
									}
								}
								if ( $woo_ver < '3.0.0' ) {
									$wps_wgm_pricing = get_post_meta( $product->id, 'wps_wgm_pricing', true );
								} else {
									$wps_wgm_pricing = get_post_meta( $product->get_id(), 'wps_wgm_pricing', true );
								}
								$templateid = $wps_wgm_pricing['template'];
								if ( is_array( $templateid ) && array_key_exists( 0, $templateid ) ) {
									$temp = $templateid[0];
								} else {
									$temp = $templateid;
								}
								$currenttime = time();
								$args['from'] = $from;
								$args['to'] = isset( $to_name ) ? $to_name : $to;
								$args['message'] = stripcslashes( $gift_msg );
								$args['coupon'] = apply_filters( 'wps_wgm_qrcode_coupon', $coupon_val );
								$args['expirydate'] = $expirydate_format;
								$args['amount'] = wc_price( $couponamont );
								$args['templateid'] = isset( $selected_template ) && ! empty( $selected_template ) ? $selected_template : $temp;
								$args['product_id'] = $product_id;
								$args['send_date']  = $gift_date;

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
									$this->wps_common_fun->wps_uwgc_attached_pdf( $message, $site_name, $time, $order_id, $coupon_val );
									if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
										$attachments = array( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/' . $giftcard_pdf_prefix . $coupon_val . '.pdf' );
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
										if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
											unlink( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/' . $giftcard_pdf_prefix . $coupon_val . '.pdf' );
										} elseif ( isset( $time ) && isset( $site_name ) && ! empty( $time ) && ! empty( $site_name ) ) {
											unlink( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/giftcard' . $time . $site_name . '.pdf' );
										}
									} else {
										$headers = array( 'Content-Type: text/html; charset=UTF-8' );
										wc_mail( $to, $subject, $message, $headers, $attachments );
										if ( isset( $giftcard_pdf_prefix ) && ! empty( $giftcard_pdf_prefix ) ) {
											unlink( WPS_UWGC_UPLOAD_DIR . '/giftcard_pdf/' . $giftcard_pdf_prefix . $coupon_val . '.pdf' );
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
								$response['message'] = __( 'Coupon amount is changed and Mail is Successfully Send.', 'giftware' );
								break;
							}
						}
					}
				}
			}
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * This function is used for updating the Order_Item Meta for sending the gift card to the updated email id.
	 *
	 * @name wps_uwgc_update_item_meta_with_new_email
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_update_item_meta_with_new_email() {
		check_ajax_referer( 'wps-wgm-verify-nonce', 'wps_nonce' );
		$response['result'] = false;
		$response['message'] = __( 'Mail sending failed due to some issue. Please try again.', 'giftware' );
		$woo_ver = WC()->version;
		if ( isset( $_POST['order_id'] ) && ! empty( $_POST['order_id'] ) && isset( $_POST['new_email_id'] ) && ! empty( $_POST['new_email_id'] ) ) {
			$correct_email_format = isset( $_POST['correct_email_format'] ) ? sanitize_text_field( wp_unslash( $_POST['correct_email_format'] ) ) : '';
			if ( 'true' == $correct_email_format ) {
				$order_id = sanitize_text_field( wp_unslash( $_POST['order_id'] ) );
				$new_email_id = sanitize_text_field( wp_unslash( $_POST['new_email_id'] ) );
				$order = wc_get_order( $order_id );
				$order_items = $order->get_items();
				foreach ( $order_items as $item_id => $item ) {
					$product = $order->get_product_from_item( $item );
					$product_id = $product->get_id();
					wc_update_order_item_meta( $item_id, 'To', $new_email_id );

					// Update the recipient email for "Check the Balance of Gift Card".
					$giftcoupon = get_post_meta( $order_id, "$order_id#$item_id", true );
					if ( is_array( $giftcoupon ) && ! empty( $giftcoupon ) ) {
						foreach ( $giftcoupon as $key => $value ) {
							$the_coupon = new WC_Coupon( $value );
							$coupon_id = $the_coupon->get_id();
							update_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_mail_to', $new_email_id );
						}
					}
				}
				$response['result'] = true;
				$response['message'] = __( 'Email Id has been updated, now you may Resend your Email', 'giftware' );
			} else {
				$response['result'] = false;
				$response['message'] = __( 'Enter a valid Email Id', 'giftware' );
			}
		} else {
			$response['result'] = false;
			$response['message'] = __( 'Email field should not be empty', 'giftware' );
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * This function is used for display qrcode/barcode Image on giftcard template
	 *
	 * @name wps_uwgc_qrcode_image
	 * @param Object $coupon Contains coupon object.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_qrcode_image( $coupon ) {
		$wps_admin_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_wgm_qrcode_settings = get_option( 'wps_wgm_qrcode_settings', array() );
		$wps_uwgc_qrcode = $wps_admin_obj->wps_wgm_get_template_data( $wps_wgm_qrcode_settings, 'wps_wgm_qrcode_enable' );
		if ( isset( $wps_uwgc_qrcode ) && 'qrcode' == $wps_uwgc_qrcode ) {
			$coupon = '<img src="' . WPS_UWGC_URL . 'assets/images/wps_qrcode.png">';

		}
		if ( isset( $wps_uwgc_qrcode ) && 'barcode' == $wps_uwgc_qrcode ) {
			$coupon = '<img src="' . WPS_UWGC_URL . 'assets/images/wps_barcode.png">';
		}
		return $coupon;
	}

	/**
	 * This function is used to show inventory tab in giftcard edit page.
	 *
	 * @name wps_uwgc_add_inventory_tab
	 * @param array $tabs Contains Tabs.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_add_inventory_tab( $tabs ) {
		if ( isset( $tabs ) && ! empty( $tabs ) ) {
			foreach ( $tabs as $key => $tab ) {
				if ( 'inventory' == $key ) {
					if ( isset( $tabs[ $key ]['class'] ) && in_array( 'hide_if_wgm_gift_card', $tabs[ $key ]['class'] ) ) {
						$index = array_search( 'hide_if_wgm_gift_card', $tabs[ $key ]['class'] );
						unset( $tabs[ $key ]['class'][ $index ] );
						array_push( $tabs[ $key ]['class'], 'show_if_wgm_gift_card' );
					}
				}
			}
		}
		return $tabs;
	}

	/**
	 * This function is used to Add custom css on Email Template.
	 *
	 * @name wps_uwgc_custom_template_css
	 * @param Strings $template_css Contains template css .
	 * @param int     $template_id Contains template id.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_custom_template_css( $template_css, $template_id ) {
		if ( isset( $template_id ) && ! empty( $template_id ) ) {
			$custom_css = get_post_meta( $template_id, 'wps_css_field', true );
			if ( isset( $custom_css ) && ! empty( $custom_css ) ) {
				$template_css = $custom_css;
			}
		}
		return $template_css;
	}

	/**
	 * This function is used to add the manual increment option inside the Coupon Section.
	 *
	 * @name wps_uwgc_manual_increment_usage_count
	 * @param int    $coupon_id Contains coupon id.
	 * @param Object $coupon Contains coupon object.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_manual_increment_usage_count( $coupon_id, $coupon ) {

		$wps_admin_obj = new Woocommerce_Gift_Cards_Common_Function();
		$wps_uwgc_other_settings = get_option( 'wps_wgm_other_settings', array() );
		$wps_uwgc_manual_inc = $wps_admin_obj->wps_wgm_get_template_data( $wps_uwgc_other_settings, 'wps_wgm_manually_increment_usage' );

		if ( isset( $wps_uwgc_manual_inc ) && 'on' == $wps_uwgc_manual_inc ) {

			woocommerce_wp_text_input(
				array(
					'id'                => 'manually_increment_usage',
					'label'             => __( 'Manually Increment Usage', 'giftware' ),
					'placeholder'       => esc_attr__( 'Increment Usage', 'giftware' ),
					'description'       => __( 'Number of times coupon has been used', 'giftware' ),
					'type'              => 'number',
					'desc_tip'          => true,
					'class'             => 'short',
					'custom_attributes' => array(
						'step'  => 1,
						'min'   => 0,
					),
					'value' => $coupon->get_usage_count() ? $coupon->get_usage_count() : 0,
				)
			);
		}
		wp_nonce_field( 'wps_uwgc_nonce_on_increment', 'manual_increment_nonce' );
	}

	/**
	 * This function is used to add/update the usage count (manual increment) manually.
	 *
	 * @name wps_uwgc_save_coupon_manual_usage_count
	 * @param int $coupon_id Contains coupon id.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_save_coupon_manual_usage_count( $coupon_id ) {
		if ( ! isset( $_POST['manual_increment_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['manual_increment_nonce'] ) ), 'wps_uwgc_nonce_on_increment' ) ) {
			return;
		}
		if ( isset( $_POST['manually_increment_usage'] ) && ! empty( $_POST['manually_increment_usage'] ) ) {
			$wps_uwgc_manual_value = sanitize_text_field( wp_unslash( $_POST['manually_increment_usage'] ) );
			update_post_meta( $coupon_id, 'usage_count', $wps_uwgc_manual_value );
		}
	}

	/**
	 * This function is used for adding the  dropdown for filterization for Offline,Online, and Imported Coupons
	 *
	 * @name wps_uwgc_manage_coupon_type
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_manage_coupon_type() {
		global $typenow;
		global $post;
		if ( 'shop_coupon' == $typenow ) {
			$wps_uwgc_online_giftcards = false;
			$wps_uwgc_offline_giftcards = false;
			$wps_uwgc_imported_coupon = false;
			if ( isset( $_GET['wps_uwgc_coupon_type'] ) ) {
				if ( 'online' == $_GET['wps_uwgc_coupon_type'] ) {
					$wps_uwgc_online_giftcards = true;
				} elseif ( 'offline' == $_GET['wps_uwgc_coupon_type'] ) {
					$wps_uwgc_offline_giftcards = true;
				} elseif ( 'importedcoupon' == $_GET['wps_uwgc_coupon_type'] ) {
					$wps_uwgc_imported_coupon = true;
				}
			}
			?>
				
			 <select name="wps_uwgc_coupon_type" id="wps_uwgc_dropdown_shop_coupon_type">
				<?php
				$online_selected = '';
				$offline_selected = '';
				$imported_selected = '';
				if ( $wps_uwgc_online_giftcards ) {
					$online_selected = " selected='selected'";
				} elseif ( $wps_uwgc_offline_giftcards ) {
					$offline_selected = " selected='selected'";
				} elseif ( $wps_uwgc_imported_coupon ) {
					$imported_selected = " selected='selected'";
				}
				?>
				 <option><?php esc_html_e( 'Select Gift Cards', 'giftware' ); ?></option>
				 <option value="online" <?php echo esc_attr( $online_selected ); ?> ><?php esc_html_e( 'Online Gift Cards', 'giftware' ); ?></option>
				 <option value="offline" <?php echo esc_attr( $offline_selected ); ?> ><?php esc_html_e( 'Offline Gift Cards', 'giftware' ); ?></option>
				 <option value="importedcoupon" <?php echo esc_attr( $imported_selected ); ?> ><?php esc_html_e( 'Imported Gift Coupons', 'giftware' ); ?></option>
			 </select>
			<?php
		}
	}

	/**
	 * This function is used for handle the requested query and return the result for ONline, Off and Imported Coupons.
	 *
	 * @name wps_uwgc_request_coupon_type
	 * @param array $vars Conains the array.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_request_coupon_type( $vars ) {
		global $typenow;
		if ( 'shop_coupon' === $typenow ) {

			if ( ! empty( $_GET['wps_uwgc_coupon_type'] ) && ( 'online' == $_GET['wps_uwgc_coupon_type'] || 'offline' == $_GET['wps_uwgc_coupon_type'] ) ) {
				$vars['meta_key']   = 'wps_wgm_giftcard_coupon_unique';
				$vars['meta_value'] = wc_clean( sanitize_text_field( wp_unslash( $_GET['wps_uwgc_coupon_type'] ) ) );
			}
			if ( ! empty( $_GET['wps_uwgc_coupon_type'] ) && 'importedcoupon' == $_GET['wps_uwgc_coupon_type'] ) {
				$vars['meta_key']   = 'wps_wgm_imported_coupon';
				$vars['meta_value'] = 'yes';
			}
		}
		return $vars;
	}

	/**
	 * This function is used for hide non required data in order item.
	 *
	 * @name wps_uwgc_giftcard_hidden_order_itemmeta
	 * @param array $order_items Conains order_items.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_giftcard_hidden_order_itemmeta( $order_items ) {
		array_push( $order_items, 'Image', 'To Name', 'Choosen Image' );
		return $order_items;
	}

	/**
	 * This function is to add meta field like field for instruction how to use shortcode in email template.
	 *
	 * @name wps_uwgc_template_shortcode
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_template_shortcode() {
		?>
		<tr>
			<td>[DISCLAIMER]</td>
			<td><?php esc_html_e( 'Replace with Disclaimer on Gift Card.', 'giftware' ); ?></td>
		</tr>
		<tr>
			<td>[FEATUREDIMAGE]</td>
			<td><?php esc_html_e( 'Replace with Featured Image on Gift Card.', 'giftware' ); ?></td>
		</tr>
		<tr>
			<td>[PRODUCTNAME]</td>
			<td><?php esc_html_e( 'Replaced with Product Name having the link also', 'giftware' ); ?></td>
		</tr>
		<tr>
			<td>[ORDERID]</td>
			<td><?php esc_html_e( 'Replaced with Order ID', 'giftware' ); ?></td>
		</tr>
		<tr>
			<td>[SHORTDESCRIPTION]</td>
			<td><?php esc_html_e( 'Replaced with Product Short Description', 'giftware' ); ?></td>
		</tr>
		<tr>
			<td>[COUPONURL]</td>
			<td><?php esc_html_e( 'Replaced with Coupon URL( Coupon will apply to cart )', 'giftware' ); ?></td>
		</tr>
		<tr>
			<td>[SCHEDULEDATE]</td>
			<td><?php esc_html_e( 'Replaced with Schedule Date.', 'giftware' ); ?></td>
		</tr>
		<tr>
			<td>[RECOMMENDEDPRODUCT]</td>
			<td><?php esc_html_e( 'Replaced with added recommended product with giftcard.', 'giftware' ); ?></td>
		</tr>
		<tr>
			<td>[DELIVERYMETHOD]</td>
			<td><?php esc_html_e( 'Replaced with giftcard delivery method.', 'giftware' ); ?></td>
		</tr>
		<?php
	}

	/**
	 * Function is used to add admin toolbar for giftcard reporting.
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_admin_toolbar()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_admin_toolbar() {
		global $wp_admin_bar;
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$args = array(
			'id'     => 'gift-cards',
			'title'  => __( 'GC Reports', 'giftware' ),
			'href'   => admin_url( 'admin.php?page=wc-reports&tab=giftcard_report' ),
		);

		$wp_admin_bar->add_menu( $args );
	}

	/**
	 * Function is used to add Gift Card Report Section.
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_report().
	 * @param array $reports Array of Reports.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_report( $reports ) {
		$reports['giftcard_report'] = array(
			'title'   => __( 'Gift Cards', 'giftware' ),
			'reports' => array(
				'giftcard_report' => array(
					'title'       => __( 'Gift Cards', 'giftware' ),
					'description' => '',
					'hide_title'  => true,
					'callback'    => array( __CLASS__, 'wps_uwgc_giftcard_report' ),
				),
			),
		);
		return $reports;
	}

	/**
	 * Function is used to include report template.
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_giftcard_report()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public static function wps_uwgc_giftcard_report() {
		include_once WPS_UWGC_DIRPATH . '/admin/partials/class-wps-uwgc-giftcard-report-list.php';
	}

	/**
	 * Function to show giftcard details on ajax call.
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_gift_card_details()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_gift_card_details() {
		check_ajax_referer( 'wps-uwgc-giftcard-report-nonce', 'wps_uwgc_nonce' );
		$_POST['wps_uwgc_report_details'] = 'wps_uwgc_report_details';
		$_POST['width'] = '650';
		$_POST['height'] = '480';
		$_POST['TB_iframe'] = true;
		$query = http_build_query( $_POST );
		$ajax_url = home_url( "?$query" );
		echo wp_kses_post( $ajax_url );
		wp_die();
	}

	/**
	 * Function is used to preview report deatils.
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_preview_report_details()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_preview_report_details() {
		if ( isset( $_GET['wps_uwgc_report_details'] ) && 'wps_uwgc_report_details' == $_GET['wps_uwgc_report_details'] ) {
			$order_id = isset( $_GET['order_id'] ) ? sanitize_text_field( wp_unslash( $_GET['order_id'] ) ) : '';
			$coupon_id = isset( $_GET['coupon_id'] ) ? sanitize_text_field( wp_unslash( $_GET['coupon_id'] ) ) : '';

			if ( '' !== $order_id && '' !== $coupon_id ) {
				$order_date = '';
				$remaining_amt = '';
				$to = '';
				$from = '';
				$msg = '';
				$gift_date = '';
				$productname = '';
				$pro_permalink = '';
				$giftcard_amount = get_post_meta( $coupon_id, 'wps_wgm_coupon_amount', true );
				$remaining_amt = get_post_meta( $coupon_id, 'coupon_amount', true );
				$order = wc_get_order( $order_id );
				$order_date = $order->get_date_created()->date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
				$product_id = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_product_id', true );
				if ( '' !== $product_id ) {
					$product = wc_get_product( $product_id );
					if ( isset( $product ) && ! empty( $product ) ) {
						$pro_permalink = $product->get_permalink();
						$productname = get_the_title( $product_id );
					}
				}
				foreach ( $order->get_items() as $item ) {
					$item_meta_data = $item->get_meta_data();
					foreach ( $item_meta_data as $key => $value ) {
						if ( isset( $value->key ) && 'To' == $value->key && ! empty( $value->value ) ) {
							$to = $value->value;
						}
						if ( isset( $value->key ) && 'From' == $value->key && ! empty( $value->value ) ) {
							$from = $value->value;
						}
						if ( isset( $value->key ) && 'Message' == $value->key && ! empty( $value->value ) ) {
							$msg = $value->value;
						}
						if ( isset( $value->key ) && 'Send Date' == $value->key && ! empty( $value->value ) ) {
							$gift_date = $value->value;
						}
					}
				}
				?>
					<div class="wps_uwgc_report_preview">
						<h3 style="text-align:;"><?php esc_html_e( 'Gift Card Details', 'giftware' ); ?></h3>

						<table>
							<tr>
								<td><b><?php esc_html_e( 'Purchased Date :', 'giftware' ); ?></b></td>
								<td><?php echo esc_html( $order_date ); ?></td>
							</tr>
							<tr>
								<?php
								if ( isset( $giftcard_amount ) && ! empty( $giftcard_amount ) ) {
									?>
								<td><b><?php esc_html_e( 'Gift Card Amount :', 'giftware' ); ?></b></td>
								<td><?php echo wp_kses_post( wc_price( $giftcard_amount ) ); ?></td>
								<?php } ?>
							</tr>
							
							<tr>
								<td><b><?php esc_html_e( 'Remaining Amount :', 'giftware' ); ?></b></td>
								<td><?php echo wp_kses_post( wc_price( $remaining_amt ) ); ?></td>
							</tr>
							<tr>
								<td><b><?php esc_html_e( ' To :', 'giftware' ); ?></b></td>
								<td><?php echo esc_html( $to ); ?></td>
							</tr>
							<tr>
								<td><b><?php esc_html_e( 'From :', 'giftware' ); ?></b></td>
								<td><?php echo esc_html( $from ); ?></td>
							</tr>
							<tr>
								<td><b><?php esc_html_e( 'Message :', 'giftware' ); ?></b></td>
								<td><?php echo esc_html( $msg ); ?></td>
							</tr>
							<tr>
								<td><b><?php esc_html_e( 'Scheduled Date :', 'giftware' ); ?></b></td>
								<td><?php echo esc_html( ( '' !== $gift_date ) ? $gift_date : $order_date ); ?></td>
							</tr>
							<tr>
								<td><b><?php esc_html_e( 'Product :', 'giftware' ); ?></b></td>
								<td><a target="_blank" href="<?php echo esc_attr( $pro_permalink ); ?>"><?php echo esc_html( $productname ); ?></a></td>
							</tr>
						</table>

						<table class="wps_uwgc_transaction">
							<h3 style="text-align:;"><?php esc_html_e( 'Gift Card Transactions', 'giftware' ); ?></h3>
							<?php
							$wps_gw_used_coupon_details = get_post_meta( $coupon_id, 'wps_uwgc_used_order_id', true );
							if ( isset( $wps_gw_used_coupon_details ) && is_array( $wps_gw_used_coupon_details ) && ! empty( $wps_gw_used_coupon_details ) ) {
								?>
							<tr>
								<th><?php esc_html_e( 'Order Id', 'giftware' ); ?></th>
								<th><?php esc_html_e( 'Used Amount', 'giftware' ); ?></th>
							</tr>
								<?php

								foreach ( $wps_gw_used_coupon_details as $key => $value ) {
									?>
								<tr>
									<td>
										<a target ="_blank" href="
										<?php
											echo esc_url( admin_url( 'post.php?post=' . absint( $value['order_id'] ) . '&action=edit' ) );
										?>
										">#<?php echo esc_html( $value['order_id'] ); ?></a>
									</td>
									<td> <?php echo wp_kses_post( wc_price( $value['used_amount'] ) ); ?></td>
								</tr>
									<?php
								}
							} else {
								?>
								<tr>
									<td><?php esc_html_e( 'Gift Cards Not Used ', 'giftware' ); ?></td>
								</tr>
								<?php
							}
							?>
						</table>
					</div>

					<style type="text/css">
						.wps_uwgc_report_preview {
						font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
						}
						.wps_uwgc_report_preview table {
						width: 100%;
						border-collapse: collapse;
						border: 1px solid #efefef;
						}
						.wps_uwgc_report_preview h3 {
						text-align: center;
						font-size: 24px;
						margin: 0 0 20px;
						}
						.wps_uwgc_report_preview .wps_uwgc_transaction {
						text-align: center;
						border: 1px solid #efefef;
						}
						.wps_uwgc_report_preview .wps_uwgc_transaction td, .wps_uwgc_report_preview .wps_uwgc_transaction th {
						border-bottom: 1px solid #efefef;
						padding: 10px;
						}
						.wps_uwgc_report_preview table ~ h3 {
						margin-top: 25px;
						}
						.wps_uwgc_report_preview .wps_uwgc_transaction {
						text-align: center;
						}
						.wps_uwgc_report_preview .wps_uwgc_transaction th {
						background-color: #efefef;
						}
						.wps_uwgc_report_preview table td {
							padding: 10px;
							border-bottom: 1px solid #efefef;
						}
					</style>
				<?php
				$message = ob_get_clean();
				$wps_admin_obj = new Woocommerce_Gift_Cards_Common_Function();
				$allowed_tags = $wps_admin_obj->wps_allowed_html_tags();
				echo wp_kses( $message, $allowed_tags );
				die();
			}
		}
	}

	/**
	 * Function is used to display show coupons datils with order id.
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_coupon_reporting_with_order_id().
	 * @param int   $coupon_id Coupon id.
	 * @param array $item Item array.
	 * @param mixed $total_discount Total Discount.
	 * @param mixed $remaining_amount Remaining Coupon Amount.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_coupon_reporting_with_order_id( $coupon_id, $item, $total_discount, $remaining_amount ) {
		$if_gc_coupon = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_unique', true );
		if ( 'online' === $if_gc_coupon ) {
			$wps_uwgc_order = get_post_meta( $coupon_id, 'wps_uwgc_used_order_id', true );
			if ( is_array( $wps_uwgc_order ) && ! empty( $wps_uwgc_order ) ) {
				$wps_uwgc_used_order_id = $item->get_order_id();
				$wps_uwgc_order[] = array(
					'order_id' => $wps_uwgc_used_order_id,
					'used_amount' => $total_discount,
				);
			} else {
				$wps_uwgc_order = array();
				$wps_uwgc_used_order_id = $item->get_order_id();
				$wps_uwgc_order[] = array(
					'order_id' => $wps_uwgc_used_order_id,
					'used_amount' => $total_discount,
				);
			}
			update_post_meta( $coupon_id, 'wps_uwgc_used_order_id', $wps_uwgc_order );
			update_post_meta( $coupon_id, 'coupon_amount', $remaining_amount );
		}
	}

	/**
	 * Function is used to add a button for import template.
	 *
	 * @since 1.0.0
	 * @name wps_add_import_template_button().
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_add_import_template_button() {
		global $typenow;
		if ( 'giftcard' == $typenow ) {
			?>
			<a href="<?php get_admin_url(); ?>edit.php?post_type=giftcard&page=uwgc-import-giftcard-templates" name="import_new_card" class="wps_import_templates button" target="_blank"><?php esc_html_e( 'Import New Cards', 'giftware' ); ?></a>
			<?php
		}
	}

	/**
	 * Function is used to add a sub menu for import template section.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_import_template().
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_import_template() {
		add_submenu_page( 'edit.php?post_type=giftcard', __( 'Import Templates', 'giftware' ), __( 'Import Templates', 'giftware' ), 'manage_options', 'uwgc-import-giftcard-templates', array( $this, 'wps_wgm_import_giftcard_template' ) );
	}

	/**
	 * Function is used to import giftcard template.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_import_giftcard_template().
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_import_giftcard_template() {
		$url  = WPS_TEMPLATE_URL . 'ultimate-woocommerce-gift-card/wps-get-gc-template.php';
		$attr = array(
			'action' => 'wps_fetch_gc_template',
		);
		$query = esc_url_raw( add_query_arg( $attr, $url ) );
		$wps_response = wp_remote_get(
			$query,
			array(
				'timeout' => 20,
				'sslverify' => false,
			)
		);
		if ( is_wp_error( $wps_response ) ) {
			$error_message = $wps_response->get_error_message();
			echo '<p><strong> Something went wrong: ' . esc_html( stripslashes( $error_message ) ) . '</strong></p>';
		} else {
			$wps_response = wp_remote_retrieve_body( $wps_response );
			$template_json_data = json_decode( $wps_response, true );
		}
		?>
		<div id="wps_wgm_loader" style="display: none;">
			<img src="<?php echo esc_url( WPS_UWGC_URL ); ?>assets/images/loading.gif">
		</div>
		<div class="wps_notice_temp" style="display:none;"> 
			<span id="wps_import_notice"></span>
			<i class="fas fa-times cancel_notice"></i>
		</div>
		<h1><?php esc_html_e( 'Import Gift Card Templates', 'giftware' ); ?></h1>
		<?php
		if ( isset( $template_json_data ) && is_array( $template_json_data ) && ! empty( $template_json_data ) ) {
			?>
		<div class="wps_uwgc_filter_wrap">
			<h2><?php esc_html_e( 'Filter Gift Card Templates', 'giftware' ); ?></h2>
			<?php
			$check_if_all_template_imported = get_option( 'wps_uwgc_all_templates_imported', false );
			if ( isset( $check_if_all_template_imported ) && false == $check_if_all_template_imported ) {
				?>
			<a href="#" name="import_all_gift_card" class="wps_import_all_giftcard_templates button"><?php esc_html_e( 'Import All Gift Card Templates At Once', 'giftware' ); ?></a>
				<?php
			}
			?>
		</div>
		<div class="wps_uwgc_wrapper">
			<div id="filters" class="button-group wps_template_filter"> 
				 <button class="button wps_gc_events is-checked" data-filter="*">show all</button>
			<?php
			foreach ( $template_json_data as $rs ) {
				?>
			  <button class="button wps_gc_events" data-filter=".<?php echo esc_attr( stripslashes( $rs['occassion_id'] ) ); ?>"><?php echo esc_html( stripslashes( $rs['occassion_name'] ) ); ?></button>
				<?php
			}
		}
		?>
			</div>
			<?php
			if ( isset( $template_json_data ) && is_array( $template_json_data ) && ! empty( $template_json_data ) ) {
				?>
			<div id="filters_on_mobile" class="wps_template_filter"> 
			<select class="select-group wps_select_template_filter">
				<option class="wps_gc_events is-checked" value="*">show all</option>
				<?php
				foreach ( $template_json_data as $rs ) {
					?>
				<option class="wps_gc_events" value =".<?php echo esc_attr( stripslashes( $rs['occassion_id'] ) ); ?>"><?php echo esc_html( stripslashes( $rs['occassion_name'] ) ); ?></option>
					<?php
				}
				?>
			</select>
			</div>
				<?php
			}
			?>
			<div class="grid wps_template_display">
				<?php
				if ( isset( $template_json_data ) && is_array( $template_json_data ) && ! empty( $template_json_data ) ) {
					foreach ( $template_json_data as $rs ) {
						if ( array_key_exists( 'templates', $rs ) ) {
							foreach ( $rs['templates'] as $temp_data ) {
								?>
								<div class="element-item template_block <?php echo esc_attr( stripslashes( $rs['occassion_id'] ) ); ?>" data-category="template">
									<h3 class="name"><?php echo esc_html( stripslashes( $temp_data['template_name'] ) ); ?></h3>
									<div class="event_template">
										<img src="
										<?php
										 echo esc_url( WPS_TEMPLATE_URL . 'ultimate-woocommerce-gift-card/giftcard-templates/' . $temp_data['template_image'] );
										?>
										  ">
									</div>
									<div class="wps_event_template_preview">
										<div class="wps_preview_links">
											<a href="">
												<i class="fas fa-eye wps_preview_template"></i>
											</a>
											<?php
											$check_template_exist = $this->wps_uwgc_check_already_imported_template( $temp_data );
											if ( $check_template_exist['status'] ) {
												?>
												<i class="fas fa-cloud-upload-alt wps_update_template" data-id="<?php echo esc_attr( stripslashes( $temp_data['template_id'] ) ); ?>"></i>
												<div class="wps_template_update_note">
													<p class="wps_note"><?php esc_html_e( 'Update this template with existing one', 'giftware' ); ?></p>
													<p class="wps_note" id="wps_caution"><span style="color:black;"><strong><?php esc_html_e( 'CAUTION : ', 'giftware' ); ?></strong></span><?php esc_html_e( 'This updation will erase all your custom work on the same template and provide you with the fresh new template.', 'giftware' ); ?></p>
												</div>
												<?php
											} else {
												?>
												<i class="fas fa-download wps_download_template" data-id="<?php echo esc_attr( stripslashes( $temp_data['template_id'] ) ); ?>"></i>
												<div class="wps_template_import_note">
													<p class="wps_note"><?php esc_html_e( 'Import this template.', 'giftware' ); ?></p>
												</div>
												<?php
											}
											?>
																						
										</div>
									</div>
									<div class="wps-popup-wrapper">
										  <div class="wps-popup">
											  <div class="wps-popup-img">
												<span><i class="far fa-times-circle"></i></span>
												<img src="
												<?php
												echo esc_url( WPS_TEMPLATE_URL . 'ultimate-woocommerce-gift-card/giftcard-original-templates/' . $temp_data['template_image'] );
												?>
												  ">
											  </div>
										  </div>
									</div>
								</div>
								<?php
							}
						}
					}
				}
				?>
			</div>
		 </div>
		<?php
	}

	/**
	 * Function is used to import selected giftcard template.
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_import_selected_template().
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_import_selected_template() {
		check_ajax_referer( 'wps-uwgc-giftcard-import-nonce', 'wps_nonce' );
		$wps_selected_temp_id = isset( $_POST['temp_id'] ) ? sanitize_text_field( wp_unslash( $_POST['temp_id'] ) ) : '';
		$response = false;
		if ( isset( $wps_selected_temp_id ) && '' !== $wps_selected_temp_id ) {
			$url  = WPS_TEMPLATE_URL . 'ultimate-woocommerce-gift-card/wps-get-gc-template.php';
			$attr = array(
				'action' => 'wps_fetch_gc_template',
			);
			$query = esc_url_raw( add_query_arg( $attr, $url ) );
			$wps_response = wp_remote_get(
				$query,
				array(
					'timeout' => 20,
					'sslverify' => false,
				)
			);
			if ( is_wp_error( $wps_response ) ) {
				$error_message = $wps_response->get_error_message();
				echo '<p><strong> Something went wrong: ' . esc_html( stripslashes( $error_message ) ) . '</strong></p>';
			} else {
				$wps_response = wp_remote_retrieve_body( $wps_response );
				$wps_json_templates_data = json_decode( $wps_response, true );
			}
			if ( is_array( $wps_json_templates_data ) && ! empty( $wps_json_templates_data ) ) {
				foreach ( $wps_json_templates_data as $wps_template ) {
					if ( array_key_exists( 'templates', $wps_template ) && isset( $wps_template['templates'] ) ) {
						foreach ( $wps_template['templates'] as $wps_templates ) {
							$filename = array();
							$check_template_exist = $this->wps_uwgc_check_already_imported_template( $wps_templates );
							if ( false == $check_template_exist['status'] ) {
								if ( $wps_templates['template_id'] == $wps_selected_temp_id ) {
									foreach ( $wps_templates['template_inside_image'] as $images ) {
										$filename[] = WPS_TEMPLATE_URL . 'ultimate-woocommerce-gift-card/giftcard-templates/inside_images/' . $images;
									}
									if ( isset( $filename ) && is_array( $filename ) && ! empty( $filename ) ) {
										foreach ( $filename as $key => $value ) {
											$upload_file = wp_upload_bits( basename( $value ), null, file_get_contents( $value ) );
											if ( ! $upload_file['error'] ) {
												$filename = $upload_file['file'];
												// The ID of the post this attachment is for.
												$parent_post_id = 0;
												// Check the type of file. We'll use this as the 'post_mime_type'.
												$filetype = wp_check_filetype( basename( $filename ), null );
												// Get the path to the upload directory.
												$wp_upload_dir = wp_upload_dir();
												// Prepare an array of post data for the attachment.
												$attachment = array(
													'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
													'post_mime_type' => $filetype['type'],
													'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),

													'post_status'    => 'inherit',
												);
												// Insert the attachment.

												$attach_id = wp_insert_attachment( $attachment, $filename, 0 );
												// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
												require_once( ABSPATH . 'wp-admin/includes/image.php' );

												// Generate the metadata for the attachment, and update the database record.
												$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
												wp_update_attachment_metadata( $attach_id, $attach_data );
												$arr[] = $attach_id;
											}
										}
									}
									$template_html = $wps_templates['template_html'];
									$gifttemplate = array(
										'post_title' => __( $wps_templates['template_name'], 'giftware' ),
										'post_content' => $template_html,
										'post_status' => 'publish',
										'post_author' => get_current_user_id(),
										'post_type'     => 'giftcard',
									);
									$parent_post_id = wp_insert_post( $gifttemplate );
									update_post_meta( $parent_post_id, 'wps_css_field', trim( $wps_templates['template_css'] ) );
									$imported_temp = get_option( 'wps_uwgc_templateid', array() );
									$imported_temp[] = $parent_post_id;
									update_option( 'wps_uwgc_templateid', $imported_temp );
									set_post_thumbnail( $parent_post_id, $arr[0] );
									$response = true;
								}
							}
						}
					}
				}
			}
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * Function is used to import all giftcard template at once on button click.
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_import_all_templates_at_once().
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_import_all_templates_at_once() {
		check_ajax_referer( 'wps-uwgc-giftcard-import-nonce', 'wps_nonce' );
		$url  = WPS_TEMPLATE_URL . 'ultimate-woocommerce-gift-card/wps-get-gc-template.php';
		$attr = array(
			'action' => 'wps_fetch_gc_template',
		);
		$query = esc_url_raw( add_query_arg( $attr, $url ) );
		$wps_response = wp_remote_get(
			$query,
			array(
				'timeout' => 20,
				'sslverify' => false,
			)
		);
		if ( is_wp_error( $wps_response ) ) {
			$error_message = $wps_response->get_error_message();
			echo '<p><strong> Something went wrong: ' . esc_html( stripslashes( $error_message ) ) . '</strong></p>';
		} else {
			$wps_response = wp_remote_retrieve_body( $wps_response );
			$gc_templates = json_decode( $wps_response, true );
		}
		$response = false;
		if ( is_array( $gc_templates ) && ! empty( $gc_templates ) ) {
			foreach ( $gc_templates as $all_templates ) {
				if ( array_key_exists( 'templates', $all_templates ) && isset( $all_templates['templates'] ) ) {
					foreach ( $all_templates['templates'] as $wps_templates ) {
						$filename = array();
						$arr = array();
						$check_template_exist = $this->wps_uwgc_check_already_imported_template( $wps_templates );
						if ( false == $check_template_exist['status'] ) {
							foreach ( $wps_templates['template_inside_image'] as $images ) {
								$filename[] = WPS_TEMPLATE_URL . 'ultimate-woocommerce-gift-card/giftcard-templates/inside_images/' . $images;
							}
							if ( isset( $filename ) && is_array( $filename ) && ! empty( $filename ) ) {
								foreach ( $filename as $key => $value ) {
									$upload_file = wp_upload_bits( basename( $value ), null, file_get_contents( $value ) );
									if ( ! $upload_file['error'] ) {
										$filename = $upload_file['file'];
										// The ID of the post this attachment is for.
										$parent_post_id = 0;
										// Check the type of file. We'll use this as the 'post_mime_type'.
										$filetype = wp_check_filetype( basename( $filename ), null );
										// Get the path to the upload directory.
										$wp_upload_dir = wp_upload_dir();
										// Prepare an array of post data for the attachment.
										$attachment = array(
											'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
											'post_mime_type' => $filetype['type'],
											'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),

											'post_status'    => 'inherit',
										);
										// Insert the attachment.
										$attach_id = wp_insert_attachment( $attachment, $filename, 0 );
										// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
										require_once( ABSPATH . 'wp-admin/includes/image.php' );
										// Generate the metadata for the attachment, and update the database record.
										$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
										wp_update_attachment_metadata( $attach_id, $attach_data );
										$arr[] = $attach_id;
									}
								}
							}
							$template_html = $wps_templates['template_html'];
							$gifttemplate = array(
								'post_title' => __( $wps_templates['template_name'], 'giftware' ),
								'post_content' => $template_html,
								'post_status' => 'publish',
								'post_author' => get_current_user_id(),
								'post_type' => 'giftcard',
							);
							$parent_post_id = wp_insert_post( $gifttemplate );
							update_post_meta( $parent_post_id, 'wps_css_field', trim( $wps_templates['template_css'] ) );
							$imported_temp = get_option( 'wps_uwgc_templateid', array() );
							$imported_temp[] = $parent_post_id;
							update_option( 'wps_uwgc_templateid', $imported_temp );
							set_post_thumbnail( $parent_post_id, $arr[0] );
							$response = true;
							update_option( 'wps_uwgc_all_templates_imported', 1 );
						}
					}
				}
			}
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * Function is used to import all giftcard template at once on button click.
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_check_already_imported_template().
	 * @param String $template_name Contains template name.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_check_already_imported_template( $template_name ) {
		$post_title = $template_name['template_name'];
		$post_status = 'publish'; // publish, draft, etc.
		$post_type = 'giftcard'; // or whatever post type desired.

		/* Attempt to find post id by post name if it exists */
		$found_post_title = get_page_by_title( $post_title, OBJECT, $post_type );
		$result = array();
		if ( ! is_null( $found_post_title ) ) {
			$found_post_id = $found_post_title->ID;
			if ( false === get_post_status( $found_post_id ) ) {
				$result['post_id'] = '';
				$result['status'] = false;
			} else {
				$result['post_id'] = $found_post_id;
				$result['status'] = true;
			}
		} else {
			$result['post_id'] = '';
			$result['status'] = false;
		}
		return $result;
	}
	/**
	 * Function is used to update selected giftcard template.
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_update_selected_template().
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_uwgc_update_selected_template() {

		check_ajax_referer( 'wps-uwgc-giftcard-import-nonce', 'wps_nonce' );
		$wps_selected_temp_id = isset( $_POST['temp_id'] ) ? sanitize_text_field( wp_unslash( $_POST['temp_id'] ) ) : '';
		$response = false;
		if ( isset( $wps_selected_temp_id ) && '' !== $wps_selected_temp_id ) {
			$url  = WPS_TEMPLATE_URL . 'ultimate-woocommerce-gift-card/wps-get-gc-template.php';
			$attr = array(
				'action' => 'wps_fetch_gc_template',
			);
			$query = esc_url_raw( add_query_arg( $attr, $url ) );
			$wps_response = wp_remote_get(
				$query,
				array(
					'timeout' => 20,
					'sslverify' => false,
				)
			);
			if ( is_wp_error( $wps_response ) ) {
				$error_message = $wps_response->get_error_message();
				echo '<p><strong> Something went wrong: ' . esc_html( stripslashes( $error_message ) ) . '</strong></p>';
			} else {
				$wps_response = wp_remote_retrieve_body( $wps_response );
				$wps_json_templates_data = json_decode( $wps_response, true );
			}
			if ( is_array( $wps_json_templates_data ) && ! empty( $wps_json_templates_data ) ) {
				foreach ( $wps_json_templates_data as $wps_template ) {
					if ( array_key_exists( 'templates', $wps_template ) && isset( $wps_template['templates'] ) ) {
						foreach ( $wps_template['templates'] as $wps_templates ) {
							if ( $wps_templates['template_id'] == $wps_selected_temp_id ) {
								$check_template_exist = $this->wps_uwgc_check_already_imported_template( $wps_templates );
								if ( true == $check_template_exist['status'] ) {
									update_post_meta( $check_template_exist['post_id'], 'wps_css_field', trim( $wps_templates['template_css'] ) );
									$updated_post = array();
									$updated_post['ID'] = $check_template_exist['post_id'];
									$updated_post['post_content'] = $wps_templates['template_html'];
									$response = wp_update_post( $updated_post );
								}
							}
						}
					}
				}
			}
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * Wps_uwgc_custom_plugin_row_meta
	 *
	 * @param array  $links links.
	 * @param string $file file.
	 */
	public function wps_uwgc_custom_plugin_row_meta( $links, $file ) {
		if ( strpos( $file, 'giftware/giftware.php' ) !== false ) {
			$new_links = array(
				'demo'    => '<a href="https://demo.wpswings.com/gift-cards-for-woocommerce-pro/?utm_source=wpswings-giftcards-demo&utm_medium=giftcards-pro-backend&utm_campaign=demo" target="_blank"><img src="' . esc_html( WPS_UWGC_URL ) . 'assets/images/Demo.svg" class="wps-info-img" alt="Demo image" style="margin-right: 5px;vertical-align: middle;max-width: 15px;">' . __( 'Demo', 'giftware' ) . '</a>',
				'doc'     => '<a href="https://docs.wpswings.com/gift-cards-for-woocommerce-pro/?utm_source=wpswings-giftcards-doc&utm_medium=giftcards-pro-backend&utm_campaign=doc" target="_blank"><img src="' . esc_html( WPS_UWGC_URL ) . 'assets/images/Documentation.svg" class="wps-info-img" alt="Demo image" style="margin-right: 5px;vertical-align: middle;max-width: 15px;">' . __( 'Documentation', 'giftware' ) . '</a>',
				'support' => '<a href="https://wpswings.com/submit-query/?utm_source=wpswings-giftcards-support&utm_medium=giftcards-pro-backend&utm_campaign=support" target="_blank"><img src="' . esc_html( WPS_UWGC_URL ) . 'assets/images/Support.svg" class="wps-info-img" alt="Demo image" style="margin-right: 5px;vertical-align: middle;max-width: 15px;">' . __( 'Support', 'giftware' ) . '</a>',
				'services' => '<a href="https://wpswings.com/woocommerce-services/?utm_source=wpswings-giftcards-services&utm_medium=giftcards-pro-backend&utm_campaign=woocommerce-services" target="_blank"><img src="' . esc_html( WPS_UWGC_URL ) . 'assets/images/Services.svg" class="wps-info-img" alt="services image" style="margin-right: 5px;vertical-align: middle;max-width: 15px;">' . __( 'Services', 'giftware' ) . '</a>',
			);

			$links = array_merge( $links, $new_links );
		}
		return $links;
	}

	/**
	 * Wps_uwgc_preview_email_template_for_pro
	 *
	 * @param string $message message.
	 */
	public function wps_uwgc_preview_email_template_for_pro( $message ) {
		$wps_admin_obj = new Woocommerce_Gift_Cards_Common_Function();
		$allowed_tags = $wps_admin_obj->wps_allowed_html_tags();
		echo wp_kses( $message, $allowed_tags );
		die();
	}
	/**
	 * Append template on product edit page for giftcard.
	 *
	 * @name wps_wgm_append_default_template
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_wgm_append_default_template() {
		check_ajax_referer( 'wps-wgm-verify-nonce', 'wps_nonce' );
		$response['result'] = __( 'Fail due to an error', 'giftware' );
		$template_ids = isset( $_POST['template_ids'] ) ? map_deep( wp_unslash( $_POST['template_ids'] ), 'sanitize_text_field' ) : '';

		if ( isset( $template_ids ) && ! empty( $template_ids ) ) {
			$args = array(
				'post_type' => 'giftcard',
				'posts_per_page' => -1,
				'post__in' => $template_ids,
			);
			$loop = new WP_Query( $args );
			$template = array();
			if ( $loop->have_posts() ) {
				while ( $loop->have_posts() ) {
					$loop->the_post();
					global $product;
					$template_id = $loop->post->ID;
					$template_title = $loop->post->post_title;
					$template[ $template_id ] = $template_title;
				}
			}
			$response['templateid'] = $template;
			$response['result'] = 'success';
		} else if ( empty( $template_ids ) ) {
			$response['result'] = 'no_ids';
		}

		echo json_encode( $response );
		wp_die();
	}

	/**
	 * Wps_wgm_restore_gc_data_on_plugins_loaded
	 */
	public function wps_wgm_restore_gc_data_on_plugins_loaded() {
		require_once WPS_UWGC_DIRPATH . 'includes/class-ultimate-woocommerce-gift-cards-activation.php';
		$restore_data = new Ultimate_Woocommerce_Gift_Cards_Activation();
		$restore_data->wps_wgm_restore_data_pro();
	}

	/**
	 * This function is for adding a new product type option Sell as a GiftCard.
	 *
	 * @param array $product_type_options product type.
	 *
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_add_product_type_option( $product_type_options ) {
		$wps_wgc_enable = wps_wgm_giftcard_enable();
		if ( $wps_wgc_enable ) {
			$product_type_options['sell_as_a_giftcard'] = array(
				'id'            => '_sell_as_a_giftcard',
				'wrapper_class' => 'show_if_simple show_if_variable',
				'label'         => 'Sell as a Gift Card',
				'description'   => 'Enable to Sell this product as a Giftcard',
				'default'       => 'no',
			);
		}
		return $product_type_options;
	}

	/**
	 * This function is for saving data of new product type option Sell as a GiftCard.
	 *
	 * @param int    $post_ID postid.
	 * @param object $product product.
	 * @param mixed  $update update.
	 *
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_save_sell_as_a_gc_product_details( $post_ID, $product, $update ) {
		$price = get_post_meta( $product->ID, '_price', true );
		update_post_meta(
			$product->ID,
			'_sell_as_a_giftcard',
			isset( $_POST['_sell_as_a_giftcard'] ) ? 'yes' : 'no'
		);
	}
}
