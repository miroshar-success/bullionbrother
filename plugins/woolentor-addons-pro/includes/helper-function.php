<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit();

/**
 * Quick View Content Template
 */
add_filter( 'woolentor_quickview_tmp', 'woolentor_quickview_template', 10, 1 );
function woolentor_quickview_template( $template ){
    $template_id = method_exists( 'Woolentor_Template_Manager', 'get_template_id' ) ? Woolentor_Template_Manager::instance()->get_template_id( 'productquickview', 'woolentor_get_option_pro' ) : '0';
    if( !empty( $template_id ) ){
        $template = WOOLENTOR_ADDONS_PL_PATH_PRO.'includes/quickview-content.php';
    }
    return $template;
}

/**
* Options return
*/
function woolentor_get_option_pro( $option, $section, $default = '' ){
    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
    return $default;
}

function woolentor_get_option_text( $option, $section, $default = '' ){
    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {
        if( !empty($options[$option]) ){
            return $options[$option];
        }
        return $default;
    }
    return $default;
}

/**
* [woolentor_generate_css_pro]
* @param  [string] $key      
* @param  [string] $tab      
* @param  [string] $css_attr 
* @return [type]  
*/
function woolentor_generate_css_pro( $key, $tab, $css_attr, $unit = '', $important = '' ){
    $field_value = !empty( woolentor_get_option_pro( $key, $tab ) ) ? woolentor_get_option_pro( $key, $tab ) : '';

    if( !empty( $field_value ) ){
        $css_attr .= ":{$field_value}{$unit}";
        return $css_attr."{$important};";
    }else{
        return false;
    }

}

/**
 * [woolentor_dimensions_pro]
 * @param  [string] $key
 * @param  [string] $tab
 * @return [String | Bool]
 */
function woolentor_dimensions_pro( $key, $tab, $css_attr, $important = '' ){
    $dimensions = !empty( woolentor_get_option_pro( $key, $tab ) ) ? woolentor_get_option_pro( $key, $tab ) : array();
    if( !empty( $dimensions['top'] ) || !empty( $dimensions['right'] ) || !empty( $dimensions['bottom'] ) || !empty( $dimensions['left'] ) ){

        $unit   = ( empty( $dimensions['unit'] ) ? 'px' : $dimensions['unit'] );
        $top    = ( !empty( $dimensions['top'] ) ? $dimensions['top'] : 0 );
        $right  = ( !empty( $dimensions['right'] ) ? $dimensions['right'] : 0 );
        $bottom = ( !empty( $dimensions['bottom'] ) ? $dimensions['bottom'] : 0 );
        $left   = ( !empty( $dimensions['left'] ) ? $dimensions['left'] : 0 );

        $css_attr .= ":{$top}{$unit} {$right}{$unit} {$bottom}{$unit} {$left}{$unit}";
        return $css_attr."{$important};";
        
    }else{
        return false;
    }
}

/**
* Woocommerce Product last order id return
*/
function woolentor_get_last_order_id(){
    global $wpdb;
    $statuses = array_keys(wc_get_order_statuses());
    $statuses = implode( "','", $statuses );

    // Getting last Order ID (max value)
    $results = $wpdb->get_col( "
        SELECT MAX(ID) FROM {$wpdb->prefix}posts
        WHERE post_type LIKE 'shop_order'
        AND post_status IN ('$statuses')" 
    );
    return reset($results);
}

/**
 * [woolentor_pro_template_endpoint]
 * @return [url]
 */
function woolentor_pro_template_endpoint(){
    return 'https://woolentor.com/library/wp-json/woolentor/v1promnmnsdc/templates';
}

/**
 * [woolentor_pro_template_url]
 * @return [url]
 */
function woolentor_pro_template_url(){
    return 'https://woolentor.com/library/wp-json/woolentor/v1/templates/%s';
}

