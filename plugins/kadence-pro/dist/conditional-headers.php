<?php
/**
 * Class for the Customizer conditional Headers.
 *
 * @package Kadence
 */

namespace Kadence_Pro;

use function Kadence\kadence;
use DateTime;
use function get_editable_roles;

/**
 * Main plugin class
 */
class Conditional_Headers {
	/**
	 * Instance Control
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Holds the string for current Header.
	 *
	 * @var values of the theme settings.
	 */
	public static $current_header = null;

	/**
	 * Associative array of Google Fonts to load.
	 *
	 * Do not access this property directly, instead use the `get_google_fonts()` method.
	 *
	 * @var array
	 */
	protected static $google_fonts = array();
	/**
	 * Current condition
	 *
	 * @var null
	 */
	public static $current_condition = null;

	/**
	 * Current user
	 *
	 * @var null
	 */
	public static $current_user = null;

	/**
	 * Holds theme header settings keys.
	 *
	 * @var the theme header settings keys.
	 */
	public static $header_keys = array(
		'custom_logo',
		'header_mobile_available_items',
		'header_desktop_available_items',
		'header_desktop_items',
		'header_wrap_background',
		'header_main_height',
		'header_main_layout',
		'header_main_background',
		'header_main_trans_background',
		'header_main_top_border',
		'header_main_bottom_border',
		'header_main_border',
		'header_main_padding',
		'header_top_height',
		'header_top_layout',
		'header_top_background',
		'header_top_trans_background',
		'header_top_top_border',
		'header_top_bottom_border',
		'header_top_padding',
		'header_top_border',
		'header_bottom_height',
		'header_bottom_layout',
		'header_bottom_background',
		'header_bottom_trans_background',
		'header_bottom_top_border',
		'header_bottom_bottom_border',
		'header_bottom_padding',
		'header_bottom_border',
		'header_mobile_items',
		'logo_width',
		'use_mobile_logo',
		'mobile_logo',
		'logo_layout',
		'brand_typography',
		'brand_typography_color',
		'brand_tag_typography',
		'header_logo_padding',
		'primary_navigation_typography',
		'primary_navigation_spacing',
		'primary_navigation_vertical_spacing',
		'primary_navigation_stretch',
		'primary_navigation_fill_stretch',
		'primary_navigation_style',
		'primary_navigation_color',
		'primary_navigation_background',
		'primary_navigation_parent_active',
		'secondary_navigation_typography',
		'secondary_navigation_spacing',
		'secondary_navigation_vertical_spacing',
		'secondary_navigation_stretch',
		'secondary_navigation_fill_stretch',
		'secondary_navigation_style',
		'secondary_navigation_color',
		'secondary_navigation_background',
		'secondary_navigation_parent_active',
		'dropdown_navigation_reveal',
		'dropdown_navigation_width',
		'dropdown_navigation_vertical_spacing',
		'dropdown_navigation_color',
		'dropdown_navigation_background',
		'dropdown_navigation_divider',
		'dropdown_navigation_shadow',
		'dropdown_navigation_typography',
		'mobile_trigger_label',
		'mobile_trigger_icon',
		'mobile_trigger_style',
		'mobile_trigger_border',
		'mobile_trigger_icon_size',
		'mobile_trigger_color',
		'mobile_trigger_background',
		'mobile_trigger_typography',
		'mobile_trigger_padding',
		'mobile_navigation_reveal',
		'mobile_navigation_collapse',
		'mobile_navigation_parent_toggle',
		'mobile_navigation_width',
		'mobile_navigation_vertical_spacing',
		'mobile_navigation_color',
		'mobile_navigation_background',
		'mobile_navigation_divider',
		'mobile_navigation_typography',
		'header_popup_side',
		'header_popup_layout',
		'header_popup_animation',
		'header_popup_vertical_align',
		'header_popup_content_align',
		'header_popup_background',
		'header_popup_close_color',
		'header_popup_close_background',
		'header_popup_close_icon_size',
		'header_popup_close_padding',
		'header_html_content',
		'header_html_typography',
		'header_html_link_style',
		'header_html_link_color',
		'header_html_margin',
		'header_html_wpautop',
		'header_button_label',
		'header_button_link',
		'header_button_style',
		'header_button_size',
		'header_button_visibility',
		'header_button_padding',
		'header_button_typography',
		'header_button_color',
		'header_button_background',
		'header_button_border_colors',
		'header_button_border',
		'header_button_shadow',
		'header_button_shadow_hover',
		'header_button_margin',
		'header_button_radius',
		'header_button_target',
		'header_button_nofollow',
		'header_button_sponsored',
		'header_button_download',
		'header_social_items',
		'header_social_style',
		'header_social_show_label',
		'header_social_item_spacing',
		'header_social_icon_size',
		'header_social_brand',
		'header_social_color',
		'header_social_background',
		'header_social_border_colors',
		'header_social_border',
		'header_social_border_radius',
		'header_social_typography',
		'header_social_margin',
		'header_mobile_switch',
		'header_mobile_social_items',
		'header_mobile_social_style',
		'header_mobile_social_show_label',
		'header_mobile_social_item_spacing',
		'header_mobile_social_icon_size',
		'header_mobile_social_brand',
		'header_mobile_social_color',
		'header_mobile_social_background',
		'header_mobile_social_border_colors',
		'header_mobile_social_border',
		'header_mobile_social_border_radius',
		'header_mobile_social_typography',
		'header_mobile_social_margin',
		'header_search_label',
		'header_search_label_visiblity',
		'header_search_icon',
		'header_search_style',
		'header_search_woo',
		'header_search_border',
		'header_search_icon_size',
		'header_search_color',
		'header_search_background',
		'header_search_typography',
		'header_search_padding',
		'header_search_margin',
		'header_search_modal_color',
		'header_search_modal_background',
		'header_search_modal_background',
		'mobile_button_label',
		'mobile_button_style',
		'mobile_button_size',
		'mobile_button_visibility',
		'mobile_button_typography',
		'mobile_button_color',
		'mobile_button_background',
		'mobile_button_border_colors',
		'mobile_button_border',
		'mobile_button_margin',
		'mobile_button_radius',
		'mobile_button_shadow',
		'mobile_button_shadow_hover',
		'mobile_html_content',
		'mobile_html_typography',
		'mobile_html_link_color',
		'mobile_html_margin',
		'mobile_html_link_style',
		'mobile_html_wpautop',
		'transparent_header_enable',
		'transparent_header_device',
		'transparent_header_archive',
		'transparent_header_page',
		'transparent_header_post',
		'transparent_header_product',
		'transparent_header_logo_width',
		'transparent_header_logo',
		'transparent_header_custom_logo',
		'transparent_header_mobile_logo',
		'transparent_header_custom_mobile_logo',
		'transparent_header_site_title_color',
		'transparent_header_navigation_color',
		'transparent_header_navigation_background',
		'transparent_header_button_color',
		'transparent_header_social_color',
		'transparent_header_html_color',
		'transparent_header_html2_color',
		'transparent_header_background',
		'transparent_header_bottom_border',
		'header_sticky',
		'header_reveal_scroll_up',
		'header_sticky_shrink',
		'header_sticky_main_shrink',
		'mobile_header_sticky',
		'mobile_header_sticky_shrink',
		'mobile_header_sticky_main_shrink',
		'header_sticky_logo',
		'header_sticky_custom_logo',
		'header_sticky_mobile_logo',
		'header_sticky_custom_mobile_logo',
		'header_sticky_logo_width',
		'header_sticky_site_title_color',
		'header_sticky_navigation_color',
		'header_sticky_navigation_background',
		'header_sticky_button_color',
		'header_sticky_social_color',
		'header_sticky_html_color',
		'header_sticky_background',
		'header_sticky_bottom_border',
		'mobile_secondary_navigation_reveal',
		'mobile_secondary_navigation_collapse',
		'mobile_secondary_navigation_parent_toggle',
		'mobile_secondary_navigation_width',
		'mobile_secondary_navigation_vertical_spacing',
		'mobile_secondary_navigation_color',
		'mobile_secondary_navigation_background',
		'mobile_secondary_navigation_divider',
		'mobile_secondary_navigation_typography',
		'header_html2_content',
		'header_html2_wpautop',
		'header_html2_typography',
		'header_html2_link_style',
		'header_html2_link_color',
		'header_html2_margin',
		'header_mobile_html2_content',
		'header_mobile_html2_wpautop',
		'header_mobile_html2_typography',
		'header_mobile_html2_link_style',
		'header_mobile_html2_link_color',
		'header_mobile_html2_margin',
		'header_account_preview',
		'header_account_icon',
		'header_account_link',
		'header_account_action',
		'header_account_modal_registration',
		'header_account_modal_registration_link',
		'header_account_dropdown_direction',
		'header_account_style',
		'header_account_label',
		'header_account_icon_size',
		'header_account_color',
		'header_account_background',
		'header_account_radius',
		'header_account_typography',
		'header_account_padding',
		'header_account_margin',
		'header_account_in_icon',
		'header_account_in_link',
		'header_account_in_action',
		'header_account_in_dropdown_source',
		'header_account_in_dropdown_direction',
		'header_account_in_style',
		'header_account_in_label',
		'header_account_in_icon_size',
		'header_account_in_image_radius',
		'header_account_in_color',
		'header_account_in_background',
		'header_account_in_radius',
		'header_account_in_typography',
		'header_account_in_padding',
		'header_account_in_margin',
		'transparent_header_account_color',
		'transparent_header_account_background',
		'transparent_header_account_in_color',
		'transparent_header_account_in_background',
		'header_mobile_account_preview',
		'header_mobile_account_icon',
		'header_mobile_account_link',
		'header_mobile_account_action',
		'header_mobile_account_modal_registration',
		'header_mobile_account_modal_registration_link',
		'header_mobile_account_style',
		'header_mobile_account_label',
		'header_mobile_account_icon_size',
		'header_mobile_account_color',
		'header_mobile_account_background',
		'header_mobile_account_radius',
		'header_mobile_account_typography',
		'header_mobile_account_padding',
		'header_mobile_account_margin',
		'header_mobile_account_in_icon',
		'header_mobile_account_in_link',
		'header_mobile_account_in_action',
		'header_mobile_account_in_dropdown_source',
		'header_mobile_account_in_style',
		'header_mobile_account_in_label',
		'header_mobile_account_in_icon_size',
		'header_mobile_account_in_image_radius',
		'header_mobile_account_in_color',
		'header_mobile_account_in_background',
		'header_mobile_account_in_radius',
		'header_mobile_account_in_typography',
		'header_mobile_account_in_padding',
		'header_mobile_account_in_margin',
		'transparent_header_mobile_account_color',
		'transparent_header_mobile_account_background',
		'transparent_header_mobile_account_in_color',
		'transparent_header_mobile_account_in_background',
		'tertiary_navigation_typography',
		'tertiary_navigation_spacing',
		'tertiary_navigation_vertical_spacing',
		'tertiary_navigation_stretch',
		'tertiary_navigation_fill_stretch',
		'tertiary_navigation_style',
		'tertiary_navigation_color',
		'tertiary_navigation_background',
		'quaternary_navigation_typography',
		'quaternary_navigation_spacing',
		'quaternary_navigation_vertical_spacing',
		'quaternary_navigation_stretch',
		'quaternary_navigation_fill_stretch',
		'quaternary_navigation_style',
		'quaternary_navigation_color',
		'quaternary_navigation_background',
		'header_divider_border',
		'header_divider_height',
		'header_divider_margin',
		'transparent_header_divider_color',
		'header_divider2_border',
		'header_divider2_height',
		'header_divider2_margin',
		'transparent_header_divider2_color',
		'header_divider3_border',
		'header_divider3_height',
		'header_divider3_margin',
		'transparent_header_divider3_color',
		'header_mobile_divider_border',
		'header_mobile_divider_height',
		'header_mobile_divider_margin',
		'transparent_header_mobile_divider_color',
		'header_mobile_divider2_border',
		'header_mobile_divider2_height',
		'header_mobile_divider2_margin',
		'transparent_header_mobile_divider2_color',
		'header_search_bar_woo',
		'header_search_bar_width',
		'header_search_bar_border',
		'header_search_bar_border_color',
		'header_search_bar_color',
		'header_search_bar_background',
		'header_search_bar_typography',
		'header_search_bar_margin',
		'transparent_header_search_bar_color',
		'transparent_header_search_bar_background',
		'transparent_header_search_bar_border',
		'sticky_header_search_bar_color',
		'sticky_header_search_bar_background',
		'sticky_header_search_bar_border',
		'header_mobile_search_bar_woo',
		'header_mobile_search_bar_width',
		'header_mobile_search_bar_border',
		'header_mobile_search_bar_border_color',
		'header_mobile_search_bar_color',
		'header_mobile_search_bar_background',
		'header_mobile_search_bar_typography',
		'header_mobile_search_bar_margin',
		'transparent_header_mobile_search_bar_color',
		'transparent_header_mobile_search_bar_background',
		'transparent_header_mobile_search_bar_border',
		'sticky_header_mobile_search_bar_color',
		'sticky_header_mobile_search_bar_background',
		'sticky_header_mobile_search_bar_border',
		'header_widget1_link_colors',
		'header_widget1_title',
		'header_widget1_content',
		'header_widget1_link_style',
		'header_widget1_margin',
		'transparent_header_widget1_color',
		'header_contact_items',
		'header_contact_item_spacing',
		'header_contact_icon_size',
		'header_contact_color',
		'header_contact_link_style',
		'header_contact_typography',
		'header_contact_margin',
		'sticky_header_contact_color',
		'transparent_header_contact_color',
		'header_mobile_contact_items',
		'header_mobile_contact_item_spacing',
		'header_mobile_contact_item_vspacing',
		'header_mobile_contact_icon_size',
		'header_mobile_contact_color',
		'header_mobile_contact_link_style',
		'header_mobile_contact_typography',
		'header_mobile_contact_margin',
		'transparent_header_mobile_contact_color',
		'header_button2_label',
		'header_button2_link',
		'header_button2_style',
		'header_button2_size',
		'header_button2_visibility',
		'header_button2_padding',
		'header_button2_typography',
		'header_button2_color',
		'header_button2_background',
		'header_button2_border_colors',
		'header_button2_border',
		'header_button2_margin',
		'header_button2_radius',
		'header_button2_shadow',
		'header_button2_shadow_hover',
		'header_button2_target',
		'header_button2_nofollow',
		'header_button2_sponsored',
		'header_button2_download',
		'transparent_header_button2_color',
		'header_sticky_button2_color',
		'mobile_button2_label',
		'mobile_button2_style',
		'mobile_button2_size',
		'mobile_button2_visibility',
		'mobile_button2_typography',
		'mobile_button2_color',
		'mobile_button2_background',
		'mobile_button2_border_colors',
		'mobile_button2_border',
		'mobile_button2_radius',
		'mobile_button2_margin',
		'mobile_button2_shadow',
		'mobile_button2_shadow_hover',
		'mobile_button2_link',
		'mobile_button2_target',
		'mobile_button2_nofollow',
		'mobile_button2_sponsored',
		'header_toggle_widget_label',
		'header_toggle_widget_icon',
		'header_toggle_widget_style',
		'header_toggle_widget_border',
		'header_toggle_widget_icon_size',
		'header_toggle_widget_color',
		'header_toggle_widget_background',
		'header_toggle_widget_typography',
		'header_toggle_widget_padding',
		'transparent_toggle_widget_color',
		'header_sticky_toggle_widget_color',
		'header_toggle_widget_side',
		'header_toggle_widget_layout',
		'header_toggle_widget_pop_width',
		'header_toggle_widget_pop_background',
		'header_toggle_widget_close_color',
		'header_widget2_link_colors',
		'header_widget2_title',
		'header_widget2_content',
		'header_widget2_link_style',
		'header_widget2_padding',
		'mobile_header_reveal_scroll_up',
		'header_mobile_cart_label',
		'header_mobile_cart_icon',
		'header_mobile_cart_style',
		'header_mobile_cart_show_total',
		'header_mobile_cart_icon_size',
		'header_mobile_cart_color',
		'header_mobile_cart_background',
		'header_mobile_cart_total_color',
		'header_mobile_cart_total_background',
		'header_mobile_cart_typography',
		'header_mobile_cart_padding',
		'header_mobile_cart_popup_side',
		'header_cart_label',
		'header_cart_icon',
		'header_cart_style',
		'header_cart_show_total',
		'header_cart_icon_size',
		'header_cart_color',
		'header_cart_background',
		'header_cart_total_color',
		'header_cart_total_background',
		'header_cart_typography',
		'header_cart_padding',
		'header_cart_popup_side',
		'header_dark_mode_switch_type',
		'header_dark_mode_switch_style',
		'header_dark_mode_light_switch_title',
		'header_dark_mode_dark_switch_title',
		'header_dark_mode_light_color',
		'header_dark_mode_light_icon',
		'header_dark_mode_dark_color',
		'header_dark_mode_colors',
		'header_dark_mode_dark_icon',
		'header_dark_mode_icon_size',
		'header_dark_mode_typography',
		'mobile_dark_mode_switch_type',
		'mobile_dark_mode_switch_style',
		'mobile_dark_mode_light_switch_title',
		'mobile_dark_mode_dark_switch_title',
		'mobile_dark_mode_light_color',
		'mobile_dark_mode_light_icon',
		'mobile_dark_mode_dark_color',
		'mobile_dark_mode_colors',
		'mobile_dark_mode_dark_icon',
		'mobile_dark_mode_icon_size',
		'mobile_dark_mode_typography',
		'dark_mode_logo',
		'dark_mode_custom_logo',
		'dark_mode_mobile_logo',
		'dark_mode_mobile_custom_logo',
	);

