<?php
/**
 * Class for the Customizer
 *
 * @package Kadence
 */

namespace Kadence_Pro;

use function Kadence\kadence;
use function wp_enqueue_script;
use Kadence\Kadence_CSS;

/**
 * Main plugin class
 */
class Mega_Menu {
	/**
	 * Instance Control
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * All the css.
	 *
	 * @var null
	 */
	public static $css = '';

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
		// Add custom fields to menu item editor.
		add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'render_field_control_containers' ), 10, 5 );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'wp_enqueue_scripts',  array( $this, 'enqueue_scripts' ) );
		//add_action( 'admin_footer', array( $this, 'render_modal_container' ) );
		//add_action( 'init', array( $this, 'register_meta' ), 20 );
		add_action( 'wp_ajax_kadence_get_menu_item_data', array( $this, 'get_item_data_ajax_callback' ) );
		add_action( 'wp_ajax_kadence_save_menu_item_data', array( $this, 'save_item_data_ajax_callback' ) );
		add_filter( 'nav_menu_link_attributes', array( $this, 'apply_menu_link_changes' ), 9, 4 );
		add_filter( 'nav_menu_item_title', array( $this, 'apply_menu_title_changes' ), 9, 4 );
		add_filter( 'nav_menu_css_class', array( $this, 'apply_menu_class_changes' ), 9, 4 );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'apply_menu_item_replace_changes' ), 9, 4 );
		add_filter( 'wp_footer', array( $this, 'output_css' ), 1 );
		add_filter( 'widget_nav_menu_args', array( $this, 'frontend_settings' ), 10, 4 );
	}
	/**
	 * Filters the arguments for the Navigation Menu widget.
	 *
	 * @since 4.2.0
	 * @since 4.4.0 Added the `$instance` parameter.
	 *
	 * @param array   $nav_menu_args {
	 *     An array of arguments passed to wp_nav_menu() to retrieve a navigation menu.
	 *
	 *     @type callable|bool $fallback_cb Callback to fire if the menu doesn't exist. Default empty.
	 *     @type mixed         $menu        Menu ID, slug, or name.
	 * }
	 * @param WP_Term $nav_menu      Nav menu object for the current menu.
	 * @param array   $args          Display arguments for the current widget.
	 * @param array   $instance      Array of settings for the current widget.
	 */
	public function frontend_settings( $nav_menu_args, $nav_menu, $args, $instance ) {
		if ( apply_filters( 'kadence_ultimate_menu_addon_widget_nav', true ) ) {
			$nav_menu_args['addon_support']   = true;
		}
		return $nav_menu_args;
	}
	/**
	 * Replace the whole item with an element.
	 *
	 * @param string   $item_output The menu item's starting HTML output.
	 * @param WP_Post  $item        Menu item data object.
	 * @param int      $depth       Depth of menu item. Used for padding.
	 * @param stdClass $args        An object of wp_nav_menu() arguments.
	 */
	public function apply_menu_item_replace_changes( $item_output, $item, $depth, $args ) {
		if ( 1 !== $depth ) {
			return $item_output;
		}
		if ( ! isset( $args->mega_support ) ) {
			return $item_output;
		}
		if ( ! $args->mega_support ) {
			return $item_output;
		}
		$data = array();
		$data['menu_item_custom'] = get_post_meta( $item->ID, '_kad_menu_item_custom', true );
		$data['menu_item_custom_element'] = json_decode( get_post_meta( $item->ID, '_kad_menu_item_custom_element', true ), true );
		if ( ! empty( $data['menu_item_custom'] ) && $data['menu_item_custom'] && ! empty( $data['menu_item_custom_element'] ) && is_array( $data['menu_item_custom_element'] ) && isset( $data['menu_item_custom_element']['value'] ) ) {
			ob_start();
			echo do_shortcode( '[kadence_element id="'. $data['menu_item_custom_element']['value'] . '"]' );
			$item_output = ob_get_clean();
		} else {
			$data['menu_item_custom_divider'] = json_decode( get_post_meta( $item->ID, '_kad_menu_item_custom_divider', true ), true );
			if ( isset( $data['menu_item_custom_divider'] ) && is_array( $data['menu_item_custom_divider'] ) && isset( $data['menu_item_custom_divider']['width'] ) ) {
				$css = new Kadence_CSS();
				if ( ! isset( $data['menu_item_custom_divider']['style'] ) || empty( $data['menu_item_custom_divider']['style'] ) ) {
					$data['menu_item_custom_divider']['style'] = 'solid';
				}
				if ( ! isset( $data['menu_item_custom_divider']['unit'] ) || empty( $data['menu_item_custom_divider']['unit'] ) ) {
					$data['menu_item_custom_divider']['unit'] = 'px';
				}
				$css->set_selector( '.header-navigation .header-menu-container .kadence-menu-mega-enabled > .sub-menu > li#menu-item-' . $item->ID . ' > a' );
				$css->add_property( 'border-bottom', $css->render_border( $data['menu_item_custom_divider'] ) );
				self::$css .= $css->css_output();
			}
		}
		return $item_output;
	}
	/**
	 * Outputs generated css.
	 */
	public function output_css() {
		if ( ! empty( self::$css ) ) {
			wp_register_style( 'kadence_mega_menu_inline', false );
			wp_enqueue_style( 'kadence_mega_menu_inline' );
			wp_add_inline_style( 'kadence_mega_menu_inline', self::$css );
			wp_print_styles( 'kadence_mega_menu_inline' );
		}
	}
	/**
	 * Checks if color should be variable.
	 *
	 * @param  string $color the color string, check for palette.
	 * @return string
	 */
	public function render_color( $color ) {
		if ( strpos( $color, 'palette' ) === 0 ) {
			$color = 'var(--global-' . $color . ')';
		}
		return $color;
	}
	/**
	 * Filter the CSS classes applied to a menu item's list item element.
	 *
	 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
	 * @param WP_Post  $item    The current menu item.
	 * @param stdClass $args    An object of wp_nav_menu() arguments.
	 * @param int      $depth   Depth of menu item. Used for padding.
	 */
	public function apply_menu_class_changes( $classes, $item, $args, $depth ) {
		if ( ! isset( $args->addon_support ) ) {
			return $classes;
		}
		if ( ! $args->addon_support ) {
			return $classes;
		}
		$data = array();
		$data['menu_label']       = get_post_meta( $item->ID, '_kad_menu_label', true );
		$data['menu_description'] = get_post_meta( $item->ID, '_kad_menu_description', true );
		$data['menu_icon_svg']    = get_post_meta( $item->ID, '_kad_menu_icon_svg', true );
		$data['menu_icon_side']   = get_post_meta( $item->ID, '_kad_menu_icon_side', true );
		if ( isset( $data['menu_label'] ) && $data['menu_label'] ) {
			$classes[] = 'kadence-menu-hidden-title';
		}
		if ( isset( $data['menu_description'] ) && $data['menu_description'] ) {
			if ( $item->description ) {
				$classes[] = 'kadence-menu-has-description';
			}
		}
		if ( isset( $data['menu_icon_svg'] ) && ! empty( $data['menu_icon_svg'] ) ) {
			$classes[] = 'kadence-menu-has-icon';
			if ( isset( $data['menu_icon_side'] ) && ! empty( $data['menu_icon_side'] ) ) {
				$classes[] = 'kadence-menu-icon-side-' . $data['menu_icon_side'];
			}
		}
		if ( 0 !== $depth ) {
			return $classes;
		}
		if ( ! isset( $args->mega_support ) ) {
			return $classes;
		}
		if ( ! $args->mega_support ) {
			return $classes;
		}
		$data['mega_menu']     = get_post_meta( $item->ID, '_kad_mega_menu', true );
		if ( $data['mega_menu'] ) {
			wp_enqueue_script( 'kadence-mega-menu' );
			$data['mega_menu_width']  = get_post_meta( $item->ID, '_kad_mega_menu_width', true );
			$data['mega_menu_columns'] = get_post_meta( $item->ID, '_kad_mega_menu_columns', true );
			$data['mega_menu_layout'] = get_post_meta( $item->ID, '_kad_mega_menu_layout', true );
			$classes[] = 'kadence-menu-mega-enabled';
			$classes[] = ( $data['mega_menu_width'] ? 'kadence-menu-mega-width-' . $data['mega_menu_width'] : 'kadence-menu-mega-width-content' );
			$classes[] = ( $data['mega_menu_columns'] ? 'kadence-menu-mega-columns-' . $data['mega_menu_columns'] : 'kadence-menu-mega-columns-3' );
			$classes[] = ( $data['mega_menu_layout'] ? 'kadence-menu-mega-layout-' . $data['mega_menu_layout'] : 'kadence-menu-mega-layout-equal' );

			$css = new Kadence_CSS();
			if ( isset( $data['mega_menu_width'] ) && 'custom' === $data['mega_menu_width'] ) {
				$data['mega_menu_custom_width'] = get_post_meta( $item->ID, '_kad_mega_menu_custom_width', true );
				$css->set_selector( '#menu-item-' . $item->ID . '.kadence-menu-mega-enabled > .sub-menu' );
				$css->add_property( 'width', ( $data['mega_menu_custom_width'] ? $data['mega_menu_custom_width'] : '400' ) . 'px' );
				$css->set_selector( '.header-navigation[class*="header-navigation-dropdown-animation-fade"] #menu-item-' . $item->ID . '.kadence-menu-mega-enabled > .sub-menu' );
				$css->add_property( 'margin-left', '-' . ( $data['mega_menu_custom_width'] ? floor( $data['mega_menu_custom_width'] / 2 ) : '400' ) . 'px' );
			}
			$data['menu_dropdown_background'] = json_decode( get_post_meta( $item->ID, '_kad_menu_dropdown_background', true ), true );
			if ( is_array( $data['menu_dropdown_background'] ) ) {
				$css->set_selector( '#menu-item-' . $item->ID . '.kadence-menu-mega-enabled > .sub-menu' );
				if ( isset( $data['menu_dropdown_background']['color'] ) && ! empty( $data['menu_dropdown_background']['color'] ) ) {
					$css->add_property( 'background-color', $this->render_color( $data['menu_dropdown_background']['color'] ) );
				}
				if ( isset( $data['menu_dropdown_background']['url'] ) && ! empty( $data['menu_dropdown_background']['url'] ) ) {
					$css->add_property( 'background-image', $data['menu_dropdown_background']['url'] );
					if ( isset( $data['menu_dropdown_background']['position'] ) && ! empty( $data['menu_dropdown_background']['position'] ) ) {
						$css->add_property( 'background-position', $data['menu_dropdown_background']['position'] );
					}
					if ( isset( $data['menu_dropdown_background']['size'] ) && ! empty( $data['menu_dropdown_background']['size'] ) ) {
						$css->add_property( 'background-size', $data['menu_dropdown_background']['size'] );
					}
					if ( isset( $data['menu_dropdown_background']['repeat'] ) && ! empty( $data['menu_dropdown_background']['repeat'] ) ) {
						$css->add_property( 'background-repeat', $data['menu_dropdown_background']['repeat'] );
					}
				}
			}
			$data['mega_menu_padding'] = json_decode( get_post_meta( $item->ID, '_kad_mega_menu_padding', true ), true );
			if ( is_array( $data['mega_menu_padding'] ) ) {
				if ( isset( $data['mega_menu_padding']['size'] ) && ! empty( $data['mega_menu_padding']['size'] ) ) {
					$css->set_selector( '#menu-item-' . $item->ID . '.kadence-menu-mega-enabled > .sub-menu' );
					$unit = ( ! empty( $data['mega_menu_padding']['unit'] ) ? $data['mega_menu_padding']['unit'] : 'px' );
					if ( ! empty( $data['mega_menu_padding']['size'][0] ) ) {
						$css->add_property( 'padding-top', $data['mega_menu_padding']['size'][0] . $unit );
					}
					if ( ! empty( $data['mega_menu_padding']['size'][1] ) ) {
						$css->add_property( 'padding-right', $data['mega_menu_padding']['size'][1] . $unit );
					}
					if ( ! empty( $data['mega_menu_padding']['size'][2] ) ) {
						$css->add_property( 'padding-bottom', $data['mega_menu_padding']['size'][2] . $unit );
					}
					if ( ! empty( $data['mega_menu_padding']['size'][3] ) ) {
						$css->add_property( 'padding-left', $data['mega_menu_padding']['size'][3] . $unit );
					}
				}
			}

			$data['menu_dropdown_item_color'] = json_decode( get_post_meta( $item->ID, '_kad_menu_dropdown_item_color', true ), true );
			if ( is_array( $data['menu_dropdown_item_color'] ) ) {
				if ( isset( $data['menu_dropdown_item_color']['color'] ) && ! empty( $data['menu_dropdown_item_color']['color'] ) ) {
					$css->set_selector( '.header-navigation .header-menu-container #menu-item-' . $item->ID . '.kadence-menu-mega-enabled > .sub-menu li.menu-item > a' );
					$css->add_property( 'color', $this->render_color( $data['menu_dropdown_item_color']['color'] ) );
				}
				if ( isset( $data['menu_dropdown_item_color']['hover'] ) && ! empty( $data['menu_dropdown_item_color']['hover'] ) ) {
					$css->set_selector( '.header-navigation .header-menu-container #menu-item-' . $item->ID . '.kadence-menu-mega-enabled > .sub-menu li.menu-item > a:hover' );
					$css->add_property( 'color', $this->render_color( $data['menu_dropdown_item_color']['hover'] ) );
				}
				if ( isset( $data['menu_dropdown_item_color']['active'] ) && ! empty( $data['menu_dropdown_item_color']['active'] ) ) {
					$css->set_selector( '.header-navigation .header-menu-container #menu-item-' . $item->ID . '.kadence-menu-mega-enabled > .sub-menu li.menu-item.current-menu-item > a' );
					$css->add_property( 'color', $this->render_color( $data['menu_dropdown_item_color']['active'] ) );
				}
			}
			$data['menu_dropdown_item_background'] = json_decode( get_post_meta( $item->ID, '_kad_menu_dropdown_item_background', true ), true );
			if ( is_array( $data['menu_dropdown_item_background'] ) ) {
				if ( isset( $data['menu_dropdown_item_background']['color'] ) && ! empty( $data['menu_dropdown_item_background']['color'] ) ) {
					$css->set_selector( '.header-navigation .header-menu-container #menu-item-' . $item->ID . '.kadence-menu-mega-enabled > .sub-menu li.menu-item > a' );
					$css->add_property( 'background', $this->render_color( $data['menu_dropdown_item_background']['color'] ) );
				}
				if ( isset( $data['menu_dropdown_item_background']['hover'] ) && ! empty( $data['menu_dropdown_item_background']['hover'] ) ) {
					$css->set_selector( '.header-navigation .header-menu-container #menu-item-' . $item->ID . '.kadence-menu-mega-enabled > .sub-menu li.menu-item > a:hover' );
					$css->add_property( 'background', $this->render_color( $data['menu_dropdown_item_background']['hover'] ) );
				}
				if ( isset( $data['menu_dropdown_item_background']['active'] ) && ! empty( $data['menu_dropdown_item_background']['active'] ) ) {
					$css->set_selector( '.header-navigation .header-menu-container #menu-item-' . $item->ID . '.kadence-menu-mega-enabled > .sub-menu li.menu-item.current-menu-item > a' );
					$css->add_property( 'background', $this->render_color( $data['menu_dropdown_item_background']['active'] ) );
				}
			}
			$data['mega_menu_item_padding'] = json_decode( get_post_meta( $item->ID, '_kad_mega_menu_item_padding', true ), true );
			if ( is_array( $data['mega_menu_item_padding'] ) ) {
				if ( isset( $data['mega_menu_item_padding']['size'] ) && ! empty( $data['mega_menu_item_padding']['size'] ) ) {
					$css->set_selector( '.header-navigation .header-menu-container #menu-item-' . $item->ID . '.kadence-menu-mega-enabled > .sub-menu li.menu-item > a' );
					$unit = ( ! empty( $data['mega_menu_item_padding']['unit'] ) ? $data['mega_menu_item_padding']['unit'] : 'px' );
					if ( isset( $data['mega_menu_item_padding']['size'][0] ) && is_numeric( $data['mega_menu_item_padding']['size'][0] ) ) {
						$css->add_property( 'padding-top', $data['mega_menu_item_padding']['size'][0] . $unit );
					}
					if ( isset( $data['mega_menu_item_padding']['size'][1] ) && is_numeric( $data['mega_menu_item_padding']['size'][1] ) ) {
						$css->add_property( 'padding-right', $data['mega_menu_item_padding']['size'][1] . $unit );
					}
					if ( isset( $data['mega_menu_item_padding']['size'][2] ) && is_numeric( $data['mega_menu_item_padding']['size'][2] ) ) {
						$css->add_property( 'padding-bottom', $data['mega_menu_item_padding']['size'][2] . $unit );
					}
					if ( isset( $data['mega_menu_item_padding']['size'][3] ) && is_numeric( $data['mega_menu_item_padding']['size'][3] ) ) {
						$css->add_property( 'padding-left', $data['mega_menu_item_padding']['size'][3] . $unit );
					}
				}
			}
			$data['menu_dropdown_item_divider'] = json_decode( get_post_meta( $item->ID, '_kad_menu_dropdown_item_divider', true ), true );
			if ( isset( $data['menu_dropdown_item_divider'] ) && is_array( $data['menu_dropdown_item_divider'] ) && isset( $data['menu_dropdown_item_divider']['width'] ) ) {
				if ( ! isset( $data['menu_dropdown_item_divider']['style'] ) || empty( $data['menu_dropdown_item_divider']['style'] ) ) {
					$data['menu_dropdown_item_divider']['style'] = 'solid';
				}
				if ( ! isset( $data['menu_dropdown_item_divider']['unit'] ) || empty( $data['menu_dropdown_item_divider']['unit'] ) ) {
					$data['menu_dropdown_item_divider']['unit'] = 'px';
				}
				$css->set_selector( '.header-navigation .header-menu-container #menu-item-' . $item->ID . '.kadence-menu-mega-enabled > .sub-menu > li.menu-item > a, .header-navigation .header-menu-container #menu-item-' . $item->ID . '.kadence-menu-mega-enabled li.menu-item' );
				$css->add_property( 'border-bottom', $css->render_border( $data['menu_dropdown_item_divider'] ) );
			}

			self::$css .= $css->css_output();
		}
		return $classes;
	}
	/**
	 * Filter the menu item's title.
	 *
	 * @param string   $title The menu item's title.
	 * @param WP_Post  $item  The current menu item.
	 * @param stdClass $args  An object of wp_nav_menu() arguments.
	 * @param int      $depth Depth of menu item. Used for padding.
	 */
	public function apply_menu_title_changes( $title, $item, $args, $depth ) {
		if ( ! isset( $args->addon_support ) ) {
			return $title;
		}
		if ( ! $args->addon_support ) {
			return $title;
		}
		$data = array();
		$data['menu_label']              = get_post_meta( $item->ID, '_kad_menu_label', true );
		$data['menu_description']       = get_post_meta( $item->ID, '_kad_menu_description', true );
		$data['menu_icon_svg']           = get_post_meta( $item->ID, '_kad_menu_icon_svg', true );
		$data['menu_icon_side']          = get_post_meta( $item->ID, '_kad_menu_icon_side', true );
		$data['menu_icon_size']          = absint( get_post_meta( $item->ID, '_kad_menu_icon_size', true ) );
		$data['menu_icon_color']         = get_post_meta( $item->ID, '_kad_menu_icon_color', true );
		$data['menu_highlight']          = get_post_meta( $item->ID, '_kad_menu_highlight', true );
		$data['menu_highlight_icon_svg'] = get_post_meta( $item->ID, '_kad_menu_highlight_icon_svg', true );
		if ( isset( $data['menu_label'] ) && $data['menu_label'] ) {
			$title = '';
		}
		if ( isset( $data['menu_description'] ) && $data['menu_description'] ) {
			if ( $item->description ) {
				$title = '<span class="menu-label-content">' . $title . '<span class="menu-label-description">' . $item->description . '</span></span>';
			}
		}
		if ( isset( $data['menu_icon_svg'] ) && ! empty( $data['menu_icon_svg'] ) ) {
			$style = array();
			$style_output = '';
			$style[] = ( isset( $data['menu_icon_color'] ) && ! empty( $data['menu_icon_color'] ) ? 'color:' . $this->color_output( $data['menu_icon_color'] ) . ';' : '' );
			$style[] = ( isset( $data['menu_icon_size'] ) && ! empty( $data['menu_icon_size'] ) ? 'font-size: ' . ( $data['menu_icon_size'] / 100 ) . 'em;' : '' );
			if ( ! empty( $style ) ) {
				$style_output = ' style="' . implode( ' ', $style ) . '"';
			}
			$icon = '<span class="menu-label-icon-wrap"' . $style_output . '><span class="menu-label-icon">' . $data['menu_icon_svg'] . '</span></span>';
			if ( isset( $data['menu_icon_side'] ) && 'left' === $data['menu_icon_side'] ) {
				$title = $icon . $title;
			} else {
				$title .= $icon;
			}
		}
		if ( ( isset( $data['menu_highlight'] ) && ! empty( $data['menu_highlight'] ) ) || ( isset( $data['menu_highlight_icon_svg'] ) && ! empty( $data['menu_highlight_icon_svg'] ) ) ) {
			$data['menu_highlight_color'] = get_post_meta( $item->ID, '_kad_menu_highlight_color', true );
			$data['menu_highlight_background'] = get_post_meta( $item->ID, '_kad_menu_highlight_background', true );
			$style = array();
			$style_output = '';
			$style[] = ( isset( $data['menu_highlight_color'] ) && ! empty( $data['menu_highlight_color'] ) ? 'color:' . $this->color_output( $data['menu_highlight_color'] ) . ';' : '' );
			$style[] = ( isset( $data['menu_highlight_background'] ) && ! empty( $data['menu_highlight_background'] ) ? ' background:' . $this->color_output( $data['menu_highlight_background'] ) . ';' : '' );
			if ( ! empty( $style ) ) {
				$style_output = ' style="' . implode( ' ', $style ) . '"';
			}
			$icon = ( isset( $data['menu_highlight_icon_svg'] ) && ! empty( $data['menu_highlight_icon_svg'] ) ? '<span class="menu-highlight-icon">' . $data['menu_highlight_icon_svg'] . '</span>' : '' );
			$title .= '<span class="menu-highlight-item ' . ( isset( $data['menu_highlight'] ) && ! empty( $data['menu_highlight'] ) ? 'has-text-highlight' : 'only-icon-highlight' ) . '"' . $style_output . '>' . esc_html( $data['menu_highlight'] ) . $icon . '</span>';
		}
		return $title;
	}
	/**
	 * Filter the HTML attributes applied to a menu item's anchor element.
	 *
	 * @param array $atts {
	 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
	 *
	 *     @type string $title        Title attribute.
	 *     @type string $target       Target attribute.
	 *     @type string $rel          The rel attribute.
	 *     @type string $href         The href attribute.
	 *     @type string $aria_current The aria-current attribute.
	 * }
	 * @param WP_Post  $item  The current menu item.
	 * @param stdClass $args  An object of wp_nav_menu() arguments.
	 * @param int      $depth Depth of menu item. Used for padding.
	 */
	public function apply_menu_link_changes( $atts, $item, $args, $depth ) {
		if ( ! isset( $args->addon_support ) ) {
			return $atts;
		}
		if ( ! $args->addon_support ) {
			return $atts;
		}
		$data = array();
		$data['menu_link'] = get_post_meta( $item->ID, '_kad_menu_link', true );
		if ( isset( $data['menu_link'] ) && $data['menu_link'] ) {
			$atts['href'] = '';
		}
		return $atts;
	}
	/**
	 * Hex to RGBA
	 *
	 * @param string $hex string hex code.
	 * @param number $alpha alpha number.
	 */
	public function hex2rgba( $hex, $alpha ) {
		if ( empty( $hex ) ) {
			return '';
		}
		$hex = str_replace( '#', '', $hex );

		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgba = 'rgba(' . $r . ', ' . $g . ', ' . $b . ', ' . $alpha . ')';
		return $rgba;
	}
	/**
	 * Adds var to color output if needed.
	 *
	 * @param string $color the output color.
	 */
	public function color_output( $color, $opacity = null ) {
		if ( strpos( $color, 'palette' ) === 0 ) {
			$color = 'var(--global-' . $color . ')';
		} else if ( isset( $opacity ) && is_numeric( $opacity ) ) {
			$color = $this->hex2rgba( $color, $opacity );
		}
		return $color;
	}
	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'menu-addons', KTP_URL . 'dist/mega-menu/menu-addon.css', array(), KTP_VERSION );
		wp_register_script( 'kadence-mega-menu', KTP_URL . 'dist/mega-menu/kadence-mega-menu.min.js', array(), KTP_VERSION, true );
	}
	/**
	 * Loads admin style sheets and scripts
	 */
	public function scripts( $hook ) {
		if ( ! isset( $hook ) || 'nav-menus.php' !== $hook ) {
			return;
		}
		require_once KTP_PATH . 'dist/mega-menu/fa-icons.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		wp_enqueue_style( 'kadence-pro-mega',  KTP_URL . 'dist/build/mega-menu-controls.min.css', array( 'wp-components' ), KTP_VERSION );
		wp_enqueue_script( 'kadence-pro-mega', KTP_URL . 'build/mega-menu.js', array( 'wp-i18n', 'wp-element', 'wp-plugins', 'wp-components', 'wp-api', 'wp-hooks', 'wp-edit-post', 'lodash', 'wp-block-library', 'wp-block-editor', 'wp-editor', 'jquery' ), KTP_VERSION, true );
		$settings = array(
			'disableCustomColors'    => get_theme_support( 'disable-custom-colors' ),
			'disableCustomFontSizes' => get_theme_support( 'disable-custom-font-sizes' ),
			'isRTL'                  => is_rtl(),
		);
		wp_enqueue_media();
		list( $color_palette, ) = (array) get_theme_support( 'editor-color-palette' );
		list( $font_sizes, )    = (array) get_theme_support( 'editor-font-sizes' );
		if ( false !== $color_palette ) {
			$settings['colors'] = $color_palette;
		}
		if ( false !== $font_sizes ) {
			$settings['fontSizes'] = $font_sizes;
		}
		wp_localize_script(
			'kadence-pro-mega',
			'kadenceProMegaParams',
			array(
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'kadence-ajax-verification' ),
				'settings'   => wp_json_encode( $settings ),
				'faIco'      => apply_filters( 'kadence_pro_faico_json', ' ' ),
				'elements'   => wp_json_encode( $this->get_hooked_elements() ),
			)
		);
		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'kadence-pro-mega', 'kadence-pro' );
		}
	}
	/**
	 * Get All elements.
	 */
	public function get_hooked_elements() {
		$args = array(
			'post_type'   => 'kadence_element',
			'numberposts' => 300,
		);

		$posts = get_posts( $args );

		$elements = array();
		if ( $posts ) {
			foreach ( $posts as $post ) {
				$elements[] = array(
					'value' => $post->ID,
					'label' => $post->post_title,
				);
			}
		}
		return $elements;
	}
	/**
	 * Register Nav Meta options.
	 */
	public function register_meta() {
		register_meta(
			'nav_menu_item', // Pass an empty string to register the meta key across all existing post types.
			'_kad_menu_mega',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => '__return_true',
			)
		);
	}
	/**
	 * Get Nav Menu Item Data.
	 */
	public function get_item_data_ajax_callback() {
		if ( ! check_ajax_referer( 'kadence-ajax-verification', 'security', false ) ) {
			wp_send_json_error( __( 'Security Error, Please reload the page.', 'kadence-pro' ) );
		}

		// Check if user is allowed to reset values.
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error( 'invalid_permissions' );
		}
		$item_id   = sanitize_text_field( $_POST['item_id'] );
		$nav_id    = sanitize_text_field( $_POST['nav_id'] );
		$parent_id = sanitize_text_field( $_POST['parent_id'] );
		if ( empty( $item_id ) ) {
			wp_send_json_error( 'missing_data' );
		}
		$data = array();
		$data['mega_menu']        = get_post_meta( $item_id, '_kad_mega_menu', true );
		$data['mega_menu_width']  = get_post_meta( $item_id, '_kad_mega_menu_width', true );
		$data['mega_menu_custom_width']  = get_post_meta( $item_id, '_kad_mega_menu_custom_width', true );
		$data['mega_menu_columns'] = get_post_meta( $item_id, '_kad_mega_menu_columns', true );
		$data['mega_menu_layout'] = get_post_meta( $item_id, '_kad_mega_menu_layout', true );

		$data['menu_label'] = get_post_meta( $item_id, '_kad_menu_label', true );
		$data['menu_link']  = get_post_meta( $item_id, '_kad_menu_link', true );
		$data['menu_description']  = get_post_meta( $item_id, '_kad_menu_description', true );
		$data['menu_icon']  = get_post_meta( $item_id, '_kad_menu_icon', true );
		$data['menu_highlight_icon'] = get_post_meta( $item_id, '_kad_menu_highlight_icon', true );
		$data['menu_icon_side'] = get_post_meta( $item_id, '_kad_menu_icon_side', true );
		$data['menu_icon_size'] = get_post_meta( $item_id, '_kad_menu_icon_size', true );
		$data['menu_icon_color'] = get_post_meta( $item_id, '_kad_menu_icon_color', true );
		//$data['menu_highlight_icon_svg'] = get_post_meta( $item_id, '_kad_menu_highlight_icon_svg', true );
		$data['menu_highlight'] = get_post_meta( $item_id, '_kad_menu_highlight', true );
		$data['menu_highlight_color'] = get_post_meta( $item_id, '_kad_menu_highlight_color', true );
		$data['menu_highlight_background'] = get_post_meta( $item_id, '_kad_menu_highlight_background', true );

		$data['mega_menu_padding'] = get_post_meta( $item_id, '_kad_mega_menu_padding', true );
		$data['menu_dropdown_background'] = get_post_meta( $item_id, '_kad_menu_dropdown_background', true );
		$data['menu_dropdown_item_color'] = get_post_meta( $item_id, '_kad_menu_dropdown_item_color', true );
		$data['mega_menu_item_padding'] = get_post_meta( $item_id, '_kad_mega_menu_item_padding', true );
		$data['menu_dropdown_item_background'] = get_post_meta( $item_id, '_kad_menu_dropdown_item_background', true );
		$data['menu_dropdown_item_divider'] = get_post_meta( $item_id, '_kad_menu_dropdown_item_divider', true );

		$data['menu_item_custom_divider'] = get_post_meta( $item_id, '_kad_menu_item_custom_divider', true );
		$data['menu_item_custom'] = get_post_meta( $item_id, '_kad_menu_item_custom', true );
		$data['menu_item_custom_element'] = get_post_meta( $item_id, '_kad_menu_item_custom_element', true );
		if ( ! empty( $parent_id ) ) {
			$data['parent_mega_menu'] = get_post_meta( $parent_id, '_kad_mega_menu', true );
		}

		wp_send_json_success( json_encode( $data ) );
	}
	/**
	 * Save Nav Menu Item Data.
	 */
	public function save_item_data_ajax_callback() {
		if ( ! check_ajax_referer( 'kadence-ajax-verification', 'security', false ) ) {
			wp_send_json_error( __( 'Security Error, please reload the page.', 'kadence-pro' ) );
		}

		// Check if user is allowed to reset values.
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error( 'Invalid User Permissions, please reload the page.' );
		}
		$item_id = sanitize_text_field( $_POST['item_id'] );
		$nav_id  = sanitize_text_field( $_POST['nav_id'] );
		$data    = json_decode( wp_unslash( $_POST['data'] ), true );
		if ( empty( $item_id ) || empty( $data ) || ! is_array( $data ) ) {
			wp_send_json_error( 'Missing Data, please try again.' );
		}
		foreach ( $data as $key => $value ) {
			if ( in_array( $key, array( 'menu_highlight_icon_svg', 'menu_icon_svg' ), true ) ) {
				update_post_meta( $item_id, '_kad_' . $key, $value );
			} else {
				$value = sanitize_text_field( $value );
				update_post_meta( $item_id, '_kad_' . $key, $value );
			}
		}
		wp_send_json_success();
	}
	/**
	 * Render buttons.
	 *
	 * @param integer $item_id the item id.
	 * @param WP_Post $item the iitem post object.
	 * @param integer $depth the menu item depth.
	 * @param stdClass $args the args in an object.
	 * @param integer $id the id of the nav menu.
	 */
	public function render_field_control_containers( $item_id, $item, $depth, $args, $id ) {
		?>
		<p class="field-kadence-mega description-wide kadence-menu-options" data-item-id="<?php echo esc_attr( $item_id ); ?>" data-nav-id="<?php echo esc_attr( $id ); ?>">
			<!-- <button class="button button-secondary kadence-mega-options-button" data-item-id="<?php echo esc_attr( $item_id ); ?>">
				<?php esc_html_e( 'Mega Menu - Container Settings', 'kadence-pro' ); ?>
			</button> -->
		</p>
		<?php
	}
	/**
	 * Render control containers.
	 */
	public function render_modal_container() {
		$screen = get_current_screen();
		if ( 'nav-menus' !== $screen->base ) {
			return;
		}
		?>
		<div id="kadence-mega-options-panel">
		</div>
		<?php
	}
}

Mega_Menu::get_instance();
