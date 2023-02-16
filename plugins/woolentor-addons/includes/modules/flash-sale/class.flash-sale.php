<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Flash_Sale{

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

        // Enqueue scripts
        add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // Alter display price
        add_filter( 'woocommerce_get_price_html', [ $this, 'flash_sale_display_price' ], 10, 2 );

        // Change simple product price only on cart & checkout page
        add_filter( 'woocommerce_product_get_price', [ $this, 'flash_sale_price_simple' ], 10, 2 );
        add_filter( 'woocommerce_product_get_sale_price', [ $this, 'flash_sale_price_simple' ], 10, 2 );

        // Change price range of the product
        add_action( 'woocommerce_product_variation_get_price', [ $this, 'flash_sale_price_variable' ], 10, 2 );
        add_filter( 'woocommerce_product_variation_get_sale_price', [ $this, 'flash_sale_price_variable' ], 10, 2 );

        // Change each variation price
        add_filter( 'woocommerce_variation_prices_price', [ $this, 'flash_sale_price_variable' ], 10, 2 );
        add_filter( 'woocommerce_variation_prices_sale_price', [ $this, 'flash_sale_price_variable' ], 10, 2 );

        // Handling price caching, for better performance
        add_filter( 'woocommerce_get_variation_prices_hash', array( $this, 'variable_product_prices_hash'), 10, 3 );

        // Add "Flash Sale" lable with products on cart table
        add_filter( 'woocommerce_get_item_data', [ $this, 'filter_get_item_data' ], 99, 2 );

        // Countdown position
        $position = woolentor_get_option( 'countdown_position', 'woolentor_flash_sale_settings', 'woocommerce_before_add_to_cart_form' );
        add_action( $position, [ $this, 'render_countdown' ] );

    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts(){

        if( is_product() || is_cart() || is_checkout() ){

            wp_enqueue_script( 'woolentor-flash-sale-module');
            wp_enqueue_style( 'woolentor-flash-sale-module' );

        }
        
    }

    /**
     * Chagne simple product price on cart & checkout page
     * For displaying the price and also the price calculation
     */
    public function flash_sale_price_simple( $price, $product ){
        $product_id = $product->get_id();

        $deal           = self::get_deal( $product_id );
        $discount_value = !empty($deal['discount_value']) ? $deal['discount_value'] : '';

        if( $deal && self::datetime_validity($deal) && $discount_value ){
            $regular_price = get_post_meta( $product_id, '_regular_price', true );
            $sale_price    = get_post_meta( $product_id, '_sale_price', true );

            if ( $regular_price && ( is_cart() || is_checkout() ) ) {
                $price = $this->get_calculated_price( $product_id, $deal );
            }
        }

        return $price;
    }


    /**
     * Manage variable product price
     */
    public function flash_sale_price_variable( $price, $product ){

        $product_id = $product->get_id();
        $parent_id  = $product->get_parent_id() == 0 ? $product_id : $product->get_parent_id();

        $parent_product = wc_get_product( $parent_id );

        $deal           = self::get_deal( $parent_product );
        $discount_type  = !empty($deal['discount_type']) ? $deal['discount_type'] : 'percentage';
        $discount_value = !empty($deal['discount_value']) ? $deal['discount_value'] : '';

        if( $deal && self::datetime_validity($deal) && $discount_value ){
            $price = $this->get_calculated_price_variable($product, $price, $deal);
        }

        return $price;
    }

    /**
     * Chagne display price on product loop & product details page
     */
    public function flash_sale_display_price( $price_html, $product ){
        if ( ! $product || class_exists( 'WC_Bundles' ) )  return $price_html;

        $product_id = $product->get_id();
        $parent_id  = $product->get_parent_id() == 0 ? $product_id : $product->get_parent_id();
        $parent_product = wc_get_product($parent_id);

        if( $product->get_type() == 'variation' ){
            return $price_html;
        }

        $deal             = self::get_deal( $parent_product );
        $discount_value   = !empty($deal['discount_value']) ? $deal['discount_value'] : '';
        $flash_sale_price = '';

        if( $deal && self::datetime_validity($deal) && $discount_value ){
            if( !$product->is_type('variable') && !$product->is_type('grouped') ){

               $flash_sale_price = wc_price( $this->get_calculated_price( $product_id, $deal ) );

            } elseif( $product->is_type('variable') ){
               $flash_sale_price = $price_html;
               $price_min        = wc_get_price_to_display( $product, [ 'price' => $product->get_variation_regular_price( 'min' ) ] );
               $price_max        = wc_get_price_to_display( $product, [ 'price' => $product->get_variation_regular_price( 'max' ) ] );

               if($product->get_variation_regular_price( 'min' ) == $product->get_variation_regular_price( 'max' ) ){
                    $price_html = wc_price( $price_max );
               } else {
                    $price_html = wc_format_price_range( $price_min, $price_max );
               }
               
            } elseif( $product->is_type('grouped') ) {
                $grouped_o_prices = $this->get_grouped_prices($product);
                $min_price = $grouped_o_prices['min'];
                $max_price = $grouped_o_prices['max'];

                if ( '' !== $min_price ) {
                    if ( $min_price !== $max_price ) {
                        $price = wc_format_price_range( $this->get_calculated_price('', $deal, $min_price), $this->get_calculated_price('', $deal, $max_price) );
                    } else {
                        $price = wc_price( $this->get_calculated_price($min_price) );
                    }

                    $is_free = 0 === $min_price && 0 === $max_price;

                    if ( $is_free ) {
                        $flash_sale_price = apply_filters( 'woocommerce_grouped_free_price_html', __( 'Free!', 'woolentor' ), $product );
                    } else {
                        $flash_sale_price = $price . $product->get_price_suffix();
                    }
                }
            }

            $manage_price_label = woolentor_get_option('manage_price_label', 'woolentor_flash_sale_settings');
            $manage_price_label = str_replace( '{original_price}', '<span class="woolentor-flash-sale-original-price">'.$price_html.'</span><br/>', $manage_price_label );
            $manage_price_label = str_replace( '{flash_sale_price}', '<span class="woolentor-flash-sale-price">'.$flash_sale_price.'</span>', $manage_price_label );

            if( is_admin() ){
                return $flash_sale_price;
            }else if( empty( $manage_price_label ) ){
                return $flash_sale_price;
            } else {
                return $manage_price_label;
            }

        } else{
            return $price_html;
        }
    }

    /**
     * Add "Flash Sale" lable with products on cart table
     */
    public function filter_get_item_data( $item_data, $cart_item ){
        $product_id = $cart_item['product_id'];

        $deal           = self::get_deal( $product_id );
        $discount_value = !empty($deal['discount_value']) ? $deal['discount_value'] : '';

        if( $deal && self::datetime_validity($deal) && $discount_value ){
            $item_data[] = array(
                'name'      => 'woolentor_cart_flash_sale_label',
                'display'   => '<span class="woolentor-flashsale-label">'. esc_html__('Flash Sale!', 'woolentor') .'</span>',
                'value'     => '',
            );
        }

        return $item_data;
    }


    /**
     * Validate user previllage
     */
    public static function user_validity( $deal ){
        $validity                            = false;
        $apply_only_for_registered_customers = !empty($deal['apply_discount_only_for_registered_customers']) ? $deal['apply_discount_only_for_registered_customers'] : '';
        $allowed_user_roles                  = !empty($deal['allowed_user_roles']) ? explode(',',  $deal['allowed_user_roles']) : array();

        if( $apply_only_for_registered_customers ){
            if( is_user_logged_in() && !$allowed_user_roles ){
                $validity = true;
            }

            if( is_user_logged_in() && $allowed_user_roles ){
                $current_user_obj   = get_user_by( 'id', get_current_user_id() );
                $current_user_roles = $current_user_obj->roles;

                if( array_intersect( $current_user_roles, $allowed_user_roles) ){
                    $validity = true;
                }
            }
        } else{
            $validity = true;
        }

        return $validity;
    }

    /**
     * Return remaining time (seconds) to end the deal
     */
    public static function get_remaining_time( $deal ){
        $flash_sale_end_date = !empty($deal["end_date"]) ? $deal["end_date"] : ''; // 2022-01-18
        $flash_sale_end_time = '23:59:59'; // 13:00:00
        $gmt_offdet     = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
        $time_str       = strtotime( $flash_sale_end_date );
        $time_total     = $gmt_offdet + $time_str;
        $current_date   = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );

        $remaining_time  = ( strtotime( $flash_sale_end_time ) - strtotime( 'TODAY' ) + strtotime( $flash_sale_end_date ) ) - current_time( 'timestamp' );

        return $remaining_time;
    }

    /**
     * Validate offer date & time
     */
    public static function datetime_validity( $deal ){
        $validity        = false;
        $current_time    = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );
        $deal_time_began = !empty($deal['start_date']) ? strtotime( $deal['start_date'] ) : '';
        $deal_time_end   = !empty($deal["end_date"]) ? strtotime( $deal["end_date"] ) : '';
        $deal_time_end   = (int) $deal_time_end + 86399; // 23 hours + 59 minutes as the end date

        // if any one of the time is defined
        if( $deal_time_began || $deal_time_end ){

            // if began datetime is set but end time not set
            if( $deal_time_began && empty($deal_time_end) ){
                if( $deal_time_began <= $current_time ){
                    $validity = true;
                }
            }
            // if end datetime is set but start datetime not set
            elseif( $deal_time_end && empty($deal_time_began) ) {
                if( $current_time <= $deal_time_end ){
                    $validity = true;
                }
            // if both time is set
            } else {
                if( ($current_time >= $deal_time_began) && ($current_time <= $deal_time_end) ){
                    $validity = true;
                }
            }

        } else {
            $validity = true;
        }

        return $validity;
    }

    /**
     * Check & validate if a product has any deal or not
     */
    public static function products_validity( $product, $deal ){
        $validity              = false;

        $apply_on_all_products = !empty( $deal['apply_on_all_products'] ) ? $deal['apply_on_all_products'] : '';
        $applicable_categories = !empty( $deal['categories'] ) ? $deal['categories'] : array();
        $applicable_products   = !empty( $deal['products'] ) ? $deal['products'] : array();
        $exclude_products      = !empty( $deal['exclude_products'] ) ? $deal['exclude_products'] : array();

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

    /**
     * Loop through each deals and get the first matched deal for the given product
     */
    public static function get_deal( $product_id ){
        $product = wc_get_product($product_id);
        $flash_sale_settings = get_option('woolentor_flash_sale_settings');

        if( isset( $flash_sale_settings['deals'] ) && is_array( $flash_sale_settings['deals'] ) ){
            foreach( $flash_sale_settings['deals'] as $key => $deal ){
                $status = !empty($deal['status']) ? $deal['status'] : 'off';
                if( $status != 'on' ){
                    continue;
                }

                if( self::user_validity($deal) && self::datetime_validity($deal) && self::products_validity($product, $deal) ){
                    return $deal;
                    break;
                }

            }
        }

        return array();
    }

    /**
     * One a deal found for the given product
     * Calculate the the price based on the discount/deal matched with the given product
     */
    public static function get_calculated_price( $product_id = '', $deal = [], $price = '' ){
       $product = wc_get_product($product_id);

       $regular_price = get_post_meta( $product_id, '_regular_price', true );
       $sale_price    = get_post_meta( $product_id, '_sale_price', true );

       $discount_type  = !empty($deal['discount_type']) ? $deal['discount_type'] : 'fixed_discount';
       $discount_value = !empty($deal['discount_value']) ? $deal['discount_value'] : '';

       $base_price     = (float) $regular_price;
       $override_sale_price = woolentor_get_option('override_sale_price', 'woolentor_flash_sale_settings');
       if( $override_sale_price && $sale_price ){
           $base_price = (float) $sale_price;
       }

       if($price){
            $base_price = (float) $price;
       }
       
       // prepare discounted price
       if( $discount_type == 'fixed_discount' ){
           
            $discount_value = (float) $discount_value;
            if( $base_price > $discount_value ){
                return $base_price - $discount_value;
            }else{
                return 0;
            }

       } elseif( $discount_type == 'percentage_discount' ){

           return $base_price * (1 -  (float) $discount_value / 100);

       } elseif( $discount_type == 'fixed_price' ){

           return  (float) $discount_value;

       }
    }

    /**
     * Calculate the discounted price for variable product
     */
    public function get_calculated_price_variable( $product, $price, $deal ){
       $base_price     = (float) $product->get_regular_price();

       $discount_type  = !empty($deal['discount_type']) ? $deal['discount_type'] : 'fixed_discount';
       $discount_value = !empty($deal['discount_value']) ? $deal['discount_value'] : '';

       $override_sale_price = woolentor_get_option('override_sale_price', 'woolentor_flash_sale_settings');
       if( $override_sale_price ){
           $base_price = (float) $price;
       }

       // prepare discounted price
       if( $discount_type == 'fixed_discount' ){

           return $base_price -  (float) $discount_value;

       } elseif( $discount_type == 'percentage_discount' ){

           return $base_price * (1 -  (float) $discount_value / 100);

       } elseif( $discount_type == 'fixed_price' ){

           return  (float) $discount_value;

       }
    }

    /**
     * Render countdown
     */
    public function render_countdown(){
        if( !is_product() ){
            return;
        }

        global $product;

        $countdown_styles = array(
            '1' => 'default',
            '2' => 'flip',
        );

        $flash_sale_settings = get_option('woolentor_flash_sale_settings');
        $countdown_style     = woolentor_get_option('countdown_style', 'woolentor_flash_sale_settings', '2');
        $countdown_style     = $countdown_styles[$countdown_style];
        $countdown_heading   = woolentor_get_option('countdown_timer_title', 'woolentor_flash_sale_settings', esc_html__('Hurry Up! Offer ends in', 'woolentor'));
        $deal                = self::get_deal($product->get_id());
        $countdown_status    = woolentor_get_option('enable_countdown_on_product_details_page', 'woolentor_flash_sale_settings', 'on');
        $deal_time_end       = !empty($deal['end_date']) ? $deal['end_date'] : '';

        $remaining_time = self::get_remaining_time($deal);

        if( $deal && $deal_time_end && $countdown_status == 'on' ):
            $custom_labels = apply_filters('woolentor_countdown_custom_labels', array(
                'daytxt'     => esc_html__('Days', 'woolentor'),
                'hourtxt'    => esc_html__('Hours', 'woolentor'),
                'minutestxt' => esc_html__('Min', 'woolentor'),
                'secondstxt' => esc_html__('Sec', 'woolentor')
            ));
        ?>

        <div class="woolentor-flash-product-countdown">

            <?php if($countdown_heading): ?>
                <p class="woolentor-flash-product-offer-timer-text"><?php echo wp_kses_post( $countdown_heading ) ?></p>
            <?php endif; ?>

            <div class="woolentor-countdown woolentor-countdown-<?php echo esc_attr($countdown_style); ?>" data-countdown="<?php echo esc_attr( $remaining_time ) ?>" data-customlavel='<?php echo wp_json_encode( $custom_labels ) ?>'></div>
        </div>

        <?php
        endif;
    }

    /**
     * Update variable product price has to flush the cached price
     */
    public function variable_product_prices_hash( $price_hash, $product, $for_display ) {
        $override_sale_price = woolentor_get_option('override_sale_price', 'woolentor_flash_sale_settings');

        $deal           = self::get_deal( $product->get_id() );
        $discount_type  = !empty($deal['discount_type']) ? $deal['discount_type'] : 'percentage';
        $discount_value = !empty($deal['discount_value']) ? $deal['discount_value'] : '';

        if( $deal && self::datetime_validity($deal) && $discount_value ){
            $price_hash['wlfs_override_sale_price'] = $override_sale_price;
            $price_hash['wlfs_discount_type']       = $discount_type;
            $price_hash['wlfs_discount_value']      = $discount_value;
        }

        return $price_hash;
    }

    /**
     * Get grouped product prices
     */
    public function get_grouped_prices( $product, $price = '' ) {
        $tax_display_mode = get_option( 'woocommerce_tax_display_shop' );
        $child_prices     = array();
        $price_range      = array();
        $children         = array_filter( array_map( 'wc_get_product', $product->get_children() ), 'wc_products_array_filter_visible_grouped' );

        foreach ( $children as $child ) {
            if ( '' !== $child->get_price() ) {
                $child_prices[] = 'incl' === $tax_display_mode ? wc_get_price_including_tax( $child ) : wc_get_price_excluding_tax( $child );
            }
        }

        if ( ! empty( $child_prices ) ) {
            $price_range['min'] = min( $child_prices );
            $price_range['max'] = max( $child_prices );
        } else {
            $price_range['min'] = '';
            $price_range['max'] = '';
        }

        return $price_range;
    }

}

Woolentor_Flash_Sale::get_instance();