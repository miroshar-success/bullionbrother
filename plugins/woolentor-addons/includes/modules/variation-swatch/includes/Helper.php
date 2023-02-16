<?php  
namespace Woolentor\Modules\Swatchly;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Helper{

    private static $_instance = null;

    /**
     * Instance
     */
    public static function instance(){
        if( is_null( self::$_instance ) ){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct(){
        
    }

    /**
     * Get global options value.
     *
     * @since 1.0.0
     *
     * @param string   $option_name Option name.
     * @param null $default Default value.
     * @param null $override_for Override global options for product list/product details page. Accepted values are: pl, sp
     *
     * @return string|null
     */
    public static function get_option( $option_name = '', $default = null, $override_for = null ) {
        $options = get_option( 'woolentor_swatch_settings' );

        $global_general_setting_fields = array(
            'enable_swatches',
            'auto_convert_dropdowns_to_label',
            'auto_convert_dropdowns_to_image',
            'auto_convert_dropdowns_to_image_condition',
            'swatch_width',
            'swatch_height',
            'tooltip',
            'show_swatch_image_in_tooltip',
            'shape_style',
            'enable_shape_inset',
            'shape_inset_size',
            'deselect_on_click',
            'show_selected_attribute_name',
            'disabled_attribute_type',
            'disable_out_of_stock',
        );
    
        if($override_for == 'sp' && isset($options['sp_override_global']) && $options['sp_override_global']){
            $opt_name = 'sp_'. $option_name;
    
            if(in_array($option_name, $global_general_setting_fields)){
                $option_name = $opt_name;
            }
        }
    
        if($override_for == 'pl' && isset($options['pl_override_global']) && $options['pl_override_global']){
            $opt_name = 'pl_'. $option_name;
    
            if(in_array($option_name, $global_general_setting_fields)){
                $option_name = $opt_name;
            }
        }
        
        $option_value = isset( $options[$option_name] ) ? $options[$option_name] : $default;

        // CS was saved checkbox value as 1 but WL uses on.
        // So convert it to 1
        if( $option_value === 'on' ){
            $option_value = 1;
        }

        return $option_value;
    }

    /**
     * Get product meta options value.
     *
     * @since 1.0.0
     *
     * @param int   $product_id Product ID.
     * @param string   $option_name Option name.
     * @param null $default Default value.
     *
     * @return string|null
     */
    public static function get_product_meta( $product_id, $taxonomy, $option_name = '', $default = '') {
        // product override
        $product_meta = get_post_meta( $product_id, '_swatchly_product_meta', true );
    
        $meta_value = isset( $product_meta[$taxonomy][$option_name] ) && $product_meta[$taxonomy][$option_name] ? $product_meta[$taxonomy][$option_name] : $default;
    
        return $meta_value;
    }

    /**
     * Get image sizes.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public static function get_image_sizes() {
        global $_wp_additional_image_sizes;
    
        $image_sizes = array();
        $default_image_sizes = array( 'thumbnail', 'medium', 'medium_large', 'large'  );
        foreach ( $default_image_sizes as $size ) {
            $image_sizes[$size]['width']  = intval( get_option( "{$size}_size_w") );
            $image_sizes[$size]['height'] = intval( get_option( "{$size}_size_h") );
            $image_sizes[$size]['crop']   = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
        }
        
        if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ){
            $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
        }
            
        return array_keys($image_sizes);
    }

    /**
     * Get swatch type by taxonomy name
     *
     * @since 1.0.0
     *
     * @param string   $taxonomy Taxonomy name.
     * @param null $product_id Product id.
     *
     * @return string
     */
    public static function get_swatch_type( $taxonomy, $product_id = null ) {
        $swatch_type = 'select';
    
        // txonomy override
        if( taxonomy_exists( $taxonomy ) ){
            global $wpdb;
    
            $attr        = substr( $taxonomy, 3 );
            $attr        = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = %s", $attr ) );
            $swatch_type = isset($attr->attribute_type) ? $attr->attribute_type : '';
    
            if( is_admin() ){
                return $swatch_type;
            }
        }
    
        // product override
        if( $product_id ){
            $product_meta                    = get_post_meta( $product_id, '_swatchly_product_meta', true );
            $auto_convert_dropdowns_to_label = isset($product_meta['auto_convert_dropdowns_to_label']) ? $product_meta['auto_convert_dropdowns_to_label'] : '';
            $swatch_type                     = isset( $product_meta[$taxonomy]['swatch_type'] ) && $product_meta[$taxonomy]['swatch_type'] ? $product_meta[$taxonomy]['swatch_type'] : $swatch_type;    
        }
    
        return $swatch_type;
    }

    /**
     * It takes an option name, and returns a CSS string if the option is set
     * 
     * @param opt_name The name of the option you want to retrieve.
     * @param args 
     */
    public static function add_inline_css( $args ){
        /* Checking if the value is empty, if it is empty it will get the value from the get_option
        function. */
        $opt_name   =  !empty($args['opt_name']) ? $args['opt_name'] : '';
        $value      = !empty($args['value']) ? $args['value'] : SELF::get_option($opt_name);

        $selectors  = !empty($args['selectors']) && is_array($args['selectors']) ? $args['selectors'] : array();
        $properties = !empty($args['properties']) ? explode(',', $args['properties']) : array();
        $unit       = !empty($args['unit']) ? $args['unit'] : '';

        if( $value ){
            if( is_array($selectors) ){
                $selectors = implode(',', $selectors);
            }
            
            $properties_css = '';
            foreach($properties as $property){
                $properties_css .= "$property: {$value}{$unit};";
            }

            return "$selectors{
                $properties_css
            }";
        }
    
        return null;
    }

    // Manage Image size key
    public static function manage_image_size( $width ){
        if( $width > 150 ){
            $image_size_key = 'medium';
        }else if( $width > 300 ){
            $image_size_key = 'large';
        }else if( $width > 1024 ){
            $image_size_key = 'full';
        }else{
            $image_size_key = 'thumbnail';
        }
        return $image_size_key;
    }

    /**
     * If the request is an AJAX request and the action is elementor, then it's an Elementor preview
     */
    public static function doing_ajax_is_elementor_preview(){
        $server       = wp_unslash( $_SERVER );

        $referer          = !empty($server['referer']) ? $server['referer'] : '';
        $request_uri      = !empty($server['REQUEST_URI']) ? $server['REQUEST_URI'] : '';

        parse_str($referer, $query_str_arr);
        parse_str($request_uri, $request_uri_arr);

        if( !empty($query_str_arr['action']) && $query_str_arr['action'] == 'elementor' ||
            !empty($request_uri_arr['action']) && $request_uri_arr['action'] == 'elementor'
        ){
            return true;
        }

        return false;
    }

}

Helper::instance();