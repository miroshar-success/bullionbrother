<?php
/**
 * Class for the Customizer
 *
 * @package Kadence
 */

namespace Kadence_Pro;

use function Kadence\kadence;
use Kadence\Kadence_CSS;
use function Kadence_Pro\output_color_switcher;
use function __return_true;
use Kadence_Blocks_Frontend;
use version_compare;
use defined;

/**
 * Main plugin class
 */
class Dark_Mode {
	/**
	 * Instance Control
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Holds theme settings array sections.
	 *
	 * @var the theme settings sections.
	 */
	public static $settings_sections = array(
		'dark-mode',
		'header-dark-mode',
		'mobile-dark-mode',
		'footer-dark-mode',
	);

	/**
	 * Associative array of Google Fonts to load.
	 *
	 * Do not access this property directly, instead use the `get_google_fonts()` method.
	 *
	 * @var array
	 */
	protected static $google_fonts = array();

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
		add_filter( 'kadence_theme_customizer_sections', array( $this, 'add_customizer_sections' ), 10 );
		add_filter( 'kadence_theme_customizer_control_choices', array( $this, 'add_customizer_header_footer_choices' ), 10 );
		add_action( 'customize_register', array( $this, 'create_pro_settings_array' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'after_setup_theme', array( $this, 'load_actions' ), 20 );
		add_filter( 'body_class', array( $this, 'add_body_class' ) );
		add_filter( 'template_redirect', array( $this, 'filter_dark_mode_learn_dash' ), 80 );
		add_action( 'get_template_part_template-parts/header/dark-mode', array( $this, 'header_dark_mode_output' ), 10 );
		add_action( 'get_template_part_template-parts/header/mobile-dark-mode', array( $this, 'mobile_dark_mode_output' ), 10 );
		add_action( 'get_template_part_template-parts/footer/footer-dark-mode', array( $this, 'footer_dark_mode_output' ), 10 );
		add_action( 'wp_head', array( $this, 'frontend_gfonts' ), 80 );
		add_shortcode( 'kadence_dark_mode', array( $this, 'output_dark_mode_switch_shortcode' ) );
	}
	/**
	 * On Load
	 */
	public function filter_dark_mode_learn_dash() {
		if ( class_exists( 'LearnDash_Settings_Section' ) && kadence()->option( 'dark_mode_enable' ) && kadence()->option( 'dark_mode_learndash_enable' ) && kadence()->option( 'dark_mode_learndash_lesson_only' ) ) {
			if ( ! is_singular( 'sfwd-lessons' ) ) {
				add_filter( 'kadence_dark_mode_enable', '__return_false' );
			}
		}
	}
	/**
	 * On Load
	 */
	public function output_dark_mode_switch_shortcode( $atts ) {
		$args = shortcode_atts(
			array(
				'show_title' => true,
			),
			$atts
		);
		$output = '';
		if ( kadence()->option( 'dark_mode_enable' ) && kadence()->option( 'dark_mode_switch_show' ) && apply_filters( 'kadence_dark_mode_enable', true ) ) {
			ob_start();
			echo '<div class="kadence-color-palette-shortcode-switcher">';
			echo Kadence_Pro\output_color_switcher( kadence()->option( 'dark_mode_switch_type' ), kadence()->option( 'dark_mode_switch_style' ), kadence()->option( 'dark_mode_light_icon' ), kadence()->option( 'dark_mode_dark_icon' ), kadence()->option( 'dark_mode_light_switch_title' ), kadence()->option( 'dark_mode_dark_switch_title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
			$output = ob_get_clean();
		}
		return $output;
	}
	/**
	 * Get header dark mode template.
	 */
	public function header_dark_mode_output() {
		$this->locate_template( 'header-dark-mode.php' );
	}
	/**
	 * Get mobile dark mode template.
	 */
	public function mobile_dark_mode_output() {
		$this->locate_template( 'mobile-dark-mode.php' );
	}
	/**
	 * Get footer dark mode template.
	 */
	public function footer_dark_mode_output() {
		$this->locate_template( 'footer-dark-mode.php' );
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
	 * Get actions.
	 */
	public function load_actions() {
		require_once KTP_PATH . 'dist/dark-mode/hooks.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
	/**
	 * Add body class.
	 *
	 * @param array $classes the body classes.
	 */
	public function add_body_class( $classes ) {
		if ( kadence()->option( 'dark_mode_enable' ) && apply_filters( 'kadence_dark_mode_enable', true ) ) {
			$cookie_name = substr( base_convert( md5( get_site_url() ), 16, 32 ), 0, 12 ) . '-paletteCookie';
			if ( ! empty( $_COOKIE[ $cookie_name ] ) ) {
				if ( sanitize_text_field( wp_unslash( $_COOKIE[ $cookie_name ] ) ) === 'dark' ) {
					$classes[] = 'color-switch-dark';
				} elseif ( sanitize_text_field( wp_unslash( $_COOKIE[ $cookie_name ] ) ) === 'light' ) {
					$classes[] = 'color-switch-light';
				} else {
					$classes[] = 'color-switch-' . kadence()->option( 'dark_mode_default' );
				}
			} else {
				$classes[] = 'color-switch-' . kadence()->option( 'dark_mode_default' );
			}
			if ( kadence()->option( 'dark_mode_custom_logo' ) ) {
				$classes[] = 'has-dark-logo';
			}
		}
		return $classes;
	}

	/**
	 * Add Defaults
	 *
	 * @access public
	 * @param array $defaults registered option defaults with kadence theme.
	 * @return array
	 */
	public function add_option_defaults( $defaults ) {
		$dark_mode_addons = array(
			'dark_mode_enable'             => false,
			'dark_mode_default'            => 'light',
			'dark_mode_os_aware'           => false,
			'dark_mode_dark_palette'       => 'second-palette',
			'dark_mode_switch_show'        => true,
			'dark_mode_switch_type'        => 'icon',
			'dark_mode_switch_style'       => 'switch',
			'dark_mode_switch_position'    => 'right',
			'dark_mode_logo'               => '',
			'dark_mode_custom_logo'        => false,
			'dark_mode_mobile_logo'        => '',
			'dark_mode_mobile_custom_logo' => false,
			'dark_mode_learndash_enable'   => false,
			'dark_mode_learndash_lesson_only' => false,
			'dark_mode_learndash_lesson_logo' => '',
			'dark_mode_switch_tooltip'     => false,
			'dark_mode_light_switch_title' => esc_html__( 'Light', 'kadence-pro' ),
			'dark_mode_dark_switch_title'  => esc_html__( 'Dark', 'kadence-pro' ),
			'dark_mode_light_color'        => '#F7FAFC',
			'dark_mode_light_icon'         => 'sun',
			'dark_mode_dark_color'         => '#2D3748',
			'dark_mode_colors'             => array(
				'light' => '#F7FAFC',
				'dark' => '#2D3748',
			),
			'dark_mode_dark_icon'          => 'moon',
			'dark_mode_icon_size'   => array(
				'size' => array(
					'desktop' => 1.2,
				),
				'unit' => array(
					'desktop' => 'em',
				),
			),
			'dark_mode_typography'            => array(
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
			'dark_mode_switch_visibility' => array(
				'desktop' => true,
				'tablet'  => true,
				'mobile'  => false,
			),
			'dark_mode_switch_side_offset'   => array(
				'size' => array(
					'mobile'  => '',
					'tablet'  => '',
					'desktop' => 30,
				),
				'unit' => array(
					'mobile'  => 'px',
					'tablet'  => 'px',
					'desktop' => 'px',
				),
			),
			'dark_mode_switch_bottom_offset'   => array(
				'size' => array(
					'mobile'  => '',
					'tablet'  => '',
					'desktop' => 30,
				),
				'unit' => array(
					'mobile'  => 'px',
					'tablet'  => 'px',
					'desktop' => 'px',
				),
			),
			'header_dark_mode_switch_type'        => 'icon',
			'header_dark_mode_switch_style'       => 'switch',
			'header_dark_mode_switch_tooltip'     => false,
			'header_dark_mode_light_switch_title' => esc_html__( 'Light', 'kadence-pro' ),
			'header_dark_mode_dark_switch_title'  => esc_html__( 'Dark', 'kadence-pro' ),
			'header_dark_mode_light_color'        => '#F7FAFC',
			'header_dark_mode_light_icon'         => 'sun',
			'header_dark_mode_dark_color'         => '#2D3748',
			'header_dark_mode_colors'             => array(
				'light' => '#F7FAFC',
				'dark' => '#2D3748',
			),
			'header_dark_mode_dark_icon'          => 'moon',
			'header_dark_mode_icon_size'   => array(
				'size' => array(
					'desktop' => 1.2,
				),
				'unit' => array(
					'desktop' => 'em',
				),
			),
			'header_dark_mode_typography'            => array(
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
			'header_dark_mode_switch_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'mobile_dark_mode_switch_type'        => 'icon',
			'mobile_dark_mode_switch_style'       => 'switch',
			'mobile_dark_mode_light_switch_title' => esc_html__( 'Light', 'kadence-pro' ),
			'mobile_dark_mode_dark_switch_title'  => esc_html__( 'Dark', 'kadence-pro' ),
			'mobile_dark_mode_light_color'        => '#F7FAFC',
			'mobile_dark_mode_light_icon'         => 'sun',
			'mobile_dark_mode_dark_color'         => '#2D3748',
			'mobile_dark_mode_colors'             => array(
				'light' => '#F7FAFC',
				'dark' => '#2D3748',
			),
			'mobile_dark_mode_dark_icon'          => 'moon',
			'mobile_dark_mode_icon_size'   => array(
				'size' => array(
					'desktop' => 1.2,
				),
				'unit' => array(
					'desktop' => 'em',
				),
			),
			'mobile_dark_mode_typography'            => array(
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
			'mobile_dark_mode_switch_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
			'footer_dark_mode_switch_type'        => 'icon',
			'footer_dark_mode_switch_style'       => 'switch',
			'footer_dark_mode_light_switch_title' => esc_html__( 'Light', 'kadence-pro' ),
			'footer_dark_mode_dark_switch_title'  => esc_html__( 'Dark', 'kadence-pro' ),
			'footer_dark_mode_light_color'        => '#F7FAFC',
			'footer_dark_mode_light_icon'         => 'sun',
			'footer_dark_mode_dark_color'         => '#2D3748',
			'footer_dark_mode_switch_tooltip'     => false,
			'footer_dark_mode_colors'             => array(
				'light' => '#F7FAFC',
				'dark' => '#2D3748',
			),
			'footer_dark_mode_dark_icon'          => 'moon',
			'footer_dark_mode_icon_size'   => array(
				'size' => array(
					'desktop' => 1.2,
				),
				'unit' => array(
					'desktop' => 'em',
				),
			),
			'footer_dark_mode_typography'            => array(
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
			'footer_dark_mode_switch_margin' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
		);
		$defaults = array_merge(
			$defaults,
			$dark_mode_addons
		);
		return $defaults;
	}
	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts() {
		if ( ! kadence()->option( 'dark_mode_enable' ) && apply_filters( 'kadence_dark_mode_enable', true ) ) {
			return;
		}
		if ( ! defined( 'KADENCE_VERSION' ) ) {
			return;
		}
		if ( ! ( version_compare( KADENCE_VERSION, '1.1.4' ) >= 0 ) ) {
			return;
		}
		$active = kadence()->option( 'dark_mode_dark_palette' );
		$css = new Kadence_CSS();
		$media_query            = array();
		$media_query['mobile']  = apply_filters( 'kadence_mobile_media_query', '(max-width: 767px)' );
		$media_query['tablet']  = apply_filters( 'kadence_tablet_media_query', '(max-width: 1024px)' );
		$media_query['desktop'] = apply_filters( 'kadence_tablet_media_query', '(min-width: 1025px)' );
		$css->set_selector( ':root' );
		$css->add_property( 'color-scheme', 'light dark' );
		$css->set_selector( 'html' );
		$css->add_property( 'color-scheme', 'light' );
		$css->set_selector( 'html body' );
		$css->add_property( '--global-light-toggle-switch', $css->render_color( kadence()->sub_option( 'dark_mode_colors', 'light' ) ) );
		$css->add_property( '--global-dark-toggle-switch', $css->render_color( kadence()->sub_option( 'dark_mode_colors', 'dark' ) ) );
		$css->set_selector( 'body.color-switch-dark' );
		$css->add_property( 'color-scheme', 'dark' );
		$css->add_property( '--global-gray-400', '#4B5563' );
		$css->add_property( '--global-gray-500', '#6B7280' );
		$css->add_property( '--global-palette1', kadence()->palette_option( 'palette1', $active ) );
		$css->add_property( '--global-palette2', kadence()->palette_option( 'palette2', $active ) );
		$css->add_property( '--global-palette3', kadence()->palette_option( 'palette3', $active ) );
		$css->add_property( '--global-palette4', kadence()->palette_option( 'palette4', $active ) );
		$css->add_property( '--global-palette5', kadence()->palette_option( 'palette5', $active ) );
		$css->add_property( '--global-palette6', kadence()->palette_option( 'palette6', $active ) );
		$css->add_property( '--global-palette7', kadence()->palette_option( 'palette7', $active ) );
		$css->add_property( '--global-palette8', kadence()->palette_option( 'palette8', $active ) );
		$css->add_property( '--global-palette9', kadence()->palette_option( 'palette9', $active ) );
		$css->add_property( '--global-palette9rgb', $css->hex2rgb( kadence()->palette_option( 'palette9', $active ) ) );
		$css->add_property( '--global-palette-highlight', $css->render_color( kadence()->sub_option( 'link_color', 'highlight' ) ) );
		$css->add_property( '--global-palette-highlight-alt', $css->render_color( kadence()->sub_option( 'link_color', 'highlight-alt' ) ) );
		$css->add_property( '--global-palette-highlight-alt2', $css->render_color( kadence()->sub_option( 'link_color', 'highlight-alt2' ) ) );

		$css->add_property( '--global-palette-btn-bg', $css->render_color( kadence()->sub_option( 'buttons_background', 'color' ) ) );
		$css->add_property( '--global-palette-btn-bg-hover', $css->render_color( kadence()->sub_option( 'buttons_background', 'hover' ) ) );

		$css->add_property( '--global-palette-btn', $css->render_color( kadence()->sub_option( 'buttons_color', 'color' ) ) );
		$css->add_property( '--global-palette-btn-hover', $css->render_color( kadence()->sub_option( 'buttons_color', 'hover' ) ) );
		// TEC.
		$css->add_property( '--tec-color-background-events', 'var(--global-palette9)' );
		$css->add_property( '--tec-color-text-event-date', 'var(--global-palette3)' );
		$css->add_property( '--tec-color-text-event-title', 'var(--global-palette3)' );
		$css->add_property( '--tec-color-text-events-title', 'var(--global-palette3)' );
		$css->add_property( '--tec-color-background-view-selector-list-item-hover', 'var(--global-palette7)' );
		$css->add_property( '--tec-color-background-secondary', 'var(--global-palette7)' );
		$css->add_property( '--tec-color-link-primary', 'var(--global-palette3)' );
		$css->add_property( '--tec-color-icon-active', 'var(--global-palette3)' );
		$css->add_property( '--tec-color-day-marker-month', 'var(--global-palette4)' );
		$css->add_property( '--tec-color-border-active-month-grid-hover', 'var(--global-palette5)' );
		$css->add_property( '--tec-color-accent-primary', 'var(--global-palette1)' );
		// Fixed Switcher.
		$css->set_selector( '.kadence-color-palette-fixed-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-label' );
		$css->render_font( kadence()->option( 'dark_mode_typography' ), $css );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '.kadence-color-palette-fixed-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-label' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'dark_mode_typography' ), 'tablet' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'dark_mode_typography' ), 'tablet' ) );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '.kadence-color-palette-fixed-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-label' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'dark_mode_typography' ), 'mobile' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'dark_mode_typography' ), 'mobile' ) );
		$css->stop_media_query();
		$css->set_selector( '.kadence-color-palette-fixed-switcher' );
		$css->add_property( 'bottom', $css->render_range( kadence()->option( 'dark_mode_switch_bottom_offset' ), 'desktop' ) );
		$css->set_selector( '.kadence-color-palette-fixed-switcher.kcpf-position-right' );
		$css->add_property( 'right', $css->render_range( kadence()->option( 'dark_mode_switch_side_offset' ), 'desktop' ) );
		$css->set_selector( '.kadence-color-palette-fixed-switcher.kcpf-position-left' );
		$css->add_property( 'left', $css->render_range( kadence()->option( 'dark_mode_switch_side_offset' ), 'desktop' ) );
		$css->set_selector( '.kadence-color-palette-fixed-switcher .kadence-color-palette-switcher.kcps-style-switch.kcps-type-icon button.kadence-color-palette-toggle:after' );
		$css->add_property( 'width', 'calc( ' . $css->render_range( kadence()->option( 'dark_mode_icon_size' ), 'desktop' ) . ' + .3em )' );
		$css->add_property( 'height', 'calc( ' . $css->render_range( kadence()->option( 'dark_mode_icon_size' ), 'desktop' ) . ' + .3em )' );
		$css->set_selector( '.kadence-color-palette-fixed-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-icon' );
		$css->add_property( 'font-size', $css->render_range( kadence()->option( 'dark_mode_icon_size' ), 'desktop' ) );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '.kadence-color-palette-fixed-switcher' );
		$css->add_property( 'bottom', $css->render_range( kadence()->option( 'dark_mode_switch_bottom_offset' ), 'tablet' ) );
		$css->set_selector( '.kadence-color-palette-fixed-switcher.kcpf-position-right' );
		$css->add_property( 'right', $css->render_range( kadence()->option( 'dark_mode_switch_side_offset' ), 'tablet' ) );
		$css->set_selector( '.kadence-color-palette-fixed-switcher.kcpf-position-left' );
		$css->add_property( 'left', $css->render_range( kadence()->option( 'dark_mode_switch_side_offset' ), 'tablet' ) );
		$css->set_selector( '.kadence-color-palette-fixed-switcher.kcpf-position-left' );
		$css->set_selector( '.kadence-color-palette-fixed-switcher .kadence-color-palette-switcher.kcps-style-switch.kcps-type-icon button.kadence-color-palette-toggle:after' );
		$css->add_property( 'width', 'calc( ' . $css->render_range( kadence()->option( 'dark_mode_icon_size' ), 'tablet' ) . ' + .3em )' );
		$css->add_property( 'height', 'calc( ' . $css->render_range( kadence()->option( 'dark_mode_icon_size' ), 'tablet' ) . ' + .3em )' );
		$css->set_selector( '.kadence-color-palette-fixed-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-icon' );
		$css->add_property( 'font-size', $css->render_range( kadence()->option( 'dark_mode_icon_size' ), 'tablet' ) );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '.kadence-color-palette-fixed-switcher' );
		$css->add_property( 'bottom', $css->render_range( kadence()->option( 'dark_mode_switch_bottom_offset' ), 'mobile' ) );
		$css->set_selector( '.kadence-color-palette-fixed-switcher.kcpf-position-right' );
		$css->add_property( 'right', $css->render_range( kadence()->option( 'dark_mode_switch_side_offset' ), 'mobile' ) );
		$css->set_selector( '.kadence-color-palette-fixed-switcher.kcpf-position-left' );
		$css->add_property( 'left', $css->render_range( kadence()->option( 'dark_mode_switch_side_offset' ), 'mobile' ) );
		$css->set_selector( '.kadence-color-palette-fixed-switcher .kadence-color-palette-switcher.kcps-style-switch.kcps-type-icon button.kadence-color-palette-toggle:after' );
		$css->add_property( 'width', 'calc( ' . $css->render_range( kadence()->option( 'dark_mode_icon_size' ), 'mobile' ) . ' + .3em )' );
		$css->add_property( 'height', 'calc( ' . $css->render_range( kadence()->option( 'dark_mode_icon_size' ), 'mobile' ) . ' + .3em )' );
		$css->set_selector( '.kadence-color-palette-fixed-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-icon' );
		$css->add_property( 'font-size', $css->render_range( kadence()->option( 'dark_mode_icon_size' ), 'mobile' ) );
		$css->stop_media_query();
		// Header Switcher.
		$css->set_selector( '.kadence-color-palette-header-switcher' );
		$css->add_property( '--global-light-toggle-switch', $css->render_color( kadence()->sub_option( 'header_dark_mode_colors', 'light' ) ) );
		$css->add_property( '--global-dark-toggle-switch', $css->render_color( kadence()->sub_option( 'header_dark_mode_colors', 'dark' ) ) );
		$css->set_selector( '.kadence-color-palette-header-switcher .kadence-color-palette-switcher' );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'header_dark_mode_switch_margin' ) ) );
		$css->set_selector( '.kadence-color-palette-header-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-label' );
		$css->render_font( kadence()->option( 'header_dark_mode_typography' ), $css );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '.kadence-color-palette-header-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-label' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'header_dark_mode_typography' ), 'tablet' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'header_dark_mode_typography' ), 'tablet' ) );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '.kadence-color-palette-header-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-label' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'header_dark_mode_typography' ), 'mobile' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'header_dark_mode_typography' ), 'mobile' ) );
		$css->stop_media_query();
		$css->set_selector( '.kadence-color-palette-header-switcher .kadence-color-palette-switcher.kcps-style-switch.kcps-type-icon button.kadence-color-palette-toggle:after' );
		$css->add_property( 'width', 'calc( ' . $css->render_range( kadence()->option( 'header_dark_mode_icon_size' ), 'desktop' ) . ' + .3em )' );
		$css->add_property( 'height', 'calc( ' . $css->render_range( kadence()->option( 'header_dark_mode_icon_size' ), 'desktop' ) . ' + .3em )' );
		$css->set_selector( '.kadence-color-palette-header-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-icon' );
		$css->add_property( 'font-size', $css->render_range( kadence()->option( 'header_dark_mode_icon_size' ), 'desktop' ) );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '.kadence-color-palette-header-switcher .kadence-color-palette-switcher.kcps-style-switch.kcps-type-icon button.kadence-color-palette-toggle:after' );
		$css->add_property( 'width', 'calc( ' . $css->render_range( kadence()->option( 'header_dark_mode_icon_size' ), 'tablet' ) . ' + .3em )' );
		$css->add_property( 'height', 'calc( ' . $css->render_range( kadence()->option( 'header_dark_mode_icon_size' ), 'tablet' ) . ' + .3em )' );
		$css->set_selector( '.kadence-color-palette-header-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-icon' );
		$css->add_property( 'font-size', $css->render_range( kadence()->option( 'header_dark_mode_icon_size' ), 'tablet' ) );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '.kadence-color-palette-header-switcher .kadence-color-palette-switcher.kcps-style-switch.kcps-type-icon button.kadence-color-palette-toggle:after' );
		$css->add_property( 'width', 'calc( ' . $css->render_range( kadence()->option( 'header_dark_mode_icon_size' ), 'mobile' ) . ' + .3em )' );
		$css->add_property( 'height', 'calc( ' . $css->render_range( kadence()->option( 'header_dark_mode_icon_size' ), 'mobile' ) . ' + .3em )' );
		$css->set_selector( '.kadence-color-palette-header-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-icon' );
		$css->add_property( 'font-size', $css->render_range( kadence()->option( 'header_dark_mode_icon_size' ), 'mobile' ) );
		$css->stop_media_query();
		// Mobile Switcher.
		$css->set_selector( '.kadence-color-palette-mobile-switcher' );
		$css->add_property( '--global-light-toggle-switch', $css->render_color( kadence()->sub_option( 'mobile_dark_mode_colors', 'light' ) ) );
		$css->add_property( '--global-dark-toggle-switch', $css->render_color( kadence()->sub_option( 'mobile_dark_mode_colors', 'dark' ) ) );
		$css->set_selector( '.kadence-color-palette-mobile-switcher .kadence-color-palette-switcher' );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'mobile_dark_mode_switch_margin' ) ) );
		$css->set_selector( '.kadence-color-palette-mobile-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-label' );
		$css->render_font( kadence()->option( 'mobile_dark_mode_typography' ), $css );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '.kadence-color-palette-mobile-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-label' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'mobile_dark_mode_typography' ), 'tablet' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'mobile_dark_mode_typography' ), 'tablet' ) );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '.kadence-color-palette-mobile-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-label' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'mobile_dark_mode_typography' ), 'mobile' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'mobile_dark_mode_typography' ), 'mobile' ) );
		$css->stop_media_query();
		$css->set_selector( '.kadence-color-palette-mobile-switcher .kadence-color-palette-switcher.kcps-style-switch.kcps-type-icon button.kadence-color-palette-toggle:after' );
		$css->add_property( 'width', 'calc( ' . $css->render_range( kadence()->option( 'mobile_dark_mode_icon_size' ), 'desktop' ) . ' + .3em )' );
		$css->add_property( 'height', 'calc( ' . $css->render_range( kadence()->option( 'mobile_dark_mode_icon_size' ), 'desktop' ) . ' + .3em )' );
		$css->set_selector( '.kadence-color-palette-mobile-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-icon' );
		$css->add_property( 'font-size', $css->render_range( kadence()->option( 'mobile_dark_mode_icon_size' ), 'desktop' ) );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '.kadence-color-palette-mobile-switcher .kadence-color-palette-switcher.kcps-style-switch.kcps-type-icon button.kadence-color-palette-toggle:after' );
		$css->add_property( 'width', 'calc( ' . $css->render_range( kadence()->option( 'mobile_dark_mode_icon_size' ), 'tablet' ) . ' + .3em )' );
		$css->add_property( 'height', 'calc( ' . $css->render_range( kadence()->option( 'mobile_dark_mode_icon_size' ), 'tablet' ) . ' + .3em )' );
		$css->set_selector( '.kadence-color-palette-mobile-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-icon' );
		$css->add_property( 'font-size', $css->render_range( kadence()->option( 'mobile_dark_mode_icon_size' ), 'tablet' ) );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '.kadence-color-palette-mobile-switcher .kadence-color-palette-switcher.kcps-style-switch.kcps-type-icon button.kadence-color-palette-toggle:after' );
		$css->add_property( 'width', 'calc( ' . $css->render_range( kadence()->option( 'mobile_dark_mode_icon_size' ), 'mobile' ) . ' + .3em )' );
		$css->add_property( 'height', 'calc( ' . $css->render_range( kadence()->option( 'mobile_dark_mode_icon_size' ), 'mobile' ) . ' + .3em )' );
		$css->set_selector( '.kadence-color-palette-mobile-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-icon' );
		$css->add_property( 'font-size', $css->render_range( kadence()->option( 'mobile_dark_mode_icon_size' ), 'mobile' ) );
		$css->stop_media_query();
		// Footer Switcher.
		$css->set_selector( '.kadence-color-palette-footer-switcher' );
		$css->add_property( '--global-light-toggle-switch', $css->render_color( kadence()->sub_option( 'footer_dark_mode_colors', 'light' ) ) );
		$css->add_property( '--global-dark-toggle-switch', $css->render_color( kadence()->sub_option( 'footer_dark_mode_colors', 'dark' ) ) );
		$css->set_selector( '.kadence-color-palette-footer-switcher .kadence-color-palette-switcher' );
		$css->add_property( 'margin', $css->render_measure( kadence()->option( 'footer_dark_mode_switch_margin' ) ) );
		$css->set_selector( '.kadence-color-palette-footer-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-label' );
		$css->render_font( kadence()->option( 'footer_dark_mode_typography' ), $css );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '.kadence-color-palette-footer-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-label' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'footer_dark_mode_typography' ), 'tablet' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'footer_dark_mode_typography' ), 'tablet' ) );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '.kadence-color-palette-footer-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-label' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'footer_dark_mode_typography' ), 'mobile' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'footer_dark_mode_typography' ), 'mobile' ) );
		$css->stop_media_query();
		$css->set_selector( '.kadence-color-palette-footer-switcher .kadence-color-palette-switcher.kcps-style-switch.kcps-type-icon button.kadence-color-palette-toggle:after' );
		$css->add_property( 'width', 'calc( ' . $css->render_range( kadence()->option( 'footer_dark_mode_icon_size' ), 'desktop' ) . ' + .3em )' );
		$css->add_property( 'height', 'calc( ' . $css->render_range( kadence()->option( 'footer_dark_mode_icon_size' ), 'desktop' ) . ' + .3em )' );
		$css->set_selector( '.kadence-color-palette-footer-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-icon' );
		$css->add_property( 'font-size', $css->render_range( kadence()->option( 'footer_dark_mode_icon_size' ), 'desktop' ) );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '.kadence-color-palette-footer-switcher .kadence-color-palette-switcher.kcps-style-switch.kcps-type-icon button.kadence-color-palette-toggle:after' );
		$css->add_property( 'width', 'calc( ' . $css->render_range( kadence()->option( 'footer_dark_mode_icon_size' ), 'tablet' ) . ' + .3em )' );
		$css->add_property( 'height', 'calc( ' . $css->render_range( kadence()->option( 'footer_dark_mode_icon_size' ), 'tablet' ) . ' + .3em )' );
		$css->set_selector( '.kadence-color-palette-footer-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-icon' );
		$css->add_property( 'font-size', $css->render_range( kadence()->option( 'footer_dark_mode_icon_size' ), 'tablet' ) );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '.kadence-color-palette-footer-switcher .kadence-color-palette-switcher.kcps-style-switch.kcps-type-icon button.kadence-color-palette-toggle:after' );
		$css->add_property( 'width', 'calc( ' . $css->render_range( kadence()->option( 'footer_dark_mode_icon_size' ), 'mobile' ) . ' + .3em )' );
		$css->add_property( 'height', 'calc( ' . $css->render_range( kadence()->option( 'footer_dark_mode_icon_size' ), 'mobile' ) . ' + .3em )' );
		$css->set_selector( '.kadence-color-palette-footer-switcher .kadence-color-palette-switcher button.kadence-color-palette-toggle .kadence-color-palette-icon' );
		$css->add_property( 'font-size', $css->render_range( kadence()->option( 'footer_dark_mode_icon_size' ), 'mobile' ) );
		$css->stop_media_query();
		if ( kadence()->option( 'dark_mode_learndash_enable' ) && kadence()->option( 'dark_mode_learndash_lesson_logo' ) ) {
			$css->set_selector( '.color-switch-dark .ld-brand-logo a:not(.brand)' );
			$css->add_property( 'display', 'none' );
			$css->set_selector( '.color-switch-light .ld-brand-logo a.brand' );
			$css->add_property( 'display', 'none' );
		}
		self::$google_fonts = $css->fonts_output();
		$the_css = $css->css_output();
		wp_enqueue_style( 'kadence-dark-mode', KTP_URL . 'dist/dark-mode/dark-mode.css', array(), KTP_VERSION );
		wp_add_inline_style( 'kadence-dark-mode', $the_css );
		wp_print_styles( 'kadence-dark-mode' );
		wp_enqueue_script( 'kadence-dark-mode', KTP_URL . 'dist/dark-mode/dark-mode.min.js', array(), KTP_VERSION, false );
		wp_localize_script(
			'kadence-dark-mode',
			'kadenceDarkModeConfig',
			array(
				'siteSlug' => substr( base_convert( md5( get_site_url() ), 16, 32 ), 0, 12 ),
				'auto' => ( kadence()->option( 'dark_mode_os_aware' ) ? true : false ),
			)
		);
	}
	/**
	 * Add Sections
	 *
	 * @access public
	 * @param array $sections registered sections with kadence theme.
	 * @return array
	 */
	public function add_customizer_sections( $sections ) {
		$sections['dark_mode'] = array(
			'title'    => __( 'Color Switch (Dark Mode)', 'kadence-pro' ),
			'panel'    => 'general',
			'priority' => 25,
		);
		$sections['header_dark_mode'] = array(
			'title'    => __( 'Color Switch (Dark Mode)', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 25,
		);
		$sections['mobile_dark_mode'] = array(
			'title'    => __( 'Color Switch (Dark Mode)', 'kadence-pro' ),
			'panel'    => 'header',
			'priority' => 25,
		);
		$sections['footer_dark_mode'] = array(
			'title'    => __( 'Color Switch (Dark Mode)', 'kadence-pro' ),
			'panel'    => 'footer',
			'priority' => 25,
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
	public function create_pro_settings_array( $wp_customize ) {
		// Load Settings files.
		foreach ( self::$settings_sections as $key ) {
			require_once KTP_PATH . 'dist/dark-mode/' . $key . '-options.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
		if ( class_exists( 'LearnDash_Settings_Section' ) ) {
			$in_focus_mode = \LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Theme_LD30', 'focus_mode_enabled' );
			if ( $in_focus_mode ) {
				require_once KTP_PATH . 'dist/dark-mode/learndash-focus-header-options.php';
			}
		}
	}
	/**
	 * Add Choices
	 *
	 * @access public
	 * @param array $choices registered choices with kadence theme.
	 * @return array
	 */
	public function add_customizer_header_footer_choices( $choices ) {
		$choices['header_desktop_items']['dark-mode'] = array(
			'name'    => esc_html__( 'Dark Mode Toggle', 'kadence-pro' ),
			'section' => 'kadence_customizer_header_dark_mode',
		);
		$choices['header_mobile_items']['mobile-dark-mode'] = array(
			'name'    => esc_html__( 'Dark Mode Toggle', 'kadence-pro' ),
			'section' => 'kadence_customizer_mobile_dark_mode',
		);
		$choices['footer_items']['footer-dark-mode'] = array(
			'name'    => esc_html__( 'Dark Mode Toggle', 'kadence-pro' ),
			'section' => 'kadence_customizer_footer_dark_mode',
		);
		return $choices;
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
	 * Output header template.
	 *
	 * @param string $template_name the name of the template.
	 */
	public function locate_template( $template_name ) {
		$template_path = 'kadence_pro/';
		$default_path  = KTP_PATH . 'dist/dark-mode/templates/';

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
}

Dark_Mode::get_instance();
