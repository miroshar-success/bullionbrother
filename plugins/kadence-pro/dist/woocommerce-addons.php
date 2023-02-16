<?php
/**
 * Class for the Customizer
 *
 * @package Kadence
 */

namespace Kadence_Pro;

use function Kadence\kadence;
use Kadence\Kadence_CSS;
use Kadence\Theme;
use WC_AJAX;
use function __return_true;
use Kadence_Blocks_Frontend;
use WC;
use wc_add_to_cart_message;
use wc_get_product;
use wc_stock_amount;
use get_post_status;

/**
 * Main plugin class
 */
class Woocommerce_Addons {
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
		'product-archive',
		'cart-show',
		'cart-notice',
		'single-product',
				// 'quick-view',
		// 'archive-filter',
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
		add_filter( 'kadence_theme_customizer_sections', array( $this, 'add_customizer_sections' ), 10 );
		add_action( 'customize_register', array( $this, 'create_pro_settings_array' ), 1 );
		add_action( 'after_setup_theme', array( $this, 'load_actions' ), 20 );
		add_filter( 'template_include', array( $this, 'archive_template_loader' ), 40 );
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'action_single_product_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'action_single_product_sticky_enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'trigger_add_to_cart_on_load' ) );
		add_filter( 'kadence_product_archive_show_top_row', array( $this, 'check_to_show_product_top_row' ) );
		add_action( 'widgets_init', array( $this, 'action_register_sidebars' ) );
		add_filter( 'customizer_widgets_section_args', array( $this, 'customizer_custom_widget_areas' ), 10, 3 );
		add_filter( 'kadence_dynamic_css', array( $this, 'dynamic_css' ), 20 );
		add_action( 'wp_head', array( $this, 'frontend_gfonts' ), 80 );
		add_action( 'wp_ajax_nopriv_kadence_pro_add_to_cart', array( $this, 'add_to_cart' ) );
		add_action( 'wp_ajax_kadence_pro_add_to_cart', array( $this, 'add_to_cart' ) );
		add_action( 'woocommerce_before_mini_cart', array( $this, 'add_mini_cart_notice' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_mini_cart_scripts' ) );
		// Add Fragment Support when running ajax.
		add_action( 'kadence_single_product_ajax_added_to_cart', array( $this, 'kadence_check_for_fragment_support' ) );
	}
	/**
	 * Add mini cart notice.
	 */
	public function kadence_check_for_fragment_support() {
		$kadence_theme_class = Theme::instance();
		if ( isset( $kadence_theme_class->components['woocommerce'] ) && method_exists( $kadence_theme_class->components['woocommerce'], 'check_for_fragment_support' ) ) {
			$kadence_theme_class->components['woocommerce']->check_for_fragment_support();
		}
	}
	/**
	 * Add mini cart notice.
	 */
	public function add_mini_cart_notice() {
		if ( kadence()->option( 'cart_pop_show_free_shipping' ) ) {
			$min_amount = kadence()->option( 'cart_pop_free_shipping_price' );
			$current = WC()->cart->subtotal;
			$output = '<div class="kadence-mini-cart-shipping">';
			if ( $current && $current < $min_amount ) {
				$message = kadence()->option( 'cart_pop_free_shipping_message' );
				$message = str_replace( '{cart_difference}', wc_price( $min_amount - $current ), $message );
				$output .= '<span class="kadence-mini-cart-shipping-message">';
				$output .= $message;
				$output .= '</span>';
				$output .= '<span class="kadence-mini-cart-shipping-progress-wrap"><span class="kadence-mini-cart-shipping-progress" style="width:' . esc_attr( ( $current / $min_amount ) * 100 ) . '%">';
				$output .= '</span></span>';
			}
			$output .= '</div>';
			echo $output;
		}
	}
	/**
	 * AJAX add to cart.
	 */
	public static function add_to_cart() {
		ob_start();

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( ! isset( $_POST['product_id'] ) ) {
			return;
		}

		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
		$product           = wc_get_product( $product_id );
		$quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		$product_status    = get_post_status( $product_id );
		$variation_id      = 0;
		$variation         = array();

		if ( $product && 'variation' === $product->get_type() ) {
			$variation_id = $product_id;
			$product_id   = $product->get_parent_id();
			$variation    = $product->get_variation_attributes();
			foreach ( $variation as $key => $value ) {
				if ( empty( $value ) ) {
					$variation[ $key ] = ( isset( $_POST[ $key ] ) && ! empty( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '' );
				}
			}
		}

		if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) && 'publish' === $product_status ) {

			do_action( 'woocommerce_ajax_added_to_cart', $product_id );
			do_action( 'kadence_single_product_ajax_added_to_cart', $product_id );

			// if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
			// 	wc_add_to_cart_message( array( $product_id => $quantity ), true );
			// }

			WC_AJAX::get_refreshed_fragments();

		} else {

			// If there was an error adding to the cart, redirect to the product page to show any errors.
			$data = array(
				'error'       => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
			);

			wp_send_json( $data );
		}
		// phpcs:enable
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
		// Widget toggle.
		$css->set_selector( '#filter-drawer.popup-drawer-layout-fullwidth .drawer-content .product-filter-widgets, #filter-drawer.popup-drawer-layout-sidepanel .drawer-inner' );
		$css->add_property( 'max-width', $css->render_size( kadence()->option( 'product_filter_widget_pop_width' ) ) );
		$css->set_selector( '#filter-drawer.popup-drawer-layout-fullwidth .drawer-content .product-filter-widgets' );
		$css->add_property( 'margin', '0 auto' );
		$css->set_selector( '.filter-toggle-open-container' );
		$css->add_property( 'margin-right', '0.5em' );
		$css->set_selector( '.filter-toggle-open >*:first-child:not(:last-child)' );
		$css->add_property( 'margin-right', '4px' );
		$css->set_selector( '.filter-toggle-open' );
		$css->add_property( 'color', 'inherit' );
		$css->add_property( 'display', 'flex' );
		$css->add_property( 'align-items', 'center' );
		$css->add_property( 'background', 'transparent' );
		$css->add_property( 'box-shadow', 'none' );
		$css->add_property( 'border-radius', '0px' );
		$css->set_selector( '.filter-toggle-open.filter-toggle-style-default' );
		$css->add_property( 'border', '0px' );
		$css->set_selector( '.filter-toggle-open:hover, .filter-toggle-open:focus' );
		$css->add_property( 'border-color', 'currentColor' );
		$css->add_property( 'background', 'transparent' );
		$css->add_property( 'color', 'inherit' );
		$css->add_property( 'box-shadow', 'none' );
		$css->set_selector( '.filter-toggle-open .filter-toggle-icon' );
		$css->add_property( 'display', 'flex' );
		$css->set_selector( '.filter-toggle-open >*:first-child:not(:last-child):empty' );
		$css->add_property( 'margin-right', '0px' );
		$css->set_selector( '.filter-toggle-open-container .filter-toggle-open' );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'product_archive_shop_filter_background', 'color' ) ) );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'product_archive_shop_filter_color', 'color' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'product_archive_shop_filter_border_color', 'color' ) ) );
		$css->add_property( 'padding', $css->render_measure( kadence()->option( 'product_archive_shop_filter_padding' ) ) );
		$css->render_font( kadence()->option( 'product_archive_shop_filter_typography' ), $css );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '.filter-toggle-open-container .filter-toggle-open' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'product_archive_shop_filter_typography' ), 'tablet' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'product_archive_shop_filter_typography' ), 'tablet' ) );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '.filter-toggle-open-container .filter-toggle-open' );
		$css->add_property( 'font-size', $css->render_font_size( kadence()->option( 'product_archive_shop_filter_typography' ), 'mobile' ) );
		$css->add_property( 'line-height', $css->render_font_height( kadence()->option( 'product_archive_shop_filter_typography' ), 'mobile' ) );
		$css->stop_media_query();
		$css->set_selector( '.filter-toggle-open-container .filter-toggle-open.filter-toggle-style-bordered' );
		$css->add_property( 'border', $css->render_border( kadence()->option( 'product_archive_shop_filter_border' ) ) );
		$css->set_selector( '.filter-toggle-open-container .filter-toggle-open .filter-toggle-icon' );
		$css->add_property( 'font-size', kadence()->sub_option( 'product_archive_shop_filter_icon_size', 'size' ) . kadence()->sub_option( 'product_archive_shop_filter_icon_size', 'unit' ) );
		$css->set_selector( '.filter-toggle-open-container .filter-toggle-open:hover, .filter-toggle-open-container .filter-toggle-open:focus' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'product_archive_shop_filter_color', 'hover' ) ) );
		$css->add_property( 'background', $css->render_color( kadence()->sub_option( 'product_archive_shop_filter_background', 'hover' ) ) );
		$css->add_property( 'border-color', $css->render_color( kadence()->sub_option( 'product_archive_shop_filter_border_color', 'hover' ) ) );

		$css->set_selector( '#filter-drawer .drawer-inner' );
		$css->render_background( kadence()->sub_option( 'product_filter_widget_pop_background', 'desktop' ), $css );
		$css->start_media_query( $media_query['tablet'] );
		$css->set_selector( '#filter-drawer .drawer-inner' );
		$css->render_background( kadence()->sub_option( 'product_filter_widget_pop_background', 'tablet' ), $css );
		$css->stop_media_query();
		$css->start_media_query( $media_query['mobile'] );
		$css->set_selector( '#filter-drawer .drawer-inner' );
		$css->render_background( kadence()->sub_option( 'product_filter_widget_pop_background', 'mobile' ), $css );
		$css->stop_media_query();
		$css->set_selector( '#filter-drawer .drawer-header .drawer-toggle, #filter-drawer .drawer-header .drawer-toggle:focus' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'product_filter_widget_close_color', 'color' ) ) );
		$css->set_selector( '#filter-drawer .drawer-header .drawer-toggle:hover, #filter-drawer .drawer-header .drawer-toggle:focus:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'product_filter_widget_close_color', 'hover' ) ) );
		// Toggle Widget area.
		$css->set_selector( '#filter-drawer .header-filter-2style-normal a:not(.button)' );
		$css->add_property( 'text-decoration', 'underline' );
		$css->set_selector( '#filter-drawer .header-filter-2style-plain a:not(.button)' );
		$css->add_property( 'text-decoration', 'none' );
		$css->set_selector( '#filter-drawer .drawer-inner .product-filter-widgets .widget-title' );
		$css->render_font( kadence()->option( 'product_filter_widget_title' ), $css );
		$css->set_selector( '#filter-drawer .drawer-inner .product-filter-widgets' );
		$css->render_font( kadence()->option( 'product_filter_widget_content' ), $css );
		$css->set_selector( '#filter-drawer .drawer-inner .product-filter-widgets a, #filter-drawer .drawer-inner .product-filter-widgets .drawer-sub-toggle' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'product_filter_widget_link_colors', 'color' ) ) );
		$css->set_selector( '#filter-drawer .drawer-inner .product-filter-widgets a:hover, #filter-drawer .drawer-inner .product-filter-widgets .drawer-sub-toggle:hover' );
		$css->add_property( 'color', $css->render_color( kadence()->sub_option( 'product_filter_widget_link_colors', 'hover' ) ) );
		$css->set_selector( '#filter-drawer .drawer-inner .product-filter-widgets' );
		$css->add_property( 'padding', $css->render_measure( kadence()->option( 'product_filter_widget_padding' ) ) );

		$css->set_selector( '.kadence-shop-active-filters' );
		$css->add_property( 'display', 'flex' );
		$css->add_property( 'flex-wrap', 'wrap' );
		$css->set_selector( '.kadence-clear-filters-container a' );
		$css->add_property( 'text-decoration', 'none' );
		$css->add_property( 'background', 'var(--global-palette7)' );
		$css->add_property( 'color', 'var(--global-palette5)' );
		$css->add_property( 'padding', '.6em' );
		$css->add_property( 'font-size', '80%' );
		$css->add_property( 'transition', 'all 0.3s ease-in-out' );

		$css->set_selector( '.kadence-clear-filters-container ul' );
		$css->add_property( 'margin', '0px' );
		$css->add_property( 'padding', '0px' );
		$css->add_property( 'border', '0px' );
		$css->add_property( 'list-style', 'none outside' );
		$css->add_property( 'overflow', 'hidden' );
		$css->add_property( 'zoom', '1' );

		$css->set_selector( '.kadence-clear-filters-container ul li' );
		$css->add_property( 'float', 'left' );
		$css->add_property( 'padding', '0 0 1px 1px' );
		$css->add_property( 'list-style', 'none' );
		$css->set_selector( '.kadence-clear-filters-container a:hover' );
		$css->add_property( 'background', 'var(--global-palette9)' );
		$css->add_property( 'color', 'var(--global-palette3)' );

		self::$google_fonts = $css->fonts_output();
		return $css->css_output();
	}
	/**
	 * Registers the sidebars.
	 */
	public function action_register_sidebars() {
		$widgets = array(
			'product-filter' => __( 'Catalog Off Canvas Sidebar', 'kadence-pro' ),
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
	 * Checks if popout is enabled and if so makes sure the action is running.
	 *
	 * @param bool $show whether or not the topbar is being forced to show.
	 */
	public function check_to_show_product_top_row( $show ) {
		if ( kadence()->option( 'product_archive_shop_filter_popout' ) ) {
			return true;
		}
		return $show;
	}
	/**
	 * Adds the snippet of js to trigger the cart open on add to cart.
	 */
	public function trigger_add_to_cart_on_load() {
		if ( is_admin() ) {
			return;
		}
		$open_cart = false;
		if ( ! is_cart() && ! is_checkout() && ( isset( $_POST['add-to-cart'] ) || isset( $_GET['add-to-cart'] ) ) ) {
			$open_cart = true;
		}
		wp_enqueue_script( 'kadence-pro-woocommerce' );
		wp_localize_script(
			'kadence-pro-woocommerce',
			'kadenceProWooConfig',
			array(
				'openCart' => $open_cart,
			)
		);
	}
	/**
	 * Enqueues a script that improves navigation menu accessibility as well as sticky header etc.
	 */
	public function action_enqueue_scripts() {

		// If the AMP plugin is active, return early.
		if ( kadence()->is_amp() ) {
			return;
		}
		// If not enabled then bail.
		if ( ! kadence()->option( 'cart_pop_show_on_add' ) ) {
			return;
		}

		// Enqueue the pro-woocommerce script.
		wp_register_script(
			'kadence-pro-woocommerce',
			KTP_URL . 'dist/woocommerce-addons/pro-woocommerce.min.js',
			array( 'jquery' ),
			KTP_VERSION,
			true
		);
		wp_script_add_data( 'kadence-pro-woocommerce', 'async', true );
		wp_script_add_data( 'kadence-pro-woocommerce', 'precache', true );

	}
	/**
	 * Enqueues a script that adds sticky for single products
	 */
	public function action_single_product_sticky_enqueue_scripts() {

		// If the AMP plugin is active, return early.
		if ( kadence()->is_amp() ) {
			return;
		}
		// If not enabled then bail.
		if ( ! kadence()->option( 'product_sticky_add_to_cart' ) ) {
			return;
		}
		if ( function_exists( 'is_product' ) && is_product() ) {
			// Enqueue the sticky script.
			wp_enqueue_style( 'kadence-sticky-add-to-cart', KTP_URL . 'dist/woocommerce-addons/kadence-sticky-add-to-cart.css', array(), KTP_VERSION );
			wp_enqueue_script(
				'kadence-sticky-add-to-cart',
				KTP_URL . 'dist/woocommerce-addons/kadence-sticky-add-to-cart.min.js',
				array(),
				KTP_VERSION,
				true
			);
		}

	}
	/**
	 * Enqueues css for free shipping notice
	 */
	public function action_enqueue_mini_cart_scripts() {

		// If the AMP plugin is active, return early.
		if ( kadence()->is_amp() ) {
			return;
		}
		// If not enabled then bail.
		if ( ! kadence()->option( 'cart_pop_show_free_shipping' ) ) {
			return;
		}
		wp_enqueue_style( 'kadence-min-cart-shipping-notice', KTP_URL . 'dist/woocommerce-addons/mini-cart-notice.css', array(), KTP_VERSION );
	}
	/**
	 * Enqueues a script that adds ajax for single products
	 */
	public function action_single_product_enqueue_scripts() {

		// If the AMP plugin is active, return early.
		if ( kadence()->is_amp() ) {
			return;
		}
		// If not enabled then bail.
		if ( ! kadence()->option( 'ajax_add_single_products' ) ) {
			return;
		}
		if ( function_exists( 'is_product' ) && is_product() ) {
			global $post;
			$product = wc_get_product( $post->ID );
			if ( ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) || $product->is_type( 'subscription' ) || $product->is_type( 'variable' ) || $product->is_type( 'variable-subscription' ) ) && apply_filters( 'kadence_enable_single_ajax_add_to_cart', true, $product ) ) {
				// Enqueue the ajax-add script.
				wp_enqueue_script(
					'kadence-single-ajax-add',
					KTP_URL . 'dist/woocommerce-addons/single-ajax-add-to-cart.min.js',
					array( 'jquery' ),
					KTP_VERSION,
					true
				);
			}
		}

	}
	/**
	 * Get woocommerce hooks template.
	 */
	public function load_actions() {
		require_once KTP_PATH . 'dist/woocommerce-addons/hooks.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
	/**
	 * Return woocommerce template.
	 *
	 * @param string $template_name the name of the template.
	 * @return string the template.
	 */
	public function get_woocommerce_template( $template_name ) {
		$template_path = 'kadence_pro/';
		$default_path  = KTP_PATH . 'dist/woocommerce-addons/templates/';

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

		return $template;
	}
	/**
	 * Output woocommerce template.
	 *
	 * @param string $template_name the name of the template.
	 */
	public function locate_woocommerce_template( $template_name ) {
		include get_woocommerce_template( $template_name );
	}
	/**
	 * Add Defaults
	 *
	 * @access public
	 * @param array $defaults registered option defaults with kadence theme.
	 * @return array
	 */
	public function add_option_defaults( $defaults ) {
		$woo_addons = array(
			'product_sticky_add_to_cart' => false,
			'product_sticky_add_to_cart_placement' => 'header',
			'product_sticky_mobile_add_to_cart' => false,
			'product_sticky_mobile_add_to_cart_placement' => 'footer',
			'product_archive_shop_custom' => false,
			'cart_pop_show_free_shipping' => false,
			'cart_pop_free_shipping_price' => 100,
			'cart_pop_free_shipping_message' => 'You\'re {cart_difference} away from free shipping',
			'cart_pop_show_on_add' => false,
			'ajax_add_single_products' => false,
			// Widget Toggle.
			'product_archive_shop_filter_popout'     => false,
			'product_archive_shop_filter_active_top' => false,
			'product_archive_shop_filter_active_remove_all' => true,
			'product_archive_shop_filter_label'  => __( 'Filter', 'kadence-pro' ),
			'product_archive_shop_filter_icon'   => 'listFilterAlt',
			'product_archive_shop_filter_style'  => 'bordered',
			'product_archive_shop_filter_border' => array(
				'width' => 1,
				'unit'  => 'px',
				'style' => 'solid',
				'color' => 'currentColor',
			),
			'product_archive_shop_filter_icon_size'   => array(
				'size' => 20,
				'unit' => 'px',
			),
			'product_archive_shop_filter_color'              => array(
				'color' => 'palette5',
				'hover' => 'palette-highlight',
			),
			'product_archive_shop_filter_background'              => array(
				'color' => '',
				'hover' => '',
			),
			'product_archive_shop_filter_border_color'              => array(
				'color' => '',
				'hover' => '',
			),
			'product_archive_shop_filter_typography'            => array(
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
			'product_archive_shop_filter_padding' => array(
				'size'   => array( 3, 5, 3, 5 ),
				'unit'   => 'px',
				'locked' => false,
			),
			'product_filter_widget_side'       => 'left',
			'product_filter_widget_layout'     => 'sidepanel',
			'product_filter_widget_pop_width'  => array(
				'size' => 400,
				'unit' => 'px',
			),
			'product_filter_widget_pop_background' => array(
				'desktop' => array(
					'color' => 'palette9',
				),
			),
			'product_filter_widget_close_color'  => array(
				'color' => 'palette5',
				'hover' => 'palette3',
			),
			// Header toggle Widget Area.
			'product_filter_widget_link_colors'       => array(
				'color'  => 'palette1',
				'hover'  => 'palette2',
			),
			'product_filter_widget_title'        => array(
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
				'color'   => 'palette3',
			),
			'product_filter_widget_content'        => array(
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
				'color'   => 'palette4',
			),
			'product_filter_widget_link_style' => 'plain',
			'product_filter_widget_padding' => array(
				'size'   => array( '', '', '', '' ),
				'unit'   => 'px',
				'locked' => false,
			),
		);
		$defaults = array_merge(
			$defaults,
			$woo_addons
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
		$sections['cart_behavior'] = array(
			'title'    => __( 'Add to Cart Behavior', 'kadence-pro' ),
			'panel'    => 'woocommerce',
			'priority' => 24,
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
			require_once KTP_PATH . 'dist/woocommerce-addons/' . $key . '-options.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}
	/**
	 * Add custom shop page template.
	 *
	 * @param string $template the template to load.
	 * @return string
	 */
	public function archive_template_loader( $template ) {
		if ( is_embed() ) {
			return $template;
		}
		if ( ! is_shop() || is_search() ) {
			return $template;
		}
		if ( kadence()->option( 'product_archive_shop_custom' ) ) {
			$template = $this->get_woocommerce_template( 'archive-product.php' );
		}
		return $template;
	}

	/**
	 * Filter header widget areas.
	 *
	 * @param array  $section_args the widget sections args.
	 * @param string $section_id the widget sections id.
	 * @param string $sidebar_id the widget area id.
	 */
	public function customizer_custom_widget_areas( $section_args, $section_id, $sidebar_id ) {
		if ( 'product-filter' === $sidebar_id ) {
			$section_args['panel']    = 'woocommerce';
			$section_args['priority'] = 18;
		}
		return $section_args;
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
}

Woocommerce_Addons::get_instance();