/**
* Add Inline CSS.
*/
function woolentor_styles_inline() {

    $containerwid = get_option( 'elementor_container_width', '1140' );
    if( $containerwid == 0 ){ $containerwid = '1140'; }

    $emptycartcss = $checkoutpagecss = $noticewrap = '';
    
    if ( class_exists( 'WooCommerce' ) ) {
        if ( is_cart() && WC()->cart->is_empty() ) {
            $emptycartcss = "
                .woolentor-page-template .woocommerce{
                    margin: 0 auto;
                    width: {$containerwid}px;
                }
            ";
        }
        if( is_checkout() ){
            $checkoutpagecss = "
               .woolentor-woocommerce-checkout .woocommerce-NoticeGroup, .woocommerce-error{
                    margin: 0 auto;
                    width: {$containerwid}px;
                } 
            ";
        }
    }

    $noticewrap = "
        .woocommerce-notices-wrapper{
            margin: 0 auto;
            width: {$containerwid}px;
        }
    ";

    $custom_css = "
        $emptycartcss
        $checkoutpagecss
        $noticewrap
        ";
    wp_add_inline_style( 'woolentor-widgets-pro', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'woolentor_styles_inline' );


if( class_exists('WooCommerce') ){
    /**
     * [woolentor_stock_status]
     */
    function woolentor_stock_status_pro( $order_text, $available_text, $product_id ){

        $product_id  = $product_id;
        if ( get_post_meta( $product_id, '_manage_stock', true ) == 'yes' ) {

            $total_stock = get_post_meta( $product_id, 'woolentor_total_stock_quantity', true );

            if ( ! $total_stock ) { echo '<div class="stock-management-progressbar">'.__('Do not set stock amount for progress bar','woolentor-pro').'</div>'; return; }

            $current_stock = round( get_post_meta( $product_id, '_stock', true ) );

            $total_sold = $total_stock > $current_stock ? $total_stock - $current_stock : 0;
            $percentage = $total_sold > 0 ? round( $total_sold / $total_stock * 100 ) : 0;

            if ( $current_stock >= 0 ) {
                echo '<div class="woolentor-stock-progress-bar">';
                    echo '<div class="wlstock-info">';
                        echo '<div class="wltotal-sold">' . __( $order_text, 'woolentor-pro' ) . '<span>' . esc_html( $total_sold ) . '</span></div>';
                        echo '<div class="wlcurrent-stock">' . __( $available_text, 'woolentor-pro' ) . '<span>' . esc_html( $current_stock ) . '</span></div>';
                    echo '</div>';
                    echo '<div class="wlprogress-area" title="' . __( 'Sold', 'woolentor-pro' ) . ' ' . esc_attr( $percentage ) . '%">';
                        echo '<div class="wlprogress-bar"style="width:' . esc_attr( $percentage ) . '%;"></div>';
                    echo '</div>';
                echo '</div>';
            }

        }

    }

    function Woolentor_Control_Sale_Badge( $settings, $id ){
        $product = wc_get_product( $id );

        $discount = '';
        $regurlar_price = get_post_meta( $id, '_regular_price', true);
        $sale_price  = get_post_meta( $id, '_sale_price', true);
        $currency = get_woocommerce_currency_symbol();

        if( $product->is_type('variable') && $product->is_on_sale() ) {
            $regurlar_price = $product->get_variation_regular_price(); // Min regular price
            $sale_price     = $product->get_variation_price(); // Min Sale price
        }

        $sale_badge_after = isset( $settings['product_after_badge_percent'] )?$settings['product_after_badge_percent']:'';
        $sale_badge_before = isset( $settings['product_before_badge_percent'] )?$settings['product_before_badge_percent']:'';

        if( $settings['product_sale_badge_type'] === 'custom' ){
            if( $product->is_on_sale() ){
                echo '<span class="ht-product-label ht-product-label-right">'. $settings['product_sale_badge_custom'].'</span>';
            }
        }elseif ($settings['product_sale_badge_type'] === 'dis_percent' ) {
            if($regurlar_price && $sale_price ){
                $price = ( $regurlar_price-$sale_price )/$regurlar_price;
                $discount = round($price *100);
                $discount =  '<span class="ht-product-label ht-product-label-right">'.$sale_badge_before.' '. $discount . __( '%', 'woolentor-pro' ) .' '. $sale_badge_after.'</span>';
            }
            echo wp_kses_post( $discount );
        }elseif ($settings['product_sale_badge_type'] === 'dis_price' ) {
            if($regurlar_price && $sale_price ){
                $price = ( $regurlar_price - $sale_price );
                $discount = $price;
                $discount =  '<span class="ht-product-label ht-product-label-right">'.$sale_badge_before.' '. $discount . $currency .' '.$sale_badge_after.'</span>';
            }
            echo wp_kses_post( $discount );
        }else{
            woolentor_sale_flash();
        }

    }

    /**
     * [woolentor_pro_locate_template]
     * @param  [string] $tmp_name Template name
     * @return [Template path]
     */
    function woolentor_pro_locate_template( $destination, $tmp_name ) {
        $woo_tmp_base = WC()->template_path();

        $woo_tmp_path     = $woo_tmp_base . $tmp_name; //active theme directory/woocommerce/
        $theme_tmp_path   = '/' . $tmp_name; //active theme root directory
        $plugin_tmp_path  = WOOLENTOR_TEMPLATE_PRO . $destination .'/'. $tmp_name;

        $located = locate_template( [ $woo_tmp_path, $theme_tmp_path ] );

        if ( ! $located && file_exists( $plugin_tmp_path ) ) {
            return apply_filters( 'woolentor_addons_locate_template', $plugin_tmp_path, $tmp_name );
        }

        return apply_filters( 'woolentor_addons_locate_template', $located, $tmp_name );
    }

    /**
     * [woolentor_pro_get_template]
     * @param  [string]  $tmp_name Template name
     * @param  [array]  $args template argument array
     * @param  boolean $echo
     * @return [void]
     */
    function woolentor_pro_get_template( $destination, $tmp_name, $args = null, $echo = true ) {
        $located = woolentor_pro_locate_template( $destination, $tmp_name );

        if ( $args && is_array( $args ) ) {
            extract( $args );
        }

        if ( $echo !== true ) { ob_start(); }

        // include file located.
        include( $located );

        if ( $echo !== true ) { return ob_get_clean(); }

    }

    /**
     * [woolentor_pro_get_taxonomies]
     * @return [array] texonomies list
     */
    function woolentor_pro_get_taxonomies( $object = 'product', $skip_terms = false ) {
        $all_taxonomies = get_object_taxonomies( $object );
        $taxonomies_list = [];
        $taxonomies_list['none'] = esc_html__( 'Select', 'woolentor-pro' );
        foreach ( $all_taxonomies as $taxonomy_data ) {
            $taxonomy = get_taxonomy( $taxonomy_data );

            if( $skip_terms === true ){
                if( ($taxonomy->show_ui) && ( 'pa_' !== substr( $taxonomy_data, 0, 3 ) ) ) {
                    $taxonomies_list[ $taxonomy_data ] = $taxonomy->label;
                }
            }else{
                if( $taxonomy->show_ui ) {
                    $taxonomies_list[ $taxonomy_data ] = $taxonomy->label;
                }
            }
            
        }
        return $taxonomies_list;
    }

    // For Cart Page
    /**
     * Show Category
     */
    function woolentor_cart_page_categories( $cart_item, $cart_item_key ){
        $product_item = $cart_item['data'];
        // make sure to get parent product if variation
        if ( $product_item->is_type( 'variation' ) ) {
            $product_item = wc_get_product( $product_item->get_parent_id() );
        }
        $cat_ids = $product_item->get_category_ids();
        // if product has categories, concatenate cart item name with them
        echo  ( $cat_ids ? '<div class="woolentor-cart-cats">' . wc_get_product_category_list( $product_item->get_id(), ', ',
                '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $cat_ids ), 'woolentor-pro') . ' ',
            '</span></div>'
        ) : '' ) ;
    }

    /**
     * Show Product stock
     */
    function woolentor_cart_page_stock( $cart_item, $cart_item_key ){
        $product = $cart_item['data'];
        if ( $product->backorders_require_notification() && $product->is_on_backorder( $cart_item['quantity'] ) ) {
            return;
        }
        echo  ( wc_get_stock_html( $product ) ? '<div class="woolentor-cart-stock">' . wc_get_stock_html( $product ) . '</div>' : '' ) ;
    }

    /**
     * Get Avaiable payment method
     */
    function woolentor_get_payment_method(){

        $generate_payment_gateways = [];
        $get_avaiable_gateways = WC()->payment_gateways->get_available_payment_gateways();
        foreach ( $get_avaiable_gateways as $key => $gateway ) {
            $generate_payment_gateways[$key] = $gateway->title;
        }
        return $generate_payment_gateways;

    }

    /**
     * Get Previous and Next product if not visible
     */
    function woolentor_get_previous_next_product($previous = false){
        global $post;

        $args = array(
            'limit'      => 2,
            'visibility' => 'catalog',
            'exclude'    =>  array( $post->ID ),
            'orderby'    => 'date',
            'status'     => 'publish',
        );

        if ( ! $previous ) {
            $args['order'] = 'ASC';
        }
     
        $terms = get_the_terms( $post->ID, 'product_cat' );

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $args['category'] = wp_list_pluck( $terms, 'slug' );
        }

        $products = wc_get_products( $args );

        if ( ! empty( $products ) && count( $products ) >= 2 ) {
            return $products[0];
        }

        return false;   

    }

    
}

