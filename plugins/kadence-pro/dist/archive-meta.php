<?php
/**
 * Class for the Customizer
 *
 * @package Kadence
 */

namespace Kadence_Pro;

use function Kadence\kadence;
use Kadence\Kadence_CSS;

/**
 * Main plugin class
 */
class Archive_Meta {
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
		'archive',
	);

	/**
	 * Holds taxonomies.
	 *
	 * @var the taxonomies being used.
	 */
	private $_taxonomies = array();

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
		//add_filter( 'kadence_theme_options_defaults', array( $this, 'add_option_defaults' ), 10 );
		//add_filter( 'kadence_theme_customizer_sections', array( $this, 'add_customizer_sections' ), 10 );
		//add_action( 'customize_register', array( $this, 'create_pro_settings_array' ), 1 );
		add_action( 'admin_init', array( $this, 'init' ), 100 );
		add_action( 'edit_term', array( $this, 'update_tax_meta' ), 10, 2 );
		//add_action( 'create_term',  array( $this, 'update_tax_image' ), 10, 2 );
		add_action( 'load-edit-tags.php', array( $this, 'load_edit_page' ) );
		add_filter( 'kadence_dynamic_css', array( $this, 'dynamic_css' ), 20 );
		add_action( 'wp', array( $this, 'update_archive_settings' ), 20 );
	}
	/**
	 * Generates the dynamic css based on customizer options.
	 *
	 * @param string $css any custom css.
	 * @return string
	 */
	public function dynamic_css( $css ) {
		$generated_css = $this->generate_archive_css();
		if ( ! empty( $generated_css ) ) {
			$css .= "\n/* Kadence Pro Archive CSS */\n" . $generated_css;
		}
		return $css;
	}
	/**
	 * Filters in settings for archives
	 */
	public function update_archive_settings() {
		if ( is_archive() ) {
			$term_id   = get_queried_object_id();
			if ( $term_id ) {
				$post_type = get_post_type();
				$columns = get_term_meta( $term_id, 'kwp-tax-columns', true );
				if ( $columns ) {
					add_filter(
						'theme_mod_' . $post_type . '_archive_columns',
						function() use ( $columns ) {
							return $columns;
						}
					);
				}
			}
		}
	}
	/**
	 * Generates the dynamic css based on page options.
	 *
	 * @return string
	 */
	public function generate_archive_css() {
		$css                    = new Kadence_CSS();
		$media_query            = array();
		$media_query['mobile']  = apply_filters( 'kadence_mobile_media_query', '(max-width: 767px)' );
		$media_query['tablet']  = apply_filters( 'kadence_tablet_media_query', '(max-width: 1024px)' );
		$media_query['desktop'] = apply_filters( 'kadence_tablet_media_query', '(min-width: 1025px)' );
		if ( is_archive() && kadence()->show_hero_title() ) {
			$term_id   = get_queried_object_id();
			if ( $term_id ) {
				$post_type = get_post_type();
				$image_id = get_term_meta( $term_id, 'kwp-tax-image-id', true );
				if ( $image_id ) {
					$image = wp_get_attachment_image_src( $image_id, 'full' );
					if ( $image ) {
						$css->set_selector( '.' . $post_type . '-archive-hero-section .entry-hero-container-inner' );
						$css->add_property( 'background-image', $image[0] );
						$bg_settings = kadence()->sub_option( $post_type . '_archive_title_background', 'desktop' );
						if ( $bg_settings && isset( $bg_settings['image'] ) ) {
							$repeat      = ( isset( $bg_settings['image']['repeat'] ) && ! empty( $bg_settings['image']['repeat'] ) ? $bg_settings['image']['repeat'] : 'no-repeat' );
							$size        = ( isset( $bg_settings['image']['size'] ) && ! empty( $bg_settings['image']['size'] ) ? $bg_settings['image']['size'] : 'cover' );
							$position    = ( isset( $bg_settings['image']['position'] ) && is_array( $bg_settings['image']['position'] ) && isset( $bg_settings['image']['position']['x'] ) && ! empty( $bg_settings['image']['position']['x'] ) && isset( $bg_settings['image']['position']['y'] ) && ! empty( $bg_settings['image']['position']['y'] ) ? ( $bg_settings['image']['position']['x'] * 100 ) . '% ' . ( $bg_settings['image']['position']['y'] * 100 ) . '%' : 'center' );
							$attachement = ( isset( $bg_settings['image']['attachment'] ) && ! empty( $bg_settings['image']['attachment'] ) ? $bg_settings['image']['attachment'] : 'scroll' );
							$css->add_property( 'background-repeat', $repeat );
							$css->add_property( 'background-position', $position );
							$css->add_property( 'background-size', $size );
							$css->add_property( 'background-attachment', $attachement );
						} else {
							$css->add_property( 'background-repeat', 'no-repeat' );
							$css->add_property( 'background-position', 'center center' );
							$css->add_property( 'background-size', 'cover' );
							$css->add_property( 'background-attachment', 'scroll' );
						}
					}
				}
			}
		}
		return $css->css_output();
	}
	/**
	 * Enqueue scripts and styles
	 *
	 * @return void
	 */
	public function load_edit_page() {
		$screen = get_current_screen();
		if ( ! in_array( $screen->taxonomy, $this->_taxonomies ) ) {
			return;
		}
		add_action( 'admin_enqueue_scripts', array( $this, 'load_media' ) );
		add_action( 'admin_footer', array( $this, 'add_script' ) );
	}

	/**
	 * Initialize the class and start calling our hooks and filters
	 */
	public function init() {
		$this->_taxonomies = apply_filters( 'kadence-pro-archive-image-taxonomies', get_taxonomies() );
		foreach ( $this->_taxonomies as $tax_name ) {
			add_action( $tax_name . '_edit_form_fields', array( $this, 'show_kadence_fields' ), 11, 2 );
		}
	}
	/**
	 * Add media script.
	 */
	public function load_media() {
		wp_enqueue_media();
	}
	/**
	 * Update the form field value
	 */
	public function update_tax_meta( $term_id, $tt_id ) {
		if ( isset( $_POST['kwp-tax-image-id'] ) && '' !== $_POST['kwp-tax-image-id'] ) {
			update_term_meta( $term_id, 'kwp-tax-image-id', wp_unslash( sanitize_title( $_POST['kwp-tax-image-id'] ) ) );
		} else {
			update_term_meta( $term_id, 'kwp-tax-image-id', '' );
		}
		if ( isset( $_POST['kwp-tax-columns'] ) && '' !== $_POST['kwp-tax-columns'] ) {
			update_term_meta( $term_id, 'kwp-tax-columns', wp_unslash( sanitize_title( $_POST['kwp-tax-columns'] ) ) );
		} else {
			update_term_meta( $term_id, 'kwp-tax-columns', '' );
		}
	}

	/**
	 * Add a form field on the archive page.
	 */
	public function show_image_field_pre( $taxonomy ) {
		?>
		<div class="form-field term-group">
			<label for="kwp-tax-image-id"><?php esc_html_e( 'Above Header Background Image', 'kadence-pro' ); ?></label>
			<input type="hidden" id="kwp-tax-image-id" name="kwp-tax-image-id" class="kwp-tax-media-url" value="">
			<div id="kwp-tax-image-wrapper"></div>
			<p>
				<input type="button" class="button button-secondary kwp-tax-media-button" id="kwp-tax-media-button" name="kwp-tax-media-button" value="<?php esc_html_e( 'Add Image', 'kadence-pro' ); ?>" />
				<input type="button" class="button button-secondary kwp-tax-media-remove" id="kwp-tax-media-remove" name="kwp-tax-media-remove" value="<?php esc_html_e( 'Remove Image', 'kadence-pro' ); ?>" />
			</p>
		</div>
		<?php
	}

	/**
	 * Edit the form field
	 */
	public function show_kadence_fields( $term, $taxonomy ) {
		?>
		<tr class="form-field term-group-wrap">
			<th scope="row">
				<label for="kwp-tax-image-id"><?php esc_html_e( 'Above Header Background Image', 'kadence-pro' ); ?></label>
			</th>
			<td>
				<?php $image_id = get_term_meta( $term->term_id, 'kwp-tax-image-id', true ); ?>
				<input type="hidden" id="kwp-tax-image-id" name="kwp-tax-image-id" value="<?php echo esc_attr( $image_id ); ?>">
				<div id="kwp-tax-image-wrapper">
				<?php
				if ( $image_id ) {
					echo wp_get_attachment_image( $image_id, 'thumbnail' );
				}
				?>
				</div>
				<p>
					<input type="button" class="button button-secondary kwp-tax-media-button" id="kwp-tax-media-button" name="kwp-tax-media-button" value="<?php esc_html_e( 'Add Image', 'kadence-pro' ); ?>" />
					<input type="button" class="button button-secondary kwp-tax-media-remove" id="kwp-tax-media-remove" name="kwp-tax-media-remove" value="<?php esc_html_e( 'Remove Image', 'kadence-pro' ); ?>" />
				</p>
			</td>
		</tr>
		<?php
		if ( $term->taxonomy !== 'product_cat' && $term->taxonomy !== 'product_tag' && $term->taxonomy !== 'product_brands' ) {
			?>
			<tr class="form-field term-group-wrap">
				<th scope="row">
					<label for="kwp-tax-columns"><?php esc_html_e( 'Archive Columns', 'kadence-pro' ); ?></label>
				</th>
				<td>
					<div class="kwp-radio-buttons">
						<?php $column = get_term_meta( $term->term_id, 'kwp-tax-columns', true ); ?>
						<input type="radio" id="kwp-default-column" name="kwp-tax-columns" value="" <?php checked( '' === $column ); ?>>
						<label for="default_column"><?php esc_html_e( 'Default', 'kadence-pro' ); ?></label>
						<input type="radio" id="kwp-1-column" name="kwp-tax-columns" value="1" <?php checked( '1' === $column ); ?>>
						<label for="kwp-1-column"><?php esc_html_e( '1 Column', 'kadence-pro' ); ?></label>
						<input type="radio" id="kwp-2-column" name="kwp-tax-columns" value="2" <?php checked( '2' === $column ); ?>>
						<label for="kwp-2-column"><?php esc_html_e( '2 Columns', 'kadence-pro' ); ?></label>
						<input type="radio" id="kwp-3-column" name="kwp-tax-columns" value="3" <?php checked( '3' === $column ); ?>>
						<label for="kwp-3-column"><?php esc_html_e( '3 Columns', 'kadence-pro' ); ?></label>
						<input type="radio" id="kwp-3-column" name="kwp-tax-columns" value="4" <?php checked( '4' === $column ); ?>>
						<label for="kwp-4-column"><?php esc_html_e( '4 Columns', 'kadence-pro' ); ?></label>
					</div>
				</td>
			</tr>
			<?php
		}
	}
	/**
	 * Add script.
	 */
	public function add_script() {
		?>
		<script>
			jQuery(document).ready( function($) {
			function kwp_tax_media_upload(button_class) {
				var _custom_media = true,
				_orig_send_attachment = wp.media.editor.send.attachment;
				$('body').on('click', button_class, function(e) {
				var button_id = '#'+$(this).attr('id');
				var send_attachment_bkp = wp.media.editor.send.attachment;
				var button = $(button_id);
				_custom_media = true;
				wp.media.editor.send.attachment = function(props, attachment){
					if ( _custom_media ) {
					$('#kwp-tax-image-id').val(attachment.id);
					$('#kwp-tax-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
					$('#kwp-tax-image-wrapper .custom_media_image').attr('src',attachment.url).css('display','block');
					} else {
					return _orig_send_attachment.apply( button_id, [props, attachment] );
					}
					}
				wp.media.editor.open(button);
				return false;
			});
			}
			kwp_tax_media_upload('.kwp-tax-media-button.button'); 
			$('body').on('click','.kwp-tax-media-remove',function(){
			$('#kwp-tax-image-id').val('');
			$('#kwp-tax-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
			});
			// Thanks: http://stackoverflow.com/questions/15281995/wordpress-create-category-ajax-response
			$(document).ajaxComplete(function(event, xhr, settings) {
			var queryStringArr = settings.data.split('&');
			if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
				var xml = xhr.responseXML;
				$response = $(xml).find('term_id').text();
				if($response!=""){
				// Clear the thumb image
				$('#kwp-tax-image-wrapper').html('');
				}
			}
			});
		});
		</script>
		<?php
	}
	/**
	 * Add Defaults
	 *
	 * @access public
	 * @param array $defaults registered option defaults with kadence theme.
	 * @return array
	 */
	public function add_option_defaults( $defaults ) {
		$script_addons = array(
			'header_scripts'     => '',
			'after_body_scripts' => '',
			'footer_scripts'     => '',
		);
		$defaults = array_merge(
			$defaults,
			$script_addons
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
		$sections['scripts'] = array(
			'title'    => __( 'Custom Scripts', 'kadence-pro' ),
			'priority' => 889,
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
			require_once KTP_PATH . 'dist/scripts-addon/' . $key . '-options.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}
}

Archive_Meta::get_instance();
