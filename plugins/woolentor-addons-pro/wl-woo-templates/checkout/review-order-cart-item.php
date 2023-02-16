<?php
/*
* This template is used int the "WL: Checkout Order Review" addon to render the products in cart
*/
?>

<div class="woocommerce-mini-cart-item woolentor-product woolentor-product-<?php echo esc_attr($style) ?>" data-cart_item_key="<?php echo esc_attr($cart_item_key) ?>">
    <div class="woolentor-product-thumb">
        <?php
        $product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

        echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            'woocommerce_cart_item_remove_link',
            sprintf(
                '<a href="%s" class="remove_from_cart_button woolentor-product-remove" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">x</a>',
                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                esc_attr__( 'Remove this item', 'woolentor-pro' ),
                esc_attr( $product_id ),
                esc_attr( $cart_item_key ),
                esc_attr( $_product->get_sku() )
            ),
            $cart_item_key
        );
        ?>
        <?php echo wp_kses_post($_product->get_image()); ?>
    </div>
    <div class="woolentor-product-content">
        <div class="woolentor-product-content-top">
            <a href="<?php echo esc_url($product_permalink) ?>"><h5 class="woolentor-product-title"><?php echo wp_kses_post($_product->get_name()) ?></h5></a>
            <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>

        <div class="woolentor-product-content-bottom">
            <div class="woolentor-product-quantity">
                <span class="woolentor-product-quantity-label"><?php echo !empty($settings['qty_text']) ? esc_html($settings['qty_text']) : esc_html__( 'QTY:', 'woolentor-pro' ); ?></span>
                <?php
                    if ( $_product->is_sold_individually() ) {
                        printf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                    } else {
                        echo woocommerce_quantity_input( array(
                            'input_name'  => "cart[{$cart_item_key}][qty]",
                            'input_value' => $cart_item['quantity'],
                            'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                            'min_value'   => '1'
                        ), $_product, false );
                    }
                ?>
            </div>
            <div class="woolentor-product-price">
                <span class="woolentor-product-price-label"><?php echo esc_html__('Price:', 'woolentor-pro') ?></span>
                <span class="woolentor-product-price-value"><?php echo wp_kses_post($product_price) ?></span>
            </div>
        </div>
    </div>
</div>