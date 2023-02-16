<?php
namespace Woolentor\Modules\Swatchly\Frontend;
use  Woolentor\Modules\Swatchly\Helper as Helper;

/**
 * Woo_Config class
 */
class Woo_Config {
    public $sp_enable_swatches;
    public $pl_enable_swatches;

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->sp_enable_swatches = Helper::get_option('sp_enable_swatches');
        $this->pl_enable_swatches = Helper::get_option('pl_enable_swatches');

        // Enable/disable swatch for out of stock variation products
        add_filter( 'woocommerce_available_variation', array($this, 'filter_out_of_stock_variation'), 9999, 3 );

        // Filter through each variation form to inject the swatch html
        add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array( $this, 'dropdown_variation_attribute_options_html_cb' ), 200, 2 );

        $current_theme   = woolentor_get_current_theme_directory();

        // before title
        if( $this->pl_enable_swatches && Helper::get_option('pl_position') == 'before_title' ){
            if( $current_theme == 'astra' ){
                add_action('astra_woo_shop_title_before', array( $this, 'loop_variation_form_html'));
            } else {
                add_action('woocommerce_shop_loop_item_title', array( $this, 'loop_variation_form_html'), 0);
            }

            // For Universal Layout
            add_action('woolentor_universal_before_title', array( $this, 'loop_variation_form_html'), 0 );
        }

        // after title
        if( $this->pl_enable_swatches &&  Helper::get_option('pl_position') == 'after_title' ){
            if( $current_theme == 'astra' ){
                add_action('astra_woo_shop_title_after', array( $this, 'loop_variation_form_html'));
            } else {
                add_action('woocommerce_shop_loop_item_title', array( $this, 'loop_variation_form_html'), 9999);
            }
            
            // For Universal Layout
            add_action('woolentor_universal_after_title', array( $this, 'loop_variation_form_html'), 0 );
        }

        // before price
        if( $this->pl_enable_swatches &&  Helper::get_option('pl_position') == 'before_price' ){
            if( $current_theme == 'astra' ){
                add_action('astra_woo_shop_price_before', array( $this, 'loop_variation_form_html'));
            } else {
                add_action('woocommerce_after_shop_loop_item_title', array( $this, 'loop_variation_form_html'), 9);
            }

            // For Universal Layout
            add_action('woolentor_universal_before_price', array( $this, 'loop_variation_form_html'), 0 );
        }

        // after price
        if( $this->pl_enable_swatches &&  Helper::get_option('pl_position') == 'after_price' ){
            if( $current_theme == 'astra' ){
                add_action('astra_woo_shop_price_after', array( $this, 'loop_variation_form_html'));
            } else {
                add_action('woocommerce_after_shop_loop_item_title', array( $this, 'loop_variation_form_html'), 11);
            }

            // For Universal Layout
            add_action('woolentor_universal_after_price', array( $this, 'loop_variation_form_html'), 0 );
        }

        // custom position
        if( $this->pl_enable_swatches && Helper::get_option('pl_position') == 'custom_position'){
            $priority = Helper::get_option('pl_custom_position_hook_priority') ? Helper::get_option('pl_custom_position_hook_priority') : 10;
            add_action( Helper::get_option('pl_custom_position_hook_name') , array( $this, 'loop_variation_form_html'), $priority );
        }

        if(  $this->pl_enable_swatches && Helper::get_option('pl_position') == 'shortcode'){
            // shortcode [swatchly_pl_swatches]
            add_shortcode( 'swatchly_pl_swatches', array( $this, 'get_loop_variation_form_html') );
        }

        // Ajax add to cart
        add_filter( 'woocommerce_loop_add_to_cart_args', array( $this, 'filter_loop_add_to_cart_args' ), 20, 2 );

        // Ajax variation threshold
        add_filter( 'woocommerce_ajax_variation_threshold', array( $this, 'ajax_variation_threshold') , 15, 2 );
    }

    /**f
     * Intented to use for the conditionally enable/disable swatch for product listing pages
     * The codition comes from the user input
     */
    public function stop_execution(){
        $enable_swatches = $this->pl_enable_swatches;
        if(is_product()){
            $enable_swatches = $this->sp_enable_swatches;
        }

        if( (!is_product() && !$enable_swatches) || 
            (is_product() && !$enable_swatches)
        ){
            return true;
        }

        $input_condition =  Helper::get_option('pl_user_input_condition');
        if( $input_condition ){
            $input_condition =  "return (". $input_condition .");";

            if( is_product() ){
                return false;
            } elseif( !eval( $input_condition ) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * If "opt-in" Disable the variation form for the "Out of Stock" products
     * Since the variation form will not render, so the swatches will not also render for all "out of stock" variation products.
     * 
     * @return bool
     */
    public function filter_out_of_stock_variation( $result, $object, $variation ) {
        $disable_out_of_stock = Helper::get_option('disable_out_of_stock');

        if( is_product() ){
            $disable_out_of_stock = Helper::get_option('disable_out_of_stock');
        }

        if( !$disable_out_of_stock ){
            return $result;
        }

        if ( ! $variation->is_in_stock() || 
            ( $variation->managing_stock() && $variation->get_stock_quantity() <= get_option( 'woocommerce_notify_no_stock_amount', 0 ) && $variation->get_backorders()  === 'no' )
        ) {
            $result = false;
        }

        return $result;
    }

    /**
     * Filter variation dropdown HTML
     * Consists each of the <select></select> element
     */
    public function dropdown_variation_attribute_options_html_cb(  $old_html,  $args ){
        $product     = $args['product'];
        
        if( is_admin() && !wp_doing_ajax() && !Helper::doing_ajax_is_elementor_preview() ){
            return $old_html;
        }
        
        // return default select for the grouped products for the single page and also for the 
        // smart group products created by the WPC Grouped Product for WooCommerce plugin
        $should_return_default_select = false;
        if( is_product() ){
            $single_product = wc_get_product(get_the_id());
            if( $single_product->get_type() == 'grouped' || $single_product->get_type() == 'woosg' ){
                $should_return_default_select = true;
            }   
        }

        $should_return_default_select = apply_filters( 'swatchly_return_default_select', $should_return_default_select, $args );
        if( $should_return_default_select ){
            return $old_html;
        }

        if( $this->stop_execution() ){
            return $old_html;
        }

        $background_color   = '';
        $enable_multi_color = '';
        $background_color_2 = '';
        $background_image   = '';
        $tooltip_image      = '';
        $tooltip_image_size = '';
        $image_id           = '';
        
        $product_id           = $product->get_id();
        $product_meta         = (array) get_post_meta( $product_id, '_swatchly_product_meta', true );
        $taxonomy             = $args['attribute'];
        $variation_attributes = $product->get_variation_attributes();

        $meta_data                       = get_post_meta($product_id, '_swatchly_product_meta', true);
        $swatch_type                     = Helper::get_swatch_type( $taxonomy, $product_id );
        $taxonomy_exists                 = taxonomy_exists( $taxonomy );
        $tooltip                         = Helper::get_option('tooltip', '', 'pl');
        $show_swatch_image_in_tooltip    = Helper::get_option('show_swatch_image_in_tooltip', '', 'pl');
        $shape_style                     = Helper::get_option('shape_style', '', 'pl');
        $enable_shape_inset              = Helper::get_option('enable_shape_inset', '', 'pl');
        $disabled_attribute_type         = Helper::get_option('disabled_attribute_type',  '', 'pl');
        $auto_convert_dropdowns_to_label = Helper::get_option('auto_convert_dropdowns_to_label', '', 'pl');
        $auto_convert_dropdowns_to_image = Helper::get_option('auto_convert_dropdowns_to_image', '', 'pl');
        $auto_convert_dropdowns_to_image_condition = Helper::get_option('auto_convert_dropdowns_to_image_condition', '', 'pl');

        if(is_product()){
            $tooltip                         = Helper::get_option('tooltip', '', 'sp');
            $show_swatch_image_in_tooltip    = Helper::get_option('show_swatch_image_in_tooltip', '', 'sp');
            $shape_style                     = Helper::get_option('shape_style', '', 'sp');
            $enable_shape_inset              = Helper::get_option('enable_shape_inset', '', 'sp');
            $disabled_attribute_type         = Helper::get_option('disabled_attribute_type',  '', 'sp');
            $auto_convert_dropdowns_to_label = Helper::get_option('auto_convert_dropdowns_to_label', '', 'sp');
            $auto_convert_dropdowns_to_image = Helper::get_option('auto_convert_dropdowns_to_image', '', 'sp');
            $auto_convert_dropdowns_to_image_condition = Helper::get_option('auto_convert_dropdowns_to_image_condition', '', 'sp');
        }

        // Override product level meta
        $auto_convert_dropdowns_to_label = isset($product_meta['auto_convert_dropdowns_to_label']) && $product_meta['auto_convert_dropdowns_to_label'] ? $product_meta['auto_convert_dropdowns_to_label'] : $auto_convert_dropdowns_to_label;

        // dropdown to image
        $auto_convert_dropdowns_to_image_p_meta = isset($product_meta['auto_convert_dropdowns_to_image']) && $product_meta['auto_convert_dropdowns_to_image'] ? $product_meta['auto_convert_dropdowns_to_image'] : '';
        $auto_convert_dropdowns_to_image        = $auto_convert_dropdowns_to_image_p_meta ? $auto_convert_dropdowns_to_image_p_meta : $auto_convert_dropdowns_to_image;

        // dropdown to image based on
        $auto_convert_dropdowns_to_image_condition_p_meta = isset($product_meta['auto_convert_dropdowns_to_image_condition']) && $product_meta['auto_convert_dropdowns_to_image_condition'] ? $product_meta['auto_convert_dropdowns_to_image_condition'] : '';
        $auto_convert_dropdowns_to_image_condition        = ( $auto_convert_dropdowns_to_image_p_meta && $auto_convert_dropdowns_to_image_condition_p_meta) ? $auto_convert_dropdowns_to_image_condition_p_meta : $auto_convert_dropdowns_to_image_condition;
        

        // Override Product attribute taxonomy level
        $shape_style = Helper::get_product_meta($product_id, $taxonomy, 'shape_style', $shape_style);
        $enable_shape_inset = Helper::get_product_meta($product_id, $taxonomy, 'enable_shape_inset', $enable_shape_inset);
        
        if($enable_shape_inset == 'disable' || !$enable_shape_inset){
            $inset_class = '';
        } elseif($enable_shape_inset){
            $inset_class    = 'swatchly-inset';
        }
        
        $disabled_attribute_type_class = str_replace( '_', '-', $disabled_attribute_type );

        // Disable ajax add to cart when catalog mode is enabled
        $enable_catalog_mode = Helper::get_option('pl_enable_catalog_mode');
        if( $enable_catalog_mode ){
            $enable_ajax_add_to_cart = false;
        } else {
            $enable_ajax_add_to_cart = Helper::get_option('pl_enable_ajax_add_to_cart');
        }

        // Check if we've found any swatch type from attribute meta / product meta
        $found_swatch_type = in_array( $swatch_type, array( 'label', 'color', 'image') );

        // Decide & get which attribute key should be used for auto convert image type swatch (pro)
        $auto_convert_image_arr = array();
        if( $auto_convert_dropdowns_to_image ){

            if($auto_convert_dropdowns_to_image_condition){
                $count_attrs = array();

                foreach($variation_attributes as $key => $variation_attribute){
                    $count_attrs[$key] = count($variation_attribute);
                }

                if($auto_convert_dropdowns_to_image_condition == 'minimum'){

                    $selected_attribute_key_for_auto_convert_to_image = array_search( min($count_attrs), $count_attrs );

                } elseif($auto_convert_dropdowns_to_image_condition == 'maximum'){

                    $selected_attribute_key_for_auto_convert_to_image = array_search( max($count_attrs), $count_attrs );

                } else {

                    $selected_attribute_key_for_auto_convert_to_image = array_key_first( $variation_attributes );

                }
            }

            // If the current dropdown match with the targeted attribute
            // Change the swatch type to "image"
            // Collect the variation images from the all attribtues
            // If an attribute does not have any image from the variation row into admin panel
            // Then the loop will be continued untill an image found for the attribute
            $allow_using_auto_image_for_empty_image_swatch = apply_filters('swatchly_allow_using_auto_image_for_empty_image_swatch', !$found_swatch_type );
            if( $allow_using_auto_image_for_empty_image_swatch && ($args['attribute'] == $selected_attribute_key_for_auto_convert_to_image) ){

                foreach( $variation_attributes[$selected_attribute_key_for_auto_convert_to_image] as $index => $value ){
                    foreach ( $product->get_available_variations() as $key => $variation ) {
                        $varation_image_id = $variation['image_id'];

                        if( in_array($value, $variation['attributes']) && $varation_image_id ){
                            $auto_convert_image_arr[$value] = $varation_image_id;

                            if( $variation['image_id'] ){
                                break;
                            }                        
                        }

                    }
                }
            }
        }

        // Decide which swatch type should be assigned for this attribute
        if( !$found_swatch_type && $auto_convert_dropdowns_to_label && !$auto_convert_dropdowns_to_image ){
            // var_dump(1);
            $swatch_type = 'label';

        } elseif( !$found_swatch_type && !$auto_convert_dropdowns_to_label && $auto_convert_dropdowns_to_image ){
            // var_dump(2);
            if( $args['attribute'] == $selected_attribute_key_for_auto_convert_to_image ){
                $swatch_type = 'image';
            } else {
                return $old_html;
            }

        } elseif( !$found_swatch_type && $auto_convert_dropdowns_to_label && $auto_convert_dropdowns_to_image ){
            // var_dump(3);
            $swatch_type = $found_swatch_type ? $swatch_type : 'label';
            if( $args['attribute'] == $selected_attribute_key_for_auto_convert_to_image ){
                $swatch_type = 'image';
            }

        } elseif( !is_product() && !$found_swatch_type &&  ($enable_ajax_add_to_cart || $enable_catalog_mode) ){
            // var_dump(4);
            $swatch_type = 'label';

        } elseif( !$found_swatch_type ){
            // var_dump(5);
            return $old_html;

        }

        // Featured Attribute class (pro)
        $featured_class = '';
        $enable_featured_attribute = Helper::get_option('enable_featured_attribute');
        if( $enable_featured_attribute ){
            $featured_attribute = Helper::get_option('featured_attribute_global');
            $featured_attribute = $featured_attribute ? 'pa_'. $featured_attribute : Helper::get_option('featured_attribute_custom');

            if( ($taxonomy_exists && $featured_attribute == $taxonomy) ||  $featured_attribute == $taxonomy){
                $featured_class = 'swatchly-featured';
            }
        }

        // Add class to hide/exclude specific variation row
        $hide_this_variation_row_class = '';
        $hide_this_variation_row = apply_filters( 'swatchly_exclude_variation', false, $args );
        if( $hide_this_variation_row ){
            $hide_this_variation_row_class = 'swatchly-hide-this-variation-row';
        }

        $selected           = $args['selected'];
        $current_term       = get_term_by( 'slug', $selected, $taxonomy );
        $current_term_label = $current_term ? $current_term->name : $selected;

        $html = "<div class='swatchly_default_select $hide_this_variation_row_class'>";
        $html .= $old_html;
        $html .= '</div>';

        if( $hide_this_variation_row_class ){
            // return the default select, since this variation is hidden by css
            // don't load the further custom swatch markup, which is not needed anymore for this variation row
            return $html; 
        }

        $attr_class = "class='swatchly-type-wrap swatchly-shape-type-$shape_style swatchly-type-$swatch_type $inset_class swatchly-$disabled_attribute_type_class $featured_class $hide_this_variation_row_class'";
        $attr_default_attr_value  = "data-default_attr_value='$current_term_label'";
        $html .= "<div $attr_class $attr_default_attr_value>";

            if ( taxonomy_exists( $taxonomy ) ) {
                $attribute_terms  = array();
                $terms_with_order_support = wc_get_product_terms(
                    $product->get_id(),
                    $taxonomy,
                    array(
                        'fields' => 'all',
                    )
                );

                // Collect all the term slugs, used array_map instead of array_colums beacuse of PHP 5.3 version compatibility
                $attribute_terms = array_map( function( $x ) { return $x->slug; }, $terms_with_order_support );

                foreach ( $attribute_terms as $index => $term_value ) {
                    $term = get_term_by('slug', $term_value, $taxonomy);
                    $image_id = ( isset($auto_convert_image_arr[$image_id]) && $auto_convert_image_arr[$image_id] ) ? $auto_convert_image_arr[$image_id] : '';

                    $count = $index + 1;
                    $tooltip_text = $term->name;

                    // Tooltip (Global level)
                    if( is_product() ) {
                        $tooltip = Helper::get_option('tooltip', '', 'sp');
                    } else {
                        $tooltip = Helper::get_option('tooltip', '', 'pl');
                    }

                    // Tooltip override (Term level)
                    $tooltip2         = get_term_meta( $term->term_id, 'swatchly_tooltip', true );
                    if($tooltip2 == 'disable'){
                        $tooltip = false;
                    }

                    $tooltip_text2 = get_term_meta( $term->term_id, 'swatchly_tooltip_text', true );
                    if($tooltip2 == 'text'){
                        $tooltip_text = $tooltip_text2;
                    }

                    $tooltip_image2 = get_term_meta( $term->term_id, 'swatchly_tooltip_image', true );
                    $tooltip_image_size2 = get_term_meta( $term->term_id, 'swatchly_tooltip_image_size', true );
                    if($tooltip2 == 'image'){
                        $tooltip = true;
                        $tooltip_image = $tooltip_image2['id'];
                        $tooltip_image_size = $tooltip_image_size2;
                    }

                    // Tooltip override (Product level taxonomy option)
                    if(isset($meta_data[$term->taxonomy]) && is_array($meta_data[$term->taxonomy])){
                        $p_tax_meta             = $meta_data[$term->taxonomy];
                        $p_tax_meta_swatch_type = $p_tax_meta['swatch_type'];

                        if( in_array($p_tax_meta_swatch_type, array('label', 'color', 'image')) ){
                            $tooltip3 = $p_tax_meta['tooltip'];

                            if($tooltip3 == 'disable'){
                                $tooltip = false;
                            }
                            
                            $tooltip_text3 = $p_tax_meta['tooltip_text'];
                            $tooltip_image3 = $p_tax_meta['tooltip_image'];
                            $tooltip_image_size3 = $p_tax_meta['tooltip_image_size'];
                            if($tooltip3 == 'text'){
                                $tooltip = true;
                                $tooltip_text = $tooltip_text3;
                            }

                            if($tooltip3 == 'image'){
                                $tooltip = true;
                                $tooltip_image = $tooltip_image3;
                                $tooltip_image_size = $tooltip_image_size3;
                            }
                        }
                    }

                    // Tooltip override (Product level term option)
                    if(isset($meta_data[$term->taxonomy]['terms'][$term->term_id]) && is_array($meta_data[$term->taxonomy]['terms'][$term->term_id])){
                        $p_tax_meta             = $meta_data[$term->taxonomy];
                        $p_tax_meta_swatch_type = $p_tax_meta['swatch_type'];

                        if( in_array($p_tax_meta_swatch_type, array('label', 'color', 'image')) ){
                            $p_term_meta = $meta_data[$term->taxonomy]['terms'][$term->term_id];
                            $tooltip4 = $p_term_meta['tooltip'];

                            if($tooltip4 == 'disable'){
                                $tooltip = false;
                            }

                            $tooltip_text4 = $p_term_meta['tooltip_text'];
                            $tooltip_image4 = $p_term_meta['tooltip_image'];
                            $tooltip_image_size4 = $p_term_meta['tooltip_image_size'];
                            if($tooltip4 == 'text'){
                                $tooltip      = true;
                                $tooltip_text = $tooltip_text4;
                            }

                            if($tooltip4 == 'image'){
                                $tooltip            = true;
                                $tooltip_image      = $tooltip_image4;
                                $tooltip_image_size = $tooltip_image_size4;
                            }
                        }
                    }

                    // Term Meta
                    $background_color   = get_term_meta( $term->term_id, 'swatchly_color', true );
                    $enable_multi_color = get_term_meta( $term->term_id, 'swatchly_enable_multi_color', true );
                    $background_color_2 = get_term_meta( $term->term_id, 'swatchly_color_2', true );
                    $image_arr          = get_term_meta( $term->term_id, 'swatchly_image', true );
                    $image_id           = isset($image_arr['id']) ? $image_arr['id'] : '';

                    $selected_class = ($term->slug == $selected) ? 'swatchly-selected' : '';

                    // Product level term options Override
                    if(isset($meta_data[$term->taxonomy]['terms'][$term->term_id]) && is_array($meta_data[$term->taxonomy]['terms'][$term->term_id])){
                        $p_term_meta = $meta_data[$term->taxonomy]['terms'][$term->term_id];
                        
                        if( !empty($p_term_meta['swatch_type']) && $p_term_meta['swatch_type'] == 'color'){
                            $swatch_type        = isset($p_term_meta['swatch_type']) ? $p_term_meta['swatch_type'] : '';
                            $background_color   = isset($p_term_meta['color']) ?  $p_term_meta['color'] : '';
                            $enable_multi_color = isset($p_term_meta['enable_multi_color']) ? $p_term_meta['enable_multi_color']: '';
                            $background_color_2 = isset($p_term_meta['color_2']) ? $p_term_meta['color_2'] : '';
                        }

                        if( !empty($p_term_meta['swatch_type']) && $p_term_meta['swatch_type'] == 'image'){
                            $swatch_type = isset($p_term_meta['swatch_type']) ? $p_term_meta['swatch_type'] : '';
                            $image_id    = isset($p_term_meta['image']) ? $p_term_meta['image'] : '';
                        }

                        if( !empty($p_term_meta['swatch_type']) && $p_term_meta['swatch_type'] == 'label'){
                        }
                    }

                    // HTML Markup
                    $swatch_width_height = Helper::get_option('swatch_width_height');
                    $tooltip_width_height = Helper::get_option('tooltip_width_height');
                    $swatch_width        = !empty($swatch_width_height['width']) ? $swatch_width_height['width'] : '';
                    $tooltip_width       = !empty($tooltip_width_height['width']) ? $tooltip_width_height['width'] : '';

                    // Images Size key
                    $swatch_image_key = Helper::manage_image_size($swatch_width);
                    $tooltip_image_key = Helper::manage_image_size($tooltip_width);

                    $swatch_image_size  = apply_filters('swatchly_swatch_image_size', $swatch_image_key);
                    $tooltip_image_size = apply_filters('swatchly_tooltip_image_size', $tooltip_image_key);
                    $tooltip_image      = wp_get_attachment_image_url( $tooltip_image, $tooltip_image_size );
                    $attr_class         = "class='swatchly-swatch $selected_class'";
                    $attr_value         = "data-attr_value='$term->slug'";
                    $attr_label         = "data-attr_label='$term->name'";
                    $attr_tooltip_text  = $tooltip && $tooltip_text ? 'data-tooltip_text="'. esc_attr($tooltip_text) .'"' : '';
                    $attr_tooltip_image = $tooltip && $tooltip_image ? 'data-tooltip_image="'. esc_attr($tooltip_image) .'"' : '';

                    if($swatch_type == 'label'){
                        $html .= "<div $attr_class $attr_tooltip_text $attr_tooltip_image $attr_label $attr_value>";
                            $html .= '<span class="swatchly-content"><span class="swatchly-text">'. esc_html($term->name) .'</span></span>';
                        $html .= '</div>';

                    }

                    if($swatch_type == 'color'){
                        $attr_inline_style = $background_color ? "style='background-color: $background_color;'" : '';

                        if($enable_multi_color){
                            $attr_inline_style = $background_color_2 ? "style='background: linear-gradient(-50deg, $background_color 50%, $background_color_2 50%);'" : '';
                        }

                        $html .= "<div $attr_class $attr_inline_style $attr_tooltip_text $attr_tooltip_image $attr_label $attr_value>";
                            $html .= '<span class="swatchly-content"></span>';
                        $html .= '</div>';
                    }

                    if($swatch_type == 'image'){
                        if( $auto_convert_dropdowns_to_image && !$image_id && (isset($auto_convert_image_arr[$term_value]) && $auto_convert_image_arr[$term_value]) ){
                            $image_id = $auto_convert_image_arr[$term_value];
                        }

                        $background_image   = $image_id ? wp_get_attachment_image_url( $image_id, $swatch_image_size ) : wc_placeholder_img_src('woocommerce_gallery_thumbnail');
                        $attr_inline_style  = $background_image ? " style='background-image: url( $background_image );'" : '';
                        $attr_inline_style  = $background_image ? " style='background-image: url( $background_image );'" : '';

                        if( $show_swatch_image_in_tooltip ){
                            $background_image   = $image_id ? wp_get_attachment_image_url( $image_id, $tooltip_image_size ) : wc_placeholder_img_src('woocommerce_gallery_thumbnail');
                            $attr_tooltip_image = $tooltip && $background_image ? 'data-tooltip_image="'. esc_attr($background_image) .'"' : '';
                        }

                        $html .= "<div $attr_class $attr_inline_style $attr_tooltip_text $attr_tooltip_image $attr_label $attr_value>";
                            $html .= '<span class="swatchly-content"></span>';
                        $html .= '</div>';
                    }

                    // More text/button
                    if( Helper::get_option('pl_enable_swatch_limit') && $count == Helper::get_option('pl_limit') && !is_product() ){
                        $more_swatch_count = count($variation_attributes) - $count;
                        if($more_swatch_count){
                            $html .= $this->get_more_icon_html($product, $more_swatch_count);
                        }

                        break;
                    }
                }
            } else{
                $attachment_id = '';
                $custom_variation = $args['options'];

                foreach( $custom_variation as $index => $variation_name ){
                    $count = $index + 1;
                    $tooltip_text = $variation_name;
                    $image_id = ( isset($auto_convert_image_arr[$variation_name]) && $auto_convert_image_arr[$variation_name] ) ? $auto_convert_image_arr[$variation_name] : '';

                    // Tooltip (Global level)
                    if( is_product() ) {
                        $tooltip = Helper::get_option('tooltip', '', 'sp');
                    } else {
                        $tooltip = Helper::get_option('tooltip', '', 'pl');
                    }

                    // Tooltip override (Product level taxonomy option)
                    if(isset($meta_data[$taxonomy]) && is_array($meta_data[$taxonomy])){
                        $p_tax_meta = $meta_data[$taxonomy];
                        $p_tax_meta_swatch_type = $p_tax_meta['swatch_type'];

                        if( in_array($p_tax_meta_swatch_type, array('label', 'color', 'image')) ){
                            $tooltip3 = isset($p_tax_meta['tooltip']) ?  $p_tax_meta['tooltip'] : '';

                            if($tooltip3 == 'disable'){
                                $tooltip = false;
                            }
                            
                            $tooltip_text3 = isset($p_tax_meta['tooltip_text']) ?  $p_tax_meta['tooltip_text'] : '';
                            $tooltip_image3 = isset($p_tax_meta['tooltip_image']) ? $p_tax_meta['tooltip_image'] : '';
                            $tooltip_image_size3 = isset($p_tax_meta['tooltip_image_size']) ? $p_tax_meta['tooltip_image_size'] : '';
                            if($tooltip3 == 'text'){
                                $tooltip = true;
                                $tooltip_image = '';
                                $tooltip_text = $tooltip_text3;
                            }

                            if($tooltip3 == 'image'){
                                $tooltip = true;
                                $tooltip_image = $tooltip_image3;
                                $tooltip_image_size = $tooltip_image_size3;
                            }
                        }
                    }

                    // Tooltip override (Product level term option)
                    if(isset($meta_data[$taxonomy]['terms'][$variation_name]) && is_array($meta_data[$taxonomy]['terms'][$variation_name])){
                        $p_term_meta = $meta_data[$taxonomy]['terms'][$variation_name];

                        if( in_array($p_tax_meta_swatch_type, array('label', 'color', 'image')) ){
                            $tooltip4 = $p_term_meta['tooltip'];

                            if($tooltip4 == 'disable'){
                                $tooltip = false;
                            }

                            $tooltip_text4 = isset($p_term_meta['tooltip_text']) ? $p_term_meta['tooltip_text'] :  '';
                            $tooltip_image4 = isset($p_term_meta['tooltip_image']) ?  $p_term_meta['tooltip_image'] :  '';
                            $tooltip_image_size4 = isset($p_term_meta['tooltip_image_size']) ? $p_term_meta['tooltip_image_size'] : '';
                            if($tooltip4 == 'text'){
                                $tooltip = true;
                                $tooltip_image = '';
                                $tooltip_text = $tooltip_text4;
                            }

                            if($tooltip4 == 'image'){
                                $tooltip = true;
                                $tooltip_image = $tooltip_image4;
                                $tooltip_image_size = $tooltip_image_size4;
                            }
                        }
                    }


                    if(isset($meta_data[$taxonomy]['terms'][$variation_name]) && isset($meta_data[$taxonomy]['terms'][$variation_name])){
                        $p_term_meta = $meta_data[$taxonomy]['terms'][$variation_name];
                        
                        if(isset($p_term_meta['swatch_type']) && $p_term_meta['swatch_type'] == 'color'){
                            $swatch_type        = isset($p_term_meta['swatch_type']) ? $p_term_meta['swatch_type'] : '';
                            $background_color   = isset($p_term_meta['color']) ? $p_term_meta['color'] : '';
                            $enable_multi_color = isset($p_term_meta['enable_multi_color']) ?  $p_term_meta['enable_multi_color'] : '';
                            $background_color_2 = isset($p_term_meta['color_2']) ? $p_term_meta['color_2'] : '';
                        }

                        if(isset($p_term_meta['swatch_type']) && $p_term_meta['swatch_type'] == 'image'){
                            $swatch_type = isset($p_term_meta['swatch_type']) ? $p_term_meta['swatch_type'] : '';
                            $image_id    = isset($p_term_meta['image']) ? $p_term_meta['image'] : '';
                        }

                        if(isset($p_term_meta['swatch_type']) && $p_term_meta['swatch_type'] == 'label'){
                        }
                    }

                    if(!in_array($swatch_type, array('label', 'color', 'image'))){
                        break;
                    }

                    // HTML Markup
                    $selected_class = ($variation_name == $selected) ? 'swatchly-selected' : '';

                    $swatch_width_height = Helper::get_option('swatch_width_height');
                    $tooltip_width_height = Helper::get_option('tooltip_width_height');
                    $swatch_width        = !empty($swatch_width_height['width']) ? $swatch_width_height['width'] : '';
                    $tooltip_width       = !empty($tooltip_width_height['width']) ? $tooltip_width_height['width'] : '';

                    // Images Size key
                    $swatch_image_key = Helper::manage_image_size($swatch_width);
                    $tooltip_image_key = Helper::manage_image_size($tooltip_width);

                    $swatch_image_size  = apply_filters('swatchly_swatch_image_size', $swatch_image_key);
                    $tooltip_image_size = apply_filters('swatchly_tooltip_image_size', $tooltip_image_key);
                    $tooltip_image      = wp_get_attachment_image_url( $tooltip_image, $tooltip_image_size );
                    $attr_class         = "class='swatchly-swatch $selected_class'";
                    $attr_label         = "data-attr_label='$variation_name'";
                    $attr_value         = "data-attr_value='$variation_name'";
                    $attr_tooltip_text  = $tooltip && $tooltip_text ? 'data-tooltip_text="'. esc_attr($tooltip_text) .'"' : '';
                    $attr_tooltip_image = $tooltip && $tooltip_image ? 'data-tooltip_image="'. esc_attr($tooltip_image) .'"' : '';

                    if($swatch_type == 'label'){
                        $html .= "<div $attr_class $attr_tooltip_text $attr_tooltip_image $attr_label $attr_value>";
                            $html .= '<span class="swatchly-content"><span class="swatchly-text">'. esc_html($variation_name) .'</span></span>';
                        $html .= '</div>';

                    }

                    if($swatch_type == 'color'){
                        $attr_inline_style = $background_color ? "style='background-color: $background_color;'" : '';

                        if($enable_multi_color){
                            $attr_inline_style = $background_color_2 ? "style='background: linear-gradient(-50deg, $background_color 50%, $background_color_2 50%);'" : '';
                        }

                        $html .= "<div $attr_class $attr_inline_style $attr_tooltip_text $attr_tooltip_image $attr_label $attr_value>";
                            $html .= '<span class="swatchly-content"></span>';
                        $html .= '</div>';
                    }

                    if($swatch_type == 'image'){
                        if( $auto_convert_dropdowns_to_image && !$image_id && (isset($auto_convert_image_arr[$variation_name]) && $auto_convert_image_arr[$variation_name]) ){
                            $image_id = $auto_convert_image_arr[$variation_name];
                        }

                        $background_image   = $image_id ? wp_get_attachment_image_url( $image_id, $swatch_image_size ) : wc_placeholder_img_src('woocommerce_gallery_thumbnail');
                        $attr_inline_style  = $background_image ? " style='background-image: url( $background_image );'" : '';

                        if( $show_swatch_image_in_tooltip ){
                            $attr_tooltip_image = $tooltip && $background_image ? 'data-tooltip_image="'. esc_attr($background_image) .'"' : '';
                        }

                        $html .= "<div $attr_class $attr_inline_style $attr_tooltip_text $attr_tooltip_image $attr_label $attr_value>";
                            $html .= '<span class="swatchly-content"></span>';
                        $html .= '</div>';
                    }

                    // More text/button
                    if( Helper::get_option('pl_enable_swatch_limit') && $count == Helper::get_option('pl_limit') && !is_product() ){
                        $more_swatch_count = count($custom_variation) - $count;
                        if($more_swatch_count){
                            $html .= $this->get_more_icon_html($product, $more_swatch_count);
                        }
                        break;
                    }
                }
            }
        $html .= '</div> <!-- /.swatchly-type-wrap --> ';

        return $html;
    }

    /**
     * More icon for the shop page
     */
    public function get_more_icon_html( $product, $more_swatch_count ){
        $pl_more_text_link_arr    = Helper::get_option('pl_more_text_link');
        $pl_more_text_link_url    = esc_url($pl_more_text_link_arr['url'] ? $pl_more_text_link_arr['url'] : $product->get_permalink());
        $pl_more_text_link_target = esc_attr($pl_more_text_link_arr['target']);

        $html = '';
        if( Helper::get_option('pl_more_text_type') == 'text' ){
            $more_text = Helper::get_option('pl_more_text');
            $more_text = $more_text ? $more_text : esc_html__('More', 'woolentor');
            

            $html .= '<div class="swatchly-more-button">';
                $html .= "<a href='$pl_more_text_link_url' target='$pl_more_text_link_target' class='swatchly-content'><span>+</span><span>$more_swatch_count</span> <span>$more_text </span></a>";
            $html .= '</div>';

        } else {

            $attr_data_tooltip_text = '';
            if(Helper::get_option('pl_more_icon_enable_tooltip')){
                $pl_more_icon_tooltip_text = Helper::get_option('pl_more_icon_tooltip_text');
                $attr_data_tooltip_text = $pl_more_icon_tooltip_text ? 'data-tooltip_text="'. esc_attr($pl_more_icon_tooltip_text) .'"' : 'data-tooltip_text="'. esc_attr('More Options', 'woolentor') .'"';
            }

            $html .= "<a href='$pl_more_text_link_url' target='$pl_more_text_link_target' class='swatchly-swatch swatchly-more-button' $attr_data_tooltip_text>";
                $html .= '<span class="swatchly-content"></span>';
            $html .= '</a>';
        }

        return $html;
    }

    /**
     * Filter loop add cart button to insert the variation form.
     */
    public function filter_loop_add_to_cart_link( $html, $product ){
        if( $this->stop_execution() ){
            return;
        }

        $position = Helper::get_option('pl_position');

        if( !in_array($position, array( 'before_cart', 'after_cart' )) ){
            return $html;
        }

        if( $position == 'before_cart' ){
            $html = $this->get_loop_variation_form_html() . $html;
        } else {
            $html =  $html . $this->get_loop_variation_form_html();
        }

        return $html;
    }

    /**
     * Loop variation form HTML
     */
    public function get_loop_variation_form_html(){
        if( $this->stop_execution() ){
            return;
        }

        global $product;
        if ( ! $product->is_type( 'variable' ) ) {
            return;
        }

        // hide out of stock meesage if the product is not in stock & user opt-in to hide the message
        $hide_out_of_stock_message = apply_filters('swatchly_hide_out_of_stock_message_for_loop_product', false);
        if( !$product->get_available_variations() && $hide_out_of_stock_message ){
            return;
        }

        $align = Helper::get_option('pl_align');

        // Enqueue variation scripts.
        wp_enqueue_script( 'swatchly-add-to-cart-variation' );

        // Get Available variations?
        $get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
        $available_variations = $get_variations ? $product->get_available_variations() : false;
        $attributes           = $product->get_variation_attributes();
        $selected_attributes  = $product->get_default_attributes();

        $attribute_keys  = array_keys( $attributes );
        $variations_json = wp_json_encode( $available_variations );
        $variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

        $html = '';
        ob_start();
        ?>
            <form class="swatchly_loop_variation_form variations_form swatchly_align_<?php echo esc_attr($align); ?>" data-product_variations="<?php echo esc_attr( $variations_json ); ?>" data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">

                <?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
                    <p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woolentor' ) ) ); ?></p>
                <?php else : ?>
                    <table class="variations" cellspacing="0">
                        <tbody>
                            <?php foreach ( $attributes as $attribute_name => $options ) : ?>
                                <tr>
                                    <?php if(Helper::get_option('pl_show_swatches_label')): ?>
                                    <td class="label"><label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label></td>
                                    <?php endif; ?>

                                    <td class="value">
                                        <?php
                                            wc_dropdown_variation_attribute_options(
                                                array(
                                                    'options'   => $options,
                                                    'attribute' => $attribute_name,
                                                    'product'   => $product,
                                                )
                                            );

                                            if(Helper::get_option('pl_show_clear_link')){
                                                echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woolentor' ) . '</a>' ) ) : '';
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </form> <!-- .swatchly_loop_variation_form -->
        <?php

        $html = ob_get_clean();
        return $html;
    }

    /**
     * Loop variation form HTML
     */
    public function loop_variation_form_html(){
        if( $this->stop_execution() ){
            return;
        }

        // Prevent showing variation form for gutenberg editor in the shop page
        $api_url = !empty($_SERVER['REQUEST_URI']) ? strtok($_SERVER['REQUEST_URI'], '?') : '';
        $pattern = "/(wp-json|woolentor)/";
        $matches = preg_match_all($pattern, $api_url);
        if( $matches == 2 ){
            return;
        }

        global $product;
        if ( ! $product->is_type( 'variable' ) ) {
            return;
        }

        // hide out of stock meesage if the product is not in stock & user opt-in to hide the message
        $hide_out_of_stock_message = apply_filters('swatchly_hide_out_of_stock_message_for_loop_product', false);
        if( !$product->get_available_variations() && $hide_out_of_stock_message ){
            return;
        }

        $align = Helper::get_option('pl_align');

        // Enqueue variation scripts.
        wp_enqueue_script( 'swatchly-add-to-cart-variation' );

        // Get Available variations?
        $get_variations       = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
        $available_variations = $get_variations ? $product->get_available_variations() : false;
        $attributes           = $product->get_variation_attributes();
        $selected_attributes  = $product->get_default_attributes();

        $attribute_keys  = array_keys( $attributes );
        $variations_json = wp_json_encode( $available_variations );
        $variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
        ?>
            <div class="swatchly_loop_variation_form variations_form swatchly_align_<?php echo esc_attr($align); ?>" data-product_variations="<?php echo esc_attr( $variations_json ); ?>" data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">

                <?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
                    <p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woolentor' ) ) ); ?></p>
                <?php else : ?>
                    <table class="variations" cellspacing="0">
                        <tbody>
                            <?php
                                // Catalog mode support for shop page
                                $enable_catalog_mode       = Helper::get_option('pl_enable_catalog_mode');
                                $global_catalog_attributes = (array) Helper::get_option('pl_catalog_global_attributes');
                                foreach($global_catalog_attributes as $key => $value){
                                    if(is_array($value)){
                                        $global_catalog_attributes[$key] = 'pa_'. $value[0];
                                    }
                                }
                                $custom_catalog_attributes = Helper::get_option('pl_catalog_custom_attributes');
                                $custom_catalog_attributes = explode(PHP_EOL, $custom_catalog_attributes);
                                $custom_catalog_attributes = array_map('trim', $custom_catalog_attributes); // remove white space from end of elements

                                $catalog_attrs           = array_values(array_merge($global_catalog_attributes, $custom_catalog_attributes));
                                $double_matched          = false;
                                $attribute_to_match      = '';

                                // filter & get product attributes which only match with the catalog attributes
                                $filtered_attributes = array_intersect_key($attributes, array_flip($catalog_attrs));

                                if( count($filtered_attributes) == 1 ){
                                    $attribute_to_match = array_keys($filtered_attributes);
                                    $attribute_to_match = $attribute_to_match[0];
                                } else {
                                    $double_matched = true;
                                }

                                foreach ( $attributes as $attribute_name => $options ) :
                                    // if only one catalog attribute match with this product
                                    // then continue till matched the attribute
                                    if( $enable_catalog_mode && $attribute_to_match && $attribute_to_match != $attribute_name ){
                                        continue;
                                    }
                                ?>
                                <tr>
                                    <?php if(Helper::get_option('pl_show_swatches_label')): ?>
                                    <td class="label"><label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label></td>
                                    <?php endif; ?>

                                    <td class="value">
                                        <?php
                                            wc_dropdown_variation_attribute_options(
                                                array(
                                                    'options'   => $options,
                                                    'attribute' => $attribute_name,
                                                    'product'   => $product,
                                                )
                                            );

                                            if(Helper::get_option('pl_show_clear_link')){
                                                echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woolentor' ) . '</a>' ) ) : '';
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php
                            // stop the loop
                            // when catalog mode is enabled & more that one catalog attributes matched with this product. So only the first attribute will show for shop.
                            // When current attribute is matched with the catalog attribute
                            if( $enable_catalog_mode && ( $double_matched || $attribute_to_match == $attribute_name) ){
                                break;
                            }
                            endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php
    }

    /**
     * Filter loop add to cart button HTML attributes
     */
    public function filter_loop_add_to_cart_args( $wp_parse_args, $product ){
        $enable_catalog_mode = Helper::get_option('pl_enable_catalog_mode');
        if( $enable_catalog_mode ){
            $enable_ajax_add_to_cart = false;
        } else {
            $enable_ajax_add_to_cart = Helper::get_option('pl_enable_ajax_add_to_cart');
        }
        
        if($enable_ajax_add_to_cart){
            if( $product->is_type( 'variable' ) ){
                $add_to_cart_text = Helper::get_option('pl_add_to_cart_text');

                $wp_parse_args['class'] .= ' swatchly_ajax_add_to_cart';
                $wp_parse_args['attributes']['data-add_to_cart_text'] = $add_to_cart_text ? $add_to_cart_text : esc_html__('Add to Cart', 'woolentor');
                $wp_parse_args['attributes']['data-select_options_text'] = apply_filters( 'woocommerce_product_add_to_cart_text', $product->add_to_cart_text(), $product );
            }
        }

        return $wp_parse_args;
    }

    /**
     * Ajax variation threshold
     */
    public function ajax_variation_threshold( $qty, $product ){
        if(Helper::get_option('ajax_variation_threshold')){
            $qty = absint(Helper::get_option('ajax_variation_threshold'));
        }

        return $qty;
    }
}