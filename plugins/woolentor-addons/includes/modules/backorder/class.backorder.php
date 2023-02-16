<?php  
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Woolentor_Backorder extends WC_Product{

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
    function __construct(){

        // Frontend scripts
        add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        // Admin scripts
        add_action('admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

        // Save order meta data while placing order
        add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'line_item_save' ], 99, 4 );

        // Through limit notice for product details page
        add_filter( 'woocommerce_add_cart_item_data', [ $this, 'render_single_product_notice' ], 99, 4 );

        // Through limit notice for cart page
        add_action('woocommerce_check_cart_items', [ $this, 'check_cart_item_backorder_limit' ] );

        // Add and save meta fields
        add_action( 'woocommerce_product_options_stock_status', [ $this, 'add_product_meta_fields' ] );
        add_action( 'woocommerce_process_product_meta', [ $this, 'save_product_metabox'], 10, 2 );

        // Render backorder availability text on product page
        add_filter('woocommerce_get_availability_text', [ $this, 'filter_get_availability_text'], 10, 2 );

        // Render backorder label to the cart page
        add_action('woocommerce_after_cart_item_name', [ $this, 'render_backorder_availability_cart_page'], 10, 2 );

        // Meta data display
        add_filter( 'woocommerce_order_item_get_formatted_meta_data', [ $this, 'item_get_formatted_meta_data' ], 10, 4 );

    }


    /**
     * Enqueue scripts
     */
    public function enqueue_scripts(){
        if( is_cart() ){
            wp_enqueue_style( 'woolentor-backorder', plugin_dir_url( __FILE__ ) . 'assets/css/backorder.css', '', WOOLENTOR_VERSION, 'all' );
        }
    }

    /**
     * Enqueue scripts admin
     */
    public function admin_enqueue_scripts(){
        global $typenow;

        if( $typenow == 'product' ){
            wp_enqueue_style( 'woolentor-backorder-admin', plugin_dir_url( __FILE__ ) . 'assets/css/backorder-admin.css', '', WOOLENTOR_VERSION, 'all' );
            wp_enqueue_script( 'woolentor-backorder-admin', plugin_dir_url( __FILE__ ) . 'assets/js/backorder-admin.js', array('jquery'), WOOLENTOR_VERSION, true );
        }
    }

    /**
     * Save line items custom metadata
     * Line items refers to the individual item of an order
     */
    public function line_item_save( $item, $cart_item_key, $values, $order ) {
        $product = $values['data'];

        if( $product->is_on_backorder($values['quantity']) ){
            $backorder_qty = $values['quantity'] - max( 0, $product->get_stock_quantity() );
            $item->add_meta_data( 'woolentor_backordered', $backorder_qty, true );
        }
    }

    /**
     * Manage Order Item in thank you page || admin order page
     *
     * @param [array] $formatted_meta
     * @param [type] $item
     * @return void
     */
    public function item_get_formatted_meta_data( $formatted_meta, $item ) {

		foreach ( $formatted_meta as $key => $meta ) {
			if ( $meta->key == 'woolentor_backordered' ) {
                $meta->display_key  = esc_html__('Backordered','woolentor');
			}
		}

		return $formatted_meta;
	}

    /**
     * Looks through the cart to check each item is within backorder limit. If not, add an error.
     */
    public function check_cart_item_backorder_limit() {
        $result = true;
        $error  = new WP_Error();

        foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
            $product_data = $values['data'];
            $variation_id = '';
            $product_id   = '';

            if( $product_data->is_type('variation') ){
                $variation_id = $product_data->get_id();
            } else {
                $product_id = $product_data->get_id();
            }

            if( 
                !$product_data->managing_stock() && $product_data->get_stock_status() == 'onbackorder' || 
                 $product_data->managing_stock() && $product_data->backorders_allowed()
            ){

                $limit_status = $this->get_limit_crossed_status( $product_id, $variation_id, 0 );

                if( $limit_status ){
                    $can_buy_max = ((int) $limit_status['backorder_limit'] + (int) $limit_status['stock_qty']) - (int) $limit_status['qty_already_backordered'];

                    $error->add( 'woolentor_out_of_backorder_limit', sprintf( __( 'Sorry, "%s" has reached its maximum backorder limit. Orders can be placed for up to <b>%s</b> units.', 'woolentor' ), $product_data->get_name(), $can_buy_max ) );

                    $result = $error;
                }
            }
        }


        if ( is_wp_error( $result ) ) {
            wc_add_notice( $result->get_error_message(), 'error' );
            $return = false;
        }

        return $result;
    }

    /**
     * Render backorder limit notice for single product page
     */
    public function render_single_product_notice( $cart_item_data, $product_id, $variation_id, $quantity ){
        $product_id   = absint( $product_id );
        $variation_id = absint( $variation_id );

        // Ensure we don't add a variation to the cart directly by variation ID.
        if ( 'product_variation' === get_post_type( $product_id ) ) {
            $variation_id = $product_id;
            $product_id   = wp_get_post_parent_id( $variation_id );
        }

        $product_data = wc_get_product( $variation_id ? $variation_id : $product_id );

        $quantity     = apply_filters( 'woocommerce_add_to_cart_quantity', $quantity, $product_id );

        if( 
            !$product_data->managing_stock() && $product_data->get_stock_status() == 'onbackorder' || 
             $product_data->managing_stock() && $product_data->backorders_allowed()
        ){
            $limit_status = $this->get_limit_crossed_status($product_id, $variation_id, $quantity);

            if($limit_status ){

                $backorder_limit         = (int) $limit_status['backorder_limit'];
                $add_to_cart_qty         = $quantity;
                $stock_qty               = (int) $limit_status['stock_qty'];
                $qty_already_backordered = (int) $limit_status['qty_already_backordered'];
                $qty_already_on_cart     = (int) $limit_status['qty_on_cart'];

                $can_add_to_cart_max = ($backorder_limit + $stock_qty) - ($qty_already_backordered + $qty_already_on_cart);
                $can_add_to_cart_max = $can_add_to_cart_max < 0 ? 0 : $can_add_to_cart_max;

                // If this product already has on cart && user allowed add on "Cart" at least 1 qty
                if( $qty_already_on_cart > 0 ){

                    $message = sprintf(
                        '<a href="%s" class="button wc-forward">%s</a> %s',
                        wc_get_cart_url(),
                        __( 'View cart', 'woolentor' ),
                        /* translators: 1: quantity in stock 2: current quantity */
                        sprintf( __( 'Sorry, "%s" has reached its maximum backorder limit — (%s available). You already have %s in your cart.', 'woolentor' ), $product_data->get_name(), $can_add_to_cart_max, $qty_already_on_cart )
                    );

                    $message = apply_filters( 'wlbackorder_cart_product_not_enough_stock_already_in_cart_message', $message, $product_data, $can_add_to_cart_max, $qty_already_on_cart );

                } else {
                    $message = sprintf( __( 'Sorry, "%s" was not added to cart because it has reached the maximum backorder limit. (%s available).', 'woolentor' ), $product_data->get_name(), $can_add_to_cart_max );

                    $message = apply_filters( 'wlbackorder_cart_product_not_enough_stock_message', $message, $product_data, $can_add_to_cart_max );
                }
                

                throw new Exception( $message );
            }  

        }

        return $quantity;
    }

    /**
     * Look for the respected product into the cart and get the total quantities if it is available to the cart table
     */
    public function get_qty_in_cart( $product_id = '', $variation_id = '' ){
        $product_id   = absint( $product_id );
        $variation_id = absint( $variation_id );

        $p_id = $variation_id ? $variation_id : $product_id;
        $product_data = wc_get_product($p_id);

        if( $product_data->is_type('variation') && $product_data->managing_stock() == 'parent' ){
            $p_id = $product_data->get_parent_id();
        }

        $products_qty_in_cart = WC()->cart->get_cart_item_quantities();
        $qty_in_cart = !empty($products_qty_in_cart[$p_id]) ? $products_qty_in_cart[$p_id] : 0;

        return $qty_in_cart;
    }

    /**
     * Look for the respected product if it is already reached the backorder limit
     */
    public function get_limit_crossed_status( $product_id = '', $variation_id = '', $qty = 0 ){
        $product_id   = absint( $product_id );
        $variation_id = absint( $variation_id );

        // Ensure we don't add a variation to the cart directly by variation ID.
        if ( 'product_variation' === get_post_type( $product_id ) ) {
            $variation_id = $product_id;
            $product_id   = wp_get_post_parent_id( $variation_id );
        }

        $p_id            = $variation_id ? $variation_id : $product_id;
        $product_data    = wc_get_product( $p_id );
        $backorder_limit = (int) $this->get_option('backorder_limit', $product_id, $variation_id );

        // Get the order count of a product/variation product
        $qty_already_backordered = (int) $this->get_total_qty_already_backordered( $product_id, $variation_id );

        // Get qty count from the cart page
        $qty_on_cart = (int) $this->get_qty_in_cart( $product_id, $variation_id );

        // Sum with qty_already_backordered and qty_in_cart
        $qunatities = $qty + $qty_already_backordered + $qty_on_cart;

        // Deduct stock qty
        $stock_qty = $product_data->get_stock_quantity() > 0 ? $product_data->get_stock_quantity() : 0;

        if( $product_data->is_on_backorder($qty + $qty_on_cart) && $stock_qty ){
            $qunatities  = $qunatities - $stock_qty;
        }

        if( $qunatities > $backorder_limit ){
            return array(
                'status'                  => true,
                'backorder_limit'         => $backorder_limit,
                'qty_already_backordered' => $qty_already_backordered,
                'qty_on_cart'             => $qty_on_cart,
                'stock_qty'               => $stock_qty
            );
        }

        return false;
    }

    /**
     * Loop trhough the orders and get the total quantities ordered of the respected product
     */
    public function get_total_qty_already_backordered( $product_id = '', $variation_id = '' ){
        $product_id   = absint( $product_id );
        $variation_id = absint( $variation_id );
        $p_id         = $variation_id ? $variation_id : $product_id;

        $qty_already_backordered = 0;

        $status = wc_get_order_statuses();
        unset($status['wc-pending']);

        $limit = apply_filters('wlbackorder_order_query_limit', 250);

        $query = new WC_Order_Query( array(
            'status'  => array('wc-pending', 'wc-processing', 'wc-on-hold', 'wc-completed'),
            'limit'   => $limit,
            'orderby' => 'date',
            'order'   => 'DESC',
        ) );
        $orders = $query->get_orders();

        // Loop through all orders
        foreach($orders as $order){
            $line_items = $order->get_items();

            // Loop throgh on the current order items
            foreach($line_items as $item){
                $item_p_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();

                if( $p_id == $item_p_id ){

                    // check if this line item is under woolentor backorder
                    if($item->meta_exists('woolentor_backordered') && $item->get_meta('woolentor_backordered')){
                        $backorder_qty = (int) $item->get_meta('woolentor_backordered');
                        $qty_already_backordered += $backorder_qty;
                        break;
                    }
                }
            }
        }

        return $qty_already_backordered;
    }

    /**
     * Generate and return backorder availability message
     */
    public function get_availability_message( $product_id ){
        $availability_date = $this->get_option('backorder_availability_date', $product_id);
        $timestamp = strtotime($availability_date);
        if($timestamp){
           $availability_date = date(get_option('date_format'), $timestamp); 
        }

        $backorder_limit   = $this->get_option('backorder_limit', $product_id);

        $availability_message = woolentor_get_option('backorder_availability_message', 'woolentor_backorder_settings');
        $availability_message = str_replace( '{availability_date}', '<span class="woolentor-backorder-availability">'.$availability_date.'</span>', $availability_message );

        if( $backorder_limit && $availability_message ){
            $availability_message = $availability_message;
        } elseif( $backorder_limit && $availability_date && empty($availability_message) ){
            $availability_message = __( 'On Backorder. Will be available on: '. $availability_date, 'woolentor' );
        }

        return $availability_message;
    }

    /**
     * Render the backorder availability message
     */
    public function filter_get_availability_text( $availability, $product ){

        $product_id = $product->get_id();
        if( $product->is_type('variation') ){
            $product_id = $product->get_parent_id();
        }

        $availability_message = $this->get_availability_message( $product_id ) ? $this->get_availability_message( $product_id ) : $availability;

        if ( $product->managing_stock() && $product->is_on_backorder( 1 ) ) {
            $availability = $product->backorders_require_notification() ? $availability_message : '';
        } elseif ( ! $product->managing_stock() && $product->is_on_backorder( 1 ) ) {
            $availability = $availability_message;
        }

        return $availability;
    }

    /**
     * Render the backorder availability message on cart page
     */
    public function render_backorder_availability_cart_page( $cart_item, $cart_item_key ){
        $product_data = $cart_item['data'];

        if($product_data->is_type('simple')){
            $product_id = $product_data->get_id();
        } elseif( $product_data->is_type('variation') ){
            $product_id = $product_data->get_parent_id();
        }

        if( $product_data->is_on_backorder() ){
            echo '<p class="woolentor-backorder-notification backorder_notification">';
            echo wp_kses_post($this->get_availability_message( $product_id ));
            echo '</p>';
        }
    }


    /**
     * Get the option value either from metadata or the global settings
     */
    public function get_option( $option_name = '', $product_id = '', $variation_id = '' ){

        $product_id   = absint( $product_id );
        $variation_id = absint( $variation_id );
        $p_id         = $variation_id ? $variation_id : $product_id;

        $product_data = wc_get_product( $p_id );
        if( $product_data->is_type('variation') ){
            $product_id = $product_data->get_parent_id();
        }

        $global_settings_value =  woolentor_get_option( $option_name, 'woolentor_backorder_settings');
        $meta_value            = get_post_meta( $product_id, '_woolentor_'. $option_name, true );

        if( $meta_value ){
            $global_settings_value = $meta_value;
        }

        return $global_settings_value;
    }

    /**
     * Render backorder meta fields into the inventory tab
     */
    public function add_product_meta_fields(){
        $product_id = get_the_id();
        $product    = wc_get_product($product_id);

        $backorder_limit_global = woolentor_get_option('backorder_limit', 'woolentor_backorder_settings');
        $backorder_limit_global = $backorder_limit_global ? __( "Store-wide backorder limit ($backorder_limit_global)", "woolentor") : '';

        $availability_date_global = woolentor_get_option('backorder_availability_date', 'woolentor_backorder_settings');
        $availability_date_global = $availability_date_global ? __( "Store-wide availability ($availability_date_global)", "woolentor") : '';

        $backorder_limit        = get_post_meta( $product_id, '_woolentor_backorder_limit', true );
        $backorder_availability = get_post_meta( $product_id, '_woolentor_backorder_availability_date', true );

        // Stock status
        $manage_stock = get_post_meta($product_id, '_manage_stock', true);
        $stock_status = $product->is_type('simple') ? get_post_meta($product_id, '_stock_status', true) : '';
        $allow_backorder = get_post_meta($product_id, '_backorders', true);
        ?>

        <div class="woolentor-backorder-fields show_if_simple show_if_variable wl_manage_stock--<?php echo esc_attr($manage_stock); ?> wl_stock_status--<?php echo esc_attr($stock_status); ?>  wl_allow_backorder--<?php echo esc_attr($allow_backorder); ?>">

        <?php
        woocommerce_wp_text_input(
            array(
                'id'                => '_woolentor_backorder_limit',
                'value'             =>  $backorder_limit,
                'label'             => __( 'Backorder Limit', 'woolentor' ),
                'placeholder'       => $backorder_limit_global,
                'wrapper_class'     => '',
                'desc_tip'          => true,
                'description'       => __( 'Backorder limit. If this is a variable product this value will be used to control backorder limit for all variations, unless you define backorder limit at variation level.', 'woolentor' ),
                'type'              => 'number',
                'custom_attributes' => array(
                    'step' => 'any',
                ),
            )
        );
        ?>
            <p class="form-field">
                <label for="_woolentor_backorder_availability_date"><?php echo esc_html__('Backorder Availability', 'woolentor') ?></label>
                <?php echo wc_help_tip( 'The selected date will show as a message to customer. You can customize the message as you need from the module settings.', 'woolentor' ); ?>
                <input type="date" class="short hasDatepicker" name="_woolentor_backorder_availability_date" id="_woolentor_backorder_availability_date" value="<?php echo esc_attr($backorder_availability); ?>" placeholder="<?php echo esc_attr($availability_date_global); ?>">
            </p>
        </div> <!-- .woolentor-backorder-fields -->
        <?php
    }

    /**
     * Save backorder meta fields into the inventory tab
     */
    public function save_product_metabox( $post_id, $post ){
        $posted_data     = wp_unslash( $_REQUEST );
        $backorder_limit = $posted_data['_woolentor_backorder_limit'];
        $backorder_availability = $posted_data['_woolentor_backorder_availability_date'];
        
        if( isset($posted_data['_woolentor_backorder_limit']) ){
            update_post_meta( $post_id, '_woolentor_backorder_limit', $backorder_limit );
        }

        if( isset($posted_data['_woolentor_backorder_availability_date']) ){
            update_post_meta( $post_id, '_woolentor_backorder_availability_date', $backorder_availability );
        }
    }
}

Woolentor_Backorder::get_instance();    