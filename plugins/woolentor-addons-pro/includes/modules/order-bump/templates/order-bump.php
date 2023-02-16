<?php
/**
 * The Template for displaying order bump product.
 *
 * This template can be overridden by copying it to yourtheme/wl-woo-templates/order-bump/order-bump.php
 *
 * It is called in the following places:
 * render_order_bump() in includes/modules/order-bump/class-frontend.php
 * 
 * Passed in $args:
 * $args['order_bump_id']
 * $args['product']
 */

$meta_data              = get_post_meta( $order_bump_id, '_woolentor_order_bump', true );
$offer_product_id       = !empty( $meta_data['product'] ) ? $meta_data['product'] : 0;
$style                  = !empty( $meta_data['style'] ) ? $meta_data['style'] : 4;
$product_title          = !empty( $meta_data['product_title'] ) ? $meta_data['product_title'] : $product->get_title();
$product_desc           = !empty( $meta_data['product_desc'] ) ? $meta_data['product_desc'] : $product->get_short_description();
$label_grab_this_offer  = !empty( $meta_data['label_grab_this_offer'] ) ? $meta_data['label_grab_this_offer'] : __('Grab this offer!', 'woolentor-pro');

$checked_product_arr = Woolentor\Modules\Order_Bump\Helper::find_product_in_cart( $offer_product_id );
$cart_item_key       = $checked_product_arr['cart_item_key'];

// Checked
$checked = $cart_item_key ? '1' : '';

$product_classes    = Woolentor\Modules\Order_Bump\Helper::wc_get_product_class( '', $product);
$order_bump_classes =  Woolentor\Modules\Order_Bump\Helper::get_order_bump_class( '', $order_bump_id, );
$classes            = array_merge( $product_classes, $order_bump_classes );
?>
<div class="<?php echo esc_attr(implode(' ', $classes)) ?>" data-order_bump_id="<?php echo esc_attr($order_bump_id) ?>">
    <div class="woolentor-order-bump-inner">

        <div class="woolentor-order-bump-info">
            <?php if( $product->get_image_id() ): ?>
            <div class="wl-image">
                <?php echo wp_kses_post( $product->get_image() ); ?>
            </div>
            <?php endif; ?>

            <div class="woolentor-order-bump-content">
                <?php if( $product_title ): ?>
                    <h3 class="wl-title"><?php echo esc_html( $product_title ); ?></h3>
                <?php endif; ?>

                <?php if( $style != '5' ): ?>
                <div class="wl-price">
                    <?php if ( $price_html = $product->get_price_html() ) : ?>
                        <span class="price"><?php echo wp_kses_post($price_html); ?></span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if( $product_desc ): ?>
                    <div class="wl-desc"><p><?php echo wp_kses_post( $product_desc ); ?></p></div>
                 <?php endif; ?>

                 <?php
                    if( $style == '6' ){
                        wc_get_template( 
                            'order-bump-action.php',
                            array(
                                'order_bump_id' => $order_bump_id,
                                'product'       => $product,
                                'cart_item_key' => $cart_item_key,
                                'checked'       => $checked,
                                'style'         => $style,
                                'label_grab_this_offer' => $label_grab_this_offer,
                            ),
                            'wl-woo-templates/order-bump',
                            Woolentor\Modules\Order_Bump\MODULE_PATH. '/templates/'
                        );
                    }
                ?>
            </div>
        </div>

        <?php
            if( $style != '6' ){
                wc_get_template( 
                    'order-bump-action.php',
                    array(
                        'order_bump_id' => $order_bump_id,
                        'product'       => $product,
                        'cart_item_key' => $cart_item_key,
                        'checked'       => $checked,
                        'style'         => $style,
                        'label_grab_this_offer' => $label_grab_this_offer,
                    ),
                    'wl-woo-templates/order-bump',
                    Woolentor\Modules\Order_Bump\MODULE_PATH. '/templates/'
                );
            }
        ?>
    </div>
</div> <!-- .woolentor-order-bump -->