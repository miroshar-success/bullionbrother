<?php
namespace EverCompare\Frontend;
/**
 * Button handlers class
 */
class Manage_Compare {

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [$reached_max_limit]
     * @var boolean
     */
    public $reached_max_limit = false;

    /**
     * [instance] Initializes a singleton instance
     * @return [Compare_Button]
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
        add_action( 'ever_compare_before_table', [ $this, 'reached_max_limit_message' ], 1 );
        add_action( 'ever_compare_after_table', [ $this, 'shareable_link' ], 1 );
    }

    /**
     * [compare_button]
     * @return [void]
     */
    public function button_print(){
        echo do_shortcode( '[evercompare_button]' );
    }

    /**
     * [button_manager] Button Manager
     * @return [void]
     */
    public function button_manager(){

        $enable_btn         = woolentor_get_option( 'btn_show_shoppage', 'ever_compare_settings_tabs', 'off' );
        $product_enable_btn = woolentor_get_option( 'btn_show_productpage', 'ever_compare_settings_tabs', 'on' );

        $shop_page_btn_position     = woolentor_get_option( 'shop_btn_position', 'ever_compare_settings_tabs', 'after_cart_btn' );
        $product_page_btn_position  = woolentor_get_option( 'product_btn_position', 'ever_compare_settings_tabs', 'after_cart_btn' );


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
                    $hook_name = woolentor_get_option( 'shop_custom_hook_name', 'ever_compare_settings_tabs', '' );
                    $priority = woolentor_get_option( 'shop_custom_hook_priority', 'ever_compare_settings_tabs', 10 );
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
                    $hook_name = woolentor_get_option( 'product_custom_hook_name', 'ever_compare_settings_tabs', '' );
                    $priority = woolentor_get_option( 'product_custom_hook_priority', 'ever_compare_settings_tabs', 10 );
                    if( !empty( $hook_name ) ){
                        add_action( $hook_name, [ $this, 'button_print' ], $priority );
                    }
                    break;
                
                default:
                    add_action( 'woocommerce_single_product_summary', [ $this, 'button_print' ], 31 );
                    break;
            }
        }

        /**
         * Popup HTML Render
         */
        if( woolentor_get_option( 'open_popup', 'ever_compare_settings_tabs', 'on' ) === 'on' ){
            add_action( 'wp_footer', [ $this, 'pop_up_html' ] );
        }

        /**
         * Manage maximum product added message
         */
        if( isset( $_COOKIE[ 'ever_compare_max_limit' ] ) && $_COOKIE[ 'ever_compare_max_limit' ] == 'yes' ) {
            setcookie( 'ever_compare_max_limit', 'no', 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
            $this->reached_max_limit = true;
        }


    }

    /**
     * [button_html] Button HTML
     * @param  [type] $atts
     * @return [HTML]
     */
    public function button_html( $atts ) {
        $button_attr = apply_filters( 'evercompare_button_arg', $atts );
        return ever_compare_get_template( 'evercompare-button-'.$atts['template_name'].'.php', $button_attr, false );
    }

    /**
     * [table_html] Wishlist table HTML
     * @return [HTML]
     */
    public function table_html( $atts ) {
        $table_attr = apply_filters( 'evercompare_table_arg', $atts );
        return ever_compare_get_template( 'evercompare-table.php', $table_attr, false );
    }

    /**
     * [pop_up_html]
     * @return [void]
     */
    public function pop_up_html(){
        echo '<div class="htcompare-popup"><div class="htcompare-popup-content-area"><span class="htcompare-popup-close">&nbsp;</span>'.do_shortcode( '[evercompare_table]' ).'</div></div>';
    }

    /**
     * [reached_max_limit_message]
     * @return [void]
     */
    public function reached_max_limit_message(){
        if( ! $this->reached_max_limit ) {
            return;
        }

        $message = !empty( woolentor_get_option('reached_max_limit_message','ever_compare_table_settings_tabs') ) ? woolentor_get_option('reached_max_limit_message','ever_compare_table_settings_tabs') : esc_html__( 'You are already added the maximum number of products.', 'ever-compare' );
        echo '<div class="ever-compare-message-error"><p>'. wp_kses_post( $message ).'</p></div>';

    }

    /**
     * [shareable_link]
     * @return [void]
     */
    public function shareable_link(){
        
        $shareable_link = woolentor_get_option( 'enable_shareable_link','ever_compare_table_settings_tabs','off' );
        if ( $shareable_link !== 'on' ) {
            return;
        }

        $ids = $this->get_compared_products();

         if ( isset( $_GET['evcompare'] ) ) {
            $query_perametter_ids = sanitize_text_field( $_GET['evcompare'] );
            if( !empty( $query_perametter_ids ) ){
                $ids = explode( ',', $query_perametter_ids );
            }
        }

        if ( empty($ids) ) {
            return;
        }

        $buttonText = woolentor_get_option( 'shareable_link_button_text','ever_compare_table_settings_tabs','Copy shareable link' ) ? woolentor_get_option( 'shareable_link_button_text','ever_compare_table_settings_tabs','Copy shareable link' ) : esc_html__( 'Copy shareable link', 'ever-compare' );
        $aftercopy_buttonText = woolentor_get_option( 'shareable_link_after_button_text','ever_compare_table_settings_tabs','Copied' ) ? woolentor_get_option( 'shareable_link_after_button_text','ever_compare_table_settings_tabs','Copied' ) : esc_html__( 'Copied', 'ever-compare' );
        $button_pos = woolentor_get_option( 'linkshare_btn_pos','ever_compare_table_settings_tabs','right' );

        $idsString = is_array( $ids ) ? implode( ',', $ids ) : '';
        $shareablelink = $this->get_compare_page_url() . '?evcompare='.$idsString;
        ?>
            <div class="ever-compare-shareable-link <?php echo esc_attr( $button_pos );?>">
                <button class="evercompare-copy-link" data-copytext="<?php echo esc_attr( $aftercopy_buttonText ); ?>" data-btntext="<?php echo esc_attr( $buttonText ); ?>"><?php echo $buttonText; ?></button>
                <p style="display: none;" class="evercompare-share-link"><?php echo $shareablelink; ?></p>
            </div>
        <?php

    }

    /**
     * [add_to_compare] Ajax callable function
     */
    public function add_to_compare( $id ) {

        $cookie_name = $this->get_cookie_name();

        if ( $this->is_product_in_compare( $id ) ) {
            $this->compare_json_response();
        }

        $products = $this->get_compared_products();

        // Reached Maximum Limit
        if( $this->reached_max_limit() ) {
            setcookie( 'ever_compare_max_limit', 'yes', 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
            $this->compare_json_response();
        }

        $products[] = $id;

        setcookie( $cookie_name, json_encode( $products ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );

        $_COOKIE[$cookie_name] = json_encode( $products );

        $this->compare_json_response();

    }

    /**
     * [reached_max_limit]
     * @return [bool]
     */
    public function reached_max_limit(){

        $products = $this->get_compared_products();
        $max_limit = intval( woolentor_get_option( 'limit','ever_compare_table_settings_tabs', 10 ) );

        if( $max_limit && count( $products ) >= $max_limit ) {
            $this->reached_max_limit = true;
        } else {
            $this->reached_max_limit = false;
        }

        return $this->reached_max_limit;
    }

    /**
     * [remove_from_compare] Ajax callable function
     * @return [json]
     */
    public function remove_from_compare( $id ) {

        $cookie_name = $this->get_cookie_name();

        if ( ! $this->is_product_in_compare( $id ) ) {
            $this->compare_json_response();
        }

        $products = $this->get_compared_products();

        foreach ( $products as $prod_key => $product_id ) {
            if ( intval( $id ) == $product_id ) {
                unset( $products[ $prod_key ] );
                $this->reached_max_limit = false;
            }
        }

        if ( empty( $products ) ) {
            setcookie( $cookie_name, false, 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
            $_COOKIE[$cookie_name] = false;
        } else {
            setcookie( $cookie_name, json_encode( $products ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
            $_COOKIE[$cookie_name] = json_encode( $products );
        }

        $this->compare_json_response();
    }

    /**
     * [get_response_html ] Compare product table HTML
     * @return [html]
     */
    public function get_response_html(){

        $products = $this->get_compared_products_data();
        $fields = $this->get_compare_fields();

        $empty_compare_text = woolentor_get_option('empty_table_text','ever_compare_table_settings_tabs');
        $return_shop_button = woolentor_get_option('shop_button_text','ever_compare_table_settings_tabs','Return to shop');

        $custom_heading = !empty( woolentor_get_option( 'table_heading', 'ever_compare_table_settings_tabs' ) ) ? woolentor_get_option( 'table_heading', 'ever_compare_table_settings_tabs' ) : array();

        $default_atts = array(
            'evercompare'  => self::instance(),
            'products'     => $products,
            'fields'       => $fields,
            'heading_txt'         => $custom_heading,
            'return_shop_button'  => $return_shop_button,
            'empty_compare_text'  => $empty_compare_text,
        );
        $table_attr = apply_filters( 'evercompare_response_html_args', $default_atts );

        ever_compare_get_template( 'evercompare-table.php', $table_attr );

    }

    /**
     * [get_compared_products_data] generate compared products data
     * @return [array] product list
     */
    public function get_compared_products_data() {
        $ids = $this->get_compared_products();

        // For shareable link
        $shareable_link = woolentor_get_option('enable_shareable_link','ever_compare_table_settings_tabs','off');
        if ( ( $shareable_link === 'on' ) && isset( $_GET['evcompare'] ) ) {
            $query_perametter_ids = sanitize_text_field( $_GET['evcompare'] );
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

        $fields = $this->get_compare_fields();

        $fields = array_filter( $fields, function(  $field ) {
            return 'pa_' === substr( $field, 0, 3 );
        }, ARRAY_FILTER_USE_KEY );

        $data_none = '-';

        foreach ( $products as $product ) {

            $rating_count   = $product->get_rating_count();
            $average        = $product->get_average_rating();

            $products_data[ $product->get_id() ] = array(
                'primary' => array(
                    'image'     => $product->get_image() ? $product->get_image('ever-compare-image') : $data_none,
                ),
                'id'            => $product->get_id(),
                'title'         => $product->get_title() ? $product->get_title() : $data_none,
                'image_id'      => $product->get_image_id(),
                'permalink'     => $product->get_permalink(),
                'price'         => $product->get_price_html() ? $product->get_price_html() : $data_none,
                'rating'        => wc_get_rating_html( $average, $rating_count ),
                'add_to_cart'   => $this->add_to_cart_html( $product ) ? $this->add_to_cart_html( $product ) :$data_none,
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
     * [get_compare_fields] Table field list
     * @return [array] Table Field list
     */
    public function get_compare_fields() {
        $fields = array(
            'primary' => ''
        );

        $default_show = array(
            'title'         => esc_html__( 'title', 'ever-compare' ),
            'ratting'       => esc_html__( 'ratting', 'ever-compare' ),
            'price'         => esc_html__( 'price', 'ever-compare' ),
            'add_to_cart'   => esc_html__( 'add_to_cart', 'ever-compare' ),
            'description'   => esc_html__( 'description', 'ever-compare' ),
            'availability'  => esc_html__( 'availability', 'ever-compare' ),
            'sku'           => esc_html__( 'sku', 'ever-compare' ),
            'weight'        => esc_html__( 'weight', 'ever-compare' ),
            'dimensions'    => esc_html__( 'dimensions', 'ever-compare' ),
        );

        $fields_settings = !empty( woolentor_get_option( 'show_fields', 'ever_compare_table_settings_tabs' ) ) ? woolentor_get_option( 'show_fields', 'ever_compare_table_settings_tabs' ) : array();

        if ( isset( $fields_settings ) && count( $fields_settings ) > 1 ) {
            $fields = $fields + $fields_settings;
        }else{
            $fields = $fields + $default_show;
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
     * [compare_display_field]
     * @param  [string] $field_id
     * @param  [array] $product
     * @return [html] 
     */
    public function compare_display_field( $field_id, $product ) {

        $type = $field_id;

        if ( 'pa_' === substr( $field_id, 0, 3 ) ) {
            $type = 'attribute';
        }
        
        switch ( $type ) {
            case 'primary':
                ?>
                    <div class="htcompare-primary-content-area">
                        <a href="#" class="htcompare-remove" data-product_id="<?php echo esc_attr( $product['id'] ); ?>">&nbsp;</a>
                        <a href="<?php echo get_permalink( $product['id'] ); ?>" class="htcompare-product-image">
                            <?php echo $product['primary']['image']; ?>
                        </a>
                    </div>
                <?php
                break;

            case 'title':
                echo '<a href="'.get_permalink( $product['id'] ).'" class="htcompare-product-title">'.$product[ $field_id ].'</a>';
                break;

            case 'ratting':
                echo '<span class="htcompare-product-ratting">'.wp_kses_post( $product[ $field_id ] ).'</span>';
                break;

            case 'price':
                echo '<div class="htcompare-product-price">'.wp_kses_post( $product[ $field_id ] ).'</div>';
                break;

            case 'add_to_cart':
                echo apply_filters( 'htcompare_add_to_cart_btn', $product[ $field_id ] );
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
     * [add_to_cart_html] Generate Cart button
     * @param [object] $product
     */
    public function add_to_cart_html( $product ) {
        if ( ! $product ) return;

        $defaults = array(
            'quantity'   => 1,
            'class'      => implode( ' ', array_filter( array(
                'htcompare-cart-button button',
                'product_type_' . $product->get_type(),
                $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
            ) ) ),
            'attributes' => array(
                'data-product_id'  => $product->get_id(),
                'data-product_sku' => $product->get_sku(),
                'aria-label'       => $product->add_to_cart_description(),
                'rel'              => 'nofollow',
            ),
        );

        $args = apply_filters( 'woocommerce_loop_add_to_cart_args', $defaults, $product );

        if ( isset( $args['attributes']['aria-label'] ) ) {
            $args['attributes']['aria-label'] = strip_tags( $args['attributes']['aria-label'] );
        }

        return apply_filters( 'woocommerce_loop_add_to_cart_link', 
            sprintf( '<a href="%s" data-quantity="%s" class="%s add-to-cart-loop" %s><span>%s</span></a>',
                esc_url( $product->add_to_cart_url() ),
                esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
                isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
                esc_html( $product->add_to_cart_text() )
            ),
        $product, $args );
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
     * [compare_cookie_name] Get Compare cookie name
     * @return [string] 
     */
    public function get_cookie_name() {
        $name = 'ever_compare_compare_list';
        if ( is_multisite() ){
            $name .= '_' . get_current_blog_id();
        }
        return $name;
    }

    /**
     * [is_product_in_compare]
     * @param  [int] $id product id
     * @return [array] product id list
     */
    public function is_product_in_compare( $id ) {
        $list = $this->get_compared_products();
        return in_array( $id, $list, true );
    }

    /**
     * [get_compared_products]
     * @return [json]
     */
    public function get_compared_products() {
        $cookie_name = $this->get_cookie_name();
        return isset( $_COOKIE[ $cookie_name ] ) ? json_decode( wp_unslash( $_COOKIE[ $cookie_name ] ), true ) : array();
    }

    /**
     * [compare_json_response]
     * @return [json] product json
     */
    public function compare_json_response() {
        $count = 0;
        $products = $this->get_compared_products();

        ob_start();

        $this->get_response_html();

        $table_html = ob_get_clean();

        if ( is_array( $products ) ) {
            $count = count( $products );
        }

        wp_send_json( array(
            'count' => $count,
            'table' => $table_html,
        ) );

    }

    /**
     * [get_compare_page_url] Compare Page Link
     * @return [URL]
     */
    public function get_compare_page_url() {
        $page_id = woolentor_get_option( 'compare_page', 'ever_compare_table_settings_tabs' );
        return get_permalink( $page_id );
    }

    /**
     * [field_name]
     * @param  [string] $field
     * @return [string] 
     */
    public function field_name( $field = '', $custom = false ){

        if( empty( $field ) ){
            return;
        }

        if( $custom === true ){
            return $field;
        }

        $default = ever_compare_get_default_fields();

        $str = substr( $field, 0, 3 );
        if( 'pa_' === $str ){
            $field_name = wc_attribute_label( $field );
        }else{
            $field_name = $default[$field];
        }
        return $field_name;

    }


}