<?php
/**
 * Post Types.
 */

namespace WLEA\Admin;

/**
 * Class.
 */
class Post_Types {

	/**
     * Constructor.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'register' ) );

        add_filter( 'gutenberg_can_edit_post_type', array( $this, 'disable_gutenberg' ), 10, 2 );
        add_filter( 'use_block_editor_for_post_type', array( $this, 'disable_gutenberg' ), 10, 2 );
    }

    /**
     * Register.
     */
    public function register() {
        $this->email();
        $this->workflow();
    }

    /**
     * Email.
     */
    protected function email() {
        $post_type = 'wlea-email';

        $rewrite = array(
            'slug'       => $post_type,
            'with_front' => true,
            'feeds'      => false,
            'pages'      => false,
        );

        $labels = array(
            'name'                  => esc_html_x( 'Email Templates', 'Post Type General Name', 'woolentor-pro' ),
            'singular_name'         => esc_html_x( 'Email Template', 'Post Type Singular Name', 'woolentor-pro' ),
            'menu_name'             => esc_html_x( 'Email Automation', 'Admin Menu text', 'woolentor-pro' ),
            'name_admin_bar'        => esc_html_x( 'Email Template', 'Add New on Toolbar', 'woolentor-pro' ),
            'archives'              => esc_html__( 'Email Template Archives', 'woolentor-pro' ),
            'attributes'            => esc_html__( 'Email Template Attributes', 'woolentor-pro' ),
            'parent_item_colon'     => esc_html__( 'Parent Email Template:', 'woolentor-pro' ),
            'all_items'             => esc_html__( 'Marketing', 'woolentor-pro' ),
            'add_new_item'          => esc_html__( 'Add New Email Template', 'woolentor-pro' ),
            'add_new'               => esc_html__( 'Add New', 'woolentor-pro' ),
            'new_item'              => esc_html__( 'New Email Template', 'woolentor-pro' ),
            'edit_item'             => esc_html__( 'Edit Email Template', 'woolentor-pro' ),
            'update_item'           => esc_html__( 'Update Email Template', 'woolentor-pro' ),
            'view_item'             => esc_html__( 'View Email Template', 'woolentor-pro' ),
            'view_items'            => esc_html__( 'View Email Templates', 'woolentor-pro' ),
            'search_items'          => esc_html__( 'Search Email Template', 'woolentor-pro' ),
            'not_found'             => esc_html__( 'Not found', 'woolentor-pro' ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'woolentor-pro' ),
            'featured_image'        => esc_html__( 'Featured Image', 'woolentor-pro' ),
            'set_featured_image'    => esc_html__( 'Set featured image', 'woolentor-pro' ),
            'remove_featured_image' => esc_html__( 'Remove featured image', 'woolentor-pro' ),
            'use_featured_image'    => esc_html__( 'Use as featured image', 'woolentor-pro' ),
            'insert_into_item'      => esc_html__( 'Insert into Email Template', 'woolentor-pro' ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this Email Template', 'woolentor-pro' ),
            'items_list'            => esc_html__( 'Email Templates list', 'woolentor-pro' ),
            'items_list_navigation' => esc_html__( 'Email Templates list navigation', 'woolentor-pro' ),
            'filter_items_list'     => esc_html__( 'Filter Email Templates list', 'woolentor-pro' ),
        );

        $args = array(
            'label'               => esc_html__( 'Email Template', 'woolentor-pro' ),
            'description'         => esc_html__( 'Email Template', 'woolentor-pro' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor' ),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'hierarchical'        => false,
            'exclude_from_search' => true,
            'show_in_rest'        => true,
            'rest_base'           => $post_type,
            'publicly_queryable'  => false,
            'capability_type'     => 'page',
            'rewrite'             => $rewrite,
        );

        register_post_type( $post_type, $args );
    }

    /**
     * Workflow.
     */
    protected function workflow() {
        $post_type = 'wlea-workflow';

        $rewrite = array(
            'slug'       => $post_type,
            'with_front' => true,
            'feeds'      => false,
            'pages'      => false,
        );

        $labels = array(
            'name'                  => esc_html_x( 'Workflows', 'Post Type General Name', 'woolentor-pro' ),
            'singular_name'         => esc_html_x( 'Workflow', 'Post Type Singular Name', 'woolentor-pro' ),
            'menu_name'             => esc_html_x( 'Workflow Automation', 'Admin Menu text', 'woolentor-pro' ),
            'name_admin_bar'        => esc_html_x( 'Workflow', 'Add New on Toolbar', 'woolentor-pro' ),
            'archives'              => esc_html__( 'Workflow Archives', 'woolentor-pro' ),
            'attributes'            => esc_html__( 'Workflow Attributes', 'woolentor-pro' ),
            'parent_item_colon'     => esc_html__( 'Parent Workflow:', 'woolentor-pro' ),
            'all_items'             => esc_html__( 'Marketing', 'woolentor-pro' ),
            'add_new_item'          => esc_html__( 'Add New Workflow', 'woolentor-pro' ),
            'add_new'               => esc_html__( 'Add New', 'woolentor-pro' ),
            'new_item'              => esc_html__( 'New Workflow', 'woolentor-pro' ),
            'edit_item'             => esc_html__( 'Edit Workflow', 'woolentor-pro' ),
            'update_item'           => esc_html__( 'Update Workflow', 'woolentor-pro' ),
            'view_item'             => esc_html__( 'View Workflow', 'woolentor-pro' ),
            'view_items'            => esc_html__( 'View Workflows', 'woolentor-pro' ),
            'search_items'          => esc_html__( 'Search Workflow', 'woolentor-pro' ),
            'not_found'             => esc_html__( 'Not found', 'woolentor-pro' ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'woolentor-pro' ),
            'featured_image'        => esc_html__( 'Featured Image', 'woolentor-pro' ),
            'set_featured_image'    => esc_html__( 'Set featured image', 'woolentor-pro' ),
            'remove_featured_image' => esc_html__( 'Remove featured image', 'woolentor-pro' ),
            'use_featured_image'    => esc_html__( 'Use as featured image', 'woolentor-pro' ),
            'insert_into_item'      => esc_html__( 'Insert into Workflow', 'woolentor-pro' ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this Workflow', 'woolentor-pro' ),
            'items_list'            => esc_html__( 'Workflows list', 'woolentor-pro' ),
            'items_list_navigation' => esc_html__( 'Workflows list navigation', 'woolentor-pro' ),
            'filter_items_list'     => esc_html__( 'Filter Workflows list', 'woolentor-pro' ),
        );

        $args = array(
            'label'               => esc_html__( 'Workflow', 'woolentor-pro' ),
            'description'         => esc_html__( 'Workflow', 'woolentor-pro' ),
            'labels'              => $labels,
            'supports'            => array( 'title' ),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'hierarchical'        => false,
            'exclude_from_search' => true,
            'show_in_rest'        => true,
            'rest_base'           => $post_type,
            'publicly_queryable'  => false,
            'capability_type'     => 'page',
            'rewrite'             => $rewrite,
        );

        register_post_type( $post_type, $args );
    }

    /**
     * Disable gutenberg.
     */
    public function disable_gutenberg( $can_edit, $post_type ) {
        if ( 'wlea-email' === $post_type || 'wlea-workflow' === $post_type ) {
            $can_edit = false;
        }

        return $can_edit;
    }

}