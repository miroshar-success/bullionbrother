<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Template_CPT{

    const CPTTYPE = 'woolentor-template';

    private static $_instance = null;
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct(){
        add_action( 'init', [ $this, 'register_custom_post_type' ] );
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
		add_action('save_post', [ $this, 'metabox_save_data' ] );
    }

    public function register_custom_post_type() {

		$labels = array(
			'name'                  => esc_html_x('Template Builder', 'Post Type General Name', 'woolentor'),
			'singular_name'         => esc_html_x('Template Builder', 'Post Type Singular Name', 'woolentor'),
			'menu_name'             => esc_html__('Template', 'woolentor'),
			'name_admin_bar'        => esc_html__('Template', 'woolentor'),
			'archives'              => esc_html__('Template Archives', 'woolentor'),
			'attributes'            => esc_html__('Template Attributes', 'woolentor'),
			'parent_item_colon'     => esc_html__('Parent Item:', 'woolentor'),
			'all_items'             => esc_html__('Templates', 'woolentor'),
			'add_new_item'          => esc_html__('Add New Template', 'woolentor'),
			'add_new'               => esc_html__('Add New', 'woolentor'),
			'new_item'              => esc_html__('New Template', 'woolentor'),
			'edit_item'             => esc_html__('Edit Template', 'woolentor'),
			'update_item'           => esc_html__('Update Template', 'woolentor'),
			'view_item'             => esc_html__('View Template', 'woolentor'),
			'view_items'            => esc_html__('View Templates', 'woolentor'),
			'search_items'          => esc_html__('Search Templates', 'woolentor'),
			'not_found'             => esc_html__('Not found', 'woolentor'),
			'not_found_in_trash'    => esc_html__('Not found in Trash', 'woolentor'),
			'featured_image'        => esc_html__('Featured Image', 'woolentor'),
			'set_featured_image'    => esc_html__('Set featured image', 'woolentor'),
			'remove_featured_image' => esc_html__('Remove featured image', 'woolentor'),
			'use_featured_image'    => esc_html__('Use as featured image', 'woolentor'),
			'insert_into_item'      => esc_html__('Insert into Template', 'woolentor'),
			'uploaded_to_this_item' => esc_html__('Uploaded to this Template', 'woolentor'),
			'items_list'            => esc_html__('Templates list', 'woolentor'),
			'items_list_navigation' => esc_html__('Templates list navigation', 'woolentor'),
			'filter_items_list'     => esc_html__('Filter from list', 'woolentor'),
		);

		$args = array(
			'label'               => esc_html__('Template Builder', 'woolentor'),
			'description'         => esc_html__('WooLentor Template', 'woolentor'),
			'labels'              => $labels,
			'supports'            => array('title', 'editor', 'elementor', 'author', 'permalink'),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'rewrite'             => array(
				'slug'       => 'woolentor-template',
				'pages'      => false,
				'with_front' => true,
				'feeds'      => false,
			),
			'query_var'           => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_rest'        => true,
			'rest_base'           => self::CPTTYPE,
		);

		register_post_type( self::CPTTYPE, $args );

		// Flash rewrite rules
		$this->flush_rewrite_rules();

	}

	/**
     * [flush_rewrite_rules] Flash rewrite rules
     * @return [void]
     */
    public function flush_rewrite_rules() {
        if( get_option('woolentor_plugin_permalinks_flushed', TRUE ) !== 'yes' ) {
            flush_rewrite_rules();
            update_option( 'woolentor_plugin_permalinks_flushed', 'yes' );
        }
    }

	/**
	 * Add Metaboxes
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
        add_meta_box(
			'container_width', 
			esc_html__('Container Width', 'woolentor'), 
			[ $this, 'container_width_field' ], 
			self::CPTTYPE, 
			'side'
		);
    }

	/**
	 * Container field HTML
	 *
	 * @param [object] $post
	 * @return void
	 */
	public function container_width_field( $post ) {
        wp_nonce_field( 'woolentor_container_width', 'woolentor_container_width_nonce' );

        $get_width = get_post_meta( $post->ID, '_woolentor_container_width', true );
		$width 	   = $get_width ? $get_width : (int)woolentorBlocks_get_option( 'container_width', 'woolentor_gutenberg_tabs', 1140 );

		?>
			<p>
				<input style="width:100%" type="number" name="container_width" value="<?php echo esc_attr( $width ); ?>" />
			</p>
    	<?php
	}

	/**
	 * Save Meta box data
	 *
	 * @param [int] $post_id
	 * @return void
	 */
	public function metabox_save_data( $post_id ) {

        if ( ! isset( $_POST['woolentor_container_width_nonce'] ) ) {
            return;
        }
        
        if ( ! wp_verify_nonce( $_POST['woolentor_container_width_nonce'], 'woolentor_container_width' ) ) {
            return;
        }
        
        if ( ! isset( $_POST['container_width'] ) ) {
            return;
        }
        
        $width = sanitize_text_field( $_POST['container_width'] );
        update_post_meta( $post_id, '_woolentor_container_width', $width );

    }

}