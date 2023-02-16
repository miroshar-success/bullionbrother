<?php
/**
 * Class Kadence_Pro\Elements_Post_Type_Controller
 *
 * @package Kadence Pro
 */

namespace Kadence_Pro;

use DateTime;
use Kadence_Blocks_Frontend;
use FLBuilder;
use FLBuilderModel;
use DOMDocument;
use \Elementor\Plugin;
use \Elementor\Core\Files\CSS\Post;
use Brizy_Editor;
use Brizy_Editor_Post;
use Brizy_Editor_Project;
use Brizy_Editor_CompiledHtml;
use Brizy_Public_Main;
use SiteOrigin_Panels_Settings;
use function Kadence\kadence;
use function get_editable_roles;
use function siteorigin_panels_render;
use function do_shortcode;
use function tutor;
use function extension_loaded;
use function libxml_use_internal_errors;
/**
 * Class managing the template areas post type.
 */
class Elements_Post_Type_Controller {

	const SLUG = 'kadence_element';
	const TYPE_SLUG = 'element_type';
	const TYPE_META_KEY = '_kad_element_type';

	/**
	 * Set check if user wants ace block.
	 *
	 * @var $aceblock bool for ace block.
	 */
	private static $aceblock = null;
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
	 * Gather placeholders for elements
	 * (This method was adopted from the advanaced ads plugin)
	 *
	 * @var array $placeholders_for_elements
	 */
	private static $placeholders_for_elements = array();

