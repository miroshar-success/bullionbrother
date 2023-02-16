<?php
/**
 * Get Post List
 * return array
 */
function ever_compare_get_post_list( $post_type = 'page' ){
    $options = array();
    $options['0'] = __('Select','ever-compare');
    $perpage = -1;
    $all_post = array( 'posts_per_page' => $perpage, 'post_type'=> $post_type );
    $post_terms = get_posts( $all_post );
    if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ){
        foreach ( $post_terms as $term ) {
            $options[ $term->ID ] = $term->post_title;
        }
        return $options;
    }
}

/**
 * [ever_compare_locate_template]
 * @param  [string] $tmp_name Template name
 * @return [Template path]
 */
function ever_compare_locate_template( $tmp_name ) {

    $woo_tmp_base = function_exists('WC') ? WC()->template_path() : '';

    $woo_tmp_path     = $woo_tmp_base . $tmp_name; //active theme directory/woocommerce/
    $theme_tmp_path   = '/' . $tmp_name; //active theme root directory
    $plugin_tmp_path  = EVERCOMPARE_DIR . 'includes/templates/' . $tmp_name;

    $located = locate_template( [ $woo_tmp_path, $theme_tmp_path ] );

    if ( ! $located && file_exists( $plugin_tmp_path ) ) {
        return apply_filters( 'evercompare_locate_template', $plugin_tmp_path, $tmp_name );
    }

    return apply_filters( 'evercompare_locate_template', $located, $tmp_name );
}

/**
 * [ever_compare_get_template]
 * @param  [string]  $tmp_name Template name
 * @param  [array]  $args template argument array
 * @param  boolean $echo
 * @return [void]
 */
function ever_compare_get_template( $tmp_name = '', $args = null, $echo = true ) {
    $located = ever_compare_locate_template( $tmp_name );

    if ( $args && is_array( $args ) ) {
        extract( $args );
    }

    if ( $echo !== true ) { ob_start(); }

    // include file located.
    include( $located );

    if ( $echo !== true ) { return ob_get_clean(); }

}


/**
 * Get default fields List
 * return array
 */
function ever_compare_get_default_fields(){
    $fields = array(
        'title'         => esc_html__( 'Title', 'ever-compare' ),
        'ratting'       => esc_html__( 'Ratting', 'ever-compare' ),
        'price'         => esc_html__( 'Price', 'ever-compare' ),
        'add_to_cart'   => esc_html__( 'Add To Cart', 'ever-compare' ),
        'description'   => esc_html__( 'Description', 'ever-compare' ),
        'availability'  => esc_html__( 'Availability', 'ever-compare' ),
        'sku'           => esc_html__( 'Sku', 'ever-compare' ),
        'weight'        => esc_html__( 'Weight', 'ever-compare' ),
        'dimensions'    => esc_html__( 'Dimensions', 'ever-compare' ),
    );
    return apply_filters( 'ever_compare_default_fields', $fields );
}

/**
 * Get Fields List
 * return array
 */
function ever_compare_get_available_attributes() {
    $attribute_list = array();

    if( function_exists( 'wc_get_attribute_taxonomies' ) ) {
        $attribute_list = wc_get_attribute_taxonomies();
    }

    $fields = ever_compare_get_default_fields();

    if ( count( $attribute_list ) > 0 ) {
        foreach ( $attribute_list as $attribute ) {
            $fields[ 'pa_' . $attribute->attribute_name ] = $attribute->attribute_label;
        }
    }

    return $fields;
}

/**
 * [ever_compare_table_active_heading]
 * @return [array]
 */
function ever_compare_table_active_heading(){
    $active_heading = !empty( woolentor_get_option( 'show_fields', 'ever_compare_table_settings_tabs' ) ) ? woolentor_get_option( 'show_fields', 'ever_compare_table_settings_tabs' ) : array();
    return $active_heading;
}

/**
 * [ever_compare_table_heading]
 * @return [array]
 */
function ever_compare_table_heading(){
    $new_list = array();
    $field_list = count( ever_compare_table_active_heading() ) > 0 ? ever_compare_table_active_heading() : ever_compare_get_default_fields();
    foreach ( $field_list as $key => $value ) {
        $new_list[$key] = \EverCompare\Frontend\Manage_Compare::instance()->field_name( $key );
    }
    return $new_list;
}

/**
 * [ever_compare_dimensions]
 * @param  [string] $key
 * @param  [string] $tab
 * @return [String | Bool]
 */
function ever_compare_dimensions( $key, $tab, $css_attr ){
    $dimensions = !empty( woolentor_get_option( $key, $tab ) ) ? woolentor_get_option( $key, $tab ) : array();
    if( !empty( $dimensions['top'] ) || !empty( $dimensions['right'] ) || !empty( $dimensions['bottom'] ) || !empty( $dimensions['left'] ) ){

        $unit   = ( empty( $dimensions['unit'] ) ? 'px' : $dimensions['unit'] );
        $top    = ( !empty( $dimensions['top'] ) ? $dimensions['top'] : 0 );
        $right  = ( !empty( $dimensions['right'] ) ? $dimensions['right'] : 0 );
        $bottom = ( !empty( $dimensions['bottom'] ) ? $dimensions['bottom'] : 0 );
        $left   = ( !empty( $dimensions['left'] ) ? $dimensions['left'] : 0 );

        $css_attr .= ":{$top}{$unit} {$right}{$unit} {$bottom}{$unit} {$left}{$unit}";
        return $css_attr.';';

    }else{
        return false;
    }
}

