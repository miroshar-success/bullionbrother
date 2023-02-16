<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Automattic\Jetpack\Constants;

class Woolentor_Size_Chart_Admin{

    private static $_instance = null;

    /**
     * Get Instance
     */
    public static function get_instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct(){
        // Add metabox
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
        add_action( 'save_post', [ $this, 'save_meta_boxes' ], 1, 2 );

        // Add custom column
        add_filter('manage_woolentor-size-chart_posts_columns' , [ $this, 'add_custom_column_name' ] );
        add_filter('manage_woolentor-size-chart_posts_custom_column' , [ $this, 'render_custom_column_data' ], 10, 2 );
    }

    /**
     * Add metaboxes
     */
    public function add_meta_boxes() {
        add_meta_box( 'wl-size-chart', __( 'Chart Table', 'woolentor-pro' ), array( $this, 'render_edittable' ), 'woolentor-size-chart', 'advanced', 'default' );
        add_meta_box( 'wl-size-chart-assign', __( 'Assign Chart', 'woolentor-pro' ), array( $this, 'render_assign_meta_fields' ), 'woolentor-size-chart', 'side', 'default' );
        add_meta_box( 'wl-size-chart-additional', __( 'Other Options', 'woolentor-pro' ), array( $this, 'render_additional_meta_fields' ), 'woolentor-size-chart', 'side', 'low' );
    }

    /**
     * Render assign meta field inputs
     */
    public function render_assign_meta_fields(){
        global $post;

        $apply_on_all_products = get_post_meta( $post->ID, '_apply_on_all_products', true );

        $products = get_post_meta( $post->ID, '_products', true );
        $products = $products ? explode(',', $products) : array();

        $exclude_products = get_post_meta( $post->ID, '_exclude_products', true );
        $exclude_products = $exclude_products ? explode(',', $exclude_products) : array();

        // Apply on all products
        woocommerce_wp_checkbox(
            array(
                'id'            => '_apply_on_all_products',
                'value'         => $apply_on_all_products,
                'wrapper_class' => 'wl-checkbox',
                'label'         => __( 'Apply On All Products?', 'woolentor-pro' ),
            )
        );

        // Category
        $box = array('args' => array(
            'taxonomy' => 'product_cat'
        ));
        echo '<div class="form-field wl-categories">';
            echo '<label>'. __('Select Categories', 'woolentor-pro'). wc_help_tip( __( 'Select the categories in which products the "Size Chart" will be displayed.', 'woolentor-pro' ) ).'</label>';
            post_categories_meta_box($post, $box);
        echo '</div>';

        // Select products
        ?>
        <p class="form-field wl-products">
            <label for="_products"><?php esc_html_e( 'Select Products', 'woolentor-pro' ); ?><?php echo wc_help_tip( __( 'Select individual products in which the "Size Chart" will be displayed.', 'woolentor-pro' ) );?></label>
            <select class="wc-product-search" multiple="multiple" style="min-width: 250px;" id="_products" name="_products[]" data-sortable="false" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woolentor-pro' ); ?>" data-action="woocommerce_json_search_products" data-minimum_input_length="3" data-exclude_type="variation">
                <?php
                foreach ( $products as $product_id ) {
                    $product = wc_get_product( $product_id );
                    if ( is_object( $product ) ) {
                        echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ) . '</option>';
                    }
                }
                ?>
            </select>
        </p>

