<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wpswings.com
 * @since      1.0.0
 *
 * @package    Ultimate Woocommerce Gift Cards
 * @subpackage Ultimate Woocommerce Gift Cards/includes
 */

use Automattic\WooCommerce\Admin\Loader;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ultimate Woocommerce Gift Cards
 * @subpackage Ultimate Woocommerce Gift Cards/includes
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Ultimate_Woocommerce_Gift_Card {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Ultimate_Woocommerce_Gift_Card_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WPS_UWGC_PLUGIN_VERSION' ) ) {
			$this->version = WPS_UWGC_PLUGIN_VERSION;
		} else {
			$this->version = '3.5.7';
		}
		$this->plugin_name = 'giftware';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Ultimate_Woocommerce_Gift_Card_Loader. Orchestrates the hooks of the plugin.
	 * - Ultimate_Woocommerce_Gift_Card_I18n. Defines internationalization functionality.
	 * - Ultimate_Woocommerce_Gift_Card_Admin. Defines all hooks for the admin area.
	 * - Ultimate_Woocommerce_Gift_Card_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ultimate-woocommerce-gift-card-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ultimate-woocommerce-gift-card-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ultimate-woocommerce-gift-card-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ultimate-woocommerce-gift-card-public.php';

		$this->loader = new Ultimate_Woocommerce_Gift_Card_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Ultimate_Woocommerce_Gift_Card_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Ultimate_Woocommerce_Gift_Card_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * This is the name for license verification.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public static $lic_callback_function = 'check_lcns_validity';

	/**
	 * This is the name for license verification initial days.
	 *
	 * @since 3.0.0
	 * @var string
	 */
	public static $lic_ini_callback_function = 'check_lcns_initial_days';

	/**
	 * Validate the use of features of this plugin.
	 *
	 * @since    1.0.0
	 */
	public static function check_lcns_validity() {

		$wps_gw_lcns_key = get_option( 'wps_gw_lcns_key', '' );
		$wps_gw_lcns_status = get_option( 'wps_gw_lcns_status', '' );
		if ( $wps_gw_lcns_key && 'true' === $wps_gw_lcns_status ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Validate the use of features of this plugin for initial days.
	 *
	 * @since    1.0.0
	 */
	public static function check_lcns_initial_days() {

		$timestamp = get_option( 'wps_gw_lcns_thirty_days' );
		if ( empty( $timestamp ) ) {
			$timestamp = 'not_set';
		}

		if ( 'not_set' === $timestamp ) {
			$current_time = current_time( 'timestamp' );
			$thirty_days  = strtotime( '+30 days', $current_time );
			update_option( 'wps_gw_lcns_thirty_days', $thirty_days );
		}

		$thirty_days  = get_option( 'wps_gw_lcns_thirty_days', 0 );
		$current_time = current_time( 'timestamp' );
		$day_count    = intval( ( intval( $thirty_days - $current_time ) ) / ( 24 * 60 * 60 ) );
		return $day_count;
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Ultimate_Woocommerce_Gift_Card_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'wps_uwgc_custom_plugin_row_meta', 10, 2 );
		$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'wps_wgm_restore_gc_data_on_plugins_loaded' );
		/*License verification */
		$callname_lic = self::$lic_callback_function;
		$callname_lic_initial = self::$lic_ini_callback_function;
		$day_count = self::$callname_lic_initial();
		$this->loader->add_action( 'wp_ajax_validate_license_handle', $plugin_admin, 'validate_license_handle' );
		$this->loader->add_action( 'wp_ajax_nopriv_validate_license_handle', $plugin_admin, 'validate_license_handle' );
		$this->loader->add_filter( 'wps_wgm_add_gift_card_setting_tab_before', $plugin_admin, 'wps_uwgc_pro_gift_card_setting_tab' );
		$this->loader->add_filter( 'wps_wgm_add_gift_card_setting_tab_after', $plugin_admin, 'wps_uwgc_add_license_setting_tab' );
		$this->loader->add_action( 'wps_uwgc_show_notice', $plugin_admin, 'wps_uwgc_show_license_activation_notice' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts_for_license_validation' );
		$this->loader->add_action( 'preview_email_template_for_pro', $plugin_admin, 'wps_uwgc_preview_email_template_for_pro', 10, 1 );

		/*Pro plugin will be activated if it is still in trial period or license is verified */

		if ( self::$callname_lic() || 0 <= $day_count ) {

			$this->loader->add_action( 'wps_gw_license_daily', $plugin_admin, 'validate_license_daily' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
			$this->loader->add_filter( 'wps_uwgc_pro_active', $plugin_admin, 'wps_uwgc_ultimate_giftcard_active' );
			$this->loader->add_filter( 'wps_wgm_template_capabilities', $plugin_admin, 'wps_uwgc_template_capabilities' );
			$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'wps_uwgc_css_metabox' );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_offline_preview', $plugin_admin, 'wps_uwgc_offline_preview' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_uwgc_offline_preview', $plugin_admin, 'wps_uwgc_offline_preview' );
			$this->loader->add_action( 'init', $plugin_admin, 'wps_uwgc_offline_email_preview' );
			$this->loader->add_filter( 'wps_wgm_product_settings', $plugin_admin, 'wps_uwgc_ultimate_product_settings' );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_check_manual_code_exist', $plugin_admin, 'wps_uwgc_check_manual_code_exist' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_uwgc_check_manual_code_exist', $plugin_admin, 'wps_uwgc_check_manual_code_exist' );
			$this->loader->add_action( 'init', $plugin_admin, 'wps_uwgc_get_all_woocommerce_orders' );
			$this->loader->add_filter( 'wps_wgm_general_setting', $plugin_admin, 'wps_uwgc_general_settings_fields' );
			$this->loader->add_action( 'wps_wgm_admin_setting_fields_html', $plugin_admin, 'wps_uwgc_settings_fields_html', 10, 2 );
			$this->loader->add_filter( 'wps_wgm_mail_template_settings', $plugin_admin, 'wps_uwgc_email_settings' );
			$this->loader->add_filter( 'wps_wgm_delivery_settings', $plugin_admin, 'wps_wgm_additional_delivery_settings' );
			$this->loader->add_action( 'wps_wgm_addtional_mail_settings', $plugin_admin, 'wps_uwgc_additional_mail_settings', 10, 2 );
			$this->loader->add_filter( 'wps_wgm_other_setting', $plugin_admin, 'wps_wgm_additional_other_setting' );
			$this->loader->add_action( 'wps_wgm_giftcard_product_type_field', $plugin_admin, 'wps_uwgc_giftcard_product_type_field' );
			$this->loader->add_action( 'wps_wgm_giftcard_product_type_save_fields', $plugin_admin, 'wps_uwgc_giftcard_product_type_save_fields' );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_show_customizable_dialog', $plugin_admin, 'wps_uwgc_show_customizable_dialog' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_uwgc_show_customizable_dialog', $plugin_admin, 'wps_uwgc_show_customizable_dialog' );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_new_way_for_generating_pdfs', $plugin_admin, 'wps_uwgc_new_way_for_generating_pdfs' );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_next_step_for_generating_pdfs', $plugin_admin, 'wps_uwgc_next_step_for_generating_pdfs' );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_offline_resend_mail', $plugin_admin, 'wps_uwgc_offline_resend_mail' );
			$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'wps_uwgc_order_edit_meta_box', 10, 2 );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_resend_mail_order_edit', $plugin_admin, 'wps_uwgc_resend_mail_order_edit' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_uwgc_resend_mail_order_edit', $plugin_admin, 'wps_uwgc_resend_mail_order_edit' );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_resend_coupon_amount', $plugin_admin, 'wps_uwgc_resend_coupon_amount' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_uwgc_resend_coupon_amount', $plugin_admin, 'wps_uwgc_resend_coupon_amount' );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_update_item_meta_with_new_email', $plugin_admin, 'wps_uwgc_update_item_meta_with_new_email' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_uwgc_update_item_meta_with_new_email', $plugin_admin, 'wps_uwgc_update_item_meta_with_new_email' );
			$this->loader->add_filter( 'wps_wgm_static_coupon_img', $plugin_admin, 'wps_uwgc_qrcode_image' );
			$this->loader->add_filter( 'wps_wgm_product_data_tabs', $plugin_admin, 'wps_uwgc_add_inventory_tab' );
			$this->loader->add_filter( 'wps_wgm_template_custom_css', $plugin_admin, 'wps_uwgc_custom_template_css', 10, 2 );
			$this->loader->add_action( 'woocommerce_coupon_options_usage_limit', $plugin_admin, 'wps_uwgc_manual_increment_usage_count', 10, 2 );
			$this->loader->add_action( 'save_post', $plugin_admin, 'wps_uwgc_save_coupon_manual_usage_count' );
			$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'wps_uwgc_manage_coupon_type' );
			$this->loader->add_filter( 'request', $plugin_admin, 'wps_uwgc_request_coupon_type' );
			$this->loader->add_filter( 'wps_wgm_giftcard_hidden_order_itemmeta', $plugin_admin, 'wps_uwgc_giftcard_hidden_order_itemmeta' );
			$this->loader->add_action( 'wps_wgm_template_custom_shortcode', $plugin_admin, 'wps_uwgc_template_shortcode' );
			// Reporting.
			$this->loader->add_action( 'wp_before_admin_bar_render', $plugin_admin, 'wps_uwgc_admin_toolbar' );
			$this->loader->add_action( 'woocommerce_admin_reports', $plugin_admin, 'wps_uwgc_report' );
			$this->loader->add_action( 'init', $plugin_admin, 'wps_uwgc_preview_report_details' );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_gift_card_details', $plugin_admin, 'wps_uwgc_gift_card_details' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_uwgc_gift_card_details', $plugin_admin, 'wps_uwgc_gift_card_details' );
			$this->loader->add_action( 'wps_wgm_coupon_reporting_with_order', $plugin_admin, 'wps_uwgc_coupon_reporting_with_order_id', 10, 4 );

			/*Import giftcard template*/
			$this->loader->add_action( 'manage_posts_extra_tablenav', $plugin_admin, 'wps_add_import_template_button', 10 );
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'wps_wgm_import_template', 5, 2 );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_import_selected_template', $plugin_admin, 'wps_uwgc_import_selected_template' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_uwgc_import_selected_template', $plugin_admin, 'wps_uwgc_import_selected_template' );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_import_all_templates_at_once', $plugin_admin, 'wps_uwgc_import_all_templates_at_once' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_uwgc_import_all_templates_at_once', $plugin_admin, 'wps_uwgc_import_all_templates_at_once' );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_update_selected_template', $plugin_admin, 'wps_uwgc_update_selected_template' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_uwgc_update_selected_template', $plugin_admin, 'wps_uwgc_update_selected_template' );
			/* Select the default template for a particular product. */
			$this->loader->add_action( 'wp_ajax_wps_wgm_append_default_template', $plugin_admin, 'wps_wgm_append_default_template' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_wgm_append_default_template', $plugin_admin, 'wps_wgm_append_default_template' );
			$this->loader->add_action( 'save_post', $plugin_admin, 'wps_save_meta_fields' );

			/* Sell as a Gift Card. */
			$this->loader->add_filter( 'product_type_options', $plugin_admin, 'wps_add_product_type_option' );
			$this->loader->add_action( 'save_post_product', $plugin_admin, 'wps_save_sell_as_a_gc_product_details', 10, 3 );

		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Ultimate_Woocommerce_Gift_Card_Public( $this->get_plugin_name(), $this->get_version() );
		$callname_lic = self::$lic_callback_function;
		$callname_lic_initial = self::$lic_ini_callback_function;
		$day_count = self::$callname_lic_initial();

		if ( self::$callname_lic() || 0 <= $day_count ) {

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			$this->loader->add_filter( 'wps_wgm_pricing_html', $plugin_public, 'wps_uwgc_pricing_html', 10, 3 );
			$this->loader->add_filter( 'wps_wgm_default_price_discount', $plugin_public, 'wps_uwgc_default_price_discount', 10, 2 );
			$this->loader->add_action( 'wps_wgm_range_price_discount', $plugin_public, 'wps_uwgc_range_price_discount', 10, 3 );
			$this->loader->add_action( 'wps_wgm_user_price_discount', $plugin_public, 'wps_uwgc_user_price_discount', 10, 2 );
			$this->loader->add_filter( 'wps_wgm_select_date', $plugin_public, 'wps_uwgc_select_date_feature', 10, 2 );
			$this->loader->add_filter( 'wps_wgm_add_delivery_method', $plugin_public, 'wps_uwgc_add_delivery_method', 10, 2 );
			$this->loader->add_filter( 'wps_wgm_upload_giftcard_image', $plugin_public, 'wps_uwgc_upload_featured_image' );
			$this->loader->add_filter( 'wps_wgm_add_preview_template_fields', $plugin_public, 'wps_uwgc_preview_template_fields' );
			$this->loader->add_filter( 'wps_wgm_default_events_html', $plugin_public, 'wps_uwgc_default_event_html', 10, 2 );
			$this->loader->add_filter( 'wps_wgm_email_template_html', $plugin_public, 'wps_uwgc_email_template_html', 10, 2 );
			$this->loader->add_filter( 'wps_wgm_add_cart_item_data', $plugin_public, 'wps_uwgc_add_cart_item_data', 10, 4 );
			$this->loader->add_filter( 'wps_wgm_before_calculate_totals', $plugin_public, 'wps_uwgc_before_calculate_totals', 10, 2 );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_append_prices', $plugin_public, 'wps_uwgc_append_prices' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_uwgc_append_prices', $plugin_public, 'wps_uwgc_append_prices' );
			$this->loader->add_filter( 'wps_wgm_get_item_meta', $plugin_public, 'wps_uwgc_get_item_meta', 10, 3 );
			$this->loader->add_filter( 'wps_wgm_add_more_coupon_fields', $plugin_public, 'wps_uwgc_add_more_coupon_fields', 10, 3 );
			$this->loader->add_filter( 'wps_wgm_add_pdf_settings', $plugin_public, 'wps_uwgc_add_pdf_settings', 10, 2 );
			$this->loader->add_action( 'wps_wgm_offline_giftcard_coupon', $plugin_public, 'wps_uwgc_offline_giftcard_coupon', 10, 2 );
			$this->loader->add_action( 'wps_wgm_send_mail_remaining_amount', $plugin_public, 'wps_uwgc_send_mail_remaining_amount', 10, 2 );
			$this->loader->add_filter( 'woocommerce_available_payment_gateways', $plugin_public, 'wps_uwgc_available_payment_gateways', 5, 1 );
			$this->loader->add_filter( 'wps_wgm_qrcode_coupon', $plugin_public, 'wps_uwgc_qrcode_coupon' );
			$this->loader->add_action( 'woocommerce_after_shop_loop_item', $plugin_public, 'wps_uwgc_preview_link_shop_page' );
			$this->loader->add_action( 'init', $plugin_public, 'wps_uwgc_preview_email_template_shop_page' );
			$this->loader->add_filter( 'wps_wgm_selected_date_format', $plugin_public, 'wps_uwgc_select_date_format_enable' );
			$this->loader->add_action( 'woocommerce_after_single_product_summary', $plugin_public, 'wps_uwgc_gift_card_expiry_notice', 5, 1 );
			$this->loader->add_filter( 'woocommerce_cart_item_price', $plugin_public, 'wps_uwgc_return_actual_price', 10, 3 );
			$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public, 'wps_uwgc_thankyou_coupon_order_creation', 10, 2 );
			$this->loader->add_filter( 'woocommerce_is_sold_individually', $plugin_public, 'wps_uwgc_hide_quantity_fields', 10, 2 );
			$this->loader->add_filter( 'wps_wgm_common_arr_data', $plugin_public, 'wps_uwgc_common_arr_data', 10, 3 );
			$this->loader->add_filter( 'wps_wgm_display_thumbnail', $plugin_public, 'wps_uwgc_display_thumbnail_temmplates', 10, 2 );

			// Custmizable Giftcard.
			$wps_uwgc_custmizable_settings = get_option( 'wps_wgm_customizable_settings', array() );
			$wps_uwgc_customizable_enale = 'off';
			if ( isset( $wps_uwgc_custmizable_settings ) && is_array( $wps_uwgc_custmizable_settings ) && ! empty( $wps_uwgc_custmizable_settings ) ) {

				if ( isset( $wps_uwgc_custmizable_settings['wps_wgm_customizable_enable'] ) ) {
					$wps_uwgc_customizable_enale = $wps_uwgc_custmizable_settings['wps_wgm_customizable_enable'];
				}
			}
			if ( isset( $wps_uwgc_customizable_enale ) && 'on' == $wps_uwgc_customizable_enale ) {
				$this->loader->add_filter( 'woocommerce_locate_template', $plugin_public, 'wps_uwgc_locate_custmizable_gift_template', 10, 3 );
				$this->loader->add_filter( 'template_include', $plugin_public, 'wps_uwgc_include_custmizable_template', 30, 1 );
				$this->loader->add_action( 'wps_cgc_delivery_methods', $plugin_public, 'wps_uwgc_add_custmizable_giftcard_delivery_methods' );
				$this->loader->add_action( 'wps_cgc_before_main_content', $plugin_public, 'wps_uwgc_custmizable_before_main_content' );
				$this->loader->add_filter( 'wps_uwgc_custmizable_common_arr', $plugin_public, 'wps_cgc_custmizable_common_arr', 10, 3 );
				$this->loader->add_filter( 'wps_wgm_customizable_email_template', $plugin_public, 'wps_uwgc_customizable_email_template', 10, 2 );
				$this->loader->add_action( 'wp_ajax_wps_cgc_upload_own_img', $plugin_public, 'wps_cgc_upload_own_img' );
				$this->loader->add_action( 'wp_ajax_nopriv_wps_cgc_upload_own_img', $plugin_public, 'wps_cgc_upload_own_img' );
				$this->loader->add_action( 'wp_ajax_wps_cgc_admin_uploads_name', $plugin_public, 'wps_cgc_admin_uploads_name' );
				$this->loader->add_action( 'wp_ajax_nopriv_wps_cgc_admin_uploads_name', $plugin_public, 'wps_cgc_admin_uploads_name' );
				$this->loader->add_filter( 'wps_uwgc_item_meta_data', $plugin_public, 'wps_cgc_add_item_meta_data', 10, 2 );
				$this->loader->add_filter( 'wps_wgm_resend_mail_arr_update', $plugin_public, 'wps_cgc_resend_mail_arr_update', 10, 2 );
			}

			$this->loader->add_action( 'init', $plugin_public, 'wps_uwgc_add_short_code_giftcard_balance' );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_check_gift_balance', $plugin_public, 'wps_uwgc_check_gift_balance' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_uwgc_check_gift_balance', $plugin_public, 'wps_uwgc_check_gift_balance' );
			$this->loader->add_filter( 'wps_wgm_mail_templates_data_set', $plugin_public, 'wps_uwgc_mail_templates_settings', 10, 3 );
			$this->loader->add_filter( 'wps_wgm_check_coupon_creation_mails', $plugin_public, 'wps_uwgc_check_coupon_creation', 10, 4 );
			$this->loader->add_action( 'wps_wgm_checkout_create_order_line_item', $plugin_public, 'wps_uwgc_checkout_create_order_line_item', 10, 3 );
			/*Hooks for scheduling giftcard*/
			$this->loader->add_action( 'wps_gw_giftcard_cron_schedule', $plugin_public, 'wps_uwgc_do_this_hourly' );
			$this->loader->add_action( 'wps_gw_giftcard_cron_delete_images', $plugin_public, 'wps_uwgc_do_this_delete_img' );
			/*Hook for send today button*/
			$this->loader->add_action( 'woocommerce_order_item_meta_end', $plugin_public, 'wps_uwgc_send_mail_forcefully_html', 10, 3 );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_send_mail_forcefully', $plugin_public, 'wps_uwgc_send_mail_forcefully' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_uwgc_send_mail_forcefully', $plugin_public, 'wps_uwgc_send_mail_forcefully' );
			$this->loader->add_action( 'woocommerce_order_details_after_order_table', $plugin_public, 'wps_uwgc_resend_mail_view_order_frontend' );
			$this->loader->add_action( 'wp_ajax_wps_uwgc_resend_mail_order_deatils_frontend', $plugin_public, 'wps_uwgc_resend_mail_order_deatils_frontend' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_uwgc_resend_mail_order_deatils_frontend', $plugin_public, 'wps_uwgc_resend_mail_order_deatils_frontend' );
			$this->loader->add_action( 'woocommerce_order_status_changed', $plugin_public, 'wps_uwgc_thankyou_coupon_order_status_change', 99, 3 );
			$this->loader->add_filter( 'woocommerce_add_to_cart_validation', $plugin_public, 'wps_uwgc_add_to_cart_validation', 10, 3 );
			$this->loader->add_filter( 'wps_wgm_common_functionality_template_args', $plugin_public, 'wps_uwgc_common_functionality_template_args', 10, 2 );
			$this->loader->add_filter( 'wps_wgm_hide_giftcard_product_thumbnail', $plugin_public, 'wps_uwgc_hide_giftcard_product_thumbnail' );
			$this->loader->add_filter( 'wps_wgm_hide_order_metafields', $plugin_public, 'wps_uwgc_hide_order_metafields_from_email', 10, 2 );
			$this->loader->add_filter( 'woocommerce_order_item_display_meta_key', $plugin_public, 'wps_uwgc_woocommerce_order_item_display_meta_key', 10, 1 );
			$this->loader->add_filter( 'woocommerce_order_item_display_meta_value', $plugin_public, 'wps_uwgc_woocommerce_order_item_display_meta_value', 10, 1 );
			$this->loader->add_action( 'woocommerce_after_calculate_totals', $plugin_public, 'wps_uwgc_apply_coupon_on_cart_total' );
			$this->loader->add_filter( 'wps_wgm_add_notiication_section', $plugin_public, 'wps_wgm_input_mobileno_section', 10, 2 );
			/*SMS NOTIFICATION*/
			$this->loader->add_action( 'wp_ajax_wps_wgm_validate_twilio_contact_number', $plugin_public, 'wps_wgm_validate_twilio_contact_number', 10, 2 );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_wgm_validate_twilio_contact_number', $plugin_public, 'wps_wgm_validate_twilio_contact_number', 10 );
			$this->loader->add_action( 'wps_wgm_send_giftcard_over_sms', $plugin_public, 'wps_wgm_send_gc_sms_via_twilio', 10, 2 );

			/*SMS NOTIFICATION*/
			$this->loader->add_filter( 'wps_wgm_customizable_email_template', $plugin_public, 'wps_uwgc_resend_message', 5, 2 );
			/*whatsapp sharing*/
			$this->loader->add_action( 'woocommerce_order_details_after_order_table', $plugin_public, 'wps_uwgc_enable_whatspp_sharing', 10, 1 );
			/*Terms and condition*/
			$this->loader->add_action( 'wps_uwgc_terms_and_condition', $plugin_public, 'wps_uwgc_terms_and_condition_content', 10, 1 );
			$this->loader->add_filter( 'wps_wgm_load_product_script', $plugin_public, 'wps_wgm_load_product_script_on_custom_page' );
			/*refuns/cancell giftcard*/
			$this->loader->add_action( 'woocommerce_order_status_changed', $plugin_public, 'wps_wgm_initiate_refund_gc', 10, 3 );
			/*mini cart discount price*/
			$this->loader->add_filter( 'wps_wgm_updated_minicart_price', $plugin_public, 'wps_mini_cart_product_product_discount_price', 99, 3 );

			/*adding coupon meta for product as a gift*/
			$this->loader->add_action( 'wps_wgm_set_coupon_meta_for_product_as_a_gift', $plugin_public, 'wps_set_coupon_meta_for_product_as_a_gift', 10, 4 );
			$this->loader->add_filter( 'wps_wgm_enable_sell_as_a_gc', $plugin_public, 'wps_enable_sell_as_a_gc', 10, 1 );

			/*purchase product as a gift*/
			$this->loader->add_filter( 'wps_wgm_ajax_product_as_a_gift', $plugin_public, 'wps_ajax_product_as_a_gift', 10, 1 );
			$this->loader->add_filter( 'wps_wgm_update_item_meta_as_a_gift', $plugin_public, 'wps_update_item_meta_as_a_gift', 10, 3 );
			$this->loader->add_action( 'woocommerce_reduce_order_stock', $plugin_public, 'wps_stop_reduce_order_stock_for_sell_as_a_gift', 10, 1 );

			$this->loader->add_action( 'wp_ajax_wps_get_data', $plugin_public, 'wps_cart_form_for_product_as_a_gift' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_get_data', $plugin_public, 'wps_cart_form_for_product_as_a_gift' );

			/* [COUPONURL] shortcode */
			$this->loader->add_action( 'wp_loaded', $plugin_public, 'wps_uwgc_apply_coupon_through_url' );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Ultimate_Woocommerce_Gift_Card_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
