<?php
namespace WishSuite\Frontend;
/**
 * Manage Wishlist class
 */
class Manage_Wishlist {

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Manage_Wishlist]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Initialize the class
     */
    private function __construct() {
        add_action( 'init', [ $this, 'button_manager' ] );

        // Remove wishlist item after add to cart.
        add_action( 'woocommerce_add_to_cart', [ $this, 'remove_wishlist_after_add_to_cart' ], 10, 6 );

    }

    /**
     * [add_product] Product Add
     * @param [int] $id
     */
    public function add_product( $id ){

        $user_id = get_current_user_id();
        $add_status = false;

        if( $user_id ){

            $args = [
                'product_id' => $id,
                'user_id' => $user_id
            ];

            $insert_id = \WishSuite\Manage_Data::instance()->create( $args );
            $add_status = $insert_id;

        }else{

            $cookie_name = $this->get_cookie_name();

            if ( $this->is_product_in_wishlist( $id ) ) {
                $add_status = false;
            }

            $products = $this->get_wishlist_products();
            $products[] = $id;

            setcookie( $cookie_name, json_encode( $products ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
            $_COOKIE[$cookie_name] = json_encode( $products );
            $add_status = true;

        }

        return $add_status;

    }

    /**
     * [remove_product]
     * @param  [type] $id
     * @return [void]
     */
    public function remove_product( $id ){
        $user_id = get_current_user_id();
        $delete_status = false;

        if( $user_id ){
            $deleted = \WishSuite\Manage_Data::instance()->delete( $user_id, $id );
            $delete_status = $deleted;
        }else{

            $cookie_name = $this->get_cookie_name();

            $products = $this->get_wishlist_products();

            if( in_array( $id, $products ) ){

                foreach ( $products as $prod_key => $product_id ) {
                    if ( intval( $id ) == $product_id ) {
                        unset( $products[ $prod_key ] );
                    }
                }

                if ( empty( $products ) ) {
                    setcookie( $cookie_name, false, 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
                    $_COOKIE[$cookie_name] = false;
                } else {
                    setcookie( $cookie_name, json_encode( $products ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
                    $_COOKIE[$cookie_name] = json_encode( $products );
                }
                $delete_status = true;

            }else{
                $delete_status = false;
            }

        }

        return $delete_status;
    }

    /**
     * [remove_wishlist_after_add_to_cart]
     * @param  [type] $cart_item_key
     * @param  [type] $product_id
     * @param  [type] $quantity
     * @param  [type] $variation_id
     * @param  [type] $variation
     * @param  [type] $cart_item_data
     * @return [type]
     */
    public function remove_wishlist_after_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ){
        if( isset( $product_id ) && 'on' === woolentor_get_option( 'after_added_to_cart', 'wishsuite_table_settings_tabs', 'on' ) ){
            $this->remove_product( $product_id );
        }
    }

    /**
     * [button_manager] Button Manager
     * @return [void]
     */
    public function button_manager(){

        $shop_page_btn_position     = woolentor_get_option( 'shop_btn_position', 'wishsuite_settings_tabs', 'after_cart_btn' );
        $product_page_btn_position  = woolentor_get_option( 'product_btn_position', 'wishsuite_settings_tabs', 'after_cart_btn' );

        $enable_btn         = woolentor_get_option( 'btn_show_shoppage', 'wishsuite_settings_tabs', 'off' );
        $product_enable_btn = woolentor_get_option( 'btn_show_productpage', 'wishsuite_settings_tabs', 'on' );
        
        // Shop Button Position
        if( $shop_page_btn_position != 'use_shortcode' && $enable_btn == 'on' ){
            switch ( $shop_page_btn_position ) {
                case 'before_cart_btn':
                    add_action( 'woocommerce_after_shop_loop_item', [ $this, 'button_print' ], 7 );
                    break;

                case 'top_thumbnail':
                    add_action( 'woocommerce_before_shop_loop_item', [ $this, 'button_print' ], 5 );
                    break;

                case 'custom_position':
                    $hook_name = woolentor_get_option( 'shop_custom_hook_name', 'wishsuite_settings_tabs', '' );
                    $priority = woolentor_get_option( 'shop_custom_hook_priority', 'wishsuite_settings_tabs', 10 );
                    if( !empty( $hook_name ) ){
                        add_action( $hook_name, [ $this, 'button_print' ], $priority );
                    }
                    break;
                
                default:
                    add_action( 'woocommerce_after_shop_loop_item', [ $this, 'button_print' ], 20 );
                    break;
            }
        }
        
        // Product Page Button Position
        if( $product_page_btn_position != 'use_shortcode' && $product_enable_btn == 'on' ){
            switch ( $product_page_btn_position ) {
                case 'before_cart_btn':
                    add_action( 'woocommerce_before_add_to_cart_button', [ $this, 'button_print' ], 20 );
                    break;

                case 'after_thumbnail':
                    add_action( 'woocommerce_product_thumbnails', [ $this, 'button_print' ], 21 );
                    break;

                case 'after_summary':
                    add_action( 'woocommerce_after_single_product_summary', [ $this, 'button_print' ], 11 );
                    break;

                case 'custom_position':
                    $hook_name = woolentor_get_option( 'product_custom_hook_name', 'wishsuite_settings_tabs', '' );
                    $priority = woolentor_get_option( 'product_custom_hook_priority', 'wishsuite_settings_tabs', 10 );
                    if( !empty( $hook_name ) ){
                        add_action( $hook_name, [ $this, 'button_print' ], $priority );
                    }
                    break;
                
                default:
                    add_action( 'woocommerce_single_product_summary', [ $this, 'button_print' ], 31 );
                    break;
            }
        }

    }

    /**
     * [add_button]
     * @return [void]
     */
    public function button_print(){
        echo do_shortcode( '[wishsuite_button]' );
    }

    /**
     * [button_html] Wishlist Button HTML
     * @param  [type] $atts template attr
     * @return [HTML]
     */
    public function button_html( $atts ) {
        $button_attr = apply_filters( 'wishsuite_button_arg', $atts );
        return wishsuite_get_template( 'wishsuite-button-'.$atts['template_name'].'.php', $button_attr, false );
    }

    /**
     * [table_html] Wishlist table HTML
     * @return [HTML]
     */
    public function table_html( $atts ) {
        $table_attr = apply_filters( 'wishsuite_table_arg', $atts );
        return wishsuite_get_template( 'wishsuite-table.php', $table_attr, false );
    }

    /**
     * [counter_html] Wishlist counter HTML
     * @return [HTML]
     */
    public function count_html( $atts ) {
        $count_attr = apply_filters( 'wishsuite_count_arg', $atts );
        return wishsuite_get_template( 'wishsuite-count.php', $count_attr, false );
    }

    /**
     * [get_cookie_name] Get cookie name
     * @return [string]
     */
    public function get_cookie_name() {
        $name = 'wishsuite_item_list';
        if ( is_multisite() ){
            $name .= '_' . get_current_blog_id();
        }
        return $name;
    }
    
    /**
     * [get_wishlist_products]
     * @param  integer $per_page
     * @param  integer $offset
     * @return [array]
     */
    public function get_wishlist_products( $per_page = 20, $offset = 0 ){

        if( is_user_logged_in() ){
            $args = [
                'number'  => $per_page,
                'offset'  => $offset,
            ];
            $items = \WishSuite\Manage_Data::instance()->read( $args );

            $ids = array();
            foreach ( $items as $itemkey => $item ) {
                $ids[] = $item['product_id'];
            }
            return $ids;
        }else{
            $cookie_name = $this->get_cookie_name();
            // return isset( $_COOKIE[ $cookie_name ] ) ? array_map( 'sanitize_text_field', json_decode( wp_unslash( $_COOKIE[ $cookie_name ] ), true ) ) : array();
            if( isset( $_COOKIE[ $cookie_name ] ) && is_array( json_decode( wp_unslash( $_COOKIE[ $cookie_name ] ), true ) ) ){
                return array_map( 'sanitize_text_field', json_decode( wp_unslash( $_COOKIE[ $cookie_name ] ), true ) );
            }else{
                return array();
            }
        }

    }

    /**
     * [is_product_in_wishlist] Check product in list
     * @param  [int] $id [description]
     * @return boolean
     */
    public function is_product_in_wishlist( $id ) {
        $id = (string) $id;
        $list = $this->get_wishlist_products();
        if ( is_array( $list ) ) {
            return in_array( $id, $list, true );
        }else{
            return false;
        }
    }

    /**
     * [get_products_data] generate wishlist products data
     * @return [array] product list
     */
    public function get_products_data() {

        $ids = $this->get_wishlist_products();

        $shareablebtn = woolentor_get_option( 'enable_social_share','wishsuite_table_settings_tabs','on' );
        if ( ( $shareablebtn === 'on' ) && isset( $_GET['wishsuitepids'] ) ) {
            $query_perametter_ids = sanitize_text_field( $_GET['wishsuitepids'] );
            if( !empty( $query_perametter_ids ) ){
                $ids = explode( ',', $query_perametter_ids );
            }
        }

        if ( empty( $ids ) ) {
            return array();
        }

        $args = array(
            'include' => $ids,
        );

        $products = wc_get_products( $args );

        $products_data = array();

        $fields = $this->get_all_fields();

        $fields = array_filter( $fields, function(  $field ) {
            return 'pa_' === substr( $field, 0, 3 );
        }, ARRAY_FILTER_USE_KEY );

        $data_none = '-';

        foreach ( $products as $product ) {

            $rating_count   = $product->get_rating_count();
            $average        = $product->get_average_rating();

            $get_row = \WishSuite\Manage_Data::instance()->read_single_item( get_current_user_id(), $product->get_id() );
            if( is_object( $get_row ) && $get_row->quantity ){
                $min_value = $get_row->quantity;
            }else{
                $min_value = $product->get_min_purchase_quantity();
            }
            $quantity_args = array(
                'input_value' => $min_value,
                'min_value'   => $product->get_min_purchase_quantity(),
                'max_value'   => $product->get_max_purchase_quantity(),
            );

            $products_data[ $product->get_id() ] = array(
                'id'            => $product->get_id(),
                'remove'        => $product->get_id(),
                'image'         => $product->get_image() ? $product->get_image('wishsuite-image') : $data_none,
                'title'         => $product->get_title() ? $product->get_title() : $data_none,
                'image_id'      => $product->get_image_id(),
                'permalink'     => $product->get_permalink(),
                'price'         => $product->get_price_html() ? $product->get_price_html() : $data_none,
                'rating'        => wc_get_rating_html( $average, $rating_count ),
                'add_to_cart'   => $this->add_to_cart_html( $product, $min_value ) ? $this->add_to_cart_html( $product, $min_value ) : $data_none,
                'quantity'      => woocommerce_quantity_input( $quantity_args, $product, false ),
                'dimensions'    => wc_format_dimensions( $product->get_dimensions( false ) ),
                'description'   => $product->get_short_description() ? $product->get_short_description() : $data_none,
                'weight'        => $product->get_weight() ? $product->get_weight() : $data_none,
                'sku'           => $product->get_sku() ? $product->get_sku() : $data_none,
                'availability'  => $this->availability_html( $product ),
            );

            foreach ( $fields as $field_id => $field_name ) {
                if ( taxonomy_exists( $field_id ) ) {
                    $products_data[ $product->get_id() ][ $field_id ] = array();
                    $terms = get_the_terms( $product->get_id(), $field_id );
                    if ( ! empty( $terms ) ) {
                        foreach ( $terms as $term ) {
                            $term = sanitize_term( $term, $field_id );
                            $products_data[ $product->get_id() ][ $field_id ][] = $term->name;
                        }
                    } else {
                        $products_data[ $product->get_id() ][ $field_id ][] = '-';
                    }
                    $products_data[ $product->get_id() ][ $field_id ] = implode( ', ', $products_data[ $product->get_id() ][ $field_id ] );
                }
            }
            
        }

        return $products_data;
    }

    /**
     * [get_all_fields] Table field list
     * @return [array] Table Field list
     */
    public function get_all_fields() {
        
        $default_show = array(
            'remove'        => esc_html__( 'Remove', 'wishsuite' ),
            'image'         => esc_html__( 'Image', 'wishsuite' ),
            'title'         => esc_html__( 'Title', 'wishsuite' ),
            'price'         => esc_html__( 'Price', 'wishsuite' ),
            'quantity'      => esc_html__( 'Quantity', 'wishsuite' ),
            'add_to_cart'   => esc_html__( 'Add To Cart', 'wishsuite' ),
        );

        $fields_settings = woolentor_get_option( 'show_fields', 'wishsuite_table_settings_tabs' );

        if ( isset( $fields_settings ) && ( is_array( $fields_settings ) ) && count( $fields_settings ) > 1 ) {
            $fields = $fields_settings;
        }else{
            $fields = $default_show;
        }

        return $fields;
    }

    /**
     * [is_products_have_field]
     * @param  [string]  $field_id 
     * @param  [object]  $products
     * @return boolean   
     */
    public function is_products_have_field( $field_id, $products ) {
        foreach ( $products as $product_id => $product ) {
            if ( isset( $product[ $field_id ] ) && ( ! empty( $product[ $field_id ] ) && '-' !== $product[ $field_id ] && 'N/A' !== $product[ $field_id ] ) ) {
                return true;
            }
        }
        return false;
    }

    /**
     * [display_field]
     * @param  [string] $field_id
     * @param  [array] $product
     * @return [html] 
     */
    public function display_field( $field_id, $product ) {

        $type = $field_id;

        if ( 'pa_' === substr( $field_id, 0, 3 ) ) {
            $type = 'attribute';
        }
        
        switch ( $type ) {
            case 'remove':
                ?>
                    <a href="#" class="wishsuite-remove" data-product_id="<?php echo esc_attr( $product['id'] ); ?>">&nbsp;</a>
                <?php
                break;

            case 'image':
                ?>
                    <a href="<?php echo get_permalink( $product['id'] ); ?>"> <?php echo $product['image']; ?> </a>
                <?php
                break;

            case 'title':
                echo '<a href="'.get_permalink( $product['id'] ).'">'.$product[ $field_id ].'</a>';
                break;

            case 'price':
                echo wp_kses_post( $product[ $field_id ] );
                break;

            case 'quantity':
                echo $product[ $field_id ];
                break;

            case 'ratting':
                echo '<span class="wishsuite-product-ratting">'.wp_kses_post( $product[ $field_id ] ).'</span>';
                break;

            case 'add_to_cart':
                echo apply_filters( 'wishsuite_add_to_cart_btn', $product[ $field_id ] );
                break;

            case 'attribute':
                echo wp_kses_post( $product[ $field_id ] );
                break;

            case 'weight':
                if ( $product[ $field_id ] ) {
                    $unit = $product[ $field_id ] !== '-' ? get_option( 'woocommerce_weight_unit' ) : '';
                    echo wc_format_localized_decimal( $product[ $field_id ] ) . ' ' . esc_attr( $unit );
                } 
                break;

            case 'description':
                echo apply_filters( 'woocommerce_short_description', $product[ $field_id ] );
                break;

            default:
                echo wp_kses_post( $product[ $field_id ] );
                break;
        }


    }

    /**
     * [field_name]
     * @param  [string] $field
     * @return [string] 
     */
    public function field_name( $field, $custom = false ){

        if( empty( $field ) ){
            return;
        }

        if( $custom === true ){
            return $field;
        }

        $default = wishsuite_get_default_fields();

        $str = substr( $field, 0, 3 );
        if( 'pa_' === $str ){
            $field_name = wc_attribute_label( $field );
        }else{
            $field_name = $default[$field];
        }
        return $field_name;

    }

    /**
     * [add_to_cart_html]
     * @param [object] $product
     */
    public function add_to_cart_html( $product, $quentity ) {
        if ( ! $product ) return;

        $btn_class = 'wishsuite-addtocart button product_type_' . $product->get_type();

        $btn_class .= $product->is_purchasable() && $product->is_in_stock() ? ' add_to_cart_button' : '';

        $btn_class .= $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? ' ajax_add_to_cart' : '';

        $cart_btn = $product->add_to_cart_text();

        ob_start();

        if( 'variable' === $product->get_type() ):
        ?>
            <div class="wishsuite-quick-cart-area">
                <div class="wishsuite-quick-cart-close">
                    <span>&#10005;</span>
                </div>
                <div class="wishsuite-quick-cart-form"></div>
            </div>
        <?php endif; ?>
            <a href="<?php echo $product->add_to_cart_url(); ?>" data-quantity="<?php echo esc_attr( $quentity ); ?>" class="<?php echo $btn_class; ?>" data-product_id="<?php echo $product->get_id(); ?>"><?php echo __( $cart_btn, 'wishsuite' );?></a>
        <?php
        return ob_get_clean();

    }

    /**
     * [availability_html]
     * @param  [object] $product
     * @return [html]
     */
    public function availability_html( $product ) {
        $html         = '';
        $availability = $product->get_availability();

        if( empty( $availability['availability'] ) ) {
            $availability['availability'] = __( 'In stock', 'woocommerce' );
        }

        if ( ! empty( $availability['availability'] ) ) {
            ob_start();

            wc_get_template( 'single-product/stock.php', array(
                'product'      => $product,
                'class'        => $availability['class'],
                'availability' => $availability['availability'],
            ) );

            $html = ob_get_clean();
        }

        return apply_filters( 'woocommerce_get_stock_html', $html, $product );
    }

    /**
     * [social_media_share]
     * @return [void]
     */
    public function social_share(){

        if( woolentor_get_option( 'enable_social_share','wishsuite_table_settings_tabs','on' ) !== 'on' ){
            return;
        }

        $ids = $this->get_wishlist_products();

        $atts = [
            'products_ids' => $ids,
        ];
        $social_share_attr = apply_filters( 'wishsuite_social_share_arg', $atts );
        wishsuite_get_template( 'wishsuite-social-share.php', $social_share_attr, true );
        
    }


}