	/**
	 * Instance Control
	 *
	 * @var null
	 */
	private static $instance = null;

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
	 * Instance Control.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor function.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ), 1 );
		add_filter( 'user_has_cap', array( $this, 'filter_post_type_user_caps' ) );
		add_action( 'init', array( $this, 'plugin_register' ), 20 );
		add_action( 'init', array( $this, 'register_meta' ), 20 );
		add_action( 'init', array( $this, 'setup_content_filter' ), 9 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'script_enqueue' ) );
		add_action( 'wp', array( $this, 'init_frontend_hooks' ), 99 );
		add_action( 'kadence_single_product_ajax_added_to_cart', array( $this, 'init_mini_cart_hooks' ) );
		add_filter( 'kadence_post_layout', array( $this, 'element_single_layout' ), 99 );
		add_action( 'init', array( $this, 'element_gutenberg_template' ) );
		add_action( 'init', array( $this, 'register_ace_script_block' ) );
		add_action( 'kadence_theme_admin_menu', array( $this, 'create_admin_page' ) );
		add_filter( 'submenu_file', array( $this, 'current_menu_fix' ) );
		add_shortcode( self::SLUG, array( $this, 'shortcode_output' ) );
		add_action( 'wp', array( $this, 'elements_single_only_logged_in_editors' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		//add_action( 'rest_request_before_callbacks', array( $this, 'filter_rest_request_for_kses' ), 100, 3 );
		add_filter( 'the_content', array( $this, 'undo_filters_for_code_output' ), 1 );
		// allow shortcode in widgets.
		add_filter( 'widget_text', 'do_shortcode' );
		//add_action( 'in_admin_header', array( $this, 'add_element_tabs' ) );
		// add_action(
		// 	'kadence_theme_admin_menu',
		// 	function() {
		// 		$this->fix_admin_menu_entry();
		// 	}
		// );


		$slug = self::SLUG;
		add_filter(
			"manage_{$slug}_posts_columns",
			function( array $columns ) : array {
				return $this->filter_post_type_columns( $columns );
			}
		);
		add_action(
			"manage_{$slug}_posts_custom_column",
			function( string $column_name, int $post_id ) {
				$this->render_post_type_column( $column_name, $post_id );
			},
			10,
			2
		);
		// if ( is_admin() ) {
		// 	add_action( 'load-post.php', array( $this, 'init_classic_metabox' ) );
		// 	add_action( 'load-post-new.php', array( $this, 'init_classic_metabox' ) );
		// }
		//add_filter( 'wpseo_sitemap_exclude_post_type', array( $this, 'sitemap_exclude_elements' ), 10, 2 );
		//add_action( 'add_meta_boxes', array( $this, 'yoast_exclude_elements' ), 100 );
		// Add tabs for element "types". Here is where that happens.
		add_filter( 'views_edit-' . self::SLUG, array( $this, 'admin_print_tabs' ) );
		add_action( 'pre_get_posts', array( $this, 'admin_filter_results' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'action_enqueue_admin_scripts' ) );
		add_action( 'wp_ajax_kadence_elements_change_status', array( $this, 'ajax_change_status' ) );
		if ( class_exists( 'Kadence_Pro\Duplicate_Elements' ) ) {
			new Duplicate_Elements( self::SLUG );
		}
	}
	/**
	 * Enqueues a script that adds sticky for single products
	 */
	public function action_enqueue_admin_scripts() {
		$current_page = get_current_screen();
		if ( 'edit-' . self::SLUG === $current_page->id ) {
			// Enqueue the post styles.
			wp_enqueue_style( 'kadence-elements-admin', KTP_URL . 'dist/elements/kadence-pro-element-post-admin.css', false, KTP_VERSION );
			wp_enqueue_script( 'kadence_elements-admin', KTP_URL . 'dist/elements/kadence-pro-element-post-admin.min.js', array( 'jquery' ), KTP_VERSION, true );
			wp_localize_script(
				'kadence_elements-admin',
				'kadence_elements_params',
				array(
					'ajax_url'   => admin_url( 'admin-ajax.php' ),
					'ajax_nonce' => wp_create_nonce( 'kadence_elements-ajax-verification' ),
					'draft' => esc_attr__( 'Draft', 'kadence-pro' ),
					'publish' => esc_attr__( 'Published', 'kadence-pro' ),
				)
			);
		}
	}
	/**
	 * Ajax callback function.
	 */
	public function ajax_change_status() {
		check_ajax_referer( 'kadence_elements-ajax-verification', 'security' );

		if ( ! isset ( $_POST['post_id'] ) || ! isset( $_POST['post_status'] ) ) {
			wp_send_json_error( __( 'Error: No post information was retrieved.', 'kadence-pro' ) );
		}
		$post_id = empty( $_POST['post_id'] ) ? '' : sanitize_text_field( wp_unslash( $_POST['post_id'] ) );
		$post_status = empty( $_POST['post_status'] ) ? '' : sanitize_text_field( wp_unslash( $_POST['post_status'] ) );
		$response = false;
		if ( 'publish' === $post_status ) {
			$response = $this->change_post_status( $post_id, 'draft' );
		} else if ( 'draft' === $post_status ) {
			$response = $this->change_post_status( $post_id, 'publish' );
		}
		if ( ! $response ) {
			$error = new WP_Error( '001', 'Post Status invalid.' );
			wp_send_json_error( $error );
		}
		wp_send_json_success();
	}
	/**
	 * Change the post status
	 * @param number $post_id - The ID of the post you'd like to change.
	 * @param string $status -  The post status publish|pending|draft|private|static|object|attachment|inherit|future|trash.
	 */
	public function change_post_status( $post_id, $status ) {
		if ( 'publish' === $status || 'draft' === $status ) {
			$current_post = get_post( $post_id );
			$current_post->post_status = $status;
			return wp_update_post( $current_post );
		} else {
			return false;
		}
	}
	/**
	 * Filter the post results if tabs selected.
	 *
	 * @param object $query An array of available list table views.
	 */
	public function admin_filter_results( $query ) {
		if ( ! ( is_admin() && $query->is_main_query() ) ) {
			return $query;
		}
		if ( ! ( isset( $query->query['post_type'] ) && 'kadence_element' === $query->query['post_type'] && isset( $_REQUEST[ self::TYPE_SLUG ] ) ) ) {
			return $query;
		}
		$screen = get_current_screen();
		if ( $screen->id == 'edit-kadence_element' ) {
			if ( isset( $_REQUEST[ self::TYPE_SLUG ] ) ) {
				$type_slug = sanitize_text_field( $_REQUEST[ self::TYPE_SLUG ] );
				if ( ! empty( $type_slug ) ) {
					$query->query_vars['meta_query'] = array(
						array(
							'key'   => self::TYPE_META_KEY,
							'value' => $type_slug,
						),
					);
				}
			}
		}
		return $query;
	}
	/**
	 * Print admin tabs.
	 *
	 * Used to output the conversion tabs with their labels.
	 *
	 *
	 * @param array $views An array of available list table views.
	 *
	 * @return array An updated array of available list table views.
	 */
	public function admin_print_tabs( $views ) {
		$current_type = '';
		$active_class = ' nav-tab-active';
		if ( ! empty( $_REQUEST[ self::TYPE_SLUG ] ) ) {
			$current_type = $_REQUEST[ self::TYPE_SLUG ];
			$active_class = '';
		}

		$url_args = [
			'post_type' => self::SLUG,
		];

		$baseurl = add_query_arg( $url_args, admin_url( 'edit.php' ) );
		?>
		<div id="kadence-element-tabs-wrapper" class="nav-tab-wrapper">
			<a class="nav-tab<?php echo esc_attr( $active_class ); ?>" href="<?php echo esc_url( $baseurl ); ?>">
				<?php echo esc_html__( 'All Element Items', 'kadence-pro' ); ?>
			</a>
			<?php
			$types = array(
				'default' => array( 
					'label' => __( 'Sections', 'kadence-pro' ),
				),
				'fixed' => array( 
					'label' => __( 'Fixed Items', 'kadence-pro' ),
				),
				'template' => array( 
					'label' => __( 'Templates', 'kadence-pro' ),
				),
				'script' => array( 
					'label' => __( 'HTML code', 'kadence-pro' ),
				),
			);
			foreach ( $types as $key => $type ) :
				$active_class = '';

				if ( $current_type === $key ) {
					$active_class = ' nav-tab-active';
				}

				$type_url = esc_url( add_query_arg( self::TYPE_SLUG, $key, $baseurl ) );
				$type_label = $type['label'];
				echo "<a class='nav-tab{$active_class}' href='{$type_url}'>{$type_label}</a>";
			endforeach;
			?>
		</div>
		<?php
		return $views;
	}
	/**
	 * Meta box initialization.
	 */
	public function init_classic_metabox() {
		$path = KTP_URL . 'build/';
		wp_enqueue_style( 'kadence-element-meta', KTP_URL . 'dist/build/meta-controls.css', array( 'wp-components' ), KTP_VERSION );
		wp_register_script(
			'kadence-element-classic-meta',
			$path . 'classic-meta.js',
			array( 'moment', 'react', 'react-dom', 'wp-components', 'wp-compose', 'wp-data', 'wp-element', 'wp-i18n', 'wp-polyfill', 'wp-edit-post' ),
			KTP_VERSION
		);
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
	}
	/**
	 * Adds the product meta box.
	 */
	public function add_metabox() {
		add_meta_box(
			'_kad_classic_meta_control',
			__( 'Element Settings', 'kadence-pro' ),
			array( $this, 'render_classic_metabox' ),
			array( self::SLUG ),
			'side',
			'low',
			array(
				'__back_compat_meta_box' => true,
			)
		);
	}
	/**
	 * Renders the meta box.
	 *
	 * @param object $post the post object.
	 */
	public function render_classic_metabox( $post ) {
		wp_localize_script(
			'kadence-element-classic-meta',
			'kadenceElementParams',
			array(
				'post_type'  => self::SLUG,
				'hooks'      => $this->get_hook_options(),
				'codeHooks'  => $this->get_code_hook_options(),
				'fixedHooks' => $this->get_fixed_hook_options(),
				'authors'    => $this->get_author_options(),
				'ace'        => $this->use_ace_block(),
				'display'    => $this->get_display_options(),
				'user'       => $this->get_user_options(),
				'languageSettings' => $this->get_language_options(),
				'restBase'   => esc_url_raw( get_rest_url() ),
				'postSelectEndpoint' => '/ktp/v1/post-select',
				//'postTypes' => kadence_blocks_pro_get_post_types(),
				'taxonomies'    => $this->get_taxonomies(),
				'templateHooks' => $this->get_template_hook_options(),
			)
		);
		$path = KTP_URL . 'build/';
		wp_enqueue_style( 'kadence-element-meta', KTP_URL . 'dist/build/meta-controls.css', false, KTP_VERSION );
		wp_enqueue_script( 'kadence-element-classic-meta' );
		// Add nonce for security and authentication.
		wp_nonce_field( 'kadence_elements_classic_meta_nonce_action', 'kadence_elements_classic_meta_nonce' );
		$output = '<div class="kadence_classic_meta_boxes">';
		$output .= '<div class="kadence_elements_classic_meta" data-post-id="' . esc_attr( $post->ID ) . '" style="padding: 10px 0 0;">';
		$output .= '</div>';
		$output .= '</div>';
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	/**
	 * Make sure elements don't have yoast SEO Metabox
	 */
	public function yoast_exclude_elements() {
		remove_meta_box( 'wpseo_meta', self::SLUG, 'normal' );
	}
	/**
	 * Make sure code elements are not sanitized.
	 *
	 * @param string $content if the post is set to show.
	 */
	public function undo_filters_for_code_output( $content ) {
		if ( is_singular( self::SLUG ) ) {
			global $post;
			$type = 'default';
			if ( get_post_meta( $post->ID, '_kad_element_type', true ) ) {
				$type = get_post_meta( $post->ID, '_kad_element_type', true );
			}
			if ( empty( $type ) ) {
				$type = 'default';
			}
			if ( 'script' === $type ) {
				remove_filter( 'the_content', 'wptexturize' );
				remove_filter( 'the_content', 'wp_filter_content_tags' );
			}
		}
		return $content;
	}
	/**
	 * Make sure elements are not in yoast sitemap.
	 *
	 * @param boolean $value if the post is set to show.
	 * @param string  $post_type the current post type.
	 */
	public function sitemap_exclude_elements( $value, $post_type ) {
		if ( self::SLUG === $post_type ) {
			return true;
		}
	}
	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts() {
		wp_register_style( 'kadence-pro-sticky', KTP_URL . 'dist/elements/kadence-pro-sticky.css', array(), KTP_VERSION );
		wp_register_script( 'kadence-pro-sticky', KTP_URL . 'dist/elements/kadence-pro-sticky.min.js', array(), KTP_VERSION, true );
	}
	/**
	 * Make sure elements are not in yoast sitemap.
	 *
	 * @param string $submenu_file the string for submenu.
	 */
	public function current_menu_fix( $submenu_file ) {
		global $parent_file, $post_type;
		if ( $post_type && self::SLUG === $post_type ) {
			$parent_file  = 'themes.php';
			$submenu_file = 'edit.php?post_type=' . self::SLUG;
		}
		return $submenu_file;
	}
	/**
	 * Make sure elements can't be accessed directly from none logged in users.
	 */
	public function elements_single_only_logged_in_editors() {
		if ( is_singular( self::SLUG ) && ! current_user_can( 'edit_posts' ) ) {
			wp_redirect( site_url(), 301 );
			die;
		}
	}
	/**
	 * Creates the plugin page and a submenu item in WP Appearance menu.
	 */
	public function create_admin_page() {
		$page = add_theme_page(
			null,
			esc_html__( 'Elements', 'kadence-pro' ),
			'edit_pages',
			'edit.php?post_type=' . self::SLUG
		);
	}
	/**
	 * Instance Control.
	 */
	public static function use_ace_block() {
		if ( is_null( self::$aceblock ) ) {
			self::$aceblock = boolval( apply_filters( 'kadence-pro-use-ace-block', true ) );
		}
		return self::$aceblock;
	}
	/**
	 * Add Ace Block For Elements.
	 */
	public function register_ace_script_block() {
		if ( ! $this->use_ace_block() ) {
			return;
		}
		// Check if this is the intended custom post type.
		if ( is_admin() ) {
			global $pagenow;
			$typenow = '';
			if ( 'post-new.php' === $pagenow ) {
				if ( isset( $_REQUEST['post_type'] ) && post_type_exists( $_REQUEST['post_type'] ) ) {
					$typenow = $_REQUEST['post_type'];
				};
			} elseif ( 'post.php' === $pagenow ) {
				if ( isset( $_GET['post'] ) && isset( $_POST['post_ID'] ) && (int) $_GET['post'] !== (int) $_POST['post_ID'] ) {
					// Do nothing
				} elseif ( isset( $_GET['post'] ) ) {
					$post_id = (int) $_GET['post'];
				} elseif ( isset( $_POST['post_ID'] ) ) {
					$post_id = (int) $_POST['post_ID'];
				}

				if ( $post_id ) {
					$post = get_post( $post_id );
					$typenow = $post->post_type;
				}
			}
			if ( $typenow != self::SLUG ) {
				return;
			}
		}
		// Register the block.
		wp_register_script(
			'kadence-pro-script',
			KTP_URL . 'build/ace-html.js',
			array( 'wp-blocks', 'wp-element', 'wp-editor' ),
			KTP_VERSION
		);
		register_block_type(
			'kadence-pro/ace-html',
			array(
				'editor_script' => 'kadence-pro-script',
			)
		);
	}	
	/**
	 * Temporarily modifies post content during saving in a way that KSES
	 * does not strip actually valid html from post content, making block content invalid.
	 */
	public function filter_rest_request_for_kses( $response, $handler, $request ) {
		// Short-circuit since this is relevant only for users without unfiltered_html capability.
		// error_log( 'handle' );
		// error_log( print_r( $handler, true ) );
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		if ( isset( $request['id'] ) && ! empty( $request['id'] ) && isset( $request['content'] ) && ! empty( $request['content'] ) && strpos( $request['content'], '<!-- wp:kadence-pro/ace-html -->') === 0 ) { 
		}
		if ( ! current_user_can( 'unfiltered_html' ) ) {
			return $response;
		}
		if ( ! current_user_can( 'edit_post', $request['id'] ) ) {
			return $response;
		}
		$post_value = '';
		// Replace inline styles with temporary data-temp-style-hash attribute before KSES...
		add_filter(
			'content_save_pre',
			static function ( $post_content ) use ( &$post_value ) {
				$post_value = $post_content;
				$post_value = str_replace( '<!-- wp:kadence-pro/ace-html -->', '', $post_value );
				$post_value = str_replace( '<!-- /wp:kadence-pro/ace-html -->', '', $post_value );
				//error_log( $post_value );
				$post_content = '<!-- wp:kadence-pro/ace-html -->[element_code]<!-- /wp:kadence-pro/ace-html -->';
				return $post_content;
			},
			0
		);
		// ...And bring it back afterwards.
		add_filter(
			'content_save_pre',
			static function ( $post_content ) use ( &$post_value ) {
				// Replaces hashed style attribute value with the original value again.
				$post_content = str_replace( '[element_code]', $post_value, $post_content );
				//error_log( $post_content );
				return $post_content;
			},
			100
		);
		return $response;
	}
	/**
	 * Add filters for element content output.
	 */
	public function element_gutenberg_template() {
		if ( ! is_admin() || ! isset( $_GET['post'] ) ) {
			// This is not the post/page we want to limit things to.
			return;
		}
		$post = get_post( $_GET['post'] );
		if ( ! $post ) {
			return;
		}
		if ( is_object( $post ) && self::SLUG !== $post->post_type ) {
			return;
		}
		$type = 'default';
		if ( get_post_meta( $post->ID, '_kad_element_type', true ) ) {
			$type = get_post_meta( $post->ID, '_kad_element_type', true );
		}
		if ( empty( $type ) ) {
			$type = 'default';
		}
		if ( 'script' === $type ) {
			$post_type_object = get_post_type_object( self::SLUG );
			if ( $this->use_ace_block() ) {
				$post_type_object->template = array(
					array( 'kadence-pro/ace-html' ),
				);
			} else {
				$post_type_object->template = array(
					array( 'core/html' ),
				);
			}
			$post_type_object->template_lock = 'all';
		}
	}
	/**
	 * Render shortcode.
	 *
	 * @param array $atts the shortcode args.
	 * @return string
	 */
	public function shortcode_output( $atts ) {
		$atts = shortcode_atts( array(
			'id' => 0,
		), $atts, self::SLUG );

		if ( empty( $atts['id'] ) ) {
			return;
		}
		$element = get_post( $atts['id'] );

		if ( empty( $element ) ) {
			return;
		}

		if ( 'publish' !== get_post_status( $element ) ) {
			return;
		}

		$meta    = $this->get_post_meta_array( $element );
		if ( ! apply_filters( 'kadence_element_display', $this->check_element_conditionals( $element, $meta ), $element, $meta ) ) {
			return;
		}

		ob_start();
		$this->output_element( $element, $meta, true );
		return ob_get_clean();
	}
	/**
	 * Add filters for element content output.
	 */
	public function setup_content_filter() {
		global $wp_embed;
		add_filter( 'ktp_the_content', array( $wp_embed, 'run_shortcode' ), 8 );
		add_filter( 'ktp_the_content', array( $wp_embed, 'autoembed'     ), 8 );
		add_filter( 'ktp_the_content', 'do_blocks' );
		add_filter( 'ktp_the_content', 'wptexturize' );
		add_filter( 'ktp_the_content', 'convert_chars' );
		// Don't use this unless classic editor add_filter( 'ktp_the_content', 'wpautop' );
		add_filter( 'ktp_the_content', 'shortcode_unautop' );
		add_filter( 'ktp_the_content', 'wp_filter_content_tags' );
		add_filter( 'ktp_the_content', 'do_shortcode', 11 );
		add_filter( 'ktp_the_content', 'convert_smilies', 20 );

		add_filter( 'ktp_code_the_content', array( $wp_embed, 'run_shortcode' ), 8 );
		add_filter( 'ktp_code_the_content', array( $wp_embed, 'autoembed'     ), 8 );
		add_filter( 'ktp_code_the_content', 'do_blocks' );
		//add_filter( 'ktp_code_the_content', 'wptexturize' );
		//add_filter( 'ktp_code_the_content', 'convert_chars' );
		// Don't use this unless classic editor add_filter( 'ktp_code_the_content', 'wpautop' );
		add_filter( 'ktp_code_the_content', 'shortcode_unautop' );
		add_filter( 'ktp_code_the_content', 'do_shortcode', 11 );
		add_filter( 'ktp_code_the_content', 'convert_smilies', 20 );
	}
	/**
	 * Loop through elements and hook items in that are part of the cart.
	 */
	public function init_mini_cart_hooks() {
		if ( ( is_admin() && ! wp_doing_ajax() ) || is_singular( self::SLUG ) ) {
			return;
		}
		$args = array(
			'post_type'              => self::SLUG,
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'post_status'            => 'publish',
			'numberposts'            => 333,
			'order'                  => 'ASC',
			'orderby'                => 'menu_order',
			'suppress_filters'       => false,
		);
		$posts = get_posts( $args );
		foreach ( $posts as $post ) {
			$meta = $this->get_post_meta_array( $post );
			if ( isset( $meta['hook'] ) && ! empty( $meta['hook'] ) && ( 'woocommerce_before_mini_cart_contents' === $meta['hook'] || 'woocommerce_mini_cart_contents' === $meta['hook'] || 'woocommerce_widget_shopping_cart_before_buttons' === $meta['hook'] || 'woocommerce_widget_shopping_cart_after_buttons' === $meta['hook'] ) ) {
				if ( apply_filters( 'kadence_element_display', $this->check_element_conditionals( $post, $meta ), $post, $meta ) ) {
					add_action(
						esc_attr( $meta['hook'] ),
						function() use ( $post, $meta ) {
							$this->output_element( $post, $meta );
						},
						absint( $meta['priority'] )
					);
					$this->enqueue_element_styles( $post, $meta );
				}
			}
		}
	}
	/**
	 * Loop through elements and hook items in where needed.
	 */
	public function init_frontend_hooks() {
		if ( is_admin() || is_singular( self::SLUG ) ) {
			return;
		}
		$args = array(
			'post_type'              => self::SLUG,
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'post_status'            => 'publish',
			'numberposts'            => 333,
			'order'                  => 'ASC',
			'orderby'                => 'menu_order',
			'suppress_filters'       => false,
		);
		$posts = get_posts( $args );
		foreach ( $posts as $post ) {
			$meta = $this->get_post_meta_array( $post );
			if ( apply_filters( 'kadence_element_display', $this->check_element_conditionals( $post, $meta ), $post, $meta ) ) {
				if ( isset( $meta['hook'] ) && ! empty( $meta['hook'] ) && 'fixed' !== substr( $meta['hook'], 0, 5 ) && 'custom' !== $meta['hook'] && 'replace_header' !== $meta['hook'] && 'replace_404' !== $meta['hook'] && 'replace_footer' !== $meta['hook'] && 'kadence_before_wrapper' !== $meta['hook'] && 'replace_login_modal' !== $meta['hook'] && 'replace_hero_header' !== $meta['hook'] && 'replace_single_content' !== $meta['hook'] && 'replace_archive_content' !== $meta['hook'] && 'replace_loop_content' !== $meta['hook'] && 'replace_meta' !== $meta['hook'] && 'woocommerce_before_single_product_image' !== $meta['hook'] && 'woocommerce_after_single_product_image' !== $meta['hook'] && 'kadence_inside_the_content_before_h1' !== $meta['hook'] && 'kadence_inside_the_content_after_h1' !== $meta['hook'] && 'kadence_inside_the_content_after_p1' !== $meta['hook'] && 'kadence_inside_the_content_after_p2' !== $meta['hook'] && 'kadence_inside_the_content_after_p3' !== $meta['hook'] && 'kadence_inside_the_content_after_p4' !== $meta['hook'] && 'kadence_replace_sidebar' !== $meta['hook'] ) {
					add_action(
						esc_attr( $meta['hook'] ),
						function() use( $post, $meta ) {
							$this->output_element( $post, $meta );
						},
						absint( $meta['priority'] )
					);
					$this->enqueue_element_styles( $post, $meta );
				} else if ( isset( $meta['hook'] ) && 'custom' === $meta['hook'] && isset( $meta['custom'] ) && ! empty( $meta['custom'] ) ) {
					add_action(
						esc_attr( $meta['custom'] ),
						function() use( $post, $meta ) {
							$this->output_element( $post, $meta );
						},
						absint( $meta['priority'] )
					);
					$this->enqueue_element_styles( $post, $meta );
				} else if ( isset( $meta['hook'] ) && 'kadence_before_wrapper' === $meta['hook'] ) {
					add_action(
						'kadence_before_wrapper',
						function() use( $post, $meta ) {
							echo '<!-- [special-element-' . esc_attr( $post->ID ) . '] -->';
							echo '<div class="kadence-before-wrapper-item">';
							$this->output_element( $post, $meta );
							echo '</div>';
							echo '<!-- [/special-element-' . esc_attr( $post->ID ) . '] -->';
						},
						absint( $meta['priority'] )
					);
					$this->enqueue_element_styles( $post, $meta );
				} else if ( isset( $meta['hook'] ) && 'replace_header' === $meta['hook'] ) {
					remove_action( 'kadence_header', 'Kadence\header_markup' );
					add_action(
						'kadence_header',
						function() use( $post, $meta ) {
							$this->output_element( $post, $meta );
						},
						absint( $meta['priority'] )
					);
					$this->enqueue_element_styles( $post, $meta );
				} else if ( isset( $meta['hook'] ) && 'replace_footer' === $meta['hook'] ) {
					remove_action( 'kadence_footer', 'Kadence\footer_markup' );
					add_action(
						'kadence_footer',
						function() use( $post, $meta ) {
							$this->output_element( $post, $meta );
						},
						absint( $meta['priority'] )
					);
					$this->enqueue_element_styles( $post, $meta );
				} else if ( isset( $meta['hook'] ) && 'replace_hero_header' === $meta['hook'] ) {
					remove_action( 'kadence_hero_header', 'Kadence\hero_title' );
					add_action(
						'kadence_hero_header',
						function() use( $post, $meta ) {
							$this->output_element( $post, $meta );
						},
						absint( $meta['priority'] )
					);
					$this->enqueue_element_styles( $post, $meta );
				} else if ( isset( $meta['hook'] ) && 'kadence_replace_sidebar' === $meta['hook'] ) {
					add_filter(
						'kadence_dynamic_sidebar_content',
						function() use( $post, $meta ) {
							$this->output_element( $post, $meta );
						},
						absint( $meta['priority'] )
					);
					$this->enqueue_element_styles( $post, $meta );
				} else if ( isset( $meta['hook'] ) && 'replace_404' === $meta['hook'] ) {
					if ( is_404() ) {
						remove_action( 'kadence_404_content', 'Kadence\get_404_content' );
						add_filter(
							'kadence_post_layout',
							function( $layout ) {
								$layout['boxed']    = 'unboxed';
								$layout['vpadding'] = 'hide';
								return $layout;
							},
							10
						);
						add_action(
							'kadence_404_content',
							function() use( $post, $meta ) {
								$this->output_element( $post, $meta );
							},
							absint( $meta['priority'] )
						);
						$this->enqueue_element_styles( $post, $meta );
					}
				} else if ( isset( $meta['hook'] ) && 'replace_single_content' === $meta['hook'] ) {
					remove_action( 'kadence_single_content', 'Kadence\single_content' );
					add_action(
						'kadence_single_content',
						function() use( $post, $meta ) {
							$this->output_element( $post, $meta );
						},
						absint( $meta['priority'] )
					);
					$this->enqueue_element_styles( $post, $meta );
				} else if ( isset( $meta['hook'] ) && 'replace_loop_content' === $meta['hook'] ) {
					remove_action( 'kadence_loop_entry', 'Kadence\loop_entry' );
					add_action(
						'kadence_loop_entry',
						function() use ( $post, $meta ) {
							echo '<div class="' . esc_attr( implode( ' ', get_post_class( 'custom-archive-loop-item entry' ) ) ) . '">';
							$this->output_element( $post, $meta );
							echo '</div>';
						},
						absint( $meta['priority'] )
					);
					$this->enqueue_element_styles( $post, $meta );
				} else if ( isset( $meta['hook'] ) && 'woocommerce_before_single_product_image' === $meta['hook'] ) {
					add_action( 'woocommerce_before_single_product_summary', array( $this, 'product_image_before_wrap' ), 11 );
					add_action( 'woocommerce_before_single_product_summary', array( $this, 'product_image_after_wrap' ), 80 );
					add_action(
						'woocommerce_before_single_product_summary',
						function() use( $post, $meta ) {
							echo '<!-- [special-element-' . esc_attr( $post->ID ) . '] -->';
							echo '<div class="product-before-images-element">';
							$this->output_element( $post, $meta );
							echo '</div>';
							echo '<!-- [/special-element-' . esc_attr( $post->ID ) . '] -->';
						},
						12
					);
				} elseif ( isset( $meta['hook'] ) && 'woocommerce_after_single_product_image' === $meta['hook'] ) {
					add_action( 'woocommerce_before_single_product_summary', array( $this, 'product_image_before_wrap' ), 11 );
					add_action( 'woocommerce_before_single_product_summary', array( $this, 'product_image_after_wrap' ), 80 );
					add_action(
						'woocommerce_before_single_product_summary',
						function() use( $post, $meta ) {
							echo '<!-- [special-element-' . esc_attr( $post->ID ) . '] -->';
							echo '<div class="product-after-images-element">';
							$this->output_element( $post, $meta );
							echo '</div>';
							echo '<!-- [/special-element-' . esc_attr( $post->ID ) . '] -->';
						},
						50
					);
				} elseif ( isset( $meta['hook'] ) && 'replace_login_modal' === $meta['hook'] ) {
						remove_action( 'kadence_account_login_form', 'Kadence_Pro\account_login_form' );
						add_action(
							'kadence_account_login_form',
							function() use( $post, $meta ) {
								$this->output_element( $post, $meta );
							},
							absint( $meta['priority'] )
						);
						$this->enqueue_element_styles( $post, $meta );
				} elseif ( isset( $meta['hook'] ) && strlen( $meta['hook'] ) > 26 && 'kadence_inside_the_content' === substr( $meta['hook'], 0, 26 ) ) {
					switch ( $meta['hook'] ) {
						case 'kadence_inside_the_content_before_h1':
							add_filter(
								'the_content',
								function( $content ) use( $post, $meta ) {
									if ( ! self::apply_in_content_filter( $post ) ) {
										ob_start();
											$this->output_element( $post, $meta );
										$insertion = ob_get_clean();
										$content = $this->insert_inside_content( $content, $insertion, $element = '<h2', 1, false, 1 );
									}
									return $content;
								},
								absint( $meta['priority'] )
							);
						break;
						case 'kadence_inside_the_content_after_h1':
							add_filter(
								'the_content',
								function( $content ) use( $post, $meta ) {
									if ( ! self::apply_in_content_filter( $post ) ) {
										ob_start();
											echo '<!-- [in-content-element-' . esc_attr( $post->ID ) . '] -->';
											echo '<div class="kadence-pro-in-content-element">';
												$this->output_element( $post, $meta );
											echo '</div>';
											echo '<!-- [/in-content-element-' . esc_attr( $post->ID ) . '] -->';
											$insertion = ob_get_clean();
											$content = $this->insert_inside_content( $content, $insertion, $element = '</h2>', 1, true, 1 );
									}
									return $content;
								},
								absint( $meta['priority'] )
							);
						break;
						case 'kadence_inside_the_content_after_p1':
							add_filter(
								'the_content',
								function( $content ) use( $post, $meta ) {
									if ( ! self::apply_in_content_filter( $post ) ) {
										ob_start();
											$this->output_element( $post, $meta );
										$insertion = ob_get_clean();
										$content = $this->insert_inside_content( $content, $insertion, $element = '</p>', 1, true, 1 );
									}
									return $content;
								},
								absint( $meta['priority'] )
							);
						break;
						case 'kadence_inside_the_content_after_p2':
							add_filter(
								'the_content',
								function( $content ) use( $post, $meta ) {
									if ( ! self::apply_in_content_filter( $post ) ) {
										ob_start();
											$this->output_element( $post, $meta );
										$insertion = ob_get_clean();
										$content = $this->insert_inside_content( $content, $insertion, $element = '</p>', 2, true, 2 );
									}
									return $content;
								},
								absint( $meta['priority'] )
							);
						break;
						case 'kadence_inside_the_content_after_p3':
							add_filter(
								'the_content',
								function( $content ) use( $post, $meta ) {
									if ( ! self::apply_in_content_filter( $post ) ) {
										ob_start();
										$this->output_element( $post, $meta );
										$insertion = ob_get_clean();
										$content = $this->insert_inside_content( $content, $insertion, $element = '</p>', 3, true, 3 );
									}
									return $content;
								},
								absint( $meta['priority'] )
							);
						break;
						case 'kadence_inside_the_content_after_p4':
							add_filter(
								'the_content',
								function( $content ) use( $post, $meta ) {
									if ( ! self::apply_in_content_filter( $post ) ) {
										ob_start();
											$this->output_element( $post, $meta );
										$insertion = ob_get_clean();
										$content = $this->insert_inside_content( $content, $insertion, $element = '</p>', 4, true, 4 );
									}
									return $content;
								},
								absint( $meta['priority'] )
							);
						break;
						default:
						# code...
						break;
					}
					$this->enqueue_element_styles( $post, $meta );
				} elseif ( isset( $meta['hook'] ) && 'fixed' === substr( $meta['hook'], 0, 5 ) ) {
					switch ( $meta['hook'] ) {
						case 'fixed_above_trans_header':
							add_action(
								'kadence_before_wrapper',
								function() use( $post, $meta ) {
									wp_enqueue_style( 'kadence-pro-sticky' );
									wp_enqueue_script( 'kadence-pro-sticky' );
									echo '<!-- [fixed-element-' . esc_attr( $post->ID ) . '] -->';
									echo '<div class="kadence-pro-fixed-wrap">';
										echo '<div class="kadence-pro-fixed-item kadence-pro-fixed-header-item kadence-pro-fixed-above-trans">';
										$this->output_element( $post, $meta );
										echo '</div>';
									echo '</div>';
									echo '<!-- [/fixed-element-' . esc_attr( $post->ID ) . '] -->';
								},
								absint( $meta['priority'] )
							);
							break;
						case 'fixed_above_header':
							add_action(
								'kadence_before_header',
								function() use( $post, $meta ) {
									wp_enqueue_style( 'kadence-pro-sticky' );
									wp_enqueue_script( 'kadence-pro-sticky' );
									echo '<!-- [fixed-element-' . esc_attr( $post->ID ) . '] -->';
									echo '<div class="kadence-pro-fixed-wrap">';
										echo '<div class="kadence-pro-fixed-item kadence-pro-fixed-header-item kadence-pro-fixed-above">';
										$this->output_element( $post, $meta );
										echo '</div>';
									echo '</div>';
									echo '<!-- [/fixed-element-' . esc_attr( $post->ID ) . '] -->';
								},
								absint( $meta['priority'] )
							);
							break;
						case 'fixed_on_header':
							add_action(
								'kadence_after_wrapper',
								function() use( $post, $meta ) {
									wp_enqueue_style( 'kadence-pro-sticky' );
									wp_enqueue_script( 'kadence-pro-sticky' );
									$fixed_width = ( isset( $meta['fixed_width'] ) && '' !== $meta['fixed_width'] ? $meta['fixed_width'] : '' );
									$position = ( isset( $meta['fixed_position'] ) && '' !== $meta['fixed_position'] ? $meta['fixed_position'] : 'left' );
									$xposition = ( isset( $meta['xposition'] ) && '' !== $meta['xposition'] ? absint( $meta['xposition'] ) : 0 );
									$yposition = ( isset( $meta['yposition'] ) && '' !== $meta['yposition'] ? absint( $meta['yposition'] ) : 0 );
									$width = ( isset( $meta['width'] ) && '' !== $meta['width'] ? absint( $meta['width'] ) : 300 );
									$added_classes = '';
									if ( '' != $fixed_width ) {
										$added_classes = 'kadence-fixed-width-' . $fixed_width . ' kadence-fixed-xposition-' . $position;
									}
									$offset = ( isset( $meta['scroll'] ) && '' !== $meta['scroll'] ? absint( $meta['scroll'] ) : 300 );
									echo '<!-- [fixed-element-' . esc_attr( $post->ID ) . '] -->';
									echo '<div class="kadence-pro-fixed-item kadence-pro-fixed-header-item kadence-pro-fixed-on-scroll item-at-start' . esc_attr( $added_classes ? ' ' . $added_classes : '' ) . '" style="' . ( '' != $fixed_width ? $position . ':' . $xposition . 'px;' : '' ) . ( '' != $fixed_width ? 'transform:translateY(' . $yposition . 'px);' : '' ) . ( 'fixed' === $fixed_width ? 'max-width:' . $width . 'px;' : '' ) . '" data-scroll-offset="'. esc_attr( $offset ) . '">';
									$this->output_element( $post, $meta );
									echo '</div>';
									echo '<!-- [/fixed-element-' . esc_attr( $post->ID ) . '] -->';
								},
								absint( $meta['priority'] )
							);
							break;
						case 'fixed_below_footer':
							add_action(
								'kadence_after_footer',
								function() use( $post, $meta ) {
									wp_enqueue_style( 'kadence-pro-sticky' );
									wp_enqueue_script( 'kadence-pro-sticky' );
									echo '<!-- [fixed-element-' . esc_attr( $post->ID ) . '] -->';
									echo '<div class="kadence-pro-fixed-wrap">';
										echo '<div class="kadence-pro-fixed-item kadence-pro-fixed-footer-item kadence-pro-fixed-below">';
										$this->output_element( $post, $meta );
										echo '</div>';
									echo '</div>';
									echo '<!-- [/fixed-element-' . esc_attr( $post->ID ) . '] -->';
								},
								absint( $meta['priority'] )
							);
							break;
						case 'fixed_on_footer':
							add_action(
								'kadence_after_wrapper',
								function() use( $post, $meta ) {
									wp_enqueue_style( 'kadence-pro-sticky' );
									wp_enqueue_script( 'kadence-pro-sticky' );
									$fixed_width = ( isset( $meta['fixed_width'] ) && '' !== $meta['fixed_width'] ? $meta['fixed_width'] : '' );
									$position = ( isset( $meta['fixed_position'] ) && '' !== $meta['fixed_position'] ? $meta['fixed_position'] : 'left' );
									$xposition = ( isset( $meta['xposition'] ) && '' !== $meta['xposition'] ? absint( $meta['xposition'] ) : 0 );
									$yposition = ( isset( $meta['yposition'] ) && '' !== $meta['yposition'] ? absint( $meta['yposition'] ) : 0 );
									$width = ( isset( $meta['width'] ) && '' !== $meta['width'] ? absint( $meta['width'] ) : 300 );
									$added_classes = '';
									if ( '' != $fixed_width ) {
										$added_classes = 'kadence-fixed-width-' . $fixed_width . ' kadence-fixed-xposition-' . $position;
									}
									echo '<!-- [fixed-element-' . esc_attr( $post->ID ) . '] -->';
									echo '<div class="kadence-pro-fixed-item kadence-pro-fixed-footer-item kadence-pro-fixed-bottom' . esc_attr( $added_classes ? ' ' . $added_classes : '' ) . '" style="' . ( '' != $fixed_width ? $position . ':' . $xposition . 'px;' : '' ) . ( '' != $fixed_width ? 'bottom:' . $yposition . 'px;' : '' ) . ( 'fixed' === $fixed_width ? 'max-width:' . $width . 'px;' : '' ) . '">';
									$this->output_element( $post, $meta );
									echo '</div>';
									echo '<!-- [/fixed-element-' . esc_attr( $post->ID ) . '] -->';
								},
								absint( $meta['priority'] )
							);
							break;
						case 'fixed_on_footer_scroll':
							add_action(
								'kadence_after_wrapper',
								function() use( $post, $meta ) {
									wp_enqueue_style( 'kadence-pro-sticky' );
									wp_enqueue_script( 'kadence-pro-sticky' );
									echo '<!-- [fixed-element-' . esc_attr( $post->ID ) . '] -->';
									$fixed_width = ( isset( $meta['fixed_width'] ) && '' !== $meta['fixed_width'] ? $meta['fixed_width'] : '' );
									$position = ( isset( $meta['fixed_position'] ) && '' !== $meta['fixed_position'] ? $meta['fixed_position'] : 'left' );
									$xposition = ( isset( $meta['xposition'] ) && '' !== $meta['xposition'] ? absint( $meta['xposition'] ) : 0 );
									$yposition = ( isset( $meta['yposition'] ) && '' !== $meta['yposition'] ? absint( $meta['yposition'] ) : 0 );
									$width = ( isset( $meta['width'] ) && '' !== $meta['width'] ? absint( $meta['width'] ) : 300 );
									$added_classes = '';
									if ( '' != $fixed_width ) {
										$added_classes = 'kadence-fixed-width-' . $fixed_width . ' kadence-fixed-xposition-' . $position;
									}
									$offset = ( isset( $meta['scroll'] ) && '' !== $meta['scroll'] ? absint( $meta['scroll'] ) : 300 );
									echo '<div class="kadence-pro-fixed-item kadence-pro-fixed-footer-item kadence-pro-fixed-on-scroll-footer kadence-pro-fixed-bottom-scroll item-at-start' . esc_attr( $added_classes ? ' ' . $added_classes : '' ) . '" style="' . ( '' != $fixed_width ? $position . ':' . $xposition . 'px;' : '' ) . ( '' != $fixed_width ? 'bottom:' . $yposition . 'px;' : '' ) . ( 'fixed' === $fixed_width ? 'max-width:' . $width . 'px;' : '' ) . '" data-scroll-offset="'. esc_attr( $offset ) . '">';
									$this->output_element( $post, $meta );
									echo '</div>';
									echo '<!-- [/fixed-element-' . esc_attr( $post->ID ) . '] -->';
								},
								absint( $meta['priority'] )
							);
							break;
						case 'fixed_on_footer_scroll_space':
							add_action(
								'kadence_after_wrapper',
								function() use( $post, $meta ) {
									wp_enqueue_style( 'kadence-pro-sticky' );
									wp_enqueue_script( 'kadence-pro-sticky' );
									echo '<!-- [fixed-element-' . esc_attr( $post->ID ) . '] -->';
									echo '<div class="kadence-pro-fixed-wrap">';
									$offset = ( isset( $meta['scroll'] ) && '' !== $meta['scroll'] ? absint( $meta['scroll'] ) : 300 );
									echo '<div class="kadence-pro-fixed-item kadence-pro-fixed-footer-item kadence-pro-fixed-on-scroll-footer-space kadence-pro-fixed-bottom-scroll item-at-start" data-scroll-offset="'. esc_attr( $offset ) . '">';
									$this->output_element( $post, $meta );
									echo '</div>';
									echo '</div>';
									echo '<!-- [/fixed-element-' . esc_attr( $post->ID ) . '] -->';
								},
								absint( $meta['priority'] )
							);
							break;
						default:
							# code...
							break;
					}
					$this->enqueue_element_styles( $post, $meta );
				}
			}
		}
	}
	/**
	 * Find the calls to `the_content` inside functions hooked to `the_content`.
	 *
	 * @return bool
	 */
	public function has_many_the_content() {
		global $wp_current_filter;
		if ( count( array_keys( $wp_current_filter, 'the_content', true ) ) > 1 ) {
			// More then one `the_content` in the stack.
			return true;
		}
		return false;
	}
	/**
	 * Determines if the in content filters should run.
	 *
	 * @param string $insertion the element content.
	 * @param integer $paragraph_id the paragraph id.
	 * @param string $content the post content.
	 */
	public function insert_inside_content( $content, $insertion = null, $element = '</h2>', $placement_id = 1, $after_element = true, $min_elements = 1 ) {
		if ( doing_filter( 'get_the_excerpt' ) ) {
			return $content;
		}
		// Do not inject on admin pages.
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return $content;
		}
		// do not inject elements multiple times, e.g., when the_content is applied multiple times.
		if ( $this->has_many_the_content() ) {
			return $content;
		}
		// make sure that no element is injected into another.
		if ( get_post_type() === self::SLUG ) {
			return $content;
		}
		if ( ! extension_loaded( 'dom' ) || apply_filters( 'kadence_pro_inject_content_simple_method', false ) ) {

			// Split content.
			$parts = explode( $element, $content );

			// Count element ocurrencies.
			$count = count( $parts );

			// check if the minimum required elements are found.
			if ( ( $count - 1 ) < $min_elements ) {
				return $content;
			}

			$output = '';
			for ( $i = 1; $i < $count; $i++ ) {
				// this is the core part that puts all the content together.
				if ( $after_element ) {
					$output .= $parts[ $i - 1 ] . $element . ( ( $i === $placement_id ) ? $insertion : '' ); // this insert after.
				} else {
					$output .= ( $i === 1 ? $parts[ 0 ] : '' ) . ( ( $i === $placement_id ) ? $insertion : '' ) . $element . $parts[ $i ]; //this insert before.
				}
			}
		} else {
			$wp_charset = get_bloginfo( 'charset' );
			$element_placeholder_data = false;
			$content_dom = new DOMDocument( '1.0', $wp_charset );
			libxml_use_internal_errors( true ); // avoid notices and warnings - html is most likely malformed.
			// Prevent removing closing tags in scripts.
			$content_to_load = preg_replace( '/<script.*?<\/script>/si', '<!--\0-->', $content );
			$content_to_load = mb_convert_encoding( $content_to_load, 'HTML-ENTITIES', 'UTF-8' );
			$success = $content_dom->loadHTML(
				// loadHTML expects ISO-8859-1, so we need to convert the post content to
				// that format. We use htmlentities to encode Unicode characters not
				// supported by ISO-8859-1 as HTML entities. However, this function also
				// converts all special characters like < or > to HTML entities, so we use
				// htmlspecialchars_decode to decode them.
				htmlspecialchars_decode(
					utf8_decode(
						htmlentities(
							'<!DOCTYPE html><html><head><body>' .
							$content_to_load .
								'</body></html>',
							ENT_COMPAT,
							'UTF-8',
							false
						)
					),
					ENT_COMPAT
				)
			);
			libxml_use_internal_errors( false );
			if ( true !== $success ) {
				// -TODO handle cases were dom-parsing failed (at least inform user)
				return $content;
			}
			$tag = preg_replace( '/[^a-z0-9]/i', '', $element ); // simplify tag.
			$tag_option = $tag;
			switch ( $tag_option ) {
				case 'p':
					// exclude paragraphs within blockquote tags.
					$tag = 'p[not(parent::blockquote)]';
				break;
				case 'h1':
				case 'h2':
				case 'h3':
				case 'h4':
				case 'h5':
				case 'h6':
					$headlines = apply_filters( 'kadence-headlines-for-element-in-content', array( 'h1', 'h2', 'h3', 'h4' ) );
					foreach ( $headlines as &$headline ) {
						$headline = 'self::' . $headline;
					}
					$tag = '*[' . implode( ' or ', $headlines ) . ']'; // /html/body/*[self::h1 or self::h2 or self::h3]
				break;
			}

			// select positions.
			$xpath = new \DOMXPath( $content_dom );
			$items = $xpath->query( '/html/body/' . $tag );
			if ( $items->length < $min_elements ) {
				$items = $xpath->query( '/html/body/*/' . $tag );
			}
			// try third level.
			if ( $items->length < $min_elements ) {
				$items = $xpath->query( '/html/body/*/*/' . $tag );
			}
			// try all levels as last resort.
			if ( $items->length < $min_elements ) {
				$items = $xpath->query( '//' . $tag );
			}
			$processed_items  = array();
			foreach ( $items as $item ) {
				$processed_items[] = $item;
			}
			// Count element ocurrencies.
			$count = count( $processed_items );
			// check if the minimum required elements are found.
			if ( ( $count ) < $min_elements ) {
				return $content;
			}
			$did_inject = false;
			$loop_through = array( $placement_id - 1 );
			foreach ( $loop_through as $loop_item ) {
				$node = $processed_items[ $loop_item ];
				// Prevent injection into image caption and gallery.
				$parent = $node;
				for ( $i = 0; $i < 4; $i++ ) {
					$parent = $parent->parentNode;
					if ( ! $parent instanceof DOMElement ) {
						break;
					}
					if ( preg_match( '/\b(wp-caption|gallery-size)\b/', $parent->getAttribute( 'class' ) ) ) {
						$node = $parent;
						break;
					}
				}
				// make sure that the ad is injected outside the link
				if ( 'img' === $tag_option && 'a' === $node->parentNode->tagName ) {
					if ( $options['before'] ) {
						$node->parentNode;
					} else {
						// go one level deeper if inserted after to not insert the ad into the link; probably after the paragraph
						$node->parentNode->parentNode;
					}
				}

				// convert HTML to XML!
				$element_placeholder_data = array(
					'tag'     => $node->tagName,
					'after'   => $after_element,
					'content' => $insertion,
				);
				$insertion = '%elements_placeholder%';
				$insert_dom = new DOMDocument( '1.0', $wp_charset );
				libxml_use_internal_errors( true );
				$insert_dom->loadHtml( '<!DOCTYPE html><html><meta http-equiv="Content-Type" content="text/html; charset=' . $wp_charset . '" /><body>' . $insertion );
				if ( ! $after_element ) {
					$ref_node = $node;

					foreach ( $insert_dom->getElementsByTagName( 'body' )->item( 0 )->childNodes as $importedNode ) {
						$importedNode = $content_dom->importNode( $importedNode, true );
						$ref_node->parentNode->insertBefore( $importedNode, $ref_node );
					}
				} else {
					// append before next node or as last child to body.
					$ref_node = $node->nextSibling;
					if ( isset( $ref_node ) ) {
						foreach ( $insert_dom->getElementsByTagName( 'body' )->item( 0 )->childNodes as $importedNode ) {
							$importedNode = $content_dom->importNode( $importedNode, true );
							$ref_node->parentNode->insertBefore( $importedNode, $ref_node );
						}
					} else {
						// append to body; -TODO using here that we only select direct children of the body tag.
						foreach ( $insert_dom->getElementsByTagName( 'body' )->item( 0 )->childNodes as $importedNode ) {
							$importedNode = $content_dom->importNode( $importedNode, true );
							$node->parentNode->appendChild( $importedNode );
						}
					}
				}
				libxml_use_internal_errors( false );
				$did_inject = true;
			}
			if ( ! $did_inject ) {
				return $content;
			}
			// convert to text-representation.
			$output = $content_dom->saveHTML();
			if ( ! $output ) {
				return $content;
			}
			return self::inject_into_content( $output, $content, $element_placeholder_data );
		}
		return $output;
	}
	/**
	 * Filter ad content.
	 *
	 * @param string $ad_content Ad content.
	 * @param string $tag_name tar before/after the content.
	 * @param array  $options Injection options.
	 *
	 * @return string ad content.
	 */
	private static function filter_element_for_content( $element_content, $tag_name, $after_element ) {
		// Inject placeholder.
		$id                           = count( self::$placeholders_for_elements );
		self::$placeholders_for_elements[] = array(
			'id'      => $id,
			'tag'     => $tag_name,
			'after'   => $after_element,
			'content' => $element_content,
		);
		$element_content                   = '%elements_placeholder_' . $id . '%';

		return $element_content;
	}
	/**
	 * Search for ad placeholders in the `$content` to determine positions at which to inject ads.
	 * Given the positions, inject ads into `$content_orig.
	 *
	 * @param string $content Post content with injected ad placeholders.
	 * @param string $content_orig Unmodified post content.
	 * @param array  $options Injection options.
	 * @param array  $ads_for_placeholders Array of ads.
	 *  Each ad contains placeholder id, before or after which tag to inject the ad, the ad content.
	 *
	 * @return string $content
	 */
	private static function inject_into_content( $content, $content_orig, $element_placeholder_data ) {
		$tag = $element_placeholder_data['tag'];
		if ( ! $element_placeholder_data['after'] ) {
			$alts[] = "<${tag}[^>]*>";
		} else {
			$alts[] = "</${tag}>";
		}
		$tag_regexp = implode( '|', $alts );
		$alts[] = '%elements_placeholder%';
		$tag_and_placeholder_regexp = implode( '|', $alts );
		preg_match_all( "#{$tag_and_placeholder_regexp}#i", $content, $tag_matches );

		$count = 0;

		// For each tag located before/after an ad placeholder, find its offset among the same tags.
		foreach ( $tag_matches[0] as $r ) {
			if ( preg_match( '/%elements_placeholder%/', $r, $result ) ) {
				if ( ! $element_placeholder_data['after'] ) {
					$element_placeholder_data['offset'] = $count;
				} else {
					$element_placeholder_data['offset'] = $count - 1;
				}
			} else {
				$count ++;
			}
		}

		// Find tags before/after which we need to inject ads.
		preg_match_all( "#{$tag_regexp}#i", $content_orig, $orig_tag_matches, PREG_OFFSET_CAPTURE );
		$new_content = '';
		$pos         = 0;

		foreach ( $orig_tag_matches[0] as $n => $r ) {
			if ( isset( $element_placeholder_data['offset'] ) && $element_placeholder_data['offset'] === $n ) {
				if ( ! $element_placeholder_data['after'] ) {
					$found_pos = $r[1];
				} else {
					$found_pos = $r[1] + strlen( $r[0] );
				}
				$new_content .= substr( $content_orig, $pos, $found_pos - $pos );
				$pos          = $found_pos;
				$new_content .= $element_placeholder_data['content'];
			}
		}
		$new_content .= substr( $content_orig, $pos );

		return $new_content;
	}
	/**
	 * Adds content to the $content based on the paragraph count.
	 *
	 * @param string $insertion the element content.
	 * @param integer $paragraph_id the paragraph id.
	 * @param string $content the post content.
	 */
	public function insert_after_paragraph( $insertion, $paragraph_id, $content ) {
		$closing_p  = '</p>';
		$paragraphs = explode( $closing_p, $content );
		foreach ( $paragraphs as $index => $paragraph ) {

			if ( trim( $paragraph ) ) {
				$paragraphs[ $index ] .= $closing_p;
			}
			if ( $paragraph_id == $index + 1 ) {
				$paragraphs[ $index ] .= $insertion;
			}
		}
		return implode( '', $paragraphs );
	}
	/**
	 * Determines if the in content filters should run.
	 *
	 * @param object $post the element post.
	 */
	public static function apply_in_content_filter( $post ) {
		$run = true;
		if ( is_admin() ) {
			return false;
		}
		global $wp_current_filter;
		if ( is_array( $wp_current_filter ) && in_array( $wp_current_filter[0], array( 'get_the_excerpt', 'init', 'wp_head' ), true ) ) {
			$run = false;
		}
		if ( is_feed() || is_search() || is_archive() ) {
			$run = false;
		}
		if ( empty( $post ) || ! $post instanceof WP_Post ) {
			$run = false;
		}
		return apply_filters( 'kadence_pro_run_in_the_content_filter', $run );
	}
	/**
	 * Outputs the custom before wrap element.
	 */
	public function product_image_before_wrap() {
		echo '<div class="kadence-product-image-wrap images">';
	}
	/**
	 * Outputs the custom before wrap element.
	 */
	public function product_image_after_wrap() {
		echo '</div>';
	}
	/**
	 * Outputs the content of the element.
	 *
	 * @param object $post the post object.
	 * @param array  $meta the post meta.
	 */
	public function output_element_template( $post, $meta ) {
		$content = $post->post_content;
		if ( ! $content ) {
			return;
		}
		// $filter_block_context = function( $context ) {
		// 	$context['postId'] = get_the_ID();
		// 	return $context;
		// };
		//add_filter( 'kadence_blocks_render_head_css', $filter_block_context, 10, 3 );
		//add_filter( 'render_block_context', $filter_block_context );
		$content = apply_filters( 'ktp_the_content', $content );
		//remove_filter( 'render_block_context', $filter_block_context );
		if ( $content ) {
			echo '<!-- [element-' . esc_attr( $post->ID ) . '] -->';
			echo $content;
			echo '<!-- [/element-' . esc_attr( $post->ID ) . '] -->';
		}
	}
	/**
	 * Outputs the content of the element.
	 *
	 * @param object $post the post object.
	 * @param array  $meta the post meta.
	 * @param bool   $shortcode if the render is from a shortcode.
	 */
	public function output_element( $post, $meta, $shortcode = false ) {
		$content = $post->post_content;
		if ( ! $content && ! class_exists( 'Elementor\Plugin' ) ) {
			return;
		}
		if ( isset( $meta['device'] ) && ! empty( $meta['device'] ) && is_array( $meta['device'] ) ) {
			$element_device_classes = array( 'kadence-element-wrap' );
			$devices = array();
			foreach ( $meta['device'] as $key => $setting ) {
				$devices[] = $setting['value'];
			}
			if ( ! in_array( 'desktop', $devices ) ) {
				$element_device_classes[] = 'vs-lg-false';
			}
			if ( ! in_array( 'tablet', $devices ) ) {
				$element_device_classes[] = 'vs-md-false';
			}
			if ( ! in_array( 'mobile', $devices ) ) {
				$element_device_classes[] = 'vs-sm-false';
			}
			echo '<div class="' . esc_attr( implode( " ", $element_device_classes ) ) . '">';
		}
		// if ( has_blocks( $content ) ) {
		// 	echo apply_filters( 'ktp_the_content', $content );
		// 	if ( isset( $meta['device'] ) && ! empty( $meta['device'] ) && is_array( $meta['device'] ) ) {
		// 		echo '</div>';
		// 	}
		// 	return;
		// }
		if ( isset( $meta['type'] ) && ! empty( $meta['type'] ) && 'script' === $meta['type'] ) {
			echo apply_filters( 'ktp_code_the_content', $content );
			if ( isset( $meta['device'] ) && ! empty( $meta['device'] ) && is_array( $meta['device'] ) ) {
				echo '</div>';
			}
			return;
		}
		$builder_info = $this->check_for_pagebuilder( $post );
		switch ( $builder_info ) {
			case 'elementor':
				$content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $post->ID );
				break;
			case 'beaver':
				ob_start();
				FLBuilder::render_query(
					array(
						'post_type' => self::SLUG,
						'p'         => $post->ID,
					)
				);
				$content = ob_get_clean();
				break;
			case 'brizy':
				$brizy = Brizy_Editor_Post::get( $post->ID );
				$html  = new Brizy_Editor_CompiledHtml( $brizy->get_compiled_html() );
				// the <head> content
				// the $headHtml contains all the assets the page needs
				$scripts = apply_filters( 'brizy_content', $html->get_head(), Brizy_Editor_Project::get(), $brizy->getWpPost() );
				// the <body> content
				$content = apply_filters( 'brizy_content', $html->get_body(), Brizy_Editor_Project::get(), $brizy->getWpPost() );
				break;
			case 'panels':
				$content = siteorigin_panels_render( $post->ID );
				break;
			default:
				$content = apply_filters( 'ktp_the_content', $content );
				break;
		}
		if ( isset( $scripts ) && ! empty( $scripts ) ) {
			echo '<!-- [element-script-' . esc_attr( $post->ID ) . '] -->';
			echo $scripts;
			echo '<!-- [/element-script-' . esc_attr( $post->ID ) . '] -->';
		}
		if ( $content ) {
			echo '<!-- [element-' . esc_attr( $post->ID ) . '] -->';
			echo $content;
			echo '<!-- [/element-' . esc_attr( $post->ID ) . '] -->';
		}
		if ( isset( $meta['device'] ) && ! empty( $meta['device'] ) && is_array( $meta['device'] ) ) {
			echo '</div>';
		}
	}
	/**
	 * Outputs the content of the element.
	 *
	 * @param object $post the post object.
	 * @param array  $meta the post meta.
	 * @param bool   $shortcode if the render is from a shortcode.
	 */
	public function enqueue_element_styles( $post, $meta, $shortcode = false ) {

		$content = $post->post_content;
		if ( ! $content ) {
			return;
		}
		$css_output = get_post_meta( $post->ID, '_kad_blocks_custom_css', true );
		$js_output = get_post_meta( $post->ID, '_kad_blocks_head_custom_js', true );
		if ( ! empty( $css_output ) || ! empty( $js_output ) ) {
			add_action(
				'wp_head',
				function() use( $post, $css_output, $js_output ) {
					if ( ! empty( $css_output ) ) {
						echo '<style id="kadence-blocks-post-custom-css-' . $post->ID . '">';
						echo $css_output;
						echo '</style>';
					}
					if ( ! empty( $js_output ) ) {
						echo $js_output;
					}
				}, 
				30
			);
		}
		$js_body_output = get_post_meta( $post->ID, '_kad_blocks_body_custom_js', true );
		if ( ! empty( $js_body_output ) ) {
			add_action(
				'wp_body_open',
				function() use( $js_body_output ) {
					echo $js_body_output;
				}, 
				10
			);
		}
		$js_footer_output = get_post_meta( $post->ID, '_kad_blocks_footer_custom_js', true );
		if ( ! empty( $js_footer_output ) ) {
			add_action(
				'wp_footer',
				function() use( $js_footer_output ) {
					echo $js_footer_output;
				}, 
				20
			);
		}
		
		if ( has_blocks( $content ) ) {
			if ( class_exists( 'Kadence_Blocks_Frontend' ) ) {
				$kadence_blocks = \Kadence_Blocks_Frontend::get_instance();
				if ( method_exists( $kadence_blocks, 'frontend_build_css' ) ) {
					$kadence_blocks->frontend_build_css( $post );
				}
				if ( class_exists( 'Kadence_Blocks_Pro_Frontend' ) ) {
					$kadence_blocks_pro = \Kadence_Blocks_Pro_Frontend::get_instance();
					if ( method_exists( $kadence_blocks_pro, 'frontend_build_css' ) ) {
						$kadence_blocks_pro->frontend_build_css( $post );
					}
				}
			}
			return;
		}

		$builder_info = $this->check_for_pagebuilder( $post );
		$post_id      = $post->ID;
		/**
		 * Get block scripts based on its editor/builder.
		 */
		switch ( $builder_info ) {
			case 'elementor':
				add_action( 'wp_enqueue_scripts', function() use ( $post_id ) {
					if ( class_exists( '\Elementor\Plugin' ) ) {

						$elementor = \Elementor\Plugin::instance();
						$elementor->frontend->enqueue_styles();

						if ( class_exists( '\ElementorPro\Plugin' ) ) {
							$elementor_pro = \ElementorPro\Plugin::instance();
							$elementor_pro->enqueue_styles();
						}
						if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
							$css_file = new \Elementor\Core\Files\CSS\Post( $post_id );
							$css_file->enqueue();
						}
					}
				} );
				break;
			case 'brizy':
				$brizy_element = \Brizy_Editor_Post::get( $post_id );
				if ( method_exists( '\Brizy_Public_Main', 'get' ) ) {
					$brizy_class = \Brizy_Public_Main::get( $brizy_element );
				} else {
					$brizy_class = new \Brizy_Public_Main( $brizy_element );
				}

				// Enqueue general Brizy scripts.
				add_filter( 'body_class', array( $brizy_class, 'body_class_frontend' ) );
				add_action( 'wp_enqueue_scripts', array( $brizy_class, '_action_enqueue_preview_assets' ), 999 );
				// Enqueue current page scripts.
				add_action( 'wp_head', function() use ( $brizy_element ) {
					$brizy_project = \Brizy_Editor_Project::get();
					$brizy_html    = new \Brizy_Editor_CompiledHtml( $brizy_element->get_compiled_html() );

					echo apply_filters( 'brizy_content', $brizy_html->get_head(), $brizy_project, $brizy_element->get_wp_post() );
				} );
				break;
		}
	}
	/**
	 * Check if page is built with elementor.
	 *
	 * @param object $post the post object.
	 */
	public function check_for_pagebuilder( $post ) {
		$builder = 'default';
		if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::instance()->db->is_built_with_elementor( $post->ID ) ) {
			// Element is built with elementor.
			$builder = 'elementor';
		} elseif ( class_exists( 'Brizy_Editor_Post' ) && class_exists( 'Brizy_Editor' ) ) {
			$supported_post_types   = Brizy_Editor::get()->supported_post_types();
			if ( in_array( self::SLUG, $supported_post_types ) ) {
				if ( Brizy_Editor_Post::get( $post->ID )->uses_editor() ) {
					// Element is built with brizy.
					$builder = 'brizy';
				}
			}
		} elseif ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_enabled( $post->ID ) ) {
			// Element is built with beaver.
			$builder = 'beaver';
		} elseif ( class_exists( 'SiteOrigin_Panels_Settings' ) && siteorigin_panels_render( $post->ID ) ) {
			// Element is built with SiteOrigin.
			$builder = 'panels';
		}
		return $builder;
	}
	/**
	 * Gets and returns page conditions.
	 */
	public static function get_current_page_conditions() {
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
				if ( is_post_type_archive() && is_object( $queried_obj ) && ! is_tax() ) {
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
	 * Check if element should show in current page.
	 *
	 * @param object $post the current element to check.
	 * @return bool
	 */
	public function check_element_conditionals( $post, $meta ) {
		$current_condition      = self::get_current_page_conditions();
		$rules_with_sub_rules   = array( 'singular', 'tax_archive' );
		$show = false;
		$all_must_be_true = ( isset( $meta ) && isset( $meta['all_show'] ) ? $meta['all_show'] : false );
		if ( isset( $meta ) && isset( $meta['show'] ) && is_array( $meta['show'] ) && ! empty( $meta['show'] ) ) {
			foreach ( $meta['show'] as $key => $rule ) {
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
		// Exclude Rules.
		if ( $show ) {
			if ( isset( $meta ) && isset( $meta['hide'] ) && is_array( $meta['hide'] ) && ! empty( $meta['hide'] ) ) {
				foreach ( $meta['hide'] as $key => $rule ) {
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
		if ( $show ) {
			if ( isset( $meta ) && isset( $meta['user'] ) && is_array( $meta['user'] ) && ! empty( $meta['user'] ) ) {
				$user_info  = self::get_current_user_info();
				$show_roles = array();
				foreach ( $meta['user'] as $key => $user_rule ) {
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
		if ( $show ) {
			if ( isset( $meta ) && isset( $meta['enable_expires'] ) && true == $meta['enable_expires'] && isset( $meta['expires'] ) && ! empty( $meta['expires'] ) ) {
				$expires = strtotime( get_date_from_gmt( $meta['expires'] ) );
				$now     = strtotime( get_date_from_gmt( current_time( 'Y-m-d H:i:s' ) ) );
				if ( $expires < $now ) {
					$show = false;
				}
			}
		}
		// Language.
		if ( $show ) {
			if ( ! empty( $meta['language'] ) ) {
				if ( function_exists( 'pll_current_language' ) ) {
					$language_slug = pll_current_language( 'slug' );
					if ( $meta['language'] !== $language_slug ) {
						$show = false;
					}
				}
				if ( $current_lang = apply_filters( 'wpml_current_language', NULL ) ) {
					if ( $meta['language'] !== $current_lang ) {
						$show = false;
					}
				}
			}
		}
		return $show;
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
	 * Get an array of post meta.
	 *
	 * @param object $post the current element to check.
	 * @return array
	 */
	public function get_post_meta_array( $post ) {
		$meta = array(
			'hook'           => '',
			'custom'         => '',
			'priority'       => '',
			'scroll'         => '300',
			'show'           => array(),
			'all_show'       => false,
			'hide'           => array(),
			'user'           => array(),
			'device'         => array(),
			'enable_expires' => false,
			'expires'        => '',
			'type'           => '',
			'fixed_width'    => '',
			'width'          => 300,
			'fixed_position' => 'left',
			'xposition'      => 0,
			'yposition'      => 0,
			'language'       => '',
		);
		if ( get_post_meta( $post->ID, '_kad_element_type', true ) ) {
			$meta['type'] = get_post_meta( $post->ID, '_kad_element_type', true );
		}
		if ( get_post_meta( $post->ID, '_kad_element_hook', true ) ) {
			$meta['hook'] = get_post_meta( $post->ID, '_kad_element_hook', true );
			if ( 'custom' === $meta['hook'] ) {
				if ( get_post_meta( $post->ID, '_kad_element_hook_custom', true ) ) {
					$meta['custom'] = get_post_meta( $post->ID, '_kad_element_hook_custom', true );
				}
			}
		}
		if ( get_post_meta( $post->ID, '_kad_element_hook_priority', true ) ) {
			$meta['priority'] = get_post_meta( $post->ID, '_kad_element_hook_priority', true );
		}
		if ( '' !== get_post_meta( $post->ID, '_kad_element_hook_scroll', true ) ) {
			$meta['scroll'] = get_post_meta( $post->ID, '_kad_element_hook_scroll', true );
		}
		if ( get_post_meta( $post->ID, '_kad_element_show_conditionals', true ) ) {
			$meta['show'] = json_decode( get_post_meta( $post->ID, '_kad_element_show_conditionals', true ), true );
		}
		if ( get_post_meta( $post->ID, '_kad_element_all_show', true ) ) {
			$meta['all_show'] = boolval( get_post_meta( $post->ID, '_kad_element_all_show', true ) );
		}
		if ( get_post_meta( $post->ID, '_kad_element_hide_conditionals', true ) ) {
			$meta['hide'] = json_decode( get_post_meta( $post->ID, '_kad_element_hide_conditionals', true ), true );
		}
		if ( get_post_meta( $post->ID, '_kad_element_user_conditionals', true ) ) {
			$meta['user'] = json_decode( get_post_meta( $post->ID, '_kad_element_user_conditionals', true ), true );
		}
		if ( get_post_meta( $post->ID, '_kad_element_device_conditionals', true ) ) {
			$meta['device'] = json_decode( get_post_meta( $post->ID, '_kad_element_device_conditionals', true ), true );
		}
		if ( get_post_meta( $post->ID, '_kad_element_enable_expires', true ) ) {
			$meta['enable_expires'] = get_post_meta( $post->ID, '_kad_element_enable_expires', true );
		}
		if ( get_post_meta( $post->ID, '_kad_element_expires', true ) ) {
			$meta['expires'] = get_post_meta( $post->ID, '_kad_element_expires', true );
		}
		if ( get_post_meta( $post->ID, '_kad_element_language', true ) ) {
			$meta['language'] = get_post_meta( $post->ID, '_kad_element_language', true );
		}
		if ( get_post_meta( $post->ID, '_kad_element_fixed_width', true ) ) {
			$meta['fixed_width'] = get_post_meta( $post->ID, '_kad_element_fixed_width', true );
		}
		if ( isset( $meta['fixed_width'] ) && '' !== $meta['fixed_width'] ) {
			if ( get_post_meta( $post->ID, '_kad_element_width', true ) ) {
				$meta['width'] = get_post_meta( $post->ID, '_kad_element_width', true );
			}
			if ( get_post_meta( $post->ID, '_kad_element_fixed_position', true ) ) {
				$meta['fixed_position'] = get_post_meta( $post->ID, '_kad_element_fixed_position', true );
			}
			if ( get_post_meta( $post->ID, '_kad_element_xposition', true ) ) {
				$meta['xposition'] = get_post_meta( $post->ID, '_kad_element_xposition', true );
			}
			if ( get_post_meta( $post->ID, '_kad_element_yposition', true ) ) {
				$meta['yposition'] = get_post_meta( $post->ID, '_kad_element_yposition', true );
			}
		}
		return $meta;
	}
	/**
	 * Enqueue Script for Meta options
	 */
	public function script_enqueue() {
		$post_type = get_post_type();
		if ( self::SLUG !== $post_type ) {
			return;
		}
		$path = KTP_URL . 'build/';
		wp_enqueue_style( 'kadence-element-meta', KTP_URL . 'dist/build/meta-controls.css', false, KTP_VERSION );
		wp_enqueue_script( 'kadence-element-meta' );
		if ( get_post_meta( get_the_ID(), '_kad_element_preview_post', true ) ) {
			$the_post_id = get_post_meta( get_the_ID(), '_kad_element_preview_post', true );
			$the_post_type = get_post_meta( get_the_ID(), '_kad_element_preview_post_type', true );
			if ( empty( $the_post_type ) ) {
				$the_post_type = 'post';
			}
		} else {
			$recent_posts = wp_get_recent_posts( array( 'numberposts' => '1' ) );
			$the_post_id = array(
				'id'=> ( ! empty( $recent_posts[0]['ID'] ) ? $recent_posts[0]['ID'] : null ),
				'name'=> __( 'Latest Post', 'kadence-pro' ),
			);
			$the_post_id = wp_json_encode( $the_post_id );
			$the_post_type  = 'post';
		}
		wp_localize_script(
			'kadence-element-meta',
			'kadenceElementParams',
			array(
				'post_type'  => $post_type,
				'hooks'      => $this->get_hook_options(),
				'codeHooks'  => $this->get_code_hook_options(),
				'fixedHooks' => $this->get_fixed_hook_options(),
				'authors'    => $this->get_author_options(),
				'ace'        => $this->use_ace_block(),
				'display'    => $this->get_display_options(),
				'user'       => $this->get_user_options(),
				'languageSettings'   => $this->get_language_options(),
				'restBase'   => esc_url_raw( get_rest_url() ),
				'postSelectEndpoint' => '/ktp/v1/post-select',
				//'postTypes' => kadence_blocks_pro_get_post_types(),
				'taxonomies'         => $this->get_taxonomies(),
				'templateHooks'      => $this->get_template_hook_options(),
				'postTypes'          => $this->get_post_types(),
				'previewPostID'      => apply_filters( 'kadence_elements_dynamic_content_preview_post', $the_post_id ),
				'previewPostType'    => apply_filters( 'kadence_elements_dynamic_content_preview_post_type', $the_post_type ),
			)
		);
		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'kadence-element-meta', 'kadence-pro' );
		}
	}
	/**
	 * Setup the post type options for post blocks.
	 *
	 * @return array
	 */
	public function get_post_types() {
		$args = array(
			'public'       => true,
			'show_in_rest' => true,
		);
		$post_types = get_post_types( $args, 'objects' );
		$output = array();
		foreach ( $post_types as $post_type ) {
			if ( 'attachment' == $post_type->name || self::SLUG == $post_type->name ) {
				continue;
			}
			$output[] = array(
				'value' => $post_type->name,
				'label' => $post_type->label,
			);
		}
		return apply_filters( 'kadence_pro_post_types', $output );
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
		return apply_filters( 'kadence_pro_element_display_languages', $languages_options );
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
		return apply_filters( 'kadence_pro_element_user_options', $roles );
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
		return apply_filters( 'kadence_pro_element_display_options', $display );
	}
	/**
	 * Get all Fixed Hook Options
	 */
	public function get_fixed_hook_options() {
		$hooks        = array(
			array(
				'label' => esc_attr__( 'Fixed', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'fixed_above_header',
						'label' => __( 'Fixed On Top', 'kadence-pro' ),
					),
					array(
						'value' => 'fixed_above_trans_header',
						'label' => __( 'Fixed Above Transparent Header', 'kadence-pro' ),
					),
					array(
						'value' => 'fixed_on_header',
						'label' => __( 'Fixed Top After Scroll', 'kadence-pro' ),
					),
					array(
						'value' => 'fixed_on_footer_scroll',
						'label' => __( 'Fixed Bottom After Scroll (no space below footer)', 'kadence-pro' ),
					),
					array(
						'value' => 'fixed_on_footer_scroll_space',
						'label' => __( 'Fixed Bottom After Scroll', 'kadence-pro' ),
					),
					array(
						'value' => 'fixed_below_footer',
						'label' => __( 'Fixed On Bottom', 'kadence-pro' ),
					),
					array(
						'value' => 'fixed_on_footer',
						'label' => __( 'Fixed Bottom (no space below footer)', 'kadence-pro' ),
					),
				),
			),
		);
		return $hooks;
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
		//print_r( $roles__in );
		$output = array();
		foreach ( $authors as $key => $author ) {
			$output[] = array(
				'value' => $author->ID,
				'label' => $author->display_name,
			);
		}
		return apply_filters( 'kadence_pro_element_display_authors', $output );
	}
	/**
	 * Get all Hook Options
	 */
	public function get_all_hook_options() {
		$normal_hooks    = $this->get_hook_options();
		$code_hooks      = $this->get_code_hook_options();
		$fixed_hooks     = $this->get_fixed_hook_options();
		$template_hooks  = $this->get_template_hook_options();
		$hooks           = array_merge( $code_hooks, $normal_hooks );
		$hooks           = array_merge( $hooks, $fixed_hooks );
		$hooks           = array_merge( $hooks, $template_hooks );
		return $hooks;
	}
	/**
	 * Get all code Hook Options
	 */
	public function get_code_hook_options() {
		$hooks        = array(
			array(
				'label' => esc_attr__( 'Scripts', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'wp_head',
						'label' => __( 'Header - Inside <head> tag', 'kadence-pro' ),
					),
					array(
						'value' => 'wp_body_open',
						'label' => __( 'After <body> tag open', 'kadence-pro' ),
					),
					array(
						'value' => 'wp_footer',
						'label' => __( 'Footer - Before </body> tag close', 'kadence-pro' ),
					),
				),
			),
		);
		$normal_hooks = $this->get_hook_options();
		$hooks        = array_merge( $hooks, $normal_hooks );
		return $hooks;
	}
	/**
	 * Get all code Hook Options
	 */
	public function get_template_hook_options() {
		$hooks        = array(
			array(
				'label' => esc_attr__( 'Templates', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'replace_header',
						'label' => esc_attr__( 'Replace Header', 'kadence-pro' ),
					),
					array(
						'value' => 'replace_hero_header',
						'label' => esc_attr__( 'Replace Above Content Hero', 'kadence-pro' ),
					),
					array(
						'value' => 'replace_single_content',
						'label' => __( 'Replace Single Post Content', 'kadence-pro' ),
					),
					// array(
					// 	'value' => 'replace_archive_content',
					// 	'label' => __( 'Replace Archive Content', 'kadence-pro' ),
					// ),
					array(
						'value' => 'replace_loop_content',
						'label' => __( 'Replace Archive Loop Item Content', 'kadence-pro' ),
					),
					// array(
					// 	'value' => 'replace_meta',
					// 	'label' => __( 'Replace Post Meta', 'kadence-pro' ),
					// ),
					array(
						'value' => 'kadence_replace_sidebar',
						'label' => esc_attr__( 'Replace Sidebar', 'kadence-pro' ),
					),
					array(
						'value' => 'replace_footer',
						'label' => esc_attr__( 'Replace Footer', 'kadence-pro' ),
					),
					array(
						'value' => 'replace_404',
						'label' => esc_attr__( 'Replace 404 Page Content', 'kadence-pro' ),
					),
				),
			),
		);

		return apply_filters( 'kadence_pro_element_template_hooks_options', $hooks );
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
		return apply_filters( 'kadence_pro_element_display_taxonomies', $output );
	}
	/**
	 * Get all Normal Hook Options
	 */
	public function get_hook_options() {
		$hooks = array(
			array(
				'label' => esc_attr__( 'Body', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'kadence_before_wrapper',
						'label' => esc_attr__( 'Before Site Wrapper', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_after_wrapper',
						'label' => esc_attr__( 'After Site Wrapper', 'kadence-pro' ),
					),
				),
			),
			array(
				'label'   => esc_attr__( 'Header', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'kadence_before_header',
						'label' => esc_attr__( 'Before Header', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_after_header',
						'label' => esc_attr__( 'After Header', 'kadence-pro' ),
					),
					array(
						'value' => 'replace_header',
						'label' => esc_attr__( 'Replace Header', 'kadence-pro' ),
					),
				),
			),
			array(
				'label'   => esc_attr__( 'Content Wrap', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'kadence_before_content',
						'label' => esc_attr__( 'Before All Content', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_after_content',
						'label' => esc_attr__( 'After All Content', 'kadence-pro' ),
					),
				),
			),
			array(
				'label'   => esc_attr__( 'Above Content Hero Title', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'kadence_entry_hero',
						'label' => esc_attr__( 'SINGLE: Above Title Content (Use Priority for Before/After Placement)', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_entry_archive_hero',
						'label' => esc_attr__( 'ARCHIVE: Above Title Content (Use Priority for Before/After Placement)', 'kadence-pro' ),
					),
					array(
						'value' => 'replace_hero_header',
						'label' => esc_attr__( 'Replace Above Content Hero', 'kadence-pro' ),
					),
				),
			),
			array(
				'label'   => esc_attr__( 'Content and Sidebar', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'kadence_before_main_content',
						'label' => esc_attr__( 'Before Content', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_after_main_content',
						'label' => esc_attr__( 'After Content', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_before_sidebar',
						'label' => esc_attr__( 'Before Sidebar', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_replace_sidebar',
						'label' => esc_attr__( 'Replace Sidebar', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_after_sidebar',
						'label' => esc_attr__( 'After Sidebar', 'kadence-pro' ),
					),
				),
			),
			array(
				'label'   => esc_attr__( 'Single Inner Content', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'kadence_single_before_inner_content',
						'label' => esc_attr__( 'Before Inner Content', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_single_before_entry_header',
						'label' => esc_attr__( 'Before Inner Title Area', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_single_before_entry_title',
						'label' => esc_attr__( 'Before Inner Title', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_single_after_entry_title',
						'label' => esc_attr__( 'After Inner Title', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_single_after_entry_header',
						'label' => esc_attr__( 'After Inner Title Area', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_single_before_entry_content',
						'label' => esc_attr__( 'Before Entry Content', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_single_after_entry_content',
						'label' => esc_attr__( 'After Entry Content', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_single_after_inner_content',
						'label' => esc_attr__( 'After Inner Content', 'kadence-pro' ),
					),
				),
			),
			array(
				'label'   => esc_attr__( 'Inside the Content', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'kadence_inside_the_content_before_h1',
						'label' => esc_attr__( 'Before First Heading Tag', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_inside_the_content_after_h1',
						'label' => esc_attr__( 'After First Heading Tag', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_inside_the_content_after_p1',
						'label' => esc_attr__( 'After First Paragraph', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_inside_the_content_after_p2',
						'label' => esc_attr__( 'After Second Paragraph', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_inside_the_content_after_p3',
						'label' => esc_attr__( 'After Third Paragraph', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_inside_the_content_after_p4',
						'label' => esc_attr__( 'After Fourth Paragraph', 'kadence-pro' ),
					),
				),
			),
			array(
				'label'   => esc_attr__( 'Comments', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'kadence_before_comments',
						'label' => esc_attr__( 'Before Comments', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_before_comments_list',
						'label' => esc_attr__( 'Before Comments List', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_after_comments_list',
						'label' => esc_attr__( 'After Comments List', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_after_comments',
						'label' => esc_attr__( 'After Comments', 'kadence-pro' ),
					),
				),
			),
			array(
				'label'   => esc_attr__( 'Archive Inner Content', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'kadence_archive_before_entry_header',
						'label' => esc_attr__( 'Before Archive Inner Title', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_archive_after_entry_header',
						'label' => esc_attr__( 'After Archive Inner Title', 'kadence-pro' ),
					),
				),
			),
			array(
				'label' => esc_attr__( 'Footer', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'kadence_before_footer',
						'label' => esc_attr__( 'Before Footer', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_after_footer',
						'label' => esc_attr__( 'After Footer', 'kadence-pro' ),
					),
					array(
						'value' => 'replace_footer',
						'label' => esc_attr__( 'Replace Footer', 'kadence-pro' ),
					),
				),
			),
			array(
				'label' => esc_attr__( '404 Page', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'kadence_404_before_inner_content',
						'label' => esc_attr__( 'Before 404 Page Content', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_404_after_inner_content',
						'label' => esc_attr__( 'After 404 Page Content', 'kadence-pro' ),
					),
					array(
						'value' => 'replace_404',
						'label' => esc_attr__( 'Replace 404 Page Content', 'kadence-pro' ),
					),
				),
			),
			array(
				'label' => esc_attr__( 'Mobile Menu Off Canvas Area', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'kadence_before_mobile_navigation_popup',
						'label' => esc_attr__( 'Before Mobile Off Canvas Content', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_after_mobile_navigation_popup',
						'label' => esc_attr__( 'After Mobile Off Canvas Content', 'kadence-pro' ),
					),
				),
			),
			array(
				'label' => esc_attr__( 'Header Account Login Modal', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'kadence_before_account_login_popup',
						'label' => esc_attr__( 'Left of Login Form', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_before_account_login_inner_popup',
						'label' => esc_attr__( 'Before Login Form', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_after_account_login_inner_popup',
						'label' => esc_attr__( 'After Login Form', 'kadence-pro' ),
					),
					array(
						'value' => 'kadence_after_account_login_popup',
						'label' => esc_attr__( 'Right of Login Form', 'kadence-pro' ),
					),
					array(
						'value' => 'replace_login_modal',
						'label' => esc_attr__( 'Replace Login Modal', 'kadence-pro' ),
					),
				),
			),
		);
		if ( class_exists( 'woocommerce' ) ) {
			$woo_add = array(
				array(
					'label' => esc_attr__( 'Woocommerce Content', 'kadence-pro' ),
					'options' => array(
						array(
							'value' => 'woocommerce_before_main_content',
							'label' => esc_attr__( 'Before Woocommerce Content', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_after_main_content',
							'label' => esc_attr__( 'After Woocommerce Content', 'kadence-pro' ),
						),
					),
				),
				array(
					'label' => esc_attr__( 'Woocommerce Product', 'kadence-pro' ),
					'options' => array(
						array(
							'value' => 'woocommerce_before_single_product',
							'label' => esc_attr__( 'Before Single Product', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_before_single_product_summary',
							'label' => esc_attr__( 'Single Product Image', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_before_single_product_image',
							'label' => esc_attr__( 'Before Single Product Image', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_after_single_product_image',
							'label' => esc_attr__( 'After Single Product Image', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_single_product_summary',
							'label' => esc_attr__( 'Single Product Summary', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_before_add_to_cart_form',
							'label' => esc_attr__( 'Before Add to Cart Form', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_after_add_to_cart_form',
							'label' => esc_attr__( 'After Add to Cart Form', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_share',
							'label' => esc_attr__( 'Single Product Share', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_after_single_product_summary',
							'label' => esc_attr__( 'Single Product Tabs', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_after_single_product',
							'label' => esc_attr__( 'After Single Product', 'kadence-pro' ),
						),
					),
				),
				array(
					'label' => esc_attr__( 'Woocommerce Archive', 'kadence-pro' ),
					'options' => array(
						array(
							'value' => 'woocommerce_before_shop_loop',
							'label' => esc_attr__( 'Before Shop Loop', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_before_shop_loop_item',
							'label' => esc_attr__( 'Before Loop Item', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_before_shop_loop_item_title',
							'label' => esc_attr__( 'Loop Item Image', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_shop_loop_item_title',
							'label' => esc_attr__( 'Loop Item Title', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_after_shop_loop_item_title',
							'label' => esc_attr__( 'Loop Item Price', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_after_shop_loop_item',
							'label' => esc_attr__( 'After Shop Loop Item', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_after_shop_loop',
							'label' => esc_attr__( 'After Shop Loop', 'kadence-pro' ),
						),
					),
				),
				array(
					'label' => esc_attr__( 'Woocommerce Account', 'kadence-pro' ),
					'options' => array(
						array(
							'value' => 'woocommerce_before_customer_login_form',
							'label' => esc_attr__( 'Before Login Form (Logged Out View)', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_after_customer_login_form',
							'label' => esc_attr__( 'After Login Form (Logged Out View)', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_before_account_navigation',
							'label' => esc_attr__( 'Before Account Navigation', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_after_account_navigation',
							'label' => esc_attr__( 'After Account Navigation', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_account_content',
							'label' => esc_attr__( 'Account Content (Logged In View)', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_account_dashboard',
							'label' => esc_attr__( 'Account Dashboard', 'kadence-pro' ),
						),
					),
				),
				array(
					'label' => esc_attr__( 'Woocommerce Cart', 'kadence-pro' ),
					'options' => array(
						array(
							'value' => 'woocommerce_before_cart',
							'label' => esc_attr__( 'Before Cart Content', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_before_cart_table',
							'label' => esc_attr__( 'Before Cart Table', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_after_cart_table',
							'label' => esc_attr__( 'After Cart Table', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_cart_collaterals',
							'label' => esc_attr__( 'Cart Totals', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_after_cart',
							'label' => esc_attr__( 'After Cart Content', 'kadence-pro' ),
						),
					),
				),
				array(
					'label' => esc_attr__( 'Woocommerce Side Cart', 'kadence-pro' ),
					'options' => array(
						array(
							'value' => 'kadence-before-side-cart',
							'label' => esc_attr__( 'Before Side Cart Content', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_before_mini_cart_contents',
							'label' => esc_attr__( 'Before Side Cart List Items', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_mini_cart_contents',
							'label' => esc_attr__( 'After Side Cart List Items', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_widget_shopping_cart_before_buttons',
							'label' => esc_attr__( 'Before Side Cart Buttons', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_widget_shopping_cart_after_buttons',
							'label' => esc_attr__( 'After Side Cart Buttons', 'kadence-pro' ),
						),
						array(
							'value' => 'kadence-after-side-cart',
							'label' => esc_attr__( 'After Side Cart Content', 'kadence-pro' ),
						),
					),
				),
				array(
					'label' => esc_attr__( 'Woocommerce Checkout', 'kadence-pro' ),
					'options' => array(
						array(
							'value' => 'woocommerce_before_checkout_form',
							'label' => esc_attr__( 'Before Checkout Form', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_checkout_before_customer_details',
							'label' => esc_attr__( 'Before Customer Details', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_checkout_after_customer_details',
							'label' => esc_attr__( 'After Customer Details', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_checkout_order_review',
							'label' => esc_attr__( 'Order Review', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_review_order_after_cart_contents',
							'label' => esc_attr__( 'After Order Review Contents', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_review_order_before_order_total',
							'label' => esc_attr__( 'Before Order Review Total', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_review_order_after_order_total',
							'label' => esc_attr__( 'After Order Review Total', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_after_checkout_form',
							'label' => esc_attr__( 'Before Checkout Form', 'kadence-pro' ),
						),
					),
				),
				array(
					'label' => esc_attr__( 'Woocommerce Order Received', 'kadence-pro' ),
					'options' => array(
						array(
							'value' => 'woocommerce_before_thankyou',
							'label' => esc_attr__( 'Before Order Received Content', 'kadence-pro' ),
						),
						array(
							'value' => 'woocommerce_thankyou',
							'label' => esc_attr__( 'After Order Received Content', 'kadence-pro' ),
						),
					),
				),
				array(
					'label' => esc_attr__( 'Shop Widget Toggle Off Canvas Area', 'kadence-pro' ),
					'options' => array(
						array(
							'value' => 'kadence_before_product_off_canvas_filter',
							'label' => esc_attr__( 'Before Widget Toggle Off Canvas Content', 'kadence-pro' ),
						),
						array(
							'value' => 'kadence_after_product_off_canvas_filter',
							'label' => esc_attr__( 'After Widget Toggle Off Canvas Content', 'kadence-pro' ),
						),
					),
				),
			);
			$hooks = array_merge( $hooks, $woo_add );
		}
		if ( class_exists( 'SFWD_LMS' ) ) {
			$learn_add = array(
				array(
					'label' => esc_attr__( 'LearnDash Focus Mode', 'kadence-pro' ),
					'options' => array(
						array(
							'value' => 'learndash-focus-sidebar-nav-before',
							'label' => esc_attr__( 'Focus Mode: Before Sidebar Nav', 'kadence-pro' ),
						),
						array(
							'value' => 'learndash-focus-sidebar-nav-after',
							'label' => esc_attr__( 'Focus Mode: After Sidebar Nav', 'kadence-pro' ),
						),
						array(
							'value' => 'learndash-focus-content-title-before',
							'label' => esc_attr__( 'Focus Mode: Before Title', 'kadence-pro' ),
						),
						array(
							'value' => 'learndash-focus-content-content-before',
							'label' => esc_attr__( 'Focus Mode: Before Content', 'kadence-pro' ),
						),
						array(
							'value' => 'learndash-focus-content-content-after',
							'label' => esc_attr__( 'Focus Mode: After Content', 'kadence-pro' ),
						),
					),
				),
				array(
					'label' => esc_attr__( 'LearnDash Course Page', 'kadence-pro' ),
					'options' => array(
						array(
							'value' => 'learndash-course-before',
							'label' => esc_attr__( 'Before Course Content', 'kadence-pro' ),
						),
						array(
							'value' => 'learndash-course-after',
							'label' => esc_attr__( 'After Course Content', 'kadence-pro' ),
						),
					),
				),
				array(
					'label' => esc_attr__( 'LearnDash Group Page', 'kadence-pro' ),
					'options' => array(
						array(
							'value' => 'learndash_group_before',
							'label' => esc_attr__( 'Before Group Content', 'kadence-pro' ),
						),
						array(
							'value' => 'learndash_group_after',
							'label' => esc_attr__( 'After Group Content', 'kadence-pro' ),
						),
					),
				),
			);
			$hooks = array_merge( $hooks, $learn_add );
		}
		if ( defined( 'TRIBE_EVENTS_FILE' ) ) {
			$events_add = array(
				array(
					'label' => esc_attr__( 'The Events Calendar', 'kadence-pro' ),
					'options' => array(
						array(
							'value' => 'kadence_tribe_events_before_main_tag',
							'label' => esc_attr__( 'Events: Before Content', 'kadence-pro' ),
						),
						array(
							'value' => 'kadence_tribe_events_after_main_tag',
							'label' => esc_attr__( 'Events: After Content', 'kadence-pro' ),
						),
						array(
							'value' => 'tribe_events_single_event_before_the_content',
							'label' => esc_attr__( 'Single Event: Before Inner Content (Classic Mode Only)', 'kadence-pro' ),
						),
						array(
							'value' => 'tribe_events_single_event_after_the_content',
							'label' => esc_attr__( 'Single Event: After Inner Content (Classic Mode Only)', 'kadence-pro' ),
						),
						array(
							'value' => 'tribe_events_single_event_before_the_meta',
							'label' => esc_attr__( 'Single Event: Before Meta (Classic Mode Only)', 'kadence-pro' ),
						),
						array(
							'value' => 'tribe_events_single_event_after_the_meta',
							'label' => esc_attr__( 'Single Event: After Meta (Classic Mode Only)', 'kadence-pro' ),
						),
					),
				),
			);
			$hooks = array_merge( $hooks, $events_add );
		}
		$custom_add = array(
			array(
				'label' => esc_attr__( 'Custom', 'kadence-pro' ),
				'options' => array(
					array(
						'value' => 'custom',
						'label' => esc_attr__( 'Custom Hook', 'kadence-pro' ),
					),
				),
			),
		);
		$hooks = array_merge( $hooks, $custom_add );
		return apply_filters( 'kadence_pro_element_hooks_options', $hooks );
	}
	/**
	 * Register Script for Meta options
	 */
	public function plugin_register() {
		$path = KTP_URL . 'build/';
		wp_register_script(
			'kadence-element-meta',
			$path . 'meta.js',
			array( 'wp-plugins', 'wp-edit-post', 'wp-element' ),
			KTP_VERSION
		);
	}
	/**
	 * Register Post Meta options
	 */
	public function register_meta() {
		register_post_meta(
			self::SLUG,
			'_kad_element_type',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_hook',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_hook_custom',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_hook_priority',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'number',
				'default'       => 10,
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_hook_scroll',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'number',
				'default'       => 300,
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_show_conditionals',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_all_show',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'boolean',
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_hide_conditionals',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_user_conditionals',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_device_conditionals',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_enable_expires',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'boolean',
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_expires',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_language',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_fixed_width',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_width',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'number',
				'default'       => 300,
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_fixed_position',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_xposition',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'number',
				'default'       => 0,
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_yposition',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'number',
				'default'       => 0,
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_preview_post',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_preview_post_type',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => '__return_true',
			)
		);
		register_post_meta(
			self::SLUG,
			'_kad_element_preview_width',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'default'       => '',
				'auth_callback' => '__return_true',
			)
		);
	}

	/**
	 * Registers the block areas post type.
	 *
	 * @since 0.1.0
	 */
	public function register_post_type() {
		$labels = array(
			'name'                  => __( 'Elements', 'kadence_pro' ),
			'singular_name'         => __( 'Element', 'kadence_pro' ),
			'menu_name'             => _x( 'Elements', 'Admin Menu text', 'kadence_pro' ),
			'add_new'               => _x( 'Add New', 'Element', 'kadence_pro' ),
			'add_new_item'          => __( 'Add New Element', 'kadence_pro' ),
			'new_item'              => __( 'New Element', 'kadence_pro' ),
			'edit_item'             => __( 'Edit Element', 'kadence_pro' ),
			'view_item'             => __( 'View Element', 'kadence_pro' ),
			'all_items'             => __( 'All Elements', 'kadence_pro' ),
			'search_items'          => __( 'Search Elements', 'kadence_pro' ),
			'parent_item_colon'     => __( 'Parent Element:', 'kadence_pro' ),
			'not_found'             => __( 'No Elements found.', 'kadence_pro' ),
			'not_found_in_trash'    => __( 'No Elements found in Trash.', 'kadence_pro' ),
			'archives'              => __( 'Element archives', 'kadence_pro' ),
			'insert_into_item'      => __( 'Insert into Element', 'kadence_pro' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Element', 'kadence_pro' ),
			'filter_items_list'     => __( 'Filter Elements list', 'kadence_pro' ),
			'items_list_navigation' => __( 'Elements list navigation', 'kadence_pro' ),
			'items_list'            => __( 'Elements list', 'kadence_pro' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Element areas to include in your site.', 'kadence_pro' ),
			'public'             => true,
			'publicly_queryable' => true,
			'has_archive'        => false,
			'exclude_from_search'=> true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'show_in_nav_menus'  => false,
			'show_in_admin_bar'  => false,
			'can_export'         => true,
			'show_in_rest'       => true,
			'rewrite'            => false,
			'rest_base'          => 'kadence_element',
			'capability_type'    => array( 'kadence_element', 'kadence_elements' ),
			'map_meta_cap'       => true,
			'supports'           => array(
				'title',
				'editor',
				'custom-fields',
				'revisions',
			),
		);

		register_post_type( self::SLUG, $args );
	}

	/**
	 * Filters the capabilities of a user to conditionally grant them capabilities for managing Elements.
	 *
	 * Any user who can 'edit_theme_options' will have access to manage Elements.
	 *
	 * @param array $allcaps A user's capabilities.
	 * @return array Filtered $allcaps.
	 */
	public function filter_post_type_user_caps( $allcaps ) {
		if ( isset( $allcaps['edit_theme_options'] ) ) {
			$allcaps['edit_kadence_elements']             = $allcaps['edit_theme_options'];
			$allcaps['edit_others_kadence_elements']      = $allcaps['edit_theme_options'];
			$allcaps['edit_published_kadence_elements']   = $allcaps['edit_theme_options'];
			$allcaps['edit_private_kadence_elements']     = $allcaps['edit_theme_options'];
			$allcaps['delete_kadence_elements']           = $allcaps['edit_theme_options'];
			$allcaps['delete_others_kadence_elements']    = $allcaps['edit_theme_options'];
			$allcaps['delete_published_kadence_elements'] = $allcaps['edit_theme_options'];
			$allcaps['delete_private_kadence_elements']   = $allcaps['edit_theme_options'];
			$allcaps['publish_kadence_elements']          = $allcaps['edit_theme_options'];
			$allcaps['read_private_kadence_elements']     = $allcaps['edit_theme_options'];
		}

		return $allcaps;
	}

	/**
	 * Fixes the label of the block areas admin menu entry.
	 *
	 * @since 0.1.0
	 */
	private function fix_admin_menu_entry() {
		global $submenu;

		if ( ! isset( $submenu['themes.php'] ) ) {
			return;
		}

		$post_type = get_post_type_object( self::SLUG );
		foreach ( $submenu['themes.php'] as $key => $submenu_entry ) {
			if ( $post_type->labels->all_items === $submenu['themes.php'][ $key ][0] ) {
				$submenu['themes.php'][ $key ][0] = $post_type->labels->menu_name;
				break;
			}
		}
	}

	/**
	 * Filters the block area post type columns in the admin list table.
	 *
	 * @since 0.1.0
	 *
	 * @param array $columns Columns to display.
	 * @return array Filtered $columns.
	 */
	private function filter_post_type_columns( array $columns ) : array {

		$add = array(
			'type'            => esc_html__( 'Type', 'kadence-pro' ),
			'hook'            => esc_html__( 'Placement', 'kadence-pro' ),
			'display'         => esc_html__( 'Display On', 'kadence-pro' ),
			'user_visibility' => esc_html__( 'Visible To', 'kadence-pro' ),
			'shortcode'       => esc_html__( 'Shortcode', 'kadence-pro' ),
			'status'          => esc_html__( 'Status', 'kadence-pro' ),
		);

		$new_columns = array();
		foreach ( $columns as $key => $label ) {
			$new_columns[ $key ] = $label;
			if ( 'title' == $key ) {
				$new_columns = array_merge( $new_columns, $add );
			}
		}

		return $new_columns;
	}
	/**
	 * Finds the label in an array.
	 *
	 * @param array  $data the array data.
	 * @param string $value the value field.
	 */
	public function get_item_label_in_array( $data, $value ) {
		foreach ( $data as $key => $item ) {
			foreach ( $item['options'] as $sub_key => $sub_item ) {
				if ( $sub_item['value'] === $value ) {
					return $sub_item['label'];
				}
			}
		}
		return false;
	}

	/**
	 * Renders column content for the block area post type list table.
	 *
	 * @param string $column_name Column name to render.
	 * @param int    $post_id     Post ID.
	 */
	private function render_post_type_column( string $column_name, int $post_id ) {
		if ( 'hook' !== $column_name && 'display' !== $column_name && 'status' !== $column_name && 'shortcode' !== $column_name && 'type' !== $column_name && 'user_visibility' !== $column_name ) {
			return;
		}
		$post = get_post( $post_id );
		$meta = $this->get_post_meta_array( $post );
		if ( 'status' === $column_name ) {
			if ( 'publish' === $post->post_status || 'draft' === $post->post_status ) {
				$title = ( 'publish' === $post->post_status ? __( 'Published', 'kadence-pro' ) : __( 'Draft', 'kadence-pro' ) );
				echo '<button class="kadence-status-toggle kadence-element-status kadence-status-' . esc_attr( $post->post_status ) . '" data-post-status="' . esc_attr( $post->post_status ) . '" data-post-id="' . esc_attr( $post_id ) . '"><span class="kadence-toggle"></span><span class="kadence-status-label">' . $title . '</span><span class="spinner"></span></button>';
			} else {
				echo '<div class="kadence-static-status-toggle">' . esc_html( $post->post_status ) . '</div>';
			}
		}
		if ( 'hook' === $column_name ) {
			if ( isset( $meta['hook'] ) && ! empty( $meta['hook'] ) && 'custom' !== $meta['hook'] ) {
				$label = $this->get_item_label_in_array( $this->get_all_hook_options(), $meta['hook'] );
				echo esc_html( $label );
			} else if ( isset( $meta['hook'] ) && 'custom' === $meta['hook'] && isset( $meta['custom'] ) && ! empty( $meta['custom'] ) ) {
				echo esc_html( $meta['custom'] );
			}
		}
		if ( 'type' === $column_name ) {
			if ( isset( $meta['type'] ) && ! empty( $meta['type'] ) ) {
				echo esc_html( ucwords( $meta['type'] ) );
			} else {
				echo esc_html__( 'Default', 'kadence-pro' );
			}
		}
		if ( 'display' === $column_name ) {
			if ( isset( $meta ) && isset( $meta['show'] ) && is_array( $meta['show'] ) && ! empty( $meta['show'] ) ) {
				foreach ( $meta['show'] as $key => $rule ) {
					$rule_split = explode( '|', $rule['rule'], 2 );
					if ( in_array( $rule_split[0], array( 'singular', 'tax_archive' ) ) ) {
						if ( ! isset( $rule['select'] ) || isset( $rule['select'] ) && 'all' === $rule['select'] ) {
							echo esc_html( 'All ' . $rule['rule'] );
							echo '<br>';
						} elseif ( isset( $rule['select'] ) && 'author' === $rule['select'] ) {
							$label = $this->get_item_label_in_array( $this->get_display_options(), $rule['rule'] );
							echo esc_html( $label . ' Author: ' );
							if ( isset( $rule['subRule'] ) ) {
								$user = get_userdata( $rule['subRule'] );
								if ( isset( $user ) && is_object( $user ) && $user->display_name ) {
									echo esc_html( $user->display_name );
								}
							}
							echo '<br>';
						} elseif ( isset( $rule['select'] ) && 'tax' === $rule['select'] ) {
							$label = $this->get_item_label_in_array( $this->get_display_options(), $rule['rule'] );
							echo esc_html( $label . ' Terms: ' );
							if ( isset( $rule['subRule'] ) && isset( $rule['subSelection'] ) && is_array( $rule['subSelection'] ) ) {
								foreach ( $rule['subSelection'] as $sub_key => $selection ) {
									echo esc_html( $selection['value'] . ', ' );
								}
							}
							echo '<br>';
						} elseif ( isset( $rule['select'] ) && 'ids' === $rule['select'] ) {
							$label = $this->get_item_label_in_array( $this->get_display_options(), $rule['rule'] );
							echo esc_html( $label . ' Items: ' );
							if ( isset( $rule['ids'] ) && is_array( $rule['ids'] ) ) {
								foreach ( $rule['ids'] as $sub_key => $sub_id ) {
									echo esc_html( $sub_id . ', ' );
								}
							}
							echo '<br>';
						} elseif ( isset( $rule['select'] ) && 'individual' === $rule['select'] ) {
							$label = $this->get_item_label_in_array( $this->get_display_options(), $rule['rule'] );
							echo esc_html( $label . ' Terms: ' );
							if ( isset( $rule['subSelection'] ) && is_array( $rule['subSelection'] ) ) {
								$show_taxs   = array();
								foreach ( $rule['subSelection'] as $sub_key => $selection ) {
									if ( isset( $selection['value'] ) && ! empty( $selection['value'] ) ) {
										$show_taxs[] = $selection['value'];
									}
								}
								echo implode( ', ', $show_taxs );
							}
							echo '<br>';
						}
					} else {
						$label = $this->get_item_label_in_array( $this->get_display_options(), $rule['rule'] );
						echo esc_html( $label ) . '<br>';
					}
				}
			} else {
				echo esc_html__( '[UNSET]', 'kadence-pro' );
			}
		}
		if ( 'user_visibility' === $column_name ) {
			if ( isset( $meta ) && isset( $meta['user'] ) && is_array( $meta['user'] ) && ! empty( $meta['user'] ) ) {
				$show_roles = array();
				foreach ( $meta['user'] as $key => $user_rule ) {
					if ( isset( $user_rule['role'] ) && ! empty( $user_rule['role'] ) ) {
						$show_roles[] = $this->get_item_label_in_array( $this->get_user_options(), $user_rule['role'] );
					}
				}
				if ( count( $show_roles ) !== 0 ) {
					echo esc_html__( 'Visible to:', 'kadence-pro' );
					echo '<br>';
					echo implode( ', ', $show_roles );
				} else {
					echo esc_html__( '[UNSET]', 'kadence-pro' );
				}
			} else {
				echo esc_html__( '[UNSET]', 'kadence-pro' );
			}
		}
		if ( 'shortcode' === $column_name ) {
			echo '<code>[kadence_element id="' . esc_attr( $post_id ) . '"]</code>';
		}
	}

	/**
	 * Renders the element single template on the front end.
	 *
	 * @param array $layout the layout array.
	 */
	public function element_single_layout( $layout ) {
		global $post;
		if ( is_singular( self::SLUG ) || ( is_admin() && is_object( $post ) && self::SLUG === $post->post_type ) ) {
			$layout = wp_parse_args(
				array(
					'layout'           => 'fullwidth',
					'boxed'            => 'unboxed',
					'feature'          => 'hide',
					'feature_position' => 'above',
					'comments'         => 'hide',
					'navigation'       => 'hide',
					'title'            => 'hide',
					'transparent'      => 'disable',
					'sidebar'          => 'disable',
					'vpadding'         => 'hide',
					'footer'           => 'disable',
					'header'           => 'disable',
					'content'          => 'enable',
				),
				$layout
			);
		}

		return $layout;
	}
}
Elements_Post_Type_Controller::get_instance();