	/**
	 * Instance Control.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning instances of the class is Forbidden', 'kadence-pro' ), '1.0' );
	}

	/**
	 * Disable un-serializing of the class.
	 *
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing instances of the class is forbidden', 'kadence-pro' ), '1.0' );
	}

	/**
	 * Constructor function.
	 */
	public function __construct() {
		add_filter( 'kadence_theme_options_defaults', array( $this, 'add_option_defaults' ), 10 );
		add_action( 'wp', array( $this, 'init_hook_in_current_header' ), 1 );
		add_action( 'customize_register', array( $this, 'init_hook_in_current_header' ), 1 );
		add_filter( 'kadence_settings_extra_source', array( $this, 'add_current_header_source' ) );
		add_filter( 'kadence_theme_customizer_sections', array( $this, 'add_customizer_sections' ), 10 );
		add_action( 'customize_register', array( $this, 'add_settings_files' ), 1 );
		add_filter( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_customizer_scripts' ), 15 );
		add_action( 'customize_register', array( $this, 'register_controls' ), 9 );
		add_action( 'admin_init', array( $this, 'download_export_file' ) );
		//add_action( 'customize_register', array( $this, 'import_export_requests' ), 999999 );
		// Ajax Calls.
		add_action( 'wp_ajax_kadence_pro_header_import', array( $this, 'import_data' ) );
		add_action( 'wp_ajax_kadence_pro_header_remove', array( $this, 'remove_header' ) );
		add_action( 'wp_ajax_kadence_pro_header_default', array( $this, 'import_default_data' ) );
		add_action( 'wp_ajax_kadence_pro_header_export', array( $this, 'export_data' ) );
	}
	/**
	 * Imports uploaded kadence conditional header settings
	 */
	public function remove_header() {
		// Make sure we have a valid nonce.
		if ( ! wp_verify_nonce( $_POST['security'], 'kadence-conditional-header-importing' ) ) {
			return;
		}
		$option_key = sanitize_text_field( $_POST['header'] );
		if ( empty( $option_key ) ) {
			wp_send_json_error( __( 'Error removing settings, Missing option information.', 'kadence-pro' ) );
		}
		delete_option( $option_key );
		wp_send_json_success();
	}
	/**
	 * Imports uploaded kadence conditional header settings
	 */
	public function import_default_data() {
		// Make sure we have a valid nonce.
		if ( ! wp_verify_nonce( $_POST['security'], 'kadence-conditional-header-importing' ) ) {
			wp_send_json_error( __( 'Security Error, Reload Page', 'kadence-pro' ) );
		}
		$option_key = sanitize_text_field( $_POST['header'] );
		if ( empty( $option_key ) ) {
			wp_send_json_error( __( 'Error importing settings, Missing option information.', 'kadence-pro' ) );
		}
		$data    = array();
		$options = get_theme_mods();
		foreach ( $options as $key => $value ) {
			if ( in_array( $key, self::$header_keys ) ) {
				$data[ $key ] = $value;
			}
		}
		update_option( $option_key, $data );
		wp_send_json_success();
	}
	/**
	 * Imports uploaded kadence conditional header settings
	 */
	public function import_data() {
		// Make sure we have a valid nonce.
		if ( ! wp_verify_nonce( $_POST['security'], 'kadence-conditional-header-importing' ) ) {
			wp_send_json_error( __( 'Security Error, Reload Page', 'kadence-pro' ) );
		}
		// Make sure WordPress upload support is loaded.
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		// Setup global vars.
		global $wp_filesystem;

		// Setup internal vars.
		$option_key = sanitize_text_field( $_POST['header'] );
		if ( empty( $option_key ) ) {
			wp_send_json_error( __( 'Error importing settings, Missing option information.', 'kadence-pro' ) );
		}
		$template             = 'conditional-header';
		$overrides            = array( 'test_form' => false, 'test_type' => false, 'mimes' => array( 'dat' => 'text/plain' ) );
		$file                 = wp_handle_upload( $_FILES['file'], $overrides );

		// Make sure we have an uploaded file.
		if ( isset( $file['error'] ) ) {
			wp_send_json_error( $file['error'] );
			return;
		}
		if ( ! file_exists( $file['file'] ) ) {
			wp_send_json_error( __( 'Error importing settings file! Please try again.', 'kadence-pro' ) );
			return;
		}
		if ( ! is_object( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		// Get the upload data.
		$data = '';
		if ( $wp_filesystem->exists( $file['file'] ) ) {
			$raw  = $wp_filesystem->get_contents( $file['file'] );
			//$data = @unserialize( $raw );
			$data = @unserialize( base64_decode( $raw ) );
		}

		// Remove the uploaded file.
		unlink( $file['file'] );

		// Data checks.
		if ( 'array' != gettype( $data ) ) {
			wp_send_json_error( __( 'Error importing settings! Please check that you uploaded a customizer export file.', 'kadence-pro' ) );
			return;
		}
		if ( ! isset( $data['header'] ) ) {
			wp_send_json_error( __( 'Error importing settings! Please check that you uploaded a customizer export file.', 'kadence-pro' ) );
			return;
		}
		if ( $data['header'] != $template ) {
			wp_send_json_error( __( 'Error importing settings! The settings you uploaded are not for the Kadence Theme Conditional Header.', 'kadence-pro' ) );
			return;
		}

		// Import custom options.
		if ( isset( $data['options'] ) ) {
			update_option( $option_key, $data['options'] );
			wp_send_json_success();
		} else {
			wp_send_json_error( 'No Data to Import' );
		}
	}
	/**
	 * Generate and return a filename.
	 *
	 * @return string
	 */
	public function get_filename() {
		return 'kadence-header-data-export.dat';
	}
	/**
	 * Get file path to export to.
	 */
	protected function get_file_path() {
		$upload_dir = wp_upload_dir();
		return trailingslashit( $upload_dir['basedir'] ) . $this->get_filename();
	}
	/**
	 * Get the file contents.
	 */
	public function get_file() {
		$file = '';
		if ( @file_exists( $this->get_file_path() ) ) { // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			$file = @file_get_contents( $this->get_file_path() ); // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents, WordPress.WP.AlternativeFunctions.file_system_read_file_get_contents
		} else {
			@file_put_contents( $this->get_file_path(), '' ); // phpcs:ignore WordPress.VIP.FileSystemWritesDisallow.file_ops_file_put_contents, Generic.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
			@chmod( $this->get_file_path(), 0664 ); // phpcs:ignore WordPress.VIP.FileSystemWritesDisallow.chmod_chmod, WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents, Generic.PHP.NoSilencedErrors.Discouraged
		}
		return $file;
	}
	/**
	 * Serve the generated file.
	 */
	public function download_export_file() {
		if ( isset( $_GET['action'], $_GET['nonce'] ) && wp_verify_nonce( wp_unslash( $_GET['nonce'] ), 'export-header' ) && 'download_header_data' === wp_unslash( $_GET['action'] ) ) { // WPCS: input var ok, sanitization ok.
			$this->export();
		}
	}
	/**
	 * Export conditional header settings.
	 */
	public function export() {
		$charset  = get_option( 'blog_charset' );
		// Set the download headers.
		header( 'Content-disposition: attachment; filename=' . $this->get_filename() );
		header( 'Content-Type: application/octet-stream; charset=' . $charset );

		echo $this->get_file();
		@unlink( $this->get_file_path() ); // phpcs:ignore WordPress.VIP.FileSystemWritesDisallow.file_ops_unlink, Generic.PHP.NoSilencedErrors.Discouraged
		// Start the download.
		die();
	}
	/**
	 * Export conditional header settings.
	 */
	public function export_data() {
		if ( ! wp_verify_nonce( $_POST['security'], 'kadence-conditional-header-exporting' ) ) {
			return;
		}
		$option_key = sanitize_text_field( $_POST['header'] );
		if ( empty( $option_key ) ) {
			wp_send_json_error( __( 'Error importing settings, Missing option information.', 'kadence-pro' ) );
		}
		$charset  = get_option( 'blog_charset' );
		$data     = array(
			'header'  => 'conditional-header',
			'options' => get_option( $option_key ),
		);
		$file = $this->get_file();
		$file .= base64_encode( serialize( $data ) );
		@file_put_contents( $this->get_file_path(), $file ); // phpcs:ignore WordPress.VIP.FileSystemWritesDisallow.file_ops_file_put_contents, Generic.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents

		$query_args = array(
			'nonce'    => wp_create_nonce( 'export-header' ),
			'action'   => 'download_header_data',
			'filename' => $this->get_filename(),
		);
		wp_send_json_success(
			array(
				'success'    => 'done',
				'url'        => add_query_arg( $query_args, admin_url( 'customize.php' ) ),
			)
		);
		// // Set the download headers.
		// header( 'Content-disposition: attachment; filename=kadence-header-data-export.dat' );
		// header( 'Content-Type: application/octet-stream; charset=' . $charset );

		// // Serialize the export data.
		// echo base64_encode( serialize( $data ) );

		// // Start the download.
		// die();
	}
	/**
	 * let the settings condtionals know to look for another source.
	 *
	 * @param boolean $source if the theme should check sources.
	 */
	public function add_current_header_source( $source ) {
		$preview_header = kadence()->option( 'current_header_preview' );
		if ( ! empty( $preview_header ) ) {
			return $preview_header;
		}
		return false;
	}
	/**
	 * Enqueue Customizer scripts
	 *
	 * @access public
	 * @return void
	 */
	public function enqueue_customizer_scripts() {
		$wp_scripts = wp_scripts();
		$script     = $wp_scripts->query( 'kadence-customizer-controls', 'registered' );

		if ( ! $script ) {
			return;
		}
		$editor_dependencies = array(
			'jquery',
			'customize-controls',
			'wp-i18n',
			'wp-components',
			'wp-edit-post',
			'wp-element',
			'lodash',
			'react',
			'react-dom',
			'wp-compose',
			'wp-polyfill',
			'wp-primitives',
		);
		$path = KTP_URL . 'build/';
		wp_enqueue_script( 'kadence-pro-customizer-controls', $path . 'customizer.js', $editor_dependencies, KTP_VERSION, true );
		wp_enqueue_style( 'kadence-conditional-controls', KTP_URL . '/dist/build/customizer-controls.css', false, KTP_VERSION );
		wp_localize_script(
			'kadence-pro-customizer-controls',
			'kadenceCustomizerConditionalData',
			array(
				'display'    => $this->get_display_options(),
				'user'       => $this->get_user_options(),
				'taxonomies' => $this->get_taxonomies(),
				'authors'    => $this->get_author_options(),
				'languageSettings' => $this->get_language_options(),
				'timeFormat' => get_option('time_format'),
				'restBase'           => esc_url_raw( get_rest_url() ),
				'postSelectEndpoint' => '/ktp/v1/post-select',
				'resetConfirm'   => __( "Attention! This will remove all customizations to this header!\n\nThis action is irreversible!", 'kadence-pro' ),
				'emptyImport'	 => __( 'Please choose a file to import.', 'kadence-pro' ),
				'conditional_url'	 => admin_url( 'customize.php' ) . '?autofocus%5Bsection%5D=kadence_customizer_conditional_header',
				'header_url'	 => admin_url( 'customize.php' ) . '?autofocus%5Bpanel%5D=kadence_customizer_header',
				'ajax_url'       => admin_url( 'admin-ajax.php' ),
				'nonce'          => array(
					'export' => wp_create_nonce( 'kadence-conditional-header-exporting' ),
					'import' => wp_create_nonce( 'kadence-conditional-header-importing' ),
				),
			)
		);
		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'kadence-pro-customizer-controls', 'kadence-pro' );
		}
	}
	/**
	 * Get all language Options
	 */
	public function get_language_options() {
		$languages_options = array();
		// Check for Polylang.
		if ( function_exists( 'pll_the_languages' ) ) {
			$languages = pll_the_languages( array( 'raw' => 1 ) );
			foreach ( $languages as $lang ) {
				$languages_options[] = array(
					'value' => $lang['slug'],
					'label' => $lang['name'],
				);
			}
		}
		// Check for WPML.
		if ( defined( 'WPML_PLUGIN_FILE' ) ) {
			$languages = apply_filters( 'wpml_active_languages', array() );
			foreach ( $languages as $lang ) {
				$languages_options[] = array(
					'value' => $lang['code'],
					'label' => $lang['native_name'],
				);
			}
		}
		return apply_filters( 'kadence_pro_conditional_header_display_languages', $languages_options );
	}
	/**
	 * Get all taxonomies
	 */
	public function get_taxonomies() {
		$output = array();
		$kadence_public_post_types = kadence()->get_post_types();
		$ignore_types              = kadence()->get_public_post_types_to_ignore();
		foreach ( $kadence_public_post_types as $post_type ) {
			$post_type_item  = get_post_type_object( $post_type );
			$post_type_name  = $post_type_item->name;
			if ( ! in_array( $post_type_name, $ignore_types, true ) ) {
				$taxonomies = get_object_taxonomies( $post_type, 'objects' );
				$taxs = array();
				$taxs_archive = array();
				foreach ( $taxonomies as $term_slug => $term ) {
					if ( ! $term->public || ! $term->show_ui ) {
						continue;
					}
					//$taxs[ $term_slug ] = $term;
					$taxs[ $term_slug ] = array(
						'name' => $term->name,
						'label' => $term->label,
					);
					$terms = get_terms( $term_slug );
					$term_items = array();
					if ( ! empty( $terms ) ) {
						foreach ( $terms as $term_key => $term_item ) {
							$term_items[] = array(
								'value' => $term_item->term_id,
								'label' => $term_item->name,
							);
						}
						$output[ $post_type ]['terms'][ $term_slug ] = $term_items;
						$output['taxs'][ $term_slug ] = $term_items;
					}
				}
				if ( 'sfwd-lessons' === $post_type ) {
					$taxs['assigned_course'] = array(
						'name' => 'assigned_course',
						'label' => __( 'Assigned Course', 'kadence-pro' ),
					);
					$args = array(
						'post_type'              => 'sfwd-courses',
						'no_found_rows'          => true,
						'update_post_term_cache' => false,
						'post_status'            => 'publish',
						'numberposts'            => 333,
						'order'                  => 'ASC',
						'orderby'                => 'menu_order',
						'suppress_filters'       => false,
					);
					$course_posts = get_posts( $args );
					if ( $course_posts && ! empty( $course_posts ) ) {
						foreach ( $course_posts as $course_post ) {
							$term_items[] = array(
								'value' => $course_post->ID,
								'label' => get_the_title( $course_post->ID ),
							);
						}
						$output[ $post_type ]['terms']['assigned_course'] = $term_items;
						$output['taxs']['assigned_course'] = $term_items;
					}
				}
				$output[ $post_type ]['taxonomy'] = $taxs;
			}
		}
		return apply_filters( 'kadence_pro_conditional_header_display_taxonomies', $output );
	}
	/**
	 * Get all Author Options
	 */
	public function get_author_options() {
		$roles__in = array();
		foreach ( wp_roles()->roles as $role_slug => $role ) {
			if ( ! empty( $role['capabilities']['edit_posts'] ) ) {
				$roles__in[] = $role_slug;
			}
		}
		$authors = get_users( array( 'roles__in' => $roles__in, 'fields' => array( 'ID', 'display_name' ) ) );
		$output = array();
		foreach ( $authors as $key => $author ) {
			$output[] = array(
				'value' => $author->ID,
				'label' => $author->display_name,
			);
		}
		return apply_filters( 'kadence_pro_conditional_header_display_authors', $output );
	}
	/**
	 * Get all Display Options
	 */
	public function get_display_options() {
		$display_general = array(
			array(
				'label' => esc_attr__( 'General', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'general|site',
						'label' => esc_attr__( 'Entire Site', 'kadence-pro' ),
					),
					array(
						'value' => 'general|front_page',
						'label' => esc_attr__( 'Front Page', 'kadence-pro' ),
					),
					array(
						'value' => 'general|home',
						'label' => esc_attr__( 'Blog Page', 'kadence-pro' ),
					),
					array(
						'value' => 'general|search',
						'label' => esc_attr__( 'Search Results', 'kadence-pro' ),
					),
					array(
						'value' => 'general|404',
						'label' => esc_attr__( 'Not Found (404)', 'kadence-pro' ),
					),
					array(
						'value' => 'general|singular',
						'label' => esc_attr__( 'All Singular', 'kadence-pro' ),
					),
					array(
						'value' => 'general|archive',
						'label' => esc_attr__( 'All Archives', 'kadence-pro' ),
					),
					array(
						'value' => 'general|author',
						'label' => esc_attr__( 'Author Archives', 'kadence-pro' ),
					),
					array(
						'value' => 'general|date',
						'label' => esc_attr__( 'Date Archives', 'kadence-pro' ),
					),
					array(
						'value' => 'general|paged',
						'label' => esc_attr__( 'Paged', 'kadence-pro' ),
					),
				),
			),
		);
		$kadence_public_post_types = kadence()->get_post_types();
		if ( defined( 'TRIBE_EVENTS_FILE' ) ) {
			$kadence_public_post_types = array_merge( $kadence_public_post_types, array( 'tribe_events' ) );
		}
		$ignore_types              = kadence()->get_public_post_types_to_ignore();
		$display_singular = array();
		foreach ( $kadence_public_post_types as $post_type ) {
			$post_type_item  = get_post_type_object( $post_type );
			$post_type_name  = $post_type_item->name;
			$post_type_label = $post_type_item->label;
			$post_type_label_plural = $post_type_item->labels->name;
			if ( ! in_array( $post_type_name, $ignore_types, true ) ) {
				$post_type_options = array(
					array(
						'value' => 'singular|' . $post_type_name,
						'label' => esc_attr__( 'Single', 'kadence-pro' ) . ' ' . $post_type_label_plural,
					),
				);
				$post_type_tax_objects = get_object_taxonomies( $post_type, 'objects' );
				foreach ( $post_type_tax_objects as $taxonomy_slug => $taxonomy ) {
					if ( $taxonomy->public && $taxonomy->show_ui && 'post_format' !== $taxonomy_slug ) {
						$post_type_options[] = array(
							'value' => 'tax_archive|' . $taxonomy_slug,
							/* translators: %1$s: taxonomy singular label.  */
							'label' => sprintf( esc_attr__( '%1$s Archives', 'kadence-pro' ), $taxonomy->labels->singular_name ),
						);
					}
				}
				if ( ! empty( $post_type_item->has_archive ) ) {
					$post_type_options[] = array(
						'value' => 'post_type_archive|' . $post_type_name,
						/* translators: %1$s: post type plural label  */
						'label' => sprintf( esc_attr__( '%1$s Archive', 'kadence-pro' ), $post_type_label_plural ),
					);
				}
				if ( class_exists( 'woocommerce' ) && 'product' === $post_type_name ) {
					$post_type_options[] = array(
						'value' => 'general|product_search',
						/* translators: %1$s: post type plural label  */
						'label' => sprintf( esc_attr__( '%1$s Search', 'kadence-pro' ), $post_type_label_plural ),
					);
				}
				$display_singular[] = array(
					'label' => $post_type_label,
					'options' => $post_type_options,
				);
			}
		}
		if ( class_exists( 'TUTOR\Tutor' ) && function_exists( 'tutor' ) ) {
			// Add lesson post type.
			$post_type_item  = get_post_type_object( tutor()->lesson_post_type );
			if ( $post_type_item ) {
				$post_type_name  = $post_type_item->name;
				$post_type_label = $post_type_item->label;
				$post_type_label_plural = $post_type_item->labels->name;
				$post_type_options = array(
					array(
						'value' => 'tutor|' . $post_type_name,
						'label' => esc_attr__( 'Single', 'kadence-pro' ) . ' ' . $post_type_label_plural,
					),
				);
				$display_singular[] = array(
					'label' => $post_type_label,
					'options' => $post_type_options,
				);
			}
		}
		$display = array_merge( $display_general, $display_singular );
		return apply_filters( 'kadence_pro_conditional_header_display_options', $display );
	}
	/**
	 * Get all Display Options
	 */
	public function get_user_options() {
		$user_basic = array(
			array(
				'label' => esc_attr__( 'Basic', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'public',
						'label' => esc_attr__( 'All Users', 'kadence-pro' ),
					),
					array(
						'value' => 'logged_out',
						'label' => esc_attr__( 'Logged out Users', 'kadence-pro' ),
					),
					array(
						'value' => 'logged_in',
						'label' => esc_attr__( 'Logged in Users', 'kadence-pro' ),
					),
				),
			),
		);
		$user_roles = array();
		$specific_roles = array();
		foreach ( get_editable_roles() as $role_slug => $role_info ) {
			$specific_roles[] = array(
				'value' => $role_slug,
				'label' => $role_info['name'],
			);
		}
		$user_roles[] = array(
			'label' => esc_attr__( 'Specific Role', 'kadence-pro' ),
			'options' => $specific_roles,
		);
		$roles = array_merge( $user_basic, $user_roles );
		return apply_filters( 'kadence_pro_conditional_header_user_options', $roles );
	}
	/**
	 * Provide a new source for options.
	 * @param string $option_key a custom setting key.
	 * @param string $key the current option key.
	 * @return string
	 */
	public function init_hook_in_current_header() {
		// Handle Preview.
		$current_header = '';
		if ( is_customize_preview() ) {
			$preview_header = kadence()->option( 'current_header_preview' );
			if ( ! empty( $preview_header ) ) {
				$current_header = $preview_header;
			}
		} else {
			$conditional_headers = kadence()->option( 'conditional_headers' );
			$showing_headers = array();
			$the_contitional_headers = array();
			if ( isset( $conditional_headers['items'] ) && is_array( $conditional_headers['items'] ) ) {
				$the_contitional_headers = $conditional_headers['items'];
			}
			if ( ! empty( $the_contitional_headers ) ) {
				foreach ( $the_contitional_headers as $key => $header ) {
					if ( apply_filters( 'kadence_conditional_header_display', $this->check_header_conditionals( $header ), $header ) ) {
						$current_header = $header['id'];
						// We can only show one header and the order already sets priority so first item to pass is the header to output.
						break;
					}
				}
			}
		}
		if ( ! empty( $current_header ) ) {
			add_filter(
				'kadence_settings_key_custom_mapping',
				function( $option_key, $key ) use( $current_header ) {
					if ( ! in_array( $key, self::$header_keys ) ) {
						return $option_key;
					}
					return $current_header;
				},
				10,
				2
			);
		}
	}
	/**
	 * Check if header should show in current page.
	 *
	 * @param array $header the current header to check.
	 * @return bool
	 */
	public function check_header_conditionals( $header ) {
		$show = false;
		// Check if enabled first and return false if not.
		if ( ! isset( $header['enabled'] ) || isset( $header['enabled'] ) && ! $header['enabled'] ) {
			return $show;
		}
		$current_condition      = $this->get_current_page_conditions();
		$rules_with_sub_rules   = array( 'singular', 'tax_archive' );
		$all_must_be_true = ( isset( $header['all_show'] ) ? $header['all_show'] : false );
		if ( ! empty( $header['show'] ) ) {
			$header_show = json_decode( $header['show'] , true );
			if ( is_array( $header_show ) ) {
				foreach ( $header_show as $key => $rule ) {
					$rule_show = false;
					if ( isset( $rule['rule'] ) && in_array( $rule['rule'], $current_condition ) ) {
						$rule_split = explode( '|', $rule['rule'], 2 );
						if ( in_array( $rule_split[0], $rules_with_sub_rules ) ) {
							if ( ! isset( $rule['select'] ) || isset( $rule['select'] ) && 'all' === $rule['select'] ) {
								$show      = true;
								$rule_show = true;
							} else if ( isset( $rule['select'] ) && 'author' === $rule['select'] ) {
								if ( isset( $rule['subRule'] ) && $rule['subRule'] == get_post_field( 'post_author', get_queried_object_id() ) ) {
									$show      = true;
									$rule_show = true;
								}
							} else if ( isset( $rule['select'] ) && 'tax' === $rule['select'] ) {
								if ( isset( $rule['subRule'] ) && isset( $rule['subSelection'] ) && is_array( $rule['subSelection'] ) ) {
									foreach ( $rule['subSelection'] as $sub_key => $selection ) {
										if ( 'assigned_course' === $rule['subRule'] ) {
											$course_id = get_post_meta( get_queried_object_id(), 'course_id', true );
											if ( $selection['value'] == $course_id ) {
												$show      = true;
												$rule_show = true;
											} elseif ( isset( $rule['mustMatch'] ) && $rule['mustMatch'] ) {
												return false;
											}
										} elseif ( has_term( $selection['value'], $rule['subRule'] ) ) {
											$show      = true;
											$rule_show = true;
										} elseif ( $this->post_is_in_descendant_term( $selection['value'], $rule['subRule'] ) ) {
											$show      = true;
											$rule_show = true;
										} elseif ( isset( $rule['mustMatch'] ) && $rule['mustMatch'] ) {
											return false;
										}
									}
								}
							} else if ( isset( $rule['select'] ) && 'ids' === $rule['select'] ) {
								if ( isset( $rule['ids'] ) && is_array( $rule['ids'] ) ) {
									$current_id = get_the_ID();
									foreach ( $rule['ids'] as $sub_key => $sub_id ) {
										if ( $current_id === $sub_id ) {
											$show      = true;
											$rule_show = true;
										}
									}
								}
							} else if ( isset( $rule['select'] ) && 'individual' === $rule['select'] ) {
								if ( isset( $rule['subSelection'] ) && is_array( $rule['subSelection'] ) ) {
									$queried_obj = get_queried_object();
									$show_taxs   = array();
									foreach ( $rule['subSelection'] as $sub_key => $selection ) {
										if ( isset( $selection['value'] ) && ! empty( $selection['value'] ) ) {
											$show_taxs[] = $selection['value'];
										}
									}
									if ( in_array( $queried_obj->term_id, $show_taxs ) ) {
										$show      = true;
										$rule_show = true;
									}
								}
							}
						} else {
							$show      = true;
							$rule_show = true;
						}
					}
					if ( ! $rule_show && $all_must_be_true ) {
						return false;
					}
				}
			}
		}
		// Exclude Rules, we only need to check these if we are currently set to show.
		if ( $show ) {
			if ( ! empty( $header['hide'] ) ) {
				$header_hide = json_decode( $header['hide'], true );
				if ( is_array( $header_hide ) ) {
					foreach ( $header_hide as $key => $rule ) {
						if ( isset( $rule['rule'] ) && in_array( $rule['rule'], $current_condition ) ) {
							$rule_split = explode( '|', $rule['rule'], 2 );
							if ( in_array( $rule_split[0], $rules_with_sub_rules ) ) {
								if ( ! isset( $rule['select'] ) || isset( $rule['select'] ) && 'all' === $rule['select'] ) {
									$show = false;
								} else if ( isset( $rule['select'] ) && 'author' === $rule['select'] ) {
									if ( isset( $rule['subRule'] ) && $rule['subRule'] == get_post_field( 'post_author', get_queried_object_id() ) ) {
										$show = false;
									}
								} else if ( isset( $rule['select'] ) && 'tax' === $rule['select'] ) {
									if ( isset( $rule['subRule'] ) && isset( $rule['subSelection'] ) && is_array( $rule['subSelection'] ) ) {
										foreach ( $rule['subSelection'] as $sub_key => $selection ) {
											if ( 'assigned_course' === $rule['subRule'] ) {
												$course_id = get_post_meta( get_queried_object_id(), 'course_id', true );
												if ( $selection['value'] == $course_id ) {
													$show = false;
												} elseif ( isset( $rule['mustMatch'] ) && $rule['mustMatch'] ) {
													$show = true;
													continue;
												}
											} elseif ( has_term( $selection['value'], $rule['subRule'] ) ) {
												$show = false;
											} elseif ( isset( $rule['mustMatch'] ) && $rule['mustMatch'] ) {
												$show = true;
												continue;
											}
										}
									}
								} else if ( isset( $rule['select'] ) && 'ids' === $rule['select'] ) {
									if ( isset( $rule['ids'] ) && is_array( $rule['ids'] ) ) {
										$current_id = get_the_ID();
										foreach ( $rule['ids'] as $sub_key => $sub_id ) {
											if ( $current_id === $sub_id ) {
												$show = false;
											}
										}
									}
								} else if ( isset( $rule['select'] ) && 'individual' === $rule['select'] ) {
									if ( isset( $rule['subSelection'] ) && is_array( $rule['subSelection'] ) ) {
										$queried_obj = get_queried_object();
										$show_taxs   = array();
										foreach ( $rule['subSelection'] as $sub_key => $selection ) {
											if ( isset( $selection['value'] ) && ! empty( $selection['value'] ) ) {
												$show_taxs[] = $selection['value'];
											}
										}
										if ( in_array( $queried_obj->term_id, $show_taxs ) ) {
											$show = false;
										}
									}
								}
							} else {
								$show = false;
							}
						}
					}
				}
			}
		}
		// User Rules, we only need to check these if we are currently set to show.
		if ( $show ) {
			if ( ! empty( $header['user'] ) ) {
				$header_user = json_decode( $header['user'], true );
				if ( is_array( $header_user ) && ! empty( $header_user ) ) {
					$user_info  = self::get_current_user_info();
					$show_roles = array();
					foreach ( $header_user as $key => $user_rule ) {
						if ( isset( $user_rule['role'] ) && ! empty( $user_rule['role'] ) ) {
							$show_roles[] = $user_rule['role'];
						}
					}
					$match = array_intersect( $show_roles, $user_info );
					if ( count( $match ) === 0 ) {
						$show = false;
					}
				}
			}
		}
		// User Hide Rules, we only need to check these if we are currently set to show.
		if ( $show ) {
			if ( ! empty( $header['user_hide'] ) ) {
				$header_hide_user = json_decode( $header['user_hide'], true );
				if ( is_array( $header_hide_user ) && ! empty( $header_hide_user ) ) {
					$user_info  = self::get_current_user_info();
					$hide_roles = array();
					foreach ( $header_hide_user as $key => $user_rule ) {
						if ( isset( $user_rule['role'] ) && ! empty( $user_rule['role'] ) ) {
							$hide_roles[] = $user_rule['role'];
						}
					}
					$match = array_intersect( $hide_roles, $user_info );
					if ( count( $match ) !== 0 ) {
						$show = false;
					}
				}
			}
		}
		// Expires Rules, we only need to check these if we are currently set to show.
		if ( $show ) {
			if ( isset( $header['enable_expires'] ) && true == $header['enable_expires'] && isset( $header['expires'] ) && ! empty( $header['expires'] ) ) {
				$expires = strtotime( get_date_from_gmt( $header['expires'] ) );
				$now     = strtotime( get_date_from_gmt( current_time( 'Y-m-d H:i:s' ) ) );
				if ( $expires < $now ) {
					$show = false;
				}
			}
		}
		// Language.
		if ( $show ) {
			if ( ! empty( $header['language'] ) ) {
				if ( function_exists( 'pll_current_language' ) ) {
					$language_slug = pll_current_language( 'slug' );
					if ( $header['language'] !== $language_slug ) {
						$show = false;
					}
				}
				if ( $current_lang = apply_filters( 'wpml_current_language', NULL ) ) {
					if ( $header['language'] !== $current_lang ) {
						$show = false;
					}
				}
			}
		}
		return $show;
	}
	/**
	 * Gets and returns page conditions.
	 */
	public function get_current_page_conditions() {
		if ( is_null( self::$current_condition ) ) {
			$condition   = array( 'general|site' );
			if ( is_front_page() ) {
				$condition[] = 'general|front_page';
			}
			if ( is_home() ) {
				$condition[] = 'general|archive';
				$condition[] = 'post_type_archive|post';
				$condition[] = 'general|home';
			} elseif ( is_search() ) {
				$condition[] = 'general|search';
				if ( class_exists( 'woocommerce' ) && function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
					$condition[] = 'general|product_search';
				}
			} elseif ( is_404() ) {
				$condition[] = 'general|404';
			} elseif ( is_singular() ) {
				$condition[] = 'general|singular';
				$condition[] = 'singular|' . get_post_type();
				if ( class_exists( 'TUTOR\Tutor' ) && function_exists( 'tutor' ) ) {
					// Add lesson post type.
					if ( is_singular( tutor()->lesson_post_type ) ) {
						$condition[] = 'tutor|' . get_post_type();
					}
				}
			} elseif ( is_archive() ) {
				$queried_obj = get_queried_object();
				$condition[] = 'general|archive';
				if ( is_post_type_archive() && is_object( $queried_obj ) ) {
					$condition[] = 'post_type_archive|' . $queried_obj->name;
				} elseif ( is_tax() || is_category() || is_tag() ) {
					if ( is_object( $queried_obj ) ) {
						$condition[] = 'tax_archive|' . $queried_obj->taxonomy;
					}
				} elseif ( is_date() ) {
					$condition[] = 'general|date';
				} elseif ( is_author() ) {
					$condition[] = 'general|author';
				}
			}
			if ( is_paged() ) {
				$condition[] = 'general|paged';
			}
			if ( class_exists( 'woocommerce' ) ) {
				if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
					$condition[] = 'general|woocommerce';
				}
			}
			self::$current_condition = $condition;
		}
		return self::$current_condition;
	}
	/**
	 * Tests if any of a post's assigned term are descendants of target term
	 *
	 * @param string $term_id The term id.
	 * @param string $tax The target taxonomy slug.
	 * @return bool True if at least 1 of the post's categories is a descendant of any of the target categories
	 */
	public function post_is_in_descendant_term( $term_id, $tax ) {
		$descendants = get_term_children( (int)$term_id, $tax );
		if ( ! is_wp_error( $descendants ) && is_array( $descendants ) ) {
			foreach ( $descendants as $child_id ) {
				if ( has_term( $child_id, $tax ) ) {
					return true;
				}
			}
		}
		return false;
	}
	/**
	 * Get current user information.
	 */
	public static function get_current_user_info() {
		if ( is_null( self::$current_user ) ) {
			$user_info = array( 'public' );
			if ( is_user_logged_in() ) {
				$user_info[] = 'logged_in';
				$user = wp_get_current_user();
				$user_info = array_merge( $user_info, $user->roles );
			} else {
				$user_info[] = 'logged_out';
			}

			self::$current_user = $user_info;
		}
		return self::$current_user;
	}
	/**
	 * Add Defaults
	 *
	 * @access public
	 * @param array $defaults registered option defaults with kadence theme.
	 * @return array
	 */
	public function add_option_defaults( $defaults ) {
		$conditional_headers = array(
			// Mobile Navigation.
			'current_header_preview' => '',
			'conditional_headers'  => array(
				'items' => array(
					array(
						'id'             => 'kadence_conditional_header_01',
						'label'          => 'Extra Header',
						'enabled'        => false,
						'show'           => '',
						'all_show'       => false,
						'hide'           => '',
						'user'           => '',
						'enable_expires' => false,
						'expires'        => '',
					),
				),
			),
		);
		$defaults = array_merge(
			$defaults,
			$conditional_headers
		);
		return $defaults;
	}
	/**
	 * Add Sections
	 *
	 * @access public
	 * @param array $sections registered sections with kadence theme.
	 * @return array
	 */
	public function add_customizer_sections( $sections ) {
		$sections['conditional_header']        = array(
			'title'    => __( 'Conditional Headers', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		return $sections;
	}
	/**
	 * Add settings
	 *
	 * @access public
	 * @param object $wp_customize the customizer object.
	 * @return void
	 */
	public function add_settings_files( $wp_customize ) {
		// Load Settings files.
		require_once KTP_PATH . 'dist/conditional-headers/conditional-header-options.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
	/**
	 * Add Controls
	 *
	 * @access public
	 * @param object $wp_customize the customizer object.
	 * @return void
	 */
	public function register_controls( $wp_customize ) {
		require_once KTP_PATH . 'dist/conditional-headers/class-kadence-control-conditional.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once KTP_PATH . 'dist/conditional-headers/class-kadence-control-conditional-select.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once KTP_PATH . 'dist/conditional-headers/class-kadence-control-conditional-heading.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

Conditional_Headers::get_instance();
