<?php

    $cart_heading = apply_filters( 'woolentor_minicart_heading', __( 'Your Cart', 'woolentor-pro' ) );
    $empty_cart_heading = apply_filters( 'woolentor_empty_minicart_heading', __( 'Your Cart Is Empty', 'woolentor-pro' ) );
    $empty_cart_body = apply_filters( 'woolentor_empty_minicart_body_content', __( 'No products in the cart.', 'woolentor-pro' ) );

    $subtotal_txt = apply_filters( 'woolentor_minicart_subtotal', __( 'Subtotal:', 'woolentor-pro' ) );
    $viewcart_btn = apply_filters( 'woolentor_minicart_viewcart_btn', __( 'View Cart', 'woolentor-pro' ) );
    $checkout_btn = apply_filters( 'woolentor_minicart_checkout_btn', __( 'Checkout', 'woolentor-pro' ) );

    // If cart is empty change heading
    if( WC()->cart->is_empty() ){
        $cart_heading = $empty_cart_heading;
    }

?>
<div class="woolentor_mini_cart_header">
    <h2><?php esc_html_e( $cart_heading, 'woolentor-pro' );?></h2>
    <span class="woolentor_mini_cart_close">&#10006;</span>
</div>

<div class="woolentor_mini_cart_content">
    <?php if( WC()->cart->is_empty() ): ?>
        <p class="woolentor_empty_cart_body"><?php esc_html_e( $empty_cart_body, 'woolentor-pro' ); ?></p>
    <?php else:?>
        <ul>
        <?php
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                $product_name =  apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_title() ), $cart_item, $cart_item_key );
                
                $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

                $product_subtotal = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
        ?>
            <li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?> woolentor_mini_cart_product-wrap">

                <?php if ( ! $_product->is_visible() ) : ?>
                <div class="woolentor_mini_cart_img">
                    <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
                    <?php
                        echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                            '<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&#10005;</a>',
                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                            __( 'Remove this item', 'woolentor-pro' ),
                            esc_attr( $product_id ),
                            esc_attr( $cart_item_key ),
                            esc_attr( $_product->get_sku() )
                        ), $cart_item_key );
                    ?>
                </div>
                <?php else : ?>
                    <div class="woolentor_mini_cart_img">
                        <a href="<?php echo esc_url( $product_permalink ); ?>">
                            <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
                        </a>
                        <?php
                            echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                '<a href="%s" class="woolentor_del remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&#10005;</a>',
                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                __( 'Remove this item', 'woolentor-pro' ),
                                esc_attr( $product_id ),
                                esc_attr( $cart_item_key ),
                                esc_attr( $_product->get_sku() )
                            ), $cart_item_key );
                        ?>
                    </div>
                <?php endif; ?>

                <div class="woolentor_cart_single_content">
                    <div class="woolentor_mini_title">
                        <h3><?php echo $product_name;?></h3>
                        <span><?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?></span>
                    </div>
                </div>
                
            </li>
            <?php } } ?>

        </ul>
    <?php endif;?>
</div>

<div class="woolentor_mini_cart_footer">
    <?php if( !WC()->cart->is_empty() ):?>
        <span class="woolentor_sub_total">
            <span><?php esc_html_e( $subtotal_txt, 'woolentor-pro' ); ?></span>
            <?php echo WC()->cart->get_cart_subtotal(); ?>
        </span>
    <?php endif; ?>
    <div class="woolentor_button_area">
        <a href="<?php echo wc_get_cart_url(); ?>" class="button btn woolentor_cart">
            <?php esc_html_e( $viewcart_btn, 'woolentor-pro' ); ?>
        </a>
        <a  href="<?php echo wc_get_checkout_url(); ?>" class="button btn woolentor_checkout">
            <?php esc_html_e( $checkout_btn, 'woolentor-pro' ); ?>
        </a>
    </div>
</div>