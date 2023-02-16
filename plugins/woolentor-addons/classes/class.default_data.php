<?php
/**
* WooLentor_Default_Data
*/
class WooLentor_Default_Data{

    /**
     * [$instance]
     * @var null
     */
    private static $instance   = null;

    /**
     * [$product_id]
     * @var null
     */
    private static $product_id = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [Assets_Management]
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * [__construct] Class Constructor
     */
    function __construct(){
        add_action( 'init', [ $this, 'init'] );
        add_action('elementor/element/before_section_start', [$this, 'theme_hook_reactive'], 10, 2);
    }

    /**
     * [init] Initialize Function
     * @return [void]
     */
    public function init(){
        add_filter( 'body_class', [ $this, 'body_class' ] );
        add_filter( 'post_class', [ $this, 'post_class' ] );
    }

    // public function wc_fontend_includes() {
    //     if( defined( 'WC_ABSPATH' ) ){
    //         include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
    //         include_once WC_ABSPATH . 'includes/wc-notice-functions.php';
    //         include_once WC_ABSPATH . 'includes/wc-template-hooks.php';
    //         include_once WC_ABSPATH . 'includes/class-wc-template-loader.php';
    //         include_once WC_ABSPATH . 'includes/class-wc-frontend-scripts.php';
    //         include_once WC_ABSPATH . 'includes/class-wc-form-handler.php';
    //         include_once WC_ABSPATH . 'includes/class-wc-cart.php';
    //         include_once WC_ABSPATH . 'includes/class-wc-tax.php';
    //         include_once WC_ABSPATH . 'includes/class-wc-shipping-zones.php';
    //         include_once WC_ABSPATH . 'includes/class-wc-customer.php';
    //         include_once WC_ABSPATH . 'includes/class-wc-embed.php';
    //         include_once WC_ABSPATH . 'includes/class-wc-session-handler.php';
    //     }
    // }

    /**
     * [body_class] Body Classes
     * @param  [array] $classes body class list
     * @return [void] 
     */
    public function body_class( $classes ){
        $post_type = get_post_type();
        if( $post_type == 'elementor_library' || $post_type == 'woolentor-template' ){

            parse_str( $_SERVER['QUERY_STRING'], $query_str_arr );
            if( !empty( $query_str_arr['elementor-preview'] ) ){

                $tmpType  = ( get_post_type() === 'woolentor-template' ) ? get_post_meta( $query_str_arr['elementor-preview'], 'woolentor_template_meta_type', true ) : '';

                if( !empty( $tmpType ) ){
                    if( 'cart' === $tmpType || 'emptycart' === $tmpType ){
                        $classes[] = 'woocommerce-cart';
                    } elseif( 'checkout' === $tmpType || 'checkouttop' === $tmpType ){
                        $classes[] = 'woocommerce-checkout';
                    } elseif( 'single' === $tmpType || 'quickview' === $tmpType ){
                        $classes[] = 'single-product';
                    }
                }else{
                    $cart_page_id      = Woolentor_Template_Manager::instance()->get_template_id( 'productcartpage' );
                    $checkout_page_id  = Woolentor_Template_Manager::instance()->get_template_id( 'productcheckoutpage' );

                    if(  $query_str_arr['elementor-preview'] == $cart_page_id ){
                        $classes[] = 'woocommerce-cart';
                    } elseif( $query_str_arr['elementor-preview'] == $checkout_page_id ){
                        $classes[] = 'woocommerce-checkout';
                    }
                    $classes[] = 'single-product';

                }

            }

            $classes[] = 'woocommerce';
            $classes[] = 'woocommerce-page';
            $classes[] = 'woolentor-woocommerce-builder';
        }
        return $classes;
    }

    /**
     * [post_class] Post Classes
     * @param  [array] $classes post class list
     * @return [void]
     */
    public function post_class( $classes ){
        $post_type = get_post_type();
        if( $post_type == 'elementor_library' || $post_type == 'woolentor-template' ){
            $classes[] = 'product';
        }
        return $classes;
    }

    /**
     * [get_product] get product
     * @param  [string] $post_type post type
     * @return [object]
     */
    public function get_product( $post_type ) {

		global $product;

		if( 'product' == $post_type ) {
			return $product;
		}

		$product = wc_get_product( woolentor_get_last_product_id() );

		return empty( $product ) ? null : $product;

	}

