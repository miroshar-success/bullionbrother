<?php
/**
 * Class for the Customizer
 *
 * @package Kadence
 */

namespace Kadence_Pro;

use function Kadence\kadence;
use Kadence\Kadence_CSS;
use Kadence_Blocks_Frontend;

/**
 * Main plugin class
 */
class Header_Addons {
	/**
	 * Instance Control
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Associative array of Google Fonts to load.
	 *
	 * Do not access this property directly, instead use the `get_google_fonts()` method.
	 *
	 * @var array
	 */
	protected static $google_fonts = array();

	/**
	 * Holds theme settings array sections.
	 *
	 * @var the theme settings sections.
	 */
	public static $settings_sections = array(
		'header-account',
		'header-mobile-account',
		'header-html2',
		'header-mobile-html2',
		'header-tertiary-navigation',
		'header-quaternary-navigation',
		'header-divider3',
		'header-divider2',
		'header-divider',
		'header-mobile-divider2',
		'header-mobile-divider',
		'header-widget-area',
		'header-toggle-widget',
		'header-contact',
		'header-search-bar',
		'header-mobile-search-bar',
		'header-mobile-contact',
		// 'header-html3',
		// 'header-mobile-html3',
		'header-button2',
		'header-mobile-button2',
		// 'header-button3',
		// 'header-mobile-button3',
		'header-mobile-nav2',
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
		add_filter( 'customizer_widgets_section_args', array( $this, 'customizer_custom_widget_areas' ), 10, 3 );
		add_filter( 'kadence_theme_options_defaults', array( $this, 'add_option_defaults' ), 10 );
		add_filter( 'kadence_theme_customizer_sections', array( $this, 'add_customizer_sections' ), 10 );
		add_filter( 'kadence_theme_customizer_control_choices', array( $this, 'add_customizer_header_choices' ), 10 );
		add_action( 'customize_register', array( $this, 'create_pro_settings_array' ), 1 );
		add_action( 'get_template_part_template-parts/header/html2', array( $this, 'header_html2_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/mobile-html2', array( $this, 'header_mobile_html2_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/account', array( $this, 'header_account_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/mobile-account', array( $this, 'header_mobile_account_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/navigation-3', array( $this, 'header_navigation3_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/navigation-4', array( $this, 'header_navigation4_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/divider', array( $this, 'header_divider_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/divider2', array( $this, 'header_divider2_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/divider3', array( $this, 'header_divider3_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/mobile-divider', array( $this, 'header_mobile_divider_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/mobile-divider2', array( $this, 'header_mobile_divider2_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/search-bar', array( $this, 'header_search_bar_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/widget1', array( $this, 'header_widget1_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/contact', array( $this, 'header_contact_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/mobile-contact', array( $this, 'header_mobile_contact_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/mobile-search-bar', array( $this, 'header_mobile_search_bar_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/button2', array( $this, 'header_button2_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/mobile-button2', array( $this, 'header_mobile_button2_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/toggle-widget', array( $this, 'header_toggle_widget_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/mobile-nav2', array( $this, 'header_mobile_secondary_navigation_output' ), 10 );
		add_action( 'after_setup_theme', array( $this, 'load_actions' ), 20 );
		add_action( 'after_setup_theme', array( $this, 'action_register_nav_menus' ), 20 );
		add_filter( 'kadence_dynamic_css', array( $this, 'dynamic_css' ), 20 );
		add_action( 'wp_head', array( $this, 'frontend_gfonts' ), 80 );
		add_action( 'widgets_init', array( $this, 'action_register_sidebars' ) );
	}
	/**
	 * Enqueue Frontend Fonts
	 */
	public function frontend_gfonts() {
		if ( empty( self::$google_fonts ) ) {
			return;
		}
		if ( class_exists( 'Kadence_Blocks_Frontend' ) ) {
			$ktblocks_instance = Kadence_Blocks_Frontend::get_instance();
			foreach ( self::$google_fonts as $key => $font ) {
				if ( ! array_key_exists( $key, $ktblocks_instance::$gfonts ) ) {
					$add_font = array(
						'fontfamily'   => $font['fontfamily'],
						'fontvariants' => ( isset( $font['fontvariants'] ) && ! empty( $font['fontvariants'] ) && is_array( $font['fontvariants'] ) ? $font['fontvariants'] : array() ),
						'fontsubsets'  => ( isset( $font['fontsubsets'] ) && ! empty( $font['fontsubsets'] ) && is_array( $font['fontsubsets'] ) ? $font['fontsubsets'] : array() ),
					);
					$ktblocks_instance::$gfonts[ $key ] = $add_font;
				} else {
					foreach ( $font['fontvariants'] as $variant ) {
						if ( ! in_array( $variant, $ktblocks_instance::$gfonts[ $key ]['fontvariants'], true ) ) {
							array_push( $ktblocks_instance::$gfonts[ $key ]['fontvariants'], $variant );
						}
					}
				}
			}
		} else {
			add_filter( 'kadence_theme_google_fonts_array', array( $this, 'filter_in_fonts' ) );
		}
	}
	/**
	 * Filters in pro fronts for output with free.
	 *
	 * @param array $font_array any custom css.
	 * @return array
	 */
	public function filter_in_fonts( $font_array ) {
		// Enqueue Google Fonts.
		foreach ( self::$google_fonts as $key => $font ) {
			if ( ! array_key_exists( $key, $font_array ) ) {
				$add_font = array(
					'fontfamily'   => $font['fontfamily'],
					'fontvariants' => ( isset( $font['fontvariants'] ) && ! empty( $font['fontvariants'] ) && is_array( $font['fontvariants'] ) ? $font['fontvariants'] : array() ),
					'fontsubsets'  => ( isset( $font['fontsubsets'] ) && ! empty( $font['fontsubsets'] ) && is_array( $font['fontsubsets'] ) ? $font['fontsubsets'] : array() ),
				);
				$font_array[ $key ] = $add_font;
			} else {
				foreach ( $font['fontvariants'] as $variant ) {
					if ( ! in_array( $variant, $font_array[ $key ]['fontvariants'], true ) ) {
						array_push( $font_array[ $key ]['fontvariants'], $variant );
					}
				}
			}
		}
		return $font_array;
	}
	/**
	 * Generates the dynamic css based on customizer options.
	 *
	 * @param string $css any custom css.
	 * @return string
	 */
	public function dynamic_css( $css ) {
		$generated_css = $this->generate_pro_header_css();
		if ( ! empty( $generated_css ) ) {
			$css .= "\n/* Kadence Pro Header CSS */\n" . $generated_css;
		}
		return $css;
	}
	/**
	 * Generates the dynamic css based on page options.
	 *
	 * @return string
	 */
	public function generate_pro_header_css() {
		$css                    = new Kadence_CSS();
		$media_query            = array();
		$media_query['mobile']  = apply_filters( 'kadence_mobile_media_query', '(max-width: 767px)' );
		$media_query['tablet']  = apply_filters( 'kadence_tablet_media_query', '(max-width: 1024px)' );
		$media_query['desktop'] = apply_filters( 'kadence_tablet_media_query', '(min-width: 1025px)' );
		// Account CSS.
		$css->set_selector( '.header-navigation-dropdown-direction-left ul ul.submenu, .header-navigation-dropdown-direction-left ul ul.sub-menu' );
		$css->add_property( 'right', '0px' );
		$css->add_property( 'left', 'auto' );
		$css->set_selector( '.rtl .header-navigation-dropdown-direction-right ul ul.submenu, .rtl .header-navigation-dropdown-direction-right ul ul.sub-menu' );
		$css->add_property( 'left', '0px' );
		$css->add_property( 'right', 'auto' );
		if ( ! is_user_logged_in() || ( is_customize_preview() && 'out' === kadence()->option( 'header_account_preview' ) ) ) {
			$css->set_selector( '.header-account-button .nav-drop-title-wrap > .kadence-svg-iconset, .header-account-button > .kadence-svg-iconset' );
			$css->add_property( 'font-size', kadence()->sub_option( 'header_account_icon_size', 'size' ) . kadence()->sub_option( 'header_account_icon_size', 'unit' ) );
			$css->set_selector( '.site-header-item .header-account-button .nav-drop-title-wrap, .site-header-item .header-account-wrap > .header-account-button' );
			$css->add_property( 'display', 'flex' );
			$css->add_property( 'align-items', 'center' );
			$css->set_selector( '.header-account-style-icon_label .header-account-label' );
			$css->add_property( 'padding-left', '5px' );
			$css->set_selector( '.header-account-style-label_icon .header-account-label' );
			$css->add_property( 'padding-right', '5px' );
			$css->set_selector( '.site-header-item .header-account-wrap .header-account-button' );
			$css->add_property( 'text-decoration', 'none' );
			$css->add_property( 'box-shadow', 'none' );
			$css->add_property( 'color', $css->render_color( ! empty( kadence()->sub_option( 'header_account_color', 'color' ) ) ? kadence()->sub_option( 'header_account_color', 'color' ) : 'inherit' ) );
			$css->add_property( 'background', $css->render_color( ! empty( kadence()->sub_option( 'header_account_background', 'color' ) ) ? kadence()->sub_option( 'header_account_background', 'color' ) : 'transparent' ) );
			$css->add_property( 'border-radius', $css->render_measure( kadence()->option( 'header_account_radius' ) ) );
			$css->add_property( 'padding', $css->render_measure( kadence()->option( 'header_account_padding' ) ) );
			$css->set_selector( '.site-header-item .header-account-wrap .header-account-button:hover' );
			$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_account_color', 'hover' ) ) );
			$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_account_background', 'hover' ) ) );
			$css->set_selector( '.header-account-wrap .header-account-button .header-account-label' );
			$css->render_font( kadence()->option( 'header_account_typography' ), $css );
			$css->set_selector( '.header-account-wrap' );
			$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_account_margin' ) ) );
			// Transparent header.
			$css->set_selector( '.transparent-header .site-header-item .header-account-wrap .header-account-button' );
			$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_account_color', 'color' ) ) );
			$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_header_account_background', 'color' ) ) );
			$css->set_selector( '.transparent-header .site-header-item .header-account-wrap .header-account-button:hover' );
			$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_account_color', 'hover' ) ) );
			$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_header_account_background', 'hover' ) ) );
		} elseif ( ! is_customize_preview() || ( is_customize_preview() && 'in' === kadence()->option( 'header_account_preview' ) ) ) {
			$css->set_selector( '.header-account-button .nav-drop-title-wrap > .kadence-svg-iconset, .header-account-button > .kadence-svg-iconset' );
			$css->add_property( 'font-size', kadence()->sub_option( 'header_account_in_icon_size', 'size' ) . kadence()->sub_option( 'header_account_in_icon_size', 'unit' ) );
			$css->set_selector( '.header-account-in-wrap .header-account-avatar' );
			$css->add_property( 'width', kadence()->sub_option( 'header_account_in_icon_size', 'size' ) . kadence()->sub_option( 'header_account_in_icon_size', 'unit' ) );
			$css->add_property( 'border-radius', $css->render_measure( kadence()->option( 'header_account_in_image_radius' ) ) );
			$css->add_property( 'overflow', 'hidden' );
			$css->set_selector( '.header-account-in-wrap .header-account-avatar img' );
			$css->add_property( 'width', '100%' );
			$css->set_selector( '.header-account-button .nav-drop-title-wrap, .header-account-in-wrap > .header-account-button' );
			$css->add_property( 'display', 'flex' );
			$css->add_property( 'align-items', 'center' );
			$css->set_selector( '.header-account-style-icon_label .header-account-label, .header-account-style-user_label .header-account-label, .header-account-style-user_name .header-account-username, .header-account-style-icon_name .header-account-username' );
			$css->add_property( 'padding-left', '0.25em' );
			$css->set_selector( '.header-account-style-label_icon .header-account-label, .header-account-style-label_user .header-account-label, .header-account-style-name_user .header-account-username, .header-account-style-name_icon .header-account-username' );
			$css->add_property( 'padding-right', '0.25em' );
			$css->set_selector( '.site-header-item .header-account-in-wrap .header-account-button' );
			$css->add_property( 'text-decoration', 'none' );
			$css->add_property( 'box-shadow', 'none' );
			$css->add_property( 'color', $css->render_color( ! empty( kadence()->sub_option( 'header_account_in_color', 'color' ) ) ? kadence()->sub_option( 'header_account_in_color', 'color' ) : 'inherit' ) );
			$css->add_property( 'background', $css->render_color( ! empty( kadence()->sub_option( 'header_account_in_background', 'color' ) ) ? kadence()->sub_option( 'header_account_in_background', 'color' ) : 'transparent' ) );
			$css->add_property( 'border-radius', $css->render_measure( kadence()->option( 'header_account_in_radius' ) ) );
			$css->add_property( 'padding', $css->render_measure( kadence()->option( 'header_account_in_padding' ) ) );
			$css->set_selector( '.site-header-item .header-account-in-wrap .header-account-button:hover' );
			$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_account_in_color', 'hover' ) ) );
			$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_account_in_background', 'hover' ) ) );
			$css->set_selector( '.site-header-item  .header-account-in-wrap .header-account-button .header-account-label, .site-header-item  .header-account-in-wrap .header-account-button .header-account-username' );
			$css->render_font( kadence()->option( 'header_account_in_typography' ), $css );
			$css->set_selector( '.header-account-in-wrap' );
			$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_account_in_margin' ) ) );
			// Transparent header.
			$css->set_selector( '.transparent-header .site-header-item .header-account-in-wrap .header-account-button' );
			$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_account_in_color', 'color' ) ) );
			$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_header_account_in_background', 'color' ) ) );
			$css->set_selector( '.transparent-header .site-header-item .header-account-in-wrap .header-account-button:hover' );
			$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_account_in_color', 'hover' ) ) );
			$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_header_account_in_background', 'hover' ) ) );
		}
		// Account Mobile CSS.
		if ( ! is_user_logged_in() || ( is_customize_preview() && 'out' === kadence()->option( 'header_mobile_account_preview' ) ) ) {
			$css->set_selector( '.header-mobile-account-wrap .header-account-button .nav-drop-title-wrap > .kadence-svg-iconset, .header-mobile-account-wrap .header-account-button > .kadence-svg-iconset' );
			$css->add_property( 'font-size', kadence()->sub_option( 'header_mobile_account_icon_size', 'size' ) . kadence()->sub_option( 'header_mobile_account_icon_size', 'unit' ) );
			$css->set_selector( '.header-mobile-account-wrap .header-account-button .nav-drop-title-wrap, .header-mobile-account-wrap > .header-account-button' );
			$css->add_property( 'display', 'flex' );
			$css->add_property( 'align-items', 'center' );
			$css->set_selector( '.header-mobile-account-wrap.header-account-style-icon_label .header-account-label' );
			$css->add_property( 'padding-left', '5px' );
			$css->set_selector( '.header-mobile-account-wrap.header-account-style-label_icon .header-account-label' );
			$css->add_property( 'padding-right', '5px' );
			$css->set_selector( '.header-mobile-account-wrap .header-account-button' );
			$css->add_property( 'text-decoration', 'none' );
			$css->add_property( 'box-shadow', 'none' );
			$css->add_property( 'border', '0' );
			$css->add_property( 'color', $css->render_color( ! empty( kadence()->sub_option( 'header_mobile_account_color', 'color' ) ) ? kadence()->sub_option( 'header_mobile_account_color', 'color' ) : 'inherit' ) );
			$css->add_property( 'background', $css->render_color( ! empty( kadence()->sub_option( 'header_mobile_account_background', 'color' ) ) ? kadence()->sub_option( 'header_mobile_account_background', 'color' ) : 'transparent' ) );
			$css->add_property( 'border-radius', $css->render_measure( kadence()->option( 'header_mobile_account_radius' ) ) );
			$css->add_property( 'padding', $css->render_measure( kadence()->option( 'header_mobile_account_padding' ) ) );
			$css->set_selector( '.header-mobile-account-wrap .header-account-button:hover' );
			$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_mobile_account_color', 'hover' ) ) );
			$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_mobile_account_background', 'hover' ) ) );
			$css->set_selector( '.header-mobile-account-wrap .header-account-button .header-account-label' );
			$css->render_font( kadence()->option( 'header_mobile_account_typography' ), $css );
			$css->set_selector( '.header-mobile-account-wrap' );
			$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_mobile_account_margin' ) ) );
			// Transparent header.
			$css->set_selector( '.mobile-transparent-header .header-mobile-account-wrap .header-account-button' );
			$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_account_color', 'color' ) ) );
			$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_account_background', 'color' ) ) );
			$css->set_selector( '.mobile-transparent-header .header-mobile-account-wrap .header-account-button:hover' );
			$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_account_color', 'hover' ) ) );
			$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_account_background', 'hover' ) ) );
		} elseif ( ! is_customize_preview() || ( is_customize_preview() && 'in' === kadence()->option( 'header_mobile_account_preview' ) ) ) {
			$css->set_selector( '.header-mobile-account-in-wrap .header-account-button .nav-drop-title-wrap > .kadence-svg-iconset, .header-mobile-account-in-wrap .header-account-button > .kadence-svg-iconset' );
			$css->add_property( 'font-size', kadence()->sub_option( 'header_mobile_account_in_icon_size', 'size' ) . kadence()->sub_option( 'header_mobile_account_in_icon_size', 'unit' ) );
			$css->set_selector( '.header-mobile-account-in-wrap .header-account-avatar' );
			$css->add_property( 'width', kadence()->sub_option( 'header_mobile_account_in_icon_size', 'size' ) . kadence()->sub_option( 'header_mobile_account_in_icon_size', 'unit' ) );
			$css->add_property( 'border-radius', $css->render_measure( kadence()->option( 'header_mobile_account_in_image_radius' ) ) );
			$css->add_property( 'overflow', 'hidden' );
			$css->set_selector( '.header-mobile-account-in-wrap .header-account-avatar img' );
			$css->add_property( 'width', '100%' );
			$css->set_selector( '.header-account-button .nav-drop-title-wrap, .header-mobile-account-in-wrap > .header-account-button' );
			$css->add_property( 'display', 'flex' );
			$css->add_property( 'align-items', 'center' );
			$css->set_selector( '.header-account-style-icon_label .header-account-label, .header-account-style-user_label .header-account-label, .header-account-style-user_name .header-account-username' );
			$css->add_property( 'padding-left', '0.25em' );
			$css->set_selector( '.header-account-style-label_icon .header-account-label, .header-account-style-label_user .header-account-label, .header-account-style-name_user .header-account-username' );
			$css->add_property( 'padding-right', '0.25em' );
			$css->set_selector( '.header-mobile-account-in-wrap .header-account-button' );
			$css->add_property( 'text-decoration', 'none' );
			$css->add_property( 'box-shadow', 'none' );
			$css->add_property( 'color', $css->render_color( ! empty( kadence()->sub_option( 'header_mobile_account_in_color', 'color' ) ) ? kadence()->sub_option( 'header_mobile_account_in_color', 'color' ) : 'inherit' ) );
			$css->add_property( 'background', $css->render_color( ! empty( kadence()->sub_option( 'header_mobile_account_in_background', 'color' ) ) ? kadence()->sub_option( 'header_mobile_account_in_background', 'color' ) : 'transparent' ) );
			$css->add_property( 'border-radius', $css->render_measure( kadence()->option( 'header_mobile_account_in_radius' ) ) );
			$css->add_property( 'padding', $css->render_measure( kadence()->option( 'header_mobile_account_in_padding' ) ) );
			$css->set_selector( '.header-mobile-account-in-wrap .header-account-button:hover' );
			$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_mobile_account_in_color', 'hover' ) ) );
			$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_mobile_account_in_background', 'hover' ) ) );
			$css->set_selector( '.header-mobile-account-in-wrap .header-account-button .header-account-label, .header-mobile-account-in-wrap .header-account-button .header-account-username' );
			$css->render_font( kadence()->option( 'header_mobile_account_in_typography' ), $css );
			$css->set_selector( '.header-mobile-account-in-wrap' );
			$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_mobile_account_in_margin' ) ) );
			// Transparent header.
			$css->set_selector( '.mobile-transparent-header .header-mobile-account-in-wrap .header-account-button' );
			$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_account_in_color', 'color' ) ) );
			$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_account_in_background', 'color' ) ) );
			$css->set_selector( '.mobile-transparent-header .header-mobile-account-in-wrap .header-account-button:hover' );
			$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_account_in_color', 'hover' ) ) );
			$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_account_in_background', 'hover' ) ) );
		}
		// Account Login Modal.
		$css->set_selector( '#login-drawer .drawer-inner .drawer-content' );
		$css->add_property( 'display', 'flex' );
		$css->add_property( 'justify-content', 'center' );
		$css->add_property( 'align-items', 'center' );
		$css->add_property( 'position', 'absolute' );
		$css->add_property( 'top', '0px' );
		$css->add_property( 'bottom', '0px' );
		$css->add_property( 'left', '0px' );
		$css->add_property( 'right', '0px' );
		$css->add_property( 'padding', '0px' );
		$css->set_selector( '#loginform p label' );
		$css->add_property( 'display', 'block' );
		$css->set_selector( '#login-drawer #loginform' );
		$css->add_property( 'width', '100%' );
		$css->set_selector( '#login-drawer #loginform input' );
		$css->add_property( 'width', '100%' );
		$css->set_selector( '#login-drawer #loginform input[type="checkbox"]' );
		$css->add_property( 'width', 'auto' );
		$css->set_selector( '#login-drawer .drawer-inner .drawer-header' );
		$css->add_property( 'position', 'relative' );
		$css->add_property( 'z-index', '100' );
		$css->set_selector( '#login-drawer .drawer-content_inner.widget_login_form_inner' );
		$css->add_property( 'padding', '2em' );
		$css->add_property( 'width', '100%' );
		$css->add_property( 'max-width', '350px' );
		$css->add_property( 'border-radius', '.25rem' );
		$css->add_property( 'background', 'var(--global-palette9)' );
		$css->add_property( 'color', 'var(--global-palette4)' );
		$css->set_selector( '#login-drawer .lost_password a' );
		$css->add_property( 'color', 'var(--global-palette6)' );
		$css->set_selector( '#login-drawer .lost_password, #login-drawer .register-field' );
		$css->add_property( 'text-align', 'center' );
		$css->set_selector( '#login-drawer .widget_login_form_inner p' );
		$css->add_property( 'margin-top', '1.2em' );
		$css->add_property( 'margin-bottom', '0em' );
		$css->set_selector( '#login-drawer .widget_login_form_inner p:first-child' );
		$css->add_property( 'margin-top', '0em' );
		$css->set_selector( '#login-drawer .widget_login_form_inner label' );
		$css->add_property( 'margin-bottom', '0.5em' );
		$css->set_selector( '#login-drawer hr.register-divider' );
		$css->add_property( 'margin', '1.2em 0' );
		$css->add_property( 'border-width', '1px' );
		$css->set_selector( '#login-drawer .register-field' );
		$css->add_property( 'font-size', '90%' );
		// Header HTML2.
		$css->set_selector( '.header-html2' );
		$css->render_font( kadence()->option( 'header_html2_typography' ), $css );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_html2_margin' ) ) );
		$css->set_selector( '.header-html2 a' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_html2_link_color', 'color' ) ) );
		$css->set_selector( '.header-html2 a:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_html2_link_color', 'hover' ) ) );
		// Header html mobile.
		$css->set_selector( '.mobile-html2' );
		$css->render_font( kadence()->option( 'header_mobile_html2_typography' ), $css );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_mobile_html2_margin' ) ) );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '.mobile-html2' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'header_mobile_html2_typography' ), 'tablet' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'header_mobile_html2_typography' ), 'tablet' ) );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '.mobile-html2' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'header_mobile_html2_typography' ), 'mobile' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'header_mobile_html2_typography' ), 'mobile' ) );
		$css->stop_media_query();
		$css->set_selector( '.mobile-html2 a' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_mobile_html2_link_color', 'color' ) ) );
		$css->set_selector( '.mobile-html2 a:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_mobile_html2_link_color', 'hover' ) ) );
		// Header HTML2 Transparent.
		$css->set_selector( '.transparent-header #main-header .header-html2, .mobile-transparent-header .mobile-html2' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_html2_color', 'color' ) ) );
		$css->set_selector( '.transparent-header #main-header .header-html2 a, .mobile-transparent-header .mobile-html2 a' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_html2_color', 'link' ) ) );
		$css->set_selector( '.transparent-header #main-header .header-html2 a:hover, .mobile-transparent-header .mobile-html2 a:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_html2_color', 'hover' ) ) );
		// Tertiary Navigation.
		$css->set_selector( '.tertiary-navigation .tertiary-menu-container > ul > li.menu-item > a' );
		$css->add_property( 'padding-left', $css->render_half_size( kadence()->option( 'tertiary_navigation_spacing' ) ) );
		$css->add_property( 'padding-right', $css->render_half_size( kadence()->option( 'tertiary_navigation_spacing' ) ) );
		if ( kadence()->option( 'tertiary_navigation_style' ) === 'standard' || kadence()->option( 'tertiary_navigation_style' ) === 'underline' ) {
			$css->add_property( 'padding-top', kadence()->sub_option( 'tertiary_navigation_vertical_spacing', 'size' ) . kadence()->sub_option( 'tertiary_navigation_vertical_spacing', 'unit' ) );
			$css->add_property( 'padding-bottom', kadence()->sub_option( 'tertiary_navigation_vertical_spacing', 'size' ) . kadence()->sub_option( 'tertiary_navigation_vertical_spacing', 'unit' ) );
		}
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'tertiary_navigation_color', 'color' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'tertiary_navigation_background', 'color' ) ) );
		$css->set_selector( '.tertiary-navigation .tertiary-menu-container > ul li.menu-item a' );
		$css->render_font( kadence()->option( 'tertiary_navigation_typography' ), $css );
		$css->set_selector( '.tertiary-navigation .tertiary-menu-container > ul > li.menu-item > a:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'tertiary_navigation_color', 'hover' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'tertiary_navigation_background', 'hover' ) ) );
		$css->set_selector( '.tertiary-navigation .tertiary-menu-container > ul > li.menu-item.current-menu-item > a' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'tertiary_navigation_color', 'active' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'tertiary_navigation_background', 'active' ) ) );
		// Quaternary Navigation.
		$css->set_selector( '.quaternary-navigation .quaternary-menu-container > ul > li.menu-item > a' );
		$css->add_property( 'padding-left', $css->render_half_size( kadence()->option( 'quaternary_navigation_spacing' ) ) );
		$css->add_property( 'padding-right', $css->render_half_size( kadence()->option( 'quaternary_navigation_spacing' ) ) );
		if ( kadence()->option( 'quaternary_navigation_style' ) === 'standard' || kadence()->option( 'quaternary_navigation_style' ) === 'underline' ) {
			$css->add_property( 'padding-top', kadence()->sub_option( 'quaternary_navigation_vertical_spacing', 'size' ) . kadence()->sub_option( 'quaternary_navigation_vertical_spacing', 'unit' ) );
			$css->add_property( 'padding-bottom', kadence()->sub_option( 'quaternary_navigation_vertical_spacing', 'size' ) . kadence()->sub_option( 'quaternary_navigation_vertical_spacing', 'unit' ) );
		}
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'quaternary_navigation_color', 'color' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'quaternary_navigation_background', 'color' ) ) );
		$css->set_selector( '.quaternary-navigation .quaternary-menu-container > ul li.menu-item a' );
		$css->render_font( kadence()->option( 'quaternary_navigation_typography' ), $css );
		$css->set_selector( '.quaternary-navigation .quaternary-menu-container > ul > li.menu-item > a:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'quaternary_navigation_color', 'hover' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'quaternary_navigation_background', 'hover' ) ) );
		$css->set_selector( '.quaternary-navigation .quaternary-menu-container > ul > li.menu-item.current-menu-item > a' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'quaternary_navigation_color', 'active' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'quaternary_navigation_background', 'active' ) ) );
		// Header divider.
		$css->set_selector( '#main-header .header-divider' );
		$css->add_property( 'border-right', $css->render_border( kadence()->option( 'header_divider_border' ) ) );
		$css->add_property( 'height', $css->render_size( kadence()->option( 'header_divider_height' ) ) );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_divider_margin' ) ) );
		$css->set_selector( '.transparent-header #main-header .header-divider' );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'transparent_header_divider_color', 'color' ) ) );
		// Header Divider 2.
		$css->set_selector( '#main-header .header-divider2' );
		$css->add_property( 'border-right', $css->render_border( kadence()->option( 'header_divider2_border' ) ) );
		$css->add_property( 'height', $css->render_size( kadence()->option( 'header_divider2_height' ) ) );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_divider2_margin' ) ) );
		$css->set_selector( '.transparent-header #main-header .header-divider2' );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'transparent_header_divider2_color', 'color' ) ) );
		// Header Divider 3.
		$css->set_selector( '#main-header .header-divider3' );
		$css->add_property( 'border-right', $css->render_border( kadence()->option( 'header_divider3_border' ) ) );
		$css->add_property( 'height', $css->render_size( kadence()->option( 'header_divider3_height' ) ) );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_divider3_margin' ) ) );
		$css->set_selector( '.transparent-header #main-header .header-divider3' );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'transparent_header_divider3_color', 'color' ) ) );
		// Header Mobile Divider.
		$css->set_selector( '#mobile-header .header-mobile-divider' );
		$css->add_property( 'border-right', $css->render_border( kadence()->option( 'header_mobile_divider_border' ) ) );
		$css->add_property( 'height', $css->render_size( kadence()->option( 'header_mobile_divider_height' ) ) );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_mobile_divider_margin' ) ) );
		$css->set_selector( '.mobile-transparent-header #mobile-header .header-mobile-divider' );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_divider_color', 'color' ) ) );
		// Header Mobile Divider 2.
		$css->set_selector( '#mobile-header .header-mobile-divider2' );
		$css->add_property( 'border-right', $css->render_border( kadence()->option( 'header_mobile_divider2_border' ) ) );
		$css->add_property( 'height', $css->render_size( kadence()->option( 'header_mobile_divider2_height' ) ) );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_mobile_divider2_margin' ) ) );
		$css->set_selector( '.mobile-transparent-header #mobile-header .header-mobile-divider2' );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_divider2_color', 'color' ) ) );
		// Header Search Bar.
		$css->set_selector( '.header-item-search-bar form ::-webkit-input-placeholder' );
		$css->add_property( 'color', 'currentColor' );
		$css->add_property( 'opacity', '0.5' );
		$css->set_selector( '.header-item-search-bar form ::placeholder' );
		$css->add_property( 'color', 'currentColor' );
		$css->add_property( 'opacity', '0.5' );
		$css->set_selector( '.header-search-bar form' );
		$css->add_property( 'max-width', '100%' );
		$css->add_property( 'width', $css->render_size( kadence()->option( 'header_search_bar_width' ) ) );
		$css->set_selector( '.header-search-bar' );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_search_bar_margin' ) ) );
		$css->set_selector( '.header-search-bar form input.search-field' );
		$css->render_font( kadence()->option( 'header_search_bar_typography' ), $css );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_search_bar_background', 'color' ) ) );
		$css->add_property( 'border', $css->render_border( kadence()->option( 'header_search_bar_border' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'header_search_bar_border_color', 'color' ) ) );
		$css->set_selector( '.header-search-bar form input.search-field, .header-search-bar form .kadence-search-icon-wrap' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_search_bar_color', 'color' ) ) );
		$css->set_selector( '.header-search-bar form input.search-field:focus' );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_search_bar_background', 'hover' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'header_search_bar_border_color', 'hover' ) ) );
		$css->set_selector( '.header-search-bar form input.search-field:focus, .header-search-bar form input.search-submit:hover ~ .kadence-search-icon-wrap, #main-header .header-search-bar form button[type="submit"]:hover ~ .kadence-search-icon-wrap' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_search_bar_color', 'hover' ) ) );
		$css->set_selector( '.transparent-header .header-search-bar form input.search-field' );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_header_search_bar_background', 'color' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'transparent_header_search_bar_border', 'color' ) ) );
		$css->set_selector( '.transparent-header .header-search-bar form input.search-field, .transparent-header .header-search-bar form .kadence-search-icon-wrap' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_search_bar_color', 'color' ) ) );
		$css->set_selector( '.transparent-header .header-search-bar form input.search-field:focus' );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_header_search_bar_background', 'hover' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'transparent_header_search_bar_border', 'hover' ) ) );
		$css->set_selector( '.transparent-header .header-search-bar form input.search-field:focus, .transparent-header .header-search-bar form input.search-submit:hover ~ .kadence-search-icon-wrap, .transparent-header #main-header .header-search-bar form button[type="submit"]:hover ~ .kadence-search-icon-wrap' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_search_bar_color', 'hover' ) ) );
		// Header Mobile Search Bar.
		$css->set_selector( '.header-mobile-search-bar form' );
		$css->add_property( 'max-width', 'calc(100vw - var(--global-sm-spacing) - var(--global-sm-spacing))' );
		$css->add_property( 'width', $css->render_size( kadence()->option( 'header_mobile_search_bar_width' ) ) );
		$css->set_selector( '.header-mobile-search-bar' );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_mobile_search_bar_margin' ) ) );
		$css->set_selector( '.header-mobile-search-bar form input.search-field' );
		$css->render_font( kadence()->option( 'header_mobile_search_bar_typography' ), $css );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_mobile_search_bar_background', 'color' ) ) );
		$css->add_property( 'border', $css->render_border( kadence()->option( 'header_mobile_search_bar_border' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'header_mobile_search_bar_border_color', 'color' ) ) );
		$css->set_selector( '.header-mobile-search-bar form input.search-field, .header-mobile-search-bar form .kadence-search-icon-wrap' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_mobile_search_bar_color', 'color' ) ) );
		$css->set_selector( '.header-mobile-search-bar form input.search-field:focus' );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_mobile_search_bar_background', 'hover' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'header_mobile_search_bar_border_color', 'hover' ) ) );
		$css->set_selector( '.header-mobile-search-bar form input.search-field:focus, .header-mobile-search-bar form input.search-submit:hover ~ .kadence-search-icon-wrap, #mobile-header .header-mobile-search-bar form button[type="submit"]:hover ~ .kadence-search-icon-wrap' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_mobile_search_bar_color', 'hover' ) ) );
		$css->set_selector( '.transparent-header .header-mobile-search-bar form input.search-field' );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_search_bar_background', 'color' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_search_bar_border', 'color' ) ) );
		$css->set_selector( '.transparent-header .header-mobile-search-bar form input.search-field, .transparent-header .header-mobile-search-bar form .kadence-search-icon-wrap' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_search_bar_color', 'color' ) ) );
		$css->set_selector( '.transparent-header .header-mobile-search-bar form input.search-field:focus' );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_search_bar_background', 'hover' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_search_bar_border', 'hover' ) ) );
		$css->set_selector( '.transparent-header .header-mobile-search-bar form input.search-field:focus, .transparent-header .header-mobile-search-bar form input.search-submit:hover ~ .kadence-search-icon-wrap, .transparent-header #mobile-header .header-mobile-search-bar form button[type="submit"]:hover ~ .kadence-search-icon-wrap' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_search_bar_color', 'hover' ) ) );
		// Header Widget area.
		$css->set_selector( '.header-widget-lstyle-normal .header-widget-area-inner a:not(.button)' );
		$css->add_property( 'text-decoration', 'underline' );
		$css->set_selector( '#main-header .header-widget1 .header-widget-area-inner .widget-title' );
		$css->render_font( kadence()->option( 'header_widget1_title' ), $css );
		$css->set_selector( '#main-header .header-widget1 .header-widget-area-inner' );
		$css->render_font( kadence()->option( 'header_widget1_content' ), $css );
		$css->set_selector( '#main-header .header-widget1 .header-widget-area-inner a' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_widget1_link_colors', 'color' ) ) );
		$css->set_selector( '#main-header .header-widget1 .header-widget-area-inner a:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_widget1_link_colors', 'hover' ) ) );
		$css->set_selector( '#main-header .header-widget1' );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_widget1_margin' ) ) );
		$css->set_selector( '.transparent-header #main-header .header-widget1 .header-widget-area-inner, .transparent-header #main-header .header-widget1 .header-widget-area-inner .widget-title' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_widget1_color', 'color' ) ) );
		$css->set_selector( '.transparent-header #main-header .header-widget1 .header-widget-area-inner a' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_widget1_color', 'link' ) ) );
		$css->set_selector( '.transparent-header #main-header .header-widget1 .header-widget-area-inner a:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_widget1_color', 'hover' ) ) );
		// Header Contact.
		$css->set_selector( '.header-contact-wrap' );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_contact_margin' ) ) );
		$css->set_selector( '.element-contact-inner-wrap' );
		$css->add_property( 'display', 'flex' );
		$css->add_property( 'flex-wrap', 'wrap' );
		$css->add_property( 'align-items', 'center' );
		$css->add_property( 'margin-top', $this->render_negative_size( kadence()->option( 'header_contact_item_spacing' ) ) );
		$css->add_property( 'margin-left', $this->render_negative_half_size( kadence()->option( 'header_contact_item_spacing' ) ) );
		$css->add_property( 'margin-right', $this->render_negative_half_size( kadence()->option( 'header_contact_item_spacing' ) ) );
		$css->set_selector( '.element-contact-inner-wrap .header-contact-item' );
		$css->add_property( 'display', 'inline-flex' );
		$css->add_property( 'flex-wrap', 'wrap' );
		$css->add_property( 'align-items', 'center' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_contact_color', 'color' ) ) );
		$css->render_font( kadence()->option( 'header_contact_typography' ), $css );
		$css->add_property( 'margin-top', $css->render_size( kadence()->option( 'header_contact_item_spacing' ) ) );
		$css->add_property( 'margin-left', $css->render_half_size( kadence()->option( 'header_contact_item_spacing' ) ) );
		$css->add_property( 'margin-right', $css->render_half_size( kadence()->option( 'header_contact_item_spacing' ) ) );
		$css->set_selector( '.element-contact-inner-wrap a.header-contact-item:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_contact_color', 'hover' ) ) );
		$css->set_selector( '.element-contact-inner-wrap .header-contact-item .kadence-svg-iconset' );
		$css->add_property( 'font-size', $css->render_size( kadence()->option( 'header_contact_icon_size' ) ) );
		$css->set_selector( '.header-contact-item img' );
		$css->add_property( 'display', 'inline-block' );
		$css->set_selector( '.header-contact-item .contact-label' );
		$css->add_property( 'margin-left', '0.3em' );
		$css->set_selector( '.rtl .header-contact-item .contact-label' );
		$css->add_property( 'margin-right', '0.3em' );
		$css->add_property( 'margin-left', '0px' );
		// Sticky Mobile Contact.
		$css->set_selector( '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .header-contact-wrap .header-contact-item' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'sticky_header_contact_color', 'color' ) ) );
		$css->set_selector( '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .header-contact-wrap .header-contact-item:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'sticky_header_contact_color', 'hover' ) ) );
		$css->set_selector( '.transparent-header #main-header .header-contact-wrap .header-contact-item' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_contact_color', 'color' ) ) );
		$css->set_selector( '.transparent-header #main-header .header-contact-wrap a.header-contact-item:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_contact_color', 'hover' ) ) );
		// Header Mobile Contact.
		$css->set_selector( '.header-mobile-contact-wrap' );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_mobile_contact_margin' ) ) );
		$css->set_selector( '.header-mobile-contact-wrap .element-contact-inner-wrap' );
		$css->add_property( 'display', 'flex' );
		$css->add_property( 'flex-wrap', 'wrap' );
		$css->add_property( 'align-items', 'center' );
		$css->add_property( 'margin-top', $this->render_negative_size( kadence()->option( 'header_mobile_contact_item_vspacing' ) ) );
		$css->add_property( 'margin-left', $this->render_negative_half_size( kadence()->option( 'header_mobile_contact_item_spacing' ) ) );
		$css->add_property( 'margin-right', $this->render_negative_half_size( kadence()->option( 'header_mobile_contact_item_spacing' ) ) );
		$css->set_selector( '.header-mobile-contact-wrap .element-contact-inner-wrap .header-contact-item' );
		$css->add_property( 'display', 'inline-flex' );
		$css->add_property( 'flex-wrap', 'wrap' );
		$css->add_property( 'align-items', 'center' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_mobile_contact_color', 'color' ) ) );
		$css->render_font( kadence()->option( 'header_mobile_contact_typography' ), $css );
		$css->add_property( 'margin-top', $css->render_size( kadence()->option( 'header_mobile_contact_item_vspacing' ) ) );
		$css->add_property( 'margin-left', $css->render_half_size( kadence()->option( 'header_mobile_contact_item_spacing' ) ) );
		$css->add_property( 'margin-right', $css->render_half_size( kadence()->option( 'header_mobile_contact_item_spacing' ) ) );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '.header-mobile-contact-wrap .element-contact-inner-wrap .header-contact-item' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'header_mobile_contact_typography' ), 'tablet' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'header_mobile_contact_typography' ), 'tablet' ) );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '.header-mobile-contact-wrap .element-contact-inner-wrap .header-contact-item' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'header_mobile_contact_typography' ), 'mobile' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'header_mobile_contact_typography' ), 'mobile' ) );
		$css->stop_media_query();
		$css->set_selector( '.header-mobile-contact-wrap .element-contact-inner-wrap a.header-contact-item:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_mobile_contact_color', 'hover' ) ) );
		$css->set_selector( '.header-mobile-contact-wrap .element-contact-inner-wrap .header-contact-item .kadence-svg-iconset' );
		$css->add_property( 'font-size', $css->render_size( kadence()->option( 'header_mobile_contact_icon_size' ) ) );
		// Sticky Mobile Contact.
		$css->set_selector( '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .header-mobile-contact-wrap .element-contact-inner-wrap .header-contact-item' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'sticky_header_contact_color', 'color' ) ) );
		$css->set_selector( '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .header-mobile-contact-wrap .element-contact-inner-wrap .header-contact-item:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'sticky_header_contact_color', 'hover' ) ) );
		// Transparent Mobile Contact.
		$css->set_selector( '.transparent-header .header-mobile-contact-wrap .element-contact-inner-wrap .header-contact-item' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_contact_color', 'color' ) ) );
		$css->set_selector( '.transparent-header .header-mobile-contact-wrap .element-contact-inner-wrap a.header-contact-item:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_mobile_contact_color', 'hover' ) ) );
		// Header Button2.
		$css->set_selector( '#main-header .header-button2' );
		$css->render_font( kadence()->option( 'header_button2_typography' ), $css );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_button2_margin' ) ) );
		$css->add_property( 'border-radius', $css->render_measure( kadence()->option( 'header_button2_radius' ) ) );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_button2_color', 'color' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_button2_background', 'color' ) ) );
		$css->add_property( 'border', $css->render_border( kadence()->option( 'header_button2_border' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'header_button2_border_colors', 'color' ) ) );
		$css->add_property( 'box-shadow', $css->render_shadow( kadence()->option( 'header_button2_shadow' ), kadence()->default( 'header_button2_shadow' ) ) );
		$css->set_selector( '#main-header .header-button2.button-size-custom' );
		$css->add_property( 'padding', $css->render_measure( kadence()->option( 'header_button2_padding' ) ) );
		$css->set_selector( '#main-header .header-button2:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_button2_color', 'hover' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_button2_background', 'hover' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'header_button2_border_colors', 'hover' ) ) );
		$css->add_property( 'box-shadow', $css->render_shadow( kadence()->option( 'header_button2_shadow_hover' ), kadence()->default( 'header_button2_shadow_hover' ) ) );
		// Sticky Button2.
		$css->set_selector( '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .header-button2, #masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .mobile-header-button-wrap .mobile-header-button2' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_sticky_button2_color', 'color' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_sticky_button2_color', 'background' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'header_sticky_button2_color', 'border' ) ) );
		$css->set_selector( '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .header-button2:hover, #masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .mobile-header-button-wrap .mobile-header-button2:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_sticky_button2_color', 'hover' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_sticky_button2_color', 'backgroundHover' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'header_sticky_button2_color', 'borderHover' ) ) );
		// Transparent Button2.
		$css->set_selector( '.transparent-header #main-header .header-button2, .mobile-transparent-header .mobile-header-button2-wrap .mobile-header-button2' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_button2_color', 'color' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_header_button2_color', 'background' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'transparent_header_button2_color', 'border' ) ) );
		$css->set_selector( '.transparent-header #main-header .header-button2:hover, .mobile-transparent-header .mobile-header-button2-wrap .mobile-header-button2:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_header_button2_color', 'hover' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_header_button2_color', 'backgroundHover' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'transparent_header_button2_color', 'borderHover' ) ) );
		// Header Mobile Button2.
		$css->set_selector( '.mobile-header-button2-wrap .mobile-header-button-inner-wrap .mobile-header-button2' );
		$css->render_font( kadence()->option( 'mobile_button2_typography' ), $css );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'mobile_button2_margin' ) ) );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'mobile_button2_color', 'color' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'mobile_button2_background', 'color' ) ) );
		$css->add_property( 'border', $css->render_border( kadence()->option( 'mobile_button2_border' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'mobile_button2_border_colors', 'color' ) ) );
		$css->add_property( 'border-radius', $css->render_measure( kadence()->option( 'mobile_button2_radius' ) ) );
		$css->add_property( 'box-shadow', $css->render_shadow( kadence()->option( 'mobile_button2_shadow' ), kadence()->default( 'mobile_button2_shadow' ) ) );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '.mobile-header-button2-wrap .mobile-header-button-inner-wrap .mobile-header-button2' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'mobile_button2_typography' ), 'tablet' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'mobile_button2_typography' ), 'tablet' ) );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '.mobile-header-button2-wrap .mobile-header-button-inner-wrap .mobile-header-button2' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'mobile_button2_typography' ), 'mobile' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'mobile_button2_typography' ), 'mobile' ) );
		$css->stop_media_query();
		$css->set_selector( '.mobile-header-button2-wrap .mobile-header-button-inner-wrap .mobile-header-button2:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'mobile_button2_color', 'hover' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'mobile_button2_background', 'hover' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'mobile_button2_border_colors', 'hover' ) ) );
		$css->add_property( 'box-shadow', $css->render_shadow( kadence()->option( 'mobile_button2_shadow_hover' ), kadence()->default( 'mobile_button2_shadow_hover' ) ) );
		// Widget toggle.
		$css->set_selector( '#widget-drawer.popup-drawer-layout-fullwidth .drawer-content .header-widget2, #widget-drawer.popup-drawer-layout-sidepanel .drawer-inner' );
		$css->add_property( 'max-width', $css->render_size( kadence()->option( 'header_toggle_widget_pop_width' ) ) );
		$css->set_selector( '#widget-drawer.popup-drawer-layout-fullwidth .drawer-content .header-widget2' );
		$css->add_property( 'margin', '0 auto' );
		$css->set_selector( '.widget-toggle-open' );
		$css->add_property( 'display', 'flex' );
		$css->add_property( 'align-items', 'center' );
		$css->add_property( 'background', 'transparent' );
		$css->add_property( 'box-shadow', 'none' );
		$css->set_selector( '.widget-toggle-open.widget-toggle-style-default' );
		$css->add_property( 'border', '0' );
		$css->set_selector( '.widget-toggle-open:hover, .widget-toggle-open:focus' );
		$css->add_property( 'border-color', 'currentColor' );
		$css->add_property( 'background', 'transparent' );
		$css->add_property( 'box-shadow', 'none' );
		$css->set_selector( '.widget-toggle-open .widget-toggle-icon' );
		$css->add_property( 'display', 'flex' );
		$css->set_selector( '.widget-toggle-open .widget-toggle-label' );
		$css->add_property( 'padding-right', '5px' );
		$css->set_selector( '.rtl .widget-toggle-open .widget-toggle-label' );
		$css->add_property( 'padding-left', '5px' );
		$css->add_property( 'padding-right', '0px' );
		$css->set_selector( '.widget-toggle-open .widget-toggle-label:empty, .rtl .widget-toggle-open .widget-toggle-label:empty' );
		$css->add_property( 'padding-right', '0px' );
		$css->add_property( 'padding-left', '0px' );
		$css->set_selector( '.widget-toggle-open-container .widget-toggle-open' );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_toggle_widget_background', 'color' ) ) );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_toggle_widget_color', 'color' ) ) );
		$css->add_property( 'padding', $css->render_measure( kadence()->option( 'header_toggle_widget_padding' ) ) );
		$css->render_font( kadence()->option( 'header_toggle_widget_typography' ), $css );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '.widget-toggle-open-container .widget-toggle-open' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'header_toggle_widget_typography' ), 'tablet' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'header_toggle_widget_typography' ), 'tablet' ) );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '.widget-toggle-open-container .widget-toggle-open' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'header_toggle_widget_typography' ), 'mobile' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'header_toggle_widget_typography' ), 'mobile' ) );
		$css->stop_media_query();
		$css->set_selector( '.widget-toggle-open-container .widget-toggle-open.widget-toggle-style-bordered' );
		$css->add_property( 'border', $css->render_border( kadence()->option( 'header_toggle_widget_border' ) ) );
		$css->set_selector( '.widget-toggle-open-container .widget-toggle-open .widget-toggle-icon' );
		$css->add_property( 'font-size', kadence()->sub_option( 'header_toggle_widget_icon_size', 'size' ) . kadence()->sub_option( 'header_toggle_widget_icon_size', 'unit' ) );
		$css->set_selector( '.widget-toggle-open-container .widget-toggle-open:hover, .widget-toggle-open-container .widget-toggle-open:focus' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_toggle_widget_color', 'hover' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_toggle_widget_background', 'hover' ) ) );
		// Transparent Header.
		$css->set_selector( '.transparent-header #main-header .widget-toggle-open-container .widget-toggle-open' );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_toggle_widget_color', 'background' ) ) );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_toggle_widget_color', 'color' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'transparent_toggle_widget_color', 'border' ) ) );
		$css->set_selector( '.transparent-header #main-header .widget-toggle-open-container .widget-toggle-open:hover' );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'transparent_toggle_widget_color', 'backgroundHover' ) ) );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'transparent_toggle_widget_color', 'hover' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'transparent_toggle_widget_color', 'borderHover' ) ) );

		// Sticky Header.
		$css->set_selector( '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .widget-toggle-open-container .widget-toggle-open' );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_sticky_toggle_widget_color', 'background' ) ) );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_sticky_toggle_widget_color', 'color' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'header_sticky_toggle_widget_color', 'border' ) ) );
		$css->set_selector( '#masthead .kadence-sticky-header.item-is-fixed:not(.item-at-start) .widget-toggle-open-container .widget-toggle-open:hover' );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'header_sticky_toggle_widget_color', 'backgroundHover' ) ) );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_sticky_toggle_widget_color', 'hover' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'header_sticky_toggle_widget_color', 'borderHover' ) ) );

		$css->set_selector( '#widget-drawer .drawer-inner' );
		$css->render_background( kadence()->sub_option( 'header_toggle_widget_pop_background', 'desktop' ), $css );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '#widget-drawer .drawer-inner' );
		$css->render_background( kadence()->sub_option( 'header_toggle_widget_pop_background', 'tablet' ), $css );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '#widget-drawer .drawer-inner' );
		$css->render_background( kadence()->sub_option( 'header_toggle_widget_pop_background', 'mobile' ), $css );
		$css->stop_media_query();
		$css->set_selector( '#widget-drawer .drawer-header .drawer-toggle, #widget-drawer .drawer-header .drawer-toggle:focus' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_toggle_widget_close_color', 'color' ) ) );
		$css->set_selector( '#widget-drawer .drawer-header .drawer-toggle:hover, #widget-drawer .drawer-header .drawer-toggle:focus:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_toggle_widget_close_color', 'hover' ) ) );

		// Toggle Widget area.
		$css->set_selector( '#widget-drawer .header-widget-2style-normal a:not(.button)' );
		$css->add_property( 'text-decoration', 'underline' );
		$css->set_selector( '#widget-drawer .header-widget-2style-plain a:not(.button)' );
		$css->add_property( 'text-decoration', 'none' );
		$css->set_selector( '#widget-drawer .header-widget2 .widget-title' );
		$css->render_font( kadence()->option( 'header_widget2_title' ), $css );
		$css->set_selector( '#widget-drawer .header-widget2' );
		$css->render_font( kadence()->option( 'header_widget2_content' ), $css );
		$css->set_selector( '#widget-drawer .header-widget2 a:not(.button), #widget-drawer .header-widget2 .drawer-sub-toggle' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_widget2_link_colors', 'color' ) ) );
		$css->set_selector( '#widget-drawer .header-widget2 a:not(.button):hover, #widget-drawer .header-widget2 .drawer-sub-toggle:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'header_widget2_link_colors', 'hover' ) ) );
		$css->set_selector( '#widget-drawer .drawer-inner .header-widget2' );
		$css->add_property( 'padding', $css->render_measure( kadence()->option( 'header_widget2_padding' ) ) );
		// Mobile Menu.
		$css->set_selector( '#mobile-secondary-site-navigation ul li' );
		$css->render_font( kadence()->option( 'mobile_secondary_navigation_typography' ), $css );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '#mobile-secondary-site-navigation ul li' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'mobile_secondary_navigation_typography' ), 'tablet' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'mobile_secondary_navigation_typography' ), 'tablet' ) );
		$css->add_property( 'letter-spacing', $css->render_font_spacing( kadence()->option( 'mobile_secondary_navigation_typography' ), 'tablet' ) );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '#mobile-secondary-site-navigation ul li' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'mobile_secondary_navigation_typography' ), 'mobile' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'mobile_secondary_navigation_typography' ), 'mobile' ) );
		$css->add_property( 'letter-spacing', $css->render_font_spacing( kadence()->option( 'mobile_secondary_navigation_typography' ), 'mobile' ) );
		$css->stop_media_query();
		$css->set_selector( '#mobile-secondary-site-navigation ul li a' );
		$css->add_property( 'padding-top', kadence()->sub_option( 'mobile_secondary_navigation_vertical_spacing', 'size' ) . kadence()->sub_option( 'mobile_secondary_navigation_vertical_spacing', 'unit' ) );
		$css->add_property( 'padding-bottom', kadence()->sub_option( 'mobile_secondary_navigation_vertical_spacing', 'size' ) . kadence()->sub_option( 'mobile_secondary_navigation_vertical_spacing', 'unit' ) );
		$css->set_selector( '#mobile-secondary-site-navigation ul li > a, #mobile-secondary-site-navigation ul li.menu-item-has-children > .drawer-nav-drop-wrap' );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'mobile_secondary_navigation_background', 'color' ) ) );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'mobile_secondary_navigation_color', 'color' ) ) );
		$css->set_selector( '#mobile-secondary-site-navigation ul li > a:hover, #mobile-secondary-site-navigation ul li.menu-item-has-children > .drawer-nav-drop-wrap:hover' );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'mobile_secondary_navigation_background', 'hover' ) ) );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'mobile_secondary_navigation_color', 'hover' ) ) );
		$css->set_selector( '#mobile-secondary-site-navigation ul li.current-menu-item > a, #mobile-secondary-site-navigation ul li.current-menu-item.menu-item-has-children > .drawer-nav-drop-wrap' );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'mobile_secondary_navigation_background', 'active' ) ) );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'mobile_secondary_navigation_color', 'active' ) ) );
		$css->set_selector( '#mobile-secondary-site-navigation ul li.menu-item-has-children .drawer-nav-drop-wrap, #mobile-secondary-site-navigation ul li:not(.menu-item-has-children) a' );
		$css->add_property( 'border-bottom', $css->render_border( kadence()->option( 'mobile_secondary_navigation_divider' ) ) );
		$css->set_selector( '#mobile-secondary-site-navigation:not(.drawer-navigation-parent-toggle-true) ul li.menu-item-has-children .drawer-nav-drop-wrap button' );
		$css->add_property( 'border-left', $css->render_border( kadence()->option( 'mobile_secondary_navigation_divider' ) ) );
		self::$google_fonts = $css->fonts_output();
		return $css->css_output();
	}
	/**
	 * Generates the size output.
	 *
	 * @param array $size an array of size settings.
	 * @return string
	 */
	public function render_negative_half_size( $size ) {
		if ( empty( $size ) ) {
			return false;
		}
		if ( ! is_array( $size ) ) {
			return false;
		}
		$size_number = ( isset( $size['size'] ) && ! empty( $size['size'] ) ? $size['size'] : '0' );
		$size_unit   = ( isset( $size['unit'] ) && ! empty( $size['unit'] ) ? $size['unit'] : 'em' );

		$size_string = 'calc(-' . $size_number . $size_unit . ' / 2)';
		return $size_string;
	}
	/**
	 * Generates the size output.
	 *
	 * @param array $size an array of size settings.
	 * @return string
	 */
	public function render_negative_size( $size ) {
		if ( empty( $size ) ) {
			return false;
		}
		if ( ! is_array( $size ) ) {
			return false;
		}
		$size_number = ( isset( $size['size'] ) && ! empty( $size['size'] ) ? $size['size'] : '0' );
		$size_unit   = ( isset( $size['unit'] ) && ! empty( $size['unit'] ) ? $size['unit'] : 'em' );

		$size_string = '-' . $size_number . $size_unit;
		return $size_string;
	}
	/**
	 * Registers the navigation menus.
	 */
	public function action_register_nav_menus() {
		register_nav_menus(
			array(
				'tertiary'         => esc_html__( 'Third', 'kadence-pro' ),
				'quaternary'       => esc_html__( 'Fourth', 'kadence-pro' ),
				'mobile-secondary' => esc_html__( 'Mobile Secondary', 'kadence-pro' ),
				'account'          => esc_html__( 'Logged Out Account', 'kadence-pro' ),
				'inaccount'        => esc_html__( 'Logged In Account', 'kadence-pro' ),
			)
		);
	}
	/**
	 * Get header html2 template.
	 */
	public function load_actions() {
		require_once KTP_PATH . 'dist/header-addons/hooks.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
	/**
	 * Get header button 2 template.
	 */
	public function header_mobile_secondary_navigation_output() {
		$this->locate_header_template( 'mobile-secondary-navigation.php' );
	}
	/**
	 * Get header button 2 template.
	 */
	public function header_toggle_widget_output() {
		$this->locate_header_template( 'toggle-widget.php' );
	}
	/**
	 * Get header button 2 template.
	 */
	public function header_button2_output() {
		$this->locate_header_template( 'button2.php' );
	}
	/**
	 * Get header button 2 template.
	 */
	public function header_mobile_button2_output() {
		$this->locate_header_template( 'mobile-button2.php' );
	}
	/**
	 * Get header contact template.
	 */
	public function header_mobile_contact_output() {
		$this->locate_header_template( 'mobile-contact.php' );
	}
	/**
	 * Get header contact template.
	 */
	public function header_mobile_search_bar_output() {
		$this->locate_header_template( 'mobile-search-bar.php' );
	}
	/**
	 * Get header contact template.
	 */
	public function header_contact_output() {
		$this->locate_header_template( 'contact.php' );
	}
	/**
	 * Get header divider template.
	 */
	public function header_widget1_output() {
		$this->locate_header_template( 'widget1.php' );
	}
	/**
	 * Get header search template.
	 */
	public function header_search_bar_output() {
		$this->locate_header_template( 'search-bar.php' );
	}
	/**
	 * Get header divider template.
	 */
	public function header_divider_output() {
		$this->locate_header_template( 'divider.php' );
	}
	/**
	 * Get header divider template.
	 */
	public function header_divider2_output() {
		$this->locate_header_template( 'divider2.php' );
	}
	/**
	 * Get header divider template.
	 */
	public function header_divider3_output() {
		$this->locate_header_template( 'divider3.php' );
	}
	/**
	 * Get header divider template.
	 */
	public function header_mobile_divider_output() {
		$this->locate_header_template( 'mobile-divider.php' );
	}
	/**
	 * Get header divider template.
	 */
	public function header_mobile_divider2_output() {
		$this->locate_header_template( 'mobile-divider2.php' );
	}
	/**
	 * Get header navigation-3 template.
	 */
	public function header_navigation3_output() {
		$this->locate_header_template( 'navigation-3.php' );
	}
	/**
	 * Get header navigation-4 template.
	 */
	public function header_navigation4_output() {
		$this->locate_header_template( 'navigation-4.php' );
	}
	/**
	 * Get header html2 template.
	 */
	public function header_html2_output() {
		$this->locate_header_template( 'html2.php' );
	}
	/**
	 * Get header mobile html2 template.
	 */
	public function header_mobile_html2_output() {
		$this->locate_header_template( 'mobile-html2.php' );
	}
	/**
	 * Get header account template.
	 */
	public function header_account_output() {
		$this->locate_header_template( 'account.php' );
	}

	/**
	 * Get header Mobile Account template.
	 */
	public function header_mobile_account_output() {
		$this->locate_header_template( 'mobile-account.php' );
	}
	/**
	 * Output header template.
	 *
	 * @param string $template_name the name of the template.
	 */
	public function locate_header_template( $template_name ) {
		$template_path = 'kadence_pro/';
		$default_path  = KTP_PATH . 'dist/header-addons/templates/';

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);
		// Get default template/.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		// Allow 3rd party plugin filter template file from their plugin.
		$filter_template = apply_filters( 'kadence_pro_get_template', $template, $template_name, $template_path, $default_path );

		if ( $filter_template !== $template ) {
			if ( ! file_exists( $filter_template ) ) {
				return;
			}
			$template = $filter_template;
		}

		include $template;
	}
	/**
	 * Add Defaults
	 *
	 * @access public
	 * @param array $defaults registered option defaults with kadence theme.
	 * @return array
	 */
	public function add_option_defaults( $defaults ) {
		// Header HTML 2.
		$header_addons = array(
			// Mobile Navigation.
			'mobile_secondary_navigation_reveal' => 'none',
			'mobile_secondary_navigation_collapse' => true,
			'mobile_secondary_navigation_parent_toggle' => false,
			'mobile_secondary_navigation_width'  => array(
				'size' => 200,
				'unit' => 'px',
			),
			'mobile_secondary_navigation_vertical_spacing'   => array(
				'size' => 1,
				'unit' => 'em',
			),
			'mobile_secondary_navigation_color'              => array(
				'color'  => 'palette8',
				'hover'  => '',
				'active' => 'palette-highlight',
			),
			'mobile_secondary_navigation_background'              => array(
				'color'  => '',
				'hover'  => '',
				'active' => '',
			),
			'mobile_secondary_navigation_divider'              => array(
				'width' => 1,
				'unit'  => 'px',
				'style' => 'solid',
				'color' => 'rgba(255,255,255,0.1)',
			),
			'mobile_secondary_navigation_typography'            => array(
				'size' => array(
					'desktop' => 14,
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
			),
			'header_html2_content'    => __( 'Insert HTML here', 'kadence-pro' ),
			'header_html2_wpautop'    => true,
			'header_html2_typography' => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
				'color'   => '',
			),
			'header_html2_link_style' => 'normal',
			'header_html2_link_color' => array(
				'color' => '',
				'hover' => '',
			),
			'header_html2_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			// Mobile HTML.
			'header_mobile_html2_content'    => __( 'Insert HTML here', 'kadence-pro' ),
			'header_mobile_html2_wpautop'    => true,
			'header_mobile_html2_typography' => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
				'color'   => '',
			),
			'header_mobile_html2_link_style' => 'normal',
			'header_mobile_html2_link_color' => array(
				'color' => '',
				'hover' => '',
			),
			'header_mobile_html2_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'header_account_preview'                 => 'in',
			'header_account_icon'                    => 'account',
			'header_account_link'                    => '',
			'header_account_action'                  => 'link',
			'header_account_dropdown_direction'      => 'right',
			'header_account_modal_registration'      => true,
			'header_account_modal_registration_link' => '',
			'header_account_style'                   => 'icon',
			'header_account_label'                   => __( 'Login', 'kadence-pro' ),
			'header_account_icon_size'               => array(
				'size' => '1.2',
				'unit' => 'em',
			),
			'header_account_color' => array(
				'color' => '',
				'hover' => '',
			),
			'header_account_background' => array(
				'color' => '',
				'hover' => '',
			),
			'header_account_radius' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => true,
			),
			'header_account_typography' => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
				'color'   => '',
			),
			'header_account_padding' => array(
				'size'   => array( '0.6', '0', '0.6', '0' ),
				'unit'   => 'em',
				'locked' => true,
			),
			'header_account_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'header_account_in_icon'                    => 'account',
			'header_account_in_link'                    => '',
			'header_account_in_action'                  => 'dropdown',
			'header_account_in_dropdown_source'         => 'navigation',
			'header_account_in_dropdown_direction'      => 'right',
			'header_account_in_style'                   => 'icon',
			'header_account_in_label'                   => __( 'Account', 'kadence-pro' ),
			'header_account_in_icon_size'               => array(
				'size' => '1.2',
				'unit' => 'em',
			),
			'header_account_in_image_radius' => array(
				'size'   => array( 100, 100, 100, 100 ),
				'unit'   => 'px',
				'locked' => true,
			),
			'header_account_in_color' => array(
				'color' => '',
				'hover' => '',
			),
			'header_account_in_background' => array(
				'color' => '',
				'hover' => '',
			),
			'header_account_in_radius' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => true,
			),
			'header_account_in_typography' => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
				'color'   => '',
			),
			'header_account_in_padding' => array(
				'size'   => array( '0.6', '0', '0.6', '0' ),
				'unit'   => 'em',
				'locked' => true,
			),
			'header_account_in_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			// Account Transparent.
			'transparent_header_account_color' => array(
				'color' => '',
				'hover' => '',
			),
			'transparent_header_account_background' => array(
				'color' => '',
				'hover' => '',
			),
			'transparent_header_account_in_color' => array(
				'color' => '',
				'hover' => '',
			),
			'transparent_header_account_in_background' => array(
				'color' => '',
				'hover' => '',
			),
			// Mobile Header Account.
			'header_mobile_account_preview'                 => 'in',
			'header_mobile_account_icon'                    => 'account',
			'header_mobile_account_link'                    => '',
			'header_mobile_account_action'                  => 'link',
			'header_mobile_account_modal_registration'      => true,
			'header_mobile_account_modal_registration_link' => '',
			'header_mobile_account_style'                   => 'icon',
			'header_mobile_account_label'                   => __( 'Login', 'kadence-pro' ),
			'header_mobile_account_icon_size'               => array(
				'size' => '1.2',
				'unit' => 'em',
			),
			'header_mobile_account_color' => array(
				'color' => '',
				'hover' => '',
			),
			'header_mobile_account_background' => array(
				'color' => '',
				'hover' => '',
			),
			'header_mobile_account_radius' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => true,
			),
			'header_mobile_account_typography' => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
				'color'   => '',
			),
			'header_mobile_account_padding' => array(
				'size'   => array( '0.6', '0', '0.6', '0' ),
				'unit'   => 'em',
				'locked' => true,
			),
			'header_mobile_account_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'header_mobile_account_in_icon'                    => 'account',
			'header_mobile_account_in_link'                    => '',
			'header_mobile_account_in_action'                  => 'link',
			'header_mobile_account_in_dropdown_source'         => 'navigation',
			'header_mobile_account_in_style'                   => 'icon',
			'header_mobile_account_in_label'                   => __( 'Account', 'kadence-pro' ),
			'header_mobile_account_in_icon_size'               => array(
				'size' => '1.2',
				'unit' => 'em',
			),
			'header_mobile_account_in_image_radius' => array(
				'size'   => array( 100, 100, 100, 100 ),
				'unit'   => 'px',
				'locked' => true,
			),
			'header_mobile_account_in_color' => array(
				'color' => '',
				'hover' => '',
			),
			'header_mobile_account_in_background' => array(
				'color' => '',
				'hover' => '',
			),
			'header_mobile_account_in_radius' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => true,
			),
			'header_mobile_account_in_typography' => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
				'color'   => '',
			),
			'header_mobile_account_in_padding' => array(
				'size'   => array( '0.6', '0', '0.6', '0' ),
				'unit'   => 'em',
				'locked' => true,
			),
			'header_mobile_account_in_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			// Transparent.
			'transparent_header_mobile_account_color' => array(
				'color' => '',
				'hover' => '',
			),
			'transparent_header_mobile_account_background' => array(
				'color' => '',
				'hover' => '',
			),
			'transparent_header_mobile_account_in_color' => array(
				'color' => '',
				'hover' => '',
			),
			'transparent_header_mobile_account_in_background' => array(
				'color' => '',
				'hover' => '',
			),
			// Tertiary Navigation.
			'tertiary_navigation_typography'        => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
			),
			'tertiary_navigation_spacing'          => array(
				'size' => 1.2,
				'unit' => 'em',
			),
			'tertiary_navigation_vertical_spacing' => array(
				'size' => 0.6,
				'unit' => 'em',
			),
			'tertiary_navigation_stretch'          => false,
			'tertiary_navigation_fill_stretch'     => false,
			'tertiary_navigation_style'            => 'standard',
			'tertiary_navigation_color'            => array(
				'color'  => 'palette5',
				'hover'  => 'palette-highlight',
				'active' => 'palette3',
			),
			'tertiary_navigation_background'       => array(
				'color'  => '',
				'hover'  => '',
				'active' => '',
			),
			// Quaternary Navigation.
			'quaternary_navigation_typography'        => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
			),
			'quaternary_navigation_spacing'          => array(
				'size' => 1.2,
				'unit' => 'em',
			),
			'quaternary_navigation_vertical_spacing' => array(
				'size' => 0.6,
				'unit' => 'em',
			),
			'quaternary_navigation_stretch'          => false,
			'quaternary_navigation_fill_stretch'     => false,
			'quaternary_navigation_style'            => 'standard',
			'quaternary_navigation_color'            => array(
				'color'  => 'palette5',
				'hover'  => 'palette-highlight',
				'active' => 'palette3',
			),
			'quaternary_navigation_background'       => array(
				'color'  => '',
				'hover'  => '',
				'active' => '',
			),
			// Header Divider.
			'header_divider_border' => array(
				'width' => 1,
				'unit'  => 'px',
				'style' => 'solid',
				'color' => 'palette6',
			),
			'header_divider_height' => array(
				'size' => 50,
				'unit' => '%',
			),
			'header_divider_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'transparent_header_divider_color' => array(
				'color' => '',
			),
			// Header Divider2.
			'header_divider2_border' => array(
				'width' => 1,
				'unit'  => 'px',
				'style' => 'solid',
				'color' => 'palette6',
			),
			'header_divider2_height' => array(
				'size' => 50,
				'unit' => '%',
			),
			'header_divider2_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'transparent_header_divider2_color' => array(
				'color' => '',
			),
			// Header Divider3.
			'header_divider3_border' => array(
				'width' => 1,
				'unit'  => 'px',
				'style' => 'solid',
				'color' => 'palette6',
			),
			'header_divider3_height' => array(
				'size' => 50,
				'unit' => '%',
			),
			'header_divider3_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'transparent_header_divider3_color' => array(
				'color' => '',
			),
			// Header Mobile Divider.
			'header_mobile_divider_border' => array(
				'width' => 1,
				'unit'  => 'px',
				'style' => 'solid',
				'color' => 'palette6',
			),
			'header_mobile_divider_height' => array(
				'size' => 50,
				'unit' => '%',
			),
			'header_mobile_divider_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'transparent_header_mobile_divider_color' => array(
				'color' => '',
			),
			// Header Mobile Divider 2.
			'header_mobile_divider2_border' => array(
				'width' => 1,
				'unit'  => 'px',
				'style' => 'solid',
				'color' => 'palette6',
			),
			'header_mobile_divider2_height' => array(
				'size' => 50,
				'unit' => '%',
			),
			'header_mobile_divider2_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'transparent_header_mobile_divider2_color' => array(
				'color' => '',
			),
			// Header Search Bar.
			'header_search_bar_woo' => true,
			'header_search_bar_width' => array(
				'size' => '240',
				'unit' => 'px',
			),
			'header_search_bar_border' => array(
				'width' => '',
				'unit'  => '',
				'style' => '',
				'color' => '',
			),
			'header_search_bar_color'       => array(
				'color'  => '',
				'hover'  => '',
			),
			'header_search_bar_background'       => array(
				'color'  => '',
				'hover'  => '',
			),
			'header_search_bar_typography'        => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
			),
			'header_search_bar_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'transparent_header_search_bar_color' => array(
				'color' => '',
				'hover'  => '',
			),
			'transparent_header_search_bar_background' => array(
				'color' => '',
				'hover'  => '',
			),
			'transparent_header_search_bar_border' => array(
				'color' => '',
				'hover'  => '',
			),
			'sticky_header_search_bar_color' => array(
				'color' => '',
				'hover'  => '',
			),
			'sticky_header_search_bar_background' => array(
				'color' => '',
				'hover'  => '',
			),
			'sticky_header_search_bar_border' => array(
				'color' => '',
				'hover'  => '',
			),
			// Header Mobile Search Bar.
			'header_mobile_search_bar_woo' => true,
			'header_mobile_search_bar_width' => array(
				'size' => '240',
				'unit' => 'px',
			),
			'header_mobile_search_bar_border' => array(
				'width' => '',
				'unit'  => '',
				'style' => '',
				'color' => '',
			),
			'header_mobile_search_bar_color'       => array(
				'color'  => '',
				'hover'  => '',
			),
			'header_mobile_search_bar_background'       => array(
				'color'  => '',
				'hover'  => '',
			),
			'header_mobile_search_bar_typography'        => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
			),
			'header_mobile_search_bar_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'transparent_header_mobile_search_bar_color' => array(
				'color' => '',
				'hover'  => '',
			),
			'transparent_header_mobile_search_bar_background' => array(
				'color' => '',
				'hover'  => '',
			),
			'transparent_header_mobile_search_bar_border' => array(
				'color' => '',
				'hover'  => '',
			),
			'sticky_header_mobile_search_bar_color' => array(
				'color' => '',
				'hover'  => '',
			),
			'sticky_header_mobile_search_bar_background' => array(
				'color' => '',
				'hover'  => '',
			),
			'sticky_header_mobile_search_bar_border' => array(
				'color' => '',
				'hover'  => '',
			),
			// Header Widget Area.
			'header_widget1_link_colors'       => array(
				'color'  => '',
				'hover'  => '',
			),
			'header_widget1_title'        => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
			),
			'header_widget1_content'        => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
			),
			'header_widget1_link_style' => 'plain',
			'header_widget1_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'transparent_header_widget1_color' => array(
				'color' => '',
				'link'  => '',
				'hover' => '',
			),
			// Header Contact.
			'header_contact_items' => array(
				'items' => array(
					array(
						'id'      => 'phone',
						'enabled' => true,
						'source'  => 'icon',
						'url'     => '',
						'imageid' => '',
						'width'   => 24,
						'link'     => '',
						'icon'    => 'phone',
						'label'   => '444-546-8765',
					),
					array(
						'id'      => 'hours',
						'enabled' => true,
						'source'  => 'icon',
						'url'     => '',
						'imageid' => '',
						'width'   => 24,
						'link'     => '',
						'icon'    => 'hours',
						'label'   => 'Mon - Fri: 8AM - 5PM',
					),
				),
			),
			'header_contact_item_spacing' => array(
				'size' => 0.6,
				'unit' => 'em',
			),
			'header_contact_icon_size' => array(
				'size' => 1,
				'unit' => 'em',
			),
			'header_contact_color' => array(
				'color' => '',
				'hover' => '',
			),
			'header_contact_link_style' => 'plain',
			'header_contact_typography' => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
			),
			'header_contact_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'sticky_header_contact_color' => array(
				'color' => '',
				'hover' => '',
			),
			'transparent_header_contact_color' => array(
				'color' => '',
				'hover' => '',
			),
			// Header Mobile Contact.
			'header_mobile_contact_items' => array(
				'items' => array(
					array(
						'id'      => 'phone',
						'enabled' => true,
						'source'  => 'icon',
						'url'     => '',
						'imageid' => '',
						'width'   => 24,
						'link'     => '',
						'icon'    => 'phone',
						'label'   => '444-546-8765',
					),
					array(
						'id'      => 'hours',
						'enabled' => true,
						'source'  => 'icon',
						'url'     => '',
						'imageid' => '',
						'width'   => 24,
						'link'     => '',
						'icon'    => 'hours',
						'label'   => 'Mon - Fri: 8AM - 5PM',
					),
				),
			),
			'header_mobile_contact_item_spacing' => array(
				'size' => 0.6,
				'unit' => 'em',
			),
			'header_mobile_contact_item_vspacing' => array(
				'size' => 0.6,
				'unit' => 'em',
			),
			'header_mobile_contact_icon_size' => array(
				'size' => 1,
				'unit' => 'em',
			),
			'header_mobile_contact_color' => array(
				'color' => '',
				'hover' => '',
			),
			'header_mobile_contact_link_style' => 'plain',
			'header_mobile_contact_typography' => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
			),
			'header_mobile_contact_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'transparent_header_mobile_contact_color' => array(
				'color' => '',
				'hover' => '',
			),
			// Header Button 2.
			'header_button2_label'      => __( 'Button', 'kadence-pro' ),
			'header_button2_link'      => '',
			'header_button2_style'      => 'filled',
			'header_button2_size'       => 'medium',
			'header_button2_visibility' => 'all',
			'header_button2_padding'   => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'header_button2_typography' => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
			),
			'header_button2_color'              => array(
				'color' => '',
				'hover' => '',
			),
			'header_button2_background'              => array(
				'color' => '',
				'hover' => '',
			),
			'header_button2_border_colors'              => array(
				'color' => '',
				'hover' => '',
			),
			'header_button2_border'              => array(
				'width' => 2,
				'unit'  => 'px',
				'style' => 'none',
			),
			'header_button2_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'header_button2_radius' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => true,
			),
			'header_button2_shadow' => array(
				'color'   => 'rgba(0,0,0,0)',
				'hOffset' => 0,
				'vOffset' => 0,
				'blur'    => 0,
				'spread'  => -7,
				'inset'   => false,
			),
			'header_button2_shadow_hover' => array(
				'color'   => 'rgba(0,0,0,0.1)',
				'hOffset' => 0,
				'vOffset' => 15,
				'blur'    => 25,
				'spread'  => -7,
				'inset'   => false,
			),
			'transparent_header_button2_color'              => array(
				'color'           => '',
				'hover'           => '',
				'background'      => '',
				'backgroundHover' => '',
				'border'          => '',
				'borderHover'     => '',
			),
			'header_sticky_button2_color'              => array(
				'color'           => '',
				'hover'           => '',
				'background'      => '',
				'backgroundHover' => '',
				'border'          => '',
				'borderHover'     => '',
			),
			// Mobile Header Button2.
			'mobile_button2_label'      => __( 'Button', 'kadence-pro' ),
			'mobile_button2_style'      => 'filled',
			'mobile_button2_size'       => 'medium',
			'mobile_button2_visibility' => 'all',
			'mobile_button2_typography' => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
			),
			'mobile_button2_color'              => array(
				'color' => '',
				'hover' => '',
			),
			'mobile_button2_background'              => array(
				'color' => '',
				'hover' => '',
			),
			'mobile_button2_border_colors'              => array(
				'color' => '',
				'hover' => '',
			),
			'mobile_button2_border'              => array(
				'width' => 2,
				'unit'  => 'px',
				'style' => 'none',
			),
			'mobile_button2_radius' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => true,
			),
			'mobile_button2_shadow' => array(
				'color'   => 'rgba(0,0,0,0)',
				'hOffset' => 0,
				'vOffset' => 0,
				'blur'    => 0,
				'spread'  => -7,
				'inset'   => false,
			),
			'mobile_button2_shadow_hover' => array(
				'color'   => 'rgba(0,0,0,0.1)',
				'hOffset' => 0,
				'vOffset' => 15,
				'blur'    => 25,
				'spread'  => -7,
				'inset'   => false,
			),
			'mobile_button2_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			// Widget Toggle.
			'header_toggle_widget_label'  => '',
			'header_toggle_widget_icon'   => 'menu',
			'header_toggle_widget_style'  => 'default',
			'header_toggle_widget_border' => array(
				'width' => 1,
				'unit'  => 'px',
				'style' => 'solid',
				'color' => 'currentColor',
			),
			'header_toggle_widget_icon_size'   => array(
				'size' => 20,
				'unit' => 'px',
			),
			'header_toggle_widget_color'              => array(
				'color' => 'palette5',
				'hover' => 'palette-highlight',
			),
			'header_toggle_widget_background'              => array(
				'color' => '',
				'hover' => '',
			),
			'header_toggle_widget_typography'            => array(
				'size' => array(
					'desktop' => 14,
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
			),
			'header_toggle_widget_padding' => array(
				'size'   => array( 0.4, 0.6, 0.4, 0.6 ),
				'unit'   => 'em',
				'locked' => false,
			),
			'transparent_toggle_widget_color'              => array(
				'color'           => '',
				'hover'           => '',
				'background'      => '',
				'backgroundHover' => '',
				'border'          => '',
				'borderHover'     => '',
			),
			'header_sticky_toggle_widget_color'              => array(
				'color'           => '',
				'hover'           => '',
				'background'      => '',
				'backgroundHover' => '',
				'border'          => '',
				'borderHover'     => '',
			),
			'header_toggle_widget_side'       => 'right',
			'header_toggle_widget_layout'     => 'sidepanel',
			'header_toggle_widget_pop_width'  => array(
				'size' => 400,
				'unit' => 'px',
			),
			'header_toggle_widget_pop_background' => array(
				'desktop' => array(
					'color' => '',
				),
			),
			'header_toggle_widget_close_color'  => array(
				'color' => '',
				'hover' => '',
			),
			// Header toggle Widget Area.
			'header_widget2_link_colors'       => array(
				'color'  => 'palette8',
				'hover'  => 'palette9',
			),
			'header_widget2_title'        => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
				'color'   => 'palette9',
			),
			'header_widget2_content'        => array(
				'size' => array(
					'desktop' => '',
				),
				'lineHeight' => array(
					'desktop' => '',
				),
				'family'  => 'inherit',
				'google'  => false,
				'weight'  => '',
				'variant' => '',
				'color'   => 'palette8',
			),
			'header_widget2_link_style' => 'plain',
			'header_widget2_padding' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
		);
		$defaults = array_merge(
			$defaults,
			$header_addons
		);
		return $defaults;
	}
	/**
	 * Add Choices
	 *
	 * @access public
	 * @param array $choices registered choices with kadence theme.
	 * @return array
	 */
	public function add_customizer_header_choices( $choices ) {
		$choices['header_desktop_items']['account'] = array(
			'name'    => esc_html__( 'Account', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_account',
		);
		$choices['header_mobile_items']['mobile-nav2'] = array(
			'name'    => esc_html__( 'Mobile Navigation 2', 'kadence-pro' ),
			'section' => 'kadence_customizer_mobile_secondary_navigation',
		);
		$choices['header_mobile_items']['mobile-account'] = array(
			'name'    => esc_html__( 'Account', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_mobile_account',
		);
		$choices['header_desktop_items']['html2'] = array(
			'name'    => esc_html__( 'HTML 2', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_html2',
		);
		$choices['header_mobile_items']['mobile-html2'] = array(
			'name'    => esc_html__( 'HTML 2', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_mobile_html2',
		);
		$choices['header_desktop_items']['navigation-3'] = array(
			'name'    => esc_html__( 'Third Navigation', 'kadence-pro' ),
			'section' => 'kadence_customizer_tertiary_navigation',
		);
		$choices['header_desktop_items']['navigation-4'] = array(
			'name'    => esc_html__( 'Fourth Navigation', 'kadence-pro' ),
			'section' => 'kadence_customizer_quaternary_navigation',
		);
		$choices['header_desktop_items']['divider'] = array(
			'name'    => esc_html__( 'Divider', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_divider',
		);
		$choices['header_desktop_items']['divider2'] = array(
			'name'    => esc_html__( 'Divider 2', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_divider2',
		);
		$choices['header_desktop_items']['divider3'] = array(
			'name'    => esc_html__( 'Divider 3', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_divider3',
		);
		$choices['header_mobile_items']['mobile-divider'] = array(
			'name'    => esc_html__( 'Divider', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_mobile_divider',
		);
		$choices['header_mobile_items']['mobile-divider2'] = array(
			'name'    => esc_html__( 'Divider 2', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_mobile_divider2',
		);
		$choices['header_desktop_items']['search-bar'] = array(
			'name'    => esc_html__( 'Search Bar', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_search_bar',
		);
		$choices['header_desktop_items']['widget1'] = array(
			'name'    => esc_html__( 'Widget area', 'kadence-pro' ),
			'section' => 'sidebar-widgets-header1',
		);
		$choices['header_desktop_items']['contact'] = array(
			'name'    => esc_html__( 'Contact', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_contact',
		);
		$choices['header_desktop_items']['button2'] = array(
			'name'    => esc_html__( 'Button 2', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_button2',
		);
		$choices['header_desktop_items']['toggle-widget'] = array(
			'name'    => esc_html__( 'Toggle Widget Area', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_toggle_widget',
		);
		$choices['header_mobile_items']['mobile-button2'] = array(
			'name'    => esc_html__( 'Button 2', 'kadence-pro' ),
			'section' => 'kadence_customizer_mobile_button2',
		);
		$choices['header_mobile_items']['mobile-contact'] = array(
			'name'    => esc_html__( 'Contact', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_mobile_contact',
		);
		$choices['header_mobile_items']['mobile-search-bar'] = array(
			'name'    => esc_html__( 'Search Bar', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_mobile_search_bar',
		);
		return $choices;
	}
	/**
	 * Add Sections
	 *
	 * @access public
	 * @param array $sections registered sections with kadence theme.
	 * @return array
	 */
	public function add_customizer_sections( $sections ) {
		$sections['header_account']        = array(
			'title'    => __( 'Header Account', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_account_design'] = array(
			'title'    => __( 'Header Account', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_mobile_account']        = array(
			'title'    => __( 'Header Account', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_mobile_account_design'] = array(
			'title'    => __( 'Header Account', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_html2']        = array(
			'title'    => __( 'Header HTML2', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_html2_design'] = array(
			'title'    => __( 'Header HTML2', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['mobile_secondary_navigation']        = array(
			'title'    => __( 'Mobile Navigation 2', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['mobile_secondary_navigation_design'] = array(
			'title'    => __( 'Mobile Navigation 2', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_mobile_html2']        = array(
			'title'    => __( 'Header HTML2', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_mobile_html2_design'] = array(
			'title'    => __( 'Header HTML2', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['tertiary_navigation']        = array(
			'title'    => __( 'Third Navigation', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['tertiary_navigation_design'] = array(
			'title'    => __( 'Third Navigation', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['quaternary_navigation']        = array(
			'title'    => __( 'Fourth Navigation', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['quaternary_navigation_design'] = array(
			'title'    => __( 'Fourth Navigation', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_divider'] = array(
			'title'    => __( 'Divider', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_divider2'] = array(
			'title'    => __( 'Divider2', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_divider3'] = array(
			'title'    => __( 'Divider3', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_mobile_divider'] = array(
			'title'    => __( 'Mobile Divider', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_mobile_divider2'] = array(
			'title'    => __( 'Mobile Divider2', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_search_bar'] = array(
			'title'    => __( 'Search Bar', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_search_bar_design'] = array(
			'title'    => __( 'Search Bar Design', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_mobile_search_bar'] = array(
			'title'    => __( 'Search Bar', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_mobile_search_bar_design'] = array(
			'title'    => __( 'Search Bar Design', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_contact'] = array(
			'title'    => __( 'Contact', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_contact_design'] = array(
			'title'    => __( 'Contact Design', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_toggle_widget'] = array(
			'title'    => __( 'Toggle Widget Area', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_toggle_widget_design'] = array(
			'title'    => __( 'Toggle Widget Area Design', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_button2'] = array(
			'title'    => __( 'Button 2', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_button2_design'] = array(
			'title'    => __( 'Button 2 Design', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['mobile_button2'] = array(
			'title'    => __( 'Mobile Button 2', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['mobile_button2_design'] = array(
			'title'    => __( 'Mobile Button 2 Design', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_mobile_contact'] = array(
			'title'    => __( 'Mobile Contact', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		$sections['header_mobile_contact_design'] = array(
			'title'    => __( 'Mobile Contact Design', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 20,
		);
		return $sections;
	}
	/**
	 * Registers the sidebars.
	 */
	public function action_register_sidebars() {
		$widgets = array(
			'header1' => __( 'Header Area', 'kadence-pro' ),
			'header2' => __( 'Header Off Canvas', 'kadence-pro' ),
		);

		foreach ( $widgets as $id => $name ) {
			register_sidebar(
				apply_filters(
					'kadence_pro_widget_area_args',
					array(
						'name'          => $name,
						'id'            => $id,
						'description'   => esc_html__( 'Add widgets here.', 'kadence-pro' ),
						'before_widget' => '<section id="%1$s" class="widget %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => '<h2 class="widget-title">',
						'after_title'   => '</h2>',
					)
				)
			);
		}
	}
	/**
	 * Filter header widget areas.
	 *
	 * @param array  $section_args the widget sections args.
	 * @param string $section_id the widget sections id.
	 * @param string $sidebar_id the widget area id.
	 */
	public function customizer_custom_widget_areas( $section_args, $section_id, $sidebar_id ) {
		if ( 'header1' === $sidebar_id || 'header2' === $sidebar_id ) {
			$section_args['panel'] = 'kadence_customizer_header';
		}
		return $section_args;
	}
	/**
	 * Add settings
	 *
	 * @access public
	 * @param object $wp_customize the customizer object.
	 * @return void
	 */
	public function create_pro_settings_array( $wp_customize ) {
		// Load Settings files.
		foreach ( self::$settings_sections as $key ) {
			require_once KTP_PATH . 'dist/header-addons/' . $key . '-options.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}
}

Header_Addons::get_instance();
