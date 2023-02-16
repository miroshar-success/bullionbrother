<?php
namespace Woolentor\Modules\Order_Bump;

// If this file is accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


class Customize_CPT{
    public $template_type;

    protected static $_instance = null;
    
    /**
     * Instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->template_type = isset( $_GET['template_type'] ) ? sanitize_text_field( wp_unslash( $_GET['template_type'] ) ) : '';

        // Remove editor from post type
        add_action('init', function(){
            if( $this->template_type == 'order-bump'){                
                remove_post_type_support( 'woolentor-template', 'editor' );
                remove_post_type_support( 'woolentor-template', 'custom-fields' );
            }
        }, 99 );

        // Rename post type labels
        add_action( 'registered_post_type_woolentor-template', [ $this, 'rename_post_type_labels' ], 10, 2 );

        // Hide unnacessary metaboxes from being shown
        add_action('add_meta_boxes', [ $this, 'remove_unnacessary_meta_boxes' ], 99, 2);

        // Set submenu as current submenu
        add_action( 'admin_head', [ $this, 'set_submenu_as_current_menu' ], -1 );
        
        // Redirect to order bump submenu after publish / update post
        add_filter( 'redirect_post_location', [ $this, 'redirect_after_post_published' ], 99 );
        add_action('save_post', [ $this, 'redirect_after_post_updated' ], 99);
        
        // Disable autosave
        add_action('admin_init', [ $this, 'disable_autosave' ] );

        // Addon body class
        add_filter( 'admin_body_class', [ $this, 'admin_body_class' ] );
    }

    /**
     * Change the labels of the post type where template_type is 'order-bump'
     * 
     * @param post_type The post type slug.
     * @param post_type_object The post type object.
     */
    public function rename_post_type_labels( $post_type, $post_type_object ){
        if( $this->template_type === 'order-bump' ){
            $post_type_object->labels->name = __( 'Order Bumps', 'woolentor-pro' );
            $post_type_object->labels->edit_item = __( 'Edit - Order Bump', 'woolentor-pro' );
            $post_type_object->labels->add_new_item = __( 'Add New - Order Bump', 'woolentor-pro' );
        }
    }

    /**
     * It removes all the meta boxes from the template edit screen except the publish meta box and the
     * order bump rules meta box.
     */
    public function remove_unnacessary_meta_boxes(){
        global $wp_meta_boxes;
        if( $this->template_type == 'order-bump'){

            $woolentor_meta_boxes = $wp_meta_boxes['woolentor-template'];
            $submitdiv            = $woolentor_meta_boxes['side']['core']['submitdiv'];
            $order_bump           = $woolentor_meta_boxes['advanced']['default']['_woolentor_order_bump'];
            $order_bump_rules     = $woolentor_meta_boxes['advanced']['default']['_woolentor_order_bump_rules'];

            unset($wp_meta_boxes['woolentor-template']);
            $wp_meta_boxes['woolentor-template'] = array(
                'side' => array(
                    'core' => array(
                        'submitdiv' => $submitdiv,
                    ),
                ),
                'advanced' => array(
                    'default' => array(
                        '_woolentor_order_bump' => $order_bump,
                        '_woolentor_order_bump_rules' => $order_bump_rules,
                    ),
                ),
            );
        }else{
            unset($wp_meta_boxes['woolentor-template']['advanced']['default']['_woolentor_order_bump'],$wp_meta_boxes['woolentor-template']['advanced']['default']['_woolentor_order_bump_rules']);
        }
    }

    /**
     * It sets the submenu page as the current submenu page.
     */
    public function set_submenu_as_current_menu(){
        global $submenu_file, $plugin_page;

        if( $this->template_type == 'order-bump' ){
            $submenu_file = 'woolentor-order-bump';
            $plugin_page = 'woolentor-order-bump';
        }
    }

    /**
     * It checks if the post type is `woolentor-template` and if the referer URL contains
     * `template_type=order-bump`. If both conditions are true, it adds the `template_type=order-bump`
     * query string to the redirect URL
     * 
     * @param location The URL to redirect to.
     * 
     * @return The location of the page.
     */
    function redirect_after_post_published( $location ){
        global $post;
    
        $referer = wp_get_referer();
        if( get_post_type($post) != 'woolentor-template' && !strpos($referer, 'template_type=order-bump') ){
            return $location;
        }
    
        $location = add_query_arg('template_type', 'order-bump', $location);
        update_post_meta( $post->ID, 'woolentor_template_meta_type', 'order-bump' );

        return $location;
    }

    /**
     * It checks if the post type is `woolentor-template` and if the referer URL contains
     * `template_type=order-bump`. If both conditions are true, it generate a new URL and add the `template_type=order-bump`
     * query string and redirects to it.
     */
    public function redirect_after_post_updated(){
        $referer   = wp_get_referer();

        if( strpos($referer, 'template_type=order-bump') && get_post_type() == 'woolentor-template' ){
            $url_parts = (array) parse_url($referer);

            if( isset($url_parts['query']) ){
                parse_str( $url_parts['query'], $query );
                $post_id = isset($query['post']) ? $query['post'] : '';

                if( $post_id ){
                    update_post_meta($post_id, 'woolentor_template_meta_type', 'order-bump' );
                    
                    $url = admin_url('post.php?post='.$post_id.'&action=edit&template_type=order-bump&mesage=6');
                    wp_redirect($url);
                    exit;
                }
            }
        }
    }

    /**
     * It disables the autosave feature for the order bump template.
     */
    public function disable_autosave() {
        if( $this->template_type == 'order-bump' ){
            wp_deregister_script('autosave');
        }
    }

    /**
     * Adds body class where `template_type=order-bump`.
     * 
     * @param classes The body classes.
     * 
     * @return The body classes.
     */
    public function admin_body_class( $classes ){
        if( $this->template_type == 'order-bump' ){
            $classes .= 'woolentor-order-bump';
        }
        return $classes;
    }
}