<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// return;
class Woolentor_Size_Chart{

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
        require( __DIR__.'/includes/class.size-chart-cpt.php' );
        require( __DIR__.'/includes/class.shortcodes.php' );
        require( __DIR__.'/includes/class.size-chart-admin.php' );

        // Enqueue Scripts
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );

        // Load woocommerce common assets to the cpt
        add_filter('woocommerce_screen_ids', [ $this, 'load_wc_default_scripts' ] );

        // Register CPT
        Woolentor_Size_Chart_CPT::instance(); 

        // Register shortcode for the size chart contents
        Woolentor_Size_Chart_Shortcodes::get_instance();
        
        // Render the size chart Button Popup / Tab
        $show_as = woolentor_get_option( 'show_as', 'woolentor_size_chart_settings' );

        if( $show_as == 'popup' ){
            $position = woolentor_get_option( 'popup_button_positon', 'woolentor_size_chart_settings', 'woocommerce_before_add_to_cart_form' );
            add_action( $position, [ $this, 'render_button_product_page' ] );
        } else {
            // Render size chart into a custom tab
            add_filter('woocommerce_product_tabs', array( $this, 'add_custom_tab' ) );
        }
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts(){
        wp_register_style( 'woolentor-size-chart', plugin_dir_url( __FILE__ ) . 'assets/css/size-chart.css' ,'', WOOLENTOR_VERSION, 'all' );
        wp_register_script( 'woolentor-size-chart', plugin_dir_url( __FILE__ ) . 'assets/js/size-chart.js', array('jquery'), WOOLENTOR_VERSION );

        if( is_product() ){
            wp_enqueue_style( 'woolentor-size-chart' );
            wp_enqueue_script( 'woolentor-size-chart' );
        }

        // To generate inline CSS
        wp_register_style( 'woolentor-size-chart-style', false );

    }

    /**
     * Admin scripts
     */
    public function admin_scripts(){
        // Only load on the specific area
        if( get_post_type() != 'woolentor-size-chart' ){
            return;
        }

        // Styles
        wp_enqueue_style('woocommerce_admin_styles');
        wp_enqueue_style( 'jquery-edittable', plugin_dir_url( __FILE__ ) . 'assets/css/jquery.edittable.min.css' ,'', WOOLENTOR_VERSION, 'all' );
        wp_enqueue_style( 'woolentor-size-chart-admin', plugin_dir_url( __FILE__ ) . 'assets/css/size-chart-admin.css' ,'', WOOLENTOR_VERSION, 'all' );

        // Scripts
        wp_enqueue_script( 'jquery-edittable', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.edittable.min.js', array('jquery'), WOOLENTOR_VERSION );
        wp_enqueue_script( 'woolentor-size-chart-admin', plugin_dir_url( __FILE__ ) . 'assets/js/size-chart-admin.js', array('jquery', 'jquery-tiptip', 'wc-clipboard'), WOOLENTOR_VERSION, true );
    }

    /**
     * WC scripts
     */
    public function load_wc_default_scripts( $screen_ids ){
        $screen_ids[] = 'woolentor-size-chart';

        return $screen_ids;
    }

    /**
     * Render size chart button
     */
    public function render_button_product_page(){
        $short_code_attributes = [
            'type' => 'popup',
        ];
        echo woolentor_do_shortcode( 'woolentor_size_chart', $short_code_attributes );
    }

    /**
     * Add a custom tab title
     */
    public function add_custom_tab( $tabs ){
        $additional_tab_label = woolentor_get_option( 'additional_tab_label', 'woolentor_size_chart_settings' );
        $additional_tab_label = $additional_tab_label ? $additional_tab_label : __('Size Chart', 'woolentor-pro');

        $chart_props = $this->get_assigned_chart( get_the_id() );

        if($chart_props){
            $tabs['woolentor_size_chart_tab'] = array(
                'title'    => $additional_tab_label,
                'callback' => array( $this, 'render_custom_tab_content' ),
                'priority' => 60
            );
        }

        return $tabs;
    }

    /**
     * Render the chart into the custom tab
     */
    public function render_custom_tab_content(){
        $short_code_attributes = [];
        echo woolentor_do_shortcode( 'woolentor_size_chart', $short_code_attributes );
    }

    /**
     * Loop through the repeater field and get the first matched chart for the given product
     */
    public static function get_assigned_chart( $current_product_id ){
        $current_product    = wc_get_product($current_product_id);
        $chart_query = new WP_Query(array(
            'post_type'      => 'woolentor-size-chart',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ));

        $chart_props = array();

        if( is_product() && woolentor_is_elementor_editor_mode() != true ){
            if( $chart_query->have_posts() ){
                while( $chart_query->have_posts() ){
                    $chart_query->the_post();
                    $chart_id = get_the_id();

                    if( self::check_if_product_assigned_on_chart( $current_product, $chart_id ) ){
                        $chart_props = self::get_chart_props($chart_id);
                        break;
                    }
                }
            }
            wp_reset_query();
        }

        return $chart_props;
    }

    public static function get_chart_props( $chart_id ){
        $chart_table     = get_post_meta($chart_id, '_chart_table', true);
        $chart_table_arr = (array) json_decode(get_post_meta($chart_id, '_chart_table', true));

        if( empty($chart_table_arr) || $chart_table == '[[""]]' ){
            $chart_table_arr = array();
        }

        $apply_on_all_products = get_post_meta($chart_id, '_apply_on_all_products', true);
        $applicable_categories = wc_get_product_cat_ids($chart_id);

        $applicable_products   = get_post_meta($chart_id, '_products', true);
        $applicable_products   = $applicable_products ? explode( ',', $applicable_products ) : array();

        $exclude_products      = get_post_meta($chart_id, '_exclude_products', true);
        $exclude_products      = $exclude_products ? explode( ',', $exclude_products ) : array();

        // Other options
        $hide_thumbnail   = get_post_meta( $chart_id, '_hide_thumbnail', true );
        $hide_desc        = get_post_meta( $chart_id, '_hide_desc', true );
        $hide_chart_table = get_post_meta( $chart_id, '_hide_chart_table', true );

        return array(
            'chart_id'              => $chart_id,
            'chart_table'           => $chart_table_arr,
            'apply_on_all_products' => $apply_on_all_products,
            'categories'            => $applicable_categories,
            'products'              => $applicable_products,
            'exclude_products'      => $exclude_products,
            'hide_thumbnail'        => $hide_thumbnail,
            'hide_desc'             => $hide_desc,
            'hide_chart_table'      => $hide_chart_table,
        );
    }

    /**
     * Check & validate if the given product is assigned into the chart or not
     */
    public static function check_if_product_assigned_on_chart( $product, $chart_id ){
        $validity              = false;

        $chart_props = self::get_chart_props( $chart_id );

        $apply_on_all_products = $chart_props['apply_on_all_products'];
        $applicable_categories = $chart_props['categories'];
        $applicable_products   = $chart_props['products'];
        $exclude_products      = $chart_props['exclude_products'];

        if( ! $product ){
            return false;
        }

        // Exlcude products
        if( in_array( $product->get_id(), $exclude_products ) ){
            return false;
        }

        if( $apply_on_all_products ){
            return true;
        } elseif( $applicable_categories || $applicable_products ) {
            $current_product_categories = wc_get_product_term_ids( $product->get_id(), 'product_cat' );
            if( array_intersect( $applicable_categories, $current_product_categories ) ){
                return true;
            } elseif( in_array($product->get_id(), $applicable_products) ){
                return true;
            }
        }

        return $validity;
    }
    
}

Woolentor_Size_Chart::get_instance();