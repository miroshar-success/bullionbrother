<?php
use Automattic\WooCommerce\Utilities\NumberUtil;

/**
* Cart page
*/
class WooLentor_Cart_Page{

    /**
     * [$instance]
     * @var null
     */
    private static $instance = null;

    /**
     * [instance]
     * @return [WooLentor_Cart_Page]
     */
    public static function instance(){
        if( is_null( self::$instance ) ){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * [__construct] class constructor
     */
    function __construct(){
        $cart_page_id = method_exists( 'Woolentor_Template_Manager', 'get_template_id' ) ? Woolentor_Template_Manager::instance()->get_template_id( 'productcartpage', 'woolentor_get_option_pro' ) : '0';
        $widget_options    = woolentor_pro_get_settings_by_widget_name( $cart_page_id, 'wl-cart-table-list' );
        $cart_table_config = woolentor_pro_get_settings_by_widget_name( $cart_page_id, 'wl-cart-table' );
        $cart_total_config = woolentor_pro_get_settings_by_widget_name( $cart_page_id, 'wl-cart-total' );

        /* 
         * For Cart table (list) addon
         * WooCommerce default templates override
         */
        if( !empty($widget_options['settings']) ){
            // Set default value
            $widget_options['settings']['style'] = '1';
        }

        $cart_table_style = '';
        if( isset($cart_table_config['settings']['style']) ){
            $cart_table_style = '1';
        }

        if( ( !empty($widget_options['settings']['style']) && $widget_options['settings']['style'] == '1' ) || 
            $cart_table_style == '1'
        ){
            add_filter('wc_get_template', [ $this, 'wc_get_template_filter' ], 9999, 5);
        }
        
        // Return if somehow widget settings are missing
        if( empty($cart_total_config['settings']) ){
            return;
        }

        $cart_total_config = wp_parse_args($cart_total_config['settings'], array(
            'cart_total_layout' => 1,
        ) );

        if( !empty($cart_total_config['default_layout']) ){
            return;
        }

        // When default is not set
        if( $cart_total_config['cart_total_layout'] ){

            // Override woocommerce_cart_totals function
            if( !function_exists('woocommerce_cart_totals') ){
                function woocommerce_cart_totals(){
                    $cart_page_id = method_exists( 'Woolentor_Template_Manager', 'get_template_id' ) ? Woolentor_Template_Manager::instance()->get_template_id( 'productcartpage', 'woolentor_get_option_pro' ) : '0';
                    $cart_total_config     = woolentor_pro_get_settings_by_widget_name( $cart_page_id, 'wl-cart-total' );
                    
                    // Return if somehow widget settings are missing
                    if( empty($cart_total_config['settings']) ){
                        return;
                    }
            
                    $cart_total_config = wp_parse_args($cart_total_config['settings'], array('cart_total_layout' => 1) );
                    
                    wc_get_template(
                        'cart/cart-totals.php', 
                        array(
                            'config' => $cart_total_config
                        ),
                        '/wl-woo-templates/',
                        WOOLENTOR_ADDONS_PL_PATH_PRO. '/wl-woo-templates/'
                    );
                }
            }   
        }
    }

    /**
     * Check if the current page is cart
     */
    public function is_cart(){
        $cart_page_id = method_exists( 'Woolentor_Template_Manager', 'get_template_id' ) ? Woolentor_Template_Manager::instance()->get_template_id( 'productcartpage', 'woolentor_get_option_pro' ) : '0';

        $get_page_id = !empty($_GET['post']) ? absint($_GET['post']) : '';

        if( is_cart() && !is_wc_endpoint_url() || ($get_page_id == $cart_page_id) ){
            return true;
        }

        return false;
    }

    /**
     * Orverride templates
     */
    public function wc_get_template_filter( $template, $template_name, $args, $template_path, $default_path ){
        if( !$this->is_cart() ){
            return $template;
        }
    
        if($template_name == 'cart/cart-item-data.php'){
            $template = wc_locate_template('cart/cart-item-data.php', 'wl-woo-templates', WOOLENTOR_ADDONS_PL_PATH_PRO. '/wl-woo-templates/');
        }
    
        return $template;
    }

    /**
     * It calculates the discount percentage of a product.
     * 
     * @param old_price The original price of the product.
     * @param new_price The new price of the product.
     * 
     * @return The percentage of the discount.
     */
    public static function get_discount_percent( $old_price, $new_price ){
        $precision = wc_get_price_decimals();
        $decrease =  (float) $old_price - (float) $new_price;
    
        if( $decrease > 0 ){
            $percent = $decrease / (float) $old_price * 100;

             return NumberUtil::round( $percent, $precision );
        }
    
        return 0;
    }

    public static function add_to_wishlist_button( $normalicon = '<i class="fa fa-heart-o"></i>', $addedicon = '<i class="fa fa-heart"></i>', $tooltip = 'no', $args = array() ) {
        global $product;
    
        $product_id = !empty( $args['product_id'] ) ? $args['product_id'] : $product->get_id();
        $product = wc_get_product($product_id);
        $config = $args['config'];
    
        $output = '';
        if( class_exists('WishSuite_Base') || class_exists('Woolentor_WishSuite_Base') ){
            $button_class = ' wishlist'.( $tooltip == 'yes' ? '' : ' wltooltip_no' );
    
            $button_args = [
                'btn_class' => $button_class,
            ];
            
            add_filter( 'wishsuite_button_arg', function( $button_arg ) use ( $button_args, $config ) {
                if( strpos( $button_arg['button_class'], 'wishlist' ) == false ){
                    $button_arg['button_class'] .= $button_args['btn_class'];

                    $button_arg['button_text']          = $config['label_add_to_wishlist'];
                    $button_arg['button_added_text']    = $config['label_added_to_wishlist'];
                    $button_arg['button_exist_text']    = $config['label_already_added_to_wishlist'];

                    if( $config['style'] === '2' ){
                        if($config['wishlist_icon']){
                            ob_start();
                            \Elementor\Icons_Manager::render_icon( $config['wishlist_icon'], [ 'aria-hidden' => 'true' ]);
                            $button_arg['button_text'] = ob_get_clean();
                        }

                        if($config['wishlist_icon_added']){
                            ob_start();
                            \Elementor\Icons_Manager::render_icon( $config['wishlist_icon_added'], [ 'aria-hidden' => 'true' ]);
                            $icon_added = ob_get_clean();

                            $button_arg['button_exist_text'] = $icon_added;
                        }

                    }
                }
                return $button_arg;
            }, 90, 1 );
    
            $output .= do_shortcode('[wishsuite_button]');
            return $output;
    
        }elseif( class_exists('TInvWL_Public_AddToWishlist') ){
            ob_start();
            TInvWL_Public_AddToWishlist::instance()->htmloutput();
            $output .= ob_get_clean();
            return $output;
    
        }elseif( class_exists( 'YITH_WCWL' ) ){
    
            if( !empty( get_option( 'yith_wcwl_wishlist_page_id' ) ) ){
                global $yith_wcwl;
                if( $config['style'] == '1' ){
                    $normalicon = '';
                    $addedicon = '';
                }

                $url          = YITH_WCWL()->get_wishlist_url();
                $product_type = $product->get_type();
                $exists       = $yith_wcwl->is_product_in_wishlist( $product->get_id() );
                $classes      = 'class="add_to_wishlist"';
                $add          = $config['label_add_to_wishlist'];
                $browse       = $config['label_added_to_wishlist'];
                $added        = $config['label_added_to_wishlist'];
    
                $output  .= '<div class="'.( $tooltip == 'yes' ? '' : 'tooltip_no' ).' wishlist button-default yith-wcwl-add-to-wishlist add-to-wishlist-' . esc_attr( $product->get_id() ) . '">';
                    $output .= '<div class="yith-wcwl-add-button';
                        $output .= $exists ? ' hide" style="display:none;"' : ' show"';
                        $output .= '><a href="' . esc_url( htmlspecialchars( YITH_WCWL()->get_wishlist_url() ) ) . '" data-product-id="' . esc_attr( $product->get_id() ) . '" data-product-type="' . esc_attr( $product_type ) . '" ' . $classes . ' >'.$normalicon.'<span class="ht-product-action-tooltip">'.esc_html( $add ).'</span></a>';
                        $output .= '<i class="fa fa-spinner fa-pulse ajax-loading" style="visibility:hidden"></i>';
                    $output .= '</div>';
    
                    $output .= '<div class="yith-wcwl-wishlistaddedbrowse hide" style="display:none;"><a class="" href="' . esc_url( $url ) . '">'.$addedicon.'<span class="ht-product-action-tooltip">'.esc_html( $browse ).'</span></a></div>';
                    $output .= '<div class="yith-wcwl-wishlistexistsbrowse ' . ( $exists ? 'show' : 'hide' ) . '" style="display:' . ( $exists ? 'block' : 'none' ) . '"><a href="' . esc_url( $url ) . '" class="">'.$addedicon.'<span class="ht-product-action-tooltip">'.esc_html( $added ).'</span></a></div>';
                $output .= '</div>';
    
                return $output;
            }
    
        }else{
            return 0;
        }
    
    
    }

    public static function compare_button( $button_arg = array() ){
        global $product;
        $product_id = !empty( $button_arg['product_id'] ) ? $button_arg['product_id'] : $product->get_id();
        $config = $button_arg['config'];
    
        $button_style       = !empty( $button_arg['style'] ) ? $button_arg['style'] : 1;
        
        $button_title       = $config['label_add_to_compare'];
        $button_text        = $config['label_add_to_compare'];
        $button_added_text  = $config['label_added_to_compare'];

        if( $config['style'] === '2' && $config['compare_icon'] ){
            ob_start();
            \Elementor\Icons_Manager::render_icon( $config['compare_icon'], [ 'aria-hidden' => 'true' ]);
            $button_text = ob_get_clean();
        }
    
        if( class_exists('Ever_Compare') || class_exists('Woolentor_Ever_Compare') ){
            $comp_link = \EverCompare\Frontend\Manage_Compare::instance()->get_compare_page_url();
            echo '<a title="'.esc_attr( $button_title ).'" href="'.esc_url( $comp_link ).'" class="htcompare-btn woolentor-compare" data-added-text="'. $button_added_text.'" data-product_id="'.esc_attr( $product_id ).'">'.$button_text.'</a>';
    
        }elseif( class_exists('YITH_Woocompare') ){
            $comp_link = home_url() . '?action=yith-woocompare-add-product';
            $comp_link = add_query_arg('id', $product_id, $comp_link);
    
            echo '<a title="'. esc_attr__('Add to Compare', 'woolentor') .'" href="'. esc_url( $comp_link ) .'" class="compare" data-product_id="'. esc_attr( $product_id ) .'" rel="nofollow">'.$button_text.'</a>';
        }else{
            return 0;
        }
    
    }

    /**
     * It takes a cart item and returns a string of formatted HTML of the meta data
     * 
     * @return the formatted cart item data.
     */
    public static function wl_get_formatted_cart_item_data( $cart_item, $flat = false ) {
        $item_data = array();

        // Variation values are shown only if they are not found in the title as of 3.0.
        // This is because variation titles display the attributes.
        if ( $cart_item['data']->is_type( 'variation' ) && is_array( $cart_item['variation'] ) ) {
            foreach ( $cart_item['variation'] as $name => $value ) {
                $taxonomy = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', urldecode( $name ) ) );

                if ( taxonomy_exists( $taxonomy ) ) {
                    // If this is a term slug, get the term's nice name.
                    $term = get_term_by( 'slug', $value, $taxonomy );
                    if ( ! is_wp_error( $term ) && $term && $term->name ) {
                        $value = $term->name;
                    }
                    $label = wc_attribute_label( $taxonomy );
                } else {
                    // If this is a custom option slug, get the options name.
                    $value = apply_filters( 'woocommerce_variation_option_name', $value, null, $taxonomy, $cart_item['data'] );
                    $label = wc_attribute_label( str_replace( 'attribute_', '', $name ), $cart_item['data'] );
                }

                // Check the nicename against the title.
                if ( '' === $value || wc_is_attribute_in_product_name( $value, $cart_item['data']->get_name() ) ) {
                    continue;
                }

                $item_data[] = array(
                    'key'   => $label,
                    'value' => $value,
                );
            }
        }

        // Filter item data to allow 3rd parties to add more to the array.
        $item_data = apply_filters( 'woocommerce_get_item_data', $item_data, $cart_item );

        // Format item data ready to display.
        foreach ( $item_data as $key => $data ) {
            // Set hidden to true to not display meta on cart.
            if ( ! empty( $data['hidden'] ) ) {
                unset( $item_data[ $key ] );
                continue;
            }
            $item_data[ $key ]['key']     = ! empty( $data['key'] ) ? $data['key'] : $data['name'];
            $item_data[ $key ]['display'] = ! empty( $data['display'] ) ? $data['display'] : $data['value'];
        }

        // Output flat or in list format.
        if ( count( $item_data ) > 0 ) {
            ob_start();

            if ( $flat ) {
                foreach ( $item_data as $data ) {
                    echo esc_html( $data['key'] ) . ': ' . wp_kses_post( $data['display'] ) . "\n";
                }
            } else {
                wc_get_template(
                    'cart/cart-item-data.php', 
                    array( 
                        'item_data' => $item_data
                    ),
                    '/wl-woo-templates/',
                    WOOLENTOR_ADDONS_PL_PATH_PRO. '/wl-woo-templates/'
                );
            }

            return ob_get_clean();
        }

        return '';
    }
}

WooLentor_Cart_Page::instance();