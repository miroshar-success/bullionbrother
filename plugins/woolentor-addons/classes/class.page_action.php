<?php
/**
* WooLentor_Page_Action
*/
class WooLentor_Page_Action{

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
     * @return [WooLentor_Page_Action]
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init(){
        $this->checkout_page();
    }

    /*
    * Manage Checkout page action
    */
    public function checkout_page(){
        $template_page_id = Woolentor_Template_Manager::instance()->get_template_id( 'productcheckoutpage' );
        if( empty( $template_page_id ) || !is_plugin_active('woolentor-addons-pro/woolentor_addons_pro.php') ){
            return;
        }

        add_action( 'woocommerce_cart_item_name', [ $this, 'add_product_thumbnail' ], 10, 3 );
        add_action( 'woocommerce_cart_item_class', [ $this, 'add_css_class_in_product_tr' ], 10, 3 );

        add_action( 'woocommerce_checkout_cart_item_quantity', '__return_null', 10, 3 );

    }

    /*
    * Table Row CSS class add in checkout page order overview table row
    */
    public function add_css_class_in_product_tr( $css_class , $cart_item, $cart_item_key ){
        if ( ! is_checkout() ) return $css_class;
        return  $css_class.' woolentor-order-review-product';
    }

    /*
    * Add Product image to checkout page order overview table
    */
    public function add_product_thumbnail( $product_name, $cart_item, $cart_item_key ){
        if ( ! is_checkout() ) return $product_name;

        $_product = $cart_item['data'];

        //$remove_icon = sprintf( '<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>', esc_url( wc_get_cart_remove_url( $cart_item_key ) ), __( 'Delete', 'woolentor' ), esc_attr( $cart_item['product_id'] ), esc_attr( $_product->get_sku() ));
        
        $thumbnail  =  sprintf('<span class="product-thumbnail">%s</span>', $_product->get_image('thumbnail') );
        $title      = sprintf('<span class="product-title">%s <strong class="product-quantity">&times;&nbsp;%s</strong>%s</span>', $product_name, $cart_item['quantity'], wc_get_formatted_cart_item_data( $cart_item ) );

        return sprintf( '<span class="woolentor-order-item-title">%s %s</span>', $thumbnail, $title );

    }


}