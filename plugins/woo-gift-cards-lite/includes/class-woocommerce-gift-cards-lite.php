<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/includes
 */

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
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/includes
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Woocommerce_Gift_Cards_Lite {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woocommerce_Gift_Cards_Lite_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'WPS_WGC_VERSION' ) ) {
			$this->version = WPS_WGC_VERSION;
		} else {
			$this->version = '2.4.7';
		}
		$this->plugin_name = 'woo-gift-cards-lite';

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
	 * - Woocommerce_Gift_Cards_Lite_Loader. Orchestrates the hooks of the plugin.
	 * - Woocommerce_Gift_Cards_Lite_I18n. Defines internationalization functionality.
	 * - Woocommerce_Gift_Cards_Lite_Admin. Defines all hooks for the admin area.
	 * - Woocommerce_Gift_Cards_Lite_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-gift-cards-lite-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-gift-cards-lite-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-gift-cards-lite-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-gift-cards-lite-public.php';

		$this->loader = new Woocommerce_Gift_Cards_Lite_Loader();

		/**
		 * The class responsible for defining all actions that occur in the onboarding the site data
		 * in the admin side of the site.
		 */
		! class_exists( 'Makewebbetter_Onboarding_Helper' ) && require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-makewebbetter-onboarding-helper.php';
		$this->onboard = new Makewebbetter_Onboarding_Helper();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woocommerce_Gift_Cards_Lite_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Woocommerce_Gift_Cards_Lite_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Woocommerce_Gift_Cards_Lite_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'wps_wgm_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'wps_wgm_enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wps_wgm_admin_menu', 10, 2 );
		$this->loader->add_filter( 'product_type_selector', $plugin_admin, 'wps_wgm_gift_card_product' );
		$this->loader->add_action( 'woocommerce_product_options_general_product_data', $plugin_admin, 'wps_wgm_woocommerce_product_options_general_product_data' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'wps_wgm_save_post' );
		$this->loader->add_action( 'woocommerce_product_data_tabs', $plugin_admin, 'wps_wgm_woocommerce_product_data_tabs' );
		$this->loader->add_action( 'woocommerce_after_order_itemmeta', $plugin_admin, 'wps_wgm_woocommerce_after_order_itemmeta', 10, 3 );
		$this->loader->add_filter( 'woocommerce_hidden_order_itemmeta', $plugin_admin, 'wps_wgm_woocommerce_hidden_order_itemmeta' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'wps_wgm_setting_notice_on_activation' );

		// Added new Actions and Filters.
		$this->loader->add_action( 'init', $plugin_admin, 'wps_wgm_giftcard_custom_post' );
		$this->loader->add_action( 'edit_form_after_title', $plugin_admin, 'wps_wgm_edit_form_after_title', 10, 1 );
		// include Gift card Template.
		$this->loader->add_action( 'init', $plugin_admin, 'wps_wgm_mothers_day_template', 10 );
		$this->loader->add_action( 'init', $plugin_admin, 'wps_wgm_new_template', 10 );
		$this->loader->add_action( 'init', $plugin_admin, 'wps_wgm_insert_custom_template', 10 );
		$this->loader->add_action( 'init', $plugin_admin, 'wps_wgm_insert_christmas_template', 10 );
		$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'wps_wgm_preview_gift_template', 10, 2 );
		$this->loader->add_action( 'init', $plugin_admin, 'wps_wgm_preview_email_template' );
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'wps_custom_plugin_row_meta', 10, 2 );

		/*cron for notification*/
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wps_wgm_set_cron_for_plugin_notification' );
		$this->loader->add_action( 'wps_wgm_check_for_notification_update', $plugin_admin, 'wps_wgm_save_notice_message' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'wps_wgm_display_notification_bar' );
		$this->loader->add_action( 'wp_ajax_wps_wgm_dismiss_notice', $plugin_admin, 'wps_wgm_dismiss_notice' );
		// Add your screen.
		$this->loader->add_filter( 'wps_helper_valid_frontend_screens', $plugin_admin, 'add_wps_frontend_screens' );
		// Add Deactivation screen.
		$this->loader->add_filter( 'wps_deactivation_supported_slug', $plugin_admin, 'add_wps_deactivation_screens' );
		// Disable Quick Edit option.
		$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'wps_wgm_remove_row_actions', 10, 2 );

		$this->loader->add_action( 'wp_ajax_wgm_ajax_callbacks', $plugin_admin, 'wps_wgm_ajax_callbacks' );
	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Woocommerce_Gift_Cards_Lite_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'woocommerce_get_price_html', $plugin_public, 'wps_wgm_woocommerce_get_price_html', 10, 2 );
		$this->loader->add_action( 'woocommerce_before_add_to_cart_button', $plugin_public, 'wps_wgm_woocommerce_before_add_to_cart_button' );
		$this->loader->add_filter( 'woocommerce_add_cart_item_data', $plugin_public, 'wps_wgm_woocommerce_add_cart_item_data', 10, 3 );
		$this->loader->add_filter( 'woocommerce_get_item_data', $plugin_public, 'wps_wgm_woocommerce_get_item_data', 10, 2 );
		$this->loader->add_action( 'woocommerce_before_calculate_totals', $plugin_public, 'wps_wgm_woocommerce_before_calculate_totals' );
		$this->loader->add_action( 'woocommerce_order_status_changed', $plugin_public, 'wps_wgm_woocommerce_order_status_changed', 10, 3 );
		$this->loader->add_action( 'woocommerce_checkout_create_order_line_item', $plugin_public, 'wps_wgm_woocommerce_checkout_create_order_line_item', 10, 3 );
		add_action( 'woocommerce_wgm_gift_card_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
		$this->loader->add_filter( 'woocommerce_loop_add_to_cart_link', $plugin_public, 'wps_wgm_woocommerce_loop_add_to_cart_link', 10, 2 );
		$this->loader->add_filter( 'woocommerce_product_is_taxable', $plugin_public, 'wps_wgm_woocommerce_product_is_taxable', 10, 2 );
		$this->loader->add_action( 'woocommerce_before_main_content', $plugin_public, 'wps_wgm_woocommerce_before_main_content' );
		$this->loader->add_action( 'woocommerce_product_query', $plugin_public, 'wps_wgm_woocommerce_product_query', 10, 2 );
		$this->loader->add_action( 'woocommerce_new_order_item', $plugin_public, 'wps_wgm_woocommerce_new_order_item', 10, 3 );
		$this->loader->add_filter( 'wc_shipping_enabled', $plugin_public, 'wps_wgm_wc_shipping_enabled' );
		$this->loader->add_action( 'wp_ajax_wps_wgc_preview_thickbox_rqst', $plugin_public, 'wps_wgm_preview_thickbox_rqst' );
		$this->loader->add_action( 'wp_ajax_nopriv_wps_wgc_preview_thickbox_rqst', $plugin_public, 'wps_wgm_preview_thickbox_rqst' );
		$this->loader->add_action( 'init', $plugin_public, 'wps_wgm_preview_email_on_single_page' );
		$other_setting = get_option( 'wps_wgm_other_settings', array() );
		if ( is_array( $other_setting ) && ! empty( $other_setting ) && array_key_exists( 'wps_wgm_additional_apply_coupon_disable', $other_setting ) ) {
			$wps_wgm_apply_coupon_disable = $other_setting['wps_wgm_additional_apply_coupon_disable'];
			if ( 'on' == $wps_wgm_apply_coupon_disable ) {
				$this->loader->add_filter( 'woocommerce_coupons_enabled', $plugin_public, 'wps_wgm_hidding_coupon_field_on_cart', 10, 1 );
			}
		}
		$this->loader->add_filter( 'woocommerce_order_item_get_formatted_meta_data', $plugin_public, 'wps_wgm_woocommerce_hide_order_metafields', 10, 1 );
		$this->loader->add_filter( 'wc_price_based_country_product_types_overriden', $plugin_public, 'wps_wgm_price_based_country_giftcard' );
		$this->loader->add_filter( 'woocommerce_hold_stock_for_checkout', $plugin_public, 'wps_wgm_apply_already_created_giftcard_coupons' );
		// Compatibility with Flatsome theme minicart price issue.
		$this->loader->add_filter( 'woocommerce_cart_item_price', $plugin_public, 'wps_mini_cart_product_price', 10, 3 );
		// Compatibilty with WPS Currency Switcher.
		$this->loader->add_filter( 'wps_currency_switcher_get_custom_product_type', $plugin_public, 'wps_currency_switcher_get_custom_product_type', 10, 2 );
		$this->loader->add_action( 'woocommerce_order_status_changed', $plugin_public, 'wps_wgm_manage_coupon_amount_on_refund', 10, 3 );
		// Compatible with Wallet.
		$this->loader->add_action( 'wps_wsfw_add_wallet_register_endpoint', $plugin_public, 'wps_wgm_add_wallet_register_endpoint', 10 );
		$this->loader->add_filter( 'wps_wsfw_add_wallet_tabs', $plugin_public, 'wps_wgm_add_wallet_tabs', 10, 1 );
		$this->loader->add_action( 'wp_ajax_wps_recharge_wallet_via_giftcard', $plugin_public, 'wps_recharge_wallet_via_giftcard' );
		$this->loader->add_action( 'wp_ajax_nopriv_wps_recharge_wallet_via_giftcard', $plugin_public, 'wps_recharge_wallet_via_giftcard' );

		// Add variable pricing type.
		$this->loader->add_action( 'wp_ajax_wps_wgm_append_variable_price', $plugin_public, 'wps_wgm_append_variable_price' );
		$this->loader->add_action( 'wp_ajax_nopriv_wps_wgm_append_variable_price', $plugin_public, 'wps_wgm_append_variable_price' );
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
	 * @return    Woocommerce_Gift_Cards_Lite_Loader    Orchestrates the hooks of the plugin.
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