        <p class="form-field wl-exclude-products">
            <label for="_exclude_products"><?php esc_html_e( 'Exclude Products', 'woolentor-pro' ); ?></label>
            <select class="wc-product-search" multiple="multiple" style="min-width: 250px;" id="_exclude_products" name="_exclude_products[]" data-sortable="false" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woolentor-pro' ); ?>" data-action="woocommerce_json_search_products" data-minimum_input_length="3" data-exclude_type="variation">
                <?php
                foreach ( $exclude_products as $product_id ) {
                    $product = wc_get_product( $product_id );
                    if ( is_object( $product ) ) {
                        echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ) . '</option>';
                    }
                }
                ?>
            </select>
        </p>
        <?php
    }

    /**
     * Render the edittable into the metabox
     */
    public function render_edittable(){
        global $post;
        $chart_table = get_post_meta( $post->ID, '_chart_table', true );

        wp_nonce_field( 'wl_size_chart_save_data', 'wl_size_chart_meta_nonce' );
        ?>
        <p class="form-field">
            <textarea id="_chart_table" style="display: none;" name="_chart_table"><?php echo esc_html($chart_table); ?></textarea>
        </p>
        <?php
    }

    /**
     * Render additional meta field inputs
     */
    public function render_additional_meta_fields(){
        global $post;

        $hide_thumbnail   = get_post_meta( $post->ID, '_hide_thumbnail', true );
        $hide_desc        = get_post_meta( $post->ID, '_hide_desc', true );
        $hide_chart_table = get_post_meta( $post->ID, '_hide_chart_table', true );

        echo '<strong>'. __('Hide Elements', 'woolentor-pro') .'</strong>' . wc_help_tip(__('Checking the box hides the corresponding item in the frontend even you have this set within this size chart', 'woolentor-pro'));
        
        // Hide thumbnail
        woocommerce_wp_checkbox(
            array(
                'id'            => '_hide_thumbnail',
                'value'         => $hide_thumbnail,
                'wrapper_class' => 'wl-checkbox',
                'label'         => __( 'Thumbnail', 'woolentor-pro' ),
            )
        );

        // description
        woocommerce_wp_checkbox(
            array(
                'id'            => '_hide_desc',
                'value'         => $hide_desc,
                'wrapper_class' => 'wl-checkbox',
                'label'         => __( 'Description', 'woolentor-pro' ),
            )
        );

        // chart table
        woocommerce_wp_checkbox(
            array(
                'id'            => '_hide_chart_table',
                'value'         => $hide_chart_table,
                'wrapper_class' => 'wl-checkbox',
                'label'         => __( 'Chart Table', 'woolentor-pro' ),
            )
        );
    }

    /**
     * Save metabox fields
     */
    public function save_meta_boxes(  $post_id, $post  ){
        $post_id = absint( $post_id );

        // $post_id and $post are required
        if ( empty( $post_id ) || empty( $post ) ) {
            return;
        }

        // Dont' save meta boxes for revisions or autosaves.
        if ( Constants::is_true( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
            return;
        }

        // Check the nonce.
        if ( empty( $_POST['wl_size_chart_meta_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['wl_size_chart_meta_nonce'] ), 'wl_size_chart_save_data' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            return;
        }

        // Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
        if ( empty( $_POST['post_ID'] ) || absint( $_POST['post_ID'] ) !== $post_id ) {
            return;
        }

        // Check user has permission to edit.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Update metabox
        $chart_table = isset( $_POST['_chart_table'] ) ? wc_clean( $_POST['_chart_table'] ) : '';
        update_post_meta( $post_id, '_chart_table', $chart_table );

        // Update assign values
        $apply_on_all_products = isset( $_POST['_apply_on_all_products'] ) ? wc_clean( wp_unslash( $_POST['_apply_on_all_products'] ) ) : '';
        
        // Applicable products
        $products = isset( $_POST['_products'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['_products'] ) ) : array();
        $products = $products ? implode(',', $products) : '';

        // Exluce products
        $exclude_products = isset( $_POST['_exclude_products'] ) ? array_map( 'intval', (array) wp_unslash( $_POST['_exclude_products'] ) ) : array();
        $exclude_products = $exclude_products ? implode(',', $exclude_products) : '';

        update_post_meta( $post_id, '_apply_on_all_products', $apply_on_all_products );
        update_post_meta( $post_id, '_products', $products );
        update_post_meta( $post_id, '_exclude_products', $exclude_products );

        // Additional options
        $hide_thumbnail   = isset( $_POST['_hide_thumbnail'] ) ? wc_clean( wp_unslash( $_POST['_hide_thumbnail'] ) ) : '';
        $hide_desc        = isset( $_POST['_hide_desc'] ) ? wc_clean( wp_unslash( $_POST['_hide_desc'] ) ) : '';
        $hide_chart_table = isset( $_POST['_hide_chart_table'] ) ? wc_clean( wp_unslash( $_POST['_hide_chart_table'] ) ) : '';

        update_post_meta( $post_id, '_hide_thumbnail', $hide_thumbnail );
        update_post_meta( $post_id, '_hide_desc', $hide_desc );
        update_post_meta( $post_id, '_hide_chart_table', $hide_chart_table );
    }

    /**
     * Add column title
     */
    public function add_custom_column_name($columns) {
        $custom_col_order = array(
            'cb'          => $columns['cb'],
            'title'       => $columns['title'],
            'product_cat' => __( 'Assigned Categories', 'woolentor-pro' ),
            'products'    => __( 'Assigned Products', 'woolentor-pro' ),
            'exclude'     => __( 'Exclude', 'woolentor-pro' ),
            'shortcode'   => __( 'Shortcode', 'woolentor-pro' ),
            'date'        => $columns['date']
        );

        return $custom_col_order;
    }

    /**
     * Display value for the column
     */
    public function render_custom_column_data( $column_name, $chart_id ){
        if( $column_name == 'shortcode' ){
            $shortcode    = '[woolentor_size_chart id="'. $chart_id .'"]';
            $success_text = __('Copied!', 'woolentor-pro');
            $failed_text  = __('Copying to clipboard failed. You should be able to right-click the button and copy.', 'woolentor-pro');

            printf( "<input type='text' readonly='readonly' value='%s' data-tip='%s' data-tip-failed='%s'>", $shortcode, $success_text, $failed_text );
        }

        if( $column_name == 'product_cat' ){
            $props = Woolentor_Size_Chart::get_chart_props($chart_id);

            if( $props['apply_on_all_products'] == 'yes' ){
                 echo __( 'N/A', 'woolentor-pro' );
            } elseif($props['categories']){
                array_map(function($term_id){
                    $term_object = get_term_by( 'id', $term_id, 'product_cat' );
                    echo $term_object->name . '<span class="wl-comma">,</span>';
                }, $props['categories']);
            } else{
                echo '—';
            }
        }

        if( $column_name == 'products' ){
            $props = Woolentor_Size_Chart::get_chart_props($chart_id);

            if( $props['apply_on_all_products'] == 'yes' ){
                echo __( 'All', 'woolentor-pro' );
            } elseif($props['products']){
                array_map(function($product_id){
                    echo get_the_title($product_id) . '<span class="wl-comma">,</span>';
                }, $props['products']);
            } else{
                echo '—';
            }
        }

        if( $column_name == 'exclude' ){
            $props = Woolentor_Size_Chart::get_chart_props($chart_id);
            
            if( $props['exclude_products'] ){
                array_map(function($product_id){
                    echo get_the_title($product_id) . '<span class="wl-comma">,</span>';
                }, $props['exclude_products']);
            } else{
                echo '—';
            }
        }
        
    }
}

Woolentor_Size_Chart_Admin::get_instance();