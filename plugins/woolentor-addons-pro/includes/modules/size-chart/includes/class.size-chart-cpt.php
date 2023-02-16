<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Size_Chart_CPT{

    const CPTTYPE = 'woolentor-size-chart';

    private static $_instance = null;
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    function __construct(){

        // Register post type
        add_action( 'init', [ $this, 'register_custom_post_type' ], 0 );

        // Disable gutenberg
        add_filter( 'gutenberg_can_edit_post_type', [ $this, 'gutenberg_can_edit_post_type' ], 10, 2 );
        add_filter( 'use_block_editor_for_post_type', [ $this, 'gutenberg_can_edit_post_type' ], 10, 2 );
        
        // Insert a sample size chart
        add_action('init', [ $this, 'insert_sample_size_chart' ] );

        // Admin menu tweaks
        add_action( 'admin_menu', [ $this, 'dashboard_menu_tweaks' ], 225 );
        add_filter( 'parent_file', [ $this, 'set_woolentor_menu_as_current_menu' ] );
    }

    /**
     * Register post type
     */
    public function register_custom_post_type() {

        $labels = array(
            'name'                  => esc_html_x('Size Chart', 'Post Type General Name', 'woolentor-pro'),
            'singular_name'         => esc_html_x('Size Chart', 'Post Type Singular Name', 'woolentor-pro'),
            'menu_name'             => esc_html__('Size Chart', 'woolentor-pro'),
            'name_admin_bar'        => esc_html__('Size Chart', 'woolentor-pro'),
            'archives'              => esc_html__('Size Chart Archives', 'woolentor-pro'),
            'attributes'            => esc_html__('Size Chart Attributes', 'woolentor-pro'),
            'parent_item_colon'     => esc_html__('Parent Item:', 'woolentor-pro'),
            'all_items'             => esc_html__('Size Charts', 'woolentor-pro'),
            'add_new_item'          => esc_html__('Add New Size Chart', 'woolentor-pro'),
            'add_new'               => esc_html__('Add New', 'woolentor-pro'),
            'new_item'              => esc_html__('New Size Chart', 'woolentor-pro'),
            'edit_item'             => esc_html__('Edit Size Chart', 'woolentor-pro'),
            'update_item'           => esc_html__('Update Size Chart', 'woolentor-pro'),
            'view_item'             => esc_html__('View Size Chart', 'woolentor-pro'),
            'view_items'            => esc_html__('View Size Charts', 'woolentor-pro'),
            'search_items'          => esc_html__('Search Size Charts', 'woolentor-pro'),
            'not_found'             => esc_html__('Not found', 'woolentor-pro'),
            'not_found_in_trash'    => esc_html__('Not found in Trash', 'woolentor-pro'),
            'featured_image'        => esc_html__('Size chart Image', 'woolentor-pro'),
            'set_featured_image'    => esc_html__('Set size chart image', 'woolentor-pro'),
            'remove_featured_image' => esc_html__('Remove image', 'woolentor-pro'),
            'use_featured_image'    => esc_html__('Use as size chart image', 'woolentor-pro'),
            'insert_into_item'      => esc_html__('Insert into Size Chart', 'woolentor-pro'),
            'uploaded_to_this_item' => esc_html__('Uploaded to this Size Chart', 'woolentor-pro'),
            'items_list'            => esc_html__('Size Charts', 'woolentor-pro'),
            'items_list_navigation' => esc_html__('Size Charts navigation', 'woolentor-pro'),
            'filter_items_list'     => esc_html__('Filter from charts', 'woolentor-pro'),
        );

        $args = array(
            'label'               => esc_html__('Size Chart', 'woolentor-pro'),
            'description'         => esc_html__('Size Chart', 'woolentor-pro'),
            'labels'              => $labels,
            'supports'            => array('title', 'editor', 'thumbnail', 'revisions'),
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'rewrite'             => array(
                'slug'       => 'woolentor-size-chart',
                'pages'      => false,
                'with_front' => true,
                'feeds'      => false,
            ),
            'query_var'           => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'page',
            'show_in_rest'        => true,
            'rest_base'           => self::CPTTYPE,
        );

        register_post_type( self::CPTTYPE, $args );

    }

    /**
     * Disable gutenberg editor
     */
    public function gutenberg_can_edit_post_type( $can_edit, $post_type ) {
        return self::CPTTYPE === $post_type ? false : $can_edit;
    }

    /**
     * Insert a sample size chart while the module is installed
     */
    public function insert_sample_size_chart(){
        if( get_option('woolentor_size_chart_sample_chart_created') ){
            return;
        }

        $chart_table = '[[\"INTERNATIONAL\",\"XS\",\"S\",\"M\",\"L\",\"XL\",\"XXL\",\"XXXL\"],[\"EUROPE\",\"32\",\"34\",\"36\",\"38\",\"40\",\"42\",\"44\"],[\"US\",\"0\",\"2\",\"4\",\"6\",\"-\",\"10\",\"12\"],[\"CHEST FIT (INCHES)\",\"30\\\"\",\"-\",\"34\\\"\",\"36\\\"\",\"38\\\"\",\"40\\\"\",\"42\\\"\"],[\"CHEST FIT (CM)\",\"716\",\"76\",\"81\",\"86\",\"91.5 \",\"96.5\",\"101.1\"],[\"WAIST FIR (INCHES)\",\"21\\\"\",\"23\\\"\",\"25\\\"\",\"-\",\"29\\\"\",\"31\\\"\",\"33\\\"\"],[\"WAIST FIR (CM)\",\"53.5\",\"58.5\",\"63.5\",\"68.5\",\"74\",\"79\",\"84\"],[\"HIPS FIR (INCHES)\",\"33\\\"\",\"34\\\"\",\"-\",\"38\\\"\",\"40\\\"\",\"-\",\"44\\\"\"],[\"HIPS FIR (CM)\",\"81.5\",\"86.5\",\"91.5 \",\"96.5 \",\"101\",\"106.5\",\"111.5\"],[\"SKORT LENGTHS (SM)\",\"36.5\",\"38\",\"39.5 \",\"41\",\"42.5 \",\"44\",\"45.5\"]]';

        $post_arr = array(
            'post_title'   =>  esc_html('Sample Size Chart'),
            'post_content' =>  esc_html('Your size chart description goes here.'),
            'post_status'  =>  'publish',
            'post_type'    => 'woolentor-size-chart',
            'meta_input'   => array(
                '_chart_table' => $chart_table,
            ),
        );
         
        // Insert the post into the database.
        wp_insert_post( $post_arr );
        update_option( 'woolentor_size_chart_sample_chart_created', true );
    }

    /**
     * Add Post type Submenu
     */
    public function dashboard_menu_tweaks(){
        $link_custom_post = 'edit.php?post_type=' . self::CPTTYPE;

        add_submenu_page(
            'woolentor_page',
            esc_html__('Size Charts', 'woolentor-pro'),
            esc_html__('Size Charts', 'woolentor-pro'),
            'manage_options',
            $link_custom_post,
            NULL
        );
    }

    /**
     * Set Woolentor while on post type pages
     */
    public function set_woolentor_menu_as_current_menu( $parent_file ){

        if( get_post_type() == 'woolentor-size-chart' ){
            $parent_file = 'woolentor_page';
        }
        return $parent_file;

    }


}