    /**
     * [theme_hook_reactive]
     * @param  [object] $element
     * @param [int] $section_id
     */
    public function theme_hook_reactive( $element, $section_id ){
        $is_editor_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        if( 'woolentor-product-archive-addons' === $element->get_name() && $is_editor_mode) {
            $this->theme_hooks( $element->get_name() );
        }else if( 'wl-single-product-upsell' === $element->get_name() && $is_editor_mode ){
            $this->theme_hooks( $element->get_name() );
        }else if( 'wl-product-related' === $element->get_name() && $is_editor_mode ){
            $this->theme_hooks( $element->get_name() );
        }else if( 'wl-cross-sell' === $element->get_name() && $is_editor_mode ){
            $this->theme_hooks( $element->get_name() );
        }
    }

    /**
     * [theme_hooks]
     * @return [void]
     */
    public function theme_hooks( $name = '' ){

        $current_theme = wp_get_theme();

        // For Astra Theme
        if( 'astra' === $current_theme->get( 'TextDomain' ) || 'hello-elementor' === $current_theme->get( 'TextDomain' ) || 'blocksy' === $current_theme->get( 'TextDomain' ) ){

            if( $name === 'woolentor-product-archive-addons' ){

                if( has_action('woocommerce_before_shop_loop', 'woocommerce_result_count') === false ) {
                    add_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 10);
                }
                if( has_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering') === false ) {
                    add_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
                }
                if( has_action('woocommerce_after_shop_loop', 'woocommerce_pagination') === false ) {
                    add_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
                }
            
                if( has_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper') === false ) {
                    add_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
                }
                if( has_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end') === false ) {
                    add_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
                }

                if( has_action('woocommerce_sidebar', 'woocommerce_get_sidebar') === false ) {
                    add_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
                }

            }
            
            if( has_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash') === false ) {
                add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
            }
            if( has_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail') === false ) {
                add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
            }
            if( has_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title') === false ) {
                add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
            }
            if( has_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price') === false ) {
                add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
            }

            if( has_action('woocommerce_before_shop_loop_item', 'astra_woo_shop_thumbnail_wrap_start') !== false ) {
                remove_action('woocommerce_before_shop_loop_item', 'astra_woo_shop_thumbnail_wrap_start', 6);
            }
            if( has_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_thumbnail') !== false ) {
                remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_thumbnail', 9);
            }
            if( has_action('woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash') !== false ) {
                remove_action('woocommerce_before_shop_loop_item', 'woocommerce_show_product_loop_sale_flash', 9);
            }
            if( has_action('woocommerce_after_shop_loop_item', 'astra_woo_shop_thumbnail_wrap_end') !== false ) {
                remove_action('woocommerce_after_shop_loop_item', 'astra_woo_shop_thumbnail_wrap_end', 8);
            }
            if( has_action('woocommerce_shop_loop_item_title', 'astra_woo_shop_out_of_stock') !== false ) {
                remove_action('woocommerce_shop_loop_item_title', 'astra_woo_shop_out_of_stock', 8);
            }
            if( has_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart') === false ) {
                add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
            }
            if( has_action('woocommerce_after_shop_loop_item', 'astra_woo_woocommerce_shop_product_content') !== false ) {
                remove_action('woocommerce_after_shop_loop_item', 'astra_woo_woocommerce_shop_product_content');
            }

        }

    }

    /**
     * [default] Show Default data in Elementor Editor Mode
     * @param  string $addons   Addon Name
     * @param  array  $settings Addon Settings
     * @return [html] 
     */
    public function default( $addons = '', $settings = array() ){

        global $post, $product;
        if( get_post_type() == 'product' ){
            self::$product_id = $product->get_id();
        }else{
            if( function_exists('woolentor_get_last_product_id') ){
                self::$product_id = woolentor_get_last_product_id();
                $product = $this->get_product('');
            }
        }

        if( $product ){
            switch ( $addons ){

                case 'wl-product-add-to-cart':
                    ob_start();
                        do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' );
                    return ob_get_clean();
                    break;

                case 'wl-single-product-price':
                    ob_start();
                    if( !empty( $product->get_price_html() ) ){
                        ?><p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $product->get_price_html(); ?></p><?php
                    }else{
                        echo '<p>'.esc_html__('Price does not set this product.','woolentor').'</p>';
                    }
                    return ob_get_clean();
                    break;

                case 'wl-single-product-short-description':
                    ob_start();
                    $short_description = get_the_excerpt( self::$product_id );
                    $short_description = apply_filters( 'woocommerce_short_description', $short_description );
                    if ( empty( $short_description ) ) { 
                        echo '<p>'.esc_html__('The short description does not set this product.','woolentor').'</p>'; 
                    }else{
                        ?>
                            <div class="woocommerce-product-details__short-description"><?php echo wp_kses_post( $short_description ); ?></div>
                        <?php
                    }
                    return ob_get_clean();
                    break;

                case 'wl-single-product-description':
                    ob_start();
                    $description = get_post_field( 'post_content', self::$product_id );
                    if ( empty( $description ) ) { 
                        echo '<p>'.esc_html__('The description does not set this product.','woolentor').'</p>'; 
                    }else{
                        echo wp_kses_post( $description );
                    }
                    return ob_get_clean();
                    break;

                case 'wl-single-product-rating':
                    ob_start();
                    if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
                        echo '<div class="wl-nodata">'.__('Rating does not enable.','woolentor').'</div>';
                    }
                    $rating_count = $product->get_rating_count();
                    $review_count = $product->get_review_count();
                    $average      = $product->get_average_rating();

                    if ( $rating_count > 0 ) : ?>
                        <div class="product">
                            <div class="woocommerce-product-rating">
                                <?php echo wc_get_rating_html( $average, $rating_count ); // WPCS: XSS ok. ?>
                                <?php if ( comments_open( self::$product_id ) ) : ?>
                                    <?php //phpcs:disable ?>
                                    <a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf( _n( '%s customer review', '%s customer reviews', $review_count, 'woolentor' ), '<span class="count">' . esc_html( $review_count ) . '</span>' ); ?>)</a>
                                    <?php // phpcs:enable ?>
                                <?php endif ?>
                            </div>
                        </div>
                    <?php else:?>
                        <?php echo '<div class="wl-nodata">'.__('No Rating Available','woolentor').'</div>';?>
                    <?php endif;
                    return ob_get_clean();
                    break;

                case 'wl-single-product-image':
                    ob_start();

                    $columns = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
                    $thumbnail_id = $product->get_image_id();
                    $wrapper_classes = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
                        'woocommerce-product-gallery',
                        'woocommerce-product-gallery--' . ( $product->get_image_id() ? 'with-images' : 'without-images' ),
                        'woocommerce-product-gallery--columns-' . absint( $columns ),
                        'images',
                    ) );

                    if ( function_exists( 'wc_get_gallery_image_html' ) ) {
                        ?>
                        <div class="product">
                            <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="transition: opacity .25s ease-in-out;float: none;width: 100%;">
                                <figure class="woocommerce-product-gallery__wrapper">
                                    <?php
                                        if ( $product->get_image_id() ) {
                                            $html = wc_get_gallery_image_html( $thumbnail_id, true );
                                        } else {
                                            $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
                                            $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woolentor' ) );
                                            $html .= '</div>';
                                        }

                                        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

                                        $attachment_ids = $product->get_gallery_image_ids();
                                        if ( $attachment_ids && $product->get_image_id() ) {
                                            foreach ( $attachment_ids as $attachment_id ) {
                                                echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id ), $attachment_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
                                            }
                                        }
                                    ?>
                                </figure>
                            </div>
                        </div>
                        <?php
                    }
                    return ob_get_clean();
                    break;

                case 'wl-single-product-meta':
                    ob_start();
                    ?>
                        <div class="product">
                            <div class="product_meta">

                                <?php do_action( 'woocommerce_product_meta_start' ); ?>

                                <?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

                                    <span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'woolentor' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woolentor' ); ?></span></span>

                                <?php endif; ?>

                                <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woolentor' ) . ' ', '</span>' ); ?>

                                <?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woolentor' ) . ' ', '</span>' ); ?>

                                <?php do_action( 'woocommerce_product_meta_end' ); ?>

                            </div>
                        </div>
                    <?php
                    return ob_get_clean();
                    break;

                case 'wl-single-product-sku':
                    ob_start();

                    if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
                        <div class="woolentor_product_sku_info">
                            <span class="sku-title"><?php esc_html_e('SKU:', 'woolentor'); ?></span>
                            <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woolentor' ); ?></span>
                        </div>
                    <?php endif;

                    return ob_get_clean();
                    break;

                case 'wl-single-product-tags':
                    ob_start();

                    if( has_term( '', 'product_tag', $product->get_id() ) ) {
                        echo '<div class="woolentor_product_tags_info">';
                            ?>
                                <span class="tags-title"><?php echo sprintf( _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woolentor' ) ); ?></span>
                                <?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">', '</span>' ); ?>
                            <?php
                        echo '</div>';
                    }else{
                        echo esc_html__('Product tags does not exists.', 'woolentor');
                    }

                    return ob_get_clean();
                    break;

                case 'wl-single-product-categories':
                    ob_start();

                    if( has_term( '', 'product_cat', $product->get_id() ) ) {
                        echo '<div class="woolentor_product_categories_info">';
                            ?>
                                <span class="categories-title"><?php echo sprintf( _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woolentor' ) ); ?></span>
                                <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">', '</span>' ); ?>
                            <?php
                        echo '</div>';
                    }else{
                        echo esc_html__('Product category does not exists.', 'woolentor');
                    }

                    return ob_get_clean();
                    break;

                case 'wl-product-additional-information':
                    ob_start();
                    wc_get_template( 'single-product/tabs/additional-information.php' );
                    return ob_get_clean();
                    break;

                case 'wl-product-data-tabs':
                    $post = get_post( $product->get_id() );
                    setup_postdata( $product->get_id() );
                    if( get_post_type() == 'elementor_library' || get_post_type() == 'woolentor-template' ){
                        add_filter( 'the_content', [ $this, 'product_content' ] );
                    }
                    add_filter( 'woocommerce_product_tabs', function( $tabs ){
                        $tabs = woocommerce_default_product_tabs();
                        if( isset( $tabs['reviews'] ) ) {
                            $tabs['reviews']['callback'] = [ $this, 'product_review_tab' ];
                        }
                        return $tabs;
                    }, 9999 );
                    ob_start();
                    echo '<div class="product">';
                        wc_get_template( 'single-product/tabs/tabs.php' );
                    echo '</div>';
                    return ob_get_clean();
                    break;
                
                case 'wl-product-data-tabs2':
                    // setup_postdata( $product->get_id() );
                    $post = get_post( $product->get_id() );
                    ob_start();
                    if( get_post_type() == 'elementor_library' ){
                        add_filter( 'the_content', [ $this, 'product_content' ] );
                    }
                    wc_get_template( 'single-product/tabs/tabs.php' );
                    return ob_get_clean();
                    break;

                case 'wl-single-product-reviews':
                    ob_start();
                    if( comments_open( self::$product_id ) ){
                        comments_template();
                    }
                    return ob_get_clean();
                    break;

                case 'wl-single-product-stock':
                    ob_start();
                    $availability = $product->get_availability();

                    if( !empty( $availability['availability'] ) ){
                        echo '<div class="product"><p class="stock '.esc_attr( $availability['class'] ).'">'.wp_kses_post( $availability['availability'] ).'</p></div>';
                    }else{
                        echo '<p>'.esc_html__('Stock availability does not exist this product.','woolentor').'</p>';
                    }
                    return ob_get_clean();
                    break;

                case 'wl-single-product-upsell':
                    ob_start();

                    $product_per_page   = '-1';
                    $columns            = 4;
                    $orderby            = 'rand';
                    $order              = 'desc';
                    if ( ! empty( $settings['columns'] ) ) {
                        $columns = $settings['columns'];
                    }
                    if ( ! empty( $settings['orderby'] ) ) {
                        $orderby = $settings['orderby'];
                    }
                    if ( ! empty( $settings['order'] ) ) {
                        $order = $settings['order'];
                    }

                    if( $product->get_upsell_ids() ){
                        woocommerce_upsell_display( $product_per_page, $columns, $orderby, $order );
                    }else{
                        echo '<p>'.esc_html__('No upsell products are available.','woolentor').'</p>';
                    }

                    return ob_get_clean();
                    break;

                case 'wl-product-related':
                    ob_start();
                    $args = [
                        'posts_per_page' => 4,
                        'columns' => 4,
                        'orderby' => $settings['orderby'],
                        'order' => $settings['order'],
                    ];
                    if ( ! empty( $settings['posts_per_page'] ) ) {
                        $args['posts_per_page'] = $settings['posts_per_page'];
                    }
                    if ( ! empty( $settings['columns'] ) ) {
                        $args['columns'] = $settings['columns'];
                    }

                    $args['related_products'] = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), 
                        $args['posts_per_page'], $product->get_upsell_ids() ) ), 'wc_products_array_filter_visible' );

                    $args['related_products'] = wc_products_array_orderby( $args['related_products'], $args['orderby'], $args['order'] );

                    if( wc_get_related_products( $product->get_id(), $args['posts_per_page'], $product->get_upsell_ids() ) ){
                        wc_get_template( 'single-product/related.php', $args );
                    }else{
                        echo '<p>'.esc_html__('No related products are available.','woolentor').'</p>';
                    }

                    return ob_get_clean();
                    break;

                default: 
                    return '';
                    break;

            }
        }


    }

    /**
     * [product_content]
     * @param  [string] $content
     * @return [string] 
     */
    public function product_content( $content ){
        $product_content = get_post( self::$product_id );
        $content = $product_content->post_content;
        return $content;
    }

    /**
     * Product review tab empty content for elementor editor mode
     *
     * @return void
     */
    public function product_review_tab(){
        return null;
    }

}
WooLentor_Default_Data::instance();