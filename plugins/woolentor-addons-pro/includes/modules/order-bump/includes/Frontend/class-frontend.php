<?php
namespace Woolentor\Modules\Order_Bump;

// If this file is accessed directly, exit
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


class Frontend{
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
        // Don't add order bump if test mode is enabled but the user is not admin
        if( Helper::is_test_mode() && !current_user_can( 'administrator' ) ){
            return;
        }

        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // Add order bumps
        add_action( 'wp', [ $this, 'add_order_bumps' ] );

        // Applying discount can be hanled in many ways, 
        // here we are using the woocommerce_before_calculate_totals to apply the discount on the cart item.
        // it doesn't affect the display price of the products, we will need to work on that separately
        add_action( 'woocommerce_before_calculate_totals', [ $this, 'set_discount_order_bumps' ], 10, 1 );

        // Alter display price
        add_filter( 'woocommerce_get_price_html', [ $this, 'alter_display_price' ], 10, 2 );

        // For Variations Popup
        add_action( 'woolentor_footer_render_content', [ $this, 'variations_popup_wrapper' ], 10 );

        // Fragments
        add_filter( 'woocommerce_update_order_review_fragments', [ $this, 'update_order_review_fragments' ] );
    }

    /**
     * It enqueues the CSS and JS files
     */
    public function enqueue_scripts(){
        wp_enqueue_style( 'woolentor-order-bump', MODULE_URL . '/assets/css/order-bump.css', [ 'slick' ], WOOLENTOR_VERSION );
        wp_enqueue_script( 'woolentor-order-bump', MODULE_URL . '/assets/js/order-bump.js', [ 'jquery', 'wc-add-to-cart-variation', 'slick' ], WOOLENTOR_VERSION, true );

        // Woolentor_order_bump_params
        wp_localize_script( 'woolentor-order-bump', 'woolentor_order_bump_params', [
            'ajax_url'     => admin_url( 'admin-ajax.php' ),
            'nonce'        => wp_create_nonce( 'woolentor_order_bump_nonce' ),
            'wp_debug_log' => WP_DEBUG_LOG,
            'i18n'         => [
                'product_added' => __( 'Product Added!', 'woolentor-pro' ),
                'product_removed' => __( 'Product Removed!', 'woolentor-pro' ),
            ],
        ] );
    }

    /**
     * It fetches all the available offers and then loops through them to render them on the defined hooks/postions.
     */
    public function add_order_bumps(){
        $available_offers = Manage_Rules::instance()->fetch_offers();

        if( !empty( $available_offers ) ){
            foreach ( $available_offers as $order_bump_id => $title ) {
                $meta_data = get_post_meta( $order_bump_id, '_woolentor_order_bump', true );
		        $position = !empty( $meta_data['position'] ) ? $meta_data['position'] : 'woocommerce_review_order_before_payment';
                
                $this->render_order_bump( $position, $order_bump_id );
            } // End foreach
        } // End if
    }

    /**
     * It iterates through each cart item and checks if the cart item an offer/order bump product.
     * If it is, it sets the price of the cart item to the discounted price
     * 
     * @param cart The cart object
     * 
     * @return the price of the product.
     */
    public function set_discount_order_bumps( $cart ){
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ){
            return;
        }

        if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ){
            return;
        }

        // Availabel offers
        $available_offers = Manage_Rules::instance()->fetch_offers();
        if( empty( $available_offers ) ){
            return;
        }

        // Iterating though each cart items
        foreach ( $cart->get_cart() as $cart_item ) {
            $cart_product         = $cart_item['data']; // Either product object or variation object

           // Iterating though each order bumps
           $i = 0;
            foreach ( $available_offers as $order_bump_id => $order_bump_name ) {

                // Don't go futher if there is no need to change the product price.
                if( !Helper::should_adjust_price($order_bump_id) ){
                    continue;
                }

                $i++;
                $meta_data          = get_post_meta( $order_bump_id, '_woolentor_order_bump', true );
		        $offer_product_id   = !empty( $meta_data['product'] ) ? $meta_data['product'] : 0; // Either simple, variable, variation or grouped product id
                $offer_product      = wc_get_product( $offer_product_id );

                // Skip iteration
                // If the offer product has childreen and the cart product is not a child of the offer product
                $children_ids = $offer_product->get_children();
                if( $offer_product->has_child() && !in_array( $cart_product->get_id(), $children_ids ) ){
                    continue;
                } elseif( !$offer_product->has_child() && $offer_product_id != $cart_product->get_id() ){
                    continue;
                }

                // Here the $price param is the actice price of the product
                $discounted_price = Helper::get_discounted_price( $order_bump_id, $cart_product, $offer_product->get_price() );

                // Set the new price
                $cart_item['data']->set_price( $discounted_price );
            }

        }
    }

    /**
     * Chagne display price on order bump products.
     * 
     * @param price_html The price HTML that is being displayed.
     * @param product The product object.
     */
    public function alter_display_price( $price_html, $product ){
        // Alter only for the mentioned product types
        if( !in_array( $product->get_type(), array('simple', 'variable', 'variation') ) ){
            return $price_html;
        }

        // Alter only for mentioned screen/pages
        if( !in_array( $this->get_current_screen(), array('cart', 'checkout', 'elementor_preview', 'woolentor_order_bump_variations_popup' ) ) ){
            return $price_html;
        }

        $product_id    = $product->get_id(); // Either simple, variable or variation product
        $product_id    = $product->is_type('variation') ? $product->get_parent_id() : $product_id ;
        $order_bump_id =  Manage_Rules::instance()->fetch_offer_by_product_id( $product_id );

        // Don't go further if there is no need to change the product price
        if( !Helper::should_adjust_price( $order_bump_id ) ){
            return $price_html;
        }

        // Alter only if the current product is available as an order bump product
        if( !$order_bump_id ){
            return $price_html;
        }

        if( $this->get_new_price_html( $order_bump_id, $product ) ){
            return $this->get_new_price_html( $order_bump_id, $product );
        }
        
        return $price_html;
    }

    /**
     * Returns the price html of the product after discount applied.
     * 
     * @param order_bump_id The ID of the order bump.
     * @param product The product object.
     * 
     * @return The new price html for the product.
     */
    public function get_new_price_html( $order_bump_id, $product ){
        $base_price_type    = Helper::get_option($order_bump_id, 'discount_base_price', 'regular_price');

        if( $product->is_type('variable') ){
            $price = $base_price_type == 'regular_price' ? $product->get_regular_price() : $product->get_price();
            
            $prices = $product->get_variation_prices( true );
            $min_price     = current( $prices['price'] );
			$max_price     = end( $prices['price'] );
			$min_reg_price = current( $prices['regular_price'] );
			$max_reg_price = end( $prices['regular_price'] );

            if( $base_price_type === 'regular_price' ){
                $discounted_min_price = (float) $min_reg_price - (float) Helper::get_discounted_price( $order_bump_id, $product, $min_reg_price );
                $discounted_max_price = (float) $max_reg_price - (float) Helper::get_discounted_price( $order_bump_id, $product, $max_reg_price );
            } else {
                $discounted_min_price = (float) $min_price - (float) Helper::get_discounted_price( $order_bump_id, $product, $min_price );
                $discounted_max_price = (float) $max_price - (float) Helper::get_discounted_price( $order_bump_id, $product, $max_price );
            }

			if ( $discounted_min_price !== $discounted_max_price ) {
				$new_price_html = sprintf('<span class="wl-price-range-old">%s</span><span class="wl-price-range-new">%s</span>',
                    wc_format_price_range( $min_price, $max_price ),
                    wc_format_price_range( $discounted_min_price, $discounted_max_price )
                );
			} elseif ( $product->is_on_sale() && $min_reg_price === $max_reg_price ) {
				$new_price_html = wc_format_sale_price( wc_price( $max_reg_price ), wc_price( $discounted_min_price ) );
			} else {
				$new_price_html = wc_price( $discounted_min_price );
			}
        } else {
            $discounted_price = '';
            $discounted_price = Helper::get_discounted_price( $order_bump_id, $product, '' );
            $price = $base_price_type == 'regular_price' ? $product->get_regular_price() : $product->get_price();
            
            $new_price_html = '';
            if( $discounted_price < $product->get_regular_price() ){
                $new_price_html = wc_format_sale_price( wc_get_price_to_display( $product, array( 'price' => $price ) ), wc_get_price_to_display( $product, array( 'price' => $discounted_price ) ) ) . $product->get_price_suffix($discounted_price);
            } else {
                $new_price_html = wc_price( wc_get_price_to_display( $product, array( 'price' => $discounted_price ) ) ) . $product->get_price_suffix($discounted_price);
            }
        }

        if( $new_price_html ){
            return $new_price_html;
        }
    }

    /**
     * Get the current page/screen or ajax action.
     */
    public function get_current_screen(){
        $current_screen = '';

        $preview_page_ids[] = woolentor_get_option('productcartpage', 'woolentor_woocommerce_template_tabs');
        $preview_page_ids[] = woolentor_get_option('productcheckoutpage', 'woolentor_woocommerce_template_tabs');

        $ajax_query_string  = !empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
        $list_table_page    = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';

        if( is_checkout() ){
            $current_screen = 'checkout';
        } elseif( is_cart() ){
            $current_screen = 'cart';
        } elseif( !empty($_GET['post']) && in_array( $_GET['post'], $preview_page_ids ) ){
            $current_screen = 'elementor_preview';
        } elseif( strpos($ajax_query_string, 'woolentor_order_bump_variations_popup') !== false ){
            $current_screen = 'woolentor_order_bump_variations_popup';
        } elseif( $list_table_page == 'woolentor-order-bump' ){
            $current_screen = 'list_table';
        }

        return $current_screen;
    }

    /**
     * It renders the order bump template.
     * 
     * @param hook_name The hook name where you want to display the order bump.
     * @param order_bump_id The ID of the order bump.
     */
    public function render_order_bump( $hook_name, $order_bump_id ){
        add_action( $hook_name, function() use ( $order_bump_id ){
            $meta_data        = get_post_meta( $order_bump_id, '_woolentor_order_bump', true );
            $offer_product_id = !empty( $meta_data['product'] ) ? $meta_data['product'] : 0;
            $product          = wc_get_product( $offer_product_id );
       
            // Markup
            $this->order_bump_markup( $order_bump_id, $product );
        }, 10 );
    }

    /**
     * It loads the order bump template
     * 
     * @param order_bump_id The ID of the order bump.
     * @param product The product object of the order bump.
     * @param echo Whether to echo the markup or return it.
     * 
     * @return The markup for the order bump.
     */
    public function order_bump_markup( $order_bump_id = 0, $product = 0, $echo = true ){
        // Load order bump tamplate
        if( !$echo ){
            ob_start();
        }

        wc_get_template( 
            'order-bump.php',
            array(
                'order_bump_id' => $order_bump_id,
                'product' => $product,
            ),
            'wl-woo-templates/order-bump',
            MODULE_PATH. '/templates/'
        );

        if( !$echo ){
            return ob_get_clean();
        }
    }
    
    /**
     * Variations Popup Markup
     */
    public function variations_popup_wrapper(){
        echo '<div class="woocommerce" id="woolentor-order-bump-variations-popup"><div class="woolentor-order-bump-modal-dialog product"><div class="woolentor-order-bump-modal-content"><button type="button" class="woolentor-order-bump-close"><span class="sli sli-close"><span class="woolentor-order-bump-placeholder-remove">'.esc_html__('X','woolentor-pro').'</span></span></button><div class="woolentor-order-bump-modal-body"></div></div></div></div>';
    }

    public function update_order_review_fragments( $fragments ){
        $available_offers = Manage_Rules::instance()->fetch_offers();

        if( !empty( $available_offers ) ){
            foreach ( $available_offers as $order_bump_id => $title ) {
                $meta_data        = get_post_meta( $order_bump_id, '_woolentor_order_bump', true );
                $offer_product_id = !empty( $meta_data['product'] ) ? $meta_data['product'] : 0;
                $offer_product    = wc_get_product( $offer_product_id );

                $fragments['.woolentor-order-bump-'. $order_bump_id] = $this->order_bump_markup( $order_bump_id, $offer_product, false );
            } // End foreach
        } // End if

        
        return $fragments;
    }
}