/*
 * Products not found content.
 */
function woolentor_pro_products_not_found_content(){
    return '<div class="products-not-found"><p class="woocommerce-info">' . esc_html__( 'No products were found matching your selection.','woolentor-pro' ) . '</p></div>';
}

/**
 * It scan the elementor page given by id
 * Look for each sections and find the widget by it's name and get the widget settings.
 * 
 * @param page_id The ID of the page you want to get the settings from.
 * @param widget_name The slug of the widget you want to get the settings for.
 * 
 * @return An array of settings for the widget.
 */
function woolentor_pro_get_settings_by_widget_name( $page_id, $widget_name ){
    $elementor_data     = get_post_meta( $page_id, '_elementor_data', true);
	
	if( is_array($elementor_data) ){
		$elementor_data_arr = $elementor_data;
	} else {
		$elementor_data_arr = (array) json_decode( $elementor_data, true);
	}
    
    $settings = array();

    // Loop through the root sections
    foreach( $elementor_data_arr as $section ){
        $widget = woolentor_pro_get_widget_from_section($section, $widget_name);

        if( is_array($widget) ){
            $settings['settings'] = $widget['settings'];
            break;
        }
    }

    return $settings;
}

/*
 * Scan each section and inner sections and look for the widget by it's name
 */
function woolentor_pro_get_widget_from_section($section, $widget_name){
    // Loop through the columns of the root section
    foreach($section['elements'] as $column){
        // Loop through the column elements
        foreach( $column['elements'] as $widget ){

            // if the element is inner section then 
            // look for the widget element again
            // otherwise it is the widget element
            if( isset($widget['isInner']) ){
                foreach($widget['elements'] as $column){
                    foreach( $column['elements'] as $widget ){
                        if( isset($widget['widgetType']) && $widget['widgetType'] == $widget_name){
                            return $widget;
                        }
                    }
                }
            } elseif(isset($widget['widgetType']) && $widget['widgetType'] == $widget_name) {
                return $widget;
            }

        }
    }

    return null;
}