/**
 * [ever_compare_generate_css]
 * @return [String | Bool]
 */
function ever_compare_generate_css( $key, $tab, $css_attr, $unit = '' ){
    $field_value = !empty( woolentor_get_option( $key, $tab ) ) ? woolentor_get_option( $key, $tab ) : '';

    if( !empty( $field_value ) ){
        $css_attr .= ":{$field_value}{$unit}";
        return $css_attr.';';
    }else{
        return false;
    }

}

/**
 * [ever_compare_icon_list]
 * @return [svg]
 */
function ever_compare_icon_list( $key = '' ){
    $icon_list = [
        'default' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 471.701 471.701">
            <g class="ever-compare-refresh"><path d="M409.6,0c-9.426,0-17.067,7.641-17.067,17.067v62.344C304.667-5.656,164.478-3.386,79.411,84.479 c-40.09,41.409-62.455,96.818-62.344,154.454c0,9.426,7.641,17.067,17.067,17.067S51.2,248.359,51.2,238.933 c0.021-103.682,84.088-187.717,187.771-187.696c52.657,0.01,102.888,22.135,138.442,60.976l-75.605,25.207 c-8.954,2.979-13.799,12.652-10.82,21.606s12.652,13.799,21.606,10.82l102.4-34.133c6.99-2.328,11.697-8.88,11.674-16.247v-102.4 C426.667,7.641,419.026,0,409.6,0z"/><path d="M443.733,221.867c-9.426,0-17.067,7.641-17.067,17.067c-0.021,103.682-84.088,187.717-187.771,187.696 c-52.657-0.01-102.888-22.135-138.442-60.976l75.605-25.207c8.954-2.979,13.799-12.652,10.82-21.606 c-2.979-8.954-12.652-13.799-21.606-10.82l-102.4,34.133c-6.99,2.328-11.697,8.88-11.674,16.247v102.4 c0,9.426,7.641,17.067,17.067,17.067s17.067-7.641,17.067-17.067v-62.345c87.866,85.067,228.056,82.798,313.122-5.068 c40.09-41.409,62.455-96.818,62.344-154.454C460.8,229.508,453.159,221.867,443.733,221.867z"/></g>
            <g class="ever-compare-check"><path d="M238.933,0C106.974,0,0,106.974,0,238.933s106.974,238.933,238.933,238.933s238.933-106.974,238.933-238.933 C477.726,107.033,370.834,0.141,238.933,0z M238.933,443.733c-113.108,0-204.8-91.692-204.8-204.8s91.692-204.8,204.8-204.8 s204.8,91.692,204.8,204.8C443.611,351.991,351.991,443.611,238.933,443.733z"/><path d="M370.046,141.534c-6.614-6.388-17.099-6.388-23.712,0v0L187.733,300.134l-56.201-56.201 c-6.548-6.78-17.353-6.967-24.132-0.419c-6.78,6.548-6.967,17.353-0.419,24.132c0.137,0.142,0.277,0.282,0.419,0.419 l68.267,68.267c6.664,6.663,17.468,6.663,24.132,0l170.667-170.667C377.014,158.886,376.826,148.082,370.046,141.534z"/></g></svg>',
        'loading' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 471.701 471.701">
            <g class="ever-compare-refresh"><path d="M409.6,0c-9.426,0-17.067,7.641-17.067,17.067v62.344C304.667-5.656,164.478-3.386,79.411,84.479 c-40.09,41.409-62.455,96.818-62.344,154.454c0,9.426,7.641,17.067,17.067,17.067S51.2,248.359,51.2,238.933 c0.021-103.682,84.088-187.717,187.771-187.696c52.657,0.01,102.888,22.135,138.442,60.976l-75.605,25.207 c-8.954,2.979-13.799,12.652-10.82,21.606s12.652,13.799,21.606,10.82l102.4-34.133c6.99-2.328,11.697-8.88,11.674-16.247v-102.4 C426.667,7.641,419.026,0,409.6,0z"/><path d="M443.733,221.867c-9.426,0-17.067,7.641-17.067,17.067c-0.021,103.682-84.088,187.717-187.771,187.696 c-52.657-0.01-102.888-22.135-138.442-60.976l75.605-25.207c8.954-2.979,13.799-12.652,10.82-21.606 c-2.979-8.954-12.652-13.799-21.606-10.82l-102.4,34.133c-6.99,2.328-11.697,8.88-11.674,16.247v102.4 c0,9.426,7.641,17.067,17.067,17.067s17.067-7.641,17.067-17.067v-62.345c87.866,85.067,228.056,82.798,313.122-5.068 c40.09-41.409,62.455-96.818,62.344-154.454C460.8,229.508,453.159,221.867,443.733,221.867z"/></g></svg>'
    ];
    return ( $key == '' ) ? $icon_list : $icon_list[$key];
}